alter function dbo.trunc(@value datetime) returns datetime as
begin
  Declare @w_data datetime;
  Set @w_data = convert(datetime,convert(char(10),@value,103),103);
  return @w_data;
end