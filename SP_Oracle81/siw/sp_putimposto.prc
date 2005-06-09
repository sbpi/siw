create or replace procedure SP_PutImposto
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_cliente                  in  varchar2,
    p_nome                     in  varchar2,
    p_descricao                in  varchar2,
    p_sigla                    in  varchar2,
    p_esfera                   in  varchar2,
    p_calculo                  in  varchar2,
    p_dia_pagamento            in  varchar2,
    p_ativo                    in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into fn_imposto
        (sq_imposto, cliente, nome, descricao, sigla, esfera, calculo, dia_pagamento, ativo)
      values
        (sq_imposto.nextval, p_cliente, p_nome, p_descricao, p_sigla, p_esfera, p_calculo, p_dia_pagamento, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update fn_imposto
         set cliente       = p_cliente,
             nome          = p_nome,
             descricao     = p_descricao,
             sigla         = p_sigla,
             esfera        = p_esfera,
             calculo       = p_calculo,
             dia_pagamento = p_dia_pagamento,
             ativo = p_ativo
       where sq_imposto = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete fn_imposto where sq_imposto = p_chave;
   End If;
end SP_PutImposto;
/

