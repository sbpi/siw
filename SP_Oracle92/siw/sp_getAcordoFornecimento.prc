create or replace procedure SP_GetAcordoFornecimento
   (p_cliente           in number   default null,
    p_chave             in number   default null,
    p_chave_aux         in number   default null,
    p_material          in number   default null,
    p_numero            in varchar2 default null,
    p_dt_ini            in date     default null,
    p_dt_fim            in date     default null,
    p_restricao         in varchar2 default null,
    p_result            out sys_refcursor) is
begin
   -- Recupera as autorizações de fornecimento
   If p_restricao is null Then
      open p_result for     
         select a.sq_autorizacao_fornecimento, a.sq_siw_solicitacao, a.numero, a.local_entrega, a.agendamento, a.mail, a.nota_empenho, a.valor_empenho, 
                a.data_prevista, a.autorizador_nome, a.autorizador_funcao, a.solicitante, a.responsavel_nome, a.responsavel_funcao, a.responsavel_rg, 
                a.responsavel_data, a.ordem_fornecimento, a.situacao,
                case a.situacao when 'N' then 'Normal' when 'C' then 'Cancelada' else 'Disponível para pagamento' end as nm_situacao,
                g.sq_cc cc_acordo,
                coalesce(h.qtd,0) as qtd_itens,
                coalesce(h.valor,0) as vl_itens
           from cl_autorizacao_fornecimento        a  
                inner     join cl_solicitacao      b on (a.sq_siw_solicitacao    = b.sq_siw_solicitacao)
                  inner   join siw_solicitacao     g on (b.sq_siw_solicitacao    = g.sq_siw_solicitacao)
                    inner join siw_menu            c on (g.sq_menu               = c.sq_menu)
                left      join (select x.sq_autorizacao_fornecimento, 
                                       count(x.sq_item_autorizacao) as qtd,
                                       sum(x.valor_unitario) as valor
                                   from cl_item_autorizacao x
                                 group by x.sq_autorizacao_fornecimento
                                )                  h on (a.sq_autorizacao_fornecimento     = h.sq_autorizacao_fornecimento)
          where c.sq_pessoa = p_cliente
            and ((p_chave             is null) or (p_chave             is not null and a.sq_autorizacao_fornecimento = p_chave))
            and ((p_chave_aux         is null) or (p_chave_aux         is not null and a.sq_siw_solicitacao          = p_chave_aux))
            and ((p_numero            is null) or (p_numero            is not null and a.numero                      = p_numero))
            and ((p_dt_ini            is null) or (p_dt_ini            is not null and a.data_prevista               between p_dt_ini and p_dt_fim));
   Elsif p_restricao = 'ITEM' Then
      open p_result for
         select a.sq_solicitacao_item as chave, a.sq_siw_solicitacao, a.quantidade, a.valor_unit_est,
                a.preco_menor, a.preco_maior, a.preco_medio, a.quantidade_autorizada, a.cancelado, a.motivo_cancelamento,
                a.ordem,
                b.sq_material, b.sq_tipo_material, b.sq_unidade_medida, 
                b.nome, b.descricao, b.detalhamento, b.apresentacao, b.codigo_interno, b.codigo_externo, 
                b.exibe_catalogo, b.vida_util, b.ativo, 
                b.pesquisa_preco_menor, b.pesquisa_preco_maior, b.pesquisa_preco_medio,
                b.pesquisa_data, b.pesquisa_validade, 
                b.pesquisa_validade-f.dias_aviso_pesquisa as pesquisa_aviso,
                case b.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                g.codigo_interno as numero_ata,
                i1.fator_embalagem, i1.valor_unidade,
                k.sq_item_autorizacao, k.quantidade as qtd_of
           from cl_solicitacao_item                     a
                inner     join cl_material              b  on (a.sq_material         = b.sq_material)
                inner     join cl_tipo_material         c  on (b.sq_tipo_material    = c.sq_tipo_material)
                inner     join co_unidade_medida        d  on (b.sq_unidade_medida   = d.sq_unidade_medida)
                inner     join cl_parametro             f  on (b.cliente             = f.cliente)
                inner     join siw_solicitacao          g  on (a.sq_siw_solicitacao  = g.sq_siw_solicitacao)
                  inner   join siw_menu                 g1 on (g.sq_menu             = g1.sq_menu)
                  inner   join siw_tramite              h  on (g.sq_siw_tramite      = h.sq_siw_tramite)
                inner     join cl_solicitacao_item_vinc i  on (a.sq_solicitacao_item = i.item_pedido)
                inner     join cl_item_fornecedor       i1 on (i.item_licitacao      = i1.sq_solicitacao_item)
                left      join (select x.sq_siw_solicitacao, x.sq_autorizacao_fornecimento,
                                       y.sq_item_autorizacao, y.sq_solicitacao_item, y.quantidade
                                  from cl_autorizacao_fornecimento x
                                       inner join cl_item_autorizacao y on (x.sq_autorizacao_fornecimento = y.sq_autorizacao_fornecimento)
                                 where x.sq_autorizacao_fornecimento = coalesce(p_chave,0)
                                )                       k on (a.sq_siw_solicitacao   = k.sq_siw_solicitacao and
                                                              a.sq_solicitacao_item  = k.sq_solicitacao_item
                                                             )
          where a.sq_siw_solicitacao = p_chave_aux;
   End If;
end SP_GetAcordoFornecimento;
/
