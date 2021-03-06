SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_GetEtpDataPrnt
   (@p_chave     int) as
begin
   -- Recupera os dados do link pai do que foi informado
      select a.sq_etapa_pai, b.*
        from pj_projeto_etapa a, pj_projeto_etapa b
       where a.sq_projeto_etapa = b.sq_etapa_pai
         and a.sq_projeto_etapa = @p_chave
end



GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

