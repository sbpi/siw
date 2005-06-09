create or replace function MontaOrdem(p_chave in number) return varchar2 is
  cursor c_ordem is
     select sq_projeto_etapa, sq_etapa_pai, titulo, ordem
       from pj_projeto_etapa
     start with sq_projeto_etapa   = p_chave
     connect by prior sq_etapa_pai = sq_projeto_etapa; 

  Result varchar2(200) := '';
begin
  If p_chave is null Then return null; End If;
  for crec in c_ordem loop
     Result :=  substr(1000 + crec.ordem, 2,3) || Result;
  end loop;
  return(Result);
end MontaOrdem;
/

