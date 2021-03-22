create or replace function CalculaDepreciacao
     ( p_chave in number,          -- Chave de MT_PERMANENTE
       p_moeda in number,          -- Moeda desejada para o cálculo da depreciação
       p_inicio in date default null, -- Data desejada para início do período de depreciação. Se nulo, usa a data de tombamento.
       p_fim    in date default null -- Data desejada para fim do período de depreciação. Se nulo, usa a data atual.
     ) return number is
     
  w_dias_depreciacao    number;
  w_vida_util_em_dias   number;
  w_fim_vida_util       date;
  w_depreciacao_diaria  number;
  w_inicio              date;
  w_fim                 date;
  w_atual               date;
  w_dias_2902           number;
  
  Result number := 0;
  
begin
  for crec in (select a.data_tombamento, b.valor_atual, a.vida_util
                 from mt_permanente             a
                      inner join mt_bem_cotacao b on a.sq_permanente = b.sq_permanente
                where a.sq_permanente = p_chave
                  and b.sq_moeda      = p_moeda
              )
  loop
     w_vida_util_em_dias  := crec.vida_util * 365; -- Não considera ano bissexto
     w_depreciacao_diaria := crec.valor_atual / w_vida_util_em_dias;
     w_fim_vida_util      := crec.data_tombamento + (crec.vida_util * 365) - 1;

     If p_inicio is not null and p_fim is not null and w_fim_vida_util < p_inicio Then
        -- Se início e fim do período foram informados e a vida útil acabou antes do início do período, não tem valor de depreciação
        Result := 0;
     Elsif p_inicio is null and p_fim > w_fim_vida_util Then
        -- Se período começa depois da vida útil concluída ou acaba antes do tombamento do bem, não há depreciação
        Result := crec.valor_atual;
     Else
        -- Trata a data de início do período
        If p_inicio is null or p_inicio <= crec.data_tombamento Then 
              w_inicio := crec.data_tombamento;
        Else
           w_inicio := p_inicio;
        End If;
       
        -- Trata a data de término do período
        If p_fim is null Then
           w_fim := trunc(sysdate);
        Else
           If p_fim > w_fim_vida_util Then
              w_fim := w_fim_vida_util;
           Else
              w_fim := p_fim;
           End If;
        End If;
       
        w_dias_depreciacao := (w_fim - w_inicio) + 1;
        
        -- Descarta 29 de fevereiro, pois a depreciação sempre trabalha com 28 dias em fevereiro
        w_atual := w_inicio;
        w_dias_2902 := 0;
        while w_atual <= w_fim loop
            If to_char(last_day(w_atual), 'dd/mm') = '29/02' and last_day(w_atual) <= w_fim Then
               w_dias_2902 := w_dias_2902 + 1;
            End If;
            w_atual := add_months(w_atual, 1);
        end loop;
        
        w_dias_depreciacao := w_dias_depreciacao - w_dias_2902;
        
        Result := w_dias_depreciacao * w_depreciacao_diaria;
        
        If Result > crec.valor_atual Then 
          Result := crec.valor_atual; 
        Elsif Result < 0 Then
          Result := 0;
        End If;
    End If;
  end loop;
  
  -- Retorna o valor atual do bem truncado em duas posições.
  return(round(Result,2));
end CalculaDepreciacao;
/
