alter procedure SP_putIndicador_Afericao
   (@p_operacao     varchar(1),
    @p_usuario      int,
    @p_chave        int           = null,
    @p_indicador    int           = null,
    @p_afericao     datetime      = null,
    @p_inicio       datetime      = null,
    @p_fim          datetime      = null,
    @p_pais         int           = null,
    @p_regiao       int           = null,
    @p_uf           varchar(2)    = null,
    @p_cidade       int           = null,
    @p_base         int           = null,
    @p_fonte        varchar(60)   = null,
    @p_valor        numeric(18,4) = null,
    @p_previsao     varchar(1)    = null,
    @p_observacao   varchar(255)  = null
   ) as
   Declare @w_chave  numeric(18);
   Declare @w_regiao numeric(18);
begin
   -- Garante a gravação da região quando a UF for informada
   If @p_pais is not null and @p_uf is not null and @p_regiao is null Begin
    select @w_regiao = sq_regiao from co_uf where sq_pais = @p_pais and co_uf = @p_uf;
   End Else Begin
     Set @w_regiao = @p_regiao;
   End
   
   If @p_operacao = 'I' or @p_operacao = 'C' Begin
      -- Gera a nova chave do registro, a partir da sequence
--      Set @w_chave sq_eoindicador_afericao.nextval into @w_chave from dual;
	 Select @w_chave = @@IDENTITY

      -- Insere registro
      insert into eo_indicador_afericao
        (sq_eoindicador,  data_afericao, referencia_inicio, referencia_fim,   sq_pais,          sq_regiao,  co_uf,   sq_cidade, 
         cadastrador,             base_geografica, fonte,         valor,             inclusao,         ultima_alteracao, previsao,   observacao)
      values
        (@p_indicador,     convert(datetime,@p_afericao),    convert(datetime,@p_inicio),          convert(datetime,@p_fim),            @p_pais,           @w_regiao,   @p_uf,    @p_cidade,
         @p_usuario,               @p_base,          @p_fonte,       @p_valor,           getdate(),          null,             @p_previsao, @p_observacao);
   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update eo_indicador_afericao
         set sq_eoindicador    = @p_indicador,
             data_afericao     = convert(datetime, @p_afericao),
             referencia_inicio = convert(datetime, @p_inicio),
             referencia_fim    = convert(datetime, @p_fim),
             sq_pais           = @p_pais,
             sq_regiao         = @w_regiao,
             co_uf             = @p_uf,
             sq_cidade         = @p_cidade,
             cadastrador       = @p_usuario,
             base_geografica   = @p_base,
             fonte             = @p_fonte,
             valor             = @p_valor,
             ultima_alteracao  = getdate(),
             previsao          = @p_previsao,
             observacao        = @p_observacao
       where sq_eoindicador_afericao = @p_chave;
   End Else If @p_operacao = 'E' Begin
      -- Recupera o período do registro
      delete eo_indicador_afericao where sq_eoindicador_afericao = @p_chave;
   End
end
