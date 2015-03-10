create or replace view VW_CONTA_BANCARIA_FINANCEIRO as
select case b2.sigla when 'PR' then b.sq_siw_solicitacao else case c2.sigla when 'PR' then c.sq_siw_solicitacao else null end end sq_projeto,
       case b2.sigla when 'PR' then b.codigo_interno     else case c2.sigla when 'PR' then c.codigo_interno     else null end end cd_projeto,
       a1.sigla sg_tramite,
       e.sq_pessoa_conta, e.numero nr_conta, e.sq_agencia ag_conta,
       a.sq_siw_solicitacao sq_financeiro, a.codigo_interno cd_financeiro, a.descricao ds_financeiro,
       d.vencimento, d.quitacao,
       a2.sq_moeda fn_sq_moeda, a2.sigla fn_sg_moeda, a2.simbolo fn_sb_moeda, a.valor fn_valor,
       e1.sq_moeda cb_sq_moeda, e1.sigla cb_sg_moeda, e1.simbolo cb_sb_moeda,
       case when f.sq_siw_solicitacao is not null 
            then case when e1.sq_moeda = a2.sq_moeda then a.valor else f.valor end -- Se o pagamento foi na moeda da conta bancária, não importa o valor da cotação.
            else case when e1.sq_moeda = a2.sq_moeda then a.valor else null    end -- Se não há cotação, retorna o valor do pagamento somente se foi feito na
                                                                                   -- mesma moeda da conta bancária
       end valor    -- Valor convertido na moeda da conta bancária
  from siw_solicitacao                    a
       inner       join siw_tramite       a1 on (a.sq_siw_tramite      = a1.sq_siw_tramite)
       inner       join co_moeda          a2 on (a.sq_moeda            = a2.sq_moeda)
       left        join siw_solicitacao   b  on (a.sq_solic_pai        = b.sq_siw_solicitacao)
         left      join siw_menu          b1 on (b.sq_menu             = b1.sq_menu)
           left    join siw_modulo        b2 on (b1.sq_modulo          = b2.sq_modulo)
         left      join siw_solicitacao   c  on (b.sq_solic_pai        = c.sq_siw_solicitacao)
           left    join siw_menu          c1 on (c.sq_menu             = c1.sq_menu)
             left  join siw_modulo        c2 on (c1.sq_modulo          = c2.sq_modulo)
       inner       join fn_lancamento     d  on (a.sq_siw_solicitacao  = d.sq_siw_solicitacao)
         inner     join co_pessoa_conta   e  on (d.sq_pessoa_conta     = e.sq_pessoa_conta)
           inner   join co_moeda          e1 on (e.sq_moeda            = e1.sq_moeda)
           left    join siw_solic_cotacao f  on (a.sq_siw_solicitacao  = f.sq_siw_solicitacao and
                                                e.sq_moeda             = f.sq_moeda
                                               )
 where a1.sigla = 'AT' -- Somente pagamentos concluídos
