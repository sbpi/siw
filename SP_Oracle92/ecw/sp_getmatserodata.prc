create or replace procedure SP_GetMatSerOData
   (p_co_grade_curric in  number,
    p_sg_serie        in  varchar2,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera os dados da matriz curricular
   open p_result for
      select a.*, b.descr_serie, b.sg_serie, c.ds_tipo_curso, d.ds_grade
      from s_periodo        a,
           s_serie          b,
           s_tipo_curso     c,
           s_grade_curric   d
      where a.co_grade_curric = p_co_grade_curric
        and a.sg_serie        = p_sg_serie
        and a.sg_serie        = b.sg_serie        (+)
        and a.co_tipo_curso   = c.co_tipo_curso   (+)
        and d.co_grade_curric = p_co_grade_curric;
end SP_GetMatSerOData;
/

