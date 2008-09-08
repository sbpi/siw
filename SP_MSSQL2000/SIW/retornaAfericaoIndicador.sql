alter function retornaAfericaoIndicador(
    @p_chave numeric(18), 
    @p_data  varchar(20)  = null, 
    @p_tipo  varchar(255) = null) 
returns float as
Begin
    Declare @Result   float;
    Declare @w_existe numeric(18);
    Set     @w_existe     = 0;
    Declare @w_data   datetime;
    Declare @w_valor  float;
  
/*  cursor c_afericao is
     select a.valor
       from eo_indicador_afericao a 
      where sq_eoindicador = @p_chave
        and ((@p_tipo       is null    and a.data_afericao  = @w_data) or 
             (@p_tipo       = 'ABAIXO' and a.data_afericao <= @w_data) or
             (@p_tipo       = 'ACIMA'  and a.data_afericao >= @w_data)
            )
     order by a.data_afericao desc;*/
    Declare c_afericao cursor for
        select a.valor
        from eo_indicador_afericao a 
        where sq_eoindicador = @p_chave
        and ((@p_tipo       is null    and a.data_afericao  = @w_data) or 
             (@p_tipo       = 'ABAIXO' and a.data_afericao <= @w_data) or
             (@p_tipo       = 'ACIMA'  and a.data_afericao >= @w_data)
            )order by a.data_afericao desc;

  If @p_chave is null Begin 
     return null; 
  End
  If @p_data is null Begin
     Set @w_data = null;
  End Else Begin
     If len(@p_data) = 7 Begin
        If substring(@p_data,3,1) <> '/' or substring(@p_data,1,2) > 12 Begin Return null; End
        Set @w_data = dbo.lastDay(dbo.to_date('01/' + @p_data,'dd/mm/yyyy'));
     End Else Begin
        If substring(@p_data,3,1) <> '/' or substring(@p_data,6,1) <> '/' or substring(@p_data,1,2) > 31 or substring(@p_data,4,2) > 12 Begin Return null; End
     End
  End
  Open c_afericao
  Fetch Next from c_afericao into @w_valor
  While @@fetch_status = 0 Begin
    Set @w_existe = 1;
    Set @Result  = @w_valor;
    Fetch Next from c_afericao into @w_valor
    If @p_tipo = 'ABAIXO' Begin 
        break; 
    End
  End
  If @w_existe = 0 Begin
     Set @Result = null;
  End
  
  return(@Result);
end
