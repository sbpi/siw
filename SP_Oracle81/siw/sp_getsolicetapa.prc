create or replace procedure SP_GetSolicEtapa
   (p_chave      in number   default null,
    p_chave_aux  in number   default null,
    p_restricao  in varchar2,
    p_chave_aux2 in number   default null,
    p_result    out siw.sys_refcursor) is
begin
   If p_restricao = 'PAIS' Then
      -- Se for alteração, evita a exibição do próprio registro e dos seus subordinados
      open p_result for
      select a.sq_projeto_etapa, a.titulo
        from pj_projeto_etapa a
       where a.sq_siw_solicitacao = p_chave
         and a.sq_projeto_etapa not in (select b.sq_projeto_etapa
                                          from pj_projeto_etapa b
                                         where b.sq_siw_solicitacao   = p_chave
                                        start with b.sq_projeto_etapa = p_chave_aux
                                        connect by prior b.sq_projeto_etapa = b.sq_etapa_pai
                                       )
      order by acentos(a.titulo);
   ElsIf p_restricao = 'LISTA' Then
      -- Recupera todas as etapas de um projeto
      open p_result for
         select a.*, b.sq_pessoa titular, c.sq_pessoa substituto,
                k.sq_pessoa tit_exec, l.sq_pessoa sub_exec,
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor,
                nvl(h.qt_ativ,0) qt_ativ, h.sq_menu p2,
                m.vincula_contrato pj_vincula_contrato, nvl(n.qt_contr,0)                 
           from pj_projeto_etapa                a,
                siw_solicitacao i,
                pj_projeto      m,
                siw_menu        j,
                eo_unidade_resp k,
                eo_unidade_resp l,
                eo_unidade_resp b,
                eo_unidade_resp c,
                co_pessoa       d,
                sg_autenticacao e,
                eo_unidade      f,
                eo_unidade      g,
                (select x.sq_projeto_etapa, y.sq_menu, count(*) qt_ativ
                   from pj_etapa_demanda             x,
                        siw_solicitacao y,
                        siw_tramite     z
                  where (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                    and (y.sq_siw_tramite     = z.sq_siw_tramite and
                         Nvl(z.sigla,'-')     <> 'CA'
                        )
                 group by x.sq_projeto_etapa, y.sq_menu
                )                   h,
                (select x.sq_projeto_etapa, y.sq_menu, count(*) qt_contr
                   from pj_etapa_contrato             x,
                        siw_solicitacao y,
                        siw_tramite     z
                  where (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                    and (y.sq_siw_tramite     = z.sq_siw_tramite and
                         Nvl(z.sigla,'-')     <> 'CA'
                        )
                 group by x.sq_projeto_etapa, y.sq_menu
                )                   n
          where (a.sq_siw_solicitacao = i.sq_siw_solicitacao)
            and (i.sq_menu            = j.sq_menu)
            and (i.sq_siw_solicitacao = m.sq_siw_solicitacao)
            and (j.sq_unid_executora  = k.sq_unidade (+) and
                 k.tipo_respons(+)    = 'T'          and
                 k.fim(+)             is null
                )
            and (j.sq_unid_executora = l.sq_unidade (+) and
                 l.tipo_respons(+)    = 'S'          and
                 l.fim (+)            is null
                )
            and (a.sq_unidade         = b.sq_unidade (+) and
                 b.tipo_respons (+)   = 'T'          and
                 b.fim (+)            is null
                )
            and (a.sq_unidade         = c.sq_unidade (+) and
                 c.tipo_respons (+)   = 'S'          and
                 c.fim (+)            is null
                )
            and (a.sq_pessoa          = d.sq_pessoa)
            and (d.sq_pessoa          = e.sq_pessoa)
            and (e.sq_unidade         = f.sq_unidade)
            and (a.sq_unidade         = g.sq_unidade)
            and (h.sq_projeto_etapa = a.sq_projeto_etapa (+))
            and (n.sq_projeto_etapa = a.sq_projeto_etapa (+))
            and a.sq_siw_solicitacao = p_chave;
   ElsIf p_restricao = 'LSTNULL' Then
      -- Recupera as etapas principais de um projeto
      open p_result for
         select a.*, b.sq_pessoa titular, c.sq_pessoa substituto, i.executor, i.solicitante,
                decode(a.programada,'S','Sim','Não') nm_programada,
                decode(a.cumulativa,'S','Sim','Não') nm_cumulativa,
                decode(a.exequivel,'S','Sim','Não') nm_exequivel,
                k.sq_pessoa tit_exec, l.sq_pessoa sub_exec,
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor,
                nvl(h.qt_ativ,0) qt_ativ, h.sq_menu p2,
                m.vincula_contrato pj_vincula_contrato, nvl(n.qt_contr,0)  
           from pj_projeto_etapa                a,
                siw_solicitacao i,
                pj_projeto      m,
                siw_menu        j,
                eo_unidade_resp k,
                eo_unidade_resp l,
                eo_unidade_resp b,
                eo_unidade_resp c,
                co_pessoa       d,
                sg_autenticacao e,
                eo_unidade      f,
                eo_unidade      g,
                (select x.sq_projeto_etapa, y.sq_menu, count(*) qt_ativ
                   from pj_etapa_demanda             x,
                        siw_solicitacao y,
                        siw_tramite     z
                  where (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                    and (y.sq_siw_tramite     = z.sq_siw_tramite and
                         Nvl(z.sigla,'-')     <> 'CA'
                        )
                 group by x.sq_projeto_etapa, y.sq_menu
                )                   h,
                (select x.sq_projeto_etapa, y.sq_menu, count(*) qt_contr
                   from pj_etapa_contrato             x,
                        siw_solicitacao y,
                        siw_tramite     z
                  where (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                    and (y.sq_siw_tramite     = z.sq_siw_tramite and
                         Nvl(z.sigla,'-')     <> 'CA'
                        )
                 group by x.sq_projeto_etapa, y.sq_menu
                )                   n
          where (a.sq_siw_solicitacao = i.sq_siw_solicitacao)
            and (i.sq_menu            = j.sq_menu)
            and (i.sq_siw_solicitacao = m.sq_siw_solicitacao)
            and (j.sq_unid_executora  = k.sq_unidade (+) and
                 k.tipo_respons (+)   = 'T'          and
                 k.fim (+)            is null
                )
            and (j.sq_unid_executora = l.sq_unidade (+) and
                 l.tipo_respons (+)   = 'S'          and
                 l.fim (+)            is null
                )
            and (a.sq_unidade         = b.sq_unidade (+) and
                 b.tipo_respons (+)   = 'T'          and
                 b.fim (+)            is null
                )
            and (a.sq_unidade         = c.sq_unidade (+) and
                 c.tipo_respons (+)   = 'S'          and
                 c.fim (+)            is null
                )
            and (a.sq_pessoa          = d.sq_pessoa)
            and (d.sq_pessoa          = e.sq_pessoa)
            and (e.sq_unidade         = f.sq_unidade)
            and (a.sq_unidade         = g.sq_unidade)
            and (h.sq_projeto_etapa = a.sq_projeto_etapa (+))
            and (n.sq_projeto_etapa = a.sq_projeto_etapa (+))
            and a.sq_siw_solicitacao = p_chave
            and a.sq_etapa_pai       is null
            and (p_chave_aux2 is null or (p_chave_aux2 is not null and a.sq_projeto_etapa <> p_chave_aux2));
   ElsIf p_restricao = 'LSTNIVEL' Then
      -- Recupera as etapas vinculadas a uma etapa do projeto
      open p_result for
         select a.*, b.sq_pessoa titular, c.sq_pessoa substituto, i.executor, i.solicitante,
                k.sq_pessoa tit_exec, l.sq_pessoa sub_exec,
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor,
                nvl(h.qt_ativ,0) qt_ativ, h.sq_menu p2,
                m.vincula_contrato pj_vincula_contrato, nvl(n.qt_contr,0)  
           from pj_projeto_etapa                a,
                siw_solicitacao i,
                pj_projeto      m,
                siw_menu        j,
                eo_unidade_resp k,
                eo_unidade_resp l,
                eo_unidade_resp b,
                eo_unidade_resp c,
                co_pessoa       d,
                sg_autenticacao e,
                eo_unidade      f,
                eo_unidade      g,
                (select x.sq_projeto_etapa, y.sq_menu, count(*) qt_ativ
                   from pj_etapa_demanda             x,
                        siw_solicitacao y,
                        siw_tramite     z
                  where (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                    and (y.sq_siw_tramite     = z.sq_siw_tramite and
                         Nvl(z.sigla,'-')     <> 'CA'
                        )
                 group by x.sq_projeto_etapa, y.sq_menu
                )                   h,
                (select x.sq_projeto_etapa, y.sq_menu, count(*) qt_contr
                   from pj_etapa_contrato             x,
                        siw_solicitacao y,
                        siw_tramite     z
                  where (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                    and (y.sq_siw_tramite     = z.sq_siw_tramite and
                         Nvl(z.sigla,'-')     <> 'CA'
                        )
                 group by x.sq_projeto_etapa, y.sq_menu
                )                   n
          where (a.sq_siw_solicitacao = i.sq_siw_solicitacao)
            and (i.sq_menu            = j.sq_menu)
            and (i.sq_siw_solicitacao = m.sq_siw_solicitacao)
            and (j.sq_unid_executora  = k.sq_unidade (+) and
                 k.tipo_respons (+)   = 'T'          and
                 k.fim (+)            is null
                )
            and (j.sq_unid_executora = l.sq_unidade (+) and
                 l.tipo_respons (+)   = 'S'          and
                 l.fim (+)            is null
                )
            and (j.sq_unid_executora  = k.sq_unidade (+) and
                 k.tipo_respons (+)   = 'T'          and
                 k.fim (+)            is null
                )
            and (a.sq_unidade         = b.sq_unidade (+) and
                 b.tipo_respons (+)   = 'T'          and
                 b.fim (+)            is null
                )
            and (a.sq_unidade         = c.sq_unidade (+) and
                 c.tipo_respons (+)   = 'S'          and
                 c.fim (+)            is null
                )
            and (a.sq_pessoa          = d.sq_pessoa)
            and (d.sq_pessoa          = e.sq_pessoa)
            and (e.sq_unidade         = f.sq_unidade)
            and (a.sq_unidade         = g.sq_unidade)
            and (h.sq_projeto_etapa = a.sq_projeto_etapa (+))
            and (n.sq_projeto_etapa = a.sq_projeto_etapa (+))
            and a.sq_siw_solicitacao = p_chave
            and a.sq_etapa_pai       = p_chave_aux
            and (p_chave_aux2 is null or (p_chave_aux2 is not null and a.sq_projeto_etapa <> p_chave_aux2));
   Elsif p_restricao = 'REGISTRO' Then
      -- Recupera os dados de uma etapa de projeto
      open p_result for
         select a.*, b.sq_pessoa titular, c.sq_pessoa substituto,
                decode(a.programada,'S','Sim','Não') nm_programada,
                decode(a.cumulativa,'S','Sim','Não') nm_cumulativa,
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor,
                nvl(h.qt_ativ,0) qt_ativ, h.sq_menu p2,
                m.vincula_contrato pj_vincula_contrato, nvl(n.qt_contr,0)
           from pj_projeto_etapa                a,
                pj_projeto      m,
                eo_unidade_resp b,
                eo_unidade_resp c,
                co_pessoa       d,
                sg_autenticacao e,
                eo_unidade      f,
                eo_unidade      g,
                (select x.sq_projeto_etapa, y.sq_menu, count(*) qt_ativ
                   from pj_etapa_demanda             x,
                        siw_solicitacao y,
                        siw_tramite     z
                  where (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                    and (y.sq_siw_tramite     = z.sq_siw_tramite and
                         Nvl(z.sigla,'-')     <> 'CA'
                        )
                 group by x.sq_projeto_etapa, y.sq_menu
                )               h,
                (select x.sq_projeto_etapa, y.sq_menu, count(*) qt_contr
                   from pj_etapa_contrato             x,
                        siw_solicitacao y,
                        siw_tramite     z
                  where (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                    and (y.sq_siw_tramite     = z.sq_siw_tramite and
                         Nvl(z.sigla,'-')     <> 'CA'
                        )
                 group by x.sq_projeto_etapa, y.sq_menu
                )                   n                
          where (a.sq_unidade       = b.sq_unidade (+) and
                 b.tipo_respons (+) = 'T'          and
                 b.fim (+)          is null
                )
            and (a.sq_unidade       = c.sq_unidade (+) and
                 c.tipo_respons (+) = 'S'          and
                 c.fim (+)          is null
                )
            and (a.sq_siw_solicitacao = m.sq_siw_solicitacao)
            and (a.sq_pessoa        = d.sq_pessoa)
            and (d.sq_pessoa        = e.sq_pessoa)
            and (e.sq_unidade       = f.sq_unidade)
            and (a.sq_unidade       = g.sq_unidade)
            and (h.sq_projeto_etapa = a.sq_projeto_etapa (+))
            and (n.sq_projeto_etapa = a.sq_projeto_etapa (+))
            and a.sq_siw_solicitacao = p_chave
            and a.sq_projeto_etapa   = p_chave_aux;
   Elsif p_restricao = 'FILHOS' Then
      -- Recupera as etapas subordinadas a outra do mesmo projeto
      open p_result for
         select a.*
           from pj_projeto_etapa   a
          where a.sq_etapa_pai       = p_chave
            and a.sq_projeto_etapa   = p_chave_aux;
   End If;
End SP_GetSolicEtapa;
/
