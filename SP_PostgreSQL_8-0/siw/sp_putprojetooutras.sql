create or replace FUNCTION SP_PutProjetoOutras
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_sq_orprioridade           numeric 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   if p_operacao = 'I' Then
      -- Insere os registro
      insert into or_acao_prioridade(sq_siw_solicitacao, sq_orprioridade) 
                  (select p_chave, p_sq_orprioridade from dual);

   Elsif p_operacao = 'E' Then
      -- Apaga os registro
      if p_sq_orprioridade is null then
         DELETE FROM or_acao_prioridade where sq_siw_solicitacao = p_chave;
      Else
         DELETE FROM or_acao_prioridade where sq_siw_solicitacao = p_chave and sq_orprioridade = p_sq_orprioridade;
      End If;   
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;