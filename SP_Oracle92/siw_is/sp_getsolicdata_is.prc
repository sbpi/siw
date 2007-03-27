create or replace procedure SP_GetSolicData_IS
   (p_chave     in number,
    p_restricao in varchar2,
    p_result    out sys_refcursor) is
begin
   If p_restricao = 'ISACGERAL' or p_restricao = 'ISACRESTR'  or p_restricao = 'ISACPROQUA' 
   or p_restricao = 'ISACRESP'  or p_restricao = 'ISACVISUAL' or p_restricao = 'ISACPROFIN'
   or p_restricao = 'ISMETA'    or p_restricao = 'ISACINTERE' or p_restricao = 'ISACANEXO'
   or p_restricao = 'VLRAGERAL' Then   
      -- Recupera as ac?es que o usuario pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,
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
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sq_unidade,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.nome_resumido nm_sol, f.nome nm_sol_comp,
                g.sq_cc,              g.nome cc_nome,                g.sigla cc_sigla,
                h.sq_pais,            h.sq_regiao,                   h.co_uf,
                h.nome nm_cidade,
                i.cd_acao,            i.cd_subacao,                  j.descricao_acao nm_ppa,
                j.descricao_subacao,  j.cd_unidade,                  j.cd_tipo_acao,   
                j.valor_ano_anterior, j.cd_orgao,   
                j1.nome nm_orgao,     
                i.nm_coordenador resp_ppa,                           i.sq_unidade sq_unidade_adm,
                i.fn_coordenador fone_ppa, i.em_coordenador mail_ppa,i.selecao_mp mpog_ppa,
                i.cd_programa sq_acao_ppa_pai, i.cd_programa,        i.selecao_se relev_ppa,
                i.selecao_mp mpog_ppa_pai,                           i.selecao_se relev_ppa_pai,
                i.sq_unidade sq_unidade_adm,                         i.nm_coordenador problema, 
                i.problema,           i.publico_alvo,                i.estrategia,                  
                i.objetivo,           i.sistematica,                 i.metodologia,
                i1.nome nm_unidade_adm,
                m.cd_programa cd_ppa_pai,  m.nome nm_ppa_pai,        m.ln_programa,
                k.nm_gerente_adjunto resp_ppa_pai,                   k.fn_gerente_adjunto fone_ppa_pai,                   
                k.em_gerente_adjunto mail_ppa_pai,
                k.nm_gerente_programa,     k.fn_gerente_programa,    k.em_gerente_programa,
                k.nm_gerente_executivo,    k.fn_gerente_executivo,   k.em_gerente_executivo,
                k.nm_gerente_adjunto,      k.fn_gerente_adjunto,     k.em_gerente_adjunto,
                l.sq_isprojeto,
                l.codigo cd_pri,      l.nome nm_pri,                 l.responsavel resp_pri,
                l.telefone fone_pri,  l.email mail_pri,              l.ordem ord_pri,
                l.ativo ativo_pri,    l.padrao padrao_pri,           n.nome ds_unidade,
                o.descricao,          o.observacao observacao_ppa,   o.reperc_financeira,
                o.valor_reperc_financeira, o.base_legal,             o.finalidade,
                o.descricao descricao_ppa, o.direta,                 o.descentralizada,
                o.linha_credito,      o.transf_obrigatoria,          o.transf_voluntaria, 
                o.transf_outras,      o.detalhamento,
                o1.nome nm_tipo_inclusao_acao
           from siw.siw_menu                                 a 
                inner        join siw.eo_unidade             a2 on (a.sq_unid_executora        = a2.sq_unidade)
                  left outer join siw.eo_unidade_resp        a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                                    a3.tipo_respons            = 'T'           and
                                                                    a3.fim                     is null
                                                                   )
                  left outer join siw.eo_unidade_resp        a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                    a4.tipo_respons            = 'S'           and
                                                                    a4.fim                     is null
                                                                   ) 
                inner              join siw.siw_modulo       a1 on (a.sq_modulo           = a1.sq_modulo)
                inner              join siw.siw_solicitacao  b  on (a.sq_menu             = b.sq_menu)
                  inner            join siw.siw_tramite      b1 on (b.sq_siw_tramite      = b1.sq_siw_tramite)
                  inner            join siw.pj_projeto       d  on (b.sq_siw_solicitacao  = d.sq_siw_solicitacao)
                    inner          join siw.eo_unidade       e  on (d.sq_unidade_resp     = e.sq_unidade)
                      left outer join siw.eo_unidade_resp    e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                    e1.tipo_respons            = 'T'           and
                                                                    e1.fim                     is null
                                                                   )
                      left outer join siw.eo_unidade_resp    e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                    e2.tipo_respons            = 'S'           and
                                                                    e2.fim                     is null
                                                                   )
                    left outer     join is_acao              i  on (d.sq_siw_solicitacao  = i.sq_siw_solicitacao)
                      left outer   join siw.eo_unidade       i1 on (i.sq_unidade          = i1.sq_unidade)
                      left outer   join is_sig_acao          j  on (i.cd_acao             = j.cd_acao              and
                                                                    i.cd_programa         = j.cd_programa          and
                                                                    i.cd_subacao          = j.cd_subacao           and
                                                                    i.ano                 = j.ano                  and
                                                                    i.cliente             = j.cliente)
                        left outer join is_sig_orgao         j1 on (j.cd_orgao            = j1.cd_orgao and
                                                                    j.cd_tipo_orgao       = j1.cd_tipo_orgao and
                                                                    j.ano                 = j1.ano
                                                                   )
                        left outer join is_ppa_acao          o  on (j.cd_programa         = o.cd_programa          and
                                                                    j.cd_acao             = o.cd_acao              and
                                                                    j.cd_unidade          = o.cd_unidade           and
                                                                    j.cliente             = o.cliente              and
                                                                    j.ano                 = o.ano)
                          left outer join is_tipo_inclusao_acao o1 on (o.cd_tipo_inclusao    = o1.cd_tipo_inclusao)
                        left outer join is_sig_unidade       n  on (j.cd_unidade          = n.cd_unidade           and
                                                                    j.ano                 = n.ano)
                        left outer join is_programa          k  on (j.cd_programa         = k.cd_programa          and
                                                                    j.ano                 = k.ano                  and
                                                                    j.cliente             = k.cliente)
                        left outer join is_sig_programa      m  on (j.cd_programa         = m.cd_programa          and
                                                                    j.ano                 = m.ano                  and
                                                                    j.cliente             = m.cliente)
                      left outer   join is_projeto           l  on (i.sq_isprojeto        = l.sq_isprojeto         and
                                                                    i.cliente             = l.cliente)
                  inner            join siw.co_pessoa        f  on (b.solicitante         = f.sq_pessoa)
                  inner            join siw.co_cidade        h  on (b.sq_cidade_origem    = h.sq_cidade)
                  left outer       join siw.ct_cc            g  on (b.sq_cc               = g.sq_cc)
                left outer         join siw.eo_unidade       c  on (a.sq_unid_executora   = c.sq_unidade)
          where b.sq_siw_solicitacao       = p_chave;
   Elsif p_restricao = 'ISPRGERAL'  or p_restricao = 'ISPRRESP'  or  p_restricao = 'ISPRVISUAL' or
         p_restricao = 'ISPRPROQUA' or p_restricao = 'ISPRINDIC' or  p_restricao = 'ISPRRESTR'  or
         p_restricao = 'ISPRINTERE' or p_restricao = 'ISPRANEXO' or  p_restricao = 'VLRPGERAL' Then
      -- Recupera as ac?es que o usuario pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,
                a.sq_unid_executora,  a.finalidade,                  a.arquivo_proced,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.data_hora,          a.envia_dia_util,              a.descricao,
                a.justificativa,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec,       a2.informal informal_exec,
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
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sq_unidade,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.nome_resumido nm_sol, f.nome nm_sol_comp,
                h.sq_pais,            h.sq_regiao,                   h.co_uf,
                h.nome nm_cidade,     i.sq_unidade sq_unidade_adm,   i.potencialidades,
                i.contribuicao_objetivo, i.diretriz,                 i.estrategia_monit,
                i.metodologia_aval,
                i.cd_programa,        j.nome ds_programa,            i.nm_gerente_programa,
                i.fn_gerente_programa, i.em_gerente_programa,        i.nm_gerente_executivo,
                i.fn_gerente_executivo, i.em_gerente_executivo,      i.nm_gerente_adjunto,
                i.fn_gerente_adjunto, i.em_gerente_adjunto,          i.sq_natureza,
                i.sq_horizonte,       i.selecao_mp mpog_ppa,         i.selecao_se relev_ppa,
                i1.nome nm_unidade_adm,                              i.sq_unidade sq_unidade_adm,
                i2.nome nm_natureza,  i3.nome nm_horizonte,
                j.ln_programa,        j.valor_estimado,              j.valor_ppa,
                j.contexto,           j.justificativa justificativa_sigplan,       
                j.objetivo,           
                j.publico_alvo,       j.estrategia,                  
                j1.nome nm_orgao,
                j2.nome nm_tipo_programa,
                k.observacao observacoes_ppa
           from siw.siw_menu                                     a 
                inner              join siw.eo_unidade           a2 on (a.sq_unid_executora   = a2.sq_unidade)
                  left outer       join siw.eo_unidade_resp      a3 on (a2.sq_unidade         = a3.sq_unidade and
                                                                        a3.tipo_respons       = 'T'           and
                                                                        a3.fim                is null
                                                                       )
                  left outer       join siw.eo_unidade_resp      a4 on (a2.sq_unidade         = a4.sq_unidade and
                                                                        a4.tipo_respons       = 'S'           and
                                                                        a4.fim                is null
                                                                       ) 
                inner              join siw.siw_modulo           a1 on (a.sq_modulo           = a1.sq_modulo)
                inner              join siw.siw_solicitacao      b  on (a.sq_menu             = b.sq_menu)
                  inner            join siw.siw_tramite          b1 on (b.sq_siw_tramite      = b1.sq_siw_tramite)
                  inner            join siw.pj_projeto           d  on (b.sq_siw_solicitacao  = d.sq_siw_solicitacao)
                    inner          join siw.eo_unidade           e  on (d.sq_unidade_resp     = e.sq_unidade)
                      left outer   join siw.eo_unidade_resp      e1 on (e.sq_unidade          = e1.sq_unidade and
                                                                        e1.tipo_respons       = 'T'           and
                                                                        e1.fim                is null
                                                                       )
                      left outer   join siw.eo_unidade_resp      e2 on (e.sq_unidade          = e2.sq_unidade and
                                                                        e2.tipo_respons       = 'S'           and
                                                                        e2.fim                is null
                                                                       )
                    left outer     join is_programa              i  on (d.sq_siw_solicitacao  = i.sq_siw_solicitacao)
                        left outer join siw.eo_unidade           i1 on (i.sq_unidade          = i1.sq_unidade)
                        left outer join is_natureza              i2 on (i.sq_natureza         = i2.sq_natureza and
                                                                        i.cliente             = i2.cliente
                                                                       )
                        left outer join is_horizonte             i3 on (i.sq_horizonte        = i3.sq_horizonte and
                                                                        i.cliente             = i3.cliente
                                                                       )
                        left outer join is_sig_programa          j  on (i.cd_programa         = j.cd_programa          and
                                                                        i.ano                 = j.ano                  and
                                                                        i.cliente             = j.cliente
                                                                       )
                           left outer join is_sig_orgao          j1 on (j.cd_orgao            = j1.cd_orgao and
                                                                        j.cd_tipo_orgao       = j1.cd_tipo_orgao and
                                                                        j.ano                 = j1.ano
                                                                       )
                           left outer join is_sig_tipo_programa     j2 on (j.cd_tipo_programa    = j2.cd_tipo_programa)
                        left outer join is_ppa_programa          k  on (i.cd_programa         = k.cd_programa          and
                                                                        i.ano                 = k.ano                  and
                                                                        i.cliente             = k.cliente
                                                                       )
                  inner            join siw.co_pessoa            f  on (b.solicitante         = f.sq_pessoa)
                  inner            join siw.co_cidade            h  on (b.sq_cidade_origem    = h.sq_cidade)
                left outer         join siw.eo_unidade           c  on (a.sq_unid_executora   = c.sq_unidade)
          where b.sq_siw_solicitacao       = p_chave;
   ElsIf p_restricao = 'ISTAGERAL' or p_restricao = 'ISTARESP' or p_restricao = 'VLRTGERAL' Then
      -- Recupera as tarefas que o usuario pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,
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
                d1.titulo,            d1.nm_responsavel,             d1.fn_responsavel,
                d1.em_responsavel,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.nome_resumido nm_sol, f.nome nm_sol_comp,
                h.sq_pais,            h.sq_regiao,                   h.co_uf,
                h.nome nm_cidade,
                k.titulo nm_projeto,
                d2.limite_orcamento,  l.cd_acao                
           from siw.siw_menu                                a
                inner        join siw.eo_unidade            a2 on (a.sq_unid_executora        = a2.sq_unidade)
                  left outer join siw.eo_unidade_resp       a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                                   a3.tipo_respons            = 'T'           and
                                                                   a3.fim                     is null
                                                                  )
                  left outer join siw.eo_unidade_resp       a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                   a4.tipo_respons            = 'S'           and
                                                                   a4.fim                     is null
                                                                  ) 
                inner        join siw.siw_modulo            a1 on (a.sq_modulo           = a1.sq_modulo)
                inner        join siw.siw_solicitacao       b  on (a.sq_menu             = b.sq_menu)
                  inner      join siw.siw_tramite           b1 on (b.sq_siw_tramite      = b1.sq_siw_tramite)
                  inner      join siw.gd_demanda            d  on (b.sq_siw_solicitacao  = d.sq_siw_solicitacao)
                    left outer join is_tarefa               d1 on (d.sq_siw_solicitacao  = d1.sq_siw_solicitacao)
                    left outer join (select x.sq_siw_solicitacao, z.limite_orcamento 
                                       from siw.siw_solicitacao            x
                                            inner join siw.gd_demanda      y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                            inner join is_unidade_limite   z on (y.sq_unidade_resp    = z.sq_unidade)
                  where x.ano                = z.ano
                    and x.sq_siw_solicitacao = p_chave
                                    )                         d2 on (d.sq_siw_solicitacao = d2.sq_siw_solicitacao)
                    inner    join siw.eo_unidade            e  on (d.sq_unidade_resp     = e.sq_unidade)
                      left outer join siw.eo_unidade_resp   e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                   e1.tipo_respons            = 'T'           and
                                                                   e1.fim                     is null
                                                                  )
                      left outer join siw.eo_unidade_resp   e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                   e2.tipo_respons            = 'S'           and
                                                                   e2.fim                     is null
                                                                  )
                  inner      join siw.co_pessoa             f  on (b.solicitante         = f.sq_pessoa)
                  inner      join siw.co_cidade             h  on (b.sq_cidade_origem    = h.sq_cidade)
                left outer   join siw.eo_unidade            c  on (a.sq_unid_executora   = c.sq_unidade)
                left outer   join siw.pj_etapa_demanda      i  on (b.sq_siw_solicitacao  = i.sq_siw_solicitacao)
                left outer   join siw.pj_projeto            k  on (b.sq_solic_pai        = k.sq_siw_solicitacao)
                left outer   join is_acao                   l  on (b.sq_solic_pai        = l.sq_siw_solicitacao)  
          where b.sq_siw_solicitacao       = p_chave;
   End If;
end SP_GetSolicData_IS;
/
