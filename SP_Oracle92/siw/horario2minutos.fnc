create or replace function horario2minutos(
   hora_inicio in varchar2 default null, -- hora no formato HH:MM. Se nulo, será assumido 00:00
   hora_fim    in varchar2 default null  --  hora no formato HH:MM. Se nulo, será assumido 24:00
  ) return number is

  w_inicio  varchar2(5) := coalesce(hora_inicio,'00:00');
  w_fim     varchar2(5) := coalesce(hora_fim,'24:00');
  w_min_ini number(18);
  w_min_fim number(18);

  Result    number;

begin
  w_min_ini := substr(w_inicio,1,2)*60+substr(w_inicio,4,2);
  w_min_fim := substr(w_fim,1,2)*60+substr(w_fim,4,2);
  result := w_min_fim - w_min_ini;
  return(Result);
end horario2minutos;
/
