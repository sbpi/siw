create or replace procedure SP_GetMTBaixaBem
   (p_cliente      in number,
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
         l_fase := substr(l_fase,Instr(l_fase,',')+1,200);
         Exit when length(l_fase)=0 or l_fase is null;
      End Loop;
      x_fase := substr(x_fase,2,200);
   End If;
   
   -- Monta uma string com todas as unidades subordinadas à que o usuário é responsável
   for crec in c_unidades_resp loop
     l_resp_unid := l_resp_unid ||','''||crec.sq_unidade||'''';
   end loop;
   
   if substr(p_restricao,1,2) = 'MT' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for 
         select a.sq_mtsaida,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      coalesce(b.fim, trunc(sysdate)) as fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,                     b.palavra_chave,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.codigo_externo,     b.titulo,                      acentos(b.titulo) as ac_titulo,
                b.sq_plano,           b.sq_cc,                       b.observacao,
                b.protocolo_siw,      b.recebedor,                   b.codigo_interno,
                to_char(b.inclusao,'dd/mm/yyyy, hh24:mi:ss') as phpdt_inclusao,
                to_char(b.conclusao,'dd/mm/yyyy, hh24:mi:ss') as phpdt_conclusao,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then case when b7.sq_cc is null
                                         then '???'
                                         else 'Classif: '||b7.nome 
                                    end
                               else 'Plano: '||b4.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                codigo2numero(coalesce(b.codigo_interno,to_char(b.sq_siw_solicitacao))) as ord_codigo_interno,
                b1.nome as nm_tramite,   b1.ordem as or_tramite,
                b1.sigla as sg_tramite,  b1.ativo,                   b1.envia_mail,
                b2.sq_tipo_unidade,   b2.nome as nm_unidade_resp,b2.informal as informal_resp,
                b2.vinculada as vinc_resp,b2.adm_central as adm_resp,b2.sigla sg_unidade_resp,
                b3.sq_pais,           b3.sq_regiao,                  b3.co_uf,
                case when b.protocolo_siw is null 
                     then null
                     else to_char(b5.numero_documento)||'/'||substr(to_char(b5.ano),3)
                end as processo,
                to_char(b5.prefixo)||'.'||substr(1000000+to_char(b5.numero_documento),2,6)||'/'||to_char(b5.ano)||'-'||substr(100+to_char(b5.digito),2,2) as protocolo_completo,
                b6.sq_menu as sq_menu_pai,
                b7.sq_cc,             b7.nome as nm_cc,              b7.sigla as sg_cc,
                b8.nome_resumido as nm_solic,                        b8.nome_resumido_ind as nm_solic_ind,
                b9.nome_resumido as nm_exec,                         b9.nome_resumido_ind as nm_exec_ind,
                ba.nome_resumido as nm_recebedor,                    ba.nome_resumido_ind as nm_recebedor_ind,
                bb.acesso,
                c.sq_tipo_movimentacao, c.nome as nm_tp_mov,         c.entrada as ent_tp_mov, 
                c.saida as sai_tp_mov, c.orcamentario as orc_tp_mov, c.consumo as con_tp_mov, 
                c.permanente as per_tp_mov,                          c.inativa_bem as in_tp_mov, 
                c.ativo as at_tp_mov,
                d.sq_almoxarifado, d.nome nm_almoxarifado,
                f.sq_pessoa as sq_fornecedor,                        f.nome as nm_fornecedor, 
                f.nome_resumido as nm_res_fornecedor,                f.nome_indice as nm_fornecedor_ind,
                f1.sq_tipo_pessoa as sq_tipo_fornecedor,             f1.nome as nm_tipo_fornecedor,
                f2.cpf, 
                f3.cnpj,
                coalesce(g.qtd,0) as qt_itens
           from mt_saida                                        a 
                inner             join siw_solicitacao          b  on (a.sq_siw_solicitacao       = b.sq_siw_solicitacao)
                   inner          join (select x.sq_siw_solicitacao, acesso(x.sq_siw_solicitacao, p_pessoa,null) as acesso
                                          from siw_solicitacao             x
                                               inner  join mt_saida        x1 on (x.sq_siw_solicitacao = x1.sq_siw_solicitacao)
                                               inner join siw_menu         y on (x.sq_menu        = y.sq_menu and
                                                                                 y.tramite        = 'S' and 
                                                                                 y.sigla          = 'MTBAIXA'
                                                                                )
                                       )                        bb on (b.sq_siw_solicitacao       = bb.sq_siw_solicitacao)
                   inner          join siw_tramite              b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                   inner          join eo_unidade               b2 on (b.sq_unidade               = b2.sq_unidade)
                   inner          join co_cidade                b3 on (b.sq_cidade_origem         = b3.sq_cidade)
                   left           join pe_plano                 b4 on (b.sq_plano                 = b4.sq_plano)
                   left           join pa_documento             b5 on (b.protocolo_siw            = b5.sq_siw_solicitacao)
                   left           join siw_solicitacao          b6 on (b.sq_solic_pai             = b6.sq_siw_solicitacao)
                   left           join ct_cc                    b7 on (b.sq_cc                    = b7.sq_cc)
                   left           join co_pessoa                b8 on (b.solicitante              = b8.sq_pessoa)
                   left           join co_pessoa                b9 on (b.executor                 = b9.sq_pessoa)
                   left           join co_pessoa                ba on (b.recebedor                = ba.sq_pessoa)
                inner             join mt_tipo_movimentacao     c  on (a.sq_tipo_movimentacao     = c.sq_tipo_movimentacao)
                inner             join mt_almoxarifado          d  on (a.sq_almoxarifado          = d.sq_almoxarifado)
                inner             join co_pessoa                f  on (a.sq_pessoa_destino        = f.sq_pessoa)
                  inner           join co_tipo_pessoa           f1 on (f.sq_tipo_pessoa           = f1.sq_tipo_pessoa)
                    left          join co_pessoa_fisica         f2 on (f.sq_pessoa                = f2.sq_pessoa)
                    left          join co_pessoa_juridica       f3 on (f.sq_pessoa                = f3.sq_pessoa)
                left              join (select x.sq_mtSaida, count(*) as qtd
                                       	  from mt_saida_item       w
                                               inner join mt_saida x on (w.sq_mtSaida = x.sq_mtSaida)
                                        group by x.sq_mtSaida
                                       )                        g  on (a.sq_mtSaida                = g.sq_mtSaida)
          where d.cliente         = p_cliente
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao   = p_chave))
            --and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and d.sq_modalidade_artigo = to_number(p_sq_acao_ppa)))
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and b.sq_plano             = p_sq_orprior))
            and (p_pais           is null or (p_pais        is not null and 0 < (select count(*) from mt_saida_item x inner join cl_material y on (x.sq_material = y.sq_material) where x.sq_mtSaida = a.sq_mtSaida and y.sq_tipo_material in (select sq_tipo_material from cl_tipo_material connect by prior sq_tipo_material = sq_tipo_pai start with sq_tipo_material=p_pais))))
            --and (p_regiao         is null or (p_regiao      is not null and d.processo           like '%'||p_regiao||'%'))
            --and (p_cidade         is null or (p_cidade      is not null and d.processo           like '%'||p_cidade||'%'))
            --and (p_usu_resp       is null or (p_usu_resp    is not null and d4.sq_lcmodalidade   = p_usu_resp))
            --and (p_uorg_resp      is null or (p_uorg_resp   is not null and b1.sigla <> 'AT' and e.sq_unidade = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc                = p_sqcc))
            and (p_projeto        is null or (p_projeto     is not null and b.sq_solic_pai         = p_projeto))
            and (p_processo       is null or (p_processo    = 'CLASSIF' and b.sq_cc is not null) or (p_processo <> 'CLASSIF' and b6.sq_menu = to_number(p_processo)))
            --and (p_uf             is null or (p_uf          is not null and d6.sq_lcsituacao       = to_number(p_uf)))
            and (p_proponente     is null or (p_proponente  is not null and 0 < (select count(*) from mt_saida_item x inner join cl_material y on (x.sq_material = y.sq_material) where x.sq_mtSaida = a.sq_mtSaida and acentos(y.nome,null) like '%'||acentos(p_proponente,null)||'%')))
            and (p_assunto        is null or (p_assunto     is not null and acentos(b.titulo,null) like '%'||acentos(p_assunto,null)||'%'))
            and (p_palavra        is null or (p_palavra     is not null and (f.nome_indice like '%'||acentos(p_palavra,null)||'%' or f.nome_resumido_ind like '%'||acentos(p_palavra,null)||'%')))
            and (p_empenho        is null or (p_empenho     is not null and acentos(b.codigo_interno,null) like '%'||acentos(p_empenho,null)||'%'))
            --and (p_prioridade     is null or (p_prioridade  is not null and d.prioridade         = p_prioridade))
            --and (coalesce(p_ativo,'N') = 'N' or (p_ativo = 'S' and d.decisao_judicial = p_ativo))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (p_prazo          is null or (p_prazo       is not null and b1.sigla <> 'AT' and cast(cast(b.fim as date)-cast(sysdate as date) as integer)+1 <=p_prazo))
            --and (p_ini_i          is null or (p_ini_i       is not null and (a.data_efetivacao between p_ini_i and p_ini_f)))
            and (p_fim_i          is null or (p_fim_i       is not null and b.conclusao between p_fim_i and p_fim_f))
            and (coalesce(p_atraso,'N') = 'N' or (p_atraso = 'S' and b1.sigla <> 'AT' and cast(b.fim as date)+1<cast(sysdate as date)))
            and (p_unidade        is null or (p_unidade     is not null and b.sq_unidade           = p_unidade))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante          = p_solicitante))
            and ((p_tipo         = 1 and b1.sigla = 'CI' and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2 and b1.ativo = 'S'  and b1.sigla <> 'CI' and bb.acesso > 15) or
                 (p_tipo         = 3 and bb.acesso > 0) or
                 (p_tipo         = 3 and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4 and b1.sigla <> 'CA') or
                 (p_tipo         = 5) or
                 (p_tipo         = 6 and b1.ativo = 'S' and bb.acesso > 0 and b1.sigla <> 'CI')
                );
   Elsif instr(p_restricao,'ITENS') > 0 Then
      -- Recupera os itens da baixa que o usuário pode ver
      open p_result for 
         select a.sq_mtsaida, 
                c.sq_saida_item, c.sq_permanente, c.valor_unitario, c.data_efetivacao,
                p.numero_rgp, p.descricao_complementar,
                p1.nome||' '||p.descricao_complementar||
                        case when p.marca is not null then ' '||p.marca end||
                        case when p.modelo is not null then ' '||p.modelo end||
                        case when p.numero_serie is not null then ' Série: '||p.numero_serie end nome_completo,
                bx.cd_baixa
           from mt_saida                                        a 
                inner             join siw_solicitacao          b  on (a.sq_siw_solicitacao       = b.sq_siw_solicitacao)
                  inner           join mt_saida_item            c  on (a.sq_mtSaida               = c.sq_mtSaida)
                    inner         join mt_permanente            p  on (c.sq_permanente            = p.sq_permanente)
                      inner       join cl_material              p1 on (p.sq_material              = p1.sq_material)
                      inner       join cl_tipo_material         p2 on (p1.sq_tipo_material        = p2.sq_tipo_material)
                      left        join (select k.sq_permanente, n.sq_siw_solicitacao sq_baixa, n.codigo_interno cd_baixa
                                          from mt_permanente                         k
                                               inner       join mt_saida_item        l  on k.sq_permanente        = l.sq_permanente
                                                 inner     join mt_saida             m  on l.sq_mtSaida           = m.sq_mtSaida
                                                   inner   join siw_solicitacao      n  on m.sq_siw_solicitacao   = n.sq_siw_solicitacao
                                  )                             bx   on (p.sq_permanente = bx.sq_permanente and b.sq_siw_solicitacao <> bx.sq_baixa)
          where a.sq_siw_solicitacao   = p_chave
            and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and c.sq_saida_item        = to_number(p_sq_acao_ppa)));
   End If;
end SP_GetMTBaixaBem;
/
