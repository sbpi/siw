create or replace function MontaOrdem (p_chave in numeric, p_retorno in varchar) returns varchar as $$
--------------------------------------------------------
--Se p_retorno é diferente de nulo, monta a ordem usando
--números para permitir a correta ordenação dos registros 
--------------------------------------------------------
declare
  c_sq_projeto_etapa      numeric(18);
  c_sq_etapa_pai          numeric(18);
  c_ordem                 numeric(4);

  c_ordens cursor (l_chave numeric) for
     select sq_projeto_etapa, sq_etapa_pai, ordem
       from pj_projeto_etapa
      where sq_projeto_etapa in (select sq_projeto_etapa from sp_fGetEtapaList(l_chave,0,'UP')); 

  Result varchar(2000) := '';
begin
  If p_chave is null Then return null; End If;
  open c_ordens (p_chave);
  loop
     fetch c_ordens into c_sq_projeto_etapa, c_sq_etapa_pai, c_ordem;
     If Not Found Then Exit; End If;
     If p_retorno is null 
        Then Result :=  c_ordem||'.'||Result;
        Else Result := substr(1000+c_ordem,2,3)||Result;
     End If;
  end loop;
  close c_ordens;
  If p_retorno is null Then Result := substr(Result,1); End If;
  return(Result);
end; $$ language 'plpgsql' volatile;
