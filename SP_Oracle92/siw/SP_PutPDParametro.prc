create or replace procedure SP_PutPDParametro
   (p_cliente                  in  number,
    p_sequencial               in  number,
    p_ano_corrente             in  number,
    p_prefixo                  in  varchar2,
    p_sufixo                   in  varchar2,
    p_dias_antecedencia        in  number,
    p_dias_prest_contas        in  number,
    p_limite_unidade           in  varchar2
   ) is
   
   p_operacao varchar2(1);
   w_existe   number(18);
   
begin
   -- Verifica se operação de inclusão ou alteração
   select count(*) into w_existe from pd_parametro a where a.cliente = p_cliente;
   If w_existe > 0 Then
      p_operacao := 'A';
   Else
      p_operacao := 'I';
   End If;
   
   -- Grava os parametros do módulo de viagens do cliente
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pd_parametro
         (cliente,   sequencial,   ano_corrente,   prefixo,   sufixo,   dias_antecedencia, 
          dias_prestacao_contas,   limite_unidade)
      values
         (p_cliente, p_sequencial, p_ano_corrente, p_prefixo, p_sufixo, p_dias_antecedencia, 
          p_dias_prest_contas,     p_limite_unidade);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pd_parametro
         set sequencial            = p_sequencial,
             ano_corrente          = p_ano_corrente,
             prefixo               = p_prefixo,
             sufixo                = p_sufixo,
             dias_antecedencia     = p_dias_antecedencia,
             dias_prestacao_contas = p_dias_prest_contas,
             limite_unidade        = p_limite_unidade
       where cliente = p_cliente;
   End If;
end SP_PutPDParametro;
/
