SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_GetSegList as
begin
   --Recupera a lista de segmentos
      select sq_segmento, nome, padrao, ativo 
      from co_segmento
end



GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

