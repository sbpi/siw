SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_PutProjetoRec
   (@p_operacao           varchar(1),
    @p_chave              int           = null,
    @p_chave_aux          int           = null,
    @p_nome               varchar(100)  = null,
    @p_tipo               int           = null,
    @p_descricao          varchar(2000) = null,
    @p_finalidade         varchar(2000) = null
   ) as
begin
   If @p_operacao = 'I' Begin -- Inclusão
      -- Insere registro na tabela de recursos
      Insert Into pj_projeto_recurso
         ( sq_siw_solicitacao, nome,    tipo,   descricao,   finalidade )
      Values
         ( @p_chave,           @p_nome, @p_tipo,@p_descricao,@p_finalidade )
   End Else If @p_operacao = 'A' Begin -- Alteração
      -- Atualiza a tabela de recursos
      Update pj_projeto_recurso set
          nome         = @p_nome,
          tipo         = @p_tipo,
          descricao    = @p_descricao,
          finalidade   = @p_finalidade
      where sq_siw_solicitacao = @p_chave
        and sq_projeto_recurso = @p_chave_aux
   End Else If @p_operacao = 'E' Begin -- Exclusão
      -- Remove o registro na tabela de recursos
      delete pj_projeto_recurso 
       where sq_siw_solicitacao = @p_chave
         and sq_projeto_recurso = @p_chave_aux
   End
end 





GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

