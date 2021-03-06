create procedure dbo.SP_GetFoneTypeList(
	@p_tipo_pessoa varchar(60),
    @p_nome        varchar(30),
    @p_ativo       varchar(1)
) as
begin
   -- Recupera o tipos de telefones existentes
      select a.sq_tipo_telefone, a.nome, a.padrao, 
             case a.padrao when 'S' then 'Sim' else 'Não' end padraodesc, 
             a.ativo, 
             case a.ativo when 'S' then 'Sim' else 'Não' end ativodesc, b.nome sq_tipo_pessoa
        from co_tipo_telefone a, co_tipo_pessoa b
      where a.sq_tipo_pessoa = b.sq_tipo_pessoa
        and (@p_tipo_pessoa is null or (@p_tipo_pessoa is not null and b.nome = @p_tipo_pessoa))
        and (@p_nome        is null or (@p_nome        is not null and dbo.acentos(a.nome) like '%' + dbo.acentos(@p_nome) + '%'))
        and (@p_ativo       is null or (@p_ativo       is not null and a.ativo = @p_ativo));      
end