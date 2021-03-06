alter procedure dbo.SP_GetStateList
   (@p_pais      int         = null,
    @p_regiao    int         = null,
    @p_ativo     varchar( 1) = null,
    @p_restricao varchar(20) = null
   ) as
begin
   -- ATENÇÃO: Se @p_restricao não for nulo, então é a chave de SIW_CLIENTE
   -- Recupera os estados existentes
      select a.co_uf, a.sq_pais, a.sq_regiao, a.nome, a.ativo, a.padrao,
             coalesce(a.codigo_ibge,'-') as codigo_ibge,
             case a.padrao when 'S' then 'Sim' else 'Não' end as padraodesc,
             case a.ativo when 'S' then 'Sim' else 'Não' end as ativodesc,
             b.nome nome_pais,
             c.nome as nome_regiao,
             dbo.acentos(a.nome) as ordena
        from co_uf                a
             inner join co_pais   b on (a.sq_pais   = b.sq_pais)
             inner join co_regiao c on (a.sq_regiao = c.sq_regiao)
             left join (select x.sq_pais, x.co_uf, count(x.sq_pais) as qtd
                          from eo_indicador_afericao   x
                               inner join eo_indicador y on (x.sq_eoindicador = y.sq_eoindicador and
                                                             y.ativo          = 'S'
                                                            )
                         where y.cliente = coalesce(cast(@p_restricao as int),0) -- @p_restricao como chave de SIW_CLIENTE
                           and x.co_uf   is not null
                        group by x.sq_pais, x.co_uf
                       )          d on (a.sq_pais = d.sq_pais and
                                        a.co_uf   = d.co_uf
                                       )
       where b.sq_pais     = @p_pais
         and (@p_restricao  is null or (@p_restricao is not null and d.sq_pais is not null))
         and (@p_regiao     is null or (@p_regiao    is not null and a.sq_regiao = @p_regiao))
         and (@p_ativo      is null or (@p_ativo     is not null and a.ativo     = @p_ativo));
end
