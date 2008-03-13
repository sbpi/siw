create or replace procedure SP_PutCLDados
   (p_restricao             in varchar2,
    p_chave                 in number,
    p_sq_lcmodalidade       in number   default null,    
    p_numero_processo       in varchar2 default null,
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
    p_dias_item             in number   default null
   ) is
begin
   If p_restricao = 'DADOS' Then
      -- Atualiza a tabela da licitação com os dados da análise
      Update cl_solicitacao set
         sq_lcmodalidade          = p_sq_lcmodalidade,
         processo                 = p_numero_processo,
         numero_certame           = p_numero_certame,
         numero_ata               = p_numero_ata,
         tipo_reajuste            = p_tipo_reajuste,
         indice_base              = p_indice_base,
         sq_eoindicador           = p_sq_eoindicador,
         limite_variacao          = p_limite_variacao,
         sq_lcfonte_recurso       = p_sq_lcfonte_recurso,
         sq_especificacao_despesa = p_sq_espec_despesa,
         sq_lcjulgamento          = p_sq_lcjulgamento,
         sq_lcsituacao            = p_sq_lcsituacao,
         financeiro_unico         = p_financeiro_unico,
         dias_validade_proposta   = p_dias
      Where sq_siw_solicitacao = p_chave;
   ElsIf p_restricao = 'CONCLUSAO' Then
      -- Atualiza a tabela da licitação com os dados da conclusão
      Update cl_solicitacao set
         data_homologacao         = p_data_homologacao,
         data_diario_oficial      = p_data_diario_oficial,
         pagina_diario_oficial    = p_pagina_diario_oficial
      Where sq_siw_solicitacao = p_chave;
   ElsIf p_restricao = 'SITUACAO' Then
      -- Atualiza a situação da licitação
      Update cl_solicitacao set
         sq_lcsituacao            = p_sq_lcsituacao
      Where sq_siw_solicitacao = p_chave;
   ElsIf p_restricao = 'ORDENACAO' Then
      -- Atualiza a ordem dos itens de uma licitação
      Update cl_solicitacao_item set
         ordem                  = p_ordem,
         dias_validade_proposta = nvl(p_dias_item,dias_validade_proposta)
      Where sq_solicitacao_item = p_chave;
   End If;
end SP_PutCLDados;
/
