create or replace function SP_GetCityData
   (p_sq_cidade  numeric, 
    p_result     refcursor
   ) returns refcursor as $$
begin
   -- Recupera os dados da cidade
   open p_result for 
      select * from co_cidade where sq_cidade = p_sq_cidade;
   return p_result;
end;  $$ language 'plpgsql' volatile;

