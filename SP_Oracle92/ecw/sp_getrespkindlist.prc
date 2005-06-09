create or replace procedure SP_GetRespKindList
   (p_cliente     in number,
    p_result      out sys_refcursor
   ) is
begin
   -- Recupera a lista de tipos de responsável
   open p_result for
      select a.co_tip_responsavel, a.ds_tip_responsavel
        from s_tipo_responsavel  a;
end SP_GetRespKindList;
/

