create or replace procedure SP_GetSolicIndic_IS
   (p_chave     in number   default null,
    p_chave_aux in number   default null,
    p_restricao in varchar2,
    p_result    out sys_refcursor) is
begin
  If p_restricao = 'LISTA' Then
      -- Recupera todas os indicadores de um programa
      open p_result for 
         select a.*, a.cd_indicador, b.sq_pessoa titular, c.sq_pessoa substituto, 
                k.sq_pessoa tit_exec, l.sq_pessoa sub_exec,
                d.nome_resumido||' ('||f.sigla||')' nm_resp, f.sigla sg_setor,
                g.nome nm_unidade_medida, h.nome nm_periodicidade, m.nome nm_base_geografica,
                i.solicitante, i.sq_unidade,
                case a.tipo when 'R' then 'Resultado' else case a.tipo when 'P' then 'Processo' else 'Indisponível' end end nm_tipo
           from is_indicador                        a
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
                left outer     join siw.co_pessoa       d on (b.sq_pessoa          = d.sq_pessoa)
                  left outer   join siw.sg_autenticacao e on (d.sq_pessoa          = e.sq_pessoa)
                    left outer join siw.eo_unidade      f on (e.sq_unidade         = f.sq_unidade)
                left outer     join is_sig_unidade_medida  g on (a.cd_unidade_medida  = g.cd_unidade_medida)
                left outer     join is_sig_periodicidade   h on (a.cd_periodicidade   = h.cd_periodicidade)
                left outer     join is_sig_base_geografica m on (a.cd_base_geografica = m.cd_base_geografica)
          where a.sq_siw_solicitacao = p_chave;
   Elsif p_restricao = 'REGISTRO' Then
      -- Recupera os dados de um indicador do programa
      open p_result for 
         select a.*, b.sq_pessoa titular, c.sq_pessoa substituto, 
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor,
                i.solicitante sq_pessoa, i.sq_unidade,
                case a.cumulativa when 'S' then 'Sim' else 'Não' end nm_cumulativa,  
                case a.exequivel  when 'S' then 'Sim' else 'Não' end nm_exequivel,
                case a.tipo       when 'P' then 'Processo' when 'R' then 'Resultado' else 'Não informado' end nm_tipo,
                h.nome nm_unidade_medida, i.nome nm_periodicidade, m.nome nm_base_geografica,
                n.valor_apurado valor_apurado_ppa, n.valor_ppa, n.valor_programa, n.valor_mes_1, n.valor_mes_2,
                n.valor_mes_3, n.valor_mes_4, n.valor_mes_5, n.valor_mes_6, n.valor_mes_7, 
                n.valor_mes_8, n.valor_mes_9, n.valor_mes_10, n.valor_mes_11, n.valor_mes_12
            from is_indicador                           a
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
                left outer     join is_sig_unidade_medida  h on (a.cd_unidade_medida  = h.cd_unidade_medida)
                left outer     join is_sig_periodicidade   i on (a.cd_periodicidade   = i.cd_periodicidade)
                left outer     join is_sig_base_geografica m on (a.cd_base_geografica = m.cd_base_geografica)
                left outer     join is_sig_indicador       n on (a.cd_programa        = n.cd_programa         and
                                                                 a.cd_indicador       = n.cd_indicador        and
                                                                 a.cliente            = n.cliente             and
                                                                 a.ano                = n.ano)
          where a.sq_siw_solicitacao = p_chave
            and a.sq_indicador       = p_chave_aux;
   End If;
End SP_GetSolicIndic_IS;
/
