create or replace FUNCTION SP_GetFoneTypeData
   (p_sq_tipo_telefone  numeric,
    p_result           REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os dados do tipo da telefone
   open p_result for 
      select * from co_tipo_telefone where sq_tipo_telefone = p_sq_tipo_telefone;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;