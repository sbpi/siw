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
    p_result       out sys_refcursor) is
begin
   If p_restricao = 'RELPATRAM' or p_restricao = 'RELPAETIQ' Then
      -- Recupera guias de tramitação
      open p_result for
      select b.inicio, b.fim, b.sq_siw_solicitacao,
             c.numero_original,
             c.prefixo||'.'||substr(1000000+c.numero_documento,2,6)||'/'||c.ano||'-'||substr(100+c.digito,2,2) as protocolo,
             c1.sigla sg_unidade,
             c2.nome as nm_especie,
             case c.interno when 'S' then b2.sigla else c3.nome_resumido end as nm_origem_doc,
             case c.processo when 'S' then 'Processo' else 'Documento' end as nm_tipo,
             d.nu_guia, d.ano_guia, c.unidade_autuacao, d.resumo, d.unidade_externa, d.interno,
             d.nu_guia||'/'||d.ano_guia||'-'||c1.sigla as guia_tramite,
             to_char(d.envio, 'dd/mm/yyyy, hh24:mi:ss') as phpdt_envio, 
             case d.interno when 'S' then d3.nome else d4.nome end as nm_destino,
             d1.nome as nm_despacho,
             d2.nome as nm_unid_origem,    d2.sigla as sg_unid_origem,
             d3.nome as nm_unid_dest,      d3.sigla as sg_unid_dest,
             d4.nome as nm_pessoa_dest,    d4.nome_resumido as nm_res_pessoa_dest
        from siw_menu                                a
             inner         join siw_solicitacao      b  on (a.sq_menu              = b.sq_menu)
               inner       join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                     from siw_solicitacao
                                )                    b1 on (b.sq_siw_solicitacao   = b1.sq_siw_solicitacao)
               inner       join eo_unidade           b2 on (b.sq_unidade           = b2.sq_unidade)
               inner       join pa_documento         c  on (b.sq_siw_solicitacao   = c.sq_siw_solicitacao)
                 inner     join eo_unidade           c1 on (c.unidade_autuacao     = c1.sq_unidade)
                 inner     join pa_especie_documento c2 on (c.sq_especie_documento = c2.sq_especie_documento)
                 left      join co_pessoa            c3 on (c.pessoa_origem        = c3.sq_pessoa)
                 left      join (select sq_siw_solicitacao, max(sq_documento_log) as sq_documento_log
                                   from pa_documento_log
                                  group by sq_siw_solicitacao
                                )                    c4 on (c.sq_siw_solicitacao   = c4.sq_siw_solicitacao)
                   left    join pa_documento_log     d  on (c4.sq_documento_log    = d.sq_documento_log)
                     left  join pa_tipo_despacho     d1 on (d.sq_tipo_despacho     = d1.sq_tipo_despacho)
                     left  join eo_unidade           d2 on (d.unidade_origem       = d2.sq_unidade)
                     left  join eo_unidade           d3 on (d.unidade_destino      = d3.sq_unidade)
                     left  join co_pessoa            d4 on (d.pessoa_destino       = d4.sq_pessoa),
               sg_autenticacao                       w
               left        join sg_pessoa_modulo     x on (w.sq_pessoa             = x.sq_pessoa)
       where a.sq_menu     = p_menu
         and w.sq_pessoa   = p_pessoa
         and (b1.acesso    >= 8 or c.unidade_int_posse = w.sq_unidade or b.cadastrador = p_pessoa)
         and (p_chave      is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
         and (p_chave_aux  is null or (p_chave_aux   is not null and d.sq_documento_log   = p_chave_aux))
         and (p_numero     is null or (p_numero      is not null and c.prefixo            = p_prefixo and c.numero_documento = p_numero and c.ano = p_ano))
         and (p_nu_guia    is null or (p_nu_guia     is not null and d.nu_guia            = p_nu_guia and d.ano_guia = p_ano_guia))
         and (p_unid_autua is null or (p_unid_autua  is not null and c.unidade_autuacao   = p_unid_autua))
         and (p_unid_posse is null or (p_unid_posse  is not null and c.unidade_int_posse  = p_unid_posse))
         and (p_ini        is null or (p_ini         is not null and d.envio              between p_ini and p_fim+1))
         and (p_restricao = 'RELPAETIQ' or 
              (p_restricao = 'RELPATRAM' and d.sq_documento_log is not null and d.recebimento is null and d1.sq_tipo_despacho is not null and d2.sq_unidade is not null)
             )
         and (p_tipo       = 1 or
              (p_tipo      = 2 and b1.acesso > 0)
             );
   Elsif instr('PADAUTUA,PADANEXA,PADJUNTA,PADTRANSF,PADELIM,PADEMPREST', p_restricao) > 0 Then
      -- Recupera guias de tramitação
      open p_result for
      select b.inicio, b.fim, b.sq_siw_solicitacao, b.sq_solic_pai,
             c.numero_original,
             c.prefixo||'.'||substr(1000000+c.numero_documento,2,6)||'/'||c.ano||'-'||substr(100+c.digito,2,2) as protocolo,
             c1.sigla sg_unidade,
             c2.nome as nm_especie,
             case c.interno when 'S' then b2.sigla else c3.nome_resumido end as nm_origem_doc,
             case c.processo when 'S' then 'Processo' else 'Documento' end as nm_tipo,
             d.nu_guia, d.ano_guia, c.unidade_autuacao, d.resumo, d.unidade_externa, d.interno,
             d.nu_guia||'/'||d.ano_guia||'-'||c1.sigla as guia_tramite, d.recebimento,
             to_char(d.envio, 'dd/mm/yyyy, hh24:mi:ss') as phpdt_envio, 
             d1.nome as nm_despacho,
             d2.nome as nm_unid_origem,    d2.sigla as sg_unid_origem,
             d3.nome as nm_unid_dest,      d3.sigla as sg_unid_dest
        from siw_menu                                a
             inner         join siw_solicitacao      b  on (a.sq_menu              = b.sq_menu)
               inner       join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                     from siw_solicitacao
                                )                    b1 on (b.sq_siw_solicitacao   = b1.sq_siw_solicitacao)
               inner       join eo_unidade           b2 on (b.sq_unidade           = b2.sq_unidade)
               inner       join pa_documento         c  on (b.sq_siw_solicitacao   = c.sq_siw_solicitacao)
                 inner     join eo_unidade           c1 on (c.unidade_autuacao     = c1.sq_unidade)
                 inner     join pa_especie_documento c2 on (c.sq_especie_documento = c2.sq_especie_documento)
                 left      join co_pessoa            c3 on (c.pessoa_origem        = c3.sq_pessoa)
                 inner     join pa_documento_log     d  on (c.sq_siw_solicitacao   = d.sq_siw_solicitacao and
                                                            d.recebimento          is not null
                                                           )
                   inner   join (select sq_siw_solicitacao, max(sq_documento_log) chave 
                                   from pa_documento_log
                                 group by sq_siw_solicitacao
                                )                    dc on (d.sq_documento_log     = dc.chave)
                   inner   join eo_unidade           d2 on (d.unidade_origem       = d2.sq_unidade)
                   inner   join eo_unidade           d3 on (d.unidade_destino      = d3.sq_unidade)
                   inner   join pa_tipo_despacho     d1 on (d.sq_tipo_despacho     = d1.sq_tipo_despacho)
                     left  join pa_parametro         d4 on (d1.sq_tipo_despacho    = d4.despacho_arqcentral)
                     left  join pa_parametro         d5 on (d1.sq_tipo_despacho    = d5.despacho_arqsetorial)
                     left  join pa_parametro         d6 on (d1.sq_tipo_despacho    = d6.despacho_emprestimo)
                     left  join pa_parametro         d7 on (d1.sq_tipo_despacho    = d7.despacho_devolucao)
                     left  join pa_parametro         d8 on (d1.sq_tipo_despacho    = d8.despacho_anexar)
                     left  join pa_parametro         d9 on (d1.sq_tipo_despacho    = d9.despacho_apensar)
                     left  join pa_parametro         da on (d1.sq_tipo_despacho    = da.despacho_eliminar)
                     left  join pa_parametro         db on (d1.sq_tipo_despacho    = db.despacho_autuar),
               sg_autenticacao                       w
               left        join sg_pessoa_modulo     x on (w.sq_pessoa             = x.sq_pessoa)
       where a.sq_menu     = p_menu
         and w.sq_pessoa   = p_pessoa
         and (b1.acesso    >= 8 or d.unidade_destino = w.sq_unidade)
         and (p_chave      is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
         and (p_chave_aux  is null or (p_chave_aux   is not null and d.sq_documento_log   = p_chave_aux))
         and (p_numero     is null or (p_numero      is not null and c.prefixo            = p_prefixo and c.numero_documento = p_numero and c.ano = p_ano))
         and (p_nu_guia    is null or (p_nu_guia     is not null and d.nu_guia            = p_nu_guia and d.ano_guia = p_ano_guia))
         and (p_unid_autua is null or (p_unid_autua  is not null and c.unidade_autuacao   = p_unid_autua))
         and (p_unid_posse is null or (p_unid_posse  is not null and c.unidade_int_posse  = p_unid_posse))
         and (p_ini        is null or (p_ini         is not null and d.envio              between p_ini and p_fim+1))
         and (p_tipo       = 1 or (p_tipo      = 2 and b1.acesso > 0))
         and ((p_restricao = 'PADAUTUA'   and db.cliente is not null and c.data_autuacao is null) or
              (p_restricao = 'PADANEXA'   and d8.cliente is not null) or
              (p_restricao = 'PADJUNTA'   and c.processo = 'S' and d9.cliente is not null) or
              (p_restricao = 'PADTRANSF'  and (d4.cliente is not null or d5.cliente is not null)) or
              (p_restricao = 'PADELIM'    and da.cliente is not null) or
              (p_restricao = 'PADEMPREST' and d6.cliente is not null)
             );
   Elsif p_restricao = 'PADTRAM' Then
      -- Recupera guias de tramitação
      open p_result for
      select b.sq_siw_solicitacao, b.inicio, b.fim, b.sq_siw_tramite,
             c.numero_original, c.numero_documento, c.unidade_int_posse,
             c.prefixo||'.'||substr(1000000+c.numero_documento,2,6)||'/'||c.ano||'-'||substr(100+c.digito,2,2) as protocolo,
             c1.sigla sg_unidade,
             c2.nome as nm_especie,
             case c.interno when 'S' then b2.sigla else c3.nome_resumido end as nm_origem_doc,
             case c.processo when 'S' then 'Processo' else 'Documento' end as nm_tipo,
             d.nu_guia, d.ano_guia, c.unidade_autuacao, d.resumo, d.unidade_externa, d.interno,
             d.nu_guia||'/'||d.ano_guia||'-'||c1.sigla as guia_tramite,
             to_char(d.envio, 'dd/mm/yyyy, hh24:mi:ss') as phpdt_envio, 
             case d.interno when 'S' then d3.nome else d4.nome end as nm_destino,
             d1.nome as nm_despacho,
             d2.nome as nm_unid_origem,    d2.sigla as sg_unid_origem,
             d3.nome as nm_unid_dest,      d3.sigla as sg_unid_dest,
             d4.nome as nm_pessoa_dest,    d4.nome_resumido as nm_res_pessoa_dest
        from siw_menu                                a
             inner         join siw_solicitacao      b  on (a.sq_menu              = b.sq_menu)
               inner       join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                     from siw_solicitacao
                                )                    b1 on (b.sq_siw_solicitacao   = b1.sq_siw_solicitacao)
               inner       join eo_unidade           b2 on (b.sq_unidade           = b2.sq_unidade)
               inner       join pa_documento         c  on (b.sq_siw_solicitacao   = c.sq_siw_solicitacao)
                 inner     join eo_unidade           c1 on (c.unidade_autuacao     = c1.sq_unidade)
                 inner     join pa_especie_documento c2 on (c.sq_especie_documento = c2.sq_especie_documento)
                 left      join co_pessoa            c3 on (c.pessoa_origem        = c3.sq_pessoa)
                 left      join (select sq_siw_solicitacao, max(sq_documento_log) as sq_documento_log
                                   from pa_documento_log
                                  group by sq_siw_solicitacao
                                )                    c4 on (c.sq_siw_solicitacao   = c4.sq_siw_solicitacao)
                   left    join pa_documento_log     d  on (c4.sq_documento_log    = d.sq_documento_log)
                     left  join pa_tipo_despacho     d1 on (d.sq_tipo_despacho     = d1.sq_tipo_despacho)
                     left  join eo_unidade           d2 on (d.unidade_origem       = d2.sq_unidade)
                     left  join eo_unidade           d3 on (d.unidade_destino      = d3.sq_unidade)
                     left  join co_pessoa            d4 on (d.pessoa_destino       = d4.sq_pessoa),
               sg_autenticacao                       w
               left        join sg_pessoa_modulo     x on (w.sq_pessoa             = x.sq_pessoa)
       where a.sq_menu     = p_menu
         and w.sq_pessoa   = p_pessoa
         and (d.sq_documento_log is null or (d.sq_documento_log is not null and d.recebimento is not null))
         and (b1.acesso    >= 8 or (c.unidade_int_posse = w.sq_unidade))
         and (p_chave      is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
         and (p_chave_aux  is null or (p_chave_aux   is not null and d.sq_documento_log   = p_chave_aux))
         and (p_numero     is null or (p_numero      is not null and c.prefixo            = p_prefixo and c.numero_documento = p_numero and c.ano = p_ano))
         and (p_nu_guia    is null or (p_nu_guia     is not null and d.nu_guia            = p_nu_guia and d.ano_guia = p_ano_guia))
         and (p_unid_autua is null or (p_unid_autua  is not null and c.unidade_autuacao   = p_unid_autua))
         and (p_ini        is null or (p_ini         is not null and d.envio              between p_ini and p_fim))
         and (p_tipo       = 1 or
              (p_tipo      = 2 and b1.acesso > 0)
             );
   Elsif p_restricao = 'PADRECEB' Then
      -- Recupera guias de tramitação
      open p_result for
      select b.inicio, b.fim, b.sq_siw_solicitacao, 
             c.numero_original,
             c.prefixo||'.'||substr(1000000+c.numero_documento,2,6)||'/'||c.ano||'-'||substr(100+c.digito,2,2) as protocolo,
             c1.sigla sg_unidade,
             c2.nome as nm_especie,
             case c.interno when 'S' then b2.sigla else c3.nome_resumido end as nm_origem_doc,
             case c.processo when 'S' then 'Processo' else 'Documento' end as nm_tipo,
             d.nu_guia, d.ano_guia, c.unidade_autuacao, d.resumo, d.unidade_externa, d.interno,
             d.nu_guia||'/'||d.ano_guia||'-'||c1.sigla as guia_tramite, d.recebimento,
             to_char(d.envio, 'dd/mm/yyyy, hh24:mi:ss') as phpdt_envio, 
             d1.nome as nm_despacho,
             d2.nome as nm_unid_origem,    d2.sigla as sg_unid_origem,
             d3.nome as nm_unid_dest,      d3.sigla as sg_unid_dest
        from siw_menu                                a
             inner         join siw_solicitacao      b  on (a.sq_menu              = b.sq_menu)
               inner       join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) acesso
                                     from siw_solicitacao
                                )                    b1 on (b.sq_siw_solicitacao   = b1.sq_siw_solicitacao)
               inner       join eo_unidade           b2 on (b.sq_unidade           = b2.sq_unidade)
               inner       join pa_documento         c  on (b.sq_siw_solicitacao   = c.sq_siw_solicitacao)
                 inner     join eo_unidade           c1 on (c.unidade_autuacao     = c1.sq_unidade)
                 inner     join pa_especie_documento c2 on (c.sq_especie_documento = c2.sq_especie_documento)
                 left      join co_pessoa            c3 on (c.pessoa_origem        = c3.sq_pessoa)
                 inner     join pa_documento_log     d  on (c.sq_siw_solicitacao   = d.sq_siw_solicitacao and
                                                            d.recebimento          is null
                                                           )
                   inner   join pa_tipo_despacho     d1 on (d.sq_tipo_despacho     = d1.sq_tipo_despacho)
                   inner   join eo_unidade           d2 on (d.unidade_origem       = d2.sq_unidade)
                   inner   join eo_unidade           d3 on (d.unidade_destino      = d3.sq_unidade),
               sg_autenticacao                       w
               left        join sg_pessoa_modulo     x on (w.sq_pessoa             = x.sq_pessoa)
       where a.sq_menu     = p_menu
         and w.sq_pessoa   = p_pessoa
         and (b1.acesso    >= 8 or d.unidade_destino = w.sq_unidade)
         and (p_nu_guia    is null or (p_nu_guia     is not null and d.nu_guia            = p_nu_guia and d.ano_guia = p_ano_guia))
         and (p_unid_autua is null or (p_unid_autua  is not null and c.unidade_autuacao   = p_unid_autua));
   Elsif p_restricao = 'RECEBIDO' Then
      -- Recupera guias de tramitação
      open p_result for
      select a.nu_guia, a.ano_guia, b.unidade_autuacao, b.sq_siw_solicitacao
        from pa_documento_log a
             inner join pa_documento b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
       where a.nu_guia          = p_nu_guia
         and a.ano_guia         = p_ano_guia
         and b.unidade_autuacao = p_unid_autua
         and a.recebimento      is not null;
   End If;
end sp_getProtocolo;
/
