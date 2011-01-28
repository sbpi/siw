create or replace FUNCTION SP_GetBankData
   (p_chave       numeric,
    p_result     REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os dados do banco informado
   open p_result for 
      select * from co_banco where sq_banco = p_chave;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;