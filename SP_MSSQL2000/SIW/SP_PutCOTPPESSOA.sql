alter procedure dbo.SP_PutCOTPPESSOA
   (@operacao  varchar(1),
    @chave     int         = null,
    @p_nome      varchar(60) = null,
    @p_padrao    varchar(1)  = null,
    @p_ativo     varchar(1)  = null
   ) as
begin
   If @operacao = 'I' Begin
      -- Insere registro
      insert into co_tipo_pessoa (nome, padrao,ativo) 
      values (
                 rtrim(ltrim(@p_nome)),
                 @p_padrao,
                 @p_ativo
         )
   End Else If @operacao = 'A' Begin
      -- Altera registro
      update co_tipo_pessoa set 
         nome      = rtrim(ltrim(@p_nome)),
         padrao    = @p_padrao,
         ativo     = @p_ativo
      where sq_tipo_pessoa = @chave
   End Else If @operacao = 'E' Begin
      -- Exclui registro
      delete co_tipo_pessoa where sq_tipo_pessoa = @chave
   End
end 