create or replace function SP_GetCityList
   (p_pais      numeric,
    p_estado    varchar,
    p_nome      varchar,
    p_restricao varchar,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera as cidades existentes
   open p_result for 
      select a.sq_cidade, a.sq_cidade, b.co_uf, c.nome as sq_pais, a.nome, coalesce(a.ddd,'-') as ddd,
             case a.capital when 'S' then 'Sim' else 'Não' end as capital, 
             coalesce(a.codigo_ibge,'-') as codigo_ibge
        from co_cidade            a
             inner   join co_uf   b on (a.co_uf   = b.co_uf and 
                                        a.sq_pais = b.sq_pais
                                       )
               inner join co_pais c on (b.sq_pais = c.sq_pais)
       where b.co_uf   = p_estado
         and c.sq_pais = p_pais
         and (p_nome is null or (p_nome is not null and acentos(a.nome, null) like acentos(p_nome, null)));
  return p_result;
end; $$ language 'plpgsql' volatile;
