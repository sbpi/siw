create or replace function retornaAfericaoIndicador(p_chave in number, p_data in date default null, p_tipo in varchar2 default null) return float is
 -- p_chave: chave da tabela EO_INDICADOR
 -- p_data : data desejada para verificação do valor. Se nulo, retorna a mais recente.
 -- p_tipo : nulo   = retorna apenas se foi encontrada aferição na data informada
 --          ABAIXO = se não encontrar aferição na data informada, recupera a apuração mais próxima antes dela
 --          ACIMA  = se não encontrar aferição na data informada, recupera a primeira mais próxima depois dela
  Result   float;
  w_existe number(18) := 0;
  
  cursor c_afericao is
     select a.valor
       from eo_indicador_afericao a 
      where sq_eoindicador = p_chave 
        and ((p_tipo       is null    and a.data_afericao  = p_data) or 
             (p_tipo       = 'ABAIXO' and a.data_afericao <= p_data) or
             (p_tipo       = 'ACIMA'  and a.data_afericao >= p_data)
            )
     order by a.data_afericao desc;
begin
  
  for crec in c_afericao loop
     w_existe := 1;
     Result   := crec.valor;
     If p_tipo = 'ABAIXO' Then exit; End If;
  end loop;
  If w_existe = 0 Then
     Result := null;
  End If;
  
  return(Result);
end retornaAfericaoIndicador;
/
