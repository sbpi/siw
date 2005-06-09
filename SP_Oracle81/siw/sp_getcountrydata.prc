create or replace procedure SP_GetCountryData
   (p_sq_pais in  number,
    p_result     out siw.sys_refcursor
   ) is
begin
   -- Recupera os dados do país
   open p_result for
      select * from co_pais where sq_pais = p_sq_pais;
end SP_GetCountryData;
/

