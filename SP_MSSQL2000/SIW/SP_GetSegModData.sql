SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_GetSegModData
   (@p_sq_segmento int,
    @p_sq_modulo   int
   ) as
begin
      select a.*, b.nome, b.objetivo_geral
        from siw_mod_seg a, 
             siw_modulo b
       where a.sq_modulo   = b.sq_modulo
         and a.sq_modulo   = @p_sq_modulo
         and a.sq_segmento = @p_sq_segmento
end



GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

