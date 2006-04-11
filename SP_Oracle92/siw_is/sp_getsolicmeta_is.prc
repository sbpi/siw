create or replace procedure SP_GetSolicMeta_IS
   (p_chave       in number   default null,
    p_chave_aux   in number   default null,
    p_restricao   in varchar2,
    p_ano         in number   default null,
    p_unidade     in number   default null,
    p_cd_programa in varchar2 default null,
    p_cd_acao     in varchar2 default null,
    p_preenchida  in varchar2 default null,
    p_meta_ppa    in varchar2 default null,
    p_exequivel   in varchar2 default null,
    p_result      out sys_refcursor) is
    
    w_cd_subacao varchar(4);
    
begin
  If p_restricao = 'LISTA' Then
      -- Recupera todas as metas de um ação
      open p_result for 
         select a.*, b.sq_pessoa titular, c.sq_pessoa substituto, 
                k.sq_pessoa tit_exec, l.sq_pessoa sub_exec,
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor,
                i.solicitante, i.sq_unidade
           from is_meta                             a
                inner          join siw.siw_solicitacao i on (a.sq_siw_solicitacao = i.sq_siw_solicitacao)
                  inner        join siw.siw_menu        j on (i.sq_menu            = j.sq_menu)
                    left outer join siw.eo_unidade_resp k on (j.sq_unid_executora  = k.sq_unidade and
                                                          k.tipo_respons       = 'T'          and
                                                          k.fim                is null
                                                         )
                    left outer join siw.eo_unidade_resp l on (j.sq_unid_executora = l.sq_unidade and
                                                          l.tipo_respons       = 'S'          and
                                                          l.fim                is null
                                                         )
                left outer     join siw.eo_unidade_resp b on (i.sq_unidade         = b.sq_unidade and
                                                          b.tipo_respons       = 'T'          and
                                                          b.fim                is null
                                                         )
                left outer     join siw.eo_unidade_resp c on (i.sq_unidade         = c.sq_unidade and
                                                          c.tipo_respons       = 'S'          and
                                                          c.fim                is null
                                                         )
                inner          join siw.co_pessoa       d on (i.solicitante        = d.sq_pessoa)
                  inner        join siw.sg_autenticacao e on (d.sq_pessoa          = e.sq_pessoa)
                    inner      join siw.eo_unidade      f on (e.sq_unidade         = f.sq_unidade)
                inner          join siw.eo_unidade      g on (i.sq_unidade         = g.sq_unidade)
          where a.sq_siw_solicitacao = p_chave;
   ElsIf p_restricao = 'LSTNULL' Then
      -- Recupera as metas principais de uma ação
      open p_result for 
         select a.*, b.sq_pessoa titular, c.sq_pessoa substituto, i.executor, i.solicitante,
                case a.programada when 'S' then 'Sim' else 'Não' end nm_programada,
                case a.cumulativa when 'S' then 'Sim' else 'Não' end nm_cumulativa,  
                case a.exequivel  when 'S' then 'Sim' else 'Não' end nm_exequivel,    
                k.sq_pessoa tit_exec, l.sq_pessoa sub_exec,
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.nome ||' - '||g.sigla sg_setor,
                h.sigla sg_tramite, h.nome descricao_tramite, 
                n.cd_unidade, n.cd_programa, n.cd_acao, n.descricao_acao, 
                o.nome descricao_programa, p.nome descricao_unidade                
           from is_meta                                 a
                inner          join siw.siw_solicitacao i on (a.sq_siw_solicitacao = i.sq_siw_solicitacao)
                  inner        join is_acao             m on (i.sq_siw_solicitacao = m.sq_siw_solicitacao)
                    inner join is_sig_programa          o on (m.cd_programa        = o.cd_programa     and
                                                              m.cliente            = o.cliente         and
                                                              m.ano                = o.ano)
                    inner      join is_sig_acao         n on (m.cd_programa        = n.cd_programa     and
                                                              m.cd_acao            = n.cd_acao         and
                                                              m.cd_subacao         = n.cd_subacao      and
                                                              m.cd_unidade         = n.cd_unidade      and
                                                              m.cliente            = n.cliente         and
                                                              m.ano                = n.ano)
                      inner    join is_sig_unidade      p on (n.cd_unidade         = p.cd_unidade      and
                                                              n.cd_tipo_unidade    = p.cd_tipo_unidade and
                                                              n.ano                = p.ano)
                  inner        join siw.siw_menu        j on (i.sq_menu            = j.sq_menu)
                    left outer join siw.eo_unidade_resp k on (j.sq_unid_executora  = k.sq_unidade and
                                                              k.tipo_respons       = 'T'          and
                                                              k.fim                is null)
                    left outer join siw.eo_unidade_resp l on (j.sq_unid_executora = l.sq_unidade and
                                                              l.tipo_respons       = 'S'          and
                                                              l.fim                is null)
                left outer     join siw.eo_unidade_resp b on (i.sq_unidade         = b.sq_unidade and
                                                              b.tipo_respons       = 'T'          and
                                                              b.fim                is null)
                left outer     join siw.eo_unidade_resp c on (i.sq_unidade         = c.sq_unidade and
                                                              c.tipo_respons       = 'S'          and
                                                              c.fim                is null)
                inner          join siw.co_pessoa       d on (i.solicitante        = d.sq_pessoa)
                  inner        join siw.sg_autenticacao e on (d.sq_pessoa          = e.sq_pessoa)
                    inner      join siw.eo_unidade      f on (e.sq_unidade         = f.sq_unidade)
                inner          join siw.eo_unidade      g on (i.sq_unidade         = g.sq_unidade)
                inner          join siw.siw_tramite     h on (i.sq_siw_tramite     = h.sq_siw_tramite)
          where (Nvl(h.sigla,'-')     <> 'CA')
            and (p_chave              is null or (p_chave       is not null and a.sq_siw_solicitacao = p_chave))
            and (p_unidade            is null or (p_unidade     is not null and g.sq_unidade         = p_unidade))
            and (p_ano                is null or (p_ano         is not null and i.ano                = p_ano))
            and (p_cd_programa        is null or (p_cd_programa is not null and m.cd_programa        = p_cd_programa))
            and (p_cd_acao            is null or (p_cd_acao     is not null and m.cd_acao            = p_cd_acao))
            and (p_preenchida         is null or (p_preenchida  = 'S' and trim(a.descricao)          is not null) or (p_preenchida  = 'N' and trim(a.descricao)    is null))
            and (p_meta_ppa           is null or (p_meta_ppa    = 'S' and a.cd_subacao               is not null) or (p_meta_ppa    = 'N' and a.cd_subacao         is null))
            and (p_exequivel          is null or (p_exequivel   is not null and a.exequivel          = p_exequivel));
   ElsIf p_restricao = 'LSTNIVEL' Then
      -- Recupera as metas vinculadas a uma meta da ação
      open p_result for 
         select a.*, b.sq_pessoa titular, c.sq_pessoa substituto, i.executor, i.solicitante,
                k.sq_pessoa tit_exec, l.sq_pessoa sub_exec,
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor
           from is_meta                             a
                inner          join siw.siw_solicitacao i on (a.sq_siw_solicitacao = i.sq_siw_solicitacao)
                  inner        join siw.siw_menu        j on (i.sq_menu            = j.sq_menu)
                    left outer join siw.eo_unidade_resp k on (j.sq_unid_executora  = k.sq_unidade and
                                                          k.tipo_respons       = 'T'          and
                                                          k.fim                is null
                                                         )
                    left outer join siw.eo_unidade_resp l on (j.sq_unid_executora = l.sq_unidade and
                                                          l.tipo_respons       = 'S'          and
                                                          l.fim                is null
                                                         )
                left outer     join siw.eo_unidade_resp b on (i.sq_unidade         = b.sq_unidade and
                                                          b.tipo_respons       = 'T'          and
                                                          b.fim                is null
                                                         )
                left outer     join siw.eo_unidade_resp c on (i.sq_unidade         = c.sq_unidade and
                                                          c.tipo_respons       = 'S'          and
                                                          c.fim                is null
                                                         )
                inner          join siw.co_pessoa       d on (i.solicitante        = d.sq_pessoa)
                  inner        join siw.sg_autenticacao e on (d.sq_pessoa          = e.sq_pessoa)
                    inner      join siw.eo_unidade      f on (e.sq_unidade         = f.sq_unidade)
                inner          join siw.eo_unidade      g on (i.sq_unidade         = g.sq_unidade)
          where a.sq_siw_solicitacao = p_chave;
   Elsif p_restricao = 'REGISTRO' Then
      -- Recupera os dados de uma meta da ação
      select cd_subacao into w_cd_subacao from is_meta where sq_meta = p_chave_aux;      
      open p_result for 
         select a.*, b.sq_pessoa titular, c.sq_pessoa substituto, 
                case a.programada when 'S' then 'Sim' else 'Não' end nm_programada,
                case a.cumulativa when 'S' then 'Sim' else 'Não' end nm_cumulativa,                
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor,
                i.solicitante sq_pessoa, i.sq_unidade,
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
            from is_meta                             a
                inner          join siw.siw_solicitacao i on (a.sq_siw_solicitacao = i.sq_siw_solicitacao)
                  inner        join siw.siw_menu        j on (i.sq_menu            = j.sq_menu)
                    left outer join siw.eo_unidade_resp k on (j.sq_unid_executora  = k.sq_unidade and
                                                          k.tipo_respons       = 'T'          and
                                                          k.fim                is null
                                                         )
                    left outer join siw.eo_unidade_resp l on (j.sq_unid_executora = l.sq_unidade and
                                                          l.tipo_respons       = 'S'          and
                                                          l.fim                is null
                                                         )
                left outer     join siw.eo_unidade_resp b on (i.sq_unidade         = b.sq_unidade and
                                                          b.tipo_respons       = 'T'          and
                                                          b.fim                is null
                                                         )
                left outer     join siw.eo_unidade_resp c on (i.sq_unidade         = c.sq_unidade and
                                                          c.tipo_respons       = 'S'          and
                                                          c.fim                is null
                                                         )
                inner          join siw.co_pessoa       d on (i.solicitante        = d.sq_pessoa)
                  inner        join siw.sg_autenticacao e on (d.sq_pessoa          = e.sq_pessoa)
                    inner      join siw.eo_unidade      f on (e.sq_unidade         = f.sq_unidade)
                inner          join siw.eo_unidade      g on (i.sq_unidade         = g.sq_unidade)
                left outer     join is_acao             h on (a.sq_siw_solicitacao = h.sq_siw_solicitacao)
                left outer     join (select x.ano, x.cliente, x.cd_programa, x.cd_acao, y.cd_subacao, z.descricao_subacao,
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
                  where w.sq_siw_solicitacao = p_chave
                    and y.sq_meta            = p_chave_aux
                    and y.sq_siw_solicitacao  = w.sq_siw_solicitacao
                    and (w.cd_programa        = x.cd_programa     and
                         w.cd_acao            = x.cd_acao         and
                         x.cd_subacao  (+)    = w_cd_subacao)
                    and (w.cd_programa        = v.cd_programa (+) and
                         w.cd_acao            = v.cd_acao     (+) and
                         v.cd_subacao  (+)    = w_cd_subacao)                         
                    and (w.cd_programa        = z.cd_programa     and
                         w.cd_acao            = z.cd_acao         and
                         w.cd_subacao         = z.cd_subacao      and
                         w.ano                = z.ano             and
                         w.cliente            = z.cliente         and
                         z.cd_subacao  (+)    = w_cd_subacao)
                                  )                     m on (h.cd_programa        = m.cd_programa and
                                                              h.cd_acao            = m.cd_acao     and
                                                              h.cliente            = m.cliente     and
                                                              h.ano                = m.ano)
          where a.sq_siw_solicitacao = p_chave
            and a.sq_meta = p_chave_aux;

   Elsif p_restricao = 'FILHOS' Then
      -- Recupera as metas subordinadas a outra da mesma ação
      open p_result for 
         select a.*
           from is_meta   a
          where a.sq_meta   = p_chave_aux;
   End If;
End SP_GetSolicMeta_IS;
/
