create or replace procedure SP_GetSolicPA
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
   
 if substr(p_restricao,1,2) = 'PA' or substr(p_restricao,1,2) = 'GR' Then
      -- Recupera as demandas que o usu�rio pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao as pede_descricao, a.justificativa as pede_justificativa,
                a1.nome as nm_modulo, a1.sigla as sg_modulo,         a1.objetivo_geral,
                a2.sq_tipo_unidade as tp_exec, a2.nome as nm_unidade_exec, a2.informal as informal_exec,
                a2.vinculada as vinc_exec,a2.adm_central as adm_exec,
                a3.sq_pessoa as tit_exec,a4.sq_pessoa as subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,                     b.palavra_chave,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                coalesce(b.codigo_interno, to_char(b.sq_siw_solicitacao)) as codigo_interno,
                b.codigo_externo,     b.titulo,                      acentos(b.titulo) as ac_titulo,
                b.sq_plano,           b.sq_cc,                       b.observacao,
                b.protocolo_siw,
                b1.nome as nm_tramite,   b1.ordem as or_tramite,
                b1.sigla as sg_tramite,  b1.ativo,                   b1.envia_mail,
                b2.acesso,
                b3.sq_siw_solicitacao as sq_emprestimo, b3.fim as devolucao_prevista,
                c.sq_tipo_unidade,    c.nome as nm_unidade_exec,     c.informal,
                c.vinculada,          c.adm_central,
                e.sq_tipo_unidade,    e.nome as nm_unidade_resp,        e.informal as informal_resp,
                e.vinculada as vinc_resp,e.adm_central as adm_resp,     e.sigla sg_unidade_resp,
                e1.sq_pessoa as titular, e2.sq_pessoa as substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                o.nome_resumido as nm_solic, o.nome_resumido_ind as nm_solic_ind,
                p.nome_resumido as nm_exec,  p.nome_resumido_ind as nm_exec_ind
           from siw_menu                                        a 
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
                   inner          join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) as acesso
                                          from siw_solicitacao
                                       )                        b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                   left           join (select y.protocolo, y.sq_siw_solicitacao, x.fim
                                          from siw_solicitacao               x
                                               inner join pa_emprestimo_item y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                         where y.devolucao is null
                                       )                        b3 on (b.sq_siw_solicitacao       = b3.protocolo)
                   inner          join eo_unidade               e  on (b.sq_unidade               = e.sq_unidade)
                     left         join eo_unidade_resp          e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                       e1.tipo_respons            = 'T'           and
                                                                       e1.fim                     is null
                                                                      )
                     left         join eo_unidade_resp          e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                       e2.tipo_respons            = 'S'           and
                                                                       e2.fim                     is null
                                                                      )
                   inner          join co_cidade                f  on (b.sq_cidade_origem         = f.sq_cidade)
                   left           join co_pessoa                o  on (b.solicitante              = o.sq_pessoa)
                   left           join co_pessoa                p  on (b.executor                 = p.sq_pessoa)
                left              join eo_unidade               c  on (a.sq_unid_executora        = c.sq_unidade)
                inner             join (select sq_siw_solicitacao, max(sq_siw_solic_log) as chave 
                                          from siw_solic_log
                                        group by sq_siw_solicitacao
                                       )                        j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
          where (p_menu           is null or (p_menu        is not null and a.sq_menu            = p_menu))
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and b.sq_plano           = p_sq_orprior))
            and (p_pais           is null or (p_pais        is not null and 0 < (select count(*) from pa_documento x join pa_emprestimo_item y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and x.prefixo = p_pais)))
            and (p_regiao         is null or (p_regiao      is not null and 0 < (select count(*) from pa_documento x join pa_emprestimo_item y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and x.numero_documento = p_regiao)))
            and (p_cidade         is null or (p_cidade      is not null and 0 < (select count(*) from pa_documento x join pa_emprestimo_item y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and x.ano = p_cidade)))
            and (p_usu_resp       is null or (p_usu_resp    is not null and 0 < (select count(*) from pa_documento x join pa_emprestimo_item y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and x.sq_especie_documento = p_usu_resp)))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and coalesce(b1.sigla,'-') <> 'AT' and e.sq_unidade = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc              = p_sqcc))
            and (p_projeto        is null or (p_projeto     is not null and b.sq_solic_pai       = p_projeto))
            and (p_processo       is null or (p_processo    is not null and 0 < (select count(*) from pa_emprestimo_item x where x.protocolo = p_processo)))
            and (p_uf             is null or (p_uf          is not null and 0 < (select count(*) from pa_documento x join pa_emprestimo_item y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and x.processo = p_uf)))
            and (p_proponente     is null or (p_proponente  is not null and 0 < (select count(*) from cl_solicitacao_item x inner join cl_material y on (x.sq_material = y.sq_material) where x.sq_siw_solicitacao = b.sq_siw_solicitacao and acentos(y.nome,null) like '%'||acentos(p_proponente,null)||'%')))
            and (p_assunto        is null or (p_assunto     is not null and 0 < (select count(*) from siw_solicitacao x join pa_emprestimo_item y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and acentos(x.descricao,null) like '%'||acentos(p_assunto,null)||'%')))
            --and (p_palavra        is null or (p_palavra     is not null and acentos(d.numero_certame,null) like '%'||acentos(p_palavra,null)||'%'))
            and (p_empenho        is null or (p_empenho     is not null and 0 < (select count(*) from pa_documento x join pa_emprestimo_item y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and acentos(x.numero_original) like '%'||acentos(p_empenho)||'%')))
            --and (p_prioridade     is null or (p_prioridade  is not null and d.prioridade         = p_prioridade))
            and (coalesce(p_ativo,'N') = 'N' or (p_ativo = 'S' and b.conclusao is null))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (p_prazo          is null or (p_prazo       is not null and coalesce(b1.sigla,'-') <> 'AT' and cast(cast(b.fim as date)-cast(sysdate as date) as integer)+1 <=p_prazo))
            and (p_ini_i          is null or (p_ini_i       is not null and b.inicio between p_ini_i and p_ini_f))
            and (p_fim_i          is null or (p_fim_i       is not null and b.fim between p_fim_i and p_fim_f))
            and (coalesce(p_atraso,'N') = 'N' or (p_atraso = 'S' and coalesce(b1.sigla,'-') <> 'AT' and b.fim+1-sysdate<0))
            and (p_unidade        is null or (p_unidade     is not null and b.sq_unidade         = p_unidade))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and ((p_tipo         = 1     and coalesce(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and coalesce(b1.sigla,'-') <> 'CI' and b.executor = p_pessoa and b.conclusao is null) or
                 (p_tipo         = 2     and b1.ativo = 'S' and coalesce(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and coalesce(b1.sigla,'-') <> 'CA') or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0)
                );
   Elsif p_restricao = 'EMPREST' Then
      -- Recupera as solicita��es que o usu�rio pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao as pede_descricao, a.justificativa as pede_justificativa,
                a1.nome as nm_modulo, a1.sigla as sg_modulo,         a1.objetivo_geral,
                a2.sq_tipo_unidade as tp_exec, a2.nome as nm_unidade_exec, a2.informal as informal_exec,
                a2.vinculada as vinc_exec,a2.adm_central as adm_exec,
                a3.sq_pessoa as tit_exec,a4.sq_pessoa as subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,                     b.palavra_chave,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                coalesce(b.codigo_interno, to_char(b.sq_siw_solicitacao)) as codigo_interno,
                b.codigo_externo,     b.titulo,                      acentos(b.titulo) as ac_titulo,
                b.sq_plano,           b.sq_cc,                       b.observacao,
                b.protocolo_siw,
                b1.nome as nm_tramite,   b1.ordem as or_tramite,
                b1.sigla as sg_tramite,  b1.ativo,                   b1.envia_mail,
                b2.acesso,
                b3.devolucao,
                c.sq_tipo_unidade,    c.nome as nm_unidade_exec,     c.informal,
                c.vinculada,          c.adm_central,
                e.sq_tipo_unidade,    e.nome as nm_unidade_resp,        e.informal as informal_resp,
                e.vinculada as vinc_resp,e.adm_central as adm_resp,     e.sigla sg_unidade_resp,
                e1.sq_pessoa as titular, e2.sq_pessoa as substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                o.nome_resumido as nm_solic, o.nome_resumido_ind as nm_solic_ind,
                p.nome_resumido as nm_exec,  p.nome_resumido_ind as nm_exec_ind
           from siw_menu                                        a 
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
                   inner          join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) as acesso
                                          from siw_solicitacao
                                       )                        b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                   inner join     pa_emprestimo_item            b3 on (b.sq_siw_solicitacao       = b3.sq_siw_solicitacao)
                   inner          join eo_unidade               e  on (b.sq_unidade               = e.sq_unidade)
                     left         join eo_unidade_resp          e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                       e1.tipo_respons            = 'T'           and
                                                                       e1.fim                     is null
                                                                      )
                     left         join eo_unidade_resp          e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                       e2.tipo_respons            = 'S'           and
                                                                       e2.fim                     is null
                                                                      )
                   inner          join co_cidade                f  on (b.sq_cidade_origem         = f.sq_cidade)
                   left           join co_pessoa                o  on (b.solicitante              = o.sq_pessoa)
                   left           join co_pessoa                p  on (b.executor                 = p.sq_pessoa)
                left              join eo_unidade               c  on (a.sq_unid_executora        = c.sq_unidade)
                inner             join (select sq_siw_solicitacao, max(sq_siw_solic_log) as chave 
                                          from siw_solic_log
                                        group by sq_siw_solicitacao
                                       )                        j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
          where b3.protocolo = p_processo
            and ((p_tipo         = 1     and coalesce(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and coalesce(b1.sigla,'-') <> 'CI' and b.executor = p_pessoa and b.conclusao is null) or
                 (p_tipo         = 2     and b1.ativo = 'S' and coalesce(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and coalesce(b1.sigla,'-') <> 'CA') or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0)
                );
   End If;
end SP_GetSolicPA;
/