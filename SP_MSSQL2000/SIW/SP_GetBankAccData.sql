alter procedure dbo.SP_GetBankAccData
   (@p_chave int
   ) as
begin
   -- Recupera os dados da conta banc�ria
      Select b.sq_banco, b.codigo agencia, a.numero, a.operacao, 
             a.tipo_conta, a.ativo, a.padrao 
      from co_pessoa_conta a, 
           co_agencia      b 
      where a.sq_agencia        = b.sq_agencia 
        and a.sq_pessoa_conta   = @p_chave;
end
