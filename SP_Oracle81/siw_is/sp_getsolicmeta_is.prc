create or replace procedure SP_GetSolicMeta_IS
   (p_cliente     in number,
    p_chave       in number   default null,
    p_chave_aux   in number   default null,
    p_restricao   in varchar2,
    p_ano         in number   default null,
    p_unidade     in number   default null,
    p_cd_programa in varchar2 default null,
    p_cd_acao     in varchar2 default null,
    p_preenchida  in varchar2 default null,
    p_meta_ppa    in varchar2 default null,
    p_exequivel   in varchar2 default null,
    p_programada  in varchar2 default null,
    p_atraso      in varchar2 default null,    
    p_result      out siw.siw.sys_refcursor) is
    
    w_cd_subacao  varchar(4);
begin
  If p_restricao = 'LISTA' Then
      -- Recupera todas as metas de um a��o
      open p_result for 
         select a.*, to_char(a.ultima_atualizacao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_ultima_atualizacao,
                b.sq_pessoa titular, c.sq_pessoa substituto,
                k.sq_pessoa tit_exec, l.sq_pessoa sub_exec,
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor,
                i.solicitante, i.sq_unidade, i.executor
           from is_meta             a,
                siw.siw_solicitacao i,
                siw.siw_menu        j,
                siw.eo_unidade_resp k,
                siw.eo_unidade_resp l,
                siw.eo_unidade_resp b,
                siw.eo_unidade_resp c,
                siw.co_pessoa       d,
                siw.sg_autenticacao e,
                siw.eo_unidade      f,
                siw.eo_unidade      g
          where (a.sq_siw_solicitacao = i.sq_siw_solicitacao)
            and (i.sq_menu            = j.sq_menu)
            and (j.sq_unid_executora  = k.sq_unidade (+) and
                 k.tipo_respons (+)       = 'T'          and
                 k.fim (+)                is null)
            and (j.sq_unid_executora = l.sq_unidade (+) and
                 l.tipo_respons (+)       = 'S'          and
                 l.fim (+)                is null)
            and (i.sq_unidade         = b.sq_unidade (+) and
                 b.tipo_respons (+)       = 'T'          and
                 b.fim (+)                is null)
            and (i.sq_unidade         = c.sq_unidade (+) and
                 c.tipo_respons (+)       = 'S'          and
                 c.fim (+)                is null)
            and (i.solicitante        = d.sq_pessoa)
            and (d.sq_pessoa          = e.sq_pessoa)
            and (e.sq_unidade         = f.sq_unidade)
            and (i.sq_unidade         = g.sq_unidade)
            and j.sq_pessoa          = p_cliente
            and a.sq_siw_solicitacao = p_chave;
   ElsIf p_restricao = 'GERENCIAL' Then
      -- Recupera informa��es para relat�rio gerencial
      open p_result for 
         select a.cd_opcao, a.nome nm_opcao, b.cd_macro, b.nome nm_macro,
                c1.sq_siw_solicitacao sq_programa, c.cd_programa, c.nome nm_programa, f.cd_tipo_programa, f.nome nm_tipo_programa,
                g.cd_acao, g.cd_subacao, g.descricao_acao nm_acao, g.descricao_subacao nm_subacao, h.cd_tipo_acao, h.nome nm_tipo_acao,
                i.cd_produto, i.nome nm_produto, j.nome nm_unid_med_produto,
                m.nome nm_estagio, n.nome nm_andamento, o.nome nm_cronograma,
                p.sq_siw_solicitacao sq_acao,
                q.ordem or_meta, q.titulo nm_meta, 
                decode(nvl(q.cd_subacao,'N'),'N','N�o','Sim') nm_meta_ppa, 
                decode(nvl(q.exequivel,'N'),'N','N�o','Sim') ex_meta, 
                q.perc_conclusao pr_meta, q.situacao_atual st_meta, q.fim_previsto dt_meta
           from is_sig_opcao_estrat       a,
                is_sig_macro_objetivo     b,
                is_sig_programa           c,
                is_programa               c1,
                is_sig_tipo_programa      f,
                is_sig_acao               g,
                is_sig_tipo_acao          h,
                is_sig_produto            i,
                is_sig_unidade_medida     j,
                is_sig_tipo_situacao      m,
                is_sig_tipo_situacao      n,
                is_sig_tipo_situacao      o,
                is_acao                   p,
                is_meta                   q,
                siw.siw_solicitacao       r,
                siw.siw_tramite           s
          where (a.cd_opcao            = b.cd_opcao)
            and (b.cd_macro            = c.cd_macro)
            and (c.cliente             = c1.cliente and c.ano = c1.ano and c.cd_programa = c1.cd_programa)
            and (c.cd_tipo_programa    = f.cd_tipo_programa)
            and (c.cliente             = g.cliente and c.ano = g.ano and c.cd_programa = g.cd_programa)
            and (g.cd_tipo_acao        = h.cd_tipo_acao)
            and (g.cd_produto          = i.cd_produto (+))
            and (g.cd_unidade_medida   = j.cd_unidade_medida (+))
            and (g.cd_estagio          = m.cd_tipo_situacao (+))
            and (g.cd_andamento        = n.cd_tipo_situacao (+))
            and (g.cd_cronograma       = o.cd_tipo_situacao (+))
            and (g.cliente             = p.cliente (+) and g.ano = p.ano (+) and g.cd_programa = p.cd_programa (+) and g.cd_acao = p.cd_acao (+))
            and (p.sq_siw_solicitacao  = q.sq_siw_solicitacao (+) and g.cd_subacao = q.cd_subacao (+))
            and (p.sq_siw_solicitacao  = r.sq_siw_solicitacao (+))
            and (r.sq_siw_tramite      = s.sq_siw_tramite (+))
            and c.cliente = p_cliente
            and (Nvl(s.sigla,'-')     <> 'CA')
            and (p_unidade            is null or (p_unidade     is not null and r.sq_unidade         = p_unidade))
            and (p_ano                is null or (p_ano         is not null and c.ano                = p_ano))
            and (p_cd_programa        is null or (p_cd_programa is not null and c.cd_programa        = p_cd_programa))
            and (p_cd_acao            is null or (p_cd_acao     is not null and g.cd_acao            = p_cd_acao))
            and (p_meta_ppa           is null or (p_meta_ppa    = 'S' and p.sq_siw_solicitacao       is not null) or (p_meta_ppa    = 'N' and p.sq_siw_solicitacao is null))
            and (p_exequivel          is null or (p_exequivel   is not null and q.exequivel          = p_exequivel))
         order by a.cd_opcao, b.cd_macro, c.cd_programa, g.cd_acao, g.cd_subacao;
   ElsIf p_restricao = 'LSTNULL' Then  
      -- Recupera as metas principais de uma a��o
      open p_result for 
         select a.*, to_char(a.ultima_atualizacao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_ultima_atualizacao,
                b.sq_pessoa titular, c.sq_pessoa substituto, 
                decode(a.programada,'S','Sim','N�o') nm_programada,
                decode(a.cumulativa,'S','Sim','N�o') nm_cumulativa,  
                decode(a.exequivel,'S','Sim','N�o') nm_exequivel,    
                k.sq_pessoa tit_exec, l.sq_pessoa sub_exec,
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.nome ||' - '||g.sigla sg_setor,
                h.sigla sg_tramite, h.nome descricao_tramite ,
                n.cd_unidade, n.cd_programa, n.cd_acao, n.descricao_acao, 
                o.nome descricao_programa, p.nome descricao_unidade,
                i.solicitante, i.executor
           from is_meta             a,
                siw.siw_solicitacao i,
                is_acao             m,
                is_sig_acao         n,
                is_sig_programa     o,
                is_sig_unidade      p,
                siw.siw_menu        j,
                siw.eo_unidade_resp k,
                siw.eo_unidade_resp l,
                siw.eo_unidade_resp b,
                siw.eo_unidade_resp c,
                siw.co_pessoa       d,
                siw.sg_autenticacao e,
                siw.eo_unidade      f,
                siw.eo_unidade      g,
                siw.siw_tramite     h
          where (a.sq_siw_solicitacao = i.sq_siw_solicitacao)
            and (i.sq_siw_solicitacao = m.sq_siw_solicitacao)
            and (m.cd_programa        = n.cd_programa (+) and
                 m.cd_acao            = n.cd_acao     (+) and
                 m.cd_subacao         = n.cd_subacao  (+) and
                 m.cd_unidade         = n.cd_unidade  (+) and
                 m.cliente            = n.cliente     (+) and
                 m.ano                = n.ano         (+))  
            and (m.cd_programa        = o.cd_programa (+) and
                 m.cliente            = o.cliente     (+) and
                 m.ano                = o.ano         (+))
            and (n.cd_unidade         = p.cd_unidade  (+) and
                 n.cd_tipo_unidade    = p.cd_tipo_unidade (+) and
                 n.ano                = p.ano          (+))
            and (i.sq_menu            = j.sq_menu)
            and (j.sq_unid_executora  = k.sq_unidade (+)  and
                 k.tipo_respons (+)       = 'T'           and
                 k.fim (+)                is null)
            and (j.sq_unid_executora = l.sq_unidade (+)   and
                 l.tipo_respons (+)       = 'S'           and
                 l.fim (+)                is null)
            and (i.sq_unidade         = b.sq_unidade (+)  and
                 b.tipo_respons (+)       = 'T'           and
                 b.fim (+)                is null)
            and (i.sq_unidade         = c.sq_unidade (+)  and
                 c.tipo_respons (+)       = 'S'           and
                 c.fim (+)                is null)
            and (i.solicitante        = d.sq_pessoa)
            and (d.sq_pessoa          = e.sq_pessoa)
            and (e.sq_unidade         = f.sq_unidade)
            and (i.sq_unidade         = g.sq_unidade)
            and (i.sq_siw_tramite     = h.sq_siw_tramite)
            and j.sq_pessoa           = p_cliente
            and (Nvl(h.sigla,'-')     <> 'CA')
            and (p_chave              is null or (p_chave       is not null and a.sq_siw_solicitacao = p_chave))
            and (p_unidade            is null or (p_unidade     is not null and g.sq_unidade         = p_unidade))
            and (p_ano                is null or (p_ano         is not null and i.ano                = p_ano))
            and (p_cd_programa        is null or (p_cd_programa is not null and m.cd_programa        = p_cd_programa))
            and (p_cd_acao            is null or (p_cd_acao     is not null and m.cd_acao            = p_cd_acao))
            and (p_preenchida         is null or (p_preenchida  = 'S' and trim(a.descricao)          is not null) or (p_preenchida  = 'N' and trim(a.descricao)    is null))
            and (p_meta_ppa           is null or (p_meta_ppa    = 'S' and a.cd_subacao               is not null) or (p_meta_ppa    = 'N' and a.cd_subacao         is null))
            and (p_programada         is null or (p_programada  = 'S' and a.programada               = 'S')       or (p_programada  = 'N' and a.programada         = 'N'))
            and (p_exequivel          is null or (p_exequivel   is not null and a.exequivel          = p_exequivel))
            and (p_atraso             is null or (a.fim_previsto < sysdate() and a.perc_conclusao < 100));
   ElsIf p_restricao = 'LSTNIVEL' Then
      -- Recupera as metas vinculadas a uma meta da a��o
      open p_result for 
         select a.*, to_char(a.ultima_atualizacao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_ultima_atualizacao,
                b.sq_pessoa titular, c.sq_pessoa substituto, 
                k.sq_pessoa tit_exec, l.sq_pessoa sub_exec,
                i.solicitante, i.executor,
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor
           from is_meta                             a,
                siw.siw_solicitacao i,
                siw.siw_menu        j,
                siw.eo_unidade_resp k,
                siw.eo_unidade_resp l,
                siw.eo_unidade_resp b,
                siw.eo_unidade_resp c,
                siw.co_pessoa       d,
                siw.sg_autenticacao e,
                siw.eo_unidade      f,
                siw.eo_unidade      g
          where (a.sq_siw_solicitacao = i.sq_siw_solicitacao)
            and (i.sq_menu            = j.sq_menu)
            and (j.sq_unid_executora  = k.sq_unidade (+) and
                 k.tipo_respons (+)       = 'T'          and
                 k.fim (+)                is null)
            and (j.sq_unid_executora = l.sq_unidade (+) and
                 l.tipo_respons (+)       = 'S'          and
                 l.fim (+)                is null)
            and (i.sq_unidade         = b.sq_unidade (+) and
                 b.tipo_respons (+)       = 'T'          and
                 b.fim (+)                is null)
            and (i.sq_unidade         = c.sq_unidade (+) and
                 c.tipo_respons (+)       = 'S'          and
                 c.fim (+)                is null)
            and (i.solicitante        = d.sq_pessoa)
            and (d.sq_pessoa          = e.sq_pessoa)
            and (e.sq_unidade         = f.sq_unidade)
            and (i.sq_unidade         = g.sq_unidade)
            and j.sq_pessoa          = p_cliente
            and a.sq_siw_solicitacao = p_chave;
   Elsif p_restricao = 'REGISTRO' Then
      -- Recupera os dados de uma meta da a��o
      select cd_subacao into w_cd_subacao from is_meta where sq_meta = p_chave_aux;
      open p_result for 
         select a.*, to_char(a.ultima_atualizacao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_ultima_atualizacao,
                b.sq_pessoa titular, c.sq_pessoa substituto, 
                decode(a.programada,'S','Sim','N�o') nm_programada,
                decode(a.cumulativa,'S','Sim','N�o') nm_cumulativa,                
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor,
                i.solicitante sq_pessoa, i.sq_unidade, i.executor,
                m.cd_programa, m.cd_acao, m.descricao_subacao, m.cd_subacao, m.cd_unidade,
                m.real_mes_1, m.real_mes_2, m.real_mes_3, m.real_mes_4, m.real_mes_5,
                m.real_mes_6, m.real_mes_7, m.real_mes_8, m.real_mes_9, m.real_mes_10,
                m.real_mes_11, m.real_mes_12, m.previsao_ano, m.atual_ano, m.real_ano,
                m.cron_ini_mes_1, m.cron_ini_mes_2, m.cron_ini_mes_3, m.cron_ini_mes_4,
                m.cron_ini_mes_5, m.cron_ini_mes_6, m.cron_ini_mes_7, m.cron_ini_mes_8,
                m.cron_ini_mes_9, m.cron_ini_mes_10, m.cron_ini_mes_11, m.cron_ini_mes_12,
                m.valor_ini_1, m.valor_ini_2, m.valor_ini_3, m.valor_ini_4, m.valor_ini_5, 
                m.valor_ini_6, m.valor_ini_7, m.valor_ini_8, m.valor_ini_9, m.valor_ini_10, 
                m.valor_ini_11, m.valor_ini_12
            from is_meta            a,
                siw.siw_solicitacao i,
                siw.siw_menu        j,
                siw.eo_unidade_resp k,
                siw.eo_unidade_resp l,
                siw.eo_unidade_resp b,
                siw.eo_unidade_resp c,
                siw.co_pessoa       d,
                siw.sg_autenticacao e,
                siw.eo_unidade      f,
                siw.eo_unidade      g,
                is_acao             h,
                (select x.ano, x.cliente, x.cd_programa, x.cd_acao, y.cd_subacao, z.descricao_subacao,
                        w.cd_unidade, 
                        x.real_mes_1, x.real_mes_2, x.real_mes_3, x.real_mes_4, x.real_mes_5,
                        x.real_mes_6, x.real_mes_7, x.real_mes_8, x.real_mes_9, x.real_mes_10,
                        x.real_mes_11, x.real_mes_12, x.previsao_ano, x.atual_ano, x.real_ano,
                        x.cron_ini_mes_1 valor_ini_1, x.cron_ini_mes_2 valor_ini_2, x.cron_ini_mes_3 valor_ini_3, x.cron_ini_mes_4 valor_ini_4, 
                        x.cron_ini_mes_5 valor_ini_5, x.cron_ini_mes_6 valor_ini_6, x.cron_ini_mes_7 valor_ini_7, x.cron_ini_mes_8 valor_ini_8, 
                        x.cron_ini_mes_9 valor_ini_9, x.cron_ini_mes_10 valor_ini_10, x.cron_ini_mes_11 valor_ini_11, x.cron_ini_mes_12 valor_ini_12, 
                        v.cron_ini_mes_1, v.cron_ini_mes_2, v.cron_ini_mes_3, v.cron_ini_mes_4,
                        v.cron_ini_mes_5, v.cron_ini_mes_6, v.cron_ini_mes_7, v.cron_ini_mes_8,
                        v.cron_ini_mes_9, v.cron_ini_mes_10, v.cron_ini_mes_11, v.cron_ini_mes_12
                   from is_acao                w,
                        is_sig_dado_financeiro x,
                        is_sig_dado_fisico     v,
                        is_meta                y,
                        is_sig_acao            z
                  where z.cliente            = p_cliente
                    and w.sq_siw_solicitacao = p_chave
                    and y.sq_meta            = p_chave_aux
                    and w.sq_siw_solicitacao  = w.sq_siw_solicitacao
                    and (w.cd_programa        = x.cd_programa (+) and
                         w.cd_acao            = x.cd_acao     (+) and
                         w.ano                = x.ano         (+) and
                         x.cd_subacao(+)      = w_cd_subacao)
                    and (w.cd_programa        = v.cd_programa (+) and
                         w.cd_acao            = v.cd_acao     (+) and
                         w.ano                = v.ano         (+) and
                         v.cd_subacao(+)      = w_cd_subacao)
                    and (w.cd_programa        = z.cd_programa     and
                         w.cd_acao            = z.cd_acao         and
                         w.ano                = z.ano             and
                         w.cliente            = z.cliente         and
                         z.cd_subacao(+)      = w_cd_subacao)
                                  ) m
          where (a.sq_siw_solicitacao = i.sq_siw_solicitacao)
            and (i.sq_menu            = j.sq_menu)
            and (j.sq_unid_executora  = k.sq_unidade (+) and
                 k.tipo_respons (+)       = 'T'          and
                 k.fim (+)                is null)
            and (j.sq_unid_executora = l.sq_unidade (+) and
                 l.tipo_respons (+)       = 'S'          and
                 l.fim (+)                is null)
            and (i.sq_unidade         = b.sq_unidade (+) and
                 b.tipo_respons (+)       = 'T'          and
                 b.fim (+)                is null)
            and (i.sq_unidade         = c.sq_unidade (+) and
                 c.tipo_respons (+)       = 'S'          and
                 c.fim (+)                is null)
            and (i.solicitante        = d.sq_pessoa)
            and (d.sq_pessoa          = e.sq_pessoa)
            and (e.sq_unidade         = f.sq_unidade)
            and (i.sq_unidade         = g.sq_unidade)
            and (a.sq_siw_solicitacao = h.sq_siw_solicitacao (+))
            and (h.cd_programa        = m.cd_programa        (+) and
                 h.cd_acao            = m.cd_acao            (+) and
                 h.cliente            = m.cliente            (+) and
                 h.ano                = m.ano                (+))
            and j.sq_pessoa          = p_cliente
            and a.sq_siw_solicitacao = p_chave
            and a.sq_meta = p_chave_aux;

   Elsif p_restricao = 'FILHOS' Then
      -- Recupera as metas subordinadas a outra da mesma a��o
      open p_result for 
         select a.*
           from is_meta   a
          where a.sq_meta   = p_chave_aux;
   End If;
End SP_GetSolicMeta_IS;
/
