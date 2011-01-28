create or replace FUNCTION SP_PutCLDados
   (p_restricao             varchar,
    p_chave                 numeric,
    p_sq_lcmodalidade       numeric,    
    p_numero_processo       varchar,
    p_abertura              date,
    p_numero_certame        varchar,
    p_numero_ata            varchar,
    p_tipo_reajuste         numeric,
    p_indice_base           varchar,
    p_sq_eoindicador        numeric,
    p_limite_variacao       numeric,
    p_sq_lcfonte_recurso    numeric,
    p_sq_espec_despesa      numeric,
    p_sq_lcjulgamento       numeric,
    p_sq_lcsituacao         numeric,
    p_financeiro_unico      varchar,
    p_data_homologacao      date,
    p_data_diario_oficial   date,
    p_pagina_diario_oficial numeric,
    p_ordem                 varchar,
    p_dias                  numeric,
    p_dias_item             numeric,
    p_protocolo             varchar,
    p_fim                   date,
    p_prioridade            numeric,
    p_nota_conclusao        varchar,
    p_fundo_fixo            varchar 
   ) RETURNS VOID AS $$
DECLARE
   w_numero_certame cl_solicitacao.numero_certame%type;
   w_sq_modalidade  numeric(18);
   w_certame        varchar(1);
   w_prefixo        siw_menu.prefixo%type;
   w_codigo         siw_solicitacao.codigo_interno%type;
   w_sigla_menu     siw_menu.sigla%type;
BEGIN
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
         dias_validade_proposta = nvl(p_dias_item,dias_validade_proposta)
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
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;