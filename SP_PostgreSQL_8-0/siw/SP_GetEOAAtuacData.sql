CREATE OR REPLACE FUNCTION siw.SP_GetEOAAtuacData
   (p_chave       numeric)

  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   --Recupera a lista de áreas de atuação
   open p_result for
      select nome, ativo, sq_area_atuacao
        from siw.eo_area_atuacao
       where sq_area_atuacao = p_chave;
       return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetEOAAtuacData
   (p_chave       numeric)
 OWNER TO siw;
