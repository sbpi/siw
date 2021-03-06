alter procedure dbo.SP_PutCOTPDEF
   (@operacao                 varchar(1),
    @chave                    int          = null,
    @sq_grupo_deficiencia     int          = null,
    @p_codigo                   varchar(3)   = null,
    @p_nome                     varchar(50)  = null,
    @p_descricao                varchar(200) = null,
    @p_ativo                    varchar(1)   = null
   ) as
begin
   If @operacao = 'I' Begin
      -- Insere registro
      insert into co_deficiencia (sq_grupo_defic, codigo, nome, descricao, ativo)
      values (
                 @sq_grupo_deficiencia,
                 rtrim(ltrim(@p_codigo)),
                 rtrim(ltrim(@p_nome)),
                 rtrim(ltrim(@p_descricao)),
                 @p_ativo
         )
   End Else If @operacao = 'A' Begin
      -- Altera registro
      update co_deficiencia set
         sq_grupo_defic       = @sq_grupo_deficiencia,
         codigo               = rtrim(ltrim(@p_codigo)),
         nome                 = rtrim(ltrim(@p_nome)),
         descricao            = rtrim(ltrim(@p_descricao)),
         ativo                = @p_ativo
      where sq_deficiencia    = @chave
   End Else If @operacao = 'E' Begin
      -- Exclui registro
      delete co_deficiencia where sq_deficiencia = @chave
   End
end 