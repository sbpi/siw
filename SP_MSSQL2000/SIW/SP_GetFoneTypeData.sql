
alter procedure dbo.SP_GetFoneTypeData (@p_chave int) as
begin
   -- Recupera os dados do tipo da telefone
      select * from co_tipo_telefone where sq_tipo_telefone = @p_chave
end

