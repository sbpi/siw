create or replace function SP_GetStateData
   (p_sq_pais numeric,
    p_co_uf   varchar,
    p_result  refcursor
   ) returns refcursor as $$
begin
   -- Recupera os dados do estado
   open p_result for 
      select * from CO_UF where sq_pais = p_sq_pais and co_uf = p_co_uf;
   return p_result;
end; $$ language 'plpgsql' volatile;

