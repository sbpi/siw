create or replace procedure SP_GetAvalData
   (p_co_tipo_avaliacao in  number,
    p_result            out sys_refcursor
   ) is
begin
   -- Recupera os dados do tipo de avaliação
   open p_result for
      select * from s_tipo_avaliacao where co_tipo_avaliacao = p_co_tipo_avaliacao;
end SP_GetAvalData;
/

