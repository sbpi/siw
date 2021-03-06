alter procedure SP_GetBankList
   (@p_codigo  varchar(30) = null,
    @p_nome    varchar(30) = null,
    @p_ativo   varchar(1) = null
	) as
begin
   -- Recupera os bancos existentes
      select sq_banco, codigo, nome, ativo, codigo + ' - ' + nome descricao, padrao, exige_operacao
        from co_banco a 
       where (@p_nome   is null or (@p_nome   is not null and dbo.acentos(nome) like '%' + dbo.acentos(@p_nome) + '%'))
         and (@p_codigo is null or (@p_codigo is not null and codigo = @p_codigo))
         and (@p_ativo  is null or (@p_ativo  is not null and ativo  = @p_ativo))
      order by padrao desc, codigo;
end
