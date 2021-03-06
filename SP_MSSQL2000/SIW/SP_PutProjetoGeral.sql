alter procedure sp_PutProjetoGeral
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
    @p_valor               numeric(18)   =null,
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
   ) as begin

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

   Declare @w_risco_pai table (
      risco                  int,   
      chave                  int
   )

   Declare @w_recurso_pai table (
       recurso               int,
       chave                 int
      )

   declare @w_etapa table (
       sq_chave_destino    int,
       sq_chave_origem     int,
       sq_chave_pai_origem int
      )

   Declare @w_etapa_pai table (
       recurso               int,
       chave                 int
      )

   declare c_rubricas cursor for
     select * from pj_rubrica where ativo = 'S' and sq_siw_solicitacao = @p_copia;
     
  declare c_riscos cursor for
     select * from siw_restricao where risco = 'S' and sq_siw_solicitacao = @p_copia;

  declare c_etapa_risco cursor for
      select a.*
        from siw_restricao_etapa      a
             inner join siw_restricao b on (a.sq_siw_restricao = b.sq_siw_restricao)
       where b.sq_siw_solicitacao = @p_copia;

  declare c_recursos cursor for
     select * from pj_projeto_recurso where sq_siw_solicitacao = @p_copia;

  declare c_etapas cursor for
      select * from pj_projeto_etapa where sq_siw_solicitacao = @p_copia;

  declare c_etapa_recurso cursor for
      select a.*
        from pj_recurso_etapa              a
             inner join pj_projeto_recurso b on (a.sq_projeto_recurso = b.sq_projeto_recurso)
       where b.sq_siw_solicitacao = @p_copia;

  declare c_atividades cursor for
      select * from siw_solicitacao t where t.sq_solic_pai = @p_chave;

  declare c_arquivos cursor for
      select sq_siw_arquivo from siw_solic_arquivo where sq_siw_solicitacao = @p_chave;

  declare c_coordenadas cursor for
      select sq_siw_coordenada from siw_coordenada_solicitacao where sq_siw_solicitacao = @p_chave;
begin
   If @p_operacao <> 'I' Begin -- Inclus�o
      -- Remove as vincula��es existentes para a solicita��o
      delete siw_solicitacao_objetivo where sq_siw_solicitacao = coalesce(@w_chave, @p_chave);
   End

   If @p_operacao = 'I' Begin -- Inclus�o

      -- Insere registro em siw_SOLICITACAO
      insert into siw_solicitacao (
         sq_siw_solicitacao, sq_menu,       sq_siw_tramite,      solicitante,
         cadastrador,        executor,      descricao,           justificativa,
         inicio,             fim,           inclusao,            ultima_alteracao,
         conclusao,          valor,         opiniao,             data_hora,
         sq_unidade,         sq_cc,         sq_solic_pai,        sq_cidade_origem,
         palavra_chave,      sq_plano,      codigo_interno,      titulo)
      (select
         @w_Chave,            @p_menu,        a.sq_siw_tramite,    @p_solicitante,
         @p_cadastrador,      @p_executor,    @p_descricao,         @p_justificativa,
         @p_inicio,           @p_fim,         getdate(),             getdate(),
         null,               @p_valor,       null,                @p_data_hora,
         @p_unidade,          @p_sqcc,        @p_solic_pai,         @p_cidade,
         @p_palavra_chave,    @p_plano,       @p_codigo,            @p_titulo
         from siw_tramite a
        where a.sq_menu = @p_menu
          and a.sigla   = 'CI'
      );

      -- Recupera a pr�xima chave
      set @w_Chave = @@IDENTITY

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

      -- Grava os dados de uma a��o or�ament�ria, se for o caso
      If @p_sq_acao_ppa is not null or @p_sq_orprioridade is not null begin
         -- Grava os dados complementares ao projeto, relativos � a��o or�ament�ria
         insert into or_acao  (sq_siw_solicitacao, sq_acao_ppa, sq_orprioridade)
         values (@w_chave, @p_sq_acao_ppa, @p_sq_orprioridade);
         If @p_sq_acao_ppa is not null begin
            -- Atualiza os dados da tabela de a��es do PPA
            update or_acao_ppa set
               selecionada_mpog      = @p_selecionada_mpog,
               selecionada_relevante = @p_selecionada_relev
            where sq_acao_ppa = @p_sq_acao_ppa;
         End
      End

      -- Insere log da solicita��o
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
      If p_copia is not null begin
         -- Complementa as informa��es da solicitacao
         Declare @w_descricao      varchar(2000);
         Declare @w_justificativa  varchar(2000);
         select @w_descricao = descricao, @w_justificativa = justificativa
            from siw_solicitacao
           where sq_siw_solicitacao = @p_copia;

         update siw_solicitacao set 
             descricao = @w_descricao,
             justificativa = @w_justificativa
         where sq_siw_solicitacao = @w_chave;

         declare @w_outra_parte numeric(18), @w_preposto numeric(18), @w_sq_cidade numeric(18)
         declare @w_limite_passagem numeric(18), @w_objetivo_superior varchar(2000), @w_exclusoes varchar(2000)
         declare @w_premissas varchar(2000), @w_restricoes varchar(2000)

         -- Complementa as informa��es do projeto
          select @w_outra_parte = outra_parte, @w_preposto = preposto, @w_sq_cidade = sq_cidade, @w_limite_passagem = limite_passagem, 
                 @w_objetivo_superior = objetivo_superior, @w_exclusoes = exclusoes, @w_premissas = premissas, @w_restricoes = restricoes
            from pj_projeto
           where sq_siw_solicitacao = @p_copia

         update pj_projeto set
                 outra_parte = @w_outra_parte, preposto = @w_preposto, sq_cidade = @w_sq_cidade, limite_passagem = @w_limite_passagem, 
                 @w_objetivo_superior = objetivo_superior, @w_exclusoes = exclusoes, @w_premissas = premissas, @w_restricoes = restricoes
         where sq_siw_solicitacao = w_chave;

         -- Insere registro na tabela de interessados
         Insert Into pj_projeto_interes ( sq_pessoa,   sq_siw_solicitacao,   tipo_visao,    envia_email )
         (Select                          a.sq_pessoa, w_chave,              a.tipo_visao,  a.envia_email
           from pj_projeto_interes a
          where a.sq_siw_solicitacao = p_copia
         );
         -- Insere registro na tabela de �reas envolvidas
         Insert Into pj_projeto_envolv ( sq_unidade,   sq_siw_solicitacao,   papel )
         (Select                         a.sq_unidade, w_chave,              a.papel
            from pj_projeto_envolv a
           where a.sq_siw_solicitacao = p_copia
         );

        -- Insere os riscos do projeto
        declare @w_sq_pessoa numeric(18), @w_sq_tipo_restricao numeric(18)
        declare @w_risco varchar(1), @w_problema varchar(1), @w_probabilidade numeric(1)
        declare @w_impacto numeric(1), @w_criticidade numeric(1), @w_estrategia varchar(1)
        declare @w_acao_resposta varchar(2000)

        Open c_riscos
        Fetch next from c_riscos
        begin
             -- insere o recurso
             insert into siw_restricao
               (sq_siw_restricao,      sq_siw_solicitacao,      sq_pessoa,           sq_pessoa_atualizacao,      sq_tipo_restricao,      
                risco,                 problema,                descricao,           probabilidade,              impacto, 
                criticidade,           estrategia,              acao_resposta,       ultima_atualizacao)
             values
               (@w_chave1,             @w_chave,                @w_sq_pessoa,        @p_cadastrador,              @w_sq_tipo_restricao, 
                @w_risco,              @w_problema,             @w_descricao,        @w_probabilidade,         @w_impacto, 
                @w_criticidade,        @w_estrategia,           @w_acao_resposta,    getdate());

              set @w_chave1 = @@IDENTITY;
         End
         Close c_riscos
         Deallocate c_riscos

          -- Insere recursos do projeto
         declare @w_nome varchar(100), @w_tipo numeric(2), @w_finalidade varchar(2000)

         Open c_recursos
         Fetch next from c_recursos
         begin
             -- Guarda pai do registro original
             -- set @w_recurso_pai(@w_sq_projeto_recurso) = @w_chave1;

             -- insere o recurso
             Insert Into pj_projeto_recurso
                ( sq_projeto_recurso, sq_siw_solicitacao, nome,       tipo,      descricao,      finalidade )
             Values
                ( @w_chave1,           @w_chave,          @w_nome,  @w_tipo, @w_descricao, @w_finalidade);
    
             -- recupera a pr�xima chave do recurso       
             set @w_chave1 = @@IDENTITY

         End
         Close c_recursos
         Deallocate c_recursos

          -- Insere etapas do projeto
         declare @w_ordem numeric(3), @w_titulo varchar(101), @w_inicio_previsto datetime
         declare @w_fim_previsto datetime, @w_orcamento numeric(18,2), @w_sq_unidade numeric(10)
         declare @w_vincula_atividade varchar(1), @w_sq_pessoa_atualizacao numeric(18)
         declare @w_unidade_medida varchar(30), @w_quantidade numeric(18,2), @w_cumulativa varchar(1)
         declare @w_programada varchar(1), @w_vincula_contrato varchar(1), @w_pacote_trabalho varchar(1)
         declare @w_base_geografica numeric(1), @w_sq_pais numeric(18), @w_sq_regiao numeric(18)
         declare @w_co_uf varchar(3), @w_peso numeric(2)


         Open c_etapas
         Fetch next from c_etapas
         begin
             -- Guarda pai do registro original
             set @i = @i + 1;
             set @w_etapa(@i).@w_sq_chave_destino    = @w_chave1;
             set @w_etapa(@i).@w_sq_chave_origem     = @sq_projeto_etapa;
             set @w_etapa(@i).@w_sq_chave_pai_origem = @sq_etapa_pai;

             set w_etapa_pai(@w_sq_projeto_etapa) = @w_chave1;

             -- insere o recurso
             Insert Into pj_projeto_etapa
                ( sq_siw_solicitacao,          ordem,                titulo,
                  descricao,          inicio_previsto,             fim_previsto,         inicio_real,
                  fim_real,           perc_conclusao,              orcamento,            sq_unidade,
                  sq_pessoa,          vincula_atividade,           sq_pessoa_atualizacao,
                  unidade_medida,     quantidade,                  cumulativa,           programada, 
                  vincula_contrato,   pacote_trabalho,             base_geografica,      sq_pais, 
                  sq_regiao,          co_uf,                       sq_cidade,            peso)
             Values
                ( @w_chave,            @w_ordem,               @w_titulo,
                  @w_descricao,        @w_inicio_previsto,     @w_fim_previsto,    null,
                  null,                0,                      @w_orcamento,       @w_sq_unidade,
                  @w_sq_pessoa,        @w_vincula_atividade,   @w_sq_pessoa_atualizacao,
                  @w_unidade_medida,   @w_quantidade,          @w_cumulativa,      @w_programada, 
                  @w_vincula_contrato, @w_pacote_trabalho,     @w_base_geografica, @w_sq_pais, 
                  @w_sq_regiao,        @w_co_uf,               @w_sq_cidade,       @w_peso);

             set @w_chave1 = @@IDENTITY;

             -- Grava os dados de uma a��o or�ament�ria, se for o caso
             If p_sq_acao_ppa is not null or p_sq_orprioridade is not null begin
                -- Grava os dados complementares ao projeto, relativos � a��o or�ament�ria
                insert into or_acao  (sq_siw_solicitacao, sq_acao_ppa, sq_orprioridade)
                values (@p_copia, @p_sq_acao_ppa, @p_sq_orprioridade);
                If p_sq_acao_ppa is not null begin
                   -- Atualiza os dados da tabela de a��es do PPA
                   update or_acao_ppa set
                      selecionada_mpog      = p_selecionada_mpog,
                      selecionada_relevante = p_selecionada_relev
                   where sq_acao_ppa = p_sq_acao_ppa;
                End;
             End;

         End
         Close c_etapas
         Deallocate c_etapas

          -- Acerta o v�nculo entre as etapas
         Set @i = 0
         While @i <= (select count(*) from @w_etapa)
         Begin
            select @w_origem = sq_chave_pai_origem, @w_destino = sq_chave_destino
              from @w_etapa
             where chave = @i
            If @w_origem is not null Begin
               update pj_projeto_etapa
                  set sq_etapa_pai = (select chave from pj_projeto_etapa where etapa = @w_origem)
                where sq_projeto_etapa = @w_destino
            End
            Set @i = @i + 1
         End

          -- Insere o relacionamento entre etapas e recursos

         declare @w_sq_projeto_etapa numeric(18)

         Open c_etapa_recurso
         Fetch next from c_etapa_recurso

             Insert Into pj_recurso_etapa
                ( sq_projeto_etapa,                   sq_projeto_recurso,                     observacao )
             Values
                ( @w_etapa_pai(@w_sq_projeto_etapa), @w_recurso_pai(@w_sq_projeto_recurso), @w_observacao );
         End
         Close c_etapa_recurso
         Deallocate c_etapa_recurso

          -- Insere o relacionamento entre etapas e riscos
         Open c_etapa_risco
         Fetch next from c_etapa_risco
         begin

             insert into siw_restricao_etapa (sq_siw_restricao,                   sq_projeto_etapa)
             values                          (@w_risco_pai(@w_sq_siw_restricao), @w_etapa_pai(@w_sq_projeto_etapa));
          end loop;

          -- Insere rubricas do projeto
         declare @w_codigo varchar(20), @w_aplicacao_financeira varchar(1)

         Open c_rubricas
         Fetch next from c_rubricas
         begin
             insert into pj_rubrica
                (sq_projeto_rubrica,  sq_siw_solicitacao,   sq_cc,      codigo,    nome,    descricao,    ativo,    aplicacao_financeira)
             values 
                (@w_chave1,           @w_chave,             @w_sq_cc ,  @w_codigo, @w_nome, @w_descricao, @w_ativo, @w_aplicacao_financeira);
             
             insert into pj_rubrica_cronograma
               (sq_rubrica_cronograma,                sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real)
               (select sq_rubrica_cronograma.nextval, w_chave1, inicio,   fim,    valor_previsto,      valor_real 
                  from pj_rubrica_cronograma
                 where sq_projeto_rubrica = @w_sq_projeto_rubrica);

             set @w_chave1 = @@IDENTITY;
         End
         Close c_rubricas
         Deallocate c_rubricas      


   End Else If @p_operacao = 'A' Begin -- Altera��o
      -- Atualiza a tabela de solicita��es
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
          palavra_chave        = @p_palavra_chave
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

      If coalesce(@p_sq_tipo_pessoa,0) = 1 Begin
         update pj_projeto set preposto = null where sq_siw_solicitacao = @p_chave;
         delete pj_projeto_representante where sq_siw_solicitacao = @p_chave;
      End

      -- Atualiza os dados de uma a��o or�ament�ria, se for o caso
      If @p_sq_acao_ppa is not null or @p_sq_orprioridade is not null Begin
         -- Grava os dados complementares ao projeto, relativos � a��o or�ament�ria
         update or_acao set
            sq_acao_ppa      = @p_sq_acao_ppa,
            sq_orprioridade  = @p_sq_orprioridade
         where sq_siw_solicitacao = @p_chave;
         If @p_sq_acao_ppa is not null Begin
            -- Atualiza os dados da tabela de a��es do PPA
            update or_acao_ppa set
               selecionada_mpog      = @p_selecionada_mpog,
               selecionada_relevante = @p_selecionada_relev
            where sq_acao_ppa = @p_sq_acao_ppa;
         End
      End

   End Else If @p_operacao = 'E' Begin -- Exclus�o
      -- Verifica a quantidade de logs da solicita��o
      select @w_log_sol = count(*) from siw_solic_log  where sq_siw_solicitacao = @p_chave;
      select @w_log_esp = count(*) from pj_projeto_log where sq_siw_solicitacao = @p_chave;
      select @w_ativ    = count(*) from siw_solicitacao where sq_solic_pai      = @p_chave;

      -- Se n�o tem atividades vinculadas nem foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contr�rio, coloca a solicita��o como cancelada.
      If (@w_log_sol + @w_log_esp + @w_ativ) > 1 Begin
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

         -- Atualiza a situa��o do projeto
         update pj_projeto set concluida = 'S' where sq_siw_solicitacao = @p_chave;

         -- Recupera a chave que indica que a solicita��o est� cancelada
         select @w_chave = a.sq_siw_tramite from siw_tramite a where a.sq_menu = @p_menu and a.sigla = 'CA';

         -- Atualiza a situa��o da solicita��o
         update siw_solicitacao set sq_siw_tramite = @w_chave where sq_siw_solicitacao = @p_chave;

         -- Atualiza a a��o PPA e inicitiva priorit�ria quando a a��o for cancelada ou exclu�da
         update or_acao set sq_acao_ppa = null, sq_orprioridade = null where sq_siw_solicitacao = @p_chave;

         -- Atualiza eventuais atividades ligadas ao projeto
         declare @w_sq_siw_solicitacao numeric(18), @w_sq_siw_tramite numeric(18)

         Open c_atividades
         Fetch next from c_atividades into
              @w_sq_siw_solicitacao, @p_cadastrador, @w_sq_siw_tramite
         begin
             -- Insere log de cancelamento
             Insert Into siw_solic_log
                (sq_siw_solicitacao,   sq_pessoa,
                 sq_siw_tramite,            data,                 devolucao,
                 observacao
                )
             (select
                 @w_sq_siw_solicitacao, @p_cadastrador,
                 @w_sq_siw_tramite,          getdate(),              'N',
                 'Cancelamento'
                from siw_solicitacao a
               where a.sq_siw_solicitacao = @w_sq_siw_solicitacao
             );
             -- Atualiza a situa��o do projeto
             update gd_demanda set concluida = 'S' where sq_siw_solicitacao = @w_sq_siw_solicitacao;

             -- Recupera a chave que indica que a solicita��o est� cancelada
             select @w_chave = @w_sq_siw_tramite from siw_tramite a where a.sq_menu = @w_sq_menu and a.sigla = 'CA';

             -- Atualiza a situa��o da solicita��o
             update siw_solicitacao set sq_siw_tramite = @w_chave where sq_siw_solicitacao = @w_sq_siw_solicitacao;
         End
         Close c_atividades
         Deallocate c_atividades
         End Else
         -- Monta string com a chave dos arquivos ligados � solicita��o informada
         Open c_arquivos
         Fetch next from c_arquivos
         begin
            set @w_arq = @w_arq + @w_sq_siw_arquivo;
         End
         Close c_arquivos
         Deallocate c_arquivos

            set @w_arq = substring(@w_arq, 3, len(@w_arq));

         -- Monta string com a chave das coordenadas ligadas � solicita��o informada
         Open c_coordenadas
         Fetch next from c_coordenadas
         begin
            set @w_coord = @w_coord + @w_sq_siw_coordenada;
         End
         Close c_coordenadas
         Deallocate c_coordenadas
            set @w_coord = substring(@w_coord, 3, len(@w_coord));

         -- Remove os registros vinculados ao projeto
         delete siw_coordenada_solicitacao  where sq_siw_solicitacao = @p_chave;
         delete siw_coordenada              where sq_siw_coordenada in (@w_coord);

         delete siw_solic_arquivo           where sq_siw_solicitacao = @p_chave;
         delete siw_arquivo                 where sq_siw_arquivo     in (@w_arq);

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

         -- Remove o log da solicita��o
         delete siw_solic_log               where sq_siw_solicitacao = @p_chave;

         -- Remove o registro na tabela de solicita��es
         delete siw_solicitacao             where sq_siw_solicitacao = @p_chave;

   -- Devolve a chave
   If @p_chave is not null
      begin  set @p_chave_nova = @p_chave;
      end else begin   set @p_chave_nova = @w_chave;
   End

   If @p_operacao in ('I','A') and @p_objetivo is not null begin
      -- Para cada objetivo estrat�gico, grava um registro na tabela de vincula��es
      while ( len(@w_objetivo) > 0 ) begin
         set @w_item  = rtrim(ltrim(substring(@w_objetivo,1,charindex(',',@w_objetivo)-1)));
         If len(@w_item) > 0 begin
            insert into siw_solicitacao_objetivo(sq_siw_solicitacao, sq_plano, sq_peobjetivo) values (coalesce(@w_chave,@p_chave), @p_plano, cast(@w_item as numeric(18)));
         End ;
            set @w_objetivo = substring(@w_objetivo,charindex(',',@w_objetivo)+1,200);
        -- Exit when @w_objetivo is null;
      End ;
   End ;
   
   -- Devolve a chave
   If @p_chave is not null
      begin set @p_chave_nova = @p_chave;
      end Else begin set @p_chave_nova = @w_chave;
   End
End