create or replace procedure sp_getSolicMeta
   (p_cliente        in  number,
    p_usuario        in  number,
    p_chave          in  number   default null,
    p_chave_aux      in  number   default null,
    p_pessoa         in  number   default null,
    p_unidade        in  number   default null,
    p_titulo         in  varchar2 default null,
    p_indicador      in  number   default null,
    p_tipo           in  varchar2 default null,
    p_ativo          in  varchar2 default null,
    p_base           in  number   default null,
    p_pais           in  number   default null,
    p_regiao         in  number   default null,
    p_uf             in  varchar2 default null,
    p_cidade         in  number   default null,
    p_alt_i          in  date     default null,
    p_alt_f          in  date     default null,
    p_ref_i          in  date     default null,
    p_ref_f          in  date     default null,
    p_restricao      in  varchar2 default null,
    p_result         out sys_refcursor) is
begin
   If p_restricao is null or p_restricao = 'EXISTEMETA' Then
      -- Recupera as metas ligadas a uma solicitação
      open p_result for 
         select a.sq_solic_meta as chave_aux,                          a.sq_siw_solicitacao as chave, 
                a.sq_pessoa,           a.sq_unidade,                   a.titulo,
                a.descricao,           a.ordem,                        a.inicio, 
                a.fim,                 a.quantidade,                   a.cumulativa, 
                a.exequivel,           a.justificativa_inexequivel,    a.outras_medidas, 
                a.situacao_atual,      a.cadastrador,                  a.inclusao, 
                a.ultima_alteracao,    a.base_geografica,              a.valor_inicial,
                case a.base_geografica
                     when 1 then case e.padrao when 'S' then 'Nacional'              else 'Nacional - '||e.nome end
                     when 2 then case e.padrao when 'S' then 'Regional - '||f.nome   else 'Regional - '||e.nome||' - '||f.nome  end
                     when 3 then case e.padrao when 'S' then 'Estadual - '||g.co_uf  else 'Estadual - '||e.nome||' - '||g.co_uf end
                     when 4 then case e.padrao when 'S' then 'Municipal - '||h.nome||'-'||g.co_uf  else 'Municipal - '||h.nome||' ('||e.nome||')' end
                     when 5 then 'Organizacional'
                end as nm_base_geografica,
                a1.sq_siw_tramite,     a1.solicitante,                 a1.inicio as ini_solic,
                a1.fim as fim_solic,   a1.conclusao,
                a2.sq_menu,            a2.sq_modulo,                   a2.nome,
                a2.p1,                 a2.p2,                          a2.p3,
                a2.p4,                 a2.sigla,                       a2.link,
                a3.nome nm_modulo,     a3.sigla sg_modulo,             
                a4.nome nm_tramite,    a4.ordem or_tramite,            a4.sigla sg_tramite,
                a4.ativo st_tramite,
                a5.nome_resumido as nm_solic,                          a5.nome_resumido_ind as nm_solic_ind,
                b.sq_eoindicador,      b.nome nm_indicador,            b.sigla sg_indicador, 
                b.ativo st_indicador,  case b.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo_ind,
                c.sq_unidade_medida,   c.nome as nm_unidade_medida,    c.sigla sg_unidade_medida,
                d.sq_tipo_indicador,   d.nome as nm_tipo_indicador,
                e.sq_pais,             e.nome as nm_pais,
                f.sq_regiao,           f.nome as nm_regiao,
                g.co_uf,
                h.sq_cidade,           h.nome as nm_cidade,
                i.nome_resumido as nm_cadastrador,
                j.nome_resumido as nm_resp_meta,
                k.nome as nm_unidade,  k1.sq_pessoa tit_exec,          k2.sq_pessoa subst_exec
           from siw_solic_meta                   a
                inner     join siw_solicitacao   a1 on (a.sq_siw_solicitacao  = a1.sq_siw_solicitacao)
                  inner   join siw_menu          a2 on (a1.sq_menu            = a2.sq_menu)
                    inner join siw_modulo        a3 on (a2.sq_modulo          = a3.sq_modulo)
                  inner   join siw_tramite       a4 on (a1.sq_siw_tramite     = a4.sq_siw_tramite)
                  inner   join co_pessoa         a5 on (a1.solicitante        = a5.sq_pessoa)
                  inner   join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_usuario) acesso
                                  from siw_solicitacao
                               )                 a6 on (a1.sq_siw_solicitacao = a6.sq_siw_solicitacao)
                inner     join eo_indicador      b  on (a.sq_eoindicador      = b.sq_eoindicador)
                  inner   join co_unidade_medida c  on (b.sq_unidade_medida   = c.sq_unidade_medida)
                  inner   join eo_tipo_indicador d  on (b.sq_tipo_indicador   = d.sq_tipo_indicador)
                left      join co_pais           e  on (a.sq_pais             = e.sq_pais)
                left      join co_regiao         f  on (a.sq_regiao           = f.sq_regiao)
                left      join co_uf             g  on (a.sq_pais             = g.sq_pais and
                                                        a.co_uf               = g.co_uf
                                                       )
                left      join co_cidade         h  on (a.sq_cidade           = h.sq_cidade)
                inner     join co_pessoa         i  on (a.cadastrador         = i.sq_pessoa)
                inner     join co_pessoa         j  on (a.sq_pessoa           = j.sq_pessoa)
                inner     join eo_unidade        k  on (a.sq_unidade          = k.sq_unidade)
                  left    join eo_unidade_resp   k1 on (k.sq_unidade          = k1.sq_unidade and
                                                        k1.tipo_respons       = 'T'           and
                                                        k1.fim                is null
                                                       )
                  left    join eo_unidade_resp   k2 on (k.sq_unidade          = k2.sq_unidade and
                                                        k2.tipo_respons       = 'S'           and
                                                        k2.fim                is null
                                                       )
          where b.cliente    = p_cliente 
            and (p_chave     is null or (p_chave     is not null and a.sq_siw_solicitacao = p_chave))
            and (p_chave_aux is null or (p_chave_aux is not null and ((p_restricao is null and a.sq_solic_meta = p_chave_aux) or 
                                                                      (p_restricao = 'EXISTEMETA' and a.sq_solic_meta <> coalesce(p_chave_aux,0))
                                                                     )
                                        )
                )
            and (p_pessoa    is null or (p_pessoa    is not null and a.sq_pessoa          = p_pessoa))
            and (p_indicador is null or (p_indicador is not null and a.sq_eoindicador     = p_indicador))
            and (p_unidade   is null or (p_unidade   is not null and a.sq_unidade         = p_unidade))
            and (p_titulo    is null or (p_titulo    is not null and acentos(b.nome)      like '%'||acentos(p_titulo)||'%'))
            and (p_ativo     is null or (p_ativo     is not null and b.ativo              = p_ativo))
            and (p_tipo      is null or (p_tipo      is not null and b.sq_tipo_indicador  = p_tipo))
            and (p_pais      is null or (p_pais      is not null and a.sq_pais            = p_pais))
            and (p_base      is null or (p_base      is not null and a.base_geografica    = p_base))
            and (p_regiao    is null or (p_regiao    is not null and a.sq_regiao          = p_regiao))
            and (p_uf        is null or (p_uf        is not null and a.sq_pais            = p_pais and a.co_uf = p_uf))
            and (p_cidade    is null or (p_cidade    is not null and a.sq_cidade          = p_cidade))
            and (p_alt_i     is null or (p_alt_i     is not null and (a.inclusao          between p_alt_i and p_alt_f or
                                                                      a.ultima_alteracao  between p_alt_i and p_alt_f
                                                                     )
                                        )
                )
            and (p_ref_i     is null or (p_ref_i     is not null and (a.inicio            between p_ref_i and p_ref_f or
                                                                      a.fim               between p_ref_i and p_ref_f or
                                                                      p_ref_i             between a.inicio and a.fim or
                                                                      p_ref_f             between a.inicio and a.fim
                                                                     )
                                        )
                );
   End If;
end sp_getSolicMeta;
/
