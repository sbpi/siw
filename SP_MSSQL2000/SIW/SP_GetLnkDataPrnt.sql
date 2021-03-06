alter procedure dbo.SP_GetLnkDataPrnt
   (@p_cliente   int,
    @p_restricao Varchar(10) =null
   ) as
begin
   -- Recupera os dados do link pai do que foi informado
      select a.sq_menu menu_pai, b.*
        from siw_menu a, siw_menu b
       where a.sq_menu       = b.sq_menu_pai
         and a.sigla         = @p_restricao
         and a.sq_pessoa     = @p_cliente
end