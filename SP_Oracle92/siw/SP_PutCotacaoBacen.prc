create or replace procedure SP_PutCotacaoBacen
   (p_cliente                  in  number,
    p_moeda                    in  number,
    p_data                     in  date,
    p_tipo                     in  varchar2,
    p_valor                    in  number
   ) is
   
   w_cont  number(10);
   w_valor co_moeda_cotacao.taxa_compra%type := p_valor;
begin
   If p_valor = 0 Then
      -- Se recebeu valor 0, recupera valor da primeira cotação anterior à data informada.
      select count(*) into w_cont
        from co_moeda_cotacao
       where sq_moeda = p_moeda
         and data     < p_data
         and ((p_tipo  = 'C' and taxa_compra > 0) or
              (p_tipo  = 'V' and taxa_venda  > 0)
             );
     
      If w_cont > 0 Then
        select valor into w_valor
          from (select data, case p_tipo when 'C' then taxa_compra else taxa_venda end valor
                   from co_moeda_cotacao
                  where sq_moeda = p_moeda
                    and data     < p_data
                 order by data desc
               )
         where rownum = 1;
      End If;
   End If;

   -- Verifica se o valor já existe;
   select count(*) into w_cont 
     from co_moeda_cotacao 
    where sq_moeda = p_moeda 
      and data     = p_data;

   If w_cont = 0 Then
      -- Insere registro
      insert into co_moeda_cotacao (sq_moeda_cotacao, cliente,  sq_moeda, data,   taxa_compra, taxa_venda)
      (select sq_moeda_cotacao.nextval,               p_cliente, p_moeda, p_data, 
              case p_tipo when 'C' then w_valor else 0 end,
              case p_tipo when 'V' then w_valor else 0 end
         from dual
      );
   Else 
      -- Altera registro
      update co_moeda_cotacao
         set taxa_compra = case p_tipo when 'C' then w_valor else taxa_compra end,
             taxa_venda  = case p_tipo when 'V' then w_valor else taxa_venda end
      where sq_moeda = p_moeda 
        and data     = p_data;
   End If;
end SP_PutCotacaoBacen;
/
