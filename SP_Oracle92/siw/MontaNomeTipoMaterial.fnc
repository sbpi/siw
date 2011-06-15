create or replace function MontaNomeTipoMaterial(p_chave in number, p_retorno in varchar2 default null, p_inverso in varchar2 default null) return varchar2 is
  cursor c_ordem is
     select sq_tipo_material, sq_tipo_pai, nome, sigla
       from cl_tipo_material
     start with sq_tipo_material = p_chave
     connect by prior sq_tipo_pai = sq_tipo_material;

  Result varchar2(2000) := '';
  Codigo varchar2(2000) := '';
  w_pai  varchar2(2000) := '';
begin
  -- Se não foi informada a chave, retorna nulo
  If p_chave is null Then return null; End If;
  
  -- Monta o nome varrendo do registro informado para cima
  If p_inverso is null 
     Then for crec in c_ordem loop Result :=  case when p_retorno = 'CODCOMP' then coalesce(Result,crec.nome || ' - ')  else crec.nome || ' - ' || Result end; codigo := crec.sigla || '.' || codigo; end loop;
     Else for crec in c_ordem loop Result :=  case when p_retorno = 'CODCOMP' then '' else Result end || ' - ' || crec.nome; codigo := crec.sigla || '.' || codigo; end loop;
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
end MontaNomeTipoMaterial;
/
