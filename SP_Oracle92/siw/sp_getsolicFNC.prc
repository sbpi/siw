create or replace procedure SP_GetSolicFNC
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
                a.envia_dia_util,     a.descricao,                   a.justificativa,
                a1.nome as nm_modulo, a1.sigla as sg_modulo,         a1.objetivo_geral,
                a2.sq_tipo_unidade as tp_exec,                       a2.nome as nm_unidade_exec, 
                a2.informal as informal_exec,                        a2.vinculada as vinc_exec,
                a2.adm_central as adm_exec,                          a2.sq_tipo_unidade,
                a2.informal,          a2.vinculada,                  a2.adm_central,
                a3.sq_pessoa as tit_exec,a4.sq_pessoa as subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.opiniao,            b.sq_solic_pai,                b.sq_cidade_origem,
                b.palavra_chave,      b.sq_plano,                    b.sq_unidade,
                b.protocolo_siw,      coalesce(dj.valor,0)+coalesce(dh.valor,0)-coalesce(dg.valor,0) as valor,
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
                b1.sq_siw_tramite,    b1.nome as nm_tramite,         b1.ordem as or_tramite,
                b1.sigla as sg_tramite,  b1.ativo,                   b1.envia_mail,
                case b1.sigla when 'AT' then b.valor else 0 end as valor_atual,
                b4.sq_solic_pai as sq_solic_avo,
                case when b4.sq_solic_pai is null 
                     then case when b4.sq_plano is null
                               then case when b4.sq_cc is null
                                         then '???'
                                         else 'Classif: '||b4.nm_cc 
                                    end
                               else ' Plano: '||b4.titulo
                          end
                     else dados_solic(b4.sq_solic_pai) 
                end as dados_avo,
                case b4.sg_menu when 'PJCAD' then b4.sq_siw_solicitacao else case b41.sg_menu when 'PJCAD' then b41.sq_siw_solicitacao else q.sq_siw_solicitacao end end as sq_projeto,
                case b4.sg_menu when 'PJCAD' then b4.titulo             else case b41.sg_menu when 'PJCAD' then b41.titulo             else q.titulo             end end as nm_projeto,
                case b4.sg_menu when 'PJCAD' then b4.codigo_interno     else case b41.sg_menu when 'PJCAD' then b41.codigo_interno     else q.codigo_interno     end end as cd_projeto,
                case when b6.sq_siw_solicitacao is null then null else to_char(b6.numero_documento)||'/'||substr(to_char(b6.ano),3) end as protocolo,
                case when b6.sq_siw_solicitacao is null then null else to_char(b6.prefixo)||'.'||substr(1000000+to_char(b6.numero_documento),2,6)||'/'||to_char(b6.ano)||'-'||substr(100+to_char(b6.digito),2,2) end as protocolo_completo,
                q.rubrica, 	
                d.pessoa,             b.codigo_interno,              d.sq_acordo_parcela,
                codigo2numero(b.codigo_interno) as ord_codigo_interno,
                d.sq_forma_pagamento, d.sq_tipo_lancamento,          d.sq_tipo_pessoa,
                d.emissao,            d.vencimento,                  d.quitacao,
                b.codigo_externo,     d.observacao,                  d.valor_imposto,
                d.valor_retencao,     d.valor_liquido,               d.aviso_prox_conc,
                d.dias_aviso,         d.sq_tipo_pessoa,              d.tipo as tipo_rubrica,
                d.referencia_inicio,  d.referencia_fim,              d.sq_solic_vinculo,
                d.processo,
                d1.nome as nm_tipo_lancamento,                       coalesce(d.quitacao, d.vencimento) as pagamento,
                case d.tipo when 1 then 'Dotação inicial' when 2 then 'Transferência entre rubricas' when 3 then 'Atualização de aplicação' when 4 then 'Entradas' else 'Normal' end as nm_tipo_rubrica,
                d2.nome as nm_pessoa, d2.nome_resumido as nm_pessoa_resumido,
                d2.nome_indice as nm_pessoa_ind,                     d2.nome_resumido_ind as nm_pessoa_resumido_ind,
                coalesce(d3.valor,0) as valor_doc,                   d3.numero as nr_doc,
                d31.sigla as sg_doc,  d31.nome as nm_tipo_doc,       d3.data as dt_doc,
                d4.sq_pessoa_conta,   d4.operacao,                   d4.nr_conta,
                d4.devolucao_valor,   d4.sq_agencia,                 d4.cd_agencia,       d4.nm_agencia,
                d4.sq_banco,          d4.cd_banco,                   d4.nm_banco,
                d4.nm_banco||' AG. '||d4.cd_agencia||' C/C '||d4.nr_conta as ds_conta_credito,
                di.nm_banco||' AG. '||di.cd_agencia||' C/C '||di.nr_conta as ds_conta_debito,
                d7.sq_forma_pagamento,d7.nome as nm_forma_pagamento, d7.sigla as sg_forma_pagamento, 
                d7.ativo as st_forma_pagamento,
                coalesce(d9.valor,0) as valor_nota,
                da.sq_imposto,
                case coalesce(da.sq_siw_solicitacao,0) when 0 then 'N' else 'S' end as lancamento_vinculado,
                cast(b.fim as date)-cast(d.dias_aviso as integer) as aviso,
                case a.sigla when 'FNDVIA' then b4.sq_unidade      else e.sq_unidade      end as sq_unidade_resp,
                case a.sigla when 'FNDVIA' then b4.sq_tipo_unidade else e.sq_tipo_unidade end as sq_tipo_unidade,
                case a.sigla when 'FNDVIA' then b4.nm_unidade      else e.nome            end as nm_unidade_resp,
                case a.sigla when 'FNDVIA' then b4.informal        else e.informal        end as informal_resp,
                case a.sigla when 'FNDVIA' then b4.vinculada       else e.vinculada       end as vinc_resp,
                case a.sigla when 'FNDVIA' then b4.adm_central     else e.adm_central     end as adm_resp,
                case a.sigla when 'FNDVIA' then b4.sg_unidade      else e.sigla           end as sg_unidade_resp,
                e1.sq_pessoa as titular, e2.sq_pessoa as substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                m.cd_acordo,          m.obj_acordo,
                m1.ordem as or_parcela,
                m2.qtd_nota,
                mo.sq_moeda,          mo.codigo cd_moeda,            mo.nome nm_moeda,
                mo.sigla sg_moeda,    mo.simbolo sb_moeda,           mo.ativo at_moeda,
                n.sq_cc,              n.nome as nm_cc,               n.sigla as sg_cc,
                o.nm_solic,           o.nm_resp,
                p.nome_resumido as nm_exec,
                b5.codigo_interno as cd_solic_vinculo, b5.titulo as nm_solic_vinculo,
                codigo2numero(b5.codigo_interno) as ord_cd_solic_vinculo
           from siw_menu                                    a 
                inner        join siw_modulo                a1 on (a.sq_modulo                = a1.sq_modulo)
                inner        join eo_unidade                a2 on (a.sq_unid_executora        = a2.sq_unidade)
                inner        join siw_solicitacao           b  on (a.sq_menu                  = b.sq_menu)
                   inner     join siw_tramite               b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                   inner     join fn_lancamento             d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                     inner   join fn_tipo_lancamento        d1 on (d.sq_tipo_lancamento       = d1.sq_tipo_lancamento)
                     inner   join co_forma_pagamento        d7 on (d.sq_forma_pagamento       = d7.sq_forma_pagamento)
                   inner     join eo_unidade                e  on (b.sq_unidade               = e.sq_unidade)
                   inner     join co_cidade                 f  on (b.sq_cidade_origem         = f.sq_cidade)
                   inner     join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) as acesso
                                     from siw_solicitacao
                                    where sq_menu = p_menu
                                  )                         b2 on (d.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                       inner join (select x.sq_siw_solicitacao, max(x.sq_siw_solic_log) as chave 
                                          from siw_solic_log              x
                                               inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                         where y.sq_menu = p_menu
                                        group by x.sq_siw_solicitacao
                                  )                         j  on (d.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                  left       join (select w.sq_siw_solic_log, x.sq_unidade
                                     from fn_lancamento_log          w
                                          inner join sg_autenticacao x on (w.destinatario       = x.sq_pessoa)
                                          inner join siw_solicitacao y on (w.sq_siw_solicitacao = y.sq_siw_solicitacao and 
                                                                           y.sq_menu            = p_menu
                                                                          )
                                  )                         k  on (j.chave                    = k.sq_siw_solic_log)
                  left       join eo_unidade_resp           a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                                   a3.tipo_respons            = 'T'           and
                                                                   a3.fim                     is null
                                                                  )
                  left       join eo_unidade_resp           a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                   a4.tipo_respons            = 'S'           and
                                                                   a4.fim                     is null
                                                                  )
                   left      join pe_plano                  b3 on (a.sq_pessoa                = b3.cliente and
                                                                   b.sq_plano                 = b3.sq_plano
                                                                  )
                   left      join (select w.sq_siw_solicitacao, w.sq_solic_pai, w.codigo_interno, w.titulo, w.sq_cc,
                                          w.sq_plano,           w.inicio,       w.fim,
                                          x.sigla as sg_menu,   s.nome as nm_cc,
                                          u.sq_unidade,         u.nome as nm_unidade,             u.sigla as sg_unidade,
                                          u.sq_tipo_unidade,    u.informal,                       u.vinculada,
                                          u.adm_central
                                     from siw_solicitacao         w
                                          inner   join eo_unidade u on (w.sq_unidade = u.sq_unidade)
                                          inner   join siw_menu   x on (w.sq_menu    = x.sq_menu)
                                            inner join siw_menu   y on (x.sq_pessoa  = y.sq_pessoa and
                                                                        y.sq_menu    = p_menu
                                                                       )
                                          left    join pe_plano   r on (w.sq_plano   = r.sq_plano)
                                          left    join ct_cc      s on (w.sq_cc      = s.sq_cc)
                                  )                         b4 on (b.sq_solic_pai             = b4.sq_siw_solicitacao)
                     left    join (select w.sq_siw_solicitacao, w.sq_solic_pai, w.sq_plano, w.codigo_interno, w.titulo, x.sigla as sg_menu
                                     from siw_solicitacao       w
                                          inner   join siw_menu x on (w.sq_menu   = x.sq_menu)
                                            inner join siw_menu y on (x.sq_pessoa = y.sq_pessoa and
                                                                      y.sq_menu   = p_menu
                                                                     )
                                  )                        b41 on (b4.sq_solic_pai            = b41.sq_siw_solicitacao)
                     left    join (select w.sq_siw_solicitacao, x.titulo, x.codigo_interno
                                     from pj_projeto                 w
                                          inner join siw_solicitacao x on (w.sq_siw_solicitacao = x.sq_siw_solicitacao)
                                  )                         b5  on (d.sq_solic_vinculo        = b5.sq_siw_solicitacao)
                   left      join pa_documento              b6  on (b.protocolo_siw           = b6.sq_siw_solicitacao)
                   left      join (select w.sq_siw_solicitacao, x.titulo, x.codigo_interno,
                                          case when y.sq_siw_solicitacao is null then 'N' else 'S' end as rubrica
                                     from pj_projeto                 w
                                          inner join siw_solicitacao x on (w.sq_siw_solicitacao = x.sq_siw_solicitacao)
                                          left  join (select sq_siw_solicitacao, count(*)
                                                         from pj_rubrica
                                                       group by sq_siw_solicitacao
                                                      )              y on (w.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                  )                         q  on (b.sq_solic_pai             = q.sq_siw_solicitacao)
                     left    join co_pessoa                 d2 on (d.pessoa                   = d2.sq_pessoa)
                     left    join fn_lancamento_doc         d3 on (d.sq_siw_solicitacao       = d3.sq_siw_solicitacao) 
                       left  join fn_tipo_documento        d31 on (d3.sq_tipo_documento       = d31.sq_tipo_documento)
                     left    join (select w.sq_pessoa,          w.sq_pessoa_conta,      w.operacao,
                                          w.numero as nr_conta, w.devolucao_valor,
                                          x.sq_agencia,         x.codigo as cd_agencia, x.nome as nm_agencia,
                                          y.sq_banco,           y.codigo as cd_banco,   y.nome as nm_banco
                                     from co_pessoa_conta         w
                                          inner   join co_agencia x on (w.sq_agencia    = x.sq_agencia)
                                            inner join co_banco   y on (x.sq_banco      = y.sq_banco)
                                          inner   join co_pessoa  z on (w.sq_pessoa     = z.sq_pessoa)
                                            inner join siw_menu   s on (z.sq_pessoa_pai = s.sq_pessoa and
                                                                        s.sq_menu       = p_menu
                                                                       )
                                    where w.ativo  = 'S'
                                      and w.padrao = 'S'
                                  )                         d4 on (d.pessoa                   = d4.sq_pessoa)
                     left    join (select w.sq_pessoa,          w.sq_pessoa_conta,      w.operacao,
                                          w.numero as nr_conta, w.devolucao_valor,
                                          x.sq_agencia,         x.codigo as cd_agencia, x.nome as nm_agencia,
                                          y.sq_banco,           y.codigo as cd_banco,   y.nome as nm_banco
                                     from co_pessoa_conta         w
                                          inner   join co_agencia x on (w.sq_agencia    = x.sq_agencia)
                                            inner join co_banco   y on (x.sq_banco      = y.sq_banco)
                                            inner join siw_menu   s on (w.sq_pessoa     = s.sq_pessoa and
                                                                        s.sq_menu       = p_menu
                                                                       )
                                  )                         di on (d.sq_pessoa_conta    = di.sq_pessoa_conta)
                       left  join (select x.sq_siw_solicitacao, sum(x.valor) as valor
                                            from fn_lancamento_doc          x
                                                 inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                                 inner join siw_menu        z on (y.sq_menu            = z.sq_menu)
                                           where x.sq_acordo_nota is not null
                                             and z.sq_menu        = p_menu
                                          group by x.sq_siw_solicitacao
                                  )                         d9 on (d.sq_siw_solicitacao       = d9.sq_siw_solicitacao)
                       left  join (select y.sq_siw_solicitacao, x.sq_imposto
                                            from fn_imposto_doc             x
                                                 inner join siw_solicitacao y on (x.solic_retencao = y.sq_siw_solicitacao or 
                                                                                  x.solic_imposto  = y.sq_siw_solicitacao
                                                                                 )
                                                 inner join siw_menu        z on (y.sq_menu        = z.sq_menu and
                                                                                  z.sigla          = 'FNDEVENT'
                                                                                 )
                                  )                         da on (d.sq_siw_solicitacao       = da.sq_siw_solicitacao)
                     left         join (select zd.sq_siw_solicitacao, sum(za.valor_total) as valor
                                          from fn_imposto_doc                    za
                                               inner     join fn_lancamento_doc  zb on (za.sq_lancamento_doc  = zb.sq_lancamento_doc)
                                                 inner   join siw_solicitacao    zd on (zb.sq_siw_solicitacao = zd.sq_siw_solicitacao)
                                               inner     join fn_imposto         zg on (za.sq_imposto         = zg.sq_imposto)
                                               inner     join siw_solicitacao    zh on (za.solic_imposto      = zh.sq_siw_solicitacao)
                                                 inner   join fn_lancamento      zi on (zh.sq_siw_solicitacao = zi.sq_siw_solicitacao)
                                                 inner   join siw_tramite        zj on (zh.sq_siw_tramite     = zj.sq_siw_tramite and zj.sigla <> 'CA')
                                                 inner   join siw_menu           zk on (zh.sq_menu            = zk.sq_menu)
                                         where zg.calculo > 0
                                           and (p_chave     is null or (p_chave     is not null and zd.sq_siw_solicitacao = p_chave))
                                        group by zd.sq_siw_solicitacao
                                       )                    dg on (d.sq_siw_solicitacao       = dg.sq_siw_solicitacao)
                     left         join (select zd.sq_siw_solicitacao, sum(case zg.tipo when 'A' then za.valor else -1*za.valor end) as valor
                                          from fn_documento_valores              za
                                               inner     join fn_lancamento_doc  zb on (za.sq_lancamento_doc  = zb.sq_lancamento_doc)
                                                 inner   join siw_solicitacao    zd on (zb.sq_siw_solicitacao = zd.sq_siw_solicitacao)
                                               inner     join fn_valores         zg on (za.sq_valores         = zg.sq_valores)
                                         where (p_chave     is null or (p_chave     is not null and zd.sq_siw_solicitacao = p_chave))
                                        group by zd.sq_siw_solicitacao
                                       )                    dh on (d.sq_siw_solicitacao       = dh.sq_siw_solicitacao)
                     left         join (select zb.sq_siw_solicitacao, sum(zb.valor) as valor
                                          from fn_lancamento_doc  zb
                                         where (p_chave    is null or (p_chave is not null and zb.sq_siw_solicitacao = p_chave))
                                        group by zb.sq_siw_solicitacao
                                       )                    dj on (d.sq_siw_solicitacao       = dj.sq_siw_solicitacao)
                     left    join eo_unidade_resp           e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                   e1.tipo_respons            = 'T'           and
                                                                   e1.fim                     is null
                                                                  )
                     left    join eo_unidade_resp           e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                   e2.tipo_respons            = 'S'           and
                                                                   e2.fim                     is null
                                                                  )
                   left      join (select w.sq_siw_solicitacao, w.objeto as obj_acordo, x.codigo_interno as cd_acordo
                                          from ac_acordo                  w
                                               inner join siw_solicitacao x on (w.sq_siw_solicitacao = x.sq_siw_solicitacao)
                                  )                         m  on (b.sq_solic_pai             = m.sq_siw_solicitacao)
                   left      join ac_acordo_parcela         m1 on (d.sq_acordo_parcela        = m1.sq_acordo_parcela and
                                                                   d.sq_acordo_parcela        is not null
                                                                  )
                     left    join (select count(y.sq_acordo_nota) as qtd_nota, y.sq_acordo_parcela
                                          from ac_parcela_nota y
                                         group by y.sq_acordo_parcela
                                  )                         m2 on (m1.sq_acordo_parcela       = m2.sq_acordo_parcela)
                   left      join co_moeda                  mo on (b.sq_moeda                 = mo.sq_moeda)
                   left      join ct_cc                     n  on (b.sq_cc                    = n.sq_cc)
                   left      join (select w.sq_pessoa, w.nome_resumido as nm_solic, w.nome_resumido||' ('||y.sigla||')' as nm_resp
                                          from co_pessoa                    w
                                               inner   join sg_autenticacao x on (w.sq_pessoa     = x.sq_pessoa)
                                                 inner join eo_unidade      y on (x.sq_unidade    = y.sq_unidade)
                                               inner   join siw_menu        z on (w.sq_pessoa_pai = z.sq_pessoa and
                                                                                  z.sq_menu       = p_menu
                                                                                 )
                                  )                         o  on (b.solicitante              = o.sq_pessoa)
                   left      join co_pessoa                 p  on (b.executor                 = p.sq_pessoa)
          where a.sq_menu        = p_menu
            and ((p_tipo         = 1     and b1.sigla = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and b1.sigla <> 'CI' and b1.sigla <> 'EE' and b.executor = p_pessoa and b.conclusao is null) or
                 (p_tipo         = 2     and b1.ativo = 'S' and b1.sigla <> 'CI' and b1.sigla <> 'EE' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and b1.sigla <> 'CA'  and b2.acesso > 0) or
                 (p_tipo         = 4     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0 and b1.sigla <> 'CI')
                )
            and ((instr(p_restricao,'PROJ')    = 0 and
                  instr(p_restricao,'ETAPA')   = 0 and
                  instr(p_restricao,'PROP')    = 0 and
                  instr(p_restricao,'RESPATU') = 0 and
                  substr(p_restricao,4,2)      <>'CC'
                 ) or 
                 ((instr(p_restricao,'PROJ')    > 0    and ((substr(a.sigla,4) not in ('CONT','VIA') and q.sq_siw_solicitacao is not null) or
                                                            ((substr(a.sigla,4) = 'CONT' and b5.sq_siw_solicitacao is not null) or 
                                                             (substr(a.sigla,4) = 'VIA'  and b41.sq_siw_solicitacao is not null)
                                                            )
                                                           )
                  ) or
                  --(instr(p_restricao,'ETAPA') > 0    and MontaOrdem(q.sq_projeto_etapa,null)  is not null) or                 
                  (instr(p_restricao,'PROP')    > 0    and d.pessoa       is not null) or
                  (instr(p_restricao,'RESPATU') > 0    and b.executor     is not null) or
                  (substr(p_restricao,4,2)      ='CC'  and b.sq_cc        is not null)
                 )
                )
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
            and (p_pais           is null or (p_pais        is not null and f.sq_pais            = p_pais))
            and (p_regiao         is null or (p_regiao      is not null and f.sq_regiao          = p_regiao))
            and (p_cidade         is null or (p_cidade      is not null and f.sq_cidade          = p_cidade))
            and (p_usu_resp       is null or (p_usu_resp    is not null and (b.executor          = p_usu_resp or 0 < (select count(*) from fn_lancamento_log where destinatario = p_usu_resp and sq_siw_solicitacao = b.sq_siw_solicitacao))))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and b.conclusao          is null and k.sq_unidade = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc              = p_sqcc))
            and (p_projeto        is null or (p_projeto     is not null and ((substr(a.sigla,4)  = 'CONT' and d.sq_solic_vinculo is not null and d.sq_solic_vinculo = p_projeto) or
                                                                             (substr(a.sigla,4)  = 'VIA'  and (coalesce(b4.sq_siw_solicitacao,0) = p_projeto or coalesce(b41.sq_siw_solicitacao,0) = p_projeto)) or
                                                                             (substr(a.sigla,4) <> 'CONT' and b.sq_solic_pai     is not null and b.sq_solic_pai     = p_projeto)
                                                                            )
                                             )
                )
            and (p_uf             is null or (p_uf          is not null and f.co_uf              = p_uf))
            and (p_assunto        is null or (p_assunto     is not null and acentos(b.descricao,null) like '%'||acentos(p_assunto,null)||'%'))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (p_prazo          is null or (p_prazo       is not null and b.conclusao          is null and cast(cast(b.fim as date)-cast(sysdate as date) as integer)+1 <=p_prazo))
            and (p_ini_i          is null or (p_ini_i       is not null and (b4.inicio           between p_ini_i and p_ini_f or
                                                                             b4.fim              between p_ini_i and p_ini_f or
                                                                             p_ini_i             between b4.inicio and b4.fim or
                                                                             p_ini_f             between b4.inicio and b4.fim
                                                                            )
                                             )
                )
            and (p_fim_i          is null or (p_fim_i       is not null and ((b1.sigla = 'AT' and d.quitacao          between p_fim_i and p_fim_f) or
                                                                             (b1.sigla <> 'AT' and d.vencimento       between p_fim_i and p_fim_f)
                                                                            )
                                             )
                )
            and (p_unidade        is null or (p_unidade     is not null and p_unidade = case a.sigla when 'FNDVIA' then b4.sq_unidade else b.sq_unidade end))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and (p_palavra        is null or (p_palavra     is not null and b.codigo_interno     like '%'||p_palavra||'%'))
            and (p_proponente     is null or (p_proponente  is not null and (acentos(d2.nome,null)          like '%'||acentos(p_proponente,null)||'%') or 
                                                                            (acentos(d2.nome_resumido,null) like '%'||acentos(p_proponente,null)||'%')
                                             )
                );
   End If;
end SP_GetSolicFNC;
/
