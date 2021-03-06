﻿create or replace FUNCTION SP_GetSolicData
   (p_chave     numeric,
    p_restricao varchar,
    p_result    REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
   w_menu siw_menu.sq_menu%type := 0;
BEGIN
   If p_chave is not null Then
      -- Recupera o menu ao qual a solicitação está ligada   
      select sq_menu into w_menu from siw_solicitacao where sq_siw_solicitacao = p_chave;
  End If;
   
   If p_restricao is null Then
      open p_result for select dados_solic(p_chave) as dados_solic;
   Elsif substr(p_restricao,1,2) = 'GD' or p_restricao = 'ORPGERAL' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao as ds_menu,        a.justificativa as just_menu,
                a1.nome as nm_modulo, a1.sigla as sg_modulo,         a1.objetivo_geral,
                a2.sq_tipo_unidade as tp_exec,                       a2.nome as nm_unidade_exec, 
                a2.informal as informal_exec,                        a2.vinculada as vinc_exec,
                a2.adm_central as adm_exec,
                a3.sq_pessoa as tit_exec,                            a4.sq_pessoa as subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,                     b.sq_solic_pai,
                b.sq_unidade,         b.sq_cidade_origem,            b.palavra_chave,
                cast(b.fim as date)-cast(d.dias_aviso as integer) as aviso,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then '---'
                               else 'Plano: '||b3.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.nome as nm_tramite,b1.ordem as or_tramite,
                b1.sigla as sg_tramite, b1.ativo,                    b1.envia_mail,
                b5.nome as nm_unidade,
                d.sq_unidade_resp,    d.assunto,                     d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                d.ordem,              d.sq_demanda_pai,              d.sq_demanda_tipo,
                d.recebimento,        d.limite_conclusao,            d.responsavel,
                d1.reuniao,           d1.nome as nm_demanda_tipo,
                d2.nome_resumido as nm_resp,
                e.sq_tipo_unidade,    e.nome as nm_unidade_resp,     e.informal as informal_resp,
                e.vinculada as vinc_resp,                            e.adm_central as adm_resp,
                e1.sq_pessoa as titular,                             e2.sq_pessoa as substituto,
                f.nome_resumido as nm_sol,
                coalesce(f1.ativo,'N') as st_sol,
                g.sq_cc,              g.nome as cc_nome,             g.sigla as cc_sigla,
                h.sq_pais,            h.sq_regiao,                   h.co_uf,
                h.nome as nm_cidade,
                i.sq_projeto_etapa,   j.titulo as nm_etapa,          k1.titulo as nm_projeto,
                montaordem(j.sq_projeto_etapa,null) as cd_ordem,
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
                  left       join pe_plano                  b3 on (b.sq_plano            = b3.sq_plano)
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
                left         join pj_etapa_demanda          i  on (b.sq_siw_solicitacao  = i.sq_siw_solicitacao)
                  left       join pj_projeto_etapa          j  on (i.sq_projeto_etapa    = j.sq_projeto_etapa)
                left         join pj_projeto                k  on (b.sq_solic_pai        = k.sq_siw_solicitacao)
                  left       join siw_solicitacao           k1 on (k.sq_siw_solicitacao  = k1.sq_siw_solicitacao)
          where b.sq_siw_solicitacao = p_chave;
   Elsif substr(p_restricao,1,2) in ('PJ','OR') Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio as exibe_rel_menu,                 a.vinculacao,
                a.data_hora,
                a.envia_dia_util,     a.descricao as ds_menu,        a.justificativa as just_menu,
                a1.nome as nm_modulo, a1.sigla as sg_modulo,         a1.objetivo_geral,
                a2.sq_tipo_unidade as tp_exec,                       a2.nome as nm_unidade_exec, 
                a2.informal as informal_exec,                        a2.vinculada as vinc_exec,
                a2.adm_central as adm_exec,
                a3.sq_pessoa as tit_exec,                            a4.sq_pessoa as subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,
                b.sq_solic_pai,       b.sq_unidade sq_unidade_cad,   b.sq_cidade_origem,
                b.codigo_interno,     b.codigo_externo,              b.titulo,
                b.palavra_chave,      ceil(months_between(b.fim,b.inicio)) as meses_projeto,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then '---'
                               else 'Plano: '||b2.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.nome nm_tramite,   b1.ordem as or_tramite,
                b1.sigla as sg_tramite, b1.ativo,                    b1.envia_mail,
                b2.sq_plano,          b2.sq_plano_pai,               b2.titulo nm_plano,
                b2.missao,            b2.valores,                    b2.visao_presente,
                b2.visao_futuro,      b2.inicio as inicio_plano,     b2.fim as fim_plano,
                b2.ativo as st_plano,
                b3.nome as nm_unidade,
                bb.sq_siw_coordenada, bb.nome as nm_coordenada,      bb.latitude, 
                bb.longitude,         bb.icone,                      bb.tipo,
                d.sq_unidade_resp,    d.prioridade,                  
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.aviso_prox_conc_pacote, d.perc_dias_aviso_pacote,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                d.vincula_contrato,   d.vincula_viagem,              d.sq_tipo_pessoa,
                d.outra_parte,        d.preposto,                    d.limite_passagem,
                d.sq_cidade as cidade_evento,                        d.objetivo_superior,
                d.exclusoes,          d.premissas,                   d.restricoes,
                d.estudos,            d.instancia_articulacao,       d.composicao_instancia,
                d.analise1,           d.analise2,                    d.analise3,
                d.analise4,           d.exibe_relatorio,
                cast(b.fim as date)-cast(d.dias_aviso as integer) as aviso,
                d2.sq_pais as pais_evento,                           d2.co_uf as uf_evento,
                d1.nome as nm_prop,   d1.nome_resumido as nm_prop_res,
                case upper(d3.nome) when 'BRASIL' then d2.nome||'-'||d2.co_uf||' ('||d3.nome||')' else d2.nome||' ('||d3.nome||')' end as nm_cidade_evento,
                d4.inicio_etapa,      d4.fim_etapa,
                d5.inicio_etapa_real, d5.fim_etapa_real,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada as vinc_resp,                            e.adm_central as adm_resp,
                e.sq_unidade,         e.sigla as sg_unidade_resp,
                e1.sq_pessoa as titular,                             e2.sq_pessoa as substituto,
                f.nome_resumido as nm_sol,
                coalesce(f1.ativo,'N') as st_sol,
                g.sq_cc,              g.nome as cc_nome,             g.sigla as cc_sigla,
                h.sq_pais,            h.sq_regiao,                   h.co_uf,
                h.nome as nm_cidade,
                h1.nome as nm_uf,
                i.sq_acao_ppa,        i.sq_orprioridade,             i.problema,
                i.descricao as ds_acao,  i.publico_alvo,             i.estrategia,
                i.indicadores,        i.objetivo,
                j.codigo as cd_ppa,   j.nome as nm_ppa,              j.responsavel as resp_ppa,
                j.telefone as fone_ppa, j.email as mail_ppa,         j.selecionada_mpog as mpog_ppa,
                j.sq_acao_ppa_pai,    j.aprovado,                    j.empenhado,
                j.liquidado,          j.liquidar,                    j.saldo,
                j.ativo as ativo_ppa, j.padrao as padrao_ppa,        j.selecionada_relevante as relev_ppa,
                k.codigo as cd_ppa_pai, k.nome as nm_ppa_pai,        k.responsavel as resp_ppa_pai,
                k.telefone as fone_ppa_pai, k.email as mail_ppa_pai, k.selecionada_mpog as mpog_ppa_pai,
                k.ativo as ativo_ppa_pai, k.padrao as padrao_ppa_pai,k.selecionada_relevante as relev_ppa_pai,
                l.codigo as cd_pri,   l.nome as nm_pri,              l.responsavel as resp_pri,
                l.telefone as fone_pri, l.email as mail_pri,         l.ordem as ord_pri,
                l.ativo as ativo_pri, l.padrao as padrao_pri,
                m.sq_acordo,          m.cd_acordo,                   m.nm_acordo,
                m.sigla as sg_acordo,
                n.sq_menu as sq_menu_pai,
                o.sq_siw_solicitacao as sq_programa, o1.codigo_interno as cd_programa, o1.titulo as nm_programa,
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
                inner        join siw_modulo                 a1 on (a.sq_modulo           = a1.sq_modulo)
                inner        join siw_solicitacao            b  on (a.sq_menu             = b.sq_menu)
                  inner      join siw_tramite                b1 on (b.sq_siw_tramite      = b1.sq_siw_tramite)
                  left       join pe_plano                   b2 on (b.sq_plano            = b2.sq_plano)
                  left       join eo_unidade                 b3 on (b.sq_unidade          = b3.sq_unidade)
                  left       join siw_coordenada_solicitacao ba on (b.sq_siw_solicitacao  = ba.sq_siw_solicitacao)
                    left     join siw_coordenada             bb on (ba.sq_siw_coordenada  = bb.sq_siw_coordenada)
                  inner      join pj_projeto                 d  on (b.sq_siw_solicitacao  = d.sq_siw_solicitacao)
                      left   join co_pessoa                  d1 on (d.outra_parte         = d1.sq_pessoa)
                      left   join co_cidade                  d2 on (d.sq_cidade           = d2.sq_cidade)
                        left join co_pais                    d3 on (d2.sq_pais            = d3.sq_pais)
                        left join (select x.sq_siw_solicitacao, min(x.inicio_previsto) as inicio_etapa, max(x.fim_previsto) as fim_etapa
                                     from pj_projeto_etapa           x
                                          inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                    where y.sq_menu = w_menu
                                   group by x.sq_siw_solicitacao
                                  )                          d4 on (d.sq_siw_solicitacao = d4.sq_siw_solicitacao)
                        left join (select x.sq_siw_solicitacao, min(x.inicio_real) as inicio_etapa_real, max(x.fim_real) as fim_etapa_real
                                     from pj_projeto_etapa           x
                                          inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                    where y.sq_menu = w_menu
                                   group by x.sq_siw_solicitacao
                                  )                          d5 on (d.sq_siw_solicitacao  = d5.sq_siw_solicitacao)
                    inner    join eo_unidade                 e  on (d.sq_unidade_resp     = e.sq_unidade)
                      left   join eo_unidade_resp            e1 on (e.sq_unidade          = e1.sq_unidade and
                                                                    e1.tipo_respons       = 'T'           and
                                                                    e1.fim                is null
                                                                   )
                      left   join eo_unidade_resp            e2 on (e.sq_unidade          = e2.sq_unidade and
                                                                    e2.tipo_respons       = 'S'           and
                                                                    e2.fim                is null
                                                                   )
                    left     join or_acao                    i  on (d.sq_siw_solicitacao  = i.sq_siw_solicitacao)
                      left   join or_acao_ppa                j  on (i.sq_acao_ppa         = j.sq_acao_ppa)
                        left join or_acao_ppa                k  on (j.sq_acao_ppa_pai     = k.sq_acao_ppa)
                      left   join or_prioridade              l  on (i.sq_orprioridade     = l.sq_orprioridade)
                  inner      join co_pessoa                  f  on (b.solicitante         = f.sq_pessoa)
                    left     join sg_autenticacao            f1 on (f.sq_pessoa           = f1.sq_pessoa)
                  inner      join co_cidade                  h  on (b.sq_cidade_origem    = h.sq_cidade)
                    inner    join co_uf                      h1 on (h.co_uf               = h1.co_uf and
                                                                    h.sq_pais             = h1.sq_pais
                                                                   )
                  left       join ct_cc                      g  on (b.sq_cc               = g.sq_cc)
                left         join (select x.sq_siw_solicitacao as sq_acordo, y.codigo_interno as cd_acordo,
                                                w.nome_resumido||' - '||z.nome||' ('||to_char(x.inicio,'dd/mm/yyyy')||'-'||to_char(x.fim,'dd/mm/yyyy')||')' as nm_acordo,
                                                v.sigla
                                           from ac_acordo                      x
                                                inner   join   co_pessoa       w on (x.outra_parte        = w.sq_pessoa)
                                                inner   join   siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                                  left  join ct_cc             z on (y.sq_cc              = z.sq_cc)
                                                  inner join siw_menu          v on (y.sq_menu            = v.sq_menu)
                                        )                    m  on (b.sq_solic_pai        = m.sq_acordo)
                left         join siw_solicitacao            n  on (b.sq_solic_pai        = n.sq_siw_solicitacao)
                left         join pe_programa                o  on (b.sq_solic_pai        = o.sq_siw_solicitacao)
                  left       join siw_solicitacao            o1 on (o.sq_siw_solicitacao  = o1.sq_siw_solicitacao)
          where b.sq_siw_solicitacao       = p_chave;
   Elsif substr(p_restricao,1,2) = 'GC' Then
      -- Recupera os acordos que o usuário pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao,                   a.justificativa as just_menu,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec, a2.informal informal_exec,
                a2.vinculada vinc_exec,a2.adm_central adm_exec,
                a3.sq_pessoa tit_exec,a4.sq_pessoa subst_exec,
                a5.dias_pagamento,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,      b.protocolo_siw,
                calculaIDCC(b.sq_siw_solicitacao) as idcc, calculaIGCC(b.sq_siw_solicitacao) as igcc,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then '---'
                               else 'Plano: '||b3.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,                      b1.envia_mail,
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
                d.prestacao_contas,   d.pagina_diario_oficial,       d.condicoes_pagamento,
                d.valor_caucao,
                case d.prestacao_contas when 'S' then 'Sim' else 'Não' end as nm_prestacao_contas,
                retornaAfericaoIndicador(d.sq_eoindicador,d.indice_base) as vl_indice_base,
                retornaExcedenteContrato(d.sq_siw_solicitacao,b.fim) as limite_usado,
                case d.tipo_reajuste when 0 then 'Não permite' when 1 then 'Com índice' else 'Sem índice' end nm_tipo_reajuste,
                d1.nome as nm_tipo_acordo,d1.sigla as sg_acordo,     d1.modalidade as cd_modalidade, d1.exibe_idec,
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
                dc.sq_siw_solicitacao as sq_compra, coalesce(dd.numero_certame,dc.codigo_interno) as cd_compra,
                cast(b.fim as date)-cast(d.dias_aviso as integer) aviso,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla as sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                f.nome nm_cidade,
                m2.titulo nm_projeto,
                n.sq_cc,              n.nome as nm_cc,                  n.sigla as sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' as nm_resp,
                coalesce(o1.ativo,'N') as st_sol,
                p.nome_resumido as nm_exec,
                i.sq_projeto_etapa,   i1.titulo as nm_etapa,
                coalesce(m1.qtd_rubrica,0) as qtd_rubrica
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
                inner             join ac_parametro         a5 on (a.sq_pessoa                = a5.cliente)
                inner             join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                   inner          join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                   left           join pe_plano             b3 on (b.sq_plano                 = b3.sq_plano)
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
                                          from ac_acordo_aditivo          x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                         where y.sq_menu = w_menu
                                           and (x.prorrogacao = 'S' or x.revisao = 'S' or x.acrescimo = 'S' or x.supressao = 'S')
                                        group by x.sq_siw_solicitacao
                                       )                    d12 on (d.sq_siw_solicitacao       = d12.sq_siw_solicitacao)
                     left         join (select x.sq_siw_solicitacao, count(x.sq_acordo_aditivo) as aditivo
                                          from ac_acordo_aditivo          x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                         where y.sq_menu = w_menu
                                           and (x.acrescimo = 'S' or x.supressao = 'S')
                                        group by x.sq_siw_solicitacao
                                       )                    d13 on (d.sq_siw_solicitacao       = d13.sq_siw_solicitacao)                                       
                     left         join (select x.sq_siw_solicitacao, count(x.sq_acordo_aditivo) as aditivo
                                          from ac_acordo_aditivo          x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                         where y.sq_menu = w_menu
                                           and x.prorrogacao = 'S'
                                        group by x.sq_siw_solicitacao
                                       )                    d14 on (d.sq_siw_solicitacao       = d14.sq_siw_solicitacao)
                     left         join siw_solicitacao      dc on (d.sq_solic_compra          = dc.sq_siw_solicitacao)
                       left       join cl_solicitacao       dd on (dc.sq_siw_solicitacao      = dd.sq_siw_solicitacao)
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
                     left         join (select x.sq_siw_solicitacao, count(x.sq_projeto_rubrica) qtd_rubrica
                                          from pj_rubrica                 x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                        group by x.sq_siw_solicitacao
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
                inner             join (select x.sq_siw_solicitacao, max(x.sq_siw_solic_log) chave 
                                          from siw_solic_log              x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                         where y.sq_menu = w_menu
                                        group by x.sq_siw_solicitacao
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
                a.sq_unid_executora,  a.finalidade,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao,                   a.justificativa as just_menu,
                a1.nome as nm_modulo, a1.sigla as sg_modulo,         a1.objetivo_geral,
                a2.sq_tipo_unidade as tp_exec,                       a2.nome as nm_unidade_exec, 
                a2.informal as informal_exec,                        a2.vinculada as vinc_exec,
                a2.adm_central as adm_exec,
                a3.sq_pessoa as tit_exec,                            a4.sq_pessoa as subst_exec,
                b.sq_siw_solicitacao, b.solicitante,                 b.cadastrador,
                b.executor,           b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.opiniao,            b.sq_solic_pai,
                b.sq_unidade,         b.sq_cidade_origem,            b.palavra_chave,
                b.valor,              b.protocolo_siw,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then '---'
                               else 'Plano: '||b3.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b4.sq_solic_pai as sq_solic_avo,
                case when b4.sq_solic_pai is null 
                     then case when b4.sq_plano is null
                               then case when b6.sq_cc is null
                                         then '???'
                                         else 'Classif: '||b6.nome 
                                    end
                               else ' Plano: '||b5.titulo
                          end
                     else dados_solic(b4.sq_solic_pai) 
                end as dados_avo,
                b1.sq_siw_tramite,    b1.nome as nm_tramite,         b1.ordem as or_tramite,
                b1.sigla as sg_tramite, b1.ativo,                    b1.envia_mail,
                d.pessoa,             b.codigo_interno,              d.sq_acordo_parcela,
                d.sq_forma_pagamento, d.sq_tipo_lancamento,          d.sq_tipo_pessoa,
                d.emissao,            d.vencimento,                  d.quitacao,
                b.codigo_externo,     d.observacao,                  
                d.aviso_prox_conc,
                d.dias_aviso,         d.sq_forma_pagamento,          d.sq_agencia,
                d.operacao_conta,     d.numero_conta,                d.sq_pais_estrang,
                d.aba_code,           d.swift_code,                  d.endereco_estrang,
                d.banco_estrang,      d.agencia_estrang,             d.cidade_estrang,
                d.informacoes,        d.codigo_deposito,             d.condicoes_pagamento,
                d.valor_imposto,      d.valor_retencao,              d.valor_liquido,
                d.tipo as tipo_rubrica,  d.processo,                 d.referencia_inicio,
                d.referencia_fim,     d.sq_pessoa_conta,             d.sq_solic_vinculo,
                case d.tipo when 1 then 'Dotação incial' when 2 then 'Transferência entre rubricas' when 3 then 'Atualização de aplicação' when 4 then 'Entradas' when 5 then 'Saídas' end nm_tipo_rubrica,
                d1.receita,           d1.despesa,                    d1.nome as nm_tipo_lancamento,
                d2.nome as nm_pessoa, d2.nome_resumido as nm_pessoa_resumido,
                coalesce(d3.valor,0) as valor_doc,
                d4.nome as nm_forma_pagamento, d4.sigla as sg_forma_pagamento, d4.ativo as st_forma_pagamento,
                d5.codigo as cd_agencia, d5.nome as nm_agencia,
                d6.sq_banco,          d6.codigo as cd_banco,            d6.nome as nm_banco,
                d6.exige_operacao,
                d7.nome as nm_pais,
                d8.nome as nm_tipo_pessoa,
                coalesce(d9.valor,0) as valor_nota,
                coalesce(da.qtd,0) as qtd_nota,
                coalesce(db.existe,0) as notas_parcela,
                dc.operacao as oper_org, dc.numero as nr_conta_org,
                dd.codigo as cd_age_org, dd.nome as nm_age_org,
                de.codigo as cd_ban_org, de.nome as nm_ban_org,
                de.exige_operacao as exige_oper_org,
                cast(b.fim as date)-cast(d.dias_aviso as integer) as aviso,
                e.sq_tipo_unidade,    e.nome as nm_unidade_resp,     e.informal as informal_resp,
                e.vinculada as vinc_resp,e.adm_central as adm_resp,  e.sigla as sg_unidade_resp,
                e1.sq_pessoa as titular,                             e2.sq_pessoa as substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                f.nome nm_cidade,
                m1.codigo_interno as cd_acordo,
                coalesce(m4.existe,0) as notas_acordo,
                n.sq_cc,              n.nome as nm_cc,               n.sigla as sg_cc,
                o.nome_resumido as nm_solic,                         o.nome_resumido||' ('||o2.sigla||')' as nm_resp,
                p.nome_resumido as nm_exec,
                case coalesce(m2.sq_siw_solicitacao,0) when 0 then q2.titulo             else m5.titulo end as nm_projeto,
                case coalesce(m2.sq_siw_solicitacao,0) when 0 then q.sq_siw_solicitacao else m2.sq_siw_solicitacao end as sq_projeto,
                case coalesce(m3.sq_siw_solicitacao,0) when 0 then q1.qtd_rubrica       else m3.qtd_rubrica        end as qtd_rubrica
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
                   left           join pe_plano             b3 on (b.sq_plano                 = b3.sq_plano)
                   left           join siw_solicitacao      b4 on (b.sq_solic_pai             = b4.sq_siw_solicitacao)
                     left         join pe_plano             b5 on (b4.sq_plano                = b5.sq_plano)
                     left         join ct_cc                b6 on (b4.sq_cc                   = b6.sq_cc)
                   inner          join fn_lancamento        d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                     inner        join fn_tipo_lancamento   d1 on (d.sq_tipo_lancamento       = d1.sq_tipo_lancamento)
                     inner        join co_forma_pagamento   d4 on (d.sq_forma_pagamento       = d4.sq_forma_pagamento)
                     inner        join co_tipo_pessoa       d8 on (d.sq_tipo_pessoa           = d8.sq_tipo_pessoa)
                     left         join (select x.sq_siw_solicitacao, sum(x.valor) as valor
                                          from fn_lancamento_doc          x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                         where y.sq_menu = w_menu
                                           and x.sq_acordo_nota is null
                                        group by x.sq_siw_solicitacao
                                       )                    d3 on (d.sq_siw_solicitacao       = d3.sq_siw_solicitacao)
                     left         join co_pessoa            d2 on (d.pessoa                   = d2.sq_pessoa)
                     left         join co_agencia           d5 on (d.sq_agencia               = d5.sq_agencia)
                       left       join co_banco             d6 on (d5.sq_banco                = d6.sq_banco)
                     left         join co_pais              d7 on (d.sq_pais_estrang          = d7.sq_pais)
                     left         join (select x.sq_siw_solicitacao, sum(x.valor) as valor
                                          from fn_lancamento_doc          x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                         where y.sq_menu = w_menu
                                           and x.sq_acordo_nota is not null
                                        group by x.sq_siw_solicitacao
                                       )                    d9 on (d.sq_siw_solicitacao       = d9.sq_siw_solicitacao)
                     left         join (select x.sq_siw_solicitacao, count(x.sq_lancamento_doc) as qtd
                                          from fn_lancamento_doc          x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                         where y.sq_menu = w_menu
                                           and x.sq_acordo_nota is not null
                                        group by x.sq_siw_solicitacao
                                       )                    da on (d.sq_siw_solicitacao       = da.sq_siw_solicitacao)
                     left outer   join (select z.sq_acordo_parcela, w.sq_pessoa, count(*) as existe
                                          from ac_parcela_nota              x
                                               inner join ac_acordo_parcela z on (x.sq_acordo_parcela  = z.sq_acordo_parcela)
                                               inner join siw_solicitacao   y on (z.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                               inner join siw_menu          w on (y.sq_menu            = w.sq_menu)
                                        group by z.sq_acordo_parcela, w.sq_pessoa
                                       )                    db on (d.cliente                  = db.sq_pessoa and
                                                                   d.sq_acordo_parcela        = db.sq_acordo_parcela and
                                                                   d.sq_acordo_parcela        is not null
                                                                  )
                     left         join co_pessoa_conta      dc on (d.sq_pessoa_conta          = dc.sq_pessoa_conta)
                       left       join co_agencia           dd on (dc.sq_agencia              = dd.sq_agencia)
                         left     join co_banco             de on (dd.sq_banco                = de.sq_banco)
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
                         left     join (select x.sq_siw_solicitacao, count(x.sq_projeto_rubrica) as qtd_rubrica
                                          from pj_rubrica                 x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                        group by x.sq_siw_solicitacao
                                       )                    m3 on (m2.sq_siw_solicitacao      = m3.sq_siw_solicitacao)
                     left outer   join (select x.sq_siw_solicitacao, count(*) as existe
                                          from ac_acordo_nota x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                        group by x.sq_siw_solicitacao
                                       )                    m4 on (m.sq_siw_solicitacao       = m4.sq_siw_solicitacao)
                   left           join pj_projeto           q  on (b.sq_solic_pai             = q.sq_siw_solicitacao)
                     left         join siw_solicitacao      q2 on (q.sq_siw_solicitacao       = q2.sq_siw_solicitacao)
                     left         join (select x.sq_siw_solicitacao, count(x.sq_projeto_rubrica) as qtd_rubrica
                                          from pj_rubrica                 x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                        group by x.sq_siw_solicitacao
                                       )                    q1 on (q.sq_siw_solicitacao       = q1.sq_siw_solicitacao)
                   left           join ct_cc                n  on (b.sq_cc                    = n.sq_cc)
                   left           join co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                     inner        join sg_autenticacao      o1 on (o.sq_pessoa                = o1.sq_pessoa)
                       inner      join eo_unidade           o2 on (o1.sq_unidade              = o2.sq_unidade)
                   left           join co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                inner             join (select x.sq_siw_solicitacao, max(x.sq_siw_solic_log) as chave 
                                          from siw_solic_log              x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                         where y.sq_menu = w_menu
                                        group by x.sq_siw_solicitacao
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
                a.sq_unid_executora,  a.finalidade,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao,                   a.justificativa as just_menu,
                a1.nome as nm_modulo, a1.sigla as sg_modulo,         a1.objetivo_geral,
                a2.sq_tipo_unidade as tp_exec,                       a2.nome as nm_unidade_exec, 
                a2.informal as informal_exec,                        a2.vinculada as vinc_exec,
                a2.adm_central as adm_exec,
                a3.sq_pessoa as tit_exec,                            a31.nome as nm_tit_exec,
                a4.sq_pessoa as subst_exec,                
                a3.sq_pessoa as tit_exec,                            a4.sq_pessoa as subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,      b.protocolo_siw,
                to_char(b.inclusao,'dd/mm/yyyy, hh24:mi:ss')  as phpdt_inclusao,
                to_char(b.inicio,'dd/mm/yyyy, hh24:mi:ss')    as phpdt_inicio,
                to_char(b.fim,'dd/mm/yyyy, hh24:mi:ss')       as phpdt_fim,
                to_char(b.conclusao,'dd/mm/yyyy, hh24:mi:ss') as phpdt_conclusao,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then '---'
                               else 'Plano: '||b3.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome as nm_tramite,         b1.ordem as or_tramite,
                b1.sigla as sg_tramite, b1.ativo,                    b1.envia_mail,
                b4.prefixo||'.'||substr(cast(1000000+b4.numero_documento as varchar),2,6)||'/'||b4.ano||'-'||substr(cast(100+to_number(b4.digito) as varchar),2,2) as protocolo,
                d.sq_unidade_resp,    d.assunto,                     d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                case d.prioridade when 0 then 'Alta' when 1 then 'Média' else 'Normal' end as nm_prioridade,
                d.ordem,
                d1.sq_pessoa as sq_prop, d1.tipo as tp_missao,       d11.codigo_interno,
                d1.reserva,           d1.pta,                        d1.justificativa_dia_util,
                d1.emissao_bilhete,   d1.pagamento_diaria,           d1.pagamento_bilhete,
                d1.boletim_numero,    d1.boletim_data,               d1.valor_alimentacao,
                d1.valor_transporte,  d1.desconto_alimentacao,       d1.desconto_transporte,
                d1.valor_adicional,   d1.tipo as tipo_missao,
                d1.sq_pais_estrang,   d1.aba_code,                   d1.swift_code,
                d1.endereco_estrang,  d1.banco_estrang,              d1.agencia_estrang,
                d1.cidade_estrang,    d1.informacoes,                d1.codigo_deposito,
                d1.numero_conta,      d1.operacao_conta,             d1.cidade_estrang,
                d1.informacoes,       d1.sq_pdvinculo_bilhete,       d1.sq_pdvinculo_reembolso,
                d1.valor_passagem,    d1.passagem,                   d1.diaria,
                d1.hospedagem,        d1.veiculo,                    d1.valor_previsto_bilhetes,
                d1.cumprimento,       d1.relatorio,                  d1.sq_relatorio_viagem, 
                d1.reembolso,         d1.reembolso_valor,            d1.reembolso_observacao,
                d1.ressarcimento,     d1.ressarcimento_valor,        d1.ressarcimento_observacao,
                d1.ressarcimento_data,d1.sq_pdvinculo_ressarcimento, d1.sq_arquivo_comprovante,
                d1.nacional,          d1.internacional,              d1.deposito_identificado,
                d1.cotacao_valor,     d1.cotacao_observacao,         d1.diaria_fim_semana,
                case d1.passagem    when 'S' then 'Sim' else 'Não' end as nm_passagem,
                case d1.hospedagem  when 'S' then 'Sim' else 'Não' end as nm_hospedagem,
                case d1.veiculo     when 'S' then 'Sim' else 'Não' end as nm_veiculo,
                case d1.reembolso   when 'S' then 'Sim' else 'Não' end as nm_reembolso,
                case d1.cumprimento when 'I' then 'Não' when 'P' then 'Sim' when 'C' then 'Cancelada' else 'Não informada' end as nm_cumprimento,
                d2.nome nm_prop,      d2.nome_resumido nm_prop_res, d2.sq_tipo_pessoa,
                coalesce(d21.ativo,'N') st_prop,
                d3.sq_tipo_vinculo,   d3.nome nm_tipo_vinculo,
                d4.sexo,              d4.cpf,
                d22.sq_forma_pagamento, d22.nome as nm_forma_pagamento, d22.sigla as sg_forma_pagamento,
                d23.nome as pais_estrang,
                d51.sq_projeto_rubrica, d51.codigo as cd_rubrica,    d51.nome as nm_rubrica,
                d52.sq_tipo_lancamento, d52.nome   as nm_lancamento, d52.descricao as ds_lancamento,
                db1.sq_projeto_rubrica as sq_rubrica_reemb,   db1.codigo as cd_rubrica_reemb,    db1.nome as nm_rubrica_reemb,
                db2.sq_tipo_lancamento as sq_lancamento_reemb, db2.nome   as nm_lancamento_reemb, db2.descricao as ds_lancamento_reemb,
                dc1.sq_projeto_rubrica as sq_rubrica_ressarc,   dc1.codigo as cd_rubrica_ressarc,    dc1.nome as nm_rubrica_ressarc,
                dc2.sq_tipo_lancamento as sq_lancamento_ressarc, dc2.nome   as nm_lancamento_ressarc, dc2.descricao as ds_lancamento_ressarc,
                d6.sq_agencia,        d6.codigo cd_agencia,          d6.nome nm_agencia,
                d7.sq_banco,          d7.codigo cd_banco,            d7.nome nm_banco,
                d7.exige_operacao,
                d8.sq_posto_trabalho, d8.sq_posto_trabalho,          d8.sq_modalidade_contrato,
                d8.matricula,
                d9.nome as nm_diaria,
                da.nome_original as nm_arquivo, da.descricao as ds_arquivo,   da.caminho as cm_arquivo,
                dd.nome_original as nm_arquivo_comprovante, dd.descricao as ds_arquivo_comprovante,   dd.caminho as cm_arquivo_comprovante,
                cast(b.fim as date)-cast(d.dias_aviso as integer) aviso,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla as sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,       e12.nome nm_titular,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                g.sq_cc,              g.nome nm_cc,                  g.sigla sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                coalesce(o1.ativo,'N') st_sol,
                p.nome_resumido nm_exec,
                soma_dias(a.sq_pessoa, b.inicio, (-1*case d1.internacional when 'S' then a11.dias_antecedencia_int else a11.dias_antecedencia end), 'U') as limite_envio,
                soma_dias(a.sq_pessoa, trunc(trunc(now())), (case d1.internacional when 'S' then a11.dias_antecedencia_int else a11.dias_antecedencia end), 'U') as envio_regular,
                case d1.internacional when 'S' then a11.dias_antecedencia_int else a11.dias_antecedencia end as dias_antecedencia,
                case trunc(b.fim) when soma_dias(a.sq_pessoa,b.inicio,trunc(b.fim)-trunc(b.inicio),'U') then 'N' else 'S' end as fim_semana,
                d9.valor_complemento, d1.complemento_qtd, d1.complemento_base, d1.complemento_valor                  
           from siw_menu                                               a
                  inner                join pd_parametro              a11 on (a.sq_pessoa                   = a11.cliente)
                  inner                join eo_unidade                 a2 on (a.sq_unid_executora           = a2.sq_unidade)
                    left               join eo_unidade_resp            a3 on (a2.sq_unidade                 = a3.sq_unidade   and
                                                                              a3.tipo_respons               = 'T'             and
                                                                              a3.fim                        is null)
                      left             join co_pessoa                 a31 on (a3.sq_pessoa                  = a31.sq_pessoa)
                    left               join eo_unidade_resp            a4 on (a2.sq_unidade                 = a4.sq_unidade   and
                                                                              a4.tipo_respons               = 'S'             and
                                                                              a4.fim                        is null)
                  inner                join siw_modulo                 a1 on (a.sq_modulo                   = a1.sq_modulo)
                  inner                join siw_solicitacao            b  on (a.sq_menu                     = b.sq_menu)
                    inner              join siw_tramite                b1 on (b.sq_siw_tramite              = b1.sq_siw_tramite)
                    left               join pe_plano                   b3 on (b.sq_plano                    = b3.sq_plano)
                    left               join pa_documento               b4 on (b.protocolo_siw               = b4.sq_siw_solicitacao)
                    inner              join gd_demanda                 d  on (b.sq_siw_solicitacao          = d.sq_siw_solicitacao)
                      inner            join pd_missao                  d1 on (d.sq_siw_solicitacao          = d1.sq_siw_solicitacao)
                        inner          join siw_solicitacao           d11 on (d1.sq_siw_solicitacao         = d11.sq_siw_solicitacao)
                        inner          join co_pessoa                  d2 on (d1.sq_pessoa                  = d2.sq_pessoa)
                          left         join sg_autenticacao           d21 on (d2.sq_pessoa                  = d21.sq_pessoa)
                          inner        join co_tipo_vinculo            d3 on (d2.sq_tipo_vinculo            = d3.sq_tipo_vinculo)
                          left         join co_pessoa_fisica           d4 on (d2.sq_pessoa                  = d4.sq_pessoa)
                            left       join gp_contrato_colaborador    d8 on (d4.cliente                    = d8.cliente      and
                                                                              d4.sq_pessoa                  = d8.sq_pessoa    and
                                                                              d8.fim                        is null)
                        left           join pd_vinculo_financeiro      d5 on (d1.sq_pdvinculo_bilhete       = d5.sq_pdvinculo_financeiro)
                          left         join pj_rubrica                d51 on (d5.sq_projeto_rubrica         = d51.sq_projeto_rubrica)
                          left         join fn_tipo_lancamento        d52 on (d5.sq_tipo_lancamento         = d52.sq_tipo_lancamento)
                        left           join pd_vinculo_financeiro      db on (d1.sq_pdvinculo_reembolso     = db.sq_pdvinculo_financeiro)
                          left         join pj_rubrica                db1 on (db.sq_projeto_rubrica         = db1.sq_projeto_rubrica)
                          left         join fn_tipo_lancamento        db2 on (db.sq_tipo_lancamento         = db2.sq_tipo_lancamento)
                        left           join pd_vinculo_financeiro      dc on (d1.sq_pdvinculo_ressarcimento = dc.sq_pdvinculo_financeiro)
                          left         join pj_rubrica                dc1 on (dc.sq_projeto_rubrica         = dc1.sq_projeto_rubrica)
                          left         join fn_tipo_lancamento        dc2 on (dc.sq_tipo_lancamento         = dc2.sq_tipo_lancamento)
                        left           join co_forma_pagamento        d22 on (d1.sq_forma_pagamento         = d22.sq_forma_pagamento)
                        left           join co_pais                   d23 on (d1.sq_pais_estrang            = d23.sq_pais)
                        left           join co_agencia                 d6 on (d1.sq_agencia                 = d6.sq_agencia)
                          left         join co_banco                   d7 on (d6.sq_banco                   = d7.sq_banco)
                        left           join pd_categoria_diaria        d9 on (d1.diaria                     = d9.sq_categoria_diaria)
                        left           join siw_arquivo                da on (d1.sq_relatorio_viagem        = da.sq_siw_arquivo)
                        left           join siw_arquivo                dd on (d1.sq_arquivo_comprovante     = dd.sq_siw_arquivo)
                      inner            join eo_unidade                 e  on (d.sq_unidade_resp             = e.sq_unidade)
                        left           join eo_unidade_resp            e1 on (e.sq_unidade                  = e1.sq_unidade   and
                                                                              e1.tipo_respons               = 'T'             and
                                                                              e1.fim                        is null)
                          left         join co_pessoa                 e12 on (e1.sq_pessoa                  = e12.sq_pessoa)
                        left           join eo_unidade_resp            e2 on (e.sq_unidade                  = e2.sq_unidade   and
                                                                              e2.tipo_respons               = 'S'             and
                                                                              e2.fim                        is null)
                    inner              join co_cidade                  f  on (b.sq_cidade_origem            = f.sq_cidade)
                    left               join ct_cc                      g  on (b.sq_cc                       = g.sq_cc)
                    left               join co_pessoa                  o  on (b.solicitante                 = o.sq_pessoa)
                      left             join sg_autenticacao            o1 on (o.sq_pessoa                   = o1.sq_pessoa)
                        left           join eo_unidade                 o2 on (o1.sq_unidade                 = o2.sq_unidade)
                    left               join co_pessoa                  p  on (b.executor                    = p.sq_pessoa)
                    inner              join (select x.sq_siw_solicitacao, max(x.sq_siw_solic_log) as chave 
                                               from siw_solic_log x
                                                    inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                              where y.sq_menu = w_menu
                                             group by x.sq_siw_solicitacao
                                            )                          j on (b.sq_siw_solicitacao          = j.sq_siw_solicitacao)
                      left             join gd_demanda_log             k on (j.chave                       = k.sq_siw_solic_log)
                        left           join sg_autenticacao            l on (k.destinatario                = l.sq_pessoa)
          where b.sq_siw_solicitacao = p_chave;          
   Elsif substr(p_restricao,1,2) = 'SR' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,
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
                     then case when b.sq_plano is null
                               then '---'
                               else 'Plano: '||b4.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                case when b1.sigla = 'AT' then b.valor else 0 end as custo_real,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,                      b1.envia_mail,
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
                  left       join pe_plano                  b4 on (b.sq_plano            = b4.sq_plano)
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
                  left       join co_pessoa                 i  on (b.executor            = i.sq_pessoa)
                  left       join co_pessoa                 j  on (b.recebedor           = j.sq_pessoa)                  
                  inner      join co_cidade                 h  on (b.sq_cidade_origem    = h.sq_cidade)
                  left       join ct_cc                     g  on (b.sq_cc               = g.sq_cc)
          where b.sq_siw_solicitacao       = p_chave;
   Elsif substr(p_restricao,1,4) = 'PEPR' Then
      -- Recupera os programas que o usuário pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,
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
                     then case when b.sq_plano is null
                               then '---'
                               else 'Plano: '||b2.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,                      b1.envia_mail,
                b2.sq_plano,          b2.sq_plano_pai,               b2.titulo as nm_plano,
                b2.missao,            b2.valores,                    b2.visao_presente,
                b2.visao_futuro,      b2.inicio as inicio_plano,     b2.fim as fim_plano,
                b2.ativo as st_plano,
                b4.sq_unidade sq_unidade_adm, b4.nome nm_unidade_adm, b4.sigla sg_unidade_adm,
                b5.sq_pessoa tit_adm, b6.sq_pessoa subst_adm,
                d.sq_siw_solicitacao sq_programa,
                d.sq_pehorizonte,     d.sq_penatureza,               b.codigo_interno, 
                b.codigo_interno cd_programa,
                b.titulo,             d.publico_alvo,                d.estrategia, 
                d.ln_programa,        d.situacao_atual,              d.exequivel, 
                d.justificativa_inexequivel,                         d.outras_medidas, 
                d.inicio_real,        d.fim_real,                    d.custo_real, 
                d.nota_conclusao,     d.aviso_prox_conc,             d.dias_aviso,
                d1.nome nm_horizonte, d1.ativo st_horizonte, 
                d7.nome nm_natureza, d7.ativo st_natureza,
                cast(b.fim as date)-cast(d.dias_aviso as integer) aviso,
                e.sq_unidade sq_unidade_resp,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla as sg_unidade_resp,
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
                      left           join pe_plano             b2 on (b.sq_plano                 = b2.sq_plano)
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
                   inner             join (select x.sq_siw_solicitacao, max(x.sq_siw_solic_log) as chave 
                                             from siw_solic_log x
                                                  inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                            where y.sq_menu = w_menu
                                           group by x.sq_siw_solicitacao
                                          )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                     left            join pe_programa_log      k  on (j.chave                    = k.sq_siw_solic_log)
                       left          join sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
          where b.sq_siw_solicitacao       = p_chave;
   Elsif substr(p_restricao,1,3) = 'PAD' Then
      -- Recupera os programas que o usuário pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,
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
                     then case when b.sq_plano is null
                               then '---'
                               else 'Plano: '||b6.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,                      b1.envia_mail,
                b3.nome as nm_unid_origem, b3.sigla sg_unid_origem,
                case b5.padrao when 'S' then b4.nome||'-'||b4.co_uf else b4.nome||' ('||b5.nome||')' end as nm_cidade,
                b7.sq_siw_solicitacao as sq_emprestimo, b7.fim as devolucao_prevista,
                b8.sq_siw_solicitacao as sq_eliminacao, b8.codigo_interno as cd_eliminacao, b8.eliminacao as dt_eliminacao, 
                b8.sigla as sg_tramite_eliminacao,
                d.numero_original,    d.numero_documento,            d.ano,
                d.prefixo,            d.digito,                      d.interno,
                d.prefixo||'.'||substr(cast(1000000+d.numero_documento as varchar),2,6)||'/'||d.ano||'-'||substr(cast(100+to_number(d.digito) as varchar),2,2) as protocolo,
                d.sq_especie_documento, d.sq_natureza_documento,     d.unidade_autuacao,
                d.data_autuacao,      d.pessoa_origem,               d.processo,
                d.circular,           d.copias,                      d.volumes,
                d.data_recebimento,   d.unidade_int_posse,           d.pessoa_ext_posse,
                d.tipo_juntada,       d.sq_caixa,                    d.pasta,
                d.data_setorial,      d.data_central,                d.observacao_setorial,
                case d.tipo_juntada when 'A' then 'Anexado' when 'P' then 'Apensado' end as nm_tipo_juntada,
                to_char(d.data_juntada, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_juntada,
                to_char(d.data_desapensacao,'DD/MM/YYYY, HH24:MI:SS') as phpdt_desapensacao,
                case d.processo when 'S' then 'Processo' else 'Documento' end as nm_tipo,
                case when d.pessoa_origem is null then b3.nome else d2.nome end as nm_origem,
                coalesce(d1.nome,'Irrestrito') as nm_natureza,       d1.sigla as sg_natureza,
                d1.descricao as ds_natureza,                         d1.ativo as st_natureza,
                d2.nome_resumido as nm_res_pessoa_origem,            d2.nome as nm_pessoa_origem,
                d3.sq_tipo_pessoa,                                   d3.nome as nm_tipo_pessoa,
                d4.sq_assunto,
                d5.codigo as cd_assunto,                             d5.descricao as ds_assunto,
                d5.detalhamento as dst_assunto,                      d5.observacao as ob_assunto,
                d7.nome as nm_especie,d7.sigla as sg_especie,        d7.ativo as st_especie,
                case d6.sigla when 'ANOS' then d5.fase_corrente_anos||' '||d6.descricao when 'NAPL' then '---' else d6.descricao end as guarda_corrente,
                case d8.sigla when 'ANOS' then d5.fase_intermed_anos||' '||d8.descricao when 'NAPL' then '---' else d8.descricao end as guarda_intermed,
                case d9.sigla when 'ANOS' then d5.fase_final_anos   ||' '||d9.descricao when 'NAPL' then '---' else d9.descricao end as guarda_final,
                da.descricao as destinacao_final,
                db.codigo as cd_assunto_pai, db.descricao as ds_assunto_pai,
                dc.codigo as cd_assunto_avo, dc.descricao as ds_assunto_avo,
                dd.codigo as cd_assunto_bis, dd.descricao as ds_assunto_bis,
                df.sq_pessoa as pessoa_interes, df.nome as nm_pessoa_interes,
                dg.prefixo||'.'||substr(cast(1000000+dg.numero_documento as varchar),2,6)||'/'||dg.ano||'-'||substr(cast(100+to_number(dg.digito) as varchar),2,2) as protocolo_pai,
                dh.numero as nr_caixa, dh.assunto as as_caixa, dh.descricao as ds_caixa, dh.data_limite as dt_caixa, dh.intermediario as in_caixa,
                dh.destinacao_final as df_caixa, dh.arquivo_data, dh.sq_arquivo_local,
                montaNomeArquivoLocal(dh.sq_arquivo_local) as nm_arquivo_local,
                di.sq_unidade as sq_unid_caixa, di.sigla as sg_unid_caixa, di.nome as nm_unid_caixa,
                dj.sq_unidade_autua, dj.nm_unidade_autua, dj.sg_unidade_autua,
                cast(b.fim as date)-cast(k.dias_aviso as integer) aviso,
                e.sq_unidade as sq_unidade_resp,
                e.sq_tipo_unidade,    e.nome as nm_unidade_resp,     e.informal as informal_resp,
                e.vinculada as vinc_resp, e.adm_central as adm_resp, e.sigla as sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                g.sq_cc,              g.nome nm_cc,                  g.sigla sg_cc,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido as nm_solic, o.nome_resumido||' ('||o2.sigla||')' as nm_resp,
                coalesce(o1.ativo,'N') as st_sol,
                p.nome_resumido as nm_exec,
                q.nome as nm_unidade_posse,                          q.sigla as sg_unidade_posse,
                q1.sq_pessoa as titular,                                q2.sq_pessoa as substituto,
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
                      left           join pe_plano                 b6 on (b.sq_plano                 = b6.sq_plano)
                      left           join (select y.protocolo, y.sq_siw_solicitacao, x.fim
                                             from siw_solicitacao               x
                                                  inner join pa_emprestimo_item y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                            where y.devolucao is null
                                          )                        b7 on (b.sq_siw_solicitacao       = b7.protocolo)
                      left           join (select y.protocolo, x.codigo_interno, y.sq_siw_solicitacao, x.fim, z.sigla, y.eliminacao
                                             from siw_solicitacao          x
                                                  inner join pa_eliminacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                                  inner join siw_tramite   z on (x.sq_siw_tramite     = z.sq_siw_tramite and z.sigla <> 'CA')
                                          )                        b8 on (b.sq_siw_solicitacao       = b8.protocolo)
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
                        left         join pa_documento             dg on (d.sq_documento_pai         = dg.sq_siw_solicitacao)
                        left         join pa_caixa                 dh on (d.sq_caixa                 = dh.sq_caixa)
                          left       join eo_unidade               di on (dh.sq_unidade              = di.sq_unidade)
                        left         join (select l.sq_siw_solicitacao, l.unidade_origem as sq_unidade_autua, 
                                                  m.nome as nm_unidade_autua, m.sigla as sg_unidade_autua
                                             from (select w.sq_siw_solicitacao, max(w.envio) as envio
                                                     from pa_documento_log              w
                                                          inner   join siw_solicitacao  x  on (w.sq_siw_solicitacao = x.sq_siw_solicitacao)
                                                          inner   join pa_tipo_despacho y  on (w.sq_tipo_despacho = y.sq_tipo_despacho)
                                                    where y.sigla   = 'AUTUAR'
                                                   group by w.sq_siw_solicitacao
                                                  )                                        k
                                                  inner   join pa_documento_log            l on (k.sq_siw_solicitacao = l.sq_siw_solicitacao and
                                                                                                 k.envio              = l.envio
                                                                                                )
                                                    inner join eo_unidade                  m on (l.unidade_origem     = m.sq_unidade)
                                          )                        dj on (d.sq_siw_solicitacao       = dj.sq_siw_solicitacao)
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
                      inner          join (select x.sq_siw_solicitacao, max(x.sq_siw_solic_log) as chave 
                                                  from siw_solic_log x
                                                       inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                                 where y.sq_menu = w_menu
                                                group by x.sq_siw_solicitacao
                                          )                        j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                     left            join pa_documento_log         k  on (j.chave                    = k.sq_siw_solic_log)
                       left          join sg_autenticacao          l  on (k.recebedor                = l.sq_pessoa)
          where b.sq_siw_solicitacao = p_chave;
   End If;

  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;