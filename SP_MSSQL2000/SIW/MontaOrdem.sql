alter function dbo.MontaOrdem(@p_chave int, @p_retorno varchar) returns varchar(200) as
--------------------------------------------------------
--Se p_retorno é diferente de nulo, monta a ordem usando
--números para permitir a correta ordenação dos registros 
--------------------------------------------------------
Begin
  if @p_chave is null return null;

  Declare @w_ordem numeric(18)
  Declare @Result  Varchar(2000)
  Set @Result = '';

  Declare @stack  table (chave int, level int)
  Declare @level int
  Declare @ordem int

  -- Grava primeiro registro na pilha
  Set @level = 1
  Insert @stack Values (@p_chave, @level)

  While @level > 0 Begin
     If exists (select * from @stack where  level = @level) Begin
        -- seleciona a chave corrente e descarta o registro da pilha
        select @p_chave = chave from @stack where level = @level
        delete from @stack where level = @level and chave = @p_chave

        -- pega a chave superior à atual
        select @p_chave = sq_etapa_pai, @ordem = ordem
          from pj_projeto_etapa
         where sq_projeto_etapa = @p_chave;

        If @p_retorno is null 
           Set @Result = cast(@ordem as varchar)+'.'+@Result;
        Else 
           Set @Result = substring(cast((1000+@ordem) as varchar),2,3)+@Result;

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
  
  If @p_retorno is null Set @Result = substring(@Result,1,len(@Result)-1);
  Return @Result;
End
