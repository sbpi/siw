CREATE OR REPLACE FUNCTION siw.SP_GetFoneTypeData
   (p_sq_tipo_telefone numeric)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera os dados do tipo da telefone
   open p_result for
      select * from siw.co_tipo_telefone where sq_tipo_telefone = p_sq_tipo_telefone;
      return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetFoneTypeData
   (p_sq_tipo_telefone numeric) OWNER TO siw;
