create or replace procedure SP_GetDeficGrpList
   (p_result    out sys_refcursor) is
begin
   -- Recupera os grupos de defici�ncias existentes
   open p_result for 
      select sq_grupo_defic, nome, ativo, 
             case ativo when 'S' then 'Sim' else 'N�o' end ativodesc 
        from co_grupo_defic
      order by nome; 
end SP_GetDeficGrpList;
/

