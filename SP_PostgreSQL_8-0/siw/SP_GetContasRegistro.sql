CREATE OR REPLACE FUNCTION siw.SP_GetContasRegistro
   (p_chave             numeric,
    p_prestacao_contas  numeric,
    p_contas_cronograma numeric,
    p_restricao         varchar)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera os registros de um cronograma de prestação de contas
   If p_restricao is null Then
      open p_result for     
         select a.sq_contas_registro as chave, a.sq_contas_cronograma, a.sq_prestacao_contas, a.pendencia,
                a.observacao,
                case a.pendencia when 'S' then 'Pendência' else 'OK' end as nm_pendencia
           from siw.siw_contas_registro a
          where ((p_chave             is null) or (p_chave             is not null and a.sq_contas_registro   = p_chave))
            and ((p_prestacao_contas  is null) or (p_prestacao_contas  is not null and a.sq_prestacao_contas  = p_prestacao_contas))
            and ((p_contas_cronograma is null) or (p_contas_cronograma is not null and a.sq_contas_cronograma = p_contas_cronograma));
   End If;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetContasRegistro
   (p_chave             numeric,
    p_prestacao_contas  numeric,
    p_contas_cronograma numeric,
    p_restricao         varchar) OWNER TO siw;
