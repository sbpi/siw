create or replace procedure SP_GetBankHousList
   (p_sq_banco   in  number,
    p_result     out sys_refcursor
   ) is
begin
   -- Recupera os dados da agência bancária
   open p_result for 
      select a.sq_agencia, b.codigo sq_banco, a.nome, a.codigo,
             case a.padrao when 'S' then 'Sim' else 'Não' end padrao,
             case a.ativo  when 'S' then 'Sim' else 'Não' end ativo
        from co_agencia a, co_banco b
       where a.sq_banco   = b.sq_banco
         and b.sq_banco   = p_sq_banco;
end SP_GetBankHousList;
/

