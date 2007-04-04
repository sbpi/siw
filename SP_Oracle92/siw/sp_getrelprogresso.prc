create or replace procedure SP_GetRelProgresso
   (p_cliente              in number,
    p_chave                in number,
    p_inicio               in date,
    p_fim                  in date,
    p_restricao            in varchar2 default null,
    p_result               out sys_refcursor) is
begin
   If substr(p_restricao,1,3) = 'PRO' Then
      open p_result for 
         select a.sq_projeto_etapa, a.ordem, a.titulo nm_etapa, a.sq_pessoa, h.nome_resumido nm_resp_etapa, a.fim_previsto, a.situacao_atual,
                a.perc_conclusao, a.fim_real fim_real_etapa, a.sq_unidade, a.inicio_previsto, a.inicio_real inicio_real_etapa,
                g.sq_siw_solicitacao sq_tarefa, f.assunto nm_tarefa, g.solicitante, i.nome_resumido nm_resp_tarefa, g.inicio, g.fim, f.inicio_real, f.fim_real,
                b.sq_siw_solicitacao sq_projeto, b.titulo nm_projeto, c.inicio inicio_projeto, c.fim fim_projeto,
                f.concluida, f.aviso_prox_conc, f.dias_aviso aviso,
                l.sigla sg_tramite, l.nome nm_tramite
           from pj_projeto_etapa               a
                left     join co_pessoa        h on (a.sq_pessoa          = h.sq_pessoa)
                left     join pj_projeto       b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                left     join siw_solicitacao  c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
                  left   join siw_menu         d on (c.sq_menu            = d.sq_menu)
                  left   join siw_tramite      j on (c.sq_siw_tramite     = j.sq_siw_tramite)
                left     join pj_etapa_demanda e on (a.sq_projeto_etapa   = e.sq_projeto_etapa)
                  left   join siw_solicitacao  g on (e.sq_siw_solicitacao = g.sq_siw_solicitacao and
                                                     ((g.fim between p_inicio and p_fim) and a.fim_real is null))
                    left join gd_demanda       f on (g.sq_siw_solicitacao = f.sq_siw_solicitacao)
                    left join co_pessoa        i on (g.solicitante        = i.sq_pessoa)
                    left join siw_tramite      l on (g.sq_siw_tramite     = l.sq_siw_tramite)
          where a.pacote_trabalho = 'S'
            and d.sq_pessoa       = p_cliente
            and j.sigla           <> 'CA'
            and (p_chave     is null or (p_chave       is not null and a.sq_siw_solicitacao   = p_chave))
            and ((p_restricao <> 'PROPREV'   and p_restricao <> 'PROREPORT' and p_restricao <> 'PROENTR') or
                 ((p_restricao = 'PROPREV'   and (((a.fim_previsto between p_inicio and p_fim) and a.perc_conclusao < 100 )
                    or ((g.fim     between p_inicio and p_fim) and a.fim_real is null)
                                                 )
                  ) or
                  (p_restricao = 'PROREPORT' and ((a.fim_real between p_inicio and p_fim)
                    or (f.fim_real between p_inicio and p_fim)
                                                 )
                  ) or
                  (p_restricao = 'PROENTR'   and ((((a.fim_previsto > p_fim) or (a.fim_previsto < p_inicio and a.perc_conclusao < 100))
                                                  ) or ((g.fim > p_fim)
                     or (g.inicio < p_inicio and f.fim_real is null)
                                                       )
                                                 )
                   )
                  )
                 );
  ElsIf p_restricao = 'RELATORIO' Then
      open p_result for 
         select a.sq_projeto_etapa, a.ordem, a.titulo nm_etapa, a.sq_pessoa, h.nome_resumido nm_resp_etapa, a.fim_previsto, a.situacao_atual,
                a.perc_conclusao, a.fim_real,
                e.sq_siw_solicitacao, f.assunto nm_tarefa, g.solicitante, i.nome_resumido nm_resp_tarefa, g.inicio, f.fim_real,
                b.titulo nm_projeto, c.inicio inicio_projeto, c.fim fim_projeto, c.sq_siw_solicitacao sq_projeto,
                calculaigc(c.sq_siw_solicitacao) as igc, calculaide(c.sq_siw_solicitacao, p_fim) as ide                
           from pj_projeto_etapa               a
                left     join co_pessoa        h on (a.sq_pessoa          = h.sq_pessoa)
                left     join pj_projeto       b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                left     join siw_solicitacao  c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
                  left   join siw_menu         d on (c.sq_menu            = d.sq_menu)
                  left   join siw_tramite      j on (c.sq_siw_tramite     = j.sq_siw_tramite)
                left     join pj_etapa_demanda e on (a.sq_projeto_etapa   = e.sq_projeto_etapa)
                  left   join gd_demanda       f on (e.sq_siw_solicitacao = f.sq_siw_solicitacao)
                  left   join siw_solicitacao  g on (e.sq_siw_solicitacao = g.sq_siw_solicitacao)
                    left join co_pessoa        i on (g.solicitante        = i.sq_pessoa)
          where a.pacote_trabalho = 'S'
            and d.sq_pessoa       = p_cliente
            and j.sigla           <> 'CA'
            and (p_chave     is null or (p_chave       is not null and a.sq_siw_solicitacao   = p_chave))
            --and (p_inicio    is null or (c.inicio between p_inicio and p_fim and c.fim between p_inicio and p_fim));
            and (
                 (
                  ((a.fim_previsto between p_inicio and p_fim) and a.perc_conclusao < 100) or 
                  ((g.fim     between p_inicio and p_fim) and a.fim_real is null)
                 ) or
                 (
                  (a.fim_real between p_inicio and p_fim) or 
                  (f.fim_real between p_inicio and p_fim)
                 ) or
                 (
                  ((a.fim_previsto > p_fim) or (a.fim_previsto < p_inicio and a.perc_conclusao < 100)
                  ) or 
                  (a.inicio_previsto < p_inicio and a.perc_conclusao < 100)
                 ) or 
                 (
                  (g.fim > p_fim) or 
                  (g.inicio < p_inicio and f.fim_real is null)
                 )
                );            
  End If;
end SP_GetRelProgresso;
/
