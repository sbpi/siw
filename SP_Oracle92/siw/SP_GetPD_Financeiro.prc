create or replace procedure SP_GetPD_Financeiro
   (p_cliente         in  number,
    p_chave           in  number   default null,
    p_solic           in  number   default null,
    p_rubrica         in  number   default null,
    p_lancamento      in  number   default null,
    p_diaria          in  varchar2 default null,
    p_hospedagem      in  varchar2 default null,
    p_veiculo         in  varchar2 default null,
    p_seguro          in  varchar2 default null,
    p_bilhete         in  varchar2 default null,
    p_reembolso       in  varchar2 default null,
    p_restricao       in  varchar2 default null,
    p_result          out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera os vínculos financeiros
      open p_result for
      select a.sq_pdvinculo_financeiro as chave, a.cliente, a.sq_siw_solicitacao, a.sq_projeto_rubrica, 
             a.sq_tipo_lancamento, a.diaria, a.hospedagem, a.veiculo, a.seguro, a.bilhete, a.reembolso,
             case a.diaria     when 'S' then 'Sim' else 'Não' end as nm_diaria,
             case a.hospedagem when 'S' then 'Sim' else 'Não' end as nm_hospedagem,
             case a.veiculo    when 'S' then 'Sim' else 'Não' end as nm_veiculo,
             case a.seguro     when 'S' then 'Sim' else 'Não' end as nm_seguro,
             case a.bilhete    when 'S' then 'Sim' else 'Não' end as nm_bilhete,
             case a.reembolso  when 'S' then 'Sim' else 'Não' end as nm_reembolso,
             e.codigo as cd_rubrica, e.nome as nm_rubrica, e.ativo as at_rubrica,
             f.nome   as nm_lancamento, f.descricao as ds_lancamento, f.ativo as at_lancamento
        from pd_vinculo_financeiro             a
             inner     join siw_solicitacao    b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
               inner   join siw_menu           c on (b.sq_menu            = c.sq_menu)
                 inner join siw_modulo         d on (c.sq_modulo          = d.sq_modulo)
             left      join pj_rubrica         e on (a.sq_projeto_rubrica = e.sq_projeto_rubrica)
             inner     join fn_tipo_lancamento f on (a.sq_tipo_lancamento = f.sq_tipo_lancamento)
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
   Elsif p_restricao = 'PREV_ORCFIN' Then
      -- Recupera a previsão orçamentária e financeira da viagem
      open p_result for
      select sq_projeto_rubrica as sq_rubrica, cd_rubrica, nm_rubrica, 
             sq_tipo_lancamento as sq_lancamento, nm_lancamento, 
             sg_moeda, nm_moeda, sb_moeda, 
             sum(valor) as valor
        from (select 'RMB' as tp_despesa, a1.reembolso_valor as valor,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     c2.sq_tipo_lancamento, c2.nome as nm_lancamento,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (a1.sq_pdvinculo_reembolso    = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                         inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento),
                     co_moeda                             d1
               where a.sq_siw_solicitacao = p_solic
                 and d1.sigla = 'BRL'
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
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
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
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
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
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_veiculo       = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                         inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria_veiculo    = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_solic
             )
      group by sq_projeto_rubrica, cd_rubrica, nm_rubrica, sq_tipo_lancamento, nm_lancamento, sg_moeda, nm_moeda, sb_moeda;
   Elsif p_restricao = 'ORCAM_PREV' Then
      -- Recupera a previsão orçamentária da viagem
      open p_result for
      select sq_projeto_rubrica as sq_rubrica, cd_rubrica, nm_rubrica, sg_moeda, nm_moeda, sb_moeda, sum(valor) as valor
        from (select 'RMB' as tp_despesa, a1.reembolso_valor as valor,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (a1.sq_pdvinculo_reembolso    = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica),
                     co_moeda                             d1
               where a.sq_siw_solicitacao = p_solic
                 and d1.sigla = 'BRL'
              UNION
              select 'BIL' as tp_despesa, a1.valor_passagem as valor,
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
              select 'DIA' as tp_despesa, (b.quantidade*b.valor) as valor,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_diaria        = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria            = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_solic
              UNION
              select 'HSP' as tp_despesa, (b.hospedagem_qtd*b.hospedagem_valor) as valor,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_hospedagem    = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria_hospedagem = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_solic
              UNION
              select 'VEI' as tp_despesa, (-1*b.valor*b.veiculo_qtd*b.veiculo_valor/100) as valor,
                     c1.sq_projeto_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_veiculo       = c.sq_pdvinculo_financeiro)
                         inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria_veiculo    = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_solic
             )
      group by sq_projeto_rubrica, cd_rubrica, nm_rubrica, sg_moeda, nm_moeda, sb_moeda;
   Elsif p_restricao = 'FINANC_PREV' Then
      -- Recupera a previsão financeira da viagem
      open p_result for
      select sq_tipo_lancamento as sq_lancamento, nm_lancamento, sg_moeda, nm_moeda, sb_moeda, sum(valor) as valor
        from (select 'RMB' as tp_despesa, a1.reembolso_valor as valor,
                     c2.sq_tipo_lancamento, c2.nome as nm_lancamento,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (a1.sq_pdvinculo_reembolso    = c.sq_pdvinculo_financeiro)
                         inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento),
                     co_moeda                             d1
               where a.sq_siw_solicitacao = p_solic
                 and d1.sigla = 'BRL'
              UNION
              select 'BIL' as tp_despesa, a1.valor_passagem as valor,
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
              select 'DIA' as tp_despesa, (b.quantidade*b.valor) as valor,
                     c2.sq_tipo_lancamento, c2.nome as nm_lancamento,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_diaria        = c.sq_pdvinculo_financeiro)
                         inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria            = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_solic
              UNION
              select 'HSP' as tp_despesa, (b.hospedagem_qtd*b.hospedagem_valor) as valor,
                     c2.sq_tipo_lancamento, c2.nome as nm_lancamento,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_hospedagem    = c.sq_pdvinculo_financeiro)
                         inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria_hospedagem = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_solic
              UNION
              select 'VEI' as tp_despesa, (-1*b.valor*b.veiculo_qtd*b.veiculo_valor/100) as valor,
                     c2.sq_tipo_lancamento, c2.nome as nm_lancamento,
                     d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
                from siw_solicitacao                      a
                     inner     join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
                     inner     join pd_diaria             b  on (a.sq_siw_solicitacao         = b.sq_siw_solicitacao)
                       inner   join pd_vinculo_financeiro c  on (b.sq_pdvinculo_veiculo       = c.sq_pdvinculo_financeiro)
                         inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento)
                       inner   join pd_valor_diaria       d  on (b.sq_valor_diaria_veiculo    = d.sq_valor_diaria)
                         inner join co_moeda              d1 on (d.sq_moeda                   = d1.sq_moeda)
               where a.sq_siw_solicitacao = p_solic
             )
      group by sq_tipo_lancamento, nm_lancamento, sg_moeda, nm_moeda, sb_moeda;
   End If;
end SP_GetPD_Financeiro;
/
