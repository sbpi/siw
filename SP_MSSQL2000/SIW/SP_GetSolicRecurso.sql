SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


alter procedure dbo.SP_GetSolicRecurso
   (@p_chave     int,
    @p_chave_aux int=null,
    @p_restricao varchar(50)
   ) as
begin
  If @p_restricao = 'LISTA'
     -- Recupera os recursos do projeto
        select a.*
          from pj_projeto_recurso  a
         where a.sq_siw_solicitacao = @p_chave
  Else If @p_restricao = 'REGISTRO'
     -- Recupera os dados de um recurso do projeto
        select a.*
          from pj_projeto_recurso a
         where a.sq_projeto_recurso = @p_chave_aux
End



GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

