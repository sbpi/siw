create or replace function SP_PutCOForm
   (p_operacao  varchar,
    p_chave     numeric,
    p_tipo      varchar,
    p_nome      varchar,
    p_ordem     numeric,
    p_ativo     varchar
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_formacao (sq_formacao, tipo, nome, ordem,ativo) 
         (select nextval('sq_formacao'),
                 p_tipo, 
                 trim(p_nome),
                 p_ordem,
                 p_ativo
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_formacao set
         tipo      = p_tipo,      
         nome      = trim(p_nome),
         ordem     = p_ordem,
         ativo     = p_ativo
      where sq_formacao = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from co_formacao where sq_formacao = p_chave;
   End If;
end; $$ language 'plpgsql' volatile;
