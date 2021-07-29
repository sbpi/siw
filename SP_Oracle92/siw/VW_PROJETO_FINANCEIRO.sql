create or replace view VW_PROJETO_FINANCEIRO as
-- Pagamentos com detalhamento de itens
select 'I' TIPO, e2.qtd_itens,
       b.sq_siw_solicitacao sq_projeto, b.codigo_interno cd_projeto, a1.sigla sg_tramite,
       /*montaOrdemRubrica(e1.sq_projeto_rubrica,'ORDENACAO') ordena,*/ e1.nome nm_rubrica, e1.sq_rubrica_pai, e1.aplicacao_financeira,
       e.sq_projeto_rubrica, 
       a.sq_siw_solicitacao sq_financeiro, a.codigo_interno cd_financeiro, a.codigo_externo cd_financeiro_externo,
       a.descricao||' - '||e.descricao ds_financeiro,
       case when f.sq_siw_solicitacao is null then e.valor_total
            when e2.qtd_itens = 1 then f.valor 
            when e2.qtd_itens = 2 then round(e.valor_total*f.fator,2) -- 2 itens arredonda para não dar erro
            else e.valor_total*f.fator -- mais de 2 itens, só retorna o valor
       end valor, -- Valor convertido na moeda do projeto
       case when f.sq_siw_solicitacao is null then a2.sq_moeda   else b2.sq_moeda           end sq_fn_moeda, 
       case when f.sq_siw_solicitacao is null then a2.sigla      else b2.sigla              end sg_fn_moeda, 
       case when f.sq_siw_solicitacao is null then a2.simbolo    else b2.simbolo            end sb_fn_moeda, 
       e.valor_total fn_valor,    -- Valor na moeda do pagamento
       a2.sq_moeda   fn_sq_moeda, -- Chave da moeda do pagamento
       a2.sigla      fn_sg_moeda, -- Sigla da moeda do pagamento
       a2.simbolo    fn_sb_moeda, -- Símbolo da moeda do pagamento
       a3.sigla      sg_menu,
       b2.sq_moeda sq_pj_moeda, b2.sigla sg_pj_moeda,
       c.vencimento, c.quitacao, a.conclusao, e.ordem, e.sq_documento_item,
       -- Necessidade de conversão para BRL (contabilidade): 
       -- se o pagamento foi em Reais ou se o projeto é em Reais e foi inserido um valor nessa moeda, não precisa.
       case when a2.sigla          = 'BRL' then 'N'
            when f1.sq_siw_solicitacao is not null then 'N'
            else 'S'
       end exige_brl,
       c2.data brl_taxa_compra_data, c2.taxa_compra brl_taxa_compra, 
       -- Valor em Reais (contabilidade), definido na seguinte ordem: 
       -- (1) se o pagamento foi em Reais: valor do pagamento
       -- (2) se o projeto é em Reais e foi inserido um valor nessa moeda: valor do pagamento convertido para a moeda do projeto
       -- (3) caso contrário: valor do pagamento convertido pela taxa de câmbio de compra
       case when a2.sigla          = 'BRL' then e.valor_total
            when f1.sq_siw_solicitacao is not null then
                 case when e2.qtd_itens = 1 then f1.valor
                      when e2.qtd_itens = 2 then round(e.valor_total*f1.fator,2) -- 2 itens arredonda para não dar erro
                      else e.valor_total*f1.fator -- mais de 2 itens, só retorna o valor
                      end
            else case when c2.sq_moeda_cotacao is null then null
                      else trunc(e.valor_total*c2.taxa_compra,2)
                 end
       end brl_valor_compra,
       c3.data brl_taxa_venda_data,  c3.taxa_venda  brl_taxa_venda,
       -- Valor em Reais (contabilidade), definido na seguinte ordem: 
       -- (1) se o pagamento foi em Reais: valor do pagamento
       -- (2) se o projeto é em Reais e foi inserido um valor nessa moeda: valor do pagamento convertido para a moeda do projeto
       -- (3) caso contrário: valor do pagamento convertido pela taxa de câmbio de venda
       case when a2.sigla          = 'BRL' then e.valor_total
            when f1.sq_siw_solicitacao is not null then
                 case when e2.qtd_itens = 1 then f1.valor
                      when e2.qtd_itens = 2 then round(e.valor_total*f1.fator,2) -- 2 itens arredonda para não dar erro
                      else e.valor_total*f1.fator -- mais de 2 itens, só retorna o valor
                      end
            else case when c3.sq_moeda_cotacao is null then null
                      else trunc(e.valor_total*c3.taxa_venda,2)
                 end
       end brl_valor_venda,
       case when a2.sigla = 'BRL' or f1.sq_siw_solicitacao is not null then 1 else trunc(1/f1.fator,4) end fator_conversao
  from siw_solicitacao                      a
       inner         join siw_tramite       a1 on (a.sq_siw_tramite      = a1.sq_siw_tramite and a1.sigla in ('EE','AT'))
       inner         join co_moeda          a2 on (a.sq_moeda            = a2.sq_moeda)
       inner         join siw_menu          a3 on (a.sq_menu             = a3.sq_menu and a3.sigla <> 'FNDFIXO' and a3.sigla <> 'FNDFUNDO')
       inner         join fn_lancamento     c  on (a.sq_siw_solicitacao  = c.sq_siw_solicitacao)
       inner         join fn_lancamento_doc d  on (a.sq_siw_solicitacao  = d.sq_siw_solicitacao)
         inner       join fn_documento_item e  on (d.sq_lancamento_doc   = e.sq_lancamento_doc)
           inner     join (select sq_lancamento_doc, count(*) qtd_itens
                             from fn_documento_item
                           group by sq_lancamento_doc
                          )                 e2 on (d.sq_lancamento_doc   = e2.sq_lancamento_doc)
           inner     join pj_rubrica        e1 on (e.sq_projeto_rubrica  = e1.sq_projeto_rubrica)
             inner   join siw_solicitacao   b  on (e1.sq_siw_solicitacao = b.sq_siw_solicitacao)
               inner join co_moeda          b2 on (b.sq_moeda            =  b2.sq_moeda)
       left          join (select k.sq_siw_solicitacao, m.valor, m.valor/k.valor fator, l.sq_moeda sq_moeda
                             from siw_solicitacao                    k
                                  inner       join siw_tramite      k1 on (k.sq_siw_tramite     = k1.sq_siw_tramite and k1.ativo = 'N')
                                  inner       join fn_lancamento    k2 on (k.sq_siw_solicitacao = k2.sq_siw_solicitacao)
                                    inner     join siw_solicitacao   l on (k2.sq_solic_vinculo  = l.sq_siw_solicitacao)
                                      inner   join siw_menu         l1 on (l.sq_menu            = l1.sq_menu)
                                    inner     join siw_solic_cotacao m on (k.sq_siw_solicitacao = m.sq_siw_solicitacao and
                                                                           m.sq_moeda           = l.sq_moeda and
                                                                           m.valor              > 0
                                                                          )
                          )                 f  on (a.sq_siw_solicitacao  = f.sq_siw_solicitacao)
       left          join (select k.sq_siw_solicitacao, m.valor, m.valor/k.valor fator, m.sq_moeda sq_moeda
                             from siw_solicitacao                    k
                                  inner       join siw_solic_cotacao m on (k.sq_siw_solicitacao = m.sq_siw_solicitacao and
                                                                           m.sq_moeda           = 34 and -- BRL
                                                                           m.valor              > 0
                                                                          )
                          )                 f1 on (a.sq_siw_solicitacao  = f1.sq_siw_solicitacao)
         left        join co_moeda_cotacao  c2 on (-- Taxa de compra deve ser do dia da conclusão do lançamento
                                                   c.cliente             = c2.cliente and
                                                   a2.sq_moeda           = c2.sq_moeda and
                                                   c2.data               = (coalesce(c.quitacao,c.vencimento,a.fim))
                                                  )
         left        join co_moeda_cotacao  c3 on (-- Taxa de venda deve ser do dia da conclusão do lançamento
                                                   c.cliente             = c3.cliente and
                                                   a2.sq_moeda           = c3.sq_moeda and
                                                   c3.data               = (coalesce(c.quitacao,c.vencimento,a.fim))
                                                  )
UNION ALL
-- Pagamentos sem detalhamento de itens
select 'D' TIPO, 1 qtd_itens,
       b.sq_siw_solicitacao sq_projeto, b.codigo_interno cd_projeto, a1.sigla sg_tramite,
       /*montaOrdemRubrica(c.sq_projeto_rubrica,'ORDENACAO') ordena,*/ c1.nome nm_rubrica, c1.sq_rubrica_pai, c1.aplicacao_financeira,
       c.sq_projeto_rubrica, 
       a.sq_siw_solicitacao sq_financeiro, a.codigo_interno cd_financeiro,  a.codigo_externo cd_financeiro_externo,
       a.descricao ds_financeiro,
       case when f.sq_siw_solicitacao is null then a.valor       else f.valor               end valor, -- Valor convertido na moeda do projeto
       case when f.sq_siw_solicitacao is null then a2.sq_moeda   else b2.sq_moeda           end sq_fn_moeda,
       case when f.sq_siw_solicitacao is null then a2.sigla      else b2.sigla              end sg_fn_moeda, 
       case when f.sq_siw_solicitacao is null then a2.simbolo    else b2.simbolo            end sb_fn_moeda, 
       a.valor       fn_valor,    -- Valor na moeda do pagamento
       a2.sq_moeda   fn_sq_moeda, -- Chave da moeda do pagamento
       a2.sigla      fn_sg_moeda, -- Sigla da moeda do pagamento
       a2.simbolo    fn_sb_moeda, -- Símbolo da moeda do pagamento
       a3.sigla      sg_menu,
       b2.sq_moeda sq_pj_moeda, b2.sigla sg_pj_moeda,
       c.vencimento, c.quitacao, a.conclusao, 1 ordem, 0 sq_documento_item,
       case when a2.sigla          = 'BRL' then 'N'
            when f1.sq_siw_solicitacao is not null then 'N'
            else 'S'
       end exige_brl,
       c2.data brl_taxa_compra_data, c2.taxa_compra brl_taxa_compra, 
       case when a2.sigla          = 'BRL' then a.valor
            when f1.sq_siw_solicitacao is not null then f1.valor
            else case when c2.sq_moeda_cotacao is null then null
                      else trunc(a.valor*c2.taxa_compra,2)
                 end
       end brl_valor_compra,
       c3.data brl_taxa_venda_data,  c3.taxa_venda  brl_taxa_venda,
       case when a2.sigla          = 'BRL' then a.valor
            when f1.sq_siw_solicitacao is not null then f1.valor
            else case when c3.sq_moeda_cotacao is null then null
                      else trunc(a.valor*c3.taxa_venda,2)
                 end
       end brl_valor_venda,
       case when a2.sigla = 'BRL' or f1.sq_siw_solicitacao is not null then 1 else trunc(1/f1.fator,4) end fator_conversao
  from siw_solicitacao                    a
       inner       join siw_tramite       a1 on (a.sq_siw_tramite      = a1.sq_siw_tramite and a1.sigla in ('EE','AT'))
       inner       join co_moeda          a2 on (a.sq_moeda            = a2.sq_moeda)
       inner       join siw_menu          a3 on (a.sq_menu             = a3.sq_menu and a3.sigla <> 'FNDFIXO' and a3.sigla <> 'FNDFUNDO')
       inner       join fn_lancamento     c  on (a.sq_siw_solicitacao  = c.sq_siw_solicitacao)
         inner     join pj_rubrica        c1 on (c.sq_projeto_rubrica  = c1.sq_projeto_rubrica)
           inner   join siw_solicitacao   b  on (c1.sq_siw_solicitacao = b.sq_siw_solicitacao)
             inner join co_moeda          b2 on (b.sq_moeda            =  b2.sq_moeda)
       inner       join fn_lancamento_doc d  on (a.sq_siw_solicitacao  = d.sq_siw_solicitacao)
         left      join fn_documento_item e  on (d.sq_lancamento_doc   = e.sq_lancamento_doc)
       left        join (select k.sq_siw_solicitacao, m.valor, m.valor/k.valor fator, l.sq_moeda sq_moeda
                           from siw_solicitacao                    k
                                inner       join siw_tramite      k1 on (k.sq_siw_tramite     = k1.sq_siw_tramite and k1.ativo = 'N')
                                inner       join fn_lancamento    k2 on (k.sq_siw_solicitacao = k2.sq_siw_solicitacao)
                                  inner     join siw_solicitacao   l on (k2.sq_solic_vinculo  = l.sq_siw_solicitacao)
                                    inner   join siw_menu         l1 on (l.sq_menu            = l1.sq_menu)
                                  inner     join siw_solic_cotacao m on (k.sq_siw_solicitacao = m.sq_siw_solicitacao and
                                                                         m.sq_moeda           = l.sq_moeda and
                                                                         m.valor              > 0
                                                                        )
                        )                 f  on (a.sq_siw_solicitacao = f.sq_siw_solicitacao)
       left          join (select k.sq_siw_solicitacao, m.valor, m.valor/k.valor fator, m.sq_moeda sq_moeda
                             from siw_solicitacao                    k
                                  inner       join siw_solic_cotacao m on (k.sq_siw_solicitacao = m.sq_siw_solicitacao and
                                                                           m.sq_moeda           = 34 and -- BRL
                                                                           m.valor              > 0
                                                                          )
                          )                 f1 on (a.sq_siw_solicitacao  = f1.sq_siw_solicitacao)
         left      join co_moeda_cotacao  c2 on (-- Taxa de compra deve ser do dia da conclusão do lançamento
                                                 c.cliente             = c2.cliente and
                                                 a2.sq_moeda           = c2.sq_moeda and
                                                 c2.data               = (coalesce(c.quitacao,c.vencimento,a.fim))
                                                )
         left      join co_moeda_cotacao  c3 on (-- Taxa de venda deve ser do dia da conclusão do lançamento
                                                 c.cliente             = c3.cliente and
                                                 a2.sq_moeda            = c3.sq_moeda and
                                                 c3.data               = (coalesce(c.quitacao,c.vencimento,a.fim))
                                                )
 where e.sq_documento_item is null
UNION ALL
-- Pagamentos por fundo fixo
select 'D' TIPO, 1 qtd_itens,
       b.sq_siw_solicitacao sq_projeto, b.codigo_interno cd_projeto, a1.sigla sg_tramite,
       /*montaOrdemRubrica(c.sq_projeto_rubrica,'ORDENACAO') ordena,*/ c1.nome nm_rubrica, c1.sq_rubrica_pai, c1.aplicacao_financeira,
       c.sq_projeto_rubrica, 
       a.sq_siw_solicitacao sq_financeiro, a.codigo_interno cd_financeiro,  a.codigo_externo cd_financeiro_externo,
       a.descricao ds_financeiro,
       case when f.sq_siw_solicitacao is null then a.valor       else f.valor               end valor, -- Valor convertido na moeda do projeto
       case when f.sq_siw_solicitacao is null then a2.sq_moeda   else b2.sq_moeda           end sq_fn_moeda,
       case when f.sq_siw_solicitacao is null then a2.sigla      else b2.sigla              end sg_fn_moeda, 
       case when f.sq_siw_solicitacao is null then a2.simbolo    else b2.simbolo            end sb_fn_moeda, 
       a.valor       fn_valor,    -- Valor na moeda do pagamento
       a2.sq_moeda   fn_sq_moeda, -- Chave da moeda do pagamento
       a2.sigla      fn_sg_moeda, -- Sigla da moeda do pagamento
       a2.simbolo    fn_sb_moeda, -- Símbolo da moeda do pagamento
       a3.sigla      sg_menu,
       b2.sq_moeda sq_pj_moeda, b2.sigla sg_pj_moeda,
       c.vencimento, c.quitacao, a.conclusao, 1 ordem, 0 sq_documento_item,
       case when a2.sigla          = 'BRL' then 'N'
            when f1.sq_siw_solicitacao is not null then 'N'
            else 'S'
       end exige_brl,
       c2.data brl_taxa_compra_data, c2.taxa_compra brl_taxa_compra, 
       case when a2.sigla          = 'BRL' then a.valor
            when f1.sq_siw_solicitacao is not null then f1.valor
            else case when c2.sq_moeda_cotacao is null then null
                      else trunc(a.valor*c2.taxa_compra,2)
                 end
       end brl_valor_compra,
       c3.data brl_taxa_venda_data,  c3.taxa_venda  brl_taxa_venda,
       case when a2.sigla          = 'BRL' then a.valor
            when f1.sq_siw_solicitacao is not null then f1.valor
            else case when c3.sq_moeda_cotacao is null then null
                      else trunc(a.valor*c3.taxa_venda,2)
                 end
       end brl_valor_venda, 
       case when a2.sigla = 'BRL' or f1.sq_siw_solicitacao is not null then 1 else trunc(1/f1.fator,4) end fator_conversao
  from siw_solicitacao                    a
       inner       join siw_tramite       a1 on (a.sq_siw_tramite      = a1.sq_siw_tramite and a1.sigla in ('EE','AT'))
       inner       join co_moeda          a2 on (a.sq_moeda            = a2.sq_moeda)
       inner       join siw_menu          a3 on (a.sq_menu             = a3.sq_menu and a3.sigla = 'FNDFUNDO')
       inner       join fn_lancamento     c  on (a.sq_siw_solicitacao  = c.sq_siw_solicitacao)
         inner     join pj_rubrica        c1 on (c.sq_projeto_rubrica  = c1.sq_projeto_rubrica)
           inner   join siw_solicitacao   b  on (c1.sq_siw_solicitacao = b.sq_siw_solicitacao)
             inner join co_moeda          b2 on (b.sq_moeda            =  b2.sq_moeda)
       inner       join fn_lancamento_doc d  on (a.sq_siw_solicitacao  = d.sq_siw_solicitacao)
         left      join fn_documento_item e  on (d.sq_lancamento_doc   = e.sq_lancamento_doc)
       left        join (select k.sq_siw_solicitacao, m.valor/k2.valor*k.valor valor, k2.valor/m.valor fator, l.sq_moeda sq_moeda
                           from siw_solicitacao                    k -- FNDFIXO
                                inner       join siw_solicitacao  k2 on (k.sq_solic_pai        = k2.sq_siw_solicitacao) -- FNDFUNDO
                                  inner     join fn_lancamento    k3 on (k2.sq_siw_solicitacao = k3.sq_siw_solicitacao)
                                  inner     join siw_solicitacao   l on (k3.sq_solic_vinculo   = l.sq_siw_solicitacao)  -- PJCAD
                                  inner     join siw_solic_cotacao m on (k2.sq_siw_solicitacao  = m.sq_siw_solicitacao and
                                                                         m.sq_moeda            = l.sq_moeda and
                                                                         m.valor               > 0
                                                                        )
                        )                 f  on (a.sq_siw_solicitacao = f.sq_siw_solicitacao)
       left          join (select k.sq_siw_solicitacao, m.valor, m.valor/k.valor fator, m.sq_moeda sq_moeda
                             from siw_solicitacao                    k
                                  inner       join siw_solic_cotacao m on (k.sq_siw_solicitacao = m.sq_siw_solicitacao and
                                                                           m.sq_moeda           = 34 and -- BRL
                                                                           m.valor              > 0
                                                                          )
                          )                 f1 on (a.sq_siw_solicitacao  = f1.sq_siw_solicitacao)
         left      join co_moeda_cotacao  c2 on (-- Taxa de compra deve ser do dia da conclusão do lançamento
                                                 c.cliente             = c2.cliente and
                                                 a2.sq_moeda           = c2.sq_moeda and
                                                 c2.data               = (coalesce(c.quitacao,c.vencimento,a.fim))
                                                )
         left      join co_moeda_cotacao  c3 on (-- Taxa de venda deve ser do dia da conclusão do lançamento
                                                 c.cliente             = c3.cliente and
                                                 a2.sq_moeda            = c3.sq_moeda and
                                                 c3.data               = (coalesce(c.quitacao,c.vencimento,a.fim))
                                                )
 where e.sq_documento_item is null;
