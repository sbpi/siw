create or replace function MontaNomeSolic(p_chave numeric)  RETURNS varchar AS $$
DECLARE
   c_ordem CURSOR FOR
     select a.sq_siw_solicitacao, a.sq_solic_pai, a.titulo, b.level
       from siw_solicitacao a
            inner join (select sq_siw_solicitacao, level 
                          from connectby('siw_solicitacao','sq_solic_pai','sq_siw_solicitacao',to_char(p_chave),0) 
                               as (sq_siw_solicitacao numeric, sq_solic_pai numeric, level int)
                       ) b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
     order by level;

  Result varchar(2000) := '';
BEGIN
  If p_chave is null Then return null; End If;
  for crec in c_ordem loop
     Result :=  crec.titulo || ' - ' || Result;
  end loop;
  return(substr(Result,1,length(Result)-3));
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;