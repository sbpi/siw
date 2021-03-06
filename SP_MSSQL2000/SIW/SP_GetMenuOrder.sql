SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO



create procedure dbo.SP_GetMenuOrder
   (@p_cliente   int,
    @p_sq_menu   int=null
   ) as
begin
   -- Recupera o número de ordem das outras opções irmãs à informada
   If @p_sq_menu is null
         select a.sq_menu, a.ultimo_nivel, a.acesso_geral, a.ordem, a.nome from siw_menu a where a.sq_menu_pai is null and a.sq_pessoa = @p_cliente order by a.ordem
   Else
         select a.sq_menu, a.ultimo_nivel, a.acesso_geral, a.ordem, a.nome from siw_menu a where a.sq_menu_pai = @p_sq_menu and a.sq_pessoa = @p_cliente order by a.ordem
end




GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

