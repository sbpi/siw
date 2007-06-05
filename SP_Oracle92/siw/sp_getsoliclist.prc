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
    p_result       out sys_refcursor) is
    
    l_item       varchar2(18);
    l_fase       varchar2(200) := p_fase ||',';
    x_fase       varchar2(200) := '';
    
    l_resp_unid  varchar2(10000) :='';
    
    -- cursor que recupera as unidades nas quais o usuário informado é titular ou substituto
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
   
   -- Monta uma string com todas as unidades subordinadas à que o usuário é responsável
   for crec in c_unidades_resp loop
     l_resp_unid := l_resp_unid ||','''||crec.sq_unidade||'''';
   end loop;
   
   If p_restricao = 'GDCAD'            or p_restricao = 'GDACOMP'           or
      p_restricao = 'GDPCAD'           or p_restricao = 'GDPACOMP'          or
      Substr(p_restricao,1,4) = 'GRDM' or p_restricao = 'ORPCAD'            or 
      p_restricao = 'ORPACOMP'         or Substr(p_restricao,1,4) = 'GRORP' or
      p_restricao = 'GDPCADET' Then
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
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.sq_unidade_resp,    d.assunto,                     d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                d.sq_siw_restricao,
                case d.prioridade when 0 then 'Alta' when 1 then 'Média' else 'Normal' end nm_prioridade,
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
                q.sq_projeto_etapa, q.titulo nm_etapa,
                MontaOrdem(q.sq_projeto_etapa) cd_ordem,
                0 resp_etapa,
                0 sq_acao_ppa, 0 sq_orprioridade
           from siw_menu                                       a 
                   inner        join eo_unidade                a2 on (a.sq_unid_executora          = a2.sq_unidade)
                     left       join eo_unidade_resp           a3 on (a2.sq_unidade                = a3.sq_unidade and
                                                                      a3.tipo_respons              = 'T'           and
                                                                      a3.fim                       is null
                                                                     )
                     left       join eo_unidade_resp           a4 on (a2.sq_unidade                = a4.sq_unidade and
                                                                      a4.tipo_respons              = 'S'           and
                                                                      a4.fim                       is null
                                                                     )
                   inner             join siw_modulo           a1 on (a.sq_modulo                  = a1.sq_modulo)
                   inner             join siw_solicitacao      b  on (a.sq_menu                    = b.sq_menu)
                      inner          join siw_tramite          b1 on (b.sq_siw_tramite             = b1.sq_siw_tramite)
                      inner          join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                             from siw_solicitacao
                                          )                    b2 on (b.sq_siw_solicitacao         = b2.sq_siw_solicitacao)
                      inner          join gd_demanda           d  on (b.sq_siw_solicitacao         = d.sq_siw_solicitacao)
                        inner        join eo_unidade           e  on (d.sq_unidade_resp            = e.sq_unidade)
                          left       join eo_unidade_resp      e1 on (e.sq_unidade                 = e1.sq_unidade and
                                                                      e1.tipo_respons              = 'T'           and
                                                                      e1.fim                       is null
                                                                     )
                          left       join eo_unidade_resp      e2 on (e.sq_unidade                 = e2.sq_unidade and
                                                                      e2.tipo_respons              = 'S'           and
                                                                      e2.fim                       is null
                                                                     )
                      inner          join co_cidade            f  on (b.sq_cidade_origem           = f.sq_cidade)
                      left           join pj_projeto           m  on (b.sq_solic_pai               = m.sq_siw_solicitacao)
                      left           join ct_cc                n  on (b.sq_cc                      = n.sq_cc)
                      left           join co_pessoa            o  on (b.solicitante                = o.sq_pessoa)
                        inner        join sg_autenticacao      o1 on (o.sq_pessoa                  = o1.sq_pessoa)
                          inner      join eo_unidade           o2 on (o1.sq_unidade                = o2.sq_unidade)
                      left           join co_pessoa            p  on (b.executor                   = p.sq_pessoa)
                   left              join eo_unidade           c  on (a.sq_unid_executora          = c.sq_unidade)
                   left              join pj_etapa_demanda     i  on (b.sq_siw_solicitacao        = i.sq_siw_solicitacao)
                      left           join pj_projeto_etapa     q  on (i.sq_projeto_etapa           = q.sq_projeto_etapa)
                   inner             join (select sq_siw_solicitacao, max(sq_siw_solic_log) chave 
                                             from siw_solic_log
                                           group by sq_siw_solicitacao
                                          )                    j  on (b.sq_siw_solicitacao         = j.sq_siw_solicitacao)
                     left            join gd_demanda_log       k  on (j.chave                      = k.sq_siw_solic_log)
                       left          join sg_autenticacao      l  on (k.destinatario               = l.sq_pessoa)
          where a.sq_menu        = p_menu
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao   = p_chave))
            and (p_pais           is null or (p_pais        is not null and f.sq_pais              = p_pais))
            and (p_regiao         is null or (p_regiao      is not null and f.sq_regiao            = p_regiao))
            and (p_cidade         is null or (p_cidade      is not null and f.sq_cidade            = p_cidade))
            and (p_usu_resp       is null or (p_usu_resp    is not null and (b.executor            = p_usu_resp or 0 < (select count(*) from gd_demanda_log where destinatario = p_usu_resp and sq_siw_solicitacao = b.sq_siw_solicitacao))))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and d.concluida            = 'N' and l.sq_unidade = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc                = p_sqcc))
            and (p_projeto        is null or (p_projeto     is not null and b.sq_solic_pai         = p_projeto))
            and (p_atividade      is null or (p_atividade   is not null and i.sq_projeto_etapa     = p_atividade))
            and (p_uf             is null or (p_uf          is not null and f.co_uf                = p_uf))
            and (p_assunto        is null or (p_assunto     is not null and acentos(d.assunto,null) like '%'||acentos(p_assunto,null)||'%'))
            and (p_palavra        is null or (p_palavra     is not null and acentos(b.palavra_chave,null) like '%'||acentos(p_palavra,null)||'%'))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,b.sq_siw_tramite) > 0))
            and (p_prazo          is null or (p_prazo       is not null and d.concluida            = 'N' and b.fim-sysdate+1 <=p_prazo))
            and (p_prioridade     is null or (p_prioridade  is not null and d.prioridade           = p_prioridade))
            and (p_ini_i          is null or (p_ini_i       is not null and (Nvl(b1.sigla,'-')     <> 'AT' and b.inicio between p_ini_i and p_ini_f) or (Nvl(b1.sigla,'-') = 'AT' and d.inicio_real between p_ini_i and p_ini_f)))
            and (p_fim_i          is null or (p_fim_i       is not null and (Nvl(b1.sigla,'-')     <> 'AT' and b.fim                between p_fim_i and p_fim_f) or (Nvl(b1.sigla,'-') = 'AT' and d.fim_real between p_fim_i and p_fim_f)))
            and (Nvl(p_atraso,'N') = 'N'  or (p_atraso      = 'S'       and d.concluida            = 'N' and b.fim+1-sysdate<0))
            and (p_proponente     is null or (p_proponente  is not null and acentos(d.proponente,null) like '%'||acentos(p_proponente,null)||'%'))
            and (p_unidade        is null or (p_unidade     is not null and d.sq_unidade_resp      = p_unidade))
            and (p_prioridade     is null or (p_prioridade  is not null and d.prioridade           = p_prioridade))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante          = p_solicitante))
            and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and d.sq_demanda_pai       = p_sq_acao_ppa))
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and d.sq_siw_restricao     = p_sq_orprior))            
            and ((p_tipo         = 1     and Nvl(b1.sigla,'-') = 'CI'   and b.cadastrador          = p_pessoa) or
                 (p_tipo         = 2     and Nvl(b1.sigla,'-') <> 'CI'  and b.executor             = p_pessoa and d.concluida = 'N') or
                 --(p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA'  and b2.acesso > 0) or
                 (p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA'  and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 5     and Nvl(b1.sigla,'-') <> 'CA') or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0)
                )
             and ((p_restricao <> 'GRDMETAPA'    and p_restricao <> 'GRDMPROP' and
                   p_restricao <> 'GRDMRESPATU'  and p_restricao <> 'GDPCADET'
                  ) or
                  ((p_restricao = 'GRDMETAPA'    and MontaOrdem(q.sq_projeto_etapa)  is not null) or
                   (p_restricao = 'GRDMPROP'     and d.proponente                    is not null) or
                   (p_restricao = 'GRDMRESPATU'  and b.executor                      is not null) or
                   (p_restricao = 'GDPCADET'     and q.sq_projeto_etapa              is null and d.sq_siw_restricao is null)
                  )
                 );
   Elsif substr(p_restricao,1,5) = 'PJCAD' or p_restricao = 'PJACOMP' or Substr(p_restricao,1,4) = 'GRPR' or 
         p_restricao = 'ORCAD'             or p_restricao = 'ORACOMP' or Substr(p_restricao,1,4) = 'GROR' Then
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
                case d.prioridade when 0 then 'Alta' when 1 then 'Média' else 'Normal' end nm_prioridade,
                d1.nome nm_prop,      d1.nome_resumido nm_prop_res,
                coalesce(d2.orc_previsto,0) as orc_previsto, coalesce(d2.orc_real,0) as orc_real, 
                b.fim-d.dias_aviso aviso,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido_ind nm_solic_ind,
                p.nome_resumido nm_exec,  p.nome_resumido_ind nm_exec_ind,
                Nvl(q.existe,0) resp_etapa,
                r.sq_acao_ppa, r.sq_orprioridade,
                SolicRestricao(b.sq_siw_solicitacao) as restricao,
                calculaIGE(d.sq_siw_solicitacao) as ige, calculaIDE(d.sq_siw_solicitacao)  as ide,
                calculaIGC(d.sq_siw_solicitacao) as igc, calculaIDC(d.sq_siw_solicitacao)  as idc,
                case when b.sq_solic_pai is not null
                     then case when s.sq_acordo is not null
                               then 'AC: '||s.cd_acordo
                               else case when u.sq_siw_solicitacao is not null
                                         then 'PR: '||u.codigo_interno
                                         else null
                                    end
                          end
                     else null
                end as cd_vinculacao
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
                      inner          join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                             from siw_solicitacao
                                          )                    b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                      inner          join pj_projeto           d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                        left         join co_pessoa            d1 on (d.outra_parte              = d1.sq_pessoa)
                        left         join (select y.sq_siw_solicitacao, sum(x.valor_previsto) as orc_previsto, sum(x.valor_real) as orc_real
                                             from pj_rubrica_cronograma x
                                                  inner join pj_rubrica y on (x.sq_projeto_rubrica = y.sq_projeto_rubrica)
                                            where y.ativo = 'S'
                                           group by y.sq_siw_solicitacao
                                          )                    d2 on (d.sq_siw_solicitacao       = d2.sq_siw_solicitacao)
                        inner        join eo_unidade           e  on (d.sq_unidade_resp          = e.sq_unidade)
                          left       join eo_unidade_resp      e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                      e1.tipo_respons            = 'T'           and
                                                                      e1.fim                     is null
                                                                     )
                          left       join eo_unidade_resp            e2 on (e.sq_unidade         = e2.sq_unidade and
                                                                            e2.tipo_respons      = 'S'           and
                                                                            e2.fim               is null
                                                                           )
                        left         join or_acao              r  on (d.sq_siw_solicitacao       = r.sq_siw_solicitacao)
                      inner          join co_cidade            f  on (b.sq_cidade_origem         = f.sq_cidade)
                      left           join ct_cc                n  on (b.sq_cc                    = n.sq_cc)
                      left           join co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                      left           join co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                      left           join (select sq_siw_solicitacao, count(*) existe
                                             from pj_projeto_etapa                a
                                                  left       join eo_unidade_resp b on (a.sq_unidade = b.sq_unidade and
                                                                                        b.fim        is null        and
                                                                                        b.sq_pessoa  = p_pessoa
                                                                                       )
                                            where (a.sq_pessoa         = p_pessoa or
                                                   b.sq_unidade_resp   is not null)
                                           group  by a.sq_siw_solicitacao
                                          )                    q on (b.sq_siw_solicitacao = q.sq_siw_solicitacao)
                   left              join eo_unidade           c  on (a.sq_unid_executora        = c.sq_unidade)
                   inner             join (select sq_siw_solicitacao, max(sq_siw_solic_log) chave 
                                             from siw_solic_log
                                           group by sq_siw_solicitacao
                                          )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                     left            join pj_projeto_log       k  on (j.chave                    = k.sq_siw_solic_log)
                       left          join sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
                   left              join (select x.sq_siw_solicitacao sq_acordo, v.nome||': '||x.codigo_interno cd_acordo,
                                                w.nome_resumido||' - '||z.nome||' ('||to_char(x.inicio,'dd/mm/yyyy')||'-'||to_char(x.fim,'dd/mm/yyyy')||')' as nm_acordo,
                                                v.sigla
                                           from ac_acordo              x
                                                join   co_pessoa       w on (x.outra_parte        = w.sq_pessoa)
                                                join   siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                                  join ct_cc           z on (y.sq_cc              = z.sq_cc)
                                                  join siw_menu        v on (y.sq_menu            = v.sq_menu)
                                        )                      s  on (b.sq_solic_pai             = s.sq_acordo)
                   left              join siw_solicitacao      t  on (b.sq_solic_pai             = t.sq_siw_solicitacao)
                   left              join pe_programa          u  on (b.sq_solic_pai             = u.sq_siw_solicitacao)
          where a.sq_menu        = p_menu
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
            and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and (r.sq_acao_ppa       = p_sq_acao_ppa or b.sq_solic_pai = p_sq_acao_ppa)))
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and r.sq_orprioridade    = p_sq_orprior))
            and (p_pais           is null or (p_pais        is not null and f.sq_pais            = p_pais))
            and (p_regiao         is null or (p_regiao      is not null and f.sq_regiao          = p_regiao))
            and (p_cidade         is null or (p_cidade      is not null and f.sq_cidade          = p_cidade))
            and (p_usu_resp       is null or (p_usu_resp    is not null and (b.executor          = p_usu_resp or 0 < (select count(*) from pj_projeto_log where destinatario = p_usu_resp and sq_siw_solicitacao = b.sq_siw_solicitacao))))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and d.concluida          = 'N' and l.sq_unidade = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc              = p_sqcc))
            and (p_projeto        is null or (p_projeto     is not null and b.sq_solic_pai       = p_projeto))
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
   Elsif substr(p_restricao,1,3) = 'GCR' or substr(p_restricao,1,3) = 'GCD' or 
         substr(p_restricao,1,3) = 'GCP' or substr(p_restricao,1,3) = 'GCA' or
         substr(p_restricao,1,3) = 'GCB' or substr(p_restricao,1,3) = 'GCC' Then
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
                round(months_between(b.fim,b.inicio)) meses_contrato,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.sq_tipo_acordo,     d.outra_parte,                 d.preposto,
                d.inicio inicio_real, d.fim fim_real,                d.duracao,
                d.valor_inicial,      Nvl(d8.valor,d.valor_atual) valor_atual, d.codigo_interno,
                d.codigo_externo,     d.objeto,                      d.atividades,
                d.produtos,           d.requisitos,                  d.observacao,
                d.dia_vencimento,     d.vincula_projeto,             d.vincula_demanda,
                d.vincula_viagem,     d.aviso_prox_conc,             d.dias_aviso,
                d.empenho,            d.processo,                    d.assinatura,
                d.publicacao,         d.sq_lcfonte_recurso,
                d.limite_variacao,    d.sq_especificacao_despesa,    d.indice_base,
                d.tipo_reajuste,
                retornaAfericaoIndicador(d.sq_eoindicador,d.indice_base) as vl_indice_base,
                round(months_between(d.fim,d.inicio)) meses_acordo,
                case when d.titulo is null then 'Não informado ('||d2.nome_resumido||')' else d.titulo end as nm_acordo,
                case d.tipo_reajuste when 0 then 'Não permite' when 1 then 'Com índice' else 'Sem índice' end nm_tipo_reajuste,
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
                d9.codigo cd_lcfonte_recurso, d9.nome nm_lcfonte_recurso, 
                da.codigo cd_espec_despesa, da.nome nm_espec_despesa,
                db.nome as nm_indicador, db.sigla as sg_indicador,
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
                case when d.titulo is not null
                     then d.titulo
                     else d2.nome_resumido||' ('||to_char(b.inicio,'dd/mm/yy')||'-'||to_char(b.fim,'dd/mm/yy')||')' 
                end as titulo
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
                      inner          join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                             from siw_solicitacao
                                          )                    b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                      inner          join ac_acordo            d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                        inner        join co_forma_pagamento   d7 on (d.sq_forma_pagamento       = d7.sq_forma_pagamento)
                        left         join (select x.sq_siw_solicitacao, sum(z.valor) valor
                                             from ac_acordo_parcela              x
                                                  inner     join fn_lancamento   y on (x.sq_acordo_parcela = y.sq_acordo_parcela)
                                                    inner   join siw_solicitacao z on (y.sq_siw_solicitacao = z.sq_siw_solicitacao)
                                                      inner join siw_tramite     w on (z.sq_siw_tramite     = w.sq_siw_tramite and
                                                                                       nvl(w.sigla,'---')   <> 'CA'
                                                                                      )
                                            where x.quitacao is not null
                                           group by x.sq_siw_solicitacao
                                          )                   d8 on (d.sq_siw_solicitacao        = d8.sq_siw_solicitacao)
                        inner        join ac_tipo_acordo       d1 on (d.sq_tipo_acordo           = d1.sq_tipo_acordo)
                        left         join co_pessoa            d2 on (d.outra_parte              = d2.sq_pessoa)
                          left       join co_pessoa_fisica    d21 on (d2.sq_pessoa               = d21.sq_pessoa)
                          left       join co_pessoa_juridica  d22 on (d2.sq_pessoa               = d22.sq_pessoa)
                        left         join co_pessoa_conta      d4 on (d.outra_parte              = d4.sq_pessoa and
                                                                      d4.ativo                   = 'S' and
                                                                      d4.padrao                  = 'S'
                                                                     )
                          left       join co_agencia           d5 on (d4.sq_agencia              = d5.sq_agencia and
                                                                      d5.ativo                   = 'S'
                                                                     )
                          left       join co_banco             d6 on (d5.sq_banco                = d6.sq_banco and
                                                                      d6.ativo                   = 'S'
                                                                     )
                        left         join co_pessoa            d3 on (d.preposto                 = d3.sq_pessoa)
                        left         join lc_fonte_recurso     d9 on (d.sq_lcfonte_recurso       = d9.sq_lcfonte_recurso)
                        left         join ct_especificacao_despesa da on (d.sq_especificacao_despesa = da.sq_especificacao_despesa)
                        left         join eo_indicador             db on (d.sq_eoindicador       = db.sq_eoindicador)
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
                      left           join ct_cc                n  on (b.sq_cc                    = n.sq_cc)
                      left           join co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                        inner        join sg_autenticacao      o1 on (o.sq_pessoa                = o1.sq_pessoa)
                          inner      join eo_unidade           o2 on (o1.sq_unidade              = o2.sq_unidade)
                      left           join co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                   left              join eo_unidade           c  on (a.sq_unid_executora        = c.sq_unidade)
                   left              join pj_etapa_contrato    i  on (b.sq_siw_solicitacao       = i.sq_siw_solicitacao)
                      left           join pj_projeto_etapa     q  on (i.sq_projeto_etapa         = q.sq_projeto_etapa)                   
                   inner             join (select sq_siw_solicitacao, max(sq_siw_solic_log) chave 
                                             from siw_solic_log
                                           group by sq_siw_solicitacao
                                          )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                     left            join ac_acordo_log        k  on (j.chave                    = k.sq_siw_solic_log)
                       left          join sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
          where a.sq_menu        = p_menu
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
            and (p_atraso         is null or (p_atraso      is not null and (d.titulo is not null and acentos(d.titulo)   like '%'||acentos(p_atraso)||'%' or
                                                                             d.titulo is     null and d2.nome_resumido_ind||')'=substr(p_atraso,instr(p_atraso,'(')+1)
                                                                            )
                                             )
                )
            and (p_proponente     is null or (p_proponente  is not null and (acentos(d2.nome,null)          like '%'||acentos(p_proponente,null)||'%') or 
                                                                            (acentos(d2.nome_resumido,null) like '%'||acentos(p_proponente,null)||'%')
                                             )
                )
            and (p_empenho        is null or (p_empenho     is not null and upper(d.empenho)     = upper(p_empenho)))
            and (p_processo       is null or (p_processo    is not null and upper(d.processo)    = upper(p_processo)))
            and ((p_tipo         = 1     and Nvl(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b.executor = p_pessoa and b.conclusao is null) or
                 --(p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA'  and b2.acesso > 0) or
                 (p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA'  and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b.conclusao is null and b1.ativo = 'S' and b2.acesso > 0)
                )
            and ((instr(p_restricao,'PROJ')    = 0 and
                  instr(p_restricao,'ETAPA')   = 0 and
                  instr(p_restricao,'PROP')    = 0 and
                  instr(p_restricao,'RESPATU') = 0 and
                  instr(p_restricao,'FONTE')   = 0 and
                  instr(p_restricao,'ESPEC')   = 0 and
                  substr(p_restricao,4,2)      <>'CC'
                 ) or 
                 ((instr(p_restricao,'PROJ')    > 0    and b.sq_solic_pai is not null) or
                  (instr(p_restricao,'ETAPA')   > 0    and MontaOrdem(q.sq_projeto_etapa)  is not null) or                 
                  (instr(p_restricao,'PROP')    > 0    and d.outra_parte  is not null) or
                  (instr(p_restricao,'RESPATU') > 0    and b.executor     is not null) or
                  (instr(p_restricao,'FONTE')   > 0    and d.sq_lcfonte_recurso is not null) or
                  (instr(p_restricao,'ESPEC')   > 0    and d.sq_especificacao_despesa is not null) or
                  (substr(p_restricao,4,2)      ='CC'  and b.sq_cc        is not null)
                 )
                );
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
                case coalesce(b1.sigla,'--') when 'AT' then b.valor else 0 end valor_atual,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.pessoa,             d.codigo_interno,              d.sq_acordo_parcela,
                d.sq_forma_pagamento, d.sq_tipo_lancamento,          d.sq_tipo_pessoa,
                d.emissao,            d.vencimento,                  d.quitacao,
                d.codigo_externo,     d.observacao,                  d.valor_imposto,
                d.valor_retencao,     d.valor_liquido,               d.aviso_prox_conc,
                d.dias_aviso,         d.sq_tipo_pessoa,              d.tipo tipo_rubrica,
                case d.tipo when 1 then 'Dotação incial' when 2 then 'Transferência entre rubricas' when 3 then 'Atualização de aplicação' when 4 then 'Entradas' else 'Normal' end nm_tipo_rubrica,
                d2.nome nm_pessoa,    d2.nome_resumido nm_pessoa_resumido,
                d2.nome_resumido_ind nm_pessoa_resumido_ind,
                Nvl(d3.valor,0) valor_doc,
                d4.sq_pessoa_conta,   d4.operacao,                   d4.numero nr_conta,
                d5.sq_agencia,        d5.codigo cd_agencia,          d5.nome nm_agencia,
                d6.sq_banco,          d6.codigo cd_banco,            d6.nome nm_banco,
                d7.sq_forma_pagamento,d7.nome nm_forma_pagamento,    d7.sigla sg_forma_pagamento, 
                d7.ativo st_forma_pagamento,
                Nvl(d9.valor,0) valor_nota,
                b.fim-d.dias_aviso aviso,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                m.codigo_interno cd_acordo, m.objeto obj_acordo,
                m1.ordem or_parcela,
                m2.qtd_nota,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                p.nome_resumido nm_exec,
                q.titulo nm_projeto
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
                      inner          join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                             from siw_solicitacao
                                          )                    b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                      inner          join fn_lancamento        d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                        inner        join co_forma_pagamento   d7 on (d.sq_forma_pagamento       = d7.sq_forma_pagamento)
                        inner        join fn_tipo_lancamento   d1 on (d.sq_tipo_lancamento       = d1.sq_tipo_lancamento)
                        left         join co_pessoa            d2 on (d.pessoa                   = d2.sq_pessoa)
                        left         join (select x.sq_siw_solicitacao, sum(x.valor) valor
                                             from fn_lancamento_doc x
                                            where x.sq_acordo_nota is null
                                           group by x.sq_siw_solicitacao
                                          )                    d3 on (d.sq_siw_solicitacao       = d3.sq_siw_solicitacao)
                        left         join co_pessoa_conta      d4 on (d.pessoa                   = d4.sq_pessoa and
                                                                      d4.ativo                   = 'S' and
                                                                      d4.padrao                  = 'S'
                                                                     )
                          left       join co_agencia           d5 on (d4.sq_agencia              = d5.sq_agencia and
                                                                      d5.ativo                   = 'S'
                                                                     )
                          left       join co_banco             d6 on (d5.sq_banco                = d6.sq_banco and
                                                                      d6.ativo                   = 'S'
                                                                     )
                          left         join (select sq_siw_solicitacao, sum(valor) valor
                                               from fn_lancamento_doc x
                                              where x.sq_acordo_nota is not null
                                             group by sq_siw_solicitacao
                                            )                  d9 on (d.sq_siw_solicitacao       = d9.sq_siw_solicitacao)
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
                      left           join ac_acordo_parcela    m1 on (d.sq_acordo_parcela        = m1.sq_acordo_parcela)
                        left         join (select count(y.sq_acordo_nota) qtd_nota, y.sq_acordo_parcela
                                             from ac_parcela_nota y
                                            group by y.sq_acordo_parcela
                                          )                    m2 on (m1.sq_acordo_parcela       = m2.sq_acordo_parcela)
                      left           join pj_projeto           q  on (b.sq_solic_pai             = q.sq_siw_solicitacao)
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
                     left            join fn_lancamento_log    k  on (j.chave                    = k.sq_siw_solic_log)
                       left          join sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
          where a.sq_menu        = p_menu
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
      -- Recupera as viagens que o usuário pode ver
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
                b.valor,              b.fim-d.dias_aviso aviso,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.sq_unidade_resp,    d.assunto,                     d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                case d.prioridade when 0 then 'Alta' when 1 then 'Média' else 'Normal' end as nm_prioridade,
                d.ordem,
                d1.sq_pessoa sq_prop, d1.tipo tp_missao,             d1.codigo_interno,
                case d1.tipo when 'I' then 'Inicial' when 'P' then 'Prorrogação' else 'Complementação' end as nm_tp_missao,
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
                d5.limite_passagem, d5.limite_diaria,
                to_char(r.saida,'dd/mm/yyyy, hh24:mi:ss') as phpdt_saida, to_char(r.chegada,'dd/mm/yyyy, hh24:mi:ss') as phpdt_chegada,
                pd_retornatrechos(b.sq_siw_solicitacao) as trechos
           from siw_menu                                a
                inner         join eo_unidade           a2 on (a.sq_unid_executora        = a2.sq_unidade)
                  left        join eo_unidade_resp      a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                               a3.tipo_respons            = 'T'           and
                                                               a3.fim                     is null)
                  left        join eo_unidade_resp      a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                               a4.tipo_respons            = 'S'           and
                                                               a4.fim                     is null)
                inner         join siw_modulo           a1 on (a.sq_modulo                = a1.sq_modulo)
                inner         join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                  inner       join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                  inner       join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                      from siw_solicitacao
                                   )                    b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                  inner       join gd_demanda           d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                    inner     join pd_missao            d1 on (d.sq_siw_solicitacao       = d1.sq_siw_solicitacao)
                      inner   join co_pessoa            d2 on (d1.sq_pessoa               = d2.sq_pessoa)
                        inner join co_tipo_vinculo      d3 on (d2.sq_tipo_vinculo         = d3.sq_tipo_vinculo)
                        inner join co_pessoa_fisica     d4 on (d2.sq_pessoa               = d4.sq_pessoa)
                        inner join (select x.sq_unidade, 
                                           coalesce(y.limite_passagem,0) as limite_passagem, 
                                           coalesce(y.limite_diaria,0)   as limite_diaria
                                      from pd_unidade                  x
                                           left join pd_unidade_limite y on (x.sq_unidade = y.sq_unidade and
                                                                             y.ano        = nvl(p_sq_orprior,y.ano)
                                                                            )
                                   )                    d5 on (d.sq_unidade_resp          = d5.sq_unidade)
                      inner   join eo_unidade           e  on (d.sq_unidade_resp          = e.sq_unidade)
                        left  join eo_unidade_resp      e1 on (e.sq_unidade               = e1.sq_unidade and
                                                               e1.tipo_respons            = 'T'           and
                                                               e1.fim                     is null)
                        left  join eo_unidade_resp      e2 on (e.sq_unidade               = e2.sq_unidade and
                                                               e2.tipo_respons            = 'S'           and
                                                               e2.fim                     is null)
                    inner     join co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                      inner   join sg_autenticacao      o1 on (o.sq_pessoa                = o1.sq_pessoa)
                        inner join eo_unidade           o2 on (o1.sq_unidade              = o2.sq_unidade)
                    left      join co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                  left        join eo_unidade           c  on (a.sq_unid_executora        = c.sq_unidade)
                  inner       join (select sq_siw_solicitacao, max(sq_siw_solic_log) chave
                                      from siw_solic_log
                                    group by sq_siw_solicitacao
                                   )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                    left      join gd_demanda_log       k  on (j.chave                    = k.sq_siw_solic_log)
                      left    join sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
                 left         join (select x.sq_siw_solicitacao, sum((y.quantidade*y.valor)) valor_diaria
                                      from siw_solicitacao         x
                                           inner join pd_diaria  y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                    group by x.sq_siw_solicitacao
                                   )                    n  on (b.sq_siw_solicitacao       = n.sq_siw_solicitacao)
                 left         join (select x.sq_siw_solicitacao, sum(y.valor_trecho) valor_trecho
                                      from siw_solicitacao              x
                                           inner join pd_deslocamento y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                    group by x.sq_siw_solicitacao
                                   )                    q  on (b.sq_siw_solicitacao       = q.sq_siw_solicitacao)
                 left         join (select x.sq_siw_solicitacao, min(y.saida) as saida, max(y.chegada) as chegada
                                      from siw_solicitacao            x
                                           inner join pd_deslocamento y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                    group by x.sq_siw_solicitacao
                                   )                    r  on (b.sq_siw_solicitacao       = r.sq_siw_solicitacao)
          where a.sq_menu         = p_menu
            and (p_projeto        is null or (p_projeto     is not null and 0 < (select count(distinct(x1.sq_siw_solicitacao)) from pd_missao_solic x1 , siw_solicitacao y1 where x1.sq_siw_solicitacao = y1.sq_siw_solicitacao and y1.sq_solic_pai = p_projeto and x1.sq_solic_missao = b.sq_siw_solicitacao)))
            and (p_atividade      is null or (p_atividade   is not null and 0 < (select count(distinct(x2.sq_siw_solicitacao)) from pd_missao_solic x2 join pj_etapa_demanda x3 on (x2.sq_siw_solicitacao = x3.sq_siw_solicitacao and x3.sq_projeto_etapa = p_atividade) where x2.sq_solic_missao = b.sq_siw_solicitacao)))
            and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and d1.codigo_interno like '%'||p_sq_acao_ppa||'%'))
            and (p_assunto        is null or (p_assunto     is not null and acentos(b.descricao,null) like '%'||acentos(p_assunto,null)||'%'))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and (p_unidade        is null or (p_unidade     is not null and d.sq_unidade_resp    = p_unidade))
            and (p_proponente     is null or (p_proponente  is not null and (acentos(d2.nome,null)          like '%'||acentos(p_proponente,null)||'%') or
                                                                            (acentos(d2.nome_resumido,null) like '%'||acentos(p_proponente,null)||'%')
                                             )
                )
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and d1.sq_pessoa         = p_sq_orprior))
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
                b.palavra_chave,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                b2.sq_peobjetivo,     b2.sq_plano,                   b2.nome nm_objetivo, 
                b2.sigla sg_objetivo, b2.descricao ds_objetivo,      b2.ativo st_objetivo,
                b3.sq_plano_pai,      b3.titulo nm_plano,            b3.missao, 
                b3.valores,           b3.visao_presente,             b3.visao_futuro, 
                b3.inicio inicio_plano,b3.fim vim_plano,             b3.ativo st_plano,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.codigo_interno cd_programa, d.titulo,              d.ln_programa,
                d.exequivel,          d.inicio_real,                 d.fim_real,
                d.custo_real, 
                d1.nome nm_horizonte, d1.ativo st_horizonte, 
                d7.nome nm_natureza, d7.ativo st_natureza,
                b.fim-d.dias_aviso aviso,
                e.sq_unidade sq_unidade_resp,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
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
                      inner          join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                             from siw_solicitacao
                                          )                    b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
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
                     left            join pe_programa_log      k  on (j.chave                    = k.sq_siw_solic_log)
                       left          join sg_autenticacao      l  on (j.k.destinatario           = l.sq_pessoa)
          where a.sq_menu        = p_menu
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
            and (p_pais           is null or (p_pais        is not null and f.sq_pais            = p_pais))
            and (p_regiao         is null or (p_regiao      is not null and f.sq_regiao          = p_regiao))
            and (p_cidade         is null or (p_cidade      is not null and f.sq_cidade          = p_cidade))
            and (p_usu_resp       is null or (p_usu_resp    is not null and (b.executor          = p_usu_resp or 0 < (select count(*) from ac_acordo_log where destinatario = p_usu_resp and sq_siw_solicitacao = b.sq_siw_solicitacao))))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and b.conclusao          is null and l.sq_unidade = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc              = p_sqcc))
            and (p_projeto        is null or (p_projeto     is not null and b.sq_solic_pai       = p_projeto))
            and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and b.sq_peobjetivo      = p_sq_acao_ppa))
            and (p_sq_orprior     is null or (p_sq_orprior is not null and b2.sq_plano           = p_sq_orprior))
            --and (p_atividade      is null or (p_atividade   is not null and i.sq_projeto_etapa   = p_atividade))
            and (p_uf             is null or (p_uf          is not null and f.co_uf              = p_uf))
            and (p_assunto        is null or (p_assunto     is not null and acentos(d.titulo,null) like '%'||acentos(p_assunto,null)||'%'))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (p_prazo          is null or (p_prazo       is not null and b.conclusao          is null and b.fim-sysdate+1 <=p_prazo))
            and (p_ini_i          is null or (p_ini_i       is not null and b.inicio             between p_ini_i and p_ini_f))
            and (p_fim_i          is null or (p_fim_i       is not null and b.fim                between p_fim_i and p_fim_f))
            and (p_unidade        is null or (p_unidade     is not null and b.sq_unidade         = p_unidade))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and (p_palavra        is null or (p_palavra     is not null and d.codigo_interno     like '%'||p_palavra||'%'))
            and ((p_tipo         = 1     and Nvl(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b.executor = p_pessoa and b.conclusao is null) or
                 --(p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA') or --  and b2.acesso > 0) or
                 --(p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA'  and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b1.ativo          = 'S') -- and b2.acesso > 0)
                )
            and ((instr(p_restricao,'PROJ')    = 0 and
                  instr(p_restricao,'ETAPA')   = 0 and
                  instr(p_restricao,'PROP')    = 0 and
                  instr(p_restricao,'RESPATU') = 0 and
                  substr(p_restricao,4,2)      <>'CC'
                 ) or 
                 ((instr(p_restricao,'PROJ')    > 0    and b.sq_solic_pai is not null) or
                  (instr(p_restricao,'RESPATU') > 0    and b.executor     is not null) or
                  (substr(p_restricao,4,2)      ='CC'  and b.sq_cc        is not null)
                 )
                );
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
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                b3.nome as nm_unid_origem, b3.sigla sg_unid_origem,
                d.numero_original,    d.data_recebimento,            d.numero_documento,
                d.ano,                d.prefixo,                     d.digito,
                d.sq_especie_documento, d.sq_natureza_documento,     d.unidade_autuacao,
                d.interno,            d.data_autuacao,               d.pessoa_origem,
                d.processo,           d.circular,                    d.copias,
                d.volumes,
                d.prefixo||'.'||substr(1000000+d.numero_documento,2,6)||'/'||d.ano||'-'||substr(100+d.digito,2,2) as protocolo,
                case when d.pessoa_origem is null then b3.nome else d2.nome end as nm_origem,
                coalesce(d1.nome,'Irrestrito') as nm_natureza,       d1.sigla sg_natureza,
                d1.descricao ds_natureza,                            d1.ativo st_natureza,
                d2.nome_resumido as nm_res_pessoa_origem,            d2.nome as nm_pessoa_origem,
                d3.sq_tipo_pessoa,
                d4.sq_assunto,
                d5.codigo as cd_assunto,                             d5.descricao as ds_assunto,
                d7.nome nm_especie,   d7.sigla sg_natureza,          d7.ativo st_natureza,
                b.fim-k.dias_aviso aviso,
                e.sq_unidade sq_unidade_resp,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                p.nome_resumido nm_exec,
                q.nome as nm_unidade_posse,                          q.sigla as sg_unidade_posse,
                q1.sq_pessoa titular,                                q2.sq_pessoa substituto,
                r.nome as nm_pessoa_posse,                           r.nome_resumido as nm_res_pessoa_posse,
                case when r.sq_pessoa is null then r.nome else q.nome end as nm_posse
           from siw_menu                                           a 
                   inner        join eo_unidade                    a2 on (a.sq_unid_executora        = a2.sq_unidade)
                     left       join eo_unidade_resp               a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                                          a3.tipo_respons            = 'T'           and
                                                                          a3.fim                     is null
                                                                         )
                     left       join eo_unidade_resp               a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                          a4.tipo_respons            = 'S'           and
                                                                          a4.fim                     is null
                                                                         )
                   inner             join siw_modulo               a1 on (a.sq_modulo                = a1.sq_modulo)
                   inner             join siw_solicitacao          b  on (a.sq_menu                  = b.sq_menu)
                      inner          join siw_tramite              b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                      inner          join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                             from siw_solicitacao
                                          )                        b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                      inner          join eo_unidade               b3 on (b.sq_unidade               = b3.sq_unidade)
                      inner          join pa_documento             d on (b.sq_siw_solicitacao        = d.sq_siw_solicitacao)
                        left         join pa_natureza_documento    d1 on (d.sq_natureza_documento    = d1.sq_natureza_documento)
                        left         join co_pessoa                d2 on (d.pessoa_origem            = d2.sq_pessoa)
                          left       join co_tipo_pessoa           d3 on (d2.sq_tipo_pessoa          = d3.sq_tipo_pessoa)
                        inner        join pa_documento_assunto     d4 on (d.sq_siw_solicitacao       = d4.sq_siw_solicitacao and
                                                                          d4.principal               = 'S'
                                                                         )
                          inner      join pa_assunto               d5 on (d4.sq_assunto              = d5.sq_assunto)
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
          where a.sq_menu        = p_menu
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
            and (p_pais           is null or (p_pais        is not null and f.sq_pais            = p_pais))
            and (p_regiao         is null or (p_regiao      is not null and f.sq_regiao          = p_regiao))
            and (p_cidade         is null or (p_cidade      is not null and f.sq_cidade          = p_cidade))
            and (p_usu_resp       is null or (p_usu_resp    is not null and (b.executor          = p_usu_resp or 0 < (select count(*) from ac_acordo_log where destinatario = p_usu_resp and sq_siw_solicitacao = b.sq_siw_solicitacao))))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and b.conclusao          is null and l.sq_unidade = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc              = p_sqcc))
            and (p_projeto        is null or (p_projeto     is not null and b.sq_solic_pai       = p_projeto))
            --and (p_atividade      is null or (p_atividade   is not null and i.sq_projeto_etapa   = p_atividade))
            and (p_uf             is null or (p_uf          is not null and f.co_uf              = p_uf))
            --and (p_assunto        is null or (p_assunto     is not null and acentos(d.titulo,null) like '%'||acentos(p_assunto,null)||'%'))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (p_prazo          is null or (p_prazo       is not null and b.conclusao          is null and b.fim-sysdate+1 <=p_prazo))
            and (p_ini_i          is null or (p_ini_i       is not null and b.inicio             between p_ini_i and p_ini_f))
            and (p_fim_i          is null or (p_fim_i       is not null and b.fim                between p_fim_i and p_fim_f))
            and (p_unidade        is null or (p_unidade     is not null and b.sq_unidade         = p_unidade))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and (p_palavra        is null or (p_palavra     is not null and d.numero_documento   like '%'||p_palavra||'%'))
            and ((p_tipo         = 1     and Nvl(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
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
                 ((instr(p_restricao,'PROJ')    > 0    and b.sq_solic_pai is not null) or
                  (instr(p_restricao,'RESPATU') > 0    and b.executor     is not null) or
                  (substr(p_restricao,4,2)      ='CC'  and b.sq_cc        is not null)
                 )
                );                
   Elsif p_restricao = 'PJEXEC' or p_restricao = 'OREXEC' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for 
         select b.sq_siw_solicitacao, d.titulo
           from siw_solicitacao               b
                   inner   join siw_tramite   b1 on (b.sq_siw_tramite     = b1.sq_siw_tramite)
                   inner   join pj_projeto    d  on (b.sq_siw_solicitacao = d.sq_siw_solicitacao)
          where b.sq_menu        = p_menu
            and Nvl(b1.sigla,'-') = 'EE' 
            and acesso(b.sq_siw_solicitacao,p_pessoa) > 15;
   Elsif p_restricao = 'PJLIST' or p_restricao = 'ORLIST' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for 
         select b.sq_siw_solicitacao, d.titulo
           from siw_solicitacao                b
                inner     join siw_tramite     b1 on (b.sq_siw_tramite     = b1.sq_siw_tramite)
                inner     join pj_projeto      d  on (b.sq_siw_solicitacao = d.sq_siw_solicitacao)
                left      join pe_objetivo     e  on (b.sq_peobjetivo      = e.sq_peobjetivo)
                left      join siw_solicitacao f  on (b.sq_solic_pai       = f.sq_siw_solicitacao)
                  left    join pe_objetivo     f1 on (f.sq_peobjetivo      = f1.sq_peobjetivo)
          where b.sq_menu         = p_menu
            and (p_projeto        is null or (p_projeto     is not null and b.sq_solic_pai       = p_projeto))
            and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and (e.sq_peobjetivo     = p_sq_acao_ppa or f1.sq_peobjetivo = p_sq_acao_ppa)))
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and (e.sq_plano          = p_sq_orprior  or f1.sq_plano = p_sq_orprior)))
            and Nvl(b1.sigla,'-') <> 'CA' 
            and (acesso(b.sq_siw_solicitacao,p_pessoa) > 0 or
                 InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0
                );
   Elsif p_restricao = 'PJLISTCAD' or p_restricao = 'ORLISTCAD' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for 
         select b.sq_siw_solicitacao, d.titulo
           from siw_solicitacao               b
                   inner   join siw_tramite   b1 on (b.sq_siw_tramite     = b1.sq_siw_tramite)
                   inner   join pj_projeto    d  on (b.sq_siw_solicitacao = d.sq_siw_solicitacao)
          where b.sq_menu         = p_menu
            and Nvl(b1.sigla,'-') not in ('CA','AT')
            and (acesso(b.sq_siw_solicitacao,p_pessoa) > 0 or
                 InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0
                );
   Else -- Trata a vinculação entre serviços
      -- Recupera as solicitações que o usuário pode ver
      open p_result for 
         select b.sq_siw_solicitacao, 
                case when d.sq_siw_solicitacao is not null 
                     then d.titulo
                     else case when e.sq_siw_solicitacao is not null
                               then e.titulo
                               else case when f.sq_siw_solicitacao is not null
                                         then f.titulo
                                         else null
                                    end
                          end
                end titulo,
                nvl(g.existe,0) qtd_projeto
           from siw_menu                   a
                inner join siw_modulo      a1 on (a.sq_modulo          = a1.sq_modulo)
                inner join siw_menu_relac  a2 on (a.sq_menu            = a2.servico_cliente and
                                                  a2.servico_cliente   = p_restricao
                                                 )
                inner join siw_solicitacao b  on (a2.servico_fornecedor= b.sq_menu and
                                                  a2.sq_siw_tramite    = b.sq_siw_tramite and
                                                  b.sq_menu            = nvl(p_menu, b.sq_menu) 
                                                 )
                inner   join siw_menu      b2 on (b.sq_menu            = b2.sq_menu)
                  inner join siw_modulo    b3 on (b2.sq_modulo         = b3.sq_modulo)
                left    join pj_projeto    d  on (b.sq_siw_solicitacao = d.sq_siw_solicitacao)
                left    join (select x.sq_siw_solicitacao, x.codigo_interno, x.vincula_demanda, 
                                     x.vincula_projeto, x.vincula_viagem,
                                     case when x.titulo is not null
                                          then x.titulo
                                          else w.nome_resumido||' - '||case when z.sq_cc is not null then z.nome else k.titulo end||' ('||to_char(y.inicio,'dd/mm/yyyy')||'-'||to_char(y.fim,'dd/mm/yyyy')||')' end as titulo
                                from ac_acordo                   x
                                     join        co_pessoa       w on x.outra_parte        = w.sq_pessoa
                                     join        siw_solicitacao y on x.sq_siw_solicitacao = y.sq_siw_solicitacao
                                       left join ct_cc           z on y.sq_cc              = z.sq_cc
                                       left join pj_projeto      k on y.sq_solic_pai       = k.sq_siw_solicitacao
                             )             e  on (b.sq_siw_solicitacao = e.sq_siw_solicitacao)
                left    join pe_programa   f  on (b.sq_siw_solicitacao = f.sq_siw_solicitacao)
                left    join (select x1.sq_solic_pai, count(*) existe
                                 from siw_solicitacao            x1
                                      inner join siw_menu        y1 on (x1.sq_menu = y1.sq_menu and
                                                                        y1.sigla   = 'PJCAD')
                               group by x1.sq_solic_pai
                              )            g on (b.sq_siw_solicitacao = g.sq_solic_pai)
          where a.sq_menu        = p_restricao
            and b.sq_menu        = nvl(p_menu, b.sq_menu)
            and ((a1.sigla = 'DM' and b3.sigla = 'AC' and e.vincula_demanda  = 'S') or
                 (a1.sigla = 'PR' and b3.sigla = 'AC' and e.vincula_projeto  = 'S') or
                 (a1.sigla = 'PD' and b3.sigla = 'AC' and e.vincula_viagem   = 'S') or
                 (a1.sigla = 'AC' and b3.sigla = 'PR' and d.vincula_contrato = 'S') or
                 (a1.sigla = 'PD' and b3.sigla = 'PR' and d.vincula_viagem   = 'S') or
                 (a1.sigla = 'FN' and b3.sigla = 'AC') or
                 (a1.sigla = 'PR' and b3.sigla = 'PE')
                )
            and (acesso(b.sq_siw_solicitacao,p_pessoa) > 0 or
                 InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0
                )
         order by titulo;
   End If;
end SP_GetSolicList;
/
