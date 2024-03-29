create or replace view VW_CONTA_BANCARIA_FINANCEIRO as
-- Recupera dados a partir da conta d�bito do lan�amento
select a3.sigla sg_menu, case when (a3.sigla = 'FNATRANSF' or substr(a3.sigla,3,1) = 'D') then 'D' else 'C' end tipo,
       b.sq_siw_solicitacao sq_projeto, b.codigo_interno cd_projeto,
       a1.sigla sg_tramite,
       e.sq_pessoa_conta, e.numero nr_conta, e.operacao op_conta,
       e2.sq_agencia ag_conta, e2.codigo ag_cd_conta, e2.nome ag_nm_conta,
       e3.sq_banco bc_conta, e3.codigo bc_cd_conta, e3.nome bc_nm_conta,
       a.sq_siw_solicitacao sq_financeiro, a.codigo_interno cd_financeiro, a.descricao ds_financeiro,
       d.vencimento, case a3.sigla when 'FNDFIXO' then d1.data else d.quitacao end quitacao,
       a2.sq_moeda fn_sq_moeda, a2.sigla fn_sg_moeda, a2.simbolo fn_sb_moeda, a.valor fn_valor,
       e1.sq_moeda cb_sq_moeda, e1.sigla cb_sg_moeda, e1.simbolo cb_sb_moeda, e1.codigo cb_cd_moeda, e1.nome cb_nm_moeda, e1.ativo cb_at_moeda,
       case when f.sq_siw_solicitacao is not null 
            then case when e1.sq_moeda = a2.sq_moeda then a.valor else f.valor end -- Se o pagamento foi na moeda da conta banc�ria, n�o importa o valor da cota��o.
            else case when e1.sq_moeda = a2.sq_moeda then a.valor else null    end -- Se n�o h� cota��o, retorna o valor do pagamento somente se foi feito na
                                                                                   -- mesma moeda da conta banc�ria
       end valor    -- Valor convertido na moeda da conta banc�ria
  from siw_solicitacao                    a
       inner       join siw_tramite       a1 on (a.sq_siw_tramite      = a1.sq_siw_tramite)
       inner       join co_moeda          a2 on (a.sq_moeda            = a2.sq_moeda)
       inner       join siw_menu          a3 on (a.sq_menu             = a3.sq_menu and a3.sigla <> 'FNAAPLICA')
       inner       join fn_lancamento     d  on (a.sq_siw_solicitacao  = d.sq_siw_solicitacao)
         inner     join co_pessoa_conta   e  on (d.sq_pessoa_conta     = e.sq_pessoa_conta)
           inner   join co_moeda          e1 on (e.sq_moeda            = e1.sq_moeda)
           inner   join co_agencia        e2 on (e.sq_agencia          = e2.sq_agencia)
             inner join co_banco          e3 on (e2.sq_banco           = e3.sq_banco)
         left      join fn_lancamento_doc d1 on (d.sq_siw_solicitacao  = d1.sq_siw_solicitacao)
         left      join siw_solicitacao   b  on (d.sq_solic_vinculo    = b.sq_siw_solicitacao)
       left        join siw_solic_cotacao f  on (a.sq_siw_solicitacao  = f.sq_siw_solicitacao and
                                                 e.sq_moeda            = f.sq_moeda
                                                )
 where -- Se petty cash, n�o pode estar cancelado. Caso contr�rio, deve estar conclu�do
       (a3.sigla = 'FNDFIXO' and a1.sigla != 'CA')
    or (a3.sigla !='FNDFIXO' and a1.sigla = 'AT') 
UNION ALL
-- Recupera dados da conta cr�dito de trasnfer�ncias banc�rias
select a3.sigla sg_menu, 'C' tipo,
       b.sq_siw_solicitacao sq_projeto, b.codigo_interno cd_projeto,
       a1.sigla sg_tramite,
       e.sq_pessoa_conta, e.numero nr_conta, e.operacao op_conta,
       e2.sq_agencia ag_conta, e2.codigo ag_cd_conta, e2.nome ag_nm_conta,
       e3.sq_banco bc_conta, e3.codigo bc_cd_conta, e3.nome bc_nm_conta,
       a.sq_siw_solicitacao sq_financeiro, a.codigo_interno cd_financeiro, a.descricao ds_financeiro,
       d.vencimento, case a3.sigla when 'FNDFIXO' then d1.data else d.quitacao end quitacao,
       a2.sq_moeda fn_sq_moeda, a2.sigla fn_sg_moeda, a2.simbolo fn_sb_moeda, a.valor fn_valor,
       e1.sq_moeda cb_sq_moeda, e1.sigla cb_sg_moeda, e1.simbolo cb_sb_moeda, e1.codigo cb_cd_moeda, e1.nome cb_nm_moeda, e1.ativo cb_at_moeda,
       case when f.sq_siw_solicitacao is not null 
            then case when e1.sq_moeda = a2.sq_moeda then a.valor else f.valor end -- Se o pagamento foi na moeda da conta banc�ria, n�o importa o valor da cota��o.
            else case when e1.sq_moeda = a2.sq_moeda then a.valor else null    end -- Se n�o h� cota��o, retorna o valor do pagamento somente se foi feito na
                                                                                   -- mesma moeda da conta banc�ria
       end valor    -- Valor convertido na moeda da conta banc�ria
  from siw_solicitacao                    a
       inner       join siw_tramite       a1 on (a.sq_siw_tramite      = a1.sq_siw_tramite)
       inner       join co_moeda          a2 on (a.sq_moeda            = a2.sq_moeda)
       inner       join siw_menu          a3 on (a.sq_menu             = a3.sq_menu)
       inner       join fn_lancamento     d  on (a.sq_siw_solicitacao  = d.sq_siw_solicitacao)
         inner     join co_pessoa_conta   e  on (d.cliente             = e.sq_pessoa and
                                                 d.sq_agencia          = e.sq_agencia and
                                                 (coalesce(d.operacao_conta,'-') = coalesce(e.operacao,'-') or coalesce(d.operacao_conta,'-') = '-') and
                                                 d.numero_conta        = e.numero and
                                                 -- S� recupera entrada extraor�ament�ria e transfer�ncia banc�ria
                                                 ((a3.sigla = 'FNATRANSF' and d.sq_pessoa_conta <> e.sq_pessoa_conta) or
                                                  (a3.sigla = 'FNAAPLICA' and d.sq_pessoa_conta =  e.sq_pessoa_conta) or
                                                  a3.sigla  = 'FNDEVENT'
                                                 )
                                                )
           inner   join co_moeda          e1 on (e.sq_moeda            = e1.sq_moeda)
           inner   join co_agencia        e2 on (e.sq_agencia          = e2.sq_agencia)
             inner join co_banco          e3 on (e2.sq_banco           = e3.sq_banco)
         left      join fn_lancamento_doc d1 on (d.sq_siw_solicitacao  = d1.sq_siw_solicitacao)
         left      join siw_solicitacao   b  on (d.sq_solic_vinculo    = b.sq_siw_solicitacao)
       left        join siw_solic_cotacao f  on (a.sq_siw_solicitacao  = f.sq_siw_solicitacao and
                                                 e.sq_moeda            = f.sq_moeda
                                                )
 where -- Lan�amento deve estar conclu�do
       a1.sigla = 'AT';
