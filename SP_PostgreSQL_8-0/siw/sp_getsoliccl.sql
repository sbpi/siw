﻿create or replace FUNCTION SP_GetSolicCL
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
    
    --  que recupera as unidades nas quais o usuário informado é titular ou substituto
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
         Exit when l_fase is null;
      End Loop;
      x_fase := substr(x_fase,2,200);
   End If;
   
   -- Monta uma string com todas as unidades subordinadas à que o usuário é responsável
   for crec in c_unidades_resp loop
     l_resp_unid := l_resp_unid ||','''||crec.sq_unidade||'''';
   end loop;
   
 if substr(p_restricao,1,4) = 'CLPC' or substr(p_restricao,1,4) = 'CLLC' or substr(p_restricao,1,4) = 'CLRP' or 
    substr(p_restricao,1,2) = 'GC'   or substr(p_restricao,1,4) = 'GRCL' Then
      -- Recupera as demandas que o usuário pode ver
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
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      coalesce(b.fim, trunc(now())) as fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,                     b.palavra_chave,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                coalesce(d.numero_certame, b.codigo_interno, to_char(b.sq_siw_solicitacao)) as codigo_interno,
                b.codigo_externo,     b.titulo,                      acentos(b.titulo) as ac_titulo,
                b.sq_plano,           b.sq_cc,                       b.observacao,
                b.protocolo_siw,      b.recebedor,
                to_char(b.inclusao,'dd/mm/yyyy, hh24:mi:ss') as phpdt_inclusao,
                to_char(b.conclusao,'dd/mm/yyyy, hh24:mi:ss') as phpdt_conclusao,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then case when n.sq_cc is null
                                         then '???'
                                         else 'Classif: '||n.nome 
                                    end
                               else 'Plano: '||b3.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.nome as nm_tramite,   b1.ordem as or_tramite,
                b1.sigla as sg_tramite,  b1.ativo,                   b1.envia_mail,
                b2.acesso,
                c.sq_tipo_unidade,    c.nome as nm_unidade_exec,     c.informal,
                c.sq_tipo_unidade as tp_exec, c.nome as nm_unidade_exec, c.informal as informal_exec,
                c.vinculada as vinc_exec,c.adm_central as adm_exec,
                a3.sq_pessoa as tit_exec,a4.sq_pessoa as subst_exec,
                c.vinculada,          c.adm_central,
                                      d.sq_especie_documento,        d.sq_eoindicador,
                d.sq_eoindicador,     d.sq_lcfonte_recurso,          d.sq_lcmodalidade,
                d.sq_lcjulgamento,    d.sq_lcsituacao,               d.sq_unidade as sq_unidade_pai,
                d.numero_original,    d.data_recebimento,
                d.processo,           d.indice_base,                 d.tipo_reajuste,
                d.limite_variacao,    d.data_homologacao,            d.data_diario_oficial,
                d.pagina_diario_oficial, d.financeiro_unico,         d.decisao_judicial,
                d.numero_ata,         d.numero_certame,              d.arp,
                d.prioridade,         d.aviso_prox_conc,             d.dias_aviso,
                d.sq_especificacao_despesa, d.interno,               d.dias_validade_proposta,
                d.sq_financeiro,      d.nota_conclusao,              d.data_abertura,
                d.fundo_fixo,         d.sq_modalidade_artigo,        coalesce(d.data_homologacao, b.conclusao) as data_autorizacao,
                case d.prioridade when 0 then 'Alta' when 1 then 'Média' else 'Normal' end as nm_prioridade,
                case d.tipo_reajuste when 0 then 'Não permite' when 1 then 'Com índice' else 'Sem índice' end as nm_tipo_reajuste,
                cast(b.fim as date)-cast(d.dias_aviso as integer) as aviso,
                d1.nome as nm_espec_despesa, d1.codigo as cd_espec_despesa,
                d2.nome as nm_eoindicador,
                d3.nome as nm_lcfonterecurso, d3.codigo as cd_lcfonterecurso,
                d4.nome as nm_lcmodalidade, d4.certame, d4.minimo_pesquisas, d4.minimo_participantes, d4.minimo_propostas_validas,
                d4.enquadramento_inicial, d4.enquadramento_final,
                d4.descricao as ds_lcmodalidade, d4.gera_contrato,
                d41.sigla as sg_modalidade_artigo, d41.descricao as ds_modalidade_artigo,
                d4.nome||' - '||d41.sigla as nm_enquadramento,
                d5.nome as nm_lcjulgamento, d5.item tipo_julgamento,
                d6.nome as nm_lcsituacao,
                d7.nome as nm_especie_documento,
                d8.consumo, d8.permanente, d8.servico, d8.outros,
                case d8.consumo    when 'S' then 'Sim' else 'Não' end as nm_consumo,
                case d8.permanente when 'S' then 'Sim' else 'Não' end as nm_permanente,
                case d8.servico    when 'S' then 'Sim' else 'Não' end as nm_servico,
                case d8.outros     when 'S' then 'Sim' else 'Não' end as nm_outros,
                d81.sq_projeto_rubrica, d81.codigo as cd_rubrica,    d81.nome as nm_rubrica,
                d82.sq_tipo_lancamento, d82.nome   as nm_lancamento, d82.descricao as ds_lancamento,
                e.sq_tipo_unidade,    e.nome as nm_unidade_resp,        e.informal as informal_resp,
                e.vinculada as vinc_resp,e.adm_central as adm_resp,     e.sigla sg_unidade_resp,
                e1.sq_pessoa as titular, e2.sq_pessoa as substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                m.sq_menu as sq_menu_pai,
                n.sq_cc,              n.nome as nm_cc,                  n.sigla as sg_cc,
                o.nome_resumido as nm_solic, o.nome_resumido_ind as nm_solic_ind,
                p.nome_resumido as nm_exec,  p.nome_resumido_ind as nm_exec_ind,
                q.nome_resumido as nm_recebedor,  p.nome_resumido_ind as nm_recebedor_ind
           from siw_menu                                        a 
                inner             join siw_modulo               a1 on (a.sq_modulo                = a1.sq_modulo)
                inner             join eo_unidade               c  on (a.sq_unid_executora        = c.sq_unidade)
                inner             join siw_solicitacao          b  on (a.sq_menu                  = b.sq_menu)
                   inner          join (select x.sq_siw_solicitacao, acesso(x.sq_siw_solicitacao, p_pessoa,null) as acesso
                                          from siw_solicitacao             x
                                               inner  join cl_solicitacao x1 on (x.sq_siw_solicitacao = x1.sq_siw_solicitacao)
                                               inner join siw_menu         y on (x.sq_menu        = y.sq_menu and
                                                                                 y.sq_menu        = coalesce(p_menu, y.sq_menu)
                                                                                )
                                       )                        b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                   inner          join siw_tramite              b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                   inner          join cl_solicitacao           d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                   inner          join eo_unidade               e  on (b.sq_unidade               = e.sq_unidade)
                   inner          join co_cidade                f  on (b.sq_cidade_origem         = f.sq_cidade)
                   left           join pe_plano                 b3 on (b.sq_plano                 = b3.sq_plano)
                   left           join pj_projeto               b4 on (b.sq_solic_pai             = b4.sq_siw_solicitacao)
                     left         join ct_especificacao_despesa d1 on (d.sq_especificacao_despesa = d1.sq_especificacao_despesa)
                     left         join eo_indicador             d2 on (d.sq_eoindicador           = d2.sq_eoindicador)
                     left         join lc_fonte_recurso         d3 on (d.sq_lcfonte_recurso       = d3.sq_lcfonte_recurso)
                     left         join lc_modalidade            d4 on (d.sq_lcmodalidade          = d4.sq_lcmodalidade)
                     left         join lc_modalidade_artigo    d41 on (d.sq_modalidade_artigo     = d41.sq_modalidade_artigo)
                     left         join lc_julgamento            d5 on (d.sq_lcjulgamento          = d5.sq_lcjulgamento)
                     left         join lc_situacao              d6 on (d.sq_lcsituacao            = d6.sq_lcsituacao)
                     left         join pa_especie_documento     d7 on (d.sq_especie_documento     = d7.sq_especie_documento)
                     left         join cl_vinculo_financeiro    d8 on (d.sq_financeiro            = d8.sq_clvinculo_financeiro)
                       left       join pj_rubrica              d81 on (d8.sq_projeto_rubrica      = d81.sq_projeto_rubrica)
                       left       join fn_tipo_lancamento      d82 on (d8.sq_tipo_lancamento      = d82.sq_tipo_lancamento)
                       left       join eo_unidade_resp          e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                       e1.tipo_respons            = 'T'           and
                                                                       e1.fim                     is null
                                                                      )
                       left       join eo_unidade_resp          e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                       e2.tipo_respons            = 'S'           and
                                                                       e2.fim                     is null
                                                                      )
                   left           join siw_solicitacao          m  on (b.sq_solic_pai             = m.sq_siw_solicitacao)
                   left           join ct_cc                    n  on (b.sq_cc                    = n.sq_cc)
                   left           join co_pessoa                o  on (b.solicitante              = o.sq_pessoa)
                   left           join co_pessoa                p  on (b.executor                 = p.sq_pessoa)
                   left           join co_pessoa                q  on (b.recebedor                = q.sq_pessoa)
                   left           join eo_unidade_resp          a3 on (c.sq_unidade               = a3.sq_unidade and
                                                                       a3.tipo_respons            = 'T'           and
                                                                       a3.fim                     is null
                                                                      )
                   left           join eo_unidade_resp          a4 on (c.sq_unidade               = a4.sq_unidade and
                                                                       a4.tipo_respons            = 'S'           and
                                                                       a4.fim                     is null
                                                                      )
          where (p_menu           is null or (p_menu        is not null and a.sq_menu              = p_menu))
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao   = p_chave))
            and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and d.sq_modalidade_artigo = to_number(p_sq_acao_ppa)))
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and b.sq_plano             = p_sq_orprior))
            and (p_pais           is null or (p_pais        is not null and 0 < (select count(*) from cl_solicitacao_item x inner join cl_material y on (x.sq_material = y.sq_material) where x.sq_siw_solicitacao = b.sq_siw_solicitacao and y.sq_tipo_material in (select sq_tipo_material from connectby('cl_tipo_material','sq_tipo_material','sq_tipo_pai',to_char(p_pais),'0') as (sq_tipo_material numeric,sq_tipo_pai numeric, level int)))))
            and (p_regiao         is null or (p_regiao      is not null and d.processo           like '%'||p_regiao||'%'))
            and (p_cidade         is null or (p_cidade      is not null and d.processo           like '%'||p_cidade||'%'))
            and (p_usu_resp       is null or (p_usu_resp    is not null and d4.sq_lcmodalidade   = p_usu_resp))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and b1.sigla <> 'AT' and e.sq_unidade = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc                = p_sqcc))
            and (p_projeto        is null or (p_projeto     is not null and b.sq_solic_pai         = p_projeto))
            and (p_processo       is null or (p_processo    = 'CLASSIF' and b.sq_cc is not null) or (p_processo <> 'CLASSIF' and m.sq_menu = to_number(p_processo)))
            and (p_uf             is null or (p_uf          is not null and d6.sq_lcsituacao       = to_number(p_uf)))
            and (p_proponente     is null or (p_proponente  is not null and 0 < (select count(*) from cl_solicitacao_item x inner join cl_material y on (x.sq_material = y.sq_material) where x.sq_siw_solicitacao = b.sq_siw_solicitacao and acentos(y.nome,null) like '%'||acentos(p_proponente,null)||'%')))
            and (p_assunto        is null or (p_assunto     is not null and acentos(b.titulo,null) like '%'||acentos(p_assunto,null)||'%'))
            and (p_palavra        is null or (p_palavra     is not null and acentos(d.numero_certame,null) like '%'||acentos(p_palavra,null)||'%'))
            and (p_empenho        is null or (p_empenho     is not null and (acentos(b.codigo_interno,null) like '%'||acentos(p_empenho,null)||'%' or acentos(d.numero_certame,null) like '%'||acentos(p_empenho,null)||'%')))
            and (p_prioridade     is null or (p_prioridade  is not null and d.prioridade         = p_prioridade))
            and (coalesce(p_ativo,'N') = 'N' or (p_ativo = 'S' and d.decisao_judicial = p_ativo))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (p_prazo          is null or (p_prazo       is not null and b1.sigla <> 'AT' and cast(cast(b.fim as date)-cast(now() as date) as integer)+1 <=p_prazo))
            and (p_ini_i          is null or (p_ini_i       is not null and d.data_abertura between p_ini_i and p_ini_f))
            and (p_fim_i          is null or (p_fim_i       is not null and coalesce(d.data_homologacao, b.conclusao) between p_fim_i and p_fim_f))
            and (coalesce(p_atraso,'N') = 'N' or (p_atraso = 'S' and b1.sigla <> 'AT' and cast(b.fim as date)+1<cast(now() as date)))
            and (p_unidade        is null or (p_unidade     is not null and b.sq_unidade           = p_unidade))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante          = p_solicitante))
            and ((instr(p_restricao,'SITUACAO') = 0 and 
                  instr(p_restricao,'PROJ') = 0 and 
                  instr(p_restricao,'MODAL') = 0  and 
                  instr(p_restricao,'ENQ') = 0  and 
                  instr(p_restricao,'ABERTURA') = 0 and 
                  instr(p_restricao,'AUTORIZ') = 0
                 ) or 
                 ((instr(p_restricao,'SITUACAO') > 0 and d6.sq_lcsituacao      is not null) or
                  (instr(p_restricao,'PROJ')     > 0 and b4.sq_siw_solicitacao is not null) or
                  (instr(p_restricao,'MODAL')    > 0 and d4.sq_lcmodalidade    is not null) or
                  (instr(p_restricao,'ENQ')      > 0 and b1.sigla              = 'AT' and d41.sq_modalidade_artigo is not null) or
                  (instr(p_restricao,'ABERTURA') > 0 and d.data_abertura       is not null) or
                  (instr(p_restricao,'AUTORIZ')  > 0 and (d.data_homologacao   is not null or b.conclusao is not null))
                 )
                )
            and ((p_tipo         = 1     and b1.sigla = 'CI'   and b.cadastrador = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and b1.sigla <> 'CI' and b.executor = p_pessoa and b.conclusao is null) or
                 (p_tipo         = 2     and b1.ativo = 'S' and b1.sigla <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and b1.sigla <> 'CA') or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0 and b1.sigla <> 'CI')
                );
   Elsif p_restricao = 'CONTRATO' Then
      -- Recupera as solicitações que o usuário pode ver
      open p_result for 
         select distinct a.sq_siw_solicitacao, a.codigo_interno, a.titulo, 
                b.numero_certame, b.processo,
                coalesce(b.numero_certame,a.codigo_interno) as cd_certame,
                c.ordem,
                c1.codigo_interno as cd_material, c1.nome as nm_material,
                e.sq_pessoa, e.sq_tipo_pessoa, e.nome as nm_fornecedor,
                coalesce(e1.cpf, e2.cnpj) as cd_fornecedor
           from siw_solicitacao                           a
                inner       join siw_menu                 a1 on (a.sq_menu             = a1.sq_menu)
                inner       join siw_tramite              a2 on (a.sq_siw_tramite      = a2.sq_siw_tramite and
                                                                 a2.sigla              = 'AT'
                                                                )
                inner       join cl_solicitacao           b  on (a.sq_siw_solicitacao  = b.sq_siw_solicitacao)
                  inner     join cl_solicitacao_item      c  on (b.sq_siw_solicitacao  = c.sq_siw_solicitacao)
                    inner   join cl_material              c1 on (c.sq_material         = c1.sq_material)
                    inner   join cl_item_fornecedor       d  on (c.sq_solicitacao_item = d.sq_solicitacao_item and
                                                                 d.pesquisa            = 'N' and
                                                                 d.vencedor            = 'S'
                                                                )
                      inner join co_pessoa                e  on (d.fornecedor          = e.sq_pessoa)
                      left  join co_pessoa_fisica         e1 on (e.sq_pessoa           = e1.sq_pessoa)
                      left  join co_pessoa_juridica       e2 on (e.sq_pessoa           = e2.sq_pessoa)
                  left      join cl_solicitacao_item_vinc f  on (c.sq_solicitacao_item = f.item_licitacao)
                    left    join cl_solicitacao_item      g  on (f.item_pedido         = g.sq_solicitacao_item)
                      left  join ac_acordo                h  on (g.sq_siw_solicitacao  = h.sq_siw_solicitacao)
          where a1.sq_menu           = p_menu
            and h.sq_siw_solicitacao is null
         order by b.numero_certame, e.nome, lpad(c.ordem,4);
   Elsif p_restricao = 'FUNDO_FIXO' Then
      -- Recupera as solicitações de compras passíveis de pagamento por fundo fixo
      open p_result for 
         select b.sq_siw_solicitacao, b.titulo, b.codigo_interno, b.codigo_externo
           from siw_solicitacao             b
                inner   join siw_tramite    b1 on (b.sq_siw_tramite     = b1.sq_siw_tramite and
                                                   b1.sigla             = 'AT'
                                                  )
                inner   join cl_solicitacao d  on (b.sq_siw_solicitacao = d.sq_siw_solicitacao and
                                                   d.fundo_fixo         = 'S'
                                                  )
                left    join (select w.sq_siw_solicitacao, w.sq_solic_vinculo
                                from fn_lancamento                w
                                     inner   join siw_solicitacao x on (w.sq_siw_solicitacao = x.sq_siw_solicitacao)
                                       inner join siw_tramite     y on (x.sq_siw_tramite     = y.sq_siw_tramite and
                                                                        y.sigla             <> 'CA'
                                                                       )
                               where w.sq_solic_vinculo is not null
                             )              e  on (b.sq_siw_solicitacao = e.sq_solic_vinculo)
          where b.sq_menu             = p_menu
            and (e.sq_siw_solicitacao is null or (e.sq_siw_solicitacao is not null and e.sq_siw_solicitacao = p_chave));
   Else -- Trata a vinculação entre serviços
      -- Recupera as solicitações que o usuário pode ver
      open p_result for 
         select b.sq_siw_solicitacao, b.codigo_interno,
                case when d.sq_siw_solicitacao is not null 
                     then b.titulo
                     else case when e.sq_siw_solicitacao is not null
                               then e.titulo
                               else case when f.sq_siw_solicitacao is not null
                                         then f1.titulo
                                         else null
                                    end
                          end
                end as titulo
           from siw_menu                     a
                inner join siw_modulo        a1 on (a.sq_modulo          = a1.sq_modulo)
                inner join siw_menu_relac    a2 on (a.sq_menu            = a2.servico_cliente and
                                                    a2.servico_cliente   = to_number(p_restricao)
                                                   )
                inner join siw_solicitacao   b  on (a2.servico_fornecedor= b.sq_menu and
                                                    a2.sq_siw_tramite    = b.sq_siw_tramite and
                                                    b.sq_menu            = coalesce(p_menu, b.sq_menu)
                                                   )
                inner   join siw_menu        b2 on (b.sq_menu            = b2.sq_menu)
                  inner join siw_modulo      b3 on (b2.sq_modulo         = b3.sq_modulo)
                left    join cl_solicitacao  d  on (b.sq_siw_solicitacao = d.sq_siw_solicitacao)
                left    join (select x.sq_siw_solicitacao, y.codigo_interno, 
                                     case when y.titulo is not null
                                          then y.titulo
                                          else w.nome_resumido||' - '||case when z.sq_cc is not null then z.nome else k1.titulo end||' ('||to_char(y.inicio,'dd/mm/yyyy')||'-'||to_char(y.fim,'dd/mm/yyyy')||')' end as titulo
                                from ac_acordo                     x
                                     left join     co_pessoa       w  on x.outra_parte         = w.sq_pessoa
                                     join          siw_solicitacao y  on x.sq_siw_solicitacao  = y.sq_siw_solicitacao
                                       left   join ct_cc           z  on y.sq_cc               = z.sq_cc
                                       left   join cl_solicitacao  k  on y.sq_solic_pai        = k.sq_siw_solicitacao
                                         left join siw_solicitacao k1 on (k.sq_siw_solicitacao = k1.sq_siw_solicitacao)
                             )               e  on (b.sq_siw_solicitacao = e.sq_siw_solicitacao)
                left    join pe_programa     f  on (b.sq_siw_solicitacao = f.sq_siw_solicitacao)
                  left  join siw_solicitacao f1 on (f.sq_siw_solicitacao = f1.sq_siw_solicitacao)
          where a.sq_menu        = to_number(p_restricao)
            and b.sq_menu        = coalesce(p_menu, b.sq_menu)
            and (acesso(b.sq_siw_solicitacao,p_pessoa) > 0 or
                 InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0
                )
         order by titulo;
   End If;

  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;