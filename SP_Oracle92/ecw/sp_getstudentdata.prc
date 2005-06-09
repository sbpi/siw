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

