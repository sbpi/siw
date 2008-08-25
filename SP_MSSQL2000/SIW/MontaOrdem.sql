alter function dbo.MontaOrdem(@p_chave int, @p_retorno varchar) returns varchar(200) as
--------------------------------------------------------
--Se p_retorno é diferente de nulo, monta a ordem usando
--números para permitir a correta ordenação dos registros 
--------------------------------------------------------
Begin
  Declare @w_ordem numeric(18)
  Declare @Result  Varchar(200)
  Set @Result = ''


  If @p_chave is null return null

  declare c_ordem cursor for
     select ordem
       from pj_projeto_etapa
      where sq_projeto_etapa in (select chave from dbo.SP_fMontaOrdem(@p_chave))

  -- Para todas as etapas superiores à informada, executa o bloco abaixo
  Open c_ordem

  Fetch next from c_ordem into @w_ordem

  While @@Fetch_Status = 0
  Begin
     If @p_retorno is null 
        Set @Result = cast(@w_ordem as varchar)+'.'+@Result;
     Else 
        Set @Result = substring(cast((1000+@w_ordem) as varchar),2,3)+@Result;
     Fetch next from c_ordem into @w_ordem
  End
  Close c_ordem
  Deallocate c_ordem

 If @p_retorno is null Set @Result = substring(@Result,1,len(@Result)-1);
 return(@Result)
end
