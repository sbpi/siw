create or replace function trunc(p_datahora timestamp without time zone) returns timestamp with time zone as $$
begin
  return(to_timestamp(to_char(p_datahora,'dd/mm/yyyy'),'dd/mm/yyyy'));
end;  $$ language 'plpgsql' volatile;

create or replace function trunc(p_datahora timestamp with time zone) returns timestamp with time zone as $$
begin
  return(to_timestamp(to_char(p_datahora,'dd/mm/yyyy'),'dd/mm/yyyy'));
end;  $$ language 'plpgsql' volatile;
