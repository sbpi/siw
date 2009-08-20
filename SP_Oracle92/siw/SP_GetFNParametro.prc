create or replace procedure SP_GetFNParametro
   (p_cliente   in number,
    p_chave_aux in number    default null,
    p_restricao in varchar2  default null,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera os parametros do modulo de recursos humanos de um cliente
      open p_result for 
         select a.sequencial, a.ano_corrente, a.prefixo, a.sufixo, a.texto_devolucao
           from fn_parametro  a
          where a.cliente = p_cliente;
   End If;
end SP_GetFNParametro;
/
