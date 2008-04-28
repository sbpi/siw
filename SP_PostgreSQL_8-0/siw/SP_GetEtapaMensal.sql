CREATE OR REPLACE FUNCTION siw.SP_GetEtapaMensal
   (p_chave     numeric)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   open p_result for
      select a.referencia, a.execucao_fisica, a.execucao_financeira,
             to_char(a.referencia, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_referencia
        from siw. pj_etapa_mensal a 
       where a.sq_projeto_etapa = p_chave;
End 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION  siw.SP_GetEtapaMensal
   (p_chave     numeric) OWNER TO siw;
