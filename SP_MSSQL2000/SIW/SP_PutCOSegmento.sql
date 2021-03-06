SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_PutCOSegmento
   (@p_operacao         varchar(1),
    @p_chave            int         = null,
    @p_nome             varchar(40) = null,
    @p_ativo            varchar(1)  = null,
    @p_padrao           varchar(1)  = null
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into co_segmento (nome, padrao,ativo)
      values (
              rtrim(@p_nome),
              rtrim(@p_padrao),
              rtrim(@p_ativo)
             )
   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update co_segmento set
         nome      = rtrim(@p_nome),
         padrao    = @p_padrao,
         ativo     = @p_ativo
      where sq_segmento   = @p_chave
   End Else If @p_operacao = 'E' Begin
      -- Exclui registro
      delete co_segmento where sq_segmento = @p_chave
   End
end 





GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

