create or replace procedure SP_GetAmbientData
   (p_co_seq_ambiente in  number,
    p_result     out sys_refcursor
   ) is
begin
   -- Recupera os dados do ambiente
   open p_result for
      select * from s_ambiente where co_seq_ambiente = p_co_seq_ambiente;
end SP_GetAmbientData;
/

