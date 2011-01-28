create or replace FUNCTION SP_GetEtapaMensal
   (p_chave     numeric,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   open p_result for
      select a.referencia, a.execucao_fisica, a.execucao_financeira,
             to_char(a.referencia, 'DD/MM/YYYY, HH24:MI:SS') phpdt_referencia
        from pj_etapa_mensal a 
       where a.sq_projeto_etapa = p_chave;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;