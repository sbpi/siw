alter procedure dbo.sp_putUnidadeMedida
   (@p_operacao   varchar(1),
    @p_cliente    int = null,
    @p_chave      int       = null,
    @p_nome       varchar(60) = null,
    @p_sigla      varchar(15) = null,
    @p_ativo      varchar(1) = null
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into co_unidade_medida
        (cliente,   nome,   sigla,          ativo)
      values
        (@p_cliente, @p_nome, upper(@p_sigla), @p_ativo);
   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update co_unidade_medida
         set nome          = @p_nome,
             sigla         = upper(@p_sigla),
             ativo         = @p_ativo
       where sq_unidade_medida = @p_chave;
   End Else If @p_operacao = 'E' Begin
      -- Exclui registro
      delete co_unidade_medida where sq_unidade_medida = @p_chave;
   End
end
