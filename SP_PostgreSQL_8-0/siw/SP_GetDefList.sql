create or replace function SP_GetDefList
   (p_nome      varchar,
    p_ativo     varchar,
    p_result    refcursor
  ) returns refcursor as $$
begin
   -- Recupera as deficiências existentes
   open p_result for 
      select a.sq_deficiencia, a.nome, a.ativo, 
             case a.ativo when 'S' then 'Sim' else 'Não' end as ativodesc,
             a.codigo, coalesce(a.descricao,'-') as descricao, b.nome as sq_grupo_defic
        from co_deficiencia a, co_grupo_defic b  
      where a.sq_grupo_defic = b.sq_grupo_defic
        and (p_nome  is null or (p_nome  is not null and acentos(a.nome,null) like '%'||acentos(p_nome,null)||'%'))
        and (p_ativo is null or (p_ativo is not null and a.ativo = p_ativo));
   return p_result;
end; $$ language 'plpgsql' volatile;