create or replace procedure SP_GetSolicIndic_IS
   (p_chave     in number   default null,
    p_chave_aux in number   default null,
    p_loa       in varchar2 default null,
    p_exequivel in varchar2 default null,    
    p_restricao in varchar2,
    p_result    out siw.siw.sys_refcursor) is
begin
  If p_restricao = 'LISTA' Then
      -- Recupera todas os indicadores de um programa
      open p_result for 
         select a.*, a.cd_indicador, b.sq_pessoa titular, c.sq_pessoa substituto, 
                k.sq_pessoa tit_exec, l.sq_pessoa sub_exec,
                d.nome_resumido||' ('||f.sigla||')' nm_resp, f.sigla sg_setor,
                g.nome nm_unidade_medida, h.nome nm_periodicidade, m.nome nm_base_geografica,
                i.solicitante, i.sq_unidade,
                decode(a.tipo,'R','Resultado','P','Processo','Indisponível') nm_tipo
           from is_indicador                        a,
                siw.siw_solicitacao i,
                siw.siw_menu        j,
                siw.eo_unidade_resp k,
                siw.eo_unidade_resp l,
                siw.eo_unidade_resp b,
                siw.eo_unidade_resp c,
                siw.co_pessoa       d,
                siw.sg_autenticacao e,
                siw.eo_unidade      f,
                is_sig_unidade_medida  g,
                is_sig_periodicidade   h, 
                is_sig_base_geografica m
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
            and (b.sq_pessoa          = d.sq_pessoa (+))
            and (d.sq_pessoa          = e.sq_pessoa (+))
            and (e.sq_unidade         = f.sq_unidade (+))
            and (a.cd_unidade_medida  = g.cd_unidade_medida (+))
            and (a.cd_periodicidade   = h.cd_periodicidade (+))
            and (a.cd_base_geografica = m.cd_base_geografica (+))
            and a.sq_siw_solicitacao = p_chave
            and (p_loa       is null or (p_loa       is not null and a.cd_indicador is not null))
            and (p_exequivel is null or (p_exequivel is not null and a.exequivel = p_exequivel));            
   Elsif p_restricao = 'REGISTRO' Then
      -- Recupera os dados de um indicador do programa
      open p_result for 
         select a.*, b.sq_pessoa titular, c.sq_pessoa substituto, 
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor,
                i.solicitante sq_pessoa, i.sq_unidade,
                decode(a.cumulativa,'S','Sim','Não') nm_cumulativa,  
                decode(a.exequivel,'S','Sim','Não') nm_exequivel,
                decode(a.tipo,'P','Processo','R','Resultado','Não informado') nm_tipo,
                h.nome nm_unidade_medida, i.nome nm_periodicidade, m.nome nm_base_geografica,
                n.valor_apurado valor_apurado_ppa, n.valor_ppa, n.valor_programa, n.valor_mes_1, n.valor_mes_2,
                n.valor_mes_3, n.valor_mes_4, n.valor_mes_5, n.valor_mes_6, n.valor_mes_7, 
                n.valor_mes_8, n.valor_mes_9, n.valor_mes_10, n.valor_mes_11, n.valor_mes_12
            from is_indicador        a,
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
                 is_sig_unidade_medida  h,
                 is_sig_periodicidade   i,
                 is_sig_base_geografica m,
                 is_sig_indicador       n
          where (a.sq_siw_solicitacao = i.sq_siw_solicitacao)
            and (i.sq_menu            = j.sq_menu)
            and (j.sq_unid_executora  = k.sq_unidade (+) and
                 k.tipo_respons (+)       = 'T'          and
                 k.fim (+)                is null)
            and (j.sq_unid_executora  = l.sq_unidade (+) and
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
            and (a.cd_unidade_medida  = h.cd_unidade_medida (+))
            and (a.cd_periodicidade   = i.cd_periodicidade (+))
            and (a.cd_base_geografica = m.cd_base_geografica (+))
            and (a.cd_programa        = n.cd_programa (+)         and
                 a.cd_indicador       = n.cd_indicador (+)        and
                 a.cliente            = n.cliente (+)             and
                 a.ano                = n.ano (+))
            and a.sq_siw_solicitacao = p_chave
            and a.sq_indicador       = p_chave_aux;
   End If;
End SP_GetSolicIndic_IS;
/
