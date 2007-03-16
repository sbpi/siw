create or replace procedure SP_PutACParametro
   (p_cliente                  in  number,
    p_sequencial               in  number,
    p_ano_corrente             in  number,
    p_prefixo                  in  varchar2,
    p_sufixo                   in  varchar2,
    p_numeracao                in  varchar2
   ) is
   
   p_operacao     varchar2(1);
   w_existe       number(18);
   w_sequencial   number(18) := p_sequencial;
   
begin
   -- Verifica se operação de inclusão ou alteração
   select count(*) into w_existe from ac_parametro a where a.cliente = p_cliente;
   If w_existe > 0 Then
      p_operacao := 'A';
   Else
      p_operacao := 'I';
   End If;
   
   -- Grava os parametros do módulo de viagens do cliente
   If p_operacao = 'I' Then
      -- Insere registro
      insert into ac_parametro
         (cliente,   sequencial,   ano_corrente,   prefixo,   sufixo,   numeracao_automatica)
      values
         (p_cliente, p_sequencial, p_ano_corrente, p_prefixo, p_sufixo, p_numeracao);
   Elsif p_operacao = 'A' Then
      -- Verifica o valor atual no banco
      select sequencial into w_sequencial from ac_parametro where cliente = p_cliente;
      If w_sequencial < p_sequencial Then w_sequencial := p_sequencial; End If;
      -- Altera registro
      update ac_parametro
         set sequencial            = w_sequencial,
             ano_corrente          = p_ano_corrente,
             prefixo               = p_prefixo,
             sufixo                = p_sufixo,
             numeracao_automatica  = p_numeracao
       where cliente = p_cliente;
   End If;
end SP_PutACParametro;
/
