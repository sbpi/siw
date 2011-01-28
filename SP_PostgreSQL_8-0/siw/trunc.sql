create or replace function trunc(p_datahora timestamp without time zone) returns date as $$
begin
  return(to_date(to_char(p_datahora,'dd/mm/yyyy'),'dd/mm/yyyy'));
end;  $$ language 'plpgsql' volatile;

create or replace function trunc(p_datahora timestamp with time zone) returns date as $$
begin
  return(to_date(to_char(p_datahora,'dd/mm/yyyy'),'dd/mm/yyyy'));
end;  $$ language 'plpgsql' volatile;
