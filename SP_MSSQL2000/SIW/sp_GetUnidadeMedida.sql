alter procedure dbo.sp_getUnidadeMedida
   (@p_cliente   int,
    @p_chave     int       = null,
    @p_nome      varchar(60) = null,
    @p_sigla     varchar(15) = null,
    @p_ativo     varchar(1) = null,
    @p_restricao varchar(15) = null
	) as
begin
   If @p_restricao = 'REGISTROS' Begin
      -- Recupera os tipos de recurso existentes
      -- open @p_result for 
         select a.sq_unidade_medida as chave, a.cliente, a.nome,
                a.sigla, a.ativo, 
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from co_unidade_medida a
          where a.cliente            = @p_cliente
            and (@p_chave             is null or (@p_chave is not null and a.sq_unidade_medida = @p_chave))
            and (@p_nome              is null or (@p_nome is not null and a.nome = @p_nome))
            and (@p_sigla             is null or (@p_sigla is not null and a.sigla = upper(@p_sigla)))
            and (@p_ativo             is null or (@p_ativo is not null and a.ativo = @p_ativo))
         order by a.nome;
   End Else If @p_restricao = 'EXISTE' Begin
      -- Verifica se há outro registro com o mesmo nome ou sigla
      -- open @p_result for 
         select a.sq_unidade_medida as chave, a.cliente, a.nome,
                a.sigla, a.ativo, 
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from co_unidade_medida a
          where a.cliente           = @p_cliente
            and a.sq_unidade_medida <> coalesce(@p_chave,0)
            and (@p_nome             is null or (@p_nome is not null and dbo.acentos(a.nome) = dbo.acentos(@p_nome)))
            and (@p_sigla            is null or (@p_sigla is not null and dbo.acentos(a.sigla) = dbo.acentos(@p_sigla)))
            and (@p_ativo            is null or (@p_ativo is not null and a.ativo = @p_ativo))
         order by a.nome;
   End Else If @p_restricao = 'VINCULADO' Begin
      -- Verifica se o registro está vinculado a um recurso
      -- open @p_result for 
         select a.sq_unidade_medida as chave, a.cliente, a.nome,
                a.sigla, a.ativo, 
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from co_unidade_medida              a
                inner join eo_recurso          b on (a.sq_unidade_medida = b.sq_unidade_medida)
          where a.cliente                = @p_cliente
            and a.sq_unidade_medida    = @p_chave
         order by a.nome;
   End
end
