create or replace procedure SP_GetPositionList
   (p_result    out sys_refcursor) is
begin
   -- Recupera as �rea de atua��es existentes
   open p_result for
      select co_cargo, ds_cargo
        from s_cargo
      order by co_cargo;
end SP_GetPositionList;
/

