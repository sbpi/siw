create or replace FUNCTION SP_GetLcFonte
   (p_chave      numeric,
    p_cliente    numeric,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera as fontes de recurso de licitações
   open p_result for 
      select a.sq_lcfonte_recurso chave, a.cliente, a.nome, a.descricao, a.ativo, a.padrao, a.orcamentario,
             case a.orcamentario  when 'S' then 'Sim' else 'Não' end nm_orcamentario,
             case a.ativo         when 'S' then 'Sim' else 'Não' end nm_ativo,
             case a.padrao        when 'S' then 'Sim' else 'Não' end nm_padrao
        from lc_fonte_recurso a
       where a.cliente = p_cliente 
         and ((p_chave is null) or (p_chave is not null and a.sq_lcfonte_recurso = p_chave));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;