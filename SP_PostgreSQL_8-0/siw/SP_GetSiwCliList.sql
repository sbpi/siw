create or replace function SP_GetSiwCliList
   (p_pais      numeric,
    p_uf        varchar,
    p_cidade    numeric,
    p_ativo     varchar,
    p_nome      varchar,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera os clienntes do SIW
   open p_result for 
      select b.sq_pessoa, b.nome_resumido, b.nome, b.nome_indice,
             a.ativacao, a.bloqueio, a.desativacao, c.cnpj,
             d.sq_cidade, d.nome as cidade, d.co_uf as uf, d.sq_pais
      from siw_cliente        a 
             left outer join  co_cidade          d on (a.sq_cidade_padrao = d.sq_cidade),
           co_pessoa          b 
             left outer join  co_pessoa_juridica c on (b.sq_pessoa = c.sq_pessoa) 
      where a.sq_pessoa          = b.sq_pessoa 
        and (p_pais    is null or (p_pais   is not null and d.sq_pais   = p_pais))
        and (p_uf      is null or (p_uf     is not null and d.co_uf     = p_uf))
        and (p_cidade  is null or (p_cidade is not null and d.sq_cidade = p_cidade))
        and (p_ativo   is null or ((p_ativo  = 'S' and (a.desativacao is null and a.bloqueio is null))
                               or  (p_ativo  = 'N' and (a.desativacao is not null and a.bloqueio is not null))))
        and (p_nome    is null or (p_nome is not null and acentos(b.nome, null) like '%'||acentos(p_nome, null)||'%'))
      order by b.nome_indice;
   return p_result;
end; $$ language 'plpgsql' volatile;
