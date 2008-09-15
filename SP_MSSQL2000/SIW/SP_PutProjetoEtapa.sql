alter procedure dbo.SP_PutProjetoEtapa
   (@p_operacao            varchar(1),
    @p_chave               int,
    @p_chave_aux           int       = null,
    @p_chave_pai           int       = null,
    @p_titulo              varchar(100) = null,
    @p_descricao           varchar(2000) = null,
    @p_ordem               int       = null,
    @p_inicio              datetime  = null,
    @p_fim                 datetime  = null,
    @p_perc_conclusao      int       = null,
    @p_orcamento           numeric(18,2)       = null,
    @p_sq_pessoa           int,
    @p_sq_unidade          int,
    @p_vincula_atividade   varchar(1)  = null,
    @p_vincula_contrato    varchar(1)  = null,
    @p_usuario             int,
    @p_programada          varchar(1)  = null,
    @p_cumulativa          varchar(1)  = null,
    @p_quantidade          int        = null,
    @p_unidade_medida      varchar(30)  = null,
    @p_pacote              varchar(1)  = null,
    @p_base                int        = null,
    @p_pais                int        = null,
    @p_regiao              int        = null,
    @p_uf                  varchar(2)  = null,
    @p_cidade              int        = null,
    @p_peso                int        = null
   ) as
   Declare @w_chave    numeric(18);
   Declare @w_pai      numeric(18);
   Declare @w_existe   numeric(18);
begin
   If @p_operacao = 'I' Begin -- Inclusão
      -- Recupera a próxima chave
      
      -- Insere registro na tabela de etapas do projeto
      Insert Into pj_projeto_etapa 
         ( sq_siw_solicitacao, sq_etapa_pai,            ordem, 
           titulo,              descricao,          inicio_previsto,         fim_previsto, 
           perc_conclusao,      orcamento,          sq_pessoa,               sq_unidade,
           vincula_atividade,   vincula_contrato,   sq_pessoa_atualizacao,   ultima_atualizacao,
           programada,          cumulativa,         quantidade,              unidade_medida,
           pacote_trabalho,     base_geografica,    sq_pais,                 sq_regiao,
           co_uf,               sq_cidade,          peso)
      Values
         ( @p_chave,            @p_chave_pai,             @p_ordem,
           @p_titulo,            @p_descricao,        @p_inicio,                @p_fim,
           @p_perc_conclusao,    @p_orcamento,        @p_sq_pessoa,             @p_sq_unidade,
           @p_vincula_atividade, @p_vincula_contrato, @p_usuario,               getdate(),            
           @p_programada,        @p_cumulativa,       @p_quantidade,            @p_unidade_medida,
           @p_pacote,            @p_base,             @p_pais,                  @p_regiao,
           @p_uf,                @p_cidade,           @p_peso);

      Set @w_chave = @@Identity;

      -- Recalcula os percentuais de execução dos pais da etapa
      exec sp_calculaPercEtapa @w_chave, null;
   
      -- Atualiza os pesos das etapas
      exec sp_ajustaPesoEtapa @w_chave, null;

      -- Atualiza as datas de início e término das etapas superiores
      exec sp_ajustaDataEtapa @w_chave;

   End Else If @p_operacao = 'A' Begin -- Alteração
      -- Recupera a etapa pai
      select @w_pai = sq_etapa_pai from pj_projeto_etapa where sq_projeto_etapa = @p_chave_aux;
      -- Atualiza a tabela de restrições da etapa
      If @p_pacote = 'N' Begin
         delete siw_restricao_etapa where sq_projeto_etapa = @p_chave_aux; 
      end    
      -- Atualiza a tabela de etapas do projeto
      Update pj_projeto_etapa set
          sq_etapa_pai          = @p_chave_pai,
          ordem                 = @p_ordem,
          titulo                = @p_titulo,
          descricao             = @p_descricao,
          inicio_previsto       = @p_inicio,
          fim_previsto          = @p_fim,
          perc_conclusao        = coalesce(@p_perc_conclusao,perc_conclusao),
          orcamento             = coalesce(@p_orcamento, orcamento),
          sq_pessoa             = @p_sq_pessoa,
          sq_unidade            = @p_sq_unidade,
          vincula_atividade     = @p_vincula_atividade,
          vincula_contrato      = @p_vincula_contrato,
          programada            = @p_programada,
          cumulativa            = @p_cumulativa,
          quantidade            = @p_quantidade,
          unidade_medida        = @p_unidade_medida,
          sq_pessoa_atualizacao = @p_usuario,
          ultima_atualizacao    = getdate(),
          pacote_trabalho       = @p_pacote,
          base_geografica       = @p_base,
          sq_pais               = @p_pais,
          sq_regiao             = @p_regiao,
          co_uf                 = @p_uf,
          sq_cidade             = @p_cidade,
          peso                  = @p_peso
      where sq_siw_solicitacao = @p_chave
        and sq_projeto_etapa   = @p_chave_aux;

      -- Se houve alteração da subordinação, recalcula para o pai anterior
      If coalesce(@w_pai,0) <> coalesce(@p_chave_pai,0) Begin
         -- Recalcula os percentuais de execução dos pais anteriores da etapa
         exec sp_calculaPercEtapa null, @w_pai;
      End
      
      -- Recalcula os percentuais de execução dos pais da etapa
      exec sp_calculaPercEtapa @p_chave_aux, null;
   
      -- Atualiza os pesos das etapas
      exec sp_ajustaPesoEtapa @p_chave, null;

      -- Atualiza as datas de início e término das etapas superiores
      exec sp_ajustaDataEtapa @p_chave;

   End Else If @p_operacao = 'E' Begin -- Exclusão
      -- Remove as vinculações de riscos
      delete siw_restricao_etapa where sq_projeto_etapa = @p_chave_aux;         
      -- Remove os registros de acompanhamento da execução
      delete pj_etapa_mensal where sq_projeto_etapa = @p_chave_aux;

      -- Recupera a etapa pai
      select @w_existe = count(sq_projeto_etapa) from pj_projeto_etapa where sq_projeto_etapa = coalesce(@p_chave_aux,0);
      If @w_existe > 0 Begin
         select @w_pai = sq_etapa_pai from pj_projeto_etapa where sq_projeto_etapa = @p_chave_aux;
      End

      -- Remove o registro na tabela de etapas do projeto
      delete pj_projeto_etapa
       where sq_siw_solicitacao = @p_chave
        and sq_projeto_etapa   = @p_chave_aux;

      -- Recalcula os percentuais de execução dos pais da etapa
      -- e os pesos relativos de cada uma das etapas do projeto
      If @w_pai is not null Begin exec sp_calculaPercEtapa null, @w_pai; End

    -- Atualiza os pesos das etapas
    exec sp_ajustaPesoEtapa @p_chave, null;

    -- Atualiza as datas de início e término das etapas superiores
    exec sp_ajustaDataEtapa @p_chave;

   
   End
   
end
