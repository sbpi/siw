alter procedure dbo.SP_GetEOAAtuacData (
	@p_chave int
	) as
begin
   --Recupera a lista de áreas de atuação
      select nome, ativo, sq_area_atuacao
        from eo_area_atuacao 
       where sq_area_atuacao = @p_chave
end