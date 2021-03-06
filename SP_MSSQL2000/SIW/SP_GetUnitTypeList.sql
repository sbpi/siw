create procedure dbo.Sp_GetUnitTypeList (
	@p_sq_pessoa int,
    @p_nome      varchar(25),
	@p_ativo     varchar(1)
) as
begin
   --Recupera a lista dos tipos de unidade
	select sq_tipo_unidade, nome, ativo
    from eo_tipo_unidade
    where sq_pessoa = @p_sq_pessoa
         and (@p_nome  is null or (@p_nome  is not null and dbo.acentos(nome) like '%' + dbo.acentos(@p_nome) + '%'))
         and (@p_ativo is null or (@p_ativo is not null and ativo = @p_ativo));
end