create or replace FUNCTION SP_GetACParametro
   (p_cliente   numeric,
    p_chave_aux numeric,
    p_restricao varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
      -- Recupera os parametros do modulo de recursos humanos de um cliente
      open p_result for 
         select a.sequencial, a.ano_corrente, a.prefixo, a.sufixo, a.numeracao_automatica, a.dias_pagamento, a.texto_pagamento
           from ac_parametro  a
          where a.cliente = p_cliente;
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;