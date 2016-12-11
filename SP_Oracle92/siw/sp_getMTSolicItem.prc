create or replace procedure sp_getMTSolicItem
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
         select a.sq_solicitacao_item as chave, a.sq_siw_solicitacao, a.quantidade, a.valor_unit_est,
                a.preco_menor, a.preco_maior, a.preco_medio, a.quantidade_autorizada, a.cancelado, a.motivo_cancelamento,
                a.ordem, a.dias_validade_proposta as dias_validade_item, a.detalhamento det_item,
                b.sq_material, b.sq_tipo_material, b.sq_unidade_medida,
                b.nome, b.descricao, b.detalhamento, b.apresentacao, b.codigo_interno, b.codigo_externo,
                b.exibe_catalogo, b.vida_util, b.ativo,
                b.pesquisa_preco_menor, b.pesquisa_preco_maior, b.pesquisa_preco_medio,
                b.pesquisa_data, b.pesquisa_validade,
                b.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case b.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case b.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                retornaNomeClasse(c.classe) nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                coalesce(j.sq_siw_solicitacao,l.sq_siw_solicitacao) as solic_filho,
                case when to_char(l.sq_siw_solicitacao) is not null
                     then to_char(l.sq_siw_solicitacao)
                     else case when to_char(k.sq_siw_tramite) is not null
                               then coalesce(i.numero_certame, j.codigo_interno)
                               else null
                          end
                end as codigo_filho
           from cl_solicitacao_item                         a
                inner        join cl_material               b  on (a.sq_material         = b.sq_material)
                inner        join cl_tipo_material          c  on (b.sq_tipo_material    = c.sq_tipo_material)
                inner        join co_unidade_medida         d  on (b.sq_unidade_medida   = d.sq_unidade_medida)
                inner        join cl_parametro              f  on (b.cliente             = f.cliente)
                left         join cl_solicitacao_item_vinc  g  on (a.sq_solicitacao_item = g.item_pedido)
                  left       join cl_solicitacao_item       h  on (g.item_licitacao      = h.sq_solicitacao_item)
                    left     join cl_solicitacao            i  on (h.sq_siw_solicitacao  = i.sq_siw_solicitacao)
                    left     join siw_solicitacao           j  on (i.sq_siw_solicitacao  = j.sq_siw_solicitacao)
                      left   join siw_tramite               k  on (j.sq_siw_tramite      = k.sq_siw_tramite and
                                                                   k.sigla              <> 'CA'
                                                                  )
                left         join (select w.cliente, w.sq_siw_solicitacao, w.sq_solic_vinculo
                                     from fn_lancamento                     w
                                            inner join siw_solicitacao      x on (w.sq_siw_solicitacao  = x.sq_siw_solicitacao)
                                            inner join siw_tramite         x1 on (x.sq_siw_tramite      = x1.sq_siw_tramite and
                                                                                  x1.sigla              <> 'CA'
                                                                                 )
                                  )                         l  on (b.cliente             = l.cliente and
                                                                   a.sq_siw_solicitacao  = l.sq_solic_vinculo
                                                                  )
          where (p_chave         is null or (p_chave         is not null and a.sq_solicitacao_item = p_chave))
            and (p_material      is null or (p_material      is not null and a.sq_material         = p_material))
            and (p_solicitacao   is null or (p_solicitacao   is not null and a.sq_siw_solicitacao  = p_solicitacao))
            and (p_cancelado     is null or (p_cancelado     is not null and a.cancelado           = p_cancelado));
   Elsif p_restricao = 'ITEMARP' Then
      -- Recupera materiais e serviços
      open p_result for
         select a.sq_solicitacao_item as chave, a.sq_siw_solicitacao, a.quantidade, a.valor_unit_est,
                a.preco_menor, a.preco_maior, a.preco_medio, a.quantidade_autorizada, a.cancelado, a.motivo_cancelamento,
                a.ordem, a.dias_validade_proposta as dias_validade_item, a.detalhamento det_item,
                case a.cancelado when 'S' then 'Sim' else 'Não' end as nm_cancelado,
                b.sq_material, b.sq_tipo_material, b.sq_unidade_medida,
                b.nome, b.descricao, b.detalhamento, b.apresentacao, b.codigo_interno, b.codigo_externo,
                b.exibe_catalogo, b.vida_util, b.ativo,
                b.pesquisa_preco_menor, b.pesquisa_preco_maior, b.pesquisa_preco_medio,
                b.pesquisa_data, b.pesquisa_validade,
                b.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case b.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case b.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                retornaNomeClasse(c.classe) nm_classe,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                g.fabricante, g.marca_modelo, g.embalagem, g.valor_unidade, g.valor_item, g.fator_embalagem
           from cl_solicitacao_item                a
                inner     join cl_material         b  on (a.sq_material         = b.sq_material)
                  inner   join cl_tipo_material    c  on (b.sq_tipo_material    = c.sq_tipo_material)
                  inner   join co_unidade_medida   d  on (b.sq_unidade_medida   = d.sq_unidade_medida)
                  inner   join cl_parametro        f  on (b.cliente             = f.cliente)
                inner     join cl_item_fornecedor  g  on (a.sq_solicitacao_item = g.sq_solicitacao_item)
          where a.sq_siw_solicitacao = p_solicitacao
            and (p_chave         is null or (p_chave         is not null and a.sq_solicitacao_item = p_chave))
            and (p_material      is null or (p_material      is not null and a.sq_material         = p_material))
            and (p_codigo        is null or (p_codigo        is not null and b.codigo_interno      = p_codigo))
            and (p_cancelado     is null or (p_cancelado     is not null and a.cancelado           = p_cancelado));
   ElsIf p_restricao = 'LICPREVORC' Then
      -- Recupera previsão orçamentária de uma licitação
      open p_result for
         select g.sq_siw_solicitacao as sq_projeto, g.codigo_interno as cd_projeto, g.titulo as nm_projeto,
                h.sq_projeto_rubrica as sq_rubrica, h.codigo||' - '||h.nome as nm_rubrica,
                sum(d.quantidade_autorizada*coalesce(coalesce(j.vl_proposta,i.pesquisa_preco_medio),0)) as vl_pesquisa
           from cl_solicitacao                                a
                inner           join cl_solicitacao_item      b on (a.sq_siw_solicitacao  = b.sq_siw_solicitacao)
                  inner         join cl_solicitacao_item_vinc c on (b.sq_solicitacao_item = c.item_licitacao)
                    inner       join cl_solicitacao_item      d on (c.item_pedido         = d.sq_solicitacao_item)
                      inner     join cl_solicitacao           e on (d.sq_siw_solicitacao  = e.sq_siw_solicitacao)
                        inner   join cl_vinculo_financeiro    f on (e.sq_financeiro       = f.sq_clvinculo_financeiro)
                          inner join siw_solicitacao          g on (f.sq_siw_solicitacao  = g.sq_siw_solicitacao)
                          inner join pj_rubrica               h on (f.sq_projeto_rubrica  = h.sq_projeto_rubrica)
                      inner     join cl_material              i on (d.sq_material         = i.sq_material)
                  left          join (select w.sq_solicitacao_item, min(w.valor_unidade) as vl_proposta
                                        from cl_item_fornecedor w
                                       where vencedor = 'S'
                                          or (vencedor = 'N' and pesquisa = 'N')
                                      group by w.sq_solicitacao_item
                                     )                        j on (b.sq_solicitacao_item = j.sq_solicitacao_item)
          where a.sq_siw_solicitacao = p_solicitacao
         group by g.sq_siw_solicitacao, g.codigo_interno, g.titulo, h.sq_projeto_rubrica, h.codigo, h.nome
         order by g.titulo, h.codigo, h.nome;
   ElsIf p_restricao = 'LICPREVFIN' Then
      -- Recupera previsão financeira de uma licitação
      open p_result for
         select h.sq_tipo_lancamento as sq_lancamento, h.nome as nm_lancamento,
                sum(d.quantidade_autorizada*coalesce(coalesce(j.vl_proposta,i.pesquisa_preco_medio),0)) as vl_pesquisa
           from cl_solicitacao                                a
                inner           join cl_solicitacao_item      b on (a.sq_siw_solicitacao  = b.sq_siw_solicitacao)
                  inner         join cl_solicitacao_item_vinc c on (b.sq_solicitacao_item = c.item_licitacao)
                    inner       join cl_solicitacao_item      d on (c.item_pedido         = d.sq_solicitacao_item)
                      inner     join cl_solicitacao           e on (d.sq_siw_solicitacao  = e.sq_siw_solicitacao)
                        inner   join cl_vinculo_financeiro    f on (e.sq_financeiro       = f.sq_clvinculo_financeiro)
                          inner join fn_tipo_lancamento       h on (f.sq_tipo_lancamento  = h.sq_tipo_lancamento)
                      inner     join cl_material              i on (d.sq_material         = i.sq_material)
                  left          join (select w.sq_solicitacao_item, min(w.valor_unidade) as vl_proposta
                                        from cl_item_fornecedor w
                                       where vencedor = 'S'
                                          or (vencedor = 'N' and pesquisa = 'N')
                                      group by w.sq_solicitacao_item
                                     )                        j on (b.sq_solicitacao_item = j.sq_solicitacao_item)
          where a.sq_siw_solicitacao = p_solicitacao
         group by h.sq_tipo_lancamento, h.nome
         order by h.nome;
   ElsIf p_restricao = 'LICITACAO' Then
      -- Recupera materiais e serviços
      open p_result for
         select a.sq_solicitacao_item as chave, a.sq_siw_solicitacao, a.quantidade, a.valor_unit_est,
                a.preco_menor, a.preco_maior, a.preco_medio, a.quantidade_autorizada, a.cancelado, a.motivo_cancelamento,
                a.ordem, a.detalhamento det_item,
                b.sq_material, b.sq_tipo_material, b.sq_unidade_medida,
                b.nome, b.descricao, b.detalhamento, b.apresentacao, b.codigo_interno, b.codigo_externo,
                b.exibe_catalogo, b.vida_util, b.ativo,
                b.pesquisa_preco_menor, b.pesquisa_preco_maior, b.pesquisa_preco_medio,
                b.pesquisa_data, b.pesquisa_validade,
                b.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case b.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case b.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                retornaNomeClasse(c.classe) nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                g.item_pedido,
                g.item_licitacao,
                h.sq_siw_solicitacao as sq_solic_pai, h.quantidade_autorizada as qtd_pedido,
                dados_solic(h.sq_siw_solicitacao) as dados_pai,
                i.qtd_cotacao,
                j.qtd_proposta,
                k.sq_siw_solicitacao as solic_filho, dados_solic(k.sq_siw_solicitacao) as dados_filho
           from cl_solicitacao_item                     a
                inner     join cl_material              b  on (a.sq_material         = b.sq_material)
                inner     join cl_tipo_material         c  on (b.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida        d  on (b.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join cl_parametro             f  on (b.cliente             = f.cliente)
                inner     join cl_solicitacao_item_vinc g on (a.sq_solicitacao_item  = g.item_licitacao)
                  inner   join cl_solicitacao_item      h on (g.item_pedido          = h.sq_solicitacao_item)
                    inner join siw_solicitacao         h1 on (h.sq_siw_solicitacao   = h1.sq_siw_solicitacao)
                    inner join siw_menu                h2 on (h1.sq_menu             = h2.sq_menu)
                    inner join siw_modulo              h3 on (h2.sq_modulo           = h3.sq_modulo and
                                                              h3.sigla               = 'CO'
                                                             )
                    inner join siw_tramite             h4 on (h1.sq_siw_tramite      = h4.sq_siw_tramite and
                                                              h4.sigla              <> 'CA'
                                                             )
                  left    join (select  z.sq_solicitacao_item, count(z.sq_item_fornecedor) as qtd_cotacao
                                  from cl_item_fornecedor  z
                                 where 'S' = z.pesquisa
                                group by z.sq_solicitacao_item
                               )                        i on (a.sq_solicitacao_item  = i.sq_solicitacao_item)
                  left    join (select z.sq_solicitacao_item, count(z.sq_item_fornecedor) as qtd_proposta
                                  from cl_item_fornecedor  z
                                 where 'N' = z.pesquisa
                                group by z.sq_solicitacao_item
                               )                        j on (a.sq_solicitacao_item  = j.sq_solicitacao_item)
                  left    join (select v.item_licitacao, x.sq_siw_solicitacao
                                  from cl_solicitacao_item_vinc          v
                                       inner   join cl_solicitacao_item  w on (v.item_pedido         = w.sq_solicitacao_item)
                                         inner join siw_solicitacao      x on (w.sq_siw_solicitacao  = x.sq_siw_solicitacao)
                                         inner join siw_tramite         x1 on (x.sq_siw_tramite      = x1.sq_siw_tramite and
                                                                               x1.sigla              <> 'CA'
                                                                              )
                                         inner join siw_menu             y on (x.sq_menu             = y.sq_menu)
                                         inner join siw_modulo           z on (y.sq_modulo           = z.sq_modulo and
                                                                               z.sigla               = 'AC'
                                                                              )
                               )                        k on (a.sq_solicitacao_item  = k.item_licitacao)
          where (p_chave         is null or (p_chave         is not null and a.sq_solicitacao_item = p_chave))
            and (p_material      is null or (p_material      is not null and a.sq_material         = p_material))
            and (p_solicitacao   is null or (p_solicitacao   is not null and a.sq_siw_solicitacao  = p_solicitacao))
            and (p_cancelado     is null or (p_cancelado     is not null and a.cancelado           = p_cancelado));
   ElsIf p_restricao = 'ARP' Then
      -- Recupera itens de ata de registro de preço
      open p_result for
         select a.sq_solicitacao_item as chave, a.sq_siw_solicitacao, a.quantidade, a.valor_unit_est,
                a.preco_menor, a.preco_maior, a.preco_medio, a.quantidade_autorizada, a.cancelado, a.motivo_cancelamento,
                a.ordem, a.detalhamento det_item,
                b.sq_material, b.sq_tipo_material, b.sq_unidade_medida,
                b.nome, b.descricao, b.detalhamento, b.apresentacao, b.codigo_interno, b.codigo_externo,
                b.exibe_catalogo, b.vida_util, b.ativo,
                b.pesquisa_preco_menor, b.pesquisa_preco_maior, b.pesquisa_preco_medio,
                b.pesquisa_data, b.pesquisa_validade,
                b.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case b.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case b.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                retornaNomeClasse(c.classe) nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                f.percentual_acrescimo,
                g.item_pedido,
                g.item_licitacao,
                h.sq_siw_solicitacao sq_solic_pai, h.quantidade_autorizada as qtd_licitacao,
                h.ordem as ordem_ata, h.quantidade as cmm,
                dados_solic(h.sq_siw_solicitacao) as dados_pai,
                coalesce(i.qtd_cotacao,0) as qtd_cotacao,
                j.qtd_proposta,
                k.codigo_interno as numero_ata, k.fim,
                k2.sq_pessoa as sq_detentor, k2.nome as nm_detentor,
                l.qtd_pedido,
                m.fabricante, m.marca_modelo, m.embalagem, m.valor_unidade, m.fator_embalagem,
                ((1 - (b.pesquisa_preco_medio/m.valor_unidade)) * 100) as variacao_valor
           from cl_solicitacao_item                        a
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
                                     where z.fim >= trunc(sysdate)
                                    group by y.sq_solicitacao_item
                                   )                        i  on (a.sq_solicitacao_item  = i.sq_solicitacao_item)
                  left        join (select y.sq_solicitacao_item, count(z.sq_item_fornecedor) as qtd_proposta
                                      from siw_solicitacao                  x
                                           inner   join cl_solicitacao_item y on (x.sq_siw_solicitacao  = y.sq_siw_solicitacao)
                                             left  join cl_item_fornecedor  z on (y.sq_solicitacao_item = z.sq_solicitacao_item and
                                                                                  'N'                   = z.pesquisa)
                                    group by y.sq_solicitacao_item
                                   )                         j on (a.sq_solicitacao_item  = j.sq_solicitacao_item)
          where (p_chave         is null or (p_chave         is not null and a.sq_solicitacao_item = p_chave))
            and (p_material      is null or (p_material      is not null and a.sq_material         = p_material))
            and (p_solicitacao   is null or (p_solicitacao   is not null and a.sq_siw_solicitacao  = p_solicitacao))
            and (p_cancelado     is null or (p_cancelado     is not null and a.cancelado           = p_cancelado));
   ElsIf p_restricao = 'LCITEM' Then
      -- Recupera materiais e serviços
      open p_result for
         select a.sq_solicitacao_item as chave, a.sq_siw_solicitacao, a.quantidade, a.valor_unit_est,
                a.preco_menor, a.preco_maior, a.preco_medio, a.quantidade_autorizada, a.cancelado, a.motivo_cancelamento,
                a.ordem, a.detalhamento det_item,
                b.sq_material, b.sq_tipo_material, b.sq_unidade_medida,
                b.nome, b.descricao, b.detalhamento, b.apresentacao, b.codigo_interno, b.codigo_externo,
                b.exibe_catalogo, b.vida_util, b.ativo,
                b.pesquisa_preco_menor, b.pesquisa_preco_maior, b.pesquisa_preco_medio,
                b.pesquisa_data, b.pesquisa_validade,
                b.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case b.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case b.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                retornaNomeClasse(c.classe) nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                dados_solic(g.sq_siw_solicitacao) as dados_solic
           from cl_solicitacao_item                a
                inner     join cl_material         b  on (a.sq_material         = b.sq_material)
                inner     join cl_tipo_material    c  on (b.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida   d  on (b.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join cl_parametro        f  on (b.cliente             = f.cliente)
                inner     join siw_solicitacao     g  on (a.sq_siw_solicitacao  = g.sq_siw_solicitacao)
                  inner   join siw_tramite         h  on (g.sq_siw_tramite      = h.sq_siw_tramite and
                                                          'AT'                  = coalesce(h.sigla,'-')
                                                         )
                  inner   join siw_menu            i  on (g.sq_menu             = i.sq_menu and
                                                          i.sigla               = 'CLPCCAD'
                                                         )
          where a.quantidade_autorizada > 0
            and a.sq_solicitacao_item   not in (select x.item_pedido
                                                 from cl_solicitacao_item_vinc             x
                                                        inner     join cl_solicitacao_item y on (x.item_licitacao     = y.sq_solicitacao_item)
                                                          inner   join siw_solicitacao     z on (y.sq_siw_solicitacao = z.sq_siw_solicitacao)
                                                            inner join siw_tramite         w on (z.sq_siw_tramite     = w.sq_siw_tramite and
                                                                                                 w.sigla             <> 'CA'
                                                                                                )
                                               );
   ElsIf p_restricao = 'ARPITEM' Then
      -- Recupera materiais e serviços
      open p_result for
         select a.sq_solicitacao_item as chave, a.sq_siw_solicitacao, a.quantidade, a.valor_unit_est,
                a.preco_menor, a.preco_maior, a.preco_medio, a.quantidade_autorizada, a.cancelado, a.motivo_cancelamento,
                a.ordem, a.detalhamento det_item,
                b.sq_material, b.sq_tipo_material, b.sq_unidade_medida,
                b.nome, b.descricao, b.detalhamento, b.apresentacao, b.codigo_interno, b.codigo_externo,
                b.exibe_catalogo, b.vida_util, b.ativo,
                b.pesquisa_preco_menor, b.pesquisa_preco_maior, b.pesquisa_preco_medio,
                b.pesquisa_data, b.pesquisa_validade,
                b.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case b.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case b.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                retornaNomeClasse(c.classe) nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                dados_solic(g.sq_siw_solicitacao) as dados_solic,
                g.codigo_interno as numero_ata,
                i.fator_embalagem
           from cl_solicitacao_item                a
                inner     join cl_material         b  on (a.sq_material         = b.sq_material)
                inner     join cl_tipo_material    c  on (b.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida   d  on (b.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join cl_parametro        f  on (b.cliente             = f.cliente)
                inner     join siw_solicitacao     g  on (a.sq_siw_solicitacao  = g.sq_siw_solicitacao and
                                                          g.fim                 >= trunc(sysdate)
                                                         )
                  inner   join siw_menu            g1 on (g.sq_menu             = g1.sq_menu and
                                                          'GCZ'                 = substr(g1.sigla,1,3)
                                                         )
                  inner   join siw_tramite         h  on (g.sq_siw_tramite      = h.sq_siw_tramite and
                                                          h.ativo               = 'S'
                                                         )
                inner     join cl_item_fornecedor  i  on (a.sq_solicitacao_item = i.sq_solicitacao_item)
          where a.sq_solicitacao_item not in (select x.item_licitacao from cl_solicitacao_item_vinc x inner join cl_solicitacao_item y on (x.item_pedido = y.sq_solicitacao_item) where y.sq_siw_solicitacao = p_solicitacao)
            --and a.quantidade          > a.quantidade_autorizada
            and a.cancelado           = 'N'
            and (p_tipo_material is null or (p_tipo_material is not null and b.sq_tipo_material = p_tipo_material))
            and (p_codigo        is null or (p_codigo        is not null and b.codigo_interno   like '%'||p_codigo||'%'))
            and (p_nome          is null or (p_nome          is not null and acentos(b.nome)    like '%'||acentos(p_nome)||'%'));
   ElsIf p_restricao = 'FORNECEDORC' or p_restricao = 'FORNECEDORP' Then
      -- Recupera materiais e serviços
      open p_result for
         select a.sq_solicitacao_item as chave, a.sq_siw_solicitacao, a.quantidade, a.valor_unit_est,
                a.preco_menor, a.preco_maior, a.preco_medio, a.quantidade_autorizada, a.cancelado, a.motivo_cancelamento,
                a.ordem, a.detalhamento det_item,
                b.sq_material, b.sq_tipo_material, b.sq_unidade_medida,
                b.nome, b.descricao, b.detalhamento, b.apresentacao, b.codigo_interno, b.codigo_externo,
                b.exibe_catalogo, b.vida_util, b.ativo,
                b.pesquisa_preco_menor, b.pesquisa_preco_maior, b.pesquisa_preco_medio,
                b.pesquisa_data, b.pesquisa_validade,
                b.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case b.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case b.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                retornaNomeClasse(c.classe) nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                g.item_pedido,
                h.sq_siw_solicitacao sq_solic_pai, h.quantidade_autorizada as qtd_pedido,
                dados_solic(h.sq_siw_solicitacao) as dados_pai,
                i.fim fornecedor_validade, i.inicio fornecedor_data, i.valor_unidade fornecedor_valor,
                i.fim-f.dias_aviso_pesquisa as fornecedor_aviso, i.fabricante, i.marca_modelo, i.embalagem,
                i.dias_validade_proposta, i.fator_embalagem, i.origem,
                j.dias_validade_proposta as dias_validade_certame
           from cl_solicitacao_item                     a
                inner     join cl_material              b  on (a.sq_material         = b.sq_material)
                inner     join cl_tipo_material         c  on (b.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida        d  on (b.sq_unidade_medida   = d.sq_unidade_medida)
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
         select a.sq_solicitacao_item as chave, a.sq_siw_solicitacao, a.quantidade, a.valor_unit_est,
                a.preco_menor, a.preco_maior, a.preco_medio, a.quantidade_autorizada, a.cancelado, a.motivo_cancelamento,
                a.ordem, a.detalhamento det_item,
                b.sq_material, b.sq_tipo_material, b.sq_unidade_medida,
                b.nome, b.descricao, b.detalhamento, b.apresentacao, b.codigo_interno, b.codigo_externo,
                b.exibe_catalogo, b.vida_util, b.ativo,
                b.pesquisa_preco_menor, b.pesquisa_preco_maior, b.pesquisa_preco_medio,
                b.pesquisa_data, b.pesquisa_validade,
                b.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case b.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case b.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                retornaNomeClasse(c.classe) nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                g.item_pedido,
                h.sq_siw_solicitacao sq_solic_pai, h.quantidade_autorizada as qtd_pedido,
                dados_solic(h.sq_siw_solicitacao) as dados_pai,
                i.fim fornecedor_validade, i.inicio fornecedor_data, i.valor_unidade fornecedor_valor,
                i.fim-f.dias_aviso_pesquisa as fornecedor_aviso, i.fabricante, i.marca_modelo, i.embalagem,
                i.dias_validade_proposta, i.origem, i.fator_embalagem
           from cl_solicitacao_item                     a
                inner     join cl_material              b  on (a.sq_material         = b.sq_material)
                inner     join cl_tipo_material         c  on (b.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida        d  on (b.sq_unidade_medida   = d.sq_unidade_medida)
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
                  left    join cl_item_fornecedor  d on (b.sq_solicitacao_item = d.sq_solicitacao_item and
                                                         'S'                   = d.pesquisa and
                                                         d.fim                 >= trunc(sysdate)
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
   ElsIf p_restricao = 'PROPOSTA' or p_restricao = 'COTACAO'  or p_restricao = 'VENCEDOR' Then
      -- Recuperas as propostas de um certame
      open p_result for
         select a.sq_solicitacao_item as chave, a.sq_siw_solicitacao, a.quantidade, a.valor_unit_est,
                a.preco_menor, a.preco_maior, a.preco_medio, a.quantidade_autorizada, a.cancelado, a.motivo_cancelamento,
                a.ordem, a.dias_validade_proposta as dias_validade_item, a.detalhamento det_item,
                b.sq_material, b.sq_tipo_material, b.sq_unidade_medida,
                b.nome, b.descricao, b.detalhamento, b.apresentacao, b.codigo_interno, b.codigo_externo,
                b.exibe_catalogo, b.vida_util, b.ativo,
                b.pesquisa_preco_menor, b.pesquisa_preco_maior,
                coalesce(b.pesquisa_preco_medio,0) as pesquisa_preco_medio,
                b.pesquisa_data, b.pesquisa_validade,
                b.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case b.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case b.exibe_catalogo when 'S' then 'Sim' else 'Não' end nm_exibe_catalogo,
                c.nome as nm_tipo_material, c.sigla as sg_tipo_material, c.classe,
                retornaNomeClasse(c.classe) nm_classe,
                montanometipomaterial(c.sq_tipo_material,'PRIMEIRO') as nm_tipo_material_pai,
                montanometipomaterial(c.sq_tipo_material) as nm_tipo_material_completo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                f.percentual_acrescimo,
                g.inicio as proposta_data, g.fim as proposta_validade, g.valor_unidade, g.valor_item,
                g.sq_item_fornecedor, g.fornecedor, g.dias_validade_proposta, g.fator_embalagem,
                g.vencedor,
                case g.origem when 'SA' then 'ARP externa' when 'SG' then 'Governo' when 'SF' then 'Site comercial' else 'Proposta fornecedor' end as nm_origem,
                h.nome_resumido as nm_fornecedor,
                i.qtd_proposta,
                nvl(b.pesquisa_preco_medio,0)*nvl(f.percentual_acrescimo,0)/100 as variacao_valor
           from cl_solicitacao_item                     a
                inner     join cl_material              b  on (a.sq_material         = b.sq_material)
                inner     join cl_tipo_material         c  on (b.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida        d  on (b.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join cl_parametro             f  on (b.cliente             = f.cliente)
                left      join cl_item_fornecedor       g  on (a.sq_solicitacao_item = g.sq_solicitacao_item and
                                                               ((p_restricao = 'COTACAO'  and 'S' = g.pesquisa) or
                                                                (p_restricao = 'PROPOSTA' and 'N' = g.pesquisa) or
                                                                (p_restricao = 'VENCEDOR' and 'N' = g.pesquisa and 'S' = g.vencedor)
                                                               )
                                                              )
                  left    join co_pessoa                h on (g.fornecedor           = h.sq_pessoa)
                left    join (select z.sq_solicitacao_item, count(z.sq_item_fornecedor) as qtd_proposta
                                from cl_item_fornecedor  z
                               where ((p_restricao = 'COTACAO'  and 'S' = z.pesquisa) or
                                      (p_restricao = 'PROPOSTA' and 'N' = z.pesquisa) or
                                      (p_restricao = 'VENCEDOR' and 'N' = z.pesquisa and 'S' = z.vencedor)
                                     )
                              group by z.sq_solicitacao_item
                             )                           i on (a.sq_solicitacao_item  = i.sq_solicitacao_item)
          where (p_solicitacao   is null or (p_solicitacao   is not null and a.sq_siw_solicitacao  = p_solicitacao));
   End If;
end sp_getMTSolicItem;
/
