alter procedure dbo.SP_GetKindPersList (
	@p_nome       varchar(60) = null
) as
begin
   -- Recupera os tipos de pessoas
       select sq_tipo_pessoa, nome 
        from co_tipo_pessoa 
       where (@p_nome is null or (@p_nome is not null and dbo.acentos(nome) = dbo.acentos(@p_nome)))
        order by nome;
end
