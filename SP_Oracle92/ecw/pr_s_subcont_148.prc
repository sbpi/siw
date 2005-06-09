CREATE OR REPLACE PROCEDURE pr_s_subcont_148(
	P_OP_IN                CHAR,
	PA_tp_subcont_ed00_IN  s_subcont_educ_not.tp_subcont_educ%TYPE,
	PA_tp_conteudo_e01_IN  s_subcont_educ_not.tp_conteudo_educ%TYPE,
	PA_ano_sem02_IN        s_subcont_educ_not.ano_sem%TYPE,
	PA_co_curso03_IN       s_subcont_educ_not.co_curso%TYPE,
	PA_co_seq_serie04_IN   s_subcont_educ_not.co_seq_serie%TYPE,
	PA_co_unidade05_IN     s_subcont_educ_not.co_unidade%TYPE,
	PA_co_turma06_IN       s_subcont_educ_not.co_turma%TYPE,
	PA_co_disciplina07_IN  s_subcont_educ_not.co_disciplina%TYPE,
	PA_co_aluno08_IN       s_subcont_educ_not.co_aluno%TYPE,
	PA_nota_subcont_09_IN  s_subcont_educ_not.nota_subcont_educ%TYPE,
	PN_tp_subcont_ed00_IN  s_subcont_educ_not.tp_subcont_educ%TYPE,
	PN_tp_conteudo_e01_IN  s_subcont_educ_not.tp_conteudo_educ%TYPE,
	PN_ano_sem02_IN        s_subcont_educ_not.ano_sem%TYPE,
	PN_co_curso03_IN       s_subcont_educ_not.co_curso%TYPE,
	PN_co_seq_serie04_IN   s_subcont_educ_not.co_seq_serie%TYPE,
	PN_co_unidade05_IN     s_subcont_educ_not.co_unidade%TYPE,
	PN_co_turma06_IN       s_subcont_educ_not.co_turma%TYPE,
	PN_co_disciplina07_IN  s_subcont_educ_not.co_disciplina%TYPE,
	PN_co_aluno08_IN       s_subcont_educ_not.co_aluno%TYPE,
	PN_nota_subcont_09_IN  s_subcont_educ_not.nota_subcont_educ%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_tp_subcont_ed00  s_subcont_educ_not.tp_subcont_educ%TYPE := PA_tp_subcont_ed00_IN;
PA_tp_conteudo_e01  s_subcont_educ_not.tp_conteudo_educ%TYPE := PA_tp_conteudo_e01_IN;
PA_ano_sem02        s_subcont_educ_not.ano_sem%TYPE := PA_ano_sem02_IN;
PA_co_curso03       s_subcont_educ_not.co_curso%TYPE := PA_co_curso03_IN;
PA_co_seq_serie04   s_subcont_educ_not.co_seq_serie%TYPE := PA_co_seq_serie04_IN;
PA_co_unidade05     s_subcont_educ_not.co_unidade%TYPE := PA_co_unidade05_IN;
PA_co_turma06       s_subcont_educ_not.co_turma%TYPE := PA_co_turma06_IN;
PA_co_disciplina07  s_subcont_educ_not.co_disciplina%TYPE := PA_co_disciplina07_IN;
PA_co_aluno08       s_subcont_educ_not.co_aluno%TYPE := PA_co_aluno08_IN;
PA_nota_subcont_09  s_subcont_educ_not.nota_subcont_educ%TYPE := PA_nota_subcont_09_IN;
PN_tp_subcont_ed00  s_subcont_educ_not.tp_subcont_educ%TYPE := PN_tp_subcont_ed00_IN;
PN_tp_conteudo_e01  s_subcont_educ_not.tp_conteudo_educ%TYPE := PN_tp_conteudo_e01_IN;
PN_ano_sem02        s_subcont_educ_not.ano_sem%TYPE := PN_ano_sem02_IN;
PN_co_curso03       s_subcont_educ_not.co_curso%TYPE := PN_co_curso03_IN;
PN_co_seq_serie04   s_subcont_educ_not.co_seq_serie%TYPE := PN_co_seq_serie04_IN;
PN_co_unidade05     s_subcont_educ_not.co_unidade%TYPE := PN_co_unidade05_IN;
PN_co_turma06       s_subcont_educ_not.co_turma%TYPE := PN_co_turma06_IN;
PN_co_disciplina07  s_subcont_educ_not.co_disciplina%TYPE := PN_co_disciplina07_IN;
PN_co_aluno08       s_subcont_educ_not.co_aluno%TYPE := PN_co_aluno08_IN;
PN_nota_subcont_09  s_subcont_educ_not.nota_subcont_educ%TYPE := PN_nota_subcont_09_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_tp_subcont_ed00  CHAR(30);
vr_tp_conteudo_e01  CHAR(10);
vr_ano_sem02        CHAR(10);
vr_co_curso03       CHAR(10);
vr_co_seq_serie04   CHAR(10);
vr_co_unidade05     CHAR(10);
vr_co_turma06       CHAR(10);
vr_co_disciplina07  CHAR(10);
vr_co_aluno08       CHAR(20);
vr_nota_subcont_09  CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_tp_subcont_ed00 IS NULL THEN
			vr_tp_subcont_ed00 := 'null';
		ELSE
			vr_tp_subcont_ed00 := pn_tp_subcont_ed00;
		END IF;
		IF pn_tp_conteudo_e01 IS NULL THEN
			vr_tp_conteudo_e01 := 'null';
		ELSE
			vr_tp_conteudo_e01 := pn_tp_conteudo_e01;
		END IF;
		IF pn_ano_sem02 IS NULL THEN
			vr_ano_sem02 := 'null';
		ELSE
			vr_ano_sem02 := pn_ano_sem02;
		END IF;
		IF pn_co_curso03 IS NULL THEN
			vr_co_curso03 := 'null';
		ELSE
			vr_co_curso03 := pn_co_curso03;
		END IF;
		IF pn_co_seq_serie04 IS NULL THEN
			vr_co_seq_serie04 := 'null';
		ELSE
			vr_co_seq_serie04 := pn_co_seq_serie04;
		END IF;
		IF pn_co_unidade05 IS NULL THEN
			vr_co_unidade05 := 'null';
		ELSE
			vr_co_unidade05 := pn_co_unidade05;
		END IF;
		IF pn_co_turma06 IS NULL THEN
			vr_co_turma06 := 'null';
		ELSE
			vr_co_turma06 := pn_co_turma06;
		END IF;
		IF pn_co_disciplina07 IS NULL THEN
			vr_co_disciplina07 := 'null';
		ELSE
			vr_co_disciplina07 := pn_co_disciplina07;
		END IF;
		IF pn_co_aluno08 IS NULL THEN
			vr_co_aluno08 := 'null';
		ELSE
			vr_co_aluno08 := pn_co_aluno08;
		END IF;
		IF pn_nota_subcont_09 IS NULL THEN
			vr_nota_subcont_09 := 'null';
		ELSE
			vr_nota_subcont_09 := pn_nota_subcont_09;
		END IF;
		v_sql1 := 'insert into S_SUB_CONTEUDO_EDUCATIVO_NOTAS(TP_SUBCONTEUDO_EDUCATIVO, TP_CONTEUDO_EDUCATIVO, ano_sem, co_curso, co_seq_serie, co_unidade, co_turma, co_disciplina, co_aluno, NOTA_SUBCONTEUDO_EDUCATIVO) values (';
		v_sql2 := '"' || RTRIM(vr_tp_subcont_ed00) || '"' || ',' || RTRIM(vr_tp_conteudo_e01) || ',' || '"' || RTRIM(vr_ano_sem02) || '"' || ',' || RTRIM(vr_co_curso03) || ',' || RTRIM(vr_co_seq_serie04) || ',' || '"' || RTRIM(vr_co_unidade05) || '"' || ',';
		v_sql3 := RTRIM(vr_co_turma06) || ',' || '"' || RTRIM(vr_co_disciplina07) || '"' || ',' || '"' || RTRIM(vr_co_aluno08) || '"' || ',' || '"' || RTRIM(vr_nota_subcont_09) || '"' || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_tp_subcont_ed00 IS NULL THEN
			vr_tp_subcont_ed00 := 'null';
		ELSE
			vr_tp_subcont_ed00 := '"' || RTRIM(pa_tp_subcont_ed00) || '"';
		END IF;
		IF pa_tp_conteudo_e01 IS NULL THEN
			vr_tp_conteudo_e01 := 'null';
		ELSE
			vr_tp_conteudo_e01 := pa_tp_conteudo_e01;
		END IF;
		IF pa_ano_sem02 IS NULL THEN
			vr_ano_sem02 := 'null';
		ELSE
			vr_ano_sem02 := '"' || RTRIM(pa_ano_sem02) || '"';
		END IF;
		IF pa_co_curso03 IS NULL THEN
			vr_co_curso03 := 'null';
		ELSE
			vr_co_curso03 := pa_co_curso03;
		END IF;
		IF pa_co_seq_serie04 IS NULL THEN
			vr_co_seq_serie04 := 'null';
		ELSE
			vr_co_seq_serie04 := pa_co_seq_serie04;
		END IF;
		IF pa_co_unidade05 IS NULL THEN
			vr_co_unidade05 := 'null';
		ELSE
			vr_co_unidade05 := '"' || RTRIM(pa_co_unidade05) || '"';
		END IF;
		IF pa_co_turma06 IS NULL THEN
			vr_co_turma06 := 'null';
		ELSE
			vr_co_turma06 := pa_co_turma06;
		END IF;
		IF pa_co_disciplina07 IS NULL THEN
			vr_co_disciplina07 := 'null';
		ELSE
			vr_co_disciplina07 := '"' || RTRIM(pa_co_disciplina07) || '"';
		END IF;
		IF pa_co_aluno08 IS NULL THEN
			vr_co_aluno08 := 'null';
		ELSE
			vr_co_aluno08 := '"' || RTRIM(pa_co_aluno08) || '"';
		END IF;
		v_sql1 := '  delete from S_SUB_CONTEUDO_EDUCATIVO_NOTAS where TP_SUBCONTEUDO_EDUCATIVO = ' || RTRIM(vr_tp_subcont_ed00) || '  and TP_CONTEUDO_EDUCATIVO = ' || RTRIM(vr_tp_conteudo_e01);
		v_sql2 := '  and ano_sem = ' || RTRIM(vr_ano_sem02) || '  and co_curso = ' || RTRIM(vr_co_curso03) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie04) || '  and co_unidade = ' || RTRIM(vr_co_unidade05);
		v_sql3 := '  and co_turma = ' || RTRIM(vr_co_turma06) || '  and co_disciplina = ' || RTRIM(vr_co_disciplina07) || '  and co_aluno = ' || RTRIM(vr_co_aluno08) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_tp_subcont_ed00 IS NULL
		AND pa_tp_subcont_ed00 IS NULL THEN
			vr_tp_subcont_ed00 := 'null';
		END IF;
		IF pn_tp_subcont_ed00 IS NULL
		AND pa_tp_subcont_ed00 IS NOT NULL THEN
			vr_tp_subcont_ed00 := 'null';
		END IF;
		IF pn_tp_subcont_ed00 IS NOT NULL
		AND pa_tp_subcont_ed00 IS NULL THEN
			vr_tp_subcont_ed00 := '"' || RTRIM(pn_tp_subcont_ed00) || '"';
		END IF;
		IF pn_tp_subcont_ed00 IS NOT NULL
		AND pa_tp_subcont_ed00 IS NOT NULL THEN
			IF pa_tp_subcont_ed00 <> pn_tp_subcont_ed00 THEN
				vr_tp_subcont_ed00 := '"' || RTRIM(pn_tp_subcont_ed00) || '"';
			ELSE
				vr_tp_subcont_ed00 := '"' || RTRIM(pa_tp_subcont_ed00) || '"';
			END IF;
		END IF;
		IF pn_tp_conteudo_e01 IS NULL
		AND pa_tp_conteudo_e01 IS NULL THEN
			vr_tp_conteudo_e01 := 'null';
		END IF;
		IF pn_tp_conteudo_e01 IS NULL
		AND pa_tp_conteudo_e01 IS NOT NULL THEN
			vr_tp_conteudo_e01 := 'null';
		END IF;
		IF pn_tp_conteudo_e01 IS NOT NULL
		AND pa_tp_conteudo_e01 IS NULL THEN
			vr_tp_conteudo_e01 := pn_tp_conteudo_e01;
		END IF;
		IF pn_tp_conteudo_e01 IS NOT NULL
		AND pa_tp_conteudo_e01 IS NOT NULL THEN
			IF pa_tp_conteudo_e01 <> pn_tp_conteudo_e01 THEN
				vr_tp_conteudo_e01 := pn_tp_conteudo_e01;
			ELSE
				vr_tp_conteudo_e01 := pa_tp_conteudo_e01;
			END IF;
		END IF;
		IF pn_ano_sem02 IS NULL
		AND pa_ano_sem02 IS NULL THEN
			vr_ano_sem02 := 'null';
		END IF;
		IF pn_ano_sem02 IS NULL
		AND pa_ano_sem02 IS NOT NULL THEN
			vr_ano_sem02 := 'null';
		END IF;
		IF pn_ano_sem02 IS NOT NULL
		AND pa_ano_sem02 IS NULL THEN
			vr_ano_sem02 := '"' || RTRIM(pn_ano_sem02) || '"';
		END IF;
		IF pn_ano_sem02 IS NOT NULL
		AND pa_ano_sem02 IS NOT NULL THEN
			IF pa_ano_sem02 <> pn_ano_sem02 THEN
				vr_ano_sem02 := '"' || RTRIM(pn_ano_sem02) || '"';
			ELSE
				vr_ano_sem02 := '"' || RTRIM(pa_ano_sem02) || '"';
			END IF;
		END IF;
		IF pn_co_curso03 IS NULL
		AND pa_co_curso03 IS NULL THEN
			vr_co_curso03 := 'null';
		END IF;
		IF pn_co_curso03 IS NULL
		AND pa_co_curso03 IS NOT NULL THEN
			vr_co_curso03 := 'null';
		END IF;
		IF pn_co_curso03 IS NOT NULL
		AND pa_co_curso03 IS NULL THEN
			vr_co_curso03 := pn_co_curso03;
		END IF;
		IF pn_co_curso03 IS NOT NULL
		AND pa_co_curso03 IS NOT NULL THEN
			IF pa_co_curso03 <> pn_co_curso03 THEN
				vr_co_curso03 := pn_co_curso03;
			ELSE
				vr_co_curso03 := pa_co_curso03;
			END IF;
		END IF;
		IF pn_co_seq_serie04 IS NULL
		AND pa_co_seq_serie04 IS NULL THEN
			vr_co_seq_serie04 := 'null';
		END IF;
		IF pn_co_seq_serie04 IS NULL
		AND pa_co_seq_serie04 IS NOT NULL THEN
			vr_co_seq_serie04 := 'null';
		END IF;
		IF pn_co_seq_serie04 IS NOT NULL
		AND pa_co_seq_serie04 IS NULL THEN
			vr_co_seq_serie04 := pn_co_seq_serie04;
		END IF;
		IF pn_co_seq_serie04 IS NOT NULL
		AND pa_co_seq_serie04 IS NOT NULL THEN
			IF pa_co_seq_serie04 <> pn_co_seq_serie04 THEN
				vr_co_seq_serie04 := pn_co_seq_serie04;
			ELSE
				vr_co_seq_serie04 := pa_co_seq_serie04;
			END IF;
		END IF;
		IF pn_co_unidade05 IS NULL
		AND pa_co_unidade05 IS NULL THEN
			vr_co_unidade05 := 'null';
		END IF;
		IF pn_co_unidade05 IS NULL
		AND pa_co_unidade05 IS NOT NULL THEN
			vr_co_unidade05 := 'null';
		END IF;
		IF pn_co_unidade05 IS NOT NULL
		AND pa_co_unidade05 IS NULL THEN
			vr_co_unidade05 := '"' || RTRIM(pn_co_unidade05) || '"';
		END IF;
		IF pn_co_unidade05 IS NOT NULL
		AND pa_co_unidade05 IS NOT NULL THEN
			IF pa_co_unidade05 <> pn_co_unidade05 THEN
				vr_co_unidade05 := '"' || RTRIM(pn_co_unidade05) || '"';
			ELSE
				vr_co_unidade05 := '"' || RTRIM(pa_co_unidade05) || '"';
			END IF;
		END IF;
		IF pn_co_turma06 IS NULL
		AND pa_co_turma06 IS NULL THEN
			vr_co_turma06 := 'null';
		END IF;
		IF pn_co_turma06 IS NULL
		AND pa_co_turma06 IS NOT NULL THEN
			vr_co_turma06 := 'null';
		END IF;
		IF pn_co_turma06 IS NOT NULL
		AND pa_co_turma06 IS NULL THEN
			vr_co_turma06 := pn_co_turma06;
		END IF;
		IF pn_co_turma06 IS NOT NULL
		AND pa_co_turma06 IS NOT NULL THEN
			IF pa_co_turma06 <> pn_co_turma06 THEN
				vr_co_turma06 := pn_co_turma06;
			ELSE
				vr_co_turma06 := pa_co_turma06;
			END IF;
		END IF;
		IF pn_co_disciplina07 IS NULL
		AND pa_co_disciplina07 IS NULL THEN
			vr_co_disciplina07 := 'null';
		END IF;
		IF pn_co_disciplina07 IS NULL
		AND pa_co_disciplina07 IS NOT NULL THEN
			vr_co_disciplina07 := 'null';
		END IF;
		IF pn_co_disciplina07 IS NOT NULL
		AND pa_co_disciplina07 IS NULL THEN
			vr_co_disciplina07 := '"' || RTRIM(pn_co_disciplina07) || '"';
		END IF;
		IF pn_co_disciplina07 IS NOT NULL
		AND pa_co_disciplina07 IS NOT NULL THEN
			IF pa_co_disciplina07 <> pn_co_disciplina07 THEN
				vr_co_disciplina07 := '"' || RTRIM(pn_co_disciplina07) || '"';
			ELSE
				vr_co_disciplina07 := '"' || RTRIM(pa_co_disciplina07) || '"';
			END IF;
		END IF;
		IF pn_co_aluno08 IS NULL
		AND pa_co_aluno08 IS NULL THEN
			vr_co_aluno08 := 'null';
		END IF;
		IF pn_co_aluno08 IS NULL
		AND pa_co_aluno08 IS NOT NULL THEN
			vr_co_aluno08 := 'null';
		END IF;
		IF pn_co_aluno08 IS NOT NULL
		AND pa_co_aluno08 IS NULL THEN
			vr_co_aluno08 := '"' || RTRIM(pn_co_aluno08) || '"';
		END IF;
		IF pn_co_aluno08 IS NOT NULL
		AND pa_co_aluno08 IS NOT NULL THEN
			IF pa_co_aluno08 <> pn_co_aluno08 THEN
				vr_co_aluno08 := '"' || RTRIM(pn_co_aluno08) || '"';
			ELSE
				vr_co_aluno08 := '"' || RTRIM(pa_co_aluno08) || '"';
			END IF;
		END IF;
		IF pn_nota_subcont_09 IS NULL
		AND pa_nota_subcont_09 IS NULL THEN
			vr_nota_subcont_09 := 'null';
		END IF;
		IF pn_nota_subcont_09 IS NULL
		AND pa_nota_subcont_09 IS NOT NULL THEN
			vr_nota_subcont_09 := 'null';
		END IF;
		IF pn_nota_subcont_09 IS NOT NULL
		AND pa_nota_subcont_09 IS NULL THEN
			vr_nota_subcont_09 := '"' || RTRIM(pn_nota_subcont_09) || '"';
		END IF;
		IF pn_nota_subcont_09 IS NOT NULL
		AND pa_nota_subcont_09 IS NOT NULL THEN
			IF pa_nota_subcont_09 <> pn_nota_subcont_09 THEN
				vr_nota_subcont_09 := '"' || RTRIM(pn_nota_subcont_09) || '"';
			ELSE
				vr_nota_subcont_09 := '"' || RTRIM(pa_nota_subcont_09) || '"';
			END IF;
		END IF;
		v_sql1 := 'update S_SUB_CONTEUDO_EDUCATIVO_NOTAS set TP_SUBCONTEUDO_EDUCATIVO = ' || RTRIM(vr_tp_subcont_ed00) || '  , TP_CONTEUDO_EDUCATIVO = ' || RTRIM(vr_tp_conteudo_e01) || '  , ano_sem = ' || RTRIM(vr_ano_sem02) || '  , co_curso = ' || RTRIM(vr_co_curso03) || '  , co_seq_serie = ' || RTRIM(vr_co_seq_serie04) || '  , co_unidade = ' || RTRIM(vr_co_unidade05);
		v_sql2 := '  , co_turma = ' || RTRIM(vr_co_turma06) || '  , co_disciplina = ' || RTRIM(vr_co_disciplina07) || '  , co_aluno = ' || RTRIM(vr_co_aluno08) || '  , NOTA_SUBCONTEUDO_EDUCATIVO = ' || RTRIM(vr_nota_subcont_09) || ' where tp_subcont_educ = ' || RTRIM(vr_tp_subcont_ed00) || '  and tp_conteudo_educ = ' || RTRIM(vr_tp_conteudo_e01) || '  and ano_sem = ' || RTRIM(vr_ano_sem02);
		v_sql3 := '  and co_curso = ' || RTRIM(vr_co_curso03) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie04) || '  and co_unidade = ' || RTRIM(vr_co_unidade05) || '  and co_turma = ' || RTRIM(vr_co_turma06) || '  and co_disciplina = ' || RTRIM(vr_co_disciplina07) || '  and co_aluno = ' || RTRIM(vr_co_aluno08) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	END IF;
	IF p_op = 'del' THEN
		v_uni := pa_co_unidade05;
	ELSE
		v_uni := pn_co_unidade05;
	END IF;
	IF user <> 'atualiza' THEN
		INSERT INTO tab_log_atualiza(sequencial,
		                             usuario,
		                             co_unidade,
		                             tabela,
		                             operacao,
		                             sql,
		                             indicador_extraido,
		                             data_hora,
		                             blob1,
		                             blob2)
		VALUES(0,
		       user,
		       v_uni,
		       's_subcont_educ_not',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_subcont_148;
/

