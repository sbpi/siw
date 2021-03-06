alter  function dbo.SP_fGetProjetoEtapa (@p_chave int = null, @p_direction varchar(50)) 
returns @stack1 table (chave int) as

Begin
  Declare @stack table (chave int, level int)
  Declare @level int

  If @p_chave is null and @p_direction = 'UP' Begin
      Insert @stack1 
          select sq_projeto_etapa from pj_projeto_etapa where sq_etapa_pai is null;
  End Else If @p_chave is null and @p_direction = 'DOWN' Begin
      Insert @stack1 
          select sq_projeto_etapa from pj_projeto_etapa where 1 = 1;
  End Else Begin
      Declare @exists int
    
      -- Grava primeiro registro na pilha
      Set @level = 1
      Insert @stack Values (@p_chave, @level)
    
      While @level > 0 Begin
         If exists (select * from @stack where  level = @level) Begin
            If upper(@p_direction)='DOWN' Begin
               -- seleciona a chave corrente e descarta o registro da pilha
               select @p_chave = chave from @stack where level = @level
               delete from @stack where level = @level and chave = @p_chave
    
               -- insere registro na tabela de retorno
               Insert @stack1
                  select sq_projeto_etapa
                    from pj_projeto_etapa
                   where sq_etapa_pai = @p_chave
    
               -- insere registros na pilha
               Insert @stack
                  select sq_projeto_etapa, @level + 1
                    from pj_projeto_etapa
                   where sq_etapa_pai = @p_chave
    
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
               select @p_chave = sq_etapa_pai
                 from pj_projeto_etapa
                where sq_projeto_etapa = @p_chave
    
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
  End
  Return
End