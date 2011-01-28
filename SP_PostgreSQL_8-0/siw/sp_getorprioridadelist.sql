create or replace FUNCTION SP_GetOrPrioridadeList
   (p_chave            numeric,
    p_cliente          numeric,
    p_sq_orprioridade  numeric,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera as iniciativas prioritárias do Governo
   open p_result for 
      select a.sq_orprioridade chave, a.nome, b.sq_orprioridade existe, a.codigo, a.ordem,
             case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
             case a.padrao when 'S' then 'Sim' else 'Não' end nm_padrao
        from or_prioridade a
             left outer join or_acao_prioridade b on a.sq_orprioridade    = b.sq_orprioridade
                                                 and b.sq_siw_solicitacao = p_chave
       where a.cliente = p_cliente 
         and ((p_sq_orprioridade is null) or (p_sq_orprioridade is not null and a.sq_orprioridade <> p_sq_orprioridade));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;