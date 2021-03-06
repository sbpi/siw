SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_GetLocalList
   (@p_cliente   int,
    @p_chave     int,
    @p_restricao varchar(255) = null) as
begin
   If @p_restricao is null
      -- Recupera as localizações do cliente
         select a.sq_localizacao,c.logradouro+' - '+a.nome+' ('+b.sigla+')' localizacao,
                b.sq_unidade, b.sq_unidade_pai
           from eo_localizacao a, eo_unidade b, co_pessoa_endereco c
          where a.sq_unidade         = b.sq_unidade
            and b.sq_pessoa_endereco = c.sq_pessoa_endereco
            and c.sq_pessoa          = @p_cliente
          order by c.logradouro, a.nome, b.sigla

end



GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

