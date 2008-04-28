CREATE OR REPLACE FUNCTION siw.SP_GetIdiomData
   (p_sq_idioma numeric)
   RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;

begin
   -- Recupera os dados do Idioma
   open p_result for
      select * from siw.co_idioma where sq_idioma = p_sq_idioma;
      return p_result;
end $BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetIdiomData
   (p_sq_idioma numeric) OWNER TO siw;
