SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_GetModList as
begin
   --Recupera a lista de módulos
      select sq_modulo, nome, objetivo_geral, sigla 
        from siw_modulo
end



GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

