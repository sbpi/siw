create or replace function SUBNAME(nome in varchar2 default null, retorno in varchar2) return varchar2 is
  Result varchar2(100);
  firstname varchar2(100) := '';
  ind number;
  i number;
begin
  if nome is null then return null; end if;
  
  Result := nome;
  ind := instr(result, ' ');
  if instr(trim(nome), ' ') = 0 
   then if retorno = 'L'
         then return null;
         else return (trim(nome));
        end if; 
  end if; 
  i := ind;
  while ind > 0 loop
   result := substr(result, ind + 1); 
   firstname := substr(nome, 1, i);
   ind := instr(result, ' ');   
   i := i + ind;
  end loop;

  if retorno = 'L'
   then return(trim(result));
   else return(trim(firstname));
  end if;   

end SUBNAME;
/

