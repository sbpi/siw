create or replace function MontaOrdem (p_chave in numeric, p_retorno in varchar default null) returns varchar as $$
--------------------------------------------------------
--Se p_retorno é diferente de nulo, monta a ordem usando
--números para permitir a correta ordenação dos registros 
--------------------------------------------------------
declare
  c_ordem cursor for
     select sq_projeto_etapa, sq_etapa_pai, ordem
       from pj_projeto_etapa
      where sq_projeto_etapa in (select sq_projeto_etapa from connectby('pj_projeto_etapa','sq_etapa_pai','sq_projeto_etapa',to_char(p_chave),0) as (sq_projeto_etapa numeric, sq_etapa_pai numeric, level int)); 

  Result varchar(2000) := '';
begin
  If p_chave is null Then return null; End If;
  for crec in c_ordem loop
     If p_retorno is null 
        Then Result :=  crec.ordem||'.'||Result;
        Else Result := substr(cast(1000+crec.ordem as varchar),2,3)||Result;
     End If;
  end loop;
  If p_retorno is null Then Result := substr(Result,1,length(Result)-1); End If;
  return(Result);
end; $$ language 'plpgsql' volatile;
