CREATE OR REPLACE FUNCTION siw.SP_GetACParametro
   (p_cliente   numeric,
    p_chave_aux numeric,
    p_restricao varchar)RETURNS character varying AS
$BODY$declare

    p_result    refcursor;
begin
   If p_restricao is null Then
      -- Recupera os parametros do modulo de recursos humanos de um cliente
      open p_result for 
         select a.sequencial, a.ano_corrente, a.prefixo, a.sufixo, a.numeracao_automatica
           from siw.ac_parametro  a
          where a.cliente = p_cliente;
   End If;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetACParametro(p_cliente numeric,p_chave_aux numeric, p_restricao varchar) OWNER TO siw;


