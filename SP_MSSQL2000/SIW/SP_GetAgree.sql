SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_GetAgree
   (@p_chave     int         =null,
    @p_cliente   int,
    @p_restricao varchar(50) = null
   ) as
begin
   If @p_restricao = 'GCCAD' or @p_restricao = 'GCACOMP' Begin
      -- Recupera os contratos que o usuário pode ver
         select a.sq_acordo, a.codigo_interno, IsNull(a.codigo_externo,'---') codigo_externo, 
                a.inicio,    a.fim,            b.nome, 
                IsNull(b.nome_resumido,'---') nome_resumido, 
                c.nome sq_tipo_acordo,         d.nome sq_cc 
           from ac_acordo          a
                   left outer join co_pessoa          b on (a.outra_parte      = b.sq_pessoa)
                   inner      join ac_tipo_acordo     c on (a.sq_tipo_acordo   = c.sq_tipo_acordo)
                   inner      join ct_cc              d on (a.sq_cc            = d.sq_cc)
          where a.cliente = @p_cliente
         order by b.nome_indice
   End Else If @p_restricao = 'GCGERAL' Begin
      -- Recupera os dados gerais do contrato informado
         select a.sq_acordo_pai, a.outra_parte, a.sq_tipo_acordo, a.sq_cc, 
                a.inicio,        a.fim,         a.codigo_externo, a.objeto, 
                a.observacao,    a.dia_vencimento 
            from ac_acordo a  
         where a.sq_acordo       = @p_chave
   End
end



GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

