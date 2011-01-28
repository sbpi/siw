create or replace FUNCTION SP_PutRestricaoEtapaInter
   (p_operacao                 varchar,
    p_chave                    numeric,
    p_sq_projeto_etapa         numeric
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into siw_etapa_interessado 
          (sq_unidade, sq_projeto_etapa) 
       values
          (p_chave,                    p_sq_projeto_etapa);
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM siw_etapa_interessado 
       where (p_chave              is null or (p_chave                is not null and sq_unidade  = p_chave))
         and (p_sq_projeto_etapa   is null or (p_sq_projeto_etapa     is not null and sq_projeto_etapa  = p_sq_projeto_etapa));
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;