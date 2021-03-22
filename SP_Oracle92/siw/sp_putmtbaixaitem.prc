create or replace procedure SP_PutMTBaixaItem
   (p_operacao                 in  varchar2,
    p_chave                    in  number   default null,
    p_chave_aux                in  number   default null,
    p_rgp                      in  number   default null
   ) is
begin
   
   If p_operacao = 'I' Then
      -- Insere registro
      insert into mt_saida_item
        (sq_saida_item,         sq_mtsaida,   sq_material,   sq_permanente,   fator_embalagem, quantidade_pedida, quantidade_entregue, valor_unitario, data_efetivacao)
      select
         sq_saida_item.nextval, a.sq_mtsaida, b.sq_material, b.sq_permanente, 1,               1,                 1,                   0,              null
      from mt_saida a,
           mt_permanente b
      where a.sq_siw_solicitacao = p_chave
        and b.numero_rgp         = p_rgp
        and 0 = (select count(*)
                   from siw_solicitacao            a
                        inner   join mt_saida_item b on a.sq_mtsaida    = b.sq_mtsaida
                          inner join mt_permanente c on b.sq_permanente = c.sq_permanente
                  where a.sq_siw_solicitacao = p_chave
                    and c.numero_rgp         = p_rgp
                );
      
   Elsif p_operacao = 'E' Then
      -- Remove o registro
      delete mt_saida_item where sq_saida_item = p_chave_aux;
   End if;
   
end SP_PutMTBaixaItem;
/
