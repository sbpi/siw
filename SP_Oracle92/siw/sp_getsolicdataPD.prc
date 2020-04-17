create or replace procedure SP_GetSolicDataPD
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
   
   If substr(p_restricao,1,2) = 'PD' Then
      -- Recupera as passagens que o usuário pode ver
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
                a3.sq_pessoa as tit_exec,                            a31.nome as nm_tit_exec,
                a4.sq_pessoa as subst_exec,                
                a3.sq_pessoa as tit_exec,                            a4.sq_pessoa as subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,                     b.observacao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,      b.protocolo_siw,
                to_char(b.inclusao,'dd/mm/yyyy, hh24:mi:ss')  as phpdt_inclusao,
                to_char(b.inicio,'dd/mm/yyyy, hh24:mi:ss')    as phpdt_inicio,
                to_char(b.fim,'dd/mm/yyyy, hh24:mi:ss')       as phpdt_fim,
                to_char(b.conclusao,'dd/mm/yyyy, hh24:mi:ss') as phpdt_conclusao,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then '---'
                               else 'Plano: '||b3.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome as nm_tramite,         b1.ordem as or_tramite,
                b1.sigla as sg_tramite, b1.ativo,                    b1.envia_mail,
                case when b4.sq_siw_solicitacao is null then null else b4.prefixo||'.'||substr(1000000+b4.numero_documento,2,6)||'/'||b4.ano||'-'||substr(100+b4.digito,2,2) end as protocolo,
                d.sq_unidade_resp,    d.assunto,                     d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                case d.prioridade when 0 then 'Alta' when 1 then 'Média' else 'Normal' end as nm_prioridade,
                d.ordem,
                d1.sq_pessoa as sq_prop, d1.tipo as tp_missao,       d11.codigo_interno,
                d1.reserva,           d1.pta,                        d1.justificativa_dia_util,
                d1.emissao_bilhete,   d1.pagamento_diaria,           d1.pagamento_bilhete,
                d1.boletim_numero,    d1.boletim_data,               d1.valor_alimentacao,
                d1.valor_transporte,  d1.desconto_alimentacao,       d1.desconto_transporte,
                d1.valor_adicional,   d1.tipo as tipo_missao,
                d1.sq_pais_estrang,   d1.aba_code,                   d1.swift_code,
                d1.endereco_estrang,  d1.banco_estrang,              d1.agencia_estrang,
                d1.cidade_estrang,    d1.informacoes,                d1.codigo_deposito,
                d1.numero_conta,      d1.operacao_conta,             d1.cidade_estrang,
                d1.informacoes,       d1.sq_pdvinculo_bilhete,       d1.sq_pdvinculo_reembolso,
                d1.valor_passagem,    d1.passagem,                   d1.diaria,
                d1.hospedagem,        d1.veiculo,                    d1.valor_previsto_bilhetes,
                d1.cumprimento,       d1.relatorio,                  d1.sq_relatorio_viagem, 
                d1.reembolso,         d1.reembolso_valor,            d1.reembolso_observacao,
                d1.ressarcimento,     d1.ressarcimento_valor,        d1.ressarcimento_observacao, 
                d1.sq_moeda_ressarcimento, d13.codigo cd_moeda_ressarcimento,  d13.sigla sg_moeda_ressarcimento, d13.simbolo sb_moeda_ressarcimento,
                d1.ressarcimento_data,d1.sq_pdvinculo_ressarcimento, d1.sq_arquivo_comprovante,
                d1.nacional,          d1.internacional,              d1.deposito_identificado,
                d1.cotacao_valor,     d1.cotacao_observacao,         d1.diaria_fim_semana,
                case d1.passagem    when 'S' then 'Sim' else 'Não' end as nm_passagem,
                case d1.hospedagem  when 'S' then 'Sim' else 'Não' end as nm_hospedagem,
                case d1.veiculo     when 'S' then 'Sim' else 'Não' end as nm_veiculo,
                case d1.reembolso   when 'S' then 'Sim' else 'Não' end as nm_reembolso,
                case d1.cumprimento when 'I' then 'Não' when 'P' then 'Sim' when 'C' then 'Cancelada' else 'Não informada' end as nm_cumprimento,
                d2.nome nm_prop,      d2.nome_resumido nm_prop_res, d2.sq_tipo_pessoa,
                coalesce(d21.ativo,'N') st_prop,
                d3.sq_tipo_vinculo,   d3.nome nm_tipo_vinculo,      d3.interno, d3.contratado,
                d4.sexo,              d4.cpf,
                d1.sq_moeda_cotacao,  d10.codigo cd_moeda_cotacao,  d10.sigla sg_moeda_cotacao, d10.simbolo sb_moeda_cotacao,
                d1.sq_moeda_complemento, d12.codigo cd_moeda_complemento,  d12.sigla sg_moeda_complemento, d12.simbolo sb_moeda_complemento,
                d22.sq_forma_pagamento, d22.nome as nm_forma_pagamento, d22.sigla as sg_forma_pagamento,
                d23.nome as pais_estrang,
                d51.sq_projeto_rubrica, d51.codigo as cd_rubrica,    d51.nome as nm_rubrica,
                d52.sq_tipo_lancamento, d52.nome   as nm_lancamento, d52.descricao as ds_lancamento,
                db1.sq_projeto_rubrica as sq_rubrica_reemb,   db1.codigo as cd_rubrica_reemb,    db1.nome as nm_rubrica_reemb,
                db2.sq_tipo_lancamento as sq_lancamento_reemb, db2.nome   as nm_lancamento_reemb, db2.descricao as ds_lancamento_reemb,
                dc1.sq_projeto_rubrica as sq_rubrica_ressarc,   dc1.codigo as cd_rubrica_ressarc,    dc1.nome as nm_rubrica_ressarc,
                dc2.sq_tipo_lancamento as sq_lancamento_ressarc, dc2.nome   as nm_lancamento_ressarc, dc2.descricao as ds_lancamento_ressarc,
                d6.sq_agencia,        d6.codigo cd_agencia,          d6.nome nm_agencia,
                d7.sq_banco,          d7.codigo cd_banco,            d7.nome nm_banco,
                d7.exige_operacao,
                d8.sq_posto_trabalho, d8.sq_posto_trabalho,          d8.sq_modalidade_contrato,
                d8.matricula,
                d9.nome as nm_diaria,
                da.nome_original as nm_arquivo, da.descricao as ds_arquivo,   da.caminho as cm_arquivo,
                dd.nome_original as nm_arquivo_comprovante, dd.descricao as ds_arquivo_comprovante,   dd.caminho as cm_arquivo_comprovante,
                cast(b.fim as date)-cast(d.dias_aviso as integer) aviso,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,        e.sigla as sg_unidade_resp,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,       e12.nome nm_titular,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                g.sq_cc,              g.nome nm_cc,                  g.sigla sg_cc,
                o.nome_resumido nm_solic, o.nome_resumido||' ('||o2.sigla||')' nm_resp,
                coalesce(o1.ativo,'N') st_sol,
                p.nome_resumido nm_exec,
                soma_dias(a.sq_pessoa, b.inicio, (-1*case d1.internacional when 'S' then a11.dias_antecedencia_int else a11.dias_antecedencia end), 'U') as limite_envio,
                soma_dias(a.sq_pessoa, trunc(sysdate), (case d1.internacional when 'S' then a11.dias_antecedencia_int else a11.dias_antecedencia end), 'U') as envio_regular,
                case d1.internacional when 'S' then a11.dias_antecedencia_int else a11.dias_antecedencia end as dias_antecedencia,
                case trunc(b.fim) when soma_dias(a.sq_pessoa,b.inicio,trunc(b.fim)-trunc(b.inicio),'U') then 'N' else 'S' end as fim_semana,
                d9.valor_complemento, d1.complemento_qtd, d1.complemento_base, d1.complemento_valor                  
           from siw_menu                                               a
                inner                join pd_parametro              a11 on (a.sq_pessoa                   = a11.cliente)
                inner                join eo_unidade                 a2 on (a.sq_unid_executora           = a2.sq_unidade)
                  left               join eo_unidade_resp            a3 on (a2.sq_unidade                 = a3.sq_unidade   and
                                                                            a3.tipo_respons               = 'T'             and
                                                                            a3.fim                        is null)
                    left             join co_pessoa                 a31 on (a3.sq_pessoa                  = a31.sq_pessoa)
                  left               join eo_unidade_resp            a4 on (a2.sq_unidade                 = a4.sq_unidade   and
                                                                            a4.tipo_respons               = 'S'             and
                                                                            a4.fim                        is null)
                inner                join siw_modulo                 a1 on (a.sq_modulo                   = a1.sq_modulo)
                inner                join siw_solicitacao            b  on (a.sq_menu                     = b.sq_menu)
                  inner              join siw_tramite                b1 on (b.sq_siw_tramite              = b1.sq_siw_tramite)
                  left               join pe_plano                   b3 on (b.sq_plano                    = b3.sq_plano)
                  left               join pa_documento               b4 on (b.protocolo_siw               = b4.sq_siw_solicitacao)
                  inner              join gd_demanda                 d  on (b.sq_siw_solicitacao          = d.sq_siw_solicitacao)
                    inner            join pd_missao                  d1 on (d.sq_siw_solicitacao          = d1.sq_siw_solicitacao)
                      left           join co_moeda                  d10 on (d1.sq_moeda_cotacao           = d10.sq_moeda)
                      inner          join siw_solicitacao           d11 on (d1.sq_siw_solicitacao         = d11.sq_siw_solicitacao)
                      left           join co_moeda                  d12 on (d1.sq_moeda_complemento       = d12.sq_moeda)
                      left           join co_moeda                  d13 on (d1.sq_moeda_ressarcimento     = d13.sq_moeda)
                      inner          join co_pessoa                  d2 on (d1.sq_pessoa                  = d2.sq_pessoa)
                        left         join sg_autenticacao           d21 on (d2.sq_pessoa                  = d21.sq_pessoa)
                        inner        join co_tipo_vinculo            d3 on (d2.sq_tipo_vinculo            = d3.sq_tipo_vinculo)
                        left         join co_pessoa_fisica           d4 on (d2.sq_pessoa                  = d4.sq_pessoa)
                          left       join gp_contrato_colaborador    d8 on (d4.cliente                    = d8.cliente      and
                                                                            d4.sq_pessoa                  = d8.sq_pessoa    and
                                                                            d8.fim                        is null)
                      left           join pd_vinculo_financeiro      d5 on (d1.sq_pdvinculo_bilhete       = d5.sq_pdvinculo_financeiro)
                        left         join pj_rubrica                d51 on (d5.sq_projeto_rubrica         = d51.sq_projeto_rubrica)
                        left         join fn_tipo_lancamento        d52 on (d5.sq_tipo_lancamento         = d52.sq_tipo_lancamento)
                      left           join pd_vinculo_financeiro      db on (d1.sq_pdvinculo_reembolso     = db.sq_pdvinculo_financeiro)
                        left         join pj_rubrica                db1 on (db.sq_projeto_rubrica         = db1.sq_projeto_rubrica)
                        left         join fn_tipo_lancamento        db2 on (db.sq_tipo_lancamento         = db2.sq_tipo_lancamento)
                      left           join pd_vinculo_financeiro      dc on (d1.sq_pdvinculo_ressarcimento = dc.sq_pdvinculo_financeiro)
                        left         join pj_rubrica                dc1 on (dc.sq_projeto_rubrica         = dc1.sq_projeto_rubrica)
                        left         join fn_tipo_lancamento        dc2 on (dc.sq_tipo_lancamento         = dc2.sq_tipo_lancamento)
                      left           join co_forma_pagamento        d22 on (d1.sq_forma_pagamento         = d22.sq_forma_pagamento)
                      left           join co_pais                   d23 on (d1.sq_pais_estrang            = d23.sq_pais)
                      left           join co_agencia                 d6 on (d1.sq_agencia                 = d6.sq_agencia)
                        left         join co_banco                   d7 on (d6.sq_banco                   = d7.sq_banco)
                      left           join pd_categoria_diaria        d9 on (d1.diaria                     = d9.sq_categoria_diaria)
                      left           join siw_arquivo                da on (d1.sq_relatorio_viagem        = da.sq_siw_arquivo)
                      left           join siw_arquivo                dd on (d1.sq_arquivo_comprovante     = dd.sq_siw_arquivo)
                    inner            join eo_unidade                 e  on (d.sq_unidade_resp             = e.sq_unidade)
                      left           join eo_unidade_resp            e1 on (e.sq_unidade                  = e1.sq_unidade   and
                                                                            e1.tipo_respons               = 'T'             and
                                                                            e1.fim                        is null)
                        left         join co_pessoa                 e12 on (e1.sq_pessoa                  = e12.sq_pessoa)
                      left           join eo_unidade_resp            e2 on (e.sq_unidade                  = e2.sq_unidade   and
                                                                            e2.tipo_respons               = 'S'             and
                                                                            e2.fim                        is null)
                  inner              join co_cidade                  f  on (b.sq_cidade_origem            = f.sq_cidade)
                  left               join ct_cc                      g  on (b.sq_cc                       = g.sq_cc)
                  left               join co_pessoa                  o  on (b.solicitante                 = o.sq_pessoa)
                    left             join sg_autenticacao            o1 on (o.sq_pessoa                   = o1.sq_pessoa)
                      left           join eo_unidade                 o2 on (o1.sq_unidade                 = o2.sq_unidade)
                  left               join co_pessoa                  p  on (b.executor                    = p.sq_pessoa)
                  inner              join (select x.sq_siw_solicitacao, max(x.sq_siw_solic_log) as chave 
                                             from siw_solic_log x
                                                  inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                            where y.sq_menu = w_menu
                                           group by x.sq_siw_solicitacao
                                          )                          j on (b.sq_siw_solicitacao          = j.sq_siw_solicitacao)
                    left             join gd_demanda_log             k on (j.chave                       = k.sq_siw_solic_log)
                      left           join sg_autenticacao            l on (k.destinatario                = l.sq_pessoa)
          where b.sq_siw_solicitacao = p_chave;          
   End If;
end SP_GetSolicDataPD;
/
