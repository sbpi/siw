create or replace procedure SP_GetMatrixList
   (p_result    out sys_refcursor) is
begin
   -- Recupera as matrizes existentes
   open p_result for
      select a.*, b.ds_tipo_curso
        from s_grade_curric a,
             s_tipo_curso b
        where a.co_tipo_curso = b.co_tipo_curso
      order by co_grade_curric;
end SP_GetMatrixList;
/

