create or replace function minutos2horario(p_minutos numeric)  RETURNS varchar AS $$
DECLARE
  w_horas   numeric(4);
  w_minutos numeric(10)     := p_minutos;
  Result    varchar(255);
  w_sinal   numeric(2)      := 1;
BEGIN
  -- Trata minutos negativos
  If p_minutos < 0 Then w_sinal := -1; w_minutos := w_minutos * -1; End If;
  
  -- Extrai as horas e os minutos
  w_horas   := floor(w_minutos/60);
  w_minutos := w_minutos - (w_horas*60);
  
  -- Configura o formato de saída
  Result    := case when length(w_horas)=1 then '0' else '' end||w_horas||':'||substr(100+w_minutos,2,2);
  If w_sinal < 0 Then Result := '-'||Result; End If;
  
  return(Result);END; $$ LANGUAGE 'PLPGSQL' VOLATILE;