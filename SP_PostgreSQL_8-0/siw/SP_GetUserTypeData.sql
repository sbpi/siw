create or replace function SP_GetUserTypeData
   (p_sq_tipo_pessoa numeric,
    p_result         refcursor
   ) returns refcursor as $$
begin
   -- Recupera os dados do tipo da pessoa
   open p_result for 
      select * from co_tipo_pessoa where sq_tipo_pessoa = p_sq_tipo_pessoa;
   return p_result;
end; $$ language 'plpgsql' volatile;