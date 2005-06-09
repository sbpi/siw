create or replace procedure SP_GetMatSerList
   (p_co_grade_curric in  number,
    p_co_tipo_curso   in  number,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera as series existentes com exceção das já existentes na matriz
   open p_result for
      select a.sg_serie, a.descr_serie, b.ds_tipo_curso, a.co_tipo_curso
        from s_serie a,
             s_tipo_curso b
        where a.co_tipo_curso = p_co_tipo_curso
          and a.co_tipo_curso = b.co_tipo_curso
          and a.sg_serie not in (select sg_serie from s_periodo where co_grade_curric = p_co_grade_curric);
end SP_GetMatSerList;
/

