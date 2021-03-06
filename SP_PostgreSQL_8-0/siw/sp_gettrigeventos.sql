create or replace FUNCTION SP_GetTrigEventos
   (p_chave     numeric,
    p_chave_aux numeric,
    p_result    REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
  -- Recupera os recursos alocados a uma etapa do projeto
  open p_result for 
     select a.sq_trigger chave, a.sq_evento chaveAux,
            b.nome nm_trigger, b.descricao,
            c.nome nm_evento, c.descricao ds_evento
       from dc_trigger_evento       a
              inner join dc_trigger b on (a.sq_trigger = b.sq_trigger)
              inner join dc_evento  c on (a.sq_evento  = c.sq_evento)
      where ((P_chave     is null) or (P_chave     is not null and a.sq_trigger = P_chave))
        and ((p_chave_aux is null) or (p_chave_aux is not null and a.sq_evento  = p_chave_aux));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;