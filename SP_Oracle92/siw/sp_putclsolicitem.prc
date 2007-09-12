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
   w_qtd      number(18,2) := p_quantidade;
   w_material number(18)   := p_material;
   w_existe   number(18);
   w_reg      cl_solicitacao_item%rowtype;
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
      -- p_chave_aux2 é passado apenas pelo item da licitação
      if p_chave_aux2 is not null then
         -- Verifica se o item da licitação está ligado a mais de um item de pedido de copra
         select count(a.item_pedido) into w_existe
           from cl_solicitacao_item_vinc a
          where a.item_licitacao = p_chave_aux;
         
         -- Remove vínculo entre pedido de compra e item de licitação
         delete cl_solicitacao_item_vinc where item_pedido = p_chave_aux2;

         if w_existe > 1 then
            -- Se o item da licitação estiver vinculado a mais de um item de compra, ajusta a quantidade
            update cl_solicitacao_item a
               set a.quantidade = (select sum(y.quantidade_autorizada)
                                     from cl_solicitacao_item_vinc       x
                                          inner join cl_solicitacao_item y on (x.item_pedido = y.sq_solicitacao_item)
                                    where x.item_licitacao = a.sq_solicitacao_item
                                  )
            where a.sq_solicitacao_item = p_chave_aux;
         end if;

         -- Recupera dados para controle de quantidade e valor da solicitação
         select quantidade, sq_material          into w_qtd, w_material from cl_solicitacao_item where sq_solicitacao_item = p_chave_aux;
         select coalesce(pesquisa_preco_medio,0) into w_valor           from cl_material         where sq_material         = w_material;
  
         -- Atualiza o valor da solicitação
         update siw_solicitacao 
            set valor = (coalesce(valor,0) - (w_valor*w_qtd))
         where sq_siw_solicitacao = p_chave;

         if w_existe <= 1 then
            -- Se o item da licitação estiver vinculado a apenas um item de compra, remove o item da licitação
            delete cl_solicitacao_item where sq_solicitacao_item = p_chave_aux;
         end if;
         
      else
         -- Recupera dados para controle de quantidade e valor da solicitação
         select quantidade, sq_material          into w_qtd, w_material from cl_solicitacao_item where sq_solicitacao_item = p_chave_aux;
         select coalesce(pesquisa_preco_medio,0) into w_valor           from cl_material         where sq_material         = w_material;
    
         -- Atualiza o valor da solicitação
         update siw_solicitacao 
            set valor = (coalesce(valor,0) - (w_valor*w_qtd))
         where sq_siw_solicitacao = p_chave;
    
         -- Tratamento para item de pedido de compra
         delete cl_solicitacao_item where sq_solicitacao_item = p_chave_aux;
      end if;
   Elsif p_operacao = 'V' Then
      -- Recupera os dados do item do pedido
      select * into w_reg
        from cl_solicitacao_item a
       where a.sq_solicitacao_item = p_chave_aux2;
       
      -- Atribui o material à variável local
      w_material := w_reg.sq_material;
      w_qtd      := w_reg.quantidade_autorizada;
      
      -- Verifica se o item do pedido já consta da licitação
      select count(sq_solicitacao_item) into w_existe
        from cl_solicitacao_item a
       where a.sq_siw_solicitacao = p_chave
         and a.sq_material        = w_reg.sq_material;
   
      if w_existe = 0 then
         -- Recupera o próximo valor da sequence
         select sq_solicitacao_item.nextval into w_chave from dual;

         -- Insere registro
         insert into cl_solicitacao_item
           (sq_solicitacao_item, sq_siw_solicitacao, sq_material,       quantidade)
         values
           (w_chave,             p_chave,            w_reg.sq_material, w_reg.quantidade_autorizada);
      Else
         -- Recupera a chave do item na licitação
         select a.sq_solicitacao_item into w_chave
           from cl_solicitacao_item a
          where a.sq_siw_solicitacao = p_chave
            and a.sq_material        = w_reg.sq_material;
          
         -- Acresce a quantidade do item do pedido à quantidade já existente na licitação
         update cl_solicitacao_item 
            set quantidade = quantidade + w_reg.quantidade_autorizada 
         where sq_solicitacao_item = w_chave;
      End If;
      
      -- Vincula o item da licitação com o item do pedido
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
      -- Recupera o preço médio do material
      select coalesce(pesquisa_preco_medio,0) into w_valor from cl_material where sq_material = w_material;

      -- Atualiza o valor da solicitação
      If p_operacao = 'A' or p_operacao = 'C' Then
         -- Primeiro subtrai o valor atual do item se for alteração ou cópia
         update siw_solicitacao set
             valor = (coalesce(valor,0)-(p_qtd_ant*w_valor))
         where sq_siw_solicitacao = p_chave;
      End If;
      
      -- Acresce o valor da solicitação com o valor do item
      update siw_solicitacao set
          valor = (coalesce(valor,0)+(w_qtd*w_valor))
       where sq_siw_solicitacao = p_chave;   
   End if;
end SP_PutCLSolicItem;
/
