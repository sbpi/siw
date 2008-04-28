CREATE OR REPLACE FUNCTION siw.SP_GetGPParametro
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
         select a.sq_unidade_gestao, a.admissao_texto, a.admissao_destino, a.rescisao_texto,
                a.rescisao_destino, a.feriado_legenda, a.feriado_nome, a.ferias_legenda, a.ferias_nome,
                a.viagem_legenda, a.viagem_nome
           from siw.gp_parametro  a
          where a.cliente = p_cliente;
   End If;
   return p_result;
end
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetGPParametro
   (p_cliente   numeric,
    p_chave_aux numeric,
    p_restricao varchar) OWNER TO siw;
