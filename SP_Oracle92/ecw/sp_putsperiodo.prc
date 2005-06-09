create or replace procedure SP_PutSPeriodo
   (p_operacao               in  varchar2,
    p_turno                  in  varchar2,
    p_co_grade_curric        in  number,
    p_ano                    in  number,
    p_co_tipo_curso          in  number,
    p_sg_serie               in  varchar2
   ) is
begin
   If p_operacao = 'L' Then
      -- Insere registro
      insert into s_periodo (turno, co_grade_curric, ano, co_tipo_curso, sg_serie)
      values(    trim(p_turno),
                 p_co_grade_curric,
                 p_ano,
                 p_co_tipo_curso,
                 trim(upper(p_sg_serie))
         );
   Elsif p_operacao = 'E' Then
     delete s_periodo where sg_serie = p_sg_serie and co_grade_curric = p_co_grade_curric;
   End If;
end SP_PutSPeriodo;
/

