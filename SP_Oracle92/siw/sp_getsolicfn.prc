create or replace procedure SP_GetSolicFN
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
    
    w_cliente    number(18);
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
   -- Recupera a chave do cliente
   select sq_pessoa_pai into w_cliente from co_pessoa where sq_pessoa = p_pessoa;
   
   If p_fase is not null Then
      Loop
         l_item  := Trim(substr(l_fase,1,Instr(l_fase,',')-1));
         If Length(l_item) > 0 Then
            x_fase := x_fase||','''||to_number(l_item)||'''';
         End If;
         l_fase := substr(l_fase,Instr(l_fase,',')+1,200);
         Exit when l_fase is null or instr(l_fase,'1') = 0;
      End Loop;
      x_fase := substr(x_fase,2,200);
   End If;
   
   -- Monta uma string com todas as unidades subordinadas à que o usuário é responsável
   for crec in c_unidades_resp loop
     l_resp_unid := l_resp_unid ||','''||crec.sq_unidade||'''';
   end loop;
   
   If p_restricao = 'FILHOS' Then
      open p_result for
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.ordem as or_servico,         a.sq_unid_executora,  
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao,                   a.justificativa,
                a1.nome as nm_modulo, a1.sigla as sg_modulo,         a1.ordem as or_modulo,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            coalesce(b2.quitacao, b.conclusao) as conclusao,
                b.valor,              b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,      dados_solic(b.sq_siw_solicitacao) as dados_solic,
                coalesce(b.codigo_interno,to_char(b.sq_siw_solicitacao)) as codigo_interno,
                codigo2numero(coalesce(b.codigo_interno,to_char(b.sq_siw_solicitacao))) as ord_codigo_interno,
                coalesce(b.codigo_interno,b.titulo,to_char(b.sq_siw_solicitacao)) as titulo,
                b.titulo as ac_titulo,
                b1.sq_siw_tramite,    b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,                      b1.envia_mail mail_tramite,
                case a.sigla when 'FNDVIA'
                             then case when b2.quitacao >= trunc(sysdate) then 'Agendado' else b1.nome end
                             else b1.nome 
                end as nm_tramite,
                calculaIGE(b.sq_siw_solicitacao) as ige, calculaIDE(b.sq_siw_solicitacao,null,null)  as ide,
                calculaIGC(b.sq_siw_solicitacao) as igc, calculaIDC(b.sq_siw_solicitacao,null,null)  as idc,
                o.nome_resumido as nm_solic, o.nome_resumido_ind as nm_solic_ind
           from siw_menu                                      a
                inner          join siw_modulo                a1 on (a.sq_modulo           = a1.sq_modulo)
                inner          join siw_solicitacao           b  on (a.sq_menu             = b.sq_menu)
                  inner        join siw_tramite               b1 on (b.sq_siw_tramite      = b1.sq_siw_tramite and b1.sigla <> 'CA')
                    left       join fn_lancamento             b2 on (b.sq_siw_solicitacao  = b2.sq_siw_solicitacao)
                    left       join co_pessoa                 o  on (b.solicitante         = o.sq_pessoa)
                      inner    join sg_autenticacao           o1 on (o.sq_pessoa           = o1.sq_pessoa)
                        inner  join eo_unidade                o2 on (o1.sq_unidade         = o2.sq_unidade)
                  inner        join siw_solicitacao           c  on (b.sq_solic_pai        = c.sq_siw_solicitacao)
                    inner      join siw_menu                  c1 on (c.sq_menu             = c1.sq_menu)
                      inner    join siw_modulo                c2 on (c1.sq_modulo          = c2.sq_modulo)
          where (c2.sigla = 'PD' or (c2.sigla <> 'PD' and b1.ativo = 'S'))
            and a1.sigla            <> 'GD'
            and substr(a.sigla,1,3) <> 'GDP'
            and b.sq_tipo_evento    is null
            and b.sq_solic_pai      =  p_chave;
   Elsif p_restricao is null or substr(p_restricao,1,2) = 'FN' Then
      -- Recupera os acordos que o usuário pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.sq_unid_executora,  
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a1.nome as nm_modulo, a1.sigla as sg_modulo,
                a2.sq_tipo_unidade,   a2.nome as nm_unidade_exec,    a2.informal,
                a2.vinculada,         a2.adm_central,
                a2.sq_tipo_unidade as tp_exec, a2.nome as nm_unidade_exec, a2.informal as informal_exec,
                a2.vinculada as vinc_exec,a2.adm_central as adm_exec,
                a3.sq_pessoa as tit_exec,a4.sq_pessoa as subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.opiniao,            b.sq_solic_pai,
                b.sq_unidade,         b.sq_cidade_origem,            b.palavra_chave,
                b.sq_plano,           coalesce(di.valor,0)+coalesce(dh.valor,0)-coalesce(dg.valor,0) as valor,
                coalesce(b.protocolo_siw, b4.protocolo_siw) as protocolo_siw,
                to_char(b.inclusao,'dd/mm/yyyy, hh24:mi:ss') as phpdt_inclusao,
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
                case b1.sigla when 'AT' then b.valor else 0 end as valor_atual,
                b1.sq_siw_tramite,    b1.nome as nm_tramite,         b1.ordem as or_tramite,
                b1.sigla as sg_tramite,  b1.ativo,                   b1.envia_mail mail_tramite,
                b2.acesso,
                case when b.protocolo_siw is null 
                     then case when b4.protocolo_siw is null
                               then d.processo
                               else to_char(b9.numero_documento)||'/'||substr(to_char(b9.ano),3,2)
                          end
                     else to_char(b8.numero_documento)||'/'||substr(to_char(b8.ano),3,2) 
                end as protocolo,
                codigo2numero(b.codigo_interno) ord_codigo_interno,
                d.pessoa,             b.codigo_interno,              d.sq_acordo_parcela,
                d.sq_forma_pagamento, d.sq_tipo_lancamento,          d.sq_tipo_pessoa,
                d.emissao,            d.vencimento,                  d.quitacao,
                b.codigo_externo,     d.observacao,                  d.valor_imposto,
                d.valor_retencao,     d.valor_liquido,               d.aviso_prox_conc,
                d.dias_aviso,         d.sq_tipo_pessoa,              d.tipo as tipo_rubrica,
                d.referencia_inicio,  d.referencia_fim,              d.sq_solic_vinculo,
                d.numero_conta,       d.processo,
                coalesce(d.quitacao, d.vencimento) as dt_pagamento,
                d1.nome as nm_tipo_lancamento,
                case d.tipo when 1 then 'Dotação inicial' when 2 then 'Transferência entre rubricas' when 3 then 'Atualização de aplicação' when 4 then 'Entradas' else 'Normal' end as nm_tipo_rubrica,
                d2.nome as nm_pessoa, d2.nome_resumido as nm_pessoa_resumido,
                d2.nome_indice as nm_pessoa_ind,                     d2.nome_resumido_ind as nm_pessoa_resumido_ind,
                d21.cpf,              d22.cnpj,
                coalesce(d3.valor,0) as valor_doc,                   d3.numero as nr_doc,
                d31.sigla as sg_doc,  d31.nome as nm_doc,            d3.data as dt_doc,
                d4.sq_pessoa_conta,   d4.operacao,                   d4.numero as nr_conta,
                d4.devolucao_valor,
                d5.sq_agencia,        d5.codigo as cd_agencia,       d5.nome as nm_agencia,
                d6.sq_banco,          d6.codigo as cd_banco,         d6.nome as nm_banco,

                g.sq_pessoa_conta sq_conta_debito, g.op_conta operacao_debito,      g.nr_conta conta_debito,
                g.cb_sq_moeda sq_moeda_cc,         g.cb_cd_moeda cd_moeda_cc,       g.cb_nm_moeda nm_moeda_cc,
                g.cb_sg_moeda sg_moeda_cc,         g.cb_sb_moeda sb_moeda_cc,       g.cb_at_moeda at_moeda_cc,
                g.ag_conta sq_agencia_debito,      g.ag_cd_conta cd_agencia_debito, g.ag_nm_conta nm_agencia_debito,
                g.bc_conta sq_banco_debito,        g.bc_cd_conta cd_banco_debito,   g.bc_nm_conta nm_banco_debito,
                g.cb_sq_moeda,                     g.cb_sg_moeda,                   g.cb_sb_moeda,           g.valor cb_valor,

                d7.sq_forma_pagamento,case substr(a.sigla,3,1) when 'R' then null else d7.nome end as nm_forma_pagamento, d7.sigla as sg_forma_pagamento, 
                d7.ativo as st_forma_pagamento,
                cast(b.fim as date)-cast(d.dias_aviso as integer) as aviso,
                e.sq_tipo_unidade,    e.nome as nm_unidade_resp,     e.informal as informal_resp,
                e.vinculada as vinc_resp,e.adm_central as adm_resp,  e.sigla as sg_unidade_resp,
                e1.sq_pessoa as titular, e2.sq_pessoa as substituto,
                case when m4.qtd is null then 'S' else 'N' end as usuario_logado, -- Se igual a S, somente o usuário logado participou da tramitação 
                mo.sq_moeda,          mo.codigo  cd_moeda,           mo.nome  nm_moeda,
                mo.sigla sg_moeda,    mo.simbolo sb_moeda,           mo.ativo at_moeda,
                n.sq_cc,              n.nome as nm_cc,               n.sigla as sg_cc,
                o.nome_resumido as nm_solic, o.nome_resumido||' ('||o2.sigla||')' as nm_resp,
                p.nome_resumido as nm_exec
           from siw_modulo                                     a1
                inner                join siw_menu             a  on (a1.sq_modulo               = a.sq_modulo and
                                                                      a.sq_pessoa                = w_cliente
                                                                     )
                   inner             join eo_unidade           a2 on (a.sq_unid_executora        = a2.sq_unidade)
                   inner             join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                      inner          join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                      inner          join fn_lancamento        d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                        inner        join co_forma_pagamento   d7 on (d.sq_forma_pagamento       = d7.sq_forma_pagamento)
                        inner        join fn_tipo_lancamento   d1 on (d.sq_tipo_lancamento       = d1.sq_tipo_lancamento)
                      inner          join (select z.sq_siw_solicitacao, acesso(z.sq_siw_solicitacao, p_pessoa) as acesso
                                             from fn_lancamento                z
                                                  inner   join siw_solicitacao y on (z.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                                    inner join siw_menu        x on (y.sq_menu            = x.sq_menu)
                                            where z.cliente = w_cliente
                                              and (p_menu   is null or (p_menu is not null and x.sq_menu = p_menu))
                                          )                    b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                      inner          join eo_unidade           e  on (b.sq_unidade               = e.sq_unidade)
                      inner          join (select z.sq_siw_solicitacao, max(z1.sq_siw_solic_log) as chave
                                             from fn_lancamento              z
                                                  inner join siw_solic_log  z1 on (z.sq_siw_solicitacao = z1.sq_siw_solicitacao)
                                            where z.cliente = w_cliente
                                           group by z.sq_siw_solicitacao
                                          )                    j  on (d.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                      left           join (select z.sq_siw_solicitacao, count(z1.sq_siw_solic_log) as qtd
                                             from fn_lancamento              z
                                                  inner join siw_solic_log  z1 on (z.sq_siw_solicitacao = z1.sq_siw_solicitacao and
                                                                                   z1.sq_pessoa         <> p_pessoa
                                                                                  )
                                            where z.cliente = w_cliente
                                              and (p_chave is null or (p_chave is not null and z.sq_siw_solicitacao = p_chave))
                                           group by z.sq_siw_solicitacao
                                          )                    m4  on (d.sq_siw_solicitacao      = m4.sq_siw_solicitacao)
                     left       join eo_unidade_resp           a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                                      a3.tipo_respons            = 'T'           and
                                                                      a3.fim                     is null
                                                                     )
                     left       join eo_unidade_resp           a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                      a4.tipo_respons            = 'S'           and
                                                                      a4.fim                     is null
                                                                     )
                      left           join vw_conta_bancaria_financeiro g on (d.sq_siw_solicitacao = g.sq_financeiro and
                                                                             d.sq_pessoa_conta    = g.sq_pessoa_conta
                                                                            )
                      left           join pe_plano             b3 on (b.sq_plano                 = b3.sq_plano)
                      left           join siw_solicitacao      b4 on (b.sq_solic_pai             = b4.sq_siw_solicitacao)
                        left         join siw_menu            b41 on (b4.sq_menu                 = b41.sq_menu)
                        left         join pe_plano             b5 on (b4.sq_plano                = b5.sq_plano)
                        left         join ct_cc                b6 on (b4.sq_cc                   = b6.sq_cc)
                        left         join pa_documento         b9 on (b4.protocolo_siw           = b9.sq_siw_solicitacao)
                        left         join pa_documento         b8 on (b.protocolo_siw            = b8.sq_siw_solicitacao)
                        left         join siw_solicitacao      b7 on (b4.sq_solic_pai            = b7.sq_siw_solicitacao)
                        left         join co_pessoa            d2 on (d.pessoa                   = d2.sq_pessoa)
                          left       join co_pessoa_fisica    d21 on (d2.sq_pessoa               = d21.sq_pessoa)
                          left       join co_pessoa_juridica  d22 on (d2.sq_pessoa               = d22.sq_pessoa)
                        left         join fn_lancamento_doc    d3 on (d.sq_siw_solicitacao       = d3.sq_siw_solicitacao) 
                          left       join fn_tipo_documento   d31 on (d3.sq_tipo_documento       = d31.sq_tipo_documento)
                        left         join co_pessoa_conta      d4 on (d.pessoa                   = d4.sq_pessoa and
                                                                      d.sq_agencia               = d4.sq_agencia and
                                                                      coalesce(d.operacao_conta,'-') = coalesce(d4.operacao,'-') and
                                                                      d.numero_conta             = d4.numero and
                                                                      ((a.sigla = 'FNATRANSF' and d.sq_pessoa_conta <> d4.sq_pessoa_conta) or
                                                                       (a.sigla = 'FNAAPLICA' and d.sq_pessoa_conta =  d4.sq_pessoa_conta) or
                                                                       a.sigla not in ('FNATRANSF','FNAAPLICA')
                                                                      )
                                                                     )
                        left         join co_agencia           d5 on (d.sq_agencia               = d5.sq_agencia)
                        left         join co_banco             d6 on (d5.sq_banco                = d6.sq_banco)
                        left        join (select zb.sq_siw_solicitacao, sum(za.valor_total) as valor
                                            from fn_imposto_doc                    za
                                                 inner     join fn_lancamento_doc  zb on (za.sq_lancamento_doc  = zb.sq_lancamento_doc)
                                                 inner     join fn_imposto         zg on (za.sq_imposto         = zg.sq_imposto and zg.cliente = w_cliente)
                                                 inner     join siw_solicitacao    zh on (za.solic_imposto      = zh.sq_siw_solicitacao)
                                                   inner   join fn_lancamento      zi on (zh.sq_siw_solicitacao = zi.sq_siw_solicitacao)
                                                   inner   join siw_tramite        zj on (zh.sq_siw_tramite     = zj.sq_siw_tramite and zj.sigla <> 'CA')
                                           where zg.calculo   > 0
                                             and (p_chave   is null or (p_chave is not null and zb.sq_siw_solicitacao = p_chave))
                                          group by zb.sq_siw_solicitacao
                                         )                     dg on (d.sq_siw_solicitacao       = dg.sq_siw_solicitacao)
                        left        join (select zb.sq_siw_solicitacao, sum(case zg.tipo when 'A' then za.valor else -1*za.valor end) as valor
                                            from fn_documento_valores              za
                                                 inner     join fn_lancamento_doc  zb on (za.sq_lancamento_doc  = zb.sq_lancamento_doc)
                                                 inner     join fn_valores         zg on (za.sq_valores         = zg.sq_valores)
                                           where zg.cliente = w_cliente
                                             and (p_chave    is null or (p_chave is not null and zb.sq_siw_solicitacao = p_chave))
                                          group by zb.sq_siw_solicitacao
                                         )                     dh on (d.sq_siw_solicitacao       = dh.sq_siw_solicitacao)
                        left        join (select zb.sq_siw_solicitacao, sum(zb.valor) as valor
                                            from fn_lancamento_doc  zb
                                           where (p_chave    is null or (p_chave is not null and zb.sq_siw_solicitacao = p_chave))
                                          group by zb.sq_siw_solicitacao
                                         )                     di on (d.sq_siw_solicitacao       = di.sq_siw_solicitacao)
                        left         join eo_unidade_resp      e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                      e1.tipo_respons            = 'T'           and
                                                                      e1.fim                     is null
                                                                     )
                        left         join eo_unidade_resp      e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                      e2.tipo_respons            = 'S'           and
                                                                      e2.fim                     is null
                                                                     )
                      left           join co_moeda             mo on (b.sq_moeda                 = mo.sq_moeda)
                      left           join ct_cc                n  on (b.sq_cc                    = n.sq_cc)
                      left           join co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                        left         join sg_autenticacao      o1 on (o.sq_pessoa                = o1.sq_pessoa)
                          left       join eo_unidade           o2 on (o1.sq_unidade              = o2.sq_unidade)
                      left           join co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                     left            join fn_lancamento_log    k  on (j.chave                    = k.sq_siw_solic_log)
                       left          join sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
          where ((p_tipo = 1 and b1.sigla = 'EE' and b2.acesso > 0 and (w_cliente <> 10135 or (w_cliente = 10135 and a.sigla <> 'FNDCONT'))) or
                 (p_tipo = 2 and b1.sigla = 'AT' and d.quitacao>=trunc(sysdate) and (w_cliente <> 10135 or (w_cliente = 10135 and a.sigla <> 'FNDCONT'))) or
                 (p_tipo = 3 and b1.sigla <> 'CA' and b2.acesso > 0) or
                 (p_tipo = 3 and b1.sigla <> 'CA' and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo = 4 and b1.sigla <> 'CA'  and b2.acesso > 0) or
                 (p_tipo = 4 and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo = 5) or
                 (p_tipo = 6 and b1.ativo          = 'S' and b2.acesso > 0 and b1.sigla <> 'CI') or
                 (p_tipo = 7 and b1.sigla <> 'CA')
                )
            and (p_menu           is null or (p_menu        is not null and a.sq_menu            = p_menu))
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
            and (p_ativo          is null or (p_ativo       is not null and (p_ativo = 'N' or (p_ativo = 'S' and b1.ativo = 'S'))))
            and (p_pais           is null or (p_pais        is not null and (d.sq_pessoa_conta   = p_pais or 
                                                                             p_pais = (select w.sq_pessoa_conta from co_pessoa_conta w where w.sq_pessoa = a.sq_pessoa and w.sq_agencia = d.sq_agencia and w.numero = d.numero_conta and w.sq_pessoa_conta <> d.sq_pessoa_conta)
                                                                            )
                                             )
                )
            and (p_regiao         is null or (p_regiao      is not null and ((b.protocolo_siw is not null and b8.numero_documento = p_regiao) or (b.protocolo_siw is null and b4.protocolo_siw is not null and b9.numero_documento = p_regiao))))
            and (p_cidade         is null or (p_cidade      is not null and ((b.protocolo_siw is not null and b8.ano              = p_cidade) or (b.protocolo_siw is null and b4.protocolo_siw is not null and b9.ano              = p_cidade))))
            and (p_usu_resp       is null or (p_usu_resp    is not null and (b.executor          = p_usu_resp or 0 < (select count(*) from fn_lancamento_log where destinatario = p_usu_resp and sq_siw_solicitacao = b.sq_siw_solicitacao))))
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and d1.sq_tipo_lancamento = p_sq_orprior))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and b.conclusao          is null and l.sq_unidade = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc              = p_sqcc))
            and (p_projeto        is null or (p_projeto     is not null and (((substr(a.sigla,4) = 'CONT' or substr(b41.sigla,1,2) = 'CL') and d.sq_solic_vinculo is not null and d.sq_solic_vinculo = p_projeto) or
                                                                             (substr(a.sigla,4) <> 'CONT' and ((b.sq_solic_pai is not null and b.sq_solic_pai = p_projeto) or (b4.sq_solic_pai is not null and b4.sq_solic_pai = p_projeto)))
                                                                            )
                                             )
                )
            and (p_assunto        is null or (p_assunto     is not null and (acentos(b.descricao,null) like '%'||acentos(p_assunto,null)||'%' or acentos(b.justificativa,null) like '%'||acentos(p_assunto,null)||'%')))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (p_prazo          is null or (p_prazo       is not null and b.conclusao          is null and trunc(d.vencimento)-cast(p_prazo as integer)<=trunc(sysdate)))
            and (p_ini_i          is null or (p_ini_i       is not null and d.vencimento         between p_ini_i and p_ini_f))
            and (p_fim_i          is null or (p_fim_i       is not null and d.quitacao           between p_fim_i and p_fim_f))
            and (p_unidade        is null or (p_unidade     is not null and b.sq_unidade         = p_unidade))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and d21.cpf  is not null and d21.cpf  = p_sq_acao_ppa))
            and (p_empenho        is null or (p_empenho     is not null and d22.cnpj is not null and d22.cnpj = p_empenho))
            and (p_palavra        is null or (p_palavra     is not null and b.codigo_interno     like '%'||p_palavra||'%'))
            and (p_atraso         is null or (p_atraso      is not null and (b4.codigo_interno   like '%'||p_atraso ||'%' or b7.codigo_interno like '%'||p_atraso ||'%' or acentos(b4.titulo) like '%'||acentos(p_atraso)||'%' or acentos(b7.titulo) like '%'||acentos(p_atraso)||'%')))
            and (p_uf             is null or (p_uf          is not null and (b4.codigo_interno   like '%'||p_uf ||'%' or b7.codigo_interno like '%'||p_uf ||'%' or acentos(b4.titulo) like '%'||acentos(p_uf)||'%' or acentos(b7.titulo) like '%'||acentos(p_uf) ||'%')))
            --and (p_uf             is null or (p_uf          is not null and f.co_uf              = p_uf))
            and (p_proponente     is null or (p_proponente  is not null and (acentos(d2.nome,null)          like '%'||acentos(p_proponente,null)||'%') or 
                                                                            (acentos(d2.nome_resumido,null) like '%'||acentos(p_proponente,null)||'%')
                                             )
                )
            and (p_restricao is null or
                 (--instr(p_restricao,'PROJ')    = 0 and
                  --instr(p_restricao,'ETAPA')   = 0 and
                  instr(p_restricao,'PROP')    = 0 and
                  instr(p_restricao,'RESPATU') = 0 and
                  substr(p_restricao,4,2)      <>'CC'
                 ) or 
                 (--(instr(p_restricao,'PROJ')    > 0    and ((substr(a.sigla,4) = 'CONT' and r.sq_siw_solicitacao is not null) or (substr(a.sigla,4) <> 'CONT'))) or
                  --(instr(p_restricao,'ETAPA') > 0    and MontaOrdem(q.sq_projeto_etapa,null)  is not null) or                 
                  (instr(p_restricao,'PROP')    > 0    and d.pessoa       is not null) or
                  (instr(p_restricao,'RESPATU') > 0    and b.executor     is not null) or
                  (substr(p_restricao,4,2)      ='CC'  and b.sq_cc        is not null)
                 )
                );
   Elsif p_restricao = 'EXTRATO' Then
      -- Recupera os acordos que o usuário pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.sq_unid_executora,  
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a1.nome as nm_modulo, a1.sigla as sg_modulo,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,
                case when b.descricao is null and a.sigla = 'FNATRANSF' then 'TRANSFERÊNCIA BANCÁRIA' else b.descricao end descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.opiniao,            b.sq_solic_pai,                b.sq_plano,
                b.sq_unidade,         b.sq_cidade_origem,            b.palavra_chave,
                coalesce(b.protocolo_siw, b4.protocolo_siw) as protocolo_siw,
                to_char(b.inclusao,'dd/mm/yyyy, hh24:mi:ss') as phpdt_inclusao,
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
                b1.sq_siw_tramite,    b1.nome as nm_tramite,         b1.ordem as or_tramite,
                b1.sigla as sg_tramite,  b1.ativo,                   b1.envia_mail mail_tramite,
                codigo2numero(b.codigo_interno) ord_codigo_interno,
                d.pessoa,             b.codigo_interno,              d.sq_acordo_parcela,
                d.sq_forma_pagamento, d.sq_tipo_lancamento,          d.sq_tipo_pessoa,
                d.emissao,            d.vencimento,                  d.quitacao,
                b.codigo_externo,     d.observacao,                  d.aviso_prox_conc,
                d.dias_aviso,         d.sq_tipo_pessoa,              d.tipo as tipo_rubrica,
                d.referencia_inicio,  d.referencia_fim,              d.sq_solic_vinculo,
                d.numero_conta,       d.processo,
                case a.sigla
                     when 'FNDFIXO' then g.quitacao
                     else coalesce(d.quitacao, d.vencimento)
                end as dt_pagamento,
                d1.nome as nm_tipo_lancamento,
                d2.nome as nm_pessoa, d2.nome_resumido as nm_pessoa_resumido,
                d2.nome_indice as nm_pessoa_ind,                     d2.nome_resumido_ind as nm_pessoa_resumido_ind,
                d21.cpf,              d22.cnpj,
                d3.numero as nr_doc,
                d31.sigla as sg_doc,  d31.nome as nm_doc,            d3.data as dt_doc,
                g.sq_pessoa_conta sq_conta_debito, g.op_conta operacao_debito,      g.nr_conta conta_debito,
                g.cb_sq_moeda sq_moeda_cc,         g.cb_cd_moeda cd_moeda_cc,       g.cb_nm_moeda nm_moeda_cc,
                g.cb_sg_moeda sg_moeda_cc,         g.cb_sb_moeda sb_moeda_cc,       g.cb_at_moeda at_moeda_cc,
                g.ag_conta sq_agencia_debito,      g.ag_cd_conta cd_agencia_debito, g.ag_nm_conta nm_agencia_debito,
                g.bc_conta sq_banco_debito,        g.bc_cd_conta cd_banco_debito,   g.bc_nm_conta nm_banco_debito,
                g.cb_sq_moeda,                     g.cb_sg_moeda,                   g.cb_sb_moeda,
                g.valor cb_valor,                  g.tipo,
                d7.sq_forma_pagamento,case substr(a.sigla,3,1) when 'R' then null else d7.nome end as nm_forma_pagamento, d7.sigla as sg_forma_pagamento, 
                d7.ativo as st_forma_pagamento,
                cast(b.fim as date)-cast(d.dias_aviso as integer) as aviso,
                n.sq_cc,              n.nome as nm_cc,               n.sigla as sg_cc
           from siw_modulo                                     a1
                inner                join siw_menu             a  on (a1.sq_modulo               = a.sq_modulo and
                                                                      a.sq_pessoa                = w_cliente
                                                                     )
                   inner             join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                      inner          join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                      inner          join fn_lancamento        d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                        inner        join co_forma_pagamento   d7 on (d.sq_forma_pagamento       = d7.sq_forma_pagamento)
                        inner        join fn_tipo_lancamento   d1 on (d.sq_tipo_lancamento       = d1.sq_tipo_lancamento)
                     inner          join vw_conta_bancaria_financeiro g on (b.sq_siw_solicitacao = g.sq_financeiro)
                      left           join pe_plano             b3 on (b.sq_plano                 = b3.sq_plano)
                      left           join siw_solicitacao      b4 on (b.sq_solic_pai             = b4.sq_siw_solicitacao)
                        left         join siw_menu            b41 on (b4.sq_menu                 = b41.sq_menu)
                        left         join pe_plano             b5 on (b4.sq_plano                = b5.sq_plano)
                        left         join ct_cc                b6 on (b4.sq_cc                   = b6.sq_cc)
                        left         join siw_solicitacao      b7 on (b4.sq_solic_pai            = b7.sq_siw_solicitacao)
                        left         join co_pessoa            d2 on (d.pessoa                   = d2.sq_pessoa)
                          left       join co_pessoa_fisica    d21 on (d2.sq_pessoa               = d21.sq_pessoa)
                          left       join co_pessoa_juridica  d22 on (d2.sq_pessoa               = d22.sq_pessoa)
                        left         join fn_lancamento_doc    d3 on (d.sq_siw_solicitacao       = d3.sq_siw_solicitacao) 
                          left       join fn_tipo_documento   d31 on (d3.sq_tipo_documento       = d31.sq_tipo_documento)
                      left           join ct_cc                n  on (b.sq_cc                    = n.sq_cc)
          where (p_menu           is null or (p_menu        is not null and a.sq_menu            = p_menu))
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
            and (p_ativo          is null or (p_ativo       is not null and (p_ativo = 'N' or (p_ativo = 'S' and b1.ativo = 'S'))))
            and (p_pais           is null or (p_pais        is not null and (g.sq_pessoa_conta   = p_pais)))
            and (p_projeto        is null or (p_projeto     is not null and (((substr(a.sigla,4) = 'CONT' or substr(b41.sigla,1,2) = 'CL') and d.sq_solic_vinculo is not null and d.sq_solic_vinculo = p_projeto) or
                                                                             (substr(a.sigla,4) <> 'CONT' and ((b.sq_solic_pai is not null and b.sq_solic_pai = p_projeto) or (b4.sq_solic_pai is not null and b4.sq_solic_pai = p_projeto)))
                                                                            )
                                             )
                )
            and (p_fim_i          is null or (p_fim_i       is not null and ((a.sigla = 'FNDFIXO'  and g.quitacao           between p_fim_i and p_fim_f) or
                                                                             (a.sigla <> 'FNDFIXO' and d.quitacao           between p_fim_i and p_fim_f)
                                                                            )
                                              )
                );
   End If;
end SP_GetSolicFN;
/
