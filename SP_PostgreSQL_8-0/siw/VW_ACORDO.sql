create or replace view vw_acordo as
select z.sq_pessoa as cliente, z.nome as nm_menu, z.sq_menu,
         a1.codigo_interno as cd_projeto, a.codigo_interno cd_acordo,
         b.inicio, b.fim
    from siw_menu                            z
         inner       join siw_solicitacao    a  on (z.sq_menu            = a.sq_menu)
           inner     join siw_solicitacao    a1 on (a.sq_solic_pai       = a1.sq_siw_solicitacao)
           inner     join ac_acordo          b  on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
             inner   join co_pessoa          g  on (b.outra_parte        = g.sq_pessoa)
             inner   join siw_tramite        j  on (a.sq_siw_tramite     = j.sq_siw_tramite);
