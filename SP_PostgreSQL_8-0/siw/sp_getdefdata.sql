create or replace FUNCTION SP_GetDefData
   (p_sq_deficiencia  numeric,
    p_result     REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os dados da deficiência
   open p_result for 
      select * from co_deficiencia where sq_deficiencia = p_sq_deficiencia;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;