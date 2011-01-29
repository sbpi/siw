create or replace FUNCTION SP_PutImposto
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_cliente                   varchar,
    p_nome                      varchar,
    p_descricao                 varchar,
    p_sigla                     varchar,
    p_esfera                    varchar,
    p_calculo                   varchar,
    p_dia_pagamento             varchar,
    p_ativo                     varchar
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into fn_imposto
        (sq_imposto, cliente, nome, descricao, sigla, esfera, calculo, dia_pagamento, ativo)
      values
        (nextVal('sq_imposto'), p_cliente, p_nome, p_descricao, p_sigla, p_esfera, p_calculo, p_dia_pagamento, p_ativo);
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
      DELETE FROM fn_imposto where sq_imposto = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;