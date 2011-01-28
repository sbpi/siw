create or replace FUNCTION SP_PutLancamentoItem
   (p_operacao            varchar,
    p_chave               numeric,
    p_chave_aux           numeric,
    p_sq_projeto_rubrica  numeric,
    p_descricao           varchar,
    p_quantidade          numeric,
    p_valor_unitario      numeric,
    p_ordem               numeric,
    p_data_cotacao        date,
    p_valor_cotacao       numeric,
    p_solic_item          numeric   
   ) RETURNS VOID AS $$
DECLARE
   
   w_sigla siw_menu.sigla%type;
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      insert into fn_documento_item
        (sq_documento_item,           sq_lancamento_doc,  sq_projeto_rubrica,   descricao,
         quantidade,                  valor_unitario,     ordem,                data_cotacao,
         valor_cotacao,               sq_solicitacao_item
        )
      values
        (sq_documento_item.nextval,   p_chave,            p_sq_projeto_rubrica, p_descricao,
         p_quantidade,                p_valor_unitario,   p_ordem,              p_data_cotacao,
         coalesce(p_valor_cotacao,0), p_solic_item
        );
   Elsif p_operacao = 'A' Then -- Alteração
      update fn_documento_item
         set sq_projeto_rubrica  = p_sq_projeto_rubrica,
             descricao           = p_descricao,
             quantidade          = p_quantidade,
             valor_unitario      = p_valor_unitario,
             ordem               = p_ordem,
             data_cotacao        = p_data_cotacao,
             valor_cotacao       = coalesce(p_valor_cotacao,0),
             sq_solicitacao_item = p_solic_item
       where sq_documento_item = p_chave_aux;
   Elsif p_operacao = 'E' Then -- Exclusão
      DELETE FROM fn_documento_item where sq_documento_item = p_chave_aux;
   End If;
   
   -- Se for pagamento de diária, acumula valores no documento e na solicitação
   select c.sigla into w_sigla
     from fn_lancamento_doc            a
          inner   join siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
            inner join siw_menu        c on (b.sq_menu            = c.sq_menu)
    where a.sq_lancamento_doc = p_chave;
    
   If w_sigla = 'FNDVIA' or w_sigla = 'FNREVENT' Then
      -- Atualiza o documento
      update fn_lancamento_doc a
         set a.valor = coalesce((select sum(x.valor_total) from fn_documento_item x where x.sq_lancamento_doc = a.sq_lancamento_doc),0)
      where sq_lancamento_doc = p_chave;

      -- Atualiza a solicitação
      update siw_solicitacao a
         set a.valor = coalesce((select sum(x.valor) from fn_lancamento_doc x where x.sq_siw_solicitacao = a.sq_siw_solicitacao),0)
      where sq_siw_solicitacao = (select sq_siw_solicitacao from fn_lancamento_doc where sq_lancamento_doc = p_chave);
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;