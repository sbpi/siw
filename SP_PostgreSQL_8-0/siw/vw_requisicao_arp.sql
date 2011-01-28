create or replace view vw_requisicao_arp as
select a1.codigo_interno as req_t, to_char(a1.inicio, 'dd/mm/yyy') as data_pedido, k.codigo_interno as numero_ata, h.ordem as item_ata, 
      a.quantidade, a.quantidade_autorizada, h.quantidade as cmm,
      a.sq_solicitacao_item as chave, a.sq_siw_solicitacao
 from cl_solicitacao_item                         a
      inner         join siw_solicitacao          a1 on (a.sq_siw_solicitacao  = a1.sq_siw_solicitacao)
        inner       join siw_menu                 a2 on (a1.sq_menu            = a2.sq_menu)
      inner         join cl_material              b  on (a.sq_material         = b.sq_material)
      inner         join cl_tipo_material         c  on (b.sq_tipo_material    = c.sq_tipo_material)
      inner         join co_unidade_medida        d  on (b.sq_unidade_medida   = d.sq_unidade_medida)
      inner         join cl_parametro             f  on (b.cliente             = f.cliente)
      inner         join cl_solicitacao_item_vinc g  on (a.sq_solicitacao_item  = g.item_pedido)
        inner       join cl_solicitacao_item      h  on (g.item_licitacao      = h.sq_solicitacao_item)
          inner     join siw_solicitacao          k  on (h.sq_siw_solicitacao  = k.sq_siw_solicitacao)
            left    join ac_acordo                k1 on (k.sq_siw_solicitacao  = k1.sq_siw_solicitacao)
              left  join co_pessoa                k2 on (k1.outra_parte        = k2.sq_pessoa)
          left      join cl_item_fornecedor       m  on (h.sq_solicitacao_item = m.sq_solicitacao_item and
                                                         h.sq_material         = m.sq_material         and
                                                         'S'                   = m.vencedor)
          left      join (select sum(x1.quantidade) as qtd_pedido, x2.item_licitacao
                            from cl_solicitacao_item                   x1
                                 inner   join cl_solicitacao_item_vinc x2 on (x1.sq_solicitacao_item = x2.item_pedido)
                                 inner   join siw_solicitacao          x3 on (x1.sq_siw_solicitacao  = x3.sq_siw_solicitacao)
                                   inner join siw_menu                 x4 on (x3.sq_menu             = x4.sq_menu)
                                   inner join siw_tramite              x5 on (x3.sq_siw_tramite      = x5.sq_siw_tramite)
                           where substr(x4.sigla,1,4) = 'CLRP'
                             and x5.sigla             = 'AT' 
                           group by x2.item_licitacao
                         )                        l  on (h.sq_solicitacao_item = l.item_licitacao)
        left        join (select y.sq_solicitacao_item, count(z.sq_item_fornecedor) as qtd_cotacao
                            from siw_solicitacao                  x
                                 inner   join cl_solicitacao_item y on (x.sq_siw_solicitacao  = y.sq_siw_solicitacao)
                                   left  join cl_item_fornecedor  z on (y.sq_material         = z.sq_material and
                                                                        'S'                   = z.pesquisa)
                           where z.fim >= trunc(now())
                          group by y.sq_solicitacao_item
                         )                        i  on (a.sq_solicitacao_item  = i.sq_solicitacao_item)
        left        join (select y.sq_solicitacao_item, count(z.sq_item_fornecedor) as qtd_proposta
                            from siw_solicitacao                  x
                                 inner   join cl_solicitacao_item y on (x.sq_siw_solicitacao  = y.sq_siw_solicitacao)
                                   left  join cl_item_fornecedor  z on (y.sq_solicitacao_item = z.sq_solicitacao_item and
                                                                        'N'                   = z.pesquisa)
                          group by y.sq_solicitacao_item
                         )                         j on (a.sq_solicitacao_item  = j.sq_solicitacao_item)
where a2.sq_pessoa = 9614