create or replace FUNCTION SP_PutPDUnidade
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_ativo                     varchar
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pd_unidade (sq_unidade, ativo) values (p_chave, p_ativo);
   Elsif p_operacao = 'A' Then
      update pd_unidade set ativo = p_ativo where sq_unidade = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM pd_unidade where sq_unidade = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;