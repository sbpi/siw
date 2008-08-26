alter procedure dbo.sp_GetObjetivo_PE
   (@p_chave     int   =null,
    @p_chave_aux int   =null,
    @p_cliente   int   =null,
    @p_nome      varchar(100) =null,
    @p_sigla     varchar(10) =null,
    @p_ativo     varchar(1) =null,
    @p_restricao varchar(20) =null
   ) as
begin
   If @p_restricao is null begin
      -- Recupera os tipos de arquivos
         select a.sq_peobjetivo as chave, a.cliente, a.sq_plano, a.nome, a.sigla, a.descricao, a.ativo, a.codigo_externo,
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from pe_objetivo a
          where a.cliente      = @p_cliente
            and a.sq_plano     = @p_chave
            and ((@p_chave_aux  is null) or (@p_chave_aux is not null and a.sq_peobjetivo = @p_chave_aux))
            and ((@p_nome       is null) or (@p_nome      is not null and upper(a.nome) like '%' + upper(@p_nome) + '%'))
            and ((@p_sigla      is null) or (@p_sigla     is not null and upper(a.sigla) = upper(@p_sigla)))
            and ((@p_ativo      is null) or (@p_ativo     is not null and a.ativo        = @p_ativo));
   End
end