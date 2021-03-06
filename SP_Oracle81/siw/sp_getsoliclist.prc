create or replace procedure SP_GetSolicList
   (p_menu         in number,
    p_pessoa       in number,
    p_restricao    in varchar2 default null,
    p_tipo         in number,
    p_ini_i        in date     default null,
    p_ini_f        in date     default null,
    p_fim_i        in date     default null,
    p_fim_f        in date     default null,
    p_atraso       in varchar2 default null,
    p_solicitante  in number   default null,
    p_unidade      in number   default null,
    p_prioridade   in number   default null,
    p_ativo        in varchar2 default null,
    p_proponente   in varchar2 default null,
    p_chave        in number   default null,
    p_assunto      in varchar2 default null,
    p_pais         in number   default null,
    p_regiao       in number   default null,
    p_uf           in varchar2 default null,
    p_cidade       in number   default null,
    p_usu_resp     in number   default null,
    p_uorg_resp    in number   default null,
    p_palavra      in varchar2 default null,
    p_prazo        in number   default null,
    p_fase         in varchar2 default null,
    p_sqcc         in number   default null,
    p_projeto      in number   default null,
    p_atividade    in number   default null,
    p_sq_acao_ppa  in varchar2 default null,
    p_sq_orprior   in number   default null,
    p_empenho      in varchar2 default null,
    p_processo     in varchar2 default null,    
    p_result       out siw.sys_refcursor) is

    l_item       varchar2(18);
    l_fase       varchar2(200) := p_fase ||',';
    x_fase       varchar2(200) := '';

    l_resp_unid  varchar2(10000) :='';

    -- cursor que recupera as unidades nas quais o usu�rio informado � titular ou substituto
    cursor c_unidades_resp is
      select distinct sq_unidade
        from eo_unidade a
      start with sq_unidade in (select sq_unidade
                                  from eo_unidade_resp b
                                 where b.sq_pessoa = p_pessoa
                                   and b.fim       is null)
      connect by prior sq_unidade = sq_unidade_pai;

begin
   If p_fase is not null Then
      Loop
         l_item  := Trim(substr(l_fase,1,Instr(l_fase,',')-1));
         If Length(l_item) > 0 Then
            x_fase := x_fase||','''||to_number(l_item)||'''';
         End If;
         l_fase := substr(l_fase,Instr(l_fase,',')+1,200);
         Exit when l_fase is null;
      End Loop;
      x_fase := substr(x_fase,2,200);
   End If;

   -- Monta uma string com todas as unidades subordinadas � que o usu�rio � respons�vel
   for crec in c_unidades_resp loop
     l_resp_unid := l_resp_unid ||','''||crec.sq_unidade||'''';
   end loop;

   If p_restricao = 'GDCAD'            or p_restricao = 'GDACOMP'           or
      p_restricao = 'GDPCAD'           or p_restricao = 'GDPACOMP'          or
      Substr(p_restricao,1,4) = 'GRDM' or p_restricao = 'ORPCAD'            or
      p_restricao = 'ORPACOMP'         or Substr(p_restricao,1,4) = 'GRORP' or 
      p_restricao = 'GDPCADET'         Then
      -- Recupera as demandas que o usu�rio pode ver
      open p_result for
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               a.acompanha_fases,
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
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.sq_unidade_resp,    d.assunto,                     d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                decode(d.prioridade,0,'Alta',1,'M�dia','Normal') nm_prioridade,
                d.ordem,
                b.fim-d.dias_aviso aviso,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                m.titulo nm_projeto,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                o.nome_resumido_ind nm_solic_ind,
                p.nome_resumido nm_exec, p.nome_resumido_ind nm_exec_ind,
                q.sq_projeto_etapa, q.titulo nm_etapa, MontaOrdem(q.sq_projeto_etapa) cd_ordem,
                0 resp_etapa,
                0 sq_acao_ppa, 0 sq_orprioridade
           from siw_menu                                       a,
                eo_unidade                a2,
                eo_unidade_resp           a3,
                eo_unidade_resp           a4,
                siw_modulo           a1,
                siw_solicitacao      b ,
                siw_tramite          b1,
                (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                 from siw_solicitacao
                )                    b2,
                gd_demanda           d ,
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
                pj_etapa_demanda     i ,
                pj_projeto_etapa     q ,
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
            and (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
            and (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
            and (d.sq_unidade_resp          = e.sq_unidade)
            and (e.sq_unidade               = e1.sq_unidade (+) and
                 e1.tipo_respons (+)        = 'T'           and
                 e1.fim (+)                 is null
                )
            and (e.sq_unidade               = e2.sq_unidade (+) and
                 e2.tipo_respons  (+)       = 'S'           and
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
            and (b.sq_siw_solicitacao       = i.sq_siw_solicitacao (+))
            and (i.sq_projeto_etapa         = q.sq_projeto_etapa (+))
            and (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
            and (j.chave                    = k.sq_siw_solic_log (+))
            and (k.destinatario             = l.sq_pessoa (+))
            and a.sq_menu        = p_menu
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
            and (p_pais           is null or (p_pais        is not null and f.sq_pais            = p_pais))
            and (p_regiao         is null or (p_regiao      is not null and f.sq_regiao          = p_regiao))
            and (p_cidade         is null or (p_cidade      is not null and f.sq_cidade          = p_cidade))
            and (p_usu_resp       is null or (p_usu_resp    is not null and (b.executor          = p_usu_resp or 0 < (select count(*) from gd_demanda_log where destinatario = p_usu_resp and sq_siw_solicitacao = b.sq_siw_solicitacao))))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and d.concluida          = 'N' and l.sq_unidade = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc              = p_sqcc))
            and (p_projeto        is null or (p_projeto     is not null and b.sq_solic_pai       = p_projeto))
            and (p_atividade      is null or (p_atividade   is not null and i.sq_projeto_etapa   = p_atividade))
            and (p_uf             is null or (p_uf          is not null and f.co_uf              = p_uf))
            and (p_assunto        is null or (p_assunto     is not null and acentos(d.assunto,null) like '%'||acentos(p_assunto,null)||'%'))
            and (p_palavra        is null or (p_palavra     is not null and acentos(b.palavra_chave,null) like '%'||acentos(p_palavra,null)||'%'))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,b.sq_siw_tramite) > 0))
            and (p_prazo          is null or (p_prazo       is not null and d.concluida          = 'N' and b.fim-sysdate+1 <=p_prazo))
            and (p_prioridade     is null or (p_prioridade  is not null and d.prioridade         = p_prioridade))
            and (p_ini_i          is null or (p_ini_i       is not null and (Nvl(b1.sigla,'-')   <> 'AT' and b.inicio between p_ini_i and p_ini_f) or (Nvl(b1.sigla,'-') = 'AT' and d.inicio_real between p_ini_i and p_ini_f)))
            and (p_fim_i          is null or (p_fim_i       is not null and (Nvl(b1.sigla,'-')   <> 'AT' and b.fim                between p_fim_i and p_fim_f) or (Nvl(b1.sigla,'-') = 'AT' and d.fim_real between p_fim_i and p_fim_f)))
            and (Nvl(p_atraso,'N') = 'N'  or (p_atraso      = 'S'       and d.concluida          = 'N' and b.fim+1-sysdate<0))
            and (p_proponente     is null or (p_proponente  is not null and acentos(d.proponente,null) like '%'||acentos(p_proponente,null)||'%'))
            and (p_unidade        is null or (p_unidade     is not null and d.sq_unidade_resp    = p_unidade))
            and (p_prioridade     is null or (p_prioridade  is not null and d.prioridade         = p_prioridade))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and ((p_tipo         = 1     and Nvl(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and Nvl(b1.sigla,'-') <> 'CI'  and b.executor           = p_pessoa and d.concluida = 'N') or
                 --(p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA'  and b2.acesso > 0) or
                 (p_tipo         = 4     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0)
                )
             and ((p_restricao <> 'GRDMETAPA'    and p_restricao <> 'GRDMPROP' and
                   p_restricao <> 'GRDMRESPATU'  and p_restricao <> 'GDPCADET'
                  ) or
                  ((p_restricao = 'GRDMETAPA'    and MontaOrdem(q.sq_projeto_etapa)  is not null) or
                   (p_restricao = 'GRDMPROP'     and d.proponente                    is not null) or
                   (p_restricao = 'GRDMRESPATU'  and b.executor                      is not null) or
                   (p_restricao = 'GDPCADET'     and q.sq_projeto_etapa              is null)
                  )
                 );
   Elsif substr(p_restricao,1,5) = 'PJCAD' or p_restricao = 'PJACOMP' or Substr(p_restricao,1,4) = 'GRPR' or
         p_restricao = 'ORCAD' or p_restricao = 'ORACOMP' or Substr(p_restricao,1,4) = 'GROR' Then
      -- Recupera as demandas que o usu�rio pode ver
      open p_result for
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               a.acompanha_fases,
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
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.sq_unidade_resp,    d.titulo,                      d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                d.vincula_contrato,   d.vincula_viagem,              d.outra_parte,
                d.sq_tipo_pessoa,
                d1.nome nm_prop,      d1.nome_resumido nm_prop_res,
                decode(d.prioridade,0,'Alta',1,'M�dia','Normal') nm_prioridade,
                b.fim-d.dias_aviso aviso,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido_ind nm_solic_ind,
                p.nome_resumido nm_exec,  p.nome_resumido_ind nm_exec_ind,
                Nvl(q.existe,0) resp_etapa,
                r.sq_acao_ppa, r.sq_orprioridade
           from siw_menu                                       a,
                eo_unidade                a2,
                eo_unidade_resp           a3,
                eo_unidade_resp           a4,
                siw_modulo           a1,
                siw_solicitacao      b,
                siw_tramite          b1,
                (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                             from siw_solicitacao
                                          )                    b2,
                pj_projeto           d,
                co_pessoa            d1,
                eo_unidade           e,
                eo_unidade_resp      e1,
                eo_unidade_resp      e2,
                or_acao              r,
                co_cidade            f,
                ct_cc                n,
                co_pessoa            o,
                co_pessoa            p,
                (select sq_siw_solicitacao, count(*) existe
                                             from pj_projeto_etapa                a,
                                                  eo_unidade_resp b
                                            where (a.sq_unidade = b.sq_unidade (+) and
                                                   b.fim (+)    is null        and
                                                   b.sq_pessoa (+) = p_pessoa
                                                  )
                                              and (a.sq_pessoa         = p_pessoa or
                                                   b.sq_unidade_resp   is not null)
                                           group  by a.sq_siw_solicitacao
                                          )                    q,
                eo_unidade           c,
                (select sq_siw_solicitacao, max(sq_siw_solic_log) chave
                                             from siw_solic_log
                                           group by sq_siw_solicitacao
                                          )                    j,
                pj_projeto_log       k,
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
            and (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
            and (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
            and (d.outra_parte              = d1.sq_pessoa (+))
            and (d.sq_unidade_resp          = e.sq_unidade)
            and (e.sq_unidade             = e1.sq_unidade (+) and
                 e1.tipo_respons (+)      = 'T'           and
                 e1.fim (+)               is null
                )
            and (e.sq_unidade             = e2.sq_unidade (+) and
                 e2.tipo_respons (+)      = 'S'           and
                 e2.fim (+)               is null
                )
            and (d.sq_siw_solicitacao       = r.sq_siw_solicitacao (+))
            and (b.sq_cidade_origem         = f.sq_cidade)
            and (b.sq_cc                    = n.sq_cc (+))
            and (b.solicitante              = o.sq_pessoa (+))
            and (b.executor                 = p.sq_pessoa (+))
            and (b.sq_siw_solicitacao = q.sq_siw_solicitacao (+))
            and (a.sq_unid_executora        = c.sq_unidade (+))
            and (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
            and (j.chave                    = k.sq_siw_solic_log (+))
            and (k.destinatario             = l.sq_pessoa (+))
            and a.sq_menu        = p_menu
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
            and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and (r.sq_acao_ppa       = p_sq_acao_ppa or b.sq_solic_pai = p_sq_acao_ppa)))
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and r.sq_orprioridade    = p_sq_orprior))
            and (p_pais           is null or (p_pais        is not null and f.sq_pais            = p_pais))
            and (p_regiao         is null or (p_regiao      is not null and f.sq_regiao          = p_regiao))
            and (p_cidade         is null or (p_cidade      is not null and f.sq_cidade          = p_cidade))
            and (p_usu_resp       is null or (p_usu_resp    is not null and (b.executor          = p_usu_resp or 0 < (select count(*) from pj_projeto_log where destinatario = p_usu_resp and sq_siw_solicitacao = b.sq_siw_solicitacao))))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and d.concluida          = 'N' and l.sq_unidade = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc              = p_sqcc))
            and (p_projeto        is null or (p_projeto     is not null and b.sq_siw_solicitacao = p_projeto))
            and (p_uf             is null or (p_uf          is not null and f.co_uf              = p_uf))
            and (p_assunto        is null or (p_assunto     is not null and acentos(d.titulo,null) like '%'||acentos(p_assunto,null)||'%'))
            and (p_palavra        is null or (p_palavra     is not null and acentos(b.palavra_chave,null) like '%'||acentos(p_palavra,null)||'%'))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (p_prazo          is null or (p_prazo       is not null and d.concluida          = 'N' and b.fim-sysdate+1 <=p_prazo))
            and (p_ini_i          is null or (p_ini_i       is not null and (Nvl(b1.sigla,'-')   <> 'AT' and b.inicio between p_ini_i and p_ini_f) or (Nvl(b1.sigla,'-') = 'AT' and d.inicio_real between p_ini_i and p_ini_f)))
            and (p_fim_i          is null or (p_fim_i       is not null and (Nvl(b1.sigla,'-')   <> 'AT' and b.fim                between p_fim_i and p_fim_f) or (Nvl(b1.sigla,'-') = 'AT' and d.fim_real between p_fim_i and p_fim_f)))
            and (Nvl(p_atraso,'N') = 'N'  or (p_atraso      = 'S'       and d.concluida          = 'N' and b.fim+1-sysdate<0))
            and (p_proponente     is null or (p_proponente  is not null and (acentos(d.proponente,null)     like '%'||acentos(p_proponente,null)||'%') or
                                                                            (acentos(d1.nome,null)          like '%'||acentos(p_proponente,null)||'%') or
                                                                            (acentos(d1.nome_resumido,null) like '%'||acentos(p_proponente,null)||'%')
                                             )
                )
            and (p_unidade        is null or (p_unidade     is not null and d.sq_unidade_resp    = p_unidade))
            and (p_prioridade     is null or (p_prioridade  is not null and d.prioridade         = p_prioridade))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and ((p_tipo         = 1     and Nvl(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and Nvl(b1.sigla,'-') <> 'CI'  and b.executor           = p_pessoa and d.concluida = 'N') or
                 --(p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA'  and b2.acesso > 0) or
                 (p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA'  and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 5     and Nvl(b1.sigla,'-') <> 'CA') or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0)
                )
            and ((p_restricao <> 'GRPRPROP'    and p_restricao <> 'GRPRRESPATU') or 
                 ((p_restricao = 'GRPRPROP'    and d.proponente  is not null)   or 
                  (p_restricao = 'GRPRRESPATU' and b.executor    is not null)
                 )
                );
   Elsif substr(p_restricao,1,3) = 'GCR' or substr(p_restricao,1,3) = 'GCD' or substr(p_restricao,1,3) = 'GCP' or
         substr(p_restricao,1,3) = 'GCP' or substr(p_restricao,1,3) = 'GCA' or
         substr(p_restricao,1,3) = 'GCB' Then   
      -- Recupera os acordos que o usu�rio pode ver
      open p_result for
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               a.acompanha_fases,
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
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.sq_tipo_acordo,     d.outra_parte,                 d.preposto,
                d.inicio inicio_real, d.fim fim_real,                d.duracao,
                d.valor_inicial,      Nvl(d8.valor,d.valor_atual),   d.codigo_interno,
                d.codigo_externo,     d.objeto,                      d.atividades,
                d.produtos,           d.requisitos,                  d.observacao,
                d.dia_vencimento,     d.vincula_projeto,             d.vincula_demanda,
                d.vincula_viagem,     d.aviso_prox_conc,             d.dias_aviso,
                d.empenho,            d.processo,                    d.assinatura,
                d.publicacao,
                d1.nome nm_tipo_acordo,d1.sigla sg_acordo,           d1.modalidade cd_modalidade,
                d2.nome nm_outra_parte, d2.nome_resumido nm_outra_parte_resumido,
                d2.nome_resumido_ind nm_outra_parte_resumido_ind,                
                d21.cpf, d22.cnpj,
                d3.nome nm_preposto,  d3.nome_resumido nm_preposto_resumido,
                d4.sq_pessoa_conta,   d4.operacao,                   d4.numero nr_conta,
                d5.sq_agencia,        d5.codigo cd_agencia,          d5.nome nm_agencia,
                d6.sq_banco,          d6.codigo cd_banco,            d6.nome nm_banco,
                d7.sq_forma_pagamento,d7.nome nm_forma_pagamento,    d7.sigla sg_forma_pagamento,
                d7.ativo st_forma_pagamento,
                b.fim-d.dias_aviso aviso,
                e.sq_unidade sq_unidade_resp,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                m.titulo nm_projeto,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                p.nome_resumido nm_exec,
                q.sq_projeto_etapa, q.titulo nm_etapa, MontaOrdem(q.sq_projeto_etapa) cd_ordem,
                d2.nome_resumido||' - '||decode(n.sq_cc,'',m.titulo,n.nome)||' ('||to_char(d.inicio,'dd/mm/yyyy')||'-'||to_char(d.fim,'dd/mm/yyyy')||')' as titulo
           from siw_menu                  a,
                eo_unidade                a2,
                eo_unidade_resp           a3,
                eo_unidade_resp           a4,
                siw_modulo                a1,
                siw_solicitacao           b,
                siw_tramite               b1,
                (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                             from siw_solicitacao
                )                         b2,
                ac_acordo                 d,
                co_forma_pagamento        d7,
                (select x.sq_siw_solicitacao, sum(z.valor) valor
                   from ac_acordo_parcela x,
                        fn_lancamento     y,
                        siw_solicitacao   z,
                        siw_tramite       w
                  where (x.sq_acordo_parcela  = y.sq_acordo_parcela)
                    and (y.sq_siw_solicitacao = z.sq_siw_solicitacao)
                    and (z.sq_siw_tramite     = w.sq_siw_tramite and
                         nvl(w.sigla,'---')   <> 'CA'
                        )
                    and x.quitacao            is not null
                 group by x.sq_siw_solicitacao
                )                         d8,
                ac_tipo_acordo            d1,
                co_pessoa                 d2,
                co_pessoa_fisica          d21,
                co_pessoa_juridica        d22,
                co_pessoa_conta           d4,
                co_agencia                d5,
                co_banco                  d6,
                co_pessoa                 d3,
                eo_unidade                e,
                eo_unidade_resp           e1,
                eo_unidade_resp           e2,
                co_cidade                 f,
                pj_projeto                m,
                ct_cc                     n,
                co_pessoa                 o,
                sg_autenticacao           o1,
                eo_unidade                o2,
                co_pessoa                 p,
                eo_unidade                c,
                pj_etapa_contrato         i,
                pj_projeto_etapa          q,
                (select sq_siw_solicitacao, max(sq_siw_solic_log) chave
                   from siw_solic_log
                 group by sq_siw_solicitacao
                )                          j,
                ac_acordo_log              k,
                sg_autenticacao            l
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
            and (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
            and (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
            and (d.sq_forma_pagamento       = d7.sq_forma_pagamento)
            and (d.sq_siw_solicitacao        = d8.sq_siw_solicitacao (+))
            and (d.sq_tipo_acordo           = d1.sq_tipo_acordo)
            and (d.outra_parte              = d2.sq_pessoa (+))
            and (d2.sq_pessoa               = d21.sq_pessoa (+))
            and (d2.sq_pessoa               = d22.sq_pessoa (+))
            and (d.outra_parte              = d4.sq_pessoa (+) and
                 d4.ativo  (+)              = 'S' and
                 d4.padrao  (+)             = 'S'
                )
            and (d4.sq_agencia              = d5.sq_agencia (+) and
                 d5.ativo  (+)              = 'S'
                )
            and (d5.sq_banco                = d6.sq_banco  (+) and
                 d6.ativo  (+)              = 'S'
                )
            and (d.preposto                 = d3.sq_pessoa (+))
            and (b.sq_unidade               = e.sq_unidade)
            and (e.sq_unidade               = e1.sq_unidade (+) and
                 e1.tipo_respons  (+)       = 'T'           and
                 e1.fim (+)                 is null
                )
            and (e.sq_unidade               = e2.sq_unidade (+) and
                 e2.tipo_respons  (+)       = 'S'           and
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
            and (b.sq_siw_solicitacao       = i.sq_siw_solicitacao (+))
            and (i.sq_projeto_etapa         = q.sq_projeto_etapa (+))
            and (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
            and (j.chave                    = k.sq_siw_solic_log (+))
            and (k.destinatario             = l.sq_pessoa (+))
            and a.sq_menu        = p_menu
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
            and (p_pais           is null or (p_pais        is not null and f.sq_pais            = p_pais))
            and (p_regiao         is null or (p_regiao      is not null and f.sq_regiao          = p_regiao))
            and (p_cidade         is null or (p_cidade      is not null and f.sq_cidade          = p_cidade))
            and (p_usu_resp       is null or (p_usu_resp    is not null and (b.executor          = p_usu_resp or 0 < (select count(*) from ac_acordo_log where destinatario = p_usu_resp and sq_siw_solicitacao = b.sq_siw_solicitacao))))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and b.conclusao          is null and l.sq_unidade = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc              = p_sqcc))
            and (p_projeto        is null or (p_projeto     is not null and b.sq_solic_pai       = p_projeto))
            and (p_atividade      is null or (p_atividade   is not null and i.sq_projeto_etapa   = p_atividade))            
            and (p_uf             is null or (p_uf          is not null and f.co_uf              = p_uf))
            and (p_assunto        is null or (p_assunto     is not null and acentos(d.objeto,null) like '%'||acentos(p_assunto,null)||'%'))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (p_prazo          is null or (p_prazo       is not null and b.conclusao          is null and b.fim-sysdate+1 <=p_prazo))
            and (p_ini_i          is null or (p_ini_i       is not null and d.inicio             between p_ini_i and p_ini_f))
            and (p_fim_i          is null or (p_fim_i       is not null and d.fim                between p_fim_i and p_fim_f))
            and (p_unidade        is null or (p_unidade     is not null and b.sq_unidade         = p_unidade))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and (p_palavra        is null or (p_palavra     is not null and d.codigo_interno     like '%'||p_palavra||'%'))
            and (p_atraso         is null or (p_atraso      is not null and d.codigo_externo     like '%'||p_atraso||'%'))
            and (p_proponente     is null or (p_proponente  is not null and (acentos(d2.nome,null)          like '%'||acentos(p_proponente,null)||'%') or
                                                                            (acentos(d2.nome_resumido,null) like '%'||acentos(p_proponente,null)||'%')
                                             )
                )
            and (p_empenho        is null or (p_empenho     is not null and upper(d.empenho)     = upper(p_empenho)))
            and (p_processo       is null or (p_processo    is not null and upper(d.processo)    = upper(p_processo)))                
            and ((substr(p_restricao,1,3)     = 'GCR' and d1.modalidade = 'F') or
                 (substr(p_restricao,1,3)     = 'GCA' and d1.modalidade = 'I') or
                 (substr(p_restricao,1,3)     = 'GCB' and d1.modalidade = 'E') or
                 (substr(p_restricao,1,3)     = 'GCC' and d1.modalidade = 'I') or            
                 (substr(p_restricao,1,3)     = 'GCD' and d1.modalidade not in ('F','I')) or
                 (substr(p_restricao,1,3)     = 'GCP' and d1.modalidade = 'I')
                )
            and ((p_tipo         = 1     and Nvl(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b.executor = p_pessoa and b.conclusao is null) or
                 --(p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA'  and b2.acesso > 0) or
                 (p_tipo         = 4     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0)
                )
            and ((instr(p_restricao,'PROJ')    = 0 and
                  instr(p_restricao,'ETAPA')   = 0 and            
                  instr(p_restricao,'PROP')    = 0 and
                  instr(p_restricao,'RESPATU') = 0 and
                  instr(p_restricao,'CC')      = 0
                 ) or 
                 ((instr(p_restricao,'PROJ')    > 0    and b.sq_solic_pai is not null) or
                  (instr(p_restricao,'ETAPA')   > 0    and MontaOrdem(q.sq_projeto_etapa)  is not null) or                                  
                  (instr(p_restricao,'PROP')    > 0    and d.outra_parte  is not null) or
                  (instr(p_restricao,'RESPATU') > 0    and b.executor     is not null) or
                  (instr(p_restricao,'CC')      > 0    and b.sq_cc        is not null)
                 )
                );
   Elsif substr(p_restricao,1,2) = 'FN' Then
      -- Recupera os acordos que o usu�rio pode ver
      open p_result for
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               a.acompanha_fases,
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
                b.opiniao,                     b.sq_solic_pai,
                b.sq_unidade,         b.sq_cidade_origem,            b.palavra_chave,
                b.valor,
                decode(nvl(b1.sigla,'--'), 'AT', b.valor, 0) valor_atual,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.pessoa,             d.codigo_interno,              d.sq_acordo_parcela,
                d.sq_forma_pagamento, d.sq_tipo_lancamento,          d.sq_tipo_pessoa,
                d.emissao,            d.vencimento,                  d.quitacao,
                d.codigo_externo,     d.observacao,                  d.valor_imposto,
                d.valor_retencao,     d.valor_liquido,               d.aviso_prox_conc,
                d.dias_aviso,         d.sq_tipo_pessoa,
                d2.nome nm_pessoa,    d2.nome_resumido nm_pessoa_resumido,
                d2.nome_resumido_ind nm_pessoa_resumido_ind,
                Nvl(d3.valor,0) valor_doc,
                d4.sq_pessoa_conta,   d4.operacao,                   d4.numero nr_conta,
                d5.sq_agencia,        d5.codigo cd_agencia,          d5.nome nm_agencia,
                d6.sq_banco,          d6.codigo cd_banco,            d6.nome nm_banco,
                d7.sq_forma_pagamento,d7.nome nm_forma_pagamento,    d7.sigla sg_forma_pagamento,
                d7.ativo st_forma_pagamento,
                b.fim-d.dias_aviso aviso,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                m.codigo_interno cd_acordo, m.objeto obj_acordo,
                m1.ordem or_parcela,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                p.nome_resumido nm_exec,
                q.titulo nm_projeto
           from siw_menu                                       a,
                eo_unidade                a2,
                eo_unidade_resp           a3,
                eo_unidade_resp           a4,
                siw_modulo           a1,
                siw_solicitacao      b,
                siw_tramite          b1,
                (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                   from siw_solicitacao
                )                    b2,
                fn_lancamento        d,
                co_forma_pagamento   d7,
                fn_tipo_lancamento   d1,
                co_pessoa            d2,
                (select x.sq_siw_solicitacao, sum(Nvl(x.valor,0)) valor
                   from fn_lancamento_doc x
                 group by x.sq_siw_solicitacao
                )                    d3,
                co_pessoa_conta      d4,
                co_agencia           d5,
                co_banco             d6,
                eo_unidade           e,
                eo_unidade_resp      e1,
                eo_unidade_resp      e2,
                co_cidade            f,
                ac_acordo            m,
                ac_acordo_parcela    m1,
                pj_projeto           q,
                ct_cc                n,
                co_pessoa            o,
                sg_autenticacao      o1,
                eo_unidade           o2,
                co_pessoa            p,
                eo_unidade           c,
                (select sq_siw_solicitacao, max(sq_siw_solic_log) chave
                   from siw_solic_log
                 group by sq_siw_solicitacao
                )                    j,
                fn_lancamento_log    k,
                sg_autenticacao      l
          where (a.sq_unid_executora        = a2.sq_unidade)
            and (a2.sq_unidade              = a3.sq_unidade (+) and
                 a3.tipo_respons (+)        = 'T'           and
                 a3.fim (+)                 is null
                )
            and (a2.sq_unidade              = a4.sq_unidade (+) and
                 a4.tipo_respons  (+)       = 'S'           and
                 a4.fim (+)                 is null
                )
            and (a.sq_modulo                = a1.sq_modulo)
            and (a.sq_menu                  = b.sq_menu)
            and (b.sq_siw_tramite           = b1.sq_siw_tramite)
            and (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
            and (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
            and (d.sq_forma_pagamento       = d7.sq_forma_pagamento)
            and (d.sq_tipo_lancamento       = d1.sq_tipo_lancamento)
            and (d.pessoa                   = d2.sq_pessoa (+))
            and (d.sq_siw_solicitacao       = d3.sq_siw_solicitacao (+))
            and (d.pessoa                   = d4.sq_pessoa (+) and
                 d4.ativo (+)               = 'S' and
                 d4.padrao (+)              = 'S'
                )
            and (d4.sq_agencia              = d5.sq_agencia (+) and
                 d5.ativo (+)               = 'S'
                )
            and (d5.sq_banco                = d6.sq_banco (+) and
                 d6.ativo (+)               = 'S'
                )
            and (b.sq_unidade               = e.sq_unidade)
            and (e.sq_unidade               = e1.sq_unidade (+) and
                 e1.tipo_respons (+)        = 'T'           and
                 e1.fim (+)                 is null
                )
            and (e.sq_unidade               = e2.sq_unidade  (+)and
                 e2.tipo_respons (+)        = 'S'           and
                 e2.fim (+)                 is null
                )
            and (b.sq_cidade_origem         = f.sq_cidade)
            and (b.sq_solic_pai             = m.sq_siw_solicitacao (+))
            and (d.sq_acordo_parcela        = m1.sq_acordo_parcela (+))
            and (b.sq_solic_pai             = q.sq_siw_solicitacao (+))
            and (b.sq_cc                    = n.sq_cc (+))
            and (b.solicitante              = o.sq_pessoa (+))
            and (o.sq_pessoa                = o1.sq_pessoa)
            and (o1.sq_unidade              = o2.sq_unidade)
            and (b.executor                 = p.sq_pessoa (+))
            and (a.sq_unid_executora        = c.sq_unidade (+))
            and (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
            and (j.chave                    = k.sq_siw_solic_log (+))
            and (k.destinatario             = l.sq_pessoa (+))
            and a.sq_menu        = p_menu
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
            and (p_pais           is null or (p_pais        is not null and f.sq_pais            = p_pais))
            and (p_regiao         is null or (p_regiao      is not null and f.sq_regiao          = p_regiao))
            and (p_cidade         is null or (p_cidade      is not null and f.sq_cidade          = p_cidade))
            and (p_usu_resp       is null or (p_usu_resp    is not null and (b.executor          = p_usu_resp or 0 < (select count(*) from fn_lancamento_log where destinatario = p_usu_resp and sq_siw_solicitacao = b.sq_siw_solicitacao))))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and b.conclusao          is null and l.sq_unidade = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc              = p_sqcc))
            and (p_projeto        is null or (p_projeto     is not null and b.sq_solic_pai       = p_projeto))
            and (p_uf             is null or (p_uf          is not null and f.co_uf              = p_uf))
            and (p_assunto        is null or (p_assunto     is not null and acentos(b.descricao,null) like '%'||acentos(p_assunto,null)||'%'))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (p_prazo          is null or (p_prazo       is not null and b.conclusao          is null and b.fim-sysdate+1 <=p_prazo))
            and (p_ini_i          is null or (p_ini_i       is not null and b.inicio             between p_ini_i and p_ini_f))
            and (p_fim_i          is null or (p_fim_i       is not null and b.fim                between p_fim_i and p_fim_f))
            and (p_unidade        is null or (p_unidade     is not null and b.sq_unidade         = p_unidade))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and (p_palavra        is null or (p_palavra     is not null and d.codigo_interno     like '%'||p_palavra||'%'))
            and (p_proponente     is null or (p_proponente  is not null and (acentos(d2.nome,null)          like '%'||acentos(p_proponente,null)||'%') or
                                                                            (acentos(d2.nome_resumido,null) like '%'||acentos(p_proponente,null)||'%')
                                             )
                )
            and ((substr(p_restricao,1,3) = 'FNR' and d1.receita = 'S') or
                 (substr(p_restricao,1,3) = 'FND' and d1.despesa = 'S')
                )
            and ((p_tipo         = 1     and Nvl(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b.executor = p_pessoa and b.conclusao is null) or
                 --(p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA'  and b2.acesso > 0) or
                 (p_tipo         = 4     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0)
                )
            and ((instr(p_restricao,'PROJ')    = 0 and
                  instr(p_restricao,'ETAPA')   = 0 and
                  instr(p_restricao,'PROP')    = 0 and
                  instr(p_restricao,'RESPATU') = 0 and
                  substr(p_restricao,4,2)      <>'CC'
                 ) or 
                 ((instr(p_restricao,'PROJ')    > 0    and q.sq_siw_solicitacao is not null) or
                  --(instr(p_restricao,'ETAPA')   > 0    and MontaOrdem(q.sq_projeto_etapa)  is not null) or                 
                  (instr(p_restricao,'PROP')    > 0    and d.pessoa       is not null) or
                  (instr(p_restricao,'RESPATU') > 0    and b.executor     is not null) or
                  (substr(p_restricao,4,2)      ='CC'  and b.sq_cc        is not null)
                 )
                );
   Elsif substr(p_restricao,1,2) = 'PD' or Substr(p_restricao,1,4) = 'GRPD' Then
      -- Recupera as viagens que o usu�rio pode ver
      open p_result for
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               a.acompanha_fases,
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
                b.valor,              b.fim-d.dias_aviso aviso,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.sq_unidade_resp,    d.assunto,                     d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                decode(d.prioridade,0,'Alta',1,'M�dia','Normal') nm_prioridade,
                d.ordem,
                d1.sq_pessoa sq_prop, d1.tipo tp_missao,             d1.codigo_interno,
                decode(d1.tipo,'I','Inicial','P','Prorroga��o','Complementa��o') nm_tp_missao,
                d1.valor_adicional,   d1.desconto_alimentacao,       d1.desconto_transporte,
                d2.nome nm_prop,      d2.nome_resumido nm_prop_res,
                d3.sq_tipo_vinculo,   d3.nome nm_tipo_vinculo,
                d4.sexo,              d4.cpf,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                p.nome_resumido nm_exec,
                n.valor_diaria, d1.valor_passagem valor_trecho,
                d5.limite_passagem, d5.limite_diaria                
           from siw_menu             a,
                eo_unidade           a2,
                eo_unidade_resp      a3,
                eo_unidade_resp      a4,
                siw_modulo           a1,
                siw_solicitacao      b,
                siw_tramite          b1,
                (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                  from siw_solicitacao
                )                    b2,
                gd_demanda           d,
                pd_missao            d1,
                co_pessoa            d2,
                co_tipo_vinculo      d3,
                co_pessoa_fisica     d4,
                (select x.sq_unidade, 
                        nvl(y.limite_passagem,0) as limite_passagem, 
                        nvl(y.limite_diaria,0)   as limite_diaria
                   from pd_unidade        x,
                        pd_unidade_limite y
                  where x.sq_unidade = y.sq_unidade 
                    and y.ano        = nvl(p_sq_orprior,y.ano)
                )                    d5,
                eo_unidade           e,
                eo_unidade_resp      e1,
                eo_unidade_resp      e2,
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
                sg_autenticacao      l,
                (select x.sq_siw_solicitacao, sum((y.quantidade*y.valor)) valor_diaria
                   from siw_solicitacao x,
                        pd_diaria       y
                  where (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                 group by x.sq_siw_solicitacao
                )                    n,
                (select x.sq_siw_solicitacao, sum(y.valor_trecho) valor_trecho
                   from siw_solicitacao x,
                        pd_deslocamento y
                  where (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                 group by x.sq_siw_solicitacao
                )                    q
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
            and (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
            and (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
            and (d.sq_siw_solicitacao       = d1.sq_siw_solicitacao)
            and (d1.sq_pessoa               = d2.sq_pessoa)
            and (d2.sq_tipo_vinculo         = d3.sq_tipo_vinculo)
            and (d2.sq_pessoa               = d4.sq_pessoa)
            and (d.sq_unidade_resp          = d5.sq_unidade)
            and (d.sq_unidade_resp          = e.sq_unidade)
            and (e.sq_unidade               = e1.sq_unidade (+) and
                 e1.tipo_respons (+)        = 'T'           and
                 e1.fim (+)                 is null
                )
            and (e.sq_unidade               = e2.sq_unidade (+) and
                 e2.tipo_respons (+)        = 'S'           and
                 e2.fim (+)                 is null
                )
            and (b.solicitante              = o.sq_pessoa)
            and (o.sq_pessoa                = o1.sq_pessoa)
            and (o1.sq_unidade              = o2.sq_unidade)
            and (b.executor                 = p.sq_pessoa (+))
            and (a.sq_unid_executora        = c.sq_unidade (+))
            and (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
            and (j.chave                    = k.sq_siw_solic_log (+))
            and (k.destinatario             = l.sq_pessoa (+))
            and (b.sq_siw_solicitacao       = n.sq_siw_solicitacao (+))
            and (b.sq_siw_solicitacao       = q.sq_siw_solicitacao (+))
            and a.sq_menu         = p_menu
            and (p_projeto        is null or (p_projeto     is not null and 0 < (select count(distinct(x1.sq_siw_solicitacao)) from pd_missao_solic x1 , siw_solicitacao y1 where x1.sq_siw_solicitacao = y1.sq_siw_solicitacao and y1.sq_solic_pai = p_projeto and x1.sq_solic_missao = b.sq_siw_solicitacao)))
            and (p_atividade      is null or (p_atividade   is not null and 0 < (select count(distinct(x2.sq_siw_solicitacao)) from pd_missao_solic x2, pj_etapa_demanda x3 where (x2.sq_siw_solicitacao = x3.sq_siw_solicitacao and x3.sq_projeto_etapa = p_atividade) and x2.sq_solic_missao = b.sq_siw_solicitacao)))
            and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and d1.codigo_interno like '%'||p_sq_acao_ppa||'%'))
            and (p_assunto        is null or (p_assunto     is not null and acentos(b.descricao,null) like '%'||acentos(p_assunto,null)||'%'))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and (p_unidade        is null or (p_unidade     is not null and d.sq_unidade_resp    = p_unidade))
            and (p_proponente     is null or (p_proponente  is not null and (acentos(d2.nome,null)          like '%'||acentos(p_proponente,null)||'%') or
                                                                            (acentos(d2.nome_resumido,null) like '%'||acentos(p_proponente,null)||'%')
                                             )
                )
            and (p_palavra        is null or (p_palavra     is not null and d4.cpf = p_palavra))
            and (p_pais           is null or (p_pais        is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.destino = y.sq_cidade and y.sq_pais = p_pais and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_regiao         is null or (p_regiao      is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.destino = y.sq_cidade and y.sq_regiao = p_regiao and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_uf             is null or (p_uf          is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.destino = y.sq_cidade and y.co_uf = p_uf and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_cidade         is null or (p_cidade      is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x where x.destino = p_cidade and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_ativo          is null or (p_ativo       is not null and d1.tipo = p_ativo))            
            and (p_usu_resp       is null or (p_usu_resp    is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x where x.sq_cia_transporte = p_usu_resp and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_ini_i          is null or (p_ini_i       is not null and ((b.inicio           between p_ini_i  and p_ini_f) or
                                                                             (b.fim              between p_ini_i  and p_ini_f) or
                                                                             (p_ini_i            between b.inicio and b.fim)   or
                                                                             (p_fim_i            between b.inicio and b.fim)
                                                                            )
                                             )
                )
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (Nvl(p_atraso,'N') = 'N'  or (p_atraso      = 'S'       and d.concluida          = 'N' and b.fim+1-sysdate<0))            
            and ((p_tipo         = 1     and Nvl(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b.executor = p_pessoa and b.conclusao is null) or
                 (p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA') or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0)
                );
   Elsif Substr(p_restricao,1,2) = 'SR' or Substr(p_restricao,1,4) = 'GRSR' Then
      -- Recupera as demandas que o usu�rio pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               a.acompanha_fases,
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
                to_char(b.inclusao,'dd/mm/yyyy, hh24:mi:ss')  phpdt_inclusao,
                to_char(b.inicio,'dd/mm/yyyy, hh24:mi:ss')    phpdt_inicio,
                to_char(b.fim,'dd/mm/yyyy, hh24:mi:ss')       phpdt_fim,
                to_char(b.conclusao,'dd/mm/yyyy, hh24:mi:ss') phpdt_conclusao,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                b3.nome nm_opiniao,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                o.nome_resumido_ind nm_solic_ind,
                p.nome_resumido nm_exec, p.nome_resumido_ind nm_exec_ind
           from siw_menu             a,
                eo_unidade           a2,
                eo_unidade_resp      a3,
                eo_unidade_resp      a4,
                siw_modulo           a1,
                eo_unidade           c,
                siw_solicitacao      b, 
                siw_tramite          b1,
                siw_opiniao          b3,
                (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                   from siw_solicitacao
                )                    b2,
                eo_unidade           e,
                eo_unidade_resp      e1,
                eo_unidade_resp      e2,
                co_cidade            f,
                ct_cc                n,
                co_pessoa            o,
                sg_autenticacao      o1,
                eo_unidade           o2,
                co_pessoa            p,
                (select sq_siw_solicitacao, max(sq_siw_solic_log) chave 
                   from siw_solic_log
                 group by sq_siw_solicitacao
                )               j
          where (a.sq_unid_executora        = a2.sq_unidade)
            and (a2.sq_unidade              = a3.sq_unidade (+) and
                 a3.tipo_respons (+)        = 'T'               and
                 a3.fim (+)                 is null
                )
            and (a2.sq_unidade              = a4.sq_unidade (+) and
                 a4.tipo_respons  (+)       = 'S'           and
                 a4.fim (+)                 is null
                )
            and (a.sq_modulo                = a1.sq_modulo)
            and (a.sq_unid_executora        = c.sq_unidade (+))
            and (a.sq_menu                  = b.sq_menu)
            and (b.sq_siw_tramite           = b1.sq_siw_tramite)
            and (b.opiniao                  = b3.sq_siw_opiniao (+))
            and (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
            and (b.sq_unidade               = e.sq_unidade)
            and (e.sq_unidade               = e1.sq_unidade (+) and
                 e1.tipo_respons (+)        = 'T'           and
                 e1.fim (+)                 is null)
            and (e.sq_unidade               = e2.sq_unidade (+) and
                 e2.tipo_respons (+)        = 'S'           and
                 e2.fim  (+)                is null)
            and (b.sq_cidade_origem         = f.sq_cidade)
            and (b.sq_cc                    = n.sq_cc (+))
            and (b.solicitante              = o.sq_pessoa)
            and (o.sq_pessoa                = o1.sq_pessoa)
            and (o1.sq_unidade              = o2.sq_unidade)
            and (b.executor                 = p.sq_pessoa (+))
            and (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
            and a.sq_menu         = decode(p_menu, 0, a.sq_menu, p_menu)
            and a1.sigla          = substr(p_restricao,1,2)
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
            and (p_pais           is null or (p_pais        is not null and f.sq_pais            = p_pais))
            and (p_regiao         is null or (p_regiao      is not null and f.sq_regiao          = p_regiao))
            and (p_uf             is null or (p_uf          is not null and f.co_uf              = p_uf))
            and (p_cidade         is null or (p_cidade      is not null and f.sq_cidade          = p_cidade))
            and (p_usu_resp       is null or (p_usu_resp    is not null and (b.executor          = p_usu_resp or 0 < (select count(*) from gd_demanda_log where destinatario = p_usu_resp and sq_siw_solicitacao = b.sq_siw_solicitacao))))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and a.sq_unid_executora  = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc              = p_sqcc))
            and (p_assunto        is null or (p_assunto     is not null and acentos(b.descricao,null) like '%'||acentos(p_assunto,null)||'%'))
            and (p_prioridade     is null or (p_prioridade  is not null and b.conclusao          is not null and b.opiniao is not null and b.opiniao = p_prioridade))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,b.sq_siw_tramite) > 0))
            and (p_prazo          is null or (p_prazo       is not null and b.conclusao          is null and b.fim-sysdate+1 <=p_prazo))
            and (p_ini_i          is null or (p_ini_i       is not null and (trunc(b.inicio)     between p_ini_i and p_ini_f)))
            and (p_fim_i          is null or (p_fim_i       is not null and (trunc(b.fim)        between p_fim_i and p_fim_f)))
            and (Nvl(p_atraso,'N') = 'N'  or (p_atraso      = 'S'       and b.conclusao          is null and b.fim-sysdate<0))
            and (p_unidade        is null or (p_unidade     is not null and b.sq_unidade         = p_unidade))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and ((p_tipo         = 1     and Nvl(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and Nvl(b1.sigla,'-') = 'AT'   and b.solicitante        = p_pessoa and a.consulta_opiniao = 'S' and b.opiniao is null) or
                 (p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA') or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0)
                );
   Elsif p_restricao = 'PJEXEC' or p_restricao = 'OREXEC' Then
      -- Recupera as demandas que o usu�rio pode ver
      open p_result for
         select b.sq_siw_solicitacao, d.titulo
           from siw_solicitacao               b,
                siw_tramite   b1,
                pj_projeto    d
          where (b.sq_siw_tramite     = b1.sq_siw_tramite)
            and (b.sq_siw_solicitacao = d.sq_siw_solicitacao)
            and b.sq_menu        = p_menu
            and Nvl(b1.sigla,'-') = 'EE'
            and acesso(b.sq_siw_solicitacao,p_pessoa) > 15;
   Elsif p_restricao = 'PJLIST' or p_restricao = 'ORLIST' Then
      -- Recupera as demandas que o usu�rio pode ver
      open p_result for
         select b.sq_siw_solicitacao, d.titulo
           from siw_solicitacao               b,
                siw_tramite   b1,
                pj_projeto    d
          where (b.sq_siw_tramite     = b1.sq_siw_tramite)
            and (b.sq_siw_solicitacao = d.sq_siw_solicitacao)
            and b.sq_menu         = p_menu
            and Nvl(b1.sigla,'-') <> 'CA'
            and (acesso(b.sq_siw_solicitacao,p_pessoa) > 0 or
                 InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0
                );
   Elsif p_restricao = 'PJLISTCAD' or p_restricao = 'ORLISTCAD' Then
      -- Recupera as demandas que o usu�rio pode ver
      open p_result for
         select b.sq_siw_solicitacao, d.titulo
           from siw_solicitacao               b,
                siw_tramite   b1,
                pj_projeto    d
          where (b.sq_siw_tramite     = b1.sq_siw_tramite)
            and (b.sq_siw_solicitacao = d.sq_siw_solicitacao)
            and b.sq_menu         = p_menu
            and Nvl(b1.sigla,'-') not in ('CA','AT')
            and (acesso(b.sq_siw_solicitacao,p_pessoa) > 0 or
                 InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0
                );
   Else -- Trata a vincula��o entre servi�os
      -- Recupera as solicita��es que o usu�rio pode ver
      open p_result for
         select b.sq_siw_solicitacao,
                decode(d.sq_siw_solicitacao,null,decode(e.sq_siw_solicitacao,null,null,e.titulo),d.titulo) titulo, 
                nvl(f.existe,0) qtd_projeto
           from siw_menu        a,
                siw_modulo      a1,
                siw_menu_relac  a2,
                siw_solicitacao b,
                siw_menu        b2,
                siw_modulo      b3,
                pj_projeto      d,
                (select x.sq_siw_solicitacao, x.codigo_interno, x.vincula_demanda, 
                        x.vincula_projeto, x.vincula_viagem,
                        w.nome_resumido||' - '||decode(z.sq_cc,null,k.titulo,z.nome)||' ('||to_char(x.inicio,'dd/mm/yyyy')||'-'||to_char(x.fim,'dd/mm/yyyy')||')' as titulo
                   from ac_acordo       x,
                        co_pessoa       w,
                        siw_solicitacao y,
                        ct_cc           z,
                        pj_projeto      k
                  where x.outra_parte        = w.sq_pessoa
                    and x.sq_siw_solicitacao = y.sq_siw_solicitacao
                    and y.sq_cc              = z.sq_cc (+)
                    and y.sq_solic_pai       = k.sq_siw_solicitacao (+)
                )               e,
                (select x1.sq_solic_pai, count(*) existe
                   from siw_solicitacao x1,
                        siw_menu        y1 
                  where x1.sq_menu            = y1.sq_menu
                    and y1.sigla              = 'PJCAD'             
                   group by x1.sq_solic_pai
                )               f           
                                                
          where (a.sq_modulo          = a1.sq_modulo)
            and (a.sq_menu            = a2.servico_cliente and
                 a2.servico_cliente   = p_restricao)
            and (a2.servico_fornecedor= b.sq_menu and
                 a2.sq_siw_tramite    = b.sq_siw_tramite and
                 b.sq_menu            = nvl(p_menu, b.sq_menu))
            and (b.sq_menu            = b2.sq_menu)
            and (b2.sq_modulo         = b3.sq_modulo)
            and (b.sq_siw_solicitacao = d.sq_siw_solicitacao (+))
            and (b.sq_siw_solicitacao = e.sq_siw_solicitacao (+))
            and (b.sq_siw_solicitacao = f.sq_solic_pai(+))
            and a.sq_menu        = p_restricao
            and b.sq_menu        = nvl(p_menu, b.sq_menu)
            and ((a1.sigla = 'DM' and b3.sigla = 'AC' and e.vincula_demanda  = 'S') or
                 (a1.sigla = 'PR' and b3.sigla = 'AC' and e.vincula_projeto  = 'S') or
                 (a1.sigla = 'PD' and b3.sigla = 'AC' and e.vincula_viagem   = 'S') or
                 (a1.sigla = 'AC' and b3.sigla = 'PR' and d.vincula_contrato = 'S') or
                 (a1.sigla = 'PD' and b3.sigla = 'PR' and d.vincula_viagem   = 'S') or
                 (a1.sigla = 'FN' and b3.sigla = 'AC')
                )
            and (acesso(b.sq_siw_solicitacao,p_pessoa) > 0 or
                 InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0
                )
         order by 2;         
   End If;
end SP_GetSolicList;
/
