create or replace function SP_PutCOTPFONE
   (p_operacao        varchar,
    p_chave           numeric,
    p_sq_tipo_pessoa  numeric,
    p_nome            varchar,
    p_padrao          varchar,
    p_ativo           varchar
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_tipo_telefone (sq_tipo_telefone, sq_tipo_pessoa, nome, padrao,ativo)
         (select nextval('sq_tipo_telefone'),
                 p_sq_tipo_pessoa,
                 trim(p_nome),
                 p_padrao,
                 p_ativo
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_tipo_telefone set
         sq_tipo_pessoa = p_sq_tipo_pessoa, 
         nome           = trim(p_nome),
         padrao         = p_padrao,
         ativo          = p_ativo
      where sq_tipo_telefone = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from co_tipo_telefone where sq_tipo_telefone = p_chave;
   End If;
end; $$ language 'plpgsql' volatile;