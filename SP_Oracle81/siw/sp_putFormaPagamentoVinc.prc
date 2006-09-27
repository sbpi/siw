create or replace procedure SP_PutFormaPagamentoVinc
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_vinculo                  in  integer 
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into siw_menu_forma_pag
        ( sq_forma_pagamento, sq_menu)
      values
        (p_chave, p_vinculo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update siw_menu_forma_pag
         set sq_menu                = p_vinculo
       where sq_forma_pagamento     = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete siw_menu_forma_pag where sq_forma_pagamento  = p_chave;
   End If;
end SP_PutFormaPagamentoVinc;
/
