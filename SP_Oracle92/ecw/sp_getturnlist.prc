create or replace procedure SP_GetTurnList
   (p_result    out sys_refcursor) is
begin
   -- Recupera as �rea de atua��es existentes
   open p_result for
      select co_turno, ds_turno
        from s_turno
      order by co_turno;
end SP_GetTurnList;
/

