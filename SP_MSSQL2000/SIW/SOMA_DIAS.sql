alter function dbo.SOMA_DIAS
  (@p_cliente   int,
   @data_inicio datetime,
   @dias        int,
   @contagem    varchar) 
   returns datetime as
/**********************************************************************************
* Nome      : soma_@dias
* Finalidade: Retorna a data fim a partir da data inicio e o n�mero de @dias
* Autor     : Alexandre Vinhadelli Papad�polis
* Data      : 29/07/2008, 14:00
* Par�metros:
*    @data_inicio   : data inicial
*    @dias          : n�mero de @dias a ser incrementado
*    @contagem      : forma da @contagem dos @dias (C -> corridos, U -> �teis
* Retorno: data inicial acrescida do n�mero de @dias (corridos/�teis) informado
***********************************************************************************/
begin

  Declare @w_atual   datetime;
  Declare @w_dias    numeric(10,1);

  Set @w_atual = cast(dbo.to_char(@data_inicio,'dd/mm/yyyy') as datetime);
  Set @w_dias  = 1;

  If upper(@contagem) = 'C' Set @w_atual = @w_atual + @dias;
  Else Begin
     If @dias >= 0 Begin
        -- Se for @contagem progressiva
         While @w_dias <= @dias Begin
           -- Incrementa a data atual
           Set @w_atual = @w_atual + 1;
    
           -- Verifica se pode incrementar o contador de @dias
           If dbo.to_char(@w_atual,'d') not in (1,7) Begin 
              If dbo.verificaDataEspecial(@w_atual,@p_cliente,null,null,null) <> 'N' Begin 
                 Set @w_dias = @w_dias + 1;
              End
           End
         End
     End Else Begin
        Set @w_dias = -1;
        -- Se for @contagem regressiva
         While @w_dias >= @dias Begin
           -- Incrementa a data atual
           Set @w_atual = @w_atual - 1;
    
           -- Verifica se pode decrementar o contador de @dias
           If dbo.to_char(@w_atual,'d') not in (1,7) Begin 
              If dbo.verificaDataEspecial(@w_atual,@p_cliente,null,null,null) <> 'N' Begin 
                 Set @w_dias = @w_dias - 1;
              End
           End
         End
     End
  End

  Return @w_atual;
end
