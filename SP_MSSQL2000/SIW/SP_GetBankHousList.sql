alter procedure dbo.SP_GetBankHousList
   (@p_sq_banco int,
    @p_nome     varchar(40) = null,
    @p_codigo   varchar(30) = null
   ) as
begin
   -- Recupera os dados da ag�ncia banc�ria
      select a.sq_agencia, b.codigo sq_banco, a.nome, a.codigo,
             case a.padrao when 'S' then 'Sim' else 'N�o' end padrao,
             case a.ativo  when 'S' then 'Sim' else 'N�o' end ativo
        from co_agencia a, co_banco b
       where a.sq_banco   = b.sq_banco
         and b.sq_banco   = @p_sq_banco
         and (@p_nome   is null or (@p_nome   is not null and dbo.acentos(a.nome) like '%' + dbo.acentos(@p_nome) + '%'))
         and (@p_codigo is null or (@p_codigo is not null and a.codigo = @p_codigo));
end