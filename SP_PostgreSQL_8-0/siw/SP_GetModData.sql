create or replace function SP_GetModData
   (p_sq_modulo   numeric,
    p_result      refcursor
   ) returns refcursor as $$
begin
   --Recupera os dados de um módulo
   open p_result for
      select * from siw_modulo where sq_modulo = p_sq_modulo;
   return p_result;
end; $$ language 'plpgsql' volatile;


