CREATE OR REPLACE FUNCTION siw.SP_GetAcordoNotaCancel
   (p_chave             numeric,
    p_chave_aux         numeric,
    p_chave_aux2        numeric,
    p_dt_ini            date,
    p_dt_fim            date,
    p_restricao         varchar)

  RETURNS refcursor AS
$BODY$declare
    p_result  refcursor;
begin
   If p_restricao is null Then
      -- Recupera os cancelamentos de notas de empenho
      open p_result for 
         select a.sq_nota_cancelamento as chave_aux2, a.sq_acordo_nota, a.data, a.valor 
           from siw.ac_nota_cancelamento      a
                inner join siw.ac_acordo_nota b on (a.sq_acordo_nota = b.sq_acordo_nota)
          where ((p_chave      is null) or (p_chave      is not null and b.sq_siw_solicitacao   = p_chave))
            and ((p_chave_aux  is null) or (p_chave_aux  is not null and a.sq_acordo_nota       = p_chave_aux))
            and ((p_chave_aux2 is null) or (p_chave_aux2 is not null and a.sq_nota_cancelamento = p_chave_aux2))
            and ((p_dt_ini     is null) or (p_dt_ini     is not null and a.data between p_dt_ini and p_dt_fim));
   End If;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetAcordoNotaCancel
   (p_chave             numeric,
    p_chave_aux         numeric,
    p_chave_aux2        numeric,
    p_dt_ini            date,
    p_dt_fim            date,
    p_restricao         varchar) OWNER TO siw;
