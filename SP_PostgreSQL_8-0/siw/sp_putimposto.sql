create or replace FUNCTION SP_PutImposto
   (p_operacao                 varchar,
    p_chave                    numeric  default null,
    p_cliente                  varchar  default null,
    p_nome                     varchar  default null,
    p_descricao                varchar  default null,
    p_tipo_lancamento          numeric  default null,
    p_tipo_documento           numeric  default null,
    p_sigla                    varchar  default null,
    p_esfera                   varchar  default null,
    p_calculo                  varchar  default null,
    p_dia_pagamento            varchar  default null,
    p_ativo                    varchar  default null,
    p_tipo_benef               numeric  default null,
    p_sq_benef                 numeric  default null,
    p_tipo_vinc                numeric  default null,
    p_sq_cc                    numeric  default null,
    p_sq_solic                 numeric  default null
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into fn_imposto
        (sq_imposto,         cliente,   nome,     descricao,   sq_tipo_lancamento, 
         sq_tipo_documento,  sigla,     esfera,   calculo,     dia_pagamento, 
         ativo,              tipo_beneficiario,   sq_beneficiario,
         tipo_vinculo,       sq_cc_vinculo,       sq_solic_vinculo)
      values
        (nextVal('sq_imposto'),  p_cliente, p_nome,   p_descricao, p_tipo_lancamento,  
         p_tipo_documento,   p_sigla,   p_esfera, p_calculo,   p_dia_pagamento, 
         p_ativo,            p_tipo_benef,        p_sq_benef,
         p_tipo_vinc,        p_sq_cc,             p_sq_solic);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update fn_imposto
         set cliente            = p_cliente,
             nome               = p_nome,
             descricao          = p_descricao,
             sq_tipo_lancamento = p_tipo_lancamento,
             sq_tipo_documento  = p_tipo_documento,
             sigla              = p_sigla,
             esfera             = p_esfera,
             calculo            = p_calculo,
             dia_pagamento      = p_dia_pagamento,
             ativo              = p_ativo,
             tipo_beneficiario  = p_tipo_benef,
             sq_beneficiario    = p_sq_benef,
             tipo_vinculo       = p_tipo_vinc,
             sq_cc_vinculo      = p_sq_cc,
             sq_solic_vinculo   = p_sq_solic
       where sq_imposto = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM fn_imposto where sq_imposto = p_chave;
   End If;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;