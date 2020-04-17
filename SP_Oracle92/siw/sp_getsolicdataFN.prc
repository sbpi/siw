create or replace procedure SP_GetSolicDataFN
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
   
   If substr(p_restricao,1,2) = 'FN' Then
      -- Recupera os acordos que o usuário pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao as ds_menu,        a.justificativa as just_menu,
                a1.nome as nm_modulo, a1.sigla as sg_modulo,         a1.objetivo_geral,
                a2.sq_tipo_unidade as tp_exec,                       a2.nome as nm_unidade_exec, 
                a2.informal as informal_exec,                        a2.vinculada as vinc_exec,
                a2.adm_central as adm_exec,
                (select sq_pessoa from eo_unidade_resp where sq_unidade = a2.sq_unidade and tipo_respons = 'T' and fim is null) as tit_exec,
                (select sq_pessoa from eo_unidade_resp where sq_unidade = a2.sq_unidade and tipo_respons = 'S' and fim is null) as subst_exec,
                b.sq_siw_solicitacao, b.solicitante,                 b.cadastrador,
                b.executor,           b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.opiniao,            b.sq_solic_pai,                b.valor,
                b.sq_unidade,         b.sq_cidade_origem,            b.palavra_chave,
                b.sq_solic_apoio,     b.data_autorizacao,            b.texto_autorizacao,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then '---'
                               else 'Plano: '||b3.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b4.sq_solic_pai as sq_solic_avo,
                case when b4.sq_solic_pai is null 
                     then case when b4.sq_plano is null
                               then case when b6.sq_cc is null
                                         then '???'
                                         else 'Classif: '||b6.nome 
                                    end
                               else ' Plano: '||b5.titulo
                          end
                     else dados_solic(b4.sq_solic_pai) 
                end as dados_avo,
                case when b.protocolo_siw is null 
                     then case when b4.protocolo_siw is null
                               then null
                               else b9.sq_siw_solicitacao
                          end
                     else b8.sq_siw_solicitacao 
                end as protocolo_siw,
                case when b.protocolo_siw is null 
                     then case when b4.protocolo_siw is null
                               then d.processo
                               else to_char(b9.prefixo)||'.'||substr(1000000+to_char(b9.numero_documento),2,6)||'/'||to_char(b9.ano)||'-'||substr(100+to_char(b9.digito),2,2)
                          end
                     else to_char(b8.prefixo)||'.'||substr(1000000+to_char(b8.numero_documento),2,6)||'/'||to_char(b8.ano)||'-'||substr(100+to_char(b8.digito),2,2)
                end as processo,
                b1.sq_siw_tramite,    b1.nome as nm_tramite,         b1.ordem as or_tramite,
                b1.sigla as sg_tramite, b1.ativo,                    b1.envia_mail,
                ba.entidade,
                d.pessoa,             b.codigo_interno,              d.sq_acordo_parcela,
                d.sq_forma_pagamento, d.sq_tipo_lancamento,          d.sq_tipo_pessoa,
                d.emissao,            d.vencimento,                  d.quitacao,
                b.codigo_externo,     d.observacao,                  d.sq_projeto_rubrica,
                d.aviso_prox_conc,    d.tipo as tipo_rubrica,        d.sq_solic_vinculo,
                d.dias_aviso,         d.sq_forma_pagamento,          d.sq_agencia,
                d.operacao_conta,     d.numero_conta,                d.sq_pais_estrang,
                d.aba_code,           d.swift_code,                  d.endereco_estrang,
                d.banco_estrang,      d.agencia_estrang,             d.cidade_estrang,
                d.informacoes,        d.codigo_deposito,             d.condicoes_pagamento,
                d.valor_imposto,      d.valor_retencao,              d.valor_liquido,
                d.referencia_inicio,  d.referencia_fim,              d.sq_rubrica_cronograma,
                d.sq_pessoa_conta,    d.cc_debito,                   d.cc_credito,
                d.cc_pessoa,          dj.nome cc_pessoa_nome,        dj.nome_resumido cc_pessoa_nome_res,
                d.cc_data,            to_char(d.cc_data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_cc_data,
                case d.tipo when 1 then 'Dotação incial' when 2 then 'Transferência entre rubricas' when 3 then 'Atualização de aplicação' when 4 then 'Entradas' when 5 then 'Saídas' end nm_tipo_rubrica,
                d1.receita,           d1.despesa,                    d1.nome as nm_tipo_lancamento,
                d2.nome as nm_pessoa, d2.nome_resumido as nm_pessoa_resumido,
                d4.nome as nm_forma_pagamento, d4.sigla as sg_forma_pagamento, d4.ativo as st_forma_pagamento,
                di.sq_pessoa_conta as conta_credito,
                di2.sq_moeda sq_moeda_benef,  di2.codigo cd_moeda_benef,  di2.nome nm_moeda_benef,
                di2.sigla    sg_moeda_benef,  di2.simbolo sb_moeda_benef, di2.ativo at_moeda_benef,
                d5.codigo as cd_agencia, d5.nome as nm_agencia,
                d6.sq_banco,          d6.codigo as cd_banco,         d6.nome as nm_banco,
                d6.exige_operacao,
                d7.nome as nm_pais,
                d8.nome as nm_tipo_pessoa,
                coalesce((select sum(x.valor) from fn_lancamento_doc x where x.sq_siw_solicitacao = d.sq_siw_solicitacao and x.sq_acordo_nota is null),0) as valor_doc,
                coalesce((select sum(x.valor) from fn_lancamento_doc x where x.sq_siw_solicitacao = d.sq_siw_solicitacao and x.sq_acordo_nota is not null),0) as valor_nota,
                coalesce((select count(*)     from fn_lancamento_doc x where x.sq_siw_solicitacao = d.sq_siw_solicitacao and x.sq_acordo_nota is not null),0) as qtd_nota,
                case when d.sq_acordo_parcela is null then 0
                     else coalesce((select count(*)
                                      from ac_acordo_parcela          zx
                                           inner join ac_parcela_nota zz on (zx.sq_acordo_parcela = zz.sq_acordo_parcela)
                                      where zz.sq_acordo_parcela = d.sq_acordo_parcela
                                   ),0)
                end as notas_parcela,
                da1.sq_lancamento_doc,   da1.numero nr_doc, da1.data dt_doc, da1.valor vl_doc,
                dc.operacao as oper_org, dc.numero as nr_conta_org,
                dc1.sq_moeda sq_moeda_cc, dc1.codigo  cd_moeda_cc, dc1.nome  nm_moeda_cc,
                dc1.sigla    sg_moeda_cc, dc1.simbolo sb_moeda_cc, dc1.ativo at_moeda_cc,
                dd.codigo as cd_age_org, dd.nome as nm_age_org,
                de.codigo as cd_ban_org, de.nome as nm_ban_org,
                de.exige_operacao as exige_oper_org,
                df.sq_imposto, df.solic_origem,
                case coalesce(df.sq_siw_solicitacao,0) when 0 then 'N' else 'S' end as lancamento_vinculado,
                coalesce((select sum(za.valor_total)
                            from fn_imposto_doc                    za
                                 inner     join fn_lancamento_doc  zb on (za.sq_lancamento_doc  = zb.sq_lancamento_doc)
                                 inner     join fn_imposto         zg on (za.sq_imposto         = zg.sq_imposto)
                                 inner     join siw_solicitacao    zh on (za.solic_imposto      = zh.sq_siw_solicitacao)
                                   inner   join siw_tramite        zj on (zh.sq_siw_tramite     = zj.sq_siw_tramite and zj.sigla <> 'CA')
                           where zg.calculo            > 0
                             and zb.sq_siw_solicitacao = d.sq_siw_solicitacao
                         ),0) as vl_abatimento,
                coalesce((select sum(case zg.tipo when 'A' then za.valor else -1*za.valor end)
                            from fn_documento_valores              za
                                 inner     join fn_lancamento_doc  zb on (za.sq_lancamento_doc  = zb.sq_lancamento_doc)
                                   inner   join siw_solicitacao    zd on (zb.sq_siw_solicitacao = zd.sq_siw_solicitacao)
                                     inner join siw_tramite        zj on (zd.sq_siw_tramite     = zj.sq_siw_tramite and zj.sigla <> 'CA')
                                 inner     join fn_valores         zg on (za.sq_valores         = zg.sq_valores)
                           where zb.sq_siw_solicitacao = d.sq_siw_solicitacao
                         ),0) as vl_outros,
                cast(b.fim as date)-cast(d.dias_aviso as integer) as aviso,
                e.sq_tipo_unidade,    e.nome as nm_unidade_resp,     e.informal as informal_resp,
                e.vinculada as vinc_resp,e.adm_central as adm_resp,  e.sigla as sg_unidade_resp,
                (select sq_pessoa from eo_unidade_resp where sq_unidade = e.sq_unidade and tipo_respons = 'T' and fim is null) as titular,
                (select sq_pessoa from eo_unidade_resp where sq_unidade = e.sq_unidade and tipo_respons = 'S' and fim is null) as substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                f.nome nm_cidade,
                m1.codigo_interno as cd_acordo,
                case when m.sq_siw_solicitacao is null then 0
                     else coalesce((select count(*) from ac_acordo_nota where sq_siw_solicitacao = m.sq_siw_solicitacao),0)
                end as notas_acordo,
                mo.sq_moeda,          mo.codigo  cd_moeda,           mo.nome  nm_moeda,
                mo.sigla sg_moeda,    mo.simbolo sb_moeda,           mo.ativo at_moeda,
                n.sq_cc,              n.nome as nm_cc,               n.sigla as sg_cc,
                o.nome_resumido as nm_solic,                         o.nome_resumido||' ('||o2.sigla||')' as nm_resp,
                p.nome_resumido as nm_exec,
                case coalesce(m2.sq_siw_solicitacao,0) when 0 then q2.titulo             else m5.titulo end as nm_projeto,
                case coalesce(m2.sq_siw_solicitacao,0) when 0 then q.sq_siw_solicitacao else m2.sq_siw_solicitacao end as sq_projeto,
                case coalesce(m3.sq_siw_solicitacao,0) when 0 then q1.qtd_rubrica       else m3.qtd_rubrica        end as qtd_rubrica
           from siw_menu                                    a 
                inner             join eo_unidade           a2 on (a.sq_unid_executora        = a2.sq_unidade)
                inner             join siw_modulo           a1 on (a.sq_modulo                = a1.sq_modulo)
                inner             join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                   inner          join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                   left           join pe_plano             b3 on (b.sq_plano                 = b3.sq_plano)
                   left           join siw_solicitacao      b4 on (b.sq_solic_pai             = b4.sq_siw_solicitacao)
                     left         join pe_plano             b5 on (b4.sq_plano                = b5.sq_plano)
                     left         join ct_cc                b6 on (b4.sq_cc                   = b6.sq_cc)
                     left         join pa_documento         b9 on (b4.protocolo_siw           = b9.sq_siw_solicitacao)
                     left         join pa_documento         b8 on (b.protocolo_siw            = b8.sq_siw_solicitacao)
                   left           join siw_solic_apoio      ba on (b.sq_solic_apoio           = ba.sq_solic_apoio)
                   inner          join fn_lancamento        d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                   left           join fn_lancamento_doc   da1 on (b.sq_siw_solicitacao       = da1.sq_siw_solicitacao)
                     inner        join fn_tipo_lancamento   d1 on (d.sq_tipo_lancamento       = d1.sq_tipo_lancamento)
                     inner        join co_forma_pagamento   d4 on (d.sq_forma_pagamento       = d4.sq_forma_pagamento)
                     inner        join co_tipo_pessoa       d8 on (d.sq_tipo_pessoa           = d8.sq_tipo_pessoa)
                     left         join co_pessoa            d2 on (d.pessoa                   = d2.sq_pessoa)
                     left         join co_agencia           d5 on (d.sq_agencia               = d5.sq_agencia)
                       left       join co_banco             d6 on (d5.sq_banco                = d6.sq_banco)
                     left         join co_pais              d7 on (d.sq_pais_estrang          = d7.sq_pais)
                     left         join co_pessoa_conta      dc on (d.sq_pessoa_conta          = dc.sq_pessoa_conta)
                       left       join co_agencia           dd on (dc.sq_agencia              = dd.sq_agencia)
                         left     join co_banco             de on (dd.sq_banco                = de.sq_banco)
                       left       join co_moeda            dc1 on (dc.sq_moeda                = dc1.sq_moeda)
                     left         join co_pessoa_conta      di on (d.pessoa                   = di.sq_pessoa and
                                                                   d.sq_agencia               = di.sq_agencia and
                                                                   coalesce(d.operacao_conta,'-') = coalesce(di.operacao,'-') and
                                                                   d.numero_conta             = di.numero and
                                                                   ((a.sigla = 'FNATRANSF' and d.sq_pessoa_conta <> di.sq_pessoa_conta) or
                                                                    (a.sigla ='FNAAPLICA' and d.sq_pessoa_conta =  di.sq_pessoa_conta) or
                                                                    a.sigla not in ('FNATRANSF','FNAAPLICA')
                                                                   )
                                                                  )
                       left       join co_agencia          di1 on (di.sq_agencia              = di1.sq_agencia)
                         left     join co_banco           di11 on (di1.sq_banco               = di11.sq_banco)
                       left       join co_moeda            di2 on (di.sq_moeda                = di2.sq_moeda)
                     left         join co_pessoa            dj on (d.cc_pessoa                = dj.sq_pessoa)
                     left         join (select y.sq_siw_solicitacao, x1.sq_siw_solicitacao as solic_origem, x.sq_imposto
                                          from fn_imposto_doc                x
                                               inner join fn_lancamento_doc x1 on (x.sq_lancamento_doc = x1.sq_lancamento_doc)
                                               inner join siw_solicitacao    y on (x.solic_retencao = y.sq_siw_solicitacao or 
                                                                                   x.solic_imposto  = y.sq_siw_solicitacao
                                                                                  )
                                               inner join siw_menu           z on (y.sq_menu        = z.sq_menu and
                                                                                   z.sigla          = 'FNDEVENT'
                                                                                  )
                                       )                    df on (d.sq_siw_solicitacao       = df.sq_siw_solicitacao)
                   inner          join eo_unidade           e  on (b.sq_unidade               = e.sq_unidade)
                   inner          join co_cidade            f  on (b.sq_cidade_origem         = f.sq_cidade)
                   left           join ac_acordo            m  on (b.sq_solic_pai             = m.sq_siw_solicitacao)
                     left         join siw_solicitacao      m1 on (m.sq_siw_solicitacao       = m1.sq_siw_solicitacao)
                       left       join pj_projeto           m2 on (m1.sq_solic_pai            = m2.sq_siw_solicitacao)
                         left     join siw_solicitacao      m5 on (m2.sq_siw_solicitacao      = m5.sq_siw_solicitacao)
                         left     join (select x.sq_siw_solicitacao, count(x.sq_projeto_rubrica) as qtd_rubrica
                                          from pj_rubrica                 x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                        group by x.sq_siw_solicitacao
                                       )                    m3 on (m2.sq_siw_solicitacao      = m3.sq_siw_solicitacao)
                   left           join co_moeda             mo on (b.sq_moeda                 = mo.sq_moeda)
                   left           join pj_projeto           q  on (b.sq_solic_pai             = q.sq_siw_solicitacao or
                                                                   d.sq_solic_vinculo         = q.sq_siw_solicitacao
                                                                  )
                     left         join siw_solicitacao      q2 on (q.sq_siw_solicitacao       = q2.sq_siw_solicitacao)
                     left         join (select x.sq_siw_solicitacao, count(x.sq_projeto_rubrica) as qtd_rubrica
                                          from pj_rubrica                 x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                        group by x.sq_siw_solicitacao
                                       )                    q1 on (q.sq_siw_solicitacao       = q1.sq_siw_solicitacao)
                   left           join ct_cc                n  on (b.sq_cc                    = n.sq_cc)
                   inner          join co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                     inner        join sg_autenticacao      o1 on (o.sq_pessoa                = o1.sq_pessoa)
                       inner      join eo_unidade           o2 on (o1.sq_unidade              = o2.sq_unidade)
                   left           join co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                inner             join (select x.sq_siw_solicitacao, max(x.sq_siw_solic_log) as chave 
                                          from siw_solic_log              x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                         where y.sq_menu = w_menu
                                        group by x.sq_siw_solicitacao
                                       )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                  left            join gd_demanda_log       k  on (j.chave                    = k.sq_siw_solic_log)
                    left          join sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
          where b.sq_siw_solicitacao       = p_chave;
   End If;
end SP_GetSolicDataFN;
/
