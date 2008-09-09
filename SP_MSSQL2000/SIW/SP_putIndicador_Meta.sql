alter procedure sp_putIndicador_meta
   (@p_operacao          varchar(1),
    @p_usuario           int   =null,
    @p_chave             int   =null,
    @p_chave_aux         int   =null,
    @p_plano             int   =null,
    @p_indicador         int   =null,
    @p_titulo            varchar(100) =null,
    @p_descricao         varchar(2000) =null,
    @p_ordem             int   =null,
    @p_inicio            datetime     =null,
    @p_fim               datetime     =null,
    @p_base              int   =null,
    @p_pais              int   =null,
    @p_regiao            int   =null,
    @p_uf                varchar(2) =null,
    @p_cidade            int   =null,
    @p_valor_inicial     numeric(18,4) =null,
    @p_quantidade        numeric(18,4) =null,
    @p_cumulativa        varchar(1) =null,
    @p_pessoa            int   =null,
    @p_unidade           int   =null,
    @p_situacao_atual    varchar(4000) =null,
    @p_exequivel         varchar(1) =null,
    @p_justificativa     varchar(1000) =null,
    @p_outras_medidas    varchar(1000) =null
   ) as
   declare @w_chave_aux  numeric(18);
   declare @w_regiao numeric(18);
begin
   -- Garante a gravação da região quando a UF for informada
   If @p_pais is not null and @p_uf is not null and @p_regiao is null Begin
     select sq_regiao into w_regiao from co_uf where sq_pais = @p_pais and co_uf = @p_uf;
   end Else begin
     set @w_regiao = @p_regiao;
   End
   
   If @p_operacao = 'I'  Begin

      -- Insere registro
      insert into siw_solic_meta
        (sq_siw_solicitacao,  sq_eoindicador,    sq_pessoa,    titulo,       descricao,     ordem,          inicio,       fim, 
         base_geografica,   sq_pais,             sq_regiao,         co_uf,        sq_cidade,    quantidade,    cumulativa,     cadastrador,  sq_unidade,
         valor_inicial,     sq_plano,            ultima_alteracao)
      values
        (@p_chave,             @p_indicador,       @p_pessoa,     @p_titulo,     @p_descricao,   @p_ordem,        @p_inicio,     @p_fim, 
         @p_base,            @p_pais,              @w_regiao,          @p_uf,         @p_cidade,     @p_quantidade,  @p_cumulativa,   @p_usuario,    @p_unidade,
         @p_valor_inicial,   @p_plano,             getdate());

   set @w_chave_aux = @@IDENTITY
   
   End Else If @p_operacao = 'A' Begin 
      If @p_plano is null Begin
         -- Tratamento para metas ligadas a solicitações
         If @p_exequivel is null Begin
            -- Altera as informações de cadastro da meta
            update siw_solic_meta
               set sq_eoindicador    = @p_indicador,
                   sq_pessoa         = @p_pessoa,
                   titulo            = @p_titulo,     
                   descricao         = @p_descricao,
                   ordem             = @p_ordem,
                   inicio            = @p_inicio,
                   fim               = @p_fim,
                   sq_pais           = @p_pais,
                   sq_regiao         = @w_regiao,
                   co_uf             = @p_uf,
                   sq_cidade         = @p_cidade,
                   base_geografica   = @p_base,
                   valor_inicial     = @p_valor_inicial,
                   quantidade        = @p_quantidade,
                   cumulativa        = @p_cumulativa,
                   cadastrador       = @p_usuario,             
                   sq_unidade        = @p_unidade,
                   ultima_alteracao  = getdate()
             where sq_solic_meta = @p_chave_aux;
         end Else
            -- Altera as informações de monitoramento da meta
            update siw_solic_meta
               set situacao_atual            = @p_situacao_atual,
                   exequivel                 = @p_exequivel,
                   justificativa_inexequivel = @p_justificativa,
                   outras_medidas            = @p_outras_medidas,
                   cadastrador               = @p_usuario,
                   ultima_alteracao          = getdate()
             where sq_solic_meta = @p_chave_aux;
         End
      Else begin
         -- Tratamento para metas ligadas a planos estratégicos
            update siw_solic_meta
               set sq_eoindicador            = @p_indicador,
                   sq_pessoa                 = @p_pessoa,
                   titulo                    = @p_titulo,     
                   descricao                 = @p_descricao,
                   ordem                     = @p_ordem,
                   inicio                    = @p_inicio,
                   fim                       = @p_fim,
                   sq_pais                   = @p_pais,
                   sq_regiao                 = @w_regiao,
                   co_uf                     = @p_uf,
                   sq_cidade                 = @p_cidade,
                   base_geografica           = @p_base,
                   valor_inicial             = @p_valor_inicial,
                   quantidade                = @p_quantidade,
                   cumulativa                = @p_cumulativa,
                   sq_unidade                = @p_unidade,
                   situacao_atual            = @p_situacao_atual,
                   exequivel                 = @p_exequivel,
                   justificativa_inexequivel = @p_justificativa,
                   outras_medidas            = @p_outras_medidas,
                   cadastrador               = @p_usuario,
                   ultima_alteracao          = getdate()
             where sq_solic_meta = @p_chave_aux;
      End
   End Else If @p_operacao = 'E' Begin
      -- Remove o cronograma da meta
      delete siw_meta_cronograma where sq_solic_meta = @p_chave_aux;
      
      -- Remove o registro da meta
      delete siw_solic_meta      where sq_solic_meta = @p_chave_aux;
   End
end