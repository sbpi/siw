CREATE OR REPLACE FUNCTION siw.sp_getcountrydata(p_sq_pais numeric)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera os dados do país
   open p_result for
      select * from siw.co_pais where sq_pais = p_sq_pais;
      return refcursor;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.sp_getcountrydata(numeric) OWNER TO siw;
