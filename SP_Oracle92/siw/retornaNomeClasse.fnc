create or replace function retornaNomeClasse(p_classe in number) return varchar2 is
  Result varchar2(20);
begin
  select case p_classe
              when 1 then 'Medicamento'
              when 2 then 'Alimento'
              when 3 then 'Consumo'
              when 4 then 'Permanente'
              when 5 then 'Serviço'
         end as nm_classe
    into Result
    from dual;

  return(Result);
end retornaNomeClasse;
/
