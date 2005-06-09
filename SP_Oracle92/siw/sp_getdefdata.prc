create or replace procedure SP_GetDefData
   (p_sq_deficiencia in  number,
    p_result     out sys_refcursor
   ) is
begin
   -- Recupera os dados da deficiência
   open p_result for 
      select * from co_deficiencia where sq_deficiencia = p_sq_deficiencia;
end SP_GetDefData;
/

