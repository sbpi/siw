create or replace function SP_GetBankData
   (p_chave      numeric,
    p_result     refcursor
   ) returns refcursor as $$
begin
   -- Recupera os dados do banco informado
   open p_result for 
      select * from co_banco where sq_banco = p_chave;
   return p_result;
end; $$ language 'plpgsql' volatile;

