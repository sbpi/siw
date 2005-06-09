create or replace procedure SP_GetSegList
   (p_result      out sys_refcursor
   ) is
begin
   --Recupera a lista de segmentos
   open p_result for
      select sq_segmento, nome, padrao, ativo 
      from co_segmento;
end SP_GetSegList;
/

