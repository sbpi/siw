create or replace procedure SP_GetRelBolsista
   (p_chave                in number,
    p_bolsista             in number   default null,
    p_etapa                in number   default null,
    p_restricao            in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      open p_result for 
         select a.sq_siw_solicitacao chave, 
                a.valor, ceil(months_between(a.fim,a.inicio)) meses_projeto,
                c.sq_projeto_etapa sq_tema, c.ordem or_tema, c.titulo nm_tema, 
                d.sq_projeto_etapa sq_nivel, d.ordem or_nivel, d.titulo nm_nivel, 
                f.inicio, f.fim, g.valor valor_parcela, f.valor_inicial, f.sq_siw_solicitacao sq_acordo, f.outra_parte,
                g.vencimento, to_char(g.vencimento, 'DD/MM/YYYY, HH24:MI:SS') phpdt_vencimento,
                h.nome nm_bolsista,
                j.ativo contrato_ativo,
                l.nome_resumido nm_resp_etapa
           from siw_solicitacao a
                inner           join pj_projeto         b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                inner           join pj_projeto_etapa   c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao and
                                                              c.sq_etapa_pai       is null)
                  inner         join co_pessoa          l on (c.sq_pessoa          = l.sq_pessoa)
                  inner         join pj_projeto_etapa   d on (c.sq_projeto_etapa   = d.sq_etapa_pai)
                    inner       join pj_etapa_contrato  e on (d.sq_projeto_etapa   = e.sq_projeto_etapa)
                      inner     join ac_acordo          f on (e.sq_siw_solicitacao = f.sq_siw_solicitacao)
                        inner   join ac_acordo_parcela  g on (f.sq_siw_solicitacao = g.sq_siw_solicitacao)
                        inner   join co_pessoa          h on (f.outra_parte        = h.sq_pessoa)
                        inner   join siw_solicitacao    i on (f.sq_siw_solicitacao = i.sq_siw_solicitacao)
                          inner join siw_tramite        j on (i.sq_siw_tramite     = j.sq_siw_tramite and
                                                              'CA'                 <> nvl(j.sigla,'--')
                                                             )
          where a.sq_siw_solicitacao = p_chave
            and ((p_bolsista is null) or (p_bolsista is not null and f.outra_parte = p_bolsista))
            and g.vencimento between a.inicio and a.fim;
   Elsif p_restricao = 'TEMA' Then
      open p_result for 
         select a.sq_siw_solicitacao chave, 
                c.ordem or_tema, c.titulo nm_tema, 
                d.ordem or_nivel, d.titulo nm_nivel, 
                f.inicio, f.fim, f.valor_inicial, f.sq_siw_solicitacao sq_acordo, f.outra_parte
           from siw_solicitacao a
                inner join pj_projeto                 b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                inner join pj_projeto_etapa           c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao and
                                                            c.sq_etapa_pai       is null)
                  inner join pj_projeto_etapa         d on (c.sq_projeto_etapa   = d.sq_etapa_pai)
                    inner join pj_etapa_contrato      e on (d.sq_projeto_etapa   = e.sq_projeto_etapa)
                      inner join ac_acordo            f on (e.sq_siw_solicitacao = f.sq_siw_solicitacao)
          where a.sq_siw_solicitacao = p_chave
            and ((p_etapa is null) or (p_etapa is not null and c.sq_projeto_etapa = p_etapa));
   End If;
end SP_GetRelBolsista;
/
