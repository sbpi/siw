alter function dbo.MontaNomeTipoRecurso(
	@p_chave   int, 
	@p_retorno varchar(255) = null) 
	returns varchar(2000) as
begin
  Declare @sq_tipo_recurso int,
          @sq_tipo_pai     int,
          @nome            varchar(255)

  Declare c_ordem cursor for
     select sq_tipo_recurso, sq_tipo_pai, nome from eo_tipo_recurso
     where sq_tipo_recurso in (select chave from dbo.sp_fGetTipoRecurso(@p_chave, 'UP'));

  Declare @Result varchar(2000);
  Set     @Result = '';
  Declare @w_pai  varchar(2000);
  Set @w_pai  = '';

  -- Se não foi informada a chave, retorna nulo
  If @p_chave is null Begin 
	return null; 
  End
  
  -- Monta o nome varrendo do registro informado para cima

  Open c_ordem
  Fetch Next from c_ordem into @sq_tipo_recurso, @sq_tipo_pai, @nome
  While @@Fetch_Status = 0 Begin
 	 Set @Result =  @nome + ' - ' + @Result;
     FETCH NEXT FROM c_ordem into @sq_tipo_recurso, @sq_tipo_pai, @nome;
  End
  Close c_ordem
  Deallocate c_ordem
  
  
  -- Se retornar apenas o primeiro nível
  If @p_retorno = 'PRIMEIRO' Begin
     return(substring(@Result,1,charindex(' - ', @Result)));
  End
  Else Begin
     return(substring(@Result,1,len(@Result)-3));
  End;
  Return(@result)
end
