SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_PutEOLocal
   (@p_operacao                 varchar(1),
    @p_chave                    int         = null,
    @p_sq_pessoa_endereco       int         = null,
    @p_sq_unidade               int         = null,
    @p_nome                     varchar(30) = null,
    @p_fax                      varchar(12) = null,
    @p_telefone                 varchar(12) = null,
    @p_ramal                    varchar(6)  = null,
    @p_telefone2                varchar(12) = null,
    @p_ativo                    varchar(1)  = null
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
     insert into eo_localizacao (sq_pessoa_endereco,
                  sq_unidade, nome, fax, telefone, ramal, telefone2, ativo)         
      values (
                  @p_sq_pessoa_endereco,
                  @p_sq_unidade,                 
                  rtrim(@p_nome),
                  rtrim(@p_fax),
                  rtrim(@p_telefone),
                  rtrim(@p_ramal),
                  rtrim(@p_telefone2),
                  @p_ativo
         )
   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update eo_localizacao set
         nome                 = rtrim(@p_nome),
         fax                  = rtrim(@p_fax),
         telefone             = rtrim(@p_telefone),
         ramal                = rtrim(@p_ramal),
         telefone2            = rtrim(@p_telefone2),
         sq_pessoa_endereco   = @p_sq_pessoa_endereco,
         sq_unidade           = @p_sq_unidade,
         ativo                = @p_ativo
      where sq_localizacao    = @p_chave
   End Else If @p_operacao = 'E' Begin
      -- Exclui registro
      delete eo_localizacao where sq_localizacao = @p_chave
   End
end 





GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

