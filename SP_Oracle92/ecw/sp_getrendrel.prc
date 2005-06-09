create or replace procedure SP_GetRendRel
   (p_periodo     in number,
    p_unidade     in number,
    p_modalidade  in number   default null,
    p_serie       in varchar2 default null,
    p_turma       in number   default null,
    p_turno       in varchar2 default null,
    p_bimestre    in varchar2 default null,
    p_result      out sys_refcursor) is
begin
   If p_bimestre = '1' Then
      open p_result for
      select count(distinct(b.co_aluno)) qtd_abaixo, a.co_disciplina,
             c.matriculados, e.frequentes, count(distinct(b.co_turma)) turma,
             f.ds_unidade, g.ds_disciplina, g.ds_ordem_imp,d.ds_curso
        from s_nota        a,
             s_aluno_turma b,
             s_curso       d,
             s_unidade     f,
             s_disciplina  g,
             s_turma       h,
             s_curso_serie i,
             (select count(distinct(x.co_aluno)) matriculados
                from s_aluno_turma      x,
                     s_turma            y,
                     s_curso_serie      k,
                     s_curso            l
               where x.co_turma        = y.co_turma     (+)
                 and x.co_unidade      = y.co_unidade   (+)
                 and x.co_curso        = y.co_curso     (+)
                 and x.co_seq_serie    = y.co_seq_serie (+)
                 and x.co_unidade      = k.co_unidade   (+)
                 and x.co_curso        = k.co_curso     (+)
                 and x.ano_sem         = k.ano_sem      (+)
                 and x.co_seq_serie    = k.co_seq_serie (+)
                 and x.co_curso        = l.co_curso     (+)
                 and x.co_unidade      = l.co_unidade   (+)
                 and x.ano_sem         = l.ano_sem      (+)
                 and x.ano_sem         = y.ano_sem      (+)
                 and ((p_modalidade is null) or (p_modalidade is not null and l.co_tipo_curso = p_modalidade))
                 and ((p_turma is null) or (p_turma is not null and y.co_turma = p_turma))
                 and ((p_turno is null) or (p_turno is not null and trim(y.co_turno) = p_turno))
                 and ((p_serie is null) or (p_serie is not null and k.sg_serie = p_serie))
                 and x.co_unidade      = p_unidade
                 and x.ano_sem         = p_periodo) c,
             (select count(distinct(w.co_aluno)) frequentes
                from s_aluno_turma      w,
                     s_turma            z,
                     s_curso_serie      k,
                     s_curso            l
               where w.co_turma        = z.co_turma     (+)
                 and w.co_unidade      = z.co_unidade   (+)
                 and w.co_curso        = z.co_curso     (+)
                 and w.co_seq_serie    = z.co_seq_serie (+)
                 and w.co_unidade      = k.co_unidade   (+)
                 and w.co_curso        = k.co_curso     (+)
                 and w.ano_sem         = k.ano_sem      (+)
                 and w.co_seq_serie    = k.co_seq_serie (+)
                 and w.co_curso        = l.co_curso     (+)
                 and w.co_unidade      = l.co_unidade   (+)
                 and w.ano_sem         = l.ano_sem      (+)
                 and w.ano_sem         = z.ano_sem      (+)
                 and w.st_movimentacao = 'ALUNO ATIVO'
                 and ((p_modalidade is null) or (p_modalidade is not null and l.co_tipo_curso = p_modalidade))
                 and ((p_turma is null) or (p_turma is not null and z.co_turma = p_turma))
                 and ((p_turno is null) or (p_turno is not null and trim(z.co_turno) = p_turno))
                 and ((p_serie is null) or (p_serie is not null and k.sg_serie = p_serie))
                 and w.co_unidade      = p_unidade
                 and w.ano_sem         = p_periodo) e
        where a.co_unidade      = b.co_unidade     (+)
          and a.ano_sem         = b.ano_sem        (+)
          and a.co_aluno        = b.co_aluno       (+)
          and a.co_aluno_turma  = b.co_aluno_turma (+)
          and a.co_turma        = b.co_turma       (+)
          and a.co_curso        = b.co_curso       (+)
          and a.co_seq_serie    = b.co_seq_serie   (+)
          and a.co_curso        = d.co_curso       (+)
          and a.co_unidade      = d.co_unidade     (+)
          and a.ano_sem         = d.ano_sem        (+)
          and a.co_unidade      = f.co_unidade     (+)
          and a.co_disciplina   = g.co_disciplina  (+)
          and a.co_unidade      = g.co_unidade     (+)
          and a.ano_sem         = g.ano_sem        (+)
          and a.co_unidade      = h.co_unidade     (+)
          and a.co_turma        = h.co_turma       (+)
          and a.co_curso        = h.co_curso       (+)
          and a.co_seq_serie    = h.co_seq_serie   (+)
          and a.ano_sem         = h.ano_sem        (+)
          and a.co_unidade      = i.co_unidade     (+)
          and a.co_curso        = i.co_curso       (+)
          and a.co_seq_serie    = i.co_seq_serie   (+)
          and a.ano_sem         = i.ano_sem        (+)
          and a.nu_nota_b1      < substr(d.nu_media_nota,1,1)
          and b.st_movimentacao = 'ALUNO ATIVO'
          and a.co_unidade = p_unidade
          and a.ano_sem    = p_periodo
          and ((p_modalidade is null) or (p_modalidade is not null and d.co_tipo_curso = p_modalidade))
          and ((p_turma is null) or (p_turma is not null and b.co_turma = p_turma))
          and ((p_turno is null) or (p_turno is not null and trim(h.co_turno) = p_turno))
          and ((p_serie is null) or (p_serie is not null and i.sg_serie = p_serie))
         group by a.co_disciplina, c.matriculados, e.frequentes, f.ds_unidade,
                  g.ds_disciplina, g.ds_ordem_imp, d.ds_curso;
   ElsIf p_bimestre = '2' Then
      open p_result for
      select count(distinct(b.co_aluno)) qtd_abaixo, a.co_disciplina,
             c.matriculados, e.frequentes, count(distinct(b.co_turma)) turma,
             f.ds_unidade, g.ds_disciplina, g.ds_ordem_imp, d.ds_curso
        from s_nota        a,
             s_aluno_turma b,
             s_curso       d,
             s_unidade     f,
             s_disciplina  g,
             s_turma       h,
             s_curso_serie i,
             (select count(distinct(x.co_aluno)) matriculados
                from s_aluno_turma      x,
                     s_turma            y,
                     s_curso_serie      k,
                     s_curso            l
               where x.co_turma        = y.co_turma     (+)
                 and x.co_unidade      = y.co_unidade   (+)
                 and x.co_curso        = y.co_curso     (+)
                 and x.co_seq_serie    = y.co_seq_serie (+)
                 and x.ano_sem         = y.ano_sem      (+)
                 and ((p_modalidade is null) or (p_modalidade is not null and l.co_tipo_curso = p_modalidade))
                 and ((p_turma is null) or (p_turma is not null and y.co_turma = p_turma))
                 and ((p_turno is null) or (p_turno is not null and trim(y.co_turno) = p_turno))
                 and ((p_serie is null) or (p_serie is not null and k.sg_serie = p_serie))
                 and x.co_unidade      = p_unidade
                 and x.ano_sem         = p_periodo) c,
             (select count(distinct(w.co_aluno)) frequentes
                from s_aluno_turma      w,
                     s_turma            z,
                     s_curso_serie      k,
                     s_curso            l
               where w.co_turma        = z.co_turma     (+)
                 and w.co_unidade      = z.co_unidade   (+)
                 and w.co_curso        = z.co_curso     (+)
                 and w.co_seq_serie    = z.co_seq_serie (+)
                 and w.ano_sem         = z.ano_sem      (+)
                 and w.st_movimentacao = 'ALUNO ATIVO'
                 and ((p_modalidade is null) or (p_modalidade is not null and l.co_tipo_curso = p_modalidade))
                 and ((p_turma is null) or (p_turma is not null and z.co_turma = p_turma))
                 and ((p_turno is null) or (p_turno is not null and trim(z.co_turno) = p_turno))
                 and ((p_serie is null) or (p_serie is not null and k.sg_serie = p_serie))
                 and w.co_unidade      = p_unidade
                 and w.ano_sem         = p_periodo) e
        where a.co_unidade      = b.co_unidade     (+)
          and a.ano_sem         = b.ano_sem        (+)
          and a.co_aluno        = b.co_aluno       (+)
          and a.co_aluno_turma  = b.co_aluno_turma (+)
          and a.co_turma        = b.co_turma       (+)
          and a.co_curso        = b.co_curso       (+)
          and a.co_seq_serie    = b.co_seq_serie   (+)
          and a.co_curso        = d.co_curso       (+)
          and a.co_unidade      = d.co_unidade     (+)
          and a.ano_sem         = d.ano_sem        (+)
          and a.co_unidade      = f.co_unidade     (+)
          and a.co_disciplina   = g.co_disciplina  (+)
          and a.co_unidade      = g.co_unidade     (+)
          and a.ano_sem         = g.ano_sem        (+)
          and a.co_unidade      = h.co_unidade     (+)
          and a.co_turma        = h.co_turma       (+)
          and a.co_curso        = h.co_curso       (+)
          and a.co_seq_serie    = h.co_seq_serie   (+)
          and a.ano_sem         = h.ano_sem        (+)
          and a.co_unidade      = i.co_unidade     (+)
          and a.co_curso        = i.co_curso       (+)
          and a.co_seq_serie    = i.co_seq_serie   (+)
          and a.ano_sem         = i.ano_sem        (+)
          and a.nu_nota_b2      < substr(d.nu_media_nota,1,1)
          and b.st_movimentacao = 'ALUNO ATIVO'
          and a.co_unidade = p_unidade
          and a.ano_sem    = p_periodo
          and ((p_modalidade is null) or (p_modalidade is not null and d.co_tipo_curso = p_modalidade))
          and ((p_turma is null) or (p_turma is not null and b.co_turma = p_turma))
          and ((p_turno is null) or (p_turno is not null and h.co_turno = p_turno))
          and ((p_serie is null) or (p_serie is not null and i.sg_serie = p_serie))
         group by a.co_disciplina, c.matriculados, e.frequentes, f.ds_unidade,
                  g.ds_disciplina, g.ds_ordem_imp, d.ds_curso;

   ElsIf p_bimestre = '3' Then
      open p_result for
      select count(distinct(b.co_aluno)) qtd_abaixo, a.co_disciplina,
             c.matriculados, e.frequentes, count(distinct(b.co_turma)) turma,
             f.ds_unidade, g.ds_disciplina, g.ds_ordem_imp, d.ds_curso
        from s_nota        a,
             s_aluno_turma b,
             s_curso       d,
             s_unidade     f,
             s_disciplina  g,
             s_turma       h,
             s_curso_serie i,
             (select count(distinct(x.co_aluno)) matriculados
                from s_aluno_turma      x,
                     s_turma            y,
                     s_curso_serie      k,
                     s_curso            l
               where x.co_turma        = y.co_turma     (+)
                 and x.co_unidade      = y.co_unidade   (+)
                 and x.co_curso        = y.co_curso     (+)
                 and x.co_seq_serie    = y.co_seq_serie (+)
                 and x.ano_sem         = y.ano_sem      (+)
                 and ((p_modalidade is null) or (p_modalidade is not null and l.co_tipo_curso = p_modalidade))
                 and ((p_turma is null) or (p_turma is not null and y.co_turma = p_turma))
                 and ((p_turno is null) or (p_turno is not null and trim(y.co_turno) = p_turno))
                 and ((p_serie is null) or (p_serie is not null and k.sg_serie = p_serie))
                 and x.co_unidade      = p_unidade
                 and x.ano_sem         = p_periodo) c,
             (select count(distinct(w.co_aluno)) frequentes
                from s_aluno_turma      w,
                     s_turma            z,
                     s_curso_serie      k,
                     s_curso            l
               where w.co_turma        = z.co_turma     (+)
                 and w.co_unidade      = z.co_unidade   (+)
                 and w.co_curso        = z.co_curso     (+)
                 and w.co_seq_serie    = z.co_seq_serie (+)
                 and w.ano_sem         = z.ano_sem      (+)
                 and w.st_movimentacao = 'ALUNO ATIVO'
                 and ((p_modalidade is null) or (p_modalidade is not null and l.co_tipo_curso = p_modalidade))
                 and ((p_turma is null) or (p_turma is not null and z.co_turma = p_turma))
                 and ((p_turno is null) or (p_turno is not null and trim(z.co_turno) = p_turno))
                 and ((p_serie is null) or (p_serie is not null and k.sg_serie = p_serie))
                 and w.co_unidade      = p_unidade
                 and w.ano_sem         = p_periodo) e
        where a.co_unidade      = b.co_unidade     (+)
          and a.ano_sem         = b.ano_sem        (+)
          and a.co_aluno        = b.co_aluno       (+)
          and a.co_aluno_turma  = b.co_aluno_turma (+)
          and a.co_turma        = b.co_turma       (+)
          and a.co_curso        = b.co_curso       (+)
          and a.co_seq_serie    = b.co_seq_serie   (+)
          and a.co_curso        = d.co_curso       (+)
          and a.co_unidade      = d.co_unidade     (+)
          and a.ano_sem         = d.ano_sem        (+)
          and a.co_unidade      = f.co_unidade     (+)
          and a.co_disciplina   = g.co_disciplina  (+)
          and a.co_unidade      = g.co_unidade     (+)
          and a.ano_sem         = g.ano_sem        (+)
          and a.co_unidade      = h.co_unidade     (+)
          and a.co_turma        = h.co_turma       (+)
          and a.co_curso        = h.co_curso       (+)
          and a.co_seq_serie    = h.co_seq_serie   (+)
          and a.ano_sem         = h.ano_sem        (+)
          and a.co_unidade      = i.co_unidade     (+)
          and a.co_curso        = i.co_curso       (+)
          and a.co_seq_serie    = i.co_seq_serie   (+)
          and a.ano_sem         = i.ano_sem        (+)
          and a.nu_nota_b3      < substr(d.nu_media_nota,1,1)
          and b.st_movimentacao = 'ALUNO ATIVO'
          and a.co_unidade = p_unidade
          and a.ano_sem    = p_periodo
          and ((p_modalidade is null) or (p_modalidade is not null and d.co_tipo_curso = p_modalidade))
          and ((p_turma is null) or (p_turma is not null and b.co_turma = p_turma))
          and ((p_turno is null) or (p_turno is not null and trim(h.co_turno) = p_turno))
          and ((p_serie is null) or (p_serie is not null and i.sg_serie = p_serie))
         group by a.co_disciplina, c.matriculados, e.frequentes, f.ds_unidade,
                  g.ds_disciplina, g.ds_ordem_imp, d.ds_curso;

   ElsIf p_bimestre = '4' Then
      open p_result for
      select count(distinct(b.co_aluno)) qtd_abaixo, a.co_disciplina,
             c.matriculados, e.frequentes, count(distinct(b.co_turma)) turma,
             f.ds_unidade, g.ds_disciplina, g.ds_ordem_imp, d.ds_curso
        from s_nota        a,
             s_aluno_turma b,
             s_curso       d,
             s_unidade     f,
             s_disciplina  g,
             s_turma       h,
             s_curso_serie i,
             (select count(distinct(x.co_aluno)) matriculados
                from s_aluno_turma      x,
                     s_turma            y,
                     s_curso_serie      k,
                     s_curso            l
               where x.co_turma        = y.co_turma     (+)
                 and x.co_unidade      = y.co_unidade   (+)
                 and x.co_curso        = y.co_curso     (+)
                 and x.co_seq_serie    = y.co_seq_serie (+)
                 and x.ano_sem         = y.ano_sem      (+)
                 and ((p_modalidade is null) or (p_modalidade is not null and l.co_tipo_curso = p_modalidade))
                 and ((p_turma is null) or (p_turma is not null and y.co_turma = p_turma))
                 and ((p_turno is null) or (p_turno is not null and trim(y.co_turno) = p_turno))
                 and ((p_serie is null) or (p_serie is not null and k.sg_serie = p_serie))
                 and x.co_unidade      = p_unidade
                 and x.ano_sem         = p_periodo) c,
             (select count(distinct(w.co_aluno)) frequentes
                from s_aluno_turma      w,
                     s_turma            z,
                     s_curso_serie      k,
                     s_curso            l
               where w.co_turma        = z.co_turma     (+)
                 and w.co_unidade      = z.co_unidade   (+)
                 and w.co_curso        = z.co_curso     (+)
                 and w.co_seq_serie    = z.co_seq_serie (+)
                 and w.ano_sem         = z.ano_sem      (+)
                 and w.st_movimentacao = 'ALUNO ATIVO'
                 and ((p_modalidade is null) or (p_modalidade is not null and l.co_tipo_curso = p_modalidade))
                 and ((p_turma is null) or (p_turma is not null and z.co_turma = p_turma))
                 and ((p_turno is null) or (p_turno is not null and trim(z.co_turno) = p_turno))
                 and ((p_serie is null) or (p_serie is not null and k.sg_serie = p_serie))
                 and w.co_unidade      = p_unidade
                 and w.ano_sem         = p_periodo) e
        where a.co_unidade      = b.co_unidade     (+)
          and a.ano_sem         = b.ano_sem        (+)
          and a.co_aluno        = b.co_aluno       (+)
          and a.co_aluno_turma  = b.co_aluno_turma (+)
          and a.co_turma        = b.co_turma       (+)
          and a.co_curso        = b.co_curso       (+)
          and a.co_seq_serie    = b.co_seq_serie   (+)
          and a.co_curso        = d.co_curso       (+)
          and a.co_unidade      = d.co_unidade     (+)
          and a.ano_sem         = d.ano_sem        (+)
          and a.co_unidade      = f.co_unidade     (+)
          and a.co_disciplina   = g.co_disciplina  (+)
          and a.co_unidade      = g.co_unidade     (+)
          and a.ano_sem         = g.ano_sem        (+)
          and a.co_unidade      = h.co_unidade     (+)
          and a.co_turma        = h.co_turma       (+)
          and a.co_curso        = h.co_curso       (+)
          and a.co_seq_serie    = h.co_seq_serie   (+)
          and a.ano_sem         = h.ano_sem        (+)
          and a.co_unidade      = i.co_unidade     (+)
          and a.co_curso        = i.co_curso       (+)
          and a.co_seq_serie    = i.co_seq_serie   (+)
          and a.ano_sem         = i.ano_sem        (+)
          and a.nu_nota_b4      < substr(d.nu_media_nota,1,1)
          and b.st_movimentacao = 'ALUNO ATIVO'
          and a.co_unidade = p_unidade
          and a.ano_sem    = p_periodo
          and ((p_modalidade is null) or (p_modalidade is not null and d.co_tipo_curso = p_modalidade))
          and ((p_turma is null) or (p_turma is not null and b.co_turma = p_turma))
          and ((p_turno is null) or (p_turno is not null and trim(h.co_turno) = p_turno))
          and ((p_serie is null) or (p_serie is not null and i.sg_serie = p_serie))
         group by a.co_disciplina, c.matriculados, e.frequentes, f.ds_unidade,
                  g.ds_disciplina, g.ds_ordem_imp, d.ds_curso;
   ElsIf p_bimestre = 'FINAL' Then
   open p_result for
      select count(distinct(b.co_aluno)) qtd_abaixo, a.co_disciplina,
             c.matriculados, e.frequentes, count(distinct(b.co_turma)) turma,
             f.ds_unidade, g.ds_disciplina, g.ds_ordem_imp
        from s_nota        a,
             s_aluno_turma b,
             s_curso       d,
             s_unidade     f,
             s_disciplina  g,
             s_turma       h,
             s_curso_serie i,
             (select count(distinct(x.co_aluno)) matriculados
                from s_aluno_turma      x,
                     s_turma            y,
                     s_curso_serie      k,
                     s_curso            l
               where x.co_turma        = y.co_turma     (+)
                 and x.co_unidade      = y.co_unidade   (+)
                 and x.co_curso        = y.co_curso     (+)
                 and x.co_seq_serie    = y.co_seq_serie (+)
                 and x.ano_sem         = y.ano_sem      (+)
                 and ((p_modalidade is null) or (p_modalidade is not null and l.co_tipo_curso = p_modalidade))
                 and ((p_turma is null) or (p_turma is not null and y.co_turma = p_turma))
                 and ((p_turno is null) or (p_turno is not null and trim(y.co_turno) = p_turno))
                 and ((p_serie is null) or (p_serie is not null and k.sg_serie = p_serie))
                 and x.co_unidade      = p_unidade
                 and x.ano_sem         = p_periodo) c,
             (select count(distinct(w.co_aluno)) frequentes
                from s_aluno_turma      w,
                     s_turma            z,
                     s_curso_serie      k,
                     s_curso            l
               where w.co_turma        = z.co_turma     (+)
                 and w.co_unidade      = z.co_unidade   (+)
                 and w.co_curso        = z.co_curso     (+)
                 and w.co_seq_serie    = z.co_seq_serie (+)
                 and w.ano_sem         = z.ano_sem      (+)
                 and w.st_movimentacao = 'ALUNO ATIVO'
                 and ((p_modalidade is null) or (p_modalidade is not null and l.co_tipo_curso = p_modalidade))
                 and ((p_turma is null) or (p_turma is not null and z.co_turma = p_turma))
                 and ((p_turno is null) or (p_turno is not null and trim(z.co_turno) = p_turno))
                 and ((p_serie is null) or (p_serie is not null and k.sg_serie = p_serie))
                 and w.co_unidade      = p_unidade
                 and w.ano_sem         = p_periodo) e
        where a.co_unidade      = b.co_unidade     (+)
          and a.ano_sem         = b.ano_sem        (+)
          and a.co_aluno        = b.co_aluno       (+)
          and a.co_aluno_turma  = b.co_aluno_turma (+)
          and a.co_turma        = b.co_turma       (+)
          and a.co_curso        = b.co_curso       (+)
          and a.co_seq_serie    = b.co_seq_serie   (+)
          and a.co_curso        = d.co_curso       (+)
          and a.co_unidade      = d.co_unidade     (+)
          and a.ano_sem         = d.ano_sem        (+)
          and a.co_unidade      = f.co_unidade     (+)
          and a.co_disciplina   = g.co_disciplina  (+)
          and a.co_unidade      = g.co_unidade     (+)
          and a.ano_sem         = g.ano_sem        (+)
          and a.co_unidade      = h.co_unidade     (+)
          and a.co_turma        = h.co_turma       (+)
          and a.co_curso        = h.co_curso       (+)
          and a.co_seq_serie    = h.co_seq_serie   (+)
          and a.ano_sem         = h.ano_sem        (+)
          and a.co_unidade      = i.co_unidade     (+)
          and a.co_curso        = i.co_curso       (+)
          and a.co_seq_serie    = i.co_seq_serie   (+)
          and a.ano_sem         = i.ano_sem        (+)
          and a.nu_media_final  < substr(a.nu_media_anual,1,1)
          and b.st_movimentacao = 'ALUNO ATIVO'
          and a.co_unidade = p_unidade
          and a.ano_sem    = p_periodo
          and ((p_modalidade is null) or (p_modalidade is not null and d.co_tipo_curso = p_modalidade))
          and ((p_turma is null) or (p_turma is not null and b.co_turma = p_turma))
          and ((p_turno is null) or (p_turno is not null and trim(h.co_turno) = p_turno))
          and ((p_serie is null) or (p_serie is not null and i.sg_serie = p_serie))
         group by a.co_disciplina, c.matriculados, e.frequentes, f.ds_unidade,
                  g.ds_disciplina, g.ds_ordem_imp, d.ds_curso;

   End If;
end SP_GetRendRel;
/

