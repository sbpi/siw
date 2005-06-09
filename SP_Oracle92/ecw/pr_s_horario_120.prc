CREATE OR REPLACE PROCEDURE pr_s_horario_120(
	P_OP_IN                CHAR,
	PA_co_tipo_horar00_IN  s_horario_turma.co_tipo_horario%TYPE,
	PA_nu_dia_semana01_IN  s_horario_turma.nu_dia_semana%TYPE,
	PA_co_curso02_IN       s_horario_turma.co_curso%TYPE,
	PA_nu_tempo03_IN       s_horario_turma.nu_tempo%TYPE,
	PA_co_unidade04_IN     s_horario_turma.co_unidade%TYPE,
	PA_ano_sem05_IN        s_horario_turma.ano_sem%TYPE,
	PA_co_funcionari06_IN  s_horario_turma.co_funcionario%TYPE,
	PA_co_turma07_IN       s_horario_turma.co_turma%TYPE,
	PA_co_disciplina08_IN  s_horario_turma.co_disciplina%TYPE,
	PA_co_seq_serie09_IN   s_horario_turma.co_seq_serie%TYPE,
	PN_co_tipo_horar00_IN  s_horario_turma.co_tipo_horario%TYPE,
	PN_nu_dia_semana01_IN  s_horario_turma.nu_dia_semana%TYPE,
	PN_co_curso02_IN       s_horario_turma.co_curso%TYPE,
	PN_nu_tempo03_IN       s_horario_turma.nu_tempo%TYPE,
	PN_co_unidade04_IN     s_horario_turma.co_unidade%TYPE,
	PN_ano_sem05_IN        s_horario_turma.ano_sem%TYPE,
	PN_co_funcionari06_IN  s_horario_turma.co_funcionario%TYPE,
	PN_co_turma07_IN       s_horario_turma.co_turma%TYPE,
	PN_co_disciplina08_IN  s_horario_turma.co_disciplina%TYPE,
	PN_co_seq_serie09_IN   s_horario_turma.co_seq_serie%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_tipo_horar00  s_horario_turma.co_tipo_horario%TYPE := PA_co_tipo_horar00_IN;
PA_nu_dia_semana01  s_horario_turma.nu_dia_semana%TYPE := PA_nu_dia_semana01_IN;
PA_co_curso02       s_horario_turma.co_curso%TYPE := PA_co_curso02_IN;
PA_nu_tempo03       s_horario_turma.nu_tempo%TYPE := PA_nu_tempo03_IN;
PA_co_unidade04     s_horario_turma.co_unidade%TYPE := PA_co_unidade04_IN;
PA_ano_sem05        s_horario_turma.ano_sem%TYPE := PA_ano_sem05_IN;
PA_co_funcionari06  s_horario_turma.co_funcionario%TYPE := PA_co_funcionari06_IN;
PA_co_turma07       s_horario_turma.co_turma%TYPE := PA_co_turma07_IN;
PA_co_disciplina08  s_horario_turma.co_disciplina%TYPE := PA_co_disciplina08_IN;
PA_co_seq_serie09   s_horario_turma.co_seq_serie%TYPE := PA_co_seq_serie09_IN;
PN_co_tipo_horar00  s_horario_turma.co_tipo_horario%TYPE := PN_co_tipo_horar00_IN;
PN_nu_dia_semana01  s_horario_turma.nu_dia_semana%TYPE := PN_nu_dia_semana01_IN;
PN_co_curso02       s_horario_turma.co_curso%TYPE := PN_co_curso02_IN;
PN_nu_tempo03       s_horario_turma.nu_tempo%TYPE := PN_nu_tempo03_IN;
PN_co_unidade04     s_horario_turma.co_unidade%TYPE := PN_co_unidade04_IN;
PN_ano_sem05        s_horario_turma.ano_sem%TYPE := PN_ano_sem05_IN;
PN_co_funcionari06  s_horario_turma.co_funcionario%TYPE := PN_co_funcionari06_IN;
PN_co_turma07       s_horario_turma.co_turma%TYPE := PN_co_turma07_IN;
PN_co_disciplina08  s_horario_turma.co_disciplina%TYPE := PN_co_disciplina08_IN;
PN_co_seq_serie09   s_horario_turma.co_seq_serie%TYPE := PN_co_seq_serie09_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_tipo_horar00  CHAR(10);
vr_nu_dia_semana01  CHAR(10);
vr_co_curso02       CHAR(10);
vr_nu_tempo03       CHAR(10);
vr_co_unidade04     CHAR(10);
vr_ano_sem05        CHAR(10);
vr_co_funcionari06  CHAR(20);
vr_co_turma07       CHAR(10);
vr_co_disciplina08  CHAR(10);
vr_co_seq_serie09   CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_tipo_horar00 IS NULL THEN
			vr_co_tipo_horar00 := 'null';
		ELSE
			vr_co_tipo_horar00 := pn_co_tipo_horar00;
		END IF;
		IF pn_nu_dia_semana01 IS NULL THEN
			vr_nu_dia_semana01 := 'null';
		ELSE
			vr_nu_dia_semana01 := pn_nu_dia_semana01;
		END IF;
		IF pn_co_curso02 IS NULL THEN
			vr_co_curso02 := 'null';
		ELSE
			vr_co_curso02 := pn_co_curso02;
		END IF;
		IF pn_nu_tempo03 IS NULL THEN
			vr_nu_tempo03 := 'null';
		ELSE
			vr_nu_tempo03 := pn_nu_tempo03;
		END IF;
		IF pn_co_unidade04 IS NULL THEN
			vr_co_unidade04 := 'null';
		ELSE
			vr_co_unidade04 := pn_co_unidade04;
		END IF;
		IF pn_ano_sem05 IS NULL THEN
			vr_ano_sem05 := 'null';
		ELSE
			vr_ano_sem05 := pn_ano_sem05;
		END IF;
		IF pn_co_funcionari06 IS NULL THEN
			vr_co_funcionari06 := 'null';
		ELSE
			vr_co_funcionari06 := pn_co_funcionari06;
		END IF;
		IF pn_co_turma07 IS NULL THEN
			vr_co_turma07 := 'null';
		ELSE
			vr_co_turma07 := pn_co_turma07;
		END IF;
		IF pn_co_disciplina08 IS NULL THEN
			vr_co_disciplina08 := 'null';
		ELSE
			vr_co_disciplina08 := pn_co_disciplina08;
		END IF;
		IF pn_co_seq_serie09 IS NULL THEN
			vr_co_seq_serie09 := 'null';
		ELSE
			vr_co_seq_serie09 := pn_co_seq_serie09;
		END IF;
		v_sql1 := 'insert into s_horario_turma(co_tipo_horario, nu_dia_semana, co_curso, nu_tempo, co_unidade, ano_sem, co_funcionario, co_turma, co_disciplina, co_seq_serie) values (';
		v_sql2 := RTRIM(vr_co_tipo_horar00) || ',' || RTRIM(vr_nu_dia_semana01) || ',' || RTRIM(vr_co_curso02) || ',' || RTRIM(vr_nu_tempo03) || ',' || '"' || RTRIM(vr_co_unidade04) || '"' || ',' || '"' || RTRIM(vr_ano_sem05) || '"' || ',';
		v_sql3 := '"' || RTRIM(vr_co_funcionari06) || '"' || ',' || RTRIM(vr_co_turma07) || ',' || '"' || RTRIM(vr_co_disciplina08) || '"' || ',' || RTRIM(vr_co_seq_serie09) || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_tipo_horar00 IS NULL THEN
			vr_co_tipo_horar00 := 'null';
		ELSE
			vr_co_tipo_horar00 := pa_co_tipo_horar00;
		END IF;
		IF pa_nu_dia_semana01 IS NULL THEN
			vr_nu_dia_semana01 := 'null';
		ELSE
			vr_nu_dia_semana01 := pa_nu_dia_semana01;
		END IF;
		IF pa_co_curso02 IS NULL THEN
			vr_co_curso02 := 'null';
		ELSE
			vr_co_curso02 := pa_co_curso02;
		END IF;
		IF pa_nu_tempo03 IS NULL THEN
			vr_nu_tempo03 := 'null';
		ELSE
			vr_nu_tempo03 := pa_nu_tempo03;
		END IF;
		IF pa_co_unidade04 IS NULL THEN
			vr_co_unidade04 := 'null';
		ELSE
			vr_co_unidade04 := '"' || RTRIM(pa_co_unidade04) || '"';
		END IF;
		IF pa_ano_sem05 IS NULL THEN
			vr_ano_sem05 := 'null';
		ELSE
			vr_ano_sem05 := '"' || RTRIM(pa_ano_sem05) || '"';
		END IF;
		IF pa_co_funcionari06 IS NULL THEN
			vr_co_funcionari06 := 'null';
		ELSE
			vr_co_funcionari06 := '"' || RTRIM(pa_co_funcionari06) || '"';
		END IF;
		IF pa_co_turma07 IS NULL THEN
			vr_co_turma07 := 'null';
		ELSE
			vr_co_turma07 := pa_co_turma07;
		END IF;
		IF pa_co_disciplina08 IS NULL THEN
			vr_co_disciplina08 := 'null';
		ELSE
			vr_co_disciplina08 := '"' || RTRIM(pa_co_disciplina08) || '"';
		END IF;
		IF pa_co_seq_serie09 IS NULL THEN
			vr_co_seq_serie09 := 'null';
		ELSE
			vr_co_seq_serie09 := pa_co_seq_serie09;
		END IF;
		v_sql1 := '  delete from s_horario_turma where co_tipo_horario = ' || RTRIM(vr_co_tipo_horar00) || '  and nu_dia_semana = ' || RTRIM(vr_nu_dia_semana01) || '  and co_curso = ' || RTRIM(vr_co_curso02) || '  and nu_tempo = ' || RTRIM(vr_nu_tempo03);
		v_sql2 := '  and co_unidade = ' || RTRIM(vr_co_unidade04) || '  and ano_sem = ' || RTRIM(vr_ano_sem05) || '  and co_funcionario = ' || RTRIM(vr_co_funcionari06) || '  and co_turma = ' || RTRIM(vr_co_turma07);
		v_sql3 := '  and co_disciplina = ' || RTRIM(vr_co_disciplina08) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie09) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_tipo_horar00 IS NULL
		AND pa_co_tipo_horar00 IS NULL THEN
			vr_co_tipo_horar00 := 'null';
		END IF;
		IF pn_co_tipo_horar00 IS NULL
		AND pa_co_tipo_horar00 IS NOT NULL THEN
			vr_co_tipo_horar00 := 'null';
		END IF;
		IF pn_co_tipo_horar00 IS NOT NULL
		AND pa_co_tipo_horar00 IS NULL THEN
			vr_co_tipo_horar00 := pn_co_tipo_horar00;
		END IF;
		IF pn_co_tipo_horar00 IS NOT NULL
		AND pa_co_tipo_horar00 IS NOT NULL THEN
			IF pa_co_tipo_horar00 <> pn_co_tipo_horar00 THEN
				vr_co_tipo_horar00 := pn_co_tipo_horar00;
			ELSE
				vr_co_tipo_horar00 := pa_co_tipo_horar00;
			END IF;
		END IF;
		IF pn_nu_dia_semana01 IS NULL
		AND pa_nu_dia_semana01 IS NULL THEN
			vr_nu_dia_semana01 := 'null';
		END IF;
		IF pn_nu_dia_semana01 IS NULL
		AND pa_nu_dia_semana01 IS NOT NULL THEN
			vr_nu_dia_semana01 := 'null';
		END IF;
		IF pn_nu_dia_semana01 IS NOT NULL
		AND pa_nu_dia_semana01 IS NULL THEN
			vr_nu_dia_semana01 := pn_nu_dia_semana01;
		END IF;
		IF pn_nu_dia_semana01 IS NOT NULL
		AND pa_nu_dia_semana01 IS NOT NULL THEN
			IF pa_nu_dia_semana01 <> pn_nu_dia_semana01 THEN
				vr_nu_dia_semana01 := pn_nu_dia_semana01;
			ELSE
				vr_nu_dia_semana01 := pa_nu_dia_semana01;
			END IF;
		END IF;
		IF pn_co_curso02 IS NULL
		AND pa_co_curso02 IS NULL THEN
			vr_co_curso02 := 'null';
		END IF;
		IF pn_co_curso02 IS NULL
		AND pa_co_curso02 IS NOT NULL THEN
			vr_co_curso02 := 'null';
		END IF;
		IF pn_co_curso02 IS NOT NULL
		AND pa_co_curso02 IS NULL THEN
			vr_co_curso02 := pn_co_curso02;
		END IF;
		IF pn_co_curso02 IS NOT NULL
		AND pa_co_curso02 IS NOT NULL THEN
			IF pa_co_curso02 <> pn_co_curso02 THEN
				vr_co_curso02 := pn_co_curso02;
			ELSE
				vr_co_curso02 := pa_co_curso02;
			END IF;
		END IF;
		IF pn_nu_tempo03 IS NULL
		AND pa_nu_tempo03 IS NULL THEN
			vr_nu_tempo03 := 'null';
		END IF;
		IF pn_nu_tempo03 IS NULL
		AND pa_nu_tempo03 IS NOT NULL THEN
			vr_nu_tempo03 := 'null';
		END IF;
		IF pn_nu_tempo03 IS NOT NULL
		AND pa_nu_tempo03 IS NULL THEN
			vr_nu_tempo03 := pn_nu_tempo03;
		END IF;
		IF pn_nu_tempo03 IS NOT NULL
		AND pa_nu_tempo03 IS NOT NULL THEN
			IF pa_nu_tempo03 <> pn_nu_tempo03 THEN
				vr_nu_tempo03 := pn_nu_tempo03;
			ELSE
				vr_nu_tempo03 := pa_nu_tempo03;
			END IF;
		END IF;
		IF pn_co_unidade04 IS NULL
		AND pa_co_unidade04 IS NULL THEN
			vr_co_unidade04 := 'null';
		END IF;
		IF pn_co_unidade04 IS NULL
		AND pa_co_unidade04 IS NOT NULL THEN
			vr_co_unidade04 := 'null';
		END IF;
		IF pn_co_unidade04 IS NOT NULL
		AND pa_co_unidade04 IS NULL THEN
			vr_co_unidade04 := '"' || RTRIM(pn_co_unidade04) || '"';
		END IF;
		IF pn_co_unidade04 IS NOT NULL
		AND pa_co_unidade04 IS NOT NULL THEN
			IF pa_co_unidade04 <> pn_co_unidade04 THEN
				vr_co_unidade04 := '"' || RTRIM(pn_co_unidade04) || '"';
			ELSE
				vr_co_unidade04 := '"' || RTRIM(pa_co_unidade04) || '"';
			END IF;
		END IF;
		IF pn_ano_sem05 IS NULL
		AND pa_ano_sem05 IS NULL THEN
			vr_ano_sem05 := 'null';
		END IF;
		IF pn_ano_sem05 IS NULL
		AND pa_ano_sem05 IS NOT NULL THEN
			vr_ano_sem05 := 'null';
		END IF;
		IF pn_ano_sem05 IS NOT NULL
		AND pa_ano_sem05 IS NULL THEN
			vr_ano_sem05 := '"' || RTRIM(pn_ano_sem05) || '"';
		END IF;
		IF pn_ano_sem05 IS NOT NULL
		AND pa_ano_sem05 IS NOT NULL THEN
			IF pa_ano_sem05 <> pn_ano_sem05 THEN
				vr_ano_sem05 := '"' || RTRIM(pn_ano_sem05) || '"';
			ELSE
				vr_ano_sem05 := '"' || RTRIM(pa_ano_sem05) || '"';
			END IF;
		END IF;
		IF pn_co_funcionari06 IS NULL
		AND pa_co_funcionari06 IS NULL THEN
			vr_co_funcionari06 := 'null';
		END IF;
		IF pn_co_funcionari06 IS NULL
		AND pa_co_funcionari06 IS NOT NULL THEN
			vr_co_funcionari06 := 'null';
		END IF;
		IF pn_co_funcionari06 IS NOT NULL
		AND pa_co_funcionari06 IS NULL THEN
			vr_co_funcionari06 := '"' || RTRIM(pn_co_funcionari06) || '"';
		END IF;
		IF pn_co_funcionari06 IS NOT NULL
		AND pa_co_funcionari06 IS NOT NULL THEN
			IF pa_co_funcionari06 <> pn_co_funcionari06 THEN
				vr_co_funcionari06 := '"' || RTRIM(pn_co_funcionari06) || '"';
			ELSE
				vr_co_funcionari06 := '"' || RTRIM(pa_co_funcionari06) || '"';
			END IF;
		END IF;
		IF pn_co_turma07 IS NULL
		AND pa_co_turma07 IS NULL THEN
			vr_co_turma07 := 'null';
		END IF;
		IF pn_co_turma07 IS NULL
		AND pa_co_turma07 IS NOT NULL THEN
			vr_co_turma07 := 'null';
		END IF;
		IF pn_co_turma07 IS NOT NULL
		AND pa_co_turma07 IS NULL THEN
			vr_co_turma07 := pn_co_turma07;
		END IF;
		IF pn_co_turma07 IS NOT NULL
		AND pa_co_turma07 IS NOT NULL THEN
			IF pa_co_turma07 <> pn_co_turma07 THEN
				vr_co_turma07 := pn_co_turma07;
			ELSE
				vr_co_turma07 := pa_co_turma07;
			END IF;
		END IF;
		IF pn_co_disciplina08 IS NULL
		AND pa_co_disciplina08 IS NULL THEN
			vr_co_disciplina08 := 'null';
		END IF;
		IF pn_co_disciplina08 IS NULL
		AND pa_co_disciplina08 IS NOT NULL THEN
			vr_co_disciplina08 := 'null';
		END IF;
		IF pn_co_disciplina08 IS NOT NULL
		AND pa_co_disciplina08 IS NULL THEN
			vr_co_disciplina08 := '"' || RTRIM(pn_co_disciplina08) || '"';
		END IF;
		IF pn_co_disciplina08 IS NOT NULL
		AND pa_co_disciplina08 IS NOT NULL THEN
			IF pa_co_disciplina08 <> pn_co_disciplina08 THEN
				vr_co_disciplina08 := '"' || RTRIM(pn_co_disciplina08) || '"';
			ELSE
				vr_co_disciplina08 := '"' || RTRIM(pa_co_disciplina08) || '"';
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
		v_sql1 := 'update s_horario_turma set co_tipo_horario = ' || RTRIM(vr_co_tipo_horar00) || '  , nu_dia_semana = ' || RTRIM(vr_nu_dia_semana01) || '  , co_curso = ' || RTRIM(vr_co_curso02) || '  , nu_tempo = ' || RTRIM(vr_nu_tempo03) || '  , co_unidade = ' || RTRIM(vr_co_unidade04) || '  , ano_sem = ' || RTRIM(vr_ano_sem05) || '  , co_funcionario = ' || RTRIM(vr_co_funcionari06);
		v_sql2 := '  , co_turma = ' || RTRIM(vr_co_turma07) || '  , co_disciplina = ' || RTRIM(vr_co_disciplina08) || '  , co_seq_serie = ' || RTRIM(vr_co_seq_serie09) || ' where co_tipo_horario = ' || RTRIM(vr_co_tipo_horar00) || '  and nu_dia_semana = ' || RTRIM(vr_nu_dia_semana01) || '  and co_curso = ' || RTRIM(vr_co_curso02) || '  and nu_tempo = ' || RTRIM(vr_nu_tempo03);
		v_sql3 := '  and co_unidade = ' || RTRIM(vr_co_unidade04) || '  and ano_sem = ' || RTRIM(vr_ano_sem05) || '  and co_funcionario = ' || RTRIM(vr_co_funcionari06) || '  and co_turma = ' || RTRIM(vr_co_turma07) || '  and co_disciplina = ' || RTRIM(vr_co_disciplina08) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie09) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	END IF;
	IF p_op = 'del' THEN
		v_uni := pa_co_unidade04;
	ELSE
		v_uni := pn_co_unidade04;
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
		       's_horario_turma',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_horario_120;
/

