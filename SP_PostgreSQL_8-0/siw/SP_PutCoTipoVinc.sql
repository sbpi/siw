create or replace function SP_PutCoTipoVinc
   (p_operacao                 varchar,
    p_chave                    numeric,
    p_sq_tipo_pessoa           numeric,
    p_cliente                  numeric,
    p_nome                     varchar,
    p_interno                  varchar,
    p_contratado               varchar,
    p_padrao                   varchar,
    p_ativo                    varchar
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_tipo_vinculo (sq_tipo_vinculo, sq_tipo_pessoa, cliente, nome, interno, contratado, padrao,ativo)
         (select nextval('sq_tipo_vinculo'),
                 p_sq_tipo_pessoa,
                 p_cliente,
                 trim(p_nome),
                 p_interno,
                 p_contratado,
                 p_padrao,
                 p_ativo
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_tipo_vinculo set
         sq_tipo_pessoa = p_sq_tipo_pessoa,
         nome           = trim(p_nome),
         interno        = p_interno,
         contratado     = p_contratado,
         padrao         = p_padrao,
         ativo          = p_ativo
      where sq_tipo_vinculo   = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from co_tipo_vinculo where sq_tipo_vinculo = p_chave;
   End If;
end; $$ language 'plpgsql' volatile;