SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_PutProjetoInter
   (@p_operacao           varchar(1),
    @p_chave              int        = null,
    @p_chave_aux          int        = null,
    @p_tipo_visao         int        = null,
    @p_envia_email        varchar(1) = null
   ) as
begin
   If @p_operacao = 'I' Begin -- Inclusão
      -- Insere registro na tabela de interessados
      Insert Into pj_projeto_interes 
         ( sq_pessoa,   sq_siw_solicitacao, tipo_visao,    envia_email )
      Values
         (@p_chave_aux,  @p_chave,            @p_tipo_visao,  @p_envia_email )
   End Else If @p_operacao = 'A' Begin -- Alteração
      -- Atualiza a tabela de solicitações
      Update pj_projeto_interes set
          tipo_visao       = @p_tipo_visao,
          envia_email      = @p_envia_email
      where sq_siw_solicitacao = @p_chave
        and sq_pessoa          = @p_chave_aux
   End Else If @p_operacao = 'E' Begin -- Exclusão
      -- Remove o registro na tabela de projetos
      delete pj_projeto_interes 
       where sq_siw_solicitacao = @p_chave
         and sq_pessoa          = @p_chave_aux
   End
end 





GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

