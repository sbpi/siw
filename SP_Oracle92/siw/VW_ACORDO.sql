CREATE OR REPLACE VIEW VW_ACORDO AS
  select a.sq_menu, a1.codigo_interno as cd_projeto, a.codigo_interno cd_acordo,
         b.inicio, b.fim, 
         c.ordem, c.vencimento, c.valor, c.quitacao
    from siw_solicitacao                          a
         inner            join siw_solicitacao    a1 on (a.sq_solic_pai       = a1.sq_siw_solicitacao)
         inner            join ac_acordo          b  on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
           inner          join co_pessoa          g  on (b.outra_parte        = g.sq_pessoa)
           inner          join siw_tramite        j  on (a.sq_siw_tramite     = j.sq_siw_tramite)
           inner          join ac_acordo_parcela  c  on (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
             left outer   join (select x.sq_acordo_parcela, count(*) as existe
                                  from fn_lancamento                x
                                       inner   join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                         inner join siw_tramite     z on (y.sq_siw_tramite     = z.sq_siw_tramite and
                                                                          'CA'                 <> Nvl(z.sigla,'CA')
                                                                         )
                                group by x.sq_acordo_parcela
                               )                  d  on (c.sq_acordo_parcela  = d.sq_acordo_parcela)
   where (b.financeiro_unico = 'N' or (b.financeiro_unico = 'S' and coalesce(d.existe,0) = 0));
