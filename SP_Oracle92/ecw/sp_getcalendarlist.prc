create or replace procedure SP_GetCalendarList
   (p_periodo      in number,
    p_unidade      in char,
    p_result       out sys_refcursor) is
begin
   open p_result for
      select ds_calendario, co_calendario
        from s_calend_titulo
       where ano           = p_periodo
         and co_unidade    = p_unidade;
end SP_GetCalendarList;
/

