create or replace procedure SP_GetDoubStudData
   (p_periodo    in number,
    p_aluno      in varchar2,
    p_mae        in varchar2,
    p_nascimento in date,
    p_result     out sys_refcursor) is
begin
   -- Recupera os alunos por nome, nome da mãe e data de nascimento
      open p_result for
         select a.ano_sem, b.dt_movimentacao, b.st_movimentacao, c.co_turno,
                c.co_letra_turma, c.co_bloco, c.ds_turma, d.ds_curso, e.sg_serie,
                f.ds_sala, a.co_unidade, a.co_aluno, g.ds_unidade, b.nu_chamada,
                h.ds_aluno, h.ds_mae, h.dt_nascimento, h.tp_sexo_aluno, a.dt_matricula,
                i.ds_escola
         from s_aluno_per_unid a,
              s_aluno_turma    b,
              s_turma          c,
              s_curso          d,
              s_curso_serie    e,
              s_sala           f,
              s_unidade        g,
              s_aluno          h,
              s_escola         i
         where a.co_aluno      = h.co_aluno
           and c.co_unidade    = i.co_unidade    (+)
           and a.ano_sem       = b.ano_sem       (+)
           and a.co_unidade    = b.co_unidade    (+)
           and a.co_aluno      = b.co_aluno      (+)
           and b.co_turma      = c.co_turma      (+)
           and b.co_unidade    = c.co_unidade    (+)
           and b.ano_sem       = c.ano_sem       (+)
           and c.co_curso      = d.co_curso      (+)
           and c.co_unidade    = d.co_unidade    (+)
           and c.ano_sem       = d.ano_sem       (+)
           and c.ano_sem       = e.ano_sem       (+)
           and c.co_unidade    = e.co_unidade    (+)
           and c.co_seq_serie  = e.co_seq_serie  (+)
           and c.co_bloco      = f.co_bloco      (+)
           and c.co_sala       = f.co_sala       (+)
           and c.co_unidade    = f.co_unidade    (+)
           and c.co_unidade    = g.co_unidade    (+)
           and a.ano_sem       = p_periodo
           and h.ds_aluno      = p_aluno
           and h.ds_mae        = p_mae
           and h.dt_nascimento = p_nascimento;
end SP_GetDoubStudData;
/

