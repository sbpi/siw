SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_GetSegModList (@p_sq_segmento int) as
begin
   --Recupera a lista de módulos
      select sq_modulo, nome 
        from siw_modulo
       where sq_modulo not in (select a.sq_modulo
                                 from siw_modulo a, 
                                      siw_mod_seg b
                                where a.sq_modulo = b.sq_modulo
                                  and sq_segmento = @p_sq_segmento)
end



GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

