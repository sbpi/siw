create or replace function MontaOrdemTipoLancamento(p_chave in number) return varchar2 is
  Result  varchar2(2000) := '';
  l_nome  fn_tipo_lancamento.nome%type;
  Posicao number(3);
  Lista   varchar2(200);
begin
  /* Para correto funcionamento, o nome deve ser precedido dos algarismos de classificação financeira. Ex: 1. Despesa; 4.1. Salários etc. */
  
  If p_chave is null Then return null; End If;
  
  select nome into l_nome from fn_tipo_lancamento where sq_tipo_lancamento = p_chave;
  
  -- Recupera a classificação financeira
  posicao := instr(l_nome,' ')-1;
  lista   := substr(l_nome,1,posicao);
  
  -- Concatena zeros à esquerda de cada número
  while instr(lista,'.') > 0 loop
      posicao := instr(lista,'.');
      Result := Result || lpad(substr(lista,1,posicao-1),4,'0');
      lista := substr(lista,posicao+1);
  end loop;
  
  Result := Result ||substr(l_nome,instr(l_nome,' '));

  return(Result);
end MontaOrdemTipoLancamento;
/
