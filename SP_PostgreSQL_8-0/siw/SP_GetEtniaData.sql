CREATE OR REPLACE FUNCTION siw.SP_GetEtniaData
   (p_sq_etnia   numeric)
     RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;

begin
   -- Recupera os dados da etnia informada
   open p_result for
      select * from siw.co_etnia where sq_etnia = p_sq_etnia;
      return p_resutl;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetEtniaData
   (p_sq_etnia   numeric) OWNER TO siw;
