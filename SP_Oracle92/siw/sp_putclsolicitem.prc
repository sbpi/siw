create or replace procedure SP_PutCLSolicItem
   (p_operacao                 in  varchar2,
    p_chave_aux                in  number   default null,
    p_chave                    in  number   default null,
    p_chave_aux2               in  number   default null,
    p_material                 in  number   default null,
    p_quantidade               in  number   default null,
    p_qtd_ant                  in  number   default null,
    p_valor                    in  number   default null,
    p_cancelado                in  varchar2 default null,
    p_motivo_cancelamento      in  varchar2 default null
   ) is
   w_chave    number(18);
   w_valor    number(18,4);
   w_qtd      number(18,2);
   w_material number(18);
begin
   If p_operacao = 'I' Then
      select sq_solicitacao_item.nextval into w_chave from dual;
      -- Insere registro
      insert into cl_solicitacao_item
        (sq_solicitacao_item, sq_siw_solicitacao, sq_material, quantidade,   cancelado,                 motivo_cancelamento)
      values
        (w_chave,             p_chave,            p_material,  p_quantidade, coalesce(p_cancelado,'N'), p_motivo_cancelamento);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update cl_solicitacao_item set 
          quantidade          = p_quantidade,
          cancelado           = coalesce(p_cancelado,'N'),
          motivo_cancelamento = p_motivo_cancelamento
    where sq_solicitacao_item = p_chave_aux;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      select quantidade, sq_material into w_qtd, w_material from cl_solicitacao_item where sq_solicitacao_item = p_chave_aux;
      select pesquisa_preco_medio into w_valor from cl_material where sq_material = w_material;
      update siw_solicitacao set
          valor = (coalesce(valor,0) - (w_valor*w_qtd))
       where sq_siw_solicitacao = p_chave;
      delete cl_solicitacao_item_vinc where item_licitacao = p_chave_aux;
      delete cl_solicitacao_item where sq_solicitacao_item = p_chave_aux;
   Elsif p_operacao = 'V' Then
      select sq_solicitacao_item.nextval into w_chave from dual;
      -- Insere registro
      insert into cl_solicitacao_item
        (sq_solicitacao_item, sq_siw_solicitacao, sq_material, quantidade)
      values
        (w_chave,             p_chave,            p_material,  p_quantidade);
      insert into cl_solicitacao_item_vinc
        (item_licitacao, item_pedido)
      values
        (w_chave, p_chave_aux2);
   Elsif p_operacao = 'C' Then
      -- Registra a quantidade autorizada para compra e a quantidade comprada para a licitação
      update cl_solicitacao_item set 
          quantidade_autorizada = p_quantidade
    where sq_solicitacao_item = p_chave_aux;        
   End If;
   If p_operacao <> 'E' Then
      select pesquisa_preco_medio into w_valor from cl_material where sq_material = p_material;
      If p_operacao = 'A' or p_operacao = 'C' Then
         update siw_solicitacao set
             valor = (coalesce(valor,0)-(p_qtd_ant*w_valor))
         where sq_siw_solicitacao = p_chave;
      End If;
      update siw_solicitacao set
          valor = (coalesce(valor,0)+(p_quantidade*w_valor))
       where sq_siw_solicitacao = p_chave;   
   End if;
end SP_PutCLSolicItem;
/
