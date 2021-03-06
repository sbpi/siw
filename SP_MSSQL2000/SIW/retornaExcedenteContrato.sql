set ANSI_NULLS ON
set QUOTED_IDENTIFIER ON
GO
ALTER function [dbo].[retornaExcedenteContrato](
    @p_chave numeric(18), 
    @p_data  datetime = null
    ) 
    returns float as
    Begin
  -- p_chave: chave da tabela AC_ACORDO

    Declare @Result    float;
    Set     @Result    = 0;
    Declare @w_data    datetime;
    Set     @w_data    = @p_data;
    Declare @w_excedente varchar(255);

    Declare c_dados cursor for
    select 
    case when a.valor_inicial = 0 then 0 
    else (a.valor_acrescimo / 
    case when a.valor_inicial = 0 
    then 1 else a.valor_inicial end) * 100 
    end as excedente from ac_acordo_aditivo a 
    where a.sq_siw_solicitacao = @p_chave and @w_data between a.inicio and a.fim;   
  
    If @p_chave is null Begin 
        return 0; 
    End
    --If @p_data  is null Begin 
    --  Set @w_data = getDate(); 
    --End
    Open c_dados
    Fetch next from c_dados into @w_excedente
    While @@fetch_status = 0
    Begin
        Set @Result = @w_excedente;         
        Fetch next from c_dados into @w_excedente
    End
    Close c_dados;
    Deallocate c_dados;        
  
  return(@Result);
end
