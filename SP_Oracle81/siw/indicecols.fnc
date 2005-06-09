create or replace function IndiceCols(p_chave in number) return varchar2 is
  cursor c_coluna is
     select c.nome
       from dc_indice                 a,
            dc_indice_cols b,
            dc_coluna      c
      where (a.sq_indice = b.sq_indice)
        and (b.sq_coluna = c.sq_coluna)
        and a.sq_indice = p_chave
     order by b.ordem;

  Result varchar2(200) := '';
begin
  If p_chave is null Then return null; End If;
  for crec in c_coluna loop
     Result := Result||crec.nome||', ';
  end loop;
  Result := Substr(Result,1,Length(Result)-2);
  return(Result);
end IndiceCols;
/

