create or replace procedure SP_GetSchOrData
   (p_co_origem_escola in  number,
    p_result           out sys_refcursor
   ) is
begin
   -- Recupera os dados da origem das escolas
   open p_result for
      select * from s_origem_escola where co_origem_escola = p_co_origem_escola;
end SP_GetSchOrData;
/

