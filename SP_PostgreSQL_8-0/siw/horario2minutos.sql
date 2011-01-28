create or replace function horario2minutos(
   hora_inicio varchar, -- hora no formato HH:MM. Se nulo, será assumido 00:00
   hora_fim    varchar   --  hora no formato HH:MM. Se nulo, será assumido 24:00
  )  RETURNS numeric AS $$
DECLARE

  w_inicio  varchar(12) := coalesce(hora_inicio,'00:00');
  w_fim     varchar(12) := coalesce(hora_fim,'24:00');
  w_min_ini numeric(18);
  w_min_fim numeric(18);
  w_sin_ini numeric(2) := 1;
  w_sin_fim numeric(2) := 1;
  w_sinal   numeric(2);

  Result    numeric;

BEGIN
  -- Configura o sinal e ajusta os horários informados
  If substr(w_inicio,1,1) = '-' Then w_sin_ini := -1; w_inicio := substr(w_inicio,2); End If;
  If substr(w_fim,1,1) = '-'    Then w_sin_fim := -1; w_fim    := substr(w_fim,2);    End If;
  -- Configura o sinal do resultado
  w_sinal := w_sin_ini * w_sin_fim;
     
  w_min_ini := substr(w_inicio,1,2)*60+substr(w_inicio,4,2);
  w_min_fim := substr(w_fim,1,2)*60+substr(w_fim,4,2);
  result := w_min_fim - w_min_ini;
  return(w_sinal*Result);END; $$ LANGUAGE 'PLPGSQL' VOLATILE;