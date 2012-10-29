create or replace procedure SP_PutCoMoedaCotacao
   (p_operacao                 in  varchar2,
    p_chave                    in  number   default null,
    p_cliente                  in  number   default null,
    p_moeda                    in  number   default null,
    p_data                     in  date     default null,
    p_taxa_compra              in  number   default null,
    p_taxa_venda               in  number   default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_moeda_cotacao (sq_moeda_cotacao, cliente, sq_moeda, data, taxa_compra, taxa_venda)
      (select sq_moeda_cotacao.nextval, p_cliente, p_moeda, p_data, p_taxa_compra, p_taxa_venda
         from dual
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_moeda_cotacao
         set taxa_compra = p_taxa_compra,
             taxa_venda  = p_taxa_venda
       where sq_moeda_cotacao = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete co_moeda_cotacao where sq_moeda_cotacao = p_chave;
   End If;
end SP_PutCoMoedaCotacao;
/
