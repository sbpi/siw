alter procedure dbo.sp_getUnidade_PE
   (@p_cliente        int,
    @p_chave          int = null,
    @p_ativo          varchar(1) = null,
    @p_restricao      varchar(15) = null
   ) as
begin
   If @p_restricao is null begin
      -- Recupera as unidades de planejamento
         select a.sq_unidade chave, a.descricao, a.ativo, a.planejamento, a.execucao, a.gestao_recursos,
                case a.ativo           when 'S' then 'Sim' else 'N�o' end as nm_ativo,
                case a.planejamento    when 'S' then 'Sim' else 'N�o' end as nm_planejamento,
                case a.execucao        when 'S' then 'Sim' else 'N�o' end as nm_execucao,
                case a.gestao_recursos when 'S' then 'Sim' else 'N�o' end as nm_recursos,
                b.nome, b.sigla
           from pe_unidade                      a
                inner   join eo_unidade         b on (a.sq_unidade = b.sq_unidade)
                  inner join co_pessoa_endereco c on (b.sq_pessoa_endereco = c.sq_pessoa_endereco)
          where a.cliente = @p_cliente 
            and ((@p_chave is null) or (@p_chave is not null and a.sq_unidade = @p_chave))
            and ((@p_ativo is null) or (@p_ativo is not null and a.ativo   = @p_ativo));         
   End
end