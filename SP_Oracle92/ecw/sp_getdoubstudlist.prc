create or replace procedure SP_GetDoubStudList
   (p_periodo  in number,
    p_regional  in varchar2 default null,
    p_tipo      in varchar2 default null,
    p_unidade   in number   default null,
    p_result    out sys_refcursor) is
begin
   If p_tipo = 'MATRICULA' Then
      -- Recupera os alunos por período e regional
      open p_result for
      select w.ds_aluno, w.ds_mae, w.dt_nascimento, w.tp_sexo_aluno, y.ds_escola, y.co_sigre, y.co_unidade,
             w.co_aluno, x1.dt_matricula, x.nu_chamada, x2.sg_serie, x3.co_turno, x3.co_letra_turma
        from s_aluno          w,
             s_aluno_turma    x,
             s_aluno_per_unid x1,
             s_curso_serie    x2,
             s_turma          x3,
             s_escola         y,
            (select a.co_aluno, count(*)
               from s_aluno       a,
                    s_aluno_turma b,
                    s_escola      c
              where a.co_aluno        = b.co_aluno
                and b.co_unidade      = c.co_unidade
                and b.st_movimentacao = rpad('ALUNO ATIVO', 25, ' ')
                and b.ano_sem         = p_periodo
             having count(*)          > 1
             group by a.co_aluno) z
       where w.co_aluno        = x.co_aluno
         and x.co_unidade      = y.co_unidade
         and x.st_movimentacao = rpad('ALUNO ATIVO', 25, ' ')
         and x.ano_sem         = x1.ano_sem
         and x.co_unidade      = x1.co_unidade
         and x.co_aluno        = x1.co_aluno
         and x.ano_sem         = x2.ano_sem
         and x.co_unidade      = x2.co_unidade
         and x.co_seq_serie    = x2.co_seq_serie
         and x.co_curso        = x2.co_curso
         and x.co_unidade      = x3.co_unidade
         and x.co_turma        = x3.co_turma
         and x.ano_sem         = x3.ano_sem
         and x.co_curso        = x3.co_curso
         and x.co_seq_serie    = x3.co_seq_serie
         and w.co_aluno        = z.co_aluno
         and x.ano_sem         = p_periodo
         and (p_regional       = 0 or (p_regional > 0 and y.co_sigre like p_regional||'%'))
         and (p_unidade        is null or (p_unidade is not null and y.co_unidade = p_unidade));
   Else
      -- Recupera os alunos por período
      open p_result for
      select w.ds_aluno, w.ds_mae, w.dt_nascimento, w.tp_sexo_aluno, y.ds_escola, y.co_sigre, y.co_unidade,
             w.co_aluno, x1.dt_matricula, x.nu_chamada, x2.sg_serie, x3.co_turno, x3.co_letra_turma
        from s_aluno          w,
             s_aluno_turma    x,
             s_aluno_per_unid x1,
             s_curso_serie    x2,
             s_turma          x3,
             s_escola         y,
            (select a.ds_aluno, a.ds_mae, a.dt_nascimento, count(*)
               from s_aluno       a,
                    s_aluno_turma b,
                    s_escola      c
              where a.co_aluno        = b.co_aluno
                and b.co_unidade      = c.co_unidade
                and b.st_movimentacao = rpad('ALUNO ATIVO', 25, ' ')
                and b.ano_sem         = p_periodo
             having count(*)          > 1
             group by a.ds_aluno, a.ds_mae, a.dt_nascimento) z
       where w.co_aluno        = x.co_aluno
         and x.co_unidade      = y.co_unidade
         and x.st_movimentacao = rpad('ALUNO ATIVO', 25, ' ')
         and x.ano_sem         = x1.ano_sem
         and x.co_unidade      = x1.co_unidade
         and x.co_aluno        = x1.co_aluno
         and x.ano_sem         = x2.ano_sem
         and x.co_unidade      = x2.co_unidade
         and x.co_seq_serie    = x2.co_seq_serie
         and x.co_curso        = x2.co_curso
         and x.co_unidade      = x3.co_unidade
         and x.co_turma        = x3.co_turma
         and x.ano_sem         = x3.ano_sem
         and x.co_curso        = x3.co_curso
         and x.co_seq_serie    = x3.co_seq_serie
         and w.ds_aluno        = z.ds_aluno
         and w.ds_mae          = z.ds_mae
         and w.dt_nascimento   = z.dt_nascimento
         and x.ano_sem         = p_periodo
         and (p_regional       = 0 or (p_regional > 0 and y.co_sigre like p_regional||'%'))
         and (p_unidade        is null or (p_unidade is not null and y.co_unidade = p_unidade));
   End If;
end SP_GetDoubStudList;
/

