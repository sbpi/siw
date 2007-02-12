create or replace procedure SP_GetCountryList
   (p_restricao in  varchar2 default null,
    p_nome      in  varchar2 default null,
    p_ativo     in  varchar2 default null,
    p_sigla     in  varchar2 default null,
    p_result    out sys_refcursor) is
begin
   -- Recupera os paises existentes
   open p_result for 
      select a.sq_pais, a.nome, coalesce(a.sigla,'-') as sigla, a.ddi, a.ativo, a.padrao,
             case a.ativo  when 'S' then 'Sim' else 'Não' end as ativodesc, 
             case a.padrao when 'S' then 'Sim' else 'Não' end as padraodesc
        from co_pais              a
             left join (select x.sq_pais, count(x.sq_pais) as qtd
                          from eo_indicador_afericao   x
                               inner join eo_indicador y on (x.sq_eoindicador = y.sq_eoindicador and
                                                             y.ativo          = 'S'
                                                            )
                         where y.cliente = coalesce(to_number(p_nome),0) -- p_nome como chave de SIW_CLIENTE
                           and x.sq_pais is not null
                        group by x.sq_pais
                       )          b on (a.sq_pais = b.sq_pais)
       where (p_restricao is null or (p_restricao = 'ATIVO'      and ativo = 'S')
                                  or (p_restricao = 'NOMEBRASIL' and nome = 'Brasil')
                                  or (p_restricao = 'NOMEFRANCA' and nome = 'França')
                                  or (p_restricao = 'BRASILFRANCA' and (nome = 'Brasil' or nome = 'França'))
                                  or (p_restricao = 'INDICADOR'))
         and ((coalesce(p_restricao,'-')  = 'INDICADOR' and b.sq_pais is not null) or 
              (coalesce(p_restricao,'-') <> 'INDICADOR' and 
               (p_nome  is null or (p_nome is not null and acentos(nome) like '%'||acentos(p_nome)||'%'))
              )
             )
         and (p_ativo is null or (p_ativo is not null and ativo = p_ativo))
         and (p_sigla is null or (p_sigla is not null and sigla = p_sigla));
end SP_GetCountryList;
/
