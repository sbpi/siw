create or replace FUNCTION SP_GetEventoTrigger
   (p_chave      numeric,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os eventos de trigger existentes
   open p_result for 
      select a.sq_evento chave, a.nome, a.descricao
        from dc_evento a
       where ((p_chave is null) or (p_chave is not null and a.sq_evento = p_chave));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;