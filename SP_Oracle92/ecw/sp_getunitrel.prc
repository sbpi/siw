create or replace procedure SP_GetUnitRel
   (p_periodo      in number,
    p_regional     in varchar2 default null,
    p_modalidade   in number default null,
    p_dif          in varchar2 default null,
    p_result       out sys_refcursor) is
begin
   If p_regional = 0 Then
      -- Recupera as unidades por período
         If p_modalidade = 0 Then
         -- Recupera as unidades sem modalidade de ensino
         open p_result for
            select distinct a.co_unidade, a.ds_unidade, a.dt_atualizacao, a.nu_alunosativos,
                   a.nu_matriculados, a.nu_ativos,b.ds_gre, substr(b.co_sigre,1,2) regional,
                   b.co_sigre, c.total_unidades, h.ano_sem, b.ds_escola, l.ds_versao, a.nu_alunoseja1,
                   a.nu_alunoseja2, a.nu_semturma
              from s_unidade a,
                   s_escola  b,
                   (select distinct e.regional, count(*) total_unidades
                     from s_unidade d,
                          (select distinct substr(co_sigre,1,2) regional, co_unidade from s_escola) e,
                          s_periodounidade i
                   where d.co_unidade  = e.co_unidade (+)
                     and d.co_unidade  = i.co_unidade (+)
                     and i.ano_sem (+) = p_periodo
                   having count(*) > 1
                   group  by e.regional) c,
                   s_periodounidade h,
                   s_versao         l
             where a.co_unidade          = b.co_unidade    (+)
               and substr(b.co_sigre,1,2)= c.regional      (+)
               and a.co_unidade          = h.co_unidade    (+)
               and a.co_unidade          = l.co_unidade    (+)
               and ((p_dif = 'N') or (p_dif = 'S' and Nvl(a.nu_alunosativos,0) <> Nvl(a.nu_ativos,0)))
               and h.ano_sem        (+)  = p_periodo;
         Else
         -- Recupera as unidades com modalidade de ensino
            open p_result for
           select distinct a.co_unidade, a.ds_unidade, a.dt_atualizacao, a.nu_alunosativos,
                   a.nu_matriculados, a.nu_ativos,b.ds_gre, substr(b.co_sigre,1,2) regional,
                   b.co_sigre, c.total_unidades, h.ano_sem, g.co_tipo_curso, j.ds_curso,
                   b.ds_escola, l.ds_versao, a.nu_alunoseja1,
                   a.nu_alunoseja2, a.nu_semturma
              from s_unidade a,
                   s_escola  b,
                   (select distinct e.regional, count(distinct(y.co_unidade)) total_unidades
                     from s_unidade d,
                          (select distinct substr(co_sigre,1,2) regional, co_unidade from s_escola) e,
                          s_periodounidade i,
                          s_tipo_curso     x,
                          s_curso          y
                   where d.co_unidade    = e.co_unidade   (+)
                     and d.co_unidade    = i.co_unidade   (+)
                     and i.ano_sem   (+) = p_periodo
                     and i.co_unidade    = y.co_unidade   (+)
                     and i.ano_sem       = y.ano_sem      (+)
                     and y.co_tipo_curso = x.co_tipo_curso(+)
                     and x.co_tipo_curso = p_modalidade
                   having count(*) > 1
                   group  by e.regional) c,
                   s_tipo_curso     g,
                   s_periodounidade h,
                   s_curso          j,
                   s_versao         l
             where a.co_unidade          = b.co_unidade    (+)
               and substr(b.co_sigre,1,2)= c.regional      (+)
               and a.co_unidade          = h.co_unidade    (+)
               and h.ano_sem         (+) = p_periodo
               and h.co_unidade          = j.co_unidade    (+)
               and h.ano_sem             = j.ano_sem       (+)
               and j.co_tipo_curso       = g.co_tipo_curso (+)
               and a.co_unidade          = l.co_unidade
               and ((p_dif = 'N') or (p_dif = 'S' and Nvl(a.nu_alunosativos,0) <> Nvl(a.nu_ativos,0)))
               and g.co_tipo_curso       = p_modalidade;
         End If;
   Else
      -- Recupera os alunos por período e regional
         If p_modalidade = 0 Then
         -- Recupera as unidades sem modalidade de ensino
            open p_result for
           select distinct a.co_unidade, a.ds_unidade, a.dt_atualizacao, a.nu_alunosativos,
                   a.nu_matriculados, a.nu_ativos,b.ds_gre, substr(b.co_sigre,1,2) regional,
                   b.co_sigre, c.total_unidades, h.ano_sem, b.ds_escola, l.ds_versao, a.nu_alunoseja1,
                   a.nu_alunoseja2, a.nu_semturma
              from s_unidade a,
                   s_escola  b,
                   (select distinct e.regional, count(*) total_unidades
                     from s_unidade d,
                          (select distinct substr(co_sigre,1,2) regional, co_unidade from s_escola) e,
                          s_periodounidade i
                   where d.co_unidade = e.co_unidade (+)
                     and d.co_unidade = i.co_unidade (+)
                     and i.ano_sem (+)= p_periodo
                   having count(*) > 1
                   group  by e.regional) c,
                   s_periodounidade h,
                   s_versao         l
             where a.co_unidade          = b.co_unidade    (+)
               and substr(b.co_sigre,1,2)= c.regional      (+)
               and a.co_unidade          = h.co_unidade    (+)
               and a.co_unidade          = l.co_unidade    (+)
               and ((p_dif = 'N') or (p_dif = 'S' and Nvl(a.nu_alunosativos,0) <> Nvl(a.nu_ativos,0)))
               and h.ano_sem        (+)  = p_periodo
               and b.co_sigre         like p_regional||'%';
         Else
         -- Recupera as unidades sem modalidade de ensino
            open p_result for
            select distinct a.co_unidade, a.ds_unidade, a.dt_atualizacao, a.nu_alunosativos,
                   a.nu_matriculados, a.nu_ativos,b.ds_gre, substr(b.co_sigre,1,2) regional,
                   b.co_sigre, c.total_unidades, h.ano_sem, g.co_tipo_curso, j.ds_curso,
                   b.ds_escola, l.ds_versao, a.nu_alunoseja1,
                   a.nu_alunoseja2, a.nu_semturma
              from s_unidade a,
                   s_escola  b,
                   (select distinct e.regional, count(distinct(y.co_unidade)) total_unidades
                     from s_unidade d,
                          (select distinct substr(co_sigre,1,2) regional, co_unidade from s_escola) e,
                          s_periodounidade i,
                          s_tipo_curso     x,
                          s_curso          y
                   where d.co_unidade    = e.co_unidade   (+)
                     and d.co_unidade    = i.co_unidade   (+)
                     and i.ano_sem   (+) = p_periodo
                     and i.co_unidade    = y.co_unidade   (+)
                     and i.ano_sem       = y.ano_sem      (+)
                     and y.co_tipo_curso = x.co_tipo_curso(+)
                     and x.co_tipo_curso = p_modalidade
                   having count(*) > 1
                   group  by e.regional) c,
                   s_tipo_curso     g,
                   s_periodounidade h,
                   s_curso          j,
                   s_versao         l
             where a.co_unidade          = b.co_unidade    (+)
               and substr(b.co_sigre,1,2)= c.regional      (+)
               and a.co_unidade          = h.co_unidade    (+)
               and h.ano_sem         (+) = p_periodo
               and h.co_unidade          = j.co_unidade    (+)
               and h.ano_sem             = j.ano_sem       (+)
               and j.co_tipo_curso       = g.co_tipo_curso (+)
               and a.co_unidade          = l.co_unidade
               and ((p_dif = 'N') or (p_dif = 'S' and Nvl(a.nu_alunosativos,0) <> Nvl(a.nu_ativos,0)))
               and g.co_tipo_curso       = p_modalidade
               and b.co_sigre         like p_regional||'%';
         End If;
   End If;
end SP_GetUnitRel;
/

