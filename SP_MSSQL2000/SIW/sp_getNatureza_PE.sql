alter procedure dbo.sp_GetNatureza_PE
   (@p_chave   int         =null,
    @p_cliente int         =null,
    @p_nome    varchar(30) =null,
    @p_ativo   varchar(1)  =null
    ) as
begin
   -- Recupera os tipos de arquivos
      select a.sq_penatureza chave, a.cliente, a.nome, a.ativo, 
             case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
        from pe_natureza a
       where ((@p_chave   is null) or (@p_chave   is not null and a.sq_penatureza = @p_chave))
         and ((@p_cliente is null) or (@p_cliente is not null and a.cliente     = @p_cliente))
         and ((@p_nome    is null) or (@p_nome    is not null and upper(a.nome) like '%' + upper(@p_nome) + '%'))
         and ((@p_ativo   is null) or (@p_ativo   is not null and a.ativo       = @p_ativo));
end