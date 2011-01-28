create or replace function MontaNomeMenu(p_chave numeric)  RETURNS varchar AS $$
DECLARE
   c_ordem CURSOR FOR
     select a.sq_menu, a.sq_menu_pai, a.nome, b.level
       from siw_menu a
            inner join (select sq_menu, level 
                          from connectby('siw_menu','sq_menu_pai','sq_menu',to_char(p_chave),0) 
                               as (sq_menu numeric, sq_menu_pai numeric, level int)
                       ) b on (a.sq_menu = b.sq_menu)
     order by level;

  Result varchar(2000) := '';
BEGIN
  If p_chave is null Then return null; End If;
  for crec in c_ordem loop
     Result :=  crec.nome || ' - ' || Result;
  end loop;
  return(substr(Result,1,length(Result)-3));
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;