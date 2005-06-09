create or replace procedure SP_GetBankHousList
   (p_sq_banco   in  number,
    p_result     out siw.sys_refcursor
   ) is
begin
   -- Recupera os dados da agência bancária
   open p_result for
      select a.sq_agencia, b.codigo sq_banco, a.nome, a.codigo,
             decode(a.padrao,'S','Sim','Não') padrao,
             decode(a.ativo,'S','Sim','Não') ativo
        from co_agencia a, co_banco b
       where a.sq_banco   = b.sq_banco
         and b.sq_banco   = p_sq_banco;
end SP_GetBankHousList;
/

