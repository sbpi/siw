alter procedure sp_putIndicador
   (@p_operacao          varchar(1),
    @p_cliente           int       =null,
    @p_chave             int       =null,
    @p_nome              varchar(60) =null,
    @p_sigla             varchar(15) =null,
    @p_tipo_indicador    int       =null,
    @p_unidade_medida    int       =null,
    @p_descricao         varchar(2000) =null,
    @p_forma_afericao    varchar(2000) =null,
    @p_fonte_comprovacao varchar(2000) =null,
    @p_ciclo_afericao    varchar(2000) =null,
    @p_vincula_meta      varchar(1) =null,
    @p_exibe_mesa        varchar(1) =null,
    @p_ativo             varchar(1) =null
   ) as
begin
   If @p_operacao = 'I' begin      
      -- Insere registro
      insert into eo_indicador
        (cliente,   sq_tipo_indicador, sq_unidade_medida, nome,   sigla,   descricao,   forma_afericao,   fonte_comprovacao,   
         ciclo_afericao,   ativo,     vincula_meta,      exibe_mesa)
      values
        (@p_cliente, @p_tipo_indicador,  @p_unidade_medida,  @p_nome, @p_sigla, @p_descricao, @p_forma_afericao, @p_fonte_comprovacao, 
         @p_ciclo_afericao, @p_ativo,   @p_vincula_meta,    @p_exibe_mesa);
   end else if @p_operacao = 'A' begin
      update eo_indicador
         set sq_tipo_indicador = @p_tipo_indicador,
             sq_unidade_medida = @p_unidade_medida,
             nome              = @p_nome,
             sigla             = @p_sigla,
             descricao         = @p_descricao,
             forma_afericao    = @p_forma_afericao,
             fonte_comprovacao = @p_fonte_comprovacao,
             ciclo_afericao    = @p_ciclo_afericao,
             vincula_meta      = @p_vincula_meta,
             exibe_mesa        = @p_exibe_mesa,
             ativo             = @p_ativo
       where sq_eoindicador = @p_chave;
   end else if @p_operacao = 'E' begin
      -- Exclui registro
      delete eo_indicador where sq_eoindicador = @p_chave;
   end
end