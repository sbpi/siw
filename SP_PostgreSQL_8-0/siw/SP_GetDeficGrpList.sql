create or replace function SP_GetDeficGrpList
   (p_nome      varchar,
    p_ativo     varchar,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera os grupos de deficiências existentes
   open p_result for 
      select sq_grupo_defic, nome, ativo, 
             case ativo when 'S' then 'Sim' else 'Não' end as ativodesc 
        from co_grupo_defic
       where (p_nome  is null or (p_nome  is not null and acentos(nome,null) like '%'||acentos(p_nome,null)||'%'))
         and (p_ativo is null or (p_ativo is not null and ativo = p_ativo))
      order by nome; 
   return p_result;
end; $$ language 'plpgsql' volatile;
