create or replace function SP_GetIdiomData
   (p_sq_idioma numeric,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera os dados do Idioma
   open p_result for 
      select * from co_idioma where sq_idioma = p_sq_idioma;
   return p_result;
end; $$ language 'plpgsql' volatile;

