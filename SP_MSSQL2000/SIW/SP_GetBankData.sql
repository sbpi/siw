   create procedure dbo.SP_GetBankData
   (@p_chave      int) as
begin
   -- Recupera os dados do banco informado
   select * from co_banco where sq_banco = @p_chave
end
