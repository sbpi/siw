create or replace procedure SP_GetProfRel
   (p_periodo      in number,
    p_regional     in varchar2 default null,
    p_modalidade   in number default null,
    p_serie        in varchar2 default null,
    p_turma        in number default null,
    p_disciplina   in number default null,
    p_turno        in varchar2 default null,
    p_bairro       in varchar2 default null,
    p_tipo         in varchar2 default null,
    p_unidade      in number   default null,
    p_escolaridade in varchar2 default null,
    p_cargo        in varchar2 default null,
    p_sexo         in varchar2 default null,
    p_mat_ini      in date     default null,
    p_mat_fim      in date     default null, 
    p_result      out sys_refcursor) is
begin
   If p_tipo = 'N' Then
      ---Recupera os professores que com e sem turma
       If p_regional = '00' Then
          -- Recupera os professores por período
          open p_result for
             select distinct(a.co_funcionario), a.nu_matricula_mec, a.ds_funcionario,
                    a.ds_instrucao, c.ds_cargo, d.ds_area_atuacao,
                    trim(a.ds_bairro) bairro_func, e.ds_bairro bairro_unidade, d.co_area_atuacao,
                    e.co_unidade, e.ds_unidade, a.dt_nascimento, a.tp_sexo, b.dt_admissao,
                    substr(f.co_sigre,1,2) regional, f.ds_gre, c.co_cargo, b.id_professor,
                    a.co_unidade unidade
               from s_funcionario      a, s_unidadefunc      b, s_cargo         c,
                    s_area_atuacao     d, s_unidade          e, s_escola        f,
                    s_funcionario_turm g, s_disciplina       h, s_turma         i,
                    s_curso            j, s_curso_serie      l
              where a.co_funcionario  = b.co_funcionario (+) and a.co_unidade = b.co_unidade (+)
                and a.co_unidade      = e.co_unidade     (+)
                and b.co_cargo        = c.co_cargo       (+)
                and b.co_area_atuacao = d.co_area_atuacao(+)
                and a.co_unidade      = f.co_unidade     (+)
                and a.co_funcionario  = g.co_funcionario (+) and a.co_unidade = g.co_unidade (+)
                and g.co_disciplina   = h.co_disciplina  (+) and g.co_unidade = h.co_unidade (+)
                and g.ano_sem         = h.ano_sem        (+)
                and g.co_turma        = i.co_turma       (+) and g.co_unidade = i.co_unidade (+) and g.co_curso     = i.co_curso     (+) and g.ano_sem = i.ano_sem (+)
                and g.co_curso        = j.co_curso       (+) and g.co_unidade = j.co_unidade (+) and g.ano_sem      = j.ano_sem      (+)
                and g.co_curso        = l.co_curso       (+) and g.co_unidade = l.co_unidade (+) and g.co_seq_serie = l.co_seq_serie (+)
                and b.id_professor    = 'S'
                and (p_modalidade   is null or (p_modalidade   is not null and j.co_tipo_curso      = p_modalidade))
                and (p_serie        is null or (p_serie        is not null and l.sg_serie           = p_serie))
                and (p_turma        is null or (p_turma        is not null and i.co_turma           = p_turma))
                and (p_disciplina   is null or (p_disciplina   is not null and h.co_tipo_disciplina = p_disciplina))
                and (p_turno        is null or (p_turno        is not null and trim(i.co_turno)     = trim(p_turno)))
                and (p_unidade      is null or (p_unidade      is not null and a.co_unidade         = p_unidade))
                and (p_escolaridade is null or (p_escolaridade is not null and a.ds_instrucao       = p_escolaridade))
                and (p_cargo        is null or (p_cargo        is not null and c.co_cargo           = p_cargo))
                and (p_sexo         is null or (p_sexo         is not null and a.tp_sexo            = p_sexo))
                and (p_mat_ini      is null or (p_mat_ini      is not null and b.dt_admissao        between p_mat_ini and p_mat_fim))
                and (p_bairro       is null or
                     (p_bairro = 'S' and a.ds_bairro = e.ds_bairro) or
                     (p_bairro = 'N' and a.ds_bairro <> e.ds_bairro)
                    )
                and b.ano_sem         = p_periodo;
       Else
          -- Recupera os professores por período e regional
          open p_result for
             select distinct(a.co_funcionario), a.nu_matricula_mec, a.ds_funcionario,
                    a.ds_instrucao, c.ds_cargo, d.ds_area_atuacao,
                    trim(a.ds_bairro) bairro_func, e.ds_bairro bairro_unidade, d.co_area_atuacao,
                    e.co_unidade, e.ds_unidade, a.dt_nascimento, a.tp_sexo, b.dt_admissao,
                    substr(f.co_sigre,1,2) regional, f.ds_gre, c.co_cargo, b.id_professor,
                    a.co_unidade unidade
               from s_funcionario      a, s_unidadefunc      b, s_cargo         c,
                    s_area_atuacao     d, s_unidade          e, s_escola        f,
                    s_funcionario_turm g, s_disciplina       h, s_turma         i,
                    s_curso            j, s_curso_serie      l
              where a.co_funcionario  = b.co_funcionario (+) and a.co_unidade = b.co_unidade (+)
                and a.co_unidade      = e.co_unidade     (+)
                and b.co_cargo        = c.co_cargo       (+)
                and b.co_area_atuacao = d.co_area_atuacao(+)
                and a.co_unidade      = f.co_unidade     (+)
                and a.co_funcionario  = g.co_funcionario (+) and a.co_unidade = g.co_unidade (+)
                and g.co_disciplina   = h.co_disciplina  (+) and g.co_unidade = h.co_unidade (+)
                and g.ano_sem         = h.ano_sem        (+)
                and g.co_turma        = i.co_turma       (+) and g.co_unidade = i.co_unidade (+) and g.co_curso     = i.co_curso     (+) and g.ano_sem = i.ano_sem (+)
                and g.co_curso        = j.co_curso       (+) and g.co_unidade = j.co_unidade (+) and g.ano_sem      = j.ano_sem      (+)
                and g.co_curso        = l.co_curso       (+) and g.co_unidade = l.co_unidade (+) and g.co_seq_serie = l.co_seq_serie (+)
                and b.id_professor    = 'S'
                and (p_modalidade   is null or (p_modalidade   is not null and j.co_tipo_curso      = p_modalidade))
                and (p_serie        is null or (p_serie        is not null and l.sg_serie           = p_serie))
                and (p_turma        is null or (p_turma        is not null and i.co_turma           = p_turma))
                and (p_disciplina   is null or (p_disciplina   is not null and h.co_tipo_disciplina = p_disciplina))
                and (p_turno        is null or (p_turno        is not null and trim(i.co_turno)     = trim(p_turno)))
                and (p_unidade      is null or (p_unidade      is not null and a.co_unidade         = p_unidade))
                and (p_escolaridade is null or (p_escolaridade is not null and a.ds_instrucao       = p_escolaridade))
                and (p_cargo        is null or (p_cargo        is not null and c.co_cargo           = p_cargo))
                and (p_sexo         is null or (p_sexo         is not null and a.tp_sexo            = p_sexo))
                and (p_mat_ini      is null or (p_mat_ini      is not null and b.dt_admissao        between p_mat_ini and p_mat_fim))
                and ((p_bairro is null) or
                     (p_bairro = 'S' and a.ds_bairro = e.ds_bairro) or
                     (p_bairro = 'N' and a.ds_bairro <> e.ds_bairro)
                    )
                and b.ano_sem         = p_periodo
                and f.co_sigre         like p_regional||'%';
       End If;
   Else
      ---Recupera somente os professores com turma
       If p_regional = '00' Then
          -- Recupera os professores por período
          open p_result for
             select distinct(a.co_funcionario), a.nu_matricula_mec, a.ds_funcionario,
                    a.ds_instrucao, c.ds_cargo, d.ds_area_atuacao,
                    trim(a.ds_bairro) bairro_func, e.ds_bairro bairro_unidade, d.co_area_atuacao,
                    e.co_unidade, e.ds_unidade, a.dt_nascimento, a.tp_sexo, b.dt_admissao,
                    substr(f.co_sigre,1,2) regional, f.ds_gre, c.co_cargo, b.id_professor,
                    a.co_unidade unidade
               from s_funcionario      a, s_unidadefunc      b, s_cargo         c,
                    s_area_atuacao     d, s_unidade          e, s_escola        f,
                    s_funcionario_turm g, s_disciplina       h, s_turma         i,
                    s_curso            j, s_curso_serie      l
               where a.co_funcionario  = b.co_funcionario(+) and a.co_unidade = b.co_unidade (+)
                and a.co_unidade      = e.co_unidade     (+)
                and b.co_cargo        = c.co_cargo       (+)
                and b.co_area_atuacao = d.co_area_atuacao(+)
                and a.co_unidade      = f.co_unidade     (+)
                and a.co_funcionario  = g.co_funcionario     and a.co_unidade = g.co_unidade
                and g.co_disciplina   = h.co_disciplina  (+) and g.co_unidade = h.co_unidade (+)
                and g.ano_sem         = h.ano_sem        (+)
                and g.co_turma        = i.co_turma       (+) and g.co_unidade = i.co_unidade (+) and g.co_curso     = i.co_curso     (+) and g.ano_sem = i.ano_sem (+)
                and g.co_curso        = j.co_curso       (+) and g.co_unidade = j.co_unidade (+) and g.ano_sem      = j.ano_sem      (+)
                and g.co_curso        = l.co_curso       (+) and g.co_unidade = l.co_unidade (+) and g.co_seq_serie = l.co_seq_serie (+)
                and b.id_professor    = 'S'
                and (p_modalidade   is null or (p_modalidade   is not null and j.co_tipo_curso      = p_modalidade))
                and (p_serie        is null or (p_serie        is not null and l.sg_serie           = p_serie))
                and (p_turma        is null or (p_turma        is not null and i.co_turma           = p_turma))
                and (p_disciplina   is null or (p_disciplina   is not null and h.co_tipo_disciplina = p_disciplina))
                and (p_turno        is null or (p_turno        is not null and trim(i.co_turno)     = trim(p_turno)))
                and (p_unidade      is null or (p_unidade      is not null and a.co_unidade         = p_unidade))
                and (p_escolaridade is null or (p_escolaridade is not null and a.ds_instrucao       = p_escolaridade))
                and (p_cargo        is null or (p_cargo        is not null and c.co_cargo           = p_cargo))
                and (p_sexo         is null or (p_sexo         is not null and a.tp_sexo            = p_sexo))
                and (p_mat_ini      is null or (p_mat_ini      is not null and b.dt_admissao        between p_mat_ini and p_mat_fim))
                and ((p_bairro is null) or
                     (p_bairro = 'S' and a.ds_bairro = e.ds_bairro) or
                     (p_bairro = 'N' and a.ds_bairro <> e.ds_bairro)
                    )
                and b.ano_sem         = p_periodo;
       Else
          -- Recupera os professores por período e regional
          open p_result for
             select distinct(a.co_funcionario), a.nu_matricula_mec, a.ds_funcionario,
                    a.ds_instrucao, c.ds_cargo, d.ds_area_atuacao,
                    trim(a.ds_bairro) bairro_func, e.ds_bairro bairro_unidade, d.co_area_atuacao,
                    e.co_unidade, e.ds_unidade, a.dt_nascimento, a.tp_sexo, b.dt_admissao,
                    substr(f.co_sigre,1,2) regional, f.ds_gre, c.co_cargo, b.id_professor,
                    a.co_unidade unidade
               from s_funcionario      a, s_unidadefunc      b, s_cargo         c,
                    s_area_atuacao     d, s_unidade          e, s_escola        f,
                    s_funcionario_turm g, s_disciplina       h, s_turma         i,
                    s_curso            j, s_curso_serie      l
              where a.co_funcionario  = b.co_funcionario(+) and a.co_unidade = b.co_unidade (+)
                and a.co_unidade      = e.co_unidade     (+)
                and b.co_cargo        = c.co_cargo       (+)
                and b.co_area_atuacao = d.co_area_atuacao(+)
                and a.co_unidade      = f.co_unidade     (+)
                and a.co_funcionario  = g.co_funcionario     and a.co_unidade = g.co_unidade
                and g.co_disciplina   = h.co_disciplina  (+) and g.co_unidade = h.co_unidade (+)
                and g.ano_sem         = h.ano_sem        (+)
                and g.co_turma        = i.co_turma       (+) and g.co_unidade = i.co_unidade (+) and g.co_curso     = i.co_curso     (+) and g.ano_sem = i.ano_sem (+)
                and g.co_curso        = j.co_curso       (+) and g.co_unidade = j.co_unidade (+) and g.ano_sem      = j.ano_sem      (+)
                and g.co_curso        = l.co_curso       (+) and g.co_unidade = l.co_unidade (+) and g.co_seq_serie = l.co_seq_serie (+)
                and b.id_professor    = 'S'
                and (p_modalidade   is null or (p_modalidade   is not null and j.co_tipo_curso      = p_modalidade))
                and (p_serie        is null or (p_serie        is not null and l.sg_serie           = p_serie))
                and (p_turma        is null or (p_turma        is not null and i.co_turma           = p_turma))
                and (p_disciplina   is null or (p_disciplina   is not null and h.co_tipo_disciplina = p_disciplina))
                and (p_turno        is null or (p_turno        is not null and trim(i.co_turno)     = trim(p_turno)))
                and (p_unidade      is null or (p_unidade      is not null and a.co_unidade         = p_unidade))
                and (p_escolaridade is null or (p_escolaridade is not null and a.ds_instrucao       = p_escolaridade))
                and (p_cargo        is null or (p_cargo        is not null and c.co_cargo           = p_cargo))
                and (p_sexo         is null or (p_sexo         is not null and a.tp_sexo            = p_sexo))
                and (p_mat_ini      is null or (p_mat_ini      is not null and b.dt_admissao        between p_mat_ini and p_mat_fim))
                and ((p_bairro is null) or
                     (p_bairro = 'S' and a.ds_bairro = e.ds_bairro) or
                     (p_bairro = 'N' and a.ds_bairro <> e.ds_bairro)
                    )
                and b.ano_sem         = p_periodo
                and f.co_sigre         like p_regional||'%';
       End If;
   End If;
end SP_GetProfRel;
/

