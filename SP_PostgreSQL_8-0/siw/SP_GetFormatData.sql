CREATE OR REPLACE FUNCTION siw.SP_GetFormatData
   (p_sq_formacao numeric)
   
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;

begin
   -- Recupera os dados da Formação
   open p_result for
      select * from siw.co_formacao where sq_formacao = p_sq_formacao;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetFormatData
   (p_sq_formacao numeric)
    OWNER TO siw;
