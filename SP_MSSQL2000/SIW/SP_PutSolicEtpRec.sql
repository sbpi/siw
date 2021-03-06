SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_PutSolicEtpRec
   (@p_operacao      varchar(1),
    @p_chave         int        = null,
    @p_recurso       int        = null
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro em pj_recurso_etapa
      insert into pj_recurso_etapa (sq_projeto_etapa, sq_projeto_recurso, observacao)
         values (@p_chave, @p_recurso, null)
   End Else If @p_operacao = 'E' Begin
      -- Remove a opção de todos os endereços da organização
      delete pj_recurso_etapa where sq_projeto_etapa = @p_chave
   End
   
   commit   
end 



GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

