create or replace view vw_pesquisa_mercado as
select a.nome as "PRODUTO", a.descricao, a.detalhamento, 
          translate(a.codigo_interno,'1-.','1') as "CODIGO", a.codigo_externo as "CODIGO ANTIGO", 
          replace(a.pesquisa_preco_menor,'.',',') as pesquisa_preco_menor, 
          replace(a.pesquisa_preco_maior,'.',',') as pesquisa_preco_maior, 
          replace(a.pesquisa_preco_medio,'.',',') as pesquisa_preco_medio, 
          a.pesquisa_data, a.pesquisa_validade, 
          c.nome as nm_tipo_material, 
          d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
          replace(g.valor_unidade,'.',',') as "PU_Forn", 
          g.inicio as "DataInserção",  g.fim as "Validade_P", 
          g.origem, case g.origem when 'SA' then 'ARP externa' when 'SG' then 'Governo' when 'SF' then 'Site comercial' else 'Proposta fornecedor' end as "TipoForn",
          h.nome as "Fornecedores", h.nome_resumido as nm_fornecedor_res, g.fornecedor as chave_fornecedor,
          a.sq_material as chave, a.sq_tipo_material, a.sq_unidade_medida
     from cl_material                        a
          inner     join cl_tipo_material    c  on (a.sq_tipo_material    = c.sq_tipo_material)
          inner     join co_unidade_medida   d  on (a.sq_unidade_medida   = d.sq_unidade_medida)
          inner     join cl_parametro        f  on (a.cliente             = f.cliente)
          inner     join cl_item_fornecedor  g  on (a.sq_material         = g.sq_material and
                                                    g.pesquisa            = 'S'
                                                   )
            inner   join co_pessoa           h  on (g.fornecedor          = h.sq_pessoa)
    where a.cliente         = 9614
      and g.inicio          <= trunc(sysdate)

