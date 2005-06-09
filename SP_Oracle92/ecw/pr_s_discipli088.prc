CREATE OR REPLACE PROCEDURE pr_s_discipli088(
	P_OP_IN                CHAR,
	PA_co_disciplina00_IN  s_disciplina.co_disciplina%TYPE,
	PA_ds_disciplina01_IN  s_disciplina.ds_disciplina%TYPE,
	PA_ano_sem02_IN        s_disciplina.ano_sem%TYPE,
	PA_ds_ordem_imp03_IN   s_disciplina.ds_ordem_imp%TYPE,
	PA_co_unidade04_IN     s_disciplina.co_unidade%TYPE,
	PA_nu_disc_credi05_IN  s_disciplina.nu_disc_credito%TYPE,
	PA_tp_disciplina06_IN  s_disciplina.tp_disciplina%TYPE,
	PA_co_disc_fedf07_IN   s_disciplina.co_disc_fedf%TYPE,
	PA_co_tipo_disci08_IN  s_disciplina.co_tipo_disciplina%TYPE,
	PN_co_disciplina00_IN  s_disciplina.co_disciplina%TYPE,
	PN_ds_disciplina01_IN  s_disciplina.ds_disciplina%TYPE,
	PN_ano_sem02_IN        s_disciplina.ano_sem%TYPE,
	PN_ds_ordem_imp03_IN   s_disciplina.ds_ordem_imp%TYPE,
	PN_co_unidade04_IN     s_disciplina.co_unidade%TYPE,
	PN_nu_disc_credi05_IN  s_disciplina.nu_disc_credito%TYPE,
	PN_tp_disciplina06_IN  s_disciplina.tp_disciplina%TYPE,
	PN_co_disc_fedf07_IN   s_disciplina.co_disc_fedf%TYPE,
	PN_co_tipo_disci08_IN  s_disciplina.co_tipo_disciplina%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_disciplina00  s_disciplina.co_disciplina%TYPE := PA_co_disciplina00_IN;
PA_ds_disciplina01  s_disciplina.ds_disciplina%TYPE := PA_ds_disciplina01_IN;
PA_ano_sem02        s_disciplina.ano_sem%TYPE := PA_ano_sem02_IN;
PA_ds_ordem_imp03   s_disciplina.ds_ordem_imp%TYPE := PA_ds_ordem_imp03_IN;
PA_co_unidade04     s_disciplina.co_unidade%TYPE := PA_co_unidade04_IN;
PA_nu_disc_credi05  s_disciplina.nu_disc_credito%TYPE := PA_nu_disc_credi05_IN;
PA_tp_disciplina06  s_disciplina.tp_disciplina%TYPE := PA_tp_disciplina06_IN;
PA_co_disc_fedf07   s_disciplina.co_disc_fedf%TYPE := PA_co_disc_fedf07_IN;
PA_co_tipo_disci08  s_disciplina.co_tipo_disciplina%TYPE := PA_co_tipo_disci08_IN;
PN_co_disciplina00  s_disciplina.co_disciplina%TYPE := PN_co_disciplina00_IN;
PN_ds_disciplina01  s_disciplina.ds_disciplina%TYPE := PN_ds_disciplina01_IN;
PN_ano_sem02        s_disciplina.ano_sem%TYPE := PN_ano_sem02_IN;
PN_ds_ordem_imp03   s_disciplina.ds_ordem_imp%TYPE := PN_ds_ordem_imp03_IN;
PN_co_unidade04     s_disciplina.co_unidade%TYPE := PN_co_unidade04_IN;
PN_nu_disc_credi05  s_disciplina.nu_disc_credito%TYPE := PN_nu_disc_credi05_IN;
PN_tp_disciplina06  s_disciplina.tp_disciplina%TYPE := PN_tp_disciplina06_IN;
PN_co_disc_fedf07   s_disciplina.co_disc_fedf%TYPE := PN_co_disc_fedf07_IN;
PN_co_tipo_disci08  s_disciplina.co_tipo_disciplina%TYPE := PN_co_tipo_disci08_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_disciplina00  CHAR(10);
vr_ds_disciplina01  CHAR(70);
vr_ano_sem02        CHAR(10);
vr_ds_ordem_imp03   CHAR(10);
vr_co_unidade04     CHAR(10);
vr_nu_disc_credi05  CHAR(10);
vr_tp_disciplina06  CHAR(40);
vr_co_disc_fedf07   CHAR(25);
vr_co_tipo_disci08  CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_disciplina00 IS NULL THEN
			vr_co_disciplina00 := 'null';
		ELSE
			vr_co_disciplina00 := pn_co_disciplina00;
		END IF;
		IF pn_ds_disciplina01 IS NULL THEN
			vr_ds_disciplina01 := 'null';
		ELSE
			vr_ds_disciplina01 := pn_ds_disciplina01;
		END IF;
		IF pn_ano_sem02 IS NULL THEN
			vr_ano_sem02 := 'null';
		ELSE
			vr_ano_sem02 := pn_ano_sem02;
		END IF;
		IF pn_ds_ordem_imp03 IS NULL THEN
			vr_ds_ordem_imp03 := 'null';
		ELSE
			vr_ds_ordem_imp03 := pn_ds_ordem_imp03;
		END IF;
		IF pn_co_unidade04 IS NULL THEN
			vr_co_unidade04 := 'null';
		ELSE
			vr_co_unidade04 := pn_co_unidade04;
		END IF;
		IF pn_nu_disc_credi05 IS NULL THEN
			vr_nu_disc_credi05 := 'null';
		ELSE
			vr_nu_disc_credi05 := pn_nu_disc_credi05;
		END IF;
		IF pn_tp_disciplina06 IS NULL THEN
			vr_tp_disciplina06 := 'null';
		ELSE
			vr_tp_disciplina06 := pn_tp_disciplina06;
		END IF;
		IF pn_co_disc_fedf07 IS NULL THEN
			vr_co_disc_fedf07 := 'null';
		ELSE
			vr_co_disc_fedf07 := pn_co_disc_fedf07;
		END IF;
		IF pn_co_tipo_disci08 IS NULL THEN
			vr_co_tipo_disci08 := 'null';
		ELSE
			vr_co_tipo_disci08 := pn_co_tipo_disci08;
		END IF;
		v_sql1 := 'insert into s_disciplina(co_disciplina, ds_disciplina, ano_sem, ds_ordem_imp, co_unidade, nu_disc_credito, tp_disciplina, co_disc_fedf, co_tipo_disciplina) values (';
		v_sql2 := '"' || RTRIM(vr_co_disciplina00) || '"' || ',' || '"' || RTRIM(vr_ds_disciplina01) || '"' || ',' || '"' || RTRIM(vr_ano_sem02) || '"' || ',' || RTRIM(vr_ds_ordem_imp03) || ',';
		v_sql3 := '"' || RTRIM(vr_co_unidade04) || '"' || ',' || RTRIM(vr_nu_disc_credi05) || ',' || '"' || RTRIM(vr_tp_disciplina06) || '"' || ',' || '"' || RTRIM(vr_co_disc_fedf07) || '"' || ',' || RTRIM(vr_co_tipo_disci08) || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_disciplina00 IS NULL THEN
			vr_co_disciplina00 := 'null';
		ELSE
			vr_co_disciplina00 := '"' || RTRIM(pa_co_disciplina00) || '"';
		END IF;
		IF pa_ano_sem02 IS NULL THEN
			vr_ano_sem02 := 'null';
		ELSE
			vr_ano_sem02 := '"' || RTRIM(pa_ano_sem02) || '"';
		END IF;
		IF pa_co_unidade04 IS NULL THEN
			vr_co_unidade04 := 'null';
		ELSE
			vr_co_unidade04 := '"' || RTRIM(pa_co_unidade04) || '"';
		END IF;
		v_sql1 := '  delete from s_disciplina where co_disciplina = ' || RTRIM(vr_co_disciplina00) || '  and ano_sem = ' || RTRIM(vr_ano_sem02) || '  and co_unidade = ' || RTRIM(vr_co_unidade04) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_disciplina00 IS NULL
		AND pa_co_disciplina00 IS NULL THEN
			vr_co_disciplina00 := 'null';
		END IF;
		IF pn_co_disciplina00 IS NULL
		AND pa_co_disciplina00 IS NOT NULL THEN
			vr_co_disciplina00 := 'null';
		END IF;
		IF pn_co_disciplina00 IS NOT NULL
		AND pa_co_disciplina00 IS NULL THEN
			vr_co_disciplina00 := '"' || RTRIM(pn_co_disciplina00) || '"';
		END IF;
		IF pn_co_disciplina00 IS NOT NULL
		AND pa_co_disciplina00 IS NOT NULL THEN
			IF pa_co_disciplina00 <> pn_co_disciplina00 THEN
				vr_co_disciplina00 := '"' || RTRIM(pn_co_disciplina00) || '"';
			ELSE
				vr_co_disciplina00 := '"' || RTRIM(pa_co_disciplina00) || '"';
			END IF;
		END IF;
		IF pn_ds_disciplina01 IS NULL
		AND pa_ds_disciplina01 IS NULL THEN
			vr_ds_disciplina01 := 'null';
		END IF;
		IF pn_ds_disciplina01 IS NULL
		AND pa_ds_disciplina01 IS NOT NULL THEN
			vr_ds_disciplina01 := 'null';
		END IF;
		IF pn_ds_disciplina01 IS NOT NULL
		AND pa_ds_disciplina01 IS NULL THEN
			vr_ds_disciplina01 := '"' || RTRIM(pn_ds_disciplina01) || '"';
		END IF;
		IF pn_ds_disciplina01 IS NOT NULL
		AND pa_ds_disciplina01 IS NOT NULL THEN
			IF pa_ds_disciplina01 <> pn_ds_disciplina01 THEN
				vr_ds_disciplina01 := '"' || RTRIM(pn_ds_disciplina01) || '"';
			ELSE
				vr_ds_disciplina01 := '"' || RTRIM(pa_ds_disciplina01) || '"';
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
		IF pn_ds_ordem_imp03 IS NULL
		AND pa_ds_ordem_imp03 IS NULL THEN
			vr_ds_ordem_imp03 := 'null';
		END IF;
		IF pn_ds_ordem_imp03 IS NULL
		AND pa_ds_ordem_imp03 IS NOT NULL THEN
			vr_ds_ordem_imp03 := 'null';
		END IF;
		IF pn_ds_ordem_imp03 IS NOT NULL
		AND pa_ds_ordem_imp03 IS NULL THEN
			vr_ds_ordem_imp03 := pn_ds_ordem_imp03;
		END IF;
		IF pn_ds_ordem_imp03 IS NOT NULL
		AND pa_ds_ordem_imp03 IS NOT NULL THEN
			IF pa_ds_ordem_imp03 <> pn_ds_ordem_imp03 THEN
				vr_ds_ordem_imp03 := pn_ds_ordem_imp03;
			ELSE
				vr_ds_ordem_imp03 := pa_ds_ordem_imp03;
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
		IF pn_nu_disc_credi05 IS NULL
		AND pa_nu_disc_credi05 IS NULL THEN
			vr_nu_disc_credi05 := 'null';
		END IF;
		IF pn_nu_disc_credi05 IS NULL
		AND pa_nu_disc_credi05 IS NOT NULL THEN
			vr_nu_disc_credi05 := 'null';
		END IF;
		IF pn_nu_disc_credi05 IS NOT NULL
		AND pa_nu_disc_credi05 IS NULL THEN
			vr_nu_disc_credi05 := pn_nu_disc_credi05;
		END IF;
		IF pn_nu_disc_credi05 IS NOT NULL
		AND pa_nu_disc_credi05 IS NOT NULL THEN
			IF pa_nu_disc_credi05 <> pn_nu_disc_credi05 THEN
				vr_nu_disc_credi05 := pn_nu_disc_credi05;
			ELSE
				vr_nu_disc_credi05 := pa_nu_disc_credi05;
			END IF;
		END IF;
		IF pn_tp_disciplina06 IS NULL
		AND pa_tp_disciplina06 IS NULL THEN
			vr_tp_disciplina06 := 'null';
		END IF;
		IF pn_tp_disciplina06 IS NULL
		AND pa_tp_disciplina06 IS NOT NULL THEN
			vr_tp_disciplina06 := 'null';
		END IF;
		IF pn_tp_disciplina06 IS NOT NULL
		AND pa_tp_disciplina06 IS NULL THEN
			vr_tp_disciplina06 := '"' || RTRIM(pn_tp_disciplina06) || '"';
		END IF;
		IF pn_tp_disciplina06 IS NOT NULL
		AND pa_tp_disciplina06 IS NOT NULL THEN
			IF pa_tp_disciplina06 <> pn_tp_disciplina06 THEN
				vr_tp_disciplina06 := '"' || RTRIM(pn_tp_disciplina06) || '"';
			ELSE
				vr_tp_disciplina06 := '"' || RTRIM(pa_tp_disciplina06) || '"';
			END IF;
		END IF;
		IF pn_co_disc_fedf07 IS NULL
		AND pa_co_disc_fedf07 IS NULL THEN
			vr_co_disc_fedf07 := 'null';
		END IF;
		IF pn_co_disc_fedf07 IS NULL
		AND pa_co_disc_fedf07 IS NOT NULL THEN
			vr_co_disc_fedf07 := 'null';
		END IF;
		IF pn_co_disc_fedf07 IS NOT NULL
		AND pa_co_disc_fedf07 IS NULL THEN
			vr_co_disc_fedf07 := '"' || RTRIM(pn_co_disc_fedf07) || '"';
		END IF;
		IF pn_co_disc_fedf07 IS NOT NULL
		AND pa_co_disc_fedf07 IS NOT NULL THEN
			IF pa_co_disc_fedf07 <> pn_co_disc_fedf07 THEN
				vr_co_disc_fedf07 := '"' || RTRIM(pn_co_disc_fedf07) || '"';
			ELSE
				vr_co_disc_fedf07 := '"' || RTRIM(pa_co_disc_fedf07) || '"';
			END IF;
		END IF;
		IF pn_co_tipo_disci08 IS NULL
		AND pa_co_tipo_disci08 IS NULL THEN
			vr_co_tipo_disci08 := 'null';
		END IF;
		IF pn_co_tipo_disci08 IS NULL
		AND pa_co_tipo_disci08 IS NOT NULL THEN
			vr_co_tipo_disci08 := 'null';
		END IF;
		IF pn_co_tipo_disci08 IS NOT NULL
		AND pa_co_tipo_disci08 IS NULL THEN
			vr_co_tipo_disci08 := pn_co_tipo_disci08;
		END IF;
		IF pn_co_tipo_disci08 IS NOT NULL
		AND pa_co_tipo_disci08 IS NOT NULL THEN
			IF pa_co_tipo_disci08 <> pn_co_tipo_disci08 THEN
				vr_co_tipo_disci08 := pn_co_tipo_disci08;
			ELSE
				vr_co_tipo_disci08 := pa_co_tipo_disci08;
			END IF;
		END IF;
		v_sql1 := 'update s_disciplina set co_disciplina = ' || RTRIM(vr_co_disciplina00) || '  , ds_disciplina = ' || RTRIM(vr_ds_disciplina01) || '  , ano_sem = ' || RTRIM(vr_ano_sem02) || '  , ds_ordem_imp = ' || RTRIM(vr_ds_ordem_imp03);
		v_sql2 := '  , co_unidade = ' || RTRIM(vr_co_unidade04) || '  , nu_disc_credito = ' || RTRIM(vr_nu_disc_credi05) || '  , tp_disciplina = ' || RTRIM(vr_tp_disciplina06) || '  , co_disc_fedf = ' || RTRIM(vr_co_disc_fedf07) || '  , co_tipo_disciplina = ' || RTRIM(vr_co_tipo_disci08);
		v_sql3 := ' where co_disciplina = ' || RTRIM(vr_co_disciplina00) || '  and ano_sem = ' || RTRIM(vr_ano_sem02) || '  and co_unidade = ' || RTRIM(vr_co_unidade04) || ';';
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
		       's_disciplina',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_discipli088;
/

