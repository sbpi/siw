create or replace procedure SP_PutCLARPItem
   (p_operacao                 in  varchar2,
    p_cliente                  in  number,
    p_solic                    in  number,
    p_item                     in  number   default null,
    p_ordem                    in  varchar2 default null,
    p_codigo                   in  varchar2 default null,
    p_fabricante               in  varchar2 default null,
    p_marca_modelo             in  varchar2 default null,
    p_embalagem                in  varchar2 default null,
    p_quantidade               in  number   default null,
    p_valor                    in  number   default null,
    p_cancelado                in  varchar2 default null,
    p_motivo                   in  varchar2 default null
   ) is
   w_item_solic   number(18)  := p_item;
   w_valor        number(18,4);
   w_material     number(18);
   w_valor        float;
   w_menu         siw_menu%rowtype;
   w_acordo       ac_acordo%rowtype;
begin
   -- recupera os dados do serviço
   select b.* into w_menu
     from siw_solicitacao      a
          inner join siw_menu  b on (a.sq_menu = b.sq_menu)
     where a.sq_siw_solicitacao = p_solic;
     
   If p_operacao in ('I','A') Then
      -- recupera a chave do material
      select sq_material into w_material from cl_material where cliente = p_cliente and codigo_interno = p_codigo;

      -- recupera os dados do serviço
      select a.*
        into w_acordo
        from ac_acordo a
       where a.sq_siw_solicitacao = p_solic;
       
   End If;
   
   If p_operacao = 'I' Then
      select sq_solicitacao_item.nextval into w_item_solic from dual;
      -- Insere registro em 
      insert into cl_solicitacao_item
        (sq_solicitacao_item, sq_siw_solicitacao, ordem,       sq_material,           quantidade,   cancelado,   motivo_cancelamento,
         valor_unit_est,      preco_menor,        preco_maior, preco_medio,           quantidade_autorizada,     dias_validade_proposta
        )
      values (
         w_item_solic,        p_solic,            p_ordem,     w_material,            p_quantidade, p_cancelado, p_motivo,
         p_valor,             p_valor,            p_valor,     p_valor,               p_quantidade,              (w_acordo.fim - w_acordo.inicio)
      );
      
      -- Insere registro na tabela CL_ITEM_FORNECEDOR
      insert into cl_item_fornecedor
        (sq_item_fornecedor,         sq_solicitacao_item,    sq_material, fornecedor,           inicio,          fim,
         valor_unidade,              valor_item,             ordem,       vencedor,             pesquisa,        fabricante, 
         marca_modelo,               embalagem,              dias_validade_proposta,            origem)
      values
        (sq_item_fornecedor.nextval, w_item_solic,           w_material,  w_acordo.outra_parte, w_acordo.inicio, w_acordo.fim,
         p_valor,                    (p_valor*p_quantidade), p_ordem,     'S',                  'N',             p_fabricante, 
         p_marca_modelo,             p_embalagem,            (w_acordo.fim - w_acordo.inicio),  'PF'
        );

   Elsif p_operacao = 'A' Then
      -- Altera registro
      update cl_solicitacao_item 
         set ordem                  = p_ordem,
             sq_material            = w_material,
             quantidade             = p_quantidade,
             cancelado              = p_cancelado,
             motivo_cancelamento    = p_motivo,
             valor_unit_est         = p_valor,
             preco_menor            = p_valor,
             preco_maior            = p_valor,
             preco_medio            = p_valor,
             quantidade_autorizada  = p_quantidade,
             dias_validade_proposta = (w_acordo.fim - w_acordo.inicio)
      where sq_solicitacao_item = w_item_solic;
      
      -- Insere registro na tabela CL_ITEM_FORNECEDOR
      update cl_item_fornecedor 
         set ordem                  = p_ordem,
             sq_material            = w_material,
             fornecedor             = w_acordo.outra_parte,
             inicio                 = w_acordo.inicio,
             fim                    = w_acordo.fim,
             valor_unidade          = p_valor,
             valor_item             = (p_valor*p_quantidade),
             fabricante             = p_fabricante,
             marca_modelo           = p_marca_modelo,
             embalagem              = p_embalagem,
             dias_validade_proposta = (w_acordo.fim - w_acordo.inicio)
      where sq_solicitacao_item = w_item_solic;
      
   Elsif p_operacao = 'E' Then
      delete cl_item_fornecedor  where sq_solicitacao_item = w_item_solic;
      delete cl_solicitacao_item where sq_solicitacao_item = w_item_solic;
   End If;
   
   -- Atualiza o valor da solicitação
   update siw_solicitacao 
      set valor = coalesce((select sum(valor_unit_est*quantidade) from cl_solicitacao_item where sq_siw_solicitacao = p_solic),0)
   where sq_siw_solicitacao = p_solic;
end SP_PutCLARPItem;
/
