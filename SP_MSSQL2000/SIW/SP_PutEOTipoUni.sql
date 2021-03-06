alter procedure dbo.SP_PutEOTipoUni
   (@p_operacao                 varchar(1),
    @p_chave                    int         = null,
    @p_cliente                  int         = null,
    @p_nome                     varchar(25) = null,
    @p_ativo                    varchar(1)  = null 
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
     insert into eo_tipo_unidade (sq_pessoa, nome, ativo)
      values (
                 @p_cliente,
                 rtrim(ltrim(@p_nome)),
                 @p_ativo
          )  
   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update eo_tipo_unidade set
        nome  = rtrim(ltrim(@p_nome)),
        ativo = @p_ativo
        where sq_tipo_unidade = @p_chave
   End Else If @p_operacao = 'E' Begin
      -- Exclui registro
      delete eo_tipo_unidade where sq_tipo_unidade = @p_chave
   End
end