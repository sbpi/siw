CREATE OR REPLACE PROCEDURE pr_s_tipo_hor158(
	P_OP_IN                CHAR,
	PA_co_tipo_horar00_IN  s_tipo_horario.co_tipo_horario%TYPE,
	PA_ds_tipo_horar01_IN  s_tipo_horario.ds_tipo_horario%TYPE,
	PA_co_curso02_IN       s_tipo_horario.co_curso%TYPE,
	PA_ano_sem03_IN        s_tipo_horario.ano_sem%TYPE,
	PA_co_unidade04_IN     s_tipo_horario.co_unidade%TYPE,
	PA_nu_ordem05_IN       s_tipo_horario.nu_ordem%TYPE,
	PN_co_tipo_horar00_IN  s_tipo_horario.co_tipo_horario%TYPE,
	PN_ds_tipo_horar01_IN  s_tipo_horario.ds_tipo_horario%TYPE,
	PN_co_curso02_IN       s_tipo_horario.co_curso%TYPE,
	PN_ano_sem03_IN        s_tipo_horario.ano_sem%TYPE,
	PN_co_unidade04_IN     s_tipo_horario.co_unidade%TYPE,
	PN_nu_ordem05_IN       s_tipo_horario.nu_ordem%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_tipo_horar00  s_tipo_horario.co_tipo_horario%TYPE := PA_co_tipo_horar00_IN;
PA_ds_tipo_horar01  s_tipo_horario.ds_tipo_horario%TYPE := PA_ds_tipo_horar01_IN;
PA_co_curso02       s_tipo_horario.co_curso%TYPE := PA_co_curso02_IN;
PA_ano_sem03        s_tipo_horario.ano_sem%TYPE := PA_ano_sem03_IN;
PA_co_unidade04     s_tipo_horario.co_unidade%TYPE := PA_co_unidade04_IN;
PA_nu_ordem05       s_tipo_horario.nu_ordem%TYPE := PA_nu_ordem05_IN;
PN_co_tipo_horar00  s_tipo_horario.co_tipo_horario%TYPE := PN_co_tipo_horar00_IN;
PN_ds_tipo_horar01  s_tipo_horario.ds_tipo_horario%TYPE := PN_ds_tipo_horar01_IN;
PN_co_curso02       s_tipo_horario.co_curso%TYPE := PN_co_curso02_IN;
PN_ano_sem03        s_tipo_horario.ano_sem%TYPE := PN_ano_sem03_IN;
PN_co_unidade04     s_tipo_horario.co_unidade%TYPE := PN_co_unidade04_IN;
PN_nu_ordem05       s_tipo_horario.nu_ordem%TYPE := PN_nu_ordem05_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_tipo_horar00  CHAR(10);
vr_ds_tipo_horar01  CHAR(40);
vr_co_curso02       CHAR(10);
vr_ano_sem03        CHAR(10);
vr_co_unidade04     CHAR(10);
vr_nu_ordem05       CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_tipo_horar00 IS NULL THEN
			vr_co_tipo_horar00 := 'null';
		ELSE
			vr_co_tipo_horar00 := pn_co_tipo_horar00;
		END IF;
		IF pn_ds_tipo_horar01 IS NULL THEN
			vr_ds_tipo_horar01 := 'null';
		ELSE
			vr_ds_tipo_horar01 := pn_ds_tipo_horar01;
		END IF;
		IF pn_co_curso02 IS NULL THEN
			vr_co_curso02 := 'null';
		ELSE
			vr_co_curso02 := pn_co_curso02;
		END IF;
		IF pn_ano_sem03 IS NULL THEN
			vr_ano_sem03 := 'null';
		ELSE
			vr_ano_sem03 := pn_ano_sem03;
		END IF;
		IF pn_co_unidade04 IS NULL THEN
			vr_co_unidade04 := 'null';
		ELSE
			vr_co_unidade04 := pn_co_unidade04;
		END IF;
		IF pn_nu_ordem05 IS NULL THEN
			vr_nu_ordem05 := 'null';
		ELSE
			vr_nu_ordem05 := pn_nu_ordem05;
		END IF;
		v_sql1 := 'insert into s_tipo_horario(co_tipo_horario, ds_tipo_horario, co_curso, ano_sem, co_unidade, nu_ordem) values (';
		v_sql2 := RTRIM(vr_co_tipo_horar00) || ',' || '"' || RTRIM(vr_ds_tipo_horar01) || '"' || ',' || RTRIM(vr_co_curso02) || ',' || '"' || RTRIM(vr_ano_sem03) || '"' || ',' || '"' || RTRIM(vr_co_unidade04) || '"' || ',' || RTRIM(vr_nu_ordem05) || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_tipo_horar00 IS NULL THEN
			vr_co_tipo_horar00 := 'null';
		ELSE
			vr_co_tipo_horar00 := pa_co_tipo_horar00;
		END IF;
		IF pa_co_curso02 IS NULL THEN
			vr_co_curso02 := 'null';
		ELSE
			vr_co_curso02 := pa_co_curso02;
		END IF;
		IF pa_ano_sem03 IS NULL THEN
			vr_ano_sem03 := 'null';
		ELSE
			vr_ano_sem03 := '"' || RTRIM(pa_ano_sem03) || '"';
		END IF;
		IF pa_co_unidade04 IS NULL THEN
			vr_co_unidade04 := 'null';
		ELSE
			vr_co_unidade04 := '"' || RTRIM(pa_co_unidade04) || '"';
		END IF;
		v_sql1 := '  delete from s_tipo_horario where co_tipo_horario = ' || RTRIM(vr_co_tipo_horar00) || '  and co_curso = ' || RTRIM(vr_co_curso02) || '  and ano_sem = ' || RTRIM(vr_ano_sem03) || '  and co_unidade = ' || RTRIM(vr_co_unidade04) || ';';
		v_sql2 := '';
		v_sql3 := '';
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
		IF pn_ds_tipo_horar01 IS NULL
		AND pa_ds_tipo_horar01 IS NULL THEN
			vr_ds_tipo_horar01 := 'null';
		END IF;
		IF pn_ds_tipo_horar01 IS NULL
		AND pa_ds_tipo_horar01 IS NOT NULL THEN
			vr_ds_tipo_horar01 := 'null';
		END IF;
		IF pn_ds_tipo_horar01 IS NOT NULL
		AND pa_ds_tipo_horar01 IS NULL THEN
			vr_ds_tipo_horar01 := '"' || RTRIM(pn_ds_tipo_horar01) || '"';
		END IF;
		IF pn_ds_tipo_horar01 IS NOT NULL
		AND pa_ds_tipo_horar01 IS NOT NULL THEN
			IF pa_ds_tipo_horar01 <> pn_ds_tipo_horar01 THEN
				vr_ds_tipo_horar01 := '"' || RTRIM(pn_ds_tipo_horar01) || '"';
			ELSE
				vr_ds_tipo_horar01 := '"' || RTRIM(pa_ds_tipo_horar01) || '"';
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
		IF pn_nu_ordem05 IS NULL
		AND pa_nu_ordem05 IS NULL THEN
			vr_nu_ordem05 := 'null';
		END IF;
		IF pn_nu_ordem05 IS NULL
		AND pa_nu_ordem05 IS NOT NULL THEN
			vr_nu_ordem05 := 'null';
		END IF;
		IF pn_nu_ordem05 IS NOT NULL
		AND pa_nu_ordem05 IS NULL THEN
			vr_nu_ordem05 := pn_nu_ordem05;
		END IF;
		IF pn_nu_ordem05 IS NOT NULL
		AND pa_nu_ordem05 IS NOT NULL THEN
			IF pa_nu_ordem05 <> pn_nu_ordem05 THEN
				vr_nu_ordem05 := pn_nu_ordem05;
			ELSE
				vr_nu_ordem05 := pa_nu_ordem05;
			END IF;
		END IF;
		v_sql1 := 'update s_tipo_horario set co_tipo_horario = ' || RTRIM(vr_co_tipo_horar00) || '  , ds_tipo_horario = ' || RTRIM(vr_ds_tipo_horar01) || '  , co_curso = ' || RTRIM(vr_co_curso02) || '  , ano_sem = ' || RTRIM(vr_ano_sem03) || '  , co_unidade = ' || RTRIM(vr_co_unidade04) || '  , nu_ordem = ' || RTRIM(vr_nu_ordem05);
		v_sql2 := ' where co_tipo_horario = ' || RTRIM(vr_co_tipo_horar00) || '  and co_curso = ' || RTRIM(vr_co_curso02) || '  and ano_sem = ' || RTRIM(vr_ano_sem03) || '  and co_unidade = ' || RTRIM(vr_co_unidade04) || ';';
		v_sql3 := '';
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
		       's_tipo_horario',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_tipo_hor158;
/

