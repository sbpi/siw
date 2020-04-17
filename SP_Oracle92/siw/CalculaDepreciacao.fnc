create or replace function CalculaDepreciacao
     ( p_chave in number,          -- Chave de MT_PERMANENTE
       p_moeda in number,          -- Moeda desejada para o cálculo da depreciação
       p_data in date default null -- Data desejada para o cálculo da depreciação. Se nulo, usa a data atual.
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
       -- Se o tombamento ocorreu a até um mês, o valor atual será igual ao de aquisição.
       Result := crec.valor_aquisicao;
    Elsif crec.meses >= crec.vida_util *12 Then
       -- Se o tombamento ocorreu há mais tempo que o definido na vida útil do bem, o valor atual será igual a 0 (zero).
       Result := 0;
    Else
       -- Caso contrário, abate do valor de aquisição o valor correspondente aos meses decorridos desde o tombamento.
       Result := crec.valor_aquisicao - (crec.valor_aquisicao / (crec.vida_util * 12) * crec.meses);
    End If;
  end loop;
  
  -- Retorna o valor atual do bem truncado em duas posições.
  return(trunc(Result,2));
end CalculaDepreciacao;
/
