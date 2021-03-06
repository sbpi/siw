SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_PutSIWModulo
   (@p_operacao         varchar(1),
    @p_sq_modulo        int           = null,
    @p_nome             varchar(60)   = null,
    @p_sigla            varchar(3)    = null,
    @p_objetivo_geral   varchar(4000) = null
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into siw_modulo (nome, sigla,objetivo_geral)
      values (
              rtrim(@p_nome),
              rtrim(upper(@p_sigla)),
              rtrim(@p_objetivo_geral)
         )
   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update siw_modulo set
         nome           = rtrim(@p_nome),
         sigla          = rtrim(upper(@p_sigla)),
         objetivo_geral = rtrim(@p_objetivo_geral)
      where sq_modulo   = @p_sq_modulo
   End Else If @p_operacao = 'E' Begin
      -- Exclui registro
      delete siw_modulo where sq_modulo = @p_sq_modulo
   End
end 





GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

