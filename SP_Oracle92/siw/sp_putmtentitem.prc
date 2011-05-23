create or replace procedure SP_PutMTEntItem
   (p_operacao                 in  varchar2,
    p_chave                    in  number   default null,
    p_chave_aux                in  number   default null,
    p_almoxarifado             in  number   default null,
    p_situacao                 in  number   default null,
    p_ordem                    in  number   default null,
    p_material                 in  number   default null,
    p_quantidade               in  number   default null,
    p_valor                    in  number   default null,
    p_fator                    in  varchar2 default null,
    p_validade                 in  date     default null,
    p_fabricacao               in  date     default null,
    p_vida_util                in  number   default null,
    p_lote                     in  varchar2 default null,
    p_marca                    in  varchar2 default null,
    p_modelo                   in  varchar2 default null
   ) is
   w_chave      number(18);
   w_valor      number(20,10);
begin
   If p_operacao in ('I','A') Then
      w_valor := trunc(p_valor / p_quantidade,10);
   End If;
   
   If p_operacao = 'I' Then
      select sq_entrada_item.nextval into w_chave from dual;
      
      -- Insere registro
      insert into mt_entrada_item
        (sq_entrada_item, sq_mtentrada,   sq_material,     sq_almoxarifado, sq_mtsituacao, quantidade,
         valor_total,     valor_unitario, fator_embalagem, validade,        fabricacao,    vida_util,
         lote_numero,     marca,          modelo,          ordem)
      values
        (w_chave,         p_chave,        p_material,      p_almoxarifado,  p_situacao,    p_quantidade,
         p_valor,         w_valor,        p_fator,         p_validade,      p_fabricacao,  p_vida_util,
         p_lote,          p_marca,        p_modelo,        p_ordem
        );
      
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update mt_entrada_item
         set sq_material       = p_material,
             sq_almoxarifado   = p_almoxarifado,
             sq_mtsituacao     = p_situacao,
             ordem             = p_ordem,
             quantidade        = p_quantidade,
             valor_total       = p_valor,
             valor_unitario    = w_valor,
             fator_embalagem   = p_fator,
             validade          = p_validade,
             fabricacao        = p_fabricacao,
             vida_util         = p_vida_util,
             lote_numero       = p_lote,
             marca             = p_marca,
             modelo            = p_modelo
       where sq_entrada_item = p_chave_aux;
   Elsif p_operacao = 'E' Then
      -- Remove o registro
      delete mt_entrada_item where sq_entrada_item = p_chave_aux;
   End if;
end SP_PutMTEntItem;
/
