alter procedure dbo.SP_GetUnitTypeData (@p_chave int) as
begin
   --Recupera os dados do tipo da unidade
      select nome, sq_tipo_unidade, ativo
        from eo_tipo_unidade 
       where sq_tipo_unidade = @p_chave
end