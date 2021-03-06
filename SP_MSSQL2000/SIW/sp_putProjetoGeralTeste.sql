ALTER  procedure [dbo].[sp_PutProjetoGeralTeste]
   (@p_operacao            varchar(1),
    @p_chave               int           =null,
    @p_copia               int           =null,
    @p_menu                int,
    @p_unidade             int           =null,
    @p_solicitante         int           =null,
    @p_proponente          varchar(90)   =null,
    @p_cadastrador         int           =null,
    @p_executor            int           =null,
    @p_plano               int           =null,
    @p_objetivo            varchar(2000) =null,    
    @p_sqcc                int           =null,
    @p_solic_pai           int           =null,
    @p_descricao           varchar(2000) =null,
    @p_justificativa       varchar(2000) =null,
    @p_inicio              datetime      =null,
    @p_fim                 datetime      =null,
    @p_valor               numeric(18,2)   =null,
    @p_data_hora           varchar(1)    =null,
    @p_unid_resp           int           =null,
    @p_codigo              varchar(60)   =null,
    @p_titulo              varchar(2000) =null,
    @p_prioridade          int           =null,
    @p_aviso               varchar(1)    =null,
    @p_dias                int           =null,
    @p_aviso_pacote        varchar(1)    =null,
    @p_dias_pacote         int           =null,
    @p_cidade              int           =null,
    @p_palavra_chave       varchar(90)   =null,
    @p_vincula_contrato    varchar(1)    =null,
    @p_vincula_viagem      varchar(1)    =null,
    @p_sq_acao_ppa         int           =null,
    @p_sq_orprioridade     int           =null,
    @p_selecionada_mpog    varchar(1)    =null,
    @p_selecionada_relev   varchar(1)    =null,
    @p_sq_tipo_pessoa      int           =null,
    @p_chave_nova          int output
   ) as 
begin

   Declare @w_origem int, @w_destino int
   declare @w_arq      varchar(4000);
   declare @w_coord    varchar(4000);
   declare @w_chave    int;
   declare @w_chave1   int;
   declare @w_log_sol  int;
   declare @w_log_esp  int;
   declare @w_ativ     int;
   declare @i          int;
   declare @w_item     int;   
   declare @w_objetivo varchar(200);
   declare @w_sq_menu  numeric(18);
   declare @w_sq_siw_arquivo numeric(18);
   declare @sq_siw_coordenada numeric(18);
   declare @w_sq_siw_coordenada numeric(18);
   set @w_arq   = ', ';
   set @w_coord = ', ';
   set @i       = 0;
   set @w_objetivo = @p_objetivo +',';
   declare @w_sq_cc  numeric(18);
   declare @w_sq_projeto_recurso numeric(18);
   declare @w_sq_projeto_rubrica numeric(18);
   declare @w_siw_restricao numeric(18);
   declare @w_ativo   varchar(1);
   declare @w_flag    int;

   Declare @w_risco_pai table (
      risco                  int,   
      chave                  int
   )

   Declare @w_recurso_pai table (
       recurso               int,
       chave                 int
      )

   declare @w_etapa table (
       chave               int,
       sq_chave_destino    int,
       sq_chave_origem     int,
       sq_chave_pai_origem int
      )

   Declare @w_etapa_pai table (
       etapa                 int,
       chave                 int
      )

   declare c_rubricas cursor for
    select 
       [SQ_CC]
      ,[CODIGO]
      ,[NOME]
      ,[DESCRICAO]
      ,[ATIVO]
      ,[APLICACAO_FINANCEIRA]
    from PJ_RUBRICA
    where ativo = 'S' and sq_siw_solicitacao = @p_copia;
     
  declare c_riscos cursor for
     select     sq_siw_restricao,      sq_siw_solicitacao,      sq_pessoa,           sq_pessoa_atualizacao,      sq_tipo_restricao,      
                risco,                 problema,                descricao,           probabilidade,              impacto, 
                criticidade,           estrategia,              acao_resposta,       ultima_atualizacao
     from siw_restricao where risco = 'S' and sq_siw_solicitacao = @p_copia;

  declare c_etapa_risco cursor for
      select a.*
        from siw_restricao_etapa      a
             inner join siw_restricao b on (a.sq_siw_restricao = b.sq_siw_restricao)
       where b.sq_siw_solicitacao = @p_copia;

  declare c_recursos cursor for
     select sq_projeto_recurso, sq_siw_solicitacao, nome, tipo, descricao, finalidade
     from pj_projeto_recurso 
     where sq_siw_solicitacao = @p_copia;

  declare c_etapas cursor for
      select  sq_projeto_etapa,   sq_siw_solicitacao,          ordem,                titulo,
                  descricao,          inicio_previsto,             fim_previsto,         inicio_real,
                  fim_real,           perc_conclusao,              orcamento,            sq_unidade,
                  sq_pessoa,          vincula_atividade,           sq_pessoa_atualizacao,
                  unidade_medida,     quantidade,                  cumulativa,           programada, 
                  vincula_contrato,   pacote_trabalho,             base_geografica,      sq_pais, 
                  sq_regiao,          co_uf,                       sq_cidade,            peso
      from pj_projeto_etapa where sq_siw_solicitacao = @p_copia;

  declare c_etapa_recurso cursor for
      select a.*
        from pj_recurso_etapa              a
             inner join pj_projeto_recurso b on (a.sq_projeto_recurso = b.sq_projeto_recurso)
       where b.sq_siw_solicitacao = @p_copia;

  declare c_atividades cursor for
      select sq_siw_solicitacao,   sq_pessoa,
             sq_siw_tramite,            data,
             devolucao,            observacao
      from siw_solic_log t where t.sq_siw_solicitacao = @p_chave;

  declare c_arquivos cursor for
      select sq_siw_arquivo from siw_solic_arquivo where sq_siw_solicitacao = @p_chave;

  declare c_coordenadas cursor for
      select sq_siw_coordenada from siw_coordenada_solicitacao where sq_siw_solicitacao = @p_chave;

   If @p_operacao <> 'I' begin -- Inclusão
      -- Remove as vinculações existentes para a solicitação
      delete siw_solicitacao_objetivo where sq_siw_solicitacao = coalesce(@w_chave, @p_chave);
   End

   If @p_operacao = 'I' begin -- Inclusão

      -- Insere registro em siw_SOLICITACAO
      insert into siw_solicitacao (
                             sq_menu,       sq_siw_tramite,      solicitante,
         cadastrador,        executor,      descricao,           justificativa,
         inicio,             fim,           inclusao,            ultima_alteracao,
         conclusao,          valor,         opiniao,             data_hora,
         sq_unidade,         sq_cc,         sq_solic_pai,        sq_cidade_origem,
         palavra_chave,      sq_plano,      codigo_interno,      titulo)
      (select
                              @p_menu,        a.sq_siw_tramite,     @p_solicitante,
         @p_cadastrador,      @p_executor,    @p_descricao,         @p_justificativa,
         @p_inicio,           @p_fim,         getdate(),              getdate(),
         null,                @p_valor,       null,                 @p_data_hora,
         @p_unidade,          @p_sqcc,        @p_solic_pai,         @p_cidade,
         @p_palavra_chave,    @p_plano,       @p_codigo,            @p_titulo
         from siw_tramite a
        where a.sq_menu = @p_menu
          and a.sigla   = 'CI'
      );

     Select @w_chave = @@IDENTITY  

      -- Insere registro em pj_projeto
      Insert into pj_projeto
         ( sq_siw_solicitacao,  sq_unidade_resp,  prioridade,        aviso_prox_conc,
           dias_aviso,          inicio_real,      fim_real,          concluida,
           data_conclusao,      nota_conclusao,   custo_real,        proponente,
           sq_tipo_pessoa,      vincula_contrato, vincula_viagem,    aviso_prox_conc_pacote, 
           perc_dias_aviso_pacote
         )
      (select
           @w_chave,              @p_unid_resp,    @p_prioridade,     @p_aviso,
           @p_dias,               null,            null,              'N',
           null,                  null,            0,                 @p_proponente,
           @p_sq_tipo_pessoa,     coalesce(@p_vincula_contrato,'N'),       coalesce(@p_vincula_viagem,'N'),
           @p_aviso_pacote,       @p_dias_pacote
       
      );

      -- Grava os dados de uma ação orçamentária, se for o caso
      If @p_sq_acao_ppa is not null or @p_sq_orprioridade is not null begin
         -- Grava os dados complementares ao projeto, relativos à ação orçamentária
         insert into or_acao  (sq_siw_solicitacao, sq_acao_ppa, sq_orprioridade)
         values (@w_chave, @p_sq_acao_ppa, @p_sq_orprioridade);
         If @p_sq_acao_ppa is not null begin
            -- Atualiza os dados da tabela de ações do PPA
            update or_acao_ppa set
               selecionada_mpog      = @p_selecionada_mpog,
               selecionada_relevante = @p_selecionada_relev
            where sq_acao_ppa = @p_sq_acao_ppa;
         End
      End

      -- Insere log da solicitação

      Insert Into siw_solic_log
         (sq_siw_solicitacao, sq_pessoa,
          sq_siw_tramite,            data,               devolucao,
          observacao
         )
      (select
          @w_chave,            @p_cadastrador,
          a.sq_siw_tramite,          getdate(),           'N',
          'Cadastramento inicial'
         from siw_tramite a
        where a.sq_menu = @p_menu
          and a.sigla   = 'CI'
      );

      -- Se o projeto foi copiado de outra, grava os dados complementares
      If @p_copia is not null begin
         -- Complementa as informações da solicitacao
         Declare @w_descricao      varchar(2000);
         Declare @w_justificativa  varchar(2000);
         select @w_descricao = descricao, @w_justificativa = justificativa
            from siw_solicitacao
           where sq_siw_solicitacao = @p_copia;

         update siw_solicitacao set 
             descricao = @w_descricao,
             justificativa = @w_justificativa
         where sq_siw_solicitacao = @w_chave;

         -- Complementa as informações do projeto
         declare @w_outra_parte       numeric(18);
         Declare @w_preposto          numeric(18);
         Declare @w_sq_cidade         numeric(18);
         Declare @w_limite_passagem   numeric(18);
         Declare @w_objetivo_superior varchar(2000);
         Declare @w_exclusoes         varchar(2000);
         Declare @w_premissas         varchar(2000);
         Declare @w_restricoes        varchar(2000);
         select @w_outra_parte = outra_parte, @w_preposto = preposto, 
                @w_sq_cidade = sq_cidade, @w_limite_passagem = limite_passagem, 
                @w_objetivo_superior = objetivo_superior, @w_exclusoes = exclusoes, 
                @w_premissas = premissas, @w_restricoes = restricoes
            from pj_projeto
           where sq_siw_solicitacao = @p_copia;
         
         update pj_projeto set
            outra_parte = @w_outra_parte, preposto = @w_preposto, 
            sq_cidade = @w_sq_cidade, limite_passagem = @w_limite_passagem, 
            objetivo_superior = @w_objetivo_superior, exclusoes = @w_exclusoes, 
            premissas = @w_premissas, restricoes = @w_restricoes
         where sq_siw_solicitacao = @w_chave;

         -- Insere registro na tabela de interessados
         declare @w_sq_pessoa          numeric(18);
         declare @w_sq_siw_solicitacao numeric(18);
         declare @w_tipo_visao         numeric(1);
         declare @w_envia_email        varchar(1);
         Select @w_sq_pessoa = sq_pessoa,   @w_sq_siw_solicitacao = sq_siw_solicitacao,
                @w_tipo_visao = tipo_visao,  @w_envia_email = envia_email
           from pj_projeto_interes
         where sq_siw_solicitacao = @p_copia

         update pj_projeto set
            @w_outra_parte = outra_parte, @w_preposto = preposto, 
            @w_sq_cidade = sq_cidade, @w_limite_passagem = limite_passagem, 
            @w_objetivo_superior = objetivo_superior, @w_exclusoes = exclusoes, 
            @w_premissas = premissas, @w_restricoes =  restricoes
         where sq_siw_solicitacao = @w_chave;
         
         -- Insere registro na tabela de áreas envolvidas
         declare @w_sq_unidade numeric(10);
         declare @w_papel      varchar(2000);

         open c_riscos
         Fetch next from c_riscos into
             @w_sq_unidade, @w_sq_siw_solicitacao, @w_papel
         While @@Fetch_Status = 0 Begin
         -- insere o recurso
         declare @w_sq_siw_restricao  numeric(18), @w_sq_pessoa_atualizacao numeric(18)
         declare @w_sq_tipo_restricao numeric(18), @w_risco                  varchar(1)
         declare @w_problema           varchar(1), @w_probabilidade          numeric(1)
         declare @w_impacto            numeric(1), @w_criticidade            numeric(1)
         declare @w_estrategia         varchar(1), @w_acao_resposta       varchar(2000)
         declare  @w_ultima_atualizacao datetime

         insert into siw_restricao
               (sq_siw_restricao,      sq_siw_solicitacao,      sq_pessoa,           sq_pessoa_atualizacao,      sq_tipo_restricao,      
                risco,                 problema,                descricao,           probabilidade,              impacto, 
                criticidade,           estrategia,              acao_resposta,       ultima_atualizacao)
         values
               (@w_chave1,             @w_chave,                @w_sq_pessoa,        @p_cadastrador,             @w_sq_tipo_restricao, 
                @w_risco,              @w_problema,             @w_descricao,        @w_probabilidade,           @w_impacto, 
                @w_criticidade,        @w_estrategia,           @w_acao_resposta,    getdate());
         -- Guarda pai do registro original
             set @w_chave1 = @@IDENTITY
         end
         close c_riscos
         deallocate c_riscos

         -- Insere recursos do projeto
         declare @w_nome varchar(100), @w_finalidade varchar(2000), @w_tipo int

         Open c_recursos
         Fetch next from c_recursos into
             @w_chave1, @w_chave, @w_nome, @w_tipo, @w_descricao, @w_finalidade
         While @@Fetch_Status = 0
         Begin
             Insert Into pj_projeto_recurso
                ( sq_projeto_recurso, sq_siw_solicitacao, nome,       tipo,      descricao,      finalidade )
             Values
                ( @w_chave1,          @w_chave,           @w_nome,    @w_tipo,   @w_descricao,   @w_finalidade);
             set @w_chave1 = @@IDENTITY
          end
          close c_recursos
          deallocate c_recursos

          -- Insere etapas do projeto
          declare @w_sq_projeto_etapa numeric(18), @w_titulo varchar(101)
          declare @w_inicio_previsto     datetime, @w_fim_previsto datetime, @w_inicio_real datetime
          declare @w_fim_real            datetime, @w_perc_conclusao numeric(18,2), @w_orcamento numeric(18,2)
          declare @w_vincula_atividade varchar(1)
          declare @w_unidade_medida   varchar(30), @w_quantidade numeric(18,2), @w_cumulativa varchar(1), @w_programada varchar(1)
          declare @w_vincula_contrato  varchar(1), @w_pacote_trabalho varchar(1), @w_base_geografica numeric(1), @w_sq_pais numeric(18)
          declare @w_sq_regiao        numeric(18), @w_co_uf varchar(3), @w_peso numeric(2)
          Declare @sq_projeto_etapa int,           @w_ordem numeric(3), @sq_etapa_pai       int

          open c_etapas
          Fetch next from c_etapas into
                  @w_ordem,            @w_titulo,
                  @w_descricao,        @w_inicio_previsto, @w_fim_previsto,     @w_orcamento,       
                  @w_sq_unidade,       @w_sq_pessoa,       @w_vincula_atividade,   
                  @w_sq_pessoa_atualizacao,                @w_unidade_medida,   @w_quantidade,
                  @w_cumulativa,       @w_programada,      @w_vincula_contrato, @w_pacote_trabalho,
                  @w_base_geografica,  @w_sq_pais,          @w_sq_regiao,        @w_co_uf,
                  @w_sq_cidade,        @w_peso

         Set @i = 0

         While @@Fetch_Status = 0 
         begin
          -- Guarda pai do registro original
             
             set @w_chave1 = @@IDENTITY
             set @i = @i + 1;

             insert into @w_etapa values (@i, @w_chave1, @w_sq_projeto_etapa, @sq_etapa_pai)
             insert into @w_etapa_pai values (@w_chave1, @i)

             -- insere o recurso
             Insert Into pj_projeto_etapa
                ( sq_projeto_etapa,   sq_siw_solicitacao,          ordem,                titulo,
                  descricao,          inicio_previsto,             fim_previsto,         inicio_real,
                  fim_real,           perc_conclusao,              orcamento,            sq_unidade,
                  sq_pessoa,          vincula_atividade,           sq_pessoa_atualizacao,
                  unidade_medida,     quantidade,                  cumulativa,           programada, 
                  vincula_contrato,   pacote_trabalho,             base_geografica,      sq_pais, 
                  sq_regiao,          co_uf,                       sq_cidade,            peso)
             Values
                ( @w_chave1,           @w_chave,               @w_ordem,           @w_titulo,
                  @w_descricao,        @w_inicio_previsto,     @w_fim_previsto,    null,
                  null,                0,                      @w_orcamento,       @w_sq_unidade,
                  @w_sq_pessoa,        @w_vincula_atividade,   @w_sq_pessoa_atualizacao,
                  @w_unidade_medida,   @w_quantidade,          @w_cumulativa,      @w_programada, 
                  @w_vincula_contrato, @w_pacote_trabalho,     @w_base_geografica, @w_sq_pais, 
                  @w_sq_regiao,        @w_co_uf,               @w_sq_cidade,       @w_peso);
            
             -- Guarda pai do registro original
             set @w_chave1 = @@IDENTITY
          end
          close c_etapas
          deallocate c_etapas

             -- insere o recurso

             -- Grava os dados de uma ação orçamentária, se for o caso
             If @p_sq_acao_ppa is not null or @p_sq_orprioridade is not null begin
                -- Grava os dados complementares ao projeto, relativos à ação orçamentária
                insert into or_acao  (sq_siw_solicitacao, sq_acao_ppa, sq_orprioridade)
                values (@p_copia, @p_sq_acao_ppa, @p_sq_orprioridade);
                If @p_sq_acao_ppa is not null begin
                   -- Atualiza os dados da tabela de ações do PPA
                   update or_acao_ppa set
                      selecionada_mpog      = @p_selecionada_mpog,
                      selecionada_relevante = @p_selecionada_relev
                   where sq_acao_ppa = @p_sq_acao_ppa;
                End
             End

          end
          -- Acerta o vínculo entre as etapas
         -- Acerta o vínculo entre os registros
         Set @i = 0
         While @i <= (select count(*) from @w_etapa)
         Begin
            select @w_origem = sq_chave_pai_origem, @w_destino = sq_chave_destino
              from @w_etapa
             where chave = @i
            If @w_origem is not null Begin
               update pj_projeto_etapa
                  set sq_etapa_pai = (select chave from @w_etapa_pai where etapa = @w_origem)
                where sq_projeto_etapa = @w_destino
            End
            Set @i = @i + 1
         End

          -- Insere o relacionamento entre etapas e recursos
         Declare @observacao varchar(500)

         Open c_etapa_recurso
         Fetch next from c_etapa_recurso into @sq_projeto_etapa, @w_sq_projeto_recurso, @observacao
        
         While @@Fetch_Status = 0
         Begin
            Insert Into pj_recurso_etapa
               (sq_projeto_etapa, sq_projeto_recurso, observacao) 
            (select a.chave, b.chave, @observacao
               from @w_etapa_pai a, @w_recurso_pai b
              where a.etapa   = @sq_projeto_etapa
                and b.recurso = @w_sq_projeto_recurso
            )
    
            Fetch next from c_etapa_recurso into @sq_projeto_etapa, @w_sq_projeto_recurso, @observacao
         End
         Close c_etapa_recurso
         Deallocate c_etapa_recurso

          -- Insere o relacionamento entre etapas e riscos
         Open c_etapa_risco
         Fetch next from c_etapa_risco into @sq_projeto_etapa, @w_sq_siw_restricao
        
         While @@Fetch_Status = 0
         Begin
            Insert Into siw_restricao_etapa 
               ( sq_siw_restricao,                   sq_projeto_etapa) 
            (select a.chave, b.chave
               from @w_etapa_pai a, @w_risco_pai b
              where a.etapa   = @w_sq_projeto_etapa
                and b.risco   = @w_sq_siw_restricao
            )
            Fetch next from c_etapa_risco into @sq_projeto_etapa, @w_sq_siw_restricao
         End
         Close c_etapa_risco
         Deallocate c_etapa_risco
          
          -- Insere rubricas do projeto

         declare @w_codigo varchar(20)
         declare @w_aplicacao_financeira varchar(1)

         Open c_rubricas
         Fetch next from c_rubricas into @w_sq_cc ,  @w_codigo, @w_nome, @w_descricao, @w_ativo, @w_aplicacao_financeira
        
         While @@Fetch_Status = 0
         Begin
             insert into pj_rubrica
                (sq_cc,        codigo,      nome,      descricao,      ativo,      aplicacao_financeira)
             values 
                (@w_sq_cc ,  @w_codigo, @w_nome, @w_descricao, @w_ativo, @w_aplicacao_financeira);
             
             Set @w_chave1 = @@IDENTITY;

             insert into pj_rubrica_cronograma
               (sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real)
               (select @w_chave1, inicio,   fim, valor_previsto, valor_real 
                  from pj_rubrica_cronograma
                 where sq_projeto_rubrica = @w_sq_projeto_rubrica);
         End
         Close c_rubricas
         Deallocate c_rubricas
      end else if @p_operacao = 'A' begin -- Alteração
      -- Atualiza a tabela de solicitações
      Update siw_solicitacao set
          sq_plano         = @p_plano,
          codigo_interno   = @p_codigo,
          titulo           = rtrim(ltrim(@p_titulo)),
          sq_cc            = @p_sqcc,
          sq_solic_pai     = @p_solic_pai,
          descricao        = coalesce(@p_descricao,descricao),
          justificativa    = coalesce(@p_justificativa,justificativa),
          solicitante      = @p_solicitante,
          executor         = @p_executor,
          inicio           = @p_inicio,
          fim              = @p_fim,
          ultima_alteracao = getdate(),
          valor            = @p_valor,
          sq_cidade_origem = @p_cidade,
          palavra_chave    = @p_palavra_chave
      where sq_siw_solicitacao = @p_chave;

      -- Atualiza a tabela de projetos
      Update pj_projeto set
          sq_unidade_resp  = @p_unid_resp,
          proponente       = @p_proponente,
          prioridade       = @p_prioridade,
          aviso_prox_conc  = @p_aviso,
          dias_aviso       = @p_dias,
          aviso_prox_conc_pacote = @p_aviso_pacote,
          perc_dias_aviso_pacote = @p_dias_pacote,
          sq_tipo_pessoa   = @p_sq_tipo_pessoa,
          vincula_contrato = coalesce(@p_vincula_contrato,'N'),
          vincula_viagem   = coalesce(@p_vincula_viagem,'N')
      where sq_siw_solicitacao = @p_chave;

      If coalesce(@p_sq_tipo_pessoa,0) = 1 begin
         update pj_projeto set preposto = null where sq_siw_solicitacao = @p_chave;
         delete pj_projeto_representante where sq_siw_solicitacao = @p_chave;
      End

      -- Atualiza os dados de uma ação orçamentária, se for o caso
      If @p_sq_acao_ppa is not null or @p_sq_orprioridade is not null begin
         -- Grava os dados complementares ao projeto, relativos à ação orçamentária
         update or_acao set
            sq_acao_ppa      = @p_sq_acao_ppa,
            sq_orprioridade  = @p_sq_orprioridade
         where sq_siw_solicitacao = @p_chave;
         If @p_sq_acao_ppa is not null begin
            -- Atualiza os dados da tabela de ações do PPA
            update or_acao_ppa set
               selecionada_mpog      = @p_selecionada_mpog,
               selecionada_relevante = @p_selecionada_relev
            where sq_acao_ppa = @p_sq_acao_ppa;
         End
      End

   end else if @p_operacao = 'E' begin -- Exclusão
      -- Verifica a quantidade de logs da solicitação
      select @w_log_sol = count(*) from siw_solic_log  where sq_siw_solicitacao = @p_chave;
      select @w_log_esp = count(*) from pj_projeto_log where sq_siw_solicitacao = @p_chave;
      select @w_ativ    = count(*) from siw_solicitacao where sq_solic_pai      = @p_chave;

      -- Se não tem atividades vinculadas nem foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contrário, coloca a solicitação como cancelada.
      If (@w_log_sol + @w_log_esp + @w_ativ) > 1 begin
         -- Insere log de cancelamento
         Insert Into siw_solic_log
            (sq_siw_solicitacao,   sq_pessoa,
             sq_siw_tramite,            data,                 devolucao,
             observacao
            )
         (select
             a.sq_siw_solicitacao, @p_cadastrador,
             a.sq_siw_tramite,          getdate(),              'N',
             'Cancelamento'
            from siw_solicitacao a
           where a.sq_siw_solicitacao = @p_chave
         );

         -- Atualiza a situação do projeto
         update pj_projeto set concluida = 'S' where sq_siw_solicitacao = @p_chave;

         -- Recupera a chave que indica que a solicitação está cancelada
         select @w_chave = a.sq_siw_tramite from siw_tramite a where a.sq_menu = @p_menu and a.sigla = 'CA';

         -- Atualiza a situação da solicitação
         update siw_solicitacao set sq_siw_tramite = @w_chave where sq_siw_solicitacao = @p_chave;

         -- Atualiza a ação PPA e inicitiva prioritária quando a ação for cancelada ou excluída
         update or_acao set sq_acao_ppa = null, sq_orprioridade = null where sq_siw_solicitacao = @p_chave;

         -- Atualiza eventuais atividades ligadas ao projeto

         Open c_atividades
         Fetch next from c_atividades into @sq_projeto_etapa, @w_sq_siw_restricao         
         while @@Fetch_Status = 0
         Begin 
             -- Insere log de cancelamento
             Insert Into siw_solic_log
                (sq_siw_solicitacao,   sq_pessoa,
                 sq_siw_tramite,            data,                 devolucao,
                 observacao
                )
             (select
                 a.sq_siw_solicitacao, @p_cadastrador,
                 a.sq_siw_tramite,          getdate(),              'N',
                 'Cancelamento'
                from siw_solicitacao a
               where a.sq_siw_solicitacao = @w_sq_siw_solicitacao
             );

             -- Atualiza a situação do projeto
             update gd_demanda set concluida = 'S' where sq_siw_solicitacao = @w_sq_siw_solicitacao;

             -- Recupera a chave que indica que a solicitação está cancelada
             select @w_chave = a.sq_siw_tramite from siw_tramite a where a.sq_menu = @w_sq_menu and a.sigla = 'CA';

             -- Atualiza a situação da solicitação
             update siw_solicitacao set sq_siw_tramite = @w_chave where sq_siw_solicitacao = @w_sq_siw_solicitacao;
         end
      end Else begin
         -- Monta string com a chave dos arquivos ligados à solicitação informada
         Set @w_flag = 0
         Open c_arquivos
         Fetch next from c_arquivos into @sq_projeto_etapa, @w_sq_siw_restricao
         While @@Fetch_Status = 0 
         Begin          
            Set @w_flag = 1;
            set @w_arq = @w_arq + @w_sq_siw_arquivo;
         end
        set @w_arq = substring(@w_arq, 3, len(@w_arq));

         -- Monta string com a chave das coordenadas ligadas à solicitação informada
         Open c_coordenadas
         Fetch next from c_coordenadas into @w_sq_siw_coordenada
         While @@Fetch_Status = 0
         Begin
             /*ALERTA!!!*/set @w_coord = @w_coord + cast(@w_sq_siw_coordenada as varchar(255));
         end 
         set @w_coord = substring(@w_coord, 3, len(@w_coord));

         -- Remove os registros vinculados ao projeto
         delete siw_coordenada_solicitacao  where sq_siw_solicitacao = @p_chave;
         delete siw_coordenada              where sq_siw_coordenada in (@w_coord);
         delete siw_solic_arquivo           where sq_siw_solicitacao = @p_chave;
         if @w_flag = 1 
         Begin
            delete siw_arquivo                 where sq_siw_arquivo     in (@w_arq);
         end
         delete siw_solic_indicador         where sq_siw_solicitacao = @p_chave;
         delete siw_meta_cronograma         where sq_solic_meta in (select sq_solic_meta from siw_solic_meta where sq_siw_solicitacao = @p_chave);
         delete siw_solic_meta              where sq_siw_solicitacao = @p_chave;
         delete siw_solicitacao_interessado where sq_siw_solicitacao = @p_chave;
         delete siw_solic_recurso_alocacao  where sq_solic_recurso in (select sq_solic_recurso from siw_solic_recurso where sq_siw_solicitacao = @p_chave);
         delete siw_solic_recurso           where sq_siw_solicitacao = @p_chave;
         delete siw_restricao_etapa         where sq_siw_restricao in (select sq_siw_restricao from siw_restricao where sq_siw_solicitacao = @p_chave);
         delete siw_restricao               where sq_siw_solicitacao = @p_chave;

         delete or_acao_prioridade          where sq_siw_solicitacao = @p_chave;
         delete or_acao_financ              where sq_siw_solicitacao = @p_chave;
         delete or_acao                     where sq_siw_solicitacao = @p_chave;
         delete pj_projeto_representante    where sq_siw_solicitacao = @p_chave;
         delete pj_projeto_envolv           where sq_siw_solicitacao = @p_chave;
         delete pj_projeto_interes          where sq_siw_solicitacao = @p_chave;
         delete pj_recurso_etapa            where sq_projeto_etapa in (select sq_projeto_etapa from pj_projeto_etapa where sq_siw_solicitacao = @p_chave);
         delete pj_rubrica_cronograma       where sq_projeto_rubrica in (select sq_projeto_rubrica from pj_rubrica where sq_siw_solicitacao = @p_chave);
         delete pj_rubrica                  where sq_siw_solicitacao = @p_chave;
         delete pj_projeto_etapa            where sq_siw_solicitacao = @p_chave;
         delete pj_projeto_recurso          where sq_siw_solicitacao = @p_chave;

         -- Remove o registro na tabela de projetos
         delete pj_projeto                  where sq_siw_solicitacao = @p_chave;

         -- Remove o log da solicitação
         delete siw_solic_log               where sq_siw_solicitacao = @p_chave;

         -- Remove o registro na tabela de solicitações
         delete siw_solicitacao             where sq_siw_solicitacao = @p_chave;
   End

   
  End
-- Devolve a chave
   If @p_chave is not null
      begin  set @p_chave_nova = @p_chave;
      end else 
   begin   set @p_chave_nova = @w_chave;
   End

   If @p_operacao in ('I','A') and @p_objetivo is not null begin
      -- Para cada objetivo estratégico, grava um registro na tabela de vinculações
    while ( len(@w_objetivo) > 0 )
      begin
         set @w_item  = rTrim(ltrim(substring(@w_objetivo,1,charindex(',',@w_objetivo)-1)));    
         --If Len(@w_item) > 0 begin
           If Len(@w_item) > 1 begin
            insert into siw_solicitacao_objetivo(sq_siw_solicitacao, sq_plano, sq_peobjetivo) values (coalesce(@w_chave,@p_chave), @p_plano, cast(@w_item as numeric));
         End
         set @w_objetivo = substring(@w_objetivo,charindex(',',@w_objetivo)+1,200);
         --Exit when @w_objetivo is null;
      End
    End   
   -- Devolve a chave
   If @p_chave is not null
   begin set @p_chave_nova = @p_chave;
   end Else begin set @p_chave_nova = @w_chave;
   End
end