create or replace function SP_GetEtniaData
   (p_sq_etnia   numeric,
    p_result     refcursor
   ) returns refcursor as $$
begin
   -- Recupera os dados da etnia informada
   open p_result for 
      select * from co_etnia where sq_etnia = p_sq_etnia;
   return p_result;
end; $$ language 'plpgsql' volatile;