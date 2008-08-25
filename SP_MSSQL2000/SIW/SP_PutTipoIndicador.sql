alter procedure sp_putTipoIndicador
   (@p_operacao   varchar(1),
    @p_cliente    varchar(32) = null,
    @p_chave      int       = null,
    @p_nome       varchar(60) = null,
    @p_ativo      varchar(1) = null
   ) as
begin
   If @p_operacao in ('I','C') Begin
      -- Insere registro
      insert into eo_tipo_indicador
        (cliente,   nome,   ativo)
      values
        (@p_cliente, @p_nome, @p_ativo);
   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update eo_tipo_indicador
         set nome            = @p_nome,
             ativo           = @p_ativo
       where sq_tipo_indicador = @p_chave;
   End Else If @p_operacao = 'E' Begin
      -- Exclui registro
      delete eo_tipo_indicador where sq_tipo_indicador = @p_chave;
   End
end
