create or replace procedure SP_PutCLDados
   (p_restricao             in varchar2,
    p_chave                 in number,
    p_sq_lcmodalidade       in number   default null,    
    p_numero_processo       in varchar2 default null,
    p_abertura              in date     default null,
    p_envelope_1            in date     default null,
    p_envelope_2            in date     default null,
    p_envelope_3            in date     default null,
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
    p_prioridade            in number   default null,
    p_nota_conclusao        in varchar2 default null,
    p_fundo_fixo            in varchar2 default null,
    p_quantidade            in number   default null,
    p_detalhamento          in varchar2 default null
   ) is
   w_numero_certame cl_solicitacao.numero_certame%type;
   w_sq_modalidade  number(18);
   w_prefixo        siw_menu.prefixo%type;
   w_codigo         siw_solicitacao.codigo_interno%type;
   w_sigla_menu     siw_menu.sigla%type;
begin
   If p_restricao = 'PROT' Then
      -- Recupera a modalidade atual
      select a.sq_lcmodalidade into w_sq_modalidade from cl_solicitacao a where sq_siw_solicitacao = p_chave;
      
      -- Recupera a sigla do serviço da solicitação
      select a.sigla into w_sigla_menu
        from siw_menu                   a
             inner join siw_solicitacao b on (a.sq_menu = b.sq_menu)
       where b.sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela da licitação com os dados da análise
      Update cl_solicitacao set
         sq_lcmodalidade = p_sq_lcmodalidade,
         processo        = coalesce(p_numero_processo,p_protocolo)
      Where sq_siw_solicitacao = p_chave;
      
      If substr(w_sigla_menu,1,4) = 'CLLC' and (w_sq_modalidade is null or (w_sq_modalidade is not null and w_sq_modalidade <> p_sq_lcmodalidade)) Then
        -- Recupera o número do certame
        CL_CriaParametro(p_chave, w_numero_certame);

        -- Atualiza a tabela da licitação com os dados da análise
        Update cl_solicitacao set numero_certame  = w_numero_certame Where sq_siw_solicitacao = p_chave;
      End If;
      
      If p_protocolo is not null Then
         -- Grava a chave do protocolo na solicitação
         update siw_solicitacao a
           set a.protocolo_siw = (select sq_siw_solicitacao from pa_documento where p_protocolo = prefixo||'.'||substr(1000000+numero_documento,2,6)||'/'||ano||'-'||substr(100+digito,2,2))
         where sq_siw_solicitacao = p_chave;
      End If;
   ElsIf p_restricao = 'DADOS' Then
      -- Recupera o prefixo do serviço, o código da solicitação e a modalidade
      select b.prefixo, a.codigo_interno, c.sq_lcmodalidade, c.numero_certame 
        into w_prefixo, w_codigo,         w_sq_modalidade,   w_numero_certame
        from siw_solicitacao           a
             inner join siw_menu       b on (a.sq_menu            = b.sq_menu)
             inner join cl_solicitacao c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
       where a.sq_siw_solicitacao = p_chave;

      -- Atualiza a tabela da licitação com os dados da análise
      Update cl_solicitacao set
         sq_lcmodalidade          = p_sq_lcmodalidade,
         processo                 = coalesce(p_numero_processo,p_protocolo),
         data_abertura            = p_abertura,
         envelope_1               = p_envelope_1,
         envelope_2               = p_envelope_2,
         envelope_3               = p_envelope_3,
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

      -- Grava os dados da solicitação
      update siw_solicitacao a
        set a.fim = p_fim
      where sq_siw_solicitacao = p_chave;

      If w_sq_modalidade is null or 
         w_numero_certame is null or 
         (w_sq_modalidade is not null and w_sq_modalidade <> p_sq_lcmodalidade)
      Then
         -- Recupera o número do certame
         CL_CriaParametro(p_chave, w_numero_certame);

        -- Atualiza a tabela da licitação com os dados da análise
        Update cl_solicitacao set numero_certame  = w_numero_certame Where sq_siw_solicitacao = p_chave;
      End If;

      If p_protocolo is not null Then
         -- Grava a chave do protocolo na solicitação
         update siw_solicitacao a
           set a.protocolo_siw = (select sq_siw_solicitacao from pa_documento where p_protocolo = prefixo||'.'||substr(1000000+numero_documento,2,6)||'/'||ano||'-'||substr(100+digito,2,2))
         where sq_siw_solicitacao = p_chave;
      End If;
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
         sq_lcsituacao            = p_sq_lcsituacao,
         data_abertura            = p_abertura,
         envelope_1               = p_envelope_1,
         envelope_2               = p_envelope_2,
         envelope_3               = p_envelope_3,
         prioridade               = p_prioridade
      Where sq_siw_solicitacao = p_chave;

      -- Grava os dados da solicitação
      update siw_solicitacao a
        set a.fim = p_fim
      where sq_siw_solicitacao = p_chave;
   ElsIf p_restricao = 'ORDENACAO' Then
      -- Atualiza a ordem dos itens de uma licitação
      Update cl_solicitacao_item set
         ordem                  = p_ordem,
         quantidade             = coalesce(p_quantidade,quantidade),
         dias_validade_proposta = coalesce(p_dias_item,dias_validade_proposta),
         detalhamento           = coalesce(p_detalhamento,detalhamento)
      Where sq_solicitacao_item = p_chave;
   ElsIf p_restricao = 'VENCEDOR' Then
      -- Registra os vencedores da licitação
      Update cl_item_fornecedor set
         vencedor = 'S'
      Where sq_item_fornecedor = p_chave;
   ElsIf p_restricao = 'AUTORIZ' Then
      update cl_solicitacao
         set fundo_fixo           = p_fundo_fixo,
             nota_conclusao       = p_nota_conclusao
      where sq_siw_solicitacao = p_chave;
   End If;
end SP_PutCLDados;
/
