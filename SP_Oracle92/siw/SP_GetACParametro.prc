create or replace procedure SP_GetACParametro
   (p_cliente   in number,
    p_chave_aux in number    default null,
    p_restricao in varchar2  default null,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera os parametros do modulo de recursos humanos de um cliente
      open p_result for 
         select a.sequencial, a.ano_corrente, a.prefixo, a.sufixo, a.numeracao_automatica, a.dias_pagamento, a.texto_pagamento
           from ac_parametro  a
          where a.cliente = p_cliente;
   End If;
end SP_GetACParametro;
/
