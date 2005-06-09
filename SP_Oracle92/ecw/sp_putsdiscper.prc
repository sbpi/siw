create or replace procedure SP_PutSDiscPer
   (p_operacao               in  varchar2,
    p_sg_serie               in  varchar2,
    p_co_tipo_disciplina     in  number,
    p_co_grade_curric        in  number,
    p_co_tipo_curso          in  number,
    p_ano                    in  number,
    p_turno                  in  varchar2,
    p_carga_horaria_sem      in  varchar2,
    p_tp_disciplina          in  varchar2,
    p_co_disciplina          in  varchar2,
    p_ds_disciplina          in  varchar2,
    p_nu_ordem_imp           in number,
    p_tp_avaliacao           in varchar2,
    p_tp_digitacao           in varchar2,
    p_tp_impressao           in varchar2,
    p_st_reprova             in varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_disciplina_per (sg_serie, co_tipo_disciplina, co_grade_curric, co_tipo_curso, ano, turno, carga_horaria_sem, tp_disciplina, co_disciplina, ds_disciplina, nu_ordem_imp, tp_avaliacao, tp_digitacao, tp_impressao, st_reprova)
      values(    trim(p_sg_serie),
                 p_co_tipo_disciplina,
                 p_co_grade_curric,
                 p_co_tipo_curso,
                 p_ano,
                 p_turno,
                 p_carga_horaria_sem,
                 trim(upper(p_tp_disciplina)),
                 trim(upper(p_co_disciplina)),
                 trim(upper(p_ds_disciplina)),
                 p_nu_ordem_imp,
                 p_tp_avaliacao,
                 p_tp_digitacao,
                 p_tp_impressao,
                 p_st_reprova
         );
   Elsif p_operacao = 'A' Then
   -- Altera registro
      update s_disciplina_per set
         sg_serie            = trim(p_sg_serie),
         co_tipo_disciplina  = p_co_tipo_disciplina,
         co_grade_curric     = p_co_grade_curric,
         co_tipo_curso       = p_co_tipo_curso,
         ano                 = p_ano,
         turno               = p_turno,
         carga_horaria_sem   = p_carga_horaria_sem,
         tp_disciplina       = trim(upper(p_tp_disciplina)),
         co_disciplina       = trim(upper(p_co_disciplina)),
         ds_disciplina       = trim(upper(p_ds_disciplina)),
         nu_ordem_imp        = p_nu_ordem_imp,
         tp_avaliacao        = p_tp_avaliacao,
         tp_digitacao        = p_tp_digitacao,
         tp_impressao        = p_tp_impressao,
         st_reprova          = p_st_reprova
      where sg_serie           = p_sg_serie
        and co_tipo_disciplina = p_co_tipo_disciplina
        and co_grade_curric    = p_co_grade_curric;

   Elsif p_operacao = 'E' Then
   -- Exclui registro
     delete s_disciplina_per
       where sg_serie              = p_sg_serie
         and co_grade_curric       = p_co_grade_curric
         and co_tipo_disciplina    = p_co_tipo_disciplina;
   End If;
end SP_PutSDiscPer;
/

