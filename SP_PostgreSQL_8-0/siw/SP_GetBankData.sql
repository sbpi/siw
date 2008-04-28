create or replace function siw.SP_GetBankData
   (p_chave      numeric)

RETURNS character varying AS
$BODY$declare
    p_result     refcursor;
  
begin
   -- Recupera os dados do banco informado
   open p_result for 
      select * from siw.co_banco where sq_banco = p_chave;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetBankData(p_chave numeric) OWNER TO siw;
