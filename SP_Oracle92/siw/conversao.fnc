create or replace function conversao(p_cliente in number, p_data in date, p_moeda_origem in number, p_moeda_destino in number, p_valor in number, p_taxa in varchar2) return number is
  /* Converte valores de uma moeda para outra, usando as cotações cadastradas para o cliente e a taxa de compra ou venda, conforme indicado na chamada
     p_cliente       -> chave de SIW_CLIENTE
     p_data          -> data da cotação a ser utilizada na conversão
     p_moeda_origem  -> moeda na qual o valor foi informado na chamada
     p_moeda_destino -> moeda na qual se deseja a conversão
     p_taxa          -> taxa desejada para a conversão (C - taxa de compra; V -> taxa de venda)
   */
  w_existe_origem  number(10);
  w_existe_destino number(10);
  w_moeda_origem   co_moeda%rowtype;
  w_moeda_destino  co_moeda%rowtype;
  Result number;
begin
  If p_moeda_origem = p_moeda_destino Then 
     Result := p_valor; 
  Else
     -- Verifica se as moedas envolvidas existem
     select count(*) into w_existe_origem  from co_moeda where sq_moeda = p_moeda_origem;
     select count(*) into w_existe_destino from co_moeda where sq_moeda = p_moeda_destino;

     If w_existe_origem > 0 and w_existe_destino > 0 Then
       -- Recupera dados das moedas envolvidas na conversão
       select * into w_moeda_origem  from co_moeda where sq_moeda = p_moeda_origem;
       select * into w_moeda_destino from co_moeda where sq_moeda = p_moeda_destino;

       -- Verifica se existe cotação para as moedas de origem e de destino na data informada
       If w_moeda_origem.sigla = 'BRL' Then
         -- Se for Real, não precisa ter cotação
         w_existe_origem := 1;
       Else
         select count(*) into w_existe_origem  
           from co_moeda_cotacao 
          where cliente  = p_cliente
            and sq_moeda = p_moeda_origem 
            and data     = (select max(data) from co_moeda_cotacao where cliente = p_cliente and sq_moeda = p_moeda_origem and data <= p_data);
       End If;
       
       If w_moeda_destino.sigla = 'BRL' Then
         -- Se for Real, não precisa ter cotação
         w_existe_destino := 1;
       Else
         select count(*) into w_existe_destino  
           from co_moeda_cotacao 
          where cliente  = p_cliente
            and sq_moeda = p_moeda_destino 
            and data     = (select max(data) from co_moeda_cotacao where cliente = p_cliente and sq_moeda = p_moeda_destino and data <= p_data);
       End If;
       
       If w_existe_origem > 0 and w_existe_destino > 0 Then
         If w_moeda_origem.sigla = 'BRL' or w_moeda_destino.sigla = 'BRL' Then
            -- Trata conversão de moedas envolvendo o Real
            If w_moeda_origem.sigla = 'BRL' Then
               -- Conversão de Real para outra moeda
               select p_valor / (case p_taxa when 'C' then taxa_compra else taxa_venda end)
                 into Result
                 from co_moeda_cotacao 
                where cliente  = p_cliente
                  and sq_moeda = p_moeda_destino 
                  and data     = (select max(data) from co_moeda_cotacao where cliente = p_cliente and sq_moeda = p_moeda_destino and data <= p_data);
            Else
               -- Conversão de outra moeda para Real
               select p_valor * (case p_taxa when 'C' then taxa_compra else taxa_venda end)
                 into Result
                 from co_moeda_cotacao 
                where cliente  = p_cliente
                  and sq_moeda = p_moeda_origem 
                  and data     = (select max(data) from co_moeda_cotacao where cliente = p_cliente and sq_moeda = p_moeda_origem and data <= p_data);
            End If;
         Else
            -- Trata conversão de moedas diferentes do Real
            -- Conversão de outra moeda para Real
            select p_valor * (case p_taxa when 'C' then (ori.taxa_compra/des.taxa_compra) else (ori.taxa_venda/des.taxa_venda) end)
              into Result
              from co_moeda_cotacao ori,
                   co_moeda_cotacao des
             where ori.cliente  = p_cliente
               and ori.sq_moeda = p_moeda_origem 
               and ori.data     = (select max(data) from co_moeda_cotacao where cliente = p_cliente and sq_moeda = p_moeda_origem and data <= p_data)
               and des.cliente  = p_cliente
               and des.sq_moeda = p_moeda_destino
               and des.data     = (select max(data) from co_moeda_cotacao where cliente = p_cliente and sq_moeda = p_moeda_destino and data <= p_data);
         End If;
       Else
         Result := 0;
       End If;
     Else
       Result := 0;
     End If;
  End If;
  return(Result);
end conversao;
/
