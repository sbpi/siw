
alter procedure dbo.SP_GetFormatData
   (@p_chave int) as
begin
   -- Recupera os dados da Formação

      select * from co_formacao where sq_formacao = @p_chave
end

