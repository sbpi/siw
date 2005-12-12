create or replace procedure SP_GetSolicMeta_IS
   (p_chave     in number   default null,
    p_chave_aux in number   default null,
    p_restricao in varchar2,
    p_ano       in number   default null,
    p_result    out siw.siw.sys_refcursor) is
begin
  If p_restricao = 'LISTA' Then
      -- Recupera todas as metas de um ação
      open p_result for 
         select a.*, b.sq_pessoa titular, c.sq_pessoa substituto, 
                k.sq_pessoa tit_exec, l.sq_pessoa sub_exec,
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor,
                i.solicitante, i.sq_unidade
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
            and a.sq_siw_solicitacao = p_chave;
   ElsIf p_restricao = 'LSTNULL' Then
      -- Recupera as metas principais de uma ação
      open p_result for 
         select a.*, b.sq_pessoa titular, c.sq_pessoa substituto, i.executor, i.solicitante,
                decode(a.programada,'S','Sim','Não') nm_programada,
                decode(a.cumulativa,'S','Sim','Não') nm_cumulativa,  
                decode(a.exequivel,'S','Sim','Não') nm_exequivel,    
                k.sq_pessoa tit_exec, l.sq_pessoa sub_exec,
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor,
                h.sigla sg_tramite
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
                siw.eo_unidade      g,
                siw.siw_tramite     h
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
            and (i.sq_siw_tramite     = h.sq_siw_tramite)
            and a.sq_siw_solicitacao = p_chave;
   ElsIf p_restricao = 'LSTNIVEL' Then
      -- Recupera as metas vinculadas a uma meta da ação
      open p_result for 
         select a.*, b.sq_pessoa titular, c.sq_pessoa substituto, i.executor, i.solicitante,
                k.sq_pessoa tit_exec, l.sq_pessoa sub_exec,
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
            and a.sq_siw_solicitacao = p_chave;
   Elsif p_restricao = 'REGISTRO' Then
      -- Recupera os dados de uma meta da ação
      open p_result for 
         select a.*, b.sq_pessoa titular, c.sq_pessoa substituto, 
                decode(a.programada,'S','Sim','Não') nm_programada,
                decode(a.cumulativa,'S','Sim','Não') nm_cumulativa,                
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor,
                i.solicitante sq_pessoa, i.sq_unidade,
                m.real_mes_1, m.real_mes_2, m.real_mes_3, m.real_mes_4, m.real_mes_5,
                m.real_mes_6, m.real_mes_7, m.real_mes_8, m.real_mes_9, m.real_mes_10,
                m.real_mes_11, m.real_mes_12, m.previsao_ano, m.atual_ano, m.real_ano
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
                is_sig_dado_financeiro m
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
                 h.cd_subacao         = m.cd_subacao         (+) and
                 h.cliente            = m.cliente            (+) and
                 h.ano                = m.ano                (+))
            and a.sq_siw_solicitacao = p_chave
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
