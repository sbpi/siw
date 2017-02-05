CREATE OR REPLACE VIEW VW_ACORDO_PARCELA AS
  select z.sq_pessoa as cliente, z.nome as nm_menu, z.sq_menu, 
         a.codigo_interno cd_acordo,
         b1.codigo_interno as cd_projeto, b.inicio, b.fim, 
         c.ordem, c.vencimento, c.valor, c.quitacao
    from siw_menu                            z
         inner       join siw_solicitacao    a  on (z.sq_menu            = a.sq_menu)
           inner     join ac_acordo          b  on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
           inner     join siw_solicitacao    b1 on (b.sq_solic_vinculo   = b1.sq_siw_solicitacao)
             inner   join co_pessoa          g  on (b.outra_parte        = g.sq_pessoa)
             inner   join siw_tramite        j  on (a.sq_siw_tramite     = j.sq_siw_tramite)
             inner   join ac_acordo_parcela  c  on (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
               left  join (select x.sq_acordo_parcela, count(*) as existe
                                    from fn_lancamento                x
                                         inner   join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                           inner join siw_tramite     z on (y.sq_siw_tramite     = z.sq_siw_tramite and
                                                                            'CA'                 <> Nvl(z.sigla,'CA')
                                                                           )
                                  group by x.sq_acordo_parcela
                                 )                  d  on (c.sq_acordo_parcela  = d.sq_acordo_parcela)
   where (b.financeiro_unico = 'N' or (b.financeiro_unico = 'S' and coalesce(d.existe,0) = 0));
