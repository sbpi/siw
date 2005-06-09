create or replace procedure SP_GetMatDiscData
   (p_co_grade_curric in  number,
    p_sg_serie        in  varchar2,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera as disciplinas da matriz curricular
   open p_result for
      select a.*, b.descr_serie, c.ds_tipo_curso, d.ds_tipo_disciplina, d.sg_disciplina
      from s_disciplina_per  a,
           s_serie           b,
           s_tipo_curso      c,
           s_tipo_disciplina d
      where a.co_grade_curric    = p_co_grade_curric
        and a.sg_serie           = p_sg_serie
        and a.sg_serie           = b.sg_serie           (+)
        and a.co_tipo_curso      = c.co_tipo_curso      (+)
        and a.co_tipo_disciplina = d.co_tipo_disciplina (+);
end SP_GetMatDiscData;
/

