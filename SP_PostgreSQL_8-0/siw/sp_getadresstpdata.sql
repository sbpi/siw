create or replace FUNCTION SP_GetAdressTPData
   (p_sq_tipo_endereco  numeric,
    p_result           REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os dados do tipo de endereço
   open p_result for 
      select * from co_tipo_endereco where sq_tipo_endereco = p_sq_tipo_endereco;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;