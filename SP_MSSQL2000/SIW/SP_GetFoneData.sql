alter procedure dbo.SP_GetFoneData (@p_chave int) as
begin
   -- Recupera os dados do endereco informado
      select b.*, c.sq_pais, c.co_uf 
      from co_pessoa_telefone b, co_cidade c  
      where b.sq_cidade          = c.sq_cidade 
        and b.sq_pessoa_telefone = @p_chave
end