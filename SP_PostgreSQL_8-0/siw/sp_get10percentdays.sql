create or replace FUNCTION SP_Get10PercentDays
   (p_inicio     date,
    p_fim        date,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os 10% de dias do prazo da tarefa
   open p_result for 
      select ceil((p_fim - p_inicio)*0.1) dias from dual;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;