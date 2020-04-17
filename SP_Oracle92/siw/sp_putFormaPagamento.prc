create or replace procedure SP_PutFormaPagamento
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_cliente                  in  varchar2,
    p_nome                     in  varchar2,
    p_sigla                    in  varchar2,
    p_codigo_externo           in  varchar2,
    p_ativo                    in  varchar2,
    p_chave_nova               out number
   ) is
   
   w_chave co_forma_pagamento.sq_forma_pagamento%type;
   
begin
   If p_operacao = 'I' Then
      -- Recupera a chave
      select sq_forma_pagamento.nextval into w_chave from dual;
      
      -- Insere registro
      insert into co_forma_pagamento (sq_forma_pagamento, cliente, nome, sigla, ativo, codigo_externo) 
      values (w_chave, p_cliente, p_nome, p_sigla, p_ativo, p_codigo_externo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_forma_pagamento
         set cliente        = p_cliente,
             nome           = p_nome,
             sigla          = p_sigla,
             ativo          = p_ativo,
             codigo_externo = p_codigo_externo
       where sq_forma_pagamento = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete siw_menu_forma_pag where sq_forma_pagamento = p_chave;
      delete co_forma_pagamento where sq_forma_pagamento = p_chave;
   End If;

   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;
end SP_PutFormaPagamento;
/
