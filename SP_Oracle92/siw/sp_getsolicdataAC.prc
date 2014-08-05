create or replace procedure SP_GetSolicDataAC
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
                a.envia_dia_util,     a.descricao as ds_menu,        a.justificativa as just_menu,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec,                          a2.nome nm_unidade_exec,
                a2.informal informal_exec,                           a2.vinculada vinc_exec,
                a2.adm_central adm_exec,                             a2.sq_tipo_unidade,
                a2.informal,          a2.vinculada,                  a2.adm_central,
                a3.sq_pessoa tit_exec,a4.sq_pessoa subst_exec,
                a5.dias_pagamento,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,      b.protocolo_siw,
                calculaIDCC(b.sq_siw_solicitacao,null,null,null) as idcc, calculaIGCC(b.sq_siw_solicitacao,null,null) as igcc,
                calculaValorContrato(b.sq_siw_solicitacao,null) as valor_contrato,
                calculaSaldoContrato(b.sq_siw_solicitacao,null) as saldo_contrato,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then '---'
                               else 'Plano: '||b3.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,                      b1.envia_mail,
                case when b4.sq_siw_solicitacao is null then null else to_char(b4.numero_documento)||'/'||substr(to_char(b4.ano),3) end as protocolo,
                case when b4.sq_siw_solicitacao is null then null else to_char(b4.prefixo)||'.'||substr(1000000+to_char(b4.numero_documento),2,6)||'/'||to_char(b4.ano)||'-'||substr(100+to_char(b4.digito),2,2) end as protocolo_completo,
                b5.sq_moeda,          b5.codigo cd_moeda,            b5.nome nm_moeda,
                b5.sigla sg_moeda,    b5.simbolo sb_moeda,           b5.ativo at_moeda,
                d.sq_tipo_acordo,     d.outra_parte,                 d.preposto,
                d.inicio inicio_real, d.fim fim_real,                d.duracao,
                d.valor_inicial,      d.valor_atual,                 b.codigo_interno,
                b.codigo_externo,     d.objeto,                      d.atividades,
                d.produtos,           d.requisitos,                  d.observacao,
                d.dia_vencimento,     d.vincula_projeto,             d.vincula_demanda,
                d.vincula_viagem,     d.aviso_prox_conc,             d.dias_aviso,
                case d.vincula_projeto when 'S' then 'Sim' else 'Não' end nm_vincula_projeto,
                case d.vincula_demanda when 'S' then 'Sim' else 'Não' end nm_vincula_demanda,
                case d.vincula_viagem  when 'S' then 'Sim' else 'Não' end nm_vincula_viagem,
                d.sq_tipo_pessoa,     d.sq_forma_pagamento,          d.sq_agencia,
                d.operacao_conta,     d.numero_conta,                d.sq_pais_estrang,
                d.aba_code,           d.swift_code,                  d.endereco_estrang,
                d.banco_estrang,      d.agencia_estrang,             d.cidade_estrang,
                d.inicio,             d.informacoes,                 d.codigo_deposito,
                d.empenho,            d.processo,                    d.assinatura,
                d.publicacao,         b.titulo,                      d.sq_lcmodalidade,
                d.numero_certame,     d.numero_ata,                  d.tipo_reajuste,
                d.indice_base,        d.sq_eoindicador,              d.limite_variacao,
                d.sq_lcfonte_recurso, d.sq_especificacao_despesa,    d.financeiro_unico,
                d.prestacao_contas,   d.pagina_diario_oficial,       d.condicoes_pagamento,
                d.valor_caucao,
                case d.prestacao_contas when 'S' then 'Sim' else 'Não' end as nm_prestacao_contas,
                retornaAfericaoIndicador(d.sq_eoindicador,d.indice_base) as vl_indice_base,
                retornaExcedenteContrato(d.sq_siw_solicitacao,b.fim) as limite_usado,
                case d.tipo_reajuste when 0 then 'Não permite' when 1 then 'Com índice' else 'Sem índice' end nm_tipo_reajuste,
                d1.nome as nm_tipo_acordo,d1.sigla as sg_acordo,     d1.modalidade as cd_modalidade, d1.exibe_idec,
                d1.prazo_indeterm,    d1.pessoa_fisica,              d1.pessoa_juridica,  
                d2.nome nm_outra_parte, d2.nome_resumido nm_outra_parte_resumido,
                d3.nome nm_outra_parte, d3.nome_resumido nm_outra_parte_resumido,
                d4.nome nm_forma_pagamento, d4.sigla sg_forma_pagamento, d4.ativo st_forma_pagamento,
                d5.codigo cd_agencia, d5.nome nm_agencia,
                d6.sq_banco,          d6.codigo cd_banco,            d6.nome nm_banco,
                d6.exige_operacao,
                d7.nome nm_pais,
                d8.nome nm_lcmodalidade,
                d9.nome nm_eoindicador,
                d10.nome nm_lcfonte_recurso,
                d11.nome nm_espec_despesa,
                d12.aditivo,
                d13.aditivo aditivo_excedente,
                d14.aditivo aditivo_prorrogacao,
                dc.sq_siw_solicitacao as sq_compra, coalesce(dd.numero_certame,dc.codigo_interno) as cd_compra,
                cast(b.fim as date)-cast(d.dias_aviso as integer) aviso,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla as sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                f.nome nm_cidade,
                m2.titulo nm_projeto,
                n.sq_cc,              n.nome as nm_cc,                  n.sigla as sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' as nm_resp,
                coalesce(o1.ativo,'N') as st_sol,
                p.nome_resumido as nm_exec,
                i.sq_projeto_etapa,   i1.titulo as nm_etapa,
                coalesce(m1.qtd_rubrica,0) as qtd_rubrica
           from siw_menu                                    a 
                inner        join eo_unidade                a2 on (a.sq_unid_executora        = a2.sq_unidade)
                  left       join eo_unidade_resp           a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                                   a3.tipo_respons            = 'T'           and
                                                                   a3.fim                     is null
                                                                  )
                  left       join eo_unidade_resp           a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                   a4.tipo_respons            = 'S'           and
                                                                   a4.fim                     is null
                                                                  )
                inner             join siw_modulo           a1 on (a.sq_modulo                = a1.sq_modulo)
                inner             join ac_parametro         a5 on (a.sq_pessoa                = a5.cliente)
                inner             join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                   left           join pa_documento         b4  on (b.protocolo_siw           = b4.sq_siw_solicitacao)
                   inner          join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                   left           join pe_plano             b3 on (b.sq_plano                 = b3.sq_plano)
                   left           join co_moeda             b5 on (b.sq_moeda                 = b5.sq_moeda)
                   inner          join ac_acordo            d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                     inner        join ac_tipo_acordo       d1 on (d.sq_tipo_acordo           = d1.sq_tipo_acordo)
                     inner        join co_forma_pagamento   d4 on (d.sq_forma_pagamento       = d4.sq_forma_pagamento)
                     left         join co_pessoa            d2 on (d.outra_parte              = d2.sq_pessoa)
                     left         join co_pessoa            d3 on (d.preposto                 = d3.sq_pessoa)
                     left         join co_agencia           d5 on (d.sq_agencia               = d5.sq_agencia)
                       left       join co_banco             d6 on (d5.sq_banco                = d6.sq_banco)
                     left         join co_pais              d7 on (d.sq_pais_estrang          = d7.sq_pais)
                     left         join lc_modalidade        d8 on (d.sq_lcmodalidade          = d8.sq_lcmodalidade)
                     left         join eo_indicador         d9 on (d.sq_eoindicador           = d9.sq_eoindicador)
                     left         join lc_fonte_recurso    d10 on (d.sq_lcfonte_recurso       = d10.sq_lcfonte_recurso)
                     left         join ct_especificacao_despesa d11 on (d.sq_especificacao_despesa = d11.sq_especificacao_despesa)
                     left         join (select x.sq_siw_solicitacao, count(x.sq_acordo_aditivo) as aditivo
                                          from ac_acordo_aditivo          x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                         where y.sq_menu = w_menu
                                           and (x.prorrogacao = 'S' or x.revisao = 'S' or x.acrescimo = 'S' or x.supressao = 'S')
                                        group by x.sq_siw_solicitacao
                                       )                    d12 on (d.sq_siw_solicitacao       = d12.sq_siw_solicitacao)
                     left         join (select x.sq_siw_solicitacao, count(x.sq_acordo_aditivo) as aditivo
                                          from ac_acordo_aditivo          x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                         where y.sq_menu = w_menu
                                           and (x.acrescimo = 'S' or x.supressao = 'S')
                                        group by x.sq_siw_solicitacao
                                       )                    d13 on (d.sq_siw_solicitacao       = d13.sq_siw_solicitacao)                                       
                     left         join (select x.sq_siw_solicitacao, count(x.sq_acordo_aditivo) as aditivo
                                          from ac_acordo_aditivo          x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                         where y.sq_menu = w_menu
                                           and x.prorrogacao = 'S'
                                        group by x.sq_siw_solicitacao
                                       )                    d14 on (d.sq_siw_solicitacao       = d14.sq_siw_solicitacao)
                     left         join siw_solicitacao      dc on (d.sq_solic_compra          = dc.sq_siw_solicitacao)
                       left       join cl_solicitacao       dd on (dc.sq_siw_solicitacao      = dd.sq_siw_solicitacao)
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
                   left           join pj_projeto           m  on (b.sq_solic_pai             = m.sq_siw_solicitacao)
                     left         join (select x.sq_siw_solicitacao, count(x.sq_projeto_rubrica) qtd_rubrica
                                          from pj_rubrica                 x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                        group by x.sq_siw_solicitacao
                                       )                    m1 on (m.sq_siw_solicitacao       = m1.sq_siw_solicitacao)
                     left         join siw_solicitacao      m2 on (m.sq_siw_solicitacao       = m2.sq_siw_solicitacao)
                   left           join ct_cc                n  on (b.sq_cc                    = n.sq_cc)
                   left           join co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                     inner        join sg_autenticacao      o1 on (o.sq_pessoa                = o1.sq_pessoa)
                       inner      join eo_unidade           o2 on (o1.sq_unidade              = o2.sq_unidade)
                   left           join co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                left         join pj_etapa_contrato         i  on (b.sq_siw_solicitacao  = i.sq_siw_solicitacao)
                  left       join pj_projeto_etapa          i1 on (i.sq_projeto_etapa    = i1.sq_projeto_etapa)                
                inner             join (select x.sq_siw_solicitacao, max(x.sq_siw_solic_log) chave 
                                          from siw_solic_log              x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                         where y.sq_menu = w_menu
                                        group by x.sq_siw_solicitacao
                                       )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                  left            join gd_demanda_log       k  on (j.chave                    = k.sq_siw_solic_log)
                    left          join sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
          where b.sq_siw_solicitacao       = p_chave;
   End If;
end SP_GetSolicDataAC;
/
