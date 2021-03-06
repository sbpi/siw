create or replace procedure SP_PutLancamentoDoc
   (p_operacao            in varchar2,
    p_chave               in number,
    p_chave_aux           in number   default null,
    p_sq_tipo_documento   in number   default null,
    p_numero              in varchar2 default null,
    p_data                in date     default null,
    p_serie               in varchar2 default null,
    p_moeda               in number   default null,
    p_valor               in number   default null,
    p_patrimonio          in varchar2 default null,
    p_retencao            in varchar2 default null,
    p_tributo             in varchar2 default null,
    p_nota                in number   default null,
    p_inicial             in number   default null,
    p_excedente           in number   default null,
    p_reajuste            in number   default null,
    p_chave_nova          out         number
   ) is
   
   w_cont       number(4) := 1;
   w_reg        ac_acordo%rowtype;
   w_chave_aux  number(18) := Nvl(p_chave_aux,0);
   w_valor      number(18,2) := p_valor;
begin
   If p_operacao = 'I' Then -- Inclus�o
      -- Recupera a pr�xima chave
      select sq_lancamento_doc.nextval into w_chave_aux from dual;   
      insert into fn_lancamento_doc
        (sq_lancamento_doc,         sq_siw_solicitacao, sq_tipo_documento,   numero,           data, 
         serie,                     valor,              patrimonio,          calcula_retencao, calcula_tributo,
         sq_acordo_nota,            valor_inicial,      valor_excedente,     valor_reajuste
        )
      values
        (w_chave_aux,               p_chave,            p_sq_tipo_documento, p_numero,         p_data, 
         p_serie,                   w_valor,            p_patrimonio,        p_retencao,       p_tributo,
         p_nota,                    p_inicial,          p_excedente,         p_reajuste
        );
   Elsif p_operacao = 'A' Then -- Altera��o
      If w_valor is null Then
         w_valor := coalesce(p_inicial,0) + coalesce(p_excedente,0) + coalesce(p_reajuste,0);
      End If;
      update fn_lancamento_doc
         set sq_tipo_documento = p_sq_tipo_documento,
             numero            = p_numero,
             data              = p_data,
             serie             = p_serie,
             valor             = w_valor,
             patrimonio        = p_patrimonio,
             calcula_retencao  = p_retencao,
             calcula_tributo   = p_tributo,
             sq_acordo_nota    = p_nota,
             valor_inicial     = p_inicial,
             valor_excedente   = p_excedente,
             valor_reajuste    = p_reajuste
       where sq_lancamento_doc = p_chave_aux;
       
       -- Se o documento tem apenas um item, iguala seu valor ao do documento
       select count(*) into w_cont from fn_documento_item where sq_lancamento_doc = p_chave_aux;
       
       If w_cont = 1 Then
          update fn_documento_item
             set valor_unitario = w_valor,
                 valor_total    = w_valor
          where sq_lancamento_doc = p_chave_aux;
       End If;
       
   Elsif p_operacao = 'E' Then -- Exclus�o
      delete fn_documento_valores where sq_lancamento_doc = p_chave_aux;
      delete fn_documento_item where sq_lancamento_doc = p_chave_aux;
      delete fn_lancamento_rubrica where sq_lancamento_doc = p_chave_aux;
      delete fn_lancamento_doc where sq_lancamento_doc = p_chave_aux;
   End If;
      
   -- Atualiza o valor da solicita��o
   update siw_solicitacao set 
      sq_moeda = p_moeda,
      valor    = (select sum(valor)
                    from (select sum(a.valor) valor
                            from fn_lancamento_doc a
                           where sq_siw_solicitacao = p_chave
                             and sq_acordo_nota     is null
                          UNION
                          select sum(coalesce(valor_inicial,0)) + sum(coalesce(valor_excedente,0)) + sum(coalesce(valor_reajuste,0)) valor
                            from fn_lancamento_doc a
                           where sq_siw_solicitacao = p_chave
                             and sq_acordo_nota     is not null
                         )
                 )
   where sq_siw_solicitacao = p_chave;
 
   If p_operacao <> 'V' Then
      -- Atualiza os valores acumulados dos impostos em FN_LANCAMENTO
      update fn_lancamento set (valor_imposto, valor_retencao, valor_liquido) = 
         (select Nvl(sum(d.valor_normal),0)                         valor_imposto, 
                 Nvl(sum(d.valor_retencao),0)                       valor_retencao, 
                 Nvl(sum(distinct(c.valor)) - sum(d.valor_total),0) valor_liquido
            from siw_solicitacao                  a
                 inner     join fn_lancamento     b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                   inner   join fn_lancamento_doc c on (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
                     inner join fn_imposto_doc    d on (c.sq_lancamento_doc  = d.sq_lancamento_doc)
           where a.sq_siw_solicitacao = p_chave
         )
      where sq_siw_solicitacao = p_chave;
   End If;
   -- Devolve a chave
   p_chave_nova := w_chave_aux;
end SP_PutLancamentoDoc;
/
