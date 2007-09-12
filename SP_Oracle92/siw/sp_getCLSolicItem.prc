create or replace procedure sp_getCLSolicItem
   (p_chave         in number    default null,
    P_solicitacao   in number    default null,
    p_material      in number    default null,
    p_cancelado     in varchar2  default null,
    p_restricao     in varchar2  default null,
    p_result        out sys_refcursor) is
begin
   If p_restricao is null or p_restricao = 'COMPRA' Then
      -- Recupera materiais e serviços
      open p_result for 
         select /*+ ordered */ a.sq_solicitacao_item as chave, a.sq_siw_solicitacao, a.quantidade, a.valor_unit_est,
                a.preco_menor, a.preco_maior, a.preco_medio, a.quantidade_autorizada, a.cancelado, a.motivo_cancelamento,
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
                dados_solic(h.sq_siw_solicitacao) as dados_pai
           from cl_solicitacao_item                     a
                inner     join cl_material              b  on (a.sq_material         = b.sq_material)
                inner     join cl_tipo_material         c  on (b.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida        d  on (b.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join ct_cc                    e  on (b.sq_cc               = e.sq_cc)
                inner     join cl_parametro             f  on (b.cliente             = f.cliente)
                inner     join cl_solicitacao_item_vinc g on (a.sq_solicitacao_item  = g.item_licitacao)
                  inner   join cl_solicitacao_item      h on (g.item_pedido          = h.sq_solicitacao_item)
          where (p_chave         is null or (p_chave         is not null and a.sq_solicitacao_item = p_chave))
            and (p_material      is null or (p_material      is not null and a.sq_material         = p_material))
            and (p_solicitacao   is null or (p_solicitacao   is not null and a.sq_siw_solicitacao  = p_solicitacao))
            and (p_cancelado     is null or (p_cancelado     is not null and a.cancelado           = p_cancelado));
   ElsIf p_restricao = 'LCITEM' Then
      -- Recupera materiais e serviços
      open p_result for 
         select /*+ ordered */ a.sq_solicitacao_item as chave, a.sq_siw_solicitacao, a.quantidade, a.valor_unit_est,
                a.preco_menor, a.preco_maior, a.preco_medio, a.quantidade_autorizada, a.cancelado, a.motivo_cancelamento,
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
   End If;
end sp_getCLSolicItem;           
/
