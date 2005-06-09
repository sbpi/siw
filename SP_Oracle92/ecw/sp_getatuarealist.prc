create or replace procedure SP_GetAtuAreaList
   (p_result    out sys_refcursor) is
begin
   -- Recupera as área de atuações existentes
   open p_result for
      select co_area_atuacao, ds_area_atuacao
        from s_area_atuacao
      order by co_area_atuacao;
end SP_GetAtuAreaList;
/

