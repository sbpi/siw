create or replace function SP_PutCOUF
   (p_operacao                 varchar,
    p_co_uf                    varchar,
    p_sq_pais                  numeric,
    p_sq_regiao                numeric,
    p_nome                     varchar,
    p_ativo                    varchar,
    p_padrao                   varchar,
    p_codigo_ibge              varchar,
    p_ordem                    numeric
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into CO_UF (co_uf, sq_pais, sq_regiao, nome, ativo, padrao, codigo_ibge, ordem) 
      values (
                 trim(upper(p_co_uf)),
                 p_sq_pais,
                 p_sq_regiao,
                 trim(p_nome),
                 p_ativo,
                 p_padrao,                
                 trim(p_codigo_ibge),
                 p_ordem
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update CO_UF set
        nome        = trim(p_nome),
        ativo       = p_ativo,
        padrao      = p_padrao,
        sq_regiao   = p_sq_regiao,
        codigo_ibge = trim(p_codigo_ibge),
        ordem       = p_ordem
      where sq_pais = p_sq_pais
        and co_uf   = p_co_uf;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from co_uf where sq_pais = p_sq_pais and co_uf = p_co_uf;
   End If;
end; $$ language 'plpgsql' volatile;