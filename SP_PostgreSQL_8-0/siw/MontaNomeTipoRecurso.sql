create or replace function MontaNomeTipoRecurso(p_chave numeric, p_retorno varchar DEFAULT NULL)  RETURNS varchar AS $$
DECLARE
   c_ordem CURSOR FOR
     select a.sq_tipo_recurso, a.sq_tipo_pai, a.nome, b.level
       from eo_tipo_recurso a
            inner join (select sq_tipo_recurso, level 
                          from connectby('eo_tipo_recurso','sq_tipo_pai','sq_tipo_recurso',to_char(p_chave),0) 
                               as (sq_tipo_recurso numeric, sq_tipo_pai numeric, level int)
                       ) b on (a.sq_tipo_recurso = b.sq_tipo_recurso)
     order by level;

  Result varchar(2000) := '';
  w_pai  varchar(2000) := '';
BEGIN
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
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;