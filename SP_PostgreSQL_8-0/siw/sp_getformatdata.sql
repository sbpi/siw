create or replace FUNCTION SP_GetFormatData
   (p_sq_formacao  numeric,
    p_result      REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os dados da Formação
   open p_result for 
      select * from co_formacao where sq_formacao = p_sq_formacao;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;