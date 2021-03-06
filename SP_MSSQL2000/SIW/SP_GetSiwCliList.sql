SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_GetSiwCliList as
begin
   -- Recupera os clienntes do SIW
      select b.sq_pessoa, b.nome_resumido, b.nome, b.nome_indice,
             a.ativacao, a.bloqueio, a.desativacao, c.cnpj,
             d.sq_cidade, d.nome cidade, d.co_uf uf, d.sq_pais
      from siw_cliente        a left outer join  co_cidade          d on (a.sq_cidade_padrao = d.sq_cidade),
           co_pessoa          b left outer join  co_pessoa_juridica c on (b.sq_pessoa = c.sq_pessoa) 
      where a.sq_pessoa          = b.sq_pessoa 
      order by b.nome_indice
end



GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

