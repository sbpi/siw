create or replace procedure sp_getCLSolicItem
   (p_chave         in number    default null,
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
         select /*+ ordered */ a.sq_solicitacao_item as chave, a.sq_siw_solicitacao, a.quantidade, a.valor_unit_est,
                a.preco_menor, a.preco_maior, a.preco_medio, a.quantidade_autorizada, a.cancelado, a.motivo_cancelamento,
                a.ordem, a.dias_validade_proposta as dias_validade_item,
                b.sq_material, b.sq_tipo_material, b.sq_unidade_medida, 
                b.nome, b.descricao, b.detalhamento, b.apresentacao, b.codigo_interno, b.codigo_externo, 
                b.exibe_catalogo, b.vida_util, b.ativo, b.sq_cc,
                b.pesquisa_preco_menor, b.pesquisa_preco_maior, b.pesquisa_preco_medio,
                b.pesquisa_data, b.pesquisa_validade, 
                b.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case b.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case b.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                case c.classe
                     when 1 then 'Medicamento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                e.nome as nm_cc
           from cl_solicitacao_item                a
                inner     join cl_material         b  on (a.sq_material         = b.sq_material)
                inner     join cl_tipo_material    c  on (b.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida   d  on (b.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join ct_cc               e  on (b.sq_cc               = e.sq_cc)
                inner     join cl_parametro        f  on (b.cliente             = f.cliente)
          where (p_chave         is null or (p_chave         is not null and a.sq_solicitacao_item = p_chave))
            and (p_material      is null or (p_material      is not null and a.sq_material         = p_material))
            and (p_solicitacao   is null or (p_solicitacao   is not null and a.sq_siw_solicitacao  = p_solicitacao))
            and (p_cancelado     is null or (p_cancelado     is not null and a.cancelado           = p_cancelado));
   ElsIf p_restricao = 'LICITACAO' Then
      -- Recupera materiais e serviços
      open p_result for 
         select /*+ ordered */ a.sq_solicitacao_item as chave, a.sq_siw_solicitacao, a.quantidade, a.valor_unit_est,
                a.preco_menor, a.preco_maior, a.preco_medio, a.quantidade_autorizada, a.cancelado, a.motivo_cancelamento,
                a.ordem,
                b.sq_material, b.sq_tipo_material, b.sq_unidade_medida, 
                b.nome, b.descricao, b.detalhamento, b.apresentacao, b.codigo_interno, b.codigo_externo, 
                b.exibe_catalogo, b.vida_util, b.ativo, b.sq_cc,
                b.pesquisa_preco_menor, b.pesquisa_preco_maior, b.pesquisa_preco_medio,
                b.pesquisa_data, b.pesquisa_validade, 
                b.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case b.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case b.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                case c.classe
                     when 1 then 'Medicamento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                e.nome as nm_cc,
                g.item_pedido,
                g.item_licitacao,
                h.sq_siw_solicitacao sq_solic_pai, h.quantidade_autorizada as qtd_pedido,
                dados_solic(h.sq_siw_solicitacao) as dados_pai,
                i.qtd_cotacao,
                j.qtd_proposta
           from cl_solicitacao_item                     a
                inner     join cl_material              b  on (a.sq_material         = b.sq_material)
                inner     join cl_tipo_material         c  on (b.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida        d  on (b.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join ct_cc                    e  on (b.sq_cc               = e.sq_cc)
                inner     join cl_parametro             f  on (b.cliente             = f.cliente)
                inner     join cl_solicitacao_item_vinc g on (a.sq_solicitacao_item  = g.item_licitacao)
                  inner   join cl_solicitacao_item      h on (g.item_pedido          = h.sq_solicitacao_item)
                  left    join (select y.sq_solicitacao_item, count(z.sq_item_fornecedor) as qtd_cotacao
                                  from siw_solicitacao                  x
                                       inner   join cl_solicitacao_item y on (x.sq_siw_solicitacao  = y.sq_siw_solicitacao)
                                         left  join cl_item_fornecedor  z on (y.sq_solicitacao_item = z.sq_solicitacao_item and
                                                                              'S'                   = z.pesquisa)
                                group by y.sq_solicitacao_item
                               )                        i on (a.sq_solicitacao_item  = i.sq_solicitacao_item)
                  left    join (select y.sq_solicitacao_item, count(z.sq_item_fornecedor) as qtd_proposta
                                  from siw_solicitacao                  x
                                       inner   join cl_solicitacao_item y on (x.sq_siw_solicitacao  = y.sq_siw_solicitacao)
                                         left  join cl_item_fornecedor  z on (y.sq_solicitacao_item = z.sq_solicitacao_item and
                                                                              'N'                   = z.pesquisa)
                                group by y.sq_solicitacao_item
                               )                        j on (a.sq_solicitacao_item  = j.sq_solicitacao_item)                               
          where (p_chave         is null or (p_chave         is not null and a.sq_solicitacao_item = p_chave))
            and (p_material      is null or (p_material      is not null and a.sq_material         = p_material))
            and (p_solicitacao   is null or (p_solicitacao   is not null and a.sq_siw_solicitacao  = p_solicitacao))
            and (p_cancelado     is null or (p_cancelado     is not null and a.cancelado           = p_cancelado));
   ElsIf p_restricao = 'ARP' Then
      -- Recupera materiais e serviços
      open p_result for 
         select /*+ ordered */ a.sq_solicitacao_item as chave, a.sq_siw_solicitacao, a.quantidade, a.valor_unit_est,
                a.preco_menor, a.preco_maior, a.preco_medio, a.quantidade_autorizada, a.cancelado, a.motivo_cancelamento,
                a.ordem,
                b.sq_material, b.sq_tipo_material, b.sq_unidade_medida, 
                b.nome, b.descricao, b.detalhamento, b.apresentacao, b.codigo_interno, b.codigo_externo, 
                b.exibe_catalogo, b.vida_util, b.ativo, b.sq_cc,
                b.pesquisa_preco_menor, b.pesquisa_preco_maior, b.pesquisa_preco_medio,
                b.pesquisa_data, b.pesquisa_validade, 
                b.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case b.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case b.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                case c.classe
                     when 1 then 'Medicamento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                e.nome as nm_cc,
                g.item_pedido,
                g.item_licitacao,
                h.sq_siw_solicitacao sq_solic_pai, h.quantidade_autorizada as qtd_licitacao,
                l.qtd_pedido,
                dados_solic(h.sq_siw_solicitacao) as dados_pai,
                i.qtd_cotacao,
                j.qtd_proposta,
                k.numero_ata,
                m.valor_unidade
           from cl_solicitacao_item                     a
                inner     join cl_material              b  on (a.sq_material         = b.sq_material)
                inner     join cl_tipo_material         c  on (b.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida        d  on (b.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join ct_cc                    e  on (b.sq_cc               = e.sq_cc)
                inner     join cl_parametro             f  on (b.cliente             = f.cliente)
                inner     join cl_solicitacao_item_vinc g on (a.sq_solicitacao_item  = g.item_pedido)
                  inner   join cl_solicitacao_item      h on (g.item_licitacao       = h.sq_solicitacao_item)
                    inner join cl_solicitacao           k on (h.sq_siw_solicitacao   = k.sq_siw_solicitacao)
                    left  join cl_item_fornecedor       m on (h.sq_solicitacao_item  = m.sq_solicitacao_item and
                                                              h.sq_material          = m.sq_material         and
                                                              'S'                    = m.vencedor)
                    left  join (select sum(x1.quantidade) as qtd_pedido, x2.item_licitacao
                                  from cl_solicitacao_item                   x1
                                       inner   join cl_solicitacao_item_vinc x2 on (x1.sq_solicitacao_item = x2.item_pedido)
                                       inner   join siw_solicitacao          x3 on (x1.sq_siw_solicitacao  = x3.sq_siw_solicitacao)
                                         inner join siw_menu                 x4 on (x3.sq_menu             = x4.sq_menu)
                                         inner join siw_tramite              x5 on (x3.sq_siw_tramite      = x5.sq_siw_tramite)
                                 where substr(x4.sigla,1,4) = 'CLRP'
                                   and x5.sigla             = 'AT' 
                                 group by x2.item_licitacao
                               )                        l on (h.sq_solicitacao_item = l.item_licitacao)
                  left    join (select y.sq_solicitacao_item, count(z.sq_item_fornecedor) as qtd_cotacao
                                  from siw_solicitacao                  x
                                       inner   join cl_solicitacao_item y on (x.sq_siw_solicitacao  = y.sq_siw_solicitacao)
                                         left  join cl_item_fornecedor  z on (y.sq_solicitacao_item = z.sq_solicitacao_item and
                                                                              'S'                   = z.pesquisa)
                                group by y.sq_solicitacao_item
                               )                        i on (a.sq_solicitacao_item  = i.sq_solicitacao_item)
                  left    join (select y.sq_solicitacao_item, count(z.sq_item_fornecedor) as qtd_proposta
                                  from siw_solicitacao                  x
                                       inner   join cl_solicitacao_item y on (x.sq_siw_solicitacao  = y.sq_siw_solicitacao)
                                         left  join cl_item_fornecedor  z on (y.sq_solicitacao_item = z.sq_solicitacao_item and
                                                                              'N'                   = z.pesquisa)
                                group by y.sq_solicitacao_item
                               )                        j on (a.sq_solicitacao_item  = j.sq_solicitacao_item)
          where (p_chave         is null or (p_chave         is not null and a.sq_solicitacao_item = p_chave))
            and (p_material      is null or (p_material      is not null and a.sq_material         = p_material))
            and (p_solicitacao   is null or (p_solicitacao   is not null and a.sq_siw_solicitacao  = p_solicitacao))
            and (p_cancelado     is null or (p_cancelado     is not null and a.cancelado           = p_cancelado));
   ElsIf p_restricao = 'LCITEM' Then
      -- Recupera materiais e serviços
      open p_result for 
         select /*+ ordered */ a.sq_solicitacao_item as chave, a.sq_siw_solicitacao, a.quantidade, a.valor_unit_est,
                a.preco_menor, a.preco_maior, a.preco_medio, a.quantidade_autorizada, a.cancelado, a.motivo_cancelamento,
                a.ordem,
                b.sq_material, b.sq_tipo_material, b.sq_unidade_medida, 
                b.nome, b.descricao, b.detalhamento, b.apresentacao, b.codigo_interno, b.codigo_externo, 
                b.exibe_catalogo, b.vida_util, b.ativo, b.sq_cc,
                b.pesquisa_preco_menor, b.pesquisa_preco_maior, b.pesquisa_preco_medio,
                b.pesquisa_data, b.pesquisa_validade, 
                b.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case b.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case b.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                case c.classe
                     when 1 then 'Medicamento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                e.nome as nm_cc,
                dados_solic(g.sq_siw_solicitacao) as dados_solic
           from cl_solicitacao_item                a
                inner     join cl_material         b  on (a.sq_material         = b.sq_material)
                inner     join cl_tipo_material    c  on (b.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida   d  on (b.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join ct_cc               e  on (b.sq_cc               = e.sq_cc)
                inner     join cl_parametro        f  on (b.cliente             = f.cliente)
                inner     join siw_solicitacao     g  on (a.sq_siw_solicitacao  = g.sq_siw_solicitacao)
                  inner   join siw_tramite         h  on (g.sq_siw_tramite      = h.sq_siw_tramite and
                                                          'AT'                  = coalesce(h.sigla,'-'))
          where a.quantidade_autorizada > 0
            and a.sq_solicitacao_item   not in (select x.item_pedido from cl_solicitacao_item_vinc x);
   ElsIf p_restricao = 'ARPITEM' Then
      -- Recupera materiais e serviços
      open p_result for 
         select /*+ ordered */ a.sq_solicitacao_item as chave, a.sq_siw_solicitacao, a.quantidade, a.valor_unit_est,
                a.preco_menor, a.preco_maior, a.preco_medio, a.quantidade_autorizada, a.cancelado, a.motivo_cancelamento,
                a.ordem,
                b.sq_material, b.sq_tipo_material, b.sq_unidade_medida, 
                b.nome, b.descricao, b.detalhamento, b.apresentacao, b.codigo_interno, b.codigo_externo, 
                b.exibe_catalogo, b.vida_util, b.ativo, b.sq_cc,
                b.pesquisa_preco_menor, b.pesquisa_preco_maior, b.pesquisa_preco_medio,
                b.pesquisa_data, b.pesquisa_validade, 
                b.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case b.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case b.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                case c.classe
                     when 1 then 'Medicamento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                e.nome as nm_cc,
                dados_solic(g.sq_siw_solicitacao) as dados_solic,
                i.numero_ata
           from cl_solicitacao_item                a
                inner     join cl_material         b  on (a.sq_material         = b.sq_material)
                inner     join cl_tipo_material    c  on (b.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida   d  on (b.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join ct_cc               e  on (b.sq_cc               = e.sq_cc)
                inner     join cl_parametro        f  on (b.cliente             = f.cliente)
                inner     join siw_solicitacao     g  on (a.sq_siw_solicitacao  = g.sq_siw_solicitacao)
                  inner   join siw_menu            g1 on (g.sq_menu             = g1.sq_menu and
                                                          'CLLC'                = substr(g1.sigla,1,4)
                                                         )
                  inner   join siw_tramite         h  on (g.sq_siw_tramite      = h.sq_siw_tramite and
                                                          'AT'                  = coalesce(h.sigla,'-'))
                inner     join cl_solicitacao      i  on (a.sq_siw_solicitacao  = i.sq_siw_solicitacao and
                                                          'S'                   = i.arp)
          where 0 < (a.quantidade_autorizada - (select coalesce(sum(x4.quantidade_autorizada),0)
                                                  from siw_menu                         x1
                                                       inner   join siw_solicitacao     x2 on (x1.sq_menu            = x2.sq_menu)
                                                         inner join siw_tramite         x3 on (x2.sq_siw_tramite     = x3.sq_siw_tramite)
                                                         inner join cl_solicitacao_item x4 on (x2.sq_siw_solicitacao = x4.sq_siw_solicitacao)
                                                 where substr(x1.sigla,1,4)   = 'CLRP'
                                                   and coalesce(x3.sigla,'-') = 'AT'
                                               )
                    )
            and a.sq_solicitacao_item not in (select x.item_licitacao from cl_solicitacao_item_vinc x inner join cl_solicitacao_item y on (x.item_pedido = y.sq_solicitacao_item) where y.sq_siw_solicitacao = p_solicitacao)
            and (p_tipo_material is null or (p_tipo_material is not null and b.sq_tipo_material = p_tipo_material))
            and (p_sq_cc         is null or (p_sq_cc         is not null and b.sq_cc            = p_sq_cc))
            and (p_codigo        is null or (p_codigo        is not null and b.codigo_interno   like '%'||p_codigo||'%'))
            and (p_nome          is null or (p_nome          is not null and acentos(b.nome)    like '%'||acentos(p_nome)||'%'));
   ElsIf p_restricao = 'FORNECEDORC' or p_restricao = 'FORNECEDORP' Then
      -- Recupera materiais e serviços
      open p_result for 
         select /*+ ordered */ a.sq_solicitacao_item as chave, a.sq_siw_solicitacao, a.quantidade, a.valor_unit_est,
                a.preco_menor, a.preco_maior, a.preco_medio, a.quantidade_autorizada, a.cancelado, a.motivo_cancelamento,
                a.ordem,
                b.sq_material, b.sq_tipo_material, b.sq_unidade_medida, 
                b.nome, b.descricao, b.detalhamento, b.apresentacao, b.codigo_interno, b.codigo_externo, 
                b.exibe_catalogo, b.vida_util, b.ativo, b.sq_cc,
                b.pesquisa_preco_menor, b.pesquisa_preco_maior, b.pesquisa_preco_medio,
                b.pesquisa_data, b.pesquisa_validade, 
                b.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case b.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case b.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                case c.classe
                     when 1 then 'Medicamento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                e.nome as nm_cc,
                g.item_pedido,
                h.sq_siw_solicitacao sq_solic_pai, h.quantidade_autorizada as qtd_pedido,
                dados_solic(h.sq_siw_solicitacao) as dados_pai,
                i.fim fornecedor_validade, i.inicio fornecedor_data, i.valor_unidade fornecedor_valor,
                i.fim-f.dias_aviso_pesquisa as fornecedor_aviso, i.fabricante, i.marca_modelo, i.embalagem,
                i.dias_validade_proposta,
                j.dias_validade_proposta as dias_validade_certame
           from cl_solicitacao_item                     a
                inner     join cl_material              b  on (a.sq_material         = b.sq_material)
                inner     join cl_tipo_material         c  on (b.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida        d  on (b.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join ct_cc                    e  on (b.sq_cc               = e.sq_cc)
                inner     join cl_parametro             f  on (b.cliente             = f.cliente)
                inner     join cl_solicitacao_item_vinc g on (a.sq_solicitacao_item  = g.item_licitacao)
                  inner   join cl_solicitacao_item      h on (g.item_pedido          = h.sq_solicitacao_item)
                left      join cl_item_fornecedor       i on (a.sq_solicitacao_item  = i.sq_solicitacao_item and
                                                              p_material             = i.fornecedor          and
                                                              ((p_restricao          = 'FORNECEDORC' and 'S' = i.pesquisa) or 
                                                               (p_restricao          = 'FORNECEDORP' and 'N' = i.pesquisa)
                                                               )
                                                              )                  
                inner     join cl_solicitacao           j  on (a.sq_siw_solicitacao  = j.sq_siw_solicitacao)
          where (p_chave         is null or (p_chave         is not null and a.sq_solicitacao_item = p_chave))
            and (p_solicitacao   is null or (p_solicitacao   is not null and a.sq_siw_solicitacao  = p_solicitacao))
            and (p_cancelado     is null or (p_cancelado     is not null and a.cancelado           = p_cancelado));
   ElsIf p_restricao = 'FORNECEDORA' Then
      -- Recupera materiais e serviços
      open p_result for 
         select /*+ ordered */ a.sq_solicitacao_item as chave, a.sq_siw_solicitacao, a.quantidade, a.valor_unit_est,
                a.preco_menor, a.preco_maior, a.preco_medio, a.quantidade_autorizada, a.cancelado, a.motivo_cancelamento,
                a.ordem,
                b.sq_material, b.sq_tipo_material, b.sq_unidade_medida, 
                b.nome, b.descricao, b.detalhamento, b.apresentacao, b.codigo_interno, b.codigo_externo, 
                b.exibe_catalogo, b.vida_util, b.ativo, b.sq_cc,
                b.pesquisa_preco_menor, b.pesquisa_preco_maior, b.pesquisa_preco_medio,
                b.pesquisa_data, b.pesquisa_validade, 
                b.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case b.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case b.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                case c.classe
                     when 1 then 'Medicamento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                e.nome as nm_cc,
                g.item_pedido,
                h.sq_siw_solicitacao sq_solic_pai, h.quantidade_autorizada as qtd_pedido,
                dados_solic(h.sq_siw_solicitacao) as dados_pai,
                i.fim fornecedor_validade, i.inicio fornecedor_data, i.valor_unidade fornecedor_valor,
                i.fim-f.dias_aviso_pesquisa as fornecedor_aviso, i.fabricante, i.marca_modelo, i.embalagem,
                i.dias_validade_proposta
           from cl_solicitacao_item                     a
                inner     join cl_material              b  on (a.sq_material         = b.sq_material)
                inner     join cl_tipo_material         c  on (b.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida        d  on (b.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join ct_cc                    e  on (b.sq_cc               = e.sq_cc)
                inner     join cl_parametro             f  on (b.cliente             = f.cliente)
                inner     join cl_solicitacao_item_vinc g on (a.sq_solicitacao_item  = g.item_pedido)
                  inner   join cl_solicitacao_item      h on (g.item_licitacao       = h.sq_solicitacao_item)
                left      join cl_item_fornecedor       i on (a.sq_solicitacao_item  = i.sq_solicitacao_item and
                                                              p_material             = i.fornecedor          and
                                                              'S'                    = i.pesquisa)                  
          where (p_chave         is null or (p_chave         is not null and a.sq_solicitacao_item = p_chave))
            and (p_solicitacao   is null or (p_solicitacao   is not null and a.sq_siw_solicitacao  = p_solicitacao))
            and (p_cancelado     is null or (p_cancelado     is not null and a.cancelado           = p_cancelado));            
   ElsIf p_restricao = 'VALIDACAOC' Then
      -- Verifica a quantidade de pesquisas de preco válidas para cada item da licitação
      open p_result for 
         select b.sq_material, c.nome, c.codigo_interno, count(d.sq_item_fornecedor) as qtd
           from siw_solicitacao                    a
                inner     join cl_solicitacao_item b on (a.sq_siw_solicitacao  = b.sq_siw_solicitacao)
                  inner   join cl_material         c on (b.sq_material         = c.sq_material)
                    left  join cl_item_fornecedor  d on (c.sq_material         = d.sq_material and
                                                         'S'                   = d.pesquisa
                                                        )
          where a.sq_siw_solicitacao = p_solicitacao
         group by b.sq_material, c.nome, c.codigo_interno;
   Elsif p_restricao = 'VALIDACAOG' Then
      -- Verifica a quantidade de propostas inseridas para cada item da licitação
      open p_result for 
         select a.qtd as qt_itens, b.qtd as qt_fornecedores, c.qtd as qt_propostas
           from (select count(*) as qtd from cl_solicitacao_item where sq_siw_solicitacao = p_solicitacao) a,
                (select count(distinct(fornecedor)) as qtd from cl_solicitacao_item x join cl_item_fornecedor y on (x.sq_solicitacao_item = y.sq_solicitacao_item and y.pesquisa = 'N') where sq_siw_solicitacao = p_solicitacao) b,
                (select count(y.sq_item_fornecedor) as qtd from cl_solicitacao_item x join cl_item_fornecedor y on (x.sq_solicitacao_item = y.sq_solicitacao_item and y.pesquisa = 'N') where sq_siw_solicitacao = p_solicitacao) c;
   ElsIf p_restricao = 'PROPOSTA' or p_restricao = 'COTACAO' Then
      -- Recuperas as propostas de um certame
      open p_result for 
         select /*+ ordered */ a.sq_solicitacao_item as chave, a.sq_siw_solicitacao, a.quantidade, a.valor_unit_est,
                a.preco_menor, a.preco_maior, a.preco_medio, a.quantidade_autorizada, a.cancelado, a.motivo_cancelamento,
                a.ordem, a.dias_validade_proposta as dias_validade_item,
                b.sq_material, b.sq_tipo_material, b.sq_unidade_medida, 
                b.nome, b.descricao, b.detalhamento, b.apresentacao, b.codigo_interno, b.codigo_externo, 
                b.exibe_catalogo, b.vida_util, b.ativo, b.sq_cc,
                b.pesquisa_preco_menor, b.pesquisa_preco_maior, 
                coalesce(b.pesquisa_preco_medio,0) as pesquisa_preco_medio,
                b.pesquisa_data, b.pesquisa_validade, 
                b.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case b.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case b.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                case c.classe
                     when 1 then 'Medicamento'
                     when 3 then 'Consumo'
                     when 4 then 'Permanente'
                     when 5 then 'Serviço'
                end as nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                e.nome as nm_cc,
                f.percentual_acrescimo,
                g.inicio as proposta_data, g.fim as proposta_validade, g.valor_unidade, g.valor_item,
                g.fornecedor, g.dias_validade_proposta,
                h.nome_resumido nm_fornecedor,
                i.qtd_proposta,
                nvl(b.pesquisa_preco_medio,0)*nvl(f.percentual_acrescimo,0)/100 as variacao_valor
           from cl_solicitacao_item                     a
                inner     join cl_material              b  on (a.sq_material         = b.sq_material)
                inner     join cl_tipo_material         c  on (b.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida        d  on (b.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join ct_cc                    e  on (b.sq_cc               = e.sq_cc)
                inner     join cl_parametro             f  on (b.cliente             = f.cliente)
                left      join cl_item_fornecedor       g  on (a.sq_solicitacao_item = g.sq_solicitacao_item and
                                                               ((p_restricao = 'COTACAO'  and 'S' = g.pesquisa) or 
                                                                (p_restricao = 'PROPOSTA' and 'N' = g.pesquisa)
                                                               )
                                                              )
                  left    join co_pessoa                h on (g.fornecedor           = h.sq_pessoa)
                left    join (select y.sq_solicitacao_item, count(z.sq_item_fornecedor) as qtd_proposta
                                from siw_solicitacao                  x
                                     inner   join cl_solicitacao_item y on (x.sq_siw_solicitacao  = y.sq_siw_solicitacao)
                                       left  join cl_item_fornecedor  z on (y.sq_solicitacao_item = z.sq_solicitacao_item and
                                                                            ((p_restricao = 'COTACAO'  and 'S' = z.pesquisa) or 
                                                                             (p_restricao = 'PROPOSTA' and 'N' = z.pesquisa)
                                                                            )
                                                                           )                              
                              group by y.sq_solicitacao_item
                             )                           i on (a.sq_solicitacao_item  = i.sq_solicitacao_item)                                                                             
          where (p_solicitacao   is null or (p_solicitacao   is not null and a.sq_siw_solicitacao  = p_solicitacao));
   End If;
end sp_getCLSolicItem;           
/
