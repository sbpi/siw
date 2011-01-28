create or replace function MontaNomeArquivoLocal(
       p_chave numeric,
       p_retorno varchar DEFAULT NULL
       )
        RETURNS varchar AS $$
DECLARE
   c_ordem CURSOR FOR
     select a.sq_arquivo_local, a.sq_local_pai, a.nome, b.level
       from pa_arquivo_local a
            inner join (select sq_arquivo_local, level 
                          from connectby('pa_arquivo_local','sq_local_pai','sq_arquivo_local',to_char(p_chave),0) 
                               as (sq_arquivo_local numeric, sq_local_pai numeric, level int)
                       ) b on (a.sq_arquivo_local = b.sq_arquivo_local)
     order by level;

  Result varchar(2000) := '';
  w_pai  varchar(2000) := '';
  w_arq  varchar(255);
BEGIN
  -- Se não foi informada a chave, retorna nulo
  If p_chave is null Then return null; End If;

  -- Monta o nome varrendo do registro informado para cima
  for crec in c_ordem loop 
      w_arq := crec.nm_arquivo;
      Result :=  crec.nome || ' - ' || Result; 
  end loop;

  -- Se retornar apenas o primeiro nível
  If result <> '' Then 
     If p_retorno = 'PRIMEIRO' Then
        Result := substr(Result,1,instr(Result,' - '));
     Else
        Result := substr(Result,1,length(Result)-3);
     End If;
  End If;
  
  return w_arq||' - '||Result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;