SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS OFF 
GO

setuser N'SIW'
GO





CREATE   function dbo.SP_fGetCCSubordinat (@p_cliente int, @p_chave int) 
returns @stack1 table (chave int) as

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
        Insert @stack1
           select sq_cc
             from ct_cc
            where sq_cc_pai = @p_chave

        -- insere registros na pilha
        Insert @stack
           select sq_cc, @level + 1
             from ct_cc
            where sq_cc_pai = @p_chave

        -- se não tiver chave inferior, não cria mais um nível
        If @@ROWCOUNT > 0 Begin
           Set @level = @level + 1
        End
     END
     ELSE Begin
        SET @level = @level - 1
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

