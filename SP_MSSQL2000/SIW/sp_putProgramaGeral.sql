    alter  procedure sp_putProgramaGeral
   (@p_operacao            varchar(1),
    @p_chave               int             =  null,
    @p_copia               int             =  null,
    @p_menu                int,
    @p_plano               int             = null,
    @p_objetivo            varchar(2000)   = null,
    @p_codigo              varchar(20)     = null,
    @p_titulo              varchar(100)    = null,
    @p_unidade             int =  null,
    @p_solicitante         int =  null,
    @p_unid_resp           int =  null,
    @p_horizonte           int =  null,
    @p_natureza            int =  null,
    @p_inicio              datetime         = null,
    @p_fim                 datetime         = null,
    @p_parcerias           varchar(90)      = null,
    @p_ln_programa         varchar(120)     = null,
    @p_cadastrador         int =  null,
    @p_executor            int =  null,
    @p_solic_pai           int =  null,
    @p_valor               int =  null,
    @p_data_hora           varchar(1) = null, --varchar
    @p_aviso               varchar(1) = null, --varchar
    @p_dias                int =  null,
    @p_chave_nova          numeric(18) --out number
   ) as
begin
   
   declare @w_chave     numeric(18);
   declare @w_log_sol   numeric(18);
   declare @w_log_esp   numeric(18);
   declare @w_ativ      numeric(18);
   declare @w_item      varchar(18);

   declare @w_objetivo  varchar(200);
    set @w_objetivo = @p_objetivo +',';

   declare @w_arq varchar;
   set @w_arq = ', ';

   --declare cursor c_recursos is
   --  select * from pj_projeto_recurso where sq_siw_solicitacao = @p_copia;
     
   --cursor c_atividades is
   --   select * from siw_solicitacao t where t.sq_solic_pai = @p_chave;
    
   --cursor c_arquivos is
   --   select sq_siw_arquivo from siw_solic_arquivo where sq_siw_solicitacao = @p_chave;
    declare @sq_siw_arquivo numeric(18);

    Declare c_arquivos cursor for
        select sq_siw_arquivo from siw_solic_arquivo where sq_siw_solicitacao = @p_chave;




   If @p_operacao <> 'I' begin
      -- Remove as vinculações existentes para a solicitação
      delete siw_solicitacao_objetivo where sq_siw_solicitacao = coalesce(@w_chave, @p_chave);
   End;

   If @p_operacao = 'I' begin -- Inclusão
      -- Recupera a próxima chave
      --select sq_siw_solicitacao.nextval into @w_chave ;
       
      -- Insere registro em SIW_SOLICITACAO
      insert into siw_solicitacao (
         sq_menu,            sq_siw_tramite,      solicitante, 
         cadastrador,        executor,           inicio,              fim,
         inclusao,           ultima_alteracao,   data_hora,           sq_unidade,
         sq_solic_pai,       sq_cidade_origem,   palavra_chave,       sq_plano,
         valor,              titulo,             codigo_interno)
      (select 
         @p_menu,             a.sq_siw_tramite,    @p_solicitante,
         @p_cadastrador,      @p_executor,         @p_inicio,            @p_fim,
         getdate(),            getdate(),            @p_data_hora,         @p_unidade,
         @p_solic_pai,        c.sq_cidade_padrao, @p_parcerias,         @p_plano,
         @p_valor,            @p_titulo,           @p_codigo
         from siw_tramite              a
              inner   join siw_menu    b on (a.sq_menu   = b.sq_menu)
                inner join siw_cliente c on (b.sq_pessoa = c.sq_pessoa)
        where a.sq_menu = @p_menu
          and a.sigla   = 'CI'
      );
      set @w_chave = @@IDENTITY;
      -- Insere registro em pe_programa

      insert into pe_programa
         ( sq_siw_solicitacao,  cliente,          sq_pehorizonte,    sq_penatureza, 
           sq_unidade_resp,     ln_programa,      aviso_prox_conc,   dias_aviso)
      (select
           @w_chave,             a.sq_pessoa,      @p_horizonte,       @p_natureza,
           @p_unid_resp,         @p_ln_programa,    @p_aviso,           @p_dias
         from siw_menu a
        where a.sq_menu = @p_menu
      );
      
      -- Insere log da solicitação
      Insert Into siw_solic_log 
         (          sq_siw_solicitacao, sq_pessoa, 
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
           
      -- Se o programa foi copiado de outro, grava os dados complementares
      If @p_copia is not null begin
         -- Insere registro na tabela de interessados
         insert into siw_solicitacao_interessado
           (          sq_siw_solicitacao, sq_pessoa,   sq_tipo_interessado)
         (select
              @w_chave,            a.sq_pessoa, a.sq_tipo_interessado
            from siw_solicitacao_interessado a
           where a.sq_siw_solicitacao = @p_copia
         );
      End ;
   end else if  @p_operacao = 'A' begin -- Alteração
      -- Atualiza a tabela de solicitações
      Update siw_solicitacao set
          sq_solic_pai     = @p_solic_pai,
          sq_plano         = @p_plano,
          solicitante      = @p_solicitante,
          sq_unidade       = @p_unidade,
          inicio           = @p_inicio,
          fim              = @p_fim,
          valor            = @p_valor,
          ultima_alteracao = getdate(),
          codigo_interno   = @p_codigo,
          titulo           = rtrim(ltrim(@p_titulo)),
          palavra_chave    = @p_parcerias
      where sq_siw_solicitacao = @p_chave;
      
      -- Atualiza a tabela de projetos
      Update pe_programa set
          sq_unidade_resp  = @p_unid_resp,
          ln_programa      = @p_ln_programa,
          aviso_prox_conc  = @p_aviso,
          dias_aviso       = @p_dias
      where sq_siw_solicitacao = @p_chave;

   end else if  @p_operacao = 'E' begin -- Exclusão
      -- Verifica a quantidade de logs da solicitação
      select @w_log_sol  = count(*)   from siw_solic_log  where sq_siw_solicitacao = @p_chave;
      select @w_log_esp  = count(*)   from pe_programa_log where sq_siw_solicitacao = @p_chave;
      select @w_ativ     = count(*)   from siw_solicitacao where sq_solic_pai      = @p_chave;
      
      -- Se não tem projetos vinculados nem foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contrário, coloca a solicitação como cancelada.
      If (@w_log_sol + @w_log_esp + @w_ativ) > 1 begin
         -- Insere log de cancelamento
         Insert Into siw_solic_log 
            (          sq_siw_solicitacao,   sq_pessoa, 
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
         
         -- Recupera a chave que indica que a solicitação está cancelada
         select @w_chave = a.sq_siw_tramite   from siw_tramite a where a.sq_menu = @p_menu and a.sigla = 'CA';
         
         -- Atualiza a situação da solicitação
         update siw_solicitacao set sq_siw_tramite = @w_chave where sq_siw_solicitacao = @p_chave;


        
      end Else begin
         -- Monta string com a chave dos arquivos ligados à solicitação informada

        -- for crec in c_arquivos loop
         --  set  @w_arq = @w_arq + crec.sq_siw_arquivo;
        -- end loop;

             Open c_arquivos
             Fetch Next from c_arquivos into @sq_siw_arquivo
             While @@Fetch_Status = 0 Begin

                 set  @w_arq = @w_arq + @sq_siw_arquivo;
                Fetch Next from c_menu into @sq_siw_arquivo

             End
             Close c_arquivos
             Deallocate c_arquivos

           set @w_arq = substring(@w_arq, 3, len(@w_arq));
         
         -- Remove os registros vinculados ao programa
         delete siw_solic_arquivo where sq_siw_solicitacao   = @p_chave;
         delete siw_arquivo       where sq_siw_arquivo     in (@w_arq);
         
         delete siw_solic_indicador         where sq_siw_solicitacao = @p_chave;
         delete siw_meta_cronograma         where sq_solic_meta in (select sq_solic_meta from siw_solic_meta where sq_siw_solicitacao = @p_chave);
         delete siw_solic_meta              where sq_siw_solicitacao = @p_chave;
         delete siw_solicitacao_interessado where sq_siw_solicitacao = @p_chave;

         -- Remove o registro na tabela de programas
         delete pe_programa where sq_siw_solicitacao = @p_chave;
            
         -- Remove o log da solicitação
         delete siw_solic_log where sq_siw_solicitacao = @p_chave;

         -- Remove o registro na tabela de solicitações
         delete siw_solicitacao where sq_siw_solicitacao = @p_chave;
      End ;
   End ;
   
   If @p_operacao in ('I','A') and @p_objetivo is not null begin
      -- Para cada objetivo estratégico, grava um registro na tabela de vinculações
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
      begin set  @p_chave_nova = @p_chave;
      end Else set @p_chave_nova = @w_chave;
   End ;