create or replace procedure sp_getSolicRecursos
   (p_cliente        in  number,
    p_usuario        in  number,
    p_chave          in  number   default null,
    p_chave_aux      in  number   default null,
    p_solicitante    in  number   default null,
    p_autorizador    in  number   default null,
    p_unidade        in  number   default null,
    p_gestora        in  number   default null,
    p_recurso        in  number   default null,
    p_tipo           in  varchar2 default null,
    p_ativo          in  varchar2 default null,
    p_ref_i          in  date     default null,
    p_ref_f          in  date     default null,
    p_restricao      in  varchar2 default null,
    p_result         out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera as indicadors de planejamento
      open p_result for 
         select a.sq_solic_recurso as chave_aux,                       a.sq_siw_solicitacao as chave, 
                a.sq_recurso,          a.tipo,                         a.solicitante, 
                a.justificativa,       a.inclusao,                     a.autorizado, 
                a.autorizacao,         a.autorizador,
                case a.tipo when 1 then 'Alocaçãol' else 'Liberação' end as nm_tipo,
                a1.sq_siw_tramite,     a1.solicitante,                 a1.inicio as ini_solic,
                a1.fim as fim_solic,   a1.conclusao,
                a2.sq_menu,            a2.sq_modulo,                   a2.nome,
                a2.p1,                 a2.p2,                          a2.p3,
                a2.p4,                 a2.sigla,                       a2.link,
                a3.nome nm_modulo,     a3.sigla sg_modulo,             
                a4.nome nm_tramite,    a4.ordem or_tramite,            a4.sigla sg_tramite,
                a4.ativo st_tramite,
                a5.nome_resumido as nm_solic,                          a5.nome_resumido_ind as nm_solic_ind,
                b.nome as nm_recurso,  b.codigo as cd_recurso, 
                c.nome as nm_unidade,  c.sigla as sg_unidade,
                d.sq_tipo_recurso,     d.nome as nm_tipo_recurso,      d.sigla as sg_tipo_recurso,
                e.nome as nm_unidade_medida,                           e.sigla as sg_unidade_medida,
                coalesce(f.alocacao,0) as alocacao,
                i.nome_resumido as nm_solicitante,
                j.nome_resumido as nm_autorizador,
                k.nome as nm_unidade,  k1.sq_pessoa tit_exec,          k2.sq_pessoa subst_exec
           from siw_solic_recurso                 a
                inner     join siw_solicitacao    a1 on (a.sq_siw_solicitacao  = a1.sq_siw_solicitacao)
                  inner   join siw_menu           a2 on (a1.sq_menu            = a2.sq_menu)
                    inner join siw_modulo         a3 on (a2.sq_modulo          = a3.sq_modulo)
                  inner   join siw_tramite        a4 on (a1.sq_siw_tramite     = a4.sq_siw_tramite)
                  inner   join co_pessoa          a5 on (a1.solicitante        = a5.sq_pessoa)
                  inner   join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_usuario) acesso
                                  from siw_solicitacao
                               )                  a6 on (a1.sq_siw_solicitacao = a6.sq_siw_solicitacao)
                inner     join eo_recurso         b  on (a.sq_recurso          = b.sq_recurso)
                  inner   join eo_unidade         c  on (b.unidade_gestora     = c.sq_unidade)
                  inner   join eo_tipo_recurso    d  on (b.sq_tipo_recurso     = d.sq_tipo_recurso)
                  inner   join co_unidade_medida  e  on (b.sq_unidade_medida   = e.sq_unidade_medida)
                  left    join (select y.sq_solic_recurso, x.sq_recurso, sum(y.unidades_solicitadas) alocacao
                                  from siw_solic_recurso                     x
                                       inner join siw_solic_recurso_alocacao y on (x.sq_solic_recurso = y.sq_solic_recurso and
                                                                                   trunc(sysdate)     between y.inicio and y.fim and
                                                                                   (p_ref_i           is null or
                                                                                    (p_ref_i          is not null and
                                                                                     y.inicio         between p_ref_i and p_ref_f or
                                                                                     y.fim            between p_ref_i and p_ref_f or
                                                                                     p_ref_i          between y.inicio and y.fim or
                                                                                     p_ref_f          between y.inicio and y.fim
                                                                                    )
                                                                                   )
                                                                                  )
                                group by y.sq_solic_recurso, x.sq_recurso
                               )                  f  on (b.sq_recurso          = f.sq_recurso and
                                                         a.sq_solic_recurso    = f.sq_solic_recurso
                                                        )
                  inner   join co_pessoa          i  on (a.solicitante         = i.sq_pessoa)
                  inner   join co_pessoa          j  on (a.autorizador         = j.sq_pessoa)
                  inner   join eo_unidade         k  on (a1.sq_unidade         = k.sq_unidade)
                    left  join eo_unidade_resp    k1 on (k.sq_unidade          = k1.sq_unidade and
                                                         k1.tipo_respons       = 'T'           and
                                                         k1.fim                is null
                                                        )
                    left  join eo_unidade_resp    k2 on (k.sq_unidade          = k2.sq_unidade and
                                                         k2.tipo_respons       = 'S'           and
                                                         k2.fim                is null
                                                        )
          where b.cliente        = p_cliente 
            and (p_chave         is null or (p_chave         is not null and a.sq_siw_solicitacao = p_chave))
            and (p_chave_aux     is null or (p_chave_aux     is not null and a.sq_solic_recurso   = p_chave_aux))
            and (p_solicitante   is null or (p_solicitante   is not null and a.solicitante        = p_solicitante))
            and (p_autorizador   is null or (p_autorizador   is not null and a.autorizador        = p_autorizador))
            and (p_recurso       is null or (p_recurso       is not null and a.sq_recurso         = p_recurso))
            and (p_unidade       is null or (p_unidade       is not null and a1.sq_unidade        = p_unidade))
            and (p_gestora       is null or (p_gestora       is not null and b.unidade_gestora    = p_gestora))
            and (p_ativo         is null or (p_ativo         is not null and b.ativo              = p_ativo))
            and (p_tipo          is null or (p_tipo          is not null and b.sq_tipo_recurso    = p_tipo))
            and (p_ref_i         is null or (p_ref_i         is not null and f.sq_solic_recurso   is not null));
   End If;
end sp_getSolicRecursos;
/
