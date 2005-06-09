create or replace procedure SP_GetRoomTypeList
   (p_result    out sys_refcursor) is
begin
   -- Recupera a origem dos tipos de salas existentes
   open p_result for
      select co_tipo_sala, ds_tipo_sala
        from s_tipo_sala
      order by co_tipo_sala;
end SP_GetRoomTypeList;
/

