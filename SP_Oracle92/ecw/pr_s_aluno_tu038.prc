CREATE OR REPLACE PROCEDURE pr_s_aluno_tu038(
	P_OP_IN                CHAR,
	PA_co_unidade00_IN     s_aluno_turma.co_unidade%TYPE,
	PA_co_turma01_IN       s_aluno_turma.co_turma%TYPE,
	PA_ano_sem02_IN        s_aluno_turma.ano_sem%TYPE,
	PA_co_aluno03_IN       s_aluno_turma.co_aluno%TYPE,
	PA_co_curso04_IN       s_aluno_turma.co_curso%TYPE,
	PA_dt_movimentac05_IN  s_aluno_turma.dt_movimentacao%TYPE,
	PA_co_seq_serie06_IN   s_aluno_turma.co_seq_serie%TYPE,
	PA_st_movimentac07_IN  s_aluno_turma.st_movimentacao%TYPE,
	PA_ds_movimentac08_IN  s_aluno_turma.ds_movimentacao%TYPE,
	PA_vl_desconto09_IN    s_aluno_turma.vl_desconto%TYPE,
	PA_st_desc_apos_10_IN  s_aluno_turma.st_desc_apos_venc%TYPE,
	PA_nu_primeira_p11_IN  s_aluno_turma.nu_primeira_parc%TYPE,
	PA_dt_vencimento12_IN  s_aluno_turma.dt_vencimento_1%TYPE,
	PA_dt_vencimento13_IN  s_aluno_turma.dt_vencimento_2%TYPE,
	PA_nu_dia_vencim14_IN  s_aluno_turma.nu_dia_vencimento%TYPE,
	PA_st_principal15_IN   s_aluno_turma.st_principal%TYPE,
	PA_co_plano16_IN       s_aluno_turma.co_plano%TYPE,
	PN_co_unidade00_IN     s_aluno_turma.co_unidade%TYPE,
	PN_co_turma01_IN       s_aluno_turma.co_turma%TYPE,
	PN_ano_sem02_IN        s_aluno_turma.ano_sem%TYPE,
	PN_co_aluno03_IN       s_aluno_turma.co_aluno%TYPE,
	PN_co_curso04_IN       s_aluno_turma.co_curso%TYPE,
	PN_dt_movimentac05_IN  s_aluno_turma.dt_movimentacao%TYPE,
	PN_co_seq_serie06_IN   s_aluno_turma.co_seq_serie%TYPE,
	PN_st_movimentac07_IN  s_aluno_turma.st_movimentacao%TYPE,
	PN_ds_movimentac08_IN  s_aluno_turma.ds_movimentacao%TYPE,
	PN_vl_desconto09_IN    s_aluno_turma.vl_desconto%TYPE,
	PN_st_desc_apos_10_IN  s_aluno_turma.st_desc_apos_venc%TYPE,
	PN_nu_primeira_p11_IN  s_aluno_turma.nu_primeira_parc%TYPE,
	PN_dt_vencimento12_IN  s_aluno_turma.dt_vencimento_1%TYPE,
	PN_dt_vencimento13_IN  s_aluno_turma.dt_vencimento_2%TYPE,
	PN_nu_dia_vencim14_IN  s_aluno_turma.nu_dia_vencimento%TYPE,
	PN_st_principal15_IN   s_aluno_turma.st_principal%TYPE,
	PN_co_plano16_IN       s_aluno_turma.co_plano%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_unidade00     s_aluno_turma.co_unidade%TYPE := PA_co_unidade00_IN;
PA_co_turma01       s_aluno_turma.co_turma%TYPE := PA_co_turma01_IN;
PA_ano_sem02        s_aluno_turma.ano_sem%TYPE := PA_ano_sem02_IN;
PA_co_aluno03       s_aluno_turma.co_aluno%TYPE := PA_co_aluno03_IN;
PA_co_curso04       s_aluno_turma.co_curso%TYPE := PA_co_curso04_IN;
PA_dt_movimentac05  s_aluno_turma.dt_movimentacao%TYPE := PA_dt_movimentac05_IN;
PA_co_seq_serie06   s_aluno_turma.co_seq_serie%TYPE := PA_co_seq_serie06_IN;
PA_st_movimentac07  s_aluno_turma.st_movimentacao%TYPE := PA_st_movimentac07_IN;
PA_ds_movimentac08  s_aluno_turma.ds_movimentacao%TYPE := PA_ds_movimentac08_IN;
PA_vl_desconto09    s_aluno_turma.vl_desconto%TYPE := PA_vl_desconto09_IN;
PA_st_desc_apos_10  s_aluno_turma.st_desc_apos_venc%TYPE := PA_st_desc_apos_10_IN;
PA_nu_primeira_p11  s_aluno_turma.nu_primeira_parc%TYPE := PA_nu_primeira_p11_IN;
PA_dt_vencimento12  s_aluno_turma.dt_vencimento_1%TYPE := PA_dt_vencimento12_IN;
PA_dt_vencimento13  s_aluno_turma.dt_vencimento_2%TYPE := PA_dt_vencimento13_IN;
PA_nu_dia_vencim14  s_aluno_turma.nu_dia_vencimento%TYPE := PA_nu_dia_vencim14_IN;
PA_st_principal15   s_aluno_turma.st_principal%TYPE := PA_st_principal15_IN;
PA_co_plano16       s_aluno_turma.co_plano%TYPE := PA_co_plano16_IN;
PN_co_unidade00     s_aluno_turma.co_unidade%TYPE := PN_co_unidade00_IN;
PN_co_turma01       s_aluno_turma.co_turma%TYPE := PN_co_turma01_IN;
PN_ano_sem02        s_aluno_turma.ano_sem%TYPE := PN_ano_sem02_IN;
PN_co_aluno03       s_aluno_turma.co_aluno%TYPE := PN_co_aluno03_IN;
PN_co_curso04       s_aluno_turma.co_curso%TYPE := PN_co_curso04_IN;
PN_dt_movimentac05  s_aluno_turma.dt_movimentacao%TYPE := PN_dt_movimentac05_IN;
PN_co_seq_serie06   s_aluno_turma.co_seq_serie%TYPE := PN_co_seq_serie06_IN;
PN_st_movimentac07  s_aluno_turma.st_movimentacao%TYPE := PN_st_movimentac07_IN;
PN_ds_movimentac08  s_aluno_turma.ds_movimentacao%TYPE := PN_ds_movimentac08_IN;
PN_vl_desconto09    s_aluno_turma.vl_desconto%TYPE := PN_vl_desconto09_IN;
PN_st_desc_apos_10  s_aluno_turma.st_desc_apos_venc%TYPE := PN_st_desc_apos_10_IN;
PN_nu_primeira_p11  s_aluno_turma.nu_primeira_parc%TYPE := PN_nu_primeira_p11_IN;
PN_dt_vencimento12  s_aluno_turma.dt_vencimento_1%TYPE := PN_dt_vencimento12_IN;
PN_dt_vencimento13  s_aluno_turma.dt_vencimento_2%TYPE := PN_dt_vencimento13_IN;
PN_nu_dia_vencim14  s_aluno_turma.nu_dia_vencimento%TYPE := PN_nu_dia_vencim14_IN;
PN_st_principal15   s_aluno_turma.st_principal%TYPE := PN_st_principal15_IN;
PN_co_plano16       s_aluno_turma.co_plano%TYPE := PN_co_plano16_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(500);
v_sql2              CHAR(500);
v_sql3              CHAR(500);
v_sql4              CHAR(500);
v_sql5              CHAR(500);
v_sql6              CHAR(500);
v_uni               CHAR(10);
vr_co_unidade00     CHAR(10);
vr_co_turma01       CHAR(10);
vr_ano_sem02        CHAR(10);
vr_co_aluno03       CHAR(20);
vr_co_curso04       CHAR(10);
vr_dt_movimentac05  CHAR(40);
vr_co_seq_serie06   CHAR(10);
vr_st_movimentac07  CHAR(35);
vr_ds_movimentac08  CHAR(250);
vr_vl_desconto09    CHAR(10);
vr_st_desc_apos_10  CHAR(10);
vr_nu_primeira_p11  CHAR(10);
vr_dt_vencimento12  CHAR(40);
vr_dt_vencimento13  CHAR(40);
vr_nu_dia_vencim14  CHAR(10);
vr_st_principal15   CHAR(10);
vr_co_plano16       CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := pn_co_unidade00;
		END IF;
		IF pn_co_turma01 IS NULL THEN
			vr_co_turma01 := 'null';
		ELSE
			vr_co_turma01 := pn_co_turma01;
		END IF;
		IF pn_ano_sem02 IS NULL THEN
			vr_ano_sem02 := 'null';
		ELSE
			vr_ano_sem02 := pn_ano_sem02;
		END IF;
		IF pn_co_aluno03 IS NULL THEN
			vr_co_aluno03 := 'null';
		ELSE
			vr_co_aluno03 := pn_co_aluno03;
		END IF;
		IF pn_co_curso04 IS NULL THEN
			vr_co_curso04 := 'null';
		ELSE
			vr_co_curso04 := pn_co_curso04;
		END IF;
		IF pn_dt_movimentac05 IS NULL THEN
			vr_dt_movimentac05 := 'null';
		ELSE
			vr_dt_movimentac05 := pn_dt_movimentac05;
		END IF;
		IF pn_co_seq_serie06 IS NULL THEN
			vr_co_seq_serie06 := 'null';
		ELSE
			vr_co_seq_serie06 := pn_co_seq_serie06;
		END IF;
		IF pn_st_movimentac07 IS NULL THEN
			vr_st_movimentac07 := 'null';
		ELSE
			vr_st_movimentac07 := pn_st_movimentac07;
		END IF;
		IF pn_ds_movimentac08 IS NULL THEN
			vr_ds_movimentac08 := 'null';
		ELSE
			vr_ds_movimentac08 := pn_ds_movimentac08;
		END IF;
		IF pn_vl_desconto09 IS NULL THEN
			vr_vl_desconto09 := 'null';
		ELSE
			vr_vl_desconto09 := pn_vl_desconto09;
		END IF;
		IF pn_st_desc_apos_10 IS NULL THEN
			vr_st_desc_apos_10 := 'null';
		ELSE
			vr_st_desc_apos_10 := pn_st_desc_apos_10;
		END IF;
		IF pn_nu_primeira_p11 IS NULL THEN
			vr_nu_primeira_p11 := 'null';
		ELSE
			vr_nu_primeira_p11 := pn_nu_primeira_p11;
		END IF;
		IF pn_dt_vencimento12 IS NULL THEN
			vr_dt_vencimento12 := 'null';
		ELSE
			vr_dt_vencimento12 := pn_dt_vencimento12;
		END IF;
		IF pn_dt_vencimento13 IS NULL THEN
			vr_dt_vencimento13 := 'null';
		ELSE
			vr_dt_vencimento13 := pn_dt_vencimento13;
		END IF;
		IF pn_nu_dia_vencim14 IS NULL THEN
			vr_nu_dia_vencim14 := 'null';
		ELSE
			vr_nu_dia_vencim14 := pn_nu_dia_vencim14;
		END IF;
		IF pn_st_principal15 IS NULL THEN
			vr_st_principal15 := 'null';
		ELSE
			vr_st_principal15 := pn_st_principal15;
		END IF;
		IF pn_co_plano16 IS NULL THEN
			vr_co_plano16 := 'null';
		ELSE
			vr_co_plano16 := pn_co_plano16;
		END IF;
		v_sql1 := 'insert into s_aluno_turma(co_unidade, co_turma, ano_sem, co_aluno, co_curso, dt_movimentacao, co_seq_serie, st_movimentacao, ' || 'ds_movimentacao, vl_desconto, ST_DESCONTO_APOS_VENC, NU_PRIMEIRA_PARCELA, dt_vencimento_1, dt_vencimento_2, nu_dia_vencimento, st_principal, co_plano) values (';
		v_sql2 := '"' || RTRIM(vr_co_unidade00) || '"' || ',' || RTRIM(vr_co_turma01) || ',' || '"' || RTRIM(vr_ano_sem02) || '"' || ',' || '"' || RTRIM(vr_co_aluno03) || '"' || ',' || RTRIM(vr_co_curso04) || ',';
		v_sql3 := '"' || vr_dt_movimentac05 || '"' || ',' || RTRIM(vr_co_seq_serie06) || ',' || '"' || RTRIM(vr_st_movimentac07) || '"' || ',' || '"' || RTRIM(vr_ds_movimentac08) || '"' || ',' || RTRIM(vr_vl_desconto09) || ',' || '"' || RTRIM(vr_st_desc_apos_10) || '"' || ',';
		v_sql4 := '"' || RTRIM(vr_nu_primeira_p11) || '"' || ',' || '"' || vr_dt_vencimento12 || '"' || ',' || '"' || vr_dt_vencimento13 || '"' || ',' || '"' || RTRIM(vr_nu_dia_vencim14) || '"' || ',';
		v_sql5 := '"' || RTRIM(vr_st_principal15) || '"' || ',' || RTRIM(vr_co_plano16) || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5;
	ELSIF p_op = 'del' THEN
		IF pa_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := '"' || RTRIM(pa_co_unidade00) || '"';
		END IF;
		IF pa_co_turma01 IS NULL THEN
			vr_co_turma01 := 'null';
		ELSE
			vr_co_turma01 := pa_co_turma01;
		END IF;
		IF pa_ano_sem02 IS NULL THEN
			vr_ano_sem02 := 'null';
		ELSE
			vr_ano_sem02 := '"' || RTRIM(pa_ano_sem02) || '"';
		END IF;
		IF pa_co_aluno03 IS NULL THEN
			vr_co_aluno03 := 'null';
		ELSE
			vr_co_aluno03 := '"' || RTRIM(pa_co_aluno03) || '"';
		END IF;
		IF pa_co_curso04 IS NULL THEN
			vr_co_curso04 := 'null';
		ELSE
			vr_co_curso04 := pa_co_curso04;
		END IF;
		IF pa_co_seq_serie06 IS NULL THEN
			vr_co_seq_serie06 := 'null';
		ELSE
			vr_co_seq_serie06 := pa_co_seq_serie06;
		END IF;
		v_sql1 := '  delete from s_aluno_turma where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and co_turma = ' || RTRIM(vr_co_turma01) || '  and ano_sem = ' || RTRIM(vr_ano_sem02) || '  and co_aluno = ' || RTRIM(vr_co_aluno03) || '  and co_curso = ' || RTRIM(vr_co_curso04) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie06) || ';';
		v_sql2 := '';
		v_sql3 := '';
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
		IF pn_co_turma01 IS NULL
		AND pa_co_turma01 IS NULL THEN
			vr_co_turma01 := 'null';
		END IF;
		IF pn_co_turma01 IS NULL
		AND pa_co_turma01 IS NOT NULL THEN
			vr_co_turma01 := 'null';
		END IF;
		IF pn_co_turma01 IS NOT NULL
		AND pa_co_turma01 IS NULL THEN
			vr_co_turma01 := pn_co_turma01;
		END IF;
		IF pn_co_turma01 IS NOT NULL
		AND pa_co_turma01 IS NOT NULL THEN
			IF pa_co_turma01 <> pn_co_turma01 THEN
				vr_co_turma01 := pn_co_turma01;
			ELSE
				vr_co_turma01 := pa_co_turma01;
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
		IF pn_co_aluno03 IS NULL
		AND pa_co_aluno03 IS NULL THEN
			vr_co_aluno03 := 'null';
		END IF;
		IF pn_co_aluno03 IS NULL
		AND pa_co_aluno03 IS NOT NULL THEN
			vr_co_aluno03 := 'null';
		END IF;
		IF pn_co_aluno03 IS NOT NULL
		AND pa_co_aluno03 IS NULL THEN
			vr_co_aluno03 := '"' || RTRIM(pn_co_aluno03) || '"';
		END IF;
		IF pn_co_aluno03 IS NOT NULL
		AND pa_co_aluno03 IS NOT NULL THEN
			IF pa_co_aluno03 <> pn_co_aluno03 THEN
				vr_co_aluno03 := '"' || RTRIM(pn_co_aluno03) || '"';
			ELSE
				vr_co_aluno03 := '"' || RTRIM(pa_co_aluno03) || '"';
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
		IF pn_dt_movimentac05 IS NULL
		AND pa_dt_movimentac05 IS NULL THEN
			vr_dt_movimentac05 := 'null';
		END IF;
		IF pn_dt_movimentac05 IS NULL
		AND pa_dt_movimentac05 IS NOT NULL THEN
			vr_dt_movimentac05 := 'null';
		END IF;
		IF pn_dt_movimentac05 IS NOT NULL
		AND pa_dt_movimentac05 IS NULL THEN
			vr_dt_movimentac05 := '"' || pn_dt_movimentac05 || '"';
		END IF;
		IF pn_dt_movimentac05 IS NOT NULL
		AND pa_dt_movimentac05 IS NOT NULL THEN
			IF pa_dt_movimentac05 <> pn_dt_movimentac05 THEN
				vr_dt_movimentac05 := '"' || pn_dt_movimentac05 || '"';
			ELSE
				vr_dt_movimentac05 := '"' || pa_dt_movimentac05 || '"';
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
		IF pn_st_movimentac07 IS NULL
		AND pa_st_movimentac07 IS NULL THEN
			vr_st_movimentac07 := 'null';
		END IF;
		IF pn_st_movimentac07 IS NULL
		AND pa_st_movimentac07 IS NOT NULL THEN
			vr_st_movimentac07 := 'null';
		END IF;
		IF pn_st_movimentac07 IS NOT NULL
		AND pa_st_movimentac07 IS NULL THEN
			vr_st_movimentac07 := '"' || RTRIM(pn_st_movimentac07) || '"';
		END IF;
		IF pn_st_movimentac07 IS NOT NULL
		AND pa_st_movimentac07 IS NOT NULL THEN
			IF pa_st_movimentac07 <> pn_st_movimentac07 THEN
				vr_st_movimentac07 := '"' || RTRIM(pn_st_movimentac07) || '"';
			ELSE
				vr_st_movimentac07 := '"' || RTRIM(pa_st_movimentac07) || '"';
			END IF;
		END IF;
		IF pn_ds_movimentac08 IS NULL
		AND pa_ds_movimentac08 IS NULL THEN
			vr_ds_movimentac08 := 'null';
		END IF;
		IF pn_ds_movimentac08 IS NULL
		AND pa_ds_movimentac08 IS NOT NULL THEN
			vr_ds_movimentac08 := 'null';
		END IF;
		IF pn_ds_movimentac08 IS NOT NULL
		AND pa_ds_movimentac08 IS NULL THEN
			vr_ds_movimentac08 := '"' || RTRIM(pn_ds_movimentac08) || '"';
		END IF;
		IF pn_ds_movimentac08 IS NOT NULL
		AND pa_ds_movimentac08 IS NOT NULL THEN
			IF pa_ds_movimentac08 <> pn_ds_movimentac08 THEN
				vr_ds_movimentac08 := '"' || RTRIM(pn_ds_movimentac08) || '"';
			ELSE
				vr_ds_movimentac08 := '"' || RTRIM(pa_ds_movimentac08) || '"';
			END IF;
		END IF;
		IF pn_vl_desconto09 IS NULL
		AND pa_vl_desconto09 IS NULL THEN
			vr_vl_desconto09 := 'null';
		END IF;
		IF pn_vl_desconto09 IS NULL
		AND pa_vl_desconto09 IS NOT NULL THEN
			vr_vl_desconto09 := 'null';
		END IF;
		IF pn_vl_desconto09 IS NOT NULL
		AND pa_vl_desconto09 IS NULL THEN
			vr_vl_desconto09 := pn_vl_desconto09;
		END IF;
		IF pn_vl_desconto09 IS NOT NULL
		AND pa_vl_desconto09 IS NOT NULL THEN
			IF pa_vl_desconto09 <> pn_vl_desconto09 THEN
				vr_vl_desconto09 := pn_vl_desconto09;
			ELSE
				vr_vl_desconto09 := pa_vl_desconto09;
			END IF;
		END IF;
		IF pn_st_desc_apos_10 IS NULL
		AND pa_st_desc_apos_10 IS NULL THEN
			vr_st_desc_apos_10 := 'null';
		END IF;
		IF pn_st_desc_apos_10 IS NULL
		AND pa_st_desc_apos_10 IS NOT NULL THEN
			vr_st_desc_apos_10 := 'null';
		END IF;
		IF pn_st_desc_apos_10 IS NOT NULL
		AND pa_st_desc_apos_10 IS NULL THEN
			vr_st_desc_apos_10 := '"' || RTRIM(pn_st_desc_apos_10) || '"';
		END IF;
		IF pn_st_desc_apos_10 IS NOT NULL
		AND pa_st_desc_apos_10 IS NOT NULL THEN
			IF pa_st_desc_apos_10 <> pn_st_desc_apos_10 THEN
				vr_st_desc_apos_10 := '"' || RTRIM(pn_st_desc_apos_10) || '"';
			ELSE
				vr_st_desc_apos_10 := '"' || RTRIM(pa_st_desc_apos_10) || '"';
			END IF;
		END IF;
		IF pn_nu_primeira_p11 IS NULL
		AND pa_nu_primeira_p11 IS NULL THEN
			vr_nu_primeira_p11 := 'null';
		END IF;
		IF pn_nu_primeira_p11 IS NULL
		AND pa_nu_primeira_p11 IS NOT NULL THEN
			vr_nu_primeira_p11 := 'null';
		END IF;
		IF pn_nu_primeira_p11 IS NOT NULL
		AND pa_nu_primeira_p11 IS NULL THEN
			vr_nu_primeira_p11 := '"' || RTRIM(pn_nu_primeira_p11) || '"';
		END IF;
		IF pn_nu_primeira_p11 IS NOT NULL
		AND pa_nu_primeira_p11 IS NOT NULL THEN
			IF pa_nu_primeira_p11 <> pn_nu_primeira_p11 THEN
				vr_nu_primeira_p11 := '"' || RTRIM(pn_nu_primeira_p11) || '"';
			ELSE
				vr_nu_primeira_p11 := '"' || RTRIM(pa_nu_primeira_p11) || '"';
			END IF;
		END IF;
		IF pn_dt_vencimento12 IS NULL
		AND pa_dt_vencimento12 IS NULL THEN
			vr_dt_vencimento12 := 'null';
		END IF;
		IF pn_dt_vencimento12 IS NULL
		AND pa_dt_vencimento12 IS NOT NULL THEN
			vr_dt_vencimento12 := 'null';
		END IF;
		IF pn_dt_vencimento12 IS NOT NULL
		AND pa_dt_vencimento12 IS NULL THEN
			vr_dt_vencimento12 := '"' || pn_dt_vencimento12 || '"';
		END IF;
		IF pn_dt_vencimento12 IS NOT NULL
		AND pa_dt_vencimento12 IS NOT NULL THEN
			IF pa_dt_vencimento12 <> pn_dt_vencimento12 THEN
				vr_dt_vencimento12 := '"' || pn_dt_vencimento12 || '"';
			ELSE
				vr_dt_vencimento12 := '"' || pa_dt_vencimento12 || '"';
			END IF;
		END IF;
		IF pn_dt_vencimento13 IS NULL
		AND pa_dt_vencimento13 IS NULL THEN
			vr_dt_vencimento13 := 'null';
		END IF;
		IF pn_dt_vencimento13 IS NULL
		AND pa_dt_vencimento13 IS NOT NULL THEN
			vr_dt_vencimento13 := 'null';
		END IF;
		IF pn_dt_vencimento13 IS NOT NULL
		AND pa_dt_vencimento13 IS NULL THEN
			vr_dt_vencimento13 := '"' || pn_dt_vencimento13 || '"';
		END IF;
		IF pn_dt_vencimento13 IS NOT NULL
		AND pa_dt_vencimento13 IS NOT NULL THEN
			IF pa_dt_vencimento13 <> pn_dt_vencimento13 THEN
				vr_dt_vencimento13 := '"' || pn_dt_vencimento13 || '"';
			ELSE
				vr_dt_vencimento13 := '"' || pa_dt_vencimento13 || '"';
			END IF;
		END IF;
		IF pn_nu_dia_vencim14 IS NULL
		AND pa_nu_dia_vencim14 IS NULL THEN
			vr_nu_dia_vencim14 := 'null';
		END IF;
		IF pn_nu_dia_vencim14 IS NULL
		AND pa_nu_dia_vencim14 IS NOT NULL THEN
			vr_nu_dia_vencim14 := 'null';
		END IF;
		IF pn_nu_dia_vencim14 IS NOT NULL
		AND pa_nu_dia_vencim14 IS NULL THEN
			vr_nu_dia_vencim14 := '"' || RTRIM(pn_nu_dia_vencim14) || '"';
		END IF;
		IF pn_nu_dia_vencim14 IS NOT NULL
		AND pa_nu_dia_vencim14 IS NOT NULL THEN
			IF pa_nu_dia_vencim14 <> pn_nu_dia_vencim14 THEN
				vr_nu_dia_vencim14 := '"' || RTRIM(pn_nu_dia_vencim14) || '"';
			ELSE
				vr_nu_dia_vencim14 := '"' || RTRIM(pa_nu_dia_vencim14) || '"';
			END IF;
		END IF;
		IF pn_st_principal15 IS NULL
		AND pa_st_principal15 IS NULL THEN
			vr_st_principal15 := 'null';
		END IF;
		IF pn_st_principal15 IS NULL
		AND pa_st_principal15 IS NOT NULL THEN
			vr_st_principal15 := 'null';
		END IF;
		IF pn_st_principal15 IS NOT NULL
		AND pa_st_principal15 IS NULL THEN
			vr_st_principal15 := '"' || RTRIM(pn_st_principal15) || '"';
		END IF;
		IF pn_st_principal15 IS NOT NULL
		AND pa_st_principal15 IS NOT NULL THEN
			IF pa_st_principal15 <> pn_st_principal15 THEN
				vr_st_principal15 := '"' || RTRIM(pn_st_principal15) || '"';
			ELSE
				vr_st_principal15 := '"' || RTRIM(pa_st_principal15) || '"';
			END IF;
		END IF;
		IF pn_co_plano16 IS NULL
		AND pa_co_plano16 IS NULL THEN
			vr_co_plano16 := 'null';
		END IF;
		IF pn_co_plano16 IS NULL
		AND pa_co_plano16 IS NOT NULL THEN
			vr_co_plano16 := 'null';
		END IF;
		IF pn_co_plano16 IS NOT NULL
		AND pa_co_plano16 IS NULL THEN
			vr_co_plano16 := pn_co_plano16;
		END IF;
		IF pn_co_plano16 IS NOT NULL
		AND pa_co_plano16 IS NOT NULL THEN
			IF pa_co_plano16 <> pn_co_plano16 THEN
				vr_co_plano16 := pn_co_plano16;
			ELSE
				vr_co_plano16 := pa_co_plano16;
			END IF;
		END IF;
		v_sql1 := 'update s_aluno_turma set co_unidade = ' || RTRIM(vr_co_unidade00) || '  , co_turma = ' || RTRIM(vr_co_turma01) || '  , ano_sem = ' || RTRIM(vr_ano_sem02) || '  , co_aluno = ' || RTRIM(vr_co_aluno03) || '  , co_curso = ' || RTRIM(vr_co_curso04);
		v_sql2 := '  , dt_movimentacao = ' || RTRIM(vr_dt_movimentac05) || '  , co_seq_serie = ' || RTRIM(vr_co_seq_serie06) || '  , st_movimentacao = ' || RTRIM(vr_st_movimentac07) || '  , ds_movimentacao = ' || RTRIM(vr_ds_movimentac08) || '  , vl_desconto = ' || RTRIM(vr_vl_desconto09);
		v_sql3 := '  , ST_DESCONTO_APOS_VENC = ' || RTRIM(vr_st_desc_apos_10) || '  , NU_PRIMEIRA_PARCELA = ' || RTRIM(vr_nu_primeira_p11) || '  , dt_vencimento_1 = ' || RTRIM(vr_dt_vencimento12);
		v_sql4 := '  , dt_vencimento_2 = ' || RTRIM(vr_dt_vencimento13) || '  , nu_dia_vencimento = ' || RTRIM(vr_nu_dia_vencim14) || '  , st_principal = ' || RTRIM(vr_st_principal15) || '  , co_plano = ' || RTRIM(vr_co_plano16);
		v_sql5 := ' where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and co_turma = ' || RTRIM(vr_co_turma01) || '  and ano_sem = ' || RTRIM(vr_ano_sem02);
		v_sql6 := '  and co_aluno = ' || RTRIM(vr_co_aluno03) || '  and co_curso = ' || RTRIM(vr_co_curso04) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie06) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5 || v_sql6;
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
		       's_aluno_turma',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_aluno_tu038;
/

