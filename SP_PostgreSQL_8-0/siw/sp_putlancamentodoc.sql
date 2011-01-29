create or replace FUNCTION SP_PutLancamentoDoc
   (p_operacao            varchar,
    p_chave               numeric,
    p_chave_aux           numeric,
    p_sq_tipo_documento   numeric,
    p_numero              varchar,
    p_data                date,
    p_serie               varchar,
    p_valor               numeric,
    p_patrimonio          varchar,
    p_retencao            varchar,
    p_tributo             varchar,
    p_nota                numeric,
    p_inicial             numeric,
    p_excedente           numeric,
    p_reajuste            numeric,
    p_chave_nova                  numeric
   ) RETURNS VOID AS $$
DECLARE
   
   w_cont       numeric(4) := 1;
   w_reg        ac_acordo%rowtype;
   w_chave_aux  numeric(18) := Nvl(p_chave_aux,0);
   w_valor      numeric(18,2) := p_valor;
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select nextVal('sq_lancamento_doc') into w_chave_aux;   
      insert into fn_lancamento_doc
        (sq_lancamento_doc,         sq_siw_solicitacao, sq_tipo_documento,   numero,           data, 
         serie,                     valor,              patrimonio,          calcula_retencao, calcula_tributo,
         sq_acordo_nota,            valor_inicial,      valor_excedente,    valor_reajuste
        )
      values
        (w_chave_aux,               p_chave,            p_sq_tipo_documento, p_numero,         p_data, 
         p_serie,                   w_valor,            p_patrimonio,        p_retencao,       p_tributo,
         p_nota,                    p_inicial,          p_excedente,         p_reajuste
        );
   Elsif p_operacao = 'A' Then -- Alteração
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
   Elsif p_operacao = 'E' Then -- Exclusão
      DELETE FROM fn_documento_item where sq_lancamento_doc = p_chave_aux;
      DELETE FROM fn_lancamento_rubrica where sq_lancamento_doc = p_chave_aux;
      DELETE FROM fn_lancamento_doc where sq_lancamento_doc = p_chave_aux;
   End If;
      
   If p_operacao = 'V' Then
      update siw_solicitacao set 
         valor = (select sum(coalesce(valor_inicial,0)) + sum(coalesce(valor_excedente,0)) + sum(coalesce(valor_reajuste,0))
                    from fn_lancamento_doc
                   where sq_siw_solicitacao = p_chave
                     and sq_acordo_nota     is not null
                 )
      where sq_siw_solicitacao = p_chave;
   Else
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
   p_chave_nova := w_chave_aux;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;