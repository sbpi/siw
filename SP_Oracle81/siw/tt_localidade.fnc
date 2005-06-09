create or replace function TT_Localidade(p_numero in varchar2) return number is
  Result number;
  l_sq_prefixo    tt_prefixos.sq_prefixo%type;
  l_prefixo       tt_prefixos.prefixo%type;
  l_numero        tt_ligacao.numero%type;
  l_reg           number(10);
begin
  Result := null;
  l_numero := p_numero;
  If length(p_numero) <= 8 Then 
     l_numero := '061'||p_numero;
  Else
     l_numero := p_numero;
  End If;
  for i in 1 .. 9 loop
      l_prefixo := substr(l_numero,1,i);
      select count(*) into  l_reg from tt_prefixos where prefixo like l_prefixo||'%';
      If l_reg = 1 Then
         select count(*) into  l_reg from tt_prefixos where prefixo = l_prefixo;
         If l_reg = 1 Then
            select sq_prefixo into Result from tt_prefixos where prefixo = l_prefixo;
            Exit;
         End If;
      End If;
  end loop;
  return(Result);
end TT_Localidade;
/

