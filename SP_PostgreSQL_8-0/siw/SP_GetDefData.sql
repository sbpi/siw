create or replace function SP_GetDefData
   (p_sq_deficiencia numeric,
    p_result         refcursor
   ) returns refcursor as $$
begin
   -- Recupera os dados da deficiência
   open p_result for 
      select * from co_deficiencia where sq_deficiencia = p_sq_deficiencia;
   return p_result;
end; $$ language 'plpgsql' volatile;
