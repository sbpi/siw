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

create or replace procedure SP_GetAmbientList
   (p_result    out sys_refcursor) is
begin
   -- Recupera os ambientes existentes
   open p_result for 
      select co_seq_ambiente, ds_ambiente 
        from s_ambiente
      order by co_seq_ambiente;
end SP_GetAmbientList;
/
