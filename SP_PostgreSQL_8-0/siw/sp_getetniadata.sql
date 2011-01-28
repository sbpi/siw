create or replace FUNCTION SP_GetEtniaData
   (p_sq_etnia    numeric,
    p_result     REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os dados da etnia informada
   open p_result for 
      select * from co_etnia where sq_etnia = p_sq_etnia;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;