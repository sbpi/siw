create or replace function SP_GetCountryList
   (p_restricao varchar,
    p_nome      varchar,
    p_ativo     varchar,
    p_sigla     varchar,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera os paises existentes
   open p_result for 
      select sq_pais, nome, coalesce(sigla,'-') as sigla, ddi,
             ativo, 
             case ativo when 'S' then 'Sim' else 'Não' end as ativodesc, 
             padrao, 
             case padrao when 'S' then 'Sim' else 'Não' end as padraodesc
        from co_pais
       where (p_restricao is null or (p_restricao = 'ATIVO'      and ativo = 'S')
                                  or (p_restricao = 'NOMEBRASIL' and nome = 'Brasil')
                                  or (p_restricao = 'NOMEFRANCA' and nome = 'França')
                                  or (p_restricao = 'BRASILFRANCA' and (nome = 'Brasil' or nome = 'França')))
         and (p_nome  is null     or (p_nome  is not null and (acentos(nome, null) like '%'||acentos(p_nome, null)||'%')))
         and (p_ativo is null     or (p_ativo is not null and ativo = p_ativo))
         and (p_sigla is null     or (p_sigla is not null and sigla = p_sigla));
   return p_result;
end; $$ language 'plpgsql' volatile;
