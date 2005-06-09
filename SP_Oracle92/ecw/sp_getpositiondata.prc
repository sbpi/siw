create or replace procedure SP_GetPositionData
   (p_co_cargo        in  varchar2,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera os dados do ambiente
   open p_result for
      select * from s_cargo where co_cargo = p_co_cargo;
end SP_GetPositionData;
/

