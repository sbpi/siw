create or replace procedure SP_PutLancamentoDoc
   (p_operacao            in varchar2,
    p_chave               in number,
    p_chave_aux           in number,
    p_sq_tipo_documento   in number   default null,
    p_numero              in varchar2 default null,
    p_data                in date     default null,
    p_serie               in varchar2 default null,
    p_valor               in number   default null,
    p_patrimonio          in varchar2 default null,
    p_retencao            in varchar2 default null,
    p_tributo             in varchar2 default null,
    p_chave_nova          out         number
   ) is
   
   w_cont       number(4) := 1;
   w_reg        ac_acordo%rowtype;
   w_chave_aux  number(18) := Nvl(p_chave_aux,0);
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_lancamento_doc.nextval into w_chave_aux from dual;   
      insert into fn_lancamento_doc
        (sq_lancamento_doc,         sq_siw_solicitacao, sq_tipo_documento,   numero,           data, 
         serie,                     valor,              patrimonio,          calcula_retencao, calcula_tributo
        )
      values
        (w_chave_aux,               p_chave,            p_sq_tipo_documento, p_numero,         p_data, 
         p_serie,                   p_valor,            p_patrimonio,        p_retencao,       p_tributo
        );
   Elsif p_operacao = 'A' Then -- Alteração
      update fn_lancamento_doc
         set sq_tipo_documento = p_sq_tipo_documento,
             numero            = p_numero,
             data              = p_data,
             serie             = p_serie,
             valor             = p_valor,
             patrimonio        = p_patrimonio,
             calcula_retencao  = p_retencao,
             calcula_tributo   = p_tributo
       where sq_lancamento_doc = p_chave_aux;
   Elsif p_operacao = 'E' Then -- Exclusão
      delete fn_documento_item where sq_lancamento_doc = p_chave_aux;
      delete fn_lancamento_rubrica where sq_lancamento_doc = p_chave_aux;
      delete fn_lancamento_doc where sq_lancamento_doc = p_chave_aux;
   End If;
      
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
   -- Devolve a chave
   p_chave_nova := w_chave_aux;
end SP_PutLancamentoDoc;
/
