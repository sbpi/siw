SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_GetMenuLink
   (@p_cliente   int,
    @p_endereco  int = null,
    @p_restricao varchar(255) = null) as
begin
   -- Recupera os links permitidos ao usuário informado
   If @p_restricao is not null
      If upper(@p_restricao) = 'IS NULL'
            select a.sq_menu, a.nome, a.link, a.ordem, a.p1, a.p2, a.p3, a.p4, a.sigla, a.imagem,
                   a.descentralizado, a.ativo, a.externo, a.tramite, a.ultimo_nivel, a.sq_modulo, 
                   isnull(b.Filho,0) Filho
              from siw_menu a
                    left outer join
                     (select sq_menu_pai,count(*) Filho from siw_menu x group by sq_menu_pai) b
                    on (a.sq_menu = b.sq_menu_pai) 
             where a.sq_menu_pai      is null
               and (@p_endereco        is null or
                    (@p_endereco       is not null and
                     (a.descentralizado='N' or
                      a.sq_menu        in (select sq_menu from siw_menu_endereco where ativo = 'S' and sq_pessoa_endereco = @p_endereco)
                     )
                    )
                   )
               and a.sq_pessoa        = @p_cliente
            order by 4,2
      Else
            select a.sq_menu, a.nome, a.link, a.ordem, a.p1, a.p2, a.p3, a.p4, a.sigla, a.imagem,
                   a.descentralizado, a.ativo, a.externo, a.tramite, a.ultimo_nivel, a.sq_modulo, 
                   isnull(b.Filho,0) Filho
              from siw_menu a
                    left outer join
                     (select sq_menu_pai,count(*) Filho from siw_menu x group by sq_menu_pai) b
                    on (a.sq_menu = b.sq_menu_pai) 
             where (@p_endereco        is null or
                    (@p_endereco       is not null and
                     (a.descentralizado='N' or
                      a.sq_menu        in (select sq_menu from siw_menu_endereco where ativo = 'S' and sq_pessoa_endereco = @p_endereco)
                     )
                    )
                   )
               and a.sq_pessoa        = @p_cliente
               and a.sq_menu_pai      = @p_restricao
            order by 4,2
end



GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

