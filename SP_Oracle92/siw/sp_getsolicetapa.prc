create or replace procedure SP_GetSolicEtapa
   (p_chave     in number   default null,
    p_chave_aux in number   default null,
    p_restricao in varchar2,
    p_result    out sys_refcursor) is
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
                nvl(h.qt_ativ,0) qt_ativ, h.sq_menu p2
           from pj_projeto_etapa                a
                inner          join siw_solicitacao i on (a.sq_siw_solicitacao = i.sq_siw_solicitacao)
                  inner        join siw_menu        j on (i.sq_menu            = j.sq_menu)
                    left outer join eo_unidade_resp k on (j.sq_unid_executora  = k.sq_unidade and
                                                          k.tipo_respons       = 'T'          and
                                                          k.fim                is null
                                                         )
                    left outer join eo_unidade_resp l on (j.sq_unid_executora = l.sq_unidade and
                                                          l.tipo_respons       = 'S'          and
                                                          l.fim                is null
                                                         )
                left outer     join eo_unidade_resp b on (a.sq_unidade         = b.sq_unidade and
                                                          b.tipo_respons       = 'T'          and
                                                          b.fim                is null
                                                         )
                left outer     join eo_unidade_resp c on (a.sq_unidade         = c.sq_unidade and
                                                          c.tipo_respons       = 'S'          and
                                                          c.fim                is null
                                                         )
                inner          join co_pessoa       d on (a.sq_pessoa          = d.sq_pessoa)
                  inner        join sg_autenticacao e on (d.sq_pessoa          = e.sq_pessoa)
                    inner      join eo_unidade      f on (e.sq_unidade         = f.sq_unidade)
                inner          join eo_unidade      g on (a.sq_unidade         = g.sq_unidade)
                left outer     join (select x.sq_projeto_etapa, y.sq_menu, count(*) qt_ativ
                                       from pj_etapa_demanda             x
                                            inner   join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                              inner join siw_tramite     z on (y.sq_siw_tramite     = z.sq_siw_tramite and
                                                                               Nvl(z.sigla,'-')     <> 'CA'
                                                                              )
                                     group by x.sq_projeto_etapa, y.sq_menu
                                )                   h on (h.sq_projeto_etapa = a.sq_projeto_etapa)
          where a.sq_siw_solicitacao = p_chave;
   ElsIf p_restricao = 'LSTNULL' Then
      -- Recupera as etapas principais de um projeto
      open p_result for 
         select a.*, b.sq_pessoa titular, c.sq_pessoa substituto, i.executor, i.solicitante,
                case a.programada when 'S' then 'Sim' else 'Não' end nm_programada,
                case a.cumulativa when 'S' then 'Sim' else 'Não' end nm_cumulativa,  
                case a.exequivel  when 'S' then 'Sim' else 'Não' end nm_exequivel,    
                k.sq_pessoa tit_exec, l.sq_pessoa sub_exec,
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor,
                nvl(h.qt_ativ,0) qt_ativ, h.sq_menu p2
           from pj_projeto_etapa                a
                inner          join siw_solicitacao i on (a.sq_siw_solicitacao = i.sq_siw_solicitacao)
                  inner        join siw_menu        j on (i.sq_menu            = j.sq_menu)
                    left outer join eo_unidade_resp k on (j.sq_unid_executora  = k.sq_unidade and
                                                          k.tipo_respons       = 'T'          and
                                                          k.fim                is null
                                                         )
                    left outer join eo_unidade_resp l on (j.sq_unid_executora = l.sq_unidade and
                                                          l.tipo_respons       = 'S'          and
                                                          l.fim                is null
                                                         )
                left outer     join eo_unidade_resp b on (a.sq_unidade         = b.sq_unidade and
                                                          b.tipo_respons       = 'T'          and
                                                          b.fim                is null
                                                         )
                left outer     join eo_unidade_resp c on (a.sq_unidade         = c.sq_unidade and
                                                          c.tipo_respons       = 'S'          and
                                                          c.fim                is null
                                                         )
                inner          join co_pessoa       d on (a.sq_pessoa          = d.sq_pessoa)
                  inner        join sg_autenticacao e on (d.sq_pessoa          = e.sq_pessoa)
                    inner      join eo_unidade      f on (e.sq_unidade         = f.sq_unidade)
                inner          join eo_unidade      g on (a.sq_unidade         = g.sq_unidade)
                left outer     join (select x.sq_projeto_etapa, y.sq_menu, count(*) qt_ativ
                                       from pj_etapa_demanda             x
                                            inner   join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                              inner join siw_tramite     z on (y.sq_siw_tramite     = z.sq_siw_tramite and
                                                                               Nvl(z.sigla,'-')     <> 'CA'
                                                                              )
                                     group by x.sq_projeto_etapa, y.sq_menu
                                )                   h on (h.sq_projeto_etapa = a.sq_projeto_etapa)
          where a.sq_siw_solicitacao = p_chave
            and a.sq_etapa_pai       is null;
   ElsIf p_restricao = 'LSTNIVEL' Then
      -- Recupera as etapas vinculadas a uma etapa do projeto
      open p_result for 
         select a.*, b.sq_pessoa titular, c.sq_pessoa substituto, i.executor, i.solicitante,
                k.sq_pessoa tit_exec, l.sq_pessoa sub_exec,
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor,
                nvl(h.qt_ativ,0) qt_ativ, h.sq_menu p2
           from pj_projeto_etapa                a
                inner          join siw_solicitacao i on (a.sq_siw_solicitacao = i.sq_siw_solicitacao)
                  inner        join siw_menu        j on (i.sq_menu            = j.sq_menu)
                    left outer join eo_unidade_resp k on (j.sq_unid_executora  = k.sq_unidade and
                                                          k.tipo_respons       = 'T'          and
                                                          k.fim                is null
                                                         )
                    left outer join eo_unidade_resp l on (j.sq_unid_executora = l.sq_unidade and
                                                          l.tipo_respons       = 'S'          and
                                                          l.fim                is null
                                                         )
                left outer     join eo_unidade_resp b on (a.sq_unidade         = b.sq_unidade and
                                                          b.tipo_respons       = 'T'          and
                                                          b.fim                is null
                                                         )
                left outer     join eo_unidade_resp c on (a.sq_unidade         = c.sq_unidade and
                                                          c.tipo_respons       = 'S'          and
                                                          c.fim                is null
                                                         )
                inner          join co_pessoa       d on (a.sq_pessoa          = d.sq_pessoa)
                  inner        join sg_autenticacao e on (d.sq_pessoa          = e.sq_pessoa)
                    inner      join eo_unidade      f on (e.sq_unidade         = f.sq_unidade)
                inner          join eo_unidade      g on (a.sq_unidade         = g.sq_unidade)
                left outer     join (select x.sq_projeto_etapa, y.sq_menu, count(*) qt_ativ
                                       from pj_etapa_demanda             x
                                            inner   join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                              inner join siw_tramite     z on (y.sq_siw_tramite     = z.sq_siw_tramite and
                                                                               Nvl(z.sigla,'-')     <> 'CA'
                                                                              )
                                     group by x.sq_projeto_etapa, y.sq_menu
                                )                   h on (h.sq_projeto_etapa = a.sq_projeto_etapa)
          where a.sq_siw_solicitacao = p_chave
            and a.sq_etapa_pai       = p_chave_aux;
   Elsif p_restricao = 'REGISTRO' Then
      -- Recupera os dados de uma etapa de projeto
      open p_result for 
         select a.*, b.sq_pessoa titular, c.sq_pessoa substituto, 
                case a.programada when 'S' then 'Sim' else 'Não' end nm_programada,
                case a.cumulativa when 'S' then 'Sim' else 'Não' end nm_cumulativa,                
                d.nome_resumido||' ('||f.sigla||')' nm_resp, g.sigla sg_setor,
                nvl(h.qt_ativ,0) qt_ativ, h.sq_menu p2
           from pj_projeto_etapa                a
                left outer join eo_unidade_resp b on (a.sq_unidade       = b.sq_unidade and
                                                      b.tipo_respons     = 'T'          and
                                                      b.fim              is null
                                                     )
                left outer join eo_unidade_resp c on (a.sq_unidade       = c.sq_unidade and
                                                      c.tipo_respons     = 'S'          and
                                                      c.fim              is null
                                                     )
                inner      join co_pessoa       d on (a.sq_pessoa        = d.sq_pessoa)
                  inner    join sg_autenticacao e on (d.sq_pessoa        = e.sq_pessoa)
                    inner  join eo_unidade      f on (e.sq_unidade       = f.sq_unidade)
                inner      join eo_unidade      g on (a.sq_unidade       = g.sq_unidade)
                left outer     join (select x.sq_projeto_etapa, y.sq_menu, count(*) qt_ativ
                                       from pj_etapa_demanda             x
                                            inner   join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                              inner join siw_tramite     z on (y.sq_siw_tramite     = z.sq_siw_tramite and
                                                                               Nvl(z.sigla,'-')     <> 'CA'
                                                                              )
                                     group by x.sq_projeto_etapa, y.sq_menu
                                )               h on (h.sq_projeto_etapa = a.sq_projeto_etapa)
          where a.sq_siw_solicitacao = p_chave
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

