--
-- Creating procedure SP_GETAMBIENTDATA
-- ====================================
--
--
-- Creating procedure SP_GETAMBIENTLIST
-- ====================================
--
--
-- Creating procedure SP_GETANEEREL
-- ================================
--
--
-- Creating procedure SP_GETATUAREADATA
-- ====================================
--
--
-- Creating procedure SP_GETATUAREALIST
-- ====================================
--
--
-- Creating procedure SP_GETAVALDATA
-- =================================
--
--
-- Creating procedure SP_GETAVALLIST
-- =================================
--
--
-- Creating procedure SP_GETCALENDARLIST
-- =====================================
--
--
-- Creating procedure SP_GETCALENDARREL
-- ====================================
--
--
-- Creating procedure SP_GETCOMUNICREL
-- ===================================
--
--
-- Creating procedure SP_GETCOURSETPDATA
-- =====================================
--
--
-- Creating procedure SP_GETCOURSETPLIST
-- =====================================
--
--
-- Creating procedure SP_GETDISCTPDATA
-- ===================================
--
--
-- Creating procedure SP_GETDISCTPLIST
-- ===================================
--
--
-- Creating procedure SP_GETDOUBSTUDDATA
-- =====================================
--
create procedure SP_GetDoubStudData
   (@p_periodo    int,
    @p_aluno      varchar(),
    @p_mae        varchar(),
    @p_nascimento datetime) as
begin
   -- Recupera os alunos por nome, nome da mãe e data de nascimento
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
           and a.ano_sem       = @p_periodo
           and h.ds_aluno      = @p_aluno
           and h.ds_mae        = @p_mae
           and h.dt_nascimento = @p_nascimento
end 


--
-- Creating procedure SP_GETDOUBSTUDLIST
-- =====================================
--
create procedure SP_GetDoubStudList
   (@p_periodo  int,
    @p_regional  varchar() = null,
    @p_tipo      varchar() = null,
    @p_unidade   int   = null) as
begin
   If @p_tipo = 'MATRICULA' Begin
      -- Recupera os alunos por período e regional
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
                and b.ano_sem         = @p_periodo
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
         and x.ano_sem         = @p_periodo
         and (@p_regional       = 0 or (@p_regional > 0 and y.co_sigre like @p_regional+'%'))
         and (@p_unidade        is null or (@p_unidade is not null and y.co_unidade = @p_unidade))
   End Else Begin
      -- Recupera os alunos por período
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
                and b.ano_sem         = @p_periodo
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
         and x.ano_sem         = @p_periodo
         and (@p_regional       = 0 or (@p_regional > 0 and y.co_sigre like @p_regional+'%'))
         and (@p_unidade        is null or (@p_unidade is not null and y.co_unidade = @p_unidade))
   End
end 


--
-- Creating procedure SP_GETFALTASREL
-- ==================================
--
create procedure SP_GetFaltasRel
   (@p_periodo      int,
    @p_unidade      int,
    @p_modalidade   int   = null,
    @p_serie        varchar() = null) as
begin
      select sum(a.nu_faltas_b1) b1, sum(a.nu_faltas_b2) b2, sum(a.nu_faltas_b3) b3, sum( a.nu_faltas_b4) b4,
             sum(a.nu_faltas_b1 + a.nu_faltas_b2 + a.nu_faltas_b3 + a.nu_faltas_b4) total_faltas,
             sum(rtrim(g.nu_aulas_dadas_b1)) dadas1, sum(rtrim(g.nu_aulas_dadas_b2)) dadas2,
             sum(rtrim(g.nu_aulas_dadas_b3)) dadas3, sum(rtrim(g.nu_aulas_dadas_b4)) dadas4,
             sum((rtrim(g.nu_aulas_dadas_b1) + rtrim(g.nu_aulas_dadas_b2) + rtrim(g.nu_aulas_dadas_b3) + rtrim(g.nu_aulas_dadas_b4))) total_aulas_dadas,
             b.ds_disciplina, b.co_disciplina, count(distinct a.co_aluno) total_alunos,
             d.sg_serie, c.ds_escola, f.ds_tipo_curso, f.co_tipo_curso
        from s_nota             a,
             s_disciplina       b,
             s_curso_serie      d,
             s_escola           c,
             s_curso            e,
             s_tipo_curso       f,
             s_aula_dada        g
       where a.co_unidade = @p_unidade
         and a.ano_sem    = @p_periodo
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
         and (@p_modalidade is null or (@p_modalidade is not null and f.co_tipo_curso = @p_modalidade))
         and (@p_serie      is null or (@p_serie      is not null and d.sg_serie      = @p_serie))
    group by d.sg_serie, b.ds_disciplina, b.co_disciplina, c.ds_escola, f.ds_tipo_curso, f.co_tipo_curso
end 


--
-- Creating procedure SP_GETFUNCDATA
-- =================================
--
create procedure SP_GetFuncData
   (@p_periodo   int,
    @p_codigo    varchar() = null,
    @p_dados     varchar() = null) as
begin
   -- Recupera os alunos por período
   If @p_dados = 'CABECALHO' or @p_dados = 'CADASTRO' or @p_dados = 'DIVERSO' Begin
         select distinct
                a.co_funcionario, a.nu_matricula_mec, a.ds_funcionario, a.nu_cpf,
                a.tp_sexo, a.ds_apelido, a.dt_nascimento, a.ds_instrucao, a.ds_uf_nascimento,
                a.ds_naturalidade, a.ds_endereco, a.ds_bairro, a.ds_cidade, a.ds_uf_cidade,
                a.nu_cep, a.nu_telefone, a.nu_celular, a.ds_e_mail, a.tp_estado_civil,
                a.ds_conjuge, a.ds_pai, a.ds_mae, a.lotacao_princ,
                a.nu_rg, a.ds_orgao_emissor, a.dt_emissao, a.nu_cpf, a.nu_registro,
                b.dt_admissao, b.nu_carga_contrato, b.nu_hora_entrada, b.nu_hora_saida,
                b.nu_hora_ini_almoc, b.nu_hora_fim_almoc,
                rtrim(b.id_professor) id_professor, rtrim(b.st_cancelado) st_cancelado,
                b.co_cargo, d.ds_cargo, c.co_unidade, c.ds_escola, e.tp_ano_letivo,
                f.ds_area_atuacao
           from s_funcionario      a,
                s_unidadefunc      b,
                s_escola           c,
                s_cargo            d,
                s_periodounidade   e,
                s_area_atuacao     f
          where a.co_funcionario     = b.co_funcionario (+)
            and b.co_unidade         = c.co_unidade (+)
            and b.co_cargo           = d.co_cargo (+)
            and c.co_unidade         = e.co_unidade (+)
            and b.co_area_atuacao    = f.co_area_atuacao   (+)
            and e.ano_sem        (+) = @p_periodo
            and b.ano_sem        (+) = @p_periodo
            and a.co_funcionario     = rpad(@p_codigo,10,' ')
   Elsif @p_dados = 'DISCIPLINA' Begin
         select distinct
                c.co_unidade, c.ds_escola, e.tp_ano_letivo,
                d.st_habilitado, f.ds_disciplina
           from s_funcionario      a,
                s_unidadefunc      b,
                s_escola           c,
                s_funcionario_disc d,
                s_periodounidade   e,
                s_disciplina       f
          where a.co_funcionario     = b.co_funcionario (+)
            and b.co_unidade         = c.co_unidade (+)
            and b.co_unidade         = d.co_unidade (+)
            and b.co_funcionario     = d.co_funcionario (+)
            and b.ano_sem            = d.ano_sem (+)
            and d.co_disciplina      = f.co_disciplina (+)
            and d.ano_sem            = f.ano_sem (+)
            and d.co_unidade         = f.co_unidade (+)
            and c.co_unidade         = e.co_unidade (+)
            and e.ano_sem        (+) = @p_periodo
            and b.ano_sem        (+) = @p_periodo
            and a.co_funcionario     = rpad(@p_codigo,10,' ')
   Elsif @p_dados = 'GRADE' Begin
          select distinct
                b.ds_escola, c.co_turno, c.co_letra_turma, f.id_professor,
                c.co_bloco, c.co_sala, e.sg_serie, a.co_disciplina, (a.nu_dia_semana) nu_dia_semana,
                (a.nu_tempo) nu_tempo, g.ds_funcionario, g.nu_matricula_mec, h.ds_cargo
           from s_horario_turma a,
                s_escola        b,
                s_turma         c,
                s_curso_serie   d,
                s_serie         e,
                s_unidadefunc   f,
                s_funcionario   g,
                s_cargo         h
          where a.co_unidade         = b.co_unidade     (+)
            and a.co_turma           = c.co_turma       (+)
            and a.co_unidade         = c.co_unidade     (+)
            and a.ano_sem            = c.ano_sem        (+)
            and a.co_curso           = d.co_curso       (+)
            and a.co_seq_serie       = d.co_seq_serie   (+)
            and a.co_unidade         = d.co_unidade     (+)
            and a.ano_sem            = d.ano_sem        (+)
            and d.sg_serie           = e.sg_serie       (+)
            and a.co_funcionario     = f.co_funcionario (+)
            and a.co_unidade         = f.co_unidade     (+)
            and a.ano_sem            = f.ano_sem        (+)
            and a.co_funcionario (+) = g.co_funcionario
            and a.co_unidade     (+) = g.co_unidade
            and f.co_cargo           = h.co_cargo       (+)
            and g.co_funcionario     = rpad(@p_codigo,10,' ')
            and a.ano_sem        (+) = @p_periodo
   End Else Begin
         select getdate() from dual
   End
end 


--
-- Creating procedure SP_GETFUNCLIST
-- =================================
--
create procedure SP_GetFuncList
   (@p_periodo     int,
    @p_regional    varchar() = null,
    @p_cpf         varchar() = null,
    @p_cargo       char()     = null,
    @p_matricula   char()     = null,
    @p_unidade     int   = null,
    @p_funcionario varchar() = null,
    @p_prof        char()     = null,
    @p_canc        char()     = null) as
begin
    -- Recupera os funcionario por período e regional
       select distinct
              a.co_funcionario, a.nu_matricula_mec, a.ds_funcionario, a.nu_cpf,
              b.co_cargo, d.ds_cargo, c.co_unidade, c.ds_escola,
              b.id_professor, b.st_cancelado
         from s_funcionario      a,
              s_unidadefunc      b,
              s_escola           c,
              s_cargo            d
        where a.co_funcionario     = b.co_funcionario (+)
          and b.co_unidade         = c.co_unidade (+)
          and b.co_cargo           = d.co_cargo (+)
          and b.ano_sem            = @p_periodo
          and (@p_regional          = 0 or (@p_regional > 0 and c.co_sigre like @p_regional+'%'))
          and (@p_cpf               is null or (@p_cpf is not null and a.nu_cpf = @p_cpf))
          and (@p_cargo             is null or (@p_cargo is not null and b.co_cargo = @p_cargo))
          and (@p_matricula         is null or (@p_matricula is not null and a.nu_matricula_mec = @p_matricula))
          and (@p_unidade           is null or (@p_unidade is not null and c.co_unidade = @p_unidade))
          and (@p_funcionario       is null or (@p_funcionario is not null and a.ds_funcionario like @p_funcionario))
          and (@p_prof              is null or (@p_prof is not null and IsNull(b.id_professor,'N') = @p_prof))
          and (@p_canc              is null or (@p_canc is not null and IsNull(b.st_cancelado,'N') = @p_canc))
end 


--
-- Creating procedure SP_GETFUNCREL
-- ================================
--
create procedure SP_GetFuncRel
   (@p_periodo      int,
    @p_regional     varchar() = null,
    @p_bairro       varchar() = null,
    @p_unidade      int   = null,
    @p_area         int   = null,
    @p_escolaridade varchar() = null,
    @p_cargo        varchar() = null,
    @p_sexo         varchar() = null,
    @p_mat_ini      datetime = null,
    @p_mat_fim      datetime = null) as
begin
   If @p_regional = 0 Begin
      -- Recupera os alunos por período
         select a.nu_matricula_mec, a.ds_funcionario, a.ds_instrucao, c.ds_cargo, d.ds_area_atuacao,
                a.ds_bairro bairro_func, e.ds_bairro bairro_unidade, d.co_area_atuacao,
                e.co_unidade, e.ds_unidade, a.dt_nascimento, a.tp_sexo, b.dt_admissao,
                substring(f.co_sigre,1,2) regional, f.ds_gre, c.co_cargo
           from s_funcionario  a,
                s_unidadefunc  b,
                s_cargo        c,
                s_area_atuacao d,
                s_unidade      e,
                s_escola       f
          where a.co_funcionario  = b.co_funcionario (+)
            and a.co_unidade      = b.co_unidade     (+)
            and a.co_unidade      = e.co_unidade     (+)
            and b.co_cargo        = c.co_cargo       (+)
            and b.co_area_atuacao = d.co_area_atuacao(+)
            and a.co_unidade      = f.co_unidade     (+)
            and (@p_unidade      is null or (@p_unidade      is not null and a.co_unidade         = @p_unidade))
            and (@p_area         is null or (@p_area         is not null and d.co_area_atuacao    = @p_area))
            and (@p_escolaridade is null or (@p_escolaridade is not null and rtrim(a.ds_instrucao) = @p_escolaridade))
            and (@p_cargo        is null or (@p_cargo        is not null and rtrim(c.co_cargo)           = @p_cargo))
            and (@p_sexo         is null or (@p_sexo         is not null and a.tp_sexo            = @p_sexo))
            and (@p_mat_ini      is null or (@p_mat_ini      is not null and b.dt_admissao        between @p_mat_ini and @p_mat_fim))
            and ((@p_bairro is null) or
                 (@p_bairro = 'S' and a.ds_bairro = e.ds_bairro) or
                 (@p_bairro = 'N' and a.ds_bairro <> e.ds_bairro)
                )
            and b.ano_sem          = @p_periodo
   End Else Begin
      -- Recupera os alunos por período e regional
         select a.nu_matricula_mec, a.ds_funcionario, a.ds_instrucao, c.ds_cargo, d.ds_area_atuacao,
                a.ds_bairro bairro_func, e.ds_bairro bairro_unidade, d.co_area_atuacao,
                e.co_unidade,e.ds_unidade, a.dt_nascimento, a.tp_sexo, b.dt_admissao,
                substring(f.co_sigre,1,2) regional, f.ds_gre, c.co_cargo
           from s_funcionario  a,
                s_unidadefunc  b,
                s_cargo        c,
                s_area_atuacao d,
                s_unidade      e,
                s_escola       f
          where a.co_funcionario  = b.co_funcionario   (+)
            and a.co_unidade      = b.co_unidade       (+)
            and a.co_unidade      = e.co_unidade       (+)
            and b.co_cargo        = c.co_cargo         (+)
            and b.co_area_atuacao = d.co_area_atuacao  (+)
            and a.co_unidade      = f.co_unidade       (+)
            and (@p_unidade      is null or (@p_unidade      is not null and a.co_unidade         = @p_unidade))
            and (@p_area         is null or (@p_area         is not null and d.co_area_atuacao    = @p_area))
            and (@p_escolaridade is null or (@p_escolaridade is not null and rtrim(a.ds_instrucao) = @p_escolaridade))
            and (@p_cargo        is null or (@p_cargo        is not null and rtrim(c.co_cargo)           = @p_cargo))
            and (@p_sexo         is null or (@p_sexo         is not null and a.tp_sexo            = @p_sexo))
            and (@p_mat_ini      is null or (@p_mat_ini      is not null and b.dt_admissao        between @p_mat_ini and @p_mat_fim))
            and ((@p_bairro is null) or
                 (@p_bairro = 'S' and a.ds_bairro = e.ds_bairro) or
                 (@p_bairro = 'N' and a.ds_bairro <> e.ds_bairro)
                )
            and b.ano_sem         = @p_periodo
            and f.co_sigre         like @p_regional+'%'
   End
end 


--
-- Creating procedure SP_GETMATDISCDATA
-- ====================================
--
create procedure SP_GetMatDiscData
   (@p_co_grade_curric int,
    @p_sg_serie        varchar()
   ) as
begin
   -- Recupera as disciplinas da matriz curricular
      select a.*, b.descr_serie, c.ds_tipo_curso, d.ds_tipo_disciplina, d.sg_disciplina
      from s_disciplina_per  a,
           s_serie           b,
           s_tipo_curso      c,
           s_tipo_disciplina d
      where a.co_grade_curric    = @p_co_grade_curric
        and a.sg_serie           = @p_sg_serie
        and a.sg_serie           = b.sg_serie           (+)
        and a.co_tipo_curso      = c.co_tipo_curso      (+)
        and a.co_tipo_disciplina = d.co_tipo_disciplina (+)
end 


--
-- Creating procedure SP_GETMATDISCODATA
-- =====================================
--
create procedure SP_GetMatDiscOData
   (@p_co_grade_curric      int,
    @p_co_tipo_disciplina   int,
    @p_sg_serie             varchar()
   ) as
begin
   -- Recupera os dados de uma disciplina da matriz curricular
      select a.*, b.ds_tipo_disciplina
      from s_disciplina_per  a,
           s_tipo_disciplina b
      where a.co_grade_curric    = @p_co_grade_curric
        and a.co_tipo_disciplina = @p_co_tipo_disciplina
        and a.sg_serie           = @p_sg_serie
        and a.co_tipo_disciplina = b.co_tipo_disciplina (+)
end 


--
-- Creating procedure SP_GETMATRIXDATA
-- ===================================
--
create procedure SP_GetMatrixData (@p_co_grade_curric int ) as
begin
   -- Recupera os dados da matriz curricular
      select a.*, b.ds_tipo_curso
      from s_grade_curric a,
           s_tipo_curso   b
      where a.co_grade_curric = @p_co_grade_curric
        and a.co_tipo_curso   = b.co_tipo_curso   (+)
end 


--
-- Creating procedure SP_GETMATRIXLIST
-- ===================================
--
--
-- Creating procedure SP_GETMATSERDATA
-- ===================================
--
create procedure SP_GetMatSerData (@p_co_grade_curric int) as
begin
   -- Recupera a lista de séries de uma matriz curricular
      select a.*, b.descr_serie, c.ds_tipo_curso
      from s_periodo        a,
           s_serie          b,
           s_tipo_curso     c
      where a.co_grade_curric = @p_co_grade_curric
        and a.sg_serie        = b.sg_serie        (+)
        and a.co_tipo_curso   = c.co_tipo_curso   (+)
end 


--
-- Creating procedure SP_GETMATSERLIST
-- ===================================
--
--
-- Creating procedure SP_GETMATSERODATA
-- ====================================
--
create procedure SP_GetMatSerOData
   (@p_co_grade_curric int,
    @p_sg_serie        varchar()
   ) as
begin
   -- Recupera os dados da matriz curricular
      select a.*, b.descr_serie, b.sg_serie, c.ds_tipo_curso, d.ds_grade
      from s_periodo        a,
           s_serie          b,
           s_tipo_curso     c,
           s_grade_curric   d
      where a.co_grade_curric = @p_co_grade_curric
        and a.sg_serie        = @p_sg_serie
        and a.sg_serie        = b.sg_serie        (+)
        and a.co_tipo_curso   = c.co_tipo_curso   (+)
        and d.co_grade_curric = @p_co_grade_curric
end 


--
-- Creating procedure SP_GETPERIODOLIST
-- ====================================
--
--
-- Creating procedure SP_GETPOSITIONDATA
-- =====================================
--
create procedure SP_GetPositionData (@p_co_cargo        varchar()) as
begin
   -- Recupera os dados do ambiente
      select * from s_cargo where co_cargo = @p_co_cargo
end 


--
-- Creating procedure SP_GETPOSITIONLIST
-- =====================================
--
--
-- Creating procedure SP_GETPROFREL
-- ================================
--
create procedure SP_GetProfRel
   (@p_periodo      int,
    @p_regional     varchar() = null,
    @p_modalidade   int = null,
    @p_serie        varchar() = null,
    @p_turma        int = null,
    @p_disciplina   int = null,
    @p_turno        varchar() = null,
    @p_bairro       varchar() = null,
    @p_tipo         varchar() = null,
    @p_unidade      int   = null,
    @p_escolaridade varchar() = null,
    @p_cargo        int   = null,
    @p_sexo         varchar() = null,
    @p_mat_ini      datetime = null,
    @p_mat_fim      datetime = null) as
begin
   If @p_tipo = 'N' Begin
      ---Recupera os professores que com e sem turma
       If @p_regional = '00' Begin
          -- Recupera os professores por período
             select distinct(a.co_funcionario), a.nu_matricula_mec, a.ds_funcionario,
                    a.ds_instrucao, c.ds_cargo, d.ds_area_atuacao,
                    rtrim(a.ds_bairro) bairro_func, e.ds_bairro bairro_unidade, d.co_area_atuacao,
                    e.co_unidade, e.ds_unidade, a.dt_nascimento, a.tp_sexo, b.dt_admissao,
                    substring(f.co_sigre,1,2) regional, f.ds_gre, c.co_cargo, b.id_professor,
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
                and (@p_modalidade   is null or (@p_modalidade   is not null and j.co_tipo_curso      = @p_modalidade))
                and (@p_serie        is null or (@p_serie        is not null and l.sg_serie           = @p_serie))
                and (@p_turma        is null or (@p_turma        is not null and i.co_turma           = @p_turma))
                and (@p_disciplina   is null or (@p_disciplina   is not null and h.co_tipo_disciplina = @p_disciplina))
                and (@p_turno        is null or (@p_turno        is not null and rtrim(i.co_turno)     = rtrim(@p_turno)))
                and (@p_unidade      is null or (@p_unidade      is not null and a.co_unidade         = @p_unidade))
                and (@p_escolaridade is null or (@p_escolaridade is not null and a.ds_instrucao       = @p_escolaridade))
                and (@p_cargo        is null or (@p_cargo        is not null and c.co_cargo           = @p_cargo))
                and (@p_sexo         is null or (@p_sexo         is not null and a.tp_sexo            = @p_sexo))
                and (@p_mat_ini      is null or (@p_mat_ini      is not null and b.dt_admissao        between @p_mat_ini and @p_mat_fim))
                and (@p_bairro       is null or
                     (@p_bairro = 'S' and a.ds_bairro = e.ds_bairro) or
                     (@p_bairro = 'N' and a.ds_bairro <> e.ds_bairro)
                    )
                and b.ano_sem         = @p_periodo
       End Else Begin
          -- Recupera os professores por período e regional
             select distinct(a.co_funcionario), a.nu_matricula_mec, a.ds_funcionario,
                    a.ds_instrucao, c.ds_cargo, d.ds_area_atuacao,
                    rtrim(a.ds_bairro) bairro_func, e.ds_bairro bairro_unidade, d.co_area_atuacao,
                    e.co_unidade, e.ds_unidade, a.dt_nascimento, a.tp_sexo, b.dt_admissao,
                    substring(f.co_sigre,1,2) regional, f.ds_gre, c.co_cargo, b.id_professor,
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
                and (@p_modalidade   is null or (@p_modalidade   is not null and j.co_tipo_curso      = @p_modalidade))
                and (@p_serie        is null or (@p_serie        is not null and l.sg_serie           = @p_serie))
                and (@p_turma        is null or (@p_turma        is not null and i.co_turma           = @p_turma))
                and (@p_disciplina   is null or (@p_disciplina   is not null and h.co_tipo_disciplina = @p_disciplina))
                and (@p_turno        is null or (@p_turno        is not null and rtrim(i.co_turno)     = rtrim(@p_turno)))
                and (@p_unidade      is null or (@p_unidade      is not null and a.co_unidade         = @p_unidade))
                and (@p_escolaridade is null or (@p_escolaridade is not null and a.ds_instrucao       = @p_escolaridade))
                and (@p_cargo        is null or (@p_cargo        is not null and c.co_cargo           = @p_cargo))
                and (@p_sexo         is null or (@p_sexo         is not null and a.tp_sexo            = @p_sexo))
                and (@p_mat_ini      is null or (@p_mat_ini      is not null and b.dt_admissao        between @p_mat_ini and @p_mat_fim))
                and ((@p_bairro is null) or
                     (@p_bairro = 'S' and a.ds_bairro = e.ds_bairro) or
                     (@p_bairro = 'N' and a.ds_bairro <> e.ds_bairro)
                    )
                and b.ano_sem         = @p_periodo
                and f.co_sigre         like @p_regional+'%'
       End
   End Else Begin
      ---Recupera somente os professores com turma
       If @p_regional = '00' Begin
          -- Recupera os professores por período
             select distinct(a.co_funcionario), a.nu_matricula_mec, a.ds_funcionario,
                    a.ds_instrucao, c.ds_cargo, d.ds_area_atuacao,
                    rtrim(a.ds_bairro) bairro_func, e.ds_bairro bairro_unidade, d.co_area_atuacao,
                    e.co_unidade, e.ds_unidade, a.dt_nascimento, a.tp_sexo, b.dt_admissao,
                    substring(f.co_sigre,1,2) regional, f.ds_gre, c.co_cargo, b.id_professor,
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
                and (@p_modalidade   is null or (@p_modalidade   is not null and j.co_tipo_curso      = @p_modalidade))
                and (@p_serie        is null or (@p_serie        is not null and l.sg_serie           = @p_serie))
                and (@p_turma        is null or (@p_turma        is not null and i.co_turma           = @p_turma))
                and (@p_disciplina   is null or (@p_disciplina   is not null and h.co_tipo_disciplina = @p_disciplina))
                and (@p_turno        is null or (@p_turno        is not null and rtrim(i.co_turno)     = rtrim(@p_turno)))
                and (@p_unidade      is null or (@p_unidade      is not null and a.co_unidade         = @p_unidade))
                and (@p_escolaridade is null or (@p_escolaridade is not null and a.ds_instrucao       = @p_escolaridade))
                and (@p_cargo        is null or (@p_cargo        is not null and c.co_cargo           = @p_cargo))
                and (@p_sexo         is null or (@p_sexo         is not null and a.tp_sexo            = @p_sexo))
                and (@p_mat_ini      is null or (@p_mat_ini      is not null and b.dt_admissao        between @p_mat_ini and @p_mat_fim))
                and ((@p_bairro is null) or
                     (@p_bairro = 'S' and a.ds_bairro = e.ds_bairro) or
                     (@p_bairro = 'N' and a.ds_bairro <> e.ds_bairro)
                    )
                and b.ano_sem         = @p_periodo
       End Else Begin
          -- Recupera os professores por período e regional
             select distinct(a.co_funcionario), a.nu_matricula_mec, a.ds_funcionario,
                    a.ds_instrucao, c.ds_cargo, d.ds_area_atuacao,
                    rtrim(a.ds_bairro) bairro_func, e.ds_bairro bairro_unidade, d.co_area_atuacao,
                    e.co_unidade, e.ds_unidade, a.dt_nascimento, a.tp_sexo, b.dt_admissao,
                    substring(f.co_sigre,1,2) regional, f.ds_gre, c.co_cargo, b.id_professor,
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
                and (@p_modalidade   is null or (@p_modalidade   is not null and j.co_tipo_curso      = @p_modalidade))
                and (@p_serie        is null or (@p_serie        is not null and l.sg_serie           = @p_serie))
                and (@p_turma        is null or (@p_turma        is not null and i.co_turma           = @p_turma))
                and (@p_disciplina   is null or (@p_disciplina   is not null and h.co_tipo_disciplina = @p_disciplina))
                and (@p_turno        is null or (@p_turno        is not null and rtrim(i.co_turno)     = rtrim(@p_turno)))
                and (@p_unidade      is null or (@p_unidade      is not null and a.co_unidade         = @p_unidade))
                and (@p_escolaridade is null or (@p_escolaridade is not null and a.ds_instrucao       = @p_escolaridade))
                and (@p_cargo        is null or (@p_cargo        is not null and c.co_cargo           = @p_cargo))
                and (@p_sexo         is null or (@p_sexo         is not null and a.tp_sexo            = @p_sexo))
                and (@p_mat_ini      is null or (@p_mat_ini      is not null and b.dt_admissao        between @p_mat_ini and @p_mat_fim))
                and ((@p_bairro is null) or
                     (@p_bairro = 'S' and a.ds_bairro = e.ds_bairro) or
                     (@p_bairro = 'N' and a.ds_bairro <> e.ds_bairro)
                    )
                and b.ano_sem         = @p_periodo
                and f.co_sigre         like @p_regional+'%'
       End
   End
end 


--
-- Creating procedure SP_GETRENDREL
-- ================================
--
create procedure SP_GetRendRel
   (@p_periodo     int,
    @p_unidade     int,
    @p_modalidade  int   = null,
    @p_serie       varchar() = null,
    @p_turma       int   = null,
    @p_turno       varchar() = null,
    @p_bimestre    varchar() = null) as
begin
   If @p_bimestre = '1' Begin
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
                 and ((@p_modalidade is null) or (@p_modalidade is not null and l.co_tipo_curso = @p_modalidade))
                 and ((@p_turma is null) or (@p_turma is not null and y.co_turma = @p_turma))
                 and ((@p_turno is null) or (@p_turno is not null and rtrim(y.co_turno) = @p_turno))
                 and ((@p_serie is null) or (@p_serie is not null and k.sg_serie = @p_serie))
                 and x.co_unidade      = @p_unidade
                 and x.ano_sem         = @p_periodo) c,
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
                 and ((@p_modalidade is null) or (@p_modalidade is not null and l.co_tipo_curso = @p_modalidade))
                 and ((@p_turma is null) or (@p_turma is not null and z.co_turma = @p_turma))
                 and ((@p_turno is null) or (@p_turno is not null and rtrim(z.co_turno) = @p_turno))
                 and ((@p_serie is null) or (@p_serie is not null and k.sg_serie = @p_serie))
                 and w.co_unidade      = @p_unidade
                 and w.ano_sem         = @p_periodo) e
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
          and a.nu_nota_b1      < substring(d.nu_media_nota,1,1)
          and b.st_movimentacao = 'ALUNO ATIVO'
          and a.co_unidade = @p_unidade
          and a.ano_sem    = @p_periodo
          and ((@p_modalidade is null) or (@p_modalidade is not null and d.co_tipo_curso = @p_modalidade))
          and ((@p_turma is null) or (@p_turma is not null and b.co_turma = @p_turma))
          and ((@p_turno is null) or (@p_turno is not null and rtrim(h.co_turno) = @p_turno))
          and ((@p_serie is null) or (@p_serie is not null and i.sg_serie = @p_serie))
         group by a.co_disciplina, c.matriculados, e.frequentes, f.ds_unidade,
                  g.ds_disciplina, g.ds_ordem_imp, d.ds_curso
   ElsIf @p_bimestre = '2' Begin
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
                 and ((@p_modalidade is null) or (@p_modalidade is not null and l.co_tipo_curso = @p_modalidade))
                 and ((@p_turma is null) or (@p_turma is not null and y.co_turma = @p_turma))
                 and ((@p_turno is null) or (@p_turno is not null and rtrim(y.co_turno) = @p_turno))
                 and ((@p_serie is null) or (@p_serie is not null and k.sg_serie = @p_serie))
                 and x.co_unidade      = @p_unidade
                 and x.ano_sem         = @p_periodo) c,
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
                 and ((@p_modalidade is null) or (@p_modalidade is not null and l.co_tipo_curso = @p_modalidade))
                 and ((@p_turma is null) or (@p_turma is not null and z.co_turma = @p_turma))
                 and ((@p_turno is null) or (@p_turno is not null and rtrim(z.co_turno) = @p_turno))
                 and ((@p_serie is null) or (@p_serie is not null and k.sg_serie = @p_serie))
                 and w.co_unidade      = @p_unidade
                 and w.ano_sem         = @p_periodo) e
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
          and a.nu_nota_b2      < substring(d.nu_media_nota,1,1)
          and b.st_movimentacao = 'ALUNO ATIVO'
          and a.co_unidade = @p_unidade
          and a.ano_sem    = @p_periodo
          and ((@p_modalidade is null) or (@p_modalidade is not null and d.co_tipo_curso = @p_modalidade))
          and ((@p_turma is null) or (@p_turma is not null and b.co_turma = @p_turma))
          and ((@p_turno is null) or (@p_turno is not null and h.co_turno = @p_turno))
          and ((@p_serie is null) or (@p_serie is not null and i.sg_serie = @p_serie))
         group by a.co_disciplina, c.matriculados, e.frequentes, f.ds_unidade,
                  g.ds_disciplina, g.ds_ordem_imp, d.ds_curso

   ElsIf @p_bimestre = '3' Begin
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
                 and ((@p_modalidade is null) or (@p_modalidade is not null and l.co_tipo_curso = @p_modalidade))
                 and ((@p_turma is null) or (@p_turma is not null and y.co_turma = @p_turma))
                 and ((@p_turno is null) or (@p_turno is not null and rtrim(y.co_turno) = @p_turno))
                 and ((@p_serie is null) or (@p_serie is not null and k.sg_serie = @p_serie))
                 and x.co_unidade      = @p_unidade
                 and x.ano_sem         = @p_periodo) c,
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
                 and ((@p_modalidade is null) or (@p_modalidade is not null and l.co_tipo_curso = @p_modalidade))
                 and ((@p_turma is null) or (@p_turma is not null and z.co_turma = @p_turma))
                 and ((@p_turno is null) or (@p_turno is not null and rtrim(z.co_turno) = @p_turno))
                 and ((@p_serie is null) or (@p_serie is not null and k.sg_serie = @p_serie))
                 and w.co_unidade      = @p_unidade
                 and w.ano_sem         = @p_periodo) e
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
          and a.nu_nota_b3      < substring(d.nu_media_nota,1,1)
          and b.st_movimentacao = 'ALUNO ATIVO'
          and a.co_unidade = @p_unidade
          and a.ano_sem    = @p_periodo
          and ((@p_modalidade is null) or (@p_modalidade is not null and d.co_tipo_curso = @p_modalidade))
          and ((@p_turma is null) or (@p_turma is not null and b.co_turma = @p_turma))
          and ((@p_turno is null) or (@p_turno is not null and rtrim(h.co_turno) = @p_turno))
          and ((@p_serie is null) or (@p_serie is not null and i.sg_serie = @p_serie))
         group by a.co_disciplina, c.matriculados, e.frequentes, f.ds_unidade,
                  g.ds_disciplina, g.ds_ordem_imp, d.ds_curso

   ElsIf @p_bimestre = '4' Begin
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
                 and ((@p_modalidade is null) or (@p_modalidade is not null and l.co_tipo_curso = @p_modalidade))
                 and ((@p_turma is null) or (@p_turma is not null and y.co_turma = @p_turma))
                 and ((@p_turno is null) or (@p_turno is not null and rtrim(y.co_turno) = @p_turno))
                 and ((@p_serie is null) or (@p_serie is not null and k.sg_serie = @p_serie))
                 and x.co_unidade      = @p_unidade
                 and x.ano_sem         = @p_periodo) c,
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
                 and ((@p_modalidade is null) or (@p_modalidade is not null and l.co_tipo_curso = @p_modalidade))
                 and ((@p_turma is null) or (@p_turma is not null and z.co_turma = @p_turma))
                 and ((@p_turno is null) or (@p_turno is not null and rtrim(z.co_turno) = @p_turno))
                 and ((@p_serie is null) or (@p_serie is not null and k.sg_serie = @p_serie))
                 and w.co_unidade      = @p_unidade
                 and w.ano_sem         = @p_periodo) e
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
          and a.nu_nota_b4      < substring(d.nu_media_nota,1,1)
          and b.st_movimentacao = 'ALUNO ATIVO'
          and a.co_unidade = @p_unidade
          and a.ano_sem    = @p_periodo
          and ((@p_modalidade is null) or (@p_modalidade is not null and d.co_tipo_curso = @p_modalidade))
          and ((@p_turma is null) or (@p_turma is not null and b.co_turma = @p_turma))
          and ((@p_turno is null) or (@p_turno is not null and rtrim(h.co_turno) = @p_turno))
          and ((@p_serie is null) or (@p_serie is not null and i.sg_serie = @p_serie))
         group by a.co_disciplina, c.matriculados, e.frequentes, f.ds_unidade,
                  g.ds_disciplina, g.ds_ordem_imp, d.ds_curso
   ElsIf @p_bimestre = 'FINAL' Begin
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
                 and ((@p_modalidade is null) or (@p_modalidade is not null and l.co_tipo_curso = @p_modalidade))
                 and ((@p_turma is null) or (@p_turma is not null and y.co_turma = @p_turma))
                 and ((@p_turno is null) or (@p_turno is not null and rtrim(y.co_turno) = @p_turno))
                 and ((@p_serie is null) or (@p_serie is not null and k.sg_serie = @p_serie))
                 and x.co_unidade      = @p_unidade
                 and x.ano_sem         = @p_periodo) c,
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
                 and ((@p_modalidade is null) or (@p_modalidade is not null and l.co_tipo_curso = @p_modalidade))
                 and ((@p_turma is null) or (@p_turma is not null and z.co_turma = @p_turma))
                 and ((@p_turno is null) or (@p_turno is not null and rtrim(z.co_turno) = @p_turno))
                 and ((@p_serie is null) or (@p_serie is not null and k.sg_serie = @p_serie))
                 and w.co_unidade      = @p_unidade
                 and w.ano_sem         = @p_periodo) e
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
          and a.nu_media_final  < substring(a.nu_media_anual,1,1)
          and b.st_movimentacao = 'ALUNO ATIVO'
          and a.co_unidade = @p_unidade
          and a.ano_sem    = @p_periodo
          and ((@p_modalidade is null) or (@p_modalidade is not null and d.co_tipo_curso = @p_modalidade))
          and ((@p_turma is null) or (@p_turma is not null and b.co_turma = @p_turma))
          and ((@p_turno is null) or (@p_turno is not null and rtrim(h.co_turno) = @p_turno))
          and ((@p_serie is null) or (@p_serie is not null and i.sg_serie = @p_serie))
         group by a.co_disciplina, c.matriculados, e.frequentes, f.ds_unidade,
                  g.ds_disciplina, g.ds_ordem_imp, d.ds_curso

   End
end 


--
-- Creating procedure SP_GETRESPKINDLIST
-- =====================================
--
--
-- Creating procedure SP_GETRESPONSDATA
-- ====================================
--
create procedure SP_GetResponsData
   (@p_periodo     int,
    @p_responsavel varchar()) as
begin
   -- Recupera os alunos por período
      select distinct c.*, e.ds_tip_responsavel, d.ano_sem,
             a.ds_aluno, a.dt_nascimento nasc_aluno, f.ds_escola,
             e.ds_tip_responsavel, a.co_aluno
        from s_aluno            a,
             s_respons_aluno    b,
             s_responsavel      c,
             s_aluno_per_unid   d,
             s_tipo_responsavel e,
             s_escola           f
        where c.co_responsavel     = b.co_responsavel (+)
          and c.co_unidade         = b.co_unidade (+)
          and b.co_aluno           = d.co_aluno (+)
          and b.co_unidade         = d.co_unidade (+)
          and d.co_aluno           = a.co_aluno (+)
          and d.co_unidade         = f.co_unidade (+)
          and c.co_tip_responsavel = e.co_tip_responsavel
          and d.ano_sem      (+)   = @p_periodo
          and c.co_responsavel     = @p_responsavel
end 


--
-- Creating procedure SP_GETROOMCLLIST
-- ===================================
--
create procedure SP_GetRoomClList
   (@p_periodo    int,
    @p_regional   varchar() = null,
    @p_unidade    int   = null,
    @p_modalidade int   = null,
    @p_turno      varchar() = null,
    @p_serie      varchar() = null,
    @p_turma      int   = null,
    @p_ambiente   int   = null,
    @p_tipo_sala  int   = null) as
begin
   If @p_regional = 0 Begin
      select a.co_letra_turma, a.co_bloco, a.co_turno, a.co_turma,
             d.ds_sala, ds_tipo_sala, ds_ambiente, nu_metragem, nu_alunos_sala,
             h.sg_serie, i.aluno_matric, j.aluno_ativo, ds_escola, d.co_sala, f.co_seq_ambiente,
             e.co_tipo_sala, c.co_curso, a.co_unidade, a.co_turma, l.co_tipo_curso,
             m.co_unidade total_uni, m.co_sala total_sala,substring(b.co_sigre,1,2) regional, b.ds_gre,
             n.tp_anee, l.ds_tipo_curso, a.st_turma_definitiv, a.ds_turma, l.sg_tipo_curso
        from s_turma         a,      s_escola        b,     s_curso         c,
             s_sala          d,      s_tipo_sala     e,     s_ambiente      f,
             s_curso_serie   g,      s_serie         h,     s_tipo_curso    l,
            (select co_unidade, co_turma, count(*) aluno_matric
             from   s_aluno_turma
             where  ano_sem = @p_periodo
             having count(*) > 1
             group  by co_unidade, co_turma) i,
            (select co_unidade, co_turma, count(*) aluno_ativo
             from   s_aluno_turma
             where  ano_sem = @p_periodo and st_movimentacao = rpad('ALUNO ATIVO', 25, ' ')
             having count(*) > 1
             group  by co_unidade, co_turma) j,
            (select co_unidade, count(*) co_sala
             from   s_turma
             where  ano_sem = @p_periodo
             having count(*) > 1
             group  by co_unidade) m,
            (select a.co_turma, a.co_unidade, count(*) tp_anee,
                    a.ano_sem
               from s_aluno_turma a,
                    s_aluno       b
              where a.ano_sem    = @p_periodo
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
         and (@p_unidade        is null or (@p_unidade    is not null and a.co_unidade      = @p_unidade))
         and (@p_modalidade     is null or (@p_modalidade is not null and l.co_tipo_curso   = @p_modalidade))
         and (@p_turno          is null or (@p_turno      is not null and a.co_turno        = @p_turno))
         and (@p_serie          is null or (@p_serie      is not null and g.sg_serie        = @p_serie))
         and (@p_turma          is null or (@p_turma      is not null and a.co_turma        = @p_turma))
         and (@p_ambiente       is null or (@p_ambiente   is not null and f.co_seq_ambiente = @p_ambiente))
         and (@p_tipo_sala      is null or (@p_tipo_sala  is not null and e.co_tipo_sala    = @p_tipo_sala))
         and a.ano_sem         = @p_periodo
   End Else Begin
      select a.co_letra_turma, a.co_bloco, a.co_turno, a.co_turma,
             d.ds_sala, ds_tipo_sala, ds_ambiente, nu_metragem, nu_alunos_sala,
             h.sg_serie, i.aluno_matric, j.aluno_ativo, ds_escola, d.co_sala, f.co_seq_ambiente,
             e.co_tipo_sala, c.co_curso, a.co_unidade, a.co_turma, l.co_tipo_curso,
             m.co_unidade total_uni, m.co_sala total_sala,substring(b.co_sigre,1,2) regional, b.ds_gre,
             n.tp_anee, l.ds_tipo_curso, a.st_turma_definitiv, a.ds_turma, l.sg_tipo_curso
        from s_turma         a,      s_escola        b,     s_curso         c,
             s_sala          d,      s_tipo_sala     e,     s_ambiente      f,
             s_curso_serie   g,      s_serie         h,     s_tipo_curso    l,
            (select co_unidade, co_turma, count(*) aluno_matric
             from   s_aluno_turma
             where  ano_sem = @p_periodo
             having count(*) > 1
             group  by co_unidade, co_turma) i,
            (select co_unidade, co_turma, count(*) aluno_ativo
             from   s_aluno_turma
             where  ano_sem = @p_periodo and st_movimentacao = rpad('ALUNO ATIVO', 25, ' ')
             having count(*) > 1
             group  by co_unidade, co_turma) j,
            (select co_unidade, count(*) co_sala
             from   s_turma
             where  ano_sem = @p_periodo
             having count(*) > 1
             group  by co_unidade) m,
            (select a.co_turma, a.co_unidade, count(*) tp_anee,
                    a.ano_sem
               from s_aluno_turma a,
                    s_aluno       b
              where a.ano_sem    = @p_periodo
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
         and (@p_unidade        is null or (@p_unidade    is not null and a.co_unidade    = @p_unidade))
         and (@p_modalidade     is null or (@p_modalidade is not null and l.co_tipo_curso = @p_modalidade))
         and (@p_turno          is null or (@p_turno      is not null and a.co_turno      = @p_turno))
         and (@p_serie          is null or (@p_serie      is not null and g.sg_serie      = @p_serie))
         and (@p_turma          is null or (@p_turma      is not null and a.co_turma      = @p_turma))
         and (@p_ambiente       is null or (@p_ambiente   is not null and f.co_seq_ambiente = @p_ambiente))
         and (@p_tipo_sala      is null or (@p_tipo_sala  is not null and e.co_tipo_sala    = @p_tipo_sala))
         and a.ano_sem         = @p_periodo
         and b.co_sigre      like @p_regional+'%'
   End
end 


--
-- Creating procedure SP_GETROOMLIST
-- =================================
--
create procedure SP_GetRoomList
   (@p_periodo    int,
    @p_regional   varchar() = null,
    @p_unidade    int   = null,
    @p_modalidade int   = null,
    @p_turno      varchar() = null,
    @p_serie      varchar() = null,
    @p_turma      int   = null,
    @p_ambiente   int   = null,
    @p_tipo_sala  int   = null) as
begin
   If @p_regional = 0 Begin
      select d.co_sala, a.co_letra_turma, a.co_bloco, a.co_turno, a.co_turma,
             d.ds_sala, ds_tipo_sala, ds_ambiente, nu_metragem, nu_alunos_sala,
             h.sg_serie, i.aluno_matric, j.aluno_ativo, ds_escola, f.co_seq_ambiente,
             e.co_tipo_sala, c.co_curso, a.co_unidade, a.co_turma, l.co_tipo_curso,
             m.co_unidade total_uni, m.co_sala total_sala,substring(b.co_sigre,1,2) regional, b.ds_gre,
             n.tp_anee, l.ds_tipo_curso, a.st_turma_definitiv, a.ds_turma, l.sg_tipo_curso
        from s_turma         a,      s_escola        b,     s_curso         c,
             s_sala          d,      s_tipo_sala     e,     s_ambiente      f,
             s_curso_serie   g,      s_serie         h,     s_tipo_curso    l,
            (select co_unidade, co_turma, count(*) aluno_matric
             from   s_aluno_turma
             where  ano_sem = @p_periodo
             having count(*) > 1
             group  by co_unidade, co_turma) i,
            (select co_unidade, co_turma, count(*) aluno_ativo
             from   s_aluno_turma
             where  ano_sem = @p_periodo and st_movimentacao = rpad('ALUNO ATIVO', 25, ' ')
             having count(*) > 1
             group  by co_unidade, co_turma) j,
            (select co_unidade, count(*) co_sala
             from   s_turma
             where  ano_sem = @p_periodo
             having count(*) > 1
             group  by co_unidade) m,
            (select a.co_turma, a.co_unidade, count(*) tp_anee,
                    a.ano_sem
               from s_aluno_turma a,
                    s_aluno       b
              where a.ano_sem    = @p_periodo
                and b.tp_anee    is not null
                and a.co_aluno   = b.co_aluno   (+)
                and a.co_unidade = b.co_unidade (+)
             having count(*) > 1
              group by a.co_unidade, a.co_turma, a.ano_sem) n
       where d.co_unidade      = b.co_unidade       (+)
         and d.co_tipo_sala    = e.co_tipo_sala     (+)
         and d.co_seq_ambiente = f.co_seq_ambiente  (+)
         and d.co_unidade      = a.co_unidade       (+) and d.co_bloco     = a.co_bloco     (+) and d.co_sala  = a.co_sala   (+)
         and a.co_curso        = c.co_curso         (+) and a.co_unidade   = c.co_unidade   (+) and a.ano_sem  = c.ano_sem   (+)
         and a.co_unidade      = g.co_unidade       (+) and a.co_seq_serie = g.co_seq_serie (+) and a.co_curso = g.co_curso  (+) and a.ano_sem = g.ano_sem(+)
         and g.sg_serie        = h.sg_serie         (+)
         and a.co_unidade      = i.co_unidade       (+) and a.co_turma     = i.co_turma     (+)
         and a.co_unidade      = j.co_unidade       (+) and a.co_turma     = j.co_turma     (+)
         and c.co_tipo_curso   = l.co_tipo_curso    (+)
         and d.co_unidade      = m.co_unidade       (+)
         and a.co_unidade      = n.co_unidade       (+) and a.co_turma     = n.co_turma     (+)
         and a.ano_sem         = @p_periodo
   End Else Begin
      select d.co_sala, a.co_letra_turma, a.co_bloco, a.co_turno, a.co_turma,
             d.ds_sala, ds_tipo_sala, ds_ambiente, nu_metragem, nu_alunos_sala,
             h.sg_serie, i.aluno_matric, j.aluno_ativo, ds_escola, f.co_seq_ambiente,
             e.co_tipo_sala, c.co_curso, a.co_unidade, a.co_turma, l.co_tipo_curso,
             m.co_unidade total_uni, m.co_sala total_sala,substring(b.co_sigre,1,2) regional, b.ds_gre,
             n.tp_anee, l.ds_tipo_curso, a.st_turma_definitiv, a.ds_turma, l.sg_tipo_curso
        from s_turma         a,      s_escola        b,     s_curso         c,
             s_sala          d,      s_tipo_sala     e,     s_ambiente      f,
             s_curso_serie   g,      s_serie         h,     s_tipo_curso    l,
            (select co_unidade, co_turma, count(*) aluno_matric
             from   s_aluno_turma
             where  ano_sem = @p_periodo
             having count(*) > 1
             group  by co_unidade, co_turma) i,
            (select co_unidade, co_turma, count(*) aluno_ativo
             from   s_aluno_turma
             where  ano_sem = @p_periodo and st_movimentacao = rpad('ALUNO ATIVO', 25, ' ')
             having count(*) > 1
             group  by co_unidade, co_turma) j,
            (select co_unidade, count(*) co_sala
             from   s_turma
             where  ano_sem = @p_periodo
             having count(*) > 1
             group  by co_unidade) m,
            (select a.co_turma, a.co_unidade, count(*) tp_anee,
                    a.ano_sem
               from s_aluno_turma a,
                    s_aluno       b
              where a.ano_sem    = @p_periodo
                and b.tp_anee    is not null
                and a.co_aluno   = b.co_aluno   (+)
                and a.co_unidade = b.co_unidade (+)
             having count(*) > 1
              group by a.co_unidade, a.co_turma, a.ano_sem) n
       where d.co_unidade      = b.co_unidade       (+)
         and d.co_tipo_sala    = e.co_tipo_sala     (+)
         and d.co_seq_ambiente = f.co_seq_ambiente  (+)
         and d.co_unidade      = a.co_unidade       (+) and d.co_bloco     = a.co_bloco     (+) and d.co_sala  = a.co_sala   (+)
         and a.co_curso        = c.co_curso         (+) and a.co_unidade   = c.co_unidade   (+) and a.ano_sem  = c.ano_sem   (+)
         and a.co_unidade      = g.co_unidade       (+) and a.co_seq_serie = g.co_seq_serie (+) and a.co_curso = g.co_curso  (+) and a.ano_sem = g.ano_sem(+)
         and g.sg_serie        = h.sg_serie         (+)
         and a.co_unidade      = i.co_unidade       (+) and a.co_turma     = i.co_turma     (+)
         and a.co_unidade      = j.co_unidade       (+) and a.co_turma     = j.co_turma     (+)
         and c.co_tipo_curso   = l.co_tipo_curso    (+)
         and d.co_unidade      = m.co_unidade       (+)
         and a.co_unidade      = n.co_unidade       (+) and a.co_turma     = n.co_turma     (+)
         and a.ano_sem         = @p_periodo
         and b.co_sigre      like @p_regional+'%'
   End
end 


--
-- Creating procedure SP_GETROOMTYPEDATA
-- =====================================
--
--
-- Creating procedure SP_GETROOMTYPELIST
-- =====================================
--
--
-- Creating procedure SP_GETSCHOOLLIST
-- ===================================
--
--
-- Creating procedure SP_GETSCHORDATA
-- ==================================
--
--
-- Creating procedure SP_GETSCHORLIST
-- ==================================
--
--
-- Creating procedure SP_GETSERIEDATA
-- ==================================
--
create procedure SP_GetSerieData (@p_sg_serie        varchar()) as
begin
   -- Recupera os dados da serie
      select * from s_serie where sg_serie = @p_sg_serie
end 


--
-- Creating procedure SP_GETSERIELIST
-- ==================================
--
--
-- Creating procedure SP_GETSTUDENTDATA
-- ====================================
--
create procedure SP_GetStudentData
   (@p_periodo   int,
    @p_matricula varchar() = null,
    @p_dados     varchar() = null) as
begin
   -- Recupera os alunos por período
   If @p_dados = 'CADASTRO' or @p_dados = 'CABECALHO' or @p_dados = 'DOCUMENTO' or @p_dados = 'MEDICA' Begin
         select a.dt_ingresso, a.co_aluno, a.ds_aluno,
                a.dt_nascimento, a.tp_anee, a.tp_sexo_aluno, a.ds_naturalidade, a.ds_uf_nascimento, a.ds_nacionalidade,
                a.ds_endereco, a.ds_bairro, a.ds_cidade, a.ds_uf_cidade, a.nu_cep, a.ds_e_mail, a.tp_estado_civil,
                a.ds_conjuge, a.nu_tempo_escolar, a.tp_escola_origem, h.ds_origem_escola, a.ds_pai, a.ds_mae,
                a.ds_telefone_pai, a.ds_telefone_mae,
                a.nu_rg, a.ds_orgao_emissor, a.dt_emissao, a.nu_reservista, a.nu_cpf, a.nu_titulo_eleitor,
                a.ds_zona, a.ds_secao, a.ds_certidao, a.nu_certidao, a.nu_livro, a.nu_folha, a.ds_cartorio,
                a.ds_cidade_certidao, a.ds_uf_certidao,
                a.tp_visao, tp_visao, a.tp_audicao tp_audicao, a.ds_probsaude, a.tp_neuro, tp_neuro,
                a.tp_cardio, tp_cardio, a.tp_psico, tp_psico, a.ds_acompanhamento,
                a.ds_alergia_aliment, a.ds_alergia_medicam, a.ds_remedios,
                c.ds_responsavel, c.co_responsavel,
                d.ano_sem, d.dt_matricula, d.tp_bolsa_escola, d.nu_bolsa_escola,
                d.nu_peso, d.nu_altura, d.tp_apto_ed_fisica, d.st_ens_religioso, d.ds_situacao_aluno,
                f.co_unidade, g.ds_escola, f.ds_unidade, e.tp_ano_letivo, d.nu_uniforme, d.nu_pe
         from s_aluno          a,
              s_respons_aluno  b,
              s_responsavel    c,
              s_aluno_per_unid d,
              s_periodounidade e,
              s_unidade        f,
              s_escola         g,
              s_origem_escola  h
         where a.co_aluno         = b.co_aluno (+)
           and b.co_responsavel   = c.co_responsavel (+)
           and b.co_unidade       = c.co_unidade (+)
           and a.co_aluno         = d.co_aluno
           and d.co_unidade       = e.co_unidade
           and d.ano_sem          = e.ano_sem
           and e.co_unidade       = f.co_unidade
           and f.co_unidade       = g.co_unidade
           and a.co_origem_escola = h.co_origem_escola (+)
           and e.ano_sem          = @p_periodo
           and a.co_aluno         = rpad(@p_matricula,12,' ')
   Elsif @p_dados = 'TURMA' Begin
         select a.ano_sem, b.dt_movimentacao, b.st_movimentacao, c.co_turno,
                c.co_letra_turma, c.co_bloco, c.ds_turma, d.ds_curso, e.sg_serie,
                f.ds_sala, a.co_unidade, a.co_aluno, b.nu_chamada,
                h.ds_aluno, h.ds_mae, h.dt_nascimento, h.tp_sexo_aluno, a.dt_matricula,
                i.ds_escola
         from s_aluno_per_unid a,
              s_aluno_turma    b,
              s_turma          c,
              s_curso          d,
              s_curso_serie    e,
              s_sala           f,
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
           and c.co_unidade    = i.co_unidade    (+)
           and a.co_aluno      = rpad(@p_matricula,12,' ')
           and a.ano_sem       = @p_periodo
   Elsif @p_dados = 'APROVEIT' Begin
         select b.sg_serie, c.ds_disciplina, b.id_exame, b.nu_nota,
                b.nu_aulas_dadas, b.nu_faltas, d.ds_escola
         from s_aluno_per_unid a,
              s_aluno_aproveit b,
              s_disciplina     c,
              s_escola         d
         where a.ano_sem       = b.ano_sem       (+)
           and a.co_unidade    = b.co_unidade    (+)
           and a.co_aluno      = b.co_aluno      (+)
           and b.co_disciplina = c.co_disciplina (+)
           and b.ano_sem       = c.ano_sem       (+)
           and b.co_unidade    = c.co_unidade    (+)
           and a.co_unidade    = d.co_unidade    (+)
           and a.co_aluno      = rpad(@p_matricula,12,' ')
           and a.ano_sem       = @p_periodo
   Elsif @p_dados = 'DEPEND' Begin
         select b.d@p_serie, c.ds_disciplina, b.nu_nota, a.co_aluno,
                b.nu_aulas_dadas, b.nu_faltas, d.ds_escola
         from s_aluno_per_unid a,
              s_aluno_dependenc b,
              s_disciplina     c,
              s_escola         d
         where a.ano_sem       = b.ano_sem       (+)
           and a.co_aluno      = b.co_aluno      (+)
           and a.co_unidade    = b.co_unidade    (+)
           and b.co_disciplina = c.co_disciplina (+)
           and b.ano_sem       = c.ano_sem       (+)
           and b.co_unidade    = c.co_unidade    (+)
           and a.co_unidade    = d.co_unidade    (+)
           and a.co_aluno      = rpad(@p_matricula,12,' ')
           and a.ano_sem       = @p_periodo
   Elsif @p_dados = 'ADAPT' Begin
         select b.sg_serie, c.ds_disciplina, b.nu_nota, a.co_aluno,
                b.nu_aulas_dadas, b.nu_faltas, d.ds_escola
         from s_aluno_per_unid  a,
              s_aluno_adaptacao b,
              s_disciplina      c,
              s_escola          d
         where a.ano_sem       = b.ano_sem       (+)
           and a.co_aluno      = b.co_aluno      (+)
           and a.co_unidade    = b.co_unidade    (+)
           and b.co_disciplina = c.co_disciplina (+)
           and b.ano_sem       = c.ano_sem       (+)
           and b.co_unidade    = c.co_unidade    (+)
           and a.co_unidade    = d.co_unidade    (+)
           and a.co_aluno      = rpad(@p_matricula,12,' ')
           and a.ano_sem       = @p_periodo
   Elsif @p_dados = 'BOLETIM' Begin
         select a.nu_nota_b1, a.nu_faltas_b1, a.nu_nota_b2, a.nu_faltas_b2, a.co_unidade,
                a.nu_nota_b3, a.nu_faltas_b3, a.nu_nota_b4, a.nu_faltas_b4,
                a.nu_media_anual, a.nu_recu@p_especial, f.ds_ordem_imp,
                c.ds_turma, d.ds_curso, c.co_turno, c.co_letra_turma, e.ds_sala,
                e.co_bloco, f.ds_disciplina, h.descr_serie, i.ds_aluno, d.nu_media_nota,
                i.ds_aluno, i.co_aluno, j.ds_escola
           from s_nota             a,
                s_aluno_turma_disc b,
                s_turma            c,
                s_curso            d,
                s_sala             e,
                s_disciplina       f,
                s_curso_serie      g,
                s_serie            h,
                s_aluno            i,
                s_escola           j
          where b.co_aluno        = a.co_aluno      (+)
            and b.ano_sem         = a.ano_sem       (+)
            and b.co_unidade      = a.co_unidade    (+)
            and b.co_disciplina   = a.co_disciplina (+)
            and b.co_turma        = c.co_turma      (+)
            and b.ano_sem         = c.ano_sem       (+)
            and b.co_unidade      = c.co_unidade    (+)
            and b.co_curso        = d.co_curso      (+)
            and b.ano_sem         = d.ano_sem       (+)
            and b.co_unidade      = d.co_unidade    (+)
            and c.co_sala         = e.co_sala       (+)
            and c.co_unidade      = e.co_unidade    (+)
            and b.co_disciplina   = f.co_disciplina (+)
            and b.ano_sem         = f.ano_sem       (+)
            and b.co_unidade      = f.co_unidade    (+)
            and b.co_seq_serie    = g.co_seq_serie  (+)
            and b.co_curso        = g.co_curso      (+)
            and b.ano_sem         = g.ano_sem       (+)
            and b.co_unidade      = g.co_unidade    (+)
            and g.sg_serie        = h.sg_serie      (+)
            and b.co_aluno        = i.co_aluno      (+)
            and b.co_unidade      = j.co_unidade    (+)
            and b.co_aluno        = rpad(@p_matricula,12,' ')
            and b.ano_sem         = @p_periodo
   End Else Begin
         select getdate() from dual
   End
end 


--
-- Creating procedure SP_GETSTUDENTLIST
-- ====================================
--
create procedure SP_GetStudentList
   (@p_periodo     int,
    @p_regional    varchar() = null,
    @p_aluno       varchar() = null,
    @p_responsavel varchar() = null,
    @p_pai         varchar() = null,
    @p_mae         varchar() = null,
    @p_matricula   varchar() = null,
    @p_unidade     int = null,
    @p_cpf         varchar() = null,
    @p_tipo_resp   int = null) as
begin
   -- Recupera os alunos por período e regional
      select distinct
             d.ano_sem, a.co_aluno, a.ds_aluno, c.ds_responsavel,
             f.co_unidade, g.ds_escola, c.co_responsavel, c.nu_cpf,
             h.co_tip_responsavel, h.ds_tip_responsavel, a.ds_pai, a.ds_mae
        from s_aluno            a,
             s_respons_aluno    b,
             s_responsavel      c,
             s_aluno_per_unid   d,
             s_periodounidade   e,
             s_unidade          f,
             s_escola           g,
             s_tipo_responsavel h
       where a.co_aluno           = b.co_aluno
         and b.co_responsavel     = c.co_responsavel
         and b.co_unidade         = c.co_unidade
         and b.co_unidade         = g.co_unidade
         and c.co_tip_responsavel = h.co_tip_responsavel
         and a.co_aluno           = d.co_aluno
         and d.co_unidade         = e.co_unidade
         and d.ano_sem            = e.ano_sem
         and e.co_unidade         = f.co_unidade
         and f.co_unidade         = g.co_unidade
         and e.ano_sem            = @p_periodo
         and (@p_regional          = 0 or (@p_regional > 0 and g.co_sigre like @p_regional+'%'))
         and (@p_aluno             is null or (@p_aluno is not null and a.ds_aluno like @p_aluno))
         and (@p_responsavel       is null or (@p_responsavel is not null and c.ds_responsavel like @p_responsavel))
         and (@p_pai               is null or (@p_pai is not null and a.ds_pai like @p_pai))
         and (@p_mae               is null or (@p_mae is not null and a.ds_mae like @p_mae))
         and (@p_matricula         is null or (@p_matricula is not null and a.co_aluno = lpad(@p_matricula,12,' ')))
         and (@p_unidade           is null or (@p_unidade is not null and g.co_unidade = @p_unidade))
         and (@p_cpf               is null or (@p_cpf is not null and c.nu_cpf = lpad(@p_cpf,14,' ')))
         and (@p_tipo_resp         is null or (@p_tipo_resp is not null and c.Co_tip_Responsavel = @p_tipo_resp))
end 


--
-- Creating procedure SP_GETSTUDENTREL
-- ===================================
--
create procedure SP_GetStudentRel
   (@p_periodo      int,
    @p_regional     varchar() = null,
    @p_materia      int   = null,
    @p_aluno        varchar() = null,
    @p_matricula    char()     = null,
    @p_unidade      int   = null,
    @p_turma        int   = null,
    @p_serie        char()     = null,
    @p_modalidade   int   = null,
    @p_turno        char()     = null,
    @p_origem       int   = null,
    @p_situacao     char()     = null,
    @p_movimentacao char()     = null,
    @p_sexo         char()     = null,
    @p_faixa_i      int   = null,
    @p_faixa_f      int   = null,
    @p_mat_i        datetime = null,
    @p_mat_f        datetime = null,
    @p_nasc_i       datetime = null,
    @p_nasc_f       datetime = null) as
begin
If @p_materia is null Begin
   -- Recupera os alunos por período e regional
      select a.ano_sem, a.ds_situacao_aluno, a.co_unidade, a.co_aluno, a.dt_matricula,
             b.dt_movimentacao, b.st_movimentacao, b.nu_chamada,
             c.co_turma, c.co_turno, c.co_letra_turma, c.co_bloco, c.ds_turma,
             d.ds_curso,
             e.sg_serie, e.co_seq_serie,
             f.ds_sala,
             h.ds_aluno, h.ds_mae, h.dt_nascimento, trunc(months_between(getdate(),h.dt_nascimento)12,0) idade,
             h.tp_sexo_aluno,
             i.ds_escola, i.ds_gre, substring(i.co_sigre,1,2) regional,
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
        and a.ano_sem          = @p_periodo
        and (@p_regional        = 0 or (@p_regional > 0 and I.co_sigre like @p_regional+'%'))
        and (@p_aluno           is null or (@p_aluno        is not null and h.ds_aluno          like @p_aluno))
        and (@p_matricula       is null or (@p_matricula    is not null and h.co_aluno          = @p_matricula))
        and (@p_unidade         is null or (@p_unidade      is not null and i.co_unidade        = @p_unidade))
        and (@p_turma           is null or (@p_turma        is not null and c.co_turma          = @p_turma))
        and (@p_serie           is null or (@p_serie        is not null and e.sg_serie          = @p_serie))
        and (@p_modalidade      is null or (@p_modalidade   is not null and j.co_tipo_curso     = @p_modalidade))
        and (@p_turno           is null or (@p_turno        is not null and c.co_turno          = @p_turno))
        and (@p_origem          is null or (@p_origem       is not null and k.co_origem_escola  = @p_origem))
        and (@p_situacao        is null or (@p_situacao     is not null and a.ds_situacao_aluno = @p_situacao))
        and (@p_movimentacao    is null or (@p_movimentacao is not null and b.st_movimentacao   = @p_movimentacao))
        and (@p_sexo            is null or (@p_sexo         is not null and h.tp_sexo_aluno     = @p_sexo))
        and (@p_faixa_i         is null or (@p_faixa_i      is not null and trunc(months_between(getdate(),h.dt_nascimento)12,0) between @p_faixa_i and @p_faixa_f))
        and (@p_mat_i           is null or (@p_mat_i        is not null and a.dt_matricula      between @p_mat_i and @p_mat_f))
        and (@p_nasc_i          is null or (@p_nasc_i       is not null and h.dt_nascimento     between @p_nasc_i and @p_nasc_f))
End Else Begin
   -- Recupera os alunos por período e regional
      select a.ano_sem, a.ds_situacao_aluno, a.co_unidade, a.co_aluno, a.dt_matricula,
             b.dt_movimentacao, b.st_movimentacao, b.nu_chamada,
             c.co_turma, c.co_turno, c.co_letra_turma, c.co_bloco, c.ds_turma,
             d.ds_curso,
             e.sg_serie, e.co_seq_serie,
             f.ds_sala,
             h.ds_aluno, h.ds_mae, h.dt_nascimento, trunc(months_between(getdate(),h.dt_nascimento)12,0) idade,
             h.tp_sexo_aluno,
             i.ds_escola, i.ds_gre, substring(i.co_sigre,1,2) regional,
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
        and a.ano_sem            = @p_periodo
        and m.co_tipo_disciplina = @p_materia
        and (@p_regional          = 0 or (@p_regional > 0 and I.co_sigre like @p_regional+'%'))
        and (@p_aluno             is null or (@p_aluno        is not null and h.ds_aluno          like @p_aluno))
        and (@p_matricula         is null or (@p_matricula    is not null and h.co_aluno          = @p_matricula))
        and (@p_unidade           is null or (@p_unidade      is not null and i.co_unidade        = @p_unidade))
        and (@p_turma             is null or (@p_turma        is not null and c.co_turma          = @p_turma))
        and (@p_serie             is null or (@p_serie        is not null and e.sg_serie          = @p_serie))
        and (@p_modalidade        is null or (@p_modalidade   is not null and j.co_tipo_curso     = @p_modalidade))
        and (@p_turno             is null or (@p_turno        is not null and c.co_turno          = @p_turno))
        and (@p_origem            is null or (@p_origem       is not null and k.co_origem_escola  = @p_origem))
        and (@p_situacao          is null or (@p_situacao     is not null and a.ds_situacao_aluno = @p_situacao))
        and (@p_movimentacao      is null or (@p_movimentacao is not null and b.st_movimentacao   = @p_movimentacao))
        and (@p_sexo              is null or (@p_sexo         is not null and h.tp_sexo_aluno     = @p_sexo))
        and (@p_faixa_i           is null or (@p_faixa_i      is not null and trunc(months_between(getdate(),h.dt_nascimento)12,0) between @p_faixa_i and @p_faixa_f))
        and (@p_mat_i             is null or (@p_mat_i        is not null and a.dt_matricula      between @p_mat_i and @p_mat_f))
        and (@p_nasc_i            is null or (@p_nasc_i       is not null and h.dt_nascimento     between @p_nasc_i and @p_nasc_f))
End
end 


--
-- Creating procedure SP_GETTURMALIST
-- ==================================
--
--
-- Creating procedure SP_GETTURNDATA
-- =================================
--
create procedure SP_GetTurnData (@p_co_turno        char()) as
begin
   -- Recupera os dados do ambiente
      select * from s_turno where co_turno = @p_co_turno
end 


--
-- Creating procedure SP_GETTURNLIST
-- =================================
--
--
-- Creating procedure SP_GETUNITREL
-- ================================
--
create procedure SP_GetUnitRel
   (@p_periodo      int,
    @p_regional     varchar() = null,
    @p_modalidade   int = null,
    @p_dif          varchar() = null) as
begin
   If @p_regional = 0 Begin
      -- Recupera as unidades por período
         If @p_modalidade = 0 Begin
         -- Recupera as unidades sem modalidade de ensino
            select distinct a.co_unidade, a.ds_unidade, a.dt_atualizacao, a.nu_alunosativos,
                   a.nu_matriculados, a.nu_ativos,b.ds_gre, substring(b.co_sigre,1,2) regional,
                   b.co_sigre, c.total_unidades, h.ano_sem, b.ds_escola, l.ds_versao, a.nu_alunoseja1,
                   a.nu_alunoseja2, a.nu_semturma
              from s_unidade a,
                   s_escola  b,
                   (select distinct e.regional, count(*) total_unidades
                     from s_unidade d,
                          (select distinct substring(co_sigre,1,2) regional, co_unidade from s_escola) e,
                          s_periodounidade i
                   where d.co_unidade  = e.co_unidade (+)
                     and d.co_unidade  = i.co_unidade (+)
                     and i.ano_sem (+) = @p_periodo
                   having count(*) > 1
                   group  by e.regional) c,
                   s_periodounidade h,
                   s_versao         l
             where a.co_unidade          = b.co_unidade    (+)
               and substring(b.co_sigre,1,2)= c.regional      (+)
               and a.co_unidade          = h.co_unidade    (+)
               and a.co_unidade          = l.co_unidade    (+)
               and ((@p_dif = 'N') or (@p_dif = 'S' and IsNull(a.nu_alunosativos,0) <> IsNull(a.nu_ativos,0)))
               and h.ano_sem        (+)  = @p_periodo
         End Else Begin
         -- Recupera as unidades com modalidade de ensino
           select distinct a.co_unidade, a.ds_unidade, a.dt_atualizacao, a.nu_alunosativos,
                   a.nu_matriculados, a.nu_ativos,b.ds_gre, substring(b.co_sigre,1,2) regional,
                   b.co_sigre, c.total_unidades, h.ano_sem, g.co_tipo_curso, j.ds_curso,
                   b.ds_escola, l.ds_versao, a.nu_alunoseja1,
                   a.nu_alunoseja2, a.nu_semturma
              from s_unidade a,
                   s_escola  b,
                   (select distinct e.regional, count(distinct(y.co_unidade)) total_unidades
                     from s_unidade d,
                          (select distinct substring(co_sigre,1,2) regional, co_unidade from s_escola) e,
                          s_periodounidade i,
                          s_tipo_curso     x,
                          s_curso          y
                   where d.co_unidade    = e.co_unidade   (+)
                     and d.co_unidade    = i.co_unidade   (+)
                     and i.ano_sem   (+) = @p_periodo
                     and i.co_unidade    = y.co_unidade   (+)
                     and i.ano_sem       = y.ano_sem      (+)
                     and y.co_tipo_curso = x.co_tipo_curso(+)
                     and x.co_tipo_curso = @p_modalidade
                   having count(*) > 1
                   group  by e.regional) c,
                   s_tipo_curso     g,
                   s_periodounidade h,
                   s_curso          j,
                   s_versao         l
             where a.co_unidade          = b.co_unidade    (+)
               and substring(b.co_sigre,1,2)= c.regional      (+)
               and a.co_unidade          = h.co_unidade    (+)
               and h.ano_sem         (+) = @p_periodo
               and h.co_unidade          = j.co_unidade    (+)
               and h.ano_sem             = j.ano_sem       (+)
               and j.co_tipo_curso       = g.co_tipo_curso (+)
               and a.co_unidade          = l.co_unidade
               and ((@p_dif = 'N') or (@p_dif = 'S' and IsNull(a.nu_alunosativos,0) <> IsNull(a.nu_ativos,0)))
               and g.co_tipo_curso       = @p_modalidade
         End
   End Else Begin
      -- Recupera os alunos por período e regional
         If @p_modalidade = 0 Begin
         -- Recupera as unidades sem modalidade de ensino
           select distinct a.co_unidade, a.ds_unidade, a.dt_atualizacao, a.nu_alunosativos,
                   a.nu_matriculados, a.nu_ativos,b.ds_gre, substring(b.co_sigre,1,2) regional,
                   b.co_sigre, c.total_unidades, h.ano_sem, b.ds_escola, l.ds_versao, a.nu_alunoseja1,
                   a.nu_alunoseja2, a.nu_semturma
              from s_unidade a,
                   s_escola  b,
                   (select distinct e.regional, count(*) total_unidades
                     from s_unidade d,
                          (select distinct substring(co_sigre,1,2) regional, co_unidade from s_escola) e,
                          s_periodounidade i
                   where d.co_unidade = e.co_unidade (+)
                     and d.co_unidade = i.co_unidade (+)
                     and i.ano_sem (+)= @p_periodo
                   having count(*) > 1
                   group  by e.regional) c,
                   s_periodounidade h,
                   s_versao         l
             where a.co_unidade          = b.co_unidade    (+)
               and substring(b.co_sigre,1,2)= c.regional      (+)
               and a.co_unidade          = h.co_unidade    (+)
               and a.co_unidade          = l.co_unidade    (+)
               and ((@p_dif = 'N') or (@p_dif = 'S' and IsNull(a.nu_alunosativos,0) <> IsNull(a.nu_ativos,0)))
               and h.ano_sem        (+)  = @p_periodo
               and b.co_sigre         like @p_regional+'%'
         End Else Begin
         -- Recupera as unidades sem modalidade de ensino
            select distinct a.co_unidade, a.ds_unidade, a.dt_atualizacao, a.nu_alunosativos,
                   a.nu_matriculados, a.nu_ativos,b.ds_gre, substring(b.co_sigre,1,2) regional,
                   b.co_sigre, c.total_unidades, h.ano_sem, g.co_tipo_curso, j.ds_curso,
                   b.ds_escola, l.ds_versao, a.nu_alunoseja1,
                   a.nu_alunoseja2, a.nu_semturma
              from s_unidade a,
                   s_escola  b,
                   (select distinct e.regional, count(distinct(y.co_unidade)) total_unidades
                     from s_unidade d,
                          (select distinct substring(co_sigre,1,2) regional, co_unidade from s_escola) e,
                          s_periodounidade i,
                          s_tipo_curso     x,
                          s_curso          y
                   where d.co_unidade    = e.co_unidade   (+)
                     and d.co_unidade    = i.co_unidade   (+)
                     and i.ano_sem   (+) = @p_periodo
                     and i.co_unidade    = y.co_unidade   (+)
                     and i.ano_sem       = y.ano_sem      (+)
                     and y.co_tipo_curso = x.co_tipo_curso(+)
                     and x.co_tipo_curso = @p_modalidade
                   having count(*) > 1
                   group  by e.regional) c,
                   s_tipo_curso     g,
                   s_periodounidade h,
                   s_curso          j,
                   s_versao         l
             where a.co_unidade          = b.co_unidade    (+)
               and substring(b.co_sigre,1,2)= c.regional      (+)
               and a.co_unidade          = h.co_unidade    (+)
               and h.ano_sem         (+) = @p_periodo
               and h.co_unidade          = j.co_unidade    (+)
               and h.ano_sem             = j.ano_sem       (+)
               and j.co_tipo_curso       = g.co_tipo_curso (+)
               and a.co_unidade          = l.co_unidade
               and ((@p_dif = 'N') or (@p_dif = 'S' and IsNull(a.nu_alunosativos,0) <> IsNull(a.nu_ativos,0)))
               and g.co_tipo_curso       = @p_modalidade
               and b.co_sigre         like @p_regional+'%'
         End
   End
end 


--
-- Creating procedure SP_GETVERSIONLIST
-- ====================================
--
--
-- Creating procedure SP_PUTSAMBIENTE
-- ==================================
--
create procedure SP_PutSAmbiente
   (@p_operacao                 varchar(),
    @p_chave                    int = null,
    @p_ds_ambiente              varchar()
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into s_ambiente (co_seq_ambiente, ds_ambiente)
         (select co_seq_ambiente.nextval,
                 rtrim(upper(@p_ds_ambiente))
            from dual
         )
   Elsif @p_operacao = 'A' Begin
      -- Altera registro
      update s_ambiente set
         ds_ambiente          = rtrim(upper(@p_ds_ambiente))
      where co_seq_ambiente   = @p_chave
   Elsif @p_operacao = 'E' Begin
      -- Exclui registro
      delete s_ambiente where co_seq_ambiente = @p_chave
   End
end 


--
-- Creating procedure SP_PUTSAREAATUACAO
-- =====================================
--
create procedure SP_PutSAreaAtuacao
   (@p_operacao                 varchar(),
    @p_chave                    int = null,
    @p_co_area_atuacao          int = null,
    @p_ds_area_atuacao          varchar()
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into s_area_atuacao (co_area_atuacao, ds_area_atuacao)
      values(
                 @p_co_area_atuacao,
                 rtrim(upper(@p_ds_area_atuacao))
         )
   Elsif @p_operacao = 'A' Begin
      -- Altera registro
      update s_area_atuacao set
         co_area_atuacao      = @p_co_area_atuacao,
         ds_area_atuacao      = rtrim(upper(@p_ds_area_atuacao))
      where co_area_atuacao   = @p_chave
   Elsif @p_operacao = 'E' Begin
      -- Exclui registro
      delete s_area_atuacao where co_area_atuacao = @p_chave
   End
end 


--
-- Creating procedure SP_PUTSCARGO
-- ===============================
--
create procedure SP_PutSCargo
   (@p_operacao                 varchar(),
    @p_chave                    varchar(),
    @p_co_cargo                 varchar(),
    @p_ds_cargo                 varchar()
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into s_cargo (co_cargo, ds_cargo)
      values(
                 @p_co_cargo,
                 rtrim(upper(@p_ds_cargo))
         )
   Elsif @p_operacao = 'A' Begin
      -- Altera registro
      update s_cargo set
         co_cargo      = @p_co_cargo,
         ds_cargo      = rtrim(upper(@p_ds_cargo))
      where co_cargo   = @p_chave
   Elsif @p_operacao = 'E' Begin
      -- Exclui registro
      delete s_cargo where co_cargo = @p_chave
   End
end 


--
-- Creating procedure SP_PUTSDISCPER
-- =================================
--
create procedure SP_PutSDiscPer
   (@p_operacao               varchar(),
    @p_sg_serie               varchar(),
    @p_co_tipo_disciplina     int,
    @p_co_grade_curric        int,
    @p_co_tipo_curso          int,
    @p_ano                    int,
    @p_turno                  varchar(),
    @p_carga_horaria_sem      varchar(),
    @p_tp_disciplina          varchar(),
    @p_co_disciplina          varchar(),
    @p_ds_disciplina          varchar(),
    @p_nu_ordem_imp           int,
    @p_tp_avaliacao           varchar(),
    @p_tp_digitacao           varchar(),
    @p_tp_impressao           varchar(),
    @p_st_reprova             varchar()
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into s_disciplina_per (sg_serie, co_tipo_disciplina, co_grade_curric, co_tipo_curso, ano, turno, carga_horaria_sem, tp_disciplina, co_disciplina, ds_disciplina, nu_ordem_imp, tp_avaliacao, tp_digitacao, tp_impressao, st_reprova)
      values(    rtrim(@p_sg_serie),
                 @p_co_tipo_disciplina,
                 @p_co_grade_curric,
                 @p_co_tipo_curso,
                 @p_ano,
                 @p_turno,
                 @p_carga_horaria_sem,
                 rtrim(upper(@p_tp_disciplina)),
                 rtrim(upper(@p_co_disciplina)),
                 rtrim(upper(@p_ds_disciplina)),
                 @p_nu_ordem_imp,
                 @p_tp_avaliacao,
                 @p_tp_digitacao,
                 @p_tp_impressao,
                 @p_st_reprova
         )
   Elsif @p_operacao = 'A' Begin
   -- Altera registro
      update s_disciplina_per set
         sg_serie            = rtrim(@p_sg_serie),
         co_tipo_disciplina  = @p_co_tipo_disciplina,
         co_grade_curric     = @p_co_grade_curric,
         co_tipo_curso       = @p_co_tipo_curso,
         ano                 = @p_ano,
         turno               = @p_turno,
         carga_horaria_sem   = @p_carga_horaria_sem,
         tp_disciplina       = rtrim(upper(@p_tp_disciplina)),
         co_disciplina       = rtrim(upper(@p_co_disciplina)),
         ds_disciplina       = rtrim(upper(@p_ds_disciplina)),
         nu_ordem_imp        = @p_nu_ordem_imp,
         tp_avaliacao        = @p_tp_avaliacao,
         tp_digitacao        = @p_tp_digitacao,
         tp_impressao        = @p_tp_impressao,
         st_reprova          = @p_st_reprova
      where sg_serie           = @p_sg_serie
        and co_tipo_disciplina = @p_co_tipo_disciplina
        and co_grade_curric    = @p_co_grade_curric

   Elsif @p_operacao = 'E' Begin
   -- Exclui registro
     delete s_disciplina_per
       where sg_serie              = @p_sg_serie
         and co_grade_curric       = @p_co_grade_curric
         and co_tipo_disciplina    = @p_co_tipo_disciplina
   End
end 


--
-- Creating procedure SP_PUTSGRADE_CURR
-- ====================================
--
create procedure SP_PutSGrade_Curr
   (@p_operacao               varchar(),
    @p_chave                  int = null,
    @p_co_tipo_curso          int,
    @p_ano                    int,
    @p_turno                  varchar(),
    @p_dt_grade               datetime,
    @p_nu_semanas             int,
    @p_nu_grade               varchar(),
    @p_ds_grade               varchar()
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into s_grade_curric (co_grade_curric, co_tipo_curso, ano, turno, dt_grade, nu_semanas, nu_grade, ds_grade)
         (select co_grade_curric.nextval,
                 @p_co_tipo_curso,
                 @p_ano,
                 rtrim(upper(@p_turno)),
                 @p_dt_grade,
                 @p_nu_semanas,
                 rtrim(@p_nu_grade),
                 rtrim(upper(@p_ds_grade))
            from dual
         )
   Elsif @p_operacao = 'A' Begin
      -- Altera registro
      update s_grade_curric set
         co_tipo_curso  = @p_co_tipo_curso ,
         ano            = @p_ano,
         turno          = rtrim(upper(@p_turno)),
         dt_grade       = @p_dt_grade,
         nu_semanas     = @p_nu_semanas,
         nu_grade       = rtrim(@p_nu_grade),
         ds_grade       = rtrim(upper(@p_ds_grade))
      where co_grade_curric    = @p_chave
   Elsif @p_operacao = 'E' Begin
      -- Exclui registro
      delete s_grade_curric where co_grade_curric = @p_chave
   End
end 


--
-- Creating procedure SP_PUTSORESCOLA
-- ==================================
--
create procedure SP_PutSOrEscola
   (@p_operacao                 varchar(),
    @p_chave                    int = null,
    @p_ds_origem_escola         varchar()
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into s_origem_escola (co_origem_escola, ds_origem_escola)
         (select co_origem_escola.nextval,
                 rtrim(upper(@p_ds_origem_escola))
            from dual
         )
   Elsif @p_operacao = 'A' Begin
      -- Altera registro
      update s_origem_escola set
         ds_origem_escola      = rtrim(upper(@p_ds_origem_escola))
      where co_origem_escola   = @p_chave
   Elsif @p_operacao = 'E' Begin
      -- Exclui registro
      delete s_origem_escola where co_origem_escola = @p_chave
   End
end 


--
-- Creating procedure SP_PUTSPERIODO
-- =================================
--
create procedure SP_PutSPeriodo
   (@p_operacao               varchar(),
    @p_turno                  varchar(),
    @p_co_grade_curric        int,
    @p_ano                    int,
    @p_co_tipo_curso          int,
    @p_sg_serie               varchar()
   ) as
begin
   If @p_operacao = 'L' Begin
      -- Insere registro
      insert into s_periodo (turno, co_grade_curric, ano, co_tipo_curso, sg_serie)
      values(    rtrim(@p_turno),
                 @p_co_grade_curric,
                 @p_ano,
                 @p_co_tipo_curso,
                 rtrim(upper(@p_sg_serie))
         )
   Elsif @p_operacao = 'E' Begin
     delete s_periodo where sg_serie = @p_sg_serie and co_grade_curric = @p_co_grade_curric
   End
end 


--
-- Creating procedure SP_PUTSSERIE
-- ===============================
--
create procedure SP_PutSSerie
   (@p_operacao               varchar(),
    @p_chave                  varchar() = null,
    @p_sg_serie               varchar() = null,
    @p_co_tipo_curso          int,
    @p_ds_serie               varchar()
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into s_serie (sg_serie, co_tipo_curso, descr_serie)
      values(
                 rtrim(upper(@p_sg_serie)),
                 @p_co_tipo_curso,
                 rtrim(upper(@p_ds_serie))
         )
   Elsif @p_operacao = 'A' Begin
      -- Altera registro
      update s_serie set
         sg_serie       = rtrim(upper(@p_sg_serie)),
         co_tipo_curso  = @p_co_tipo_curso ,
         descr_serie    = rtrim(upper(@p_ds_serie))
      where sg_serie    = @p_chave
   Elsif @p_operacao = 'E' Begin
      -- Exclui registro
      delete s_serie where sg_serie = @p_chave
   End
end 


--
-- Creating procedure SP_PUTSTIPOCURSO
-- ===================================
--
create procedure SP_PutSTipoCurso
   (@p_operacao                 varchar(),
    @p_chave                    int = null,
    @p_sg_tipo_curso            varchar(),
    @p_ds_tipo_curso            varchar()
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into s_tipo_curso (co_tipo_curso, sg_tipo_curso, ds_tipo_curso)
         (select co_tipo_curso.nextval,
                 rtrim(upper(@p_sg_tipo_curso)),
                 rtrim(upper(@p_ds_tipo_curso))
            from dual
         )
   Elsif @p_operacao = 'A' Begin
      -- Altera registro
      update s_tipo_curso set
         sg_tipo_curso          = rtrim(upper(@p_sg_tipo_curso)),
         ds_tipo_curso          = rtrim(upper(@p_ds_tipo_curso))
      where co_tipo_curso   = @p_chave
   Elsif @p_operacao = 'E' Begin
      -- Exclui registro
      delete s_tipo_curso where co_tipo_curso = @p_chave
   End
end 


--
-- Creating procedure SP_PUTSTIPODISC
-- ==================================
--
create procedure SP_PutSTipoDisc
   (@p_operacao                 varchar(),
    @p_chave                    int = null,
    @p_sg_disciplina            char(),
    @p_ds_tipo_disciplina       varchar()
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into s_tipo_disciplina (co_tipo_disciplina, sg_disciplina, ds_tipo_disciplina)
         (select co_tipo_disciplina.nextval,
                 rtrim(upper(@p_sg_disciplina)),
                 rtrim(upper(@p_ds_tipo_disciplina))
            from dual
         )
   Elsif @p_operacao = 'A' Begin
      -- Altera registro
      update s_tipo_disciplina set
         sg_disciplina          = rtrim(upper(@p_sg_disciplina)),
         ds_tipo_disciplina     = rtrim(upper(@p_ds_tipo_disciplina))
      where co_tipo_disciplina  = @p_chave
   Elsif @p_operacao = 'E' Begin
      -- Exclui registro
      delete s_tipo_disciplina where co_tipo_disciplina = @p_chave
   End
end 


--
-- Creating procedure SP_PUTSTIPOSALA
-- ==================================
--
create procedure SP_PutSTipoSala
   (@p_operacao                 varchar(),
    @p_chave                    int = null,
    @p_ds_tipo_sala         varchar()
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into s_tipo_sala (co_tipo_sala, ds_tipo_sala)
         (select co_tipo_sala.nextval,
                 rtrim(upper(@p_ds_tipo_sala))
            from dual
         )
   Elsif @p_operacao = 'A' Begin
      -- Altera registro
      update s_tipo_sala set
         ds_tipo_sala      = rtrim(upper(@p_ds_tipo_sala))
      where co_tipo_sala   = @p_chave
   Elsif @p_operacao = 'E' Begin
      -- Exclui registro
      delete s_tipo_sala where co_tipo_sala = @p_chave
   End
end 


--
-- Creating procedure SP_PUTSTPAVALIACAO
-- =====================================
--
create procedure SP_PutSTPAvaliacao
   (@p_operacao                 varchar(),
    @p_chave                    int = null,
    @p_ds_tipo_avaliacao        varchar()
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into s_tipo_avaliacao (co_tipo_avaliacao, ds_tipo_avaliacao)
         (select co_tipo_avaliacao.nextval,
                 rtrim(upper(@p_ds_tipo_avaliacao))
            from dual
         )
   Elsif @p_operacao = 'A' Begin
      -- Altera registro
      update s_tipo_avaliacao set
         ds_tipo_avaliacao     = rtrim(upper(@p_ds_tipo_avaliacao))
      where co_tipo_avaliacao   = @p_chave
   Elsif @p_operacao = 'E' Begin
      -- Exclui registro
      delete s_tipo_avaliacao where co_tipo_avaliacao = @p_chave
   End
end 


--
-- Creating procedure SP_PUTSTURNO
-- ===============================
--
create procedure SP_PutSTurno
   (@p_operacao                 varchar(),
    @p_chave                    char() = null,
    @p_co_turno                 char() = null,
    @p_ds_turno                 varchar()
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into s_turno (co_turno, ds_turno)
      values(
                 @p_co_turno,
                 rtrim(upper(@p_ds_turno))
         )
   Elsif @p_operacao = 'A' Begin
      -- Altera registro
      update s_turno set
         co_turno       = @p_co_turno,
         ds_turno       = rtrim(upper(@p_ds_turno))
      where co_turno    = @p_chave
   Elsif @p_operacao = 'E' Begin
      -- Exclui registro
      delete s_turno where co_turno = @p_chave
   End
end 

