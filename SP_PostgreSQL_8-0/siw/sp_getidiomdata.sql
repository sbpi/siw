create or replace FUNCTION SP_GetIdiomData
   (p_sq_idioma  numeric,
    p_result     REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os dados do Idioma
   open p_result for 
      select * from co_idioma where sq_idioma = p_sq_idioma;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;