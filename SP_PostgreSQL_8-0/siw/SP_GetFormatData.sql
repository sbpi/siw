create or replace function SP_GetFormatData
   (p_sq_formacao numeric,
    p_result      refcursor
   ) returns refcursor as $$
begin
   -- Recupera os dados da Formação
   open p_result for 
      select * from co_formacao where sq_formacao = p_sq_formacao;
   return p_result;
end; $$ language 'plpgsql' volatile;