alter function VerificaDataMovel
   (@p_ano  int, 
    @p_tipo varchar(1)
   ) returns datetime as --datetime as
/**********************************************************************************
* Finalidade: Retorna a data em que ocorre uma data móvel no ano informado
* Autor     : Alexandre Vinhadelli Papadópolis
* Data      :  28/07/2005, 15:55
*
* Parâmetros:
*    @p_ano    : ano de referência
*    @l_tipo   : S: segunda de carnaval
*                T: terça de carnaval
*                Q: cinzas
*                P: paixão de Cristo
*                D: Páscoa
*                H: Corpus Christi
***********************************************************************************/
begin
  Declare @Result datetime;
  Declare @a      integer;
  Declare @b      integer;
  Declare @c      integer;
  Declare @d      integer;
  Declare @e      integer;
  Declare @f      integer;
  Declare @g      integer;
  Declare @h      integer;
  Declare @i      integer;
  Declare @k      integer;
  Declare @l      integer;
  Declare @m      integer;
  Declare @p      integer;
  Declare @q      integer;
  Declare @pascoa datetime;
  Declare @terca  datetime;
  Declare @dia    datetime;
  Declare @l_tipo varchar(10);
  Set @l_tipo = upper(@p_tipo);

  -- Se o tipo não for válido, retorna nulo
  If @l_tipo not in ('S','C','Q','P','D','H') Return null;
  
  -- Calcula o Domingo de Páscoa, que é a data base para os outros feriados móveis
  Set @a      = (@p_ano%19);
  Set @b      = floor(@p_ano / 100);
  Set @c      = (@p_ano%100);
  Set @d      = floor(@b / 4);
  Set @e      = (@b%4);
  Set @f      = floor((@b + 8) / 25);
  Set @g      = floor((@b - @f + 1) /3);
  Set @h      = (((19*@a) + @b - @d - @g + 15)%30);
  Set @i      = floor(@c / 4);
  Set @k      = (@c%4);
  Set @l      = ((32 + (2 * @e) + (2 * @i) - @h - @k)%7);
  Set @m      = floor((@a + (11 * @h) + (22 * @l)) / 451);
  Set @p      = floor((@h + @l - (7 * @m) + 114) / 31);
  Set @q      = ((@h + @l - (7 * @m) + 114)%31);
  Set @pascoa = convert(datetime,substring(cast((100+@q+1) as varchar),2,2)+'/'+substring(cast((100+@p) as varchar),2,2)+'/'+substring(cast((10000+@p_ano) as varchar),2,4),103);
  
  -- Verifica a data a ser retornada, em função do tipo informado
  If      @l_tipo = 'D' Set @Result = @pascoa;
  Else If @l_tipo = 'P' Set @Result = @pascoa - 2;
  Else If @l_tipo = 'H' Set @Result = @pascoa + 60;
  Else Begin
     Set @dia = @pascoa - 42;
     If datepart(dw,@dia) > 3 Set @terca = @dia - datepart(dw,@dia) - 3;  Else Set @terca = @dia - datepart(dw,@dia) - 4;
     If      @l_tipo = 'S' Set @Result = @terca - 1;
     Else If @l_tipo = 'C' Set @Result = @terca;
     Else If @l_tipo = 'Q' Set @Result = @terca + 1;
  End
  return(@Result);
end
