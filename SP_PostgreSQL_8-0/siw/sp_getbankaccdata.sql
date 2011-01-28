create or replace FUNCTION SP_GetBankAccData
   (p_chave       numeric,
    p_result     REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os dados da conta bancária
   open p_result for 
      Select b.sq_banco, b.codigo agencia, a.numero, a.operacao, 
             a.tipo_conta, a.ativo, a.padrao, a.devolucao_valor,
             a.saldo_inicial 
      from co_pessoa_conta a, 
           co_agencia      b 
      where a.sq_agencia        = b.sq_agencia 
        and a.sq_pessoa_conta   = p_chave;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;