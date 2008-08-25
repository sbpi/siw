alter function dbo.trunc(@value datetime) returns datetime as
begin
  return convert(datetime,convert(varchar(10),@value,103),103);
end