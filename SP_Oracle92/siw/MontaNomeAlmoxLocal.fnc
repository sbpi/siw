create or replace function MontaNomeAlmoxLocal(p_chave in number, p_retorno in varchar2 default null) return varchar2 is
  cursor c_ordem is
     select sq_almoxarifado_local, sq_local_pai, nome
       from mt_almoxarifado_local
     start with sq_almoxarifado_local = p_chave
     connect by prior sq_local_pai = sq_almoxarifado_local;

  Result varchar2(2000) := '';
  w_pai  varchar2(2000) := '';
begin
  -- Se não foi informada a chave, retorna nulo
  If p_chave is null Then return null; End If;
  
  -- Monta o nome varrendo do registro informado para cima
  for crec in c_ordem loop Result :=  crec.nome || ' - ' || Result; end loop;
  
  -- Se retornar apenas o primeiro nível
  If p_retorno = 'PRIMEIRO' Then
     return(substr(Result,1,instr(Result,' - ')));
  Else
     return(substr(Result,1,length(Result)-3));
  End If;
end MontaNomeAlmoxLocal;
/
