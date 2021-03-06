alter procedure dbo.SP_PutCOTPENDER
   (@operacao                 varchar(1),
    @chave                    int         = null,
    @p_sq_tipo_pessoa           int         = null,
    @p_nome                     varchar(30) = null,
    @p_padrao                   varchar(1)  = null,
    @p_ativo                    varchar(1)  = null,
    @p_email                    varchar(1)  = null,
    @p_internet                 varchar(1)  = null
   ) as
begin
   If @operacao = 'I' Begin
      -- Insere registro
      insert into co_tipo_endereco (sq_tipo_pessoa, nome, padrao, ativo, email, internet) 
      values (
                 @p_sq_tipo_pessoa,
                 rtrim(ltrim(@p_nome)),
                 @p_padrao,
                 @p_ativo,                 
                 @p_email,
                 @p_internet
         )
   End Else If @operacao = 'A' Begin
      -- Altera registro
      update co_tipo_endereco set
         sq_tipo_pessoa       = @p_sq_tipo_pessoa,
         nome                 = rtrim(ltrim(@p_nome)),
         padrao               = @p_padrao,
         ativo                = @p_ativo,      
         email                = @p_email,
         internet             = @p_internet
      where sq_tipo_endereco  = @chave
   End Else If @operacao = 'E' Begin
      -- Exclui registro
      delete co_tipo_endereco where sq_tipo_endereco = @chave
   End
end 