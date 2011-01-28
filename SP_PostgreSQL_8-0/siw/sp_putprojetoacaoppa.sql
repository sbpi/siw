create or replace FUNCTION SP_PutProjetoAcaoPPA
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_sq_acao_ppa               numeric,
    p_observacao                varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   if p_operacao = 'I' Then
      -- Insere os registro
      insert into or_acao_financ(sq_siw_solicitacao, sq_acao_ppa, observacao) 
                  (select p_chave, p_sq_acao_ppa, p_observacao);
   Elsif p_operacao = 'A' Then
      -- Altera os resgitros
      update or_acao_financ set
        observacao  = p_observacao
      where sq_siw_solicitacao = p_chave and sq_acao_ppa = p_sq_acao_ppa;
   Elsif p_operacao = 'E' Then
      -- Apaga os registro
      DELETE FROM or_acao_financ where sq_siw_solicitacao = p_chave and sq_acao_ppa = p_sq_acao_ppa;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;