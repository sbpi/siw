create or replace procedure SP_PutSGrade_Curr
   (p_operacao               in  varchar2,
    p_chave                  in  number default null,
    p_co_tipo_curso          in  number,
    p_ano                    in  number,
    p_turno                  in  varchar2,
    p_dt_grade               in  date,
    p_nu_semanas             in  number,
    p_nu_grade               in  varchar2,
    p_ds_grade               in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_grade_curric (co_grade_curric, co_tipo_curso, ano, turno, dt_grade, nu_semanas, nu_grade, ds_grade)
         (select co_grade_curric.nextval,
                 p_co_tipo_curso,
                 p_ano,
                 trim(upper(p_turno)),
                 p_dt_grade,
                 p_nu_semanas,
                 trim(p_nu_grade),
                 trim(upper(p_ds_grade))
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_grade_curric set
         co_tipo_curso  = p_co_tipo_curso ,
         ano            = p_ano,
         turno          = trim(upper(p_turno)),
         dt_grade       = p_dt_grade,
         nu_semanas     = p_nu_semanas,
         nu_grade       = trim(p_nu_grade),
         ds_grade       = trim(upper(p_ds_grade))
      where co_grade_curric    = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_grade_curric where co_grade_curric = p_chave;
   End If;
end SP_PutSGrade_Curr;
/

