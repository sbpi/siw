alter procedure dbo.SP_GetMenuCode
   (@p_cliente   int,
    @p_sigla     varchar(10)
   ) as
begin
   -- Recupera o código de uma opção do menu a partir de sua sigla
      select *
      from siw_menu a 
      where a.sq_pessoa = @p_cliente
        and a.sigla     = @p_sigla
end
