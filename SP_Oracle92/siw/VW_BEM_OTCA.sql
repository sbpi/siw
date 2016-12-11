create or replace view VW_BEM_OTCA as
         select p.sq_permanente as chave, p.cliente, p.sq_localizacao, p.sq_almoxarifado, 
                p.sq_cc, p.sq_material, p.sq_entrada_item, p.sq_mtsituacao, p.sq_projeto,
                p.fornecedor_garantia, p.numero_rgp, p.data_tombamento, 
                p.descricao_complementar, p.numero_serie, p.marca, p.modelo, p.data_fim_garantia, 
                p.vida_util, p.observacao, p.ativo,
                case p.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                a.sq_tipo_material, a.sq_unidade_medida, 
                a.nome, a.descricao, a.detalhamento, a.apresentacao, a.codigo_interno, a.codigo_externo, 
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, 
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome nm_localizacao, e.sq_unidade, e.nome nm_unidade, e.sigla sg_unidade,
                f.logradouro,
                g.nome nm_cc, g.sigla sg_cc,
                h.titulo nm_projeto,
                brl.valor_aquisicao vl_aquisicao_brl, brl.valor_atual vl_atual_brl, brl.data_valor_atual dt_vl_atual_brl,
                usd.valor_aquisicao vl_aquisicao_usd, usd.valor_atual vl_atual_usd, usd.data_valor_atual dt_vl_atual_usd,
                eur.valor_aquisicao vl_aquisicao_eur, eur.valor_atual vl_atual_eur, eur.data_valor_atual dt_vl_atual_eur
           from mt_permanente                            p
                inner           join cl_material         a  on (p.sq_material         = a.sq_material)
                  inner         join cl_tipo_material    c  on (a.sq_tipo_material    = c.sq_tipo_material)
                inner           join eo_localizacao      d  on (p.sq_localizacao      = d.sq_localizacao)
                  inner         join eo_unidade          e  on (d.sq_unidade          = e.sq_unidade)
                  inner         join co_pessoa_endereco  f  on (d.sq_pessoa_endereco  = f.sq_pessoa_endereco)
                left            join ct_cc               g  on (p.sq_cc               = g.sq_cc)
                left            join siw_solicitacao     h  on (p.sq_projeto          = h.sq_siw_solicitacao)
                left            join mt_entrada_item     i  on (p.sq_entrada_item     = i.sq_entrada_item)
                  left          join fn_documento_item   j  on (i.sq_documento_item   = j.sq_documento_item)
                    left        join fn_lancamento_doc   k  on (j.sq_lancamento_doc   = k.sq_lancamento_doc)
                      left      join fn_lancamento       l  on (k.sq_siw_solicitacao  = l.sq_siw_solicitacao)
                        left    join siw_solicitacao     m  on (l.sq_siw_solicitacao  = m.sq_siw_solicitacao)
                left            join (select cot.sq_permanente, cot.sq_moeda, cot.valor_aquisicao, cot.valor_atual, cot.data_valor_atual
                                        from mt_bem_cotacao      cot
                                             inner join co_moeda moe on (cot.sq_moeda = moe.sq_moeda)
                                       where moe.sigla = 'BRL'
                                     )                brl  on (p.sq_permanente       = brl.sq_permanente)
                left            join (select cot.sq_permanente, cot.sq_moeda, cot.valor_aquisicao, cot.valor_atual, cot.data_valor_atual
                                        from mt_bem_cotacao      cot
                                             inner join co_moeda moe on (cot.sq_moeda = moe.sq_moeda)
                                       where moe.sigla = 'USD'
                                     )                usd  on (p.sq_permanente       = usd.sq_permanente)
                left            join (select cot.sq_permanente, cot.sq_moeda, cot.valor_aquisicao, cot.valor_atual, cot.data_valor_atual
                                        from mt_bem_cotacao      cot
                                             inner join co_moeda moe on (cot.sq_moeda = moe.sq_moeda)
                                       where moe.sigla = 'EUR'
                                     )                eur  on (p.sq_permanente       = eur.sq_permanente)
          where a.cliente        = 17305;
