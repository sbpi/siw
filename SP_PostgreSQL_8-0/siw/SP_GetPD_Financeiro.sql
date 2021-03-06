create or replace FUNCTION SP_GetPD_Financeiro
   (p_cliente          numeric,
    p_chave            numeric,
    p_solic            numeric,
    p_rubrica          numeric,
    p_lancamento       numeric,
    p_diaria           varchar,
    p_hospedagem       varchar,
    p_veiculo          varchar,
    p_seguro           varchar,
    p_bilhete          varchar,
    p_reembolso        varchar,
    p_ressarcimento    varchar,
    p_restricao        varchar,
    p_result          REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
      -- Recupera os vínculos financeiros
      open p_result for
      select a.sq_pdvinculo_financeiro as chave, a.cliente, a.sq_siw_solicitacao, a.sq_projeto_rubrica, 
             a.sq_tipo_lancamento, a.diaria, a.hospedagem, a.veiculo, a.seguro, a.bilhete, a.reembolso,
             a.ressarcimento,
             case a.diaria         when 'S' then 'Sim' else 'Não' end as nm_diaria,
             case a.hospedagem     when 'S' then 'Sim' else 'Não' end as nm_hospedagem,
             case a.veiculo        when 'S' then 'Sim' else 'Não' end as nm_veiculo,
             case a.seguro         when 'S' then 'Sim' else 'Não' end as nm_seguro,
             case a.bilhete        when 'S' then 'Sim' else 'Não' end as nm_bilhete,
             case a.reembolso      when 'S' then 'Sim' else 'Não' end as nm_reembolso,
             case a.ressarcimento  when 'S' then 'Sim' else 'Não' end as nm_ressarcimento,
             e.codigo as cd_rubrica, e.nome as nm_rubrica, e.ativo as at_rubrica,
             f.nome   as nm_lancamento, f.descricao as ds_lancamento, f.ativo as at_lancamento
        from pd_vinculo_financeiro             a
             inner     join siw_solicitacao    b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
               inner   join siw_menu           c on (b.sq_menu            = c.sq_menu)
                 inner join siw_modulo         d on (c.sq_modulo          = d.sq_modulo)
             left      join pj_rubrica         e on (a.sq_projeto_rubrica = e.sq_projeto_rubrica)
             inner     join fn_tipo_lancamento f on (a.sq_tipo_lancamento = f.sq_tipo_lancamento)
       where a.cliente        = p_cliente
         and (p_chave         is null or (p_chave         is not null and a.sq_pdvinculo_financeiro = p_chave))
         and (p_solic         is null or (p_solic         is not null and a.sq_siw_solicitacao      = p_solic))
         and (p_rubrica       is null or (p_rubrica       is not null and a.sq_projeto_rubrica      = p_rubrica))
         and (p_lancamento    is null or (p_lancamento    is not null and a.sq_tipo_lancamento      = p_lancamento))
         and (p_diaria        is null or (p_diaria        is not null and a.diaria                  = p_diaria))
         and (p_hospedagem    is null or (p_hospedagem    is not null and a.hospedagem              = p_hospedagem))
         and (p_veiculo       is null or (p_veiculo       is not null and a.veiculo                 = p_veiculo))
         and (p_seguro        is null or (p_seguro        is not null and a.seguro                  = p_seguro))
         and (p_bilhete       is null or (p_bilhete       is not null and a.bilhete                 = p_bilhete))
         and (p_reembolso     is null or (p_reembolso     is not null and a.reembolso               = p_reembolso))
         and (p_ressarcimento is null or (p_ressarcimento is not null and a.ressarcimento           = p_ressarcimento));
   Elsif p_restricao = 'PREV_ORCFIN' Then
      -- Recupera a previsão orçamentária e financeira da viagem
      open p_result for
      select sq_projeto_rubrica as sq_rubrica, cd_rubrica, nm_rubrica, 
             sq_tipo_lancamento as sq_lancamento, nm_lancamento, 
             sg_moeda, nm_moeda, sb_moeda, 
             sum(valor) as valor
        from (select distinct 'CMP' as tp_despesa, a1.complemento_valor as valor,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     c2.sq_tipo_lancamento, c2.nome as nm_lancamento,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_vinculo_financeiro c  on (a.sq_solic_pai               = c.sq_siw_solicitacao and
                                                                 c.diaria                     = 'S'
                                                                )
                       inner   join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                       inner   join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento),
                     co_moeda                             d1
               where a.sq_siw_solicitacao = p_solic
                 and d1.sigla             =	'BRL'
              UNION
              select 'RMB' as tp_despesa, b.valor_autorizado as valor,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     c2.sq_tipo_lancamento, c2.nome as nm_lancamento,
                     b1.sigla as sg_moeda, b1.nome as nm_moeda, b1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_reembolso          b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                       inner   join co_moeda              b1 on (b.sq_moeda                   = b1.sq_moeda)
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (a1.sq_pdvinculo_reembolso    = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                         inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
               where a.sq_siw_solicitacao = p_solic
              UNION
              select 'BIL' as tp_despesa, a1.valor_passagem as valor,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     c2.sq_tipo_lancamento, c2.nome as nm_lancamento,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (a1.sq_pdvinculo_bilhete      = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                         inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento),
                     co_moeda                             d1
               where a.sq_siw_solicitacao = p_solic
                 and d1.sigla = 'BRL'
              UNION
              select 'DIA' as tp_despesa, (b.quantidade*b.valor) as valor,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     c2.sq_tipo_lancamento, c2.nome as nm_lancamento,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao and b.tipo = 'S')
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_diaria        = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                         inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria            = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_solic
              UNION
              select 'HSP' as tp_despesa, (b.hospedagem_qtd*b.hospedagem_valor) as valor,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     c2.sq_tipo_lancamento, c2.nome as nm_lancamento,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao and b.tipo = 'S')
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_hospedagem    = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                         inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria_hospedagem = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_solic
              UNION
              select 'VEI' as tp_despesa, (-1*b.valor*b.veiculo_qtd*b.veiculo_valor/100) as valor,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     c2.sq_tipo_lancamento, c2.nome as nm_lancamento,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao and b.tipo = 'S')
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_veiculo       = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                         inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria_veiculo    = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_solic
             ) a
      group by sq_projeto_rubrica, cd_rubrica, nm_rubrica, sq_tipo_lancamento, nm_lancamento, sg_moeda, nm_moeda, sb_moeda;
   Elsif p_restricao = 'ORCAM_SIT' Then
      -- Recupera o orçamento atual do projeto para pagamento de viagens
      open p_result for
      select distinct e.codigo as cd_rubrica, e.nome as nm_rubrica, e.descricao, e.ativo as at_rubrica,
             f.total_previsto, f.total_real,
             case coalesce(f.total_previsto,0) when 0 then 0 else (f.total_real/f.total_previsto*100) end as perc_exec,
             (f.total_previsto-f.total_real) as saldo
        from pd_vinculo_financeiro             a
             inner     join siw_solicitacao    b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
               inner   join siw_menu           c on (b.sq_menu            = c.sq_menu)
                 inner join siw_modulo         d on (c.sq_modulo          = d.sq_modulo)
             inner     join pj_rubrica         e on (a.sq_projeto_rubrica = e.sq_projeto_rubrica)
                left   join (select sum(x.valor_previsto) as total_previsto, 
                                    sum(x.valor_real) as total_real, 
                                    x.sq_projeto_rubrica
                               from pj_rubrica_cronograma x
                             group by x.sq_projeto_rubrica
                            )                  f on (e.sq_projeto_rubrica = f.sq_projeto_rubrica)
       where a.cliente = p_cliente
         and (p_chave      is null or (p_chave      is not null and a.sq_pdvinculo_financeiro = p_chave))
         and (p_solic      is null or (p_solic      is not null and a.sq_siw_solicitacao      = p_solic))
         and (p_rubrica    is null or (p_rubrica    is not null and a.sq_projeto_rubrica      = p_rubrica))
         and (p_lancamento is null or (p_lancamento is not null and a.sq_tipo_lancamento      = p_lancamento))
         and (p_diaria     is null or (p_diaria     is not null and a.diaria                  = p_diaria))
         and (p_hospedagem is null or (p_hospedagem is not null and a.hospedagem              = p_hospedagem))
         and (p_veiculo    is null or (p_veiculo    is not null and a.veiculo                 = p_veiculo))
         and (p_seguro     is null or (p_seguro     is not null and a.seguro                  = p_seguro))
         and (p_bilhete    is null or (p_bilhete    is not null and a.bilhete                 = p_bilhete))
         and (p_reembolso  is null or (p_reembolso  is not null and a.reembolso               = p_reembolso));
   Elsif p_restricao = 'ORCAM_PREV' Then
      -- Recupera a previsão orçamentária da viagem
      open p_result for
      select sq_projeto_rubrica as sq_rubrica, cd_rubrica, nm_rubrica, sg_moeda, nm_moeda, sb_moeda, sum(valor) as valor
        from (select distinct 'CMP' as tp_despesa, a1.complemento_valor as valor, 0 as sq_diaria,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_vinculo_financeiro c  on (a.sq_solic_pai               = c.sq_siw_solicitacao and
                                                                 c.diaria                     = 'S'
                                                                )
                       inner   join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica),
                     co_moeda                             d1
               where a.sq_siw_solicitacao = p_solic
                 and d1.sigla             =	'BRL'
              UNION
              select 'RMB' as tp_despesa, b.valor_autorizado as valor, b.sq_pdreembolso as sq_diaria,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     b1.sigla as sg_moeda, b1.nome as nm_moeda, b1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join siw_tramite           a2 on (a.sq_siw_tramite             = a2.sq_siw_tramite)
                     inner     join pd_reembolso          b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                       inner   join co_moeda              b1 on (b.sq_moeda                   = b1.sq_moeda)
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (a1.sq_pdvinculo_reembolso    = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
               where a.sq_siw_solicitacao = p_solic
              UNION
              select 'BIL' as tp_despesa, a1.valor_passagem as valor, 0 as sq_diaria,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (a1.sq_pdvinculo_bilhete      = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica),
                     co_moeda                             d1
               where a.sq_siw_solicitacao = p_solic
                 and d1.sigla = 'BRL'
              UNION
              select 'DIA' as tp_despesa, (b.quantidade*b.valor) as valor, b.sq_diaria,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao and b.tipo = 'S')
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_diaria        = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria            = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_solic
              UNION
              select 'HSP' as tp_despesa, (b.hospedagem_qtd*b.hospedagem_valor) as valor, b.sq_diaria,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao and b.tipo = 'S')
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_hospedagem    = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria_hospedagem = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_solic
              UNION
              select 'VEI' as tp_despesa, (-1*b.valor*b.veiculo_qtd*b.veiculo_valor/100) as valor, b.sq_diaria,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao and b.tipo = 'S')
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_veiculo       = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria_veiculo    = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_solic
             ) a
      group by sq_projeto_rubrica, cd_rubrica, nm_rubrica, sg_moeda, nm_moeda, sb_moeda;
   Elsif p_restricao = 'FINANC_PREV' Then
      -- Recupera a previsão financeira da viagem
      open p_result for
      select sq_tipo_lancamento as sq_lancamento, nm_lancamento, sg_moeda, nm_moeda, sb_moeda, sum(valor) as valor
        from (select distinct 'CMP' as tp_despesa, a1.complemento_valor as valor, 0 as sq_diaria,
                     c2.sq_tipo_lancamento, c2.nome as nm_lancamento,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_vinculo_financeiro c  on (a.sq_solic_pai               = c.sq_siw_solicitacao and
                                                                 c.diaria                     = 'S'
                                                                )
                       inner   join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento),
                     co_moeda                             d1
               where a.sq_siw_solicitacao = p_solic
                 and d1.sigla             =	'BRL'
              UNION
              select 'RMB' as tp_despesa, b.valor_autorizado as valor, b.sq_pdreembolso as sq_diaria,
                     c2.sq_tipo_lancamento, c2.nome as nm_lancamento,
                     b1.sigla as sg_moeda, b1.nome as nm_moeda, b1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_reembolso          b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                       inner   join co_moeda              b1 on (b.sq_moeda                   = b1.sq_moeda)
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (a1.sq_pdvinculo_reembolso    = c.sq_pdvinculo_financeiro)
                         inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
               where a.sq_siw_solicitacao = p_solic
              UNION
              select 'BIL' as tp_despesa, a1.valor_passagem as valor, 0 as sq_diaria,
                     c2.sq_tipo_lancamento, c2.nome as nm_lancamento,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (a1.sq_pdvinculo_bilhete      = c.sq_pdvinculo_financeiro)
                         inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento),
                     co_moeda                             d1
               where a.sq_siw_solicitacao = p_solic
                 and d1.sigla = 'BRL'
              UNION
              select 'DIA' as tp_despesa, (b.quantidade*b.valor) as valor, b.sq_diaria,
                     c2.sq_tipo_lancamento, c2.nome as nm_lancamento,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao and b.tipo = 'S')
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_diaria        = c.sq_pdvinculo_financeiro)
                         inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria            = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_solic
              UNION
              select 'HSP' as tp_despesa, (b.hospedagem_qtd*b.hospedagem_valor) as valor, b.sq_diaria,
                     c2.sq_tipo_lancamento, c2.nome as nm_lancamento,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao and b.tipo = 'S')
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_hospedagem    = c.sq_pdvinculo_financeiro)
                         inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria_hospedagem = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_solic
              UNION
              select 'VEI' as tp_despesa, (-1*b.valor*b.veiculo_qtd*b.veiculo_valor/100) as valor, b.sq_diaria,
                     c2.sq_tipo_lancamento, c2.nome as nm_lancamento,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao and b.tipo = 'S')
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_veiculo       = c.sq_pdvinculo_financeiro)
                         inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria_veiculo    = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_solic
             ) a
      group by sq_tipo_lancamento, nm_lancamento, sg_moeda, nm_moeda, sb_moeda;
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;