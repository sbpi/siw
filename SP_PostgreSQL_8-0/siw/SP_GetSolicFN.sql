create or replace FUNCTION SP_GetSolicFN
   (p_menu         numeric,
    p_pessoa       numeric,
    p_restricao    varchar,
    p_tipo         numeric,
    p_ini_i        date,
    p_ini_f        date,
    p_fim_i        date,
    p_fim_f        date,
    p_atraso       varchar,
    p_solicitante  numeric,
    p_unidade      numeric,
    p_prioridade   numeric,
    p_ativo        varchar,
    p_proponente   varchar,
    p_chave        numeric,
    p_assunto      varchar,
    p_pais         numeric,
    p_regiao       numeric,
    p_uf           varchar,
    p_cidade       numeric,
    p_usu_resp     numeric,
    p_uorg_resp    numeric,
    p_palavra      varchar,
    p_prazo        numeric,
    p_fase         varchar,
    p_sqcc         numeric,
    p_projeto      numeric,
    p_atividade    numeric,
    p_sq_acao_ppa  varchar,
    p_sq_orprior   numeric,
    p_empenho      varchar,
    p_processo     varchar,
    p_result       REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
    
    l_item       varchar(18);
    l_fase       varchar(200) := p_fase ||',';
    x_fase       varchar(200) := '';
    
    l_resp_unid  varchar(10000) :='';
    
    --  Recupera as unidades nas quais o usuário informado é titular ou substituto
     c_unidades_resp CURSOR FOR
        select distinct a.sq_unidade
          from eo_unidade_resp         b
	       inner   join co_pessoa  c on (b.sq_pessoa     = c.sq_pessoa)
	         inner join eo_unidade a on (c.sq_pessoa_pai = a.sq_pessoa)
         where b.sq_pessoa = p_pessoa
           and b.fim       is null
           and a.sq_unidade in (select sq_unidade from connectby('eo_unidade','sq_unidade','sq_unidade_pai',to_char(b.sq_unidade),0) as (sq_unidade numeric, sq_unidade_pai numeric, level int));

      
BEGIN
   If p_fase is not null Then
      Loop
         l_item  := Trim(substr(l_fase,1,Instr(l_fase,',')-1));
         If Length(l_item) > 0 Then
            x_fase := x_fase||','''||to_number(l_item)||'''';
         End If;
         l_fase := substr(l_fase,Instr(l_fase,',')+1,200);
         Exit when l_fase is null or instr(l_fase,',') = 0;
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
                coalesce(b.codigo_interno,b.titulo,to_char(b.sq_siw_solicitacao)) as titulo,
                b.titulo as ac_titulo,
                b1.sq_siw_tramite,    b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,                      b1.envia_mail,
                case a.sigla when 'FNDVIA'
                             then case when b2.quitacao >= trunc(now()) then 'Agendado' else b1.nome end
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
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao,                   a.justificativa,
                a1.nome as nm_modulo, a1.sigla as sg_modulo,         a1.objetivo_geral,
                a2.sq_tipo_unidade as tp_exec, a2.nome as nm_unidade_exec, a2.informal as informal_exec,
                a2.vinculada as vinc_exec,a2.adm_central as adm_exec,
                a3.sq_pessoa as tit_exec,a4.sq_pessoa as subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.opiniao,            b.sq_solic_pai,
                b.sq_unidade,         b.sq_cidade_origem,            b.palavra_chave,
                b.valor,              b.sq_plano,
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
                b1.sigla as sg_tramite,  b1.ativo,                   b1.envia_mail,
                c.sq_tipo_unidade,    c.nome as nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.pessoa,             b.codigo_interno,              d.sq_acordo_parcela,
                d.sq_forma_pagamento, d.sq_tipo_lancamento,          d.sq_tipo_pessoa,
                d.emissao,            d.vencimento,                  d.quitacao,
                b.codigo_externo,     d.observacao,                  d.valor_imposto,
                d.valor_retencao,     d.valor_liquido,               d.aviso_prox_conc,
                d.dias_aviso,         d.sq_tipo_pessoa,              d.tipo as tipo_rubrica,
                d.referencia_inicio,  d.referencia_fim,              d.sq_solic_vinculo,
                d.numero_conta,
                coalesce(d.quitacao, d.vencimento) as dt_pagamento,
                d1.nome as nm_tipo_lancamento,
                case d.tipo when 1 then 'Dotação inicial' when 2 then 'Transferência entre rubricas' when 3 then 'Atualização de aplicação' when 4 then 'Entradas' else 'Normal' end as nm_tipo_rubrica,
                d2.nome as nm_pessoa, d2.nome_resumido as nm_pessoa_resumido,
                d2.nome_indice as nm_pessoa_ind,                     d2.nome_resumido_ind as nm_pessoa_resumido_ind,
                coalesce(d3.valor,0) as valor_doc,
                d4.sq_pessoa_conta,   d4.operacao,                   d4.numero as nr_conta,
                d4.devolucao_valor,
                d5.sq_agencia,        d5.codigo as cd_agencia,       d5.nome as nm_agencia,
                d6.sq_banco,          d6.codigo as cd_banco,         d6.nome as nm_banco,
                d7.sq_forma_pagamento,case substr(a.sigla,3,1) when 'R' then null else d7.nome end as nm_forma_pagamento, d7.sigla as sg_forma_pagamento, 
                d7.ativo as st_forma_pagamento,
                coalesce(d9.valor,0) as valor_nota,
                trunc(b.fim)-cast(d.dias_aviso as integer)as aviso,
                e.sq_tipo_unidade,    e.nome as nm_unidade_resp,     e.informal as informal_resp,
                e.vinculada as vinc_resp,e.adm_central as adm_resp,  e.sigla as sg_unidade_resp,
                e1.sq_pessoa as titular, e2.sq_pessoa as substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                m3.codigo_interno as cd_acordo, m.objeto as obj_acordo,
                m1.ordem as or_parcela,
                m2.qtd_nota,
                n.sq_cc,              n.nome as nm_cc,               n.sigla as sg_cc,
                o.nome_resumido as nm_solic, o.nome_resumido||' ('||o2.sigla||')' as nm_resp,
                p.nome_resumido as nm_exec,
                q1.titulo as nm_projeto,
                case when q2.sq_siw_solicitacao is null then 'N' else 'S' end as rubrica,
                r1.codigo_interno as cd_solic_vinculo, r1.titulo as nm_solic_vinculo
           from co_pessoa                                      z1
                inner join siw_cliente_modulo                  z2 on (z1.sq_pessoa_pai           = z2.sq_pessoa)
                inner join siw_modulo                          a1 on (z2.sq_modulo               = a1.sq_modulo and
                                                                      a1.sigla                   = 'FN'
                                                                     )
                inner           join siw_menu                  a  on (a1.sq_modulo               = a.sq_modulo and
                                                                      z2.sq_pessoa               = a.sq_pessoa and
                                                                      a.sigla                    <> 'FNDFIXO'
                                                                     )
                   inner        join eo_unidade                a2 on (a.sq_unid_executora        = a2.sq_unidade)
                   inner             join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                      inner          join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                      inner          join (select y.sq_siw_solicitacao, acesso(y.sq_siw_solicitacao, p_pessoa) as acesso
                                             from co_pessoa                      w
                                                  inner     join siw_menu        x on (w.sq_pessoa_pai      = x.sq_pessoa and 
                                                                                       x.sq_menu            = coalesce(p_menu,x.sq_menu))
                                                    inner   join siw_solicitacao y on (x.sq_menu            = y.sq_menu)
                                                      inner join fn_lancamento   z on (y.sq_siw_solicitacao = z.sq_siw_solicitacao)
                                            where w.sq_pessoa = p_pessoa
                                          )                    b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                      inner          join eo_unidade           e  on (b.sq_unidade               = e.sq_unidade)
                      inner          join co_cidade            f  on (b.sq_cidade_origem         = f.sq_cidade)
                      inner          join fn_lancamento        d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                        inner        join co_forma_pagamento   d7 on (d.sq_forma_pagamento       = d7.sq_forma_pagamento)
                        inner        join fn_tipo_lancamento   d1 on (d.sq_tipo_lancamento       = d1.sq_tipo_lancamento)
                      inner          join (select y.sq_siw_solicitacao, max(z1.sq_siw_solic_log) as chave
                                             from co_pessoa                      w
                                                  inner     join siw_menu        x on (w.sq_pessoa_pai      = x.sq_pessoa and 
                                                                                       x.sq_menu            = coalesce(p_menu,x.sq_menu))
                                                    inner   join siw_solicitacao y on (x.sq_menu            = y.sq_menu)
                                                      inner join fn_lancamento   z on (y.sq_siw_solicitacao = z.sq_siw_solicitacao)
                                                      inner join siw_solic_log  z1 on (y.sq_siw_solicitacao = z1.sq_siw_solicitacao)
                                            where w.sq_pessoa = p_pessoa
                                           group by y.sq_siw_solicitacao
                                          )                    j  on (d.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                     left       join eo_unidade_resp           a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                                      a3.tipo_respons            = 'T'           and
                                                                      a3.fim                     is null
                                                                     )
                     left       join eo_unidade_resp           a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                      a4.tipo_respons            = 'S'           and
                                                                      a4.fim                     is null
                                                                     )
                      left           join pe_plano             b3 on (a.sq_pessoa                = b3.cliente and
                                                                      b.sq_plano                 = b3.sq_plano
                                                                     )
                      left           join siw_solicitacao      b4 on (b.sq_solic_pai             = b4.sq_siw_solicitacao)
                        left         join pe_plano             b5 on (a.sq_pessoa                = b5.cliente and
                                                                      b4.sq_plano                = b5.sq_plano
                                                                     )
                        left         join ct_cc                b6 on (b4.sq_cc                   = b6.sq_cc)
                        left         join siw_solicitacao      b7 on (b4.sq_solic_pai            = b7.sq_siw_solicitacao)
                        left         join co_pessoa            d2 on (d.pessoa                   = d2.sq_pessoa)
                        left         join (select x.sq_siw_solicitacao, sum(x.valor) as valor
                                             from fn_lancamento_doc x
                                                  inner join siw_solicitacao y on (x.sq_lancamento_doc = y.sq_siw_solicitacao)
                                                  inner join siw_menu        z on (y.sq_menu           = z.sq_menu)
                                            where x.sq_acordo_nota is null
                                              and z.sq_menu        = p_menu
                                           group by x.sq_siw_solicitacao
                                          )                    d3 on (d.sq_siw_solicitacao       = d3.sq_siw_solicitacao)
                        left         join co_pessoa_conta      d4 on (d.pessoa                   = d4.sq_pessoa and
                                                                      d4.ativo                   = 'S' and
                                                                      d4.padrao                  = 'S'
                                                                     )
                        left         join co_agencia           d5 on (d.sq_agencia               = d5.sq_agencia)
                        left         join co_banco             d6 on (d5.sq_banco                = d6.sq_banco)
                          left       join (select x.sq_siw_solicitacao, sum(x.valor) as valor
                                             from fn_lancamento_doc          x
                                                  inner join siw_solicitacao y on (x.sq_lancamento_doc = y.sq_siw_solicitacao)
                                                  inner join siw_menu        z on (y.sq_menu           = z.sq_menu)
                                            where x.sq_acordo_nota is not null
                                              and z.sq_menu        = p_menu
                                           group by x.sq_siw_solicitacao
                                          )                    d9 on (d.sq_siw_solicitacao       = d9.sq_siw_solicitacao)
                        left         join eo_unidade_resp      e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                      e1.tipo_respons            = 'T'           and
                                                                      e1.fim                     is null
                                                                     )
                        left         join eo_unidade_resp      e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                      e2.tipo_respons            = 'S'           and
                                                                      e2.fim                     is null
                                                                     )
                      left           join ac_acordo            m  on (b.sq_solic_pai             = m.sq_siw_solicitacao)
                        left         join siw_solicitacao      m3 on (m.sq_siw_solicitacao       = m3.sq_siw_solicitacao)
                      left           join ac_acordo_parcela    m1 on (d.sq_acordo_parcela        = m1.sq_acordo_parcela and
                                                                      d.sq_acordo_parcela        is not null
                                                                     )
                        left         join (select count(y.sq_acordo_nota) as qtd_nota, y.sq_acordo_parcela
                                             from ac_parcela_nota y
                                            group by y.sq_acordo_parcela
                                          )                    m2 on (m1.sq_acordo_parcela       = m2.sq_acordo_parcela)
                      left           join pj_projeto           q  on (b.sq_solic_pai             = q.sq_siw_solicitacao)
                        left         join siw_solicitacao      q1 on (q.sq_siw_solicitacao       = q1.sq_siw_solicitacao)
                        left         join (select sq_siw_solicitacao, count(*)
                                             from pj_rubrica
                                           group by sq_siw_solicitacao
                                          )                    q2 on (q.sq_siw_solicitacao       = q2.sq_siw_solicitacao)
                      left           join pj_projeto           r  on (d.sq_solic_vinculo         = r.sq_siw_solicitacao)
                        left         join siw_solicitacao      r1 on (r.sq_siw_solicitacao       = r1.sq_siw_solicitacao)
                      left           join ct_cc                n  on (b.sq_cc                    = n.sq_cc)
                      left           join co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                        left         join sg_autenticacao      o1 on (o.sq_pessoa                = o1.sq_pessoa)
                          left       join eo_unidade           o2 on (o1.sq_unidade              = o2.sq_unidade)
                      left           join co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                   left              join eo_unidade           c  on (a.sq_unid_executora        = c.sq_unidade)
                     left            join fn_lancamento_log    k  on (j.chave                    = k.sq_siw_solic_log)
                       left          join sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
          where z1.sq_pessoa = p_pessoa
            and ((p_tipo = 1 and b1.sigla = 'EE') or
                 (p_tipo = 2 and b1.sigla = 'AT' and d.quitacao>=trunc(now())) or
                 (p_tipo = 3 and b2.acesso > 0) or
                 (p_tipo = 3 and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo = 4 and b1.sigla <> 'CA'  and b2.acesso > 0) or
                 (p_tipo = 4 and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo = 5) or
                 (p_tipo = 6 and b1.ativo          = 'S' and b2.acesso > 0 and b1.sigla <> 'CI')
                )
            and (p_menu           is null or (p_menu        is not null and a.sq_menu            = p_menu))
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao = p_chave))
            and (p_pais           is null or (p_pais        is not null and d5.sq_banco          = p_pais))
            and (p_regiao         is null or (p_regiao      is not null and f.sq_regiao          = p_regiao))
            and (p_cidade         is null or (p_cidade      is not null and f.sq_cidade          = p_cidade))
            and (p_usu_resp       is null or (p_usu_resp    is not null and (b.executor          = p_usu_resp or 0 < (select count(*) from fn_lancamento_log where destinatario = p_usu_resp and sq_siw_solicitacao = b.sq_siw_solicitacao))))
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and d1.sq_tipo_lancamento = p_sq_orprior))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and b.conclusao          is null and l.sq_unidade = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc              = p_sqcc))
            and (p_projeto        is null or (p_projeto     is not null and ((substr(a.sigla,4)  = 'CONT' and d.sq_solic_vinculo is not null and d.sq_solic_vinculo = p_projeto) or
                                                                             (substr(a.sigla,4) <> 'CONT' and b.sq_solic_pai     is not null and b.sq_solic_pai     = p_projeto)
                                                                            )
                                             )
                )
            and (p_assunto        is null or (p_assunto     is not null and (acentos(b.descricao,null) like '%'||acentos(p_assunto,null)||'%' or acentos(b.justificativa,null) like '%'||acentos(p_assunto,null)||'%')))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (p_prazo          is null or (p_prazo       is not null and b.conclusao          is null and trunc(d.vencimento)-cast(p_prazo as integer)<=trunc(now())))
            and (p_ini_i          is null or (p_ini_i       is not null and d.vencimento         between p_ini_i and p_ini_f))
            and (p_fim_i          is null or (p_fim_i       is not null and d.quitacao           between p_fim_i and p_fim_f))
            and (p_unidade        is null or (p_unidade     is not null and b.sq_unidade         = p_unidade))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and (p_palavra        is null or (p_palavra     is not null and b.codigo_interno     like '%'||p_palavra||'%'))
            and (p_atraso         is null or (p_atraso      is not null and (b4.codigo_interno   like '%'||p_atraso ||'%' or b7.codigo_interno like '%'||p_atraso ||'%' or acentos(b4.titulo) like '%'||acentos(p_atraso)||'%' or acentos(b7.titulo) like '%'||acentos(p_atraso)||'%')))
            and (p_uf             is null or (p_uf          is not null and (b4.codigo_interno   like '%'||p_uf ||'%' or b7.codigo_interno like '%'||p_uf ||'%' or acentos(b4.titulo) like '%'||acentos(p_uf)||'%' or acentos(b7.titulo) like '%'||acentos(p_uf) ||'%')))
            --and (p_uf             is null or (p_uf          is not null and f.co_uf              = p_uf))
            and (p_proponente     is null or (p_proponente  is not null and (acentos(d2.nome,null)          like '%'||acentos(p_proponente,null)||'%') or 
                                                                            (acentos(d2.nome_resumido,null) like '%'||acentos(p_proponente,null)||'%')
                                             )
                )
            and (p_restricao is null or
                 (instr(p_restricao,'PROJ')    = 0 and
                  instr(p_restricao,'ETAPA')   = 0 and
                  instr(p_restricao,'PROP')    = 0 and
                  instr(p_restricao,'RESPATU') = 0 and
                  substr(p_restricao,4,2)      <>'CC'
                 ) or 
                 ((instr(p_restricao,'PROJ')    > 0    and ((substr(a.sigla,4) = 'CONT' and r.sq_siw_solicitacao is not null) or (substr(a.sigla,4) <> 'CONT' and q.sq_siw_solicitacao is not null))) or
                  --(instr(p_restricao,'ETAPA') > 0    and MontaOrdem(q.sq_projeto_etapa,null)  is not null) or                 
                  (instr(p_restricao,'PROP')    > 0    and d.pessoa       is not null) or
                  (instr(p_restricao,'RESPATU') > 0    and b.executor     is not null) or
                  (substr(p_restricao,4,2)      ='CC'  and b.sq_cc        is not null)
                 )
                );
   End If;

  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;
