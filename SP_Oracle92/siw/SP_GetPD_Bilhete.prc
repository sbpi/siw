create or replace procedure SP_GetPD_Bilhete
   (p_solic     in number   default null,
    p_bilhete   in number   default null,
    p_inicio    in date     default null,
    p_fim       in date     default null,
    p_numero    in varchar2 default null,
    p_cia_trans in number   default null,
    p_tipo      in varchar2 default null,
    p_restricao in varchar2,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera os bilhetes ligados a viagens
      open p_result for
         select a.sq_bilhete as chave, a.sq_siw_solicitacao, a.sq_cia_transporte, a.data, a.numero, a.trecho, a.valor_bilhete, a.valor_bilhete_cheio, a.valor_pta, 
                a.valor_taxa_embarque, a.rloc, a.classe, a.utilizado, a.faturado, a.observacao,
                case a.utilizado when 'I' then 'Integral' when 'P' then 'Parcial' when 'C' then 'Não utilizado' else 'Não informado' end as nm_utilizado,
                case a.faturado  when 'S' then 'Sim' else 'Não' end as nm_faturado,
                b.codigo_interno,
                c.codigo_interno as cd_pai,
                c1.nome as nm_tramite,
                d.cumprimento,
                case d.cumprimento when 'I' then 'Não' when 'P' then 'Sim' when 'C' then 'Cancelada' else 'Não informada' end as nm_cumprimento,
                e.nome as nm_beneficiario,
                f.nome as nm_cia_transporte,
                h.faixa_inicio, h.faixa_fim, h.desconto,
                i.numero as nr_fatura, i.inicio_decendio, i.fim_decendio, i.emissao as emissao_fatura, i.vencimento as vencimento_fatura, i.valor as vl_fatura,
                i.agencia_viagem,
                case when g.sq_desconto_agencia is null then 0 else (g.valor_bilhete*h.desconto/100) end as valor_desconto,
                trunc((g.valor_bilhete+g.valor_pta+g.valor_taxa_embarque)-case when g.sq_desconto_agencia is null then 0 else (g.valor_bilhete*h.desconto/100) end,2) as vl_bilhete_fatura
           from pd_bilhete                       a
                left    join siw_solicitacao     b on (a.sq_siw_solicitacao  = b.sq_siw_solicitacao)
                  left  join siw_solicitacao     c on (b.sq_solic_pai        = c.sq_siw_solicitacao)
                  left  join siw_tramite        c1 on (c.sq_siw_tramite      = c1.sq_siw_tramite)
                left    join pd_missao           d on (a.sq_siw_solicitacao  = d.sq_siw_solicitacao)
                  left  join co_pessoa           e on (d.sq_pessoa           = e.sq_pessoa)
                inner   join pd_cia_transporte   f on (a.sq_cia_transporte   = f.sq_cia_transporte)
                left    join pd_bilhete          g on (a.sq_siw_solicitacao  = g.sq_siw_solicitacao and
                                                       a.sq_cia_transporte   = g.sq_cia_transporte and
                                                       a.numero              = g.numero and
                                                       a.tipo                = 'S' and
                                                       g.tipo                = 'P'
                                                      )
                  left  join pd_desconto_agencia h on (g.sq_desconto_agencia = h.sq_desconto_agencia)
                  left  join pd_fatura_agencia   i on (g.sq_fatura_agencia   = i.sq_fatura_agencia)
          where a.tipo               = coalesce(p_tipo,'S')
            and (p_solic             is null or (p_solic     is not null and a.sq_siw_solicitacao = p_solic))
            and (p_bilhete           is null or (p_bilhete   is not null and a.sq_bilhete         = p_bilhete))
            and (p_numero            is null or (p_numero    is not null and a.numero             = p_numero))
            and (p_cia_trans         is null or (p_cia_trans is not null and a.sq_cia_transporte  = p_cia_trans))
            and (p_inicio            is null or (p_inicio    is not null and a.data               between p_inicio and p_fim));
   End If;
End SP_GetPD_Bilhete;
/
