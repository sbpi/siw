create or replace procedure SP_GetCourseTPData
   (p_co_tipo_curso in  number,
    p_result     out sys_refcursor
   ) is
begin
   -- Recupera os dados do tipo de curso
   open p_result for
      select * from s_tipo_curso where co_tipo_curso = p_co_tipo_curso;
end SP_GetCourseTPData;
/

