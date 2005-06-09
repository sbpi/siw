---------------------------------------------
-- Export file for user SIGE               --
-- Created by alexvp on 24/10/03, 12:56:21 --
---------------------------------------------

spool SP.log

prompt
prompt Creating sequence CO_GRADE_CURRIC
prompt =================================
prompt
create sequence CO_GRADE_CURRIC
minvalue 1
maxvalue 999999999999999999999999999
start with 91
increment by 1
cache 20;

prompt
prompt Creating sequence CO_ORIGEM_ESCOLA
prompt ==================================
prompt
create sequence CO_ORIGEM_ESCOLA
minvalue 1
maxvalue 999999999999999999999999999
start with 49
increment by 1
cache 20;

prompt
prompt Creating sequence CO_SEQ_AMBIENTE
prompt =================================
prompt
create sequence CO_SEQ_AMBIENTE
minvalue 1
maxvalue 9999999999999999999999999999
start with 14
increment by 1
nocache;

prompt
prompt Creating sequence CO_TIPO_AVALIACAO
prompt ===================================
prompt
create sequence CO_TIPO_AVALIACAO
minvalue 8
maxvalue 999999999999999999999999999
start with 48
increment by 1
cache 20;

prompt
prompt Creating sequence CO_TIPO_CURSO
prompt ===============================
prompt
create sequence CO_TIPO_CURSO
minvalue 8
maxvalue 999999999999999999999999999
start with 68
increment by 1
cache 20;

prompt
prompt Creating sequence CO_TIPO_DISCIPLINA
prompt ====================================
prompt
create sequence CO_TIPO_DISCIPLINA
minvalue 121
maxvalue 999999999999999999999999999
start with 143
increment by 1
nocache;

prompt
prompt Creating sequence CO_TIPO_SALA
prompt ==============================
prompt
create sequence CO_TIPO_SALA
minvalue 17
maxvalue 999999999999999999999999999
start with 37
increment by 1
cache 20;

prompt
prompt Creating procedure SP_GETAMBIENTDATA
prompt ====================================
prompt
create or replace procedure SP_GetAmbientData
   (p_co_seq_ambiente in  number,
    p_result     out sys_refcursor
   ) is
begin
   -- Recupera os dados do ambiente
   open p_result for 
      select * from s_ambiente where co_seq_ambiente = p_co_seq_ambiente;
end SP_GetAmbientData;
/

prompt
prompt Creating procedure SP_GETAMBIENTLIST
prompt ====================================
prompt
create or replace procedure SP_GetAmbientList
   (p_result    out sys_refcursor) is
begin
   -- Recupera os ambientes existentes
   open p_result for 
      select co_seq_ambiente, ds_ambiente 
        from s_ambiente
      order by co_seq_ambiente;
end SP_GetAmbientList;
/

prompt
prompt Creating procedure SP_GETANEEREL
prompt ================================
prompt
create or replace procedure SP_GetANEERel
   (p_periodo      in number,
    p_regional     in varchar2 default null,
    p_result       out sys_refcursor) is
begin
   If p_regional = 0 Then
      open p_result for
         select b.co_unidade, tp_anee, count(*) qtd_anee, ds_escola, ds_gre, substr(a.co_sigre,1,2) regional
           from s_aluno       b,
                s_escola      a
          where b.tp_anee     is not null 
            and a.co_unidade = b.co_unidade
          group by b.co_unidade, b.tp_anee, a.ds_escola, a.ds_gre, a.co_sigre;
   Else
      open p_result for
         select b.co_unidade, tp_anee, count(*) qtd_anee, ds_escola, ds_gre, substr(a.co_sigre,1,2) regional
           from s_aluno       b,
                s_escola      a
          where b.tp_anee     is not null 
            and a.co_unidade = b.co_unidade
            and a.co_sigre      like p_regional||'%'
          group by b.co_unidade, b.tp_anee, a.ds_escola, a.ds_gre, a.co_sigre;
   End If; 
end SP_GetANEERel;
/

prompt
prompt Creating procedure SP_GETATUAREADATA
prompt ====================================
prompt
create or replace procedure SP_GetAtuAreaData
   (p_co_area_atuacao in  number,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera os dados do ambiente
   open p_result for 
      select * from s_area_atuacao where co_area_atuacao = p_co_area_atuacao;
end SP_GetAtuAreaData;
/

prompt
prompt Creating procedure SP_GETATUAREALIST
prompt ====================================
prompt
create or replace procedure SP_GetAtuAreaList
   (p_result    out sys_refcursor) is
begin
   -- Recupera as área de atuações existentes
   open p_result for 
      select co_area_atuacao, ds_area_atuacao 
        from s_area_atuacao
      order by co_area_atuacao;
end SP_GetAtuAreaList;
/

prompt
prompt Creating procedure SP_GETAVALDATA
prompt =================================
prompt
create or replace procedure SP_GetAvalData
   (p_co_tipo_avaliacao in  number,
    p_result            out sys_refcursor
   ) is
begin
   -- Recupera os dados do tipo de avaliação
   open p_result for 
      select * from s_tipo_avaliacao where co_tipo_avaliacao = p_co_tipo_avaliacao;
end SP_GetAvalData;
/

prompt
prompt Creating procedure SP_GETAVALLIST
prompt =================================
prompt
create or replace procedure SP_GetAvalList
   (p_result    out sys_refcursor) is
begin
   -- Recupera os tipos de avaliações existentes
   open p_result for 
      select co_tipo_avaliacao, ds_tipo_avaliacao 
        from s_tipo_avaliacao
      order by co_tipo_avaliacao;
end SP_GetAvalList;
/

prompt
prompt Creating procedure SP_GETCALENDARLIST
prompt =====================================
prompt
create or replace procedure SP_GetCalendarList
   (p_periodo      in number,
    p_unidade      in char,
    p_result       out sys_refcursor) is
begin
   open p_result for
      select ds_calendario, co_calendario
        from s_calend_titulo
       where ano           = p_periodo
         and co_unidade    = p_unidade;
end SP_GetCalendarList;
/

prompt
prompt Creating procedure SP_GETCALENDARREL
prompt ====================================
prompt
create or replace procedure SP_GetCalendarRel
   (p_calendario   in number,
    p_result       out sys_refcursor) is
begin
   open p_result for
      select a.dt_calendario, b.ds_calendario, c.ds_dia_calendario
        from s_calendario      a,
             s_calend_titulo   b,
             s_dia_calendario  c
       where a.co_calendario     = b.co_calendario     (+)
         and a.co_unidade        = b.co_unidade        (+)
         and a.co_dia_calendario = c.co_dia_calendario (+)
         and a.co_unidade        = c.co_unidade        (+)
         and a.co_calendario = p_calendario;
end SP_GetCalendarRel;
/

prompt
prompt Creating procedure SP_GETCOMUNICREL
prompt ===================================
prompt
create or replace procedure SP_GetComunicRel
   (p_regional     in varchar2 default null,
    p_result       out sys_refcursor) is
begin
   If p_regional = 0 Then
   open p_result for
      select a.ds_arquivo, a.dt_recebimento, a.dt_process, ds_usuario,
             b.ds_escola, b.ds_gre, a.co_escola, substr(b.co_sigre,1,2) regional,
             a.nu_regional
        from s_comunicacao a,
             s_escola      b
       where a.co_escola   = b.co_unidade
         and b.co_sigre    like p_regional||'%';
    Else
    open p_result for
      select a.ds_arquivo, a.dt_recebimento, a.dt_process, ds_usuario,
             b.ds_escola, b.ds_gre, a.co_escola, substr(b.co_sigre,1,2) regional,
             a.nu_regional
        from s_comunicacao a,
             s_escola      b
       where a.co_escola   = b.co_unidade
         and b.co_sigre    like p_regional||'%';
    End If;
end SP_GetComunicRel;
/

prompt
prompt Creating procedure SP_GETCOURSETPDATA
prompt =====================================
prompt
create or replace procedure SP_GetCourseTPData
   (p_co_tipo_curso in  number,
    p_result     out sys_refcursor
   ) is
begin
   -- Recupera os dados do tipo de curso
   open p_result for 
      select * from s_tipo_curso where co_tipo_curso = p_co_tipo_curso;
end SP_GetCourseTPData;
/

prompt
prompt Creating procedure SP_GETCOURSETPLIST
prompt =====================================
prompt
create or replace procedure SP_GetCourseTPList
   (p_result    out sys_refcursor) is
begin
   -- Recupera os tipos de curso existentes
   open p_result for 
      select co_tipo_curso, sg_tipo_curso, ds_tipo_curso 
        from s_tipo_curso
      order by co_tipo_curso;
end SP_GetCourseTPList;
/

prompt
prompt Creating procedure SP_GETDISCTPDATA
prompt ===================================
prompt
create or replace procedure SP_GetDiscTPData
   (p_co_tipo_disciplina in  number,
    p_result             out sys_refcursor
   ) is
begin
   -- Recupera os dados do tipo da disciplina
   open p_result for 
      select * from s_tipo_disciplina where co_tipo_disciplina = p_co_tipo_disciplina;
end SP_GetDiscTPData;
/

prompt
prompt Creating procedure SP_GETDISCTPLIST
prompt ===================================
prompt
create or replace procedure SP_GetDiscTPList
   (p_result    out sys_refcursor) is
begin
   -- Recupera os tipos de diciplinas existentes
   open p_result for 
      select co_tipo_disciplina, sg_disciplina, ds_tipo_disciplina 
        from s_tipo_disciplina
      order by co_tipo_disciplina;
end SP_GetDiscTPList;
/

prompt
prompt Creating procedure SP_GETDOUBSTUDDATA
prompt =====================================
prompt
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

prompt
prompt Creating procedure SP_GETDOUBSTUDLIST
prompt =====================================
prompt
create or replace procedure SP_GetDoubStudList
   (p_periodo  in number,
    p_regional  in varchar2 default null,
    p_tipo      in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   If p_tipo = 'MATRICULA' Then
      If p_regional = 0 Then
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
            and x.ano_sem         = p_periodo
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
            and w.co_aluno        = z.co_aluno;
      Else 
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
            and x.ano_sem         = p_periodo
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
            and y.co_sigre      like p_regional||'%';
      End If;
   Else
      If p_regional = 0 Then
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
            and x.ano_sem         = p_periodo
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
            and w.dt_nascimento   = z.dt_nascimento;
      Else 
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
            and x.ano_sem         = p_periodo
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
            and y.co_sigre      like p_regional||'%';
      End If;
   End If;
end SP_GetDoubStudList;
/

prompt
prompt Creating procedure SP_GETFALTASREL
prompt ==================================
prompt
create or replace procedure SP_GetFaltasRel
   (p_periodo      in number,
    p_unidade      in number,
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
    group by d.sg_serie, b.ds_disciplina, b.co_disciplina, c.ds_escola, f.ds_tipo_curso, f.co_tipo_curso;
end SP_GetFaltasRel;
/

prompt
prompt Creating procedure SP_GETFUNCDATA
prompt =================================
prompt
create or replace procedure SP_GetFuncData
   (p_periodo   in number,
    p_codigo    in varchar2 default null,
    p_dados     in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   -- Recupera os alunos por período
   If p_dados = 'CABECALHO' or p_dados = 'CADASTRO' or p_dados = 'DIVERSO' Then
      open p_result for
         select distinct 
                a.co_funcionario, a.nu_matricula_mec, a.ds_funcionario, a.nu_cpf,
                a.tp_sexo, a.ds_apelido, a.dt_nascimento, a.ds_instrucao, a.ds_uf_nascimento,
                a.ds_naturalidade, a.ds_endereco, a.ds_bairro, a.ds_cidade, a.ds_uf_cidade,
                a.nu_cep, a.nu_telefone, a.nu_celular, a.ds_e_mail, a.tp_estado_civil,
                a.ds_conjuge, a.ds_pai, a.ds_mae, a.lotacao_princ, 
                a.nu_rg, a.ds_orgao_emissor, a.dt_emissao, a.nu_cpf, a.nu_registro,
                b.dt_admissao, b.nu_carga_contrato, b.nu_hora_entrada, b.nu_hora_saida,
                b.nu_hora_ini_almoc, b.nu_hora_fim_almoc, 
                trim(b.id_professor) id_professor, trim(b.st_cancelado) st_cancelado,
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
            and e.ano_sem        (+) = p_periodo
            and b.ano_sem        (+) = p_periodo
            and a.co_funcionario     = rpad(p_codigo,10,' ');
   Elsif p_dados = 'DISCIPLINA' Then
      open p_result for
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
            and e.ano_sem        (+) = p_periodo
            and b.ano_sem        (+) = p_periodo
            and a.co_funcionario     = rpad(p_codigo,10,' ');
   Elsif p_dados = 'GRADE' Then
      open p_result for
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
            and a.co_funcionario     = g.co_funcionario (+)
            and a.co_unidade         = g.co_unidade     (+)
            and f.co_cargo           = h.co_cargo       (+)
            and a.co_funcionario     = rpad(p_codigo,10,' ')
            and a.ano_sem            = p_periodo;                  
   Else
      open p_result for
         select sysdate from dual;
   End If;
end SP_GetFuncData;
/

prompt
prompt Creating procedure SP_GETFUNCLIST
prompt =================================
prompt
create or replace procedure SP_GetFuncList
   (p_periodo  in number,
    p_regional  in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   If p_regional = 0 Then
      -- Recupera os funcionarios por período
      open p_result for 
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
            and b.ano_sem        (+) = p_periodo;
   Else
      -- Recupera os funcionario por período e regional
      open p_result for 
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
            and b.ano_sem        (+) = p_periodo
            and c.co_sigre           like p_regional||'%';
   End If;
end SP_GetFuncList;
/

prompt
prompt Creating procedure SP_GETFUNCREL
prompt ================================
prompt
create or replace procedure SP_GetFuncRel
   (p_periodo  in number,
    p_regional  in varchar2 default null,
    p_bairro    in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   If p_regional = 0 Then
      -- Recupera os alunos por período
      open p_result for 
         select a.nu_matricula_mec, a.ds_funcionario, a.ds_instrucao, c.ds_cargo, d.ds_area_atuacao,
                a.ds_bairro bairro_func, e.ds_bairro bairro_unidade, d.co_area_atuacao,
                e.co_unidade, e.ds_unidade, a.dt_nascimento, a.tp_sexo, b.dt_admissao, 
                substr(f.co_sigre,1,2) regional, f.ds_gre, c.co_cargo
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
            and ((p_bairro is null) or
                 (p_bairro = 'S' and a.ds_bairro = e.ds_bairro) or
                 (p_bairro = 'N' and a.ds_bairro <> e.ds_bairro)
                )            
            and b.ano_sem          = p_periodo;
   Else
      -- Recupera os alunos por período e regional
      open p_result for 
         select a.nu_matricula_mec, a.ds_funcionario, a.ds_instrucao, c.ds_cargo, d.ds_area_atuacao,
                a.ds_bairro bairro_func, e.ds_bairro bairro_unidade, d.co_area_atuacao,
                e.co_unidade,e.ds_unidade, a.dt_nascimento, a.tp_sexo, b.dt_admissao, 
                substr(f.co_sigre,1,2) regional, f.ds_gre, c.co_cargo
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
            and ((p_bairro is null) or
                 (p_bairro = 'S' and a.ds_bairro = e.ds_bairro) or
                 (p_bairro = 'N' and a.ds_bairro <> e.ds_bairro)
                )
            and b.ano_sem         = p_periodo
            and f.co_sigre         like p_regional||'%';
   End If;
end SP_GetFuncRel;
/

prompt
prompt Creating procedure SP_GETMATDISCDATA
prompt ====================================
prompt
create or replace procedure SP_GetMatDiscData
   (p_co_grade_curric in  number,
    p_sg_serie        in  varchar2,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera as disciplinas da matriz curricular
   open p_result for 
      select a.*, b.descr_serie, c.ds_tipo_curso, d.ds_tipo_disciplina, d.sg_disciplina
      from s_disciplina_per  a, 
           s_serie           b,
           s_tipo_curso      c,
           s_tipo_disciplina d
      where a.co_grade_curric    = p_co_grade_curric 
        and a.sg_serie           = p_sg_serie
        and a.sg_serie           = b.sg_serie           (+)
        and a.co_tipo_curso      = c.co_tipo_curso      (+)
        and a.co_tipo_disciplina = d.co_tipo_disciplina (+);
end SP_GetMatDiscData;
/

prompt
prompt Creating procedure SP_GETMATDISCODATA
prompt =====================================
prompt
create or replace procedure SP_GetMatDiscOData
   (p_co_grade_curric      in  number,
    p_co_tipo_disciplina   in  number,
    p_sg_serie             in  varchar2,
    p_result               out sys_refcursor
   ) is
begin
   -- Recupera os dados de uma disciplina da matriz curricular
   open p_result for 
      select a.*, b.ds_tipo_disciplina
      from s_disciplina_per  a, 
           s_tipo_disciplina b
      where a.co_grade_curric    = p_co_grade_curric 
        and a.co_tipo_disciplina = p_co_tipo_disciplina
        and a.sg_serie           = p_sg_serie
        and a.co_tipo_disciplina = b.co_tipo_disciplina (+);
end SP_GetMatDiscOData;
/

prompt
prompt Creating procedure SP_GETMATRIXDATA
prompt ===================================
prompt
create or replace procedure SP_GetMatrixData
   (p_co_grade_curric in  number,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera os dados da matriz curricular
   open p_result for 
      select a.*, b.ds_tipo_curso
      from sige.s_grade_curric a,
           sige.s_tipo_curso   b
      where a.co_grade_curric = p_co_grade_curric
        and a.co_tipo_curso   = b.co_tipo_curso   (+);
end SP_GetMatrixData;
/

prompt
prompt Creating procedure SP_GETMATRIXLIST
prompt ===================================
prompt
create or replace procedure SP_GetMatrixList
   (p_result    out sys_refcursor) is
begin
   -- Recupera as matrizes existentes
   open p_result for 
      select a.*, b.ds_tipo_curso
        from s_grade_curric a,
             s_tipo_curso b
        where a.co_tipo_curso = b.co_tipo_curso
      order by co_grade_curric;
end SP_GetMatrixList;
/

prompt
prompt Creating procedure SP_GETMATSERDATA
prompt ===================================
prompt
create or replace procedure SP_GetMatSerData
   (p_co_grade_curric in  number,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera a lista de séries de uma matriz curricular
   open p_result for 
      select a.*, b.descr_serie, c.ds_tipo_curso
      from s_periodo        a, 
           s_serie          b,
           s_tipo_curso     c
      where a.co_grade_curric = p_co_grade_curric 
        and a.sg_serie        = b.sg_serie        (+)
        and a.co_tipo_curso   = c.co_tipo_curso   (+);
end SP_GetMatSerData;
/

prompt
prompt Creating procedure SP_GETMATSERLIST
prompt ===================================
prompt
create or replace procedure SP_GetMatSerList
   (p_co_grade_curric in  number,
    p_co_tipo_curso   in  number,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera as series existentes com exceção das já existentes na matriz
   open p_result for 
      select a.sg_serie, a.descr_serie, b.ds_tipo_curso, a.co_tipo_curso 
        from s_serie a,
             s_tipo_curso b
        where a.co_tipo_curso = p_co_tipo_curso
          and a.co_tipo_curso = b.co_tipo_curso
          and a.sg_serie not in (select sg_serie from s_periodo where co_grade_curric = p_co_grade_curric);
end SP_GetMatSerList;
/

prompt
prompt Creating procedure SP_GETMATSERODATA
prompt ====================================
prompt
create or replace procedure SP_GetMatSerOData
   (p_co_grade_curric in  number,
    p_sg_serie        in  varchar2,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera os dados da matriz curricular
   open p_result for 
      select a.*, b.descr_serie, b.sg_serie, c.ds_tipo_curso, d.ds_grade
      from s_periodo        a, 
           s_serie          b,
           s_tipo_curso     c,
           s_grade_curric   d
      where a.co_grade_curric = p_co_grade_curric 
        and a.sg_serie        = p_sg_serie
        and a.sg_serie        = b.sg_serie        (+)
        and a.co_tipo_curso   = c.co_tipo_curso   (+)
        and d.co_grade_curric = p_co_grade_curric;
end SP_GetMatSerOData;
/

prompt
prompt Creating procedure SP_GETPERIODOLIST
prompt ====================================
prompt
create or replace procedure SP_GetPeriodoList
   (p_result     out sys_refcursor
   ) is
begin
   -- Recupera os períodos disponíveis
   open p_result for 
      select distinct ano_sem,
             decode(trim(a.tp_ano_letivo),'A',substr(ano_sem,1,4),substr(ano_sem,1,4)||' - Semestre '||substr(ano_sem,5,1)) periodo
         from s_periodounidade a;
end SP_GetPeriodoList;
/

prompt
prompt Creating procedure SP_GETPOSITIONDATA
prompt =====================================
prompt
create or replace procedure SP_GetPositionData
   (p_co_cargo        in  varchar2,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera os dados do ambiente
   open p_result for 
      select * from s_cargo where co_cargo = p_co_cargo;
end SP_GetPositionData;
/

prompt
prompt Creating procedure SP_GETPOSITIONLIST
prompt =====================================
prompt
create or replace procedure SP_GetPositionList
   (p_result    out sys_refcursor) is
begin
   -- Recupera as área de atuações existentes
   open p_result for 
      select co_cargo, ds_cargo 
        from s_cargo
      order by co_cargo;
end SP_GetPositionList;
/

prompt
prompt Creating procedure SP_GETPROFREL
prompt ================================
prompt
create or replace procedure SP_GetProfRel
   (p_periodo     in number,
    p_regional    in varchar2 default null,
    p_modalidade  in number default null,
    p_serie       in varchar2 default null,
    p_turma       in number default null,
    p_disciplina  in number default null,
    p_turno       in varchar2 default null, 
    p_bairro      in varchar2 default null, 
    p_tipo        in varchar2 default null,
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
               from s_funcionario      a, 
                    s_unidadefunc      b, 
                    s_cargo            c,
                    s_area_atuacao     d, 
                    s_unidade          e,
                    s_escola           f,
                    s_funcionario_turm g,
                    s_disciplina       h,
                    s_turma            i,
                    s_curso            j,
                    s_curso_serie      l
              where a.co_funcionario  = b.co_funcionario (+)
                and a.co_unidade      = b.co_unidade     (+)
                and a.co_unidade      = e.co_unidade     (+)
                and b.co_cargo        = c.co_cargo       (+)
                and b.co_area_atuacao = d.co_area_atuacao(+)
                and a.co_unidade      = f.co_unidade     (+)
                and a.co_funcionario  = g.co_funcionario (+)
                and a.co_unidade      = g.co_unidade     (+)
                and g.co_disciplina   = h.co_disciplina  (+)
                and g.co_unidade      = h.co_unidade     (+)
                and g.ano_sem         = h.ano_sem        (+)
                and g.co_turma        = i.co_turma       (+)
                and g.co_unidade      = i.co_unidade     (+)
                and g.co_curso        = i.co_curso       (+)
                and g.ano_sem         = i.ano_sem        (+)
                and g.co_curso        = j.co_curso       (+)
                and g.co_unidade      = j.co_unidade     (+)
                and g.ano_sem         = j.ano_sem        (+)
                and g.co_curso        = l.co_curso       (+)
                and g.co_seq_serie    = l.co_seq_serie   (+)
                and g.co_unidade      = l.co_unidade     (+)
                and b.id_professor    = 'S'   
                and ((p_modalidade is null) or (p_modalidade is not null and j.co_tipo_curso = p_modalidade))
                and ((p_serie is null) or (p_serie is not null and l.sg_serie = p_serie))
                and ((p_turma is null) or (p_turma is not null and i.co_turma = p_turma))
                and ((p_disciplina is null) or (p_disciplina is not null and h.co_tipo_disciplina = p_disciplina))
                and ((p_turno is null) or (p_turno is not null and trim(i.co_turno) = trim(p_turno)))
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
               from s_funcionario      a, 
                    s_unidadefunc      b, 
                    s_cargo            c,
                    s_area_atuacao     d, 
                    s_unidade          e,
                    s_escola           f,
                    s_funcionario_turm g,
                    s_disciplina       h,
                    s_turma            i,
                    s_curso            j,
                    s_curso_serie      l
              where a.co_funcionario  = b.co_funcionario (+)
                and a.co_unidade      = b.co_unidade     (+)
                and a.co_unidade      = e.co_unidade     (+)
                and b.co_cargo        = c.co_cargo       (+)
                and b.co_area_atuacao = d.co_area_atuacao(+)
                and a.co_unidade      = f.co_unidade     (+)
                and a.co_funcionario  = g.co_funcionario (+)
                and a.co_unidade      = g.co_unidade     (+)
                and g.co_disciplina   = h.co_disciplina  (+)
                and g.co_unidade      = h.co_unidade     (+)
                and g.ano_sem         = h.ano_sem        (+)
                and g.co_turma        = i.co_turma       (+)
                and g.co_unidade      = i.co_unidade     (+)
                and g.co_curso        = i.co_curso       (+)
                and g.ano_sem         = i.ano_sem        (+)
                and g.co_curso        = j.co_curso       (+)
                and g.co_unidade      = j.co_unidade     (+)
                and g.ano_sem         = j.ano_sem        (+)
                and g.co_curso        = l.co_curso       (+)
                and g.co_seq_serie    = l.co_seq_serie   (+)
                and g.co_unidade      = l.co_unidade     (+)
                and b.id_professor    = 'S'   
                and ((p_modalidade is null) or (p_modalidade is not null and j.co_tipo_curso = p_modalidade))
                and ((p_serie is null) or (p_serie is not null and l.sg_serie = p_serie))
                and ((p_turma is null) or (p_turma is not null and i.co_turma = p_turma))
                and ((p_disciplina is null) or (p_disciplina is not null and h.co_tipo_disciplina = p_disciplina))
                and ((p_turno is null) or (p_turno is not null and trim(i.co_turno) = trim(p_turno)))
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
               from s_funcionario      a, 
                    s_unidadefunc      b, 
                    s_cargo            c,
                    s_area_atuacao     d, 
                    s_unidade          e,
                    s_escola           f,
                    s_funcionario_turm g,
                    s_disciplina       h,
                    s_turma            i,
                    s_curso            j,
                    s_curso_serie      l
              where a.co_funcionario  = b.co_funcionario (+)
                and a.co_unidade      = b.co_unidade     (+)
                and a.co_unidade      = e.co_unidade     (+)
                and b.co_cargo        = c.co_cargo       (+)
                and b.co_area_atuacao = d.co_area_atuacao(+)
                and a.co_unidade      = f.co_unidade     (+)
                and a.co_funcionario  = g.co_funcionario 
                and a.co_unidade      = g.co_unidade     
                and g.co_disciplina   = h.co_disciplina  (+)
                and g.co_unidade      = h.co_unidade     (+)
                and g.ano_sem         = h.ano_sem        (+)
                and g.co_turma        = i.co_turma       (+)
                and g.co_unidade      = i.co_unidade     (+)
                and g.co_curso        = i.co_curso       (+)
                and g.ano_sem         = i.ano_sem        (+)
                and g.co_curso        = j.co_curso       (+)
                and g.co_unidade      = j.co_unidade     (+)
                and g.ano_sem         = j.ano_sem        (+)
                and g.co_curso        = l.co_curso       (+)
                and g.co_seq_serie    = l.co_seq_serie   (+)
                and g.co_unidade      = l.co_unidade     (+)
                and b.id_professor    = 'S'   
                and ((p_modalidade is null) or (p_modalidade is not null and j.co_tipo_curso = p_modalidade))
                and ((p_serie is null) or (p_serie is not null and l.sg_serie = p_serie))
                and ((p_turma is null) or (p_turma is not null and i.co_turma = p_turma))
                and ((p_disciplina is null) or (p_disciplina is not null and h.co_tipo_disciplina = p_disciplina))
                and ((p_turno is null) or (p_turno is not null and trim(i.co_turno) = trim(p_turno)))
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
               from s_funcionario      a, 
                    s_unidadefunc      b, 
                    s_cargo            c,
                    s_area_atuacao     d, 
                    s_unidade          e,
                    s_escola           f,
                    s_funcionario_turm g,
                    s_disciplina       h,
                    s_turma            i,
                    s_curso            j,
                    s_curso_serie      l
              where a.co_funcionario  = b.co_funcionario (+)
                and a.co_unidade      = b.co_unidade     (+)
                and a.co_unidade      = e.co_unidade     (+)
                and b.co_cargo        = c.co_cargo       (+)
                and b.co_area_atuacao = d.co_area_atuacao(+)
                and a.co_unidade      = f.co_unidade     (+)
                and a.co_funcionario  = g.co_funcionario 
                and a.co_unidade      = g.co_unidade     
                and g.co_disciplina   = h.co_disciplina  (+)
                and g.co_unidade      = h.co_unidade     (+)
                and g.ano_sem         = h.ano_sem        (+)
                and g.co_turma        = i.co_turma       (+)
                and g.co_unidade      = i.co_unidade     (+)
                and g.co_curso        = i.co_curso       (+)
                and g.ano_sem         = i.ano_sem        (+)
                and g.co_curso        = j.co_curso       (+)
                and g.co_unidade      = j.co_unidade     (+)
                and g.ano_sem         = j.ano_sem        (+)
                and g.co_curso        = l.co_curso       (+)
                and g.co_seq_serie    = l.co_seq_serie   (+)
                and g.co_unidade      = l.co_unidade     (+)
                and b.id_professor    = 'S'   
                and ((p_modalidade is null) or (p_modalidade is not null and j.co_tipo_curso = p_modalidade))
                and ((p_serie is null) or (p_serie is not null and l.sg_serie = p_serie))
                and ((p_turma is null) or (p_turma is not null and i.co_turma = p_turma))
                and ((p_disciplina is null) or (p_disciplina is not null and h.co_tipo_disciplina = p_disciplina))
                and ((p_turno is null) or (p_turno is not null and trim(i.co_turno) = trim(p_turno)))
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

prompt
prompt Creating procedure SP_GETRENDREL
prompt ================================
prompt
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

prompt
prompt Creating procedure SP_GETRESPKINDLIST
prompt =====================================
prompt
create or replace procedure SP_GetRespKindList
   (p_cliente     in number,
    p_result      out sys_refcursor
   ) is
begin
   -- Recupera a lista de tipos de responsável
   open p_result for 
      select a.co_tip_responsavel, a.ds_tip_responsavel
        from s_tipo_responsavel  a;
end SP_GetRespKindList;
/

prompt
prompt Creating procedure SP_GETRESPONSDATA
prompt ====================================
prompt
create or replace procedure SP_GetResponsData
   (p_periodo     in number,
    p_responsavel in varchar2,
    p_result      out sys_refcursor) is
begin
   -- Recupera os alunos por período
   open p_result for
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
          and d.ano_sem      (+)   = p_periodo
          and c.co_responsavel     = p_responsavel;
end SP_GetResponsData;
/

prompt
prompt Creating procedure SP_GETROOMCLLIST
prompt ===================================
prompt
create or replace procedure SP_GetRoomClList
   (p_periodo   in number,
    p_regional  in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   If p_regional = 0 Then
   open p_result for 
      select a.co_letra_turma, a.co_bloco, a.co_turno, a.co_turma,
             d.ds_sala, ds_tipo_sala, ds_ambiente, nu_metragem, nu_alunos_sala,
             h.sg_serie, i.aluno_matric, j.aluno_ativo, ds_escola, d.co_sala, f.co_seq_ambiente,
             e.co_tipo_sala, c.co_curso, a.co_unidade, a.co_turma, l.co_tipo_curso,
             m.co_unidade total_uni, m.co_sala total_sala,substr(b.co_sigre,1,2) regional, b.ds_gre,
             n.tp_anee
        from s_turma         a,
             s_escola        b,
             s_curso         c,
             s_sala          d,
             s_tipo_sala     e,
             s_ambiente      f,
             s_curso_serie   g,
             s_serie         h,
             s_tipo_curso    l,
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
         and a.co_curso        = c.co_curso         (+)
         and a.co_unidade      = c.co_unidade       (+)
         and a.ano_sem         = c.ano_sem          (+)
         and a.co_unidade      = d.co_unidade       (+)
         and a.co_bloco        = d.co_bloco         (+)
         and a.co_sala         = d.co_sala          (+)
         and d.co_tipo_sala    = e.co_tipo_sala     (+)
         and d.co_seq_ambiente = f.co_seq_ambiente  (+)
         and a.co_seq_serie    = g.co_seq_serie     (+)
         and a.co_curso        = g.co_curso         (+)
         and a.ano_sem         = g.ano_sem          (+)
         and a.co_unidade      = g.co_unidade       (+)
         and g.sg_serie        = h.sg_serie         (+)
         and a.co_unidade      = i.co_unidade       (+)
         and a.co_turma        = i.co_turma         (+)
         and a.co_unidade      = j.co_unidade       (+)
         and a.co_turma        = j.co_turma         (+)
         and c.co_tipo_curso   = l.co_tipo_curso    (+)
         and a.co_unidade      = m.co_unidade       (+)
         and a.co_unidade      = n.co_unidade       (+)
         and a.co_turma        = n.co_turma         (+)
         and a.ano_sem         = p_periodo;
   Else
   open p_result for 
      select a.co_letra_turma, a.co_bloco, a.co_turno, a.co_turma,
             d.ds_sala, ds_tipo_sala, ds_ambiente, nu_metragem, nu_alunos_sala,
             h.sg_serie, i.aluno_matric, j.aluno_ativo, ds_escola, d.co_sala, f.co_seq_ambiente,
             e.co_tipo_sala, c.co_curso, a.co_unidade, a.co_turma, l.co_tipo_curso,
             m.co_unidade total_uni, m.co_sala total_sala,substr(b.co_sigre,1,2) regional, b.ds_gre,
             n.tp_anee
        from s_turma         a,
             s_escola        b,
             s_curso         c,
             s_sala          d,
             s_tipo_sala     e,
             s_ambiente      f,
             s_curso_serie   g,
             s_serie         h,
             s_tipo_curso    l,
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
         and a.co_curso        = c.co_curso         (+)
         and a.co_unidade      = c.co_unidade       (+)
         and a.ano_sem         = c.ano_sem          (+)
         and a.co_unidade      = d.co_unidade       (+)
         and a.co_bloco        = d.co_bloco         (+)
         and a.co_sala         = d.co_sala          (+)
         and d.co_tipo_sala    = e.co_tipo_sala     (+)
         and d.co_seq_ambiente = f.co_seq_ambiente  (+)
         and a.co_seq_serie    = g.co_seq_serie     (+)
         and a.co_curso        = g.co_curso         (+)
         and a.ano_sem         = g.ano_sem          (+)
         and a.co_unidade      = g.co_unidade       (+)
         and g.sg_serie        = h.sg_serie         (+)
         and a.co_unidade      = i.co_unidade       (+)
         and a.co_turma        = i.co_turma         (+)
         and a.co_unidade      = j.co_unidade       (+)
         and a.co_turma        = j.co_turma         (+)
         and c.co_tipo_curso   = l.co_tipo_curso    (+)
         and a.co_unidade      = m.co_unidade       (+)
         and a.co_unidade      = n.co_unidade       (+)
         and a.co_turma        = n.co_turma         (+)
         and a.ano_sem         = p_periodo
         and b.co_sigre      like p_regional||'%';   
   End If; 
end SP_GetRoomClList;
/

prompt
prompt Creating procedure SP_GETROOMTYPEDATA
prompt =====================================
prompt
create or replace procedure SP_GetRoomTypeData
   (p_co_tipo_sala     in  number,
    p_result           out sys_refcursor
   ) is
begin
   -- Recupera os dados da origem dos tipos de sala
   open p_result for 
      select * from s_tipo_sala where co_tipo_sala = p_co_tipo_sala;
end SP_GetRoomTypeData;
/

prompt
prompt Creating procedure SP_GETROOMTYPELIST
prompt =====================================
prompt
create or replace procedure SP_GetRoomTypeList
   (p_result    out sys_refcursor) is
begin
   -- Recupera a origem dos tipos de salas existentes
   open p_result for 
      select co_tipo_sala, ds_tipo_sala 
        from s_tipo_sala
      order by co_tipo_sala;
end SP_GetRoomTypeList;
/

prompt
prompt Creating procedure SP_GETSCHOOLLIST
prompt ===================================
prompt
create or replace procedure SP_GetSchoolList
   (p_cliente     in number,
    p_result      out sys_refcursor
   ) is
begin
   -- Recupera a lista de escolas
   open p_result for 
      select a.co_unidade, a.ds_escola, a.co_sigre, a.ds_endereco, a.ds_bairro, a.nu_cep,
             a.ds_cidade, a.ds_uf_cidade, a.ds_gre,
             b.ds_unidade, b.tp_escola, b.ds_nome_relatorio, b.ds_vinheta, b.nu_telefone_1,
             b.nu_telefone_2, b.nu_fax, b.ds_e_mail, b.ds_ato, b.ds_numero, b.dt_data,
             b.ds_orgao, b.ds_grade_curric, b.nu_cgc_escola, b.nu_inscr_escola,
             b.ds_diretor, b.ds_secretario, b.dt_atualizacao, b.ds_rural,
             b.nu_remessa, b.nu_alunosativos, b.nu_matriculados, b.nu_ativos
        from s_escola  a,
             s_unidade b
        where a.co_unidade = b.co_unidade (+); 
end SP_GetSchoolList;
/

prompt
prompt Creating procedure SP_GETSCHORDATA
prompt ==================================
prompt
create or replace procedure SP_GetSchOrData
   (p_co_origem_escola in  number,
    p_result           out sys_refcursor
   ) is
begin
   -- Recupera os dados da origem das escolas
   open p_result for 
      select * from s_origem_escola where co_origem_escola = p_co_origem_escola;
end SP_GetSchOrData;
/

prompt
prompt Creating procedure SP_GETSCHORLIST
prompt ==================================
prompt
create or replace procedure SP_GetSchOrList
   (p_result    out sys_refcursor) is
begin
   -- Recupera a origem das escolas existentes
   open p_result for 
      select co_origem_escola, ds_origem_escola 
        from s_origem_escola
      order by co_origem_escola;
end SP_GetSchOrList;
/

prompt
prompt Creating procedure SP_GETSERIEDATA
prompt ==================================
prompt
create or replace procedure SP_GetSerieData
   (p_sg_serie        in  varchar2,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera os dados da serie
   open p_result for 
      select * from s_serie where sg_serie = p_sg_serie;
end SP_GetSerieData;
/

prompt
prompt Creating procedure SP_GETSERIELIST
prompt ==================================
prompt
create or replace procedure SP_GetSerieList
   (p_result    out sys_refcursor) is
begin
   -- Recupera as series existentes
   open p_result for 
      select a.sg_serie, a.descr_serie, b.ds_tipo_curso, a.co_tipo_curso 
        from sige.s_serie a,
             s_tipo_curso b
        where a.co_tipo_curso = b.co_tipo_curso
      order by sg_serie;
end SP_GetSerieList;
/

prompt
prompt Creating procedure SP_GETSTUDENTDATA
prompt ====================================
prompt
create or replace procedure SP_GetStudentData
   (p_periodo   in number,
    p_matricula in varchar2 default null,
    p_dados     in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   -- Recupera os alunos por período
   If p_dados = 'CADASTRO' or p_dados = 'CABECALHO' or p_dados = 'DOCUMENTO' or p_dados = 'MEDICA' Then
      open p_result for
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
           and e.ano_sem          = p_periodo
           and a.co_aluno         = rpad(p_matricula,12,' ');
   Elsif p_dados = 'TURMA' Then
      open p_result for
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
           and a.co_aluno      = rpad(p_matricula,12,' ')
           and a.ano_sem       = p_periodo;
   Elsif p_dados = 'APROVEIT' Then
      open p_result for
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
           and a.co_aluno      = rpad(p_matricula,12,' ')
           and a.ano_sem       = p_periodo;
   Elsif p_dados = 'DEPEND' Then
      open p_result for
         select b.dp_serie, c.ds_disciplina, b.nu_nota, a.co_aluno,
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
           and a.co_aluno      = rpad(p_matricula,12,' ')
           and a.ano_sem       = p_periodo;
   Elsif p_dados = 'ADAPT' Then
      open p_result for
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
           and a.co_aluno      = rpad(p_matricula,12,' ')
           and a.ano_sem       = p_periodo;
   Elsif p_dados = 'BOLETIM' Then
      open p_result for        
         select a.nu_nota_b1, a.nu_faltas_b1, a.nu_nota_b2, a.nu_faltas_b2, a.co_unidade,
                a.nu_nota_b3, a.nu_faltas_b3, a.nu_nota_b4, a.nu_faltas_b4, 
                a.nu_media_anual, a.nu_recup_especial, f.ds_ordem_imp,
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
            and b.co_aluno        = rpad(p_matricula,12,' ')
            and b.ano_sem         = p_periodo;
   Else
      open p_result for
         select sysdate from dual;
   End If;
end SP_GetStudentData;
/

prompt
prompt Creating procedure SP_GETSTUDENTLIST
prompt ====================================
prompt
create or replace procedure SP_GetStudentList
   (p_periodo   in number,
    p_regional  in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   If p_regional = 0 Then
      -- Recupera os alunos por período
      open p_result for 
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
          where a.co_aluno           = b.co_aluno (+)
            and b.co_responsavel     = c.co_responsavel (+)
            and b.co_unidade         = c.co_unidade (+)
            and c.co_tip_responsavel = h.co_tip_responsavel (+)
            and a.co_aluno           = d.co_aluno
            and d.co_unidade         = e.co_unidade
            and d.ano_sem            = e.ano_sem
            and e.co_unidade         = f.co_unidade
            and f.co_unidade         = g.co_unidade
            and e.ano_sem            = p_periodo;
   Else
      -- Recupera os alunos por período e regional
      open p_result for 
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
          where a.co_aluno           = b.co_aluno (+)
            and b.co_responsavel     = c.co_responsavel (+)
            and b.co_unidade         = c.co_unidade (+)
            and c.co_tip_responsavel = h.co_tip_responsavel (+)
            and a.co_aluno           = d.co_aluno
            and d.co_unidade         = e.co_unidade
            and d.ano_sem            = e.ano_sem
            and e.co_unidade         = f.co_unidade
            and f.co_unidade         = g.co_unidade
            and e.ano_sem            = p_periodo
            and g.co_sigre           like p_regional||'%';
   End If;
end SP_GetStudentList;
/

prompt
prompt Creating procedure SP_GETSTUDENTREL
prompt ===================================
prompt
create or replace procedure SP_GetStudentRel
   (p_periodo   in number,
    p_regional  in varchar2 default null,
    p_materia   in number   default null,
    p_result    out sys_refcursor) is
begin
If p_materia is null Then
   If p_regional = 0 Then
      -- Recupera os alunos por período
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
         from s_aluno_per_unid a,
              s_aluno_turma    b,
              s_turma          c,
              s_curso          d,
              s_curso_serie    e,
              s_sala           f,
              s_aluno          h,
              s_escola         i,
              s_tipo_curso     j,
              s_origem_escola  k
         where a.co_aluno         = h.co_aluno
           and c.co_unidade       = i.co_unidade    (+)
           and a.ano_sem          = b.ano_sem       (+)
           and a.co_unidade       = b.co_unidade    (+)
           and a.co_aluno         = b.co_aluno      (+)
           and b.co_turma         = c.co_turma      (+)
           and b.co_unidade       = c.co_unidade    (+)
           and b.ano_sem          = c.ano_sem       (+)
           and c.co_curso         = d.co_curso      (+)
           and c.co_unidade       = d.co_unidade    (+)
           and c.ano_sem          = d.ano_sem       (+)
           and c.co_curso         = e.co_curso      (+)
           and c.ano_sem          = e.ano_sem       (+)
           and c.co_unidade       = e.co_unidade    (+)
           and c.co_seq_serie     = e.co_seq_serie  (+)
           and c.co_bloco         = f.co_bloco      (+)
           and c.co_sala          = f.co_sala       (+)
           and c.co_unidade       = f.co_unidade    (+)
           and c.co_unidade       = i.co_unidade    (+)
           and d.co_tipo_curso    = j.co_tipo_curso (+)
           and h.co_origem_escola = k.co_origem_escola
           and a.ano_sem          = p_periodo;
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
                k.co_origem_escola, k.ds_origem_escola
         from s_aluno_per_unid a,
              s_aluno_turma    b,
              s_turma          c,
              s_curso          d,
              s_curso_serie    e,
              s_sala           f,
              s_aluno          h,
              s_escola         i,
              s_tipo_curso     j,
              s_origem_escola  k
         where a.co_aluno         = h.co_aluno
           and c.co_unidade       = i.co_unidade    (+)
           and a.ano_sem          = b.ano_sem       (+)
           and a.co_unidade       = b.co_unidade    (+)
           and a.co_aluno         = b.co_aluno      (+)
           and b.co_turma         = c.co_turma      (+)
           and b.co_unidade       = c.co_unidade    (+)
           and b.ano_sem          = c.ano_sem       (+)
           and c.co_curso         = d.co_curso      (+)
           and c.co_unidade       = d.co_unidade    (+)
           and c.ano_sem          = d.ano_sem       (+)
           and c.co_curso         = e.co_curso      (+)
           and c.ano_sem          = e.ano_sem       (+)
           and c.co_unidade       = e.co_unidade    (+)
           and c.co_seq_serie     = e.co_seq_serie  (+)
           and c.co_bloco         = f.co_bloco      (+)
           and c.co_sala          = f.co_sala       (+)
           and c.co_unidade       = f.co_unidade    (+)
           and c.co_unidade       = i.co_unidade    (+)
           and d.co_tipo_curso    = j.co_tipo_curso (+)
           and h.co_origem_escola = k.co_origem_escola (+)
           and a.ano_sem          = p_periodo
           and i.co_sigre         like p_regional||'%';
   End If;
Else
   If p_regional = 0 Then
      -- Recupera os alunos por período
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
         from s_aluno_per_unid   a,
              s_aluno_turma      b,
              s_turma            c,
              s_curso            d,
              s_curso_serie      e,
              s_sala             f,
              s_aluno            h,
              s_escola           i,
              s_tipo_curso       j,
              s_origem_escola    k,
              s_turma_disciplina l,
              s_disciplina       m
         where a.co_aluno           = h.co_aluno
           and c.co_unidade         = i.co_unidade    (+)
           and a.ano_sem            = b.ano_sem       (+)
           and a.co_unidade         = b.co_unidade    (+)
           and a.co_aluno           = b.co_aluno      (+)
           and b.co_turma           = c.co_turma      (+)
           and b.co_unidade         = c.co_unidade    (+)
           and b.ano_sem            = c.ano_sem       (+)
           and c.co_curso           = d.co_curso      (+)
           and c.co_unidade         = d.co_unidade    (+)
           and c.ano_sem            = d.ano_sem       (+)
           and c.co_curso           = e.co_curso      (+)
           and c.ano_sem            = e.ano_sem       (+)
           and c.co_unidade         = e.co_unidade    (+)
           and c.co_seq_serie       = e.co_seq_serie  (+)
           and c.co_bloco           = f.co_bloco      (+)
           and c.co_sala            = f.co_sala       (+)
           and c.co_unidade         = f.co_unidade    (+)
           and c.co_unidade         = i.co_unidade    (+)
           and d.co_tipo_curso      = j.co_tipo_curso (+)
           and h.co_origem_escola   = k.co_origem_escola
           and c.co_unidade         = l.co_unidade
           and c.co_turma           = l.co_turma
           and c.ano_sem            = l.ano_sem
           and c.co_curso           = l.co_curso
           and c.co_seq_serie       = l.co_seq_serie
           and l.co_disciplina      = m.co_disciplina
           and l.ano_sem            = m.ano_sem
           and l.co_unidade         = m.co_unidade
           and a.ano_sem            = p_periodo
           and m.co_tipo_disciplina = p_materia;
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
         from s_aluno_per_unid   a,
              s_aluno_turma      b,
              s_turma            c,
              s_curso            d,
              s_curso_serie      e,
              s_sala             f,
              s_aluno            h,
              s_escola           i,
              s_tipo_curso       j,
              s_origem_escola    k,
              s_turma_disciplina l,
              s_disciplina       m
         where a.co_aluno           = h.co_aluno
           and c.co_unidade         = i.co_unidade    (+)
           and a.ano_sem            = b.ano_sem       (+)
           and a.co_unidade         = b.co_unidade    (+)
           and a.co_aluno           = b.co_aluno      (+)
           and b.co_turma           = c.co_turma      (+)
           and b.co_unidade         = c.co_unidade    (+)
           and b.ano_sem            = c.ano_sem       (+)
           and c.co_curso           = d.co_curso      (+)
           and c.co_unidade         = d.co_unidade    (+)
           and c.ano_sem            = d.ano_sem       (+)
           and c.co_curso           = e.co_curso      (+)
           and c.ano_sem            = e.ano_sem       (+)
           and c.co_unidade         = e.co_unidade    (+)
           and c.co_seq_serie       = e.co_seq_serie  (+)
           and c.co_bloco           = f.co_bloco      (+)
           and c.co_sala            = f.co_sala       (+)
           and c.co_unidade         = f.co_unidade    (+)
           and c.co_unidade         = i.co_unidade    (+)
           and d.co_tipo_curso      = j.co_tipo_curso (+)
           and h.co_origem_escola   = k.co_origem_escola
           and c.co_unidade         = l.co_unidade
           and c.co_turma           = l.co_turma
           and c.ano_sem            = l.ano_sem
           and c.co_curso           = l.co_curso
           and c.co_seq_serie       = l.co_seq_serie
           and l.co_disciplina      = m.co_disciplina
           and l.ano_sem            = m.ano_sem
           and l.co_unidade         = m.co_unidade
           and a.ano_sem            = p_periodo
           and m.co_tipo_disciplina = p_materia
           and i.co_sigre           like p_regional||'%';
   End If;
End If;
end SP_GetStudentRel;
/

prompt
prompt Creating procedure SP_GETTURMALIST
prompt ==================================
prompt
create or replace procedure SP_GetTurmaList
   (p_periodo         in  number,
    p_unidade         in  varchar2,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera os dados da turma
   open p_result for 
      select a.co_turma, a.co_curso, a.co_seq_serie, a.co_letra_turma, a.ds_turma, 
             a.co_turma_procura, b.sg_serie, d.co_tipo_curso, d.sg_tipo_curso
        from s_turma       a,
             s_curso_serie b,
             s_curso       c,
             s_tipo_curso  d
       where a.co_curso      = b.co_curso
         and a.ano_sem       = b.ano_sem
         and a.co_unidade    = b.co_unidade
         and a.co_seq_serie  = b.co_seq_serie
         and b.co_curso      = c.co_curso
         and b.co_unidade    = c.co_unidade
         and b.ano_sem       = c.ano_sem
         and c.co_tipo_curso = d.co_tipo_curso
         and a.co_unidade    = p_unidade
         and a.ano_sem       = p_periodo;
end SP_GetTurmaList;
/

prompt
prompt Creating procedure SP_GETTURNDATA
prompt =================================
prompt
create or replace procedure SP_GetTurnData
   (p_co_turno        in  char,
    p_result          out sys_refcursor
   ) is
begin
   -- Recupera os dados do ambiente
   open p_result for 
      select * from s_turno where co_turno = p_co_turno;
end SP_GetTurnData;
/

prompt
prompt Creating procedure SP_GETTURNLIST
prompt =================================
prompt
create or replace procedure SP_GetTurnList
   (p_result    out sys_refcursor) is
begin
   -- Recupera as área de atuações existentes
   open p_result for 
      select co_turno, ds_turno 
        from s_turno
      order by co_turno;
end SP_GetTurnList;
/

prompt
prompt Creating procedure SP_GETUNITREL
prompt ================================
prompt
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
                   where d.co_unidade = e.co_unidade (+)
                     and d.co_unidade = i.co_unidade (+)
                     and i.ano_sem    = p_periodo
                   having count(*) > 1
                   group  by e.regional) c,
                   s_periodounidade h,
                   s_versao         l
             where a.co_unidade          = b.co_unidade    (+)
               and substr(b.co_sigre,1,2)= c.regional      (+)  
               and a.co_unidade          = h.co_unidade    (+) 
               and a.co_unidade          = l.co_unidade    (+) 
               and ((p_dif = 'N') or (p_dif = 'S' and Nvl(a.nu_alunosativos,0) <> Nvl(a.nu_ativos,0)))
               and h.ano_sem             = p_periodo;
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
                     and i.ano_sem       = p_periodo
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
               and h.ano_sem             = p_periodo
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
                     and i.ano_sem    = p_periodo
                   having count(*) > 1
                   group  by e.regional) c,
                   s_periodounidade h,
                   s_versao         l
             where a.co_unidade          = b.co_unidade    (+)
               and substr(b.co_sigre,1,2)= c.regional      (+)  
               and a.co_unidade          = h.co_unidade    (+)
               and a.co_unidade          = l.co_unidade    (+)
               and ((p_dif = 'N') or (p_dif = 'S' and Nvl(a.nu_alunosativos,0) <> Nvl(a.nu_ativos,0)))
               and h.ano_sem             = p_periodo
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
                     and i.ano_sem       = p_periodo
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
               and h.ano_sem             = p_periodo
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

prompt
prompt Creating procedure SP_GETVERSIONLIST
prompt ====================================
prompt
create or replace procedure SP_GetVersionList
   (p_result    out sys_refcursor) is
begin
   -- Recupera as versões
   open p_result for 
      select distinct(ds_versao)
        from s_versao
      order by ds_versao;
end SP_GetVersionList;
/

prompt
prompt Creating procedure SP_PUTSAMBIENTE
prompt ==================================
prompt
create or replace procedure SP_PutSAmbiente
   (p_operacao                 in  varchar2,
    p_chave                    in  number default null,
    p_ds_ambiente              in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_ambiente (co_seq_ambiente, ds_ambiente)
         (select co_seq_ambiente.nextval,
                 trim(upper(p_ds_ambiente))
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_ambiente set
         ds_ambiente          = trim(upper(p_ds_ambiente))
      where co_seq_ambiente   = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_ambiente where co_seq_ambiente = p_chave;
   End If;
end SP_PutSAmbiente;
/

prompt
prompt Creating procedure SP_PUTSAREAATUACAO
prompt =====================================
prompt
create or replace procedure SP_PutSAreaAtuacao
   (p_operacao                 in  varchar2,
    p_chave                    in  number default null,
    p_co_area_atuacao          in  number default null,
    p_ds_area_atuacao          in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_area_atuacao (co_area_atuacao, ds_area_atuacao)
      values(
                 p_co_area_atuacao,
                 trim(upper(p_ds_area_atuacao))
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_area_atuacao set
         co_area_atuacao      = p_co_area_atuacao,  
         ds_area_atuacao      = trim(upper(p_ds_area_atuacao))
      where co_area_atuacao   = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_area_atuacao where co_area_atuacao = p_chave;
   End If;
end SP_PutSAreaAtuacao;
/

prompt
prompt Creating procedure SP_PUTSCARGO
prompt ===============================
prompt
create or replace procedure SP_PutSCargo
   (p_operacao                 in  varchar2,
    p_chave                    in  varchar2,
    p_co_cargo                 in  varchar2,
    p_ds_cargo                 in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_cargo (co_cargo, ds_cargo)
      values(
                 p_co_cargo,
                 trim(upper(p_ds_cargo))
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_cargo set
         co_cargo      = p_co_cargo,  
         ds_cargo      = trim(upper(p_ds_cargo))
      where co_cargo   = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_cargo where co_cargo = p_chave;
   End If;
end SP_PutSCargo;
/

prompt
prompt Creating procedure SP_PUTSDISCPER
prompt =================================
prompt
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

prompt
prompt Creating procedure SP_PUTSGRADE_CURR
prompt ====================================
prompt
create or replace procedure SP_PutSGrade_Curr
   (p_operacao               in  varchar2,
    p_chave                  in  number default null,
    p_co_tipo_curso          in  number,
    p_ano                    in  number,
    p_turno                  in  varchar2,
    p_dt_grade               in  date,
    p_nu_semanas             in  number,
    p_nu_grade               in  varchar2,
    p_ds_grade               in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_grade_curric (co_grade_curric, co_tipo_curso, ano, turno, dt_grade, nu_semanas, nu_grade, ds_grade)
         (select co_grade_curric.nextval,
                 p_co_tipo_curso,
                 p_ano,
                 trim(upper(p_turno)),
                 p_dt_grade,
                 p_nu_semanas,
                 trim(p_nu_grade),
                 trim(upper(p_ds_grade))
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_grade_curric set
         co_tipo_curso  = p_co_tipo_curso , 
         ano            = p_ano,
         turno          = trim(upper(p_turno)),
         dt_grade       = p_dt_grade,
         nu_semanas     = p_nu_semanas,
         nu_grade       = trim(p_nu_grade),
         ds_grade       = trim(upper(p_ds_grade))
      where co_grade_curric    = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_grade_curric where co_grade_curric = p_chave;
   End If;
end SP_PutSGrade_Curr;
/

prompt
prompt Creating procedure SP_PUTSORESCOLA
prompt ==================================
prompt
create or replace procedure SP_PutSOrEscola
   (p_operacao                 in  varchar2,
    p_chave                    in  number default null,
    p_ds_origem_escola         in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_origem_escola (co_origem_escola, ds_origem_escola)
         (select co_origem_escola.nextval,
                 trim(upper(p_ds_origem_escola))
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_origem_escola set
         ds_origem_escola      = trim(upper(p_ds_origem_escola))
      where co_origem_escola   = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_origem_escola where co_origem_escola = p_chave;
   End If;
end SP_PutSOrEscola;
/

prompt
prompt Creating procedure SP_PUTSPERIODO
prompt =================================
prompt
create or replace procedure SP_PutSPeriodo
   (p_operacao               in  varchar2,
    p_turno                  in  varchar2,
    p_co_grade_curric        in  number,
    p_ano                    in  number,
    p_co_tipo_curso          in  number,
    p_sg_serie               in  varchar2
   ) is
begin
   If p_operacao = 'L' Then
      -- Insere registro
      insert into s_periodo (turno, co_grade_curric, ano, co_tipo_curso, sg_serie)
      values(    trim(p_turno),
                 p_co_grade_curric,
                 p_ano,
                 p_co_tipo_curso,
                 trim(upper(p_sg_serie))
         );
   Elsif p_operacao = 'E' Then
     delete s_periodo where sg_serie = p_sg_serie and co_grade_curric = p_co_grade_curric;
   End If;  
end SP_PutSPeriodo;
/

prompt
prompt Creating procedure SP_PUTSSERIE
prompt ===============================
prompt
create or replace procedure SP_PutSSerie
   (p_operacao               in  varchar2,
    p_chave                  in  varchar2 default null,
    p_sg_serie               in  varchar2 default null,
    p_co_tipo_curso          in  number,
    p_ds_serie               in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_serie (sg_serie, co_tipo_curso, descr_serie)
      values(
                 trim(upper(p_sg_serie)),
                 p_co_tipo_curso,
                 trim(upper(p_ds_serie))
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_serie set
         sg_serie       = trim(upper(p_sg_serie)),
         co_tipo_curso  = p_co_tipo_curso , 
         descr_serie    = trim(upper(p_ds_serie))
      where sg_serie    = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_serie where sg_serie = p_chave;
   End If;
end SP_PutSSerie;
/

prompt
prompt Creating procedure SP_PUTSTIPOCURSO
prompt ===================================
prompt
create or replace procedure SP_PutSTipoCurso
   (p_operacao                 in  varchar2,
    p_chave                    in  number default null,
    p_sg_tipo_curso            in  varchar2,
    p_ds_tipo_curso            in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_tipo_curso (co_tipo_curso, sg_tipo_curso, ds_tipo_curso)
         (select co_tipo_curso.nextval,
                 trim(upper(p_sg_tipo_curso)),
                 trim(upper(p_ds_tipo_curso))
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_tipo_curso set
         sg_tipo_curso          = trim(upper(p_sg_tipo_curso)),
         ds_tipo_curso          = trim(upper(p_ds_tipo_curso))
      where co_tipo_curso   = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_tipo_curso where co_tipo_curso = p_chave;
   End If;
end SP_PutSTipoCurso;
/

prompt
prompt Creating procedure SP_PUTSTIPODISC
prompt ==================================
prompt
create or replace procedure SP_PutSTipoDisc
   (p_operacao                 in  varchar2,
    p_chave                    in  number default null,
    p_sg_disciplina            in  char,
    p_ds_tipo_disciplina       in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_tipo_disciplina (co_tipo_disciplina, sg_disciplina, ds_tipo_disciplina)
         (select co_tipo_disciplina.nextval,
                 trim(upper(p_sg_disciplina)),
                 trim(upper(p_ds_tipo_disciplina))
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_tipo_disciplina set
         sg_disciplina          = trim(upper(p_sg_disciplina)),
         ds_tipo_disciplina     = trim(upper(p_ds_tipo_disciplina))
      where co_tipo_disciplina  = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_tipo_disciplina where co_tipo_disciplina = p_chave;
   End If;
end SP_PutSTipoDisc;
/

prompt
prompt Creating procedure SP_PUTSTIPOSALA
prompt ==================================
prompt
create or replace procedure SP_PutSTipoSala
   (p_operacao                 in  varchar2,
    p_chave                    in  number default null,
    p_ds_tipo_sala         in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_tipo_sala (co_tipo_sala, ds_tipo_sala)
         (select co_tipo_sala.nextval,
                 trim(upper(p_ds_tipo_sala))
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_tipo_sala set
         ds_tipo_sala      = trim(upper(p_ds_tipo_sala))
      where co_tipo_sala   = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_tipo_sala where co_tipo_sala = p_chave;
   End If;
end SP_PutSTipoSala;
/

prompt
prompt Creating procedure SP_PUTSTPAVALIACAO
prompt =====================================
prompt
create or replace procedure SP_PutSTPAvaliacao
   (p_operacao                 in  varchar2,
    p_chave                    in  number default null,
    p_ds_tipo_avaliacao        in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_tipo_avaliacao (co_tipo_avaliacao, ds_tipo_avaliacao)
         (select co_tipo_avaliacao.nextval,
                 trim(upper(p_ds_tipo_avaliacao))
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_tipo_avaliacao set
         ds_tipo_avaliacao     = trim(upper(p_ds_tipo_avaliacao))
      where co_tipo_avaliacao   = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_tipo_avaliacao where co_tipo_avaliacao = p_chave;
   End If;
end SP_PutSTPAvaliacao;
/

prompt
prompt Creating procedure SP_PUTSTURNO
prompt ===============================
prompt
create or replace procedure SP_PutSTurno
   (p_operacao                 in  varchar2,
    p_chave                    in  char default null,
    p_co_turno                 in  char default null,
    p_ds_turno                 in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_turno (co_turno, ds_turno)
      values(
                 p_co_turno,
                 trim(upper(p_ds_turno))
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_turno set
         co_turno       = p_co_turno,  
         ds_turno       = trim(upper(p_ds_turno))
      where co_turno    = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_turno where co_turno = p_chave;
   End If;
end SP_PutSTurno;
/


spool off
