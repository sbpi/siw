create or replace FUNCTION SP_GetCLFinanceiro
   (p_cliente          numeric,
    p_menu             numeric,
    p_solic            numeric,
    p_chave            numeric,
    p_rubrica          numeric,
    p_lancamento       numeric,
    p_consumo          varchar,
    p_permanente       varchar,
    p_servico          varchar,
    p_outros           varchar,
    p_restricao        varchar,
    p_result          REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
      -- Recupera os vínculos financeiros
      open p_result for
      select a.sq_clvinculo_financeiro as chave, a.cliente, a.sq_siw_solicitacao, a.sq_projeto_rubrica, 
             a.sq_tipo_lancamento, a.consumo, a.permanente, a.servico, a.outros,
             case a.consumo    when 'S' then 'Sim' else 'Não' end as nm_consumo,
             case a.permanente when 'S' then 'Sim' else 'Não' end as nm_permanente,
             case a.servico    when 'S' then 'Sim' else 'Não' end as nm_servico,
             case a.outros     when 'S' then 'Sim' else 'Não' end as nm_outros,
             e.codigo as cd_rubrica, e.nome as nm_rubrica, e.ativo as at_rubrica,
             f.nome   as nm_lancamento, f.descricao as ds_lancamento, f.ativo as at_lancamento
        from cl_vinculo_financeiro             a
             inner     join siw_solicitacao    b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
               inner   join siw_menu           c on (b.sq_menu            = c.sq_menu)
                 inner join siw_modulo         d on (c.sq_modulo          = d.sq_modulo)
             left      join pj_rubrica         e on (a.sq_projeto_rubrica = e.sq_projeto_rubrica)
             inner     join fn_tipo_lancamento f on (a.sq_tipo_lancamento = f.sq_tipo_lancamento)
       where a.cliente = p_cliente
         and a.sq_menu = p_menu
         and (p_chave      is null or (p_chave      is not null and a.sq_clvinculo_financeiro = p_chave))
         and (p_solic      is null or (p_solic      is not null and a.sq_siw_solicitacao      = p_solic))
         and (p_rubrica    is null or (p_rubrica    is not null and a.sq_projeto_rubrica      = p_rubrica))
         and (p_lancamento is null or (p_lancamento is not null and a.sq_tipo_lancamento      = p_lancamento))
         and (p_consumo    is null or (p_consumo    is not null and a.consumo                 = p_consumo))
         and (p_permanente is null or (p_permanente is not null and a.permanente              = p_permanente))
         and (p_servico    is null or (p_servico    is not null and a.servico                 = p_servico))
         and (p_outros     is null or (p_outros     is not null and a.outros                  = p_outros));
   Elsif p_restricao = 'PREV_ORCFIN' Then
      -- Recupera a previsão orçamentária e financeira da viagem
      open p_result for
      select a.valor,
             c1.sq_projeto_rubrica as sq_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
             c2.sq_tipo_lancamento as sq_lancamento, c2.nome as nm_lancamento,
             d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
        from siw_solicitacao                      a
             inner     join cl_solicitacao        a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
               inner   join cl_vinculo_financeiro c  on (a1.sq_financeiro             = c.sq_clvinculo_financeiro)
                 inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica)
                 inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento),
             co_moeda                             d1
       where a.sq_siw_solicitacao = p_solic
         and d1.sigla             = 'BRL';
   Elsif p_restricao = 'ORCAM_PREV' Then
      -- Recupera a previsão orçamentária da viagem
      open p_result for
      select a.valor,
             c1.sq_projeto_rubrica as sq_rubrica, c1.codigo as cd_rubrica, c1.nome as nm_rubrica,
             d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
        from siw_solicitacao                      a
             inner     join cl_solicitacao        a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
               inner   join cl_vinculo_financeiro c  on (a1.sq_financeiro             = c.sq_clvinculo_financeiro)
                 inner join pj_rubrica            c1 on (c.sq_projeto_rubrica         = c1.sq_projeto_rubrica),
             co_moeda                             d1
       where a.sq_siw_solicitacao = p_solic
         and d1.sigla             = 'BRL';
   Elsif p_restricao = 'FINANC_PREV' Then
      -- Recupera a previsão financeira da viagem
      open p_result for
      select a.valor,
             c2.sq_tipo_lancamento as sq_lancamento, c2.nome as nm_lancamento,
             d1.sigla as sg_moeda, d1.nome as nm_moeda, d1.simbolo as sb_moeda
        from siw_solicitacao                      a
             inner     join cl_solicitacao        a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
               inner   join cl_vinculo_financeiro c  on (a1.sq_financeiro             = c.sq_clvinculo_financeiro)
                 inner join fn_tipo_lancamento    c2 on (c.sq_tipo_lancamento         = c2.sq_tipo_lancamento),
             co_moeda                             d1
       where a.sq_siw_solicitacao = p_solic
         and d1.sigla             = 'BRL';
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;