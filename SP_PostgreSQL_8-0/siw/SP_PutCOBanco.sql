create or replace function SP_PutCOBanco
   (p_operacao                 varchar,
    p_chave                    numeric,
    p_nome                     varchar,
    p_codigo                   varchar,
    p_padrao                   varchar,
    p_ativo                    varchar
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_banco (sq_banco, nome, codigo, padrao, ativo)
         (select coalesce(p_Chave,nextval('sq_banco')),
                 trim(upper(p_nome)),
                 trim(p_codigo),                 
                 p_padrao,
                 p_ativo
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_banco set
         nome                 = trim(upper(p_nome)),
         codigo               = trim(p_codigo),
         padrao               = p_padrao,
         ativo                = p_ativo
      where sq_banco    = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from co_banco where sq_banco = p_chave;
   End If;
end; $$ language 'plpgsql' volatile;
