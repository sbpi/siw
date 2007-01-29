create or replace procedure SP_GetRelBolsista
   (p_chave                in number,
    p_bolsista             in number   default null,
    p_tema                 in number   default null,
    p_nivel                in number   default null,
    p_contrato             in number   default null,
    p_restricao            in varchar2 default null,
    p_result    out siw.sys_refcursor) is
begin
   If p_restricao is null Then
      open p_result for 
         select a.sq_siw_solicitacao chave, 
                a.valor valor_projeto, ceil(months_between(a.fim,a.inicio)) meses_projeto,
                ceil(months_between(a.fim,sysdate())) meses_fim_projeto,
                c.sq_projeto_etapa sq_tema, c.ordem or_tema, c.titulo nm_tema,
                ceil(months_between(c.fim_previsto,c.inicio_previsto)) meses_tema,
                c.orcamento valor_tema,
                d.sq_projeto_etapa sq_nivel, d.ordem or_nivel, d.titulo nm_nivel,
                f.inicio, f.fim, g.valor valor_parcela, f.valor_inicial, f.sq_siw_solicitacao sq_acordo, f.outra_parte,
                g.vencimento, to_char(g.vencimento, 'DD/MM/YYYY, HH24:MI:SS') phpdt_vencimento,
                h.nome nm_bolsista,
                j.ativo contrato_ativo,
                l.nome_resumido nm_resp_etapa,
                j.sigla
           from siw_solicitacao    a,
                pj_projeto         b,
                pj_projeto_etapa   c,
                co_pessoa          l,
                pj_projeto_etapa   d,
                pj_etapa_contrato  e,
                ac_acordo          f,
                ac_acordo_parcela  g,
                co_pessoa          h,
                siw_solicitacao    i,
                siw_tramite        j
          where (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
            and (a.sq_siw_solicitacao = c.sq_siw_solicitacao 
            and  c.sq_etapa_pai       is null)
            and (c.sq_pessoa          = l.sq_pessoa)
            and (c.sq_projeto_etapa   = d.sq_etapa_pai)
            and (d.sq_projeto_etapa   = e.sq_projeto_etapa)
            and (e.sq_siw_solicitacao = f.sq_siw_solicitacao)
            and (f.sq_siw_solicitacao = g.sq_siw_solicitacao 
            and  g.vencimento         between a.inicio and a.fim)
            and (f.outra_parte        = h.sq_pessoa)
            and (f.sq_siw_solicitacao = i.sq_siw_solicitacao)
            and (i.sq_siw_tramite     = j.sq_siw_tramite 
            and  'CA'                 <> nvl(j.sigla,'--'))
            and a.sq_siw_solicitacao = p_chave
            and (p_bolsista          is null or (p_bolsista          is not null and f.outra_parte      = p_bolsista))
            and (p_tema              is null or (p_tema              is not null and c.sq_projeto_etapa = p_tema))
            and (f.sq_siw_solicitacao is null or (f.sq_siw_solicitacao is not null and g.sq_acordo_parcela is not null))
          order by c.ordem, d.ordem, h.nome, g.vencimento;
   Elsif p_restricao = 'TEMA' Then
       open p_result for 
         select a.sq_siw_solicitacao chave,
                a.fim fim_projeto, a.inicio inicio_projeto,
                a.valor valor_projeto, ceil(months_between(a.fim,a.inicio)) meses_projeto,
                ceil(months_between(a.fim,sysdate())) meses_fim_projeto,
                c.sq_projeto_etapa sq_tema, c.ordem or_tema, c.titulo nm_tema,
                ceil(months_between(c.fim_previsto,c.inicio_previsto)) meses_tema,
                c.orcamento valor_tema,
                d.sq_projeto_etapa sq_nivel, d.ordem or_nivel, d.titulo nm_nivel, d.orcamento valor_nivel,
                ceil(months_between(d.fim_previsto,d.inicio_previsto)) meses_nivel,
                f.inicio, f.fim, g.valor valor_parcela, f.valor_inicial, f.sq_siw_solicitacao sq_acordo, f.outra_parte,
                g.vencimento, to_char(g.vencimento, 'DD/MM/YYYY, HH24:MI:SS') phpdt_vencimento,
                h.nome nm_bolsista,
                j.ativo contrato_ativo,
                l.nome_resumido nm_resp_etapa,
                j.sigla
           from siw_solicitacao    a,
                pj_projeto         b,
                pj_projeto_etapa   c,
                co_pessoa          l,
                pj_projeto_etapa   d,
                pj_etapa_contrato  e,
                ac_acordo          f,
                ac_acordo_parcela  g,
                co_pessoa          h,
                siw_solicitacao    i,
                siw_tramite        j
          where (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
            and (a.sq_siw_solicitacao = c.sq_siw_solicitacao 
            and  c.sq_etapa_pai       is null)
            and (c.sq_pessoa          = l.sq_pessoa)
            and (c.sq_projeto_etapa   = d.sq_etapa_pai)
            and (d.sq_projeto_etapa   = e.sq_projeto_etapa   (+))
            and (e.sq_siw_solicitacao = f.sq_siw_solicitacao (+))
            and (f.sq_siw_solicitacao = g.sq_siw_solicitacao (+) 
            and  g.vencimento (+)     between a.inicio and a.fim)
            and (f.outra_parte        = h.sq_pessoa          (+))
            and (f.sq_siw_solicitacao = i.sq_siw_solicitacao (+))
            and (i.sq_siw_tramite     = j.sq_siw_tramite     (+) 
            and  'CA'                 <> nvl(j.sigla,'--')   (+))
            and a.sq_siw_solicitacao = p_chave
            and (p_bolsista          is null or (p_bolsista          is not null and f.outra_parte      = p_bolsista))
            and (p_tema              is null or (p_tema              is not null and c.sq_projeto_etapa = p_tema))
            and (f.sq_siw_solicitacao is null or (f.sq_siw_solicitacao is not null and g.sq_acordo_parcela is not null))
          order by c.ordem, d.ordem, h.nome, g.vencimento;
   Elsif p_restricao = 'MENSAL' Then
      open p_result for 
         select distinct a.sq_siw_solicitacao chave,
                a.fim fim_projeto, a.inicio inicio_projeto,
                a.valor valor_projeto, ceil(months_between(a.fim,a.inicio)) meses_projeto,
                ceil(months_between(a.fim,g.vencimento)) meses_fim_projeto,
                d.ordem or_nivel, d.titulo nm_nivel, d.orcamento valor_nivel,
                ceil(months_between(d.fim_previsto,d.inicio_previsto)) meses_nivel,
                f.inicio, f.fim, g.valor valor_parcela, f.valor_inicial, f.sq_siw_solicitacao sq_acordo, f.outra_parte,
                g.vencimento, to_char(g.vencimento, 'DD/MM/YYYY, HH24:MI:SS') phpdt_vencimento
           from siw_solicitacao    a,
                pj_projeto         b,
                pj_projeto_etapa   c,
                co_pessoa          l,
                pj_projeto_etapa   d,
                pj_etapa_contrato  e,
                ac_acordo          f,
                ac_acordo_parcela  g,
                co_pessoa          h,
                siw_solicitacao    i,
                siw_tramite        j
          where (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
            and (a.sq_siw_solicitacao = c.sq_siw_solicitacao 
            and  c.sq_etapa_pai       is null)
            and (c.sq_pessoa          = l.sq_pessoa)
            and (c.sq_projeto_etapa   = d.sq_etapa_pai)
            and (d.sq_projeto_etapa   = e.sq_projeto_etapa   (+))
            and (e.sq_siw_solicitacao = f.sq_siw_solicitacao (+))
            and (f.sq_siw_solicitacao = g.sq_siw_solicitacao (+) 
            and  g.vencimento (+)     between a.inicio and a.fim)
            and (f.outra_parte        = h.sq_pessoa          (+))
            and (f.sq_siw_solicitacao = i.sq_siw_solicitacao (+))
            and (i.sq_siw_tramite     = j.sq_siw_tramite     (+) 
            and  'CA'                 <> nvl(j.sigla,'--')   (+))
            and a.sq_siw_solicitacao = p_chave
            and (f.sq_siw_solicitacao is null or (f.sq_siw_solicitacao is not null and g.sq_acordo_parcela is not null))
          order by g.vencimento, d.ordem;
   End If;
end SP_GetRelBolsista;
/
