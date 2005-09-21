create or replace procedure SP_GetSolicMeta_IS
   (p_chave     in number   default null,
    p_chave_aux in number   default null,
    p_restricao in varchar2,
    p_result    out sys_refcursor) is
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
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor,
                h.sigla sg_tramite
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
                inner          join siw.siw_tramite     h on (i.sq_siw_tramite     = h.sq_siw_tramite)
          where a.sq_siw_solicitacao = p_chave;
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
      open p_result for 
         select a.*, b.sq_pessoa titular, c.sq_pessoa substituto, 
                case a.programada when 'S' then 'Sim' else 'Não' end nm_programada,
                case a.cumulativa when 'S' then 'Sim' else 'Não' end nm_cumulativa,                
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor,
                i.solicitante sq_pessoa, i.sq_unidade,
                m.real_mes_1, m.real_mes_2, m.real_mes_3, m.real_mes_4, m.real_mes_5,
                m.real_mes_6, m.real_mes_7, m.real_mes_8, m.real_mes_9, m.real_mes_10,
                m.real_mes_11, m.real_mes_12, m.previsao_ano, m.atual_ano, m.real_ano
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
                left outer  join is_sig_dado_financeiro m on (h.cd_programa        = m.cd_programa and
                                                              h.cd_acao            = m.cd_acao     and
                                                              h.cd_subacao         = m.cd_subacao  and
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
