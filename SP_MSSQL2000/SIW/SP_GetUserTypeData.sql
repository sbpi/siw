
alter procedure dbo.SP_GetUserTypeData (@p_chave int) as
begin
   -- Recupera os dados do tipo da pessoa
      select * from co_tipo_pessoa where sq_tipo_pessoa = @p_chave
end
