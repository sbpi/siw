create or replace function MontaNomeTipoRecurso(p_chave in number) return varchar2 is
  cursor c_ordem is
     select sq_tipo_recurso, sq_tipo_pai, nome
       from eo_tipo_recurso
     start with sq_tipo_recurso = p_chave
     connect by prior sq_tipo_pai = sq_tipo_recurso;

  Result varchar2(2000) := '';
begin
  If p_chave is null Then return null; End If;
  for crec in c_ordem loop
     Result :=  crec.nome || ' - ' || Result;
  end loop;
  return(substr(Result,1,length(Result)-3));
end MontaNomeTipoRecurso;
/
