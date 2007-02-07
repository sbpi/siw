create or replace procedure SP_GetRelBolsista
   (p_chave                in number,
    p_bolsista             in number   default null,
    p_tema                 in number   default null,
    p_nivel                in number   default null,
    p_contrato             in number   default null,
    p_mes                  in varchar2 default null,
    p_ano                  in varchar2 default null,
    p_restricao            in varchar2 default null,
    p_result               out sys_refcursor) is
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
           from siw_solicitacao                         a
                inner           join pj_projeto         b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                inner           join pj_projeto_etapa   c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao and
                                                              c.sq_etapa_pai       is null)
                  inner         join co_pessoa          l on (c.sq_pessoa          = l.sq_pessoa)
                  inner         join pj_projeto_etapa   d on (c.sq_projeto_etapa   = d.sq_etapa_pai)
                    inner       join pj_etapa_contrato  e on (d.sq_projeto_etapa   = e.sq_projeto_etapa)
                      inner     join ac_acordo          f on (e.sq_siw_solicitacao = f.sq_siw_solicitacao)
                        inner   join ac_acordo_parcela  g on (f.sq_siw_solicitacao = g.sq_siw_solicitacao and
                                                              g.vencimento         between a.inicio and a.fim
                                                             )
                        inner   join co_pessoa          h on (f.outra_parte        = h.sq_pessoa)
                        inner   join siw_solicitacao    i on (f.sq_siw_solicitacao = i.sq_siw_solicitacao)
                          inner join siw_tramite        j on (i.sq_siw_tramite     = j.sq_siw_tramite and
                                                              'CA'                 <> nvl(j.sigla,'--')
                                                             )
          where a.sq_siw_solicitacao = p_chave
            and (p_bolsista          is null or (p_bolsista          is not null and f.outra_parte      = p_bolsista))
            and (p_tema              is null or (p_tema              is not null and c.sq_projeto_etapa = p_tema))
            and (f.sq_siw_solicitacao is null or (f.sq_siw_solicitacao is not null and g.sq_acordo_parcela is not null))
          order by c.ordem, d.ordem, h.nome, g.vencimento;
   Elsif p_restricao = 'TEMA' Then
       open p_result for 
         select a.sq_siw_solicitacao chave,
                a.fim fim_projeto, a.inicio inicio_projeto,
                a.valor valor_projeto, ceil(months_between(a.fim,a.inicio)) meses_projeto,
                ceil(months_between(a.fim,sysdate)) meses_fim_projeto,
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
                j.sigla,
                m.nome nm_projeto_resp
           from siw_solicitacao a
                inner           join pj_projeto         b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                inner           join pj_projeto_etapa   c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao and
                                                              c.sq_etapa_pai       is null)
                  inner         join co_pessoa          l on (c.sq_pessoa          = l.sq_pessoa)
                  inner         join pj_projeto_etapa   d on (c.sq_projeto_etapa   = d.sq_etapa_pai)
                    left        join pj_etapa_contrato  e on (d.sq_projeto_etapa   = e.sq_projeto_etapa)
                      left      join ac_acordo          f on (e.sq_siw_solicitacao = f.sq_siw_solicitacao)
                        left    join ac_acordo_parcela  g on (f.sq_siw_solicitacao = g.sq_siw_solicitacao and
                                                              g.vencimento         between a.inicio and a.fim
                                                             )
                        left    join co_pessoa          h on (f.outra_parte        = h.sq_pessoa)
                        left    join siw_solicitacao    i on (f.sq_siw_solicitacao = i.sq_siw_solicitacao)
                          left  join siw_tramite        j on (i.sq_siw_tramite     = j.sq_siw_tramite and
                                                              'CA'                 <> nvl(j.sigla,'--')
                                                             )
                  inner         join co_pessoa          m on (a.solicitante        = m.sq_pessoa)
          where a.sq_siw_solicitacao = p_chave
            and (p_bolsista          is null or (p_bolsista          is not null and f.outra_parte      = p_bolsista))
            and (p_tema              is null or (p_tema              is not null and c.sq_projeto_etapa = p_tema))
            and (p_mes               is null or (p_mes               is not null and to_number(to_char(g.vencimento,'yyyymm')) = to_number(to_char(p_ano||p_mes))))
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
           from siw_solicitacao a
                inner           join pj_projeto         b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                inner           join pj_projeto_etapa   c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao and
                                                              c.sq_etapa_pai       is null)
                  inner         join pj_projeto_etapa   d on (c.sq_projeto_etapa   = d.sq_etapa_pai)
                    left        join pj_etapa_contrato  e on (d.sq_projeto_etapa   = e.sq_projeto_etapa)
                      left      join ac_acordo          f on (e.sq_siw_solicitacao = f.sq_siw_solicitacao)
                        left    join ac_acordo_parcela  g on (f.sq_siw_solicitacao = g.sq_siw_solicitacao and
                                                              g.vencimento         between a.inicio and a.fim
                                                             )
                        left    join siw_solicitacao    i on (f.sq_siw_solicitacao = i.sq_siw_solicitacao)
                          left  join siw_tramite        j on (i.sq_siw_tramite     = j.sq_siw_tramite and
                                                              'CA'                 <> nvl(j.sigla,'--')
                                                             )
          where a.sq_siw_solicitacao = p_chave
            and (f.sq_siw_solicitacao is null or (f.sq_siw_solicitacao is not null and g.sq_acordo_parcela is not null))
          order by g.vencimento, d.ordem;
   Elsif p_restricao = 'RESUMO' Then
      open p_result for 
         select distinct a.sq_siw_solicitacao chave,
                a.fim fim_projeto, a.inicio inicio_projeto,
                a.valor valor_projeto, ceil(months_between(a.fim,a.inicio)) meses_projeto,
                ceil(months_between(a.fim,sysdate)) meses_fim_projeto,
                d.sq_projeto_etapa sq_nivel, d.ordem or_nivel, d.titulo nm_nivel, d.orcamento valor_nivel,
                ceil(months_between(d.fim_previsto,d.inicio_previsto)) meses_nivel,
                f.inicio, f.fim, g.valor valor_parcela, f.valor_inicial, f.sq_siw_solicitacao sq_acordo, f.outra_parte,
                g.vencimento, to_char(g.vencimento, 'DD/MM/YYYY, HH24:MI:SS') phpdt_vencimento,
                h.nome nm_bolsista,
                j.ativo contrato_ativo,
                l.nome_resumido nm_resp_etapa,
                j.sigla,
                m.nome nm_projeto_resp
           from siw_solicitacao a
                inner           join pj_projeto         b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                inner           join pj_projeto_etapa   c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao and
                                                              c.sq_etapa_pai       is null)
                  inner         join co_pessoa          l on (c.sq_pessoa          = l.sq_pessoa)
                  inner         join pj_projeto_etapa   d on (c.sq_projeto_etapa   = d.sq_etapa_pai)
                    left        join pj_etapa_contrato  e on (d.sq_projeto_etapa   = e.sq_projeto_etapa)
                      left      join ac_acordo          f on (e.sq_siw_solicitacao = f.sq_siw_solicitacao)
                        left    join ac_acordo_parcela  g on (f.sq_siw_solicitacao = g.sq_siw_solicitacao and
                                                              g.vencimento         between a.inicio and a.fim
                                                             )
                        left    join co_pessoa          h on (f.outra_parte        = h.sq_pessoa)
                        left    join siw_solicitacao    i on (f.sq_siw_solicitacao = i.sq_siw_solicitacao)
                          left  join siw_tramite        j on (i.sq_siw_tramite     = j.sq_siw_tramite and
                                                              'CA'                 <> nvl(j.sigla,'--')
                                                             )
                  inner         join co_pessoa          m on (a.solicitante        = m.sq_pessoa)
          where a.sq_siw_solicitacao = p_chave
            and (g.vencimento is null or (p_mes is not null and to_number(to_char(g.vencimento,'yyyymm')) = to_number(to_char(p_ano||p_mes))))
          order by d.ordem, d.titulo;
   Elsif p_restricao = 'RESUMO1' Then
      open p_result for 
         select a.sq_siw_solicitacao chave,
                a.fim fim_projeto, a.inicio inicio_projeto,
                a.valor valor_projeto, ceil(months_between(a.fim,a.inicio)) meses_projeto,
                ceil(months_between(a.fim,sysdate)) meses_fim_projeto,
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
                j.sigla,
                m.nome nm_projeto_resp
           from siw_solicitacao a
                inner           join pj_projeto         b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                inner           join pj_projeto_etapa   c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao and
                                                              c.sq_etapa_pai       is null)
                  left          join co_pessoa          l on (c.sq_pessoa          = l.sq_pessoa)
                  left          join pj_projeto_etapa   d on (c.sq_projeto_etapa   = d.sq_etapa_pai)
                    left        join pj_etapa_contrato  e on (d.sq_projeto_etapa   = e.sq_projeto_etapa)
                      left      join ac_acordo          f on (e.sq_siw_solicitacao = f.sq_siw_solicitacao)
                        left    join ac_acordo_parcela  g on (f.sq_siw_solicitacao = g.sq_siw_solicitacao and
                                                              g.vencimento         between a.inicio and a.fim
                                                             )
                        left    join co_pessoa          h on (f.outra_parte        = h.sq_pessoa)
                        left    join siw_solicitacao    i on (f.sq_siw_solicitacao = i.sq_siw_solicitacao)
                          left  join siw_tramite        j on (i.sq_siw_tramite     = j.sq_siw_tramite and
                                                              'CA'                 <> nvl(j.sigla,'--')
                                                             )
                  inner         join co_pessoa          m on (a.solicitante        = m.sq_pessoa)
          where a.sq_siw_solicitacao = p_chave
            and (p_bolsista           is null or (p_bolsista           is not null and f.outra_parte      = p_bolsista))
            and (p_tema               is null or (p_tema               is not null and c.sq_projeto_etapa = p_tema))
            and (g.vencimento         is null or (p_mes                is not null and to_number(to_char(g.vencimento,'yyyymm')) = to_number(to_char(p_ano||p_mes))))
            and (f.sq_siw_solicitacao is null or (f.sq_siw_solicitacao is not null and g.sq_acordo_parcela is not null))
          order by c.ordem, d.ordem, h.nome, g.vencimento;
   End If;
end SP_GetRelBolsista;
/
