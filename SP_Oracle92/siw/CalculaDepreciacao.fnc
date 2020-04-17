create or replace function CalculaDepreciacao
     ( p_chave in number,          -- Chave de MT_PERMANENTE
       p_moeda in number,          -- Moeda desejada para o c�lculo da deprecia��o
       p_data in date default null -- Data desejada para o c�lculo da deprecia��o. Se nulo, usa a data atual.
     ) return number is
     
  Result number;
  
begin
  for crec in (select a.vida_util, a.data_tombamento, b.valor_aquisicao,
                      trunc(months_between(coalesce(p_data,trunc(sysdate)),a.data_tombamento),0) meses
                 from mt_permanente             a
                      inner join mt_bem_cotacao b on a.sq_permanente = b.sq_permanente
                where a.sq_permanente = p_chave
                  and b.sq_moeda      = p_moeda
              )
  loop
    If crec.meses = 0 Then
       -- Se o tombamento ocorreu a at� um m�s, o valor atual ser� igual ao de aquisi��o.
       Result := crec.valor_aquisicao;
    Elsif crec.meses >= crec.vida_util *12 Then
       -- Se o tombamento ocorreu h� mais tempo que o definido na vida �til do bem, o valor atual ser� igual a 0 (zero).
       Result := 0;
    Else
       -- Caso contr�rio, abate do valor de aquisi��o o valor correspondente aos meses decorridos desde o tombamento.
       Result := crec.valor_aquisicao - (crec.valor_aquisicao / (crec.vida_util * 12) * crec.meses);
    End If;
  end loop;
  
  -- Retorna o valor atual do bem truncado em duas posi��es.
  return(trunc(Result,2));
end CalculaDepreciacao;
/
