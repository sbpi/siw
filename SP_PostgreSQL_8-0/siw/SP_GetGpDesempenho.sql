create or replace FUNCTION SP_GetGpDesempenho
   (p_chave      numeric,
    p_ano        numeric,
    p_result     REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
      -- Recupera os dados do desempenho do colaborador
      open p_result for 
      select t.sq_contrato_colaborador as chave, 
             t.ano, 
             t.percentual 
        from gp_desempenho t
       where t.sq_contrato_colaborador = p_chave
         and ((p_ano           is null) or (p_ano is not null and t.ano = p_ano));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;