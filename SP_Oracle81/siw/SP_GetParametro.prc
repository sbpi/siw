create or replace procedure SP_GetParametro
   (p_cliente   in number,
    p_modulo    in varchar2 default null,
    p_restricao in varchar2 default null,
    p_result    out siw.sys_refcursor) is
begin
   If p_restricao is null Then
      If p_modulo = 'PD' Then
         -- Recupera os parametros do modulo de passagens e di�rias
         open p_result for
            select a.sequencial, a.ano_corrente, a.prefixo, a.sufixo, a.dias_antecedencia,
                   a.dias_prestacao_contas, a.limite_unidade
              from pd_parametro  a
             where a.cliente = p_cliente;
      Elsif  p_modulo = 'AC' Then
         -- Recupera os parametros do modulo de contratos
         open p_result for
            select a.sequencial, a.ano_corrente, a.prefixo, a.sufixo, a.numeracao_automatica
              from ac_parametro  a
             where a.cliente = p_cliente;      
      End If;
   End If;
end SP_GetParametro;
/
