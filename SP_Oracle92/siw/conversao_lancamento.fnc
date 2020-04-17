create or replace function conversao_lancamento
  (p_cliente       in number, 
   p_data          in date, 
   p_lancamento    in number, 
   p_moeda_destino in number, 
   p_valor         in number, 
   p_taxa          in varchar2
  ) return number is
  /* Converte valores de um lançamento financeiro para outra moeda, 
     usando as cotações cadastradas para o lançamento ou as cotações do Banco Central de compra ou venda, 
     conforme indicado na chamada
     p_cliente       -> chave de SIW_CLIENTE
     p_data          -> data da cotação a ser utilizada na conversão
     p_lancamento    -> chave primária do lançamento a ser convertido
     p_moeda_destino -> moeda para a qual se deseja a conversão
     p_taxa          -> taxa desejada para a conversão se não existir cotação na moeda destino para o lançamento
                        (C - taxa de compra; V -> taxa de venda)
   */
  w_cont             number(10);
  w_moeda_origem     co_moeda%rowtype;
  Result number;
begin
  -- O lançamento deve existir e deve estar ligado a projeto. 
  -- Além disso, a moeda de destino deve existir.
  -- Senão retorna 0.
  select count(*) into w_cont 
    from siw_solicitacao            a
         inner join fn_lancamento   d on a.sq_siw_solicitacao = d.sq_siw_solicitacao
         inner join siw_solicitacao b on d.sq_solic_vinculo   = b.sq_siw_solicitacao,
         co_moeda                   c
   where a.sq_siw_solicitacao = p_lancamento
     and a.sq_moeda           is not null
     and c.sq_moeda           = p_moeda_destino;
  If w_cont = 0 Then 
     return 0; 
  End If;
  
  -- Define a moeda de origem
  select * into w_moeda_origem  from co_moeda where sq_moeda = (select sq_moeda from siw_solicitacao where sq_siw_solicitacao = p_lancamento);
  
  -- Se não precisar de conversão, retorna o valor informado
  If w_moeda_origem.sq_moeda = p_moeda_destino Then 
     return p_valor; 
  End If;

  -- Verifica se a moeda de destino é igual à do projeto
  select count(*) into w_cont
    from siw_solicitacao               k
         inner   join fn_lancamento   k2 on (k.sq_siw_solicitacao = k2.sq_siw_solicitacao)
           inner join siw_solicitacao  l on (k2.sq_solic_vinculo  = l.sq_siw_solicitacao)
   where k.sq_siw_solicitacao = p_lancamento
     and l.sq_moeda           = p_moeda_destino;
  
  If w_cont = 0 Then
     -- Se a moeda de destino não for igual à do projeto, usa a função de conversão
     return conversao(p_cliente, p_data, w_moeda_origem.sq_moeda, p_moeda_destino, p_valor, p_taxa);
  Else 
     -- Verifica se o lançamento tem cotação para a moeda do projeto
     select count(*) into w_cont
       from siw_solicitacao                     k
            inner join fn_lancamento           k2 on (k.sq_siw_solicitacao = k2.sq_siw_solicitacao)
              inner     join siw_solicitacao    l on (k2.sq_solic_vinculo  = l.sq_siw_solicitacao)
              inner     join siw_solic_cotacao  m on (k.sq_siw_solicitacao = m.sq_siw_solicitacao and
                                                      l.sq_moeda           = m.sq_moeda and
                                                      m.valor              > 0
                                                     )
      where k.sq_siw_solicitacao = p_lancamento;
     
     If w_cont = 0 Then
        -- Se o lançamento não tem cotação armazenada para a moeda do projeto, usa a função de conversão
        return conversao(p_cliente, p_data, w_moeda_origem.sq_moeda, p_moeda_destino, p_valor, p_taxa);
     Else
        -- Caso contrário, usa a cotação armazenada para converter o valor
       select p_valor * m.valor/k.valor into Result
              from siw_solicitacao                     k
                   inner join fn_lancamento           k2 on (k.sq_siw_solicitacao = k2.sq_siw_solicitacao)
                     inner     join siw_solicitacao    l on (k2.sq_solic_vinculo  = l.sq_siw_solicitacao)
                       inner   join siw_solic_cotacao  m on (k.sq_siw_solicitacao = m.sq_siw_solicitacao and
                                                             l.sq_moeda           = m.sq_moeda and
                                                             m.valor              > 0
                                                            )
        where k.sq_siw_solicitacao = p_lancamento;
     End If;

  End If;

  return(Result);
end conversao_lancamento;
/
