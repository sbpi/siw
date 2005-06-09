create or replace procedure SP_GetEtniaList
   (p_result    out sys_refcursor) is
begin
   -- Recupera as etnias existentes
   open p_result for 
      select codigo_siape, sq_etnia, nome, ativo, 
             case ativo when 'S' then 'Sim' else 'Não' end descativo 
        from co_etnia;
end SP_GetEtniaList;
/

