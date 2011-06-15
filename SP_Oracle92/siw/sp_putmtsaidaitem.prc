create or replace procedure SP_PutMtSaidaItem
   (p_operacao                 in  varchar2,
    p_saida                    in  number   default null,
    p_item                     in  number   default null,
    p_solicitacao              in  number   default null,
    p_material                 in  number   default null,
    p_fator                    in  number   default null,
    p_solicitada               in  number   default null,
    p_entregue                 in  number   default null,
    p_data_efetivacao          in  date     default null
   ) is
   w_saida       number(18) := p_saida;
begin
   If p_solicitacao is not null Then
      select sq_mtsaida into w_saida from mt_saida where sq_siw_solicitacao = p_solicitacao;
   End If;
   
   If p_operacao = 'I' Then
      -- Insere item
      insert into mt_saida_item
        (sq_saida_item,         sq_mtsaida,          sq_material,    sq_permanente, fator_embalagem, 
         quantidade_pedida,     quantidade_entregue, valor_unitario, data_efetivacao)
      values
        (sq_saida_item.nextval, w_saida,             p_material,     null,          p_fator,
         p_solicitada,          0,                   0,              null);
   Elsif p_operacao = 'E' Then
      -- Remove todos os itens da solicitacao
      delete mt_saida_item 
      where sq_mtsaida = w_saida 
        and (p_item    is null or (p_item is not null and sq_saida_item = p_item));
   Elsif p_operacao = 'R' Then
      -- Remove as quantidades autorizadas
      delete mt_saida_estoque
      where sq_saida_item in (select sq_saida_item from mt_saida_item where sq_mtsaida = w_saida);
      
      -- Atualiza a quantidade autorizada para zero
      update mt_saida_item set quantidade_entregue = 0, valor_unitario = 0 where sq_mtsaida = w_saida;
   Elsif p_operacao = 'V' Then
      -- Registra a quantidade autorizada de cada item do estoque
      insert into mt_saida_estoque (sq_saida_item, sq_estoque_item, quantidade) values (p_saida, p_item, p_entregue);
      
      -- Atualiza a quantidade total autorizada
      update mt_saida_item a
         set quantidade_entregue = (select sum(quantidade) from mt_saida_estoque where sq_saida_item = a.sq_saida_item),
             valor_unitario      = (select sum(y.quantidade * x.valor_unitario)
                                      from mt_estoque_item             w
                                           inner join mt_entrada_item  x on (w.sq_entrada_item = x.sq_entrada_item)
                                           inner join mt_saida_estoque y on (y.sq_saida_item   = p_saida and
                                                                             w.sq_estoque_item = y.sq_estoque_item
                                                                            )
                                     where w.sq_estoque_item = p_item
                                   )
       where sq_saida_item = p_saida;
   Elsif p_operacao = 'C' Then
      -- Registra a data de entrega
      update mt_saida_item set data_efetivacao = p_data_efetivacao where sq_saida_item = p_item;
   End If;
end SP_PutMtSaidaItem;
/
