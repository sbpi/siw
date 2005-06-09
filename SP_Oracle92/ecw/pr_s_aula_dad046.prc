CREATE OR REPLACE PROCEDURE pr_s_aula_dad046(
	P_OP_IN                CHAR,
	PA_co_turma00_IN       s_aula_dada.co_turma%TYPE,
	PA_co_curs_serie01_IN  s_aula_dada.co_curs_serie_disc%TYPE,
	PA_nu_aulas_dada02_IN  s_aula_dada.nu_aulas_dadas_b1%TYPE,
	PA_co_unidade03_IN     s_aula_dada.co_unidade%TYPE,
	PA_nu_aulas_prev04_IN  s_aula_dada.nu_aulas_prev_b1%TYPE,
	PA_ano_sem05_IN        s_aula_dada.ano_sem%TYPE,
	PA_co_curso06_IN       s_aula_dada.co_curso%TYPE,
	PA_co_seq_serie07_IN   s_aula_dada.co_seq_serie%TYPE,
	PA_nu_aulas_dada08_IN  s_aula_dada.nu_aulas_dadas_b2%TYPE,
	PA_nu_aulas_dada09_IN  s_aula_dada.nu_aulas_dadas_b3%TYPE,
	PA_nu_aulas_prev10_IN  s_aula_dada.nu_aulas_prev_b3%TYPE,
	PA_nu_aulas_dada11_IN  s_aula_dada.nu_aulas_dadas_b4%TYPE,
	PA_nu_aulas_prev12_IN  s_aula_dada.nu_aulas_prev_b4%TYPE,
	PA_co_disciplina13_IN  s_aula_dada.co_disciplina%TYPE,
	PA_nu_aulas_prev14_IN  s_aula_dada.nu_aulas_prev_b2%TYPE,
	PN_co_turma00_IN       s_aula_dada.co_turma%TYPE,
	PN_co_curs_serie01_IN  s_aula_dada.co_curs_serie_disc%TYPE,
	PN_nu_aulas_dada02_IN  s_aula_dada.nu_aulas_dadas_b1%TYPE,
	PN_co_unidade03_IN     s_aula_dada.co_unidade%TYPE,
	PN_nu_aulas_prev04_IN  s_aula_dada.nu_aulas_prev_b1%TYPE,
	PN_ano_sem05_IN        s_aula_dada.ano_sem%TYPE,
	PN_co_curso06_IN       s_aula_dada.co_curso%TYPE,
	PN_co_seq_serie07_IN   s_aula_dada.co_seq_serie%TYPE,
	PN_nu_aulas_dada08_IN  s_aula_dada.nu_aulas_dadas_b2%TYPE,
	PN_nu_aulas_dada09_IN  s_aula_dada.nu_aulas_dadas_b3%TYPE,
	PN_nu_aulas_prev10_IN  s_aula_dada.nu_aulas_prev_b3%TYPE,
	PN_nu_aulas_dada11_IN  s_aula_dada.nu_aulas_dadas_b4%TYPE,
	PN_nu_aulas_prev12_IN  s_aula_dada.nu_aulas_prev_b4%TYPE,
	PN_co_disciplina13_IN  s_aula_dada.co_disciplina%TYPE,
	PN_nu_aulas_prev14_IN  s_aula_dada.nu_aulas_prev_b2%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_turma00       s_aula_dada.co_turma%TYPE := PA_co_turma00_IN;
PA_co_curs_serie01  s_aula_dada.co_curs_serie_disc%TYPE := PA_co_curs_serie01_IN;
PA_nu_aulas_dada02  s_aula_dada.nu_aulas_dadas_b1%TYPE := PA_nu_aulas_dada02_IN;
PA_co_unidade03     s_aula_dada.co_unidade%TYPE := PA_co_unidade03_IN;
PA_nu_aulas_prev04  s_aula_dada.nu_aulas_prev_b1%TYPE := PA_nu_aulas_prev04_IN;
PA_ano_sem05        s_aula_dada.ano_sem%TYPE := PA_ano_sem05_IN;
PA_co_curso06       s_aula_dada.co_curso%TYPE := PA_co_curso06_IN;
PA_co_seq_serie07   s_aula_dada.co_seq_serie%TYPE := PA_co_seq_serie07_IN;
PA_nu_aulas_dada08  s_aula_dada.nu_aulas_dadas_b2%TYPE := PA_nu_aulas_dada08_IN;
PA_nu_aulas_dada09  s_aula_dada.nu_aulas_dadas_b3%TYPE := PA_nu_aulas_dada09_IN;
PA_nu_aulas_prev10  s_aula_dada.nu_aulas_prev_b3%TYPE := PA_nu_aulas_prev10_IN;
PA_nu_aulas_dada11  s_aula_dada.nu_aulas_dadas_b4%TYPE := PA_nu_aulas_dada11_IN;
PA_nu_aulas_prev12  s_aula_dada.nu_aulas_prev_b4%TYPE := PA_nu_aulas_prev12_IN;
PA_co_disciplina13  s_aula_dada.co_disciplina%TYPE := PA_co_disciplina13_IN;
PA_nu_aulas_prev14  s_aula_dada.nu_aulas_prev_b2%TYPE := PA_nu_aulas_prev14_IN;
PN_co_turma00       s_aula_dada.co_turma%TYPE := PN_co_turma00_IN;
PN_co_curs_serie01  s_aula_dada.co_curs_serie_disc%TYPE := PN_co_curs_serie01_IN;
PN_nu_aulas_dada02  s_aula_dada.nu_aulas_dadas_b1%TYPE := PN_nu_aulas_dada02_IN;
PN_co_unidade03     s_aula_dada.co_unidade%TYPE := PN_co_unidade03_IN;
PN_nu_aulas_prev04  s_aula_dada.nu_aulas_prev_b1%TYPE := PN_nu_aulas_prev04_IN;
PN_ano_sem05        s_aula_dada.ano_sem%TYPE := PN_ano_sem05_IN;
PN_co_curso06       s_aula_dada.co_curso%TYPE := PN_co_curso06_IN;
PN_co_seq_serie07   s_aula_dada.co_seq_serie%TYPE := PN_co_seq_serie07_IN;
PN_nu_aulas_dada08  s_aula_dada.nu_aulas_dadas_b2%TYPE := PN_nu_aulas_dada08_IN;
PN_nu_aulas_dada09  s_aula_dada.nu_aulas_dadas_b3%TYPE := PN_nu_aulas_dada09_IN;
PN_nu_aulas_prev10  s_aula_dada.nu_aulas_prev_b3%TYPE := PN_nu_aulas_prev10_IN;
PN_nu_aulas_dada11  s_aula_dada.nu_aulas_dadas_b4%TYPE := PN_nu_aulas_dada11_IN;
PN_nu_aulas_prev12  s_aula_dada.nu_aulas_prev_b4%TYPE := PN_nu_aulas_prev12_IN;
PN_co_disciplina13  s_aula_dada.co_disciplina%TYPE := PN_co_disciplina13_IN;
PN_nu_aulas_prev14  s_aula_dada.nu_aulas_prev_b2%TYPE := PN_nu_aulas_prev14_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(500);
v_sql2              CHAR(500);
v_sql3              CHAR(500);
v_sql4              CHAR(500);
v_sql5              CHAR(500);
v_sql6              CHAR(500);
v_uni               CHAR(10);
vr_co_turma00       CHAR(10);
vr_co_curs_serie01  CHAR(10);
vr_nu_aulas_dada02  CHAR(10);
vr_co_unidade03     CHAR(10);
vr_nu_aulas_prev04  CHAR(10);
vr_ano_sem05        CHAR(10);
vr_co_curso06       CHAR(10);
vr_co_seq_serie07   CHAR(10);
vr_nu_aulas_dada08  CHAR(10);
vr_nu_aulas_dada09  CHAR(10);
vr_nu_aulas_prev10  CHAR(10);
vr_nu_aulas_dada11  CHAR(10);
vr_nu_aulas_prev12  CHAR(10);
vr_co_disciplina13  CHAR(10);
vr_nu_aulas_prev14  CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_turma00 IS NULL THEN
			vr_co_turma00 := 'null';
		ELSE
			vr_co_turma00 := pn_co_turma00;
		END IF;
		IF pn_co_curs_serie01 IS NULL THEN
			vr_co_curs_serie01 := 'null';
		ELSE
			vr_co_curs_serie01 := pn_co_curs_serie01;
		END IF;
		IF pn_nu_aulas_dada02 IS NULL THEN
			vr_nu_aulas_dada02 := 'null';
		ELSE
			vr_nu_aulas_dada02 := pn_nu_aulas_dada02;
		END IF;
		IF pn_co_unidade03 IS NULL THEN
			vr_co_unidade03 := 'null';
		ELSE
			vr_co_unidade03 := pn_co_unidade03;
		END IF;
		IF pn_nu_aulas_prev04 IS NULL THEN
			vr_nu_aulas_prev04 := 'null';
		ELSE
			vr_nu_aulas_prev04 := pn_nu_aulas_prev04;
		END IF;
		IF pn_ano_sem05 IS NULL THEN
			vr_ano_sem05 := 'null';
		ELSE
			vr_ano_sem05 := pn_ano_sem05;
		END IF;
		IF pn_co_curso06 IS NULL THEN
			vr_co_curso06 := 'null';
		ELSE
			vr_co_curso06 := pn_co_curso06;
		END IF;
		IF pn_co_seq_serie07 IS NULL THEN
			vr_co_seq_serie07 := 'null';
		ELSE
			vr_co_seq_serie07 := pn_co_seq_serie07;
		END IF;
		IF pn_nu_aulas_dada08 IS NULL THEN
			vr_nu_aulas_dada08 := 'null';
		ELSE
			vr_nu_aulas_dada08 := pn_nu_aulas_dada08;
		END IF;
		IF pn_nu_aulas_dada09 IS NULL THEN
			vr_nu_aulas_dada09 := 'null';
		ELSE
			vr_nu_aulas_dada09 := pn_nu_aulas_dada09;
		END IF;
		IF pn_nu_aulas_prev10 IS NULL THEN
			vr_nu_aulas_prev10 := 'null';
		ELSE
			vr_nu_aulas_prev10 := pn_nu_aulas_prev10;
		END IF;
		IF pn_nu_aulas_dada11 IS NULL THEN
			vr_nu_aulas_dada11 := 'null';
		ELSE
			vr_nu_aulas_dada11 := pn_nu_aulas_dada11;
		END IF;
		IF pn_nu_aulas_prev12 IS NULL THEN
			vr_nu_aulas_prev12 := 'null';
		ELSE
			vr_nu_aulas_prev12 := pn_nu_aulas_prev12;
		END IF;
		IF pn_co_disciplina13 IS NULL THEN
			vr_co_disciplina13 := 'null';
		ELSE
			vr_co_disciplina13 := pn_co_disciplina13;
		END IF;
		IF pn_nu_aulas_prev14 IS NULL THEN
			vr_nu_aulas_prev14 := 'null';
		ELSE
			vr_nu_aulas_prev14 := pn_nu_aulas_prev14;
		END IF;
		v_sql1 := 'insert into s_aula_dada(co_turma, CO_CURSO_SERIE_DISCIPLINA, nu_aulas_dadas_b1, co_unidade, NU_AULAS_PREVISTAS_B1, ano_sem, co_curso, co_seq_serie, nu_aulas_dadas_b2, ' || 'nu_aulas_dadas_b3, NU_AULAS_PREVISTAS_B3, nu_aulas_dadas_b4, NU_AULAS_PREVISTAS_B4, co_disciplina, NU_AULAS_PREVISTAS_B2) values (';
		v_sql2 := RTRIM(vr_co_turma00) || ',' || RTRIM(vr_co_curs_serie01) || ',' || '"' || RTRIM(vr_nu_aulas_dada02) || '"' || ',' || '"' || RTRIM(vr_co_unidade03) || '"' || ',' || '"' || RTRIM(vr_nu_aulas_prev04) || '"' || ',';
		v_sql3 := '"' || RTRIM(vr_ano_sem05) || '"' || ',' || RTRIM(vr_co_curso06) || ',' || RTRIM(vr_co_seq_serie07) || ',' || '"' || RTRIM(vr_nu_aulas_dada08) || '"' || ',' || '"' || RTRIM(vr_nu_aulas_dada09) || '"' || ',';
		v_sql4 := '"' || RTRIM(vr_nu_aulas_prev10) || '"' || ',' || '"' || RTRIM(vr_nu_aulas_dada11) || '"' || ',' || '"' || RTRIM(vr_nu_aulas_prev12) || '"' || ',';
		v_sql5 := '"' || RTRIM(vr_co_disciplina13) || '"' || ',' || '"' || RTRIM(vr_nu_aulas_prev14) || '"' || ');';
		v_sql6 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5 || v_sql6;
	ELSIF p_op = 'del' THEN
		IF pa_co_turma00 IS NULL THEN
			vr_co_turma00 := 'null';
		ELSE
			vr_co_turma00 := pa_co_turma00;
		END IF;
		IF pa_co_curs_serie01 IS NULL THEN
			vr_co_curs_serie01 := 'null';
		ELSE
			vr_co_curs_serie01 := pa_co_curs_serie01;
		END IF;
		IF pa_co_unidade03 IS NULL THEN
			vr_co_unidade03 := 'null';
		ELSE
			vr_co_unidade03 := '"' || RTRIM(pa_co_unidade03) || '"';
		END IF;
		IF pa_ano_sem05 IS NULL THEN
			vr_ano_sem05 := 'null';
		ELSE
			vr_ano_sem05 := '"' || RTRIM(pa_ano_sem05) || '"';
		END IF;
		IF pa_co_curso06 IS NULL THEN
			vr_co_curso06 := 'null';
		ELSE
			vr_co_curso06 := pa_co_curso06;
		END IF;
		IF pa_co_seq_serie07 IS NULL THEN
			vr_co_seq_serie07 := 'null';
		ELSE
			vr_co_seq_serie07 := pa_co_seq_serie07;
		END IF;
		IF pa_co_disciplina13 IS NULL THEN
			vr_co_disciplina13 := 'null';
		ELSE
			vr_co_disciplina13 := '"' || RTRIM(pa_co_disciplina13) || '"';
		END IF;
		v_sql1 := '  delete from s_aula_dada where co_turma = ' || RTRIM(vr_co_turma00) || '  and CO_CURSO_SERIE_DISCIPLINA = ' || RTRIM(vr_co_curs_serie01) || '  and co_unidade = ' || RTRIM(vr_co_unidade03);
		v_sql2 := '  and ano_sem = ' || RTRIM(vr_ano_sem05) || '  and co_curso = ' || RTRIM(vr_co_curso06) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie07) || '  and co_disciplina = ' || RTRIM(vr_co_disciplina13) || ';';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_turma00 IS NULL
		AND pa_co_turma00 IS NULL THEN
			vr_co_turma00 := 'null';
		END IF;
		IF pn_co_turma00 IS NULL
		AND pa_co_turma00 IS NOT NULL THEN
			vr_co_turma00 := 'null';
		END IF;
		IF pn_co_turma00 IS NOT NULL
		AND pa_co_turma00 IS NULL THEN
			vr_co_turma00 := pn_co_turma00;
		END IF;
		IF pn_co_turma00 IS NOT NULL
		AND pa_co_turma00 IS NOT NULL THEN
			IF pa_co_turma00 <> pn_co_turma00 THEN
				vr_co_turma00 := pn_co_turma00;
			ELSE
				vr_co_turma00 := pa_co_turma00;
			END IF;
		END IF;
		IF pn_co_curs_serie01 IS NULL
		AND pa_co_curs_serie01 IS NULL THEN
			vr_co_curs_serie01 := 'null';
		END IF;
		IF pn_co_curs_serie01 IS NULL
		AND pa_co_curs_serie01 IS NOT NULL THEN
			vr_co_curs_serie01 := 'null';
		END IF;
		IF pn_co_curs_serie01 IS NOT NULL
		AND pa_co_curs_serie01 IS NULL THEN
			vr_co_curs_serie01 := pn_co_curs_serie01;
		END IF;
		IF pn_co_curs_serie01 IS NOT NULL
		AND pa_co_curs_serie01 IS NOT NULL THEN
			IF pa_co_curs_serie01 <> pn_co_curs_serie01 THEN
				vr_co_curs_serie01 := pn_co_curs_serie01;
			ELSE
				vr_co_curs_serie01 := pa_co_curs_serie01;
			END IF;
		END IF;
		IF pn_nu_aulas_dada02 IS NULL
		AND pa_nu_aulas_dada02 IS NULL THEN
			vr_nu_aulas_dada02 := 'null';
		END IF;
		IF pn_nu_aulas_dada02 IS NULL
		AND pa_nu_aulas_dada02 IS NOT NULL THEN
			vr_nu_aulas_dada02 := 'null';
		END IF;
		IF pn_nu_aulas_dada02 IS NOT NULL
		AND pa_nu_aulas_dada02 IS NULL THEN
			vr_nu_aulas_dada02 := '"' || RTRIM(pn_nu_aulas_dada02) || '"';
		END IF;
		IF pn_nu_aulas_dada02 IS NOT NULL
		AND pa_nu_aulas_dada02 IS NOT NULL THEN
			IF pa_nu_aulas_dada02 <> pn_nu_aulas_dada02 THEN
				vr_nu_aulas_dada02 := '"' || RTRIM(pn_nu_aulas_dada02) || '"';
			ELSE
				vr_nu_aulas_dada02 := '"' || RTRIM(pa_nu_aulas_dada02) || '"';
			END IF;
		END IF;
		IF pn_co_unidade03 IS NULL
		AND pa_co_unidade03 IS NULL THEN
			vr_co_unidade03 := 'null';
		END IF;
		IF pn_co_unidade03 IS NULL
		AND pa_co_unidade03 IS NOT NULL THEN
			vr_co_unidade03 := 'null';
		END IF;
		IF pn_co_unidade03 IS NOT NULL
		AND pa_co_unidade03 IS NULL THEN
			vr_co_unidade03 := '"' || RTRIM(pn_co_unidade03) || '"';
		END IF;
		IF pn_co_unidade03 IS NOT NULL
		AND pa_co_unidade03 IS NOT NULL THEN
			IF pa_co_unidade03 <> pn_co_unidade03 THEN
				vr_co_unidade03 := '"' || RTRIM(pn_co_unidade03) || '"';
			ELSE
				vr_co_unidade03 := '"' || RTRIM(pa_co_unidade03) || '"';
			END IF;
		END IF;
		IF pn_nu_aulas_prev04 IS NULL
		AND pa_nu_aulas_prev04 IS NULL THEN
			vr_nu_aulas_prev04 := 'null';
		END IF;
		IF pn_nu_aulas_prev04 IS NULL
		AND pa_nu_aulas_prev04 IS NOT NULL THEN
			vr_nu_aulas_prev04 := 'null';
		END IF;
		IF pn_nu_aulas_prev04 IS NOT NULL
		AND pa_nu_aulas_prev04 IS NULL THEN
			vr_nu_aulas_prev04 := '"' || RTRIM(pn_nu_aulas_prev04) || '"';
		END IF;
		IF pn_nu_aulas_prev04 IS NOT NULL
		AND pa_nu_aulas_prev04 IS NOT NULL THEN
			IF pa_nu_aulas_prev04 <> pn_nu_aulas_prev04 THEN
				vr_nu_aulas_prev04 := '"' || RTRIM(pn_nu_aulas_prev04) || '"';
			ELSE
				vr_nu_aulas_prev04 := '"' || RTRIM(pa_nu_aulas_prev04) || '"';
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
		IF pn_co_curso06 IS NULL
		AND pa_co_curso06 IS NULL THEN
			vr_co_curso06 := 'null';
		END IF;
		IF pn_co_curso06 IS NULL
		AND pa_co_curso06 IS NOT NULL THEN
			vr_co_curso06 := 'null';
		END IF;
		IF pn_co_curso06 IS NOT NULL
		AND pa_co_curso06 IS NULL THEN
			vr_co_curso06 := pn_co_curso06;
		END IF;
		IF pn_co_curso06 IS NOT NULL
		AND pa_co_curso06 IS NOT NULL THEN
			IF pa_co_curso06 <> pn_co_curso06 THEN
				vr_co_curso06 := pn_co_curso06;
			ELSE
				vr_co_curso06 := pa_co_curso06;
			END IF;
		END IF;
		IF pn_co_seq_serie07 IS NULL
		AND pa_co_seq_serie07 IS NULL THEN
			vr_co_seq_serie07 := 'null';
		END IF;
		IF pn_co_seq_serie07 IS NULL
		AND pa_co_seq_serie07 IS NOT NULL THEN
			vr_co_seq_serie07 := 'null';
		END IF;
		IF pn_co_seq_serie07 IS NOT NULL
		AND pa_co_seq_serie07 IS NULL THEN
			vr_co_seq_serie07 := pn_co_seq_serie07;
		END IF;
		IF pn_co_seq_serie07 IS NOT NULL
		AND pa_co_seq_serie07 IS NOT NULL THEN
			IF pa_co_seq_serie07 <> pn_co_seq_serie07 THEN
				vr_co_seq_serie07 := pn_co_seq_serie07;
			ELSE
				vr_co_seq_serie07 := pa_co_seq_serie07;
			END IF;
		END IF;
		IF pn_nu_aulas_dada08 IS NULL
		AND pa_nu_aulas_dada08 IS NULL THEN
			vr_nu_aulas_dada08 := 'null';
		END IF;
		IF pn_nu_aulas_dada08 IS NULL
		AND pa_nu_aulas_dada08 IS NOT NULL THEN
			vr_nu_aulas_dada08 := 'null';
		END IF;
		IF pn_nu_aulas_dada08 IS NOT NULL
		AND pa_nu_aulas_dada08 IS NULL THEN
			vr_nu_aulas_dada08 := '"' || RTRIM(pn_nu_aulas_dada08) || '"';
		END IF;
		IF pn_nu_aulas_dada08 IS NOT NULL
		AND pa_nu_aulas_dada08 IS NOT NULL THEN
			IF pa_nu_aulas_dada08 <> pn_nu_aulas_dada08 THEN
				vr_nu_aulas_dada08 := '"' || RTRIM(pn_nu_aulas_dada08) || '"';
			ELSE
				vr_nu_aulas_dada08 := '"' || RTRIM(pa_nu_aulas_dada08) || '"';
			END IF;
		END IF;
		IF pn_nu_aulas_dada09 IS NULL
		AND pa_nu_aulas_dada09 IS NULL THEN
			vr_nu_aulas_dada09 := 'null';
		END IF;
		IF pn_nu_aulas_dada09 IS NULL
		AND pa_nu_aulas_dada09 IS NOT NULL THEN
			vr_nu_aulas_dada09 := 'null';
		END IF;
		IF pn_nu_aulas_dada09 IS NOT NULL
		AND pa_nu_aulas_dada09 IS NULL THEN
			vr_nu_aulas_dada09 := '"' || RTRIM(pn_nu_aulas_dada09) || '"';
		END IF;
		IF pn_nu_aulas_dada09 IS NOT NULL
		AND pa_nu_aulas_dada09 IS NOT NULL THEN
			IF pa_nu_aulas_dada09 <> pn_nu_aulas_dada09 THEN
				vr_nu_aulas_dada09 := '"' || RTRIM(pn_nu_aulas_dada09) || '"';
			ELSE
				vr_nu_aulas_dada09 := '"' || RTRIM(pa_nu_aulas_dada09) || '"';
			END IF;
		END IF;
		IF pn_nu_aulas_prev10 IS NULL
		AND pa_nu_aulas_prev10 IS NULL THEN
			vr_nu_aulas_prev10 := 'null';
		END IF;
		IF pn_nu_aulas_prev10 IS NULL
		AND pa_nu_aulas_prev10 IS NOT NULL THEN
			vr_nu_aulas_prev10 := 'null';
		END IF;
		IF pn_nu_aulas_prev10 IS NOT NULL
		AND pa_nu_aulas_prev10 IS NULL THEN
			vr_nu_aulas_prev10 := '"' || RTRIM(pn_nu_aulas_prev10) || '"';
		END IF;
		IF pn_nu_aulas_prev10 IS NOT NULL
		AND pa_nu_aulas_prev10 IS NOT NULL THEN
			IF pa_nu_aulas_prev10 <> pn_nu_aulas_prev10 THEN
				vr_nu_aulas_prev10 := '"' || RTRIM(pn_nu_aulas_prev10) || '"';
			ELSE
				vr_nu_aulas_prev10 := '"' || RTRIM(pa_nu_aulas_prev10) || '"';
			END IF;
		END IF;
		IF pn_nu_aulas_dada11 IS NULL
		AND pa_nu_aulas_dada11 IS NULL THEN
			vr_nu_aulas_dada11 := 'null';
		END IF;
		IF pn_nu_aulas_dada11 IS NULL
		AND pa_nu_aulas_dada11 IS NOT NULL THEN
			vr_nu_aulas_dada11 := 'null';
		END IF;
		IF pn_nu_aulas_dada11 IS NOT NULL
		AND pa_nu_aulas_dada11 IS NULL THEN
			vr_nu_aulas_dada11 := '"' || RTRIM(pn_nu_aulas_dada11) || '"';
		END IF;
		IF pn_nu_aulas_dada11 IS NOT NULL
		AND pa_nu_aulas_dada11 IS NOT NULL THEN
			IF pa_nu_aulas_dada11 <> pn_nu_aulas_dada11 THEN
				vr_nu_aulas_dada11 := '"' || RTRIM(pn_nu_aulas_dada11) || '"';
			ELSE
				vr_nu_aulas_dada11 := '"' || RTRIM(pa_nu_aulas_dada11) || '"';
			END IF;
		END IF;
		IF pn_nu_aulas_prev12 IS NULL
		AND pa_nu_aulas_prev12 IS NULL THEN
			vr_nu_aulas_prev12 := 'null';
		END IF;
		IF pn_nu_aulas_prev12 IS NULL
		AND pa_nu_aulas_prev12 IS NOT NULL THEN
			vr_nu_aulas_prev12 := 'null';
		END IF;
		IF pn_nu_aulas_prev12 IS NOT NULL
		AND pa_nu_aulas_prev12 IS NULL THEN
			vr_nu_aulas_prev12 := '"' || RTRIM(pn_nu_aulas_prev12) || '"';
		END IF;
		IF pn_nu_aulas_prev12 IS NOT NULL
		AND pa_nu_aulas_prev12 IS NOT NULL THEN
			IF pa_nu_aulas_prev12 <> pn_nu_aulas_prev12 THEN
				vr_nu_aulas_prev12 := '"' || RTRIM(pn_nu_aulas_prev12) || '"';
			ELSE
				vr_nu_aulas_prev12 := '"' || RTRIM(pa_nu_aulas_prev12) || '"';
			END IF;
		END IF;
		IF pn_co_disciplina13 IS NULL
		AND pa_co_disciplina13 IS NULL THEN
			vr_co_disciplina13 := 'null';
		END IF;
		IF pn_co_disciplina13 IS NULL
		AND pa_co_disciplina13 IS NOT NULL THEN
			vr_co_disciplina13 := 'null';
		END IF;
		IF pn_co_disciplina13 IS NOT NULL
		AND pa_co_disciplina13 IS NULL THEN
			vr_co_disciplina13 := '"' || RTRIM(pn_co_disciplina13) || '"';
		END IF;
		IF pn_co_disciplina13 IS NOT NULL
		AND pa_co_disciplina13 IS NOT NULL THEN
			IF pa_co_disciplina13 <> pn_co_disciplina13 THEN
				vr_co_disciplina13 := '"' || RTRIM(pn_co_disciplina13) || '"';
			ELSE
				vr_co_disciplina13 := '"' || RTRIM(pa_co_disciplina13) || '"';
			END IF;
		END IF;
		IF pn_nu_aulas_prev14 IS NULL
		AND pa_nu_aulas_prev14 IS NULL THEN
			vr_nu_aulas_prev14 := 'null';
		END IF;
		IF pn_nu_aulas_prev14 IS NULL
		AND pa_nu_aulas_prev14 IS NOT NULL THEN
			vr_nu_aulas_prev14 := 'null';
		END IF;
		IF pn_nu_aulas_prev14 IS NOT NULL
		AND pa_nu_aulas_prev14 IS NULL THEN
			vr_nu_aulas_prev14 := '"' || RTRIM(pn_nu_aulas_prev14) || '"';
		END IF;
		IF pn_nu_aulas_prev14 IS NOT NULL
		AND pa_nu_aulas_prev14 IS NOT NULL THEN
			IF pa_nu_aulas_prev14 <> pn_nu_aulas_prev14 THEN
				vr_nu_aulas_prev14 := '"' || RTRIM(pn_nu_aulas_prev14) || '"';
			ELSE
				vr_nu_aulas_prev14 := '"' || RTRIM(pa_nu_aulas_prev14) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_aula_dada set co_turma = ' || RTRIM(vr_co_turma00) || '  , CO_CURSO_SERIE_DISCIPLINA = ' || RTRIM(vr_co_curs_serie01) || '  , nu_aulas_dadas_b1 = ' || RTRIM(vr_nu_aulas_dada02) || '  , co_unidade = ' || RTRIM(vr_co_unidade03);
		v_sql2 := '  , NU_AULAS_PREVISTAS_B1 = ' || RTRIM(vr_nu_aulas_prev04) || '  , ano_sem = ' || RTRIM(vr_ano_sem05) || '  , co_curso = ' || RTRIM(vr_co_curso06) || '  , co_seq_serie = ' || RTRIM(vr_co_seq_serie07) || '  , nu_aulas_dadas_b2 = ' || RTRIM(vr_nu_aulas_dada08);
		v_sql3 := '  , nu_aulas_dadas_b3 = ' || RTRIM(vr_nu_aulas_dada09) || '  , NU_AULAS_PREVISTAS_B3 = ' || RTRIM(vr_nu_aulas_prev10) || '  , nu_aulas_dadas_b4 = ' || RTRIM(vr_nu_aulas_dada11) || '  , NU_AULAS_PREVISTAS_B4 = ' || RTRIM(vr_nu_aulas_prev12);
		v_sql4 := '  , co_disciplina = ' || RTRIM(vr_co_disciplina13) || '  , NU_AULAS_PREVISTAS_B2 = ' || RTRIM(vr_nu_aulas_prev14);
		v_sql5 := ' where co_turma = ' || RTRIM(vr_co_turma00) || '  and co_curs_serie_disc = ' || RTRIM(vr_co_curs_serie01) || '  and co_unidade = ' || RTRIM(vr_co_unidade03);
		v_sql6 := '  and ano_sem = ' || RTRIM(vr_ano_sem05) || '  and co_curso = ' || RTRIM(vr_co_curso06) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie07) || '  and co_disciplina = ' || RTRIM(vr_co_disciplina13) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5 || v_sql6;
	END IF;
	IF p_op = 'del' THEN
		v_uni := pa_co_unidade03;
	ELSE
		v_uni := pn_co_unidade03;
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
		       's_aula_dada',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_aula_dad046;
/

