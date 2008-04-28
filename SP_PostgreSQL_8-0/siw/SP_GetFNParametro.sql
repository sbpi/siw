CREATE OR REPLACE FUNCTION siw.SP_GetFNParametro
   (p_cliente   numeric,
    p_chave_aux numeric,
    p_restricao varchar)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   If p_restricao is null Then
      -- Recupera os parametros do modulo de recursos humanos de um cliente
      open p_result for 
         select a.sequencial, a.ano_corrente, a.prefixo, a.sufixo
           from siw.fn_parametro  a
          where a.cliente = p_cliente;
   End If;
   return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetFNParametro
   (p_cliente   numeric,
    p_chave_aux numeric,
    p_restricao varchar) OWNER TO siw;
