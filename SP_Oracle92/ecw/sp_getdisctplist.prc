create or replace procedure SP_GetDiscTPList
   (p_result    out sys_refcursor) is
begin
   -- Recupera os tipos de diciplinas existentes
   open p_result for
      select co_tipo_disciplina, sg_disciplina, ds_tipo_disciplina
        from s_tipo_disciplina
      order by co_tipo_disciplina;
end SP_GetDiscTPList;
/

