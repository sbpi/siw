create or replace function SP_GetIdiomList
   (p_nome      varchar,
    p_ativo     varchar,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera os idiomas          existentes
   open p_result for 
      select sq_idioma, nome, padrao, ativo, 
             case padrao when 'S' then 'Sim' else 'Não' end as padraodesc, 
             case ativo when 'S' then 'Sim' else 'Não' end as ativodesc 
       from co_idioma
       where (p_nome  is null or (p_nome  is not null and acentos(nome,null) like '%'||acentos(p_nome,null)||'%'))
         and (p_ativo is null or (p_ativo is not null and ativo = p_ativo));
   return p_result;
end; $$ language 'plpgsql' volatile;