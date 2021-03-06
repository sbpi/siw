SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_GetCompanyData
   (@p_cliente  int,
    @p_cnpj     varchar(255)) as
begin
     select a.*, b.nome, b.nome_resumido
       from co_pessoa_juridica a,
            co_pessoa          b
      where a.sq_pessoa             = b.sq_pessoa
        and isnull(b.sq_pessoa_pai,1)  = @p_cliente
        and a.cnpj                  = @p_cnpj
end



GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

