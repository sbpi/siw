create or replace procedure SP_GetMatSerData
   (p_co_grade_curric in  number,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera a lista de séries de uma matriz curricular
   open p_result for
      select a.*, b.descr_serie, c.ds_tipo_curso
      from s_periodo        a,
           s_serie          b,
           s_tipo_curso     c
      where a.co_grade_curric = p_co_grade_curric
        and a.sg_serie        = b.sg_serie        (+)
        and a.co_tipo_curso   = c.co_tipo_curso   (+);
end SP_GetMatSerData;
/

