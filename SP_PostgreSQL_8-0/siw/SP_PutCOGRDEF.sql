create or replace function SP_PutCOGRDEF
   (p_operacao  varchar,
    p_chave     numeric,
    p_nome      varchar,
    p_ativo     varchar
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_grupo_defic (sq_grupo_defic, nome, ativo)  
         (select nextval('sq_grupo_deficiencia'),
                 trim(p_nome),
                 p_ativo
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_grupo_defic set 
         nome      = trim(p_nome),
         ativo     = p_ativo
      where sq_grupo_defic = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from co_grupo_defic where sq_grupo_defic = p_chave;
   End If;
end; $$ language 'plpgsql' volatile;

