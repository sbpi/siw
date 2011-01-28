create or replace FUNCTION SP_PutACParametro
   (p_cliente                   numeric,
    p_sequencial                numeric,
    p_ano_corrente              numeric,
    p_prefixo                   varchar,
    p_sufixo                    varchar,
    p_numeracao                 varchar,
    p_pagamento                 numeric,
    p_condicao                 varchar
   ) RETURNS VOID AS $$
DECLARE
   
   p_operacao     varchar(1);
   w_existe       numeric(18);
   w_sequencial   numeric(18) := p_sequencial;
   
BEGIN
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
         (cliente,   sequencial,   ano_corrente,   prefixo,   sufixo,   numeracao_automatica, dias_pagamento, texto_pagamento)
      values
         (p_cliente, p_sequencial, p_ano_corrente, p_prefixo, p_sufixo, p_numeracao,          p_pagamento,    p_condicao);
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
             numeracao_automatica  = p_numeracao,
             dias_pagamento        = p_pagamento,
             texto_pagamento       = p_condicao
       where cliente = p_cliente;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;