create or replace procedure SP_GetSolicViagem
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

   If p_restricao = 'GRPDPROJ' Then
      -- Recupera as viagens que o usuário pode ver
      open p_result for
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.data_hora,          a.envia_dia_util,              a.descricao,
                a.justificativa,
                a1.nome as nm_modulo,    a1.sigla as sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade as tp_exec, a2.nome as nm_unidade_exec,       a2.informal as informal_exec,
                a2.vinculada as vinc_exec,a2.adm_central as adm_exec,
                a3.sq_pessoa as tit_exec,a4.sq_pessoa as subst_exec,
                a5.dias_prestacao_contas,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.data_hora,          b.opiniao,                     b.sq_solic_pai,
                b.sq_unidade,         b.sq_cidade_origem,            b.palavra_chave,
                b.valor,              b.fim-d.dias_aviso aviso,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem as or_tramite,
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
                case d1.tipo when 'I' then 'Inicial' when 'P' then 'Prorrogação' else 'Complemento' end nm_tp_missao,
                d2.nome nm_prop,      d2.nome_resumido nm_prop_res,
                d3.sq_tipo_vinculo,   d3.nome nm_tipo_vinculo,
                d4.sexo,              d4.cpf,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                p.nome_resumido nm_exec,
                m.sq_projeto,         m.nm_projeto,
                case when (b1.sigla='PC' and trunc(b.fim) + coalesce(d6.dias_prestacao_contas, a5.dias_prestacao_contas) + 1 - trunc(sysdate)<0) then 'S' else 'N' end as atraso_pc
           from siw_menu                                    a
                  inner          join  eo_unidade           a2 on (a.sq_unid_executora        = a2.sq_unidade)
                    left outer   join  eo_unidade_resp      a3 on (a2.sq_unidade              = a3.sq_unidade  and
                                                                   a3.tipo_respons            = 'T'            and
                                                                   a3.fim                     is null)
                    left outer   join  eo_unidade_resp      a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                   a4.tipo_respons            = 'S'           and
                                                                   a4.fim                     is null)
                  inner          join siw_modulo           a1 on (a.sq_modulo                = a1.sq_modulo)
                  inner          join pd_parametro         a5 on (a.sq_pessoa                = a5.cliente)
                  inner          join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                    inner        join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                    inner        join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                         from siw_solicitacao
                                      )                    b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                    inner        join gd_demanda           d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                      inner      join pd_missao            d1 on (d.sq_siw_solicitacao       = d1.sq_siw_solicitacao)
                        left     join pd_categoria_diaria  d6 on (d1.diaria                  = d6.sq_categoria_diaria)
                        inner    join siw_solicitacao     d11 on (d1.sq_siw_solicitacao      = d11.sq_siw_solicitacao)
                        inner    join co_pessoa            d2 on (d1.sq_pessoa               = d2.sq_pessoa)
                          inner  join co_tipo_vinculo      d3 on (d2.sq_tipo_vinculo         = d3.sq_tipo_vinculo)
                          inner  join co_pessoa_fisica     d4 on (d2.sq_pessoa               = d4.sq_pessoa)
                        inner    join eo_unidade           e  on (d.sq_unidade_resp          = e.sq_unidade)
                          left outer join eo_unidade_resp  e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                  e1.tipo_respons            = 'T'           and
                                                                  e1.fim                     is null)
                          left outer join eo_unidade_resp  e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                  e2.tipo_respons            = 'S'           and
                                                                  e2.fim                     is null)
                    inner        join co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                      inner      join sg_autenticacao      o1 on (o.sq_pessoa                = o1.sq_pessoa)
                        inner    join eo_unidade           o2 on (o1.sq_unidade              = o2.sq_unidade)
                    left outer   join co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                  left outer     join eo_unidade           c  on (a.sq_unid_executora        = c.sq_unidade)
                  inner          join (select sq_siw_solicitacao, max(sq_siw_solic_log) chave
                                         from siw_solic_log
                                     group by sq_siw_solicitacao
                                      )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                    left outer   join gd_demanda_log       k  on (j.chave                    = k.sq_siw_solic_log)
                      left outer join sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
                  inner          join (select distinct t1.sq_siw_solicitacao sq_projeto, t2.titulo nm_projeto
                                         from pj_projeto                   t1
                                              inner   join siw_solicitacao t2 on (t1.sq_siw_solicitacao = t2.sq_siw_solicitacao)
                                      )                    m  on (b.sq_solic_pai             = m.sq_projeto)
          where a.sq_menu        = p_menu
            and (p_projeto        is null or (p_projeto     is not null and m.sq_projeto = p_projeto))
            and (p_atividade      is null or (p_atividade   is not null and 0 < (select count(distinct(x2.sq_siw_solicitacao)) from pd_missao_solic x2 join pj_etapa_demanda x3 on (x2.sq_siw_solicitacao = x3.sq_siw_solicitacao and x3.sq_projeto_etapa = p_atividade) where x2.sq_solic_missao = b.sq_siw_solicitacao)))
            and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and d11.codigo_interno like '%'||p_sq_acao_ppa||'%'))
            and (p_assunto        is null or (p_assunto     is not null and acentos(b.descricao,null) like '%'||acentos(p_assunto,null)||'%'))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and (p_unidade        is null or (p_unidade     is not null and d.sq_unidade_resp    = p_unidade))
            and (p_proponente     is null or (p_proponente  is not null and (acentos(d2.nome,null)          like '%'||acentos(p_proponente,null)||'%') or
                                                                            (acentos(d2.nome_resumido,null) like '%'||acentos(p_proponente,null)||'%')
                                             )
                )
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and d1.sq_pessoa = p_sq_orprior))
            and (p_palavra        is null or (p_palavra     is not null and d4.cpf = p_palavra))
            and (p_pais           is null or (p_pais        is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.tipo = 'S' and x.destino = y.sq_cidade and y.sq_pais = p_pais and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_regiao         is null or (p_regiao      is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.tipo = 'S' and x.destino = y.sq_cidade and y.sq_regiao = p_regiao and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_uf             is null or (p_uf          is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.tipo = 'S' and x.destino = y.sq_cidade and y.co_uf = p_uf and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_cidade         is null or (p_cidade      is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x where x.tipo = 'S' and x.destino = p_cidade and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (coalesce(p_ativo,'N') = 'N' or (p_ativo    = 'S' and (b.justificativa is not null or d1.justificativa_dia_util is not null)))
            and (p_usu_resp       is null or (p_usu_resp    is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x where x.tipo = 'S' and x.sq_cia_transporte = p_usu_resp and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_ini_i          is null or (p_ini_i       is not null and ((b.inicio           between p_ini_i  and p_ini_f) or
                                                                             (b.fim              between p_ini_i  and p_ini_f) or
                                                                             (p_ini_i            between b.inicio and b.fim)   or
                                                                             (p_ini_f            between b.inicio and b.fim)
                                                                            )
                                             )
                )
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (coalesce(p_atraso,'N') = 'N' or (p_atraso  = 'S'       and b1.sigla='PC' and trunc(b.fim) + coalesce(d6.dias_prestacao_contas, a5.dias_prestacao_contas) + 1 - trunc(sysdate)<0))
            and ((p_tipo         = 1     and Nvl(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b.executor = p_pessoa and b.conclusao is null) or
                 (p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA'  and b2.acesso > 0) or
                 (p_tipo         = 4     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0 and b1.sigla <> 'CI')
                );
   Elsif p_restricao = 'GRPDATIV' Then
      -- Recupera as viagens que o usuário pode ver
      open p_result for
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.data_hora,          a.envia_dia_util,              a.descricao,
                a.justificativa,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec,       a2.informal informal_exec,
                a2.vinculada vinc_exec,a2.adm_central adm_exec,
                a3.sq_pessoa tit_exec,a4.sq_pessoa subst_exec,
                a5.dias_prestacao_contas,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.data_hora,          b.opiniao,                     b.sq_solic_pai,
                b.sq_unidade,         b.sq_cidade_origem,            b.palavra_chave,
                b.valor,              b.fim-d.dias_aviso aviso,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then case when n.sq_cc is null
                                         then '???'
                                         else 'Classif: '||n.nome 
                                    end
                               else ' Plano: '||b3.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome as nm_tramite,            b1.ordem as or_tramite,
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
                case d1.tipo when 'I' then 'Inicial' when 'P' then 'Prorrogação' else 'Complemento' end nm_tp_missao,
                d2.nome nm_prop,      d2.nome_resumido nm_prop_res,
                d3.sq_tipo_vinculo,   d3.nome nm_tipo_vinculo,
                d4.sexo,              d4.cpf,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                p.nome_resumido nm_exec,
                m.sq_projeto,         m.nm_projeto,
                n.sq_atividade,       n.nm_atividade,
                case when (b1.sigla='PC' and trunc(b.fim) + coalesce(d6.dias_prestacao_contas, a5.dias_prestacao_contas) + 1 - trunc(sysdate)<0) then 'S' else 'N' end as atraso_pc
           from siw_menu                                    a
                  inner          join  eo_unidade           a2 on (a.sq_unid_executora        = a2.sq_unidade)
                    left outer   join  eo_unidade_resp      a3 on (a2.sq_unidade              = a3.sq_unidade  and
                                                                   a3.tipo_respons            = 'T'            and
                                                                   a3.fim                     is null)
                    left outer   join  eo_unidade_resp      a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                   a4.tipo_respons            = 'S'           and
                                                                   a4.fim                     is null)
                  inner          join siw_modulo           a1 on (a.sq_modulo                = a1.sq_modulo)
                  inner          join pd_parametro         a5 on (a.sq_pessoa                = a5.cliente)
                  inner          join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                    inner        join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                    inner        join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                         from siw_solicitacao
                                      )                    b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                    left         join pe_plano             b3 on (b.sq_plano                 = b3.sq_plano)
                    inner        join gd_demanda           d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                      inner      join pd_missao            d1 on (d.sq_siw_solicitacao       = d1.sq_siw_solicitacao)
                        left     join pd_categoria_diaria  d6 on (d1.diaria                  = d6.sq_categoria_diaria)
                        inner    join siw_solicitacao      d11 on (d1.sq_siw_solicitacao     = d11.sq_siw_solicitacao)
                        inner    join co_pessoa            d2 on (d1.sq_pessoa               = d2.sq_pessoa)
                          inner  join co_tipo_vinculo      d3 on (d2.sq_tipo_vinculo         = d3.sq_tipo_vinculo)
                          inner  join co_pessoa_fisica     d4 on (d2.sq_pessoa               = d4.sq_pessoa)
                        inner    join eo_unidade           e  on (d.sq_unidade_resp          = e.sq_unidade)
                          left outer join eo_unidade_resp  e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                  e1.tipo_respons            = 'T'           and
                                                                  e1.fim                     is null)
                          left outer join eo_unidade_resp  e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                  e2.tipo_respons            = 'S'           and
                                                                  e2.fim                     is null)
                    left         join ct_cc                n  on (b.sq_cc                    = n.sq_cc)
                    inner        join co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                      inner      join sg_autenticacao      o1 on (o.sq_pessoa                = o1.sq_pessoa)
                        inner    join eo_unidade           o2 on (o1.sq_unidade              = o2.sq_unidade)
                    left outer   join co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                  left outer     join eo_unidade           c  on (a.sq_unid_executora        = c.sq_unidade)
                  inner          join (select sq_siw_solicitacao, max(sq_siw_solic_log) chave
                                         from siw_solic_log
                                     group by sq_siw_solicitacao
                                      )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                    left outer   join gd_demanda_log       k  on (j.chave                    = k.sq_siw_solic_log)
                      left outer join sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
                  inner          join (select distinct t3.sq_solic_missao, t1.sq_siw_solicitacao sq_projeto, t2.titulo nm_projeto
                                         from pj_projeto                   t1
                                              inner   join siw_solicitacao t2 on (t1.sq_siw_solicitacao = t2.sq_solic_pai)
                                                inner join pd_missao_solic t3 on (t2.sq_siw_solicitacao = t3.sq_siw_solicitacao)
                                      )                    m  on (d.sq_siw_solicitacao       = m.sq_solic_missao)
                  inner          join (select distinct t3.sq_solic_missao, t1.sq_siw_solicitacao sq_atividade, t1.assunto nm_atividade
                                         from gd_demanda                   t1
                                              inner   join siw_solicitacao t2 on (t1.sq_siw_solicitacao = t2.sq_siw_solicitacao)
                                                inner join pd_missao_solic t3 on (t2.sq_siw_solicitacao = t3.sq_siw_solicitacao)
                                      )                    n  on (d.sq_siw_solicitacao       = n.sq_solic_missao)
          where a.sq_menu        = p_menu
            and (p_projeto        is null or (p_projeto     is not null and m.sq_projeto = p_projeto))
            and (p_atividade      is null or (p_atividade   is not null and n.sq_atividade = p_atividade))
            and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and d11.codigo_interno like '%'||p_sq_acao_ppa||'%'))
            and (p_assunto        is null or (p_assunto     is not null and acentos(b.descricao,null) like '%'||acentos(p_assunto,null)||'%'))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and (p_unidade        is null or (p_unidade     is not null and d.sq_unidade_resp    = p_unidade))
            and (p_proponente     is null or (p_proponente  is not null and (acentos(d2.nome,null)          like '%'||acentos(p_proponente,null)||'%') or
                                                                            (acentos(d2.nome_resumido,null) like '%'||acentos(p_proponente,null)||'%')
                                             )
                )
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and d1.sq_pessoa = p_sq_orprior))
            and (p_palavra        is null or (p_palavra     is not null and d4.cpf = p_palavra))
            and (p_pais           is null or (p_pais        is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.tipo = 'S' and x.destino = y.sq_cidade and y.sq_pais = p_pais and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_regiao         is null or (p_regiao      is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.tipo = 'S' and x.destino = y.sq_cidade and y.sq_regiao = p_regiao and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_uf             is null or (p_uf          is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.tipo = 'S' and x.destino = y.sq_cidade and y.co_uf = p_uf and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_cidade         is null or (p_cidade      is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x where x.tipo = 'S' and x.destino = p_cidade and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (coalesce(p_ativo,'N') = 'N' or (p_ativo    = 'S' and (b.justificativa is not null or d1.justificativa_dia_util is not null)))
            and (p_usu_resp       is null or (p_usu_resp    is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x where x.tipo = 'S' and x.sq_cia_transporte = p_usu_resp and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_ini_i          is null or (p_ini_i       is not null and ((b.inicio           between p_ini_i  and p_ini_f) or
                                                                             (b.fim              between p_ini_i  and p_ini_f) or
                                                                             (p_ini_i            between b.inicio and b.fim)   or
                                                                             (p_ini_f            between b.inicio and b.fim)
                                                                            )
                                             )
                )
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (coalesce(p_atraso,'N') = 'N' or (p_atraso  = 'S'       and b1.sigla='PC' and trunc(b.fim) + coalesce(d6.dias_prestacao_contas, a5.dias_prestacao_contas) + 1 - trunc(sysdate)<0))
            and ((p_tipo         = 1     and Nvl(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b.executor = p_pessoa and b.conclusao is null) or
                 (p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA'  and b2.acesso > 0) or
                 (p_tipo         = 4     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0 and b1.sigla <> 'CI')
                );
   Elsif p_restricao = 'GRPDCIAVIAGEM' Then
      -- Recupera as viagens que o usuário pode ver
      open p_result for
         select distinct 
                a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.data_hora,          a.envia_dia_util,              a.descricao,
                a.justificativa,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec,       a2.informal informal_exec,
                a2.vinculada vinc_exec,a2.adm_central adm_exec,
                a3.sq_pessoa tit_exec,a4.sq_pessoa subst_exec,
                a5.dias_prestacao_contas,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.data_hora,          b.opiniao,                     b.sq_solic_pai,
                b.sq_unidade,         b.sq_cidade_origem,            b.palavra_chave,
                b.valor,              b.fim-d.dias_aviso aviso,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then case when n.sq_cc is null
                                         then '???'
                                         else 'Classif: '||n.nome 
                                    end
                               else ' Plano: '||b3.titulo
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
                case d1.tipo when 'I' then 'Inicial' when 'P' then 'Prorrogação' else 'Complemento' end nm_tp_missao,
                d2.nome nm_prop,      d2.nome_resumido nm_prop_res,
                d3.sq_tipo_vinculo,   d3.nome nm_tipo_vinculo,
                d4.sexo,              d4.cpf,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                p.nome_resumido nm_exec,
                m.sq_cia_transporte, m.nm_cia_viagem,
                case when (b1.sigla='PC' and trunc(b.fim) + coalesce(d6.dias_prestacao_contas, a5.dias_prestacao_contas) + 1 - trunc(sysdate)<0) then 'S' else 'N' end as atraso_pc
           from siw_menu                                    a
                  inner          join  eo_unidade           a2 on (a.sq_unid_executora        = a2.sq_unidade)
                    left outer   join  eo_unidade_resp      a3 on (a2.sq_unidade              = a3.sq_unidade  and
                                                                   a3.tipo_respons            = 'T'            and
                                                                   a3.fim                     is null)
                    left outer   join  eo_unidade_resp      a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                   a4.tipo_respons            = 'S'           and
                                                                   a4.fim                     is null)
                  inner          join siw_modulo           a1 on (a.sq_modulo                = a1.sq_modulo)
                  inner          join pd_parametro         a5 on (a.sq_pessoa                = a5.cliente)
                  inner          join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                    inner        join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                    inner        join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                         from siw_solicitacao
                                      )                    b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                    left         join pe_plano             b3 on (b.sq_plano                 = b3.sq_plano)
                    inner        join gd_demanda           d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                      inner      join pd_missao            d1 on (d.sq_siw_solicitacao       = d1.sq_siw_solicitacao)
                        left     join pd_categoria_diaria  d6 on (d1.diaria                  = d6.sq_categoria_diaria)
                        inner    join siw_solicitacao      d11 on (d1.sq_siw_solicitacao     = d11.sq_siw_solicitacao)
                        inner    join co_pessoa            d2 on (d1.sq_pessoa               = d2.sq_pessoa)
                          inner  join co_tipo_vinculo      d3 on (d2.sq_tipo_vinculo         = d3.sq_tipo_vinculo)
                          inner  join co_pessoa_fisica     d4 on (d2.sq_pessoa               = d4.sq_pessoa)
                        inner    join eo_unidade           e  on (d.sq_unidade_resp          = e.sq_unidade)
                          left outer join eo_unidade_resp  e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                  e1.tipo_respons            = 'T'           and
                                                                  e1.fim                     is null)
                          left outer join eo_unidade_resp  e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                  e2.tipo_respons            = 'S'           and
                                                                  e2.fim                     is null)
                    left         join ct_cc                n  on (b.sq_cc                    = n.sq_cc)
                    inner        join co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                      inner      join sg_autenticacao      o1 on (o.sq_pessoa                = o1.sq_pessoa)
                        inner    join eo_unidade           o2 on (o1.sq_unidade              = o2.sq_unidade)
                    left outer   join co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                  left outer     join eo_unidade           c  on (a.sq_unid_executora        = c.sq_unidade)
                  inner          join (select sq_siw_solicitacao, max(sq_siw_solic_log) chave
                                         from siw_solic_log
                                     group by sq_siw_solicitacao
                                      )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                    left outer   join gd_demanda_log       k  on (j.chave                    = k.sq_siw_solic_log)
                      left outer join sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
                  inner          join (select x1.sq_siw_solicitacao, x2.sq_cia_transporte, x2.nome nm_cia_viagem
                                         from pd_bilhete                        x1
                                              left       join pd_cia_transporte x2 on (x1.sq_cia_transporte = x2.sq_cia_transporte)
                                        where x1.tipo = 'S'
                                     group by x1.sq_siw_solicitacao, x2.sq_cia_transporte, x2.nome
                                      )                    m  on (b.sq_siw_solicitacao       = m.sq_siw_solicitacao)
          where a.sq_menu        = p_menu
            and (p_projeto        is null or (p_projeto     is not null and (b.sq_solic_pai = p_projeto or 0 < (select count(distinct(x.sq_siw_solicitacao)) from pd_missao_solic x , siw_solicitacao y where x.sq_siw_solicitacao = y.sq_siw_solicitacao and y.sq_solic_pai = p_projeto and x.sq_solic_missao = b.sq_siw_solicitacao))))
            and (p_atividade      is null or (p_atividade   is not null and 0 < (select count(distinct(x.sq_siw_solicitacao)) from pd_missao_solic x where x.sq_siw_solicitacao = p_atividade and x.sq_solic_missao = b.sq_siw_solicitacao)))
            and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and d11.codigo_interno like '%'||p_sq_acao_ppa||'%'))
            and (p_assunto        is null or (p_assunto     is not null and acentos(b.descricao,null) like '%'||acentos(p_assunto,null)||'%'))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and (p_unidade        is null or (p_unidade     is not null and d.sq_unidade_resp    = p_unidade))
            and (p_proponente     is null or (p_proponente  is not null and (acentos(d2.nome,null)          like '%'||acentos(p_proponente,null)||'%') or
                                                                            (acentos(d2.nome_resumido,null) like '%'||acentos(p_proponente,null)||'%')
                                             )
                )
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and d1.sq_pessoa = p_sq_orprior))
            and (p_palavra        is null or (p_palavra     is not null and d4.cpf = p_palavra))
            and (p_pais           is null or (p_pais        is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.tipo = 'S' and x.destino = y.sq_cidade and y.sq_pais = p_pais and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_regiao         is null or (p_regiao      is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.tipo = 'S' and x.destino = y.sq_cidade and y.sq_regiao = p_regiao and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_uf             is null or (p_uf          is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.tipo = 'S' and x.destino = y.sq_cidade and y.co_uf = p_uf and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_cidade         is null or (p_cidade      is not null and 0 < (select count(sq_deslocamento) from pd_deslocamento where tipo = 'S' and destino = p_cidade and sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (coalesce(p_ativo,'N') = 'N' or (p_ativo    = 'S' and (b.justificativa is not null or d1.justificativa_dia_util is not null)))
            and (p_usu_resp       is null or (p_usu_resp    is not null and m.sq_cia_transporte = p_usu_resp))
            and (p_ini_i          is null or (p_ini_i       is not null and ((b.inicio           between p_ini_i  and p_ini_f) or
                                                                             (b.fim              between p_ini_i  and p_ini_f) or
                                                                             (p_ini_i            between b.inicio and b.fim)   or
                                                                             (p_ini_f            between b.inicio and b.fim)
                                                                            )
                                             )
                )
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (coalesce(p_atraso,'N') = 'N' or (p_atraso  = 'S'       and b1.sigla='PC' and trunc(b.fim) + coalesce(d6.dias_prestacao_contas, a5.dias_prestacao_contas) + 1 - trunc(sysdate)<0))
            and ((p_tipo         = 1     and Nvl(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b.executor = p_pessoa and b.conclusao is null) or
                 (p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA'  and b2.acesso > 0) or
                 (p_tipo         = 4     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0 and b1.sigla <> 'CI')
                );
   Elsif p_restricao = 'GRPDCIDADE' Then
      -- Recupera as viagens que o usuário pode ver
      open p_result for
         select distinct 
                a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.data_hora,          a.envia_dia_util,              a.descricao,
                a.justificativa,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec,       a2.informal informal_exec,
                a2.vinculada vinc_exec,a2.adm_central adm_exec,
                a3.sq_pessoa tit_exec,a4.sq_pessoa subst_exec,
                a5.dias_prestacao_contas,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.data_hora,          b.opiniao,                     b.sq_solic_pai,
                b.sq_unidade,         b.sq_cidade_origem,            b.palavra_chave,
                b.valor,              b.fim-d.dias_aviso aviso,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then case when n.sq_cc is null
                                         then '???'
                                         else 'Classif: '||n.nome 
                                    end
                               else ' Plano: '||b3.titulo
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
                case d1.tipo when 'I' then 'Inicial' when 'P' then 'Prorrogação' else 'Complemento' end nm_tp_missao,
                d2.nome nm_prop,      d2.nome_resumido nm_prop_res,
                d3.sq_tipo_vinculo,   d3.nome nm_tipo_vinculo,
                d4.sexo,              d4.cpf,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                p.nome_resumido nm_exec,
                m.destino, m.nm_destino, acentos(m.nm_destino) as nm_destino_ind,
                case when (b1.sigla='PC' and trunc(b.fim) + coalesce(d6.dias_prestacao_contas, a5.dias_prestacao_contas) + 1 - trunc(sysdate)<0) then 'S' else 'N' end as atraso_pc
           from siw_menu                                    a
                  inner          join  eo_unidade           a2 on (a.sq_unid_executora        = a2.sq_unidade)
                    left outer   join  eo_unidade_resp      a3 on (a2.sq_unidade              = a3.sq_unidade  and
                                                                   a3.tipo_respons            = 'T'            and
                                                                   a3.fim                     is null)
                    left outer   join  eo_unidade_resp      a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                   a4.tipo_respons            = 'S'           and
                                                                   a4.fim                     is null)
                  inner          join siw_modulo           a1 on (a.sq_modulo                = a1.sq_modulo)
                  inner          join pd_parametro         a5 on (a.sq_pessoa                = a5.cliente)
                  inner          join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                    inner        join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                    inner        join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                         from siw_solicitacao
                                      )                    b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                    left         join pe_plano             b3 on (b.sq_plano                 = b3.sq_plano)
                    inner        join gd_demanda           d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                      inner      join pd_missao            d1 on (d.sq_siw_solicitacao       = d1.sq_siw_solicitacao)
                        left     join pd_categoria_diaria  d6 on (d1.diaria                  = d6.sq_categoria_diaria)
                        inner    join siw_solicitacao     d11 on (d1.sq_siw_solicitacao      = d11.sq_siw_solicitacao)
                        inner    join co_pessoa            d2 on (d1.sq_pessoa               = d2.sq_pessoa)
                          inner  join co_tipo_vinculo      d3 on (d2.sq_tipo_vinculo         = d3.sq_tipo_vinculo)
                          inner  join co_pessoa_fisica     d4 on (d2.sq_pessoa               = d4.sq_pessoa)
                        inner    join eo_unidade           e  on (d.sq_unidade_resp          = e.sq_unidade)
                          left outer join eo_unidade_resp  e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                  e1.tipo_respons            = 'T'           and
                                                                  e1.fim                     is null)
                          left outer join eo_unidade_resp  e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                  e2.tipo_respons            = 'S'           and
                                                                  e2.fim                     is null)
                    left         join ct_cc                n  on (b.sq_cc                    = n.sq_cc)
                    inner        join co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                      inner      join sg_autenticacao      o1 on (o.sq_pessoa                = o1.sq_pessoa)
                        inner    join eo_unidade           o2 on (o1.sq_unidade              = o2.sq_unidade)
                    left outer   join co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                  left outer     join eo_unidade           c  on (a.sq_unid_executora        = c.sq_unidade)
                  inner          join (select sq_siw_solicitacao, max(sq_siw_solic_log) chave
                                         from siw_solic_log
                                     group by sq_siw_solicitacao
                                      )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                    left outer   join gd_demanda_log       k  on (j.chave                    = k.sq_siw_solic_log)
                      left outer join sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
                  left outer     join (select x1.sq_siw_solicitacao, x1.sq_cia_transporte, x1.destino, 
                                              x3.nome||case x4.padrao when 'S' then '-'||x3.co_uf else ' ('||x4.nome||')' end nm_destino
                                         from pd_deslocamento                   x1
                                              left       join co_cidade         x3 on (x1.destino           = x3.sq_cidade)
                                                left     join co_pais           x4 on (x3.sq_pais           = x4.sq_pais)
                                        where x1.tipo = 'S'
                                     group by x1.sq_siw_solicitacao, x1.sq_cia_transporte, x1.destino, x3.nome, x3.co_uf, x4.padrao, x4.nome
                                      )                    m  on (b.sq_siw_solicitacao       = m.sq_siw_solicitacao)
          where a.sq_menu        = p_menu
            and (p_projeto        is null or (p_projeto     is not null and (b.sq_solic_pai = p_projeto or 0 < (select count(distinct(x.sq_siw_solicitacao)) from pd_missao_solic x , siw_solicitacao y where x.sq_siw_solicitacao = y.sq_siw_solicitacao and y.sq_solic_pai = p_projeto and x.sq_solic_missao = b.sq_siw_solicitacao))))
            and (p_atividade      is null or (p_atividade   is not null and 0 < (select count(distinct(x.sq_siw_solicitacao)) from pd_missao_solic x where x.sq_siw_solicitacao = p_atividade and x.sq_solic_missao = b.sq_siw_solicitacao)))
            and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and d11.codigo_interno like '%'||p_sq_acao_ppa||'%'))
            and (p_assunto        is null or (p_assunto     is not null and acentos(b.descricao,null) like '%'||acentos(p_assunto,null)||'%'))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and (p_unidade        is null or (p_unidade     is not null and d.sq_unidade_resp    = p_unidade))
            and (p_proponente     is null or (p_proponente  is not null and (acentos(d2.nome,null)          like '%'||acentos(p_proponente,null)||'%') or
                                                                            (acentos(d2.nome_resumido,null) like '%'||acentos(p_proponente,null)||'%')
                                             )
                )
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and d1.sq_pessoa = p_sq_orprior))
            and (p_palavra        is null or (p_palavra     is not null and d4.cpf = p_palavra))
            and (p_pais           is null or (p_pais        is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.tipo = 'S' and x.destino = y.sq_cidade and y.sq_pais = p_pais and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_regiao         is null or (p_regiao      is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.tipo = 'S' and x.destino = y.sq_cidade and y.sq_regiao = p_regiao and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_uf             is null or (p_uf          is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.tipo = 'S' and x.destino = y.sq_cidade and y.co_uf = p_uf and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_cidade         is null or (p_cidade      is not null and m.destino = p_cidade))
            and (coalesce(p_ativo,'N') = 'N' or (p_ativo    = 'S' and (b.justificativa is not null or d1.justificativa_dia_util is not null)))
            and (p_usu_resp       is null or (p_usu_resp    is not null and m.sq_cia_transporte = p_usu_resp))
            and (p_ini_i          is null or (p_ini_i       is not null and ((b.inicio           between p_ini_i  and p_ini_f) or
                                                                             (b.fim              between p_ini_i  and p_ini_f) or
                                                                             (p_ini_i            between b.inicio and b.fim)   or
                                                                             (p_ini_f            between b.inicio and b.fim)
                                                                            )
                                             )
                )
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (coalesce(p_atraso,'N') = 'N' or (p_atraso  = 'S'       and b1.sigla='PC' and trunc(b.fim) + coalesce(d6.dias_prestacao_contas, a5.dias_prestacao_contas) + 1 - trunc(sysdate)<0))
            and ((p_tipo         = 1     and Nvl(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b.executor = p_pessoa and b.conclusao is null) or
                 (p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA'  and b2.acesso > 0) or
                 (p_tipo         = 4     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0 and b1.sigla <> 'CI')
                )
            and m.destino  not in (select origem
                                     from pd_deslocamento x
                                    where x.tipo             = 'S' 
                                      and x.sq_siw_solicitacao = b.sq_siw_solicitacao
                                      and x.saida              = (select min(saida)
                                                                    from pd_deslocamento y
                                                                   where y.tipo             = 'S' 
                                                                     and y.sq_siw_solicitacao = x.sq_siw_solicitacao
                                                                 )
                                  )
            and m.destino is not null;
   Elsif p_restricao = 'GRPDDATA' Then
      -- Recupera as viagens que o usuário pode ver
      open p_result for
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.data_hora,          a.envia_dia_util,              a.descricao,
                a.justificativa,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec,       a2.informal informal_exec,
                a2.vinculada vinc_exec,a2.adm_central adm_exec,
                a3.sq_pessoa tit_exec,a4.sq_pessoa subst_exec,
                a5.dias_prestacao_contas,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.data_hora,          b.opiniao,                     b.sq_solic_pai,
                b.sq_unidade,         b.sq_cidade_origem,            b.palavra_chave,
                b.valor,              b.fim-d.dias_aviso aviso,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then case when n.sq_cc is null
                                         then '???'
                                         else 'Classif: '||n.nome 
                                    end
                               else ' Plano: '||b3.titulo
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
                case d1.tipo when 'I' then 'Inicial' when 'P' then 'Prorrogação' else 'Complemento' end nm_tp_missao,
                decode(d1.tipo,'I','Inicial','P','Prorrogação','Complemento') nm_tp_missao,
                d2.nome nm_prop,      d2.nome_resumido nm_prop_res,
                d3.sq_tipo_vinculo,   d3.nome nm_tipo_vinculo,
                d4.sexo,              d4.cpf,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                p.nome_resumido nm_exec,
                m.nm_mes, m.cd_mes,
                case when (b1.sigla='PC' and trunc(b.fim) + coalesce(d6.dias_prestacao_contas, a5.dias_prestacao_contas) + 1 - trunc(sysdate)<0) then 'S' else 'N' end as atraso_pc
           from siw_menu             a
                  inner              join eo_unidade           a2 on (a.sq_unid_executora        = a2.sq_unidade)
                    left outer       join eo_unidade_resp      a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                                      a3.tipo_respons            = 'T'           and
                                                                      a3.fim                     is null)
                    left outer       join eo_unidade_resp      a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                      a4.tipo_respons            = 'S'           and
                                                                      a4.fim                     is null)
                  inner              join siw_modulo           a1 on (a.sq_modulo                = a1.sq_modulo)
                  inner              join pd_parametro         a5 on (a.sq_pessoa                = a5.cliente)
                  inner              join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                    inner            join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                    inner            join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                             from siw_solicitacao
                                          )                    b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                    left             join pe_plano             b3 on (b.sq_plano                 = b3.sq_plano)
                    inner            join gd_demanda           d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                      inner          join pd_missao            d1 on (d.sq_siw_solicitacao       = d1.sq_siw_solicitacao)
                        left         join pd_categoria_diaria  d6 on (d1.diaria                  = d6.sq_categoria_diaria)
                        inner        join siw_solicitacao     d11 on (d1.sq_siw_solicitacao      = d11.sq_siw_solicitacao)
                        inner        join co_pessoa            d2 on (d1.sq_pessoa               = d2.sq_pessoa)
                          inner      join co_tipo_vinculo      d3 on (d2.sq_tipo_vinculo         = d3.sq_tipo_vinculo)
                          inner      join co_pessoa_fisica     d4 on (d2.sq_pessoa               = d4.sq_pessoa)
                      inner          join eo_unidade           e  on (d.sq_unidade_resp          = e.sq_unidade)
                        left outer   join eo_unidade_resp      e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                      e1.tipo_respons            = 'T'           and
                                                                      e1.fim                     is null)
                        left outer   join eo_unidade_resp      e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                      e2.tipo_respons            = 'S'           and
                                                                      e2.fim                     is null)
                    left             join ct_cc                n  on (b.sq_cc                    = n.sq_cc)
                    inner            join co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                      inner          join sg_autenticacao      o1 on (o.sq_pessoa                = o1.sq_pessoa)
                        inner        join eo_unidade           o2 on (o1.sq_unidade              = o2.sq_unidade)
                    left outer       join co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                  left outer         join eo_unidade           c  on (a.sq_unid_executora        = c.sq_unidade)
                  inner              join (select sq_siw_solicitacao, max(sq_siw_solic_log) chave
                                             from siw_solic_log
                                         group by sq_siw_solicitacao
                                          )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                    left outer       join gd_demanda_log       k  on (j.chave                    = k.sq_siw_solic_log)
                      left outer     join sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
                    left outer       join (select distinct x1.sq_siw_solicitacao, to_char(x1.chegada,'yyyy/mm') nm_mes,
                                                  to_date('01/'||to_char(x1.chegada,'mm/yyyy'),'dd/mm/yyyy') cd_mes
                                             from pd_deslocamento   x1
                                            where x1.tipo = 'S' 
                                              and (p_ini_i is null or (p_ini_i is not null and ((x1.saida   between p_ini_i  and p_ini_f)    or
                                                                                                (x1.chegada between p_ini_i  and p_ini_f)    or
                                                                                                (p_ini_i    between x1.saida and x1.chegada) or
                                                                                                (p_ini_f    between x1.saida and x1.chegada)
                                                                                               )
                                                                      )
                                               )
                                          )                    m on (b.sq_siw_solicitacao        = m.sq_siw_solicitacao)
          where a.sq_menu        = p_menu
            and (p_projeto        is null or (p_projeto     is not null and 0 < (select count(distinct(x.sq_siw_solicitacao)) from pd_missao_solic x , siw_solicitacao y where x.sq_siw_solicitacao = y.sq_siw_solicitacao and y.sq_solic_pai = p_projeto and x.sq_solic_missao = b.sq_siw_solicitacao)))
            and (p_atividade      is null or (p_atividade   is not null and 0 < (select count(distinct(x.sq_siw_solicitacao)) from pd_missao_solic x where x.sq_siw_solicitacao = p_atividade and x.sq_solic_missao = b.sq_siw_solicitacao)))
            and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and d11.codigo_interno like '%'||p_sq_acao_ppa||'%'))
            and (p_assunto        is null or (p_assunto     is not null and acentos(b.descricao,null) like '%'||acentos(p_assunto,null)||'%'))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and (p_unidade        is null or (p_unidade     is not null and d.sq_unidade_resp    = p_unidade))
            and (p_proponente     is null or (p_proponente  is not null and (acentos(d2.nome,null)          like '%'||acentos(p_proponente,null)||'%') or
                                                                            (acentos(d2.nome_resumido,null) like '%'||acentos(p_proponente,null)||'%')
                                             )
                )
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and d1.sq_pessoa = p_sq_orprior))
            and (p_palavra        is null or (p_palavra     is not null and d4.cpf = p_palavra))
            and (p_pais           is null or (p_pais        is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.tipo = 'S' and x.destino = y.sq_cidade and y.sq_pais = p_pais and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_regiao         is null or (p_regiao      is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.tipo = 'S' and x.destino = y.sq_cidade and y.sq_regiao = p_regiao and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_uf             is null or (p_uf          is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.tipo = 'S' and x.destino = y.sq_cidade and y.co_uf = p_uf and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_cidade         is null or (p_cidade      is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x where x.tipo = 'S' and x.destino = p_cidade and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (coalesce(p_ativo,'N') = 'N' or (p_ativo    = 'S' and (b.justificativa is not null or d1.justificativa_dia_util is not null)))
            and (p_usu_resp       is null or (p_usu_resp    is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x where x.tipo = 'S' and x.sq_cia_transporte = p_usu_resp and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_fim_i          is null or (p_fim_i       is not null and m.nm_mes = to_char(p_fim_i,'yyyy/mm')))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (coalesce(p_atraso,'N') = 'N' or (p_atraso  = 'S'       and b1.sigla='PC' and trunc(b.fim) + coalesce(d6.dias_prestacao_contas, a5.dias_prestacao_contas) + 1 - trunc(sysdate)<0))
            and ((p_tipo         = 1     and Nvl(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b.executor = p_pessoa and b.conclusao is null) or
                 (p_tipo         = 2     and b1.ativo = 'S' and Nvl(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and Nvl(b1.sigla,'-') <> 'CA'  and b2.acesso > 0) or
                 (p_tipo         = 4     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0 and b1.sigla <> 'CI')
                );
   End If;
end SP_GetSolicViagem;
/
