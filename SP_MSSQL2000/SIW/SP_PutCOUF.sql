SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_PutCOUF
   (@p_operacao                 varchar(1),
    @p_co_uf                    varchar(3),
    @p_sq_pais                  int         = null,
    @p_sq_regiao                int         = null,
    @p_nome                     varchar(30) = null,
    @p_ativo                    varchar(1)  = null,
    @p_padrao                   varchar(1)  = null,
    @p_codigo_ibge              varchar(2)  = null, 
    @p_ordem                    int         = null  
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into CO_UF (co_uf, sq_pais, sq_regiao, nome, ativo, padrao, codigo_ibge, ordem) 
      values (
                 rtrim(upper(@p_co_uf)),
                 @p_sq_pais,
                 @p_sq_regiao,
                 rtrim(@p_nome),
                 @p_ativo,
                 @p_padrao,                
                 rtrim(@p_codigo_ibge),
                 @p_ordem
         )
   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update CO_UF set
        nome        = rtrim(@p_nome),
        ativo       = @p_ativo,
        padrao      = @p_padrao,
        sq_regiao   = @p_sq_regiao,
        codigo_ibge = rtrim(@p_codigo_ibge),
        ordem       = @p_ordem
      where sq_pais = @p_sq_pais
        and co_uf   = @p_co_uf
   End Else If @p_operacao = 'E' Begin
      -- Exclui registro
      delete co_uf where sq_pais = @p_sq_pais and co_uf = @p_co_uf
   End
end 





GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

