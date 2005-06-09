create or replace procedure SP_GetDefGrpData
   (p_sq_grupo_deficiencia in  number,
    p_result     out siw.sys_refcursor
   ) is
begin
   -- Recupera os dados do grupo de deficiência
   open p_result for
      select * from co_grupo_defic where sq_grupo_defic = p_sq_grupo_deficiencia;
end SP_GetDefGrpData;
/

