create or replace function SP_GetBankHousData
   (p_sq_agencia numeric,
    p_result     refcursor
   ) returns refcursor as $$
begin
   -- Recupera os dados da agência bancária
   open p_result for 
      select * from co_agencia where sq_agencia = p_sq_agencia;
   return p_result;
end; $$ language 'plpgsql' volatile;

