create or replace procedure SP_PutImposto
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_cliente                  in  varchar2  default null,
    p_nome                     in  varchar2  default null,
    p_descricao                in  varchar2  default null,
    p_tipo_lancamento          in  number    default null,
    p_tipo_documento           in  number    default null,
    p_sigla                    in  varchar2  default null,
    p_esfera                   in  varchar2  default null,
    p_calculo                  in  varchar2  default null,
    p_dia_pagamento            in  varchar2  default null,
    p_ativo                    in  varchar2  default null,
    p_tipo_benef               in  number    default null,
    p_sq_benef                 in  number    default null,
    p_tipo_vinc                in  number    default null,
    p_sq_cc                    in  number    default null,
    p_sq_solic                 in  number    default null,
    p_sq_forma_pag             in  number    default null,
    p_codigo_externo           in  varchar2  default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into fn_imposto
        (sq_imposto,         cliente,   nome,     descricao,   sq_tipo_lancamento,
         sq_tipo_documento,  sigla,     esfera,   calculo,     dia_pagamento,
         ativo,              tipo_beneficiario,   sq_beneficiario,
         tipo_vinculo,       sq_cc_vinculo,       sq_solic_vinculo,
         sq_forma_pagamento, codigo_externo)
      values
        (sq_imposto.nextval, p_cliente, p_nome,   p_descricao, p_tipo_lancamento,
         p_tipo_documento,   p_sigla,   p_esfera, p_calculo,   p_dia_pagamento,
         p_ativo,            p_tipo_benef,        p_sq_benef,
         p_tipo_vinc,        p_sq_cc,             p_sq_solic,
         p_sq_forma_pag,     p_codigo_externo);
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
             sq_solic_vinculo   = p_sq_solic,
             sq_forma_pagamento = p_sq_forma_pag,
             codigo_externo     = p_codigo_externo
       where sq_imposto = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete fn_imposto where sq_imposto = p_chave;
   End If;
end SP_PutImposto;
/
