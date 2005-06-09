create or replace procedure SP_GetStudentRel
   (p_periodo      in number,
    p_regional     in varchar2 default null,
    p_materia      in number   default null,
    p_aluno        in varchar2 default null,
    p_matricula    in char     default null,
    p_unidade      in number   default null,
    p_turma        in number   default null,
    p_serie        in char     default null,
    p_modalidade   in number   default null,
    p_turno        in char     default null,
    p_origem       in number   default null,
    p_situacao     in char     default null,
    p_movimentacao in char     default null,
    p_sexo         in char     default null,
    p_faixa_i      in number   default null,
    p_faixa_f      in number   default null,
    p_mat_i        in date     default null,
    p_mat_f        in date     default null,
    p_nasc_i       in date     default null,
    p_nasc_f       in date     default null,
    p_result       out sys_refcursor) is
begin
If p_materia is null Then
   -- Recupera os alunos por período e regional
   open p_result for
      select a.ano_sem, a.ds_situacao_aluno, a.co_unidade, a.co_aluno, a.dt_matricula,
             b.dt_movimentacao, b.st_movimentacao, b.nu_chamada,
             c.co_turma, c.co_turno, c.co_letra_turma, c.co_bloco, c.ds_turma,
             d.ds_curso,
             e.sg_serie, e.co_seq_serie,
             f.ds_sala,
             h.ds_aluno, h.ds_mae, h.dt_nascimento, trunc(months_between(sysdate,h.dt_nascimento)/12,0) idade,
             h.tp_sexo_aluno,
             i.ds_escola, i.ds_gre, substr(i.co_sigre,1,2) regional,
             j.co_tipo_curso, j.sg_tipo_curso,
             k.co_origem_escola, k.ds_origem_escola
      from s_aluno_per_unid a,    s_aluno_turma    b,   s_turma          c,
           s_curso          d,    s_curso_serie    e,   s_sala           f,
           s_aluno          h,    s_escola         i,   s_tipo_curso     j,
           s_origem_escola  k
      where a.co_aluno         = h.co_aluno
        and c.co_unidade       = i.co_unidade
        and a.ano_sem          = b.ano_sem     and a.co_unidade = b.co_unidade  and a.co_aluno  = b.co_aluno
        and b.co_turma         = c.co_turma    and b.co_unidade = c.co_unidade  and b.ano_sem   = c.ano_sem
        and c.co_curso         = d.co_curso    and c.co_unidade = d.co_unidade  and c.ano_sem   = d.ano_sem
        and c.co_curso         = e.co_curso    and c.co_unidade = e.co_unidade  and c.ano_sem   = e.ano_sem   and c.co_seq_serie = e.co_seq_serie
        and c.co_bloco         = f.co_bloco    and c.co_unidade = f.co_unidade  and c.co_sala   = f.co_sala  
        and c.co_unidade       = i.co_unidade
        and d.co_tipo_curso    = j.co_tipo_curso
        and h.co_origem_escola = k.co_origem_escola (+)
        and a.ano_sem          = p_periodo
        and (p_regional        = 0 or (p_regional > 0 and I.co_sigre like p_regional||'%'))
        and (p_aluno           is null or (p_aluno        is not null and h.ds_aluno          like p_aluno))
        and (p_matricula       is null or (p_matricula    is not null and h.co_aluno          = p_matricula))
        and (p_unidade         is null or (p_unidade      is not null and i.co_unidade        = p_unidade))
        and (p_turma           is null or (p_turma        is not null and c.co_turma          = p_turma))
        and (p_serie           is null or (p_serie        is not null and e.sg_serie          = p_serie))
        and (p_modalidade      is null or (p_modalidade   is not null and j.co_tipo_curso     = p_modalidade))
        and (p_turno           is null or (p_turno        is not null and c.co_turno          = p_turno))
        and (p_origem          is null or (p_origem       is not null and k.co_origem_escola  = p_origem))
        and (p_situacao        is null or (p_situacao     is not null and a.ds_situacao_aluno = p_situacao))
        and (p_movimentacao    is null or (p_movimentacao is not null and b.st_movimentacao   = p_movimentacao))
        and (p_sexo            is null or (p_sexo         is not null and h.tp_sexo_aluno     = p_sexo))
        and (p_faixa_i         is null or (p_faixa_i      is not null and trunc(months_between(sysdate,h.dt_nascimento)/12,0) between p_faixa_i and p_faixa_f))
        and (p_mat_i           is null or (p_mat_i        is not null and a.dt_matricula      between p_mat_i and p_mat_f))
        and (p_nasc_i          is null or (p_nasc_i       is not null and h.dt_nascimento     between p_nasc_i and p_nasc_f));
Else
   -- Recupera os alunos por período e regional
   open p_result for
      select a.ano_sem, a.ds_situacao_aluno, a.co_unidade, a.co_aluno, a.dt_matricula,
             b.dt_movimentacao, b.st_movimentacao, b.nu_chamada,
             c.co_turma, c.co_turno, c.co_letra_turma, c.co_bloco, c.ds_turma,
             d.ds_curso,
             e.sg_serie, e.co_seq_serie,
             f.ds_sala,
             h.ds_aluno, h.ds_mae, h.dt_nascimento, trunc(months_between(sysdate,h.dt_nascimento)/12,0) idade,
             h.tp_sexo_aluno,
             i.ds_escola, i.ds_gre, substr(i.co_sigre,1,2) regional,
             j.co_tipo_curso, j.sg_tipo_curso,
             k.co_origem_escola, k.ds_origem_escola,
             m.co_tipo_disciplina, m.ds_disciplina
      from s_aluno_per_unid a,    s_aluno_turma      b, s_turma          c,
           s_curso          d,    s_curso_serie      e, s_sala           f,
           s_aluno          h,    s_escola           i, s_tipo_curso     j,
           s_origem_escola  k,    s_turma_disciplina l, s_disciplina     m
      where a.co_aluno         = h.co_aluno
        and c.co_unidade       = i.co_unidade
        and a.ano_sem          = b.ano_sem       and a.co_unidade = b.co_unidade  and a.co_aluno  = b.co_aluno
        and b.co_turma         = c.co_turma      and b.co_unidade = c.co_unidade  and b.ano_sem   = c.ano_sem
        and c.co_curso         = d.co_curso      and c.co_unidade = d.co_unidade  and c.ano_sem   = d.ano_sem
        and c.co_curso         = e.co_curso      and c.co_unidade = e.co_unidade  and c.ano_sem   = e.ano_sem   and c.co_seq_serie = e.co_seq_serie
        and c.co_bloco         = f.co_bloco      and c.co_unidade = f.co_unidade  and c.co_sala   = f.co_sala  
        and c.co_unidade       = i.co_unidade
        and d.co_tipo_curso    = j.co_tipo_curso
        and h.co_origem_escola = k.co_origem_escola
        and c.co_unidade       = l.co_unidade    and c.co_turma   = l.co_turma    and c.ano_sem = l.ano_sem     and c.co_curso     = l.co_curso      and c.co_seq_serie = l.co_seq_serie
        and l.co_disciplina    = m.co_disciplina and l.co_unidade = m.co_unidade and l.ano_sem  = m.ano_sem
        and a.ano_sem            = p_periodo
        and m.co_tipo_disciplina = p_materia
        and (p_regional          = 0 or (p_regional > 0 and I.co_sigre like p_regional||'%'))
        and (p_aluno             is null or (p_aluno        is not null and h.ds_aluno          like p_aluno))
        and (p_matricula         is null or (p_matricula    is not null and h.co_aluno          = p_matricula))
        and (p_unidade           is null or (p_unidade      is not null and i.co_unidade        = p_unidade))
        and (p_turma             is null or (p_turma        is not null and c.co_turma          = p_turma))
        and (p_serie             is null or (p_serie        is not null and e.sg_serie          = p_serie))
        and (p_modalidade        is null or (p_modalidade   is not null and j.co_tipo_curso     = p_modalidade))
        and (p_turno             is null or (p_turno        is not null and c.co_turno          = p_turno))
        and (p_origem            is null or (p_origem       is not null and k.co_origem_escola  = p_origem))
        and (p_situacao          is null or (p_situacao     is not null and a.ds_situacao_aluno = p_situacao))
        and (p_movimentacao      is null or (p_movimentacao is not null and b.st_movimentacao   = p_movimentacao))
        and (p_sexo              is null or (p_sexo         is not null and h.tp_sexo_aluno     = p_sexo))
        and (p_faixa_i           is null or (p_faixa_i      is not null and trunc(months_between(sysdate,h.dt_nascimento)/12,0) between p_faixa_i and p_faixa_f))
        and (p_mat_i             is null or (p_mat_i        is not null and a.dt_matricula      between p_mat_i and p_mat_f))
        and (p_nasc_i            is null or (p_nasc_i       is not null and h.dt_nascimento     between p_nasc_i and p_nasc_f));
End If;
end SP_GetStudentRel;
/

