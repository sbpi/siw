CREATE OR REPLACE PROCEDURE pr_s_historic114(
	P_OP_IN                CHAR,
	PA_co_serie00_IN       s_historico_nota.co_serie%TYPE,
	PA_co_ano_sem01_IN     s_historico_nota.co_ano_sem%TYPE,
	PA_co_aluno02_IN       s_historico_nota.co_aluno%TYPE,
	PA_co_unidade03_IN     s_historico_nota.co_unidade%TYPE,
	PA_co_historico_04_IN  s_historico_nota.co_historico_nota%TYPE,
	PA_co_disciplina05_IN  s_historico_nota.co_disciplina%TYPE,
	PA_nu_nota_0106_IN     s_historico_nota.nu_nota_01%TYPE,
	PA_ds_disciplina07_IN  s_historico_nota.ds_disciplina%TYPE,
	PA_nu_faltas08_IN      s_historico_nota.nu_faltas%TYPE,
	PA_nu_nota_0209_IN     s_historico_nota.nu_nota_02%TYPE,
	PA_nu_carga_hora10_IN  s_historico_nota.nu_carga_horaria%TYPE,
	PA_nu_aulas_dada11_IN  s_historico_nota.nu_aulas_dadas%TYPE,
	PA_nu_nota_0312_IN     s_historico_nota.nu_nota_03%TYPE,
	PA_tp_disciplina13_IN  s_historico_nota.tp_disciplina%TYPE,
	PA_nu_credito14_IN     s_historico_nota.nu_credito%TYPE,
	PA_ds_aprov15_IN       s_historico_nota.ds_aprov%TYPE,
	PA_ctr_import16_IN     s_historico_nota.ctr_import%TYPE,
	PA_nu_ordem17_IN       s_historico_nota.nu_ordem%TYPE,
	PA_tp_obrigatori18_IN  s_historico_nota.tp_obrigatoria%TYPE,
	PN_co_serie00_IN       s_historico_nota.co_serie%TYPE,
	PN_co_ano_sem01_IN     s_historico_nota.co_ano_sem%TYPE,
	PN_co_aluno02_IN       s_historico_nota.co_aluno%TYPE,
	PN_co_unidade03_IN     s_historico_nota.co_unidade%TYPE,
	PN_co_historico_04_IN  s_historico_nota.co_historico_nota%TYPE,
	PN_co_disciplina05_IN  s_historico_nota.co_disciplina%TYPE,
	PN_nu_nota_0106_IN     s_historico_nota.nu_nota_01%TYPE,
	PN_ds_disciplina07_IN  s_historico_nota.ds_disciplina%TYPE,
	PN_nu_faltas08_IN      s_historico_nota.nu_faltas%TYPE,
	PN_nu_nota_0209_IN     s_historico_nota.nu_nota_02%TYPE,
	PN_nu_carga_hora10_IN  s_historico_nota.nu_carga_horaria%TYPE,
	PN_nu_aulas_dada11_IN  s_historico_nota.nu_aulas_dadas%TYPE,
	PN_nu_nota_0312_IN     s_historico_nota.nu_nota_03%TYPE,
	PN_tp_disciplina13_IN  s_historico_nota.tp_disciplina%TYPE,
	PN_nu_credito14_IN     s_historico_nota.nu_credito%TYPE,
	PN_ds_aprov15_IN       s_historico_nota.ds_aprov%TYPE,
	PN_ctr_import16_IN     s_historico_nota.ctr_import%TYPE,
	PN_nu_ordem17_IN       s_historico_nota.nu_ordem%TYPE,
	PN_tp_obrigatori18_IN  s_historico_nota.tp_obrigatoria%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_serie00       s_historico_nota.co_serie%TYPE := PA_co_serie00_IN;
PA_co_ano_sem01     s_historico_nota.co_ano_sem%TYPE := PA_co_ano_sem01_IN;
PA_co_aluno02       s_historico_nota.co_aluno%TYPE := PA_co_aluno02_IN;
PA_co_unidade03     s_historico_nota.co_unidade%TYPE := PA_co_unidade03_IN;
PA_co_historico_04  s_historico_nota.co_historico_nota%TYPE := PA_co_historico_04_IN;
PA_co_disciplina05  s_historico_nota.co_disciplina%TYPE := PA_co_disciplina05_IN;
PA_nu_nota_0106     s_historico_nota.nu_nota_01%TYPE := PA_nu_nota_0106_IN;
PA_ds_disciplina07  s_historico_nota.ds_disciplina%TYPE := PA_ds_disciplina07_IN;
PA_nu_faltas08      s_historico_nota.nu_faltas%TYPE := PA_nu_faltas08_IN;
PA_nu_nota_0209     s_historico_nota.nu_nota_02%TYPE := PA_nu_nota_0209_IN;
PA_nu_carga_hora10  s_historico_nota.nu_carga_horaria%TYPE := PA_nu_carga_hora10_IN;
PA_nu_aulas_dada11  s_historico_nota.nu_aulas_dadas%TYPE := PA_nu_aulas_dada11_IN;
PA_nu_nota_0312     s_historico_nota.nu_nota_03%TYPE := PA_nu_nota_0312_IN;
PA_tp_disciplina13  s_historico_nota.tp_disciplina%TYPE := PA_tp_disciplina13_IN;
PA_nu_credito14     s_historico_nota.nu_credito%TYPE := PA_nu_credito14_IN;
PA_ds_aprov15       s_historico_nota.ds_aprov%TYPE := PA_ds_aprov15_IN;
PA_ctr_import16     s_historico_nota.ctr_import%TYPE := PA_ctr_import16_IN;
PA_nu_ordem17       s_historico_nota.nu_ordem%TYPE := PA_nu_ordem17_IN;
PA_tp_obrigatori18  s_historico_nota.tp_obrigatoria%TYPE := PA_tp_obrigatori18_IN;
PN_co_serie00       s_historico_nota.co_serie%TYPE := PN_co_serie00_IN;
PN_co_ano_sem01     s_historico_nota.co_ano_sem%TYPE := PN_co_ano_sem01_IN;
PN_co_aluno02       s_historico_nota.co_aluno%TYPE := PN_co_aluno02_IN;
PN_co_unidade03     s_historico_nota.co_unidade%TYPE := PN_co_unidade03_IN;
PN_co_historico_04  s_historico_nota.co_historico_nota%TYPE := PN_co_historico_04_IN;
PN_co_disciplina05  s_historico_nota.co_disciplina%TYPE := PN_co_disciplina05_IN;
PN_nu_nota_0106     s_historico_nota.nu_nota_01%TYPE := PN_nu_nota_0106_IN;
PN_ds_disciplina07  s_historico_nota.ds_disciplina%TYPE := PN_ds_disciplina07_IN;
PN_nu_faltas08      s_historico_nota.nu_faltas%TYPE := PN_nu_faltas08_IN;
PN_nu_nota_0209     s_historico_nota.nu_nota_02%TYPE := PN_nu_nota_0209_IN;
PN_nu_carga_hora10  s_historico_nota.nu_carga_horaria%TYPE := PN_nu_carga_hora10_IN;
PN_nu_aulas_dada11  s_historico_nota.nu_aulas_dadas%TYPE := PN_nu_aulas_dada11_IN;
PN_nu_nota_0312     s_historico_nota.nu_nota_03%TYPE := PN_nu_nota_0312_IN;
PN_tp_disciplina13  s_historico_nota.tp_disciplina%TYPE := PN_tp_disciplina13_IN;
PN_nu_credito14     s_historico_nota.nu_credito%TYPE := PN_nu_credito14_IN;
PN_ds_aprov15       s_historico_nota.ds_aprov%TYPE := PN_ds_aprov15_IN;
PN_ctr_import16     s_historico_nota.ctr_import%TYPE := PN_ctr_import16_IN;
PN_nu_ordem17       s_historico_nota.nu_ordem%TYPE := PN_nu_ordem17_IN;
PN_tp_obrigatori18  s_historico_nota.tp_obrigatoria%TYPE := PN_tp_obrigatori18_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(500);
v_sql2              CHAR(500);
v_sql3              CHAR(500);
v_sql4              CHAR(500);
v_sql5              CHAR(500);
v_sql6              CHAR(500);
v_uni               CHAR(10);
vr_co_serie00       CHAR(10);
vr_co_ano_sem01     CHAR(10);
vr_co_aluno02       CHAR(20);
vr_co_unidade03     CHAR(10);
vr_co_historico_04  CHAR(10);
vr_co_disciplina05  CHAR(10);
vr_nu_nota_0106     CHAR(10);
vr_ds_disciplina07  CHAR(70);
vr_nu_faltas08      CHAR(10);
vr_nu_nota_0209     CHAR(20);
vr_nu_carga_hora10  CHAR(10);
vr_nu_aulas_dada11  CHAR(10);
vr_nu_nota_0312     CHAR(20);
vr_tp_disciplina13  CHAR(40);
vr_nu_credito14     CHAR(10);
vr_ds_aprov15       CHAR(40);
vr_ctr_import16     CHAR(10);
vr_nu_ordem17       CHAR(10);
vr_tp_obrigatori18  CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_serie00 IS NULL THEN
			vr_co_serie00 := 'null';
		ELSE
			vr_co_serie00 := pn_co_serie00;
		END IF;
		IF pn_co_ano_sem01 IS NULL THEN
			vr_co_ano_sem01 := 'null';
		ELSE
			vr_co_ano_sem01 := pn_co_ano_sem01;
		END IF;
		IF pn_co_aluno02 IS NULL THEN
			vr_co_aluno02 := 'null';
		ELSE
			vr_co_aluno02 := pn_co_aluno02;
		END IF;
		IF pn_co_unidade03 IS NULL THEN
			vr_co_unidade03 := 'null';
		ELSE
			vr_co_unidade03 := pn_co_unidade03;
		END IF;
		IF pn_co_historico_04 IS NULL THEN
			vr_co_historico_04 := 'null';
		ELSE
			vr_co_historico_04 := pn_co_historico_04;
		END IF;
		IF pn_co_disciplina05 IS NULL THEN
			vr_co_disciplina05 := 'null';
		ELSE
			vr_co_disciplina05 := pn_co_disciplina05;
		END IF;
		IF pn_nu_nota_0106 IS NULL THEN
			vr_nu_nota_0106 := 'null';
		ELSE
			vr_nu_nota_0106 := pn_nu_nota_0106;
		END IF;
		IF pn_ds_disciplina07 IS NULL THEN
			vr_ds_disciplina07 := 'null';
		ELSE
			vr_ds_disciplina07 := pn_ds_disciplina07;
		END IF;
		IF pn_nu_faltas08 IS NULL THEN
			vr_nu_faltas08 := 'null';
		ELSE
			vr_nu_faltas08 := pn_nu_faltas08;
		END IF;
		IF pn_nu_nota_0209 IS NULL THEN
			vr_nu_nota_0209 := 'null';
		ELSE
			vr_nu_nota_0209 := pn_nu_nota_0209;
		END IF;
		IF pn_nu_carga_hora10 IS NULL THEN
			vr_nu_carga_hora10 := 'null';
		ELSE
			vr_nu_carga_hora10 := pn_nu_carga_hora10;
		END IF;
		IF pn_nu_aulas_dada11 IS NULL THEN
			vr_nu_aulas_dada11 := 'null';
		ELSE
			vr_nu_aulas_dada11 := pn_nu_aulas_dada11;
		END IF;
		IF pn_nu_nota_0312 IS NULL THEN
			vr_nu_nota_0312 := 'null';
		ELSE
			vr_nu_nota_0312 := pn_nu_nota_0312;
		END IF;
		IF pn_tp_disciplina13 IS NULL THEN
			vr_tp_disciplina13 := 'null';
		ELSE
			vr_tp_disciplina13 := pn_tp_disciplina13;
		END IF;
		IF pn_nu_credito14 IS NULL THEN
			vr_nu_credito14 := 'null';
		ELSE
			vr_nu_credito14 := pn_nu_credito14;
		END IF;
		IF pn_ds_aprov15 IS NULL THEN
			vr_ds_aprov15 := 'null';
		ELSE
			vr_ds_aprov15 := pn_ds_aprov15;
		END IF;
		IF pn_ctr_import16 IS NULL THEN
			vr_ctr_import16 := 'null';
		ELSE
			vr_ctr_import16 := pn_ctr_import16;
		END IF;
		IF pn_nu_ordem17 IS NULL THEN
			vr_nu_ordem17 := 'null';
		ELSE
			vr_nu_ordem17 := pn_nu_ordem17;
		END IF;
		IF pn_tp_obrigatori18 IS NULL THEN
			vr_tp_obrigatori18 := 'null';
		ELSE
			vr_tp_obrigatori18 := pn_tp_obrigatori18;
		END IF;
		v_sql1 := 'insert into s_historico_nota(co_serie, co_ano_sem, co_aluno, co_unidade, co_historico_nota, co_disciplina, nu_nota_01, ds_disciplina, nu_faltas, nu_nota_02, ' || 'nu_carga_horaria, nu_aulas_dadas, nu_nota_03, tp_disciplina, nu_credito, ds_aprov, ctr_import, nu_ordem, tp_obrigatoria) values (';
		v_sql2 := '"' || RTRIM(vr_co_serie00) || '"' || ',' || '"' || RTRIM(vr_co_ano_sem01) || '"' || ',' || '"' || RTRIM(vr_co_aluno02) || '"' || ',' || '"' || RTRIM(vr_co_unidade03) || '"' || ',';
		v_sql3 := RTRIM(vr_co_historico_04) || ',' || '"' || RTRIM(vr_co_disciplina05) || '"' || ',' || '"' || RTRIM(vr_nu_nota_0106) || '"' || ',' || '"' || RTRIM(vr_ds_disciplina07) || '"' || ',';
		v_sql4 := '"' || RTRIM(vr_nu_faltas08) || '"' || ',' || '"' || RTRIM(vr_nu_nota_0209) || '"' || ',' || '"' || RTRIM(vr_nu_carga_hora10) || '"' || ',' || '"' || RTRIM(vr_nu_aulas_dada11) || '"' || ',' || '"' || RTRIM(vr_nu_nota_0312) || '"' || ',';
		v_sql5 := '"' || RTRIM(vr_tp_disciplina13) || '"' || ',' || RTRIM(vr_nu_credito14) || ',' || '"' || RTRIM(vr_ds_aprov15) || '"' || ',' || '"' || RTRIM(vr_ctr_import16) || '"' || ',' || RTRIM(vr_nu_ordem17) || ',' || '"' || RTRIM(vr_tp_obrigatori18) || '"' || ');';
		v_sql6 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5 || v_sql6;
	ELSIF p_op = 'del' THEN
		IF pa_co_serie00 IS NULL THEN
			vr_co_serie00 := 'null';
		ELSE
			vr_co_serie00 := '"' || RTRIM(pa_co_serie00) || '"';
		END IF;
		IF pa_co_ano_sem01 IS NULL THEN
			vr_co_ano_sem01 := 'null';
		ELSE
			vr_co_ano_sem01 := '"' || RTRIM(pa_co_ano_sem01) || '"';
		END IF;
		IF pa_co_aluno02 IS NULL THEN
			vr_co_aluno02 := 'null';
		ELSE
			vr_co_aluno02 := '"' || RTRIM(pa_co_aluno02) || '"';
		END IF;
		IF pa_co_unidade03 IS NULL THEN
			vr_co_unidade03 := 'null';
		ELSE
			vr_co_unidade03 := '"' || RTRIM(pa_co_unidade03) || '"';
		END IF;
		IF pa_co_historico_04 IS NULL THEN
			vr_co_historico_04 := 'null';
		ELSE
			vr_co_historico_04 := pa_co_historico_04;
		END IF;
		v_sql1 := '  delete from s_historico_nota where co_serie = ' || RTRIM(vr_co_serie00) || '  and co_ano_sem = ' || RTRIM(vr_co_ano_sem01) || '  and co_aluno = ' || RTRIM(vr_co_aluno02);
		v_sql2 := '  and co_unidade = ' || RTRIM(vr_co_unidade03) || '  and co_historico_nota = ' || RTRIM(vr_co_historico_04) || ';';
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
		IF pn_co_ano_sem01 IS NULL
		AND pa_co_ano_sem01 IS NULL THEN
			vr_co_ano_sem01 := 'null';
		END IF;
		IF pn_co_ano_sem01 IS NULL
		AND pa_co_ano_sem01 IS NOT NULL THEN
			vr_co_ano_sem01 := 'null';
		END IF;
		IF pn_co_ano_sem01 IS NOT NULL
		AND pa_co_ano_sem01 IS NULL THEN
			vr_co_ano_sem01 := '"' || RTRIM(pn_co_ano_sem01) || '"';
		END IF;
		IF pn_co_ano_sem01 IS NOT NULL
		AND pa_co_ano_sem01 IS NOT NULL THEN
			IF pa_co_ano_sem01 <> pn_co_ano_sem01 THEN
				vr_co_ano_sem01 := '"' || RTRIM(pn_co_ano_sem01) || '"';
			ELSE
				vr_co_ano_sem01 := '"' || RTRIM(pa_co_ano_sem01) || '"';
			END IF;
		END IF;
		IF pn_co_aluno02 IS NULL
		AND pa_co_aluno02 IS NULL THEN
			vr_co_aluno02 := 'null';
		END IF;
		IF pn_co_aluno02 IS NULL
		AND pa_co_aluno02 IS NOT NULL THEN
			vr_co_aluno02 := 'null';
		END IF;
		IF pn_co_aluno02 IS NOT NULL
		AND pa_co_aluno02 IS NULL THEN
			vr_co_aluno02 := '"' || RTRIM(pn_co_aluno02) || '"';
		END IF;
		IF pn_co_aluno02 IS NOT NULL
		AND pa_co_aluno02 IS NOT NULL THEN
			IF pa_co_aluno02 <> pn_co_aluno02 THEN
				vr_co_aluno02 := '"' || RTRIM(pn_co_aluno02) || '"';
			ELSE
				vr_co_aluno02 := '"' || RTRIM(pa_co_aluno02) || '"';
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
		IF pn_co_historico_04 IS NULL
		AND pa_co_historico_04 IS NULL THEN
			vr_co_historico_04 := 'null';
		END IF;
		IF pn_co_historico_04 IS NULL
		AND pa_co_historico_04 IS NOT NULL THEN
			vr_co_historico_04 := 'null';
		END IF;
		IF pn_co_historico_04 IS NOT NULL
		AND pa_co_historico_04 IS NULL THEN
			vr_co_historico_04 := pn_co_historico_04;
		END IF;
		IF pn_co_historico_04 IS NOT NULL
		AND pa_co_historico_04 IS NOT NULL THEN
			IF pa_co_historico_04 <> pn_co_historico_04 THEN
				vr_co_historico_04 := pn_co_historico_04;
			ELSE
				vr_co_historico_04 := pa_co_historico_04;
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
		IF pn_nu_nota_0106 IS NULL
		AND pa_nu_nota_0106 IS NULL THEN
			vr_nu_nota_0106 := 'null';
		END IF;
		IF pn_nu_nota_0106 IS NULL
		AND pa_nu_nota_0106 IS NOT NULL THEN
			vr_nu_nota_0106 := 'null';
		END IF;
		IF pn_nu_nota_0106 IS NOT NULL
		AND pa_nu_nota_0106 IS NULL THEN
			vr_nu_nota_0106 := '"' || RTRIM(pn_nu_nota_0106) || '"';
		END IF;
		IF pn_nu_nota_0106 IS NOT NULL
		AND pa_nu_nota_0106 IS NOT NULL THEN
			IF pa_nu_nota_0106 <> pn_nu_nota_0106 THEN
				vr_nu_nota_0106 := '"' || RTRIM(pn_nu_nota_0106) || '"';
			ELSE
				vr_nu_nota_0106 := '"' || RTRIM(pa_nu_nota_0106) || '"';
			END IF;
		END IF;
		IF pn_ds_disciplina07 IS NULL
		AND pa_ds_disciplina07 IS NULL THEN
			vr_ds_disciplina07 := 'null';
		END IF;
		IF pn_ds_disciplina07 IS NULL
		AND pa_ds_disciplina07 IS NOT NULL THEN
			vr_ds_disciplina07 := 'null';
		END IF;
		IF pn_ds_disciplina07 IS NOT NULL
		AND pa_ds_disciplina07 IS NULL THEN
			vr_ds_disciplina07 := '"' || RTRIM(pn_ds_disciplina07) || '"';
		END IF;
		IF pn_ds_disciplina07 IS NOT NULL
		AND pa_ds_disciplina07 IS NOT NULL THEN
			IF pa_ds_disciplina07 <> pn_ds_disciplina07 THEN
				vr_ds_disciplina07 := '"' || RTRIM(pn_ds_disciplina07) || '"';
			ELSE
				vr_ds_disciplina07 := '"' || RTRIM(pa_ds_disciplina07) || '"';
			END IF;
		END IF;
		IF pn_nu_faltas08 IS NULL
		AND pa_nu_faltas08 IS NULL THEN
			vr_nu_faltas08 := 'null';
		END IF;
		IF pn_nu_faltas08 IS NULL
		AND pa_nu_faltas08 IS NOT NULL THEN
			vr_nu_faltas08 := 'null';
		END IF;
		IF pn_nu_faltas08 IS NOT NULL
		AND pa_nu_faltas08 IS NULL THEN
			vr_nu_faltas08 := '"' || RTRIM(pn_nu_faltas08) || '"';
		END IF;
		IF pn_nu_faltas08 IS NOT NULL
		AND pa_nu_faltas08 IS NOT NULL THEN
			IF pa_nu_faltas08 <> pn_nu_faltas08 THEN
				vr_nu_faltas08 := '"' || RTRIM(pn_nu_faltas08) || '"';
			ELSE
				vr_nu_faltas08 := '"' || RTRIM(pa_nu_faltas08) || '"';
			END IF;
		END IF;
		IF pn_nu_nota_0209 IS NULL
		AND pa_nu_nota_0209 IS NULL THEN
			vr_nu_nota_0209 := 'null';
		END IF;
		IF pn_nu_nota_0209 IS NULL
		AND pa_nu_nota_0209 IS NOT NULL THEN
			vr_nu_nota_0209 := 'null';
		END IF;
		IF pn_nu_nota_0209 IS NOT NULL
		AND pa_nu_nota_0209 IS NULL THEN
			vr_nu_nota_0209 := '"' || RTRIM(pn_nu_nota_0209) || '"';
		END IF;
		IF pn_nu_nota_0209 IS NOT NULL
		AND pa_nu_nota_0209 IS NOT NULL THEN
			IF pa_nu_nota_0209 <> pn_nu_nota_0209 THEN
				vr_nu_nota_0209 := '"' || RTRIM(pn_nu_nota_0209) || '"';
			ELSE
				vr_nu_nota_0209 := '"' || RTRIM(pa_nu_nota_0209) || '"';
			END IF;
		END IF;
		IF pn_nu_carga_hora10 IS NULL
		AND pa_nu_carga_hora10 IS NULL THEN
			vr_nu_carga_hora10 := 'null';
		END IF;
		IF pn_nu_carga_hora10 IS NULL
		AND pa_nu_carga_hora10 IS NOT NULL THEN
			vr_nu_carga_hora10 := 'null';
		END IF;
		IF pn_nu_carga_hora10 IS NOT NULL
		AND pa_nu_carga_hora10 IS NULL THEN
			vr_nu_carga_hora10 := '"' || RTRIM(pn_nu_carga_hora10) || '"';
		END IF;
		IF pn_nu_carga_hora10 IS NOT NULL
		AND pa_nu_carga_hora10 IS NOT NULL THEN
			IF pa_nu_carga_hora10 <> pn_nu_carga_hora10 THEN
				vr_nu_carga_hora10 := '"' || RTRIM(pn_nu_carga_hora10) || '"';
			ELSE
				vr_nu_carga_hora10 := '"' || RTRIM(pa_nu_carga_hora10) || '"';
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
		IF pn_nu_nota_0312 IS NULL
		AND pa_nu_nota_0312 IS NULL THEN
			vr_nu_nota_0312 := 'null';
		END IF;
		IF pn_nu_nota_0312 IS NULL
		AND pa_nu_nota_0312 IS NOT NULL THEN
			vr_nu_nota_0312 := 'null';
		END IF;
		IF pn_nu_nota_0312 IS NOT NULL
		AND pa_nu_nota_0312 IS NULL THEN
			vr_nu_nota_0312 := '"' || RTRIM(pn_nu_nota_0312) || '"';
		END IF;
		IF pn_nu_nota_0312 IS NOT NULL
		AND pa_nu_nota_0312 IS NOT NULL THEN
			IF pa_nu_nota_0312 <> pn_nu_nota_0312 THEN
				vr_nu_nota_0312 := '"' || RTRIM(pn_nu_nota_0312) || '"';
			ELSE
				vr_nu_nota_0312 := '"' || RTRIM(pa_nu_nota_0312) || '"';
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
		IF pn_nu_credito14 IS NULL
		AND pa_nu_credito14 IS NULL THEN
			vr_nu_credito14 := 'null';
		END IF;
		IF pn_nu_credito14 IS NULL
		AND pa_nu_credito14 IS NOT NULL THEN
			vr_nu_credito14 := 'null';
		END IF;
		IF pn_nu_credito14 IS NOT NULL
		AND pa_nu_credito14 IS NULL THEN
			vr_nu_credito14 := pn_nu_credito14;
		END IF;
		IF pn_nu_credito14 IS NOT NULL
		AND pa_nu_credito14 IS NOT NULL THEN
			IF pa_nu_credito14 <> pn_nu_credito14 THEN
				vr_nu_credito14 := pn_nu_credito14;
			ELSE
				vr_nu_credito14 := pa_nu_credito14;
			END IF;
		END IF;
		IF pn_ds_aprov15 IS NULL
		AND pa_ds_aprov15 IS NULL THEN
			vr_ds_aprov15 := 'null';
		END IF;
		IF pn_ds_aprov15 IS NULL
		AND pa_ds_aprov15 IS NOT NULL THEN
			vr_ds_aprov15 := 'null';
		END IF;
		IF pn_ds_aprov15 IS NOT NULL
		AND pa_ds_aprov15 IS NULL THEN
			vr_ds_aprov15 := '"' || RTRIM(pn_ds_aprov15) || '"';
		END IF;
		IF pn_ds_aprov15 IS NOT NULL
		AND pa_ds_aprov15 IS NOT NULL THEN
			IF pa_ds_aprov15 <> pn_ds_aprov15 THEN
				vr_ds_aprov15 := '"' || RTRIM(pn_ds_aprov15) || '"';
			ELSE
				vr_ds_aprov15 := '"' || RTRIM(pa_ds_aprov15) || '"';
			END IF;
		END IF;
		IF pn_ctr_import16 IS NULL
		AND pa_ctr_import16 IS NULL THEN
			vr_ctr_import16 := 'null';
		END IF;
		IF pn_ctr_import16 IS NULL
		AND pa_ctr_import16 IS NOT NULL THEN
			vr_ctr_import16 := 'null';
		END IF;
		IF pn_ctr_import16 IS NOT NULL
		AND pa_ctr_import16 IS NULL THEN
			vr_ctr_import16 := '"' || RTRIM(pn_ctr_import16) || '"';
		END IF;
		IF pn_ctr_import16 IS NOT NULL
		AND pa_ctr_import16 IS NOT NULL THEN
			IF pa_ctr_import16 <> pn_ctr_import16 THEN
				vr_ctr_import16 := '"' || RTRIM(pn_ctr_import16) || '"';
			ELSE
				vr_ctr_import16 := '"' || RTRIM(pa_ctr_import16) || '"';
			END IF;
		END IF;
		IF pn_nu_ordem17 IS NULL
		AND pa_nu_ordem17 IS NULL THEN
			vr_nu_ordem17 := 'null';
		END IF;
		IF pn_nu_ordem17 IS NULL
		AND pa_nu_ordem17 IS NOT NULL THEN
			vr_nu_ordem17 := 'null';
		END IF;
		IF pn_nu_ordem17 IS NOT NULL
		AND pa_nu_ordem17 IS NULL THEN
			vr_nu_ordem17 := pn_nu_ordem17;
		END IF;
		IF pn_nu_ordem17 IS NOT NULL
		AND pa_nu_ordem17 IS NOT NULL THEN
			IF pa_nu_ordem17 <> pn_nu_ordem17 THEN
				vr_nu_ordem17 := pn_nu_ordem17;
			ELSE
				vr_nu_ordem17 := pa_nu_ordem17;
			END IF;
		END IF;
		IF pn_tp_obrigatori18 IS NULL
		AND pa_tp_obrigatori18 IS NULL THEN
			vr_tp_obrigatori18 := 'null';
		END IF;
		IF pn_tp_obrigatori18 IS NULL
		AND pa_tp_obrigatori18 IS NOT NULL THEN
			vr_tp_obrigatori18 := 'null';
		END IF;
		IF pn_tp_obrigatori18 IS NOT NULL
		AND pa_tp_obrigatori18 IS NULL THEN
			vr_tp_obrigatori18 := '"' || RTRIM(pn_tp_obrigatori18) || '"';
		END IF;
		IF pn_tp_obrigatori18 IS NOT NULL
		AND pa_tp_obrigatori18 IS NOT NULL THEN
			IF pa_tp_obrigatori18 <> pn_tp_obrigatori18 THEN
				vr_tp_obrigatori18 := '"' || RTRIM(pn_tp_obrigatori18) || '"';
			ELSE
				vr_tp_obrigatori18 := '"' || RTRIM(pa_tp_obrigatori18) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_historico_nota set co_serie = ' || RTRIM(vr_co_serie00) || '  , co_ano_sem = ' || RTRIM(vr_co_ano_sem01) || '  , co_aluno = ' || RTRIM(vr_co_aluno02) || '  , co_unidade = ' || RTRIM(vr_co_unidade03) || '  , co_historico_nota = ' || RTRIM(vr_co_historico_04);
		v_sql2 := '  , co_disciplina = ' || RTRIM(vr_co_disciplina05) || '  , nu_nota_01 = ' || RTRIM(vr_nu_nota_0106) || '  , ds_disciplina = ' || RTRIM(vr_ds_disciplina07) || '  , nu_faltas = ' || RTRIM(vr_nu_faltas08) || '  , nu_nota_02 = ' || RTRIM(vr_nu_nota_0209);
		v_sql3 := '  , nu_carga_horaria = ' || RTRIM(vr_nu_carga_hora10) || '  , nu_aulas_dadas = ' || RTRIM(vr_nu_aulas_dada11) || '  , nu_nota_03 = ' || RTRIM(vr_nu_nota_0312) || '  , tp_disciplina = ' || RTRIM(vr_tp_disciplina13) || '  , nu_credito = ' || RTRIM(vr_nu_credito14);
		v_sql4 := '  , ds_aprov = ' || RTRIM(vr_ds_aprov15) || '  , ctr_import = ' || RTRIM(vr_ctr_import16) || '  , nu_ordem = ' || RTRIM(vr_nu_ordem17) || '  , tp_obrigatoria = ' || RTRIM(vr_tp_obrigatori18);
		v_sql5 := ' where co_serie = ' || RTRIM(vr_co_serie00) || '  and co_ano_sem = ' || RTRIM(vr_co_ano_sem01) || '  and co_aluno = ' || RTRIM(vr_co_aluno02);
		v_sql6 := '  and co_unidade = ' || RTRIM(vr_co_unidade03) || '  and co_historico_nota = ' || RTRIM(vr_co_historico_04) || ';';
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
		       's_historico_nota',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_historic114;
/

