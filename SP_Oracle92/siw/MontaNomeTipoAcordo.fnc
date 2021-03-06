create or replace function MontaNomeTipoAcordo(p_chave in number, p_retorno in varchar2 default null) return varchar2 is
  cursor c_ordem is
     select sq_tipo_acordo, sq_tipo_acordo_pai, nome
       from ac_tipo_acordo
     start with sq_tipo_acordo = p_chave
     connect by prior sq_tipo_acordo_pai = sq_tipo_acordo;

  Result varchar2(2000) := '';
  w_pai  varchar2(2000) := '';
begin
  -- Se n�o foi informada a chave, retorna nulo
  If p_chave is null Then return null; End If;
  
  -- Monta o nome varrendo do registro informado para cima
  for crec in c_ordem loop Result :=  crec.nome || ' - ' || Result; end loop;
  
  -- Se retornar apenas o primeiro n�vel
  If p_retorno = 'PRIMEIRO' Then
     return(substr(Result,1,instr(Result,' - ')));
  Else
     return(substr(Result,1,length(Result)-3));
  End If;
end MontaNomeTipoAcordo;
/
