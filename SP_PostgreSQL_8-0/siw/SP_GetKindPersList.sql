create or replace function SP_GetKindPersList
   (p_nome      varchar,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera os tipos de pessoas
   open p_result for
      select sq_tipo_pessoa, nome 
        from co_tipo_pessoa 
       where (p_nome is null or (p_nome is not null and acentos(nome,null) = acentos(p_nome,null)))
        order by nome;
   return p_result;
end; $$ language 'plpgsql' volatile;