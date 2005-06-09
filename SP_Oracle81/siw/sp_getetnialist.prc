create or replace procedure SP_GetEtniaList
   (p_result    out siw.sys_refcursor) is
begin
   -- Recupera as etnias existentes
   open p_result for
      select codigo_siape, sq_etnia, nome, ativo,
             decode(ativo,'S','Sim','Não') descativo
        from co_etnia;
end SP_GetEtniaList;
/

