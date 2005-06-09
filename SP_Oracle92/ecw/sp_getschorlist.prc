create or replace procedure SP_GetSchOrList
   (p_result    out sys_refcursor) is
begin
   -- Recupera a origem das escolas existentes
   open p_result for
      select co_origem_escola, ds_origem_escola
        from s_origem_escola
      order by co_origem_escola;
end SP_GetSchOrList;
/

