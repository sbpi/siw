create or replace procedure SP_GetCourseTPList
   (p_result    out sys_refcursor) is
begin
   -- Recupera os tipos de curso existentes
   open p_result for
      select co_tipo_curso, sg_tipo_curso, ds_tipo_curso
        from s_tipo_curso
      order by co_tipo_curso;
end SP_GetCourseTPList;
/

