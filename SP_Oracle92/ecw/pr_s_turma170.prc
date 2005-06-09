CREATE OR REPLACE PROCEDURE pr_s_turma170(
	P_OP_IN                CHAR,
	PA_co_unidade00_IN     s_turma.co_unidade%TYPE,
	PA_co_turma01_IN       s_turma.co_turma%TYPE,
	PA_ano_sem02_IN        s_turma.ano_sem%TYPE,
	PA_co_curso03_IN       s_turma.co_curso%TYPE,
	PA_co_grau04_IN        s_turma.co_grau%TYPE,
	PA_co_turno05_IN       s_turma.co_turno%TYPE,
	PA_co_seq_serie06_IN   s_turma.co_seq_serie%TYPE,
	PA_co_letra_turm07_IN  s_turma.co_letra_turma%TYPE,
	PA_co_bloco08_IN       s_turma.co_bloco%TYPE,
	PA_ds_turma09_IN       s_turma.ds_turma%TYPE,
	PA_st_turma_defi10_IN  s_turma.st_turma_definitiv%TYPE,
	PA_nu_maximo_alu11_IN  s_turma.nu_maximo_aluno%TYPE,
	PA_co_tipo_horar12_IN  s_turma.co_tipo_horario%TYPE,
	PA_co_turma_proc13_IN  s_turma.co_turma_procura%TYPE,
	PA_st_laboratori14_IN  s_turma.st_laboratorio%TYPE,
	PA_disc_origem15_IN    s_turma.disc_origem%TYPE,
	PA_fo_turma16_IN       s_turma.fo_turma%TYPE,
	PA_co_sala17_IN        s_turma.co_sala%TYPE,
	PN_co_unidade00_IN     s_turma.co_unidade%TYPE,
	PN_co_turma01_IN       s_turma.co_turma%TYPE,
	PN_ano_sem02_IN        s_turma.ano_sem%TYPE,
	PN_co_curso03_IN       s_turma.co_curso%TYPE,
	PN_co_grau04_IN        s_turma.co_grau%TYPE,
	PN_co_turno05_IN       s_turma.co_turno%TYPE,
	PN_co_seq_serie06_IN   s_turma.co_seq_serie%TYPE,
	PN_co_letra_turm07_IN  s_turma.co_letra_turma%TYPE,
	PN_co_bloco08_IN       s_turma.co_bloco%TYPE,
	PN_ds_turma09_IN       s_turma.ds_turma%TYPE,
	PN_st_turma_defi10_IN  s_turma.st_turma_definitiv%TYPE,
	PN_nu_maximo_alu11_IN  s_turma.nu_maximo_aluno%TYPE,
	PN_co_tipo_horar12_IN  s_turma.co_tipo_horario%TYPE,
	PN_co_turma_proc13_IN  s_turma.co_turma_procura%TYPE,
	PN_st_laboratori14_IN  s_turma.st_laboratorio%TYPE,
	PN_disc_origem15_IN    s_turma.disc_origem%TYPE,
	PN_fo_turma16_IN       s_turma.fo_turma%TYPE,
	PN_co_sala17_IN        s_turma.co_sala%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_unidade00     s_turma.co_unidade%TYPE := PA_co_unidade00_IN;
PA_co_turma01       s_turma.co_turma%TYPE := PA_co_turma01_IN;
PA_ano_sem02        s_turma.ano_sem%TYPE := PA_ano_sem02_IN;
PA_co_curso03       s_turma.co_curso%TYPE := PA_co_curso03_IN;
PA_co_grau04        s_turma.co_grau%TYPE := PA_co_grau04_IN;
PA_co_turno05       s_turma.co_turno%TYPE := PA_co_turno05_IN;
PA_co_seq_serie06   s_turma.co_seq_serie%TYPE := PA_co_seq_serie06_IN;
PA_co_letra_turm07  s_turma.co_letra_turma%TYPE := PA_co_letra_turm07_IN;
PA_co_bloco08       s_turma.co_bloco%TYPE := PA_co_bloco08_IN;
PA_ds_turma09       s_turma.ds_turma%TYPE := PA_ds_turma09_IN;
PA_st_turma_defi10  s_turma.st_turma_definitiv%TYPE := PA_st_turma_defi10_IN;
PA_nu_maximo_alu11  s_turma.nu_maximo_aluno%TYPE := PA_nu_maximo_alu11_IN;
PA_co_tipo_horar12  s_turma.co_tipo_horario%TYPE := PA_co_tipo_horar12_IN;
PA_co_turma_proc13  s_turma.co_turma_procura%TYPE := PA_co_turma_proc13_IN;
PA_st_laboratori14  s_turma.st_laboratorio%TYPE := PA_st_laboratori14_IN;
PA_disc_origem15    s_turma.disc_origem%TYPE := PA_disc_origem15_IN;
PA_fo_turma16       s_turma.fo_turma%TYPE := PA_fo_turma16_IN;
PA_co_sala17        s_turma.co_sala%TYPE := PA_co_sala17_IN;
PN_co_unidade00     s_turma.co_unidade%TYPE := PN_co_unidade00_IN;
PN_co_turma01       s_turma.co_turma%TYPE := PN_co_turma01_IN;
PN_ano_sem02        s_turma.ano_sem%TYPE := PN_ano_sem02_IN;
PN_co_curso03       s_turma.co_curso%TYPE := PN_co_curso03_IN;
PN_co_grau04        s_turma.co_grau%TYPE := PN_co_grau04_IN;
PN_co_turno05       s_turma.co_turno%TYPE := PN_co_turno05_IN;
PN_co_seq_serie06   s_turma.co_seq_serie%TYPE := PN_co_seq_serie06_IN;
PN_co_letra_turm07  s_turma.co_letra_turma%TYPE := PN_co_letra_turm07_IN;
PN_co_bloco08       s_turma.co_bloco%TYPE := PN_co_bloco08_IN;
PN_ds_turma09       s_turma.ds_turma%TYPE := PN_ds_turma09_IN;
PN_st_turma_defi10  s_turma.st_turma_definitiv%TYPE := PN_st_turma_defi10_IN;
PN_nu_maximo_alu11  s_turma.nu_maximo_aluno%TYPE := PN_nu_maximo_alu11_IN;
PN_co_tipo_horar12  s_turma.co_tipo_horario%TYPE := PN_co_tipo_horar12_IN;
PN_co_turma_proc13  s_turma.co_turma_procura%TYPE := PN_co_turma_proc13_IN;
PN_st_laboratori14  s_turma.st_laboratorio%TYPE := PN_st_laboratori14_IN;
PN_disc_origem15    s_turma.disc_origem%TYPE := PN_disc_origem15_IN;
PN_fo_turma16       s_turma.fo_turma%TYPE := PN_fo_turma16_IN;
PN_co_sala17        s_turma.co_sala%TYPE := PN_co_sala17_IN;
v_blob1             s_turma.fo_turma%TYPE;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(750);
v_sql2              CHAR(750);
v_sql3              CHAR(750);
v_sql4              CHAR(750);
v_uni               CHAR(10);
vr_co_unidade00     CHAR(10);
vr_co_turma01       CHAR(10);
vr_ano_sem02        CHAR(10);
vr_co_curso03       CHAR(10);
vr_co_grau04        CHAR(10);
vr_co_turno05       CHAR(10);
vr_co_seq_serie06   CHAR(10);
vr_co_letra_turm07  CHAR(10);
vr_co_bloco08       CHAR(10);
vr_ds_turma09       CHAR(40);
vr_st_turma_defi10  CHAR(10);
vr_nu_maximo_alu11  CHAR(10);
vr_co_tipo_horar12  CHAR(10);
vr_co_turma_proc13  CHAR(10);
vr_st_laboratori14  CHAR(10);
vr_disc_origem15    CHAR(10);
vr_fo_turma16       CHAR(10);
vr_co_sala17        CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	v_blob1 := NULL;
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
		IF pn_co_curso03 IS NULL THEN
			vr_co_curso03 := 'null';
		ELSE
			vr_co_curso03 := pn_co_curso03;
		END IF;
		IF pn_co_grau04 IS NULL THEN
			vr_co_grau04 := 'null';
		ELSE
			vr_co_grau04 := pn_co_grau04;
		END IF;
		IF pn_co_turno05 IS NULL THEN
			vr_co_turno05 := 'null';
		ELSE
			vr_co_turno05 := pn_co_turno05;
		END IF;
		IF pn_co_seq_serie06 IS NULL THEN
			vr_co_seq_serie06 := 'null';
		ELSE
			vr_co_seq_serie06 := pn_co_seq_serie06;
		END IF;
		IF pn_co_letra_turm07 IS NULL THEN
			vr_co_letra_turm07 := 'null';
		ELSE
			vr_co_letra_turm07 := pn_co_letra_turm07;
		END IF;
		IF pn_co_bloco08 IS NULL THEN
			vr_co_bloco08 := 'null';
		ELSE
			vr_co_bloco08 := pn_co_bloco08;
		END IF;
		IF pn_ds_turma09 IS NULL THEN
			vr_ds_turma09 := 'null';
		ELSE
			vr_ds_turma09 := pn_ds_turma09;
		END IF;
		IF pn_st_turma_defi10 IS NULL THEN
			vr_st_turma_defi10 := 'null';
		ELSE
			vr_st_turma_defi10 := pn_st_turma_defi10;
		END IF;
		IF pn_nu_maximo_alu11 IS NULL THEN
			vr_nu_maximo_alu11 := 'null';
		ELSE
			vr_nu_maximo_alu11 := pn_nu_maximo_alu11;
		END IF;
		IF pn_co_tipo_horar12 IS NULL THEN
			vr_co_tipo_horar12 := 'null';
		ELSE
			vr_co_tipo_horar12 := pn_co_tipo_horar12;
		END IF;
		IF pn_co_turma_proc13 IS NULL THEN
			vr_co_turma_proc13 := 'null';
		ELSE
			vr_co_turma_proc13 := pn_co_turma_proc13;
		END IF;
		IF pn_st_laboratori14 IS NULL THEN
			vr_st_laboratori14 := 'null';
		ELSE
			vr_st_laboratori14 := pn_st_laboratori14;
		END IF;
		IF pn_disc_origem15 IS NULL THEN
			vr_disc_origem15 := 'null';
		ELSE
			vr_disc_origem15 := pn_disc_origem15;
		END IF;
		IF pn_fo_turma16 IS NULL THEN
			vr_fo_turma16 := NULL;
		ELSE
			vr_fo_turma16 := ':vblob1';
		END IF;
		v_blob1 := pn_fo_turma16;
		IF pn_co_sala17 IS NULL THEN
			vr_co_sala17 := 'null';
		ELSE
			vr_co_sala17 := pn_co_sala17;
		END IF;
		v_sql1 := 'insert into s_turma(co_unidade, co_turma, ano_sem, co_curso, co_grau, co_turno, co_seq_serie, co_letra_turma, co_bloco, ds_turma, ' || 'ST_TURMA_DEFINITIVA, nu_maximo_aluno, co_tipo_horario, co_turma_procura, st_laboratorio, disc_origem, fo_turma, co_sala) values (';
		v_sql2 := '"' || RTRIM(vr_co_unidade00) || '"' || ',' || RTRIM(vr_co_turma01) || ',' || '"' || RTRIM(vr_ano_sem02) || '"' || ',' || RTRIM(vr_co_curso03) || ',' || '"' || RTRIM(vr_co_grau04) || '"' || ',' || '"' || RTRIM(vr_co_turno05) || '"' || ',' || RTRIM(vr_co_seq_serie06) || ',';
		v_sql3 := '"' || RTRIM(vr_co_letra_turm07) || '"' || ',' || '"' || RTRIM(vr_co_bloco08) || '"' || ',' || '"' || RTRIM(vr_ds_turma09) || '"' || ',' || '"' || RTRIM(vr_st_turma_defi10) || '"' || ',' || '"' || RTRIM(vr_nu_maximo_alu11) || '"' || ',' || RTRIM(vr_co_tipo_horar12) || ',' || '"' || RTRIM(vr_co_turma_proc13) || '"' || ',';
		v_sql4 := '"' || RTRIM(vr_st_laboratori14) || '"' || ',' || '"' || RTRIM(vr_disc_origem15) || '"' || ',' || RTRIM(vr_fo_turma16) || ',' || '"' || RTRIM(vr_co_sala17) || '"' || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4;
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
		IF pa_co_curso03 IS NULL THEN
			vr_co_curso03 := 'null';
		ELSE
			vr_co_curso03 := pa_co_curso03;
		END IF;
		IF pa_co_seq_serie06 IS NULL THEN
			vr_co_seq_serie06 := 'null';
		ELSE
			vr_co_seq_serie06 := pa_co_seq_serie06;
		END IF;
		v_sql1 := '  delete from s_turma where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and co_turma = ' || RTRIM(vr_co_turma01) || '  and ano_sem = ' || RTRIM(vr_ano_sem02);
		v_sql2 := '  and co_curso = ' || RTRIM(vr_co_curso03) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie06) || ';';
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
		IF pn_co_grau04 IS NULL
		AND pa_co_grau04 IS NULL THEN
			vr_co_grau04 := 'null';
		END IF;
		IF pn_co_grau04 IS NULL
		AND pa_co_grau04 IS NOT NULL THEN
			vr_co_grau04 := 'null';
		END IF;
		IF pn_co_grau04 IS NOT NULL
		AND pa_co_grau04 IS NULL THEN
			vr_co_grau04 := '"' || RTRIM(pn_co_grau04) || '"';
		END IF;
		IF pn_co_grau04 IS NOT NULL
		AND pa_co_grau04 IS NOT NULL THEN
			IF pa_co_grau04 <> pn_co_grau04 THEN
				vr_co_grau04 := '"' || RTRIM(pn_co_grau04) || '"';
			ELSE
				vr_co_grau04 := '"' || RTRIM(pa_co_grau04) || '"';
			END IF;
		END IF;
		IF pn_co_turno05 IS NULL
		AND pa_co_turno05 IS NULL THEN
			vr_co_turno05 := 'null';
		END IF;
		IF pn_co_turno05 IS NULL
		AND pa_co_turno05 IS NOT NULL THEN
			vr_co_turno05 := 'null';
		END IF;
		IF pn_co_turno05 IS NOT NULL
		AND pa_co_turno05 IS NULL THEN
			vr_co_turno05 := '"' || RTRIM(pn_co_turno05) || '"';
		END IF;
		IF pn_co_turno05 IS NOT NULL
		AND pa_co_turno05 IS NOT NULL THEN
			IF pa_co_turno05 <> pn_co_turno05 THEN
				vr_co_turno05 := '"' || RTRIM(pn_co_turno05) || '"';
			ELSE
				vr_co_turno05 := '"' || RTRIM(pa_co_turno05) || '"';
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
		IF pn_co_letra_turm07 IS NULL
		AND pa_co_letra_turm07 IS NULL THEN
			vr_co_letra_turm07 := 'null';
		END IF;
		IF pn_co_letra_turm07 IS NULL
		AND pa_co_letra_turm07 IS NOT NULL THEN
			vr_co_letra_turm07 := 'null';
		END IF;
		IF pn_co_letra_turm07 IS NOT NULL
		AND pa_co_letra_turm07 IS NULL THEN
			vr_co_letra_turm07 := '"' || RTRIM(pn_co_letra_turm07) || '"';
		END IF;
		IF pn_co_letra_turm07 IS NOT NULL
		AND pa_co_letra_turm07 IS NOT NULL THEN
			IF pa_co_letra_turm07 <> pn_co_letra_turm07 THEN
				vr_co_letra_turm07 := '"' || RTRIM(pn_co_letra_turm07) || '"';
			ELSE
				vr_co_letra_turm07 := '"' || RTRIM(pa_co_letra_turm07) || '"';
			END IF;
		END IF;
		IF pn_co_bloco08 IS NULL
		AND pa_co_bloco08 IS NULL THEN
			vr_co_bloco08 := 'null';
		END IF;
		IF pn_co_bloco08 IS NULL
		AND pa_co_bloco08 IS NOT NULL THEN
			vr_co_bloco08 := 'null';
		END IF;
		IF pn_co_bloco08 IS NOT NULL
		AND pa_co_bloco08 IS NULL THEN
			vr_co_bloco08 := '"' || RTRIM(pn_co_bloco08) || '"';
		END IF;
		IF pn_co_bloco08 IS NOT NULL
		AND pa_co_bloco08 IS NOT NULL THEN
			IF pa_co_bloco08 <> pn_co_bloco08 THEN
				vr_co_bloco08 := '"' || RTRIM(pn_co_bloco08) || '"';
			ELSE
				vr_co_bloco08 := '"' || RTRIM(pa_co_bloco08) || '"';
			END IF;
		END IF;
		IF pn_ds_turma09 IS NULL
		AND pa_ds_turma09 IS NULL THEN
			vr_ds_turma09 := 'null';
		END IF;
		IF pn_ds_turma09 IS NULL
		AND pa_ds_turma09 IS NOT NULL THEN
			vr_ds_turma09 := 'null';
		END IF;
		IF pn_ds_turma09 IS NOT NULL
		AND pa_ds_turma09 IS NULL THEN
			vr_ds_turma09 := '"' || RTRIM(pn_ds_turma09) || '"';
		END IF;
		IF pn_ds_turma09 IS NOT NULL
		AND pa_ds_turma09 IS NOT NULL THEN
			IF pa_ds_turma09 <> pn_ds_turma09 THEN
				vr_ds_turma09 := '"' || RTRIM(pn_ds_turma09) || '"';
			ELSE
				vr_ds_turma09 := '"' || RTRIM(pa_ds_turma09) || '"';
			END IF;
		END IF;
		IF pn_st_turma_defi10 IS NULL
		AND pa_st_turma_defi10 IS NULL THEN
			vr_st_turma_defi10 := 'null';
		END IF;
		IF pn_st_turma_defi10 IS NULL
		AND pa_st_turma_defi10 IS NOT NULL THEN
			vr_st_turma_defi10 := 'null';
		END IF;
		IF pn_st_turma_defi10 IS NOT NULL
		AND pa_st_turma_defi10 IS NULL THEN
			vr_st_turma_defi10 := '"' || RTRIM(pn_st_turma_defi10) || '"';
		END IF;
		IF pn_st_turma_defi10 IS NOT NULL
		AND pa_st_turma_defi10 IS NOT NULL THEN
			IF pa_st_turma_defi10 <> pn_st_turma_defi10 THEN
				vr_st_turma_defi10 := '"' || RTRIM(pn_st_turma_defi10) || '"';
			ELSE
				vr_st_turma_defi10 := '"' || RTRIM(pa_st_turma_defi10) || '"';
			END IF;
		END IF;
		IF pn_nu_maximo_alu11 IS NULL
		AND pa_nu_maximo_alu11 IS NULL THEN
			vr_nu_maximo_alu11 := 'null';
		END IF;
		IF pn_nu_maximo_alu11 IS NULL
		AND pa_nu_maximo_alu11 IS NOT NULL THEN
			vr_nu_maximo_alu11 := 'null';
		END IF;
		IF pn_nu_maximo_alu11 IS NOT NULL
		AND pa_nu_maximo_alu11 IS NULL THEN
			vr_nu_maximo_alu11 := '"' || RTRIM(pn_nu_maximo_alu11) || '"';
		END IF;
		IF pn_nu_maximo_alu11 IS NOT NULL
		AND pa_nu_maximo_alu11 IS NOT NULL THEN
			IF pa_nu_maximo_alu11 <> pn_nu_maximo_alu11 THEN
				vr_nu_maximo_alu11 := '"' || RTRIM(pn_nu_maximo_alu11) || '"';
			ELSE
				vr_nu_maximo_alu11 := '"' || RTRIM(pa_nu_maximo_alu11) || '"';
			END IF;
		END IF;
		IF pn_co_tipo_horar12 IS NULL
		AND pa_co_tipo_horar12 IS NULL THEN
			vr_co_tipo_horar12 := 'null';
		END IF;
		IF pn_co_tipo_horar12 IS NULL
		AND pa_co_tipo_horar12 IS NOT NULL THEN
			vr_co_tipo_horar12 := 'null';
		END IF;
		IF pn_co_tipo_horar12 IS NOT NULL
		AND pa_co_tipo_horar12 IS NULL THEN
			vr_co_tipo_horar12 := pn_co_tipo_horar12;
		END IF;
		IF pn_co_tipo_horar12 IS NOT NULL
		AND pa_co_tipo_horar12 IS NOT NULL THEN
			IF pa_co_tipo_horar12 <> pn_co_tipo_horar12 THEN
				vr_co_tipo_horar12 := pn_co_tipo_horar12;
			ELSE
				vr_co_tipo_horar12 := pa_co_tipo_horar12;
			END IF;
		END IF;
		IF pn_co_turma_proc13 IS NULL
		AND pa_co_turma_proc13 IS NULL THEN
			vr_co_turma_proc13 := 'null';
		END IF;
		IF pn_co_turma_proc13 IS NULL
		AND pa_co_turma_proc13 IS NOT NULL THEN
			vr_co_turma_proc13 := 'null';
		END IF;
		IF pn_co_turma_proc13 IS NOT NULL
		AND pa_co_turma_proc13 IS NULL THEN
			vr_co_turma_proc13 := '"' || RTRIM(pn_co_turma_proc13) || '"';
		END IF;
		IF pn_co_turma_proc13 IS NOT NULL
		AND pa_co_turma_proc13 IS NOT NULL THEN
			IF pa_co_turma_proc13 <> pn_co_turma_proc13 THEN
				vr_co_turma_proc13 := '"' || RTRIM(pn_co_turma_proc13) || '"';
			ELSE
				vr_co_turma_proc13 := '"' || RTRIM(pa_co_turma_proc13) || '"';
			END IF;
		END IF;
		IF pn_st_laboratori14 IS NULL
		AND pa_st_laboratori14 IS NULL THEN
			vr_st_laboratori14 := 'null';
		END IF;
		IF pn_st_laboratori14 IS NULL
		AND pa_st_laboratori14 IS NOT NULL THEN
			vr_st_laboratori14 := 'null';
		END IF;
		IF pn_st_laboratori14 IS NOT NULL
		AND pa_st_laboratori14 IS NULL THEN
			vr_st_laboratori14 := '"' || RTRIM(pn_st_laboratori14) || '"';
		END IF;
		IF pn_st_laboratori14 IS NOT NULL
		AND pa_st_laboratori14 IS NOT NULL THEN
			IF pa_st_laboratori14 <> pn_st_laboratori14 THEN
				vr_st_laboratori14 := '"' || RTRIM(pn_st_laboratori14) || '"';
			ELSE
				vr_st_laboratori14 := '"' || RTRIM(pa_st_laboratori14) || '"';
			END IF;
		END IF;
		IF pn_disc_origem15 IS NULL
		AND pa_disc_origem15 IS NULL THEN
			vr_disc_origem15 := 'null';
		END IF;
		IF pn_disc_origem15 IS NULL
		AND pa_disc_origem15 IS NOT NULL THEN
			vr_disc_origem15 := 'null';
		END IF;
		IF pn_disc_origem15 IS NOT NULL
		AND pa_disc_origem15 IS NULL THEN
			vr_disc_origem15 := '"' || RTRIM(pn_disc_origem15) || '"';
		END IF;
		IF pn_disc_origem15 IS NOT NULL
		AND pa_disc_origem15 IS NOT NULL THEN
			IF pa_disc_origem15 <> pn_disc_origem15 THEN
				vr_disc_origem15 := '"' || RTRIM(pn_disc_origem15) || '"';
			ELSE
				vr_disc_origem15 := '"' || RTRIM(pa_disc_origem15) || '"';
			END IF;
		END IF;
		IF pn_fo_turma16 IS NULL THEN
			vr_fo_turma16 := NULL;
		ELSE
			vr_fo_turma16 := ':vblob1';
		END IF;
		v_blob1 := pn_fo_turma16;
		IF pn_co_sala17 IS NULL
		AND pa_co_sala17 IS NULL THEN
			vr_co_sala17 := 'null';
		END IF;
		IF pn_co_sala17 IS NULL
		AND pa_co_sala17 IS NOT NULL THEN
			vr_co_sala17 := 'null';
		END IF;
		IF pn_co_sala17 IS NOT NULL
		AND pa_co_sala17 IS NULL THEN
			vr_co_sala17 := '"' || RTRIM(pn_co_sala17) || '"';
		END IF;
		IF pn_co_sala17 IS NOT NULL
		AND pa_co_sala17 IS NOT NULL THEN
			IF pa_co_sala17 <> pn_co_sala17 THEN
				vr_co_sala17 := '"' || RTRIM(pn_co_sala17) || '"';
			ELSE
				vr_co_sala17 := '"' || RTRIM(pa_co_sala17) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_turma set co_unidade = ' || RTRIM(vr_co_unidade00) || '  , co_turma = ' || RTRIM(vr_co_turma01) || '  , ano_sem = ' || RTRIM(vr_ano_sem02) || '  , co_curso = ' || RTRIM(vr_co_curso03) || '  , co_grau = ' || RTRIM(vr_co_grau04);
		v_sql2 := '  , co_turno = ' || RTRIM(vr_co_turno05) || '  , co_seq_serie = ' || RTRIM(vr_co_seq_serie06) || '  , co_letra_turma = ' || RTRIM(vr_co_letra_turm07) || '  , co_bloco = ' || RTRIM(vr_co_bloco08) || '  , ds_turma = ' || RTRIM(vr_ds_turma09) || '  , ST_TURMA_DEFINITIVA = ' || RTRIM(vr_st_turma_defi10);
		v_sql3 := '  , nu_maximo_aluno = ' || RTRIM(vr_nu_maximo_alu11) || '  , co_tipo_horario = ' || RTRIM(vr_co_tipo_horar12) || '  , co_turma_procura = ' || RTRIM(vr_co_turma_proc13) || '  , st_laboratorio = ' || RTRIM(vr_st_laboratori14) || '  , disc_origem = ' || RTRIM(vr_disc_origem15) || '  , fo_turma = ' || RTRIM(vr_fo_turma16) || '  , co_sala = ' || RTRIM(vr_co_sala17);
		v_sql4 := ' where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and co_turma = ' || RTRIM(vr_co_turma01) || '  and ano_sem = ' || RTRIM(vr_ano_sem02) || '  and co_curso = ' || RTRIM(vr_co_curso03) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie06) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4;
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
		       's_turma',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       v_blob1,
		       NULL);
	END IF;
END pr_s_turma170;
/

