CREATE OR REPLACE PROCEDURE pr_s_historic116(
	P_OP_IN                CHAR,
	PA_co_serie00_IN       s_historico_serie.co_serie%TYPE,
	PA_co_aluno01_IN       s_historico_serie.co_aluno%TYPE,
	PA_co_ano_sem02_IN     s_historico_serie.co_ano_sem%TYPE,
	PA_co_unidade03_IN     s_historico_serie.co_unidade%TYPE,
	PA_ds_curso04_IN       s_historico_serie.ds_curso%TYPE,
	PA_ds_nome_coleg05_IN  s_historico_serie.ds_nome_colegio%TYPE,
	PA_tp_periodo06_IN     s_historico_serie.tp_periodo%TYPE,
	PA_ds_cidade07_IN      s_historico_serie.ds_cidade%TYPE,
	PA_ds_uf_cidade08_IN   s_historico_serie.ds_uf_cidade%TYPE,
	PA_ds_resultado_09_IN  s_historico_serie.ds_resultado_final%TYPE,
	PA_nu_aulas_dada10_IN  s_historico_serie.nu_aulas_dadas%TYPE,
	PA_nu_dias_letiv11_IN  s_historico_serie.nu_dias_letivos%TYPE,
	PA_nu_faltas12_IN      s_historico_serie.nu_faltas%TYPE,
	PA_ds_serie13_IN       s_historico_serie.ds_serie%TYPE,
	PA_ctr_import14_IN     s_historico_serie.ctr_import%TYPE,
	PN_co_serie00_IN       s_historico_serie.co_serie%TYPE,
	PN_co_aluno01_IN       s_historico_serie.co_aluno%TYPE,
	PN_co_ano_sem02_IN     s_historico_serie.co_ano_sem%TYPE,
	PN_co_unidade03_IN     s_historico_serie.co_unidade%TYPE,
	PN_ds_curso04_IN       s_historico_serie.ds_curso%TYPE,
	PN_ds_nome_coleg05_IN  s_historico_serie.ds_nome_colegio%TYPE,
	PN_tp_periodo06_IN     s_historico_serie.tp_periodo%TYPE,
	PN_ds_cidade07_IN      s_historico_serie.ds_cidade%TYPE,
	PN_ds_uf_cidade08_IN   s_historico_serie.ds_uf_cidade%TYPE,
	PN_ds_resultado_09_IN  s_historico_serie.ds_resultado_final%TYPE,
	PN_nu_aulas_dada10_IN  s_historico_serie.nu_aulas_dadas%TYPE,
	PN_nu_dias_letiv11_IN  s_historico_serie.nu_dias_letivos%TYPE,
	PN_nu_faltas12_IN      s_historico_serie.nu_faltas%TYPE,
	PN_ds_serie13_IN       s_historico_serie.ds_serie%TYPE,
	PN_ctr_import14_IN     s_historico_serie.ctr_import%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_serie00       s_historico_serie.co_serie%TYPE := PA_co_serie00_IN;
PA_co_aluno01       s_historico_serie.co_aluno%TYPE := PA_co_aluno01_IN;
PA_co_ano_sem02     s_historico_serie.co_ano_sem%TYPE := PA_co_ano_sem02_IN;
PA_co_unidade03     s_historico_serie.co_unidade%TYPE := PA_co_unidade03_IN;
PA_ds_curso04       s_historico_serie.ds_curso%TYPE := PA_ds_curso04_IN;
PA_ds_nome_coleg05  s_historico_serie.ds_nome_colegio%TYPE := PA_ds_nome_coleg05_IN;
PA_tp_periodo06     s_historico_serie.tp_periodo%TYPE := PA_tp_periodo06_IN;
PA_ds_cidade07      s_historico_serie.ds_cidade%TYPE := PA_ds_cidade07_IN;
PA_ds_uf_cidade08   s_historico_serie.ds_uf_cidade%TYPE := PA_ds_uf_cidade08_IN;
PA_ds_resultado_09  s_historico_serie.ds_resultado_final%TYPE := PA_ds_resultado_09_IN;
PA_nu_aulas_dada10  s_historico_serie.nu_aulas_dadas%TYPE := PA_nu_aulas_dada10_IN;
PA_nu_dias_letiv11  s_historico_serie.nu_dias_letivos%TYPE := PA_nu_dias_letiv11_IN;
PA_nu_faltas12      s_historico_serie.nu_faltas%TYPE := PA_nu_faltas12_IN;
PA_ds_serie13       s_historico_serie.ds_serie%TYPE := PA_ds_serie13_IN;
PA_ctr_import14     s_historico_serie.ctr_import%TYPE := PA_ctr_import14_IN;
PN_co_serie00       s_historico_serie.co_serie%TYPE := PN_co_serie00_IN;
PN_co_aluno01       s_historico_serie.co_aluno%TYPE := PN_co_aluno01_IN;
PN_co_ano_sem02     s_historico_serie.co_ano_sem%TYPE := PN_co_ano_sem02_IN;
PN_co_unidade03     s_historico_serie.co_unidade%TYPE := PN_co_unidade03_IN;
PN_ds_curso04       s_historico_serie.ds_curso%TYPE := PN_ds_curso04_IN;
PN_ds_nome_coleg05  s_historico_serie.ds_nome_colegio%TYPE := PN_ds_nome_coleg05_IN;
PN_tp_periodo06     s_historico_serie.tp_periodo%TYPE := PN_tp_periodo06_IN;
PN_ds_cidade07      s_historico_serie.ds_cidade%TYPE := PN_ds_cidade07_IN;
PN_ds_uf_cidade08   s_historico_serie.ds_uf_cidade%TYPE := PN_ds_uf_cidade08_IN;
PN_ds_resultado_09  s_historico_serie.ds_resultado_final%TYPE := PN_ds_resultado_09_IN;
PN_nu_aulas_dada10  s_historico_serie.nu_aulas_dadas%TYPE := PN_nu_aulas_dada10_IN;
PN_nu_dias_letiv11  s_historico_serie.nu_dias_letivos%TYPE := PN_nu_dias_letiv11_IN;
PN_nu_faltas12      s_historico_serie.nu_faltas%TYPE := PN_nu_faltas12_IN;
PN_ds_serie13       s_historico_serie.ds_serie%TYPE := PN_ds_serie13_IN;
PN_ctr_import14     s_historico_serie.ctr_import%TYPE := PN_ctr_import14_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_serie00       CHAR(70);
vr_co_aluno01       CHAR(20);
vr_co_ano_sem02     CHAR(10);
vr_co_unidade03     CHAR(10);
vr_ds_curso04       CHAR(60);
vr_ds_nome_coleg05  CHAR(70);
vr_tp_periodo06     CHAR(10);
vr_ds_cidade07      CHAR(40);
vr_ds_uf_cidade08   CHAR(10);
vr_ds_resultado_09  CHAR(25);
vr_nu_aulas_dada10  CHAR(10);
vr_nu_dias_letiv11  CHAR(10);
vr_nu_faltas12      CHAR(10);
vr_ds_serie13       CHAR(40);
vr_ctr_import14     CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_serie00 IS NULL THEN
			vr_co_serie00 := 'null';
		ELSE
			vr_co_serie00 := pn_co_serie00;
		END IF;
		IF pn_co_aluno01 IS NULL THEN
			vr_co_aluno01 := 'null';
		ELSE
			vr_co_aluno01 := pn_co_aluno01;
		END IF;
		IF pn_co_ano_sem02 IS NULL THEN
			vr_co_ano_sem02 := 'null';
		ELSE
			vr_co_ano_sem02 := pn_co_ano_sem02;
		END IF;
		IF pn_co_unidade03 IS NULL THEN
			vr_co_unidade03 := 'null';
		ELSE
			vr_co_unidade03 := pn_co_unidade03;
		END IF;
		IF pn_ds_curso04 IS NULL THEN
			vr_ds_curso04 := 'null';
		ELSE
			vr_ds_curso04 := pn_ds_curso04;
		END IF;
		IF pn_ds_nome_coleg05 IS NULL THEN
			vr_ds_nome_coleg05 := 'null';
		ELSE
			vr_ds_nome_coleg05 := pn_ds_nome_coleg05;
		END IF;
		IF pn_tp_periodo06 IS NULL THEN
			vr_tp_periodo06 := 'null';
		ELSE
			vr_tp_periodo06 := pn_tp_periodo06;
		END IF;
		IF pn_ds_cidade07 IS NULL THEN
			vr_ds_cidade07 := 'null';
		ELSE
			vr_ds_cidade07 := pn_ds_cidade07;
		END IF;
		IF pn_ds_uf_cidade08 IS NULL THEN
			vr_ds_uf_cidade08 := 'null';
		ELSE
			vr_ds_uf_cidade08 := pn_ds_uf_cidade08;
		END IF;
		IF pn_ds_resultado_09 IS NULL THEN
			vr_ds_resultado_09 := 'null';
		ELSE
			vr_ds_resultado_09 := pn_ds_resultado_09;
		END IF;
		IF pn_nu_aulas_dada10 IS NULL THEN
			vr_nu_aulas_dada10 := 'null';
		ELSE
			vr_nu_aulas_dada10 := pn_nu_aulas_dada10;
		END IF;
		IF pn_nu_dias_letiv11 IS NULL THEN
			vr_nu_dias_letiv11 := 'null';
		ELSE
			vr_nu_dias_letiv11 := pn_nu_dias_letiv11;
		END IF;
		IF pn_nu_faltas12 IS NULL THEN
			vr_nu_faltas12 := 'null';
		ELSE
			vr_nu_faltas12 := pn_nu_faltas12;
		END IF;
		IF pn_ds_serie13 IS NULL THEN
			vr_ds_serie13 := 'null';
		ELSE
			vr_ds_serie13 := pn_ds_serie13;
		END IF;
		IF pn_ctr_import14 IS NULL THEN
			vr_ctr_import14 := 'null';
		ELSE
			vr_ctr_import14 := pn_ctr_import14;
		END IF;
		v_sql1 := 'insert into s_historico_serie(co_serie, co_aluno, co_ano_sem, co_unidade, ds_curso, ds_nome_colegio, tp_periodo, ds_cidade, ds_uf_cidade, ds_resultado_final, nu_aulas_dadas, nu_dias_letivos, nu_faltas, ds_serie, ctr_import) values (';
		v_sql2 := '"' || RTRIM(vr_co_serie00) || '"' || ',' || '"' || RTRIM(vr_co_aluno01) || '"' || ',' || '"' || RTRIM(vr_co_ano_sem02) || '"' || ',' || '"' || RTRIM(vr_co_unidade03) || '"' || ',' || '"' || RTRIM(vr_ds_curso04) || '"' || ',' || '"' || RTRIM(vr_ds_nome_coleg05) || '"' || ',' || '"' || RTRIM(vr_tp_periodo06) || '"' || ',';
		v_sql3 := '"' || RTRIM(vr_ds_cidade07) || '"' || ',' || '"' || RTRIM(vr_ds_uf_cidade08) || '"' || ',' || '"' || RTRIM(vr_ds_resultado_09) || '"' || ',' || '"' || RTRIM(vr_nu_aulas_dada10) || '"' || ',' || '"' || RTRIM(vr_nu_dias_letiv11) || '"' || ',' || '"' || RTRIM(vr_nu_faltas12) || '"' || ',' || '"' || RTRIM(vr_ds_serie13) || '"' || ',' || '"' || RTRIM(vr_ctr_import14) || '"' || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_serie00 IS NULL THEN
			vr_co_serie00 := 'null';
		ELSE
			vr_co_serie00 := '"' || RTRIM(pa_co_serie00) || '"';
		END IF;
		IF pa_co_aluno01 IS NULL THEN
			vr_co_aluno01 := 'null';
		ELSE
			vr_co_aluno01 := '"' || RTRIM(pa_co_aluno01) || '"';
		END IF;
		IF pa_co_ano_sem02 IS NULL THEN
			vr_co_ano_sem02 := 'null';
		ELSE
			vr_co_ano_sem02 := '"' || RTRIM(pa_co_ano_sem02) || '"';
		END IF;
		IF pa_co_unidade03 IS NULL THEN
			vr_co_unidade03 := 'null';
		ELSE
			vr_co_unidade03 := '"' || RTRIM(pa_co_unidade03) || '"';
		END IF;
		v_sql1 := '  delete from s_historico_serie where co_serie = ' || RTRIM(vr_co_serie00) || '  and co_aluno = ' || RTRIM(vr_co_aluno01) || '  and co_ano_sem = ' || RTRIM(vr_co_ano_sem02) || '  and co_unidade = ' || RTRIM(vr_co_unidade03) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_serie00 IS NULL
		AND pa_co_serie00 IS NULL THEN
			vr_co_serie00 := 'null';
		END IF;
		IF pn_co_serie00 IS NULL
		AND pa_co_serie00 IS NOT NULL THEN
			vr_co_serie00 := 'null';
		END IF;
		IF pn_co_serie00 IS NOT NULL
		AND pa_co_serie00 IS NULL THEN
			vr_co_serie00 := '"' || RTRIM(pn_co_serie00) || '"';
		END IF;
		IF pn_co_serie00 IS NOT NULL
		AND pa_co_serie00 IS NOT NULL THEN
			IF pa_co_serie00 <> pn_co_serie00 THEN
				vr_co_serie00 := '"' || RTRIM(pn_co_serie00) || '"';
			ELSE
				vr_co_serie00 := '"' || RTRIM(pa_co_serie00) || '"';
			END IF;
		END IF;
		IF pn_co_aluno01 IS NULL
		AND pa_co_aluno01 IS NULL THEN
			vr_co_aluno01 := 'null';
		END IF;
		IF pn_co_aluno01 IS NULL
		AND pa_co_aluno01 IS NOT NULL THEN
			vr_co_aluno01 := 'null';
		END IF;
		IF pn_co_aluno01 IS NOT NULL
		AND pa_co_aluno01 IS NULL THEN
			vr_co_aluno01 := '"' || RTRIM(pn_co_aluno01) || '"';
		END IF;
		IF pn_co_aluno01 IS NOT NULL
		AND pa_co_aluno01 IS NOT NULL THEN
			IF pa_co_aluno01 <> pn_co_aluno01 THEN
				vr_co_aluno01 := '"' || RTRIM(pn_co_aluno01) || '"';
			ELSE
				vr_co_aluno01 := '"' || RTRIM(pa_co_aluno01) || '"';
			END IF;
		END IF;
		IF pn_co_ano_sem02 IS NULL
		AND pa_co_ano_sem02 IS NULL THEN
			vr_co_ano_sem02 := 'null';
		END IF;
		IF pn_co_ano_sem02 IS NULL
		AND pa_co_ano_sem02 IS NOT NULL THEN
			vr_co_ano_sem02 := 'null';
		END IF;
		IF pn_co_ano_sem02 IS NOT NULL
		AND pa_co_ano_sem02 IS NULL THEN
			vr_co_ano_sem02 := '"' || RTRIM(pn_co_ano_sem02) || '"';
		END IF;
		IF pn_co_ano_sem02 IS NOT NULL
		AND pa_co_ano_sem02 IS NOT NULL THEN
			IF pa_co_ano_sem02 <> pn_co_ano_sem02 THEN
				vr_co_ano_sem02 := '"' || RTRIM(pn_co_ano_sem02) || '"';
			ELSE
				vr_co_ano_sem02 := '"' || RTRIM(pa_co_ano_sem02) || '"';
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
		IF pn_ds_curso04 IS NULL
		AND pa_ds_curso04 IS NULL THEN
			vr_ds_curso04 := 'null';
		END IF;
		IF pn_ds_curso04 IS NULL
		AND pa_ds_curso04 IS NOT NULL THEN
			vr_ds_curso04 := 'null';
		END IF;
		IF pn_ds_curso04 IS NOT NULL
		AND pa_ds_curso04 IS NULL THEN
			vr_ds_curso04 := '"' || RTRIM(pn_ds_curso04) || '"';
		END IF;
		IF pn_ds_curso04 IS NOT NULL
		AND pa_ds_curso04 IS NOT NULL THEN
			IF pa_ds_curso04 <> pn_ds_curso04 THEN
				vr_ds_curso04 := '"' || RTRIM(pn_ds_curso04) || '"';
			ELSE
				vr_ds_curso04 := '"' || RTRIM(pa_ds_curso04) || '"';
			END IF;
		END IF;
		IF pn_ds_nome_coleg05 IS NULL
		AND pa_ds_nome_coleg05 IS NULL THEN
			vr_ds_nome_coleg05 := 'null';
		END IF;
		IF pn_ds_nome_coleg05 IS NULL
		AND pa_ds_nome_coleg05 IS NOT NULL THEN
			vr_ds_nome_coleg05 := 'null';
		END IF;
		IF pn_ds_nome_coleg05 IS NOT NULL
		AND pa_ds_nome_coleg05 IS NULL THEN
			vr_ds_nome_coleg05 := '"' || RTRIM(pn_ds_nome_coleg05) || '"';
		END IF;
		IF pn_ds_nome_coleg05 IS NOT NULL
		AND pa_ds_nome_coleg05 IS NOT NULL THEN
			IF pa_ds_nome_coleg05 <> pn_ds_nome_coleg05 THEN
				vr_ds_nome_coleg05 := '"' || RTRIM(pn_ds_nome_coleg05) || '"';
			ELSE
				vr_ds_nome_coleg05 := '"' || RTRIM(pa_ds_nome_coleg05) || '"';
			END IF;
		END IF;
		IF pn_tp_periodo06 IS NULL
		AND pa_tp_periodo06 IS NULL THEN
			vr_tp_periodo06 := 'null';
		END IF;
		IF pn_tp_periodo06 IS NULL
		AND pa_tp_periodo06 IS NOT NULL THEN
			vr_tp_periodo06 := 'null';
		END IF;
		IF pn_tp_periodo06 IS NOT NULL
		AND pa_tp_periodo06 IS NULL THEN
			vr_tp_periodo06 := '"' || RTRIM(pn_tp_periodo06) || '"';
		END IF;
		IF pn_tp_periodo06 IS NOT NULL
		AND pa_tp_periodo06 IS NOT NULL THEN
			IF pa_tp_periodo06 <> pn_tp_periodo06 THEN
				vr_tp_periodo06 := '"' || RTRIM(pn_tp_periodo06) || '"';
			ELSE
				vr_tp_periodo06 := '"' || RTRIM(pa_tp_periodo06) || '"';
			END IF;
		END IF;
		IF pn_ds_cidade07 IS NULL
		AND pa_ds_cidade07 IS NULL THEN
			vr_ds_cidade07 := 'null';
		END IF;
		IF pn_ds_cidade07 IS NULL
		AND pa_ds_cidade07 IS NOT NULL THEN
			vr_ds_cidade07 := 'null';
		END IF;
		IF pn_ds_cidade07 IS NOT NULL
		AND pa_ds_cidade07 IS NULL THEN
			vr_ds_cidade07 := '"' || RTRIM(pn_ds_cidade07) || '"';
		END IF;
		IF pn_ds_cidade07 IS NOT NULL
		AND pa_ds_cidade07 IS NOT NULL THEN
			IF pa_ds_cidade07 <> pn_ds_cidade07 THEN
				vr_ds_cidade07 := '"' || RTRIM(pn_ds_cidade07) || '"';
			ELSE
				vr_ds_cidade07 := '"' || RTRIM(pa_ds_cidade07) || '"';
			END IF;
		END IF;
		IF pn_ds_uf_cidade08 IS NULL
		AND pa_ds_uf_cidade08 IS NULL THEN
			vr_ds_uf_cidade08 := 'null';
		END IF;
		IF pn_ds_uf_cidade08 IS NULL
		AND pa_ds_uf_cidade08 IS NOT NULL THEN
			vr_ds_uf_cidade08 := 'null';
		END IF;
		IF pn_ds_uf_cidade08 IS NOT NULL
		AND pa_ds_uf_cidade08 IS NULL THEN
			vr_ds_uf_cidade08 := '"' || RTRIM(pn_ds_uf_cidade08) || '"';
		END IF;
		IF pn_ds_uf_cidade08 IS NOT NULL
		AND pa_ds_uf_cidade08 IS NOT NULL THEN
			IF pa_ds_uf_cidade08 <> pn_ds_uf_cidade08 THEN
				vr_ds_uf_cidade08 := '"' || RTRIM(pn_ds_uf_cidade08) || '"';
			ELSE
				vr_ds_uf_cidade08 := '"' || RTRIM(pa_ds_uf_cidade08) || '"';
			END IF;
		END IF;
		IF pn_ds_resultado_09 IS NULL
		AND pa_ds_resultado_09 IS NULL THEN
			vr_ds_resultado_09 := 'null';
		END IF;
		IF pn_ds_resultado_09 IS NULL
		AND pa_ds_resultado_09 IS NOT NULL THEN
			vr_ds_resultado_09 := 'null';
		END IF;
		IF pn_ds_resultado_09 IS NOT NULL
		AND pa_ds_resultado_09 IS NULL THEN
			vr_ds_resultado_09 := '"' || RTRIM(pn_ds_resultado_09) || '"';
		END IF;
		IF pn_ds_resultado_09 IS NOT NULL
		AND pa_ds_resultado_09 IS NOT NULL THEN
			IF pa_ds_resultado_09 <> pn_ds_resultado_09 THEN
				vr_ds_resultado_09 := '"' || RTRIM(pn_ds_resultado_09) || '"';
			ELSE
				vr_ds_resultado_09 := '"' || RTRIM(pa_ds_resultado_09) || '"';
			END IF;
		END IF;
		IF pn_nu_aulas_dada10 IS NULL
		AND pa_nu_aulas_dada10 IS NULL THEN
			vr_nu_aulas_dada10 := 'null';
		END IF;
		IF pn_nu_aulas_dada10 IS NULL
		AND pa_nu_aulas_dada10 IS NOT NULL THEN
			vr_nu_aulas_dada10 := 'null';
		END IF;
		IF pn_nu_aulas_dada10 IS NOT NULL
		AND pa_nu_aulas_dada10 IS NULL THEN
			vr_nu_aulas_dada10 := '"' || RTRIM(pn_nu_aulas_dada10) || '"';
		END IF;
		IF pn_nu_aulas_dada10 IS NOT NULL
		AND pa_nu_aulas_dada10 IS NOT NULL THEN
			IF pa_nu_aulas_dada10 <> pn_nu_aulas_dada10 THEN
				vr_nu_aulas_dada10 := '"' || RTRIM(pn_nu_aulas_dada10) || '"';
			ELSE
				vr_nu_aulas_dada10 := '"' || RTRIM(pa_nu_aulas_dada10) || '"';
			END IF;
		END IF;
		IF pn_nu_dias_letiv11 IS NULL
		AND pa_nu_dias_letiv11 IS NULL THEN
			vr_nu_dias_letiv11 := 'null';
		END IF;
		IF pn_nu_dias_letiv11 IS NULL
		AND pa_nu_dias_letiv11 IS NOT NULL THEN
			vr_nu_dias_letiv11 := 'null';
		END IF;
		IF pn_nu_dias_letiv11 IS NOT NULL
		AND pa_nu_dias_letiv11 IS NULL THEN
			vr_nu_dias_letiv11 := '"' || RTRIM(pn_nu_dias_letiv11) || '"';
		END IF;
		IF pn_nu_dias_letiv11 IS NOT NULL
		AND pa_nu_dias_letiv11 IS NOT NULL THEN
			IF pa_nu_dias_letiv11 <> pn_nu_dias_letiv11 THEN
				vr_nu_dias_letiv11 := '"' || RTRIM(pn_nu_dias_letiv11) || '"';
			ELSE
				vr_nu_dias_letiv11 := '"' || RTRIM(pa_nu_dias_letiv11) || '"';
			END IF;
		END IF;
		IF pn_nu_faltas12 IS NULL
		AND pa_nu_faltas12 IS NULL THEN
			vr_nu_faltas12 := 'null';
		END IF;
		IF pn_nu_faltas12 IS NULL
		AND pa_nu_faltas12 IS NOT NULL THEN
			vr_nu_faltas12 := 'null';
		END IF;
		IF pn_nu_faltas12 IS NOT NULL
		AND pa_nu_faltas12 IS NULL THEN
			vr_nu_faltas12 := '"' || RTRIM(pn_nu_faltas12) || '"';
		END IF;
		IF pn_nu_faltas12 IS NOT NULL
		AND pa_nu_faltas12 IS NOT NULL THEN
			IF pa_nu_faltas12 <> pn_nu_faltas12 THEN
				vr_nu_faltas12 := '"' || RTRIM(pn_nu_faltas12) || '"';
			ELSE
				vr_nu_faltas12 := '"' || RTRIM(pa_nu_faltas12) || '"';
			END IF;
		END IF;
		IF pn_ds_serie13 IS NULL
		AND pa_ds_serie13 IS NULL THEN
			vr_ds_serie13 := 'null';
		END IF;
		IF pn_ds_serie13 IS NULL
		AND pa_ds_serie13 IS NOT NULL THEN
			vr_ds_serie13 := 'null';
		END IF;
		IF pn_ds_serie13 IS NOT NULL
		AND pa_ds_serie13 IS NULL THEN
			vr_ds_serie13 := '"' || RTRIM(pn_ds_serie13) || '"';
		END IF;
		IF pn_ds_serie13 IS NOT NULL
		AND pa_ds_serie13 IS NOT NULL THEN
			IF pa_ds_serie13 <> pn_ds_serie13 THEN
				vr_ds_serie13 := '"' || RTRIM(pn_ds_serie13) || '"';
			ELSE
				vr_ds_serie13 := '"' || RTRIM(pa_ds_serie13) || '"';
			END IF;
		END IF;
		IF pn_ctr_import14 IS NULL
		AND pa_ctr_import14 IS NULL THEN
			vr_ctr_import14 := 'null';
		END IF;
		IF pn_ctr_import14 IS NULL
		AND pa_ctr_import14 IS NOT NULL THEN
			vr_ctr_import14 := 'null';
		END IF;
		IF pn_ctr_import14 IS NOT NULL
		AND pa_ctr_import14 IS NULL THEN
			vr_ctr_import14 := '"' || RTRIM(pn_ctr_import14) || '"';
		END IF;
		IF pn_ctr_import14 IS NOT NULL
		AND pa_ctr_import14 IS NOT NULL THEN
			IF pa_ctr_import14 <> pn_ctr_import14 THEN
				vr_ctr_import14 := '"' || RTRIM(pn_ctr_import14) || '"';
			ELSE
				vr_ctr_import14 := '"' || RTRIM(pa_ctr_import14) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_historico_serie set co_serie = ' || RTRIM(vr_co_serie00) || '  , co_aluno = ' || RTRIM(vr_co_aluno01) || '  , co_ano_sem = ' || RTRIM(vr_co_ano_sem02) || '  , co_unidade = ' || RTRIM(vr_co_unidade03) || '  , ds_curso = ' || RTRIM(vr_ds_curso04) || '  , ds_nome_colegio = ' || RTRIM(vr_ds_nome_coleg05) || '  , tp_periodo = ' || RTRIM(vr_tp_periodo06);
		v_sql2 := '  , ds_cidade = ' || RTRIM(vr_ds_cidade07) || '  , ds_uf_cidade = ' || RTRIM(vr_ds_uf_cidade08) || '  , ds_resultado_final = ' || RTRIM(vr_ds_resultado_09) || '  , nu_aulas_dadas = ' || RTRIM(vr_nu_aulas_dada10) || '  , nu_dias_letivos = ' || RTRIM(vr_nu_dias_letiv11) || '  , nu_faltas = ' || RTRIM(vr_nu_faltas12) || '  , ds_serie = ' || RTRIM(vr_ds_serie13) || '  , ctr_import = ' || RTRIM(vr_ctr_import14);
		v_sql3 := ' where co_serie = ' || RTRIM(vr_co_serie00) || '  and co_aluno = ' || RTRIM(vr_co_aluno01) || '  and co_ano_sem = ' || RTRIM(vr_co_ano_sem02) || '  and co_unidade = ' || RTRIM(vr_co_unidade03) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3;
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
		       's_historico_serie',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_historic116;
/

