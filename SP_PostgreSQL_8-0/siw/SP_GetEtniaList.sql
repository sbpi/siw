create or replace function SP_GetEtniaList
   (p_nome      varchar,
    p_ativo     varchar,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera as etnias existentes
   open p_result for 
      select codigo_siape, sq_etnia, nome, ativo, 
             case ativo when 'S' then 'Sim' else 'Não' end as descativo 
        from co_etnia
       where (p_nome  is null or (p_nome  is not null and acentos(nome,null) like '%'||acentos(p_nome,null)||'%'))
         and (p_ativo is null or (p_ativo is not null and ativo = p_ativo));
   return p_result;
end; $$ language 'plpgsql' volatile;