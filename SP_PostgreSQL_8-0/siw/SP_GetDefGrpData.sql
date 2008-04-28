CREATE OR REPLACE FUNCTION siw.SP_GetDefGrpData
   (p_sq_grupo_deficiencia numeric)

  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera os dados do grupo de deficiência
   open p_result for
      select * from siw.co_grupo_defic where sq_grupo_defic = p_sq_grupo_deficiencia;
      return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetDefGrpData
   (p_sq_grupo_deficiencia numeric)
 OWNER TO siw;
