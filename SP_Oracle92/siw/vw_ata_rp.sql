create or replace view vw_ata_rp as
select a.nome as "Produto", a.descricao, a.detalhamento, 
       translate(a.codigo_interno,'1-.','1') as "CODIGO", a.codigo_externo as "CODIGO ANTIGO",
       case when a.pesquisa_preco_menor is null then null else 'R$ '||fValor(a.pesquisa_preco_menor,'T',4) end as pesquisa_preco_menor,
       case when a.pesquisa_preco_maior is null then null else 'R$ '||fValor(a.pesquisa_preco_maior,'T',4) end as pesquisa_preco_maior,
       case when a.pesquisa_preco_medio is null then null else 'R$ '||fValor(a.pesquisa_preco_medio,'T',4) end as pesquisa_preco_medio,
       a.pesquisa_data, a.pesquisa_validade,
       d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
       x.ordem as "Item",
       case when x.quantidade is null then null else replace(fValor(x.quantidade,'T',0),',','') end as "CMM - Ata de RP",
       x.motivo_cancelamento as "Status",
       w.inclusao as "DataHoje", w.codigo_interno as "Nº da Ata", w.fim as "Validade",
       w2.publicacao as "DOM",
       f.percentual_acrescimo,
       case when x1.valor_unidade is null then null else fValor(x1.valor_unidade,'T',4) end as "Valor Unitário",
       case when x1.valor_item is null then null else 'R$ '||fValor(x1.valor_item,'T',4) end as "Valor Quant Mensal",
       x1.marca_modelo as "Marca",
       x1.fabricante as "Fabricante",
       x1.embalagem as "Apresentação",
       w3.nome as "Empresa Detentora",
       w3.nome_resumido as "Detentora Resum",
       ((1 - (a.pesquisa_preco_medio/x1.valor_unidade)) * 100) as variacao_valor,
       w3.sq_pessoa as sq_detentor_ata, a.sq_material, a.sq_unidade_medida
  from cl_material                        a
       inner     join cl_tipo_material    c  on (a.sq_tipo_material    = c.sq_tipo_material)
       inner     join co_unidade_medida   d  on (a.sq_unidade_medida   = d.sq_unidade_medida)
       inner     join cl_parametro        f  on (a.cliente             = f.cliente)
       inner     join cl_solicitacao_item x  on (a.sq_material         = x.sq_material)
         inner   join cl_item_fornecedor  x1 on (x.sq_solicitacao_item = x1.sq_solicitacao_item)
         inner   join siw_solicitacao     w  on (x.sq_siw_solicitacao  = w.sq_siw_solicitacao)
           inner join siw_menu            w1 on (w.sq_menu             = w1.sq_menu and w1.sigla = 'GCZCAD')
           inner join ac_acordo           w2 on (w.sq_siw_solicitacao  = w2.sq_siw_solicitacao)
           inner join co_pessoa           w3 on (w2.outra_parte        = w3.sq_pessoa)
           inner join siw_tramite         z  on (w.sq_siw_tramite      = z.sq_siw_tramite and
                                                 z.sigla               <> 'CA'
                                                )
 where a.cliente         = 9614;

