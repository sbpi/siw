create or replace procedure SP_GetIdiomData
   (p_sq_idioma in  number,
    p_result     out sys_refcursor
   ) is
begin
   -- Recupera os dados do Idioma
   open p_result for 
      select * from co_idioma where sq_idioma = p_sq_idioma;
end SP_GetIdiomData;
/

