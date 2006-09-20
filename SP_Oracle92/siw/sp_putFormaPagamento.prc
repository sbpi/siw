create or replace procedure SP_PutFormaPagamento
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_cliente                  in  varchar2,
    p_nome                     in  varchar2,
    p_sigla                    in  varchar2,
    p_ativo                    in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_forma_pagamento
        (sq_forma_pagamento, cliente, nome, sigla, ativo)
      values
        (sq_forma_pagamento.nextval, p_cliente, p_nome, p_sigla, p_ativo);
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
      delete co_forma_pagamento where sq_forma_pagamento = p_chave;
   End If;
end SP_PutFormaPagamento;
/
