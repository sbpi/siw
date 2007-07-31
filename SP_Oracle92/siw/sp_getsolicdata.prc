create or replace procedure SP_GetSolicData
   (p_chave     in number,
    p_restricao in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      open p_result for select dados_solic(p_chave) as dados_solic from dual;
   Elsif substr(p_restricao,1,2) = 'GD' or p_restricao = 'ORPGERAL' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,                  a.arquivo_proced,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao,                   a.justificativa,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec, a2.informal informal_exec,
                a2.vinculada vinc_exec,a2.adm_central adm_exec,
                a3.sq_pessoa tit_exec,a4.sq_pessoa subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,
                b.fim-d.dias_aviso aviso,
                case when b.sq_solic_pai is null 
                     then case when b3.sq_peobjetivo is null
                               then '---'
                               else 'Plano: '||b4.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                b5.nome nm_unidade,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.sq_unidade_resp,    d.assunto,                     d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                d.ordem,              d.sq_demanda_pai,              d.sq_demanda_tipo,
                d.recebimento,        d.limite_conclusao,            d.responsavel,
                d1.nome nm_demanda_tipo,
                d2.nome_resumido nm_resp,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.nome_resumido nm_sol,
                coalesce(f1.ativo,'N') as st_sol,
                g.sq_cc,              g.nome cc_nome,                g.sigla cc_sigla,
                h.sq_pais,            h.sq_regiao,                   h.co_uf,
                h.nome nm_cidade,
                i.sq_projeto_etapa,   j.titulo nm_etapa,             k1.titulo nm_projeto,
                montaordem(j.sq_projeto_etapa) as cd_ordem,
                l.sq_siw_restricao,   l.descricao as ds_restricao,
                case l.risco when 'S' then 'Risco' else 'Problema' end as nm_tipo_restricao
           from siw_menu                                    a
                inner        join eo_unidade                a2 on (a.sq_unid_executora   = a2.sq_unidade)
                  left       join eo_unidade_resp           a3 on (a2.sq_unidade         = a3.sq_unidade and
                                                                   a3.tipo_respons       = 'T'           and
                                                                   a3.fim                is null
                                                                  )
                  left       join eo_unidade_resp           a4 on (a2.sq_unidade         = a4.sq_unidade and
                                                                   a4.tipo_respons       = 'S'           and
                                                                   a4.fim                is null
                                                                  ) 
                inner        join siw_modulo                a1 on (a.sq_modulo           = a1.sq_modulo)
                inner        join siw_solicitacao           b  on (a.sq_menu             = b.sq_menu)
                  inner      join siw_tramite               b1 on (b.sq_siw_tramite      = b1.sq_siw_tramite)
                  left       join pe_objetivo               b3 on (b.sq_peobjetivo       = b3.sq_peobjetivo)
                    left     join pe_plano                  b4 on (b3.sq_plano           = b4.sq_plano)
                  left       join eo_unidade                b5 on (b.sq_unidade          = b5.sq_unidade)
                  inner      join gd_demanda                d  on (b.sq_siw_solicitacao  = d.sq_siw_solicitacao)
                    left     join gd_demanda_tipo           d1 on (d.sq_demanda_tipo     = d1.sq_demanda_tipo)
                    left     join co_pessoa                 d2 on (d.responsavel         = d2.sq_pessoa)
                    left     join eo_unidade                e  on (d.sq_unidade_resp     = e.sq_unidade)
                      left   join eo_unidade_resp           e1 on (e.sq_unidade          = e1.sq_unidade and
                                                                   e1.tipo_respons      = 'T'           and
                                                                   e1.fim               is null
                                                                  )
                      left   join eo_unidade_resp           e2 on (e.sq_unidade          = e2.sq_unidade and
                                                                   e2.tipo_respons      = 'S'           and
                                                                   e2.fim               is null
                                                                  )
                  inner      join co_pessoa                 f  on (b.solicitante         = f.sq_pessoa)
                    left     join sg_autenticacao           f1 on (f.sq_pessoa           = f1.sq_pessoa)
                  inner      join co_cidade                 h  on (b.sq_cidade_origem    = h.sq_cidade)
                  left       join ct_cc                     g  on (b.sq_cc               = g.sq_cc)
                  left       join siw_restricao             l  on (d.sq_siw_restricao    = l.sq_siw_restricao)
                left         join eo_unidade                c  on (a.sq_unid_executora   = c.sq_unidade)
                left         join pj_etapa_demanda          i  on (b.sq_siw_solicitacao  = i.sq_siw_solicitacao)
                  left       join pj_projeto_etapa          j  on (i.sq_projeto_etapa    = j.sq_projeto_etapa)
                left         join pj_projeto                k  on (b.sq_solic_pai        = k.sq_siw_solicitacao)
                  left       join siw_solicitacao           k1 on (k.sq_siw_solicitacao  = k1.sq_siw_solicitacao)
          where b.sq_siw_solicitacao       = p_chave;
   Elsif substr(p_restricao,1,2) in ('PJ','OR') Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,                  a.arquivo_proced,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao,                   a.justificativa,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec, a2.informal informal_exec,
                a2.vinculada vinc_exec,a2.adm_central adm_exec,
                a3.sq_pessoa tit_exec,a4.sq_pessoa subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.codigo_interno,     b.codigo_externo,              b.titulo,
                b.palavra_chave,      ceil(months_between(b.fim,b.inicio)) meses_projeto,
                case when b.sq_solic_pai is null 
                     then case when b4.sq_peobjetivo is null
                               then '---'
                               else 'Plano: '||b5.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                b2.sq_peobjetivo,     b2.sq_plano,                   b2.nome nm_objetivo, 
                b2.sigla sg_objetivo, b2.descricao ds_objetivo,      b2.ativo st_objetivo,
                b3.sq_plano_pai,      b3.titulo nm_plano,            b3.missao, 
                b3.valores,           b3.visao_presente,             b3.visao_futuro, 
                b3.inicio inicio_plano,b3.fim vim_plano,             b3.ativo st_plano,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.sq_unidade_resp,    d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.aviso_prox_conc_pacote, d.perc_dias_aviso_pacote,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                d.vincula_contrato,   d.vincula_viagem,              d.sq_tipo_pessoa,
                d.outra_parte,        d.preposto,                    d.limite_passagem,
                d.sq_cidade cidade_evento, d.vincula_contrato,       d.objetivo_superior,
                d.exclusoes,          d.premissas,                   d.restricoes,
                b.fim-d.dias_aviso aviso,
                d2.sq_pais pais_evento,d2.co_uf uf_evento,
                d1.nome nm_prop,      d1.nome_resumido nm_prop_res,
                case upper(d3.nome) when 'BRASIL' then d2.nome||'-'||d2.co_uf||' ('||d3.nome||')' else d2.nome||' ('||d3.nome||')' end nm_cidade_evento,
                d4.inicio_etapa,      d4.fim_etapa,
                d4.inicio_etapa,      d4.fim_etapa,
                d5.inicio_etapa_real, d5.fim_etapa_real,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sq_unidade,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.nome_resumido nm_sol,
                coalesce(f1.ativo,'N') as st_sol,
                g.sq_cc,              g.nome cc_nome,                g.sigla cc_sigla,
                h.sq_pais,            h.sq_regiao,                   h.co_uf,
                h.nome nm_cidade,
                h1.nome nm_uf,
                i.sq_acao_ppa,        i.sq_orprioridade,             i.problema,
                i.descricao ds_acao,  i.publico_alvo,                i.estrategia,
                i.indicadores,        i.objetivo,
                j.codigo cd_ppa,      j.nome nm_ppa,                 j.responsavel resp_ppa,
                j.telefone  fone_ppa, j.email mail_ppa,              j.selecionada_mpog mpog_ppa,
                j.sq_acao_ppa_pai,    j.aprovado,                    j.empenhado,
                j.liquidado,          j.liquidar,                    j.saldo,
                j.ativo ativo_ppa,    j.padrao padrao_ppa,           j.selecionada_relevante relev_ppa,
                k.codigo cd_ppa_pai,  k.nome nm_ppa_pai,             k.responsavel resp_ppa_pai,
                k.telefone  fone_ppa_pai, k.email mail_ppa_pai,      k.selecionada_mpog mpog_ppa_pai,
                k.ativo ativo_ppa_pai,k.padrao padrao_ppa_pai,       k.selecionada_relevante relev_ppa_pai,
                l.codigo cd_pri,      l.nome nm_pri,                 l.responsavel resp_pri,
                l.telefone fone_pri,  l.email mail_pri,              l.ordem ord_pri,
                l.ativo ativo_pri,    l.padrao padrao_pri,
                m.sq_acordo,          m.cd_acordo,                   m.nm_acordo,
                m.sigla sg_acordo,
                n.sq_menu sq_menu_pai,
                o.sq_siw_solicitacao sq_programa, o1.codigo_interno cd_programa, o1.titulo nm_programa,
                acentos(b.titulo,1) as ac_titulo,
                calculaIGE(d.sq_siw_solicitacao) as ige, calculaIDE(d.sq_siw_solicitacao) as ide,
                calculaIGC(d.sq_siw_solicitacao) as igc, calculaIDC(d.sq_siw_solicitacao) as idc
           from siw_menu                                     a 
                inner        join eo_unidade                 a2 on (a.sq_unid_executora   = a2.sq_unidade)
                  left       join eo_unidade_resp            a3 on (a2.sq_unidade         = a3.sq_unidade and
                                                                    a3.tipo_respons       = 'T'           and
                                                                    a3.fim                is null
                                                                   )
                  left       join eo_unidade_resp            a4 on (a2.sq_unidade         = a4.sq_unidade and
                                                                    a4.tipo_respons       = 'S'           and
                                                                    a4.fim                is null
                                                                   ) 
                inner              join siw_modulo           a1 on (a.sq_modulo           = a1.sq_modulo)
                inner              join siw_solicitacao      b  on (a.sq_menu             = b.sq_menu)
                  inner            join siw_tramite          b1 on (b.sq_siw_tramite      = b1.sq_siw_tramite)
                  left             join pe_objetivo          b2 on (b.sq_peobjetivo       = b2.sq_peobjetivo)
                    left           join pe_plano             b3 on (b2.sq_plano           = b3.sq_plano)
                    left           join pe_objetivo          b4 on (b.sq_peobjetivo       = b4.sq_peobjetivo)
                      left         join pe_plano             b5 on (b4.sq_plano           = b5.sq_plano)
                  inner            join pj_projeto           d  on (b.sq_siw_solicitacao  = d.sq_siw_solicitacao)
                      left         join co_pessoa            d1 on (d.outra_parte         = d1.sq_pessoa)
                      left         join co_cidade            d2 on (d.sq_cidade           = d2.sq_cidade)
                        left       join co_pais              d3 on (d2.sq_pais            = d3.sq_pais)
                        left       join (select sq_siw_solicitacao, min(inicio_previsto) as inicio_etapa, max(fim_previsto) as fim_etapa
                                           from pj_projeto_etapa
                                          group by sq_siw_solicitacao
                                        )                    d4 on (d.sq_siw_solicitacao = d4.sq_siw_solicitacao)
                        left       join (select sq_siw_solicitacao, min(inicio_real) as inicio_etapa_real, max(fim_real) as fim_etapa_real
                                           from pj_projeto_etapa
                                          group by sq_siw_solicitacao
                                        )                    d5 on (d.sq_siw_solicitacao = d5.sq_siw_solicitacao)
                    inner          join eo_unidade           e  on (d.sq_unidade_resp     = e.sq_unidade)
                      left         join eo_unidade_resp      e1 on (e.sq_unidade          = e1.sq_unidade and
                                                                     e1.tipo_respons      = 'T'           and
                                                                     e1.fim               is null
                                                                    )
                      left         join eo_unidade_resp      e2 on (e.sq_unidade          = e2.sq_unidade and
                                                                     e2.tipo_respons      = 'S'           and
                                                                     e2.fim               is null
                                                                    )
                    left           join or_acao              i  on (d.sq_siw_solicitacao  = i.sq_siw_solicitacao)
                      left         join or_acao_ppa          j  on (i.sq_acao_ppa         = j.sq_acao_ppa)
                        left       join or_acao_ppa          k  on (j.sq_acao_ppa_pai     = k.sq_acao_ppa)
                      left         join or_prioridade        l  on (i.sq_orprioridade     = l.sq_orprioridade)
                  inner            join co_pessoa            f  on (b.solicitante         = f.sq_pessoa)
                    left           join sg_autenticacao      f1 on (f.sq_pessoa           = f1.sq_pessoa)
                  inner            join co_cidade            h  on (b.sq_cidade_origem    = h.sq_cidade)
                    inner          join co_uf                h1 on (h.co_uf               = h1.co_uf and
                                                                    h.sq_pais             = h1.sq_pais
                                                                   )
                  left             join ct_cc                g  on (b.sq_cc               = g.sq_cc)
                left               join eo_unidade           c  on (a.sq_unid_executora   = c.sq_unidade)
                left               join (select x.sq_siw_solicitacao sq_acordo, y.codigo_interno cd_acordo,
                                                w.nome_resumido||' - '||z.nome||' ('||to_char(x.inicio,'dd/mm/yyyy')||'-'||to_char(x.fim,'dd/mm/yyyy')||')' as nm_acordo,
                                                v.sigla
                                           from ac_acordo                      x
                                                inner   join   co_pessoa       w on (x.outra_parte        = w.sq_pessoa)
                                                inner   join   siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                                  left  join ct_cc             z on (y.sq_cc              = z.sq_cc)
                                                  inner join siw_menu          v on (y.sq_menu            = v.sq_menu)
                                        )                    m  on (b.sq_solic_pai        = m.sq_acordo)
                left               join siw_solicitacao      n  on (b.sq_solic_pai        = n.sq_siw_solicitacao)
                left               join pe_programa          o  on (b.sq_solic_pai        = o.sq_siw_solicitacao)
                  left             join siw_solicitacao      o1 on (o.sq_siw_solicitacao  = o1.sq_siw_solicitacao)
          where b.sq_siw_solicitacao       = p_chave;
   Elsif substr(p_restricao,1,2) = 'GC' Then
      -- Recupera os acordos que o usuário pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,                  a.arquivo_proced,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao,                   a.justificativa,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec, a2.informal informal_exec,
                a2.vinculada vinc_exec,a2.adm_central adm_exec,
                a3.sq_pessoa tit_exec,a4.sq_pessoa subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,
                case when b.sq_solic_pai is null 
                     then case when b3.sq_peobjetivo is null
                               then '---'
                               else 'Plano: '||b4.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.sq_tipo_acordo,     d.outra_parte,                 d.preposto,
                d.inicio inicio_real, d.fim fim_real,                d.duracao,
                d.valor_inicial,      d.valor_atual,                 b.codigo_interno,
                b.codigo_externo,     d.objeto,                      d.atividades,
                d.produtos,           d.requisitos,                  d.observacao,
                d.dia_vencimento,     d.vincula_projeto,             d.vincula_demanda,
                d.vincula_viagem,     d.aviso_prox_conc,             d.dias_aviso,
                case d.vincula_projeto when 'S' then 'Sim' else 'Não' end nm_vincula_projeto,
                case d.vincula_demanda when 'S' then 'Sim' else 'Não' end nm_vincula_demanda,
                case d.vincula_viagem  when 'S' then 'Sim' else 'Não' end nm_vincula_viagem,
                d.sq_tipo_pessoa,     d.sq_forma_pagamento,          d.sq_agencia,
                d.operacao_conta,     d.numero_conta,                d.sq_pais_estrang,
                d.aba_code,           d.swift_code,                  d.endereco_estrang,
                d.banco_estrang,      d.agencia_estrang,             d.cidade_estrang,
                d.inicio,             d.informacoes,                 d.codigo_deposito,
                d.empenho,            d.processo,                    d.assinatura,
                d.publicacao,         b.titulo,                      d.sq_lcmodalidade,
                d.numero_certame,     d.numero_ata,                  d.tipo_reajuste,
                d.indice_base,        d.sq_eoindicador,              d.limite_variacao,
                d.sq_lcfonte_recurso, d.sq_especificacao_despesa,    d.financeiro_unico,
                d.prestacao_contas,
                case d.prestacao_contas when 'S' then 'Sim' else 'Não' end as nm_prestacao_contas,
                retornaAfericaoIndicador(d.sq_eoindicador,d.indice_base) as vl_indice_base,
                retornaExcedenteContrato(d.sq_siw_solicitacao,b.fim) as limite_usado,
                case d.tipo_reajuste when 0 then 'Não permite' when 1 then 'Com índice' else 'Sem índice' end nm_tipo_reajuste,
                d1.nome nm_tipo_acordo,d1.sigla sg_acordo,           d1.modalidade cd_modalidade,
                d1.prazo_indeterm,    d1.pessoa_fisica,              d1.pessoa_juridica,  
                d2.nome nm_outra_parte, d2.nome_resumido nm_outra_parte_resumido,
                d3.nome nm_outra_parte, d3.nome_resumido nm_outra_parte_resumido,
                d4.nome nm_forma_pagamento, d4.sigla sg_forma_pagamento, d4.ativo st_forma_pagamento,
                d5.codigo cd_agencia, d5.nome nm_agencia,
                d6.sq_banco,          d6.codigo cd_banco,            d6.nome nm_banco,
                d6.exige_operacao,
                d7.nome nm_pais,
                d8.nome nm_lcmodalidade,
                d9.nome nm_eoindicador,
                d10.nome nm_lcfonte_recurso,
                d11.nome nm_espec_despesa,
                d12.aditivo,
                d13.aditivo aditivo_excedente,
                d14.aditivo aditivo_prorrogacao,
                b.fim-d.dias_aviso aviso,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                f.nome nm_cidade,
                m2.titulo nm_projeto,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                coalesce(o1.ativo,'N') as st_sol,
                p.nome_resumido nm_exec,
                i.sq_projeto_etapa,   i1.titulo nm_etapa,
                nvl(m1.qtd_rubrica,0) qtd_rubrica
           from siw_menu                                    a 
                inner        join eo_unidade                a2 on (a.sq_unid_executora        = a2.sq_unidade)
                  left       join eo_unidade_resp           a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                                   a3.tipo_respons            = 'T'           and
                                                                   a3.fim                     is null
                                                                  )
                  left       join eo_unidade_resp           a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                   a4.tipo_respons            = 'S'           and
                                                                   a4.fim                     is null
                                                                  )
                inner             join siw_modulo           a1 on (a.sq_modulo                = a1.sq_modulo)
                inner             join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                   inner          join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                    left          join pe_objetivo          b3 on (b.sq_peobjetivo            = b3.sq_peobjetivo)
                      left        join pe_plano             b4 on (b3.sq_plano                = b4.sq_plano)
                   inner          join ac_acordo            d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                     inner        join ac_tipo_acordo       d1 on (d.sq_tipo_acordo           = d1.sq_tipo_acordo)
                     inner        join co_forma_pagamento   d4 on (d.sq_forma_pagamento       = d4.sq_forma_pagamento)
                     left         join co_pessoa            d2 on (d.outra_parte              = d2.sq_pessoa)
                     left         join co_pessoa            d3 on (d.preposto                 = d3.sq_pessoa)
                     left         join co_agencia           d5 on (d.sq_agencia               = d5.sq_agencia)
                       left       join co_banco             d6 on (d5.sq_banco                = d6.sq_banco)
                     left         join co_pais              d7 on (d.sq_pais_estrang          = d7.sq_pais)
                     left         join lc_modalidade        d8 on (d.sq_lcmodalidade          = d8.sq_lcmodalidade)
                     left         join eo_indicador         d9 on (d.sq_eoindicador           = d9.sq_eoindicador)
                     left         join lc_fonte_recurso    d10 on (d.sq_lcfonte_recurso       = d10.sq_lcfonte_recurso)
                     left         join ct_especificacao_despesa d11 on (d.sq_especificacao_despesa = d11.sq_especificacao_despesa)
                     left         join (select x.sq_siw_solicitacao, count(x.sq_acordo_aditivo) as aditivo
                                          from ac_acordo_aditivo x
                                         where x.prorrogacao = 'S'
                                            or x.revisao     = 'S'
                                            or x.acrescimo   = 'S'
                                            or x.supressao   = 'S'
                                        group by x.sq_siw_solicitacao
                                       )                    d12 on (d.sq_siw_solicitacao       = d12.sq_siw_solicitacao)
                     left         join (select x.sq_siw_solicitacao, count(x.sq_acordo_aditivo) as aditivo
                                          from ac_acordo_aditivo x
                                         where x.acrescimo   = 'S'
                                            or x.supressao   = 'S'
                                        group by x.sq_siw_solicitacao
                                       )                    d13 on (d.sq_siw_solicitacao       = d13.sq_siw_solicitacao)                                       
                     left         join (select x.sq_siw_solicitacao, count(x.sq_acordo_aditivo) as aditivo
                                          from ac_acordo_aditivo x
                                         where x.prorrogacao = 'S'
                                        group by x.sq_siw_solicitacao
                                       )                    d14 on (d.sq_siw_solicitacao       = d14.sq_siw_solicitacao)
                   inner          join eo_unidade           e  on (b.sq_unidade               = e.sq_unidade)
                     left         join eo_unidade_resp      e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                   e1.tipo_respons            = 'T'           and
                                                                   e1.fim                     is null
                                                                  )
                     left         join eo_unidade_resp      e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                   e2.tipo_respons            = 'S'           and
                                                                   e2.fim                     is null
                                                                  )
                   inner          join co_cidade            f  on (b.sq_cidade_origem         = f.sq_cidade)
                   left           join pj_projeto           m  on (b.sq_solic_pai             = m.sq_siw_solicitacao)
                     left         join (select sq_siw_solicitacao, count(sq_projeto_rubrica) qtd_rubrica
                                          from pj_rubrica
                                        group by sq_siw_solicitacao
                                       )                    m1 on (m.sq_siw_solicitacao       = m1.sq_siw_solicitacao)
                     left         join siw_solicitacao      m2 on (m.sq_siw_solicitacao       = m2.sq_siw_solicitacao)
                   left           join ct_cc                n  on (b.sq_cc                    = n.sq_cc)
                   left           join co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                     inner        join sg_autenticacao      o1 on (o.sq_pessoa                = o1.sq_pessoa)
                       inner      join eo_unidade           o2 on (o1.sq_unidade              = o2.sq_unidade)
                   left           join co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                left              join eo_unidade           c  on (a.sq_unid_executora        = c.sq_unidade)
                left         join pj_etapa_contrato         i  on (b.sq_siw_solicitacao  = i.sq_siw_solicitacao)
                  left       join pj_projeto_etapa          i1 on (i.sq_projeto_etapa    = i1.sq_projeto_etapa)                
                inner             join (select sq_siw_solicitacao, max(sq_siw_solic_log) chave 
                                          from siw_solic_log
                                        group by sq_siw_solicitacao
                                       )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                  left            join gd_demanda_log       k  on (j.chave                    = k.sq_siw_solic_log)
                    left          join sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
          where b.sq_siw_solicitacao       = p_chave;
   Elsif substr(p_restricao,1,2) = 'FN' Then
      -- Recupera os acordos que o usuário pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,                  a.arquivo_proced,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao,                   a.justificativa,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec, a2.informal informal_exec,
                a2.vinculada vinc_exec,a2.adm_central adm_exec,
                a3.sq_pessoa tit_exec,a4.sq_pessoa subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.opiniao,            b.sq_solic_pai,
                b.sq_unidade,         b.sq_cidade_origem,            b.palavra_chave,
                b.valor,
                case when b.sq_solic_pai is null 
                     then case when b3.sq_peobjetivo is null
                               then '---'
                               else 'Plano: '||b4.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.pessoa,             b.codigo_interno,              d.sq_acordo_parcela,
                d.sq_forma_pagamento, d.sq_tipo_lancamento,          d.sq_tipo_pessoa,
                d.emissao,            d.vencimento,                  d.quitacao,
                b.codigo_externo,     d.observacao,                  
                d.aviso_prox_conc,
                d.dias_aviso,         d.sq_forma_pagamento,          d.sq_agencia,
                d.operacao_conta,     d.numero_conta,                d.sq_pais_estrang,
                d.aba_code,           d.swift_code,                  d.endereco_estrang,
                d.banco_estrang,      d.agencia_estrang,             d.cidade_estrang,
                d.informacoes,        d.codigo_deposito,
                d.valor_imposto,      d.valor_retencao,              d.valor_liquido,
                d.tipo tipo_rubrica,  d.processo,
                case d.tipo when 1 then 'Dotação incial' when 2 then 'Transferência entre rubricas' when 3 then 'Atualização de aplicação' when 4 then 'Entradas' when 5 then 'Saídas' end nm_tipo_rubrica,
                d1.receita,           d1.despesa,                    d1.nome nm_tipo_lancamento,
                d2.nome nm_pessoa, d2.nome_resumido nm_pessoa_resumido,
                coalesce(d3.valor,0) valor_doc,
                d4.nome nm_forma_pagamento, d4.sigla sg_forma_pagamento, d4.ativo st_forma_pagamento,
                d5.codigo cd_agencia, d5.nome nm_agencia,
                d6.sq_banco,          d6.codigo cd_banco,            d6.nome nm_banco,
                d6.exige_operacao,
                d7.nome nm_pais,
                d8.nome nm_tipo_pessoa,
                coalesce(d9.valor,0) valor_nota,
                coalesce(da.qtd,0) qtd_nota,
                coalesce(db.existe,0) as notas_parcela,
                b.fim-d.dias_aviso aviso,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                f.nome nm_cidade,
                m1.codigo_interno cd_acordo,
                coalesce(m4.existe,0) as notas_acordo,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                p.nome_resumido nm_exec,
                case nvl(m2.sq_siw_solicitacao,0) when 0 then q2.titulo             else m5.titulo end nm_projeto,
                case nvl(m2.sq_siw_solicitacao,0) when 0 then q.sq_siw_solicitacao else m2.sq_siw_solicitacao end sq_projeto,
                case nvl(m3.sq_siw_solicitacao,0) when 0 then q1.qtd_rubrica       else m3.qtd_rubrica        end qtd_rubrica
           from siw_menu                                    a 
                inner        join eo_unidade                a2 on (a.sq_unid_executora        = a2.sq_unidade)
                  left       join eo_unidade_resp           a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                                   a3.tipo_respons            = 'T'           and
                                                                   a3.fim                     is null
                                                                  )
                  left       join eo_unidade_resp           a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                   a4.tipo_respons            = 'S'           and
                                                                   a4.fim                     is null
                                                                  )
                inner             join siw_modulo           a1 on (a.sq_modulo                = a1.sq_modulo)
                inner             join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                   inner          join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                    left          join pe_objetivo          b3 on (b.sq_peobjetivo            = b3.sq_peobjetivo)
                      left        join pe_plano             b4 on (b3.sq_plano                = b4.sq_plano)
                   inner          join fn_lancamento        d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                     inner        join fn_tipo_lancamento   d1 on (d.sq_tipo_lancamento       = d1.sq_tipo_lancamento)
                     inner        join co_forma_pagamento   d4 on (d.sq_forma_pagamento       = d4.sq_forma_pagamento)
                     inner        join co_tipo_pessoa       d8 on (d.sq_tipo_pessoa           = d8.sq_tipo_pessoa)
                     left         join (select x.sq_siw_solicitacao, sum(x.valor) valor
                                          from fn_lancamento_doc x
                                         where x.sq_acordo_nota is null
                                        group by x.sq_siw_solicitacao
                                       )                    d3 on (d.sq_siw_solicitacao       = d3.sq_siw_solicitacao)
                     left         join co_pessoa            d2 on (d.pessoa                   = d2.sq_pessoa)
                     left         join co_agencia           d5 on (d.sq_agencia               = d5.sq_agencia)
                       left       join co_banco             d6 on (d5.sq_banco                = d6.sq_banco)
                     left         join co_pais              d7 on (d.sq_pais_estrang          = d7.sq_pais)
                     left         join (select sq_siw_solicitacao, sum(valor) valor
                                          from fn_lancamento_doc x
                                         where x.sq_acordo_nota is not null
                                        group by sq_siw_solicitacao
                                       )                    d9 on (d.sq_siw_solicitacao       = d9.sq_siw_solicitacao)
                     left         join (select sq_siw_solicitacao, count(x.sq_lancamento_doc) as qtd
                                          from fn_lancamento_doc x
                                         where x.sq_acordo_nota is not null
                                        group by sq_siw_solicitacao
                                       )                    da on (d.sq_siw_solicitacao       = da.sq_siw_solicitacao)
                     left outer   join (select x.sq_acordo_parcela, count(*) as existe
                                          from ac_parcela_nota x
                                        group by x.sq_acordo_parcela
                                       )                    db on (d.sq_acordo_parcela        = db.sq_acordo_parcela)
                   inner          join eo_unidade           e  on (b.sq_unidade               = e.sq_unidade)
                     left         join eo_unidade_resp      e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                   e1.tipo_respons            = 'T'           and
                                                                   e1.fim                     is null
                                                                  )
                     left         join eo_unidade_resp      e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                   e2.tipo_respons            = 'S'           and
                                                                   e2.fim                     is null
                                                                  )
                   inner          join co_cidade            f  on (b.sq_cidade_origem         = f.sq_cidade)
                   left           join ac_acordo            m  on (b.sq_solic_pai             = m.sq_siw_solicitacao)
                     left         join siw_solicitacao      m1 on (m.sq_siw_solicitacao       = m1.sq_siw_solicitacao)
                       left       join pj_projeto           m2 on (m1.sq_solic_pai            = m2.sq_siw_solicitacao)
                         left     join siw_solicitacao      m5 on (m2.sq_siw_solicitacao      = m5.sq_siw_solicitacao)
                         left     join (select sq_siw_solicitacao, count(sq_projeto_rubrica) qtd_rubrica
                                          from pj_rubrica
                                        group by sq_siw_solicitacao
                                       )                    m3 on (m2.sq_siw_solicitacao      = m3.sq_siw_solicitacao)
                     left outer   join (select x.sq_siw_solicitacao, count(*) as existe
                                          from ac_acordo_nota x
                                        group by x.sq_siw_solicitacao
                                       )                    m4 on (m.sq_siw_solicitacao       = m4.sq_siw_solicitacao)
                   left           join pj_projeto           q  on (b.sq_solic_pai             = q.sq_siw_solicitacao)
                     left         join siw_solicitacao      q2 on (q.sq_siw_solicitacao       = q2.sq_siw_solicitacao)
                     left         join (select sq_siw_solicitacao, count(sq_projeto_rubrica) qtd_rubrica
                                          from pj_rubrica
                                        group by sq_siw_solicitacao
                                       )                    q1 on (q.sq_siw_solicitacao       = q1.sq_siw_solicitacao)
                   left           join ct_cc                n  on (b.sq_cc                    = n.sq_cc)
                   left           join co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                     inner        join sg_autenticacao      o1 on (o.sq_pessoa                = o1.sq_pessoa)
                       inner      join eo_unidade           o2 on (o1.sq_unidade              = o2.sq_unidade)
                   left           join co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                left              join eo_unidade           c  on (a.sq_unid_executora        = c.sq_unidade)
                inner             join (select sq_siw_solicitacao, max(sq_siw_solic_log) chave 
                                          from siw_solic_log
                                        group by sq_siw_solicitacao
                                       )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                  left            join gd_demanda_log       k  on (j.chave                    = k.sq_siw_solic_log)
                    left          join sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
          where b.sq_siw_solicitacao       = p_chave;
   Elsif substr(p_restricao,1,2) = 'PD' Then
      -- Recupera as passagens que o usuário pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,                  a.arquivo_proced,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao,                   a.justificativa justif_solic,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec, a2.informal informal_exec,
                a2.vinculada vinc_exec,a2.adm_central adm_exec,
                a3.sq_pessoa tit_exec, a31.nome nm_tit_exec,
                a4.sq_pessoa subst_exec,                
                a3.sq_pessoa tit_exec,a4.sq_pessoa subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,
                case when b.sq_solic_pai is null 
                     then case when b3.sq_peobjetivo is null
                               then '---'
                               else 'Plano: '||b4.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.sq_unidade_resp,    d.assunto,                     d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                case d.prioridade when 0 then 'Alta' when 1 then 'Média' else 'Normal' end nm_prioridade,
                d.ordem,
                d1.sq_pessoa sq_prop, d1.tipo tp_missao,             d11.codigo_interno,
                case d1.tipo when 'I' then 'Inicial' when 'P' then 'Prorrogação' when 'C' then 'Complementação' end  nm_tipo_missao,
                d1.reserva,           d1.pta,                        d1.justificativa_dia_util,
                d1.emissao_bilhete,   d1.pagamento_diaria,           d1.pagamento_bilhete,
                d1.boletim_numero,    d1.boletim_data,               d1.valor_alimentacao,
                d1.valor_transporte,  d1.desconto_alimentacao,       d1.desconto_transporte,
                d1.valor_adicional,   d1.tipo tipo_missao,
                d1.sq_pais_estrang,   d1.aba_code,                   d1.swift_code,
                d1.endereco_estrang,  d1.banco_estrang,              d1.agencia_estrang,
                d1.cidade_estrang,    d1.informacoes,                d1.codigo_deposito,
                d1.numero_conta,      d1.operacao_conta,
                d1.valor_passagem,
                d2.nome nm_prop,      d2.nome_resumido nm_prop_res,
                coalesce(d21.ativo,'N') st_prop,
                d3.sq_tipo_vinculo,   d3.nome nm_tipo_vinculo,
                d4.sexo,              d4.cpf,
                d6.sq_agencia,        d6.codigo cd_agencia,          d6.nome nm_agencia,
                d7.sq_banco,          d7.codigo cd_banco,            d7.nome nm_banco,
                d7.exige_operacao,
                d8.sq_posto_trabalho, d8.sq_posto_trabalho,          d8.sq_modalidade_contrato,
                d8.matricula,
                b.fim-d.dias_aviso aviso,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,       e12.nome nm_titular,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                g.sq_cc,              g.nome nm_cc,                  g.sigla sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                coalesce(o1.ativo,'N') st_sol,
                p.nome_resumido nm_exec
           from siw_menu                                               a
                  inner                join eo_unidade                 a2 on (a.sq_unid_executora        = a2.sq_unidade)
                    left               join eo_unidade_resp            a3 on (a2.sq_unidade              = a3.sq_unidade   and
                                                                              a3.tipo_respons            = 'T'             and
                                                                              a3.fim                     is null)
                      left             join co_pessoa                 a31 on (a3.sq_pessoa               = a31.sq_pessoa)
                    left               join eo_unidade_resp            a4 on (a2.sq_unidade              = a4.sq_unidade   and
                                                                              a4.tipo_respons            = 'S'             and
                                                                              a4.fim                     is null)
                  inner                join siw_modulo                 a1 on (a.sq_modulo                = a1.sq_modulo)
                  inner                join siw_solicitacao            b  on (a.sq_menu                  = b.sq_menu)
                    inner              join siw_tramite                b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                    left               join pe_objetivo                b3 on (b.sq_peobjetivo            = b3.sq_peobjetivo)
                      left             join pe_plano                   b4 on (b3.sq_plano                = b4.sq_plano)
                    inner              join gd_demanda                 d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                      inner            join pd_missao                  d1 on (d.sq_siw_solicitacao       = d1.sq_siw_solicitacao)
                        inner          join siw_solicitacao           d11 on (d1.sq_siw_solicitacao      = d11.sq_siw_solicitacao)
                        inner          join co_pessoa                  d2 on (d1.sq_pessoa               = d2.sq_pessoa)
                          left         join sg_autenticacao           d21 on (d2.sq_pessoa               = d21.sq_pessoa)
                          inner        join co_tipo_vinculo            d3 on (d2.sq_tipo_vinculo         = d3.sq_tipo_vinculo)
                          inner        join co_pessoa_fisica           d4 on (d2.sq_pessoa               = d4.sq_pessoa)
                            left       join gp_contrato_colaborador    d8 on (d4.cliente                 = d8.cliente      and
                                                                              d4.sq_pessoa               = d8.sq_pessoa    and
                                                                              d8.fim                     is null)
                        left       join co_agencia                     d6 on (d1.sq_agencia              = d6.sq_agencia)
                          left       join co_banco                     d7 on (d6.sq_banco                = d7.sq_banco)
                      inner            join eo_unidade                 e  on (d.sq_unidade_resp          = e.sq_unidade)
                        left           join eo_unidade_resp            e1 on (e.sq_unidade               = e1.sq_unidade   and
                                                                              e1.tipo_respons            = 'T'             and
                                                                              e1.fim                     is null)
                          left         join co_pessoa                 e12 on (e1.sq_pessoa               = e12.sq_pessoa)
                        left           join eo_unidade_resp            e2 on (e.sq_unidade               = e2.sq_unidade   and
                                                                              e2.tipo_respons            = 'S'             and
                                                                              e2.fim                     is null)
                    inner              join co_cidade                  f  on (b.sq_cidade_origem         = f.sq_cidade)
                    left               join ct_cc                      g  on (b.sq_cc                    = g.sq_cc)
                    left               join co_pessoa                  o  on (b.solicitante              = o.sq_pessoa)
                      left             join sg_autenticacao            o1 on (o.sq_pessoa                = o1.sq_pessoa)
                        left           join eo_unidade                 o2 on (o1.sq_unidade              = o2.sq_unidade)
                    left               join co_pessoa                  p  on (b.executor                 = p.sq_pessoa)
                  left                 join eo_unidade                 c  on (a.sq_unid_executora        = c.sq_unidade)
                    inner              join (select sq_siw_solicitacao, max(sq_siw_solic_log) chave 
                                               from siw_solic_log
                                             group by sq_siw_solicitacao
                                            )                          j on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                      left             join gd_demanda_log             k on (j.chave                    = k.sq_siw_solic_log)
                        left           join sg_autenticacao            l on (k.destinatario             = l.sq_pessoa)
          where b.sq_siw_solicitacao = p_chave;          
   Elsif substr(p_restricao,1,2) = 'SR' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,                  a.arquivo_proced,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao,                   a.justificativa,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec, a2.informal informal_exec,
                a2.vinculada vinc_exec,a2.adm_central adm_exec,
                a3.sq_pessoa tit_exec,a4.sq_pessoa subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,      b.observacao,                  b.recebedor,
                b.motivo_insatisfacao,
                to_char(b.inclusao,'dd/mm/yyyy, hh24:mi:ss')  phpdt_inclusao,
                to_char(b.inicio,'dd/mm/yyyy, hh24:mi:ss')    phpdt_inicio,
                to_char(b.fim,'dd/mm/yyyy, hh24:mi:ss')       phpdt_fim,
                to_char(b.conclusao,'dd/mm/yyyy, hh24:mi:ss') phpdt_conclusao,
                case when b.sq_solic_pai is null 
                     then case when b4.sq_peobjetivo is null
                               then '---'
                               else 'Plano: '||b5.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                b2.nome  nm_opiniao,
                b3.sq_veiculo,        b3.qtd_pessoas,                b3.carga,
                b3.hodometro_saida,   b3.hodometro_chegada,          b3.destino,
                b3.parcial,           b3.procedimento,
                to_char(b3.horario_saida,'dd/mm/yyyy, hh24:mi:ss')   phpdt_horario_saida,
                to_char(b3.horario_chegada,'dd/mm/yyyy, hh24:mi:ss') phpdt_horario_chegada,
                case b3.procedimento when 0 then 'Não Informado' 
                                     when 1 then 'Somente levar' 
                                     when 2 then 'Levar e aguardar' 
                                     when 3 then 'Somente buscar' 
                                     when 4 then 'Abastecimento (uso exclusivo do setor de tráfego)'
                end as nm_procedimento,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                e.sq_tipo_unidade,    e.nome nm_unidade_solic,        e.informal informal_solic,
                e.vinculada vinc_solic,e.adm_central adm_solic,       e.sigla as sg_unidade_solic,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.nome_resumido nm_sol,
                coalesce(f1.ativo,'N') as st_sol,
                g.sq_cc,              g.nome cc_nome,                g.sigla cc_sigla,
                h.sq_pais,            h.sq_regiao,                   h.co_uf,
                h.nome nm_cidade,
                i.nome_resumido nm_exec,
                j.nome_resumido nm_recebedor,
                case when l.placa is null 
                     then null 
                     else substr(l.placa,1,3)||'-'||substr(l.placa,4) ||' - '||l.marca||' '||l.modelo 
                end as nm_placa
           from siw_menu                                    a
                inner        join eo_unidade                a2 on (a.sq_unid_executora   = a2.sq_unidade)
                  left       join eo_unidade_resp           a3 on (a2.sq_unidade         = a3.sq_unidade and
                                                                   a3.tipo_respons       = 'T'           and
                                                                   a3.fim                is null
                                                                  )
                  left       join eo_unidade_resp           a4 on (a2.sq_unidade         = a4.sq_unidade and
                                                                   a4.tipo_respons       = 'S'           and
                                                                   a4.fim                is null
                                                                  ) 
                inner        join siw_modulo                a1 on (a.sq_modulo           = a1.sq_modulo)
                inner        join siw_solicitacao           b  on (a.sq_menu             = b.sq_menu)
                  inner      join siw_tramite               b1 on (b.sq_siw_tramite      = b1.sq_siw_tramite)
                  left       join siw_opiniao               b2 on (b.opiniao             = b2.sq_siw_opiniao)
                  left       join sr_solicitacao_transporte b3 on (b.sq_siw_solicitacao  = b3.sq_siw_solicitacao)
                    left     join sr_veiculo                l  on (b3.sq_veiculo         = l.sq_veiculo)
                  left       join pe_objetivo               b4 on (b.sq_peobjetivo       = b4.sq_peobjetivo)
                    left     join pe_plano                  b5 on (b4.sq_plano           = b5.sq_plano)
                  inner      join eo_unidade                e  on (b.sq_unidade          = e.sq_unidade)
                    left     join eo_unidade_resp           e1 on (e.sq_unidade          = e1.sq_unidade and
                                                                   e1.tipo_respons       = 'T'           and
                                                                   e1.fim                is null
                                                                  )
                    left     join eo_unidade_resp           e2 on (e.sq_unidade          = e2.sq_unidade and
                                                                   e2.tipo_respons       = 'S'           and
                                                                   e2.fim                is null
                                                                  )
                  inner      join co_pessoa                 f  on (b.solicitante         = f.sq_pessoa)
                    left     join sg_autenticacao           f1 on (f.sq_pessoa           = f1.sq_pessoa)
                    left     join co_pessoa                 i  on (b.executor            = i.sq_pessoa)
                    left     join co_pessoa                 j  on (b.recebedor           = j.sq_pessoa)                  
                  inner      join co_cidade                 h  on (b.sq_cidade_origem    = h.sq_cidade)
                  left       join ct_cc                     g  on (b.sq_cc               = g.sq_cc)
                left         join eo_unidade                c  on (a.sq_unid_executora   = c.sq_unidade)
          where b.sq_siw_solicitacao       = p_chave;
   Elsif substr(p_restricao,1,4) = 'PEPR' Then
      -- Recupera os programas que o usuário pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,                  a.arquivo_proced,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao,                   a.justificativa,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec, a2.informal informal_exec,
                a2.vinculada vinc_exec,a2.adm_central adm_exec,
                a3.sq_pessoa tit_exec,a4.sq_pessoa subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,      b.observacao,
                case when b.sq_solic_pai is null 
                     then case when b2.sq_peobjetivo is null
                               then '---'
                               else 'Plano: '||b3.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                b2.sq_peobjetivo,     b2.sq_plano,                   b2.nome nm_objetivo, 
                b2.sigla sg_objetivo, b2.descricao ds_objetivo,      b2.ativo st_objetivo,
                b3.sq_plano_pai,      b3.titulo nm_plano,            b3.missao, 
                b3.valores,           b3.visao_presente,             b3.visao_futuro, 
                b3.inicio inicio_plano,b3.fim vim_plano,             b3.ativo st_plano,
                b4.sq_unidade sq_unidade_adm, b4.nome nm_unidade_adm, b4.sigla sg_unidade_adm,
                b5.sq_pessoa tit_adm, b6.sq_pessoa subst_adm,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.sq_siw_solicitacao sq_programa,
                d.sq_pehorizonte,     d.sq_penatureza,               b.codigo_interno cd_programa,
                b.titulo,             d.publico_alvo,                d.estrategia, 
                d.ln_programa,        d.situacao_atual,              d.exequivel, 
                d.justificativa_inexequivel,                         d.outras_medidas, 
                d.inicio_real,        d.fim_real,                    d.custo_real, 
                d.nota_conclusao,     d.aviso_prox_conc,             d.dias_aviso,
                d1.nome nm_horizonte, d1.ativo st_horizonte, 
                d7.nome nm_natureza, d7.ativo st_natureza,
                b.fim-d.dias_aviso aviso,
                e.sq_unidade sq_unidade_resp,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                g.sq_cc,              g.nome nm_cc,                  g.sigla sg_cc,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                coalesce(o1.ativo,'N') st_sol,
                p.nome_resumido nm_exec
           from siw_menu                                       a 
                   inner        join eo_unidade                a2 on (a.sq_unid_executora        = a2.sq_unidade)
                     left       join eo_unidade_resp           a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                                      a3.tipo_respons            = 'T'           and
                                                                      a3.fim                     is null
                                                                     )
                     left       join eo_unidade_resp           a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                      a4.tipo_respons            = 'S'           and
                                                                      a4.fim                     is null
                                                                     )
                   inner             join siw_modulo           a1 on (a.sq_modulo                = a1.sq_modulo)
                   inner             join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                      inner          join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                      inner          join pe_objetivo          b2 on (b.sq_peobjetivo            = b2.sq_peobjetivo)
                        inner        join pe_plano             b3 on (b2.sq_plano                = b3.sq_plano)
                      inner          join eo_unidade           b4 on (b.sq_unidade               = b4.sq_unidade)
                        left         join eo_unidade_resp      b5 on (b4.sq_unidade              = b5.sq_unidade and
                                                                      b5.tipo_respons            = 'T'           and
                                                                      b5.fim                     is null
                                                                     )
                        left         join eo_unidade_resp      b6 on (b4.sq_unidade              = b6.sq_unidade and
                                                                      b6.tipo_respons            = 'S'           and
                                                                      b6.fim                     is null
                                                                     )
                      inner          join pe_programa          d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                        inner        join pe_horizonte         d1 on (d.sq_pehorizonte           = d1.sq_pehorizonte)
                        inner        join pe_natureza          d7 on (d.sq_penatureza            = d7.sq_penatureza)
                        inner        join eo_unidade           e  on (d.sq_unidade_resp          = e.sq_unidade)
                          left       join eo_unidade_resp      e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                      e1.tipo_respons            = 'T'           and
                                                                      e1.fim                     is null
                                                                     )
                          left       join eo_unidade_resp      e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                      e2.tipo_respons            = 'S'           and
                                                                      e2.fim                     is null
                                                                     )
                      inner          join co_cidade            f  on (b.sq_cidade_origem         = f.sq_cidade)
                      left           join ct_cc                g  on (b.sq_cc                    = g.sq_cc)
                      left           join ct_cc                n  on (b.sq_cc                    = n.sq_cc)
                      left           join co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                        left         join sg_autenticacao      o1 on (o.sq_pessoa                = o1.sq_pessoa)
                          left       join eo_unidade           o2 on (o1.sq_unidade              = o2.sq_unidade)
                      left           join co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                   left              join eo_unidade           c  on (a.sq_unid_executora        = c.sq_unidade)
                   inner             join (select sq_siw_solicitacao, max(sq_siw_solic_log) chave 
                                             from siw_solic_log
                                           group by sq_siw_solicitacao
                                          )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                     left            join pe_programa_log      k  on (j.chave                    = k.sq_siw_solic_log)
                       left          join sg_autenticacao      l  on (j.k.destinatario           = l.sq_pessoa)
          where b.sq_siw_solicitacao       = p_chave;
   Elsif substr(p_restricao,1,3) = 'PAD' Then
      -- Recupera os programas que o usuário pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,                  a.arquivo_proced,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao,                   a.justificativa,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec, a2.informal informal_exec,
                a2.vinculada vinc_exec,a2.adm_central adm_exec,
                a3.sq_pessoa tit_exec,a4.sq_pessoa subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,
                case when b.sq_solic_pai is null 
                     then case when b6.sq_peobjetivo is null
                               then '---'
                               else 'Plano: '||b7.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                b3.nome as nm_unid_origem, b3.sigla sg_unid_origem,
                case b5.padrao when 'S' then b4.nome||'-'||b4.co_uf else b4.nome||' ('||b5.nome||')' end as nm_cidade,
                d.numero_original,    d.numero_documento,            d.ano,
                d.prefixo,            d.digito,                      d.interno,
                d.prefixo||'.'||substr(1000000+d.numero_documento,2,6)||'/'||d.ano||'-'||substr(100+d.digito,2,2) as protocolo,
                d.sq_especie_documento, d.sq_natureza_documento,     d.unidade_autuacao,
                d.data_autuacao,      d.pessoa_origem,               d.processo,
                d.circular,           d.copias,                      d.volumes,
                d.data_recebimento,   d.unidade_int_posse,           d.pessoa_ext_posse,
                case d.processo when 'S' then 'Processo' else 'Documento' end as nm_tipo,
                case when d.pessoa_origem is null then b3.nome else d2.nome end as nm_origem,
                coalesce(d1.nome,'Irrestrito') as nm_natureza,       d1.sigla sg_natureza,
                d1.descricao ds_natureza,                            d1.ativo st_natureza,
                d2.nome_resumido as nm_res_pessoa_origem,            d2.nome as nm_pessoa_origem,
                d3.sq_tipo_pessoa,                                   d3.nome as nm_tipo_pessoa,
                d4.sq_assunto,
                d5.codigo as cd_assunto,                             d5.descricao as ds_assunto,
                d5.detalhamento dst_assunto,                         d5.observacao as ob_assunto,
                d7.nome nm_especie,   d7.sigla sg_natureza,          d7.ativo st_natureza,
                case d6.sigla when 'ANOS' then d5.fase_corrente_anos||' '||d6.descricao when 'NAPL' then '---' else d6.descricao end as guarda_corrente,
                case d8.sigla when 'ANOS' then d5.fase_intermed_anos||' '||d8.descricao when 'NAPL' then '---' else d8.descricao end as guarda_intermed,
                case d9.sigla when 'ANOS' then d5.fase_final_anos   ||' '||d9.descricao when 'NAPL' then '---' else d9.descricao end as guarda_final,
                da.descricao as destinacao_final,
                db.codigo as cd_assunto_pai, db.descricao as ds_assunto_pai,
                dc.codigo as cd_assunto_avo, dc.descricao as ds_assunto_avo,
                dd.codigo as cd_assunto_bis, dd.descricao as ds_assunto_bis,
                df.sq_pessoa as pessoa_interes, df.nome as nm_pessoa_interes,
                b.fim-k.dias_aviso aviso,
                e.sq_unidade sq_unidade_resp,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                g.sq_cc,              g.nome nm_cc,                  g.sigla sg_cc,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                coalesce(o1.ativo,'N') st_sol,
                p.nome_resumido nm_exec,
                q.nome as nm_unidade_posse,                          q.sigla as sg_unidade_posse,
                q1.sq_pessoa titular,                                q2.sq_pessoa substituto,
                r.nome as nm_pessoa_posse,                           r.nome_resumido as nm_res_pessoa_posse
           from siw_menu                                           a 
                   inner             join eo_unidade               a2 on (a.sq_unid_executora        = a2.sq_unidade)
                     left            join eo_unidade_resp          a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                                          a3.tipo_respons            = 'T'           and
                                                                          a3.fim                     is null
                                                                         )
                     left            join eo_unidade_resp          a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                          a4.tipo_respons            = 'S'           and
                                                                          a4.fim                     is null
                                                                         )
                   inner             join siw_modulo               a1 on (a.sq_modulo                = a1.sq_modulo)
                   inner             join siw_solicitacao          b  on (a.sq_menu                  = b.sq_menu)
                      inner          join siw_tramite              b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                      inner          join eo_unidade               b3 on (b.sq_unidade               = b3.sq_unidade)
                      inner          join co_cidade                b4 on (b.sq_cidade_origem         = b4.sq_cidade)
                        inner        join co_pais                  b5 on (b4.sq_pais                 = b5.sq_pais)
                      left           join pe_objetivo              b6 on (b.sq_peobjetivo            = b6.sq_peobjetivo)
                        left         join pe_plano                 b7 on (b6.sq_plano                = b7.sq_plano)
                      inner          join pa_documento             d  on (b.sq_siw_solicitacao        = d.sq_siw_solicitacao)
                        left         join pa_natureza_documento    d1 on (d.sq_natureza_documento    = d1.sq_natureza_documento)
                        left         join co_pessoa                d2 on (d.pessoa_origem            = d2.sq_pessoa)
                          left       join co_tipo_pessoa           d3 on (d2.sq_tipo_pessoa          = d3.sq_tipo_pessoa)
                        inner        join pa_documento_assunto     d4 on (d.sq_siw_solicitacao       = d4.sq_siw_solicitacao and
                                                                          d4.principal               = 'S'
                                                                         )
                          inner      join pa_assunto               d5 on (d4.sq_assunto              = d5.sq_assunto)
                            inner    join pa_tipo_guarda           d6 on (d5.fase_corrente_guarda    = d6.sq_tipo_guarda)
                            inner    join pa_tipo_guarda           d8 on (d5.fase_intermed_guarda    = d8.sq_tipo_guarda)
                            inner    join pa_tipo_guarda           d9 on (d5.fase_final_guarda       = d9.sq_tipo_guarda)
                            inner    join pa_tipo_guarda           da on (d5.destinacao_final        = da.sq_tipo_guarda)
                            left     join pa_assunto               db on (d5.sq_assunto_pai          = db.sq_assunto)
                              left   join pa_assunto               dc on (db.sq_assunto_pai          = dc.sq_assunto)
                                left join pa_assunto               dd on (dc.sq_assunto_pai          = dd.sq_assunto)
                        left         join pa_documento_interessado de on (d.sq_siw_solicitacao       = de.sq_siw_solicitacao and
                                                                          de.principal               = 'S'
                                                                         )
                          left       join co_pessoa                df on (de.sq_pessoa               = df.sq_pessoa)
                        inner        join pa_especie_documento     d7 on (d.sq_especie_documento     = d7.sq_especie_documento)
                        inner        join eo_unidade               e  on (d.unidade_autuacao         = e.sq_unidade)
                          left       join eo_unidade_resp          e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                          e1.tipo_respons            = 'T'           and
                                                                          e1.fim                     is null
                                                                         )
                          left       join eo_unidade_resp          e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                          e2.tipo_respons            = 'S'           and
                                                                          e2.fim                     is null
                                                                         )
                        left         join eo_unidade               q  on (d.unidade_int_posse        = q.sq_unidade)
                          left       join eo_unidade_resp          q1 on (q.sq_unidade               = q1.sq_unidade and
                                                                          q1.tipo_respons            = 'T'           and
                                                                          q1.fim                     is null
                                                                         )
                          left       join eo_unidade_resp          q2 on (q.sq_unidade               = q2.sq_unidade and
                                                                          q2.tipo_respons            = 'S'           and
                                                                          q2.fim                     is null
                                                                         )
                        left         join co_pessoa                r  on (d.pessoa_ext_posse         = r.sq_pessoa)
                      left           join ct_cc                    g  on (b.sq_cc                    = g.sq_cc)
                      inner          join co_cidade                f  on (b.sq_cidade_origem         = f.sq_cidade)
                      left           join ct_cc                    n  on (b.sq_cc                    = n.sq_cc)
                      left           join co_pessoa                o  on (b.solicitante              = o.sq_pessoa)
                        left         join sg_autenticacao          o1 on (o.sq_pessoa                = o1.sq_pessoa)
                          left       join eo_unidade               o2 on (o1.sq_unidade              = o2.sq_unidade)
                      left           join co_pessoa                p  on (b.executor                 = p.sq_pessoa)
                   inner             join (select sq_siw_solicitacao, max(sq_siw_solic_log) chave 
                                             from siw_solic_log
                                           group by sq_siw_solicitacao
                                          )                        j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                     left            join pa_documento_log         k  on (j.chave                    = k.sq_siw_solic_log)
                       left          join sg_autenticacao          l  on (k.recebedor                = l.sq_pessoa)
          where b.sq_siw_solicitacao = p_chave;
   End If;
end SP_GetSolicData;
/
