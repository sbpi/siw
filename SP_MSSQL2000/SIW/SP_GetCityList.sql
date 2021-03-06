alter procedure dbo.SP_GetCityList
   (@p_pais      int,
    @p_estado    varchar( 2),
    @p_nome      varchar(60) = null,
    @p_restricao varchar(30) = null
   ) as
begin
   Declare @w_restricao varchar(15);

   Set @w_restricao = coalesce(@p_restricao, '-');

   -- Recupera as cidades existentes
      select a.sq_cidade, a.sq_cidade, b.co_uf, c.nome as sq_pais, a.nome, coalesce(a.ddd,'-') as ddd,
             case a.capital when 'S' then 'Sim' else 'Não' end as capital, 
             coalesce(a.codigo_ibge,'-') as codigo_ibge,
             dbo.acentos(a.nome) as ordena
        from co_cidade            a
             inner  join co_uf    b on (a.co_uf     = b.co_uf and 
                                        a.sq_pais   = b.sq_pais
                                       )
             inner  join co_pais  c on (a.sq_pais   = c.sq_pais)
             left   join (select x.sq_cidade, count(x.sq_cidade) as qtd
                           from eo_indicador_afericao   x
                                inner join eo_indicador y on (x.sq_eoindicador = y.sq_eoindicador and
                                                              y.ativo          = 'S'
                                                             )
                          where y.cliente   = coalesce(cast(@p_nome as int),0) -- @p_restricao como chave de SIW_CLIENTE
                            and x.sq_cidade is not null
                         group by x.sq_cidade
                        )         d on (a.sq_cidade = d.sq_cidade)
       where b.co_uf   = @p_estado
         and c.sq_pais = @p_pais
         and ((@w_restricao  = 'INDICADOR'  and d.sq_cidade is not null) or
              (@w_restricao  <> 'INDICADOR' and (@p_nome     is null      or (@p_nome is not null and dbo.acentos(a.nome) like '%'+dbo.acentos(@p_nome)+'%')))
             );
end
