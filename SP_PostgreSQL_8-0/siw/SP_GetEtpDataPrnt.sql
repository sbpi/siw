CREATE OR REPLACE FUNCTION siw.SP_GetEtpDataPrnt
   (p_chave    numeric)
     RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;

begin
   -- Recupera os dados do link pai do que foi informado
   open p_result for
      select a.sq_etapa_pai, b.*
        from siw.pj_projeto_etapa a, siw.pj_projeto_etapa b
       where a.sq_projeto_etapa = b.sq_etapa_pai
         and a.sq_projeto_etapa = p_chave;
         return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetEtpDataPrnt
   (p_chave    numeric) OWNER TO siw;
