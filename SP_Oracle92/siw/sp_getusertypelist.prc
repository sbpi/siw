create or replace procedure SP_GetUserTypeList
   (p_result    out sys_refcursor) is
begin
   -- Recupera o tipos de pessoas existentes
   open p_result for 
      select sq_tipo_pessoa, nome, padrao, 
             case padrao when 'S' then 'Sim' else 'Não' end padraodesc, 
             ativo, 
             case ativo when 'S' then 'Sim' else 'Não' end ativodesc
        from co_tipo_pessoa;  
end SP_GetUserTypeList;
/

