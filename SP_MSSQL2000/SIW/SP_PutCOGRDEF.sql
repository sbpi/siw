alter procedure dbo.SP_PutCOGRDEF
   (@operacao  varchar(1),
    @chave     int         = null,
    @p_nome      varchar(50) = null,
    @p_ativo     varchar(1)  = null
   ) as
begin
   If @operacao = 'I' Begin
      -- Insere registro
      insert into co_grupo_defic (nome, ativo)  
      values (
                 rtrim(@p_nome),
                 @p_ativo
         )
   End Else If @operacao = 'A' Begin
      -- Altera registro
      update co_grupo_defic set 
         nome      = rtrim(@p_nome),
         ativo     = @p_ativo
      where sq_grupo_defic = @chave
   End Else If @operacao = 'E' Begin
      -- Exclui registro
      delete co_grupo_defic where sq_grupo_defic = @chave
   End
end