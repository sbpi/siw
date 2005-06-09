CREATE OR REPLACE PROCEDURE pr_s_horario_118(
	P_OP_IN                CHAR,
	PA_co_unidade00_IN     s_horario_aluno.co_unidade%TYPE,
	PA_co_tipo_horar01_IN  s_horario_aluno.co_tipo_horario%TYPE,
	PA_ano_sem02_IN        s_horario_aluno.ano_sem%TYPE,
	PA_nu_dia03_IN         s_horario_aluno.nu_dia%TYPE,
	PA_nu_tempo04_IN       s_horario_aluno.nu_tempo%TYPE,
	PA_co_disciplina05_IN  s_horario_aluno.co_disciplina%TYPE,
	PA_co_turma06_IN       s_horario_aluno.co_turma%TYPE,
	PA_co_aluno07_IN       s_horario_aluno.co_aluno%TYPE,
	PA_co_curso08_IN       s_horario_aluno.co_curso%TYPE,
	PA_co_seq_serie09_IN   s_horario_aluno.co_seq_serie%TYPE,
	PN_co_unidade00_IN     s_horario_aluno.co_unidade%TYPE,
	PN_co_tipo_horar01_IN  s_horario_aluno.co_tipo_horario%TYPE,
	PN_ano_sem02_IN        s_horario_aluno.ano_sem%TYPE,
	PN_nu_dia03_IN         s_horario_aluno.nu_dia%TYPE,
	PN_nu_tempo04_IN       s_horario_aluno.nu_tempo%TYPE,
	PN_co_disciplina05_IN  s_horario_aluno.co_disciplina%TYPE,
	PN_co_turma06_IN       s_horario_aluno.co_turma%TYPE,
	PN_co_aluno07_IN       s_horario_aluno.co_aluno%TYPE,
	PN_co_curso08_IN       s_horario_aluno.co_curso%TYPE,
	PN_co_seq_serie09_IN   s_horario_aluno.co_seq_serie%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_unidade00     s_horario_aluno.co_unidade%TYPE := PA_co_unidade00_IN;
PA_co_tipo_horar01  s_horario_aluno.co_tipo_horario%TYPE := PA_co_tipo_horar01_IN;
PA_ano_sem02        s_horario_aluno.ano_sem%TYPE := PA_ano_sem02_IN;
PA_nu_dia03         s_horario_aluno.nu_dia%TYPE := PA_nu_dia03_IN;
PA_nu_tempo04       s_horario_aluno.nu_tempo%TYPE := PA_nu_tempo04_IN;
PA_co_disciplina05  s_horario_aluno.co_disciplina%TYPE := PA_co_disciplina05_IN;
PA_co_turma06       s_horario_aluno.co_turma%TYPE := PA_co_turma06_IN;
PA_co_aluno07       s_horario_aluno.co_aluno%TYPE := PA_co_aluno07_IN;
PA_co_curso08       s_horario_aluno.co_curso%TYPE := PA_co_curso08_IN;
PA_co_seq_serie09   s_horario_aluno.co_seq_serie%TYPE := PA_co_seq_serie09_IN;
PN_co_unidade00     s_horario_aluno.co_unidade%TYPE := PN_co_unidade00_IN;
PN_co_tipo_horar01  s_horario_aluno.co_tipo_horario%TYPE := PN_co_tipo_horar01_IN;
PN_ano_sem02        s_horario_aluno.ano_sem%TYPE := PN_ano_sem02_IN;
PN_nu_dia03         s_horario_aluno.nu_dia%TYPE := PN_nu_dia03_IN;
PN_nu_tempo04       s_horario_aluno.nu_tempo%TYPE := PN_nu_tempo04_IN;
PN_co_disciplina05  s_horario_aluno.co_disciplina%TYPE := PN_co_disciplina05_IN;
PN_co_turma06       s_horario_aluno.co_turma%TYPE := PN_co_turma06_IN;
PN_co_aluno07       s_horario_aluno.co_aluno%TYPE := PN_co_aluno07_IN;
PN_co_curso08       s_horario_aluno.co_curso%TYPE := PN_co_curso08_IN;
PN_co_seq_serie09   s_horario_aluno.co_seq_serie%TYPE := PN_co_seq_serie09_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_unidade00     CHAR(10);
vr_co_tipo_horar01  CHAR(10);
vr_ano_sem02        CHAR(10);
vr_nu_dia03         CHAR(10);
vr_nu_tempo04       CHAR(10);
vr_co_disciplina05  CHAR(10);
vr_co_turma06       CHAR(10);
vr_co_aluno07       CHAR(20);
vr_co_curso08       CHAR(10);
vr_co_seq_serie09   CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := pn_co_unidade00;
		END IF;
		IF pn_co_tipo_horar01 IS NULL THEN
			vr_co_tipo_horar01 := 'null';
		ELSE
			vr_co_tipo_horar01 := pn_co_tipo_horar01;
		END IF;
		IF pn_ano_sem02 IS NULL THEN
			vr_ano_sem02 := 'null';
		ELSE
			vr_ano_sem02 := pn_ano_sem02;
		END IF;
		IF pn_nu_dia03 IS NULL THEN
			vr_nu_dia03 := 'null';
		ELSE
			vr_nu_dia03 := pn_nu_dia03;
		END IF;
		IF pn_nu_tempo04 IS NULL THEN
			vr_nu_tempo04 := 'null';
		ELSE
			vr_nu_tempo04 := pn_nu_tempo04;
		END IF;
		IF pn_co_disciplina05 IS NULL THEN
			vr_co_disciplina05 := 'null';
		ELSE
			vr_co_disciplina05 := pn_co_disciplina05;
		END IF;
		IF pn_co_turma06 IS NULL THEN
			vr_co_turma06 := 'null';
		ELSE
			vr_co_turma06 := pn_co_turma06;
		END IF;
		IF pn_co_aluno07 IS NULL THEN
			vr_co_aluno07 := 'null';
		ELSE
			vr_co_aluno07 := pn_co_aluno07;
		END IF;
		IF pn_co_curso08 IS NULL THEN
			vr_co_curso08 := 'null';
		ELSE
			vr_co_curso08 := pn_co_curso08;
		END IF;
		IF pn_co_seq_serie09 IS NULL THEN
			vr_co_seq_serie09 := 'null';
		ELSE
			vr_co_seq_serie09 := pn_co_seq_serie09;
		END IF;
		v_sql1 := 'insert into s_horario_aluno(co_unidade, co_tipo_horario, ano_sem, nu_dia, nu_tempo, co_disciplina, co_turma, co_aluno, co_curso, co_seq_serie) values (';
		v_sql2 := '"' || RTRIM(vr_co_unidade00) || '"' || ',' || RTRIM(vr_co_tipo_horar01) || ',' || '"' || RTRIM(vr_ano_sem02) || '"' || ',' || RTRIM(vr_nu_dia03) || ',' || RTRIM(vr_nu_tempo04) || ',';
		v_sql3 := '"' || RTRIM(vr_co_disciplina05) || '"' || ',' || RTRIM(vr_co_turma06) || ',' || '"' || RTRIM(vr_co_aluno07) || '"' || ',' || RTRIM(vr_co_curso08) || ',' || RTRIM(vr_co_seq_serie09) || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := '"' || RTRIM(pa_co_unidade00) || '"';
		END IF;
		IF pa_co_tipo_horar01 IS NULL THEN
			vr_co_tipo_horar01 := 'null';
		ELSE
			vr_co_tipo_horar01 := pa_co_tipo_horar01;
		END IF;
		IF pa_ano_sem02 IS NULL THEN
			vr_ano_sem02 := 'null';
		ELSE
			vr_ano_sem02 := '"' || RTRIM(pa_ano_sem02) || '"';
		END IF;
		IF pa_nu_dia03 IS NULL THEN
			vr_nu_dia03 := 'null';
		ELSE
			vr_nu_dia03 := pa_nu_dia03;
		END IF;
		IF pa_nu_tempo04 IS NULL THEN
			vr_nu_tempo04 := 'null';
		ELSE
			vr_nu_tempo04 := pa_nu_tempo04;
		END IF;
		IF pa_co_disciplina05 IS NULL THEN
			vr_co_disciplina05 := 'null';
		ELSE
			vr_co_disciplina05 := '"' || RTRIM(pa_co_disciplina05) || '"';
		END IF;
		IF pa_co_turma06 IS NULL THEN
			vr_co_turma06 := 'null';
		ELSE
			vr_co_turma06 := pa_co_turma06;
		END IF;
		IF pa_co_aluno07 IS NULL THEN
			vr_co_aluno07 := 'null';
		ELSE
			vr_co_aluno07 := '"' || RTRIM(pa_co_aluno07) || '"';
		END IF;
		IF pa_co_curso08 IS NULL THEN
			vr_co_curso08 := 'null';
		ELSE
			vr_co_curso08 := pa_co_curso08;
		END IF;
		IF pa_co_seq_serie09 IS NULL THEN
			vr_co_seq_serie09 := 'null';
		ELSE
			vr_co_seq_serie09 := pa_co_seq_serie09;
		END IF;
		v_sql1 := '  delete from s_horario_aluno where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and co_tipo_horario = ' || RTRIM(vr_co_tipo_horar01) || '  and ano_sem = ' || RTRIM(vr_ano_sem02) || '  and nu_dia = ' || RTRIM(vr_nu_dia03);
		v_sql2 := '  and nu_tempo = ' || RTRIM(vr_nu_tempo04) || '  and co_disciplina = ' || RTRIM(vr_co_disciplina05) || '  and co_turma = ' || RTRIM(vr_co_turma06);
		v_sql3 := '  and co_aluno = ' || RTRIM(vr_co_aluno07) || '  and co_curso = ' || RTRIM(vr_co_curso08) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie09) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_unidade00 IS NULL
		AND pa_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		END IF;
		IF pn_co_unidade00 IS NULL
		AND pa_co_unidade00 IS NOT NULL THEN
			vr_co_unidade00 := 'null';
		END IF;
		IF pn_co_unidade00 IS NOT NULL
		AND pa_co_unidade00 IS NULL THEN
			vr_co_unidade00 := '"' || RTRIM(pn_co_unidade00) || '"';
		END IF;
		IF pn_co_unidade00 IS NOT NULL
		AND pa_co_unidade00 IS NOT NULL THEN
			IF pa_co_unidade00 <> pn_co_unidade00 THEN
				vr_co_unidade00 := '"' || RTRIM(pn_co_unidade00) || '"';
			ELSE
				vr_co_unidade00 := '"' || RTRIM(pa_co_unidade00) || '"';
			END IF;
		END IF;
		IF pn_co_tipo_horar01 IS NULL
		AND pa_co_tipo_horar01 IS NULL THEN
			vr_co_tipo_horar01 := 'null';
		END IF;
		IF pn_co_tipo_horar01 IS NULL
		AND pa_co_tipo_horar01 IS NOT NULL THEN
			vr_co_tipo_horar01 := 'null';
		END IF;
		IF pn_co_tipo_horar01 IS NOT NULL
		AND pa_co_tipo_horar01 IS NULL THEN
			vr_co_tipo_horar01 := pn_co_tipo_horar01;
		END IF;
		IF pn_co_tipo_horar01 IS NOT NULL
		AND pa_co_tipo_horar01 IS NOT NULL THEN
			IF pa_co_tipo_horar01 <> pn_co_tipo_horar01 THEN
				vr_co_tipo_horar01 := pn_co_tipo_horar01;
			ELSE
				vr_co_tipo_horar01 := pa_co_tipo_horar01;
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
		IF pn_nu_dia03 IS NULL
		AND pa_nu_dia03 IS NULL THEN
			vr_nu_dia03 := 'null';
		END IF;
		IF pn_nu_dia03 IS NULL
		AND pa_nu_dia03 IS NOT NULL THEN
			vr_nu_dia03 := 'null';
		END IF;
		IF pn_nu_dia03 IS NOT NULL
		AND pa_nu_dia03 IS NULL THEN
			vr_nu_dia03 := pn_nu_dia03;
		END IF;
		IF pn_nu_dia03 IS NOT NULL
		AND pa_nu_dia03 IS NOT NULL THEN
			IF pa_nu_dia03 <> pn_nu_dia03 THEN
				vr_nu_dia03 := pn_nu_dia03;
			ELSE
				vr_nu_dia03 := pa_nu_dia03;
			END IF;
		END IF;
		IF pn_nu_tempo04 IS NULL
		AND pa_nu_tempo04 IS NULL THEN
			vr_nu_tempo04 := 'null';
		END IF;
		IF pn_nu_tempo04 IS NULL
		AND pa_nu_tempo04 IS NOT NULL THEN
			vr_nu_tempo04 := 'null';
		END IF;
		IF pn_nu_tempo04 IS NOT NULL
		AND pa_nu_tempo04 IS NULL THEN
			vr_nu_tempo04 := pn_nu_tempo04;
		END IF;
		IF pn_nu_tempo04 IS NOT NULL
		AND pa_nu_tempo04 IS NOT NULL THEN
			IF pa_nu_tempo04 <> pn_nu_tempo04 THEN
				vr_nu_tempo04 := pn_nu_tempo04;
			ELSE
				vr_nu_tempo04 := pa_nu_tempo04;
			END IF;
		END IF;
		IF pn_co_disciplina05 IS NULL
		AND pa_co_disciplina05 IS NULL THEN
			vr_co_disciplina05 := 'null';
		END IF;
		IF pn_co_disciplina05 IS NULL
		AND pa_co_disciplina05 IS NOT NULL THEN
			vr_co_disciplina05 := 'null';
		END IF;
		IF pn_co_disciplina05 IS NOT NULL
		AND pa_co_disciplina05 IS NULL THEN
			vr_co_disciplina05 := '"' || RTRIM(pn_co_disciplina05) || '"';
		END IF;
		IF pn_co_disciplina05 IS NOT NULL
		AND pa_co_disciplina05 IS NOT NULL THEN
			IF pa_co_disciplina05 <> pn_co_disciplina05 THEN
				vr_co_disciplina05 := '"' || RTRIM(pn_co_disciplina05) || '"';
			ELSE
				vr_co_disciplina05 := '"' || RTRIM(pa_co_disciplina05) || '"';
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
		IF pn_co_aluno07 IS NULL
		AND pa_co_aluno07 IS NULL THEN
			vr_co_aluno07 := 'null';
		END IF;
		IF pn_co_aluno07 IS NULL
		AND pa_co_aluno07 IS NOT NULL THEN
			vr_co_aluno07 := 'null';
		END IF;
		IF pn_co_aluno07 IS NOT NULL
		AND pa_co_aluno07 IS NULL THEN
			vr_co_aluno07 := '"' || RTRIM(pn_co_aluno07) || '"';
		END IF;
		IF pn_co_aluno07 IS NOT NULL
		AND pa_co_aluno07 IS NOT NULL THEN
			IF pa_co_aluno07 <> pn_co_aluno07 THEN
				vr_co_aluno07 := '"' || RTRIM(pn_co_aluno07) || '"';
			ELSE
				vr_co_aluno07 := '"' || RTRIM(pa_co_aluno07) || '"';
			END IF;
		END IF;
		IF pn_co_curso08 IS NULL
		AND pa_co_curso08 IS NULL THEN
			vr_co_curso08 := 'null';
		END IF;
		IF pn_co_curso08 IS NULL
		AND pa_co_curso08 IS NOT NULL THEN
			vr_co_curso08 := 'null';
		END IF;
		IF pn_co_curso08 IS NOT NULL
		AND pa_co_curso08 IS NULL THEN
			vr_co_curso08 := pn_co_curso08;
		END IF;
		IF pn_co_curso08 IS NOT NULL
		AND pa_co_curso08 IS NOT NULL THEN
			IF pa_co_curso08 <> pn_co_curso08 THEN
				vr_co_curso08 := pn_co_curso08;
			ELSE
				vr_co_curso08 := pa_co_curso08;
			END IF;
		END IF;
		IF pn_co_seq_serie09 IS NULL
		AND pa_co_seq_serie09 IS NULL THEN
			vr_co_seq_serie09 := 'null';
		END IF;
		IF pn_co_seq_serie09 IS NULL
		AND pa_co_seq_serie09 IS NOT NULL THEN
			vr_co_seq_serie09 := 'null';
		END IF;
		IF pn_co_seq_serie09 IS NOT NULL
		AND pa_co_seq_serie09 IS NULL THEN
			vr_co_seq_serie09 := pn_co_seq_serie09;
		END IF;
		IF pn_co_seq_serie09 IS NOT NULL
		AND pa_co_seq_serie09 IS NOT NULL THEN
			IF pa_co_seq_serie09 <> pn_co_seq_serie09 THEN
				vr_co_seq_serie09 := pn_co_seq_serie09;
			ELSE
				vr_co_seq_serie09 := pa_co_seq_serie09;
			END IF;
		END IF;
		v_sql1 := 'update s_horario_aluno set co_unidade = ' || RTRIM(vr_co_unidade00) || '  , co_tipo_horario = ' || RTRIM(vr_co_tipo_horar01) || '  , ano_sem = ' || RTRIM(vr_ano_sem02) || '  , nu_dia = ' || RTRIM(vr_nu_dia03) || '  , nu_tempo = ' || RTRIM(vr_nu_tempo04) || '  , co_disciplina = ' || RTRIM(vr_co_disciplina05) || '  , co_turma = ' || RTRIM(vr_co_turma06);
		v_sql2 := '  , co_aluno = ' || RTRIM(vr_co_aluno07) || '  , co_curso = ' || RTRIM(vr_co_curso08) || '  , co_seq_serie = ' || RTRIM(vr_co_seq_serie09) || ' where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and co_tipo_horario = ' || RTRIM(vr_co_tipo_horar01) || '  and ano_sem = ' || RTRIM(vr_ano_sem02) || '  and nu_dia = ' || RTRIM(vr_nu_dia03) || '  and nu_tempo = ' || RTRIM(vr_nu_tempo04);
		v_sql3 := '  and co_disciplina = ' || RTRIM(vr_co_disciplina05) || '  and co_turma = ' || RTRIM(vr_co_turma06) || '  and co_aluno = ' || RTRIM(vr_co_aluno07) || '  and co_curso = ' || RTRIM(vr_co_curso08) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie09) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	END IF;
	IF p_op = 'del' THEN
		v_uni := pa_co_unidade00;
	ELSE
		v_uni := pn_co_unidade00;
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
		       's_horario_aluno',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_horario_118;
/

