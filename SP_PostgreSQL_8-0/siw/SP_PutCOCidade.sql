create or replace function SP_PutCOCidade
   (p_operacao                 varchar,
    p_sq_cidade                numeric,
    p_ddd                      varchar,
    p_codigo_ibge              varchar,
    p_sq_pais                  numeric,
    p_sq_regiao                numeric,
    p_co_uf                    varchar,
    p_nome                     varchar,
    p_capital                  varchar
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_cidade (sq_cidade, ddd, codigo_ibge, sq_pais, sq_regiao, co_uf, nome, capital) 
         (select nextval('sq_cidade'), 
                 trim(p_ddd),
                 trim(p_codigo_ibge),
                 p_sq_pais,
                 a.sq_regiao,
                 p_co_uf,
                 trim(upper(p_nome)),
                 p_capital
            from co_uf a 
           where a.co_uf   = p_co_uf
             and a.sq_pais = p_sq_pais
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
     update co_cidade set 
        ddd         = trim(p_ddd),
        codigo_ibge = trim(p_codigo_ibge),
        sq_pais     = p_sq_pais,
        sq_regiao   = (select sq_regiao from co_uf where co_uf = p_co_uf and sq_pais = p_sq_pais),
        co_uf       = p_co_uf,
        nome        = trim(upper(p_nome)),
        capital     = p_capital
     where sq_cidade = p_sq_cidade;
     
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from co_cidade where sq_cidade = p_sq_cidade;
   End If;
end; $$ language 'plpgsql' volatile;