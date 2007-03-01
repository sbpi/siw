create or replace procedure SP_GetParametro
   (p_cliente   in number,
    p_modulo    in varchar2 default null,
    p_restricao in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      If p_modulo = 'PD' Then
         -- Recupera os parametros do modulo de passagens e diárias
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
      Elsif  p_modulo = 'PA' Then
         -- Recupera os parametros do modulo de protocolo e arquivo
         open p_result for
            select a.cliente, a.despacho_arqcentral, a.despacho_emprestimo, a.despacho_devolucao,
                   a.despacho_autuar, a.despacho_arqsetorial, a.despacho_anexar, a.despacho_apensar,
                   a.despacho_eliminar, a.arquivo_central, a.limite_interessados, a.ano_corrente
              from pa_parametro  a
             where a.cliente = p_cliente;             
      End If;
   End If;
end SP_GetParametro;
/
