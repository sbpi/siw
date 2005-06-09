CREATE OR REPLACE PROCEDURE pr_s_calendar054(
	P_OP_IN                CHAR,
	PA_dt_calendario00_IN  s_calendario.dt_calendario%TYPE,
	PA_co_dia_calend01_IN  s_calendario.co_dia_calendario%TYPE,
	PA_ano_sem02_IN        s_periodounidade.ano_sem%TYPE,
	PA_co_unidade03_IN     s_calendario.co_unidade%TYPE,
	PN_dt_calendario00_IN  s_calendario.dt_calendario%TYPE,
	PN_co_dia_calend01_IN  s_calendario.co_dia_calendario%TYPE,
	PN_ano_sem02_IN        s_periodounidade.ano_sem%TYPE,
	PN_co_unidade03_IN     s_calendario.co_unidade%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_dt_calendario00  s_calendario.dt_calendario%TYPE := PA_dt_calendario00_IN;
PA_co_dia_calend01  s_calendario.co_dia_calendario%TYPE := PA_co_dia_calend01_IN;
PA_ano_sem02        s_periodounidade.ano_sem%TYPE := PA_ano_sem02_IN;
PA_co_unidade03     s_calendario.co_unidade%TYPE := PA_co_unidade03_IN;
PN_dt_calendario00  s_calendario.dt_calendario%TYPE := PN_dt_calendario00_IN;
PN_co_dia_calend01  s_calendario.co_dia_calendario%TYPE := PN_co_dia_calend01_IN;
PN_ano_sem02        s_periodounidade.ano_sem%TYPE := PN_ano_sem02_IN;
PN_co_unidade03     s_calendario.co_unidade%TYPE := PN_co_unidade03_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_dt_calendario00  CHAR(40);
vr_co_dia_calend01  CHAR(10);
vr_ano_sem02        CHAR(10);
vr_co_unidade03     CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_dt_calendario00 IS NULL THEN
			vr_dt_calendario00 := 'null';
		ELSE
			vr_dt_calendario00 := pn_dt_calendario00;
		END IF;
		IF pn_co_dia_calend01 IS NULL THEN
			vr_co_dia_calend01 := 'null';
		ELSE
			vr_co_dia_calend01 := pn_co_dia_calend01;
		END IF;
		IF pn_ano_sem02 IS NULL THEN
			vr_ano_sem02 := 'null';
		ELSE
			vr_ano_sem02 := pn_ano_sem02;
		END IF;
		IF pn_co_unidade03 IS NULL THEN
			vr_co_unidade03 := 'null';
		ELSE
			vr_co_unidade03 := pn_co_unidade03;
		END IF;
		v_sql1 := 'insert into s_calendario(dt_calendario, co_dia_calendario, ano_sem, co_unidade) values (';
		v_sql2 := '"' || vr_dt_calendario00 || '"' || ',' || RTRIM(vr_co_dia_calend01) || ',' || '"' || RTRIM(vr_ano_sem02) || '"' || ',' || '"' || RTRIM(vr_co_unidade03) || '"' || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_dt_calendario00 IS NULL THEN
			vr_dt_calendario00 := 'null';
		ELSE
			vr_dt_calendario00 := '"' || pa_dt_calendario00 || '"';
		END IF;
		IF pa_co_dia_calend01 IS NULL THEN
			vr_co_dia_calend01 := 'null';
		ELSE
			vr_co_dia_calend01 := pa_co_dia_calend01;
		END IF;
		IF pa_ano_sem02 IS NULL THEN
			vr_ano_sem02 := 'null';
		ELSE
			vr_ano_sem02 := '"' || RTRIM(pa_ano_sem02) || '"';
		END IF;
		IF pa_co_unidade03 IS NULL THEN
			vr_co_unidade03 := 'null';
		ELSE
			vr_co_unidade03 := '"' || RTRIM(pa_co_unidade03) || '"';
		END IF;
		v_sql1 := '  delete from s_calendario where dt_calendario = ' || RTRIM(vr_dt_calendario00) || '  and co_dia_calendario = ' || RTRIM(vr_co_dia_calend01) || '  and ano_sem = ' || RTRIM(vr_ano_sem02) || '  and co_unidade = ' || RTRIM(vr_co_unidade03) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_dt_calendario00 IS NULL
		AND pa_dt_calendario00 IS NULL THEN
			vr_dt_calendario00 := 'null';
		END IF;
		IF pn_dt_calendario00 IS NULL
		AND pa_dt_calendario00 IS NOT NULL THEN
			vr_dt_calendario00 := 'null';
		END IF;
		IF pn_dt_calendario00 IS NOT NULL
		AND pa_dt_calendario00 IS NULL THEN
			vr_dt_calendario00 := '"' || pn_dt_calendario00 || '"';
		END IF;
		IF pn_dt_calendario00 IS NOT NULL
		AND pa_dt_calendario00 IS NOT NULL THEN
			IF pa_dt_calendario00 <> pn_dt_calendario00 THEN
				vr_dt_calendario00 := '"' || pn_dt_calendario00 || '"';
			ELSE
				vr_dt_calendario00 := '"' || pa_dt_calendario00 || '"';
			END IF;
		END IF;
		IF pn_co_dia_calend01 IS NULL
		AND pa_co_dia_calend01 IS NULL THEN
			vr_co_dia_calend01 := 'null';
		END IF;
		IF pn_co_dia_calend01 IS NULL
		AND pa_co_dia_calend01 IS NOT NULL THEN
			vr_co_dia_calend01 := 'null';
		END IF;
		IF pn_co_dia_calend01 IS NOT NULL
		AND pa_co_dia_calend01 IS NULL THEN
			vr_co_dia_calend01 := pn_co_dia_calend01;
		END IF;
		IF pn_co_dia_calend01 IS NOT NULL
		AND pa_co_dia_calend01 IS NOT NULL THEN
			IF pa_co_dia_calend01 <> pn_co_dia_calend01 THEN
				vr_co_dia_calend01 := pn_co_dia_calend01;
			ELSE
				vr_co_dia_calend01 := pa_co_dia_calend01;
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
		v_sql1 := 'update s_calendario set dt_calendario = ' || RTRIM(vr_dt_calendario00) || '  , co_dia_calendario = ' || RTRIM(vr_co_dia_calend01) || '  , ano_sem = ' || RTRIM(vr_ano_sem02) || '  , co_unidade = ' || RTRIM(vr_co_unidade03);
		v_sql2 := ' where dt_calendario = ' || RTRIM(vr_dt_calendario00) || '  and co_dia_calendario = ' || RTRIM(vr_co_dia_calend01) || '  and ano_sem = ' || RTRIM(vr_ano_sem02) || '  and co_unidade = ' || RTRIM(vr_co_unidade03) || ';';
		v_sql3 := '';
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
		       's_calendario',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_calendar054;
/

