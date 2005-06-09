create or replace procedure SP_GetEtniaData
   (p_sq_etnia   in  number,
    p_result     out sys_refcursor
   ) is
begin
   -- Recupera os dados da etnia informada
   open p_result for 
      select * from co_etnia where sq_etnia = p_sq_etnia;
end SP_GetEtniaData;
/

