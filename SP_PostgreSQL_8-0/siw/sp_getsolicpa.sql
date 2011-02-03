create or replace FUNCTION SP_GetSolicPA
   (p_menu         numeric,
    p_pessoa       numeric,
    p_restricao    varchar,
    p_tipo         numeric,
    p_ini_i        date,
    p_ini_f        date,
    p_fim_i        date,
    p_fim_f        date,
    p_atraso       varchar,
    p_solicitante  numeric,
    p_unidade      numeric,
    p_prioridade   numeric,
    p_ativo        varchar,
    p_proponente   varchar,
    p_chave        numeric,
    p_assunto      varchar,
    p_pais         numeric,
    p_regiao       numeric,
    p_uf           varchar,
    p_cidade       numeric,
    p_usu_resp     numeric,
    p_uorg_resp    numeric,
    p_palavra      varchar,
    p_prazo        numeric,
    p_fase         varchar,
    p_sqcc         numeric,
    p_projeto      numeric,
    p_atividade    numeric,
    p_sq_acao_ppa  varchar,
    p_sq_orprior   numeric,
    p_empenho      varchar,
    p_processo     varchar,
    p_result       REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
    
    l_item       varchar(18);
    l_fase       varchar(200) := p_fase ||',';
    x_fase       varchar(200) := '';
    
    l_resp_unid  varchar(10000) :='';
    
    --  que recupera as unidades nas quais o usuário informado é titular ou substituto
     c_unidades_resp CURSOR FOR
        select distinct a.sq_unidade
          from eo_unidade_resp         b
	       inner   join co_pessoa  c on (b.sq_pessoa     = c.sq_pessoa)
	         inner join eo_unidade a on (c.sq_pessoa_pai = a.sq_pessoa)
         where b.sq_pessoa = p_pessoa
           and b.fim       is null
           and a.sq_unidade in (select sq_unidade from connectby('eo_unidade','sq_unidade','sq_unidade_pai',to_char(b.sq_unidade),0) as (sq_unidade numeric, sq_unidade_pai numeric, level int));
      
BEGIN
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
   
 if p_restricao <> 'PAELIM' and (substr(p_restricao,1,2) = 'PA' or substr(p_restricao,1,4) = 'GREM') Then
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
                coalesce(b3.qtd,0) as qtd_processo, coalesce(b4.qtd,0) as qtd_documento, coalesce(b5.qtd,0) as qtd_itens,
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
                                          from siw_solicitacao         x
                                               inner join pa_documento y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                         where x.sq_menu = coalesce(p_menu, x.sq_menu)
                                       )                        b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                   left           join (select x.sq_siw_solicitacao, count(*) as qtd
                                          from siw_solicitacao               x
                                               inner join pa_emprestimo_item y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                               inner join pa_documento       z on (y.protocolo          = z.sq_siw_solicitacao)
                                         where z.processo = 'S'
                                        group by x.sq_siw_solicitacao
                                       )                        b3 on (b.sq_siw_solicitacao       = b3.sq_siw_solicitacao)
                   left           join (select x.sq_siw_solicitacao, count(*) as qtd
                                          from siw_solicitacao               x
                                               inner join pa_emprestimo_item y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                               inner join pa_documento       z on (y.protocolo          = z.sq_siw_solicitacao)
                                         where z.processo = 'N'
                                        group by x.sq_siw_solicitacao
                                       )                        b4 on (b.sq_siw_solicitacao       = b4.sq_siw_solicitacao)
                   left           join (select x.sq_siw_solicitacao, count(*) as qtd
                                          from siw_solicitacao               x
                                               inner join pa_emprestimo_item y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                               inner join pa_documento       z on (y.protocolo          = z.sq_siw_solicitacao)
                                        group by x.sq_siw_solicitacao
                                       )                        b5 on (b.sq_siw_solicitacao       = b5.sq_siw_solicitacao)
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
          where (p_menu           is null or (p_menu        is not null and a.sq_menu            = p_menu))
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and b.sq_plano           = p_sq_orprior))
            and (p_pais           is null or (p_pais        is not null and 0 < (select count(*) from pa_documento x join pa_emprestimo_item y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and x.prefixo = to_char(p_pais))))
            and (p_regiao         is null or (p_regiao      is not null and 0 < (select count(*) from pa_documento x join pa_emprestimo_item y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and x.numero_documento = p_regiao)))
            and (p_cidade         is null or (p_cidade      is not null and 0 < (select count(*) from pa_documento x join pa_emprestimo_item y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and x.ano = p_cidade)))
            and (p_usu_resp       is null or (p_usu_resp    is not null and 0 < (select count(*) from pa_documento x join pa_emprestimo_item y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and x.sq_especie_documento = p_usu_resp)))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and e.sq_unidade = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc              = p_sqcc))
            and (p_projeto        is null or (p_projeto     is not null and b.sq_solic_pai       = p_projeto))
            and (p_processo       is null or (p_processo    is not null and 0 < (select count(*)
                                                                                   from pa_emprestimo_item                    w
                                                                                        inner   join pa_documento_interessado x on (w.sq_siw_solicitacao = x.sq_siw_solicitacao)
                                                                                          inner join co_pessoa                y on (x.sq_pessoa = y.sq_pessoa)
                                                                                  where w.sq_siw_solicitacao          = b.sq_siw_solicitacao
                                                                                    and (acentos(y.nome_indice)       like '%'||acentos(p_processo)||'%' or
                                                                                         acentos(y.nome_resumido_ind) like '%'||acentos(p_processo)||'%'
                                                                                        )
                                                                                )
                                             )
                )
            and (p_uf             is null or (p_uf          is not null and 0 < (select count(*) from pa_documento x join pa_emprestimo_item y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and x.processo = p_uf)))
            and (p_proponente     is null or (p_proponente  is not null and (0 < (select count(*) from pa_documento x join pa_emprestimo_item y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and to_char(x.pessoa_origem) = p_proponente) or
                                                                             0 < (select count(*) from pa_documento x join pa_emprestimo_item y on x.sq_siw_solicitacao = y.protocolo inner join co_pessoa z on x.pessoa_origem = z.sq_pessoa where y.sq_siw_solicitacao = b.sq_siw_solicitacao and z.nome_indice like '%'||acentos(p_proponente)||'%' or z.nome_resumido_ind like '%'||acentos(p_proponente)||'%')
                                                                            )
                                             )
                )
            and (p_assunto        is null or (p_assunto     is not null and 0 < (select count(*) from siw_solicitacao x join pa_emprestimo_item y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and acentos(x.descricao,null) like '%'||acentos(p_assunto,null)||'%')))
            --and (p_palavra        is null or (p_palavra     is not null and acentos(d.numero_certame,null) like '%'||acentos(p_palavra,null)||'%'))
            and (p_empenho        is null or (p_empenho     is not null and 0 < (select count(*) from pa_documento x join pa_emprestimo_item y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and acentos(x.numero_original) like '%'||acentos(p_empenho)||'%')))
            --and (p_prioridade     is null or (p_prioridade  is not null and d.prioridade         = p_prioridade))
            and (coalesce(p_ativo,'N') = 'N' or (p_ativo = 'S' and b.conclusao is null))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (p_prazo          is null or (p_prazo       is not null and b1.sigla not in ('CI','AT') and trunc(b.fim)-trunc(now())+1 <=p_prazo))
            and (p_ini_i          is null or (p_ini_i       is not null and b.inicio             between p_ini_i and p_ini_f))
            and (p_fim_i          is null or (p_fim_i       is not null and b.fim                between p_fim_i and p_fim_f))
            and (coalesce(p_atraso,'N') = 'N' or (p_atraso = 'S' and b1.sigla not in ('CI','AT') and b.fim+1-now()<0))
            and (p_unidade        is null or (p_unidade     is not null and 0 < (select count(*) from siw_solicitacao x join pa_emprestimo_item y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and x.sq_unidade = p_unidade)))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and ((p_tipo         = 1     and coalesce(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and coalesce(b1.sigla,'-') <> 'CI' and b.executor = p_pessoa and b.conclusao is null) or
                 (p_tipo         = 2     and b1.ativo = 'S' and coalesce(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and coalesce(b1.sigla,'-') <> 'CA') or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0 and b1.sigla <> 'CI')
                );
 elsif p_restricao = 'PAELIM' or substr(p_restricao,1,4) = 'GREL' Then
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
                coalesce(b3.qtd,0) as qtd_processo, coalesce(b4.qtd,0) as qtd_documento, coalesce(b5.qtd,0) as qtd_itens,
                b6.dt_eliminacao,
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
                   left           join (select x.sq_siw_solicitacao, count(*) as qtd
                                          from siw_solicitacao               x
                                               inner join pa_eliminacao      y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                               inner join pa_documento       z on (y.protocolo          = z.sq_siw_solicitacao)
                                         where z.processo = 'S'
                                        group by x.sq_siw_solicitacao
                                       )                        b3 on (b.sq_siw_solicitacao       = b3.sq_siw_solicitacao)
                   left           join (select x.sq_siw_solicitacao, count(*) as qtd
                                          from siw_solicitacao               x
                                               inner join pa_eliminacao      y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                               inner join pa_documento       z on (y.protocolo          = z.sq_siw_solicitacao)
                                         where z.processo = 'N'
                                        group by x.sq_siw_solicitacao
                                       )                        b4 on (b.sq_siw_solicitacao       = b4.sq_siw_solicitacao)
                   left           join (select x.sq_siw_solicitacao, count(*) as qtd
                                          from siw_solicitacao               x
                                               inner join pa_eliminacao      y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                               inner join pa_documento       z on (y.protocolo          = z.sq_siw_solicitacao)
                                        group by x.sq_siw_solicitacao
                                       )                        b5 on (b.sq_siw_solicitacao       = b5.sq_siw_solicitacao)
                   left           join (select x.sq_siw_solicitacao, max(y.eliminacao) as dt_eliminacao
                                          from siw_solicitacao               x
                                               inner join pa_eliminacao      y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                               inner join pa_documento       z on (y.protocolo          = z.sq_siw_solicitacao)
                                        group by x.sq_siw_solicitacao
                                       )                        b6 on (b.sq_siw_solicitacao       = b6.sq_siw_solicitacao)
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
          where (p_menu           is null or (p_menu        is not null and a.sq_menu            = p_menu))
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and b.sq_plano           = p_sq_orprior))
            and (p_pais           is null or (p_pais        is not null and 0 < (select count(*) from pa_documento x join pa_eliminacao y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and x.prefixo = p_pais)))
            and (p_regiao         is null or (p_regiao      is not null and 0 < (select count(*) from pa_documento x join pa_eliminacao y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and x.numero_documento = p_regiao)))
            and (p_cidade         is null or (p_cidade      is not null and 0 < (select count(*) from pa_documento x join pa_eliminacao y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and x.ano = p_cidade)))
            and (p_usu_resp       is null or (p_usu_resp    is not null and 0 < (select count(*) from pa_documento x join pa_eliminacao y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and x.sq_especie_documento = p_usu_resp)))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and e.sq_unidade = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc              = p_sqcc))
            and (p_projeto        is null or (p_projeto     is not null and b.sq_solic_pai       = p_projeto))
            and (p_processo       is null or (p_processo    is not null and 0 < (select count(*)
                                                                                   from pa_eliminacao                    w
                                                                                        inner   join pa_documento_interessado x on (w.sq_siw_solicitacao = x.sq_siw_solicitacao)
                                                                                          inner join co_pessoa                y on (x.sq_pessoa = y.sq_pessoa)
                                                                                  where w.sq_siw_solicitacao          = b.sq_siw_solicitacao
                                                                                    and (acentos(y.nome_indice)       like '%'||acentos(p_processo)||'%' or
                                                                                         acentos(y.nome_resumido_ind) like '%'||acentos(p_processo)||'%'
                                                                                        )
                                                                                )
                                             )
                )
            and (p_uf             is null or (p_uf          is not null and 0 < (select count(*) from pa_documento x join pa_eliminacao y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and x.processo = p_uf)))
            and (p_proponente     is null or (p_proponente  is not null and (0 < (select count(*) from pa_documento x join pa_eliminacao y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and to_char(x.pessoa_origem) = p_proponente) or
                                                                             0 < (select count(*) from pa_documento x join pa_eliminacao y on x.sq_siw_solicitacao = y.protocolo inner join co_pessoa z on x.pessoa_origem = z.sq_pessoa where y.sq_siw_solicitacao = b.sq_siw_solicitacao and z.nome_indice like '%'||acentos(p_proponente)||'%' or z.nome_resumido_ind like '%'||acentos(p_proponente)||'%')
                                                                            )
                                             )
                )
            and (p_assunto        is null or (p_assunto     is not null and 0 < (select count(*) from siw_solicitacao x join pa_eliminacao y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and acentos(x.descricao,null) like '%'||acentos(p_assunto,null)||'%')))
            --and (p_palavra        is null or (p_palavra     is not null and acentos(d.numero_certame,null) like '%'||acentos(p_palavra,null)||'%'))
            and (p_empenho        is null or (p_empenho     is not null and 0 < (select count(*) from pa_documento x join pa_eliminacao y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and acentos(x.numero_original) like '%'||acentos(p_empenho)||'%')))
            --and (p_prioridade     is null or (p_prioridade  is not null and d.prioridade         = p_prioridade))
            and (coalesce(p_ativo,'N') = 'N' or (p_ativo = 'S' and b.conclusao is null))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (p_prazo          is null or (p_prazo       is not null and b1.sigla not in ('CI','AT') and cast(cast(b.fim as date)-cast(now() as date) as integer)+1 <=p_prazo))
            and (p_ini_i          is null or (p_ini_i       is not null and b.inicio             between p_ini_i and p_ini_f))
            and (p_fim_i          is null or (p_fim_i       is not null and 0 < (select count(*) from pa_documento x join pa_eliminacao y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and y.eliminacao between p_fim_i and p_fim_f)))
            and (coalesce(p_atraso,'N') = 'N' or (p_atraso = 'S' and b1.sigla not in ('CI','AT') and b.fim+1-now()<0))
            and (p_unidade        is null or (p_unidade     is not null and 0 < (select count(*) from siw_solicitacao x join pa_eliminacao y on x.sq_siw_solicitacao = y.protocolo where y.sq_siw_solicitacao = b.sq_siw_solicitacao and x.sq_unidade = p_unidade)))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and ((p_tipo         = 1     and coalesce(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and coalesce(b1.sigla,'-') <> 'CI' and b.executor = p_pessoa and b.conclusao is null) or
                 (p_tipo         = 2     and b1.ativo = 'S' and coalesce(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and coalesce(b1.sigla,'-') <> 'CA') or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0 and b1.sigla <> 'CI')
                );
   Elsif p_restricao = 'EMPREST' Then
      -- Recupera as solicitações que o usuário pode ver
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
          where b3.protocolo = to_number(p_processo)
            and ((p_tipo         = 1     and coalesce(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and coalesce(b1.sigla,'-') <> 'CI' and b.executor = p_pessoa and b.conclusao is null) or
                 (p_tipo         = 2     and b1.ativo = 'S' and coalesce(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and coalesce(b1.sigla,'-') <> 'CA') or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0 and b1.sigla <> 'CI')
                );
   End If;

  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;