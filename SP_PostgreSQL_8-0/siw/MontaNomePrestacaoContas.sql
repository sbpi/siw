create or replace function MontaNomePrestacaoContas(p_chave numeric, p_retorno varchar DEFAULT NULL)  RETURNS varchar AS $$
DECLARE
   c_ordem CURSOR FOR
     select a.sq_prestacao_contas, a.sq_prestacao_pai, a.nome, b.level
       from ac_prestacao_contas a
            inner join (select sq_prestacao_contas, level 
                          from connectby('ac_prestacao_contas','sq_prestacao_pai','sq_prestacao_contas',to_char(p_chave),0) 
                               as (sq_prestacao_contas numeric, sq_prestacao_pai numeric, level int)
                       ) b on (a.sq_prestacao_contas = b.sq_prestacao_contas)
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