create or replace procedure SP_GetSolicEmail (
   p_cliente      in  number,
   p_result       out siw.sys_refcursor
  ) is
begin
   -- Recupera as demandas cujo alerta deve ser comunicado por e-mail
   open p_result for
     select a.nome, a.sigla, a1.sigla sg_modulo, a2.nome nm_unidade_exec,
            a6.nome nm_tit_exec, a5.email em_tit_exec, a8.nome nm_sub_exec, a7.email em_sub_exec,
            b.sq_siw_solicitacao, b.sq_solic_pai, b.descricao, b.inicio, b.fim,
            b1.nome nm_tramite, b1.ordem or_tramite, b1.sigla sg_tramite,
            d.assunto, d.aviso_prox_conc,  d.dias_aviso, d.proponente,
            decode(d.prioridade,0,'Alta',1,'Média','Normal') nm_prioridade,
            b.fim-d.dias_aviso aviso, e.nome nm_unidade_resp, e.sigla sg_unidade_resp,
            e4.nome nm_tit_resp, e3.email em_tit_resp, e6.nome nm_sub_resp, e5.email em_sub_resp,
            m.titulo nm_projeto, n.nome nm_cc,
            q.titulo nm_etapa, MontaOrdem(q.sq_projeto_etapa) cd_ordem,
            o.nome nm_solic, o1.email em_solic,
            o.nome_resumido||' ('||o2.sigla||')' nm_resp,
            p.nome_resumido||' ('||u.sigla||')' nm_exec, t.email em_exec,
            decode(sign(b.fim - sysdate),-1,trunc(sysdate-b.fim),-1) dias_atraso,
            ceil(b.fim-sysdate) dias_fim,
            r2.codigo cd_programa, r2.nome nm_programa,
            r1.codigo cd_acao,     r1.nome nm_acao,
            r4.codigo cd_pri,      r4.nome nm_pri
       from siw_menu                                       a,
            eo_unidade                a2,
            eo_unidade_resp           a3,
            sg_autenticacao         a5,
            co_pessoa               a6,
            eo_unidade_resp           a4,
            sg_autenticacao         a7,
            co_pessoa               a8,
            siw_modulo           a1,
            siw_solicitacao      b ,
            siw_tramite          b1,
            gd_demanda           d ,
            eo_unidade           e ,
            eo_unidade_resp      e1,
            sg_autenticacao    e3,
            co_pessoa          e4,
            eo_unidade_resp      e2,
            sg_autenticacao    e5,
            co_pessoa          e6,
            siw_solicitacao      r3,
            or_acao              r ,
            or_acao_ppa          r1,
            or_acao_ppa        r2,
            or_prioridade        r4,
            pj_projeto           m ,
            ct_cc                n ,
            co_pessoa            o ,
            sg_autenticacao      o1,
            eo_unidade           o2,
            co_pessoa            p ,
            sg_autenticacao      t ,
            eo_unidade           u ,
            eo_unidade           c ,
            pj_etapa_demanda     i ,
            pj_projeto_etapa     q
      where (a.sq_unid_executora        = a2.sq_unidade)
        and (a2.sq_unidade              = a3.sq_unidade (+) and
             a3.tipo_respons (+)        = 'T'           and
             a3.fim (+)                 is null
            )
        and (a3.sq_pessoa              = a5.sq_pessoa (+))
        and (a3.sq_pessoa              = a6.sq_pessoa (+))
        and (a2.sq_unidade              = a4.sq_unidade (+) and
             a4.tipo_respons (+)        = 'S'           and
             a4.fim (+)                 is null
            )
        and (a4.sq_pessoa              = a7.sq_pessoa (+))
        and (a4.sq_pessoa              = a8.sq_pessoa (+))
        and (a.sq_modulo                = a1.sq_modulo)
        and (a.sq_menu                  = b.sq_menu and
             b.conclusao                is null
            )
        and (b.sq_siw_tramite           = b1.sq_siw_tramite)
        and (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
        and (d.sq_unidade_resp          = e.sq_unidade)
        and (e.sq_unidade               = e1.sq_unidade (+) and
             e1.tipo_respons (+)        = 'T'           and
             e1.fim (+)                 is null
            )
        and (e1.sq_pessoa              = e3.sq_pessoa (+))
        and (e1.sq_pessoa              = e4.sq_pessoa (+))
        and (e.sq_unidade               = e2.sq_unidade (+) and
             e2.tipo_respons (+)        = 'S'           and
             e2.fim (+)                 is null
            )
        and (e2.sq_pessoa              = e5.sq_pessoa (+))
        and (e2.sq_pessoa              = e6.sq_pessoa (+))
        and (b.sq_solic_pai             = r3.sq_siw_solicitacao (+))
        and (r3.sq_siw_solicitacao      = r.sq_siw_solicitacao (+))
        and (r.sq_acao_ppa              = r1.sq_acao_ppa (+))
        and (r1.sq_acao_ppa_pai         = r2.sq_acao_ppa (+))
        and (r.sq_orprioridade          = r4.sq_orprioridade (+))
        and (b.sq_solic_pai             = m.sq_siw_solicitacao (+))
        and (b.sq_cc                    = n.sq_cc (+))
        and (b.solicitante              = o.sq_pessoa (+))
        and (o.sq_pessoa                = o1.sq_pessoa)
        and (o1.sq_unidade              = o2.sq_unidade)
        and (b.executor                 = p.sq_pessoa (+))
        and (p.sq_pessoa                = t.sq_pessoa (+))
        and (t.sq_unidade               = u.sq_unidade)
        and (a.sq_unid_executora        = c.sq_unidade (+))
        and (b.sq_siw_solicitacao       = i.sq_siw_solicitacao (+))
        and (i.sq_projeto_etapa         = q.sq_projeto_etapa (+))
        and a.sq_pessoa = p_cliente
        and a.tramite   = 'S'
        and d.concluida = 'N'
        and ((b.fim < sysdate) or
             (d.aviso_prox_conc = 'S' and (b.fim-sysdate < d.dias_aviso))
            )
     UNION
     select a.nome, a.sigla, a1.sigla sg_modulo, a2.nome nm_unidade_exec,
            a6.nome nm_tit_exec, a5.email em_tit_exec, a8.nome nm_sub_exec, a7.email em_sub_exec,
            b.sq_siw_solicitacao, b.sq_solic_pai, b.descricao, b.inicio, b.fim,
            b1.nome nm_tramite, b1.ordem or_tramite, b1.sigla sg_tramite,
            d.titulo, d.aviso_prox_conc, d.dias_aviso, d.proponente,
            decode(d.prioridade,0,'Alta',1,'Média','Normal') nm_prioridade,
            b.fim-d.dias_aviso aviso, e.nome nm_unidade_resp, e.sigla sg_unidade_resp,
            e4.nome nm_tit_resp, e3.email em_tit_resp, e6.nome nm_sub_resp, e5.email em_sub_resp,
            d.titulo nm_projeto, n.nome nm_cc,
            null nm_etapa, null cd_ordem,
            o.nome_resumido nm_solic, o1.email em_solic,
            o.nome_resumido||' ('||o2.sigla||')' nm_resp,
            p.nome_resumido nm_exec, t.email em_exec,
            decode(sign(b.fim - sysdate),-1,trunc(sysdate-b.fim),-1) dias_atraso,
            ceil(b.fim-sysdate) dias_fim,
            r2.codigo cd_programa, r2.nome nm_programa,
            r1.codigo cd_acao,     r1.nome nm_acao,
            null      cd_pri,      null    nm_pri
       from siw_menu                                       a,
            eo_unidade                a2,
            eo_unidade_resp           a3,
            sg_autenticacao         a5,
            co_pessoa               a6,
            eo_unidade_resp           a4,
            sg_autenticacao         a7,
            co_pessoa               a8,
            siw_modulo           a1,
            siw_solicitacao      b ,
            siw_tramite          b1,
            pj_projeto           d ,
            eo_unidade           e ,
            eo_unidade_resp e1,
            sg_autenticacao    e3,
            co_pessoa          e4,
            eo_unidade_resp e2,
            sg_autenticacao    e5,
            co_pessoa          e6,
            or_acao              r ,
            or_acao_ppa          r1,
            or_acao_ppa        r2,
            ct_cc                n ,
            co_pessoa            o ,
            sg_autenticacao      o1,
            eo_unidade           o2,
            co_pessoa            p ,
            sg_autenticacao      t
      where (a.sq_unid_executora        = a2.sq_unidade)
        and (a2.sq_unidade              = a3.sq_unidade (+) and
             a3.tipo_respons (+)        = 'T'           and
             a3.fim (+)                 is null
            )
        and (a3.sq_pessoa              = a5.sq_pessoa (+))
        and (a3.sq_pessoa              = a6.sq_pessoa (+))
        and (a2.sq_unidade              = a4.sq_unidade (+) and
             a4.tipo_respons (+)         = 'S'           and
             a4.fim (+)                 is null
            )
        and (a4.sq_pessoa              = a7.sq_pessoa (+))
        and (a4.sq_pessoa              = a8.sq_pessoa (+))
        and (a.sq_modulo                = a1.sq_modulo)
        and (a.sq_menu                  = b.sq_menu and
             b.conclusao                is null
            )
        and (b.sq_siw_tramite           = b1.sq_siw_tramite)
        and (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
        and (d.sq_unidade_resp          = e.sq_unidade)
        and (e.sq_unidade             = e1.sq_unidade (+) and
             e1.tipo_respons (+)      = 'T'           and
             e1.fim (+)               is null
            )
        and (e1.sq_pessoa              = e3.sq_pessoa (+))
        and (e1.sq_pessoa              = e4.sq_pessoa (+))
        and (e.sq_unidade             = e2.sq_unidade (+) and
             e2.tipo_respons (+)      = 'S'           and
             e2.fim (+)               is null
            )
        and (e2.sq_pessoa              = e5.sq_pessoa (+))
        and (e2.sq_pessoa              = e6.sq_pessoa (+))
        and (d.sq_siw_solicitacao       = r.sq_siw_solicitacao (+))
        and (r.sq_acao_ppa              = r1.sq_acao_ppa (+))
        and (r1.sq_acao_ppa_pai         = r2.sq_acao_ppa (+))
        and (b.sq_cc                    = n.sq_cc (+))
        and (b.solicitante              = o.sq_pessoa (+))
        and (o.sq_pessoa                = o1.sq_pessoa)
        and (o1.sq_unidade              = o2.sq_unidade)
        and (b.executor                 = p.sq_pessoa (+))
        and (p.sq_pessoa                = t.sq_pessoa (+))
        and a.sq_pessoa = p_cliente
        and a.tramite   = 'S'
        and d.concluida = 'N'
        and ((b.fim < sysdate) or
             (d.aviso_prox_conc = 'S' and (b.fim-sysdate < d.dias_aviso))
            );
end SP_GetSolicEmail;
/

