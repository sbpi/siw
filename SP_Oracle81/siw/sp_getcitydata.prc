create or replace procedure SP_GetCityData
   (p_sq_cidade  in  number,
    p_result     out siw.sys_refcursor
   ) is
begin
   -- Recupera os dados da cidade
   open p_result for
      select * from co_cidade where sq_cidade = p_sq_cidade;
end SP_GetCityData;
/

