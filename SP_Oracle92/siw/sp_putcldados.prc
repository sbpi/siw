create or replace procedure SP_PutCLDados
   (p_restricao             in varchar2,
    p_chave                 in number,
    p_executor              in number   default null,
    p_sq_lcmodalidade       in number   default null,    
    p_numero_processo       in varchar2 default null,
    p_abertura              in varchar2 default null,
    p_envelope_1            in varchar2 default null,
    p_envelope_2            in varchar2 default null,
    p_envelope_3            in varchar2 default null,
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
    p_inicio                in date     default null,
    p_prioridade            in number   default null,
    p_nota_conclusao        in varchar2 default null,
    p_fundo_fixo            in varchar2 default null,
    p_quantidade            in number   default null,
    p_detalhamento          in varchar2 default null,
    p_rubrica               in varchar2 default null,
    p_just_pesquisa         in varchar2 default null,
    p_just_proposta         in varchar2 default null,
    p_just_preco_maior      in varchar2 default null,
    p_arquivo_justificativa in varchar2 default null
   ) is
   w_numero_certame cl_solicitacao.numero_certame%type;
   w_sq_modalidade  number(18);
   w_prefixo        siw_menu.prefixo%type;
   w_codigo         siw_solicitacao.codigo_interno%type;
   w_sigla_menu     siw_menu.sigla%type;
   w_abertura       date := null;
   w_envelope_1     date := null;
   w_envelope_2     date := null;
   w_envelope_3     date := null;
begin
   -- Tratamento das datas
   If p_abertura is not null Then
      If length(p_abertura)=10 Then w_abertura := to_date(p_abertura,'dd/mm/yyyy'); Else w_abertura := to_date(p_abertura,'dd/mm/yyyy, hh24:mi:ss'); End If;
   End If;

   If p_envelope_1 is not null Then
      If length(p_envelope_1)=10 Then w_envelope_1 := to_date(p_envelope_1,'dd/mm/yyyy'); Else w_envelope_1 := to_date(p_envelope_1,'dd/mm/yyyy, hh24:mi:ss'); End If;
   End If;

   If p_envelope_2 is not null Then
      If length(p_envelope_2)=10 Then w_envelope_2 := to_date(p_envelope_2,'dd/mm/yyyy'); Else w_envelope_2 := to_date(p_envelope_2,'dd/mm/yyyy, hh24:mi:ss'); End If;
   End If;

   If p_envelope_3 is not null Then
      If length(p_envelope_3)=10 Then w_envelope_3 := to_date(p_envelope_3,'dd/mm/yyyy'); Else w_envelope_3 := to_date(p_envelope_3,'dd/mm/yyyy, hh24:mi:ss'); End If;
   End If;

   If p_restricao = 'JUST-PESQ-PROP' Then
     
      -- Atualiza a tabela da licita��o com as justificativas para n�o cumprimento 
      -- das quantidades m�nimas de pesquisas de pre�o e/ou propostas
      Update cl_solicitacao set
         justificativa_regra_pesquisas = coalesce(p_just_pesquisa,justificativa_regra_pesquisas),
         justificativa_regra_propostas = coalesce(p_just_proposta,justificativa_regra_propostas)
      Where sq_siw_solicitacao = p_chave;
      
   Elsif p_restricao = 'PROT' Then
      -- Recupera a modalidade atual
      select a.sq_lcmodalidade, a.numero_certame into w_sq_modalidade, w_numero_certame from cl_solicitacao a where sq_siw_solicitacao = p_chave;
      
      -- Recupera a sigla do servi�o da solicita��o
      select a.sigla into w_sigla_menu
        from siw_menu                   a
             inner join siw_solicitacao b on (a.sq_menu = b.sq_menu)
       where b.sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela da licita��o com os dados da an�lise
      Update cl_solicitacao set
         sq_lcmodalidade = p_sq_lcmodalidade,
         processo        = coalesce(p_numero_processo,p_protocolo)
      Where sq_siw_solicitacao = p_chave;
      
      If substr(w_sigla_menu,1,4) = 'CLLC' and (coalesce(w_numero_certame,'#') <> p_numero_certame or (coalesce(w_sq_modalidade,0) <> p_sq_lcmodalidade)) Then
          -- Recupera o n�mero do certame
          CL_CriaParametro(p_chave, w_numero_certame);

          -- Atualiza a tabela da licita��o com os dados da an�lise
          Update cl_solicitacao set numero_certame  = w_numero_certame Where sq_siw_solicitacao = p_chave;
      End If;
      
      If p_protocolo is not null Then
         -- Grava a chave do protocolo na solicita��o
         update siw_solicitacao a
           set a.protocolo_siw = (select sq_siw_solicitacao from pa_documento where p_protocolo = prefixo||'.'||substr(1000000+numero_documento,2,6)||'/'||ano||'-'||substr(100+digito,2,2))
         where sq_siw_solicitacao = p_chave;
      End If;
   ElsIf p_restricao = 'DADOS' Then
      -- Recupera o prefixo do servi�o, o c�digo da solicita��o e a modalidade
      select b.prefixo, a.codigo_interno, c.sq_lcmodalidade, c.numero_certame 
        into w_prefixo, w_codigo,         w_sq_modalidade,   w_numero_certame
        from siw_solicitacao           a
             inner join siw_menu       b on (a.sq_menu            = b.sq_menu)
             inner join cl_solicitacao c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
       where a.sq_siw_solicitacao = p_chave;

      -- Atualiza a tabela da licita��o com os dados da an�lise
      Update cl_solicitacao set
         sq_lcmodalidade          = p_sq_lcmodalidade,
         processo                 = coalesce(p_numero_processo,p_protocolo),
         data_abertura            = w_abertura,
         envelope_1               = w_envelope_1,
         envelope_2               = w_envelope_2,
         envelope_3               = w_envelope_3,
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
      update siw_solicitacao a set a.executor = p_executor, a.inicio = p_inicio where sq_siw_solicitacao = p_chave;

      If w_sq_modalidade is null or 
         w_numero_certame is null or 
         (w_sq_modalidade is not null and w_sq_modalidade <> p_sq_lcmodalidade)
      Then
         -- Recupera o n�mero do certame
         CL_CriaParametro(p_chave, w_numero_certame);

        -- Atualiza a tabela da licita��o com os dados da an�lise
        Update cl_solicitacao set numero_certame  = w_numero_certame Where sq_siw_solicitacao = p_chave;
      End If;

      -- Grava a chave do protocolo na solicita��o
      update siw_solicitacao a
         set a.protocolo_siw = case when p_protocolo is null
                                    then null
                                    else (select sq_siw_solicitacao from pa_documento where p_protocolo = prefixo||'.'||substr(1000000+numero_documento,2,6)||'/'||ano||'-'||substr(100+digito,2,2))
                               end
      where sq_siw_solicitacao = p_chave;
   ElsIf p_restricao = 'CONCLUSAO' Then
      -- Atualiza a tabela da licita��o com os dados da conclus�o
      Update cl_solicitacao set
         data_homologacao          = p_data_homologacao,
         data_diario_oficial       = p_data_diario_oficial,
         pagina_diario_oficial     = p_pagina_diario_oficial,
         justificativa_preco_maior = p_just_preco_maior,
         sq_arquivo_justificativa  = p_arquivo_justificativa
      Where sq_siw_solicitacao = p_chave;
   ElsIf p_restricao = 'SITUACAO' Then
      -- Atualiza a situa��o da licita��o
      Update cl_solicitacao set
         sq_lcsituacao            = p_sq_lcsituacao,
         data_abertura            = w_abertura,
         envelope_1               = w_envelope_1,
         envelope_2               = w_envelope_2,
         envelope_3               = w_envelope_3,
         prioridade               = p_prioridade
      Where sq_siw_solicitacao = p_chave;

      -- Grava os dados da solicita��o
      update siw_solicitacao a set a.inicio = p_inicio where sq_siw_solicitacao = p_chave;
      
   ElsIf p_restricao = 'ORDENACAO' Then
      -- Atualiza a ordem dos itens de uma licita��o
      Update cl_solicitacao_item set
         ordem                  = p_ordem,
         quantidade             = coalesce(p_quantidade,quantidade),
         dias_validade_proposta = coalesce(p_dias_item,dias_validade_proposta),
         detalhamento           = coalesce(p_detalhamento,detalhamento),
         sq_projeto_rubrica     = coalesce(to_number(p_rubrica),sq_projeto_rubrica)
      Where sq_solicitacao_item = p_chave;
   ElsIf p_restricao = 'VENCEDOR' Then
      -- Registra os vencedores da licita��o

      -- Primeiro garante que n�o ser� registrado nenhum outro vencedor para o mesmo item
      update cl_item_fornecedor
         set vencedor = 'N'
      where sq_solicitacao_item = (select sq_solicitacao_item from cl_item_fornecedor where sq_item_fornecedor = p_chave);

      -- Depois grava o vencedor indicado
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
