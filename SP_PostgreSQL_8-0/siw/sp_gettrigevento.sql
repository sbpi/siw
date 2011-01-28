create or replace FUNCTION SP_GetTrigEvento
   (p_chave     numeric,
    p_chave_aux numeric,
    p_result    REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
  -- Recupera os recursos alocados a uma etapa do projeto
  open p_result for 
     select a.sq_trigger, a.sq_tabela, a.sq_usuario, a.sq_sistema, a.nome, a.descricao,
            b.sq_evento, b.nome nm_evento, b.descricao ds_evento, 
            c.sq_trigger existe,
            d.nome nm_tabela,
            e.nome nm_sistema,
            f.nome nm_usuario
       from dc_trigger a
              inner join dc_tabela d on (a.sq_tabela = d.sq_tabela)
              inner join dc_sistema e on (a.sq_sistema = e.sq_sistema)
              inner join dc_usuario f on (a.sq_usuario = f.sq_usuario),
            dc_evento  b 
              left outer join dc_trigger_evento c on (b.sq_evento = c.sq_evento and c.sq_trigger = p_chave)
      where a.sq_trigger = p_chave;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;