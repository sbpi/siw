create or replace function SP_GetEtpDataPrnts
   (p_chave      NUMERIC,
    p_result     REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera as etapas acima da informada
   open p_result for 
      select montaOrdem(p_chave, null) as ordem;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;