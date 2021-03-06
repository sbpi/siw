alter procedure dbo.Sp_GetEtapaOrder
   (@p_solic     int,
    @p_chave     int = null,
    @p_chave_pai int = null
   ) as
begin
   -- Recupera o n�mero de ordem das outras op��es irm�s � informada
      select a.sq_projeto_etapa, dbo.montaOrdem(a.sq_projeto_etapa,null) as ordem, a.titulo, a.inicio_previsto, a.fim_previsto, a.orcamento, a.peso,
             case coalesce(a.sq_etapa_pai,0) when coalesce(@p_chave_pai,0) then a.ordem else 0 end as ordena,
             coalesce(b.orcamento, c.valor) as saldo_pai,
             coalesce((select sum(x.orcamento) 
                         from pj_projeto_etapa x 
                        where x.sq_siw_solicitacao = @p_solic 
                          and (@p_chave is null or (@p_chave is not null and x.sq_projeto_etapa <> @p_chave))
                          and coalesce(x.sq_etapa_pai,0) = coalesce(a.sq_etapa_pai,0)
                     ),0) as alocado
        from pj_projeto_etapa           a
             left join pj_projeto_etapa b on (a.sq_etapa_pai       = b.sq_projeto_etapa)
             inner join siw_solicitacao c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
       where a.sq_siw_solicitacao = @p_solic
         and ((coalesce(@p_chave_pai,0) = coalesce(a.sq_etapa_pai,0)) or
               (coalesce(@p_chave_pai,0) = coalesce(a.sq_projeto_etapa,0))
              );
end
