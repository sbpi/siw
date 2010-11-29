create or replace function MontaNomeArquivoLocal(
       p_chave in number,
       p_retorno in varchar2 default null
       )
       return varchar2 is
  cursor c_ordem is
     select a.sq_arquivo_local, a.sq_local_pai, a.nome, b.nome as nm_arquivo
       from pa_arquivo_local      a
            inner join pa_arquivo b on (a.sq_localizacao = b.sq_localizacao)
     start with a.sq_arquivo_local = p_chave
     connect by prior a.sq_local_pai = a.sq_arquivo_local;

  Result varchar2(2000) := '';
  w_pai  varchar2(2000) := '';
  w_arq  varchar2(255);
begin
  -- Se não foi informada a chave, retorna nulo
  If p_chave is null Then return null; End If;

  -- Monta o nome varrendo do registro informado para cima
  for crec in c_ordem loop 
      w_arq := crec.nm_arquivo;
      Result :=  crec.nome || ' - ' || Result; 
  end loop;

  -- Se retornar apenas o primeiro nível
  If p_retorno = 'PRIMEIRO' Then
     Result := substr(Result,1,instr(Result,' - '));
  Else
     Result := substr(Result,1,length(Result)-3);
  End If;
  
  return w_arq||' - '||Result;
end MontaNomeArquivoLocal;
/
