create or replace view vw_requisicao_lic as
select a1.codigo_interno as req_t, to_char(a1.inicio, 'dd/mm/yyy') as data_pedido, a3.nome as unidade_solicitante,
      a.ordem as item,
      translate(b.codigo_interno, '1.-', '1') as codigo, sum(a.quantidade) as quantidade
 from cl_solicitacao_item                     a
      inner         join siw_solicitacao      a1 on (a.sq_siw_solicitacao  = a1.sq_siw_solicitacao)
        inner       join eo_unidade           a3 on (a1.sq_unidade         = a3.sq_unidade)
        inner       join siw_menu             a2 on (a1.sq_menu            = a2.sq_menu)
      inner     join cl_material              b  on (a.sq_material         = b.sq_material)
      inner     join cl_tipo_material         c  on (b.sq_tipo_material    = c.sq_tipo_material)
      inner     join co_unidade_medida        d  on (b.sq_unidade_medida   = d.sq_unidade_medida)
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
where a2.sq_pessoa = 9614
  and a2.sigla     = 'CLLCCAD'
group by a1.codigo_interno, a1.inicio, a3.nome, a.ordem, b.codigo_interno;
