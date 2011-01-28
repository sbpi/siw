create or replace function MontaNomeTipoMaterial(p_chave numeric, p_retorno varchar DEFAULT NULL, p_inverso varchar DEFAULT NULL)  RETURNS varchar AS $$
DECLARE
   c_ordem CURSOR FOR
     select a.sq_tipo_material, a.sq_tipo_pai, a.nome, a.sigla, b.level
       from cl_tipo_material a
            inner join (select sq_tipo_material, level 
                          from connectby('cl_tipo_material','sq_tipo_pai','sq_tipo_material',to_char(p_chave),0) 
                               as (sq_tipo_material numeric, sq_tipo_pai numeric, level int)
                       ) b on (a.sq_tipo_material = b.sq_tipo_material)
     order by level;

  Result varchar(2000) := '';
  Codigo varchar(2000) := '';
  w_pai  varchar(2000) := '';
BEGIN
  -- Se não foi informada a chave, retorna nulo
  If p_chave is null Then return null; End If;
  
  -- Monta o nome varrendo do registro informado para cima
  If p_inverso is null 
     Then for crec in c_ordem loop Result :=  crec.nome || ' - ' || Result; codigo := crec.sigla || '.' || codigo; end loop;
     Else for crec in c_ordem loop Result :=  Result || ' - ' || crec.nome; codigo := crec.sigla || '.' || codigo; end loop;
  End If;
  
  -- Se retornar apenas o primeiro nível
  If p_inverso is null Then
     If p_retorno = 'PRIMEIRO' Then
        return(codigo||' '||substr(Result,1,instr(Result,' - ')));
     Else
        return(codigo||' '||substr(Result,1,length(Result)-3));
     End If;
  Else
     If p_retorno = 'PRIMEIRO' Then
        return(codigo||' '||substr(Result,1,instr(Result,' - ')));
     Else
        If substr(Result,1,3) = ' - '
           Then return(codigo||' '||substr(Result,4));
           Else return codigo||' '||Result;
        End If;
     End If;
  End If;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;