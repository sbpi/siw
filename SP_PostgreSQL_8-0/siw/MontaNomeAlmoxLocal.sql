create or replace function MontaNomeAlmoxLocal(p_chave numeric, p_retorno varchar DEFAULT NULL)  RETURNS varchar AS $$
DECLARE
   c_ordem CURSOR FOR
     select a.sq_almoxarifado_local, a.sq_local_pai, a.nome, b.level
       from mt_almoxarifado_local a
            inner join (select sq_almoxarifado_local, level 
                          from connectby('mt_almoxarifado_local','sq_local_pai','sq_almoxarifado_local',to_char(p_chave),0) 
                               as (sq_almoxarifado_local numeric, sq_local_pai numeric, level int)
                       ) b on (a.sq_almoxarifado_local = b.sq_almoxarifado_local)
     order by level;

  Result varchar(2000) := '';
  w_pai  varchar(2000) := '';
BEGIN
  -- Se não foi informada a chave, retorna nulo
  If p_chave is null Then return null; End If;
  
  -- Monta o nome varrendo do registro informado para cima
  for crec in c_ordem loop Result :=  crec.nome || ' - ' || Result; end loop;
  
  -- Se retornar apenas o primeiro nível
  If result <> '' Then 
     If p_retorno = 'PRIMEIRO' Then
        return(substr(Result,1,instr(Result,' - ')));
     Else
        return(substr(Result,1,length(Result)-3));
     End If;
  Else
     return Result;
  End If;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;