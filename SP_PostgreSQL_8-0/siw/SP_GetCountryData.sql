create or replace function SP_GetCountryData
   (p_sq_pais numeric,
    p_result  refcursor
   ) returns refcursor as $$
begin
   -- Recupera os dados do país
   open p_result for 
      select * from co_pais where sq_pais = p_sq_pais;
   return p_result;
end; $$ language 'plpgsql' volatile;

