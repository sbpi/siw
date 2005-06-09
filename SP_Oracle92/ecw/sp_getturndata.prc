create or replace procedure SP_GetTurnData
   (p_co_turno        in  char,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera os dados do ambiente
   open p_result for
      select * from s_turno where co_turno = p_co_turno;
end SP_GetTurnData;
/

