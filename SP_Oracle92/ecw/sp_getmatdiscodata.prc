create or replace procedure SP_GetMatDiscOData
   (p_co_grade_curric      in  number,
    p_co_tipo_disciplina   in  number,
    p_sg_serie             in  varchar2,
    p_result               out sys_refcursor
   ) is
begin
   -- Recupera os dados de uma disciplina da matriz curricular
   open p_result for
      select a.*, b.ds_tipo_disciplina
      from s_disciplina_per  a,
           s_tipo_disciplina b
      where a.co_grade_curric    = p_co_grade_curric
        and a.co_tipo_disciplina = p_co_tipo_disciplina
        and a.sg_serie           = p_sg_serie
        and a.co_tipo_disciplina = b.co_tipo_disciplina (+);
end SP_GetMatDiscOData;
/

