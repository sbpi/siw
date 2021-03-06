alter procedure dbo. SP_PutProjetoEnvio
   (@p_menu               int,
    @p_chave              int,
    @p_pessoa             int,
    @p_tramite            int,
    @p_novo_tramite       int,
    @p_devolucao          varchar(1),
    @p_observacao         varchar(2000),
    @p_destinatario       int          =null,
    @p_despacho           varchar(2000),
    @p_caminho            varchar(255) =null,
    @p_tamanho             int          =null,
    @p_tipo                varchar(60)  =null,
    @p_nome                varchar(255) =null
   ) as
begin
   Declare @w_reg   int
   Declare @w_chave int
   Set @w_chave = null

   If @p_tramite <> @p_novo_tramite Begin
      -- Se houve mudança de fase, grava o log
      Insert Into siw_solic_log 
         (sq_siw_solicitacao,        sq_pessoa, 
          sq_siw_tramite,            data,
          devolucao,                 observacao
         )
      (Select 
          @p_chave,                  @p_pessoa,
          @p_tramite,                getdate(),
          @p_devolucao,              'Envio da fase "'+a.nome+'" '+' para a fase "'+b.nome+'".'
         from siw_tramite a,
              siw_tramite b
        where a.sq_siw_tramite = @p_tramite
          and b.sq_siw_tramite = @p_novo_tramite
      )
    
      -- Recupera a chave
      select @w_chave = @@IDENTITY

      -- Atualiza a situação do projeto
      Update siw_solicitacao set
         sq_siw_tramite         = @p_novo_tramite
      Where sq_siw_solicitacao = @p_chave
   End

   -- Verifica se o envio é na/para fase de cadastramento. Se for, atualiza o cadastrador.
   If @p_destinatario is  not null Begin
      -- Atualiza o responsável atual pela demanda
      Update siw_solicitacao set executor    = @p_destinatario Where sq_siw_solicitacao = @p_chave

      select w_reg = count(*) from siw_tramite where sq_siw_tramite = IsNull(@p_novo_tramite,@p_tramite) and sigla='CI';
      If @w_reg > 0 Begin
         Update siw_solicitacao set
            cadastrador = @p_destinatario
          Where sq_siw_solicitacao = @p_chave
      End
   End
   
   -- Insere registro na tabela de encaminhamentos do projeto
   Insert into pj_projeto_log 
      (sq_siw_solicitacao,        cadastrador,        destinatario,
       data_inclusao,             observacao,         despacho,
       sq_siw_solic_log
      )
   values (
       @p_chave,                   @p_pessoa,         @p_destinatario,
       getdate(),                  @p_observacao,     @p_despacho,
       @w_chave
    )
end