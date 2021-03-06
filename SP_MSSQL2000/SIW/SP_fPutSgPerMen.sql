SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS OFF 
GO

setuser N'SIW'
GO


CREATE  function dbo.SP_fPutSgPerMen (@p_chave int)
returns @stack1 table (chave int) as
-- Recupera os registros acima do informado

Begin

  Declare @stack table (chave int, level int)
  Declare @level int

  -- Grava primeiro registro na pilha
  Set @level = 1
  Insert @stack Values (@p_chave, @level)

  While @level > 0 Begin
     If exists (select * from @stack where  level = @level) Begin
        -- seleciona a chave corrente e descarta o registro da pilha
        select @p_chave = chave from @stack where level = @level
        delete from @stack where level = @level and chave = @p_chave

        -- insere registro na tabela de retorno
        Insert @stack1 values (@p_chave)

        -- pega a chave superior à atual
        select @p_chave = sq_menu_pai
          from siw_menu
         where sq_menu = @p_chave

        -- se não tiver chave superior, não cria mais um nível
        If @p_chave is not null Begin
           Set @level = @level + 1
           Insert @stack Values (@p_chave, @level)
        End
     END
     ELSE Begin
        SELECT @level = @level - 1
     End
  End
  Return
End



GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

