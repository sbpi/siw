create or replace procedure SP_GetFormatList
   (p_result    out sys_refcursor) is
begin
   -- Recupera os bancos existentes
   open p_result for 
      select ordem, sq_formacao, nome, ativo, 
             case tipo when '1' then 'Acadêmica' 
                       when '2' then 'Técnica'
                       else 'Prod.Cient.' 
             end tipo, 
             case ativo when 'S' then 'Sim' else 'Não' end ativodesc 
        from co_formacao; 
end SP_GetFormatList;
/

