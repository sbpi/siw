CREATE OR REPLACE VIEW VW_PROJETOS AS
  select b.sq_siw_solicitacao, b.titulo, b.codigo_interno, b.codigo_externo, b.descricao
   from siw_solicitacao               b
           inner   join siw_tramite   b1 on (b.sq_siw_tramite     = b1.sq_siw_tramite)
           inner   join pj_projeto    d  on (b.sq_siw_solicitacao = d.sq_siw_solicitacao)
   where coalesce(b1.sigla,'-') not in ('CA','AT');
