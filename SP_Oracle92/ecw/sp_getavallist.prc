create or replace procedure SP_GetAvalList
   (p_result    out sys_refcursor) is
begin
   -- Recupera os tipos de avaliações existentes
   open p_result for
      select co_tipo_avaliacao, ds_tipo_avaliacao
        from s_tipo_avaliacao
      order by co_tipo_avaliacao;
end SP_GetAvalList;
/

