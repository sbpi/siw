alter procedure dbo.SP_GetEOAAtuac 
   (@p_sq_pessoa   int,
    @p_nome     varchar(25) = null,
    @p_ativo    varchar( 1) = null
   ) as
begin
   --Recupera a lista de áreas de atuação
	select sq_area_atuacao, nome, ativo
    from eo_area_atuacao
    where sq_pessoa = @p_sq_pessoa
         and (@p_nome  is null or (@p_nome  is not null and dbo.acentos(nome) like '%' + dbo.acentos(@p_nome) + '%'))
         and (@p_ativo is null or (@p_ativo is not null and ativo = @p_ativo));
end