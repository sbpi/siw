create or replace procedure SP_GetBankAccData
   (p_chave      in  number,
    p_result     out siw.sys_refcursor
   ) is
begin
   -- Recupera os dados da conta banc�ria
   open p_result for
      Select b.sq_banco, b.codigo agencia, a.numero, a.operacao,
             a.tipo_conta, a.ativo, a.padrao
      from co_pessoa_conta a,
           co_agencia      b
      where a.sq_agencia        = b.sq_agencia
        and a.sq_pessoa_conta   = p_chave;
end SP_GetBankAccData;
/

