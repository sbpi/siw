
alter procedure dbo.SP_GetIdiomData
   (@p_chave int) as
begin
   -- Recupera os dados do Idioma
      select * from co_idioma where sq_idioma = @p_chave
end

