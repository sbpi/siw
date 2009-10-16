create or replace function minutos2horario(p_minutos in number) return varchar2 is
  w_horas   number(4);
  w_minutos number(2);
  Result varchar2(255);
begin
  w_horas   := floor(p_minutos/60);
  w_minutos := p_minutos - (w_horas*60);
  Result    := case when length(w_horas)=1 then '0' else '' end||w_horas||':'||substr(100+w_minutos,2,2);
  return(Result);
end minutos2horario;
/
