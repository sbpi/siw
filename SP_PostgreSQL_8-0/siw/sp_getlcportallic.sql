create or replace FUNCTION SP_GetLcPortalLic
   (p_cliente           numeric,
    p_usuario           numeric,
    p_menu              numeric,
    p_chave             numeric,
    p_restricao         varchar,
    p_unidade           numeric,
    p_fonte             numeric,
    p_modalidade        numeric,
    p_finalidade        numeric,
    p_criterio          numeric,
    p_situacao          numeric,
    p_aber_i            date,
    p_aber_f            date,
    p_objeto            varchar,
    p_processo          varchar,
    p_empenho           varchar,
    p_publicar          varchar,
    p_pais              numeric,
    p_regiao            numeric,
    p_uf                varchar,
    p_cidade            numeric,
    p_result            REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
    
BEGIN
    -- Recupera as licitações que o usuário pode ver
    open p_result for 
       select a.sq_portal_lic,      a.cliente,           a.sq_unidade, 
              a.sq_lcmodalidade,    a.sq_lcfinalidade,   a.sq_lcjulgamento, a.sq_lcsituacao,
              a.sq_lcfonte_recurso, a.abertura,          a.objeto,          a.processo,        a.processo,
              a.empenho,            a.publicar,          a.observacao,      a.edital,          a.fundamentacao,
              b.nome nm_unid,       b.sigla sg_unid,     b.email mail_unid,
              c.nome nm_modalidade, c.sigla sg_modalidade,
              e.nome nm_finalidade,
              f.nome nm_criterio,
              g.nome nm_situacao,
              h.nome nm_fonte,
              case a.publicar when 'S' then 'Sim' else 'Não' end nm_publicar
         from lc_portal_lic                      a
              inner      join eo_unidade         b  on (a.sq_unidade         = b.sq_unidade)
                inner    join co_pessoa_endereco b1 on (b.sq_pessoa_endereco = b1.sq_pessoa_endereco)
                  inner  join co_cidade          b2 on (b1.sq_cidade         = b2.sq_cidade)
              inner      join lc_modalidade      c  on (a.sq_lcmodalidade    = c.sq_lcmodalidade)
              inner      join lc_finalidade      e  on (a.sq_lcfinalidade    = e.sq_lcfinalidade)
              inner      join lc_julgamento      f  on (a.sq_lcjulgamento    = f.sq_lcjulgamento)
              inner      join lc_situacao        g  on (a.sq_lcsituacao      = g.sq_lcsituacao)
              inner      join lc_fonte_recurso   h  on (a.sq_lcfonte_recurso = h.sq_lcfonte_recurso)
         where a.cliente         = p_cliente
           and 0                 < Acesso_Lc(a.sq_portal_lic, p_usuario, p_menu)
           and (p_chave          is null or (p_chave          is not null and a.sq_portal_lic          = p_chave))
           and (p_unidade        is null or (p_unidade        is not null and b.sq_unidade             = p_unidade))
           and (p_fonte          is null or (p_fonte          is not null and a.sq_lcfonte_recurso     = p_fonte))
           and (p_modalidade     is null or (p_modalidade     is not null and a.sq_lcmodalidade        = p_modalidade))
           and (p_finalidade     is null or (p_finalidade     is not null and a.sq_lcfinalidade        = p_finalidade))
           and (p_criterio       is null or (p_criterio       is not null and a.sq_lcjulgamento        = p_criterio))
           and (p_situacao       is null or (p_situacao       is not null and a.sq_lcsituacao          = p_situacao))
           and (p_aber_i         is null or (p_aber_i         is not null and a.abertura               between p_aber_i and p_aber_f))
           and (p_objeto         is null or (p_objeto         is not null and acentos(a.objeto,null)   like '%'||acentos(p_objeto,null)||'%'))
           and (p_processo       is null or (p_processo       is not null and acentos(a.processo,null) like '%'||acentos(p_processo,null)||'%'))
           and (p_empenho        is null or (p_empenho        is not null and acentos(a.empenho,null)  like '%'||acentos(p_empenho,null)||'%'))
           and (p_publicar       is null or (p_publicar       is not null and a.publicar               = p_publicar))
           and (p_pais           is null or (p_pais           is not null and b2.sq_pais               = p_pais))
           and (p_regiao         is null or (p_regiao         is not null and b2.sq_regiao             = p_regiao))
           and (p_uf             is null or (p_uf             is not null and b2.co_uf                 = p_uf))
           and (p_cidade         is null or (p_cidade         is not null and b2.sq_cidade             = p_cidade))
           ;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;