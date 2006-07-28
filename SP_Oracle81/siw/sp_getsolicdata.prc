create or replace procedure SP_GetSolicData
   (p_chave     in number,
    p_restricao in varchar2,
    p_result    out siw.sys_refcursor) is
begin
   If p_restricao = 'GDGERAL' or p_restricao = 'GDPGERAL' or p_restricao = 'ORPGERAL' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               a.acompanha_fases,
                a.sq_unid_executora,  a.finalidade,                  a.arquivo_proced,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.data_hora,          a.envia_dia_util,              a.descricao,
                a.justificativa,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec, a2.informal informal_exec,
                a2.vinculada vinc_exec,a2.adm_central adm_exec,
                a3.sq_pessoa tit_exec,a4.sq_pessoa subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.data_hora,                   b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.sq_unidade_resp,    d.assunto,                     d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                d.ordem,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.nome_resumido nm_sol,
                g.sq_cc,              g.nome cc_nome,                g.sigla cc_sigla,
                h.sq_pais,            h.sq_regiao,                   h.co_uf,
                h.nome nm_cidade,
                i.sq_projeto_etapa,   j.titulo nm_etapa,             k.titulo nm_projeto
           from siw_menu             a,
                eo_unidade                a2,
                eo_unidade_resp           a3,
                eo_unidade_resp           a4,
                siw_modulo           a1,
                siw_solicitacao      b ,
                siw_tramite          b1,
                gd_demanda           d ,
                eo_unidade           e ,
                eo_unidade_resp      e1,
                eo_unidade_resp      e2,
                co_pessoa            f ,
                co_cidade            h ,
                ct_cc                g ,
                eo_unidade           c ,
                pj_etapa_demanda     i ,
                pj_projeto_etapa     j ,
                pj_projeto             k
          where (a.sq_unid_executora        = a2.sq_unidade)
            and (a2.sq_unidade              = a3.sq_unidade (+) and
                 a3.tipo_respons (+)        = 'T'           and
                 a3.fim (+)                 is null
                )
            and (a2.sq_unidade              = a4.sq_unidade (+) and
                 a4.tipo_respons (+)        = 'S'           and
                 a4.fim (+)                 is null
                )
            and (a.sq_modulo           = a1.sq_modulo)
            and (a.sq_menu             = b.sq_menu)
            and (b.sq_siw_tramite      = b1.sq_siw_tramite)
            and (b.sq_siw_solicitacao  = d.sq_siw_solicitacao)
            and (d.sq_unidade_resp     = e.sq_unidade)
            and (e.sq_unidade               = e1.sq_unidade (+) and
                 e1.tipo_respons (+)        = 'T'           and
                 e1.fim (+)                 is null
                )
            and (e.sq_unidade               = e2.sq_unidade (+) and
                 e2.tipo_respons (+)        = 'S'           and
                 e2.fim (+)                 is null
                )
            and (b.solicitante         = f.sq_pessoa)
            and (b.sq_cidade_origem    = h.sq_cidade)
            and (b.sq_cc               = g.sq_cc (+))
            and (a.sq_unid_executora   = c.sq_unidade (+))
            and (b.sq_siw_solicitacao  = i.sq_siw_solicitacao (+))
            and (i.sq_projeto_etapa    = j.sq_projeto_etapa (+))
            and (b.sq_solic_pai        = k.sq_siw_solicitacao (+))
            and b.sq_siw_solicitacao       = p_chave;
   Elsif p_restricao = 'PJGERAL' or p_restricao = 'ORGERAL' or p_restricao = 'ORINFO'
         or p_restricao = 'ORRESP' or p_restricao = 'OROUTRAS' or p_restricao = 'ORVISUAL'
         or p_restricao = 'ORFINANC' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               a.acompanha_fases,
                a.sq_unid_executora,  a.finalidade,                  a.arquivo_proced,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.data_hora,          a.envia_dia_util,              a.descricao,
                a.justificativa,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec, a2.informal informal_exec,
                a2.vinculada vinc_exec,a2.adm_central adm_exec,
                a3.sq_pessoa tit_exec,a4.sq_pessoa subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.data_hora,                   b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.sq_unidade_resp,    d.titulo,                      d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                d.vincula_contrato,   d.vincula_viagem,              d.sq_tipo_pessoa,
                d.outra_parte,        d.preposto,                    d.limite_passagem,
                d.sq_cidade cidade_evento, d2.sq_pais pais_evento,   d2.co_uf uf_evento,
                d1.nome nm_prop,      d1.nome_resumido nm_prop_res,
                decode(upper(d3.nome),'BRASIL',d2.nome||'-'||d2.co_uf||' ('||d3.nome||')',d2.nome||' ('||d3.nome||')') nm_cidade_evento,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sq_unidade,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.nome_resumido nm_sol,
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
                m.sq_acordo,          m.cd_acordo,                   m.nm_acordo
           from siw_menu                                        a,
                eo_unidade                a2,
                eo_unidade_resp           a3,
                eo_unidade_resp           a4,
                siw_modulo           a1,
                siw_solicitacao      b ,
                siw_tramite          b1,
                pj_projeto           d ,
                co_pessoa            d1,
                co_cidade            d2,
                co_pais              d3,
                eo_unidade           e ,
                eo_unidade_resp      e1,
                eo_unidade_resp      e2,
                or_acao              i ,
                or_acao_ppa          j ,
                or_acao_ppa          k ,
                or_prioridade        l ,
                co_pessoa            f ,
                co_cidade            h ,
                co_uf                h1,
                ct_cc                g ,
                eo_unidade           c,
                (select x.sq_siw_solicitacao sq_acordo, x.codigo_interno cd_acordo,
                        w.nome_resumido||' - '||z.nome||' ('||to_char(x.inicio,'dd/mm/yyyy')||'-'||to_char(x.fim,'dd/mm/yyyy')||')' as nm_acordo
                   from ac_acordo       x,
                        co_pessoa       w,
                        siw_solicitacao y, 
                        ct_cc           z
                  where (x.outra_parte        = w.sq_pessoa)
                    and (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                    and (y.sq_cc              = z.sq_cc)
                )                    m
          where (a.sq_unid_executora        = a2.sq_unidade)
            and (a2.sq_unidade              = a3.sq_unidade (+) and
                 a3.tipo_respons (+)        = 'T'           and
                 a3.fim (+)                 is null
                )
            and (a2.sq_unidade              = a4.sq_unidade (+) and
                 a4.tipo_respons (+)        = 'S'           and
                 a4.fim (+)                 is null
                )
            and (a.sq_modulo           = a1.sq_modulo)
            and (a.sq_menu             = b.sq_menu)
            and (b.sq_siw_tramite      = b1.sq_siw_tramite)
            and (b.sq_siw_solicitacao  = d.sq_siw_solicitacao)
            and (d.outra_parte         = d1.sq_pessoa (+))
            and (d.sq_cidade           = d2.sq_cidade (+))
            and (d2.sq_pais            = d3.sq_pais (+))
            and (d.sq_unidade_resp     = e.sq_unidade)
            and (e.sq_unidade               = e1.sq_unidade (+) and
                 e1.tipo_respons (+)        = 'T'           and
                 e1.fim (+)                 is null
                )
            and (e.sq_unidade               = e2.sq_unidade (+) and
                 e2.tipo_respons (+)        = 'S'           and
                 e2.fim (+)                 is null
                )
            and (d.sq_siw_solicitacao  = i.sq_siw_solicitacao (+))
            and (i.sq_acao_ppa         = j.sq_acao_ppa (+))
            and (j.sq_acao_ppa_pai     = k.sq_acao_ppa (+))
            and (i.sq_orprioridade     = l.sq_orprioridade (+))
            and (b.solicitante         = f.sq_pessoa)
            and (b.sq_cidade_origem    = h.sq_cidade)
            and (h.co_uf               = h1.co_uf and
                 h.sq_pais             = h1.sq_pais
                )
            and (b.sq_cc               = g.sq_cc (+))
            and (a.sq_unid_executora   = c.sq_unidade (+))
            and (b.sq_solic_pai        = m.sq_acordo (+))
            and b.sq_siw_solicitacao       = p_chave;
   Elsif substr(p_restricao,1,2) = 'GC' Then
      -- Recupera os acordos que o usuário pode ver
      open p_result for
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               a.acompanha_fases,
                a.sq_unid_executora,  a.finalidade,                  a.arquivo_proced,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.data_hora,          a.envia_dia_util,              a.descricao,
                a.justificativa,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec, a2.informal informal_exec,
                a2.vinculada vinc_exec,a2.adm_central adm_exec,
                a3.sq_pessoa tit_exec,a4.sq_pessoa subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.data_hora,                   b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.sq_tipo_acordo,     d.outra_parte,                 d.preposto,
                d.inicio inicio_real, d.fim fim_real,                d.duracao,
                d.valor_inicial,      d.valor_atual,                 d.codigo_interno,
                d.codigo_externo,     d.objeto,                      d.atividades,
                d.produtos,           d.requisitos,                  d.observacao,
                d.dia_vencimento,     d.vincula_projeto,             d.vincula_demanda,
                d.vincula_viagem,     d.aviso_prox_conc,             d.dias_aviso,
                decode(d.vincula_projeto,'S','Sim','Não') nm_vincula_projeto,
                decode(d.vincula_demanda,'S','Sim','Não') nm_vincula_demanda,
                decode(d.vincula_viagem ,'S','Sim','Não') nm_vincula_viagem,
                d.sq_tipo_pessoa,     d.sq_forma_pagamento,          d.sq_agencia,
                d.operacao_conta,     d.numero_conta,                d.sq_pais_estrang,
                d.aba_code,           d.swift_code,                  d.endereco_estrang,
                d.banco_estrang,      d.agencia_estrang,             d.cidade_estrang,
                d.inicio,             d.informacoes,                 d.codigo_deposito,
                d1.nome nm_tipo_acordo,d1.sigla sg_acordo,           d1.modalidade cd_modalidade,
                d1.prazo_indeterm,    d1.pessoa_fisica,              d1.pessoa_juridica,
                d2.nome nm_outra_parte, d2.nome_resumido nm_outra_parte_resumido,
                d3.nome nm_outra_parte, d3.nome_resumido nm_outra_parte_resumido,
                d4.nome nm_forma_pagamento, d4.sigla sg_forma_pagamento, d4.ativo st_forma_pagamento,
                d5.codigo cd_agencia, d5.nome nm_agencia,
                d6.sq_banco,          d6.codigo cd_banco,            d6.nome nm_banco,
                d7.nome nm_pais,
                b.fim-d.dias_aviso aviso,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                f.nome nm_cidade,
                m.titulo nm_projeto,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                p.nome_resumido nm_exec
           from siw_menu                                       a,
                eo_unidade                a2,
                eo_unidade_resp           a3,
                eo_unidade_resp           a4,
                siw_modulo           a1,
                siw_solicitacao      b ,
                siw_tramite          b1,
                ac_acordo            d ,
                ac_tipo_acordo       d1,
                co_forma_pagamento   d4,
                co_pessoa            d2,
                co_pessoa            d3,
                co_agencia           d5,
                co_banco             d6,
                co_pais              d7,
                eo_unidade           e ,
                eo_unidade_resp      e1,
                eo_unidade_resp      e2,
                co_cidade            f ,
                pj_projeto           m ,
                ct_cc                n ,
                co_pessoa            o ,
                sg_autenticacao      o1,
                eo_unidade           o2,
                co_pessoa            p ,
                eo_unidade           c ,
                (select sq_siw_solicitacao, max(sq_siw_solic_log) chave
                   from siw_solic_log
                 group by sq_siw_solicitacao
               )                    j ,
                gd_demanda_log       k ,
                sg_autenticacao      l
          where (a.sq_unid_executora        = a2.sq_unidade)
            and (a2.sq_unidade              = a3.sq_unidade (+) and
                 a3.tipo_respons (+)        = 'T'           and
                 a3.fim (+)                 is null
                )
            and (a2.sq_unidade              = a4.sq_unidade (+) and
                 a4.tipo_respons (+)        = 'S'           and
                 a4.fim (+)                 is null
                )
            and (a.sq_modulo                = a1.sq_modulo)
            and (a.sq_menu                  = b.sq_menu)
            and (b.sq_siw_tramite           = b1.sq_siw_tramite)
            and (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
            and (d.sq_tipo_acordo           = d1.sq_tipo_acordo)
            and (d.sq_forma_pagamento       = d4.sq_forma_pagamento)
            and (d.outra_parte              = d2.sq_pessoa (+))
            and (d.preposto                 = d3.sq_pessoa (+))
            and (d.sq_agencia               = d5.sq_agencia (+))
            and (d5.sq_banco                = d6.sq_banco (+))
            and (d.sq_pais_estrang          = d7.sq_pais (+))
            and (b.sq_unidade               = e.sq_unidade)
            and (e.sq_unidade               = e1.sq_unidade (+) and
                 e1.tipo_respons (+)        = 'T'           and
                 e1.fim (+)                 is null
                )
            and (e.sq_unidade               = e2.sq_unidade (+) and
                 e2.tipo_respons (+)        = 'S'           and
                 e2.fim (+)                 is null
                )
            and (b.sq_cidade_origem         = f.sq_cidade)
            and (b.sq_solic_pai             = m.sq_siw_solicitacao (+))
            and (b.sq_cc                    = n.sq_cc (+))
            and (b.solicitante              = o.sq_pessoa (+))
            and (o.sq_pessoa                = o1.sq_pessoa)
            and (o1.sq_unidade              = o2.sq_unidade)
            and (b.executor                 = p.sq_pessoa (+))
            and (a.sq_unid_executora        = c.sq_unidade (+))
            and (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
            and (j.chave                    = k.sq_siw_solic_log (+))
            and (k.destinatario             = l.sq_pessoa (+))
            and b.sq_siw_solicitacao       = p_chave;
   Elsif substr(p_restricao,1,2) = 'FN' Then
      -- Recupera os acordos que o usuário pode ver
      open p_result for
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               a.acompanha_fases,
                a.sq_unid_executora,  a.finalidade,                  a.arquivo_proced,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.data_hora,          a.envia_dia_util,              a.descricao,
                a.justificativa,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec, a2.informal informal_exec,
                a2.vinculada vinc_exec,a2.adm_central adm_exec,
                a3.sq_pessoa tit_exec,a4.sq_pessoa subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.data_hora,          b.opiniao,                     b.sq_solic_pai,
                b.sq_unidade,         b.sq_cidade_origem,            b.palavra_chave,
                b.valor,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.pessoa,             d.codigo_interno,              d.sq_acordo_parcela,
                d.sq_forma_pagamento, d.sq_tipo_lancamento,          d.sq_tipo_pessoa,
                d.emissao,            d.vencimento,                  d.quitacao,
                d.codigo_externo,     d.observacao,
                d.aviso_prox_conc,
                d.dias_aviso,         d.sq_forma_pagamento,          d.sq_agencia,
                d.operacao_conta,     d.numero_conta,                d.sq_pais_estrang,
                d.aba_code,           d.swift_code,                  d.endereco_estrang,
                d.banco_estrang,      d.agencia_estrang,             d.cidade_estrang,
                d.informacoes,        d.codigo_deposito,
                d.valor_imposto,      d.valor_retencao,              d.valor_liquido,
                d1.receita,           d1.despesa,                    d1.nome nm_tipo_lancamento,
                d2.nome nm_pessoa, d2.nome_resumido nm_pessoa_resumido,
                Nvl(d3.valor,0) valor_doc,
                d4.nome nm_forma_pagamento, d4.sigla sg_forma_pagamento, d4.ativo st_forma_pagamento,
                d5.codigo cd_agencia, d5.nome nm_agencia,
                d6.sq_banco,          d6.codigo cd_banco,            d6.nome nm_banco,
                d7.nome nm_pais,
                d8.nome nm_tipo_pessoa,
                Nvl(d9.valor_total,0) valor_total,
                b.fim-d.dias_aviso aviso,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                f.nome nm_cidade,
                m.codigo_interno cd_acordo,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                p.nome_resumido nm_exec,
                q.titulo nm_projeto
           from siw_menu                                       a,
                eo_unidade                a2,
                eo_unidade_resp           a3,
                eo_unidade_resp           a4,
                siw_modulo           a1,
                siw_solicitacao      b ,
                siw_tramite          b1,
                fn_lancamento        d ,
                fn_tipo_lancamento   d1,
                co_forma_pagamento   d4,
                co_tipo_pessoa       d8,
                (select x.sq_siw_solicitacao, sum(Nvl(x.valor,0)) valor
                   from fn_lancamento_doc x
                 group by x.sq_siw_solicitacao
                )                    d3,
                co_pessoa            d2,
                co_agencia           d5,
                co_banco             d6,
                co_pais              d7,
                (select sq_siw_solicitacao, Nvl(sum(distinct(valor)),0) valor_total
                   from fn_lancamento_doc
                 group by sq_siw_solicitacao
                )                    d9,
                eo_unidade           e ,
                eo_unidade_resp      e1,
                eo_unidade_resp      e2,
                co_cidade            f ,
                ac_acordo            m ,
                pj_projeto           q ,
                ct_cc                n ,
                co_pessoa            o ,
                sg_autenticacao      o1,
                eo_unidade           o2,
                co_pessoa            p ,
                eo_unidade           c ,
                (select sq_siw_solicitacao, max(sq_siw_solic_log) chave
                   from siw_solic_log
                 group by sq_siw_solicitacao
                )                    j ,
                gd_demanda_log       k ,
                sg_autenticacao      l 
          where (a.sq_unid_executora        = a2.sq_unidade)
            and (a2.sq_unidade              = a3.sq_unidade (+) and
                 a3.tipo_respons (+)        = 'T'           and
                 a3.fim (+)                 is null
                )
            and (a2.sq_unidade              = a4.sq_unidade (+) and
                 a4.tipo_respons (+)        = 'S'           and
                 a4.fim (+)                 is null
                )
            and (a.sq_modulo                = a1.sq_modulo)
            and (a.sq_menu                  = b.sq_menu)
            and (b.sq_siw_tramite           = b1.sq_siw_tramite)
            and (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
            and (d.sq_tipo_lancamento       = d1.sq_tipo_lancamento)
            and (d.sq_forma_pagamento       = d4.sq_forma_pagamento)
            and (d.sq_tipo_pessoa           = d8.sq_tipo_pessoa)
            and (d.sq_siw_solicitacao       = d3.sq_siw_solicitacao (+))
            and (d.pessoa                   = d2.sq_pessoa (+))
            and (d.sq_agencia               = d5.sq_agencia (+))
            and (d5.sq_banco                = d6.sq_banco (+))
            and (d.sq_pais_estrang          = d7.sq_pais (+))
            and (d.sq_siw_solicitacao       = d9.sq_siw_solicitacao (+))
            and (b.sq_unidade               = e.sq_unidade)
            and (e.sq_unidade               = e1.sq_unidade (+) and
                 e1.tipo_respons (+)        = 'T'           and
                 e1.fim (+)                 is null
                )
            and (e.sq_unidade               = e2.sq_unidade (+) and
                 e2.tipo_respons (+)        = 'S'           and
                 e2.fim (+)                 is null
                )
            and (b.sq_cidade_origem         = f.sq_cidade)
            and (b.sq_solic_pai             = m.sq_siw_solicitacao (+))
            and (b.sq_solic_pai             = q.sq_siw_solicitacao (+))
            and (b.sq_cc                    = n.sq_cc (+))
            and (b.solicitante              = o.sq_pessoa (+))
            and (o.sq_pessoa                = o1.sq_pessoa)
            and (o1.sq_unidade              = o2.sq_unidade)
            and (b.executor                 = p.sq_pessoa (+))
            and (a.sq_unid_executora        = c.sq_unidade)
            and (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
            and (j.chave                    = k.sq_siw_solic_log (+))
            and (k.destinatario             = l.sq_pessoa (+))
            and b.sq_siw_solicitacao       = p_chave;
   Elsif substr(p_restricao,1,2) = 'PD' Then
      -- Recupera as tarefas que o usuário pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               a.acompanha_fases,
                a.sq_unid_executora,  a.finalidade,                  a.arquivo_proced,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.data_hora,          a.envia_dia_util,              a.descricao,
                a.justificativa justif_solic,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec,       a2.informal informal_exec,
                a2.vinculada vinc_exec,a2.adm_central adm_exec,
                a3.sq_pessoa tit_exec, a31.nome nm_tit_exec,
                a4.sq_pessoa subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.data_hora,                   b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.sq_unidade_resp,    d.assunto,                     d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                decode(d.prioridade,0,'Alta',1,'Média','Normal') nm_prioridade,
                d.ordem,
                d1.sq_pessoa sq_prop, d1.tipo tp_missao,             d1.codigo_interno,
                decode(d1.tipo,'I','Inicial','P','Prorrogação','C','Complementação') nm_tipo_missao,
                d1.reserva,           d1.pta,                        d1.justificativa_dia_util,
                d1.emissao_bilhete,   d1.pagamento_diaria,           d1.pagamento_bilhete,
                d1.boletim_numero,    d1.boletim_data,               d1.valor_alimentacao,
                d1.valor_transporte,  d1.desconto_alimentacao,       d1.desconto_transporte,
                d1.valor_adicional,   d1.codigo_externo,             d1.tipo tipo_missao,
                d1.sq_pais_estrang,   d1.aba_code,                   d1.swift_code,
                d1.endereco_estrang,  d1.banco_estrang,              d1.agencia_estrang,
                d1.cidade_estrang,    d1.informacoes,                d1.codigo_deposito,
                d1.numero_conta,      d1.operacao_conta,
                d1.valor_passagem,    
                d2.nome nm_prop,      d2.nome_resumido nm_prop_res,  
                d3.sq_tipo_vinculo,   d3.nome nm_tipo_vinculo,
                d4.sexo,              d4.cpf,                        
                d6.sq_agencia,        d6.codigo cd_agencia,          d6.nome nm_agencia,
                d7.sq_banco,          d7.codigo cd_banco,            d7.nome nm_banco,
                d8.sq_posto_trabalho, d8.sq_posto_trabalho,          d8.sq_modalidade_contrato,
                d8.matricula,
                b.fim-d.dias_aviso aviso,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,       e12.nome nm_titular,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                p.nome_resumido nm_exec
           from siw_menu                                       a,
                eo_unidade                a2,
                eo_unidade_resp           a3,
                co_pessoa                 a31,
                eo_unidade_resp           a4,
                siw_modulo           a1,
                siw_solicitacao      b,
                siw_tramite          b1,
                gd_demanda           d,
                pd_missao            d1,
                co_pessoa            d2,
                co_tipo_vinculo      d3,
                co_pessoa_fisica     d4,
                co_agencia           d6,
                co_banco             d7,
                gp_contrato_colaborador d8,
                eo_unidade           e,
                eo_unidade_resp      e1,
                co_pessoa            e12,
                eo_unidade_resp      e2,
                co_cidade            f,
                co_pessoa            o,
                sg_autenticacao      o1,
                eo_unidade           o2,
                co_pessoa            p,
                eo_unidade           c,
                (select sq_siw_solicitacao, max(sq_siw_solic_log) chave 
                   from siw_solic_log
                 group by sq_siw_solicitacao
                )                    j,
                gd_demanda_log       k,
                sg_autenticacao      l
          where (a.sq_unid_executora        = a2.sq_unidade)
            and (a2.sq_unidade              = a3.sq_unidade (+) and
                 a3.tipo_respons (+)            = 'T'           and
                 a3.fim (+)                     is null)
            and (a2.sq_unidade              = a4.sq_unidade (+) and
                 a4.tipo_respons (+)            = 'S'           and
                 a4.fim (+)                     is null)
            and (a3.sq_pessoa               = a31.sq_pessoa (+))
            and (a.sq_modulo                = a1.sq_modulo)
            and (a.sq_menu                  = b.sq_menu)
            and (b.sq_siw_tramite           = b1.sq_siw_tramite)
            and (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
            and (d.sq_siw_solicitacao       = d1.sq_siw_solicitacao)
            and (d1.sq_pessoa               = d2.sq_pessoa)
            and (d2.sq_tipo_vinculo         = d3.sq_tipo_vinculo)
            and (d2.sq_pessoa               = d4.sq_pessoa)
            and (d1.sq_agencia              = d6.sq_agencia (+))
            and (d6.sq_banco                = d7.sq_banco (+))
            and (d4.cliente                 = d8.cliente (+) and
                 d4.sq_pessoa               = d8.sq_pessoa (+) and
                 d8.fim (+)                 is null
                )
            and (d.sq_unidade_resp          = e.sq_unidade)
            and (e.sq_unidade               = e1.sq_unidade (+) and
                 e1.tipo_respons (+)            = 'T'           and
                 e1.fim (+)                     is null)
            and (e1.sq_pessoa               = e12.sq_pessoa (+))
            and (e.sq_unidade               = e2.sq_unidade (+) and
                 e2.tipo_respons (+)            = 'S'           and
                 e2.fim (+)                     is null)
            and (b.sq_cidade_origem         = f.sq_cidade)
            and (b.solicitante              = o.sq_pessoa (+))
            and (o.sq_pessoa                = o1.sq_pessoa)
            and (o1.sq_unidade              = o2.sq_unidade)
            and (b.executor                 = p.sq_pessoa (+))
            and (a.sq_unid_executora        = c.sq_unidade (+))
            and (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
            and (j.chave                    = k.sq_siw_solic_log (+))
            and (k.destinatario             = l.sq_pessoa (+))
            and b.sq_siw_solicitacao = p_chave;
   End If;
end SP_GetSolicData;
/
