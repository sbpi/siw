create or replace view VW_CONTA_BANCARIA_FINANCEIRO as
-- Recupera dados a partir da conta débito do lançamento
select case substr(a3.sigla,3,1) when 'R' then 'C' else 'D' end tipo,
       case b2.sigla when 'PR' then b.sq_siw_solicitacao else case c2.sigla when 'PR' then c.sq_siw_solicitacao else null end end sq_projeto,
       case b2.sigla when 'PR' then b.codigo_interno     else case c2.sigla when 'PR' then c.codigo_interno     else null end end cd_projeto,
       a1.sigla sg_tramite,
       e.sq_pessoa_conta, e.numero nr_conta, e.operacao op_conta,
       e2.sq_agencia ag_conta, e2.codigo ag_cd_conta, e2.nome ag_nm_conta,
       e3.sq_banco bc_conta, e3.codigo bc_cd_conta, e3.nome bc_nm_conta,
       a.sq_siw_solicitacao sq_financeiro, a.codigo_interno cd_financeiro, a.descricao ds_financeiro,
       d.vencimento, d.quitacao,
       a2.sq_moeda fn_sq_moeda, a2.sigla fn_sg_moeda, a2.simbolo fn_sb_moeda, a.valor fn_valor,
       e1.sq_moeda cb_sq_moeda, e1.sigla cb_sg_moeda, e1.simbolo cb_sb_moeda, e1.codigo cb_cd_moeda, e1.nome cb_nm_moeda, e1.ativo cb_at_moeda,
       case when f.sq_siw_solicitacao is not null 
            then case when e1.sq_moeda = a2.sq_moeda then a.valor else f.valor end -- Se o pagamento foi na moeda da conta bancária, não importa o valor da cotação.
            else case when e1.sq_moeda = a2.sq_moeda then a.valor else null    end -- Se não há cotação, retorna o valor do pagamento somente se foi feito na
                                                                                   -- mesma moeda da conta bancária
       end valor    -- Valor convertido na moeda da conta bancária
  from siw_solicitacao                    a
       inner       join siw_tramite       a1 on (a.sq_siw_tramite      = a1.sq_siw_tramite)
       inner       join co_moeda          a2 on (a.sq_moeda            = a2.sq_moeda)
       inner       join siw_menu          a3 on (a.sq_menu             = a3.sq_menu)
       left        join siw_solicitacao   b  on (a.sq_solic_pai        = b.sq_siw_solicitacao)
         left      join siw_menu          b1 on (b.sq_menu             = b1.sq_menu)
           left    join siw_modulo        b2 on (b1.sq_modulo          = b2.sq_modulo)
         left      join siw_solicitacao   c  on (b.sq_solic_pai        = c.sq_siw_solicitacao)
           left    join siw_menu          c1 on (c.sq_menu             = c1.sq_menu)
             left  join siw_modulo        c2 on (c1.sq_modulo          = c2.sq_modulo)
       inner       join fn_lancamento     d  on (a.sq_siw_solicitacao  = d.sq_siw_solicitacao)
         inner     join co_pessoa_conta   e  on (d.sq_pessoa_conta     = e.sq_pessoa_conta)
           inner   join co_moeda          e1 on (e.sq_moeda            = e1.sq_moeda)
           inner   join co_agencia        e2 on (e.sq_agencia          = e2.sq_agencia)
             inner join co_banco          e3 on (e2.sq_banco           = e3.sq_banco)
           left    join siw_solic_cotacao f  on (a.sq_siw_solicitacao  = f.sq_siw_solicitacao and
                                                e.sq_moeda             = f.sq_moeda
                                               )
 where a1.sigla = 'AT' -- Somente pagamentos concluídos
UNION ALL
-- Recupera dados da conta crédito de trasnferências bancárias
select 'C' tipo,
       case b2.sigla when 'PR' then b.sq_siw_solicitacao else case c2.sigla when 'PR' then c.sq_siw_solicitacao else null end end sq_projeto,
       case b2.sigla when 'PR' then b.codigo_interno     else case c2.sigla when 'PR' then c.codigo_interno     else null end end cd_projeto,
       a1.sigla sg_tramite,
       e.sq_pessoa_conta, e.numero nr_conta, e.operacao op_conta,
       e2.sq_agencia ag_conta, e2.codigo ag_cd_conta, e2.nome ag_nm_conta,
       e3.sq_banco bc_conta, e3.codigo bc_cd_conta, e3.nome bc_nm_conta,
       a.sq_siw_solicitacao sq_financeiro, a.codigo_interno cd_financeiro, a.descricao ds_financeiro,
       d.vencimento, d.quitacao,
       a2.sq_moeda fn_sq_moeda, a2.sigla fn_sg_moeda, a2.simbolo fn_sb_moeda, a.valor fn_valor,
       e1.sq_moeda cb_sq_moeda, e1.sigla cb_sg_moeda, e1.simbolo cb_sb_moeda, e1.codigo cb_cd_moeda, e1.nome cb_nm_moeda, e1.ativo cb_at_moeda,
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
         inner     join co_pessoa_conta   e  on (d.cliente             = e.sq_pessoa and
                                                 d.sq_agencia          = e.sq_agencia and
                                                 d.numero_conta        = e.numero
                                                )
           inner   join co_moeda          e1 on (e.sq_moeda            = e1.sq_moeda)
           inner   join co_agencia        e2 on (e.sq_agencia          = e2.sq_agencia)
             inner join co_banco          e3 on (e2.sq_banco           = e3.sq_banco)
           left    join siw_solic_cotacao f  on (a.sq_siw_solicitacao  = f.sq_siw_solicitacao and
                                                 e.sq_moeda            = f.sq_moeda
                                                )
 where a1.sigla = 'AT' -- Somente pagamentos concluídos
