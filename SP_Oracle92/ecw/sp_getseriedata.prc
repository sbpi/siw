create or replace procedure SP_GetSerieData
   (p_sg_serie        in  varchar2,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera os dados da serie
   open p_result for
      select * from s_serie where sg_serie = p_sg_serie;
end SP_GetSerieData;
/

