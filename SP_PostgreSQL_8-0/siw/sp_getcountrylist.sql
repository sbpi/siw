create or replace FUNCTION SP_GetCountryList
   (p_restricao  varchar,
    p_nome       varchar,
    p_ativo      varchar,
    p_sigla      varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os paises existentes
   open p_result for 
      select a.sq_pais, a.nome, coalesce(a.sigla,'-') as sigla, a.ddi, a.ativo, a.padrao,
             case a.ativo  when 'S' then 'Sim' else 'Não' end as ativodesc, 
             case a.padrao when 'S' then 'Sim' else 'Não' end as padraodesc,
             case a.continente when 1 then 'América'
                               when 2 then 'Europa'
                               when 3 then 'Ásia'
                               when 4 then 'África'
                               else        'Oceania'
             end as nm_continente,
             c.sq_moeda, c.codigo as cd_moeda, c.nome as nm_moeda, c.sigla as sg_moeda, c.simbolo as sb_moeda
        from co_pais              a
             left join (select x.sq_pais, count(x.sq_pais) as qtd
                          from eo_indicador_afericao   x
                               inner join eo_indicador y on (x.sq_eoindicador = y.sq_eoindicador and
                                                             y.ativo          = 'S'
                                                            )
                         where to_char(y.cliente) = coalesce(p_nome,'0') -- p_nome como chave de SIW_CLIENTE
                           and x.sq_pais is not null
                        group by x.sq_pais
                       )          b on (a.sq_pais  = b.sq_pais)
             left join co_moeda   c on (a.sq_moeda = c.sq_moeda)
       where (p_restricao is null or (p_restricao = 'ATIVO'        and a.ativo = 'S')
                                  or (p_restricao = 'NOMEBRASIL'   and a.nome = 'Brasil')
                                  or (p_restricao = 'NOMEFRANCA'   and a.nome = 'França')
                                  or (p_restricao = 'BRASILFRANCA' and (a.nome = 'Brasil' or a.nome = 'França'))
                                  or (p_restricao = 'INDICADOR')
                                  or (p_restricao like 'CONTINENTE%' and a.continente = replace(p_restricao,'CONTINENTE','')))
         and ((coalesce(p_restricao,'-')  = 'INDICADOR' and b.sq_pais is not null) or 
              (coalesce(p_restricao,'-') <> 'INDICADOR' and 
               (p_nome  is null or (p_nome is not null and acentos(a.nome) like '%'||acentos(p_nome)||'%'))
              )
             )
         and (p_ativo is null or (p_ativo is not null and a.ativo = p_ativo))
         and (p_sigla is null or (p_sigla is not null and a.sigla = p_sigla));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;