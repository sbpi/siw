alter procedure dbo.SP_PutDemandaGeral(
    @p_operacao            varchar(1),
    @p_chave               int    = null,
    @p_copia               int    = null,
    @p_menu                int     = null,
    @p_unidade             int     = null,
    @p_solicitante         int     = null,
    @p_proponente          varchar(90)   = null,
    @p_cadastrador         int       = null,
    @p_executor            int       = null,
    @p_sqcc                int       = null,
    @p_descricao           varchar(2000) = null,
    @p_justificativa       varchar(2000) = null,
    @p_ordem               int       = null,
    @p_inicio              datetime  = null,
    @p_fim                 datetime  = null,
    @p_valor               numeric(18,2)       = null,
    @p_data_hora           varchar(1) = null,
    @p_unid_resp           int       = null,
    @p_assunto             varchar(2000) = null,
    @p_prioridade          int       = null,
    @p_aviso               varchar(1) = null,
    @p_dias                int       = null,
    @p_cidade              int       = null,
    @p_palavra_chave       varchar(90) = null,
    @p_inicio_real         datetime  = null,
    @p_fim_real            datetime  = null,
    @p_concluida           varchar(1) = null,
    @p_data_conclusao      datetime  = null,
    @p_nota_conclusao      varchar(2000) = null,
    @p_custo_real          numeric(18,2)       = null,
    @p_opiniao             int       = null,
    @p_projeto             int       = null,
    @p_atividade           int       = null,
    @p_projeto_ant         int       = null,
    @p_atividade_ant       int       = null,
    @p_restricao           int       = null,
    @p_demanda_tipo        int       = null,
    @p_recebimento         datetime  = null,
    @p_limite_conclusao    datetime  = null,
    @p_responsavel         int       = null,
    @p_chave_nova          int output
   ) as
begin
   Declare @w_arq          varchar(4000);
   Set     @w_arq = ', ';
   Declare @w_chave        numeric(18);
   Declare @w_log_sol      numeric(18);
   Declare @w_log_esp      numeric(18);
   Declare @sq_siw_arquivo numeric(18);
   Declare @w_flag         int;

   Declare c_arquivos cursor for
      select sq_siw_arquivo from siw_solic_arquivo where sq_siw_solicitacao = @p_chave;

   If @p_operacao = 'I' Begin -- Inclus�o
      -- Recupera a pr�xima chave
    	
       
      -- Insere registro em SIW_SOLICITACAO
      insert into siw_solicitacao (
         sq_menu,       sq_siw_tramite,      solicitante, 
         cadastrador,        executor,      descricao,           justificativa, 
         inicio,             fim,           inclusao,            ultima_alteracao, 
         conclusao,          valor,         opiniao,             data_hora, 
         sq_unidade,         sq_cc,         sq_cidade_origem,    palavra_chave,
         sq_solic_pai)
      (select 
         @p_menu,        a.sq_siw_tramite,    @p_solicitante,
         @p_cadastrador,      @p_executor,    @p_descricao,         @p_justificativa,
         @p_inicio,           @p_fim,         getdate(),             getdate(),
         null,               @p_valor,       null,                @p_data_hora,
         @p_unidade,          @p_sqcc,        @p_cidade,            @p_palavra_chave,
         @p_projeto
         from siw_tramite a
        where a.sq_menu = @p_menu
          and a.sigla   = 'CI'
      );

      Set @w_Chave = @@Identity;
      
      -- Insere registro em GD_DEMANDA
      Insert into gd_demanda
         ( sq_siw_solicitacao,  sq_unidade_resp, assunto,           prioridade,
           aviso_prox_conc,     dias_aviso,      inicio_real,       fim_real,
           concluida,           data_conclusao,  nota_conclusao,    custo_real,
           proponente,          ordem,           sq_demanda_pai,    sq_siw_restricao,
           sq_demanda_tipo,     recebimento,     limite_conclusao,  responsavel
         )
      (select
           @w_chave,             @p_unid_resp,     @p_assunto,         @p_prioridade,
           @p_aviso,             @p_dias,          null,              null,
           'N',                 null,            null,              0,
           @p_proponente,        @p_ordem,         @p_atividade_ant,   @p_restricao,
           @p_demanda_tipo,      @p_recebimento,   @p_limite_conclusao, @p_responsavel
      );

      -- Insere log da solicita��o
      Insert Into siw_solic_log 
         (sq_siw_solicitacao, sq_pessoa, 
          sq_siw_tramite,            data,               devolucao, 
          observacao
         )
      (select 
          @w_chave,            @p_cadastrador,
          a.sq_siw_tramite,          getdate(),            'N',
          'Cadastramento inicial'
         from siw_tramite a
        where a.sq_menu = @p_menu
          and a.sigla   = 'CI'
      );
           
      -- Se receber @p_atividade, grava na tabela de atividades de projeto
      If @p_atividade is not null Begin
         Insert Into pj_etapa_demanda 
                (sq_projeto_etapa, sq_siw_solicitacao)
         Values (@p_atividade,      @w_chave);
      End

      -- Se a demanda foi copiada de outra, grava os dados complementares
      If @p_copia is not null Begin
         -- Insere registro na tabela de interessados
         Insert Into gd_demanda_interes 
            ( sq_pessoa,   sq_siw_solicitacao,   tipo_visao,    envia_email )
         (Select
              a.sq_pessoa, @w_chave,              a.tipo_visao,  a.envia_email 
           from gd_demanda_interes a
          where a.sq_siw_solicitacao = @p_copia
         );
         -- Insere registro na tabela de �reas envolvidas
         Insert Into gd_demanda_envolv 
            ( sq_unidade,   sq_siw_solicitacao,   papel )
         (Select
              a.sq_unidade, @w_chave,              a.papel
            from gd_demanda_envolv a
           where a.sq_siw_solicitacao = @p_copia
          );
      End
   End Else If @p_operacao = 'A' Begin -- Altera��o
      -- Atualiza a tabela de solicita��es
      Update siw_solicitacao set
          sq_solic_pai     = @p_projeto,
          sq_cc            = @p_sqcc,
          solicitante      = @p_solicitante,
          executor         = @p_executor,
          descricao        = rtrim(ltrim(@p_descricao)), 
          justificativa    = rtrim(ltrim(@p_justificativa)),
          inicio           = @p_inicio,
          fim              = @p_fim,
          ultima_alteracao = getdate(),
          valor            = @p_valor,
          sq_cidade_origem = @p_cidade,
          palavra_chave    = @p_palavra_chave
      where sq_siw_solicitacao = @p_chave;
      
      -- Atualiza a tabela de demandas
      Update gd_demanda set
          sq_demanda_pai   = @p_atividade_ant,
          sq_siw_restricao = @p_restricao,
          sq_unidade_resp  = @p_unid_resp,
          proponente       = @p_proponente,
          assunto          = rtrim(ltrim(@p_assunto)),
          prioridade       = @p_prioridade,
          aviso_prox_conc  = @p_aviso,
          dias_aviso       = @p_dias,
          inicio_real      = @p_inicio_real,
          ordem            = @p_ordem,
          sq_demanda_tipo  = @p_demanda_tipo,
          recebimento      = @p_recebimento,
          limite_conclusao = @p_limite_conclusao
      where sq_siw_solicitacao = @p_chave;

      delete pj_etapa_demanda where sq_siw_solicitacao = @p_chave;
      If @p_atividade is not null Begin
         -- Cria a vincula��o com os novos dados
         Insert Into pj_etapa_demanda 
                (sq_projeto_etapa, sq_siw_solicitacao)
          Values (@p_atividade,     @p_chave);
      End
   End Else If @p_operacao = 'E' Begin -- Exclus�o
      -- Verifica a quantidade de logs da solicita��o
      select @w_log_sol = count(*) from siw_solic_log  where sq_siw_solicitacao = @p_chave;
      select @w_log_esp = count(*) from gd_demanda_log where sq_siw_solicitacao = @p_chave;
      
      -- Se n�o foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contr�rio, coloca a solicita��o como cancelada.
      If (@w_log_sol + @w_log_esp) > 1 Begin
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
         
         -- Atualiza a situa��o da demanda
         update gd_demanda set concluida = 'S' where sq_siw_solicitacao = @p_chave;

         -- Recupera a chave que indica que a solicita��o est� cancelada
         select @w_chave = a.sq_siw_tramite from siw_tramite a where a.sq_menu = @p_menu and a.sigla = 'CA';
         
         -- Atualiza a situa��o da solicita��o
         update siw_solicitacao set sq_siw_tramite = @w_chave where sq_siw_solicitacao = @p_chave;
      End Else Begin
         -- Monta string com a chave dos arquivos ligados � solicita��o informada
         --for crec in c_arquivos loop
         --   @w_arq := @w_arq || crec.sq_siw_arquivo;
         --end loop;
             Set @w_flag = 0;
             Open c_arquivos
             Fetch Next from c_arquivos into @sq_siw_arquivo
             While @@Fetch_Status = 0 Begin
			     Set @w_flag = 1;
                 Set  @w_arq = @w_arq + @sq_siw_arquivo;
                --Fetch Next from c_menu into @sq_siw_arquivo
	             Fetch Next from c_arquivos into @sq_siw_arquivo
             End
             Close c_arquivos
             Deallocate c_arquivos

         -- Remove os registros vinculados � demanda
         delete siw_solic_arquivo where sq_siw_solicitacao = @p_chave;
         If @w_flag = 1 Begin
			Set @w_arq = substring(@w_arq, 3, len(@w_arq));
            delete siw_arquivo       where sq_siw_arquivo     in (@w_arq);
         End
         
         delete gd_demanda_envolv  where sq_siw_solicitacao = @p_chave;
         delete gd_demanda_interes where sq_siw_solicitacao = @p_chave;
         delete pj_etapa_demanda   where sq_siw_solicitacao = @p_chave;

         -- Remove o registro na tabela de demandas
         delete gd_demanda where sq_siw_solicitacao = @p_chave;
            
         -- Remove o log da solicita��o
         delete siw_solic_log where sq_siw_solicitacao = @p_chave;

         -- Remove o registro na tabela de solicita��es
         delete siw_solicitacao where sq_siw_solicitacao = @p_chave;
      End
   End Else If @p_operacao = 'C' Begin -- Conclus�o
      -- Atualiza a tabela de solicita��es com os dados da conclus�o
      Update siw_solicitacao set
          conclusao        = @p_data_conclusao,
          ultima_alteracao = getdate(),
          sq_siw_tramite   = (select sq_siw_tramite from siw_tramite where sq_menu = @p_menu and sigla='AT')
      where sq_siw_solicitacao = @p_chave;
      
      -- Atualiza a tabela de demandas com os dados da conclus�o
      Update gd_demanda set
          fim_real        = @p_fim_real,
          concluida       = @p_concluida,
          data_conclusao  = @p_data_conclusao,
          nota_conclusao  = rtrim(ltrim(@p_nota_conclusao)),
          custo_real      = @p_custo_real
      where sq_siw_solicitacao = @p_chave;
   End Else If @p_operacao = 'O' Begin -- Opini�o
      -- Atualiza a tabela de solicita��es com a opini�o do solicitante
      Update siw_solicitacao set
          opiniao         = @p_opiniao
      where sq_siw_solicitacao = @p_chave;
   End Else If @p_operacao = 'F' Begin -- Altera��o
      -- Atualiza a tabela de solicita��es
      Update siw_solicitacao set
          sq_solic_pai     = @p_projeto,
          sq_cc            = @p_sqcc,
          ultima_alteracao = getdate(),
          palavra_chave    = @p_palavra_chave
      where sq_siw_solicitacao = @p_chave;
      
      -- Atualiza a tabela de demandas
      Update gd_demanda set
          sq_unidade_resp  = @p_unid_resp,
          prioridade       = @p_prioridade,
          sq_demanda_tipo  = @p_demanda_tipo,
          responsavel      = @p_responsavel
      where sq_siw_solicitacao = @p_chave;
   End Else If @p_operacao = 'D' Begin -- Altera��o
      -- Atualiza a tabela de solicita��es
      Update siw_solicitacao set
          inicio           = @p_inicio,
          fim              = @p_fim,
          valor            = @p_valor,          
          ultima_alteracao = getdate()
      where sq_siw_solicitacao = @p_chave;
   End
   -- Devolve a chave
   If @p_chave is not null
      Begin Set @p_chave_nova = @p_chave;
      End Else Begin Set @p_chave_nova = @w_chave;
   End
end
