
alter procedure dbo.SP_PutCOTPFONE
   (@operacao        varchar(1),
    @chave           int         = null,
    @p_sq_tipo_pessoa  int         = null,
    @p_nome            varchar(25) = null,
    @p_padrao          varchar(1)  = null,
    @p_ativo           varchar(1)  = null
   ) as
begin
   If @operacao = 'I' Begin
      -- Insere registro
      insert into co_tipo_telefone (sq_tipo_pessoa, nome, padrao,ativo)
      values (
                 @p_sq_tipo_pessoa,
                 rtrim(ltrim(@p_nome)),
                 @p_padrao,
                 @p_ativo
         )
   End Else If @operacao = 'A' Begin
      -- Altera registro
      update co_tipo_telefone set
         sq_tipo_pessoa = @p_sq_tipo_pessoa, 
         nome           = rtrim(ltrim(@p_nome)),
         padrao         = @p_padrao,
         ativo          = @p_ativo
      where sq_tipo_telefone = @chave
   End Else If @operacao = 'E' Begin
      -- Exclui registro
      delete co_tipo_telefone where sq_tipo_telefone = @chave
   End
end 
