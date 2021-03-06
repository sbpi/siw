alter procedure dbo.SP_PutCOAgencia
   (@operacao  varchar(1),
    @chave     int         = null,
    @p_banco  int         = null,
    @p_nome      varchar(40) = null,
    @p_codigo    int         = null,
    @p_padrao    varchar(1)  = null,
    @p_ativo     varchar(1)  = null
   ) as
begin
   If @operacao = 'I' Begin
      -- Insere registro
      insert into co_agencia (sq_banco, nome, codigo, padrao, ativo)
      values (
                 @p_banco,
                 rtrim(ltrim(upper(@p_nome))),
                 rtrim(ltrim(@p_codigo)),
                 @p_padrao,
                 @p_ativo
         )
   End Else If @operacao = 'A' Begin
      -- Altera registro
      update co_agencia set
         sq_banco  = @p_banco,
         nome      = rtrim(ltrim(upper(@p_nome))),
         codigo    = rtrim(ltrim(@p_codigo)),
         padrao    = @p_padrao,
         ativo     = @p_ativo
      where sq_agencia = @chave
   End Else If @operacao = 'E' Begin
      -- Exclui registro
      delete co_agencia where sq_agencia = @chave
   End
end




