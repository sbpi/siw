create or replace procedure SP_GetCalendarRel
   (p_calendario   in number,
    p_result       out sys_refcursor) is
begin
   open p_result for
      select a.dt_calendario, b.ds_calendario, c.ds_dia_calendario
        from s_calendario      a
               left outer join s_calend_titulo   b on (a.co_calendario     = b.co_calendario and
                                                       a.co_unidade        = b.co_unidade)
               left outer join s_dia_calendario  c on (a.co_dia_calendario = c.co_dia_calendario and
                                                       a.co_unidade        = c.co_unidade)
       where  a.co_calendario = p_calendario;
end SP_GetCalendarRel;
/

