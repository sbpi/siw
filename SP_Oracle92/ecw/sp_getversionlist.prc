create or replace procedure SP_GetVersionList
   (p_result    out sys_refcursor) is
begin
   -- Recupera as versões
   open p_result for
      select distinct(ds_versao)
        from s_versao
      order by ds_versao;
end SP_GetVersionList;
/

