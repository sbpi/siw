create or replace function SP_GetDefGrpData
   (p_sq_grupo_deficiencia numeric,
    p_result               refcursor
   ) returns refcursor as $$
begin
   -- Recupera os dados do grupo de deficiência
   open p_result for 
      select * from co_grupo_defic where sq_grupo_defic = p_sq_grupo_deficiencia;
   return p_result;
end; $$ language 'plpgsql' volatile;