create or replace function SP_GetRegionList
   (p_pais      numeric,
    p_tipo      varchar,
    p_nome      varchar,
    p_result    refcursor
   ) returns refcursor as $$
begin
   If p_tipo = 'N' then
      -- Recupera a lista daas regiões existentes
      open p_result for 
         select a.sq_regiao, a.nome, a.ordem, a.sigla, b.nome as nome_pais, b.sq_pais as sq_pais, b.padrao,
                b.padrao, a.sq_regiao as sq_regiao
           from co_regiao a, co_pais b
          where a.sq_pais = b.sq_pais
            and (p_pais is null or (p_pais is not null and b.sq_pais = p_pais))
            and (p_nome is null or (p_nome is not null and acentos(a.nome,null) like '%'||acentos(p_nome,null)||'%')); 
   Else
      -- Recupera as regiões de um determinado pais
      open p_result for 
         select * 
           from co_regiao 
          where sq_pais = p_pais;
   End If;
   return p_result;
end; $$ language 'plpgsql' volatile;