alter procedure dbo.SP_PutCOEtnia
   (@operacao        varchar(1),
    @chave           int         = null,
    @p_nome            varchar(10) = null,
    @p_codigo_siape    int         = null,
    @p_ativo           varchar(1)  = null
   ) as
begin
   If @operacao = 'I' Begin
      -- Insere registro
      insert into co_etnia (nome, codigo_siape,ativo)
      values (
                 rtrim(ltrim(@p_nome)),
                 @p_codigo_siape,
                 @p_ativo
         )
   End Else If @operacao = 'A' Begin
      -- Altera registro
      update co_etnia set
         nome            = rtrim(ltrim(@p_nome)),
         codigo_siape    = @p_codigo_siape,
         ativo           = @p_ativo
      where sq_etnia = @chave
   End Else If @operacao = 'E' Begin
      -- Exclui registro
      delete co_etnia where sq_etnia = @chave
   End
end
