
alter procedure dbo.SP_GetEtniaData
   (@p_chave   int) as
begin
   -- Recupera os dados da etnia informada
      select * from co_etnia where sq_etnia = @p_chave
end
