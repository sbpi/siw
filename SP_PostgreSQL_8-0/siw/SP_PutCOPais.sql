create or replace function SP_PutCOPais
   (p_operacao                 varchar,
    p_chave                    numeric,
    p_nome                     varchar,
    p_ativo                    varchar,
    p_padrao                   varchar,
    p_ddi                      varchar,
    p_sigla                    varchar
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_pais (sq_pais, nome, ativo, padrao, ddi, sigla)
         (select nextval('sq_pais'),
                 trim(p_nome),
                 p_ativo,
                 p_padrao,                 
                 p_ddi,
                 p_sigla
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_pais set
         nome                 = trim(p_nome),
         ativo                = p_ativo,
         padrao               = p_padrao,
         ddi                  = p_ddi,
         sigla                = trim(p_sigla)
      where sq_pais    = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from co_pais where sq_pais = p_chave;
   End If;
end; $$ language 'plpgsql' volatile;