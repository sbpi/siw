create or replace function SP_PutCORegiao
   (p_operacao                 varchar,
    p_chave                    numeric,
    p_sq_pais                  numeric,
    p_nome                     varchar,
    p_sigla                    varchar,
    p_ordem                    numeric
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_regiao (sq_regiao, sq_pais, nome, sigla, ordem) 
         (select nextval('sq_regiao'),
                 p_sq_pais,
                 trim(p_nome),
                 trim(upper(p_sigla)),
                 p_ordem
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_regiao set
         sq_pais              = p_sq_pais,
         nome                 = trim(p_nome),
         sigla                = trim(upper(p_sigla)),
         ordem                = p_ordem
      where sq_regiao    = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from co_regiao where sq_regiao = p_chave;
   End If;
end; $$ language 'plpgsql' volatile;

