create or replace function siw.SP_GetBankHousData
   (p_sq_agencia numeric)
  RETURNS character varying AS
$BODY$declare

    p_result     refcursor;
   
begin
   -- Recupera os dados da agência bancária
   open p_result for
      select * from siw.co_agencia where sq_agencia = p_sq_agencia;
end
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetBankHousData(p_sq_agencia numeric) OWNER TO siw;
