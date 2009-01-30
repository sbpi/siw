create or replace procedure SP_PutCLDados
   (p_restricao             in varchar2,
    p_chave                 in number,
    p_sq_lcmodalidade       in number   default null,    
    p_numero_processo       in varchar2 default null,
    p_abertura              in date     default null,
    p_numero_certame        in varchar2 default null,
    p_numero_ata            in varchar2 default null,
    p_tipo_reajuste         in number   default null,
    p_indice_base           in varchar2 default null,
    p_sq_eoindicador        in number   default null,
    p_limite_variacao       in number   default null,
    p_sq_lcfonte_recurso    in number   default null,
    p_sq_espec_despesa      in number   default null,
    p_sq_lcjulgamento       in number   default null,
    p_sq_lcsituacao         in number   default null,
    p_financeiro_unico      in varchar2 default null,
    p_data_homologacao      in date     default null,
    p_data_diario_oficial   in date     default null,
    p_pagina_diario_oficial in number   default null,
    p_ordem                 in varchar2 default null,
    p_dias                  in number   default null,
    p_dias_item             in number   default null,
    p_protocolo             in varchar2 default null,
    p_fim                   in date     default null,
    p_prioridade            in number   default null
   ) is
begin
   If p_restricao = 'PROT' Then
      -- Atualiza a tabela da licita��o com os dados da an�lise
      Update cl_solicitacao set
         sq_lcmodalidade          = p_sq_lcmodalidade,
         processo                 = coalesce(p_numero_processo,p_protocolo),
         numero_certame           = p_numero_certame
      Where sq_siw_solicitacao = p_chave;
      
      If p_protocolo is not null Then
         -- Grava a chave do protocolo na solicita��o
         update siw_solicitacao a
           set a.protocolo_siw = (select sq_siw_solicitacao from pa_documento where p_protocolo = prefixo||'.'||substr(1000000+numero_documento,2,6)||'/'||ano||'-'||substr(100+digito,2,2))
         where sq_siw_solicitacao = p_chave;
      End If;
   ElsIf p_restricao = 'DADOS' Then
      -- Atualiza a tabela da licita��o com os dados da an�lise
      Update cl_solicitacao set
         sq_lcmodalidade          = p_sq_lcmodalidade,
         processo                 = coalesce(p_numero_processo,p_protocolo),
         data_abertura            = p_abertura,
         numero_certame           = p_numero_certame,
         numero_ata               = p_numero_ata,
         tipo_reajuste            = case when p_tipo_reajuste is not null then p_tipo_reajuste else tipo_reajuste end,
         indice_base              = p_indice_base,
         sq_eoindicador           = p_sq_eoindicador,
         limite_variacao          = case when p_limite_variacao is not null then p_limite_variacao else limite_variacao end,
         sq_lcfonte_recurso       = p_sq_lcfonte_recurso,
         sq_especificacao_despesa = p_sq_espec_despesa,
         sq_lcjulgamento          = p_sq_lcjulgamento,
         sq_lcsituacao            = p_sq_lcsituacao,
         financeiro_unico         = p_financeiro_unico,
         dias_validade_proposta   = p_dias,
         prioridade               = p_prioridade
      Where sq_siw_solicitacao = p_chave;

      -- Grava os dados da solicita��o
      update siw_solicitacao a
        set a.fim = p_fim
      where sq_siw_solicitacao = p_chave;

      If p_protocolo is not null Then
         -- Grava a chave do protocolo na solicita��o
         update siw_solicitacao a
           set a.protocolo_siw = (select sq_siw_solicitacao from pa_documento where p_protocolo = prefixo||'.'||substr(1000000+numero_documento,2,6)||'/'||ano||'-'||substr(100+digito,2,2))
         where sq_siw_solicitacao = p_chave;
      End If;
   ElsIf p_restricao = 'CONCLUSAO' Then
      -- Atualiza a tabela da licita��o com os dados da conclus�o
      Update cl_solicitacao set
         data_homologacao         = p_data_homologacao,
         data_diario_oficial      = p_data_diario_oficial,
         pagina_diario_oficial    = p_pagina_diario_oficial
      Where sq_siw_solicitacao = p_chave;
   ElsIf p_restricao = 'SITUACAO' Then
      -- Atualiza a situa��o da licita��o
      Update cl_solicitacao set
         sq_lcsituacao            = p_sq_lcsituacao,
         data_abertura            = p_abertura,
         prioridade               = p_prioridade
      Where sq_siw_solicitacao = p_chave;

      -- Grava os dados da solicita��o
      update siw_solicitacao a
        set a.fim = p_fim
      where sq_siw_solicitacao = p_chave;
   ElsIf p_restricao = 'ORDENACAO' Then
      -- Atualiza a ordem dos itens de uma licita��o
      Update cl_solicitacao_item set
         ordem                  = p_ordem,
         dias_validade_proposta = nvl(p_dias_item,dias_validade_proposta)
      Where sq_solicitacao_item = p_chave;
   ElsIf p_restricao = 'VENCEDOR' Then
      -- Registra os vencedores da licita��o
      Update cl_item_fornecedor set
         vencedor = 'S'
      Where sq_item_fornecedor = p_chave;
   End If;
end SP_PutCLDados;
/
