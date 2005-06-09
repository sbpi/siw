create or replace procedure SP_GetRoomClList
   (p_periodo    in number,
    p_regional   in varchar2 default null,
    p_unidade    in number   default null,
    p_modalidade in number   default null,
    p_turno      in varchar2 default null,
    p_serie      in varchar2 default null,
    p_turma      in number   default null,
    p_ambiente   in number   default null,
    p_tipo_sala  in number   default null,
    p_result     out sys_refcursor) is
begin
   If p_regional = 0 Then
   open p_result for
      select a.co_letra_turma, a.co_bloco, a.co_turno, a.co_turma,
             d.ds_sala, ds_tipo_sala, ds_ambiente, nu_metragem, nu_alunos_sala,
             h.sg_serie, i.aluno_matric, j.aluno_ativo, ds_escola, d.co_sala, f.co_seq_ambiente,
             e.co_tipo_sala, c.co_curso, a.co_unidade, a.co_turma, l.co_tipo_curso,
             m.co_unidade total_uni, m.co_sala total_sala,substr(b.co_sigre,1,2) regional, b.ds_gre,
             n.tp_anee, l.ds_tipo_curso, a.st_turma_definitiv, a.ds_turma, l.sg_tipo_curso
        from s_turma         a,      s_escola        b,     s_curso         c,
             s_sala          d,      s_tipo_sala     e,     s_ambiente      f,
             s_curso_serie   g,      s_serie         h,     s_tipo_curso    l,
            (select co_unidade, co_turma, count(*) aluno_matric
             from   s_aluno_turma
             where  ano_sem = p_periodo
             having count(*) > 1
             group  by co_unidade, co_turma) i,
            (select co_unidade, co_turma, count(*) aluno_ativo
             from   s_aluno_turma
             where  ano_sem = p_periodo and st_movimentacao = rpad('ALUNO ATIVO', 25, ' ')
             having count(*) > 1
             group  by co_unidade, co_turma) j,
            (select co_unidade, count(*) co_sala
             from   s_turma
             where  ano_sem = p_periodo
             having count(*) > 1
             group  by co_unidade) m,
            (select a.co_turma, a.co_unidade, count(*) tp_anee,
                    a.ano_sem
               from s_aluno_turma a,
                    s_aluno       b
              where a.ano_sem    = p_periodo
                and b.tp_anee    is not null
                and a.co_aluno   = b.co_aluno   (+)
                and a.co_unidade = b.co_unidade (+)
             having count(*) > 1
              group by a.co_unidade, a.co_turma, a.ano_sem) n
       where a.co_unidade      = b.co_unidade       (+)
         and a.co_curso        = c.co_curso         (+) and a.co_unidade = c.co_unidade(+) and a.ano_sem = c.ano_sem(+)
         and a.co_unidade      = d.co_unidade       (+) and a.co_bloco   = d.co_bloco  (+) and a.co_sala = d.co_sala(+)
         and d.co_tipo_sala    = e.co_tipo_sala     (+)
         and d.co_seq_ambiente = f.co_seq_ambiente  (+)
         and a.co_seq_serie    = g.co_seq_serie     (+) and a.co_curso   = g.co_curso  (+) and a.ano_sem = g.ano_sem(+) and a.co_unidade = g.co_unidade(+)
         and g.sg_serie        = h.sg_serie         (+)
         and a.co_unidade      = i.co_unidade       (+) and a.co_turma   = i.co_turma  (+)
         and a.co_unidade      = j.co_unidade       (+) and a.co_turma   = j.co_turma  (+)
         and c.co_tipo_curso   = l.co_tipo_curso    (+)
         and a.co_unidade      = m.co_unidade       (+)
         and a.co_unidade      = n.co_unidade       (+) and a.co_turma   = n.co_turma  (+)
         and (p_unidade        is null or (p_unidade    is not null and a.co_unidade      = p_unidade))
         and (p_modalidade     is null or (p_modalidade is not null and l.co_tipo_curso   = p_modalidade))
         and (p_turno          is null or (p_turno      is not null and a.co_turno        = p_turno))
         and (p_serie          is null or (p_serie      is not null and g.sg_serie        = p_serie))
         and (p_turma          is null or (p_turma      is not null and a.co_turma        = p_turma))
         and (p_ambiente       is null or (p_ambiente   is not null and f.co_seq_ambiente = p_ambiente))
         and (p_tipo_sala      is null or (p_tipo_sala  is not null and e.co_tipo_sala    = p_tipo_sala))
         and a.ano_sem         = p_periodo;
   Else
   open p_result for
      select a.co_letra_turma, a.co_bloco, a.co_turno, a.co_turma,
             d.ds_sala, ds_tipo_sala, ds_ambiente, nu_metragem, nu_alunos_sala,
             h.sg_serie, i.aluno_matric, j.aluno_ativo, ds_escola, d.co_sala, f.co_seq_ambiente,
             e.co_tipo_sala, c.co_curso, a.co_unidade, a.co_turma, l.co_tipo_curso,
             m.co_unidade total_uni, m.co_sala total_sala,substr(b.co_sigre,1,2) regional, b.ds_gre,
             n.tp_anee, l.ds_tipo_curso, a.st_turma_definitiv, a.ds_turma, l.sg_tipo_curso
        from s_turma         a,      s_escola        b,     s_curso         c,
             s_sala          d,      s_tipo_sala     e,     s_ambiente      f,
             s_curso_serie   g,      s_serie         h,     s_tipo_curso    l,
            (select co_unidade, co_turma, count(*) aluno_matric
             from   s_aluno_turma
             where  ano_sem = p_periodo
             having count(*) > 1
             group  by co_unidade, co_turma) i,
            (select co_unidade, co_turma, count(*) aluno_ativo
             from   s_aluno_turma
             where  ano_sem = p_periodo and st_movimentacao = rpad('ALUNO ATIVO', 25, ' ')
             having count(*) > 1
             group  by co_unidade, co_turma) j,
            (select co_unidade, count(*) co_sala
             from   s_turma
             where  ano_sem = p_periodo
             having count(*) > 1
             group  by co_unidade) m,
            (select a.co_turma, a.co_unidade, count(*) tp_anee,
                    a.ano_sem
               from s_aluno_turma a,
                    s_aluno       b
              where a.ano_sem    = p_periodo
                and b.tp_anee    is not null
                and a.co_aluno   = b.co_aluno   (+)
                and a.co_unidade = b.co_unidade (+)
             having count(*) > 1
              group by a.co_unidade, a.co_turma, a.ano_sem) n
       where a.co_unidade      = b.co_unidade       (+)
         and a.co_curso        = c.co_curso         (+) and a.co_unidade = c.co_unidade(+) and a.ano_sem = c.ano_sem(+)
         and a.co_unidade      = d.co_unidade       (+) and a.co_bloco   = d.co_bloco  (+) and a.co_sala = d.co_sala(+)
         and d.co_tipo_sala    = e.co_tipo_sala     (+)
         and d.co_seq_ambiente = f.co_seq_ambiente  (+)
         and a.co_seq_serie    = g.co_seq_serie     (+) and a.co_curso   = g.co_curso  (+) and a.ano_sem = g.ano_sem(+) and a.co_unidade = g.co_unidade(+)
         and g.sg_serie        = h.sg_serie         (+)
         and a.co_unidade      = i.co_unidade       (+) and a.co_turma   = i.co_turma  (+)
         and a.co_unidade      = j.co_unidade       (+) and a.co_turma   = j.co_turma  (+)
         and c.co_tipo_curso   = l.co_tipo_curso    (+)
         and a.co_unidade      = m.co_unidade       (+)
         and a.co_unidade      = n.co_unidade       (+) and a.co_turma   = n.co_turma  (+)
         and (p_unidade        is null or (p_unidade    is not null and a.co_unidade    = p_unidade))
         and (p_modalidade     is null or (p_modalidade is not null and l.co_tipo_curso = p_modalidade))
         and (p_turno          is null or (p_turno      is not null and a.co_turno      = p_turno))
         and (p_serie          is null or (p_serie      is not null and g.sg_serie      = p_serie))
         and (p_turma          is null or (p_turma      is not null and a.co_turma      = p_turma))
         and (p_ambiente       is null or (p_ambiente   is not null and f.co_seq_ambiente = p_ambiente))
         and (p_tipo_sala      is null or (p_tipo_sala  is not null and e.co_tipo_sala    = p_tipo_sala))
         and a.ano_sem         = p_periodo
         and b.co_sigre      like p_regional||'%';
   End If;
end SP_GetRoomClList;
/

