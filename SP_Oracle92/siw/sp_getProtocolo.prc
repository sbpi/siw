create or replace procedure sp_getProtocolo
   (p_menu         in number,
    p_pessoa       in number,
    p_restricao    in varchar2 default null,
    p_chave        in number   default null,
    p_chave_aux    in number   default null,
    p_prefixo      in number   default null,
    p_numero       in number   default null,
    p_ano          in number   default null,
    p_unid_autua   in number   default null,
    p_unid_posse   in number   default null,
    p_nu_guia      in number   default null,
    p_ano_guia     in number   default null,
    p_ini          in date     default null,
    p_fim          in date     default null,
    p_tipo         in number   default null,
    p_despacho     in number   default null,
    p_empenho      in varchar2 default null,
    p_solicitante  in number   default null,
    p_unidade      in number   default null,
    p_proponente   in varchar2 default null,
    p_cd_assunto   in varchar2 default null,
    p_assunto      in varchar2 default null,
    p_processo     in varchar2 default null,
    p_result       out sys_refcursor) is
    
    w_filtro varchar2(10);
begin
   If p_prefixo is not null or p_numero is not null or p_ano is not null or
      p_empenho is not null or p_solicitante is not null or p_unidade is not null or 
      p_proponente is not null or p_assunto is not null or p_processo is not null or 
      p_cd_assunto is not null or p_ini is not null or p_unid_autua is not null or p_nu_guia is not null
   Then w_filtro := 'true'; Else w_filtro := 'false'; End If;
   
   If p_restricao = 'RELPATRAM' or p_restricao = 'RELPAETIQ' Then
      -- Recupera guias de tramitação
      open p_result for
      select b.inicio, b.fim, b.sq_siw_solicitacao, b.descricao,
             c.numero_original,
             c.prefixo||'.'||substr(1000000+c.numero_documento,2,6)||'/'||c.ano||'-'||substr(100+c.digito,2,2) as protocolo,
             c1.sigla sg_unidade,
             c2.nome as nm_especie,
             case when c5.sq_siw_solicitacao is not null
                  then c5.prefixo||'.'||substr(1000000+c5.numero_documento,2,6)||'/'||c5.ano||'-'||substr(100+c5.digito,2,2) 
                  else null
             end as protocolo_pai,
             case c.interno when 'S' then b2.sigla else c3.nome_resumido end as nm_origem_doc,
             case c.processo when 'S' then 'Proc' else 'Doc' end as nm_tipo,
             d.nu_guia, d.ano_guia, c.unidade_autuacao, d.resumo, d.unidade_externa, d.interno,
             d.nu_guia||'/'||d.ano_guia||'-'||d6.sigla as guia_tramite,
             to_char(d.envio, 'dd/mm/yyyy, hh24:mi:ss') as phpdt_envio, 
             case d.interno when 'S' then d3.nome else d4.nome end as nm_destino,
             d1.sq_tipo_despacho, d1.nome as nm_despacho,
             d2.nome as nm_unid_origem,    d2.sigla as sg_unid_origem,
             d3.nome as nm_unid_dest,      d3.sigla as sg_unid_dest,
             d4.nome as nm_pessoa_dest,    d4.nome_resumido as nm_res_pessoa_dest,
             d8.nome as nm_pessoa_resp,    d8.nome_resumido as nm_res_pessoa_resp
        from siw_menu                                  a
             inner           join siw_solicitacao      b  on (a.sq_menu              = b.sq_menu)
               inner         join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                       from siw_solicitacao
                                  )                    b1 on (b.sq_siw_solicitacao   = b1.sq_siw_solicitacao)
               inner         join eo_unidade           b2 on (b.sq_unidade           = b2.sq_unidade)
               inner         join pa_documento         c  on (b.sq_siw_solicitacao   = c.sq_siw_solicitacao)
                 inner       join eo_unidade           c1 on (c.unidade_autuacao     = c1.sq_unidade)
                 inner       join pa_especie_documento c2 on (c.sq_especie_documento = c2.sq_especie_documento)
                 left        join co_pessoa            c3 on (c.pessoa_origem        = c3.sq_pessoa)
                 left        join pa_documento         c5 on (c.sq_documento_pai     = c5.sq_siw_solicitacao)
                 left        join (select sq_siw_solicitacao, max(sq_documento_log) as sq_documento_log
                                     from pa_documento_log
                                    group by sq_siw_solicitacao
                                  )                    c4 on (c.sq_siw_solicitacao   = c4.sq_siw_solicitacao)
                   left      join pa_documento_log     d  on (c4.sq_documento_log    = d.sq_documento_log)
                     left    join pa_tipo_despacho     d1 on (d.sq_tipo_despacho     = d1.sq_tipo_despacho)
                       left  join pa_parametro         d7 on (d1.sq_tipo_despacho    = d7.despacho_arqcentral)
                     left    join eo_unidade           d2 on (d.unidade_origem       = d2.sq_unidade)
                       left  join pa_unidade           d5 on (d2.sq_unidade          = d5.sq_unidade)
                       left  join eo_unidade           d6 on (d6.sq_unidade          = coalesce(d5.sq_unidade_pai,d5.sq_unidade))
                     left    join eo_unidade           d3 on (d.unidade_destino      = d3.sq_unidade)
                     left    join co_pessoa            d4 on (d.pessoa_destino       = d4.sq_pessoa)
                     left    join co_pessoa            d8 on (d.cadastrador          = d8.sq_pessoa),
               sg_autenticacao                         w
       where a.sq_menu     = p_menu
         and w.sq_pessoa   = p_pessoa
         and (p_restricao = 'RELPAETIQ' or 
              (p_restricao = 'RELPATRAM' and b.sq_solic_pai is null and (w.sq_unidade = d.unidade_origem or 
                                                                         w.sq_unidade = d.unidade_destino or 
                                                                         w.sq_pessoa  = d.cadastrador or
                                                                         0            < (select count(*) from eo_unidade_resp where sq_pessoa = p_pessoa and sq_unidade = d.unidade_origem and fim is null)
                                                                        )
              ) or
              w_filtro = 'true'
             )
         and (p_chave      is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
         and (p_chave_aux  is null or (p_chave_aux   is not null and d.sq_documento_log   = p_chave_aux))
         and (p_prefixo    is null or (p_prefixo     is not null and c.prefixo            = p_prefixo))
         and (p_numero     is null or (p_numero      is not null and c.numero_documento   = p_numero))
         and (p_ano        is null or (p_ano         is not null and c.ano                = p_ano))
         and (p_nu_guia    is null or (p_nu_guia     is not null and d.nu_guia            = p_nu_guia and d.ano_guia = p_ano_guia))
         --and (p_unid_autua is null or (p_unid_autua  is not null and c.unidade_autuacao   = p_unid_autua))
         and (p_unid_posse is null or (p_unid_posse  is not null and c.unidade_int_posse  = p_unid_posse))
         and (p_ini        is null or (p_ini         is not null and d.envio              between p_ini and p_fim))
         and (p_restricao = 'RELPAETIQ' or 
              (p_restricao = 'RELPATRAM' and d.sq_documento_log is not null and d.recebimento is null and d1.sq_tipo_despacho is not null and d2.sq_unidade is not null and d7.despacho_arqcentral is null)
             )
         and (p_tipo       = 1 or
              (p_tipo      = 2 and (b1.acesso > 0 or w.sq_pessoa  = d.cadastrador or (p_restricao = 'RELPATRAM' and (w.sq_unidade = d.unidade_origem or 0 < (select count(*) from eo_unidade_resp where sq_pessoa = p_pessoa and sq_unidade = d.unidade_origem and fim is null))))) or
              w_filtro = 'true'
             );
   Elsif instr('PADAUTUA,PADANEXA,PADJUNTA,PACLASSIF, PADTRANSF,PADELIM,PADEMPREST,PAENVCEN,PADDESM,PADALTREG', p_restricao) > 0 Then
      -- Recupera guias de tramitação
      open p_result for
      select b.inicio, b.fim, b.sq_siw_solicitacao, b.sq_solic_pai, b.descricao,
             b2.nome, b2.sigla,
             b3.sigla as sg_tramite,
             c.numero_original, c.observacao_setorial, c.sq_caixa, c.pasta, c.data_setorial, c.ano,
             c.numero_documento||'/'||substr(c.ano,3,2) as protocolo,
             c1.sigla sg_unidade,
             c2.nome as nm_especie,
             case c.interno when 'S' then b2.sigla else c3.nome_resumido end as nm_origem_doc,
             case c.processo when 'S' then 'Proc' else 'Doc' end as nm_tipo,
             c5.codigo as cd_assunto,
             case b3.ativo 
                  when 'S' then case c5.fase_corrente_anos when 0 then c6.descricao else to_char(c5.fase_corrente_anos) || ' '  || c6.sigla end
                  else case b3.sigla
                            when 'AS' then case c5.fase_intermed_anos when 0 then c8.descricao else to_char(c5.fase_intermed_anos) || ' '  || c8.sigla end
                            when 'AT' then case c5.fase_final_anos when 0 then c9.descricao else to_char(c5.fase_final_anos) || ' '  || c9.sigla end
                       end
             end as data_limite_doc,
             ca.numero as nr_caixa, cb.sigla as sg_unid_caixa,
             d.nu_guia, d.ano_guia, c.unidade_autuacao, d.resumo, d.unidade_externa, d.interno,
             d.nu_guia||'/'||d.ano_guia||'-'||dd.sigla as guia_tramite, d.recebimento,
             to_char(d.envio, 'dd/mm/yyyy, hh24:mi:ss') as phpdt_envio, 
             d1.nome as nm_despacho,
             d2.nome as nm_unid_origem,    d2.sigla as sg_unid_origem,
             d3.nome as nm_unid_dest,      d3.sigla as sg_unid_dest
        from siw_menu                                a
             inner         join siw_solicitacao      b  on (a.sq_menu               = b.sq_menu)
               inner       join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                     from siw_solicitacao
                                )                    b1 on (b.sq_siw_solicitacao    = b1.sq_siw_solicitacao)
               inner       join eo_unidade           b2 on (b.sq_unidade            = b2.sq_unidade)
               inner       join siw_tramite          b3 on (b.sq_siw_tramite        = b3.sq_siw_tramite)
               inner       join pa_documento         c  on (b.sq_siw_solicitacao    = c.sq_siw_solicitacao)
                 inner     join eo_unidade           c1 on (c.unidade_autuacao      = c1.sq_unidade)
                 inner     join pa_especie_documento c2 on (c.sq_especie_documento  = c2.sq_especie_documento)
                 left      join co_pessoa            c3 on (c.pessoa_origem         = c3.sq_pessoa)
                 inner     join pa_documento_assunto c4 on (c.sq_siw_solicitacao    = c4.sq_siw_solicitacao and
                                                            c4.principal            = 'S'
                                                           )
                 inner     join pa_assunto           c5 on (c4.sq_assunto           = c5.sq_assunto)
                   inner   join pa_tipo_guarda       c6 on (c5.fase_corrente_guarda = c6.sq_tipo_guarda)
                   inner   join pa_tipo_guarda       c8 on (c5.fase_intermed_guarda = c8.sq_tipo_guarda)
                   inner   join pa_tipo_guarda       c9 on (c5.fase_final_guarda    = c9.sq_tipo_guarda)
                 left      join pa_caixa             ca on (c.sq_caixa              = ca.sq_caixa)
                   left    join eo_unidade           cb on (ca.sq_unidade           = cb.sq_unidade)
                 inner     join pa_documento_log     d  on (c.sq_siw_solicitacao    = d.sq_siw_solicitacao and
                                                            (p_restricao   = 'PACLASSIF' or 
                                                             p_restricao   = 'PADALTREG' or
                                                             (p_restricao <> 'PACLASSIF' and d.recebimento is not null)
                                                            )
                                                           )
                   inner   join (select sq_siw_solicitacao, max(sq_documento_log) chave 
                                   from pa_documento_log
                                  where  0 = instr(coalesce(resumo,'-'),'*** RECUSADO')
                                 group by sq_siw_solicitacao
                                )                    dc on (d.sq_documento_log      = dc.chave)
                   inner   join eo_unidade           d2 on (d.unidade_origem        = d2.sq_unidade)
                     inner join pa_unidade           dc on (d2.sq_unidade           = dc.sq_unidade)
                     inner join eo_unidade           dd on (dd.sq_unidade           = coalesce(dc.sq_unidade_pai,dc.sq_unidade))
                   inner   join eo_unidade           d3 on (d.unidade_destino       = d3.sq_unidade)
                   inner   join pa_tipo_despacho     d1 on (d.sq_tipo_despacho      = d1.sq_tipo_despacho)
                     left  join pa_parametro         d4 on (d1.sq_tipo_despacho     = d4.despacho_arqcentral)
                     left  join pa_parametro         d5 on (d1.sq_tipo_despacho     = d5.despacho_arqsetorial)
                     left  join pa_parametro         d6 on (d1.sq_tipo_despacho     = d6.despacho_emprestimo)
                     left  join pa_parametro         d7 on (d1.sq_tipo_despacho     = d7.despacho_devolucao)
                     left  join pa_parametro         d8 on (d1.sq_tipo_despacho     = d8.despacho_anexar)
                     left  join pa_parametro         d9 on (d1.sq_tipo_despacho     = d9.despacho_apensar)
                     left  join pa_parametro         da on (d1.sq_tipo_despacho     = da.despacho_eliminar)
                     left  join pa_parametro         db on (d1.sq_tipo_despacho     = db.despacho_autuar)
                     left  join pa_parametro         de on (d1.sq_tipo_despacho     = de.despacho_desmembrar),
               sg_autenticacao                       w
               left        join (select x.sq_pessoa, y.sq_modulo, y.sigla
                                   from sg_pessoa_modulo      x
                                        inner join siw_modulo y on (x.sq_modulo = y.sq_modulo)
                                  where y.sigla = 'PA'
                                )                    w1 on (w.sq_pessoa             = w1.sq_pessoa)
       where a.sq_menu     = p_menu
         and w.sq_pessoa   = p_pessoa
         and (p_restricao = 'PACLASSIF' or 
              p_restricao = 'PADALTREG' or 
              (b1.acesso    >= 8 or d.unidade_destino = w.sq_unidade or 0 < (select count(*) from eo_unidade_resp where sq_pessoa = p_pessoa and sq_unidade = d.unidade_destino and fim is null))
             )
         and (p_chave      is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
         and (p_chave_aux  is null or (p_chave_aux   is not null and d.sq_documento_log   = p_chave_aux))
         and (p_prefixo    is null or (p_prefixo     is not null and c.prefixo            = p_prefixo))
         and (p_numero     is null or (p_numero      is not null and c.numero_documento   = p_numero))
         and (p_ano        is null or (p_ano         is not null and c.ano                = p_ano))
         and (p_nu_guia    is null or (p_nu_guia     is not null and d.nu_guia            = p_nu_guia and d.ano_guia = p_ano_guia))
         and (p_unid_autua is null or (p_unid_autua  is not null and c.unidade_autuacao   = p_unid_autua))
         and (p_unid_posse is null or (p_unid_posse  is not null and c.unidade_int_posse  = p_unid_posse))
         and (p_unidade     is null or (p_unidade     is not null and b.sq_unidade         = p_unidade))
         and (p_ini         is null or (p_ini         is not null and b.inicio between p_ini and p_fim))
         and (p_empenho     is null or (p_empenho     is not null and acentos(c.numero_original) like '%'||acentos(p_empenho)||'%'))
         and (p_cd_assunto  is null or (p_cd_assunto  is not null and c5.codigo like p_cd_assunto||'%'))
         and (p_assunto     is null or (p_assunto     is not null and acentos(b.descricao) like '%'||acentos(p_assunto)||'%'))
         and (p_solicitante is null or (p_solicitante is not null and c.sq_especie_documento = p_solicitante))
         and (p_proponente  is null or (p_proponente  is not null and (to_char(c.pessoa_origem) = p_proponente or c3.nome_indice like '%'||acentos(p_proponente)||'%' or c3.nome_resumido_ind like '%'||acentos(p_proponente)||'%')))
         and (p_processo    is null or (p_processo    is not null and 0 < (select count(*)
                                                                             from pa_documento_interessado x
                                                                                  inner join co_pessoa     y on (x.sq_pessoa = y.sq_pessoa)
                                                                            where x.sq_siw_solicitacao = d.sq_siw_solicitacao
                                                                              and (acentos(y.nome_indice)       like '%'||acentos(p_processo)||'%' or
                                                                                   acentos(y.nome_resumido_ind) like '%'||acentos(p_processo)||'%'
                                                                                  )
                                                                          )
                                       )
             )
         and (p_tipo       = 1 or (p_tipo      = 2 and b1.acesso > 0))
         and ((p_restricao = 'PADAUTUA'   and db.cliente is not null and c.data_autuacao is null) or
              (p_restricao = 'PADANEXA'   and d8.cliente is not null and b.sq_solic_pai is null) or
              (p_restricao = 'PADJUNTA'   and d9.cliente is not null and b.sq_solic_pai is null) or
              (p_restricao = 'PADTRANSF'  and (b3.sigla <> 'CA' and d5.cliente is not null and c.data_setorial is null)) or
              (p_restricao = 'PAENVCEN'   and b3.sigla = 'AS' and b.sq_solic_pai is null and c.data_setorial is not null and (c.unidade_int_posse = w.sq_unidade or 0 < (select count(*) from eo_unidade_resp where sq_pessoa = p_pessoa and sq_unidade = c.unidade_int_posse and fim is null))) or
              (p_restricao = 'PADDESM'    and de.cliente is not null and b.sq_solic_pai is null and c.data_desapensacao is null) or
              (p_restricao = 'PACLASSIF'  and b3.sigla <> 'CA' and b.sq_solic_pai is null and (c5.provisorio = 'S' or w_filtro = 'true')) or
              (p_restricao = 'PADELIM'    and da.cliente is not null) or
              (p_restricao = 'PADEMPREST' and d6.cliente is not null) or
              (p_restricao = 'PADALTREG'  and b3.sigla <> 'CA' and
                                              (-- Se for gestor do sistema e um parâmetro de busca tiver sido informado
                                               ((w1.sq_modulo is not null or w.gestor_sistema ='S') and 
                                                w_filtro = 'true'
                                               ) or
                                               -- Se o documento tiver sido criado pelo (setor de lotação do usuário/setor gerenciado pelo usuário) e estiver nele
                                               (b.cadastrador = p_pessoa and 
                                                (w.sq_unidade = c.unidade_int_posse or 
                                                 0            < (select count(*) from eo_unidade_resp where sq_pessoa = p_pessoa and sq_unidade = c.unidade_int_posse and fim is null)
                                                )
                                               ) or
                                               -- Se o documento original for do (setor de lotação do usuário/setor gerenciado pelo usuário) e estiver nele.
                                               (b.sq_unidade  = c.unidade_int_posse and 
                                                (w.sq_unidade = c.unidade_int_posse or 
                                                 0            < (select count(*) from eo_unidade_resp where sq_pessoa = p_pessoa and sq_unidade = c.unidade_int_posse and fim is null)
                                                )
                                               ) or
                                               -- Se espécie for DEFINIR e o protocolo estiver no (setor de lotação do usuário/setor gerenciado pelo usuário)
                                               (c2.sigla      = 'DEFINIR' and
                                                0            < (select count(*) from eo_unidade_resp where sq_pessoa = p_pessoa and sq_unidade = c.unidade_int_posse and fim is null)
                                               ) or
                                               -- Se protocolo for solicitação de viagem ou prestação de contas de viagem e o usuário for do setor de viagens
                                               (c2.sigla      in ('PRCOV','SOVI') and
                                                0             < (select count(*) from siw_menu where sigla = 'PDINICIAL' and sq_unid_executora = w.sq_unidade)
                                               )
                                              )
              )
             );
   Elsif p_restricao = 'PADTRAM' Then
      -- Recupera guias de tramitação
      open p_result for
      select b.sq_siw_solicitacao, b.inicio, b.fim, b.sq_siw_tramite, b.sq_solic_pai, b.descricao,
             c.numero_original, c.numero_documento, c.unidade_int_posse, c.pessoa_ext_posse,
             c.numero_documento||'/'||substr(c.ano,3,2) as protocolo,
             c1.sigla sg_unidade,
             c2.nome as nm_especie,
             case c.interno when 'S' then b2.sigla else c3.nome_resumido end as nm_origem_doc,
             case c.processo when 'S' then 'Proc' else 'Doc' end as nm_tipo,
             coalesce(c5.qtd,0) as qt_apensos,
             case when b.sq_siw_solicitacao is not null
                  then b3.prefixo||'.'||substr(1000000+b3.numero_documento,2,6)||'/'||b3.ano||'-'||substr(100+b3.digito,2,2) 
                  else null
             end as protocolo_pai,
             c6.sigla as sg_unidade_posse, c6.nome as nm_unidade_posse,
             case coalesce(c7.existe,0) when 0 then 'N' else 'S' end as st_mesma_lotacao,
             d.nu_guia, d.ano_guia, c.unidade_autuacao, d.resumo, d.unidade_externa, d.interno,
             d.nu_guia||'/'||d.ano_guia||'-'||d6.sigla as guia_tramite,
             to_char(d.envio, 'dd/mm/yyyy, hh24:mi:ss') as phpdt_envio, 
             case d.interno when 'S' then d3.nome else d4.nome end as nm_destino,
             d1.nome as nm_despacho,
             d2.nome as nm_unid_origem,    d2.sigla as sg_unid_origem,
             d3.nome as nm_unid_dest,      d3.sigla as sg_unid_dest,
             d4.nome as nm_pessoa_dest,    d4.nome_resumido as nm_res_pessoa_dest
        from siw_menu                                  a
             inner           join pa_parametro         a1 on (a.sq_pessoa            = a1.cliente)
             inner           join siw_solicitacao      b  on (a.sq_menu              = b.sq_menu)
               inner         join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                       from siw_solicitacao
                                  )                    b1 on (b.sq_siw_solicitacao   = b1.sq_siw_solicitacao)
               inner         join eo_unidade           b2 on (b.sq_unidade           = b2.sq_unidade)
                 left        join pa_documento         b3 on (b.sq_solic_pai         = b3.sq_siw_solicitacao)
               inner         join siw_tramite          b4 on (b.sq_siw_tramite       = b4.sq_siw_tramite)
               inner         join pa_documento         c  on (b.sq_siw_solicitacao   = c.sq_siw_solicitacao)
                 inner       join eo_unidade           c1 on (c.unidade_autuacao     = c1.sq_unidade)
                 inner       join pa_especie_documento c2 on (c.sq_especie_documento = c2.sq_especie_documento)
                 left        join co_pessoa            c3 on (c.pessoa_origem        = c3.sq_pessoa)
                 left        join (select x.sq_siw_solicitacao, max(x.sq_documento_log) as sq_documento_log
                                     from pa_documento_log           x
                                          inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                    where y.sq_menu = p_menu
                                      and 0         = instr(coalesce(x.resumo,'-'),'*** RECUSADO')
                                    group by x.sq_siw_solicitacao
                                  )                    c4 on (c.sq_siw_solicitacao   = c4.sq_siw_solicitacao)
                 left        join (select x.sq_documento_pai, count(*) as qtd
                                     from pa_documento               x
                                          inner join siw_solicitacao y on (x.sq_documento_pai = y.sq_siw_solicitacao)
                                    where x.sq_documento_pai is null
                                      and x.tipo_juntada     = 'P'
                                      and y.sq_menu          = p_menu
                                    group by x.sq_documento_pai
                                  )                    c5 on (c.sq_siw_solicitacao   = c5.sq_documento_pai)
                 left        join eo_unidade           c6 on (c.unidade_int_posse    = c6.sq_unidade)
                 left        join (select x.sq_siw_solicitacao, count(*) as existe
                                     from pa_documento               x
                                          inner   join siw_solicitacao x1 on (x.sq_siw_solicitacao = x1.sq_siw_solicitacao)
                                            inner join siw_menu        x2 on (x2.sq_menu  = p_menu  and x1.sq_menu           = x2.sq_menu)
                                          left    join sg_autenticacao y  on (y.sq_pessoa = p_pessoa and x.unidade_int_posse = y.sq_unidade)
                                          left    join eo_unidade_resp z  on (z.sq_pessoa = p_pessoa and x.unidade_int_posse = z.sq_unidade and z.fim is null)
                                    where y.sq_pessoa is not null or z.sq_pessoa is not null
                                   group by x.sq_siw_solicitacao
                                  )                    c7 on (c.sq_siw_solicitacao   = c7.sq_siw_solicitacao)
                 inner       join pa_documento_assunto c8 on (c.sq_siw_solicitacao   = c8.sq_siw_solicitacao and
                                                              c8.principal           = 'S'
                                                             )
                   inner     join pa_assunto           c9 on (c8.sq_assunto           = c9.sq_assunto)
                   left      join pa_documento_log     d  on (c4.sq_documento_log    = d.sq_documento_log)
                     left    join pa_tipo_despacho     d1 on (d.sq_tipo_despacho     = d1.sq_tipo_despacho)
                     left    join eo_unidade           d2 on (d.unidade_origem       = d2.sq_unidade)
                       left  join pa_unidade           d5 on (d2.sq_unidade          = d5.sq_unidade)
                       left  join eo_unidade           d6 on (d6.sq_unidade          = coalesce(d5.sq_unidade_pai,d5.sq_unidade))
                     left    join eo_unidade           d3 on (d.unidade_destino      = d3.sq_unidade)
                     left    join co_pessoa            d4 on (d.pessoa_destino       = d4.sq_pessoa),
               sg_autenticacao                         w
       where a.sq_menu      = p_menu
         and w.sq_pessoa    = p_pessoa
         and b.sq_solic_pai is null
         and (d.sq_documento_log is null or (d.sq_documento_log is not null and d.recebimento is not null))
         and (w_filtro = 'true' or (c.unidade_int_posse = w.sq_unidade or 0 < (select count(*) from eo_unidade_resp where sq_pessoa = p_pessoa and sq_unidade = c.unidade_int_posse and fim is null)))
         and (p_chave      is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
         and (p_chave_aux  is null or (p_chave_aux   is not null and d.sq_documento_log   = p_chave_aux))
         and (p_prefixo    is null or (p_prefixo     is not null and c.prefixo            = p_prefixo))
         and (p_numero     is null or (p_numero      is not null and c.numero_documento   = p_numero and c.ano = p_ano))
         and (p_nu_guia    is null or (p_nu_guia     is not null and d.nu_guia            = p_nu_guia and d.ano_guia = p_ano_guia))
         and (p_unid_autua is null or (p_unid_autua  is not null and c.unidade_autuacao   = p_unid_autua))
         and (p_ini         is null or (p_ini         is not null and b.inicio             between p_ini and p_fim))
         and (p_unidade     is null or (p_unidade     is not null and b.sq_unidade         = p_unidade))
         and (p_empenho     is null or (p_empenho     is not null and acentos(c.numero_original) like '%'||acentos(p_empenho)||'%'))
         and (p_cd_assunto  is null or (p_cd_assunto  is not null and c9.codigo like p_cd_assunto||'%'))
         and (p_assunto     is null or (p_assunto     is not null and acentos(b.descricao) like '%'||acentos(p_assunto)||'%'))
         and (p_solicitante is null or (p_solicitante is not null and c.sq_especie_documento = p_solicitante))
         and (p_proponente  is null or (p_proponente  is not null and (to_char(c.pessoa_origem) = p_proponente or c3.nome_indice like '%'||acentos(p_proponente)||'%' or c3.nome_resumido_ind like '%'||acentos(p_proponente)||'%')))
         and (p_processo    is null or (p_processo    is not null and 0 < (select count(*)
                                                                             from pa_documento_interessado x
                                                                                  inner join co_pessoa     y on (x.sq_pessoa = y.sq_pessoa)
                                                                            where x.sq_siw_solicitacao = d.sq_siw_solicitacao
                                                                              and (acentos(y.nome_indice)       like '%'||acentos(p_processo)||'%' or
                                                                                   acentos(y.nome_resumido_ind) like '%'||acentos(p_processo)||'%'
                                                                                  )
                                                                          )
                                       )
             )
         and (p_despacho   is null or (p_despacho    is not null and 
                                       ((b4.ativo = 'S' and (coalesce(d1.sigla,'-') <> 'ARQUIVAR S' or (d1.sigla = 'ARQUIVAR S' and w_filtro = 'true'))) or 
                                        (b4.ativo = 'N' and b4.sigla = 'AS' and w_filtro = 'true')
                                       ) and
                                       ((p_despacho not in (a1.despacho_autuar,
                                                            a1.despacho_anexar,
                                                            a1.despacho_apensar,
                                                            a1.despacho_desmembrar
                                                           ) and
                                         c.sq_documento_pai is null
                                        ) or
                                        (p_despacho = a1.despacho_autuar     and c.processo = 'N') or
                                        (p_despacho = a1.despacho_anexar     and c.sq_documento_pai is null) or
                                        (p_despacho = a1.despacho_apensar    and c.sq_documento_pai is null) or
                                        (p_despacho = a1.despacho_desmembrar and c.sq_documento_pai is null and coalesce(c5.qtd,0) = 0) 
                                       )
                                      )
             )
         and (p_tipo       = 1 or
              (p_tipo      = 2 and (b1.acesso > 0 or w_filtro = 'true' or (c.unidade_int_posse = w.sq_unidade or 0 < (select count(*) from eo_unidade_resp where sq_pessoa = p_pessoa and sq_unidade = c.unidade_int_posse and fim is null))))
             );
   Elsif p_restricao = 'PADRECEB' Then
      -- Recupera guias de tramitação
      open p_result for
      select b.inicio, b.fim, b.sq_siw_solicitacao, b.descricao,
             c.numero_original,
             c.numero_documento||'/'||substr(c.ano,3,2) as protocolo,
             c1.sigla sg_unidade,
             c2.nome as nm_especie,
             case when c5.sq_siw_solicitacao is not null
                  then c5.prefixo||'.'||substr(1000000+c5.numero_documento,2,6)||'/'||c5.ano||'-'||substr(100+c5.digito,2,2) 
                  else null
             end as protocolo_pai,
             case c.interno when 'S' then b2.sigla else c3.nome_resumido end as nm_origem_doc,
             case c.processo when 'S' then 'Proc' else 'Doc' end as nm_tipo,
             case coalesce(c7.existe,0) when 0 then 'N' else 'S' end as st_mesma_lotacao,
             d.nu_guia, d.ano_guia, c.unidade_autuacao, d.resumo, d.unidade_externa, d.interno,
             d.unidade_origem, d.unidade_destino,
             d.nu_guia||'/'||d.ano_guia||'-'||d6.sigla as guia_tramite, d.recebimento, d.sq_tipo_despacho,
             to_char(d.envio, 'dd/mm/yyyy, hh24:mi:ss') as phpdt_envio, 
             d1.nome as nm_despacho,
             case when d.unidade_destino is null then d4.nome_resumido else d2.nome end as nm_unid_origem,    d2.sigla as sg_unid_origem,
             d3.nome as nm_unid_dest,      d3.sigla as sg_unid_dest,
             d7.despacho_arqcentral,
             d8.nome as nm_pessoa_resp,    d8.nome_resumido as nm_res_pessoa_resp
        from siw_menu                                a
             inner         join siw_solicitacao      b  on (a.sq_menu              = b.sq_menu)
               inner       join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                     from siw_solicitacao
                                )                    b1 on (b.sq_siw_solicitacao   = b1.sq_siw_solicitacao)
               inner       join eo_unidade           b2 on (b.sq_unidade           = b2.sq_unidade)
               inner       join pa_documento         c  on (b.sq_siw_solicitacao   = c.sq_siw_solicitacao)
                 inner     join eo_unidade           c1 on (c.unidade_autuacao     = c1.sq_unidade)
                   inner   join pa_unidade           c4 on (c1.sq_unidade          = c4.sq_unidade)
                     inner join eo_unidade           c6 on (c6.sq_unidade          = coalesce(c4.sq_unidade_pai,c4.sq_unidade))
                 inner     join pa_especie_documento c2 on (c.sq_especie_documento = c2.sq_especie_documento)
                 left      join co_pessoa            c3 on (c.pessoa_origem        = c3.sq_pessoa)
                 left      join pa_documento         c5 on (c.sq_documento_pai     = c5.sq_siw_solicitacao)
                 left      join (select x.sq_siw_solicitacao, count(*) as existe
                                   from pa_documento               x
                                        inner   join siw_solicitacao x1 on (x.sq_siw_solicitacao = x1.sq_siw_solicitacao)
                                          inner join siw_menu        x2 on (x2.sq_menu  = p_menu  and x1.sq_menu           = x2.sq_menu)
                                        left    join sg_autenticacao y  on (y.sq_pessoa = p_pessoa and x.unidade_int_posse = y.sq_unidade)
                                        left    join eo_unidade_resp z  on (z.sq_pessoa = p_pessoa and x.unidade_int_posse = z.sq_unidade and z.fim is null)
                                  where y.sq_pessoa is not null or z.sq_pessoa is not null
                                 group by x.sq_siw_solicitacao
                                )                    c7 on (c.sq_siw_solicitacao   = c7.sq_siw_solicitacao)
                 inner     join pa_documento_assunto c8 on (c.sq_siw_solicitacao   = c8.sq_siw_solicitacao and
                                                            c8.principal           = 'S'
                                                           )
                   inner   join pa_assunto           c9 on (c8.sq_assunto          = c9.sq_assunto)
                 inner     join pa_documento_log     d  on (c.sq_siw_solicitacao   = d.sq_siw_solicitacao and
                                                            d.recebimento          is null
                                                           )
                   inner   join pa_tipo_despacho     d1 on (d.sq_tipo_despacho     = d1.sq_tipo_despacho)
                   left    join pa_parametro         d7 on (d1.sq_tipo_despacho    = d7.despacho_arqcentral)
                   inner   join eo_unidade           d2 on (d.unidade_origem       = d2.sq_unidade)
                     inner join pa_unidade           d5 on (d2.sq_unidade          = d5.sq_unidade)
                     inner join eo_unidade           d6 on (d6.sq_unidade          = coalesce(d5.sq_unidade_pai,d5.sq_unidade))
                   left    join eo_unidade           d3 on (d.unidade_destino      = d3.sq_unidade)
                   left    join co_pessoa            d4 on (d.pessoa_destino       = d4.sq_pessoa)
                   left    join co_pessoa            d8 on (d.cadastrador          = d8.sq_pessoa),
               sg_autenticacao                       w
       where a.sq_menu      = p_menu
         and w.sq_pessoa    = p_pessoa
         and b.sq_solic_pai is null
         and (p_prefixo     is null or (p_prefixo     is not null and c.prefixo            = p_prefixo))
         and (p_numero      is null or (p_numero      is not null and c.numero_documento   = p_numero))
         and (p_ano         is null or (p_ano         is not null and c.ano                = p_ano))
         and (p_unidade     is null or (p_unidade     is not null and b.sq_unidade         = p_unidade))
         and (p_ini         is null or (p_ini         is not null and b.inicio             between p_ini and p_fim))
         and (p_empenho     is null or (p_empenho     is not null and acentos(c.numero_original) like '%'||acentos(p_empenho)||'%'))
         and (p_cd_assunto  is null or (p_cd_assunto  is not null and c9.codigo like p_cd_assunto||'%'))
         and (p_assunto     is null or (p_assunto     is not null and acentos(b.descricao) like '%'||acentos(p_assunto)||'%'))
         and (p_solicitante is null or (p_solicitante is not null and c.sq_especie_documento = p_solicitante))
         and (p_proponente  is null or (p_proponente  is not null and (to_char(c.pessoa_origem) = p_proponente or c3.nome_indice like '%'||acentos(p_proponente)||'%' or c3.nome_resumido_ind like '%'||acentos(p_proponente)||'%')))
         and (p_processo    is null or (p_processo    is not null and 0 < (select count(*)
                                                                             from pa_documento_interessado x
                                                                                  inner join co_pessoa     y on (x.sq_pessoa = y.sq_pessoa)
                                                                            where x.sq_siw_solicitacao = d.sq_siw_solicitacao
                                                                              and (acentos(y.nome_indice)       like '%'||acentos(p_processo)||'%' or
                                                                                   acentos(y.nome_resumido_ind) like '%'||acentos(p_processo)||'%'
                                                                                  )
                                                                          )
                                       )
             )
         and (p_nu_guia     is not null or ((d.unidade_destino is not null and (d.unidade_destino = w.sq_unidade or 0 < (select count(*) from eo_unidade_resp where sq_pessoa = p_pessoa and sq_unidade = d.unidade_destino and fim is null))) or (d.unidade_destino is null and (d.unidade_origem = w.sq_unidade or 0 < (select count(*) from eo_unidade_resp where sq_pessoa = p_pessoa and sq_unidade = d.unidade_origem and fim is null)))))
         and (p_nu_guia     is null or (p_nu_guia     is not null and d.nu_guia            = p_nu_guia and d.ano_guia = p_ano_guia));
         --and (p_unid_autua is null or (p_unid_autua  is not null and coalesce(c4.sq_unidade_pai,c4.sq_unidade) = coalesce(d5.sq_unidade_pai, d5.sq_unidade)));
   Elsif p_restricao = 'RECEBIDO' Then
      -- Recupera guias de tramitação
      open p_result for
      select a.nu_guia, a.ano_guia, b.unidade_autuacao, b.sq_siw_solicitacao
        from pa_documento_log          a
             inner   join pa_documento b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
       where a.nu_guia          = p_nu_guia
         and a.ano_guia         = p_ano_guia
         and b.unidade_autuacao = p_unid_autua
         and a.recebimento      is null;
   Elsif p_restricao = 'EXISTE' Then
      -- Verifica a existência de um protocolo
      open p_result for
      select a.sq_siw_solicitacao, a.processo,
             a.prefixo||'.'||substr(1000000+a.numero_documento,2,6)||'/'||a.ano||'-'||substr(100+a.digito,2,2) as protocolo
        from siw_solicitacao         b
             inner join pa_documento a on (b.sq_siw_solicitacao = a.sq_siw_solicitacao)
       where b.sq_menu          = p_menu
         and a.prefixo          = p_prefixo 
         and a.numero_documento = p_numero 
         and a.ano              = p_ano;
   End If;
end sp_getProtocolo;
/
