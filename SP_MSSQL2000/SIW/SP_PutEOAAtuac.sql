alter procedure dbo.SP_PutEOAAtuac
   (@p_operacao                 varchar(1),
    @p_chave                    int         = null,
    @p_cliente                  int         = null,
    @p_nome                     varchar(25) = null,
    @p_ativo                    varchar(1)  = null 
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
     insert into eo_area_atuacao (sq_pessoa, nome, ativo)
      values (
                 @p_cliente,
                 rtrim(ltrim(@p_nome)),
                 @p_ativo
          )  
   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update eo_area_atuacao set
        nome  = rtrim(ltrim(@p_nome)),
        ativo = @p_ativo
        where sq_area_atuacao = @p_chave
   End Else If @p_operacao = 'E' Begin
      -- Exclui registro
      delete eo_area_atuacao where sq_area_atuacao = @p_chave
   End
end