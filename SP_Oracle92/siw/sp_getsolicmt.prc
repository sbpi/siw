create or replace procedure SP_GetSolicMT
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
         l_fase := substr(l_fase,Instr(l_fase,',')+1,200);
         Exit when l_fase is null;
      End Loop;
      x_fase := substr(x_fase,2,200);
   End If;
   
   -- Monta uma string com todas as unidades subordinadas à que o usuário é responsável
   for crec in c_unidades_resp loop
     l_resp_unid := l_resp_unid ||','''||crec.sq_unidade||'''';
   end loop;
   
 if substr(p_restricao,1,2) = 'MT' or substr(p_restricao,1,4) = 'GRMT' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for 
         select a.sq_menu,                       a.sq_modulo,                                a.nome,
                a.tramite,                       a.ultimo_nivel,                             a.p1,
                a.p2,                            a.p3,                                       a.p4,
                a.sigla,                         a.descentralizado,                          a.externo,
                a.sq_unid_executora,             a.emite_os,                                 a.consulta_opiniao,
                a.exibe_relatorio,               a.data_hora,
                a1.nome as nm_modulo,            a1.sigla as sg_modulo,
                b.sq_siw_solicitacao,            b.sq_siw_tramite,                           b.solicitante,
                b.cadastrador,                   b.executor,                                 b.descricao,
                b.justificativa,                 b.inicio,                                   coalesce(b.fim, trunc(sysdate)) as fim,
                b.inclusao,                      b.ultima_alteracao,                         b.conclusao,
                b.valor,                         b.opiniao,                                  b.palavra_chave,
                b.sq_solic_pai,                  b.sq_unidade,                               b.sq_cidade_origem,
                coalesce(b.codigo_interno, to_char(b.sq_siw_solicitacao)) as codigo_interno,
                b.codigo_externo,                b.titulo,                                   acentos(b.titulo) as ac_titulo,
                b.sq_plano,                      b.sq_cc,                                    b.observacao,
                b.protocolo_siw,                 b.recebedor,                                dados_solic(b.sq_siw_solicitacao) as dados_solic,
                to_char(b.inclusao,'dd/mm/yyyy, hh24:mi:ss') as phpdt_inclusao,
                to_char(b.conclusao,'dd/mm/yyyy, hh24:mi:ss') as phpdt_conclusao,
                b1.nome as nm_tramite,           b1.ordem as or_tramite,                     b1.sigla as sg_tramite,
                b1.ativo,
                b2.acesso,
                c.nome as nm_unidade_exec,       c.sigla as sg_unidade_exec,
                d.sq_mtsaida,                    d.sq_unidade_origem,                        d.sq_unidade_destino,
                d.sq_pessoa_destino,
                case when b.protocolo_siw is null then null else to_char(b5.numero_documento)||'/'||substr(to_char(b5.ano),2) end as processo,
                case when b.protocolo_siw is null 
                     then null 
                     else to_char(b5.prefixo)||'.'||substr(1000000+to_char(b5.numero_documento),2,6)||'/'||to_char(b5.ano)||'-'||substr(100+to_char(b5.digito),2,2)
                end as protocolo_completo,
                cast(b.fim as date)-1 as aviso,
                d1.sq_almoxarifado,              d1.nome as nm_almoxarifado,                 d1.ativo as at_almoxarifado,
                d2.sq_tipo_movimentacao,         d2.nome as nm_tp_mov,                       d2.entrada as ent_tp_mov, 
                d2.saida as sai_tp_mov,          d2.orcamentario as orc_tp_mov,              d2.consumo as con_tp_mov, 
                d2.permanente as per_tp_mov,     d2.inativa_bem as in_tp_mov,                d2.ativo as at_tp_mov,
                d3.nome as nm_unidade_ori,       d3.sigla as sg_unidade_ori,
                d4.nome as nm_unidade_dest,      d4.sigla as sg_unidade_dest,
                d5.nome_resumido as nm_pes_dest, d5.nome_resumido_ind as nm_pes_dest_ind,
                e.nome as nm_unidade_solic,      e.sigla as sg_unidade_solic,
                o.nome_resumido as nm_solic,     o.nome_resumido_ind as nm_solic_ind,
                coalesce(n.qtd,0) as qt_itens,
                p.nome_resumido as nm_exec,      p.nome_resumido_ind as nm_exec_ind,
                q.nome_resumido as nm_recebedor, p.nome_resumido_ind as nm_recebedor_ind
           from siw_menu                                        a 
                inner             join siw_modulo               a1 on (a.sq_modulo                = a1.sq_modulo)
                inner             join eo_unidade               c  on (a.sq_unid_executora        = c.sq_unidade)
                inner             join siw_solicitacao          b  on (a.sq_menu                  = b.sq_menu)
                   inner          join siw_tramite              b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                   inner          join (select x.sq_siw_solicitacao, acesso(x.sq_siw_solicitacao, p_pessoa,null) as acesso
                                          from siw_solicitacao             x
                                               inner join mt_saida        x1 on (x.sq_siw_solicitacao = x1.sq_siw_solicitacao)
                                               inner join siw_menu         y on (x.sq_menu            = y.sq_menu and
                                                                                 y.sq_menu            = p_menu
                                                                                )
                                       )                        b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                   left           join pa_documento             b5 on (b.protocolo_siw            = b5.sq_siw_solicitacao)
                   inner          join mt_saida                 d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                     inner        join mt_almoxarifado          d1 on (d.sq_almoxarifado          = d1.sq_almoxarifado)
                     inner        join mt_tipo_movimentacao     d2 on (d.sq_tipo_movimentacao     = d2.sq_tipo_movimentacao)
                     inner        join eo_unidade               d3 on (d.sq_unidade_origem        = d3.sq_unidade)
                     left         join eo_unidade               d4 on (d.sq_unidade_destino       = d4.sq_unidade)
                     left         join co_pessoa                d5 on (d.sq_pessoa_destino        = d5.sq_pessoa)
                   inner          join eo_unidade               e  on (b.sq_unidade               = e.sq_unidade)
                   left           join (select x.sq_siw_solicitacao, count(*) as qtd
                                       	  from siw_menu                     w
                                               inner	 join co_pessoa      w1 on (w.sq_pessoa          = w1.sq_pessoa_pai and
                                                                                  w1.sq_pessoa         = p_pessoa
                                                                                 )
                                               inner   join siw_solicitacao x on (w.sq_menu            = x.sq_menu)
                                               inner   join mt_saida        y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                                 inner join mt_saida_item   z on (y.sq_mtsaida         = z.sq_mtsaida)
                                         where w.sq_menu = p_menu
                                        group by x.sq_siw_solicitacao
                                       )                        n  on (d.sq_siw_solicitacao       = n.sq_siw_solicitacao)
                   inner          join co_pessoa                o  on (b.solicitante              = o.sq_pessoa)
                   left           join co_pessoa                p  on (b.executor                 = p.sq_pessoa)
                   left           join co_pessoa                q  on (b.recebedor                = q.sq_pessoa)
          where (p_menu           is null or (p_menu        is not null and a.sq_menu              = p_menu))
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao   = p_chave))
            --and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and d.sq_modalidade_artigo = to_number(p_sq_acao_ppa)))
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and b.sq_plano             = p_sq_orprior))
            and (p_pais           is null or (p_pais        is not null and 0 < (select count(*) from cl_solicitacao_item x inner join cl_material y on (x.sq_material = y.sq_material) where x.sq_siw_solicitacao = b.sq_siw_solicitacao and y.sq_tipo_material in (select sq_tipo_material from cl_tipo_material connect by prior sq_tipo_material = sq_tipo_pai start with sq_tipo_material=p_pais))))
            --and (p_regiao         is null or (p_regiao      is not null and d.processo           like '%'||p_regiao||'%'))
            --and (p_cidade         is null or (p_cidade      is not null and d.processo           like '%'||p_cidade||'%'))
            --and (p_usu_resp       is null or (p_usu_resp    is not null and d4.sq_lcmodalidade   = p_usu_resp))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and b1.sigla <> 'AT' and e.sq_unidade = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc                = p_sqcc))
            and (p_projeto        is null or (p_projeto     is not null and b.sq_solic_pai         = p_projeto))
            --and (p_processo       is null or (p_processo    = 'CLASSIF' and b.sq_cc is not null) or (p_processo <> 'CLASSIF' and m.sq_menu = to_number(p_processo)))
            --and (p_uf             is null or (p_uf          is not null and d6.sq_lcsituacao       = to_number(p_uf)))
            and (p_proponente     is null or (p_proponente  is not null and 0 < (select count(*) from cl_solicitacao_item x inner join cl_material y on (x.sq_material = y.sq_material) where x.sq_siw_solicitacao = b.sq_siw_solicitacao and acentos(y.nome,null) like '%'||acentos(p_proponente,null)||'%')))
            and (p_assunto        is null or (p_assunto     is not null and acentos(b.titulo,null) like '%'||acentos(p_assunto,null)||'%'))
            --and (p_palavra        is null or (p_palavra     is not null and acentos(d.numero_certame,null) like '%'||acentos(p_palavra,null)||'%'))
            and (p_empenho        is null or (p_empenho     is not null and acentos(b.codigo_interno,null) like '%'||acentos(p_empenho,null)||'%'))
            --and (p_prioridade     is null or (p_prioridade  is not null and d.prioridade         = p_prioridade))
            --and (coalesce(p_ativo,'N') = 'N' or (p_ativo = 'S' and d.decisao_judicial = p_ativo))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (p_prazo          is null or (p_prazo       is not null and b1.sigla <> 'AT' and cast(cast(b.fim as date)-cast(sysdate as date) as integer)+1 <=p_prazo))
            and (p_ini_i          is null or (p_ini_i       is not null and trunc(b.inclusao)  between p_ini_i and p_ini_f))
            and (p_fim_i          is null or (p_fim_i       is not null and ((b1.sigla  = 'EE' and b.fim between p_fim_i and p_fim_f) or 
                                                                             (b1.sigla <> 'EE' and 0 < (select count(*) from mt_saida_item where sq_mtsaida = d.sq_mtsaida and data_efetivacao between p_fim_i and p_fim_f))
                                                                            )
                                             )
                )
            and (coalesce(p_atraso,'N') = 'N' or (p_atraso = 'S' and b1.sigla <> 'AT' and cast(b.fim as date)+1<cast(sysdate as date)))
            and (p_unidade        is null or (p_unidade     is not null and d.sq_unidade_destino   = p_unidade))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante          = p_solicitante))
            and ((instr(p_restricao,'AUTORIZ') = 0
                 ) or 
                 ((instr(p_restricao,'AUTORIZ')  > 0 and b.conclusao is not null)
                 )
                )
            and ((p_tipo         = 1 and b1.sigla = 'CI' and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2 and b1.ativo = 'S'  and b1.sigla <> 'CI' and b.executor = p_pessoa and b.conclusao is null) or
                 (p_tipo         = 2 and b1.ativo = 'S'  and b1.sigla <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3 and b2.acesso > 0) or
                 (p_tipo         = 3 and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4 and b1.sigla <> 'CA') or
                 (p_tipo         = 5) or
                 (p_tipo         = 6 and b1.ativo = 'S' and b2.acesso > 0 and b1.sigla <> 'CI')
                );
   Elsif p_restricao = 'ALINV' Then
      -- Recupera o inventário de estoque
      open p_result for 
        select a.sq_almoxarifado,                   a.nome as nm_almoxarifado,
               a1.sq_localizacao,                   a1.nome as nm_localizacao,
               a11.sq_unidade,                      a11.nome as nm_unidade,
               a12.sq_pessoa_endereco,              a12.logradouro,
               b.sq_estoque,                        b.ultima_saida,                                  b.ultima_entrada,
               b.ultimo_preco_compra,               b.consumo_medio_mensal,                          b.ponto_ressuprimento,
               b.ciclo_compra,                      b.disponivel,                                    b.preco_medio,
               b.estoque_minimo,
               b1.sq_material,                      b1.nome as nm_material,                          montanometipomaterial(b1.sq_tipo_material,'CODCOMP') as nm_tipo_completo,
               b11.sq_unidade_medida,               b11.nome as nm_unidade_medida,                   b11.sigla as sg_unidade_medida,
               b12.sq_tipo_material,                b12.nome as nm_tipo_material,                    b12.classe,
               case b12.classe when 1 then 'Medicamento' when 2 then 'Alimento' when 3 then 'Consumo' when 4 then 'Permanente' when 5 then 'Serviço' end as nm_classe,
               c.sq_almoxarifado_local,             c.saldo_atual,                                   montaNomeAlmoxLocal(c.sq_almoxarifado_local) as nm_almoxarifado_local
          from mt_almoxarifado                                a
               inner             join eo_localizacao         a1 on (a.sq_localizacao         = a1.sq_localizacao)
                 inner           join eo_unidade            a11 on (a1.sq_unidade            = a11.sq_unidade)
                 inner           join co_pessoa_endereco    a12 on (a1.sq_pessoa_endereco    = a12.sq_pessoa_endereco)
               inner             join mt_estoque              b on (a.sq_almoxarifado        = b.sq_almoxarifado)
                 inner           join cl_material            b1 on (b.sq_material            = b1.sq_material)
                   inner         join co_unidade_medida     b11 on (b1.sq_unidade_medida     = b11.sq_unidade_medida)
                   inner         join cl_tipo_material      b12 on (b1.sq_tipo_material      = b12.sq_tipo_material)
                 inner           join (select w.sq_estoque, w.sq_almoxarifado_local, sum(w.saldo_atual) as saldo_atual
                                         from mt_estoque_item                  w
                                              inner join mt_almoxarifado_local x on (w.sq_almoxarifado_local = x.sq_almoxarifado_local)
                                              inner join mt_entrada_item       y on (w.sq_entrada_item       = y.sq_entrada_item)
                                        where x.sq_almoxarifado = p_chave
                                       group by w.sq_estoque, w.sq_almoxarifado_local
                                      )                       c on (b.sq_estoque             = c.sq_estoque)
         where a.cliente         = p_menu
           and a.sq_almoxarifado = p_chave
           and (coalesce(p_ativo,'N') = 'N' or (p_ativo = 'S' and b.disponivel = p_ativo))
           and (p_pais           is null or (p_pais        is not null and b12.sq_tipo_material in (select sq_tipo_material
                                                                                                      from cl_tipo_material
                                                                                                    connect by prior sq_tipo_material = sq_tipo_pai
                                                                                                    start with sq_tipo_material = p_pais
                                                                                                   )
                                            )
               )
           and (p_proponente     is null or (p_proponente  is not null and acentos(b1.nome,null) like '%'||acentos(p_proponente,null)||'%'))
           and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b12.classe||'''') > 0))
        order by a.nome, b1.nome, nm_almoxarifado_local;
   Elsif p_restricao = 'ALENTRADA' Then
      -- Recupera o inventário de estoque
      open p_result for 
        select a.sq_almoxarifado,                   a.nome as nm_almoxarifado,
               a1.sq_localizacao,                   a1.nome as nm_localizacao,
               a11.sq_unidade,                      a11.nome as nm_unidade,
               a12.sq_pessoa_endereco,              a12.logradouro,
               a2.sq_menu as sq_menu,               a2.sigla as sg_menu,                             a2.nome as nm_menu,
               a2.p1,                               a2.p2,                                           a2.p3,
               a2.p4,                               substr(a2.link,1,instr(a2.link,'par=')+3) as link_menu,
               b.sq_almoxarifado_local,             montaNomeAlmoxLocal(b.sq_almoxarifado_local) as nm_almoxarifado_local,
               c.sq_entrada_item,                   c.saldo_atual,
          	   d.quantidade as qt_entrada,          d.valor_unitario as vl_entrada,                  d.valor_total as tot_entrada,
               d.validade,
               d1.sq_material,                      d1.nome as nm_material,                          montanometipomaterial(d1.sq_tipo_material,'CODCOMP') as nm_tipo_completo,
               d12.sq_tipo_material,                d12.nome as nm_tipo_material,                    d12.classe,
               case d12.classe when 1 then 'Medicamento' when 2 then 'Alimento' when 3 then 'Consumo' when 4 then 'Permanente' when 5 then 'Serviço' end as nm_classe,
               d2.sq_mtentrada,                     d2.recebimento_previsto,                         d2.recebimento_efetivo,
               d2.armazenamento,                    d2.numero_empenho,                               d2.data_empenho,
               d11.sq_unidade_medida,               d11.nome as nm_unidade_medida,                   d11.sigla as sg_unidade_medida,
               d21.sq_pessoa as fornecedor,         d21.nome as nm_fornecedor,                       d21.nome_resumido as nm_res_fornecedor,
               d22.sq_mtsituacao,                   d22.nome as nm_situacao,                         d22.sigla as sg_situacao,
               d23.sq_tipo_movimentacao,            d23.nome as nm_tipo_movimentacao,
               d24.numero as nr_doc,                d24.data as dt_doc,                              d24.valor as vl_doc,
               d241.sq_tipo_documento,              d241.nome as nm_tip_doc,                         d241.sigla as sg_tip_doc,
               g.ultima_saida,                      g.ultima_entrada,                                g.preco_medio,
               g.ultimo_preco_compra,               g.consumo_medio_mensal,                          g.ponto_ressuprimento,
               g.ciclo_compra,                      g.disponivel
          from mt_almoxarifado                                a
               inner             join eo_localizacao         a1 on (a.sq_localizacao         = a1.sq_localizacao)
                 inner           join eo_unidade            a11 on (a1.sq_unidade            = a11.sq_unidade)
                 inner           join co_pessoa_endereco    a12 on (a1.sq_pessoa_endereco    = a12.sq_pessoa_endereco)
               inner             join siw_menu               a2 on (a.cliente                = a2.sq_pessoa and
                                                                    a2.sigla                 = 'MTENTMAT'
                                                                   )
               inner             join mt_almoxarifado_local   b on (a.sq_almoxarifado        = b.sq_almoxarifado)
                 inner           join mt_estoque_item         c on (b.sq_almoxarifado_local  = c.sq_almoxarifado_local)
                   inner         join mt_entrada_item         d on (c.sq_entrada_item        = d.sq_entrada_item)
                     inner       join cl_material            d1 on (d.sq_material            = d1.sq_material)
                       inner     join co_unidade_medida     d11 on (d1.sq_unidade_medida     = d11.sq_unidade_medida)
                       inner     join cl_tipo_material      d12 on (d1.sq_tipo_material      = d12.sq_tipo_material)
                     inner       join mt_entrada             d2 on (d.sq_mtentrada           = d2.sq_mtentrada)
                       inner     join co_pessoa             d21 on (d2.fornecedor            = d21.sq_pessoa)
                       inner     join mt_situacao           d22 on (d2.sq_mtsituacao         = d22.sq_mtsituacao)
                       inner     join mt_tipo_movimentacao  d23 on (d2.sq_tipo_movimentacao  = d23.sq_tipo_movimentacao)
                       inner     join fn_lancamento_doc     d24 on (d2.sq_lancamento_doc     = d24.sq_lancamento_doc)
                         inner   join fn_tipo_documento    d241 on (d24.sq_tipo_documento    = d241.sq_tipo_documento)
                   inner         join mt_estoque              g on (c.sq_estoque             = g.sq_estoque)
         where a.cliente         = p_menu
           and a.sq_almoxarifado = p_chave
           and (coalesce(p_ativo,'N') = 'N' or (p_ativo = 'S' and g.disponivel = p_ativo))
           and (p_sqcc           is null or (p_sqcc        is not null and d2.sq_tipo_movimentacao = p_sqcc))
           and (p_proponente     is null or (p_proponente  is not null and acentos(d1.nome,null) like '%'||acentos(p_proponente,null)||'%'))
           and (p_pais           is null or (p_pais        is not null and d12.sq_tipo_material in (select sq_tipo_material
                                                                                                      from cl_tipo_material
                                                                                                    connect by prior sq_tipo_material = sq_tipo_pai
                                                                                                    start with sq_tipo_material = p_pais
                                                                                                   )
                                            )
               )
           and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||d12.classe||'''') > 0))
           and (p_ini_i          is null or (p_ini_i       is not null and d2.armazenamento      between p_ini_i and p_ini_f))
        order by a.nome, d1.nome, d2.armazenamento, d.validade;
   Elsif p_restricao = 'ALSAIDA' Then
      -- Recupera o inventário de estoque
      open p_result for 
        select distinct
               a.cliente,                           a.sq_almoxarifado,                               a.nome as nm_almoxarifado,
               a1.sq_localizacao,                   a1.nome as nm_localizacao,
               a11.sq_unidade,                      a11.nome as nm_unidade,
               a12.sq_pessoa_endereco,              a12.logradouro,
               b.sq_almoxarifado_local,             montaNomeAlmoxLocal(b.sq_almoxarifado_local) as nm_almoxarifado_local,
               c.sq_entrada_item,                   c.saldo_atual,
          	   d1.sq_material,                      d1.nome as nm_material,                          montanometipomaterial(d1.sq_tipo_material,'CODCOMP') as nm_tipo_completo,
               d12.sq_tipo_material,                d12.nome as nm_tipo_material,                    d12.classe,
               case d12.classe when 1 then 'Medicamento' when 2 then 'Alimento' when 3 then 'Consumo' when 4 then 'Permanente' when 5 then 'Serviço' end as nm_classe,
               d2.sq_mtentrada,                     d2.recebimento_previsto,                         d2.recebimento_efetivo,
               d2.armazenamento,                    d2.numero_empenho,                               d2.data_empenho,
               d11.sq_unidade_medida,               d11.nome as nm_unidade_medida,                   d11.sigla as sg_unidade_medida,
               d21.sq_pessoa as fornecedor,         d21.nome as nm_fornecedor,                       d21.nome_resumido as nm_res_fornecedor,
               d22.sq_mtsituacao,                   d22.nome as nm_situacao,                         d22.sigla as sg_situacao,
               d23.sq_tipo_movimentacao as sq_tipo_entrada,                                          d23.nome as nm_tipo_entrada,
               d24.numero as nr_doc,                d24.data as dt_doc,                              d24.valor as vl_doc,
               d241.sq_tipo_documento,              d241.nome as nm_tip_doc,                         d241.sigla as sg_tip_doc,
               e1.quantidade_pedida,                e1.quantidade_entregue,                          e1.fator_embalagem,
               e1.valor_unitario,                   e1.data_efetivacao,                              e1.valor_unitario as vl_saida,
               case when e11.sq_unidade_destino is not null then 'I'                    else 'E'                   end as tp_destino,
               case when e11.sq_unidade_destino is not null then e11.sq_unidade_destino else e11.sq_pessoa_destino end as sq_destino,
               case when e11.sq_unidade_destino is not null then e112.nome              else e113.nome             end as nm_destino,
               e111.sq_tipo_movimentacao,           e111.nome as nm_tipo_movimentacao,
               f.sq_siw_solicitacao,                f.codigo_interno,                                f.justificativa,
               f.inicio,                            f.fim,                                           dados_solic(f.sq_siw_solicitacao) as dados_solic,
               f1.sq_siw_tramite,                   f1.nome as nm_tramite,                           f1.sigla as sg_tramite,
               f2.sq_menu,                          f2.nome as nm_menu,                              f2.sigla as sg_menu,
               g.ultima_saida,                      g.ultima_entrada,                                g.preco_medio,
               g.ultimo_preco_compra,               g.consumo_medio_mensal,                          g.ponto_ressuprimento,
               g.ciclo_compra,                      g.disponivel
          from mt_almoxarifado                                a
               inner             join eo_localizacao         a1 on (a.sq_localizacao         = a1.sq_localizacao)
                 inner           join eo_unidade            a11 on (a1.sq_unidade            = a11.sq_unidade)
                 inner           join co_pessoa_endereco    a12 on (a1.sq_pessoa_endereco    = a12.sq_pessoa_endereco)
               inner             join mt_almoxarifado_local   b on (a.sq_almoxarifado        = b.sq_almoxarifado)
                 inner           join mt_estoque_item         c on (b.sq_almoxarifado_local  = c.sq_almoxarifado_local)
                   inner         join mt_entrada_item         d on (c.sq_entrada_item        = d.sq_entrada_item)
                     inner       join cl_material            d1 on (d.sq_material            = d1.sq_material)
                       inner     join co_unidade_medida     d11 on (d1.sq_unidade_medida     = d11.sq_unidade_medida)
                       inner     join cl_tipo_material      d12 on (d1.sq_tipo_material      = d12.sq_tipo_material)
                     inner       join mt_entrada             d2 on (d.sq_mtentrada           = d2.sq_mtentrada)
                       inner     join co_pessoa             d21 on (d2.fornecedor            = d21.sq_pessoa)
                       inner     join mt_situacao           d22 on (d2.sq_mtsituacao         = d22.sq_mtsituacao)
                       inner     join mt_tipo_movimentacao  d23 on (d2.sq_tipo_movimentacao  = d23.sq_tipo_movimentacao)
                       inner     join fn_lancamento_doc     d24 on (d2.sq_lancamento_doc     = d24.sq_lancamento_doc)
                         inner   join fn_tipo_documento    d241 on (d24.sq_tipo_documento    = d241.sq_tipo_documento)
                   inner         join mt_saida_estoque        e on (c.sq_estoque_item        = e.sq_estoque_item)
                     inner       join mt_saida_item          e1 on (e.sq_saida_item          = e1.sq_saida_item)
                       inner     join mt_saida              e11 on (e1.sq_mtsaida            = e11.sq_mtsaida)
                         inner   join mt_tipo_movimentacao e111 on (e11.sq_tipo_movimentacao = e111.sq_tipo_movimentacao)  
                         left    join eo_unidade           e112 on (e11.sq_unidade_destino   = e112.sq_unidade)  
                         left    join co_pessoa            e113 on (e11.sq_pessoa_destino    = e113.sq_pessoa)  
                         inner   join siw_solicitacao         f on (e11.sq_siw_solicitacao   = f.sq_siw_solicitacao)
                           inner join siw_tramite            f1 on (f.sq_siw_tramite         = f1.sq_siw_tramite)
                           inner join siw_menu               f2 on (f.sq_menu                = f2.sq_menu)
                   inner         join mt_estoque              g on (c.sq_estoque             = g.sq_estoque)
         where a.cliente         = p_menu
           and (p_chave          is null or (p_chave       is not null and a.sq_almoxarifado        = p_chave))
           and (coalesce(p_ativo,'N') = 'N' or (p_ativo = 'S' and g.disponivel                      = p_ativo))
           and (p_sqcc           is null or (p_sqcc        is not null and e11.sq_tipo_movimentacao = p_sqcc))
           and (f1.sigla         is null or (f1.sigla      is not null and f1.sigla                 <> 'CA'))
           and (p_palavra        is null or (p_palavra     is not null and f.codigo_interno       like '%'||p_palavra||'%'))
           and (p_unidade        is null or (p_unidade     is not null and e11.sq_unidade_destino    = p_unidade))
           and (p_proponente     is null or (p_proponente  is not null and acentos(d1.nome,null)  like '%'||acentos(p_proponente,null)||'%'))
           and (p_pais           is null or (p_pais        is not null and d12.sq_tipo_material     in (select sq_tipo_material
                                                                                                          from cl_tipo_material
                                                                                                        connect by prior sq_tipo_material = sq_tipo_pai
                                                                                                        start with sq_tipo_material = p_pais
                                                                                                       )
                                            )
               )
           and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||d12.classe||'''') > 0))
           and (p_ini_i          is null or (p_ini_i       is not null and trunc(f.inclusao) between p_ini_i and p_ini_f))
           and (p_fim_i          is null or (p_fim_i       is not null and ((f1.sigla  = 'EE' and f.fim between p_fim_i and p_fim_f) or 
                                                                            (f1.sigla <> 'EE' and e1.data_efetivacao between p_fim_i and p_fim_f)
                                                                           )
                                            )
               );
   Elsif p_restricao = 'ALMAPA' Then
      -- Recupera dados para exibição do mapa de entradas e saídas de material 
      open p_result for 
        select a.cliente,
               a.sq_almoxarifado,                   a.nome as nm_almoxarifado,
               a1.sq_localizacao,                   a1.nome as nm_localizacao,
               a11.sq_unidade,                      a11.nome as nm_unidade,
               a12.sq_pessoa_endereco,              a12.logradouro,
               b.sq_almoxarifado_local,             montaNomeAlmoxLocal(b.sq_almoxarifado_local) as nm_almoxarifado_local,
               c.sq_entrada_item,                   c.saldo_atual,
               d.quantidade as qt_entrada,          d.valor_unitario as vl_entrada,                  d.valor_total as tot_entrada,
               d.validade,
               d1.sq_material,                      d1.nome as nm_material,                          montanometipomaterial(d1.sq_tipo_material,'CODCOMP') as nm_tipo_completo,
               d12.sq_tipo_material,                d12.nome as nm_tipo_material,                    d12.classe,
               case d12.classe when 1 then 'Medicamento' when 2 then 'Alimento' when 3 then 'Consumo' when 4 then 'Permanente' when 5 then 'Serviço' end as nm_classe,
               d2.sq_mtentrada,                     d2.recebimento_previsto,                         d2.recebimento_efetivo,
               d2.armazenamento,                    d2.numero_empenho,                               d2.data_empenho,
               d11.sq_unidade_medida,               d11.nome as nm_unidade_medida,                   d11.sigla as sg_unidade_medida,
               d21.sq_pessoa as fornecedor,         d21.nome as nm_fornecedor,                       d21.nome_resumido as nm_res_fornecedor,
               d22.sq_mtsituacao,                   d22.nome as nm_situacao,                         d22.sigla as sg_situacao,
               d23.sq_tipo_movimentacao,            d23.nome as nm_tipo_movimentacao,
               d24.numero as nr_doc,                d24.data as dt_doc,                              d24.valor as vl_doc,
               d241.sq_tipo_documento,              d241.nome as nm_tip_doc,                         d241.sigla as sg_tip_doc,
               null as quantidade_pedida,           null as quantidade_entregue,                     null as fator_embalagem,
               null as valor_unitario,              null as data_efetivacao,                         null as vl_saida,
               null as tp_destino,                  null as sq_destino,                              null as nm_destino,
               null as sq_tipo_saida,        null as nm_tipo_saida,
               null as sq_siw_solicitacao,          null as codigo_interno,                          null as justificativa,
               null as inicio,                      null as fim,                                     null as dados_solic,
               null as sq_siw_tramite,              null as nm_tramite,                              null as sg_tramite,
               a2.sq_menu as sq_menu,               a2.sigla as sg_menu,                             a2.nome as nm_menu,
               a2.p1,                               a2.p2,                                           a2.p3,
               a2.p4,                               substr(a2.link,1,instr(a2.link,'par=')+3) as link_menu,
               g.ultima_saida,                      g.ultima_entrada,                                g.preco_medio,
               g.ultimo_preco_compra,               g.consumo_medio_mensal,                          g.ponto_ressuprimento,
               g.ciclo_compra,                      g.disponivel
          from mt_almoxarifado                                a
               inner             join eo_localizacao         a1 on (a.sq_localizacao         = a1.sq_localizacao)
                 inner           join eo_unidade            a11 on (a1.sq_unidade            = a11.sq_unidade)
                 inner           join co_pessoa_endereco    a12 on (a1.sq_pessoa_endereco    = a12.sq_pessoa_endereco)
               inner             join siw_menu               a2 on (a.cliente                = a2.sq_pessoa and
                                                                    a2.sigla                 = 'MTENTMAT'
                                                                   )
               inner             join mt_almoxarifado_local   b on (a.sq_almoxarifado        = b.sq_almoxarifado)
                 inner           join mt_estoque_item         c on (b.sq_almoxarifado_local  = c.sq_almoxarifado_local)
                   inner         join mt_entrada_item         d on (c.sq_entrada_item        = d.sq_entrada_item)
                     inner       join cl_material            d1 on (d.sq_material            = d1.sq_material)
                       inner     join co_unidade_medida     d11 on (d1.sq_unidade_medida     = d11.sq_unidade_medida)
                       inner     join cl_tipo_material      d12 on (d1.sq_tipo_material      = d12.sq_tipo_material)
                     inner       join mt_entrada             d2 on (d.sq_mtentrada           = d2.sq_mtentrada)
                       inner     join co_pessoa             d21 on (d2.fornecedor            = d21.sq_pessoa)
                       inner     join mt_situacao           d22 on (d2.sq_mtsituacao         = d22.sq_mtsituacao)
                       inner     join mt_tipo_movimentacao  d23 on (d2.sq_tipo_movimentacao  = d23.sq_tipo_movimentacao)
                       inner     join fn_lancamento_doc     d24 on (d2.sq_lancamento_doc     = d24.sq_lancamento_doc)
                         inner   join fn_tipo_documento    d241 on (d24.sq_tipo_documento    = d241.sq_tipo_documento)
                   inner         join mt_estoque              g on (c.sq_estoque             = g.sq_estoque)
         where a.cliente         = p_menu
           and a.sq_almoxarifado = p_chave
           and (coalesce(p_ativo,'N') = 'N' or (p_ativo = 'S' and g.disponivel = p_ativo))
           and (p_proponente     is null or (p_proponente  is not null and acentos(d1.nome,null) like '%'||acentos(p_proponente,null)||'%'))
           and (p_pais           is null or (p_pais        is not null and d12.sq_tipo_material in (select sq_tipo_material
                                                                                                      from cl_tipo_material
                                                                                                    connect by prior sq_tipo_material = sq_tipo_pai
                                                                                                    start with sq_tipo_material = p_pais
                                                                                                   )
                                            )
               )
           and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||d12.classe||'''') > 0))
           and (p_ini_i          is null or (p_ini_i       is not null and d2.armazenamento      between p_ini_i and p_ini_f))
        UNION
        select distinct
               a.cliente,                           a.sq_almoxarifado,                               a.nome as nm_almoxarifado,
               a1.sq_localizacao,                   a1.nome as nm_localizacao,
               a11.sq_unidade,                      a11.nome as nm_unidade,
               a12.sq_pessoa_endereco,              a12.logradouro,
               b.sq_almoxarifado_local,             montaNomeAlmoxLocal(b.sq_almoxarifado_local) as nm_almoxarifado_local,
               c.sq_entrada_item,                   c.saldo_atual,
               null as qt_entrada,                  null as vl_entrada,                              null as tot_entrada,
               null as validade,
               d1.sq_material,                      d1.nome as nm_material,                          montanometipomaterial(d1.sq_tipo_material,'CODCOMP') as nm_tipo_completo,
               d12.sq_tipo_material,                d12.nome as nm_tipo_material,                    d12.classe,
               case d12.classe when 1 then 'Medicamento' when 2 then 'Alimento' when 3 then 'Consumo' when 4 then 'Permanente' when 5 then 'Serviço' end as nm_classe,
               d2.sq_mtentrada,                     d2.recebimento_previsto,                         d2.recebimento_efetivo,
               e1.data_efetivacao as armazenamento, d2.numero_empenho,                               d2.data_empenho,
               d11.sq_unidade_medida,               d11.nome as nm_unidade_medida,                   d11.sigla as sg_unidade_medida,
               d21.sq_pessoa as fornecedor,         d21.nome as nm_fornecedor,                       d21.nome_resumido as nm_res_fornecedor,
               d22.sq_mtsituacao,                   d22.nome as nm_situacao,                         d22.sigla as sg_situacao,
               e111.sq_tipo_movimentacao,           e111.nome as nm_tipo_movimentacao,
               d24.numero as nr_doc,                d24.data as dt_doc,                              d24.valor as vl_doc,
               d241.sq_tipo_documento,              d241.nome as nm_tip_doc,                         d241.sigla as sg_tip_doc,
               e1.quantidade_pedida,                e1.quantidade_entregue,                          e1.fator_embalagem,
               e1.valor_unitario,                   e1.data_efetivacao,                              e1.valor_unitario as vl_saida,
               case when e11.sq_unidade_destino is not null then 'I'                    else 'E'                   end as tp_destino,
               case when e11.sq_unidade_destino is not null then e11.sq_unidade_destino else e11.sq_pessoa_destino end as sq_destino,
               case when e11.sq_unidade_destino is not null then e112.nome              else e113.nome             end as nm_destino,
               e111.sq_tipo_movimentacao,           e111.nome as nm_tipo_movimentacao,
               f.sq_siw_solicitacao,                f.codigo_interno,                                f.justificativa,
               f.inicio,                            f.fim,                                           dados_solic(f.sq_siw_solicitacao) as dados_solic,
               f1.sq_siw_tramite,                   f1.nome as nm_tramite,                           f1.sigla as sg_tramite,
               f2.sq_menu,                          f2.sigla as sg_menu,                             f2.nome as nm_menu,
               null as p1,                          null as p2,                                      null as p3,
               null as p4,                          null as link_menu,
               g.ultima_saida,                      g.ultima_entrada,                                g.preco_medio,
               g.ultimo_preco_compra,               g.consumo_medio_mensal,                          g.ponto_ressuprimento,
               g.ciclo_compra,                      g.disponivel
          from mt_almoxarifado                                a
               inner             join eo_localizacao         a1 on (a.sq_localizacao         = a1.sq_localizacao)
                 inner           join eo_unidade            a11 on (a1.sq_unidade            = a11.sq_unidade)
                 inner           join co_pessoa_endereco    a12 on (a1.sq_pessoa_endereco    = a12.sq_pessoa_endereco)
               inner             join mt_almoxarifado_local   b on (a.sq_almoxarifado        = b.sq_almoxarifado)
                 inner           join mt_estoque_item         c on (b.sq_almoxarifado_local  = c.sq_almoxarifado_local)
                   inner         join mt_entrada_item         d on (c.sq_entrada_item        = d.sq_entrada_item)
                     inner       join cl_material            d1 on (d.sq_material            = d1.sq_material)
                       inner     join co_unidade_medida     d11 on (d1.sq_unidade_medida     = d11.sq_unidade_medida)
                       inner     join cl_tipo_material      d12 on (d1.sq_tipo_material      = d12.sq_tipo_material)
                     inner       join mt_entrada             d2 on (d.sq_mtentrada           = d2.sq_mtentrada)
                       inner     join co_pessoa             d21 on (d2.fornecedor            = d21.sq_pessoa)
                       inner     join mt_situacao           d22 on (d2.sq_mtsituacao         = d22.sq_mtsituacao)
                       inner     join fn_lancamento_doc     d24 on (d2.sq_lancamento_doc     = d24.sq_lancamento_doc)
                         inner   join fn_tipo_documento    d241 on (d24.sq_tipo_documento    = d241.sq_tipo_documento)
                   inner         join mt_saida_estoque        e on (c.sq_estoque_item        = e.sq_estoque_item)
                     inner       join mt_saida_item          e1 on (e.sq_saida_item          = e1.sq_saida_item)
                       inner     join mt_saida              e11 on (e1.sq_mtsaida            = e11.sq_mtsaida)
                         inner   join mt_tipo_movimentacao e111 on (e11.sq_tipo_movimentacao = e111.sq_tipo_movimentacao)  
                         left    join eo_unidade           e112 on (e11.sq_unidade_destino   = e112.sq_unidade)  
                         left    join co_pessoa            e113 on (e11.sq_pessoa_destino    = e113.sq_pessoa)  
                         inner   join siw_solicitacao         f on (e11.sq_siw_solicitacao   = f.sq_siw_solicitacao)
                           inner join siw_tramite            f1 on (f.sq_siw_tramite         = f1.sq_siw_tramite)
                           inner join siw_menu               f2 on (f.sq_menu                = f2.sq_menu)
                   inner         join mt_estoque              g on (c.sq_estoque             = g.sq_estoque)
         where a.cliente         = p_menu
           and a.sq_almoxarifado = p_chave
           and (f1.sigla         is null or (f1.sigla      is not null and f1.sigla <> 'CA'))
           and (p_proponente     is null or (p_proponente  is not null and acentos(d1.nome,null) like '%'||acentos(p_proponente,null)||'%'))
           and (p_pais           is null or (p_pais        is not null and d12.sq_tipo_material in (select sq_tipo_material
                                                                                                      from cl_tipo_material
                                                                                                    connect by prior sq_tipo_material = sq_tipo_pai
                                                                                                    start with sq_tipo_material = p_pais
                                                                                                   )
                                            )
               )
           and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||d12.classe||'''') > 0))
           and (p_ini_i          is null or (p_ini_i       is not null and ((f1.sigla is not null and f1.sigla = 'EE' and f.fim between p_ini_i and p_ini_f) or 
                                                                            (e1.data_efetivacao between p_ini_i and p_ini_f)
                                                                           )
                                            )
               )
        order by nm_almoxarifado, nm_material, nm_almoxarifado_local, armazenamento;
   Elsif p_restricao = 'FUNDO_FIXO' Then
      -- Recupera as solicitações de compras passíveis de pagamento por fundo fixo
      open p_result for 
         select b.sq_siw_solicitacao, b.titulo, b.codigo_interno, b.codigo_externo, 
                to_char(b.inclusao,'dd/mm/yyyy, hh24:mi:ss') as phpdt_inclusao
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
            and (p_chave is null or e.sq_siw_solicitacao is null or (p_chave is not null and e.sq_siw_solicitacao is not null and e.sq_siw_solicitacao = p_chave));
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
end SP_GetSolicMT;
/
