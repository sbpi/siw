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
            and a.co_funcionario (+) = g.co_funcionario
            and a.co_unidade     (+) = g.co_unidade
            and f.co_cargo           = h.co_cargo       (+)
            and g.co_funcionario     = rpad(p_codigo,10,' ')
            and a.ano_sem        (+) = p_periodo;
   Else
      open p_result for
         select sysdate from dual;
   End If;
end SP_GetFuncData;
/

