﻿create or replace FUNCTION SP_PutProjetoEtapa
   (p_operacao             varchar,
    p_chave               numeric,
    p_chave_aux           numeric,
    p_chave_pai           numeric,
    p_titulo              varchar,
    p_descricao           varchar,
    p_ordem               numeric,
    p_inicio              date,
    p_fim                 date,
    p_perc_conclusao      numeric,
    p_orcamento           numeric,
    p_sq_pessoa           numeric,
    p_sq_unidade          numeric,
    p_vincula_atividade   varchar,
    p_vincula_contrato    varchar,
    p_usuario             numeric,
    p_programada          varchar,
    p_cumulativa          varchar,
    p_quantidade          numeric,
    p_unidade_medida      varchar,
    p_pacote              varchar,
    p_base                numeric,
    p_pais                numeric,
    p_regiao              numeric,
    p_uf                  varchar,
    p_cidade              numeric,
    p_peso                numeric    
   ) RETURNS VOID AS $$
DECLARE
   w_chave    numeric(18);
   w_pai      numeric(18);
   w_existe   numeric(18);
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select nextVal('sq_projeto_etapa') into w_chave;
      
      -- Insere registro na tabela de etapas do projeto
      Insert Into pj_projeto_etapa 
         ( sq_projeto_etapa,    sq_siw_solicitacao, sq_etapa_pai,            ordem, 
           titulo,              descricao,          inicio_previsto,         fim_previsto, 
           perc_conclusao,      orcamento,          sq_pessoa,               sq_unidade,
           vincula_atividade,   vincula_contrato,   sq_pessoa_atualizacao,   ultima_atualizacao,
           programada,          cumulativa,         quantidade,              unidade_medida,
           pacote_trabalho,     base_geografica,    sq_pais,                 sq_regiao,
           co_uf,               sq_cidade,          peso)
      Values
         ( w_chave,             p_chave,            p_chave_pai,             p_ordem,
           p_titulo,            p_descricao,        p_inicio,                p_fim,
           p_perc_conclusao,    p_orcamento,        p_sq_pessoa,             p_sq_unidade,
           p_vincula_atividade, p_vincula_contrato, p_usuario,               now(),            
           p_programada,        p_cumulativa,       p_quantidade,            p_unidade_medida,
           p_pacote,            p_base,             p_pais,                  p_regiao,
           p_uf,                p_cidade,           p_peso);

      -- Recalcula os percentuais de execução dos pais da etapa
      PERFORM sp_calculaPercEtapa(w_chave, null);
   
      -- Atualiza os pesos das etapas
      PERFORM sp_ajustaPesoEtapa(w_chave, null);

      -- Atualiza as datas de início e término das etapas superiores
      PERFORM sp_ajustaDataEtapa(w_chave);

   Elsif p_operacao = 'A' Then -- Alteração
      -- Recupera a etapa pai
      select sq_etapa_pai into w_pai from pj_projeto_etapa where sq_projeto_etapa = p_chave_aux;
      -- Atualiza a tabela de restrições da etapa
      If p_pacote = 'N' Then
         DELETE FROM siw_restricao_etapa where sq_projeto_etapa = p_chave_aux; 
      end If;    
      -- Atualiza a tabela de etapas do projeto
      Update pj_projeto_etapa set
          sq_etapa_pai          = p_chave_pai,
          ordem                 = p_ordem,
          titulo                = p_titulo,
          descricao             = p_descricao,
          inicio_previsto       = p_inicio,
          fim_previsto          = p_fim,
          perc_conclusao        = coalesce(p_perc_conclusao,perc_conclusao),
          orcamento             = coalesce(p_orcamento, orcamento),
          sq_pessoa             = p_sq_pessoa,
          sq_unidade            = p_sq_unidade,
          vincula_atividade     = p_vincula_atividade,
          vincula_contrato      = p_vincula_contrato,
          programada            = p_programada,
          cumulativa            = p_cumulativa,
          quantidade            = p_quantidade,
          unidade_medida        = p_unidade_medida,
          sq_pessoa_atualizacao = p_usuario,
          ultima_atualizacao    = now(),
          pacote_trabalho       = p_pacote,
          base_geografica       = p_base,
          sq_pais               = p_pais,
          sq_regiao             = p_regiao,
          co_uf                 = p_uf,
          sq_cidade             = p_cidade,
          peso                  = p_peso
      where sq_siw_solicitacao = p_chave
        and sq_projeto_etapa   = p_chave_aux;

      -- Se houve alteração da subordinação, recalcula para o pai anterior
      If coalesce(w_pai,0) <> coalesce(p_chave_pai,0) Then
         -- Recalcula os percentuais de execução dos pais anteriores da etapa
         PERFORM sp_calculaPercEtapa(null, w_pai);
      End If;
      
      -- Recalcula os percentuais de execução dos pais da etapa
      PERFORM sp_calculaPercEtapa(p_chave_aux, null);
   
      -- Atualiza os pesos das etapas
      PERFORM sp_ajustaPesoEtapa(p_chave, null);

      -- Atualiza as datas de início e término das etapas superiores
      PERFORM sp_ajustaDataEtapa(p_chave);

   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove as vinculações de riscos
      DELETE FROM siw_restricao_etapa where sq_projeto_etapa = p_chave_aux;         
      -- Remove os registros de acompanhamento da execução
      DELETE FROM pj_etapa_mensal a where a.sq_projeto_etapa = p_chave_aux;

      -- Recupera a etapa pai
      select count(sq_projeto_etapa) into w_existe from pj_projeto_etapa where sq_projeto_etapa = coalesce(p_chave_aux,0);
      If w_existe > 0 Then
         select sq_etapa_pai into w_pai from pj_projeto_etapa where sq_projeto_etapa = p_chave_aux;
      End If;

      -- Remove o registro de comentários na tabela de etapas do projeto
      DELETE FROM pj_etapa_comentario
       where sq_projeto_etapa   = p_chave_aux;

      -- Remove o registro na tabela de etapas do projeto
      DELETE FROM pj_projeto_etapa
       where sq_siw_solicitacao = p_chave
        and sq_projeto_etapa   = p_chave_aux;

      -- Recalcula os percentuais de execução dos pais da etapa
      -- e os pesos relativos de cada uma das etapas do projeto
      If w_pai is not null Then PERFORM sp_calculaPercEtapa(null, w_pai); End If;

    -- Atualiza os pesos das etapas
    PERFORM sp_ajustaPesoEtapa(p_chave, null);

    -- Atualiza as datas de início e término das etapas superiores
    PERFORM sp_ajustaDataEtapa(p_chave);

   
   End If;
   END; $$ LANGUAGE 'PLPGSQL' VOLATILE;