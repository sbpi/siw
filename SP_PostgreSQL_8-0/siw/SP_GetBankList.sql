create or replace function SP_GetBankList
   (p_codigo    varchar,
    p_nome      varchar,
    p_ativo     varchar,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera os bancos existentes
   open p_result for 
      select sq_banco, codigo, nome, ativo, codigo||' - '||nome as descricao, padrao 
        from co_banco 
       where (p_nome   is null or (p_nome   is not null and acentos(nome, null) like '%'||acentos(p_nome, null)||'%'))
         and (p_codigo is null or (p_codigo is not null and codigo = p_codigo))
         and (p_ativo  is null or (p_ativo  is not null and ativo  = p_ativo))
      order by padrao desc, codigo;
   return p_result;
end; $$ language 'plpgsql' volatile;
