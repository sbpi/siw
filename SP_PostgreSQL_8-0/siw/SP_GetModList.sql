create or replace function SP_GetModList
   (p_result      refcursor
   ) returns refcursor as $$
begin
   --Recupera a lista de módulos
   open p_result for
      select sq_modulo, nome, objetivo_geral, sigla 
        from siw_modulo;
   return p_result;
end; $$ language 'plpgsql' volatile;