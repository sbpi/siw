create or replace procedure SP_GetAtuAreaData
   (p_co_area_atuacao in  number,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera os dados do ambiente
   open p_result for
      select * from s_area_atuacao where co_area_atuacao = p_co_area_atuacao;
end SP_GetAtuAreaData;
/

