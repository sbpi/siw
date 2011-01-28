create or replace FUNCTION SP_GetBankHousData
   (p_sq_agencia  numeric,
    p_result     REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os dados da agência bancária
   open p_result for 
      select * from co_agencia where sq_agencia = p_sq_agencia;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;