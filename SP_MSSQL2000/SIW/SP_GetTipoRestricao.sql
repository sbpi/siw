create procedure sp_GetTipoRestricao
   (@p_chave     int       = null,
    @p_cliente   int       = null,
    @p_nome      varchar(30) = null,
    @p_codigo    varchar(30) = null,
    @p_ativo     varchar(1) = null,
    @p_restricao varchar(15) = null
	) as
begin
   If @p_restricao is null Begin
      -- Recupera os tipos de arquivos
         select a.sq_tipo_restricao chave, a.cliente, a.nome, a.codigo_externo, a.ativo, 
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from siw_tipo_restricao a
          where a.cliente            = @p_cliente
            and ((@p_chave   is null) or (@p_chave   is not null and a.sq_tipo_restricao = @p_chave))
            and ((@p_nome    is null) or (@p_nome    is not null and upper(a.nome) like '%' + upper(@p_nome) + '%'))
            and ((@p_ativo   is null) or (@p_ativo   is not null and a.ativo             = @p_ativo));
   End Else If @p_restricao = 'EXISTE' Begin
      -- Verifica se há outro registro com a mesmo nome ou codigo
         select a.sq_tipo_restricao chave, a.cliente, a.nome, a.codigo_externo, a.ativo, 
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from siw_tipo_restricao a
          where a.sq_tipo_restricao <> coalesce(@p_chave,0)
            and a.cliente            = @p_cliente
            and ((@p_nome    is null) or (@p_nome    is not null and upper(a.nome) like '%' + upper(@p_nome) + '%'))
            and ((@p_codigo  is null) or (@p_codigo  is not null and upper(a.codigo_externo) like '%' + upper(@p_codigo) + '%'));
   End
end
