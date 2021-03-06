create  function dbo.SP_fGetLnkDataPrnts (@p_chave int, @p_direction varchar(50)) 
returns @stack1 table (chave int) as

Begin
  Declare @stack table (chave int, level int)
  Declare @level int

  -- Grava primeiro registro na pilha
  Set @level = 1
  Insert @stack Values (@p_chave, @level)

  Insert @stack1 Values (@p_chave)


  While @level > 0 Begin
     If exists (select * from @stack where  level = @level) Begin
        If upper(@p_direction)='DOWN' Begin
           -- seleciona a chave corrente e descarta o registro da pilha
           select @p_chave = chave from @stack where level = @level
           delete from @stack where level = @level and chave = @p_chave

           -- insere registro na tabela de retorno
           Insert @stack1
              select sq_menu
                from siw_menu
               where sq_menu_pai = @p_chave

           -- insere registros na pilha
           Insert @stack
              select sq_menu, @level + 1
                from siw_menu
               where sq_menu_pai = @p_chave

           -- se n�o tiver chave inferior, n�o cria mais um n�vel
           If @@ROWCOUNT > 0 Begin
              Set @level = @level + 1
           End
        End Else Begin
           -- seleciona a chave corrente e descarta o registro da pilha
           select @p_chave = chave from @stack where level = @level
           delete from @stack where level = @level and chave = @p_chave

           -- insere registro na tabela de retorno
           Insert @stack1 values (@p_chave)

           -- pega a chave superior � atual
           select @p_chave = sq_menu_pai
             from siw_menu
            where sq_menu_pai = @p_chave

           -- se n�o tiver chave superior, n�o cria mais um n�vel
           If @p_chave is not null Begin
              Set @level = @level + 1
              Insert @stack Values (@p_chave, @level)
           End
           End
     END
     ELSE Begin
        SELECT @level = @level - 1
     End
  End
  Return
End

