create or replace view VW_FINANCEIRO_PAISES as
-- Pagamentos com detalhamento de itens
select a3.sigla      sg_menu,
       b.sq_siw_solicitacao sq_projeto, b.codigo_interno cd_projeto, b.titulo nm_projeto, a1.sigla sg_tramite,
       c1.sq_tipo_lancamento, c1.nome nm_tipo_lancamento, c1.descricao ds_tipo_lancamento,
       e1.sq_pais, e1.nome nm_pais,
       a.sq_siw_solicitacao sq_financeiro, a.codigo_interno cd_financeiro, a.codigo_externo cd_financeiro_externo,
       a.descricao ds_financeiro,
       c.vencimento, c.quitacao, a.conclusao, 
       a2.sq_moeda   fn_sq_moeda, -- Chave da moeda do pagamento
       a2.sigla      fn_sg_moeda, -- Sigla da moeda do pagamento
       a2.simbolo    fn_sb_moeda, -- Símbolo da moeda do pagamento
       e.valor       fn_valor,    -- Valor na moeda do pagamento
       b2.sq_moeda   sq_pj_moeda, -- Chave da moeda do projeto
       b2.sigla      sg_pj_moeda, -- Sigla da moeda do projeto
       b2.simbolo    sb_pj_moeda, -- Símbolo da moeda do projeto
       conversao_lancamento(a3.sq_pessoa, coalesce(c.quitacao,c.vencimento,a.fim), a.sq_siw_solicitacao, b.sq_moeda, e.valor, 'V') valor, -- valor na moeda do projeto
       conversao_lancamento(a3.sq_pessoa, coalesce(c.quitacao,c.vencimento,a.fim), a.sq_siw_solicitacao, 70, e.valor, 'V') usd_valor_venda -- valor em dólar
  from siw_solicitacao                       a
       inner         join siw_menu           a3 on (a.sq_menu             = a3.sq_menu)
       inner         join siw_tramite        a1 on (a.sq_siw_tramite      = a1.sq_siw_tramite and 
                                                    ((a3.sigla             = 'FNDFIXO' and a1.sigla <> 'CA') or
                                                     (a3.sigla             <> 'FNDFIXO' and a1.sigla = 'AT')
                                                    )
                                                   )
       inner         join co_moeda           a2 on (a.sq_moeda            = a2.sq_moeda)
       inner         join fn_lancamento      c  on (a.sq_siw_solicitacao  = c.sq_siw_solicitacao)
         left        join co_moeda_cotacao   c2 on (-- Taxa deve ser do dia da conclusão do lançamento
                                                    c.cliente             = c2.cliente and
                                                    c2.sq_moeda           = 70 and
                                                    c2.data               = (coalesce(c.quitacao,c.vencimento,a.fim))
                                                   )
         inner       join fn_tipo_lancamento c1 on (c.sq_tipo_lancamento  = c1.sq_tipo_lancamento)
         inner       join siw_solicitacao    b  on (c.sq_solic_vinculo    = b.sq_siw_solicitacao)
           inner     join co_moeda           b2 on (b.sq_moeda            =  b2.sq_moeda)
       inner         join fn_lancamento_pais e  on (a.sq_siw_solicitacao  = e.sq_siw_solicitacao and e.valor > 0)
           inner     join co_pais            e1 on (e.sq_pais             = e1.sq_pais);
