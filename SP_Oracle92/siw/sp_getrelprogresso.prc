create or replace procedure SP_GetRelProgresso
   (p_cliente              in number,
    p_plano                in number default null,
    p_objetivo             in number default null,
    p_programa             in number default null,
    p_chave                in number default null,
    p_inicio               in date,
    p_fim                  in date,
    p_restricao            in varchar2 default null,
    p_result               out sys_refcursor) is

    w_inicio date := p_inicio;
    w_fim    date := p_fim;
    w_dias   number(18);
    w_mes    date;
begin
   If p_restricao = 'PROENTR' Then
      If to_char(p_inicio,'dd') = '01' and p_fim = last_day(p_inicio) Then
         w_mes    := add_months(w_inicio,1);
         w_inicio := to_date('01/'||to_char(w_mes,'mm/yyyy'),'dd/mm/yyyy');
         w_fim    := last_day(w_mes);
      Elsif to_char(p_inicio,'dd') = '01' and to_char(p_fim,'dd') = '15' and to_char(p_inicio,'yyyymm') = to_char(p_fim,'yyyymm') Then
         w_inicio := p_fim + 1;
         w_fim    := last_day(w_inicio);
      Else
         w_dias   := w_fim - w_inicio + 1;
         w_inicio := w_inicio + w_dias;
         w_fim    := w_fim + w_dias;
      End If;
   Else
      w_inicio := p_inicio;
      w_fim    := p_fim;
   End If;
   
   If substr(p_restricao,1,3) = 'PRO' Then
      open p_result for 
         select a.sq_projeto_etapa, a.ordem, a.titulo nm_etapa, a.sq_pessoa, h.nome_resumido nm_resp_etapa, a.fim_previsto, a.situacao_atual,
                a.perc_conclusao, a.fim_real fim_real_etapa, a.sq_unidade, a.inicio_previsto, a.inicio_real inicio_real_etapa, a.pacote_trabalho,
                a.peso,
                montaOrdem(a.sq_projeto_etapa) as cd_ordem,
                b.sq_siw_solicitacao sq_projeto, b.titulo nm_projeto, c.inicio inicio_projeto, c.fim fim_projeto,
                i.sq_menu, i.sq_tarefa, i.nm_tarefa, i.solicitante, i.nm_resp_tarefa, i.inicio, i.fim, i.inicio_real, i.fim_real,
                i.concluida, i.aviso_prox_conc, i.aviso, i.sg_tramite, i.nm_tramite, w_inicio as ini_prox_per, w_fim as fim_prox_per,
                SolicRestricao(a.sq_siw_solicitacao, a.sq_projeto_etapa) as restricao
           from pj_projeto_etapa               a
                left     join co_pessoa        h on (a.sq_pessoa          = h.sq_pessoa)
                left     join pj_projeto       b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                left     join siw_solicitacao  c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
                  left   join siw_menu         d on (c.sq_menu            = d.sq_menu)
                  left   join siw_tramite      j on (c.sq_siw_tramite     = j.sq_siw_tramite)
                left     join pj_etapa_demanda e on (a.sq_projeto_etapa   = e.sq_projeto_etapa)
                  left   join (select k.sq_siw_solicitacao as sq_tarefa, k.solicitante, k.inicio, k.fim, k.sq_menu,
                                      l.assunto nm_tarefa, m.nome_resumido nm_resp_tarefa, l.inicio_real, l.fim_real,
                                      l.concluida, l.aviso_prox_conc, l.dias_aviso aviso,
                                      n.sigla sg_tramite, n.nome nm_tramite
                                 from siw_solicitacao             k
                                      inner join gd_demanda       l on (k.sq_siw_solicitacao = l.sq_siw_solicitacao)
                                      inner join co_pessoa        m on (k.solicitante        = m.sq_pessoa)
                                      inner join siw_tramite      n on (k.sq_siw_tramite     = n.sq_siw_tramite and
                                                                        'CA'                 <> coalesce(n.sigla,'---')
                                                                       )
                                where (p_restricao = 'PROREPORT' and l.fim_real between w_inicio and w_fim)
                                   or (p_restricao = 'PROPREV'   and k.fim      between w_inicio and w_fim)
                                   or (p_restricao = 'PROENTR'   and k.fim between w_inicio and w_fim)
                                   or (p_restricao = 'PROPEND'   and ((l.fim_real   is null and k.fim <= w_fim) or
                                                                      (l.fim_real   is not null and k.fim between w_inicio and w_fim and l.fim_real > w_fim)
                                                                     )
                                      )
                              )                i on (e.sq_siw_solicitacao = i.sq_tarefa)
          where d.sq_pessoa       = p_cliente
            and j.sigla           <> 'CA'
            and ((i.sq_tarefa is null and a.pacote_trabalho = 'S') or i.sq_tarefa is not null)
            and (p_chave     is null or (p_chave       is not null and a.sq_siw_solicitacao   = p_chave))
            and (i.sq_tarefa is not null or
                 ((p_restricao = 'PROPREV'   and a.fim_previsto between w_inicio and w_fim)) or
                  (p_restricao = 'PROREPORT' and a.fim_real     between w_inicio and w_fim) or
                  (p_restricao = 'PROENTR'   and a.fim_previsto between w_inicio and w_fim) or
                  (p_restricao = 'PROPEND'   and ((a.fim_real   is null and a.fim_previsto <= w_fim) or
                                                  (a.fim_real   is not null and a.fim_previsto between w_inicio and w_fim and a.fim_real > w_fim)
                                                 )
                  )
                );
  ElsIf p_restricao = 'RELATORIO' Then
      open p_result for 
         select a.sq_projeto_etapa, a.ordem, a.titulo nm_etapa, a.sq_pessoa, a.fim_previsto, a.situacao_atual, a.perc_conclusao, a.fim_real, 
                montaOrdem(a.sq_projeto_etapa) as cd_ordem,
                b.sq_siw_solicitacao as sq_projeto, b.titulo as nm_projeto, 
                c.inicio as inicio_projeto, c.fim as fim_projeto, c.sq_siw_solicitacao as sq_projeto,
                c1.sq_pessoa as resp_projeto, c1.nome_resumido as nm_resp_projeto, 
                c2.titulo as nm_programa,
                c7.nome as nm_cc, 
                case when c4.sq_peobjetivo is not null then c4.nome else c5.nome end as nm_objetivo,
                e.sq_siw_solicitacao, 
                f.assunto as nm_tarefa, f.fim_real, 
                g.solicitante, 
                i.nome_resumido as nm_resp_tarefa, i.nome_resumido,
                g.inicio, 
                h.nome_resumido as nm_resp_etapa, 
                case when k.sq_plano is not null then k.titulo else c6.titulo end as nm_plano, 
                m1.sq_unidade, m1.nome as nm_unidade,
                calculaIGE(c.sq_siw_solicitacao) as ige, calculaIDE(c.sq_siw_solicitacao, w_fim, w_inicio) as ide                
           from pj_projeto_etapa                    a
                inner         join co_pessoa        h  on (a.sq_pessoa           = h.sq_pessoa)
                inner         join pj_projeto       b  on (a.sq_siw_solicitacao  = b.sq_siw_solicitacao)
                inner         join siw_solicitacao  c  on (a.sq_siw_solicitacao  = c.sq_siw_solicitacao)
                  inner       join co_pessoa        c1 on (c.solicitante         = c1.sq_pessoa)
                    inner     join sg_autenticacao  m  on (c1.sq_pessoa          = m.sq_pessoa)
                      inner   join eo_unidade       m1 on (m.sq_unidade          = m1.sq_unidade) 
                  inner       join siw_tramite      j  on (c.sq_siw_tramite      = j.sq_siw_tramite and 
                                                           'CA'                  <> coalesce(j.sigla,'-')
                                                          )
                  inner       join siw_menu         d  on (c.sq_menu             = d.sq_menu)
                  left        join pe_programa      c2 on (c.sq_solic_pai        = c2.sq_siw_solicitacao)
                    left      join siw_solicitacao  c3 on (c2.sq_siw_solicitacao = c3.sq_siw_solicitacao)
                      left    join pe_objetivo      c4 on (c3.sq_peobjetivo      = c4.sq_peobjetivo)
                        left  join pe_plano         k  on (c4.sq_plano           = k.sq_plano)
                  left        join pe_objetivo      c5 on (c.sq_peobjetivo       = c5.sq_peobjetivo)
                    left      join pe_plano         c6 on (c5.sq_plano           = c6.sq_plano)
                  left        join ct_cc            c7 on (c.sq_cc               = c7.sq_cc)
                left          join pj_etapa_demanda e  on (a.sq_projeto_etapa    = e.sq_projeto_etapa)
                  left        join gd_demanda       f  on (e.sq_siw_solicitacao  = f.sq_siw_solicitacao)
                  left        join siw_solicitacao  g  on (e.sq_siw_solicitacao  = g.sq_siw_solicitacao)
                    left      join co_pessoa        i  on (g.solicitante         = i.sq_pessoa)
          where d.sq_pessoa      = p_cliente
            and (p_chave         is null or (p_chave       is not null and a.sq_siw_solicitacao = p_chave))
            and (p_programa      is null or (p_programa    is not null and c.sq_solic_pai       = p_programa))
            and (p_objetivo      is null or (p_objetivo    is not null and (c.sq_peobjetivo     = p_objetivo or c3.sq_peobjetivo = p_objetivo)))
            and (p_plano         is null or (p_plano       is not null and (c4.sq_plano         = p_plano    or c5.sq_plano      = p_plano)))
            and (p_chave         is not null or 
                 (p_chave        is null and
                  (c.inicio      between w_inicio      and w_fim or
                   c.fim         between w_inicio      and w_fim or
                   w_inicio      between c.inicio      and c.fim or
                   w_fim         between c.inicio      and c.fim or
                   b.inicio_real between w_inicio      and w_fim or
                   b.fim_real    between w_inicio      and w_fim or
                   w_inicio      between b.inicio_real and b.fim_real or
                   w_fim         between b.inicio_real and b.fim_real
                  )
                 )
                );            
  ElsIf p_restricao = 'REL_DET' Then
      open p_result for 
         select distinct a.sq_siw_solicitacao as sq_projeto,
                e.titulo as nm_projeto
           from siw_solicitacao  a
                inner       join siw_menu         d on (a.sq_menu             = d.sq_menu)
                inner       join pj_projeto       e on (a.sq_siw_solicitacao  = e.sq_siw_solicitacao)
                inner       join siw_tramite      f on (a.sq_siw_tramite      = f.sq_siw_tramite)
                left        join pe_programa      b  on (a.sq_solic_pai       = b.sq_siw_solicitacao)
                  left      join siw_solicitacao  b1 on (b.sq_siw_solicitacao = b1.sq_siw_solicitacao)
                  left      join pe_objetivo      b2 on (b1.sq_peobjetivo      = b2.sq_peobjetivo)
                  left      join pe_plano         b3 on (b2.sq_plano          = b3.sq_plano)
                left        join pe_objetivo      c  on (a.sq_peobjetivo      = c.sq_peobjetivo)
                  left      join pe_plano         c1 on (c.sq_plano           = c1.sq_plano)
          where d.sq_pessoa      = p_cliente
            and 'CA'             <> coalesce(f.sigla,'-')
            and (p_chave         is null or (p_chave       is not null and a.sq_siw_solicitacao = p_chave))
            and (p_programa      is null or (p_programa    is not null and a.sq_solic_pai       = p_programa))
            and (p_objetivo      is null or (p_objetivo    is not null and (a.sq_peobjetivo      = p_objetivo or b1.sq_peobjetivo = p_objetivo)))
            and (p_plano         is null or (p_plano       is not null and (b2.sq_plano          = p_plano    or c.sq_plano = p_plano)));
  End If;
end SP_GetRelProgresso;
/
