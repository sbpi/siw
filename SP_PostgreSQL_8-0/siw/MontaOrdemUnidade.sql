create or replace function MontaOrdemUnidade(p_chave numeric)  RETURNS varchar AS $$
DECLARE
   c_ordem CURSOR FOR
     select a.sq_unidade, a.sq_unidade_pai, a.nome, a.ordem, b.level
       from eo_unidade a
            inner join (select sq_unidade, level 
                          from connectby('eo_unidade','sq_unidade_pai','sq_unidade',to_char(p_chave),0) 
                               as (sq_unidade numeric, sq_unidade_pai numeric, level int)
                       ) b on (a.sq_unidade = b.sq_unidade)
     order by level;

  Result varchar(2000) := '';
BEGIN
  If p_chave is null Then return null; End If;
  for crec in c_ordem loop
     Result :=  substr(to_char(10000 + crec.ordem), 2,4) || Result;
  end loop;
  return(Result);
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;