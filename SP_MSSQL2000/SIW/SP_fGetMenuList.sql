alter function dbo.SP_fGetMenuList (@p_chave int, @p_direction varchar(50)) 
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

           -- se não tiver chave inferior, não cria mais um nível
           If @@ROWCOUNT > 0 Begin
              Set @level = @level + 1
           End
        End Else Begin
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
           End
     END
     ELSE Begin
        SELECT @level = @level - 1
     End
  End
  Return
End
