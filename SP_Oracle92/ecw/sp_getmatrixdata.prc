create or replace procedure SP_GetMatrixData
   (p_co_grade_curric in  number,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera os dados da matriz curricular
   open p_result for
      select a.*, b.ds_tipo_curso
      from s_grade_curric a,
           s_tipo_curso   b
      where a.co_grade_curric = p_co_grade_curric
        and a.co_tipo_curso   = b.co_tipo_curso   (+);
end SP_GetMatrixData;
/

