create or replace procedure SP_GetIdiomList
   (p_result    out sys_refcursor) is
begin
   -- Recupera os bancos existentes
   open p_result for 
      select sq_idioma, nome, padrao, ativo, 
             case padrao when 'S' then 'Sim' else 'N�o' end padraodesc, 
             case ativo when 'S' then 'Sim' else 'N�o' end ativodesc 
       from co_idioma; 
end SP_GetIdiomList;
/

