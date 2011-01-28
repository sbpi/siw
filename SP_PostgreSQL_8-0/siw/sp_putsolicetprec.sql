create or replace FUNCTION SP_PutSolicEtpRec
   (p_operacao       varchar,
    p_chave          numeric,
    p_recurso        numeric   
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro em pj_recurso_etapa
      insert into pj_recurso_etapa (sq_projeto_etapa, sq_projeto_recurso, observacao)
         values (p_chave, p_recurso, null);
   Elsif p_operacao = 'E' Then
      -- Remove a opção de todos os endereços da organização
      DELETE FROM pj_recurso_etapa where sq_projeto_etapa = p_chave;
   End If;
   
   commit;   END; $$ LANGUAGE 'PLPGSQL' VOLATILE;