create or replace view vw_item_catalogo as
select a.sq_material, 
       a.nome, 
       b.nome as nm_unidade_medida,
       b.sigla as sg_unidade_medida,
       c.nome as nm_tipo_material,
       a.detalhamento, 
       translate(a.codigo_interno,'1.-','1') as codigo_interno, 
       translate(a.codigo_externo,'1.-','1') as codigo_externo, 
       a.exibe_catalogo, 
       a.ativo, 
       a.pesquisa_data, 
       a.pesquisa_validade, 
       a.pesquisa_preco_menor, 
       a.pesquisa_preco_maior, 
       a.pesquisa_preco_medio 
  from cl_material                  a
       inner join co_unidade_medida b on (a.sq_unidade_medida = b.sq_unidade_medida)
       inner join cl_tipo_material  c on (a.sq_tipo_material  = c.sq_tipo_material)
 where cliente = 9614;