create or replace view VW_PROJETO_FINANCEIRO as
-- Pagamentos com detalhamento de itens
select 'I' TIPO, b.sq_siw_solicitacao sq_projeto, b.codigo_interno cd_projeto,
       /*montaOrdemRubrica(e1.sq_projeto_rubrica,'ORDENACAO') ordena,*/ e1.nome nm_rubrica, 
       e.sq_projeto_rubrica, a.sq_siw_solicitacao sq_financeiro, a.codigo_interno cd_financeiro,
       case when f.sq_siw_solicitacao is null then e.valor_total else e.valor_total*f.fator end valor, 
       case when f.sq_siw_solicitacao is null then a2.sq_moeda   else b2.sq_moeda           end sq_fn_moeda, 
       case when f.sq_siw_solicitacao is null then a2.sigla      else b2.sigla              end sg_fn_moeda, 
       b2.sq_moeda sq_pj_moeda, b2.sigla sg_pj_moeda
  from siw_solicitacao                      a
       inner         join siw_tramite       a1 on (a.sq_siw_tramite      = a1.sq_siw_tramite and a1.sigla in ('EE','AT'))
       inner         join co_moeda          a2 on (a.sq_moeda            = a2.sq_moeda)
       inner         join fn_lancamento     c  on (a.sq_siw_solicitacao  = c.sq_siw_solicitacao)
       inner         join fn_lancamento_doc d  on (a.sq_siw_solicitacao  = d.sq_siw_solicitacao)
         inner       join fn_documento_item e  on (d.sq_lancamento_doc   = e.sq_lancamento_doc)
           inner     join pj_rubrica        e1 on (e.sq_projeto_rubrica  = e1.sq_projeto_rubrica)
             inner   join siw_solicitacao   b  on (e1.sq_siw_solicitacao = b.sq_siw_solicitacao)
               inner join co_moeda          b2 on (b.sq_moeda            =  b2.sq_moeda)
       left      join (select k.sq_siw_solicitacao, m.valor,
                              case when k.valor > m.valor then m.valor/k.valor else k.valor/m.valor end fator
                        from siw_solicitacao              k
                             inner join siw_tramite      k1 on (k.sq_siw_tramite     = k1.sq_siw_tramite and k1.ativo = 'N')
                             inner join siw_solicitacao   l on (k.sq_solic_pai       = l.sq_siw_solicitacao)
                             inner join siw_solic_cotacao m on (k.sq_siw_solicitacao = m.sq_siw_solicitacao and
                                                                l.sq_moeda           = m.sq_moeda and
                                                                m.valor              > 0
                                                               )
                      )                 f  on (a.sq_siw_solicitacao = f.sq_siw_solicitacao)
UNION
-- Pagamentos sem detalhamento de itens
select 'D' TIPO, b.sq_siw_solicitacao sq_projeto, b.codigo_interno cd_projeto,
       /*montaOrdemRubrica(c.sq_projeto_rubrica,'ORDENACAO') ordena,*/ c1.nome nm_rubrica, 
       c.sq_projeto_rubrica, a.sq_siw_solicitacao sq_financeiro, a.codigo_interno cd_financeiro,
       case when f.sq_siw_solicitacao is null then a.valor       else f.valor               end valor, 
       case when f.sq_siw_solicitacao is null then a2.sq_moeda   else b2.sq_moeda           end sq_fn_moeda, 
       case when f.sq_siw_solicitacao is null then a2.sigla      else b2.sigla              end sg_fn_moeda, 
       b2.sq_moeda sq_pj_moeda, b2.sigla sg_pj_moeda
  from siw_solicitacao                    a
       inner       join siw_tramite       a1 on (a.sq_siw_tramite      = a1.sq_siw_tramite and a1.sigla in ('EE','AT'))
       inner       join co_moeda          a2 on (a.sq_moeda            = a2.sq_moeda)
       inner       join fn_lancamento     c  on (a.sq_siw_solicitacao  = c.sq_siw_solicitacao)
         inner     join pj_rubrica        c1 on (c.sq_projeto_rubrica  = c1.sq_projeto_rubrica)
           inner   join siw_solicitacao   b  on (c1.sq_siw_solicitacao = b.sq_siw_solicitacao)
             inner join co_moeda          b2 on (b.sq_moeda            =  b2.sq_moeda)
       inner       join fn_lancamento_doc d  on (a.sq_siw_solicitacao  = d.sq_siw_solicitacao)
         left      join fn_documento_item e  on (d.sq_lancamento_doc   = e.sq_lancamento_doc)
       left        join (select k.sq_siw_solicitacao, m.valor
                          from siw_solicitacao              k
                               inner join siw_tramite      k1 on (k.sq_siw_tramite     = k1.sq_siw_tramite and k1.ativo = 'N')
                               inner join siw_solicitacao   l on (k.sq_solic_pai       = l.sq_siw_solicitacao)
                               inner join siw_solic_cotacao m on (k.sq_siw_solicitacao = m.sq_siw_solicitacao and
                                                                  l.sq_moeda           = m.sq_moeda
                                                                 )
                        )                 f  on (a.sq_siw_solicitacao = f.sq_siw_solicitacao)
 where e.sq_documento_item is null;
