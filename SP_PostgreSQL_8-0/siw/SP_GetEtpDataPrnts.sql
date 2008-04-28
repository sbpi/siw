CREATE OR REPLACE FUNCTION siw.SP_GetEtpDataPrnts
   (p_chave   numeric)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera as etapas acima da informada
   open p_result for
      select sq_projeto_etapa, sq_etapa_pai, titulo, ordem
        from siw.pj_projeto_etapa;
    /*  start with sq_projeto_etapa   = p_chave
      connect by prior sq_etapa_pai = sq_projeto_etapa;*/
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetEtpDataPrnts
   (p_chave   numeric)OWNER TO siw;
