create or replace procedure SP_GetFaltasRel
   (p_periodo      in number,
    p_unidade      in number,
    p_modalidade   in number   default null,
    p_serie        in varchar2 default null,
    p_result       out sys_refcursor) is
begin
   open p_result for
      select sum(a.nu_faltas_b1) b1, sum(a.nu_faltas_b2) b2, sum(a.nu_faltas_b3) b3, sum( a.nu_faltas_b4) b4,
             sum(a.nu_faltas_b1 + a.nu_faltas_b2 + a.nu_faltas_b3 + a.nu_faltas_b4) total_faltas,
             sum(trim(g.nu_aulas_dadas_b1)) dadas1, sum(trim(g.nu_aulas_dadas_b2)) dadas2,
             sum(trim(g.nu_aulas_dadas_b3)) dadas3, sum(trim(g.nu_aulas_dadas_b4)) dadas4,
             sum((trim(g.nu_aulas_dadas_b1) + trim(g.nu_aulas_dadas_b2) + trim(g.nu_aulas_dadas_b3) + trim(g.nu_aulas_dadas_b4))) total_aulas_dadas,
             b.ds_disciplina, b.co_disciplina, count(distinct a.co_aluno) total_alunos,
             d.sg_serie, c.ds_escola, f.ds_tipo_curso, f.co_tipo_curso
        from s_nota             a,
             s_disciplina       b,
             s_curso_serie      d,
             s_escola           c,
             s_curso            e,
             s_tipo_curso       f,
             s_aula_dada        g
       where a.co_unidade = p_unidade
         and a.ano_sem    = p_periodo
         and a.co_disciplina = b.co_disciplina    (+)
         and a.co_unidade    = b.co_unidade       (+)
         and a.ano_sem       = b.ano_sem          (+)
         and a.co_seq_serie  = d.co_seq_serie     (+)
         and a.co_curso      = d.co_curso         (+)
         and a.co_unidade    = d.co_unidade       (+)
         and a.ano_sem       = d.ano_sem          (+)
         and a.co_unidade    = c.co_unidade       (+)
         and d.co_curso      = e.co_curso         (+)
         and d.co_unidade    = e.co_unidade       (+)
         and d.ano_sem       = e.ano_sem          (+)
         and e.co_tipo_curso = f.co_tipo_curso    (+)
         and a.co_unidade    = g.co_unidade       (+)
         and a.co_disciplina = g.co_disciplina    (+)
         and a.co_turma      = g.co_turma         (+)
         and a.ano_sem       = g.ano_sem          (+)
         and a.co_seq_serie  = g.co_seq_serie     (+)
         and (p_modalidade is null or (p_modalidade is not null and f.co_tipo_curso = p_modalidade))
         and (p_serie      is null or (p_serie      is not null and d.sg_serie      = p_serie))
    group by d.sg_serie, b.ds_disciplina, b.co_disciplina, c.ds_escola, f.ds_tipo_curso, f.co_tipo_curso;
end SP_GetFaltasRel;
/

