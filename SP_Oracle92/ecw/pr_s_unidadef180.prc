CREATE OR REPLACE PROCEDURE pr_s_unidadef180(
	P_OP_IN                CHAR,
	PA_co_funcionari00_IN  s_unidadefunc.co_funcionario%TYPE,
	PA_ano_sem01_IN        s_unidadefunc.ano_sem%TYPE,
	PA_co_unidade02_IN     s_unidadefunc.co_unidade%TYPE,
	PA_co_cargo03_IN       s_unidadefunc.co_cargo%TYPE,
	PA_nu_carga_cont04_IN  s_unidadefunc.nu_carga_contrato%TYPE,
	PA_nu_hora_entra05_IN  s_unidadefunc.nu_hora_entrada%TYPE,
	PA_nu_hora_ini_a06_IN  s_unidadefunc.nu_hora_ini_almoc%TYPE,
	PA_nu_hora_fim_a07_IN  s_unidadefunc.nu_hora_fim_almoc%TYPE,
	PA_nu_hora_saida08_IN  s_unidadefunc.nu_hora_saida%TYPE,
	PA_st_altera_not09_IN  s_unidadefunc.st_altera_notas%TYPE,
	PA_ds_ficha_pess10_IN  s_unidadefunc.ds_ficha_pessoal%TYPE,
	PA_ds_senha11_IN       s_unidadefunc.ds_senha%TYPE,
	PA_nivel_salaria12_IN  s_unidadefunc.nivel_salarial%TYPE,
	PA_id_professor13_IN   s_unidadefunc.id_professor%TYPE,
	PA_co_area_atuac14_IN  s_unidadefunc.co_area_atuacao%TYPE,
	PA_st_cancelado15_IN   s_unidadefunc.st_cancelado%TYPE,
	PA_dt_admissao16_IN    s_unidadefunc.dt_admissao%TYPE,
	PN_co_funcionari00_IN  s_unidadefunc.co_funcionario%TYPE,
	PN_ano_sem01_IN        s_unidadefunc.ano_sem%TYPE,
	PN_co_unidade02_IN     s_unidadefunc.co_unidade%TYPE,
	PN_co_cargo03_IN       s_unidadefunc.co_cargo%TYPE,
	PN_nu_carga_cont04_IN  s_unidadefunc.nu_carga_contrato%TYPE,
	PN_nu_hora_entra05_IN  s_unidadefunc.nu_hora_entrada%TYPE,
	PN_nu_hora_ini_a06_IN  s_unidadefunc.nu_hora_ini_almoc%TYPE,
	PN_nu_hora_fim_a07_IN  s_unidadefunc.nu_hora_fim_almoc%TYPE,
	PN_nu_hora_saida08_IN  s_unidadefunc.nu_hora_saida%TYPE,
	PN_st_altera_not09_IN  s_unidadefunc.st_altera_notas%TYPE,
	PN_ds_ficha_pess10_IN  s_unidadefunc.ds_ficha_pessoal%TYPE,
	PN_ds_senha11_IN       s_unidadefunc.ds_senha%TYPE,
	PN_nivel_salaria12_IN  s_unidadefunc.nivel_salarial%TYPE,
	PN_id_professor13_IN   s_unidadefunc.id_professor%TYPE,
	PN_co_area_atuac14_IN  s_unidadefunc.co_area_atuacao%TYPE,
	PN_st_cancelado15_IN   s_unidadefunc.st_cancelado%TYPE,
	PN_dt_admissao16_IN    s_unidadefunc.dt_admissao%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_funcionari00  s_unidadefunc.co_funcionario%TYPE := PA_co_funcionari00_IN;
PA_ano_sem01        s_unidadefunc.ano_sem%TYPE := PA_ano_sem01_IN;
PA_co_unidade02     s_unidadefunc.co_unidade%TYPE := PA_co_unidade02_IN;
PA_co_cargo03       s_unidadefunc.co_cargo%TYPE := PA_co_cargo03_IN;
PA_nu_carga_cont04  s_unidadefunc.nu_carga_contrato%TYPE := PA_nu_carga_cont04_IN;
PA_nu_hora_entra05  s_unidadefunc.nu_hora_entrada%TYPE := PA_nu_hora_entra05_IN;
PA_nu_hora_ini_a06  s_unidadefunc.nu_hora_ini_almoc%TYPE := PA_nu_hora_ini_a06_IN;
PA_nu_hora_fim_a07  s_unidadefunc.nu_hora_fim_almoc%TYPE := PA_nu_hora_fim_a07_IN;
PA_nu_hora_saida08  s_unidadefunc.nu_hora_saida%TYPE := PA_nu_hora_saida08_IN;
PA_st_altera_not09  s_unidadefunc.st_altera_notas%TYPE := PA_st_altera_not09_IN;
PA_ds_ficha_pess10  s_unidadefunc.ds_ficha_pessoal%TYPE := PA_ds_ficha_pess10_IN;
PA_ds_senha11       s_unidadefunc.ds_senha%TYPE := PA_ds_senha11_IN;
PA_nivel_salaria12  s_unidadefunc.nivel_salarial%TYPE := PA_nivel_salaria12_IN;
PA_id_professor13   s_unidadefunc.id_professor%TYPE := PA_id_professor13_IN;
PA_co_area_atuac14  s_unidadefunc.co_area_atuacao%TYPE := PA_co_area_atuac14_IN;
PA_st_cancelado15   s_unidadefunc.st_cancelado%TYPE := PA_st_cancelado15_IN;
PA_dt_admissao16    s_unidadefunc.dt_admissao%TYPE := PA_dt_admissao16_IN;
PN_co_funcionari00  s_unidadefunc.co_funcionario%TYPE := PN_co_funcionari00_IN;
PN_ano_sem01        s_unidadefunc.ano_sem%TYPE := PN_ano_sem01_IN;
PN_co_unidade02     s_unidadefunc.co_unidade%TYPE := PN_co_unidade02_IN;
PN_co_cargo03       s_unidadefunc.co_cargo%TYPE := PN_co_cargo03_IN;
PN_nu_carga_cont04  s_unidadefunc.nu_carga_contrato%TYPE := PN_nu_carga_cont04_IN;
PN_nu_hora_entra05  s_unidadefunc.nu_hora_entrada%TYPE := PN_nu_hora_entra05_IN;
PN_nu_hora_ini_a06  s_unidadefunc.nu_hora_ini_almoc%TYPE := PN_nu_hora_ini_a06_IN;
PN_nu_hora_fim_a07  s_unidadefunc.nu_hora_fim_almoc%TYPE := PN_nu_hora_fim_a07_IN;
PN_nu_hora_saida08  s_unidadefunc.nu_hora_saida%TYPE := PN_nu_hora_saida08_IN;
PN_st_altera_not09  s_unidadefunc.st_altera_notas%TYPE := PN_st_altera_not09_IN;
PN_ds_ficha_pess10  s_unidadefunc.ds_ficha_pessoal%TYPE := PN_ds_ficha_pess10_IN;
PN_ds_senha11       s_unidadefunc.ds_senha%TYPE := PN_ds_senha11_IN;
PN_nivel_salaria12  s_unidadefunc.nivel_salarial%TYPE := PN_nivel_salaria12_IN;
PN_id_professor13   s_unidadefunc.id_professor%TYPE := PN_id_professor13_IN;
PN_co_area_atuac14  s_unidadefunc.co_area_atuacao%TYPE := PN_co_area_atuac14_IN;
PN_st_cancelado15   s_unidadefunc.st_cancelado%TYPE := PN_st_cancelado15_IN;
PN_dt_admissao16    s_unidadefunc.dt_admissao%TYPE := PN_dt_admissao16_IN;
v_blob1             s_unidadefunc.ds_ficha_pessoal%TYPE;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(500);
v_sql2              CHAR(500);
v_sql3              CHAR(500);
v_sql4              CHAR(500);
v_sql5              CHAR(500);
v_sql6              CHAR(500);
v_uni               CHAR(10);
vr_co_funcionari00  CHAR(100);
vr_ano_sem01        CHAR(10);
vr_co_unidade02     CHAR(10);
vr_co_cargo03       CHAR(30);
vr_nu_carga_cont04  CHAR(10);
vr_nu_hora_entra05  CHAR(10);
vr_nu_hora_ini_a06  CHAR(10);
vr_nu_hora_fim_a07  CHAR(10);
vr_nu_hora_saida08  CHAR(10);
vr_st_altera_not09  CHAR(10);
vr_ds_ficha_pess10  CHAR(10);
vr_ds_senha11       CHAR(10);
vr_nivel_salaria12  CHAR(10);
vr_id_professor13   CHAR(10);
vr_co_area_atuac14  CHAR(10);
vr_st_cancelado15   CHAR(10);
vr_dt_admissao16    CHAR(40);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	v_blob1 := NULL;
	IF p_op = 'ins' THEN
		IF pn_co_funcionari00 IS NULL THEN
			vr_co_funcionari00 := 'null';
		ELSE
			vr_co_funcionari00 := pn_co_funcionari00;
		END IF;
		IF pn_ano_sem01 IS NULL THEN
			vr_ano_sem01 := 'null';
		ELSE
			vr_ano_sem01 := pn_ano_sem01;
		END IF;
		IF pn_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := pn_co_unidade02;
		END IF;
		IF pn_co_cargo03 IS NULL THEN
			vr_co_cargo03 := 'null';
		ELSE
			vr_co_cargo03 := pn_co_cargo03;
		END IF;
		IF pn_nu_carga_cont04 IS NULL THEN
			vr_nu_carga_cont04 := 'null';
		ELSE
			vr_nu_carga_cont04 := pn_nu_carga_cont04;
		END IF;
		IF pn_nu_hora_entra05 IS NULL THEN
			vr_nu_hora_entra05 := 'null';
		ELSE
			vr_nu_hora_entra05 := pn_nu_hora_entra05;
		END IF;
		IF pn_nu_hora_ini_a06 IS NULL THEN
			vr_nu_hora_ini_a06 := 'null';
		ELSE
			vr_nu_hora_ini_a06 := pn_nu_hora_ini_a06;
		END IF;
		IF pn_nu_hora_fim_a07 IS NULL THEN
			vr_nu_hora_fim_a07 := 'null';
		ELSE
			vr_nu_hora_fim_a07 := pn_nu_hora_fim_a07;
		END IF;
		IF pn_nu_hora_saida08 IS NULL THEN
			vr_nu_hora_saida08 := 'null';
		ELSE
			vr_nu_hora_saida08 := pn_nu_hora_saida08;
		END IF;
		IF pn_st_altera_not09 IS NULL THEN
			vr_st_altera_not09 := 'null';
		ELSE
			vr_st_altera_not09 := pn_st_altera_not09;
		END IF;
		IF pn_ds_ficha_pess10 IS NULL THEN
			vr_ds_ficha_pess10 := NULL;
		ELSE
			vr_ds_ficha_pess10 := ':vblob1';
		END IF;
		v_blob1 := pn_ds_ficha_pess10;
		IF pn_ds_senha11 IS NULL THEN
			vr_ds_senha11 := 'null';
		ELSE
			vr_ds_senha11 := pn_ds_senha11;
		END IF;
		IF pn_nivel_salaria12 IS NULL THEN
			vr_nivel_salaria12 := 'null';
		ELSE
			vr_nivel_salaria12 := pn_nivel_salaria12;
		END IF;
		IF pn_id_professor13 IS NULL THEN
			vr_id_professor13 := 'null';
		ELSE
			vr_id_professor13 := pn_id_professor13;
		END IF;
		IF pn_co_area_atuac14 IS NULL THEN
			vr_co_area_atuac14 := 'null';
		ELSE
			vr_co_area_atuac14 := pn_co_area_atuac14;
		END IF;
		IF pn_st_cancelado15 IS NULL THEN
			vr_st_cancelado15 := 'null';
		ELSE
			vr_st_cancelado15 := pn_st_cancelado15;
		END IF;
		IF pn_dt_admissao16 IS NULL THEN
			vr_dt_admissao16 := 'null';
		ELSE
			vr_dt_admissao16 := pn_dt_admissao16;
		END IF;
		v_sql1 := 'insert into S_UNIDADEFUNCIONARIO(co_funcionario, ano_sem, co_unidade, co_cargo, nu_carga_contrato, nu_hora_entrada, NU_HORA_INICIO_ALMOCO, NU_HORA_FIM_ALMOCO, ';
		v_sql2 := 'nu_hora_saida, st_altera_notas, ds_ficha_pessoal, ds_senha, nivel_salarial, id_professor, ' || 'co_area_atuacao, st_cancelado, dt_admissao) values (';
		v_sql3 := '"' || RTRIM(vr_co_funcionari00) || '"' || ',' || '"' || RTRIM(vr_ano_sem01) || '"' || ',' || '"' || RTRIM(vr_co_unidade02) || '"' || ',' || '"' || RTRIM(vr_co_cargo03) || '"' || ',' || RTRIM(vr_nu_carga_cont04) || ',';
		v_sql4 := '"' || RTRIM(vr_nu_hora_entra05) || '"' || ',' || '"' || RTRIM(vr_nu_hora_ini_a06) || '"' || ',' || '"' || RTRIM(vr_nu_hora_fim_a07) || '"' || ',' || '"' || RTRIM(vr_nu_hora_saida08) || '"' || ',';
		v_sql5 := '"' || RTRIM(vr_st_altera_not09) || '"' || ',' || RTRIM(vr_ds_ficha_pess10) || ',' || '"' || RTRIM(vr_ds_senha11) || '"' || ',' || '"' || RTRIM(vr_nivel_salaria12) || '"' || ',';
		v_sql6 := '"' || RTRIM(vr_id_professor13) || '"' || ',' || RTRIM(vr_co_area_atuac14) || ',' || '"' || RTRIM(vr_st_cancelado15) || '"' || ',' || '"' || vr_dt_admissao16 || '"' || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5 || v_sql6;
	ELSIF p_op = 'del' THEN
		IF pa_co_funcionari00 IS NULL THEN
			vr_co_funcionari00 := 'null';
		ELSE
			vr_co_funcionari00 := '"' || RTRIM(pa_co_funcionari00) || '"';
		END IF;
		IF pa_ano_sem01 IS NULL THEN
			vr_ano_sem01 := 'null';
		ELSE
			vr_ano_sem01 := '"' || RTRIM(pa_ano_sem01) || '"';
		END IF;
		IF pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := '"' || RTRIM(pa_co_unidade02) || '"';
		END IF;
		v_sql1 := '  delete from S_UNIDADEFUNCIONARIO where co_funcionario = ' || RTRIM(vr_co_funcionari00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_funcionari00 IS NULL
		AND pa_co_funcionari00 IS NULL THEN
			vr_co_funcionari00 := 'null';
		END IF;
		IF pn_co_funcionari00 IS NULL
		AND pa_co_funcionari00 IS NOT NULL THEN
			vr_co_funcionari00 := 'null';
		END IF;
		IF pn_co_funcionari00 IS NOT NULL
		AND pa_co_funcionari00 IS NULL THEN
			vr_co_funcionari00 := '"' || RTRIM(pn_co_funcionari00) || '"';
		END IF;
		IF pn_co_funcionari00 IS NOT NULL
		AND pa_co_funcionari00 IS NOT NULL THEN
			IF pa_co_funcionari00 <> pn_co_funcionari00 THEN
				vr_co_funcionari00 := '"' || RTRIM(pn_co_funcionari00) || '"';
			ELSE
				vr_co_funcionari00 := '"' || RTRIM(pa_co_funcionari00) || '"';
			END IF;
		END IF;
		IF pn_ano_sem01 IS NULL
		AND pa_ano_sem01 IS NULL THEN
			vr_ano_sem01 := 'null';
		END IF;
		IF pn_ano_sem01 IS NULL
		AND pa_ano_sem01 IS NOT NULL THEN
			vr_ano_sem01 := 'null';
		END IF;
		IF pn_ano_sem01 IS NOT NULL
		AND pa_ano_sem01 IS NULL THEN
			vr_ano_sem01 := '"' || RTRIM(pn_ano_sem01) || '"';
		END IF;
		IF pn_ano_sem01 IS NOT NULL
		AND pa_ano_sem01 IS NOT NULL THEN
			IF pa_ano_sem01 <> pn_ano_sem01 THEN
				vr_ano_sem01 := '"' || RTRIM(pn_ano_sem01) || '"';
			ELSE
				vr_ano_sem01 := '"' || RTRIM(pa_ano_sem01) || '"';
			END IF;
		END IF;
		IF pn_co_unidade02 IS NULL
		AND pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		END IF;
		IF pn_co_unidade02 IS NULL
		AND pa_co_unidade02 IS NOT NULL THEN
			vr_co_unidade02 := 'null';
		END IF;
		IF pn_co_unidade02 IS NOT NULL
		AND pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := '"' || RTRIM(pn_co_unidade02) || '"';
		END IF;
		IF pn_co_unidade02 IS NOT NULL
		AND pa_co_unidade02 IS NOT NULL THEN
			IF pa_co_unidade02 <> pn_co_unidade02 THEN
				vr_co_unidade02 := '"' || RTRIM(pn_co_unidade02) || '"';
			ELSE
				vr_co_unidade02 := '"' || RTRIM(pa_co_unidade02) || '"';
			END IF;
		END IF;
		IF pn_co_cargo03 IS NULL
		AND pa_co_cargo03 IS NULL THEN
			vr_co_cargo03 := 'null';
		END IF;
		IF pn_co_cargo03 IS NULL
		AND pa_co_cargo03 IS NOT NULL THEN
			vr_co_cargo03 := 'null';
		END IF;
		IF pn_co_cargo03 IS NOT NULL
		AND pa_co_cargo03 IS NULL THEN
			vr_co_cargo03 := '"' || RTRIM(pn_co_cargo03) || '"';
		END IF;
		IF pn_co_cargo03 IS NOT NULL
		AND pa_co_cargo03 IS NOT NULL THEN
			IF pa_co_cargo03 <> pn_co_cargo03 THEN
				vr_co_cargo03 := '"' || RTRIM(pn_co_cargo03) || '"';
			ELSE
				vr_co_cargo03 := '"' || RTRIM(pa_co_cargo03) || '"';
			END IF;
		END IF;
		IF pn_nu_carga_cont04 IS NULL
		AND pa_nu_carga_cont04 IS NULL THEN
			vr_nu_carga_cont04 := 'null';
		END IF;
		IF pn_nu_carga_cont04 IS NULL
		AND pa_nu_carga_cont04 IS NOT NULL THEN
			vr_nu_carga_cont04 := 'null';
		END IF;
		IF pn_nu_carga_cont04 IS NOT NULL
		AND pa_nu_carga_cont04 IS NULL THEN
			vr_nu_carga_cont04 := pn_nu_carga_cont04;
		END IF;
		IF pn_nu_carga_cont04 IS NOT NULL
		AND pa_nu_carga_cont04 IS NOT NULL THEN
			IF pa_nu_carga_cont04 <> pn_nu_carga_cont04 THEN
				vr_nu_carga_cont04 := pn_nu_carga_cont04;
			ELSE
				vr_nu_carga_cont04 := pa_nu_carga_cont04;
			END IF;
		END IF;
		IF pn_nu_hora_entra05 IS NULL
		AND pa_nu_hora_entra05 IS NULL THEN
			vr_nu_hora_entra05 := 'null';
		END IF;
		IF pn_nu_hora_entra05 IS NULL
		AND pa_nu_hora_entra05 IS NOT NULL THEN
			vr_nu_hora_entra05 := 'null';
		END IF;
		IF pn_nu_hora_entra05 IS NOT NULL
		AND pa_nu_hora_entra05 IS NULL THEN
			vr_nu_hora_entra05 := '"' || RTRIM(pn_nu_hora_entra05) || '"';
		END IF;
		IF pn_nu_hora_entra05 IS NOT NULL
		AND pa_nu_hora_entra05 IS NOT NULL THEN
			IF pa_nu_hora_entra05 <> pn_nu_hora_entra05 THEN
				vr_nu_hora_entra05 := '"' || RTRIM(pn_nu_hora_entra05) || '"';
			ELSE
				vr_nu_hora_entra05 := '"' || RTRIM(pa_nu_hora_entra05) || '"';
			END IF;
		END IF;
		IF pn_nu_hora_ini_a06 IS NULL
		AND pa_nu_hora_ini_a06 IS NULL THEN
			vr_nu_hora_ini_a06 := 'null';
		END IF;
		IF pn_nu_hora_ini_a06 IS NULL
		AND pa_nu_hora_ini_a06 IS NOT NULL THEN
			vr_nu_hora_ini_a06 := 'null';
		END IF;
		IF pn_nu_hora_ini_a06 IS NOT NULL
		AND pa_nu_hora_ini_a06 IS NULL THEN
			vr_nu_hora_ini_a06 := '"' || RTRIM(pn_nu_hora_ini_a06) || '"';
		END IF;
		IF pn_nu_hora_ini_a06 IS NOT NULL
		AND pa_nu_hora_ini_a06 IS NOT NULL THEN
			IF pa_nu_hora_ini_a06 <> pn_nu_hora_ini_a06 THEN
				vr_nu_hora_ini_a06 := '"' || RTRIM(pn_nu_hora_ini_a06) || '"';
			ELSE
				vr_nu_hora_ini_a06 := '"' || RTRIM(pa_nu_hora_ini_a06) || '"';
			END IF;
		END IF;
		IF pn_nu_hora_fim_a07 IS NULL
		AND pa_nu_hora_fim_a07 IS NULL THEN
			vr_nu_hora_fim_a07 := 'null';
		END IF;
		IF pn_nu_hora_fim_a07 IS NULL
		AND pa_nu_hora_fim_a07 IS NOT NULL THEN
			vr_nu_hora_fim_a07 := 'null';
		END IF;
		IF pn_nu_hora_fim_a07 IS NOT NULL
		AND pa_nu_hora_fim_a07 IS NULL THEN
			vr_nu_hora_fim_a07 := '"' || RTRIM(pn_nu_hora_fim_a07) || '"';
		END IF;
		IF pn_nu_hora_fim_a07 IS NOT NULL
		AND pa_nu_hora_fim_a07 IS NOT NULL THEN
			IF pa_nu_hora_fim_a07 <> pn_nu_hora_fim_a07 THEN
				vr_nu_hora_fim_a07 := '"' || RTRIM(pn_nu_hora_fim_a07) || '"';
			ELSE
				vr_nu_hora_fim_a07 := '"' || RTRIM(pa_nu_hora_fim_a07) || '"';
			END IF;
		END IF;
		IF pn_nu_hora_saida08 IS NULL
		AND pa_nu_hora_saida08 IS NULL THEN
			vr_nu_hora_saida08 := 'null';
		END IF;
		IF pn_nu_hora_saida08 IS NULL
		AND pa_nu_hora_saida08 IS NOT NULL THEN
			vr_nu_hora_saida08 := 'null';
		END IF;
		IF pn_nu_hora_saida08 IS NOT NULL
		AND pa_nu_hora_saida08 IS NULL THEN
			vr_nu_hora_saida08 := '"' || RTRIM(pn_nu_hora_saida08) || '"';
		END IF;
		IF pn_nu_hora_saida08 IS NOT NULL
		AND pa_nu_hora_saida08 IS NOT NULL THEN
			IF pa_nu_hora_saida08 <> pn_nu_hora_saida08 THEN
				vr_nu_hora_saida08 := '"' || RTRIM(pn_nu_hora_saida08) || '"';
			ELSE
				vr_nu_hora_saida08 := '"' || RTRIM(pa_nu_hora_saida08) || '"';
			END IF;
		END IF;
		IF pn_st_altera_not09 IS NULL
		AND pa_st_altera_not09 IS NULL THEN
			vr_st_altera_not09 := 'null';
		END IF;
		IF pn_st_altera_not09 IS NULL
		AND pa_st_altera_not09 IS NOT NULL THEN
			vr_st_altera_not09 := 'null';
		END IF;
		IF pn_st_altera_not09 IS NOT NULL
		AND pa_st_altera_not09 IS NULL THEN
			vr_st_altera_not09 := '"' || RTRIM(pn_st_altera_not09) || '"';
		END IF;
		IF pn_st_altera_not09 IS NOT NULL
		AND pa_st_altera_not09 IS NOT NULL THEN
			IF pa_st_altera_not09 <> pn_st_altera_not09 THEN
				vr_st_altera_not09 := '"' || RTRIM(pn_st_altera_not09) || '"';
			ELSE
				vr_st_altera_not09 := '"' || RTRIM(pa_st_altera_not09) || '"';
			END IF;
		END IF;
		IF pn_ds_ficha_pess10 IS NULL THEN
			vr_ds_ficha_pess10 := NULL;
		ELSE
			vr_ds_ficha_pess10 := ':vblob1';
		END IF;
		v_blob1 := pn_ds_ficha_pess10;
		IF pn_ds_senha11 IS NULL
		AND pa_ds_senha11 IS NULL THEN
			vr_ds_senha11 := 'null';
		END IF;
		IF pn_ds_senha11 IS NULL
		AND pa_ds_senha11 IS NOT NULL THEN
			vr_ds_senha11 := 'null';
		END IF;
		IF pn_ds_senha11 IS NOT NULL
		AND pa_ds_senha11 IS NULL THEN
			vr_ds_senha11 := '"' || RTRIM(pn_ds_senha11) || '"';
		END IF;
		IF pn_ds_senha11 IS NOT NULL
		AND pa_ds_senha11 IS NOT NULL THEN
			IF pa_ds_senha11 <> pn_ds_senha11 THEN
				vr_ds_senha11 := '"' || RTRIM(pn_ds_senha11) || '"';
			ELSE
				vr_ds_senha11 := '"' || RTRIM(pa_ds_senha11) || '"';
			END IF;
		END IF;
		IF pn_nivel_salaria12 IS NULL
		AND pa_nivel_salaria12 IS NULL THEN
			vr_nivel_salaria12 := 'null';
		END IF;
		IF pn_nivel_salaria12 IS NULL
		AND pa_nivel_salaria12 IS NOT NULL THEN
			vr_nivel_salaria12 := 'null';
		END IF;
		IF pn_nivel_salaria12 IS NOT NULL
		AND pa_nivel_salaria12 IS NULL THEN
			vr_nivel_salaria12 := '"' || RTRIM(pn_nivel_salaria12) || '"';
		END IF;
		IF pn_nivel_salaria12 IS NOT NULL
		AND pa_nivel_salaria12 IS NOT NULL THEN
			IF pa_nivel_salaria12 <> pn_nivel_salaria12 THEN
				vr_nivel_salaria12 := '"' || RTRIM(pn_nivel_salaria12) || '"';
			ELSE
				vr_nivel_salaria12 := '"' || RTRIM(pa_nivel_salaria12) || '"';
			END IF;
		END IF;
		IF pn_id_professor13 IS NULL
		AND pa_id_professor13 IS NULL THEN
			vr_id_professor13 := 'null';
		END IF;
		IF pn_id_professor13 IS NULL
		AND pa_id_professor13 IS NOT NULL THEN
			vr_id_professor13 := 'null';
		END IF;
		IF pn_id_professor13 IS NOT NULL
		AND pa_id_professor13 IS NULL THEN
			vr_id_professor13 := '"' || RTRIM(pn_id_professor13) || '"';
		END IF;
		IF pn_id_professor13 IS NOT NULL
		AND pa_id_professor13 IS NOT NULL THEN
			IF pa_id_professor13 <> pn_id_professor13 THEN
				vr_id_professor13 := '"' || RTRIM(pn_id_professor13) || '"';
			ELSE
				vr_id_professor13 := '"' || RTRIM(pa_id_professor13) || '"';
			END IF;
		END IF;
		IF pn_co_area_atuac14 IS NULL
		AND pa_co_area_atuac14 IS NULL THEN
			vr_co_area_atuac14 := 'null';
		END IF;
		IF pn_co_area_atuac14 IS NULL
		AND pa_co_area_atuac14 IS NOT NULL THEN
			vr_co_area_atuac14 := 'null';
		END IF;
		IF pn_co_area_atuac14 IS NOT NULL
		AND pa_co_area_atuac14 IS NULL THEN
			vr_co_area_atuac14 := pn_co_area_atuac14;
		END IF;
		IF pn_co_area_atuac14 IS NOT NULL
		AND pa_co_area_atuac14 IS NOT NULL THEN
			IF pa_co_area_atuac14 <> pn_co_area_atuac14 THEN
				vr_co_area_atuac14 := pn_co_area_atuac14;
			ELSE
				vr_co_area_atuac14 := pa_co_area_atuac14;
			END IF;
		END IF;
		IF pn_st_cancelado15 IS NULL
		AND pa_st_cancelado15 IS NULL THEN
			vr_st_cancelado15 := 'null';
		END IF;
		IF pn_st_cancelado15 IS NULL
		AND pa_st_cancelado15 IS NOT NULL THEN
			vr_st_cancelado15 := 'null';
		END IF;
		IF pn_st_cancelado15 IS NOT NULL
		AND pa_st_cancelado15 IS NULL THEN
			vr_st_cancelado15 := '"' || RTRIM(pn_st_cancelado15) || '"';
		END IF;
		IF pn_st_cancelado15 IS NOT NULL
		AND pa_st_cancelado15 IS NOT NULL THEN
			IF pa_st_cancelado15 <> pn_st_cancelado15 THEN
				vr_st_cancelado15 := '"' || RTRIM(pn_st_cancelado15) || '"';
			ELSE
				vr_st_cancelado15 := '"' || RTRIM(pa_st_cancelado15) || '"';
			END IF;
		END IF;
		IF pn_dt_admissao16 IS NULL
		AND pa_dt_admissao16 IS NULL THEN
			vr_dt_admissao16 := 'null';
		END IF;
		IF pn_dt_admissao16 IS NULL
		AND pa_dt_admissao16 IS NOT NULL THEN
			vr_dt_admissao16 := 'null';
		END IF;
		IF pn_dt_admissao16 IS NOT NULL
		AND pa_dt_admissao16 IS NULL THEN
			vr_dt_admissao16 := '"' || pn_dt_admissao16 || '"';
		END IF;
		IF pn_dt_admissao16 IS NOT NULL
		AND pa_dt_admissao16 IS NOT NULL THEN
			IF pa_dt_admissao16 <> pn_dt_admissao16 THEN
				vr_dt_admissao16 := '"' || pn_dt_admissao16 || '"';
			ELSE
				vr_dt_admissao16 := '"' || pa_dt_admissao16 || '"';
			END IF;
		END IF;
		v_sql1 := 'update S_UNIDADEFUNCIONARIO set co_funcionario = ' || RTRIM(vr_co_funcionari00) || '  , ano_sem = ' || RTRIM(vr_ano_sem01) || '  , co_unidade = ' || RTRIM(vr_co_unidade02) || '  , co_cargo = ' || RTRIM(vr_co_cargo03);
		v_sql2 := '  , nu_carga_contrato = ' || RTRIM(vr_nu_carga_cont04) || '  , nu_hora_entrada = ' || RTRIM(vr_nu_hora_entra05) || '  , NU_HORA_INICIO_ALMOCO = ' || RTRIM(vr_nu_hora_ini_a06) || '  , NU_HORA_FIM_ALMOCO = ' || RTRIM(vr_nu_hora_fim_a07);
		v_sql3 := '  , nu_hora_saida = ' || RTRIM(vr_nu_hora_saida08) || '  , st_altera_notas = ' || RTRIM(vr_st_altera_not09) || '  , ds_ficha_pessoal = ' || RTRIM(vr_ds_ficha_pess10) || '  , ds_senha = ' || RTRIM(vr_ds_senha11);
		v_sql4 := '  , nivel_salarial = ' || RTRIM(vr_nivel_salaria12) || '  , id_professor = ' || RTRIM(vr_id_professor13) || '  , co_area_atuacao = ' || RTRIM(vr_co_area_atuac14);
		v_sql5 := '  , st_cancelado = ' || RTRIM(vr_st_cancelado15) || '  , dt_admissao = ' || RTRIM(vr_dt_admissao16);
		v_sql6 := ' where co_funcionario = ' || RTRIM(vr_co_funcionari00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5 || v_sql6;
	END IF;
	IF p_op = 'del' THEN
		v_uni := pa_co_unidade02;
	ELSE
		v_uni := pn_co_unidade02;
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
		       's_unidadefunc',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       v_blob1,
		       NULL);
	END IF;
END pr_s_unidadef180;
/

