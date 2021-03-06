alter procedure sp_PutDemandaEnvio
   (@p_menu          int,
    @p_chave         int,
    @p_pessoa        int,
    @p_tramite       int          =null,
    @p_novo_tramite  int,
    @p_devolucao     varchar(1),
    @p_observacao    varchar(2000),
    @p_destinatario  int,
    @p_despacho      varchar(2000),
    @p_caminho       varchar(255) =null,
    @p_tamanho       int          =null,
    @p_tipo          varchar(100) =null,
    @p_nome_original varchar(255) =null
   ) as
   declare @w_reg           int;
   declare @w_chave         int;
   declare @w_chave_dem     int;
   declare @w_chave_arq     int;

   set @w_reg =null;
   set @w_chave =null;
   set @w_chave_dem =null;
   set @w_chave_arq =null;
begin
   If @p_tramite <> @p_novo_tramite begin
      -- Recupera a próxima chave
      
      -- Se houve mudança de fase, grava o log
      Insert Into siw_solic_log 
         (sq_siw_solicitacao, sq_pessoa, 
          sq_siw_tramite,            data,               devolucao, 
          observacao
         )
      (Select 
          @p_chave,            @p_pessoa,
          @p_tramite,                 getdate(),           @p_devolucao,
          'Envio da fase "'+a.nome+'" '+
          ' para a fase "'+b.nome+'".'
         from siw_tramite a,
              siw_tramite b
        where a.sq_siw_tramite = @p_tramite
          and b.sq_siw_tramite = @p_novo_tramite
      );
      select @w_chave = @@IDENTITY;


      -- Atualiza a situação da demanda
      Update siw_solicitacao set
         sq_siw_tramite        = @p_novo_tramite
      Where sq_siw_solicitacao = @p_chave;
   end

   -- Verifica se o envio é na/para fase de cadastramento. Se for, atualiza o cadastrador.
   If @p_destinatario is not null begin

      -- Atualiza o responsável atual pela demanda
      Update siw_solicitacao set conclusao = null, executor = @p_destinatario Where sq_siw_solicitacao = @p_chave;

      -- Atualiza o situacao da demanda para não concluída
      Update gd_demanda set 
         concluida      = 'N',
         inicio_real    = null,
         fim_real       = null,
         data_conclusao = null, 
         nota_conclusao = null, 
         custo_real     = 0
      Where sq_siw_solicitacao = @p_chave;

      select @w_reg=count(*) from siw_tramite where sq_siw_tramite = coalesce(@p_novo_tramite,@p_tramite) and sigla='CI';
      If @w_reg > 0 begin
         Update siw_solicitacao set cadastrador = @p_destinatario Where sq_siw_solicitacao = @p_chave;
      end
   end

   -- Insere registro na tabela de encaminhamentos da demanda
   Insert into gd_demanda_log 
      (sq_siw_solicitacao, cadastrador, 
       destinatario,              data_inclusao,      observacao, 
       despacho,                  sq_siw_solic_log
      )
   Values (
       @p_chave,            @p_pessoa,
       @p_destinatario,            getdate(),            @p_observacao,
       @p_despacho,                @w_chave
    );
   -- Recupera a nova chave da tabela de encaminhamentos da demanda
      Select @w_chave_dem= @@IDENTITY

   -- Se foi informado um arquivo, grava.
   If @p_caminho is not null begin
       
      -- Insere registro em SIW_ARQUIVO
      insert into siw_arquivo (cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
      (select sq_pessoa_pai, @p_chave+' - Anexo', null, getdate(), 
              @p_tamanho,   @p_tipo,        @p_caminho, @p_nome_original
         from co_pessoa a
        where a.sq_pessoa = @p_pessoa
      );
      -- Recupera a próxima chave
      Select @w_chave_arq= @@IDENTITY
      
      -- Decide se o vínculo do arquivo será com o log da solicitação ou da demanda.
      If @p_tramite <> @p_novo_tramite begin
         -- Insere registro em SIW_SOLIC_LOG_ARQ
         insert into siw_solic_log_arq (sq_siw_solic_log, sq_siw_arquivo)
         values (@w_chave, @w_chave_arq);
      end Else
         -- Insere registro em GD_DEMANDA_LOG_ARQ
         insert into gd_demanda_log_arq (sq_demanda_log, sq_siw_arquivo)
         values (@w_chave_dem, @w_chave_arq);
      end
   end
