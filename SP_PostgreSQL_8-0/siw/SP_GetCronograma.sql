CREATE OR REPLACE FUNCTION siw.SP_GetCronograma
   (p_chave             numeric,
    p_chave_aux         numeric,
    p_inicio            date,
    p_fim               date)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera o cronograma da rubrica
   open p_result for 
         select a.sq_rubrica_cronograma, a.inicio, a.fim, a.valor_previsto, a.valor_real
           from siw.pj_rubrica_cronograma a
      where a.sq_projeto_rubrica = p_chave      
        and ((p_chave_aux is null) or (p_chave_aux  is not null and a.sq_rubrica_cronograma = p_chave_aux))
        and ((p_inicio    is null) or (p_inicio       is not null and ((a.inicio  between p_inicio and p_fim) or
                                                                       (a.fim     between p_inicio and p_fim) or
                                                                       (p_inicio  between a.inicio and a.fim) or
                                                                       (p_fim     between a.inicio and a.fim)
                                                                       )
                                       )
            );
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetCronograma
   (p_chave             numeric,
    p_chave_aux         numeric,
    p_inicio            date,
    p_fim               date) OWNER TO siw;
