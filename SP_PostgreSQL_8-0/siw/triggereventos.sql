create or replace function TriggerEventos(p_chave numeric)  RETURNS varchar AS $$
DECLARE
   c_trigger CURSOR FOR
     select c.nome
       from dc_trigger                     a
            inner   join dc_trigger_evento b on (a.sq_trigger = b.sq_trigger)
              inner join dc_evento         c on (b.sq_evento  = c.sq_evento)
      where a.sq_trigger = p_chave
     order by c.nome;

  Result varchar(200) := '';
BEGIN
  If p_chave is null Then return null; End If;
  for crec in c_trigger loop
     Result := Result||crec.nome||', ';
  end loop;
  Result := Substr(Result,1,Length(Result)-2);
  return(Result);END; $$ LANGUAGE 'PLPGSQL' VOLATILE;