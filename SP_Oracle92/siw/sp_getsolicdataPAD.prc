create or replace procedure SP_GetSolicDataPAD
   (p_chave     in number,
    p_restricao in varchar2 default null,
    p_result    out sys_refcursor
   ) is
   w_menu siw_menu.sq_menu%type := 0;
   w_reg number(18);
begin
   If p_chave is not null Then
      -- Recupera o menu ao qual a solicitação está ligada   
      select count(*) into w_reg from siw_solicitacao where sq_siw_solicitacao = p_chave;
      If w_reg > 0 Then
        select sq_menu into w_menu from siw_solicitacao where sq_siw_solicitacao = p_chave;      
      Else
        w_menu := 0;
      End If;
  End If;
   
   If substr(p_restricao,1,3) = 'PAD' Then
      -- Recupera os programas que o usuário pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao as ds_menu,        a.justificativa,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec, a2.informal informal_exec,
                a2.vinculada vinc_exec,a2.adm_central adm_exec,
                a3.sq_pessoa tit_exec,a4.sq_pessoa subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,      b.protocolo_siw,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then '---'
                               else 'Plano: '||b6.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                case when b.protocolo_siw is not null then dados_solic(b.protocolo_siw) end as dados_vinc,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,                      b1.envia_mail,
                b3.nome as nm_unid_origem, b3.sigla sg_unid_origem,
                case b5.padrao when 'S' then b4.nome||'-'||b4.co_uf else b4.nome||' ('||b5.nome||')' end as nm_cidade,
                b7.sq_siw_solicitacao as sq_emprestimo, b7.fim as devolucao_prevista,
                b8.sq_siw_solicitacao as sq_eliminacao, b8.codigo_interno as cd_eliminacao, b8.eliminacao as dt_eliminacao, 
                b8.sigla as sg_tramite_eliminacao,
                d.numero_original,    d.numero_documento,            d.ano,
                d.prefixo,            d.digito,                      d.interno,
                to_char(d.numero_documento)||'/'||substr(to_char(d.ano),3) as protocolo,
                d.prefixo||'.'||substr(1000000+d.numero_documento,2,6)||'/'||d.ano||'-'||substr(100+d.digito,2,2) as protocolo_completo,
                d.sq_especie_documento, d.sq_natureza_documento,     d.unidade_autuacao,
                d.data_autuacao,      d.pessoa_origem,               d.processo,
                d.circular,           d.copias,                      d.volumes,
                d.data_recebimento,   d.unidade_int_posse,           d.pessoa_ext_posse,
                d.tipo_juntada,       d.sq_caixa,                    d.pasta,
                d.data_setorial,      d.data_central,                d.observacao_setorial,
                case d.tipo_juntada when 'A' then 'Anexado' when 'P' then 'Apensado' end as nm_tipo_juntada,
                to_char(d.data_juntada, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_juntada,
                to_char(d.data_desapensacao,'DD/MM/YYYY, HH24:MI:SS') as phpdt_desapensacao,
                case d.processo when 'S' then 'Processo' else 'Documento' end as nm_tipo,
                case when d.pessoa_origem is null then b3.nome else d2.nome end as nm_origem,
                coalesce(d1.nome,'Irrestrito') as nm_natureza,       d1.sigla as sg_natureza,
                d1.descricao as ds_natureza,                         d1.ativo as st_natureza,
                d2.nome_resumido as nm_res_pessoa_origem,            d2.nome as nm_pessoa_origem,
                d3.sq_tipo_pessoa,                                   d3.nome as nm_tipo_pessoa,
                d4.sq_assunto,
                d5.codigo as cd_assunto,                             d5.descricao as ds_assunto,
                d5.detalhamento as dst_assunto,                      d5.observacao as ob_assunto,
                d7.nome as nm_especie,d7.sigla as sg_especie,        d7.ativo as st_especie,
                case d6.sigla when 'ANOS' then d5.fase_corrente_anos||' '||d6.descricao when 'NAPL' then '---' else d6.descricao end as guarda_corrente,
                case d8.sigla when 'ANOS' then d5.fase_intermed_anos||' '||d8.descricao when 'NAPL' then '---' else d8.descricao end as guarda_intermed,
                case d9.sigla when 'ANOS' then d5.fase_final_anos   ||' '||d9.descricao when 'NAPL' then '---' else d9.descricao end as guarda_final,
                da.descricao as destinacao_final,
                db.codigo as cd_assunto_pai, db.descricao as ds_assunto_pai,
                dc.codigo as cd_assunto_avo, dc.descricao as ds_assunto_avo,
                dd.codigo as cd_assunto_bis, dd.descricao as ds_assunto_bis,
                df.sq_pessoa as pessoa_interes, df.nome as nm_pessoa_interes,
                case when dg.sq_siw_solicitacao is null then null else dg.prefixo||'.'||substr(1000000+dg.numero_documento,2,6)||'/'||dg.ano||'-'||substr(100+dg.digito,2,2) end as protocolo_pai,
                dh.numero as nr_caixa, dh.assunto as as_caixa, dh.descricao as ds_caixa, dh.data_limite as dt_caixa, dh.intermediario as in_caixa,
                dh.destinacao_final as df_caixa, dh.arquivo_data, dh.sq_arquivo_local,
                montaNomeArquivoLocal(dh.sq_arquivo_local) as nm_arquivo_local,
                di.sq_unidade as sq_unid_caixa, di.sigla as sg_unid_caixa, di.nome as nm_unid_caixa,
                dj.sq_unidade_autua, dj.nm_unidade_autua, dj.sg_unidade_autua,
                cast(b.fim as date)-cast(k.dias_aviso as integer) aviso,
                e.sq_unidade as sq_unidade_resp,
                e.sq_tipo_unidade,    e.nome as nm_unidade_resp,     e.informal as informal_resp,
                e.vinculada as vinc_resp, e.adm_central as adm_resp, e.sigla as sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                g.sq_cc,              g.nome nm_cc,                  g.sigla sg_cc,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido as nm_solic, o.nome_resumido||' ('||o2.sigla||')' as nm_resp,
                coalesce(o1.ativo,'N') as st_sol,
                p.nome_resumido as nm_exec,
                q.nome as nm_unidade_posse,                          q.sigla as sg_unidade_posse,
                q1.sq_pessoa as titular,                                q2.sq_pessoa as substituto,
                r.nome as nm_pessoa_posse,                           r.nome_resumido as nm_res_pessoa_posse
           from siw_menu                                           a 
                   inner             join eo_unidade               a2 on (a.sq_unid_executora        = a2.sq_unidade)
                     left            join eo_unidade_resp          a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                                          a3.tipo_respons            = 'T'           and
                                                                          a3.fim                     is null
                                                                         )
                     left            join eo_unidade_resp          a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                          a4.tipo_respons            = 'S'           and
                                                                          a4.fim                     is null
                                                                         )
                   inner             join siw_modulo               a1 on (a.sq_modulo                = a1.sq_modulo)
                   inner             join siw_solicitacao          b  on (a.sq_menu                  = b.sq_menu)
                      inner          join siw_tramite              b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                      inner          join eo_unidade               b3 on (b.sq_unidade               = b3.sq_unidade)
                      inner          join co_cidade                b4 on (b.sq_cidade_origem         = b4.sq_cidade)
                        inner        join co_pais                  b5 on (b4.sq_pais                 = b5.sq_pais)
                      left           join pe_plano                 b6 on (b.sq_plano                 = b6.sq_plano)
                      left           join (select y.protocolo, y.sq_siw_solicitacao, x.fim
                                             from siw_solicitacao               x
                                                  inner join pa_emprestimo_item y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                                  inner join siw_tramite        z on (x.sq_siw_tramite     = z.sq_siw_tramite)
                                            where y.devolucao is null
                                              and z.sigla     not in ('CA','AT')
                                          )                        b7 on (b.sq_siw_solicitacao       = b7.protocolo)
                      left           join (select y.protocolo, x.codigo_interno, y.sq_siw_solicitacao, x.fim, z.sigla, y.eliminacao
                                             from siw_solicitacao          x
                                                  inner join pa_eliminacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                                  inner join siw_tramite   z on (x.sq_siw_tramite     = z.sq_siw_tramite and z.sigla <> 'CA')
                                          )                        b8 on (b.sq_siw_solicitacao       = b8.protocolo)
                      inner          join pa_documento             d  on (b.sq_siw_solicitacao        = d.sq_siw_solicitacao)
                        left         join pa_natureza_documento    d1 on (d.sq_natureza_documento    = d1.sq_natureza_documento)
                        left         join co_pessoa                d2 on (d.pessoa_origem            = d2.sq_pessoa)
                          left       join co_tipo_pessoa           d3 on (d2.sq_tipo_pessoa          = d3.sq_tipo_pessoa)
                        inner        join pa_documento_assunto     d4 on (d.sq_siw_solicitacao       = d4.sq_siw_solicitacao and
                                                                          d4.principal               = 'S'
                                                                         )
                          inner      join pa_assunto               d5 on (d4.sq_assunto              = d5.sq_assunto)
                            inner    join pa_tipo_guarda           d6 on (d5.fase_corrente_guarda    = d6.sq_tipo_guarda)
                            inner    join pa_tipo_guarda           d8 on (d5.fase_intermed_guarda    = d8.sq_tipo_guarda)
                            inner    join pa_tipo_guarda           d9 on (d5.fase_final_guarda       = d9.sq_tipo_guarda)
                            inner    join pa_tipo_guarda           da on (d5.destinacao_final        = da.sq_tipo_guarda)
                            left     join pa_assunto               db on (d5.sq_assunto_pai          = db.sq_assunto)
                              left   join pa_assunto               dc on (db.sq_assunto_pai          = dc.sq_assunto)
                                left join pa_assunto               dd on (dc.sq_assunto_pai          = dd.sq_assunto)
                        left         join pa_documento_interessado de on (d.sq_siw_solicitacao       = de.sq_siw_solicitacao and
                                                                          de.principal               = 'S'
                                                                         )
                          left       join co_pessoa                df on (de.sq_pessoa               = df.sq_pessoa)
                        left         join pa_documento             dg on (d.sq_documento_pai         = dg.sq_siw_solicitacao)
                        left         join pa_caixa                 dh on (d.sq_caixa                 = dh.sq_caixa)
                          left       join eo_unidade               di on (dh.sq_unidade              = di.sq_unidade)
                        left         join (select l.sq_siw_solicitacao, l.unidade_origem as sq_unidade_autua, 
                                                  m.nome as nm_unidade_autua, m.sigla as sg_unidade_autua
                                             from (select w.sq_siw_solicitacao, max(w.envio) as envio
                                                     from pa_documento_log              w
                                                          inner   join siw_solicitacao  x  on (w.sq_siw_solicitacao = x.sq_siw_solicitacao)
                                                          inner   join pa_tipo_despacho y  on (w.sq_tipo_despacho = y.sq_tipo_despacho)
                                                    where y.sigla   = 'AUTUAR'
                                                   group by w.sq_siw_solicitacao
                                                  )                                        k
                                                  inner   join pa_documento_log            l on (k.sq_siw_solicitacao = l.sq_siw_solicitacao and
                                                                                                 k.envio              = l.envio
                                                                                                )
                                                    inner join eo_unidade                  m on (l.unidade_origem     = m.sq_unidade)
                                          )                        dj on (d.sq_siw_solicitacao       = dj.sq_siw_solicitacao)
                        inner        join pa_especie_documento     d7 on (d.sq_especie_documento     = d7.sq_especie_documento)
                        inner        join eo_unidade               e  on (d.unidade_autuacao         = e.sq_unidade)
                          left       join eo_unidade_resp          e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                          e1.tipo_respons            = 'T'           and
                                                                          e1.fim                     is null
                                                                         )
                          left       join eo_unidade_resp          e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                          e2.tipo_respons            = 'S'           and
                                                                          e2.fim                     is null
                                                                         )
                        left         join eo_unidade               q  on (d.unidade_int_posse        = q.sq_unidade)
                          left       join eo_unidade_resp          q1 on (q.sq_unidade               = q1.sq_unidade and
                                                                          q1.tipo_respons            = 'T'           and
                                                                          q1.fim                     is null
                                                                         )
                          left       join eo_unidade_resp          q2 on (q.sq_unidade               = q2.sq_unidade and
                                                                          q2.tipo_respons            = 'S'           and
                                                                          q2.fim                     is null
                                                                         )
                        left         join co_pessoa                r  on (d.pessoa_ext_posse         = r.sq_pessoa)
                      left           join ct_cc                    g  on (b.sq_cc                    = g.sq_cc)
                      inner          join co_cidade                f  on (b.sq_cidade_origem         = f.sq_cidade)
                      left           join ct_cc                    n  on (b.sq_cc                    = n.sq_cc)
                      left           join co_pessoa                o  on (b.solicitante              = o.sq_pessoa)
                        left         join sg_autenticacao          o1 on (o.sq_pessoa                = o1.sq_pessoa)
                          left       join eo_unidade               o2 on (o1.sq_unidade              = o2.sq_unidade)
                      left           join co_pessoa                p  on (b.executor                 = p.sq_pessoa)
                      inner          join (select x.sq_siw_solicitacao, max(x.sq_siw_solic_log) as chave 
                                                  from siw_solic_log x
                                                       inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                                 where y.sq_menu = w_menu
                                                group by x.sq_siw_solicitacao
                                          )                        j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                     left            join pa_documento_log         k  on (j.chave                    = k.sq_siw_solic_log)
                       left          join sg_autenticacao          l  on (k.recebedor                = l.sq_pessoa)
          where b.sq_siw_solicitacao = p_chave;
   End If;
end SP_GetSolicDataPAD;
/
