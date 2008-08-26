alter procedure dbo.sp_putTipoInteressado
   (@p_operacao  varchar(1),
    @p_servico      int        =null,
    @p_chave     int           =null,
    @p_nome      varchar(60)   =null,
    @p_ordem     varchar(4)    =null,
    @p_sigla     varchar(15)   =null,
    @p_descricao varchar(2000) =null,
    @p_ativo     varchar(1)    =null
   ) as
begin
   If @p_operacao = 'I' begin
      -- Insere registro
      insert into siw_tipo_interessado
        (sq_menu, nome,    ordem,    sigla,           descricao,    ativo)
      values
        (@p_servico, @p_nome, @p_ordem, upper(@p_sigla), @p_descricao, @p_ativo);
   end else if @p_operacao = 'A' begin
      -- Altera registro
      update siw_tipo_interessado
         set nome          = @p_nome,
             ordem         = @p_ordem,
             sigla         = upper(@p_sigla),
             descricao     = @p_descricao,
             ativo         = @p_ativo
       where sq_tipo_interessado = @p_chave;
   end else if @p_operacao = 'E' begin
      -- Exclui registro
      delete siw_tipo_interessado where sq_tipo_interessado = @p_chave;
   End
end