create or replace procedure sp_getMtEntItem
   (p_cliente       in number    default null,
    p_entrada       in number    default null,
    p_item          in number    default null,
    P_solicitacao   in number    default null,
    p_material      in number    default null,
    p_cancelado     in varchar2  default null,
    p_tipo_material in number    default null,
    p_sq_cc         in number    default null,
    p_codigo        in varchar2  default null,
    p_nome          in varchar2  default null,
    p_aviso         in varchar2  default null,
    p_invalida      in varchar2  default null,
    p_valida        in varchar2  default null,
    p_branco        in varchar2  default null,
    p_restricao     in varchar2  default null,
    p_result        out sys_refcursor) is
begin
   If p_restricao is null or p_restricao = 'COMPRA' Then
      -- Recupera materiais e serviços
      open p_result for 
         select a.sq_mtentrada,                     a.recebimento_previsto,              a.recebimento_efetivo, 
                a.armazenamento,                    a.numero_empenho,                    a.data_empenho,
                b.sq_siw_solicitacao,               b.sq_siw_tramite,                    b.solicitante,
                b.cadastrador,                      b.executor,                          b.descricao,
                b.justificativa,                    b.inicio,                            coalesce(b.fim, trunc(sysdate)) as fim,
                b.inclusao,                         b.ultima_alteracao,                  b.conclusao,
                b.valor,                            b.opiniao,                           b.palavra_chave,
                b.sq_solic_pai,                     b.sq_unidade,                        b.sq_cidade_origem,
                b.codigo_externo,                   b.titulo,                            acentos(b.titulo) as ac_titulo,
                b.sq_plano,                         b.sq_cc,                             b.observacao,
                b.protocolo_siw,                    b.recebedor,                         b.codigo_interno,
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
                b1.nome as nm_tramite,              b1.ordem as or_tramite,              b1.sigla as sg_tramite,
                b1.ativo,                           b1.envia_mail,
                b2.sq_tipo_unidade,                 b2.nome as nm_unidade_resp,          b2.informal as informal_resp,
                b2.vinculada as vinc_resp,          b2.adm_central as adm_resp,          b2.sigla sg_unidade_resp,
                b3.sq_pais,                         b3.sq_regiao,                        b3.co_uf,
                case when b.protocolo_siw is null then null else to_char(b5.numero_documento)||'/'||substr(to_char(b5.ano),2) end as processo,
                to_char(b5.prefixo)||'.'||substr(1000000+to_char(b5.numero_documento),2,6)||'/'||to_char(b5.ano)||'-'||substr(100+to_char(b5.digito),2,2) as protocolo_completo,
                b6.sq_menu as sq_menu_pai,
                b7.sq_cc,                           b7.nome as nm_cc,                    b7.sigla as sg_cc,
                b8.nome_resumido as nm_solic,       b8.nome_resumido_ind as nm_solic_ind,
                b9.nome_resumido as nm_exec,        b9.nome_resumido_ind as nm_exec_ind,
                ba.nome_resumido as nm_recebedor,   ba.nome_resumido_ind as nm_recebedor_ind,
                c.sq_tipo_movimentacao,             c.nome as nm_tp_mov,                 c.entrada as ent_tp_mov, 
                c.saida as sai_tp_mov,              c.orcamentario as orc_tp_mov,        c.consumo as con_tp_mov, 
                c.permanente as per_tp_mov,         c.inativa_bem as in_tp_mov,          c.ativo as at_tp_mov,
                d.sq_mtsituacao,                    d.nome as nm_sit,                    d.sigla as sg_sit, 
                d.entrada as ent_sit,               d.saida as sai_sit,                  d.estorno as est_sit, 
                d.consumo as con_sit,               d.permanente as per_sit,             d.inativa_bem as in_sit, 
                d.situacao_fisica as fis_sit,       d.ativo as at_sit,
                e.sq_lancamento_doc,                e.numero as nr_doc,                  e.data as dt_doc, 
                e.serie as sr_doc,                  e.valor as vl_doc,                   e.patrimonio as pat_doc,
                e1.sq_tipo_documento,               e1.nome as nm_tp_doc,                e1.sigla as sg_tp_doc,
                e1.ativo as at_tp_doc,              e1.detalha_item as det_doc,
                f.sq_pessoa as sq_fornecedor,       f.nome as nm_fornecedor,             f.nome_resumido as nm_res_fornecedor,
                f.nome_indice as nm_fornecedor_ind,
                f1.sq_tipo_pessoa as sq_tipo_fornecedor,                                 f1.nome as nm_tipo_fornecedor,
                f2.cpf, 
                f3.cnpj,
                g.sq_entrada_item,                  g.quantidade,                        g.valor_total, 
                g.valor_unitario,                   g.fator_embalagem,                   g.validade, 
                g.fabricacao,                       g.vida_util,                         g.lote_numero, 
                g.lote_bloqueado,                   g.marca,                             g.modelo,
                g1.sq_almoxarifado,                 g1.nome as nm_almox,                 g1.ativo as at_almox,
                g2.sq_mtsituacao as sq_sit_item,    g2.nome as nm_sit_item,              g2.sigla as sg_sit_item, 
                g2.entrada as ent_sit_item,         g2.saida as sai_sit_item,            g2.estorno as est_sit_item, 
                g2.consumo as con_sit_item,         g2.permanente as per_sit_item,       g2.inativa_bem as in_sit_item, 
                g2.situacao_fisica as fis_sit_item, g2.ativo as at_sit_item,
                g3.sq_material,                     g3.sq_tipo_material,                 g3.sq_unidade_medida, 
                g3.nome,                            g3.descricao,                        g3.detalhamento, 
                g3.apresentacao,                    g3.codigo_interno,                   g3.codigo_externo, 
                g3.exibe_catalogo,                  g3.vida_util,                        g3.ativo, 
                g3.pesquisa_preco_menor,            g3.pesquisa_preco_maior,             g3.pesquisa_preco_medio,
                g3.pesquisa_data,                   g3.pesquisa_validade,
                case g3.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,               case g3.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                g31.nome as nm_tipo_material,       g31.sigla as sg_tipo_material,       g31.classe,
                case g31.classe when 1 then 'Medicamento' when 3 then 'Consumo' when 4 then 'Permanente' when 5 then 'Serviço' end as nm_classe,
                montanometipomaterial(g31.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(g31.sq_tipo_material) as nm_tipo_material_completo,
                g32.nome as nm_unidade_medida,      g32.sigla as sg_unidade_medida
           from mt_entrada                                      a 
                inner             join siw_solicitacao          b  on (a.sq_siw_solicitacao       = b.sq_siw_solicitacao)
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
                inner             join mt_situacao              d  on (a.sq_mtsituacao            = d.sq_mtsituacao)
                inner             join fn_lancamento_doc        e  on (a.sq_lancamento_doc        = e.sq_lancamento_doc)
                  inner           join fn_tipo_documento        e1 on (e.sq_tipo_documento        = e1.sq_tipo_documento)
                inner             join co_pessoa                f  on (a.fornecedor               = f.sq_pessoa)
                  inner           join co_tipo_pessoa           f1 on (f.sq_tipo_pessoa           = f1.sq_tipo_pessoa)
                    left          join co_pessoa_fisica         f2 on (f.sq_pessoa                = f2.sq_pessoa)
                    left          join co_pessoa_juridica       f3 on (f.sq_pessoa                = f3.sq_pessoa)
                inner             join mt_entrada_item          g  on (a.sq_mtentrada             = g.sq_mtentrada)
                  inner           join mt_almoxarifado          g1 on (g.sq_almoxarifado          = g1.sq_almoxarifado)
                  inner           join mt_situacao              g2 on (g.sq_mtsituacao            = g2.sq_mtsituacao)
                  inner           join cl_material              g3 on (g.sq_material              = g3.sq_material)
                     inner        join cl_tipo_material        g31 on (g3.sq_tipo_material        = g31.sq_tipo_material)
                     inner        join co_unidade_medida       g32 on (g3.sq_unidade_medida       = g32.sq_unidade_medida)
          where a.cliente         = p_cliente
            and (p_entrada        is null or (p_entrada           is not null and a.sq_mtentrada         = p_entrada))
            and (p_item           is null or (p_item              is not null and g.sq_entrada_item      = p_item));
   End If;
end sp_getMtEntItem;
/
