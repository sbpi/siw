create or replace function SP_PutCOIdioma
   (p_operacao  varchar,
    p_chave     numeric,
    p_nome      varchar,
    p_padrao    varchar,
    p_ativo     varchar
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_idioma (sq_idioma, nome, padrao,ativo) 
         (select nextval('sq_idioma'), 
                 trim(p_nome),
                 p_padrao,
                 p_ativo
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_idioma set 
         nome      = trim(p_nome),
         padrao    = p_padrao,
         ativo     = p_ativo
      where sq_idioma = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from co_idioma where sq_idioma = p_chave;
   End If;
end; $$ language 'plpgsql' volatile;