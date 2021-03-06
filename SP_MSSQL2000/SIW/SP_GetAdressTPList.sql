alter procedure dbo.SP_GetAdressTPList 
   (@p_tipo_pessoa varchar(60) = null,
    @p_nome        varchar(60) = null,
    @p_ativo       varchar( 1) = null
   ) as
begin
   -- Recupera os tipos de endereço
      select a.sq_tipo_endereco, a.nome, a.padrao, 
             case a.padrao when 'S' then 'Sim' else 'Não' end as padraodesc,
             case a.email when 'S' then 'Sim' else 'Não' end as email, 
             case a.internet when 'S' then 'Sim' else 'Não' end as internet,
             a.ativo, 
             case a.ativo when 'S' then 'Sim' else 'Não' end as ativodesc, 
             b.nome as sq_tipo_pessoa, b.nome as nm_tipo_pessoa
        from co_tipo_endereco a, co_tipo_pessoa b  
      where a.sq_tipo_pessoa = b.sq_tipo_pessoa
        and (@p_tipo_pessoa is null or (@p_tipo_pessoa is not null and b.nome  = @p_tipo_pessoa))
        and (@p_nome        is null or (@p_nome        is not null and dbo.acentos(a.nome) like '%'+dbo.acentos(@p_nome)+'%'))
        and (@p_ativo       is null or (@p_ativo       is not null and a.ativo = @p_ativo));
end
