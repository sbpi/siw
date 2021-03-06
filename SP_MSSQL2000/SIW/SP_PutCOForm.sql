
alter procedure dbo.SP_PutCOForm
   (@operacao  varchar(1),
    @chave     int         = null,
    @p_tipo      varchar(1)  = null,
    @p_nome      varchar(50) = null,
    @p_ordem     int         = null,
    @p_ativo     varchar(1)  = null
   ) as
begin
   If @operacao = 'I' Begin
      -- Insere registro
      insert into co_formacao (tipo, nome, ordem,ativo) 
      values (
                 @p_tipo, 
                 rtrim(ltrim(@p_nome)),
                 @p_ordem,
                 @p_ativo
         )
   End Else If @operacao = 'A' Begin
      -- Altera registro
      update co_formacao set
         tipo      = @p_tipo,      
         nome      = rtrim(ltrim(@p_nome)),
         ordem     = @p_ordem,
         ativo     = @p_ativo
      where sq_formacao = @chave
   End Else If @operacao = 'E' Begin
      -- Exclui registro
      delete co_formacao where sq_formacao = @chave
   End
end