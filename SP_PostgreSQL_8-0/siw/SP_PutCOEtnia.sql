create or replace function SP_PutCOEtnia
   (p_operacao        varchar,
    p_chave           numeric,
    p_nome            varchar,
    p_codigo_siape    numeric,
    p_ativo           varchar
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_etnia (sq_etnia, nome, codigo_siape,ativo)
         (select nextval('sq_etnia'),
                 trim(p_nome),
                 p_codigo_siape,
                 p_ativo
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_etnia set
         nome            = trim(p_nome),
         codigo_siape    = p_codigo_siape,
         ativo           = p_ativo
      where sq_etnia = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from co_etnia where sq_etnia = p_chave;
   End If;
end; $$ language 'plpgsql' volatile;