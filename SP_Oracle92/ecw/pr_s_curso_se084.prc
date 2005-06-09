CREATE OR REPLACE PROCEDURE pr_s_curso_se084(
	P_OP_IN                CHAR,
	PA_co_curs_serie00_IN  s_curso_serie_disc.co_curs_serie_disc%TYPE,
	PA_co_unidade01_IN     s_curso_serie_disc.co_unidade%TYPE,
	PA_co_disciplina02_IN  s_curso_serie_disc.co_disciplina%TYPE,
	PA_ano_sem03_IN        s_curso_serie_disc.ano_sem%TYPE,
	PA_co_curso04_IN       s_curso_serie_disc.co_curso%TYPE,
	PA_nu_aulas_sema05_IN  s_curso_serie_disc.nu_aulas_semanal%TYPE,
	PA_co_seq_serie06_IN   s_curso_serie_disc.co_seq_serie%TYPE,
	PA_nu_minuto_aul07_IN  s_curso_serie_disc.nu_minuto_aula%TYPE,
	PA_nu_carga_hor_08_IN  s_curso_serie_disc.nu_carga_hor_anual%TYPE,
	PA_tp_avaliacao09_IN   s_curso_serie_disc.tp_avaliacao%TYPE,
	PA_tp_digitacao10_IN   s_curso_serie_disc.tp_digitacao%TYPE,
	PA_tp_impressao11_IN   s_curso_serie_disc.tp_impressao%TYPE,
	PA_st_reprova12_IN     s_curso_serie_disc.st_reprova%TYPE,
	PA_tp_disciplina13_IN  s_curso_serie_disc.tp_disciplina%TYPE,
	PN_co_curs_serie00_IN  s_curso_serie_disc.co_curs_serie_disc%TYPE,
	PN_co_unidade01_IN     s_curso_serie_disc.co_unidade%TYPE,
	PN_co_disciplina02_IN  s_curso_serie_disc.co_disciplina%TYPE,
	PN_ano_sem03_IN        s_curso_serie_disc.ano_sem%TYPE,
	PN_co_curso04_IN       s_curso_serie_disc.co_curso%TYPE,
	PN_nu_aulas_sema05_IN  s_curso_serie_disc.nu_aulas_semanal%TYPE,
	PN_co_seq_serie06_IN   s_curso_serie_disc.co_seq_serie%TYPE,
	PN_nu_minuto_aul07_IN  s_curso_serie_disc.nu_minuto_aula%TYPE,
	PN_nu_carga_hor_08_IN  s_curso_serie_disc.nu_carga_hor_anual%TYPE,
	PN_tp_avaliacao09_IN   s_curso_serie_disc.tp_avaliacao%TYPE,
	PN_tp_digitacao10_IN   s_curso_serie_disc.tp_digitacao%TYPE,
	PN_tp_impressao11_IN   s_curso_serie_disc.tp_impressao%TYPE,
	PN_st_reprova12_IN     s_curso_serie_disc.st_reprova%TYPE,
	PN_tp_disciplina13_IN  s_curso_serie_disc.tp_disciplina%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_curs_serie00  s_curso_serie_disc.co_curs_serie_disc%TYPE := PA_co_curs_serie00_IN;
PA_co_unidade01     s_curso_serie_disc.co_unidade%TYPE := PA_co_unidade01_IN;
PA_co_disciplina02  s_curso_serie_disc.co_disciplina%TYPE := PA_co_disciplina02_IN;
PA_ano_sem03        s_curso_serie_disc.ano_sem%TYPE := PA_ano_sem03_IN;
PA_co_curso04       s_curso_serie_disc.co_curso%TYPE := PA_co_curso04_IN;
PA_nu_aulas_sema05  s_curso_serie_disc.nu_aulas_semanal%TYPE := PA_nu_aulas_sema05_IN;
PA_co_seq_serie06   s_curso_serie_disc.co_seq_serie%TYPE := PA_co_seq_serie06_IN;
PA_nu_minuto_aul07  s_curso_serie_disc.nu_minuto_aula%TYPE := PA_nu_minuto_aul07_IN;
PA_nu_carga_hor_08  s_curso_serie_disc.nu_carga_hor_anual%TYPE := PA_nu_carga_hor_08_IN;
PA_tp_avaliacao09   s_curso_serie_disc.tp_avaliacao%TYPE := PA_tp_avaliacao09_IN;
PA_tp_digitacao10   s_curso_serie_disc.tp_digitacao%TYPE := PA_tp_digitacao10_IN;
PA_tp_impressao11   s_curso_serie_disc.tp_impressao%TYPE := PA_tp_impressao11_IN;
PA_st_reprova12     s_curso_serie_disc.st_reprova%TYPE := PA_st_reprova12_IN;
PA_tp_disciplina13  s_curso_serie_disc.tp_disciplina%TYPE := PA_tp_disciplina13_IN;
PN_co_curs_serie00  s_curso_serie_disc.co_curs_serie_disc%TYPE := PN_co_curs_serie00_IN;
PN_co_unidade01     s_curso_serie_disc.co_unidade%TYPE := PN_co_unidade01_IN;
PN_co_disciplina02  s_curso_serie_disc.co_disciplina%TYPE := PN_co_disciplina02_IN;
PN_ano_sem03        s_curso_serie_disc.ano_sem%TYPE := PN_ano_sem03_IN;
PN_co_curso04       s_curso_serie_disc.co_curso%TYPE := PN_co_curso04_IN;
PN_nu_aulas_sema05  s_curso_serie_disc.nu_aulas_semanal%TYPE := PN_nu_aulas_sema05_IN;
PN_co_seq_serie06   s_curso_serie_disc.co_seq_serie%TYPE := PN_co_seq_serie06_IN;
PN_nu_minuto_aul07  s_curso_serie_disc.nu_minuto_aula%TYPE := PN_nu_minuto_aul07_IN;
PN_nu_carga_hor_08  s_curso_serie_disc.nu_carga_hor_anual%TYPE := PN_nu_carga_hor_08_IN;
PN_tp_avaliacao09   s_curso_serie_disc.tp_avaliacao%TYPE := PN_tp_avaliacao09_IN;
PN_tp_digitacao10   s_curso_serie_disc.tp_digitacao%TYPE := PN_tp_digitacao10_IN;
PN_tp_impressao11   s_curso_serie_disc.tp_impressao%TYPE := PN_tp_impressao11_IN;
PN_st_reprova12     s_curso_serie_disc.st_reprova%TYPE := PN_st_reprova12_IN;
PN_tp_disciplina13  s_curso_serie_disc.tp_disciplina%TYPE := PN_tp_disciplina13_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(600);
v_sql2              CHAR(600);
v_sql3              CHAR(600);
v_sql4              CHAR(600);
v_sql5              CHAR(600);
v_uni               CHAR(10);
vr_co_curs_serie00  CHAR(10);
vr_co_unidade01     CHAR(10);
vr_co_disciplina02  CHAR(10);
vr_ano_sem03        CHAR(10);
vr_co_curso04       CHAR(10);
vr_nu_aulas_sema05  CHAR(10);
vr_co_seq_serie06   CHAR(10);
vr_nu_minuto_aul07  CHAR(10);
vr_nu_carga_hor_08  CHAR(10);
vr_tp_avaliacao09   CHAR(30);
vr_tp_digitacao10   CHAR(20);
vr_tp_impressao11   CHAR(20);
vr_st_reprova12     CHAR(10);
vr_tp_disciplina13  CHAR(40);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_curs_serie00 IS NULL THEN
			vr_co_curs_serie00 := 'null';
		ELSE
			vr_co_curs_serie00 := pn_co_curs_serie00;
		END IF;
		IF pn_co_unidade01 IS NULL THEN
			vr_co_unidade01 := 'null';
		ELSE
			vr_co_unidade01 := pn_co_unidade01;
		END IF;
		IF pn_co_disciplina02 IS NULL THEN
			vr_co_disciplina02 := 'null';
		ELSE
			vr_co_disciplina02 := pn_co_disciplina02;
		END IF;
		IF pn_ano_sem03 IS NULL THEN
			vr_ano_sem03 := 'null';
		ELSE
			vr_ano_sem03 := pn_ano_sem03;
		END IF;
		IF pn_co_curso04 IS NULL THEN
			vr_co_curso04 := 'null';
		ELSE
			vr_co_curso04 := pn_co_curso04;
		END IF;
		IF pn_nu_aulas_sema05 IS NULL THEN
			vr_nu_aulas_sema05 := 'null';
		ELSE
			vr_nu_aulas_sema05 := pn_nu_aulas_sema05;
		END IF;
		IF pn_co_seq_serie06 IS NULL THEN
			vr_co_seq_serie06 := 'null';
		ELSE
			vr_co_seq_serie06 := pn_co_seq_serie06;
		END IF;
		IF pn_nu_minuto_aul07 IS NULL THEN
			vr_nu_minuto_aul07 := 'null';
		ELSE
			vr_nu_minuto_aul07 := pn_nu_minuto_aul07;
		END IF;
		IF pn_nu_carga_hor_08 IS NULL THEN
			vr_nu_carga_hor_08 := 'null';
		ELSE
			vr_nu_carga_hor_08 := pn_nu_carga_hor_08;
		END IF;
		IF pn_tp_avaliacao09 IS NULL THEN
			vr_tp_avaliacao09 := 'null';
		ELSE
			vr_tp_avaliacao09 := pn_tp_avaliacao09;
		END IF;
		IF pn_tp_digitacao10 IS NULL THEN
			vr_tp_digitacao10 := 'null';
		ELSE
			vr_tp_digitacao10 := pn_tp_digitacao10;
		END IF;
		IF pn_tp_impressao11 IS NULL THEN
			vr_tp_impressao11 := 'null';
		ELSE
			vr_tp_impressao11 := pn_tp_impressao11;
		END IF;
		IF pn_st_reprova12 IS NULL THEN
			vr_st_reprova12 := 'null';
		ELSE
			vr_st_reprova12 := pn_st_reprova12;
		END IF;
		IF pn_tp_disciplina13 IS NULL THEN
			vr_tp_disciplina13 := 'null';
		ELSE
			vr_tp_disciplina13 := pn_tp_disciplina13;
		END IF;
		v_sql1 := 'insert into S_CURSO_SERIE_DISCIPLINA (CO_CURSO_SERIE_DISCIPLINA, co_unidade, co_disciplina, ano_sem, co_curso, nu_aulas_semanal, co_seq_serie, nu_minuto_aula, NU_CARGA_HORARIA_ANUAL, tp_avaliacao, ' || 'tp_digitacao, tp_impressao, st_reprova, tp_disciplina) values (';
		v_sql2 := RTRIM(vr_co_curs_serie00) || ',' || '"' || RTRIM(vr_co_unidade01) || '"' || ',' || '"' || RTRIM(vr_co_disciplina02) || '"' || ',' || '"' || RTRIM(vr_ano_sem03) || '"' || ',' || RTRIM(vr_co_curso04) || ',';
		v_sql3 := RTRIM(vr_nu_aulas_sema05) || ',' || RTRIM(vr_co_seq_serie06) || ',' || RTRIM(vr_nu_minuto_aul07) || ',' || RTRIM(vr_nu_carga_hor_08) || ',' || '"' || RTRIM(vr_tp_avaliacao09) || '"' || ',';
		v_sql4 := '"' || RTRIM(vr_tp_digitacao10) || '"' || ',' || '"' || RTRIM(vr_tp_impressao11) || '"' || ',' || '"' || RTRIM(vr_st_reprova12) || '"' || ',' || '"' || RTRIM(vr_tp_disciplina13) || '"' || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4;
	ELSIF p_op = 'del' THEN
		IF pa_co_curs_serie00 IS NULL THEN
			vr_co_curs_serie00 := 'null';
		ELSE
			vr_co_curs_serie00 := pa_co_curs_serie00;
		END IF;
		IF pa_co_unidade01 IS NULL THEN
			vr_co_unidade01 := 'null';
		ELSE
			vr_co_unidade01 := '"' || RTRIM(pa_co_unidade01) || '"';
		END IF;
		IF pa_co_disciplina02 IS NULL THEN
			vr_co_disciplina02 := 'null';
		ELSE
			vr_co_disciplina02 := '"' || RTRIM(pa_co_disciplina02) || '"';
		END IF;
		IF pa_ano_sem03 IS NULL THEN
			vr_ano_sem03 := 'null';
		ELSE
			vr_ano_sem03 := '"' || RTRIM(pa_ano_sem03) || '"';
		END IF;
		v_sql1 := '  delete from S_CURSO_SERIE_DISCIPLINA where CO_CURSO_SERIE_DISCIPLINA = ' || RTRIM(vr_co_curs_serie00) || '  and co_unidade = ' || RTRIM(vr_co_unidade01);
		v_sql2 := '  and co_disciplina = ' || RTRIM(vr_co_disciplina02) || '  and ano_sem = ' || RTRIM(vr_ano_sem03) || ';';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_curs_serie00 IS NULL
		AND pa_co_curs_serie00 IS NULL THEN
			vr_co_curs_serie00 := 'null';
		END IF;
		IF pn_co_curs_serie00 IS NULL
		AND pa_co_curs_serie00 IS NOT NULL THEN
			vr_co_curs_serie00 := 'null';
		END IF;
		IF pn_co_curs_serie00 IS NOT NULL
		AND pa_co_curs_serie00 IS NULL THEN
			vr_co_curs_serie00 := pn_co_curs_serie00;
		END IF;
		IF pn_co_curs_serie00 IS NOT NULL
		AND pa_co_curs_serie00 IS NOT NULL THEN
			IF pa_co_curs_serie00 <> pn_co_curs_serie00 THEN
				vr_co_curs_serie00 := pn_co_curs_serie00;
			ELSE
				vr_co_curs_serie00 := pa_co_curs_serie00;
			END IF;
		END IF;
		IF pn_co_unidade01 IS NULL
		AND pa_co_unidade01 IS NULL THEN
			vr_co_unidade01 := 'null';
		END IF;
		IF pn_co_unidade01 IS NULL
		AND pa_co_unidade01 IS NOT NULL THEN
			vr_co_unidade01 := 'null';
		END IF;
		IF pn_co_unidade01 IS NOT NULL
		AND pa_co_unidade01 IS NULL THEN
			vr_co_unidade01 := '"' || RTRIM(pn_co_unidade01) || '"';
		END IF;
		IF pn_co_unidade01 IS NOT NULL
		AND pa_co_unidade01 IS NOT NULL THEN
			IF pa_co_unidade01 <> pn_co_unidade01 THEN
				vr_co_unidade01 := '"' || RTRIM(pn_co_unidade01) || '"';
			ELSE
				vr_co_unidade01 := '"' || RTRIM(pa_co_unidade01) || '"';
			END IF;
		END IF;
		IF pn_co_disciplina02 IS NULL
		AND pa_co_disciplina02 IS NULL THEN
			vr_co_disciplina02 := 'null';
		END IF;
		IF pn_co_disciplina02 IS NULL
		AND pa_co_disciplina02 IS NOT NULL THEN
			vr_co_disciplina02 := 'null';
		END IF;
		IF pn_co_disciplina02 IS NOT NULL
		AND pa_co_disciplina02 IS NULL THEN
			vr_co_disciplina02 := '"' || RTRIM(pn_co_disciplina02) || '"';
		END IF;
		IF pn_co_disciplina02 IS NOT NULL
		AND pa_co_disciplina02 IS NOT NULL THEN
			IF pa_co_disciplina02 <> pn_co_disciplina02 THEN
				vr_co_disciplina02 := '"' || RTRIM(pn_co_disciplina02) || '"';
			ELSE
				vr_co_disciplina02 := '"' || RTRIM(pa_co_disciplina02) || '"';
			END IF;
		END IF;
		IF pn_ano_sem03 IS NULL
		AND pa_ano_sem03 IS NULL THEN
			vr_ano_sem03 := 'null';
		END IF;
		IF pn_ano_sem03 IS NULL
		AND pa_ano_sem03 IS NOT NULL THEN
			vr_ano_sem03 := 'null';
		END IF;
		IF pn_ano_sem03 IS NOT NULL
		AND pa_ano_sem03 IS NULL THEN
			vr_ano_sem03 := '"' || RTRIM(pn_ano_sem03) || '"';
		END IF;
		IF pn_ano_sem03 IS NOT NULL
		AND pa_ano_sem03 IS NOT NULL THEN
			IF pa_ano_sem03 <> pn_ano_sem03 THEN
				vr_ano_sem03 := '"' || RTRIM(pn_ano_sem03) || '"';
			ELSE
				vr_ano_sem03 := '"' || RTRIM(pa_ano_sem03) || '"';
			END IF;
		END IF;
		IF pn_co_curso04 IS NULL
		AND pa_co_curso04 IS NULL THEN
			vr_co_curso04 := 'null';
		END IF;
		IF pn_co_curso04 IS NULL
		AND pa_co_curso04 IS NOT NULL THEN
			vr_co_curso04 := 'null';
		END IF;
		IF pn_co_curso04 IS NOT NULL
		AND pa_co_curso04 IS NULL THEN
			vr_co_curso04 := pn_co_curso04;
		END IF;
		IF pn_co_curso04 IS NOT NULL
		AND pa_co_curso04 IS NOT NULL THEN
			IF pa_co_curso04 <> pn_co_curso04 THEN
				vr_co_curso04 := pn_co_curso04;
			ELSE
				vr_co_curso04 := pa_co_curso04;
			END IF;
		END IF;
		IF pn_nu_aulas_sema05 IS NULL
		AND pa_nu_aulas_sema05 IS NULL THEN
			vr_nu_aulas_sema05 := 'null';
		END IF;
		IF pn_nu_aulas_sema05 IS NULL
		AND pa_nu_aulas_sema05 IS NOT NULL THEN
			vr_nu_aulas_sema05 := 'null';
		END IF;
		IF pn_nu_aulas_sema05 IS NOT NULL
		AND pa_nu_aulas_sema05 IS NULL THEN
			vr_nu_aulas_sema05 := pn_nu_aulas_sema05;
		END IF;
		IF pn_nu_aulas_sema05 IS NOT NULL
		AND pa_nu_aulas_sema05 IS NOT NULL THEN
			IF pa_nu_aulas_sema05 <> pn_nu_aulas_sema05 THEN
				vr_nu_aulas_sema05 := pn_nu_aulas_sema05;
			ELSE
				vr_nu_aulas_sema05 := pa_nu_aulas_sema05;
			END IF;
		END IF;
		IF pn_co_seq_serie06 IS NULL
		AND pa_co_seq_serie06 IS NULL THEN
			vr_co_seq_serie06 := 'null';
		END IF;
		IF pn_co_seq_serie06 IS NULL
		AND pa_co_seq_serie06 IS NOT NULL THEN
			vr_co_seq_serie06 := 'null';
		END IF;
		IF pn_co_seq_serie06 IS NOT NULL
		AND pa_co_seq_serie06 IS NULL THEN
			vr_co_seq_serie06 := pn_co_seq_serie06;
		END IF;
		IF pn_co_seq_serie06 IS NOT NULL
		AND pa_co_seq_serie06 IS NOT NULL THEN
			IF pa_co_seq_serie06 <> pn_co_seq_serie06 THEN
				vr_co_seq_serie06 := pn_co_seq_serie06;
			ELSE
				vr_co_seq_serie06 := pa_co_seq_serie06;
			END IF;
		END IF;
		IF pn_nu_minuto_aul07 IS NULL
		AND pa_nu_minuto_aul07 IS NULL THEN
			vr_nu_minuto_aul07 := 'null';
		END IF;
		IF pn_nu_minuto_aul07 IS NULL
		AND pa_nu_minuto_aul07 IS NOT NULL THEN
			vr_nu_minuto_aul07 := 'null';
		END IF;
		IF pn_nu_minuto_aul07 IS NOT NULL
		AND pa_nu_minuto_aul07 IS NULL THEN
			vr_nu_minuto_aul07 := pn_nu_minuto_aul07;
		END IF;
		IF pn_nu_minuto_aul07 IS NOT NULL
		AND pa_nu_minuto_aul07 IS NOT NULL THEN
			IF pa_nu_minuto_aul07 <> pn_nu_minuto_aul07 THEN
				vr_nu_minuto_aul07 := pn_nu_minuto_aul07;
			ELSE
				vr_nu_minuto_aul07 := pa_nu_minuto_aul07;
			END IF;
		END IF;
		IF pn_nu_carga_hor_08 IS NULL
		AND pa_nu_carga_hor_08 IS NULL THEN
			vr_nu_carga_hor_08 := 'null';
		END IF;
		IF pn_nu_carga_hor_08 IS NULL
		AND pa_nu_carga_hor_08 IS NOT NULL THEN
			vr_nu_carga_hor_08 := 'null';
		END IF;
		IF pn_nu_carga_hor_08 IS NOT NULL
		AND pa_nu_carga_hor_08 IS NULL THEN
			vr_nu_carga_hor_08 := pn_nu_carga_hor_08;
		END IF;
		IF pn_nu_carga_hor_08 IS NOT NULL
		AND pa_nu_carga_hor_08 IS NOT NULL THEN
			IF pa_nu_carga_hor_08 <> pn_nu_carga_hor_08 THEN
				vr_nu_carga_hor_08 := pn_nu_carga_hor_08;
			ELSE
				vr_nu_carga_hor_08 := pa_nu_carga_hor_08;
			END IF;
		END IF;
		IF pn_tp_avaliacao09 IS NULL
		AND pa_tp_avaliacao09 IS NULL THEN
			vr_tp_avaliacao09 := 'null';
		END IF;
		IF pn_tp_avaliacao09 IS NULL
		AND pa_tp_avaliacao09 IS NOT NULL THEN
			vr_tp_avaliacao09 := 'null';
		END IF;
		IF pn_tp_avaliacao09 IS NOT NULL
		AND pa_tp_avaliacao09 IS NULL THEN
			vr_tp_avaliacao09 := '"' || RTRIM(pn_tp_avaliacao09) || '"';
		END IF;
		IF pn_tp_avaliacao09 IS NOT NULL
		AND pa_tp_avaliacao09 IS NOT NULL THEN
			IF pa_tp_avaliacao09 <> pn_tp_avaliacao09 THEN
				vr_tp_avaliacao09 := '"' || RTRIM(pn_tp_avaliacao09) || '"';
			ELSE
				vr_tp_avaliacao09 := '"' || RTRIM(pa_tp_avaliacao09) || '"';
			END IF;
		END IF;
		IF pn_tp_digitacao10 IS NULL
		AND pa_tp_digitacao10 IS NULL THEN
			vr_tp_digitacao10 := 'null';
		END IF;
		IF pn_tp_digitacao10 IS NULL
		AND pa_tp_digitacao10 IS NOT NULL THEN
			vr_tp_digitacao10 := 'null';
		END IF;
		IF pn_tp_digitacao10 IS NOT NULL
		AND pa_tp_digitacao10 IS NULL THEN
			vr_tp_digitacao10 := '"' || RTRIM(pn_tp_digitacao10) || '"';
		END IF;
		IF pn_tp_digitacao10 IS NOT NULL
		AND pa_tp_digitacao10 IS NOT NULL THEN
			IF pa_tp_digitacao10 <> pn_tp_digitacao10 THEN
				vr_tp_digitacao10 := '"' || RTRIM(pn_tp_digitacao10) || '"';
			ELSE
				vr_tp_digitacao10 := '"' || RTRIM(pa_tp_digitacao10) || '"';
			END IF;
		END IF;
		IF pn_tp_impressao11 IS NULL
		AND pa_tp_impressao11 IS NULL THEN
			vr_tp_impressao11 := 'null';
		END IF;
		IF pn_tp_impressao11 IS NULL
		AND pa_tp_impressao11 IS NOT NULL THEN
			vr_tp_impressao11 := 'null';
		END IF;
		IF pn_tp_impressao11 IS NOT NULL
		AND pa_tp_impressao11 IS NULL THEN
			vr_tp_impressao11 := '"' || RTRIM(pn_tp_impressao11) || '"';
		END IF;
		IF pn_tp_impressao11 IS NOT NULL
		AND pa_tp_impressao11 IS NOT NULL THEN
			IF pa_tp_impressao11 <> pn_tp_impressao11 THEN
				vr_tp_impressao11 := '"' || RTRIM(pn_tp_impressao11) || '"';
			ELSE
				vr_tp_impressao11 := '"' || RTRIM(pa_tp_impressao11) || '"';
			END IF;
		END IF;
		IF pn_st_reprova12 IS NULL
		AND pa_st_reprova12 IS NULL THEN
			vr_st_reprova12 := 'null';
		END IF;
		IF pn_st_reprova12 IS NULL
		AND pa_st_reprova12 IS NOT NULL THEN
			vr_st_reprova12 := 'null';
		END IF;
		IF pn_st_reprova12 IS NOT NULL
		AND pa_st_reprova12 IS NULL THEN
			vr_st_reprova12 := '"' || RTRIM(pn_st_reprova12) || '"';
		END IF;
		IF pn_st_reprova12 IS NOT NULL
		AND pa_st_reprova12 IS NOT NULL THEN
			IF pa_st_reprova12 <> pn_st_reprova12 THEN
				vr_st_reprova12 := '"' || RTRIM(pn_st_reprova12) || '"';
			ELSE
				vr_st_reprova12 := '"' || RTRIM(pa_st_reprova12) || '"';
			END IF;
		END IF;
		IF pn_tp_disciplina13 IS NULL
		AND pa_tp_disciplina13 IS NULL THEN
			vr_tp_disciplina13 := 'null';
		END IF;
		IF pn_tp_disciplina13 IS NULL
		AND pa_tp_disciplina13 IS NOT NULL THEN
			vr_tp_disciplina13 := 'null';
		END IF;
		IF pn_tp_disciplina13 IS NOT NULL
		AND pa_tp_disciplina13 IS NULL THEN
			vr_tp_disciplina13 := '"' || RTRIM(pn_tp_disciplina13) || '"';
		END IF;
		IF pn_tp_disciplina13 IS NOT NULL
		AND pa_tp_disciplina13 IS NOT NULL THEN
			IF pa_tp_disciplina13 <> pn_tp_disciplina13 THEN
				vr_tp_disciplina13 := '"' || RTRIM(pn_tp_disciplina13) || '"';
			ELSE
				vr_tp_disciplina13 := '"' || RTRIM(pa_tp_disciplina13) || '"';
			END IF;
		END IF;
		v_sql1 := 'update S_CURSO_SERIE_DISCIPLINA set CO_CURSO_SERIE_DISCIPLINA = ' || RTRIM(vr_co_curs_serie00) || '  , co_unidade = ' || RTRIM(vr_co_unidade01) || '  , co_disciplina = ' || RTRIM(vr_co_disciplina02);
		v_sql2 := '  , ano_sem = ' || RTRIM(vr_ano_sem03) || '  , co_curso = ' || RTRIM(vr_co_curso04) || '  , nu_aulas_semanal = ' || RTRIM(vr_nu_aulas_sema05) || '  , co_seq_serie = ' || RTRIM(vr_co_seq_serie06);
		v_sql3 := '  , nu_minuto_aula = ' || RTRIM(vr_nu_minuto_aul07) || '  , NU_CARGA_HORARIA_ANUAL = ' || RTRIM(vr_nu_carga_hor_08) || '  , tp_avaliacao = ' || RTRIM(vr_tp_avaliacao09) || '  , tp_digitacao = ' || RTRIM(vr_tp_digitacao10);
		v_sql4 := '  , tp_impressao = ' || RTRIM(vr_tp_impressao11) || '  , st_reprova = ' || RTRIM(vr_st_reprova12) || '  , tp_disciplina = ' || RTRIM(vr_tp_disciplina13);
		v_sql5 := ' where co_curs_serie_disc = ' || RTRIM(vr_co_curs_serie00) || '  and co_unidade = ' || RTRIM(vr_co_unidade01) || '  and co_disciplina = ' || RTRIM(vr_co_disciplina02) || '  and ano_sem = ' || RTRIM(vr_ano_sem03) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5;
	END IF;
	IF p_op = 'del' THEN
		v_uni := pa_co_unidade01;
	ELSE
		v_uni := pn_co_unidade01;
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
		       's_curso_serie_disc',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_curso_se084;
/

