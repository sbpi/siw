create or replace procedure SP_GetParametro
   (p_cliente   in number,
    p_modulo    in varchar2 default null,
    p_restricao in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      If p_modulo = 'PD' Then
         -- Recupera os parametros do modulo de passagens e di�rias
         open p_result for
            select a.sequencial, a.ano_corrente, a.prefixo, a.sufixo, a.dias_antecedencia,
                   a.dias_prestacao_contas, a.limite_unidade, a.cadastrador_geral
              from pd_parametro  a
             where a.cliente = p_cliente;
      Elsif  p_modulo = 'AC' Then
         -- Recupera os parametros do modulo de contratos
         open p_result for
            select a.sequencial, a.ano_corrente, a.prefixo, a.sufixo, a.numeracao_automatica, a.dias_pagamento, a.texto_pagamento
              from ac_parametro  a
             where a.cliente = p_cliente;
      Elsif  p_modulo = 'PA' Then
         -- Recupera os parametros do modulo de protocolo e arquivo
         open p_result for
            select a.cliente, a.despacho_arqcentral, a.despacho_desarqcentral, a.despacho_emprestimo, a.despacho_devolucao,
                   a.despacho_autuar, a.despacho_arqsetorial, a.despacho_anexar, a.despacho_apensar, a.despacho_eliminar, 
                   a.despacho_desmembrar, a.arquivo_central, a.limite_interessados, a.ano_corrente, a.envio_externo, 
                   a.emite_guia_remessa
              from pa_parametro  a
             where a.cliente = p_cliente; 
      Elsif  p_modulo = 'CL' Then
         -- Recupera os parametros do modulo de contratos
         open p_result for
            select a.cliente, a.ano_corrente,a.dias_validade_pesquisa, a.dias_aviso_pesquisa, 
                   a.percentual_acrescimo, a.compra_central, a.pesquisa_central, a.contrato_central, 
                   a.banco_ata_central, a.banco_preco_central, a.codificacao_central,
                   a.pede_valor_pedido, a.codificacao_automatica, a.prefixo, a.sequencial, a.sufixo, 
                   a.cadastrador_geral
              from cl_parametro a
             where a.cliente = p_cliente;                         
      End If;
   End If;
end SP_GetParametro;
/
