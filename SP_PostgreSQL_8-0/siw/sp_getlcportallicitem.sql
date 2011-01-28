create or replace FUNCTION SP_GetLcPortalLicItem
   (p_cliente   numeric,
    p_chave     numeric,
    p_chave_aux numeric,
    p_nome      varchar,
    p_cancelado varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os itens de uma licitação
   open p_result for 
      select a.sq_portal_lic chave, a.sq_portal_lic_item, a.ordem, a.nome,
             a.descricao, a.descricao, a.quantidade, a.valor_unitario, a.valor_total,
             a.cancelado, a.situacao, a.sq_unidade_fornec, 
             b.nome nm_unidade_fornec, b.sigla sg_unidade_fornec,
              case a.cancelado when 'S' then 'Sim' else 'Não' end nm_cancelado
        from lc_portal_lic_item a 
             left outer join lc_unidade_fornec b on a.sq_unidade_fornec = b.sq_unidade_fornec
                                                and a.cliente           = b.cliente 
       where a.cliente       = p_cliente
         and a.sq_portal_lic = p_chave
         and (p_chave_aux    is null or (p_chave_aux is not null and a.sq_portal_lic_item = p_chave_aux))
         and (p_nome         is null or (p_nome      is not null and acentos(a.nome,null)   like '%'||acentos(p_nome,null)||'%'))
         and (p_cancelado    is null or (p_cancelado is not null and a.cancelado = p_cancelado))
      order by a.ordem;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;