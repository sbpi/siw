create or replace FUNCTION SP_GetFNParametro
   (p_cliente   numeric,
    p_chave_aux numeric,
    p_restricao varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
      -- Recupera os parametros do modulo de recursos humanos de um cliente
      open p_result for 
         select a.sequencial, a.ano_corrente, a.prefixo, a.sufixo, a.texto_devolucao, a.fundo_fixo_valor, a.fundo_fixo_qtd,
                a.fundo_fixo_dias_utilizacao, a.fundo_fixo_dias_contas, a.fundo_fixo_data_contas
           from fn_parametro  a
          where a.cliente = p_cliente;
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;