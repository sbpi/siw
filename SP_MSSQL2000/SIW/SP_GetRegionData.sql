SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_GetRegionData (@p_sq_regiao int) as
begin
   -- Recupera os dados da região
      select * from co_regiao where sq_regiao = @p_sq_regiao
end



GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

