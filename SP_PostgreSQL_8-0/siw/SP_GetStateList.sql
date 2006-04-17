create or replace function SP_GetStateList
   (p_pais      numeric,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera os estados existentes
   open p_result for 
      select a.co_uf, b.nome as nome_pais, a.sq_pais as sq_pais, c.nome as nome_regiao,
             a.sq_regiao as sq_regiao,
             a.nome, coalesce(a.codigo_ibge,'-') as codigo_ibge,  
             a.ativo as ativo, 
             case a.ativo when 'S' then 'Sim' else 'Não' end as ativodesc,
             a.padrao, 
             case a.padrao when 'S' then 'Sim' else 'Não' end as padraodesc 
        from co_uf a, co_pais b, co_regiao c
       where a.sq_pais     = b.sq_pais  
         and a.sq_regiao   = c.sq_regiao
         and b.sq_pais     = p_pais;
   return p_result;
end; $$ language 'plpgsql' volatile;
