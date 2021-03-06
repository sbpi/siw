SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_GetLinkDataHelp
   (@p_cliente   int,
    @p_modulo    int,
    @p_sq_pessoa int         = null,
    @p_restricao varchar(50) = null
   ) as
begin
  -- Recupera os links permitidos ao usuário informado (pessoa > 0)  ou ao cliente informado (pessoa = 0)
  If @p_restricao is not null begin
     If upper(@p_restricao) = 'IS NULL' begin
           select a.sq_menu,a.nome,a.link,a.ordem,a.p1,a.p2,a.p3,a.p4,a.sigla,a.imagem, a.externo, IsNull(a.target,'content') target, a.ultimo_nivel, a.ativo, IsNull(b.filho,0) Filho,
                  a.finalidade, a.tramite, a.como_funciona
             from siw_menu a
                    left outer join
                      (select sq_menu_pai,count(*) Filho from siw_menu x where ativo = 'S' group by sq_menu_pai) b
                    on (a.sq_menu = b.sq_menu_pai)  
            where a.ativo            = 'S'
              and a.sq_menu_pai      is null
              and a.sq_pessoa        = @p_cliente
              and a.sq_modulo        = @p_modulo
              and (@p_sq_pessoa       <= 0 or (@p_sq_pessoa > 0 and siw.marcado(a.sq_menu, @p_sq_pessoa,null,null,null) > 0))
           order by 4,2
     End Else Begin
           select a.sq_menu,a.nome,a.link,a.ordem,a.p1,a.p2,a.p3,a.p4,a.sigla,a.imagem, a.externo, IsNull(a.target,'content') target, a.ultimo_nivel, a.ativo, IsNull(b.filho,0) Filho,
                  a.finalidade, a.tramite, a.como_funciona
             from siw_menu a
                    left outer join
                      (select sq_menu_pai,count(*) Filho from siw_menu x where ativo = 'S' group by sq_menu_pai) b
                    on (a.sq_menu = b.sq_menu_pai)  
            where a.ativo            = 'S'
              and a.sq_menu_pai      = @p_restricao
              and a.sq_pessoa        = @p_cliente
              and a.sq_modulo        = @p_modulo
              and (@p_sq_pessoa       <= 0 or (@p_sq_pessoa > 0 and siw.marcado(a.sq_menu, @p_sq_pessoa,null,null,null) > 0))
           order by 4,2
     End
  End
end


GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

