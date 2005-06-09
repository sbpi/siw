create or replace procedure SP_GetDeficGrpList
   (p_result    out siw.sys_refcursor) is
begin
   -- Recupera os grupos de defici�ncias existentes
   open p_result for
      select sq_grupo_defic, nome, ativo,
             decode(ativo,'S','Sim','N�o') ativodesc
        from co_grupo_defic
      order by nome;
end SP_GetDeficGrpList;
/

