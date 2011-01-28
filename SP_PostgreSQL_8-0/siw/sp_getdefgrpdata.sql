create or replace FUNCTION SP_GetDefGrpData
   (p_sq_grupo_deficiencia  numeric,
    p_result     REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os dados do grupo de deficiência
   open p_result for 
      select * from co_grupo_defic where sq_grupo_defic = p_sq_grupo_deficiencia;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;