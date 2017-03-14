create or replace procedure SP_GetSolicAC
   (p_menu         in number,
    p_pessoa       in number,
    p_restricao    in varchar2 default null,
    p_tipo         in number,
    p_ini_i        in date     default null,
    p_ini_f        in date     default null,
    p_fim_i        in date     default null,
    p_fim_f        in date     default null,
    p_atraso       in varchar2 default null,
    p_solicitante  in number   default null,
    p_unidade      in number   default null,
    p_prioridade   in number   default null,
    p_ativo        in varchar2 default null,
    p_proponente   in varchar2 default null,
    p_chave        in number   default null,
    p_assunto      in varchar2 default null,
    p_pais         in number   default null,
    p_regiao       in number   default null,
    p_uf           in varchar2 default null,
    p_cidade       in number   default null,
    p_usu_resp     in number   default null,
    p_uorg_resp    in number   default null,
    p_palavra      in varchar2 default null,
    p_prazo        in number   default null,
    p_fase         in varchar2 default null,
    p_sqcc         in number   default null,
    p_projeto      in number   default null,
    p_atividade    in number   default null,
    p_sq_acao_ppa  in varchar2 default null,
    p_sq_orprior   in number   default null,
    p_empenho      in varchar2 default null,
    p_processo     in varchar2 default null,
    p_result       out sys_refcursor) is
    
    l_item       varchar2(18);
    l_fase       varchar2(200) := p_fase ||',';
    x_fase       varchar2(200) := '';
    
    l_resp_unid  varchar2(10000) :='';
    
    -- cursor que recupera as unidades nas quais o usuário informado é titular ou substituto
    cursor c_unidades_resp is
      select distinct sq_unidade
        from eo_unidade a
      start with sq_unidade in (select sq_unidade
                                  from eo_unidade_resp b
                                 where b.sq_pessoa = p_pessoa
                                   and b.fim       is null)
      connect by prior sq_unidade = sq_unidade_pai;
      
begin
   If p_fase is not null Then
      Loop
         l_item  := Trim(substr(l_fase,1,Instr(l_fase,',')-1));
         If Length(l_item) > 0 Then
            x_fase := x_fase||','''||to_number(l_item)||'''';
         End If;
         l_fase := substr(l_fase,Instr(l_fase,',')+1);
         Exit when l_fase is null or instr(l_fase,',') = 0;
      End Loop;
      x_fase := substr(x_fase,2,200);
   End If;
   
   -- Monta uma string com todas as unidades subordinadas à que o usuário é responsável
   for crec in c_unidades_resp loop
     l_resp_unid := l_resp_unid ||','''||crec.sq_unidade||'''';
   end loop;
   
   If substr(p_restricao,1,2) = 'GC' Then
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
                a.envia_dia_util,     a.descricao,                   a.justificativa,
                a1.nome as nm_modulo, a1.sigla as sg_modulo,         a1.objetivo_geral,
                a2.sq_tipo_unidade as tp_exec,                       a2.nome as nm_unidade_exec, 
                a2.informal as informal_exec,                        a2.vinculada as vinc_exec,
                a2.adm_central as adm_exec,                          a2.sq_tipo_unidade,
                a2.informal,          a2.vinculada,                  a2.adm_central,
                a3.sq_pessoa as tit_exec,                            a4.sq_pessoa as subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,      b.sq_plano,                    b.protocolo_siw,
                b2.idcc,              b2.igcc,                       b2.valor_contrato,
                b2.saldo_contrato,
                case when b2.idcc < 75   then 'IDCC próximo da faixa desejável'
                     when b2.idcc <= 100 then 'IDCC na faixa desejável'
                     else                     'IDCC fora da faixa desejável'
                end as nm_idcc,
                case d1.exibe_idec 
                     when 'N' then null
                     else case when b2.idcc < 70   then 'IDEC fora da faixa desejável'
                               when b2.idcc < 90   then 'IDEC próximo da faixa desejável'
                               else                     'IDEC na faixa desejável'
                          end
                end as nm_idec,
                round(months_between(b.fim,b.inicio)) as meses_contrato,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then case when n.sq_cc is null
                                         then '???'
                                         else 'Classif: '||n.nome 
                                    end
                               else ' Plano: '||b3.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome as nm_tramite,            b1.ordem as or_tramite,
                b1.sigla as sg_tramite,  b1.ativo,                      b1.envia_mail,
                b5.sq_moeda,          b5.codigo cd_moeda,            b5.nome nm_moeda,
                b5.sigla sg_moeda,    b5.simbolo sb_moeda,           b5.ativo at_moeda,
                d.sq_tipo_acordo,     d.outra_parte,                 d.preposto,
                d.inicio as inicio_real, d.fim as fim_real,          d.duracao,
                d.valor_inicial,      coalesce(d8.valor,d.valor_atual) as valor_atual, b.codigo_interno,
                codigo2numero(b.codigo_interno) as ord_codigo_interno,
                b.codigo_externo,     d.objeto,                      d.atividades,
                d.produtos,           d.requisitos,                  d.observacao,
                d.dia_vencimento,     d.vincula_projeto,             d.vincula_demanda,
                d.vincula_viagem,     d.aviso_prox_conc,             d.dias_aviso,
                d.empenho,            d.processo,                    d.assinatura,
                d.publicacao,         d.sq_lcfonte_recurso,
                d.limite_variacao,    d.sq_especificacao_despesa,    d.indice_base,
                d.tipo_reajuste,
                case when b4.sq_siw_solicitacao is null then null else to_char(b4.numero_documento)||'/'||substr(to_char(b4.ano),3) end as protocolo,
                case when b4.sq_siw_solicitacao is null then null else to_char(b4.prefixo)||'.'||substr(1000000+to_char(b4.numero_documento),2,6)||'/'||to_char(b4.ano)||'-'||substr(100+to_char(b4.digito),2,2) end as protocolo_completo,
                retornaAfericaoIndicador(d.sq_eoindicador,d.indice_base) as vl_indice_base,
                round(months_between(d.fim,d.inicio)) as meses_acordo,
                case when b.titulo is null then 'Não informado ('||d2.nome_resumido||')' else b.titulo end as nm_acordo,
                acentos(b.titulo) as ac_titulo,
                case d.tipo_reajuste when 0 then 'Não permite' when 1 then 'Com índice' else 'Sem índice' end as nm_tipo_reajuste,
                d1.nome as nm_tipo_acordo,d1.sigla as sg_acordo,     d1.modalidade as cd_modalidade, d1.exibe_idec,
                d2.nome as nm_outra_parte,                           d2.nome_resumido as nm_outra_parte_resumido,
                d2.nome_indice as nm_outra_parte_ind,                d2.nome_resumido_ind as nm_outra_parte_resumido_ind,
                d21.cpf, d22.cnpj,
                d3.nome as nm_preposto,  d3.nome_resumido as nm_preposto_resumido,
                d4.sq_pessoa_conta,   d4.operacao,                   d4.numero as nr_conta,
                d4.devolucao_valor,
                d5.sq_agencia,        d5.codigo as cd_agencia,       d5.nome as nm_agencia,
                d6.sq_banco,          d6.codigo as cd_banco,         d6.nome as nm_banco,
                d7.sq_forma_pagamento,d7.nome as nm_forma_pagamento, d7.sigla as sg_forma_pagamento, 
                d7.ativo as st_forma_pagamento,
                d9.codigo as cd_lcfonte_recurso, d9.nome as nm_lcfonte_recurso, 
                da.codigo as cd_espec_despesa, da.nome as nm_espec_despesa,
                db.nome as nm_indicador, db.sigla as sg_indicador,
                cast(b.fim as date)-cast(d.dias_aviso as integer) as aviso,
                e.sq_unidade as sq_unidade_resp,
                e.sq_tipo_unidade,    e.nome as nm_unidade_resp,     e.informal as informal_resp,
                e.vinculada as vinc_resp,e.adm_central as adm_resp,  e.sigla as sg_unidade_resp,
                e1.sq_pessoa as titular, e2.sq_pessoa as substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                h.qtd as qtd_item,
                k1.sq_tipo_log, k1.nome as nm_tipo_log, k1.sigla as sg_tipo_log,
                m1.titulo as nm_projeto,
                n.sq_cc,              n.nome as nm_cc,               n.sigla as sg_cc,
                o.nome_resumido as nm_solic, o.nome_resumido||' ('||o2.sigla||')' as nm_resp,
                p.nome_resumido as nm_exec,
                q.sq_projeto_etapa, q.titulo as nm_etapa, MontaOrdem(q.sq_projeto_etapa,null) as cd_ordem,
                case when b.titulo is not null
                     then b.titulo
                     else d2.nome_resumido||' ('||to_char(b.inicio,'dd/mm/yy')||'-'||to_char(b.fim,'dd/mm/yy')||')' 
                end as titulo
           from siw_menu                                       a 
                   inner             join eo_unidade           a2 on (a.sq_unid_executora        = a2.sq_unidade)
                   inner             join siw_modulo           a1 on (a.sq_modulo                = a1.sq_modulo)
                   inner             join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                      inner          join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                      inner          join ac_acordo            d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                      inner          join (select x.sq_siw_solicitacao, 
                                                  acesso(x.sq_siw_solicitacao, p_pessoa,null) as acesso, 
                                                  calculaIDCC(x.sq_siw_solicitacao,null,null,null) as idcc, 
                                                  calculaIGCC(x.sq_siw_solicitacao,null,null) as igcc,
                                                  calculaValorContrato(x.sq_siw_solicitacao,null) as valor_contrato,
                                                  calculaSaldoContrato(x.sq_siw_solicitacao,null) as saldo_contrato
                                             from siw_solicitacao        x
                                                  inner join siw_menu    y on (x.sq_menu        = y.sq_menu and
                                                                               y.sq_menu        = p_menu
                                                                              )
                                          )                    b2 on (d.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                        inner        join co_forma_pagamento   d7 on (a.sq_pessoa                = d7.cliente and
                                                                      d.sq_forma_pagamento       = d7.sq_forma_pagamento
                                                                     )
                      left           join pa_documento         b4  on (b.protocolo_siw           = b4.sq_siw_solicitacao)
                      left           join co_moeda             b5 on (b.sq_moeda                 = b5.sq_moeda)
                     left            join eo_unidade_resp      a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                                      a3.tipo_respons            = 'T'           and
                                                                      a3.fim                     is null
                                                                     )
                     left            join eo_unidade_resp      a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                      a4.tipo_respons            = 'S'           and
                                                                      a4.fim                     is null
                                                                     )
                      left           join pe_plano             b3 on (a.sq_pessoa                = b3.cliente and
                                                                      b.sq_plano                 = b3.sq_plano
                                                                     )
                        left         join (select x.sq_siw_solicitacao, sum(z.valor) as valor
                                             from fn_lancamento                  y
                                                    inner   join siw_solicitacao z  on (y.sq_siw_solicitacao = z.sq_siw_solicitacao)
                                                      inner join siw_tramite     w  on (z.sq_siw_tramite     = w.sq_siw_tramite and
                                                                                        w.sigla              <> 'CA'
                                                                                       )
                                                  inner join ac_acordo_parcela   x  on (x.quitacao           is not null and
                                                                                        x.sq_acordo_parcela  = y.sq_acordo_parcela
                                                                                       )
                                            where y.sq_acordo_parcela  is not null
                                           group by x.sq_siw_solicitacao
                                          )                    d8 on (d.sq_siw_solicitacao       = d8.sq_siw_solicitacao)
                        inner        join ac_tipo_acordo       d1 on (a.sq_pessoa                = d1.cliente and
                                                                      d.sq_tipo_acordo           = d1.sq_tipo_acordo
                                                                     )
                        left         join co_pessoa            d2 on (d.outra_parte              = d2.sq_pessoa)
                          left       join co_pessoa_fisica    d21 on (d2.sq_pessoa               = d21.sq_pessoa)
                          left       join co_pessoa_juridica  d22 on (d2.sq_pessoa               = d22.sq_pessoa)
                        left         join co_pessoa_conta      d4 on (d.outra_parte              = d4.sq_pessoa and d4.padrao = 'S' and d4.ativo = 'S')
                          left       join co_agencia           d5 on (d4.sq_agencia              = d5.sq_agencia)
                          left       join co_banco             d6 on (d5.sq_banco                = d6.sq_banco)
                        left         join co_pessoa            d3 on (d.preposto                 = d3.sq_pessoa)
                        left         join lc_fonte_recurso     d9 on (a.sq_pessoa                = d9.cliente and
                                                                      d.sq_lcfonte_recurso       = d9.sq_lcfonte_recurso
                                                                     )
                        left         join ct_especificacao_despesa da on (d.sq_especificacao_despesa = da.sq_especificacao_despesa and
                                                                          d.sq_especificacao_despesa is not null
                                                                         )
                        left         join eo_indicador             db on (d.sq_eoindicador       = db.sq_eoindicador and
                                                                          d.sq_eoindicador       is not null
                                                                         )
                        left         join siw_solicitacao      dc  on (d.sq_solic_vinculo        = dc.sq_siw_solicitacao and
                                                                       d.sq_solic_vinculo        is not null
                                                                      )
                      inner          join eo_unidade           e  on (b.sq_unidade               = e.sq_unidade)
                        left         join eo_unidade_resp      e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                      e1.tipo_respons            = 'T'           and
                                                                      e1.fim                     is null
                                                                     )
                        left         join eo_unidade_resp      e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                      e2.tipo_respons            = 'S'           and
                                                                      e2.fim                     is null
                                                                     )
                      inner          join co_cidade            f  on (b.sq_cidade_origem         = f.sq_cidade)
                      left           join pj_projeto           m  on (d.sq_solic_vinculo         = m.sq_siw_solicitacao)
                        left         join siw_solicitacao      m1 on (m.sq_siw_solicitacao       = m1.sq_siw_solicitacao)
                      left           join ct_cc                n  on (b.sq_cc                    = n.sq_cc and
                                                                      b.sq_cc                    is not null
                                                                     )
                      inner          join co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                        inner        join sg_autenticacao      o1 on (o.sq_pessoa                = o1.sq_pessoa)
                          inner      join eo_unidade           o2 on (o1.sq_unidade              = o2.sq_unidade)
                      left           join co_pessoa            p  on (b.executor                 = p.sq_pessoa and
                                                                     	b.executor                 is not null
                                                                      )
                   left              join (select x.sq_siw_solicitacao, count(x.sq_solicitacao_item) as qtd
                                             from cl_solicitacao_item x
                                           group by x.sq_siw_solicitacao
                                          )                    h  on (b.sq_siw_solicitacao       = h.sq_siw_solicitacao)                               
                   left              join pj_etapa_contrato    i  on (b.sq_siw_solicitacao       = i.sq_siw_solicitacao)
                      left           join pj_projeto_etapa     q  on (i.sq_projeto_etapa         = q.sq_projeto_etapa)                   
                   inner             join (select x.sq_siw_solicitacao, max(x.sq_siw_solic_log) as chave 
                                             from siw_solic_log              x
                                                  inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                            where y.sq_menu = p_menu
                                           group by x.sq_siw_solicitacao
                                          )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                     left            join ac_acordo_log        k  on (j.chave                    = k.sq_siw_solic_log)
                       left          join siw_tipo_log         k1 on (k.sq_tipo_log              = k1.sq_tipo_log)
                       left          join sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
          where a.sq_menu        = p_menu
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
            and (p_pais           is null or (p_pais        is not null and f.sq_pais            = p_pais))
            and (p_regiao         is null or (p_regiao      is not null and f.sq_regiao          = p_regiao))
            and (p_cidade         is null or (p_cidade      is not null and f.sq_cidade          = p_cidade))
            and (p_usu_resp       is null or (p_usu_resp    is not null and (b.executor          = p_usu_resp or 0 < (select count(*) from ac_acordo_log where destinatario = p_usu_resp and sq_siw_solicitacao = b.sq_siw_solicitacao))))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and b.conclusao          is null and l.sq_unidade = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc              = p_sqcc))
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and d.sq_tipo_acordo     in (select sq_tipo_acordo
                                                                                                       from ac_tipo_acordo
                                                                                                     connect by prior sq_tipo_acordo = sq_tipo_acordo_pai
                                                                                                     start with sq_tipo_acordo = p_sq_orprior
                                                                                                    )
                                             )
                )
            and (p_projeto        is null or (p_projeto     is not null and (b.sq_solic_pai      = p_projeto or d.sq_solic_vinculo  = p_projeto)))
            and (p_atividade      is null or (p_atividade   is not null and i.sq_projeto_etapa   = p_atividade))
            and (p_uf             is null or (p_uf          is not null and f.co_uf              = p_uf))
            and (p_assunto        is null or (p_assunto     is not null and acentos(d.objeto,null) like '%'||acentos(p_assunto,null)||'%'))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (p_prazo          is null or (p_prazo       is not null and b.conclusao          is null and cast(cast(b.fim as date)-cast(sysdate as date) as integer)+1 <=p_prazo))
            and (p_ini_i          is null or (p_ini_i       is not null and d.inicio             between p_ini_i and p_ini_f))
            and (p_fim_i          is null or (p_fim_i       is not null and d.fim                between p_fim_i and p_fim_f))
            and (p_unidade        is null or (p_unidade     is not null and b.sq_unidade         = p_unidade))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and (p_palavra        is null or (p_palavra     is not null and b.codigo_interno     like '%'||p_palavra||'%'))
            and (p_atraso         is null or (p_atraso      is not null and (b.titulo is not null and acentos(b.titulo)   like '%'||acentos(p_atraso)||'%' or
                                                                             b.titulo is     null and d2.nome_resumido_ind||')'=substr(p_atraso,instr(p_atraso,'(')+1)
                                                                            )
                                             )
                )
            and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and ((p_sq_acao_ppa = 'IDCC PRÓXIMO DA FAIXA DESEJÁVEL' and b2.idcc <   75 ) or
                                                                             (p_sq_acao_ppa = 'IDCC NA FAIXA DESEJÁVEL'         and b2.idcc >=  75 and b2.idcc <= 100 ) or
                                                                             (p_sq_acao_ppa = 'IDCC FORA DA FAIXA DESEJÁVEL'    and b2.idcc >  100 ) or
                                                                             (p_sq_acao_ppa = 'IDEC FORA DA FAIXA DESEJÁVEL'    and b2.idcc <   70 ) or
                                                                             (p_sq_acao_ppa = 'IDEC PRÓXIMO DA FAIXA DESEJÁVEL' and b2.idcc >=  70 and b2.idcc < 90 ) or
                                                                             (p_sq_acao_ppa = 'IDEC NA FAIXA DESEJÁVEL'         and b2.idcc >=  90 )
                                                                            )
                                             )
                )
            and (p_proponente     is null or (p_proponente  is not null and (acentos(d2.nome,null)          like '%'||acentos(p_proponente,null)||'%') or 
                                                                            (acentos(d2.nome_resumido,null) like '%'||acentos(p_proponente,null)||'%')
                                             )
                )
            and (p_empenho        is null or (p_empenho     is not null and upper(d.empenho)     = upper(p_empenho)))
            and (p_processo       is null or (p_processo    is not null and upper(d.processo)    = upper(p_processo)))
            and ((p_tipo         = 1     and b1.sigla = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and b1.sigla <> 'CI' and b.conclusao is null and (a.destinatario = 'S' and b.executor = p_pessoa) or (a.destinatario = 'N' and b2.acesso > 15)) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and b1.sigla <> 'CA'  and b2.acesso > 0) or
                 (p_tipo         = 4     and b1.sigla <> 'CA'  and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b.conclusao is null and b1.ativo = 'S' and b2.acesso > 0 and b1.sigla <> 'CI')
                )
            and ((instr(p_restricao,'PROJ')    = 0 and
                  instr(p_restricao,'ETAPA')   = 0 and
                  instr(p_restricao,'PROP')    = 0 and
                  instr(p_restricao,'IDEC')    = 0 and
                  instr(p_restricao,'RESPATU') = 0 and
                  instr(p_restricao,'FONTE')   = 0 and
                  instr(p_restricao,'ESPEC')   = 0 and
                  substr(p_restricao,4,2)      <>'CC'
                 ) or 
                 ((instr(p_restricao,'PROJ')    > 0    and (b.sq_solic_pai is not null or d.sq_solic_vinculo is not null)) or
                  (instr(p_restricao,'ETAPA')   > 0    and MontaOrdem(q.sq_projeto_etapa,null)  is not null) or                 
                  (instr(p_restricao,'PROP')    > 0    and d.outra_parte  is not null) or
                  (instr(p_restricao,'IDEC')    > 0    and d1.exibe_idec = 'S') or
                  (instr(p_restricao,'RESPATU') > 0    and b.executor     is not null) or
                  (instr(p_restricao,'FONTE')   > 0    and d.sq_lcfonte_recurso is not null) or
                  (instr(p_restricao,'ESPEC')   > 0    and d.sq_especificacao_despesa is not null) or
                  (substr(p_restricao,4,2)      ='CC'  and b.sq_cc        is not null)
                 )
                );
   End If;
end SP_GetSolicAC;
/
