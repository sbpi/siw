alter function dbo.to_char(@value datetime, @format varchar(50)) 
returns varchar(50) as
begin
  Declare @Result           VarChar(4000)

  If @value is null return null;

  Set @Result = Null
  
  If lower(@format)='dd/mm/yyyy, hh24:mi:ss'
     Set @Result = convert(varchar,@value,103)+', '+convert(varchar,@value,108)
  Else If lower(@format)='dd/mm/yy, hh24:mi:ss'
     Set @Result = convert(varchar,@value,3)+', '+convert(varchar,@value,108)

  return(@Result)

end
