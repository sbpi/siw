
alter procedure dbo.SP_GetBankHousData
   (@p_chave int) as
begin
   -- Recupera os dados da agência bancária
   select * from co_agencia where sq_agencia = @p_chave
end
