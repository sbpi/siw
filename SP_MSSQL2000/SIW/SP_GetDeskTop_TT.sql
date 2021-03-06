SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_GetDeskTop_TT (@p_usuario int) as
begin
   -- Recupera as ligações do usuário em aberto
      select (x.existe + y.existe) existe from
      (select count(*) existe
         from tt_ligacao         a
                 inner      join tt_usuario         b on (a.sq_usuario_central = b.sq_usuario_central)
                 inner      join tt_ramal           c on (a.sq_ramal           = c.sq_ramal)
                 inner      join tt_tronco          d on (a.sq_tronco          = d.sq_tronco)
                    inner   join co_pessoa_telefone e on (d.sq_pessoa_telefone = e.sq_pessoa_telefone)
                 left outer join ct_cc              g on (a.sq_cc              = g.sq_cc)
                 left outer join tt_prefixos        h on (a.sq_prefixo         = h.sq_prefixo)
        where a.trabalho           is null
          and b.usuario            = @p_usuario
      ) x,
      (select count(*) existe
         from tt_ligacao         a
                 inner      join tt_ramal_usuario   c on (a.sq_ramal           = c.sq_ramal)
                    inner   join tt_usuario         b on (c.sq_usuario_central = b.sq_usuario_central)
                 inner      join tt_ramal           d on (a.sq_ramal           = d.sq_ramal)
                 inner      join tt_tronco          e on (a.sq_tronco          = e.sq_tronco)
                    inner   join co_pessoa_telefone f on (e.sq_pessoa_telefone = f.sq_pessoa_telefone)
                 left outer join ct_cc              g on (a.sq_cc              = g.sq_cc)
                 left outer join tt_prefixos        h on (a.sq_prefixo         = h.sq_prefixo)
        where a.trabalho           is null
          and a.sq_usuario_central is null 
          and a.data               between c.inicio and IsNull(c.fim,getdate()+1) 
          and b.usuario            = @p_usuario
      ) y;
end


GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

