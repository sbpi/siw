alter procedure dbo.SP_PutCOIdioma
   (@operacao  varchar(1),
    @chave     int         = null,
    @p_nome      varchar(20) = null,
    @p_padrao    varchar(1)  = null,
    @p_ativo     varchar(1)  = null
   ) as
begin
   If @operacao = 'I' Begin
      -- Insere registro
      insert into co_idioma (nome, padrao,ativo) 
      values (
                 rtrim(ltrim(@p_nome)),
                 @p_padrao,
                 @p_ativo
         )
   End Else If @operacao = 'A' Begin
      -- Altera registro
      update co_idioma set 
         nome      = rtrim(ltrim(@p_nome)),
         padrao    = @p_padrao,
         ativo     = @p_ativo
      where sq_idioma = @chave
   End Else If @operacao = 'E' Begin
      -- Exclui registro
      delete co_idioma where sq_idioma = @chave
   End
end 


