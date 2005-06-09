create or replace procedure SP_GetSerieList
   (p_result    out sys_refcursor) is
begin
   -- Recupera as series existentes
   open p_result for
      select a.sg_serie, a.descr_serie, b.ds_tipo_curso, a.co_tipo_curso
        from s_serie a,
             s_tipo_curso b
        where a.co_tipo_curso = b.co_tipo_curso
      order by sg_serie;
end SP_GetSerieList;
/

