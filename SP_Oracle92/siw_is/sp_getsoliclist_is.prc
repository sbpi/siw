create or replace procedure SP_GetSolicList_IS
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
    p_projeto      in number   default null,
    p_atividade    in number   default null,
    p_programa     in varchar2 default null,
    p_codigo       in varchar2 default null,
    p_sq_orprior   in number   default null,
    p_cd_subacao   in varchar2 default null,
    p_result       out sys_refcursor) is
    
    l_item       varchar2(18);
    l_fase       varchar2(200) := p_fase ||',';
    x_fase       varchar2(200) := '';
    
    l_resp_unid  varchar2(10000) :='';
    
    -- cursor que recupera as unidades nas quais o usuário informado é titular ou substituto
    cursor c_unidades_resp is
      select distinct sq_unidade
        from siw.eo_unidade a
      start with sq_unidade in (select sq_unidade
                                  from siw.eo_unidade_resp b
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
   
   -- Monta uma string com todas as unidades subordinadas à que o usuário é responsável
   for crec in c_unidades_resp loop
     l_resp_unid := l_resp_unid ||','''||crec.sq_unidade||'''';
   end loop;
   
   If p_restricao = 'ISTCAD' or Substr(p_restricao,1,5) = 'GRIST' Then
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
                d.sq_unidade_resp,    d.assunto,                     d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                case d.prioridade when 0 then 'Alta' when 1 then 'Média' else 'Normal' end nm_prioridade,
                d.ordem,
                d1.titulo,            d1.nm_responsavel,             d1.fn_responsavel,
                d1.em_responsavel,
                b.fim-d.dias_aviso aviso,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                m.titulo nm_projeto,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                p.nome_resumido nm_exec,
                q.sq_projeto_etapa, q.titulo nm_etapa, q.sq_projeto_etapa cd_ordem,
                0 resp_etapa,
                0 sq_acao_ppa, 0 sq_orprioridade
           from siw.siw_menu                                       a 
                   inner        join siw.eo_unidade                a2 on (a.sq_unid_executora        = a2.sq_unidade)
                     left outer join siw.eo_unidade_resp           a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                                      a3.tipo_respons            = 'T'           and
                                                                      a3.fim                     is null
                                                                     )
                     left outer join siw.eo_unidade_resp           a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                      a4.tipo_respons            = 'S'           and
                                                                      a4.fim                     is null
                                                                     )
                   inner             join siw.siw_modulo           a1 on (a.sq_modulo                = a1.sq_modulo)
                   inner             join siw.siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                      inner          join siw.siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                      inner          join (select sq_siw_solicitacao, acesso_is(sq_siw_solicitacao, p_pessoa) acesso
                                             from siw.siw_solicitacao
                                          )                    b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                      inner          join siw.gd_demanda           d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                        left outer   join is_tarefa                d1 on (d.sq_siw_solicitacao       = d1.sq_siw_solicitacao)
                        inner        join siw.eo_unidade           e  on (d.sq_unidade_resp          = e.sq_unidade)
                          left outer join siw.eo_unidade_resp      e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                      e1.tipo_respons            = 'T'           and
                                                                      e1.fim                     is null
                                                                     )
                          left outer join siw.eo_unidade_resp      e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                      e2.tipo_respons            = 'S'           and
                                                                      e2.fim                     is null
                                                                     )
                      inner          join siw.co_cidade            f  on (b.sq_cidade_origem         = f.sq_cidade)
                      left outer     join siw.pj_projeto           m  on (b.sq_solic_pai             = m.sq_siw_solicitacao)
                      left outer     join siw.ct_cc                n  on (b.sq_cc                    = n.sq_cc)
                      left outer     join siw.co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                        inner        join siw.sg_autenticacao      o1 on (o.sq_pessoa                = o1.sq_pessoa)
                          inner      join siw.eo_unidade           o2 on (o1.sq_unidade              = o2.sq_unidade)
                      left outer     join siw.co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                   left outer        join siw.eo_unidade           c  on (a.sq_unid_executora        = c.sq_unidade)
                   left outer        join siw.pj_etapa_demanda     i  on (b.sq_siw_solicitacao       = i.sq_siw_solicitacao)
                      left outer     join siw.pj_projeto_etapa     q  on (i.sq_projeto_etapa         = q.sq_projeto_etapa)
                   inner             join (select sq_siw_solicitacao, max(sq_siw_solic_log) chave 
                                             from siw.siw_solic_log
                                           group by sq_siw_solicitacao
                                          )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                     left outer      join siw.gd_demanda_log       k  on (j.chave                    = k.sq_siw_solic_log)
                       left outer    join siw.sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
          where a.sq_menu        = p_menu
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
            and (p_pais           is null or (p_pais        is not null and f.sq_pais            = p_pais))
            and (p_regiao         is null or (p_regiao      is not null and f.sq_regiao          = p_regiao))
            and (p_cidade         is null or (p_cidade      is not null and f.sq_cidade          = p_cidade))
            and (p_usu_resp       is null or (p_usu_resp    is not null and b.executor           = p_usu_resp))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and d.concluida          = 'N' and l.sq_unidade = p_uorg_resp))
            and (p_projeto        is null or (p_projeto     is not null and b.sq_solic_pai       = p_projeto))
            and (p_atividade      is null or (p_atividade   is not null and i.sq_projeto_etapa   = p_atividade))
            and (p_uf             is null or (p_uf          is not null and f.co_uf              = p_uf))
            and (p_assunto        is null or (p_assunto     is not null and siw.acentos(d.assunto,null) like '%'||siw.acentos(p_assunto,null)||'%'))
            and (p_palavra        is null or (p_palavra     is not null and siw.acentos(b.palavra_chave,null) like '%'||siw.acentos(p_palavra,null)||'%'))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (p_prazo          is null or (p_prazo       is not null and d.concluida          = 'N' and b.fim-sysdate+1 <=p_prazo))
            and (p_prioridade     is null or (p_prioridade  is not null and d.prioridade         = p_prioridade))
            and (p_ini_i          is null or (p_ini_i       is not null and b.inicio             between p_ini_i and p_ini_f))
            and (p_fim_i          is null or (p_fim_i       is not null and b.fim                between p_fim_i and p_fim_f))
            and (Nvl(p_atraso,'N') = 'N'  or (p_atraso      = 'S'       and d.concluida          = 'N' and b.fim+1-sysdate<0))
            and (p_proponente     is null or (p_proponente  is not null and siw.acentos(d.proponente,null) like '%'||siw.acentos(p_proponente,null)||'%'))
            and (p_unidade        is null or (p_unidade     is not null and d.sq_unidade_resp    = p_unidade))
            and (p_prioridade     is null or (p_prioridade  is not null and d.prioridade         = p_prioridade))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and ((p_tipo         = 1     and Nvl(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and Nvl(b1.sigla,'-') <> 'CI'  and b.executor           = p_pessoa and d.concluida = 'N') or
                 (p_tipo         = 2     and Instr('CI,AT,CA', Nvl(b1.sigla,'-')) = 0 and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA'  and b2.acesso > 0) or
                 (p_tipo         = 4     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 5)
                );
   Elsif p_restricao = 'ISACAD' or Substr(p_restricao,1,5) = 'GRISA' Then
      -- Recupera as ações que o usuário pode ver
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
                case d.prioridade when 0 then 'Alta' when 1 then 'Média' else 'Normal' end nm_prioridade,
                b.fim-d.dias_aviso aviso,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido nm_solic,
                p.nome_resumido nm_exec,
                Nvl(q.existe,0) resp_etapa,
                r.cd_programa,        r.cd_acao,                     r.cd_subacao,
                upper(r.nm_coordenador) nm_coordenador,              r1.cd_unidade,
                r1.cd_unidade||'.'||r.cd_programa||'.'||r.cd_acao cd_acao_completa,
                r2.previsao_ano,      r2.atual_ano,                  r2.real_ano,
                r2.flag_alteracao dt_carga_financ
           from siw.siw_menu                                       a 
                   inner        join siw.eo_unidade                a2 on (a.sq_unid_executora        = a2.sq_unidade)
                     left outer join siw.eo_unidade_resp           a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                                      a3.tipo_respons            = 'T'           and
                                                                      a3.fim                     is null
                                                                     )
                     left outer join siw.eo_unidade_resp           a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                      a4.tipo_respons            = 'S'           and
                                                                      a4.fim                     is null
                                                                     )
                   inner             join siw.siw_modulo           a1 on (a.sq_modulo                = a1.sq_modulo)
                   inner             join siw.siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                      inner          join siw.siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                      inner          join (select sq_siw_solicitacao, acesso_is(sq_siw_solicitacao, p_pessoa) acesso
                                             from siw.siw_solicitacao
                                          )                        b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                      inner          join siw.pj_projeto           d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                        inner        join siw.eo_unidade           e  on (d.sq_unidade_resp          = e.sq_unidade)
                          left outer join siw.eo_unidade_resp e1 on (e.sq_unidade             = e1.sq_unidade and
                                                                 e1.tipo_respons          = 'T'           and
                                                                 e1.fim                   is null
                                                                )
                          left outer join siw.eo_unidade_resp e2 on (e.sq_unidade             = e2.sq_unidade and
                                                                 e2.tipo_respons          = 'S'           and
                                                                 e2.fim                   is null
                                                                )
                        left outer   join is_acao              r  on (d.sq_siw_solicitacao       = r.sq_siw_solicitacao)
                          left outer join is_sig_acao          r1 on (r.cd_programa              = r1.cd_programa  and
                                                                      r.cd_acao                  = r1.cd_acao      and
                                                                      r.cd_subacao               = r1.cd_subacao   and
                                                                      r.cliente                  = r1.cliente      and
                                                                      r.ano                      = r1.ano)
                        left outer join is_sig_dado_financeiro r2 on (r1.cd_programa             = r2.cd_programa  and
                                                                      r1.cd_acao                 = r2.cd_acao      and
                                                                      r1.cd_subacao              = r2.cd_subacao   and
                                                                      r1.cliente                 = r2.cliente      and
                                                                      r1.ano                     = r2.ano)
                      inner          join siw.co_cidade            f  on (b.sq_cidade_origem         = f.sq_cidade)
                      left outer     join siw.ct_cc                n  on (b.sq_cc                    = n.sq_cc)
                      left outer     join siw.co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                      left outer     join siw.co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                      left outer     join (select sq_siw_solicitacao, count(*) existe
                                             from siw.pj_projeto_etapa                a
                                                  left outer join siw.eo_unidade_resp b on (a.sq_unidade = b.sq_unidade and
                                                                                        b.fim        is null        and
                                                                                        b.sq_pessoa  = p_pessoa
                                                                                       )
                                            where (a.sq_pessoa         = p_pessoa or
                                                   b.sq_unidade_resp   is not null)
                                           group  by a.sq_siw_solicitacao
                                          )                    q on (b.sq_siw_solicitacao = q.sq_siw_solicitacao)
                   left outer        join siw.eo_unidade           c  on (a.sq_unid_executora        = c.sq_unidade)
                   inner             join (select sq_siw_solicitacao, max(sq_siw_solic_log) chave 
                                             from siw.siw_solic_log
                                           group by sq_siw_solicitacao
                                          )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                     left outer      join siw.pj_projeto_log       k  on (j.chave                    = k.sq_siw_solic_log)
                       left outer    join siw.sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
                   left outer        join (select sq_acao, count(*) qtd_restricao 
                                             from is_restricao
                                           group by sq_acao
                                          )                    s on (s.sq_acao                       = b.sq_siw_solicitacao)
  
          where a.sq_menu        = p_menu
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
            and (p_programa       is null or (p_programa    is not null and r.cd_programa        = p_programa))
            and (p_codigo         is null or (p_codigo      is not null and r.cd_acao            = p_codigo))
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and r.sq_isprojeto       = p_sq_orprior))
            and (p_pais           is null or (p_pais        is not null and f.sq_pais            = p_pais))
            and (p_regiao         is null or (p_regiao      is not null and f.sq_regiao          = p_regiao))
            and (p_cidade         is null or (p_cidade      is not null and f.sq_cidade          = p_cidade))
            and (p_usu_resp       is null or (p_usu_resp    is not null and b.executor           = p_usu_resp))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and d.concluida          = 'N' and l.sq_unidade = p_uorg_resp))
            and (p_projeto        is null or (p_projeto     is not null and b.sq_siw_solicitacao = p_projeto))
            and (p_uf             is null or (p_uf          is not null and f.co_uf              = p_uf))
            and (p_assunto        is null or (p_assunto     is not null and siw.acentos(d.titulo,null) like '%'||siw.acentos(p_assunto,null)||'%'))
            and (p_palavra        is null or (p_palavra     is not null and siw.acentos(b.palavra_chave,null) like '%'||siw.acentos(p_palavra,null)||'%'))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (p_prazo          is null or (p_prazo       is not null and d.concluida          = 'N' and b.fim-sysdate+1 <=p_prazo))
            and (p_ini_i          is null or (p_ini_i       is not null and b.inicio             between p_ini_i and p_ini_f))
            and (p_fim_i          is null or (p_fim_i       is not null and b.fim                between p_fim_i and p_fim_f))
            and (Nvl(p_atraso,'N') = 'N'  or (p_atraso      = 'S'       and d.concluida          = 'N' and b.fim+1-sysdate<0))
            and (Nvl(p_ativo,'N')  = 'N'  or (p_ativo       = 'S'       and s.qtd_restricao       > 0))
            and (p_proponente     is null or (p_proponente  is not null and siw.acentos(d.proponente,null) like '%'||siw.acentos(p_proponente,null)||'%'))
            and (p_unidade        is null or (p_unidade     is not null and d.sq_unidade_resp    = p_unidade))
            and (p_prioridade     is null or (p_prioridade  is not null and d.prioridade         = p_prioridade))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and (p_cd_subacao     is null or (p_cd_subacao  is not null and r1.cd_subacao        = p_cd_subacao))
            and ((p_tipo         = 1     and Nvl(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and Nvl(b1.sigla,'-') <> 'CI'  and b.executor           = p_pessoa and d.concluida = 'N') or
                 (p_tipo         = 2     and Instr('CI,AT,CA', Nvl(b1.sigla,'-')) = 0 and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA'  and b2.acesso > 0) or
                 (p_tipo         = 4     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 5     and Nvl(b1.sigla,'-') <> 'CA')
                )
            ;
   Elsif p_restricao = 'ISPCAD' or Substr(p_restricao,1,5) = 'GRISP' Then
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
                case d.prioridade when 0 then 'Alta' when 1 then 'Média' else 'Normal' end nm_prioridade,
                b.fim-d.dias_aviso aviso,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido nm_solic,
                p.nome_resumido nm_exec,
                Nvl(q.existe,0) resp_etapa,
                r.cd_programa,        r.nm_gerente_programa
           from siw.siw_menu                                       a 
                   inner        join siw.eo_unidade                a2 on (a.sq_unid_executora        = a2.sq_unidade)
                     left outer join siw.eo_unidade_resp           a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                                      a3.tipo_respons            = 'T'           and
                                                                      a3.fim                     is null
                                                                     )
                     left outer join siw.eo_unidade_resp           a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                      a4.tipo_respons            = 'S'           and
                                                                      a4.fim                     is null
                                                                     )
                   inner             join siw.siw_modulo           a1 on (a.sq_modulo                = a1.sq_modulo)
                   inner             join siw.siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                      inner          join siw.siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                      inner          join (select sq_siw_solicitacao, acesso_is(sq_siw_solicitacao, p_pessoa) acesso
                                             from siw.siw_solicitacao
                                          )                    b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                      inner          join siw.pj_projeto           d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                        inner        join siw.eo_unidade           e  on (d.sq_unidade_resp          = e.sq_unidade)
                          left outer join siw.eo_unidade_resp e1 on (e.sq_unidade             = e1.sq_unidade and
                                                                 e1.tipo_respons          = 'T'           and
                                                                 e1.fim                   is null
                                                                )
                          left outer join siw.eo_unidade_resp e2 on (e.sq_unidade             = e2.sq_unidade and
                                                                 e2.tipo_respons          = 'S'           and
                                                                 e2.fim                   is null
                                                                )
                        left outer   join is_programa          r  on (d.sq_siw_solicitacao       = r.sq_siw_solicitacao)
                      inner          join siw.co_cidade            f  on (b.sq_cidade_origem         = f.sq_cidade)
                      left outer     join siw.ct_cc                n  on (b.sq_cc                    = n.sq_cc)
                      left outer     join siw.co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                      left outer     join siw.co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                      left outer     join (select sq_siw_solicitacao, count(*) existe
                                             from siw.pj_projeto_etapa                a
                                                  left outer join siw.eo_unidade_resp b on (a.sq_unidade = b.sq_unidade and
                                                                                        b.fim        is null        and
                                                                                        b.sq_pessoa  = p_pessoa
                                                                                       )
                                            where (a.sq_pessoa         = p_pessoa or
                                                   b.sq_unidade_resp   is not null)
                                           group  by a.sq_siw_solicitacao
                                          )                    q on (b.sq_siw_solicitacao = q.sq_siw_solicitacao)
                   left outer        join siw.eo_unidade           c  on (a.sq_unid_executora        = c.sq_unidade)
                   inner             join (select sq_siw_solicitacao, max(sq_siw_solic_log) chave 
                                             from siw.siw_solic_log
                                           group by sq_siw_solicitacao
                                          )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                     left outer      join siw.pj_projeto_log       k  on (j.chave                    = k.sq_siw_solic_log)
                       left outer    join siw.sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
                    left outer        join (select sq_programa, count(*) qtd_restricao 
                                             from is_restricao
                                           group by sq_programa
                                          )                    s on (s.sq_programa               = b.sq_siw_solicitacao)
          where a.sq_menu        = p_menu
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
            and (p_codigo         is null or (p_codigo      is not null and r.cd_programa        = p_codigo))
            and (p_pais           is null or (p_pais        is not null and f.sq_pais            = p_pais))
            and (p_regiao         is null or (p_regiao      is not null and f.sq_regiao          = p_regiao))
            and (p_cidade         is null or (p_cidade      is not null and f.sq_cidade          = p_cidade))
            and (p_usu_resp       is null or (p_usu_resp    is not null and b.executor           = p_usu_resp))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and d.concluida          = 'N' and l.sq_unidade = p_uorg_resp))
            and (p_projeto        is null or (p_projeto     is not null and b.sq_siw_solicitacao = p_projeto))
            and (p_uf             is null or (p_uf          is not null and f.co_uf              = p_uf))
            and (p_assunto        is null or (p_assunto     is not null and siw.acentos(d.titulo,null) like '%'||siw.acentos(p_assunto,null)||'%'))
            and (p_palavra        is null or (p_palavra     is not null and siw.acentos(b.palavra_chave,null) like '%'||siw.acentos(p_palavra,null)||'%'))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (p_prazo          is null or (p_prazo       is not null and d.concluida          = 'N' and b.fim-sysdate+1 <=p_prazo))
            and (p_ini_i          is null or (p_ini_i       is not null and b.inicio             between p_ini_i and p_ini_f))
            and (p_fim_i          is null or (p_fim_i       is not null and b.fim                between p_fim_i and p_fim_f))
            and (Nvl(p_atraso,'N') = 'N'  or (p_atraso      = 'S'       and d.concluida          = 'N' and b.fim+1-sysdate<0))
            and (Nvl(p_ativo,'N')  = 'N'  or (p_ativo       = 'S'       and s.qtd_restricao       > 0))
            and (p_proponente     is null or (p_proponente  is not null and siw.acentos(d.proponente,null) like '%'||siw.acentos(p_proponente,null)||'%'))
            and (p_unidade        is null or (p_unidade     is not null and d.sq_unidade_resp    = p_unidade))
            and (p_prioridade     is null or (p_prioridade  is not null and d.prioridade         = p_prioridade))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and ((p_tipo         = 1     and Nvl(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and Nvl(b1.sigla,'-') <> 'CI'  and b.executor           = p_pessoa and d.concluida = 'N') or
                 (p_tipo         = 2     and Instr('CI,AT,CA', Nvl(b1.sigla,'-')) = 0 and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA'  and b2.acesso > 0) or
                 (p_tipo         = 4     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 5     and Nvl(b1.sigla,'-') <> 'CA')
                )
            ;
   Elsif p_restricao = 'PJEXEC' or p_restricao = 'OREXEC' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for 
         select b.sq_siw_solicitacao, d.titulo
           from siw.siw_solicitacao               b
                   inner   join siw.siw_tramite   b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                   inner   join siw.pj_projeto    d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
          where b.sq_menu        = p_menu
            and Nvl(b1.sigla,'-') = 'EE' 
            and acesso_is(b.sq_siw_solicitacao,p_pessoa) > 15;
   Elsif p_restricao = 'PJLIST' or p_restricao = 'ORLIST' Then
      -- Recupera os projetos que não estão na fase de cadastramento
      open p_result for 
         select b.sq_siw_solicitacao, d.titulo
           from siw.siw_solicitacao               b
                   inner   join siw.siw_tramite   b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                   inner   join siw.pj_projeto    d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
          where b.sq_menu         = p_menu
            and Nvl(b1.sigla,'-') <> 'CA' 
            and (acesso_is(b.sq_siw_solicitacao,p_pessoa) > 0 or
                 InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0
                );
   Elsif p_restricao = 'PJLISTCAD' or p_restricao = 'ORLISTCAD' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for 
         select b.sq_siw_solicitacao, d.titulo
           from siw.siw_solicitacao               b
                   inner   join siw.siw_tramite   b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                   inner   join siw.pj_projeto    d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
          where b.sq_menu         = p_menu
            and Nvl(b1.sigla,'-') not in ('CA','AT')
            and (acesso_is(b.sq_siw_solicitacao,p_pessoa) > 0 or
                 InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0
                );
   End If;
end SP_GetSolicList_IS;
/
