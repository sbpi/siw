alter procedure sp_putSolicRestricao
   (@p_operacao              varchar(1),
    @p_chave                 int   =null,
    @p_chave_aux             int   =null,
    @p_pessoa                int   =null,
    @p_pessoa_atualizacao    int   =null,
    @p_tipo_restricao        int   =null,
    @p_risco                 varchar(1) =null,
    @p_problema              varchar(1) =null,
    @p_descricao             varchar(2000) =null,
    @p_probabilidade         int   =null,
    @p_impacto               int   =null,
    @p_criticidade           int   =null,
    @p_estrategia            varchar(1) =null,
    @p_acao_resposta         varchar(2000) =null,
    @p_fase_atual            varchar(1) =null,
    @p_data_situacao         datetime    =null,
    @p_situacao_atual        varchar(2000) =null
   ) as
   declare @w_chave_aux  numeric(18);

Begin
   -- informada
   If @p_operacao = 'I'  Begin

      -- Insere registro
      insert into siw_restricao
        (sq_siw_solicitacao, sq_pessoa,      sq_pessoa_atualizacao,    sq_tipo_restricao,   risco,              problema,         descricao,         probabilidade, 
         impacto,            criticidade,    estrategia,               acao_resposta,       fase_atual,         data_situacao,    situacao_atual,    ultima_atualizacao)

      values
        (@p_chave,           @p_pessoa,      @p_pessoa_atualizacao,    @p_tipo_restricao,   @p_risco,           @p_problema,      @p_descricao,       @p_probabilidade, 
         @p_impacto,         @p_criticidade, @p_estrategia,            @p_acao_resposta,    @p_fase_atual,      @p_data_situacao, @p_situacao_atual,  getdate());

      set @w_chave_aux = @@IDENTITY;

   End Else If @p_operacao = 'A' Begin 
      -- Altera registro
      update siw_restricao
         set sq_pessoa             = @p_pessoa, 
             sq_pessoa_atualizacao = @p_pessoa_atualizacao,     
             sq_tipo_restricao     = @p_tipo_restricao,
             risco                 = @p_risco,
             problema              = @p_problema,
             descricao             = @p_descricao,
             probabilidade         = @p_probabilidade,
             impacto               = @p_impacto,
             criticidade           = @p_criticidade,
             estrategia            = @p_estrategia ,
             acao_resposta         = @p_acao_resposta ,
             fase_atual            = @p_fase_atual,
             data_situacao         = @p_data_situacao,
             situacao_atual        = @p_situacao_atual,             
             ultima_atualizacao    = getdate()
       where sq_siw_restricao  = @p_chave_aux;
   End Else If @p_operacao = 'E' Begin
      -- Exclui o registro de siw_restricao_etapa
      delete siw_restricao_etapa where sq_siw_restricao = @p_chave_aux;
      -- Recupera o período do registro
      delete siw_restricao where sq_siw_restricao = @p_chave_aux;
   End
end