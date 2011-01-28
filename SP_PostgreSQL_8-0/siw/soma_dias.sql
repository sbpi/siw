create or replace function soma_dias(
   p_cliente   in numeric,
   data_inicio in timestamp,
   dias        in numeric,
   contagem    in varchar) 
   returns date as $$
begin
  Return soma_dias(p_cliente,cast(data_inicio as date), dias, contagem);
end; $$ language 'plpgsql' volatile;
   
create or replace function soma_dias(
   p_cliente   in numeric,
   data_inicio in date,
   dias        in numeric,
   contagem    in varchar) 
   returns date as $$
/**********************************************************************************
* Nome      : soma_dias
* Finalidade: Retorna a data fim a partir da data inicio e o número de dias
* Autor     : Alexandre Vinhadelli Papadópolis
* Data      : 29/07/2008, 14:00
* Parâmetros:
*    data_inicio   : data inicial
*    dias          : número de dias a ser incrementado
*    contagem      : forma da contagem dos dias (C -> corridos, U -> úteis
* Retorno: data inicial acrescida do número de dias (corridos/úteis) informado
***********************************************************************************/
declare
  w_atual   date := to_date(to_char(data_inicio,'dd/mm/yyyy')||'000000','dd/mm/yyyyhh24miss');
  w_dias    numeric(10,1) := 1;
begin
  If upper(contagem) = 'C' Then
     w_atual := w_atual + dias;
  Else
     If dias >= 0 Then
        -- Se for contagem progressiva
         While w_dias <= dias Loop
           -- Incrementa a data atual
           w_atual := w_atual + 1;
    
           -- Verifica se pode incrementar o contador de dias
           If to_number(to_char(w_atual,'d')) not in (1,7) Then 
              If verificaDataEspecial(w_atual,p_cliente,null,null,null) <> 'N' Then 
                 w_dias := w_dias + 1;
              End If;
           End If;
         End Loop;
     Else
        w_dias := -1;
        -- Se for contagem regressiva
         While w_dias >= dias Loop
           -- Incrementa a data atual
           w_atual := w_atual - 1;
    
           -- Verifica se pode decrementar o contador de dias
           If to_number(to_char(w_atual,'d')) not in (1,7) Then 
              If verificaDataEspecial(w_atual,p_cliente,null,null,null) <> 'N' Then 
                 w_dias := w_dias - 1;
              End If;
           End If;
         End Loop;
     End If; 
  End If;

  Return w_atual;
end; $$ language 'plpgsql' volatile;
