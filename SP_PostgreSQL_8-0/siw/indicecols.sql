create or replace function IndiceCols(p_chave numeric)  RETURNS varchar AS $$
DECLARE
   c_coluna CURSOR FOR
     select c.nome,
            case b.ordenacao when 'A' then 'ASC' else 'DESC' end as ordenacao
       from dc_indice                 a
            inner join dc_indice_cols b on (a.sq_indice = b.sq_indice)
            inner join dc_coluna      c on (b.sq_coluna = c.sq_coluna)
      where a.sq_indice = p_chave
     order by b.ordem;

  Result varchar(200) := '';
BEGIN
  If p_chave is null Then return null; End If;
  for crec in c_coluna loop
     Result := Result||crec.nome||' '||crec.ordenacao||', ';
  end loop;
  Result := Substr(Result,1,Length(Result)-2);
  return(Result);END; $$ LANGUAGE 'PLPGSQL' VOLATILE;