create or replace FUNCTION SP_PutPD_ReembValor
   (p_operacao             varchar,
    p_chave               numeric,
    p_chave_aux           numeric,
    p_moeda               numeric,
    p_valor_solicitado    numeric,
    p_justificativa       varchar,
    p_valor_autorizado    numeric,
    p_observacao          varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela de reembolso
      insert into pd_reembolso
        (sq_pdreembolso,         sq_siw_solicitacao, sq_moeda, valor_solicitado,   justificativa,   valor_autorizado,               observacao)
      (select 
         sq_pdreembolso.nextval, p_chave,            p_moeda,  p_valor_solicitado, p_justificativa, coalesce(p_valor_autorizado,0), p_observacao
        from dual
      );
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de reembolso
      update pd_reembolso
         set sq_moeda         = p_moeda,
             valor_solicitado = p_valor_solicitado,
             justificativa    = p_justificativa,
             valor_autorizado = coalesce(p_valor_autorizado,0),
             observacao       = p_observacao
       where sq_pdreembolso = p_chave_aux;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de reembolso
      DELETE FROM pd_reembolso where sq_pdreembolso = p_chave_aux;
   End If;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;