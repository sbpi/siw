create or replace FUNCTION SP_GetRestricaoEtapa
   (p_chave              numeric,
    p_sq_projeto_etapa   numeric,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os tipos de dado existentes
   open p_result for 
      select a.sq_siw_restricao, a.sq_projeto_etapa
        from siw_restricao_etapa a
       where (p_chave     is null or (p_chave     is not null and a.sq_siw_restricao = p_chave))
         and (p_sq_projeto_etapa   is null or (p_sq_projeto_etapa     is not null and a.sq_projeto_etapa     = p_sq_projeto_etapa));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;