CREATE OR REPLACE FUNCTION siw.SP_GetCityList
   (p_pais      numeric,
    p_estado    varchar,
    p_nome      varchar,
    p_restricao varchar)

      RETURNS refcursor AS
$BODY$

DECLARE

    p_result    refcursor;
    w_restricao varchar(15);
begin
   w_restricao := coalesce(p_restricao, '-');

   -- Recupera as cidades existentes
   open p_result for 
      select a.sq_cidade, a.sq_cidade as sq_cidade, b.co_uf, c.nome as sq_pais, a.nome, nvl(a.ddd,'-') as ddd,
             case a.capital when 'S' then 'Sim' else 'Não' end as capital, 
             Nvl(a.codigo_ibge,'-')  as codigo_ibge,
             acentos(a.nome) as ordena
        from siw.co_cidade            a
             inner  join co_uf    b on (a.co_uf     = b.co_uf and 
                                        a.sq_pais   = b.sq_pais
                                       )
             inner  join siw.co_pais  c on (a.sq_pais   = c.sq_pais)
             left   join (select x.sq_cidade, count(x.sq_cidade) as qtd
                           from siw.eo_indicador_afericao   x
                                inner join siw.eo_indicador y on (x.sq_eoindicador = y.sq_eoindicador and
                                                              y.ativo          = 'S'
                                                             )
                          where y.cliente   = coalesce(to_number(p_nome),0) -- p_restricao como chave de SIW_CLIENTE
                            and x.sq_cidade is not null
                         group by x.sq_cidade
                        )         d on (a.sq_cidade = d.sq_cidade)
       where b.co_uf   = p_estado
         and c.sq_pais = p_pais
         and ((w_restricao  = 'INDICADOR'  and d.sq_cidade is not null) or
              (w_restricao  <> 'INDICADOR' and (p_nome     is null      or (p_nome is not null and acentos(a.nome) like '%'||acentos(p_nome)||'%')))
             );
end 
 $BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetCityList
   (p_pais      numeric,
    p_estado    varchar,
    p_nome      varchar,
    p_restricao varchar) OWNER TO siw;
