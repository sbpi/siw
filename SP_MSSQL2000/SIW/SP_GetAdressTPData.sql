
alter procedure dbo.SP_GetAdressTPData
   (@p_chave int)
as
begin
   -- Recupera os dados do tipo de endereço
      select * from co_tipo_endereco where sq_tipo_endereco = @p_chave
end

