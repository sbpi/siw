create or replace function SP_PutCOTPENDER
   (p_operacao                 varchar,
    p_chave                    numeric,
    p_sq_tipo_pessoa           numeric,
    p_nome                     varchar,
    p_padrao                   varchar,
    p_ativo                    varchar,
    p_email                    varchar,
    p_internet                 varchar
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_tipo_endereco (sq_tipo_endereco, sq_tipo_pessoa, nome, padrao, ativo, email, internet) 
         (select nextval('sq_tipo_endereco'),
                 p_sq_tipo_pessoa,
                 trim(p_nome),
                 p_padrao,
                 p_ativo,                 
                 p_email,
                 p_internet
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_tipo_endereco set
         sq_tipo_pessoa       = p_sq_tipo_pessoa,
         nome                 = trim(p_nome),
         padrao               = p_padrao,
         ativo                = p_ativo,      
         email                = p_email,
         internet             = p_internet
      where sq_tipo_endereco  = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from co_tipo_endereco where sq_tipo_endereco = p_chave;
   End If;
end; $$ language 'plpgsql' volatile;