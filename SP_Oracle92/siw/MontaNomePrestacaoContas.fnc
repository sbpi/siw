create or replace function MontaNomePrestacaoContas(p_chave in number, p_retorno in varchar2 default null) return varchar2 is
  cursor c_ordem is
     select sq_prestacao_contas, sq_prestacao_pai, nome
       from ac_prestacao_contas
     start with sq_prestacao_contas = p_chave
     connect by prior sq_prestacao_pai = sq_prestacao_contas;

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
end MontaNomePrestacaoContas;
/
