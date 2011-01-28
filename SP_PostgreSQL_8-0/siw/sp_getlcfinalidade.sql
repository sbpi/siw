create or replace FUNCTION SP_GetLcFinalidade
   (p_chave      numeric,
    p_cliente    numeric,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera as finalidades de licitação
   open p_result for 
      select a.sq_lcfinalidade chave, a.cliente, a.nome, a.descricao, a.ativo, a.padrao,
             case a.ativo  when 'S' then 'Sim' else 'Não' end nm_ativo,
             case a.padrao when 'S' then 'Sim' else 'Não' end nm_padrao
        from lc_finalidade a
       where a.cliente = p_cliente 
         and ((p_chave is null) or (p_chave is not null and a.sq_lcfinalidade = p_chave));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;