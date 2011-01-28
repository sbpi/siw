create or replace FUNCTION SP_PutFormaPagamentoVinc
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_vinculo                   integer 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
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
      DELETE FROM siw_menu_forma_pag where sq_forma_pagamento  = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;