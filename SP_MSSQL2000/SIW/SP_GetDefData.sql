alter procedure dbo.SP_GetDefData
   (@p_chave int) as
begin
   -- Recupera os dados da deficiência
      select * from co_deficiencia where sq_deficiencia = @p_chave
end

