create or replace procedure SP_GetSolicResultado
   (
    p_cliente     in number,
    p_programa    in number   default null,
    p_projeto     in number   default null,
    p_unidade     in number   default null,
    p_solicitante in number   default null,
    p_texto       in varchar2 default null,
    p_inicio      in date     default null,
    p_fim         in date     default null,
    p_restricao   in varchar2 default null,
    p_result      out sys_refcursor) is
begin
   If p_restricao = 'LISTA' Then
      -- Recupera todas as etapas de um projeto
      open p_result for 
         select a.sq_projeto_etapa, a.sq_siw_solicitacao, a.sq_etapa_pai, a.ordem, a.titulo, a.descricao, a.inicio_previsto, a.fim_previsto,
                a.inicio_real, a.fim_real, a.perc_conclusao, a.orcamento, a.sq_unidade, a.sq_pessoa, a.vincula_atividade, a.sq_pessoa_atualizacao,
                a.ultima_atualizacao, a.situacao_atual, a.unidade_medida, a.quantidade, a.cumulativa, a.programada, a.exequivel,
                a.justificativa_inexequivel, a.outras_medidas, a.vincula_contrato, a.peso, a.pacote_trabalho,
                case when a.inicio_real between p_inicio and p_fim then a.inicio_real
                     else case when a.fim_real between p_inicio and p_fim then a.fim_real
                               else case when a.inicio_previsto between p_inicio and p_fim then a.inicio_previsto
                                         else case when a.fim_previsto between p_inicio and p_fim then a.fim_previsto end
                                    end
                          end
                end as mes_ano,
                montaOrdem(a.sq_projeto_etapa) as cd_ordem,
                b.sq_pessoa titular, b1.nome nm_tit_resp, b2.ativo st_tit_resp, b2.email em_tit_resp,
                c.sq_pessoa substituto, c1.nome nm_sub_resp, c2.ativo st_sub_resp, c2.email em_sub_resp,
                k.sq_pessoa tit_exec, l.sq_pessoa sub_exec,
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor, g.nome as nm_setor,
                coalesce(h.qt_ativ,0) qt_ativ, h.sq_menu p2,
                m.vincula_contrato pj_vincula_contrato, coalesce(n.qt_contr,0) , n.sq_menu p3,
                SolicRestricao(a.sq_siw_solicitacao, a.sq_projeto_etapa) as restricao,
                e.email, e.ativo st_resp,
                i.codigo_interno as cd_projeto, i.titulo as nm_projeto, i.executor,
                i1.sq_pessoa tit_proj, i2.sq_pessoa sub_proj,
                i3.sq_siw_solicitacao as sq_programa, i3.codigo_interno as cd_programa, i3.titulo as nm_programa
                
           from pj_projeto_etapa                    a
                inner          join siw_solicitacao i  on (a.sq_siw_solicitacao  = i.sq_siw_solicitacao)
                    inner      join siw_solicitacao i3 on (i.sq_solic_pai        = i3.sq_siw_solicitacao)
                      inner    join pe_programa     i4 on (i3.sq_siw_solicitacao = i4.sq_siw_solicitacao)
                    left       join eo_unidade_resp i1 on (i.sq_unidade          = i1.sq_unidade and
                                                           i1.tipo_respons       = 'T'          and
                                                           i1.fim                is null
                                                          )
                    left       join eo_unidade_resp i2 on (i.sq_unidade          = i2.sq_unidade and
                                                           i2.tipo_respons       = 'S'          and
                                                           i2.fim                is null
                                                          )                
                  inner        join pj_projeto      m  on (a.sq_siw_solicitacao  = m.sq_siw_solicitacao)
                  inner        join siw_menu        j  on (i.sq_menu             = j.sq_menu)
                    left       join eo_unidade_resp k  on (j.sq_unid_executora   = k.sq_unidade and
                                                           k.tipo_respons        = 'T'          and
                                                           k.fim                 is null
                                                          )
                    left       join eo_unidade_resp l  on (j.sq_unid_executora   = l.sq_unidade and
                                                           l.tipo_respons        = 'S'          and
                                                           l.fim                 is null
                                                          )
                left           join eo_unidade_resp b  on (a.sq_unidade          = b.sq_unidade and
                                                           b.tipo_respons        = 'T'          and
                                                           b.fim                 is null
                                                          )
                  left         join co_pessoa       b1 on (b.sq_pessoa           = b1.sq_pessoa)
                    left       join sg_autenticacao b2 on (b1.sq_pessoa          = b2.sq_pessoa)
                left           join eo_unidade_resp c  on (a.sq_unidade          = c.sq_unidade and
                                                           c.tipo_respons        = 'S'          and
                                                           c.fim                 is null
                                                          )
                  left         join co_pessoa       c1 on (c.sq_pessoa           = c1.sq_pessoa)
                    left       join sg_autenticacao c2 on (c1.sq_pessoa          = c2.sq_pessoa)
                inner          join co_pessoa       d  on (a.sq_pessoa           = d.sq_pessoa)
                  inner        join sg_autenticacao e  on (d.sq_pessoa           = e.sq_pessoa)
                    inner      join eo_unidade      f  on (e.sq_unidade          = f.sq_unidade)
                inner          join eo_unidade      g  on (a.sq_unidade          = g.sq_unidade)
                left           join (select x.sq_projeto_etapa, y.sq_menu, count(*) qt_ativ
                                       from pj_etapa_demanda             x
                                            inner   join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                              inner join siw_tramite     z on (y.sq_siw_tramite     = z.sq_siw_tramite and
                                                                               coalesce(z.sigla,'-')     <> 'CA'
                                                                              )
                                     group by x.sq_projeto_etapa, y.sq_menu
                                )                   h  on (h.sq_projeto_etapa = a.sq_projeto_etapa)
                left           join (select x.sq_projeto_etapa, y.sq_menu, count(*) qt_contr
                                       from pj_etapa_contrato            x
                                            inner   join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                              inner join siw_tramite     z on (y.sq_siw_tramite     = z.sq_siw_tramite and
                                                                               coalesce(z.sigla,'-')     <> 'CA'
                                                                              )
                                     group by x.sq_projeto_etapa, y.sq_menu
                                )                   n  on (n.sq_projeto_etapa = a.sq_projeto_etapa)
          where (j.sq_pessoa = p_cliente) 
            and (p_programa    is null or (p_programa    is not null and i.sq_solic_pai       = p_programa))
            and (p_projeto     is null or (p_projeto     is not null and i.sq_siw_solicitacao = p_projeto))
            and (p_unidade     is null or (p_unidade     is not null and a.sq_unidade         = p_unidade))
            and (p_solicitante is null or (p_solicitante is not null and a.sq_pessoa          = p_solicitante))
            and (p_inicio      is null or (p_inicio      is not null and (a.inicio_previsto between p_inicio and p_fim or
                                                                          a.fim_previsto    between p_inicio and p_fim or
                                                                          a.inicio_real     between p_inicio and p_fim or
                                                                          a.fim_real        between p_inicio and p_fim
                                                                         )
                                           )
                )
            and (p_texto       is null or (p_texto       is not null and (acentos(a.titulo)    like '%'||acentos(p_texto)||'%' or
                                                                          acentos(a.descricao) like '%'||acentos(p_texto)||'%' or
                                                                          (a.perc_conclusao=100 and acentos(a.situacao_atual) like '%'||acentos(p_texto)||'%')
                                                                         )
                                           )
                );         
              Else
      -- Recupera os dados da etapa pelo nome
      open p_result for 
         select a.sq_projeto_etapa, a.sq_siw_solicitacao, a.sq_etapa_pai, a.ordem, a.titulo, a.descricao, a.inicio_previsto, a.fim_previsto, 
                a.inicio_real, a.fim_real, a.perc_conclusao, a.orcamento, a.sq_unidade, a.sq_pessoa, a.vincula_atividade, a.sq_pessoa_atualizacao, 
                a.ultima_atualizacao, a.situacao_atual, a.unidade_medida, a.quantidade, a.cumulativa, a.programada, a.exequivel, 
                a.justificativa_inexequivel, a.outras_medidas, a.vincula_contrato, a.pacote_trabalho, a.peso
           from pj_projeto_etapa   a;
--          where a.sq_siw_solicitacao = p_chave
--            and acentos(a.titulo,1)  = p_restricao;
   End If;
End SP_GetSolicResultado             ;
/
