SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_GetSegName (@p_sq_segmento int) as
begin
   -- Recupera os dados da etnia informada
      select nome from co_segmento where sq_segmento = @p_sq_segmento
end



GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

