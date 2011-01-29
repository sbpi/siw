create or replace FUNCTION SP_PutFormaPagamento
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_cliente                   varchar,
    p_nome                      varchar,
    p_sigla                     varchar,
    p_ativo                     varchar
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_forma_pagamento
        (sq_forma_pagamento, cliente, nome, sigla, ativo)
      values
        (nextVal('sq_forma_pagamento'), p_cliente, p_nome, p_sigla, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_forma_pagamento
         set cliente       = p_cliente,
             nome          = p_nome,
             sigla         = p_sigla,
             ativo = p_ativo
       where sq_forma_pagamento = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM co_forma_pagamento where sq_forma_pagamento = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;