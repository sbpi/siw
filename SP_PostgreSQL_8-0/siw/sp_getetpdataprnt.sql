create or replace FUNCTION SP_GetEtpDataPrnt
   (p_chave      numeric,
    p_result    REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os dados do link pai do que foi informado
   open p_result for 
      select a.sq_etapa_pai, b.*
        from pj_projeto_etapa a, pj_projeto_etapa b
       where a.sq_projeto_etapa = b.sq_etapa_pai
         and a.sq_projeto_etapa = p_chave;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;