create or replace procedure SP_GetRoomTypeData
   (p_co_tipo_sala     in  number,
    p_result           out sys_refcursor
   ) is
begin
   -- Recupera os dados da origem dos tipos de sala
   open p_result for
      select * from s_tipo_sala where co_tipo_sala = p_co_tipo_sala;
end SP_GetRoomTypeData;
/

