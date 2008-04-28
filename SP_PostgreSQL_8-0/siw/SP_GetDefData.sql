CREATE OR REPLACE FUNCTION siw.SP_GetDefData
   (p_sq_deficiencia numeric)

  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera os dados da deficiência
   open p_result for
      select * from siw.co_deficiencia where sq_deficiencia = p_sq_deficiencia;

      return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetDefData
   (p_sq_deficiencia numeric)
 OWNER TO siw;
