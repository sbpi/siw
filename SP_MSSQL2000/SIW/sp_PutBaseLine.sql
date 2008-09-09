alter procedure dbo.sp_putBaseLine
   (@p_cliente             int,
    @p_chave               int,
    @p_pessoa              int           =null,
    @p_tramite             int           =null,
    @p_caminho             varchar(255)  =null,
    @p_tamanho             int           =null,
    @p_tipo                varchar(100)  =null,
    @p_nome                varchar(255)  =null
   ) as
   declare @w_chave_log numeric(18);
   declare @w_chave_arq numeric(18);
begin   
   -- Se foi informado um arquivo, grava.
   If @p_caminho is not null Begin

      Insert Into siw_solic_log 
         (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
          sq_siw_tramite,            data,               devolucao, 
          observacao
         )
      (select 
          @w_chave_log,        @p_chave,            @p_pessoa,
          @p_tramite,          getdate(),            'N',
          '*** Nova versão'
        
      );
      set @w_chave_log = @@IDENTITY
      
      -- Insere registro em siw_ARQUIVO
      insert into siw_arquivo 
      (       sq_siw_arquivo, cliente,    nome,               descricao, inclusao, 
              tamanho,        tipo,       caminho,            nome_original)
      (select @w_chave_arq,   @p_cliente, 'Arquivo de dados', null,      getdate(), 
              @p_tamanho,     @p_tipo,    @p_caminho,         @p_nome
       
      );

      -- Recupera a próxima chave
      set @w_chave_arq = @@IDENTITY
       
      -- Insere registro em siw_solic_log_arq
      insert into siw_solic_log_arq (sq_siw_solic_log, sq_siw_arquivo)
      values (@w_chave_log, @w_chave_arq);
   End
end