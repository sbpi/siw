create or replace function SP_GetRegionData
   (p_sq_regiao  numeric,
    p_result     refcursor
   ) returns refcursor as $$
begin
   -- Recupera os dados da região
   open p_result for 
      select * from co_regiao where sq_regiao = p_sq_regiao;
   return p_result;
end; $$ language 'plpgsql' volatile;