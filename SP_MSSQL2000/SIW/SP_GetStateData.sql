alter procedure dbo.SP_GetStateData
   (@p_sq_pais int,
    @p_co_uf   varchar(3)
   ) as
begin
   -- Recupera os dados do estado
      select * from CO_UF where sq_pais = @p_sq_pais and co_uf = @p_co_uf;
end
