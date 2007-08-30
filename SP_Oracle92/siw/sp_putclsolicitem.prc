create or replace procedure SP_PutCLSolicItem
   (p_operacao                 in  varchar2,
    p_chave_aux                in  number   default null,
    p_chave                    in  number   default null,
    p_chave_aux2               in  number   default null,
    p_material                 in  number   default null,
    p_quantidade               in  number   default null,
    p_valor                    in  number   default null,
    p_cancelado                in  varchar2 default null,
    p_motivo_cancelamento      in  varchar2 default null
   ) is
   w_chave   number(18);
   w_valor   number(18,2);
begin
   If p_operacao = 'I' Then
      select sq_solicitacao_item.nextval into w_chave from dual;
      -- Insere registro
      insert into cl_solicitacao_item
        (sq_solicitacao_item, sq_siw_solicitacao, sq_material, quantidade,   cancelado,   motivo_cancelamento)
      values
        (w_chave,             p_chave,            p_material,  p_quantidade, p_cancelado, p_motivo_cancelamento);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update cl_solicitacao_item set 
          quantidade          = p_quantidade,
          cancelado           = p_cancelado,
          motivo_cancelamento = p_motivo_cancelamento
    where sq_solicitacao_item = p_chave_aux;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete cl_solicitacao_item_vinc where item_licitacao = p_chave_aux;
      delete cl_solicitacao_item where sq_solicitacao_item = p_chave_aux;
   Elsif p_operacao = 'V' Then
      select sq_solicitacao_item.nextval into w_chave from dual;
      -- Insere registro
      insert into cl_solicitacao_item
        (sq_solicitacao_item, sq_siw_solicitacao, sq_material, quantidade,   quantidade_autorizada, cancelado,   motivo_cancelamento)
      values
        (w_chave,             p_chave,            p_material,  p_quantidade, p_quantidade,          p_cancelado, p_motivo_cancelamento);
      insert into cl_solicitacao_item_vinc
        (item_licitacao, item_pedido)
      values
        (w_chave, p_chave_aux2);
   Elsif p_operacao = 'C' Then
      -- Registra a quantidade autorizada para compra
      update cl_solicitacao_item set 
          quantidade_autorizada = p_quantidade
    where sq_solicitacao_item = p_chave_aux;        
   End If;
/*   If p_operacao = 'I' or p_operacao = 'A' Then
      select pesquisa_preco_menor into w_valor from cl_material where sq_material = p_material;
      update siw_solicitacao set
          valor = valor + (p_quantidade*w_valor)
       where sq_siw_solicitacao = p_chave;   
   End if;*/
end SP_PutCLSolicItem;
/
