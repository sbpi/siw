create or replace function SP_PutCOAgencia
   (p_operacao  varchar,
    p_chave     numeric,
    p_sq_banco  numeric,
    p_nome      varchar,
    p_codigo    numeric,
    p_padrao    varchar,
    p_ativo     varchar
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_agencia (sq_agencia, sq_banco, nome, codigo, padrao, ativo)
         (select coalesce(p_Chave,nextval('sq_agencia')),
                 p_sq_banco,
                 trim(upper(p_nome)),
                 trim(p_codigo),
                 p_padrao,
                 p_ativo
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_agencia set
         sq_banco  = p_sq_banco,
         nome      = trim(upper(p_nome)),
         codigo    = trim(p_codigo),
         padrao    = p_padrao,
         ativo     = p_ativo
      where sq_agencia = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from co_agencia where sq_agencia = p_chave;
   End If;
end; $$ language 'plpgsql' volatile;