CREATE OR REPLACE PROCEDURE pr_s_dia_cale086(
	P_OP_IN                CHAR,
	PA_co_dia_calend00_IN  s_dia_calendario.co_dia_calendario%TYPE,
	PA_ds_dia_calend01_IN  s_dia_calendario.ds_dia_calendario%TYPE,
	PA_ano_sem02_IN        s_periodounidade.ano_sem%TYPE,
	PA_ds_cor_calend03_IN  s_dia_calendario.ds_cor_calendario%TYPE,
	PA_co_unidade04_IN     s_dia_calendario.co_unidade%TYPE,
	PA_st_letivo05_IN      s_dia_calendario.st_letivo%TYPE,
	PN_co_dia_calend00_IN  s_dia_calendario.co_dia_calendario%TYPE,
	PN_ds_dia_calend01_IN  s_dia_calendario.ds_dia_calendario%TYPE,
	PN_ano_sem02_IN        s_periodounidade.ano_sem%TYPE,
	PN_ds_cor_calend03_IN  s_dia_calendario.ds_cor_calendario%TYPE,
	PN_co_unidade04_IN     s_dia_calendario.co_unidade%TYPE,
	PN_st_letivo05_IN      s_dia_calendario.st_letivo%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_dia_calend00  s_dia_calendario.co_dia_calendario%TYPE := PA_co_dia_calend00_IN;
PA_ds_dia_calend01  s_dia_calendario.ds_dia_calendario%TYPE := PA_ds_dia_calend01_IN;
PA_ano_sem02        s_periodounidade.ano_sem%TYPE := PA_ano_sem02_IN;
PA_ds_cor_calend03  s_dia_calendario.ds_cor_calendario%TYPE := PA_ds_cor_calend03_IN;
PA_co_unidade04     s_dia_calendario.co_unidade%TYPE := PA_co_unidade04_IN;
PA_st_letivo05      s_dia_calendario.st_letivo%TYPE := PA_st_letivo05_IN;
PN_co_dia_calend00  s_dia_calendario.co_dia_calendario%TYPE := PN_co_dia_calend00_IN;
PN_ds_dia_calend01  s_dia_calendario.ds_dia_calendario%TYPE := PN_ds_dia_calend01_IN;
PN_ano_sem02        s_periodounidade.ano_sem%TYPE := PN_ano_sem02_IN;
PN_ds_cor_calend03  s_dia_calendario.ds_cor_calendario%TYPE := PN_ds_cor_calend03_IN;
PN_co_unidade04     s_dia_calendario.co_unidade%TYPE := PN_co_unidade04_IN;
PN_st_letivo05      s_dia_calendario.st_letivo%TYPE := PN_st_letivo05_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_dia_calend00  CHAR(10);
vr_ds_dia_calend01  CHAR(40);
vr_ano_sem02        CHAR(10);
vr_ds_cor_calend03  CHAR(40);
vr_co_unidade04     CHAR(10);
vr_st_letivo05      CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_dia_calend00 IS NULL THEN
			vr_co_dia_calend00 := 'null';
		ELSE
			vr_co_dia_calend00 := pn_co_dia_calend00;
		END IF;
		IF pn_ds_dia_calend01 IS NULL THEN
			vr_ds_dia_calend01 := 'null';
		ELSE
			vr_ds_dia_calend01 := pn_ds_dia_calend01;
		END IF;
		IF pn_ano_sem02 IS NULL THEN
			vr_ano_sem02 := 'null';
		ELSE
			vr_ano_sem02 := pn_ano_sem02;
		END IF;
		IF pn_ds_cor_calend03 IS NULL THEN
			vr_ds_cor_calend03 := 'null';
		ELSE
			vr_ds_cor_calend03 := pn_ds_cor_calend03;
		END IF;
		IF pn_co_unidade04 IS NULL THEN
			vr_co_unidade04 := 'null';
		ELSE
			vr_co_unidade04 := pn_co_unidade04;
		END IF;
		IF pn_st_letivo05 IS NULL THEN
			vr_st_letivo05 := 'null';
		ELSE
			vr_st_letivo05 := pn_st_letivo05;
		END IF;
		v_sql1 := 'insert into s_dia_calendario(co_dia_calendario, ds_dia_calendario, ano_sem, ds_cor_calendario, co_unidade, st_letivo) values (';
		v_sql2 := RTRIM(vr_co_dia_calend00) || ',' || '"' || RTRIM(vr_ds_dia_calend01) || '"' || ',' || '"' || RTRIM(vr_ano_sem02) || '"' || ',' || '"' || RTRIM(vr_ds_cor_calend03) || '"' || ',' || '"' || RTRIM(vr_co_unidade04) || '"' || ',' || '"' || RTRIM(vr_st_letivo05) || '"' || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_dia_calend00 IS NULL THEN
			vr_co_dia_calend00 := 'null';
		ELSE
			vr_co_dia_calend00 := pa_co_dia_calend00;
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
		v_sql1 := '  delete from s_dia_calendario where co_dia_calendario = ' || RTRIM(vr_co_dia_calend00) || '  and ano_sem = ' || RTRIM(vr_ano_sem02) || '  and co_unidade = ' || RTRIM(vr_co_unidade04) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_dia_calend00 IS NULL
		AND pa_co_dia_calend00 IS NULL THEN
			vr_co_dia_calend00 := 'null';
		END IF;
		IF pn_co_dia_calend00 IS NULL
		AND pa_co_dia_calend00 IS NOT NULL THEN
			vr_co_dia_calend00 := 'null';
		END IF;
		IF pn_co_dia_calend00 IS NOT NULL
		AND pa_co_dia_calend00 IS NULL THEN
			vr_co_dia_calend00 := pn_co_dia_calend00;
		END IF;
		IF pn_co_dia_calend00 IS NOT NULL
		AND pa_co_dia_calend00 IS NOT NULL THEN
			IF pa_co_dia_calend00 <> pn_co_dia_calend00 THEN
				vr_co_dia_calend00 := pn_co_dia_calend00;
			ELSE
				vr_co_dia_calend00 := pa_co_dia_calend00;
			END IF;
		END IF;
		IF pn_ds_dia_calend01 IS NULL
		AND pa_ds_dia_calend01 IS NULL THEN
			vr_ds_dia_calend01 := 'null';
		END IF;
		IF pn_ds_dia_calend01 IS NULL
		AND pa_ds_dia_calend01 IS NOT NULL THEN
			vr_ds_dia_calend01 := 'null';
		END IF;
		IF pn_ds_dia_calend01 IS NOT NULL
		AND pa_ds_dia_calend01 IS NULL THEN
			vr_ds_dia_calend01 := '"' || RTRIM(pn_ds_dia_calend01) || '"';
		END IF;
		IF pn_ds_dia_calend01 IS NOT NULL
		AND pa_ds_dia_calend01 IS NOT NULL THEN
			IF pa_ds_dia_calend01 <> pn_ds_dia_calend01 THEN
				vr_ds_dia_calend01 := '"' || RTRIM(pn_ds_dia_calend01) || '"';
			ELSE
				vr_ds_dia_calend01 := '"' || RTRIM(pa_ds_dia_calend01) || '"';
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
		IF pn_ds_cor_calend03 IS NULL
		AND pa_ds_cor_calend03 IS NULL THEN
			vr_ds_cor_calend03 := 'null';
		END IF;
		IF pn_ds_cor_calend03 IS NULL
		AND pa_ds_cor_calend03 IS NOT NULL THEN
			vr_ds_cor_calend03 := 'null';
		END IF;
		IF pn_ds_cor_calend03 IS NOT NULL
		AND pa_ds_cor_calend03 IS NULL THEN
			vr_ds_cor_calend03 := '"' || RTRIM(pn_ds_cor_calend03) || '"';
		END IF;
		IF pn_ds_cor_calend03 IS NOT NULL
		AND pa_ds_cor_calend03 IS NOT NULL THEN
			IF pa_ds_cor_calend03 <> pn_ds_cor_calend03 THEN
				vr_ds_cor_calend03 := '"' || RTRIM(pn_ds_cor_calend03) || '"';
			ELSE
				vr_ds_cor_calend03 := '"' || RTRIM(pa_ds_cor_calend03) || '"';
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
		IF pn_st_letivo05 IS NULL
		AND pa_st_letivo05 IS NULL THEN
			vr_st_letivo05 := 'null';
		END IF;
		IF pn_st_letivo05 IS NULL
		AND pa_st_letivo05 IS NOT NULL THEN
			vr_st_letivo05 := 'null';
		END IF;
		IF pn_st_letivo05 IS NOT NULL
		AND pa_st_letivo05 IS NULL THEN
			vr_st_letivo05 := '"' || RTRIM(pn_st_letivo05) || '"';
		END IF;
		IF pn_st_letivo05 IS NOT NULL
		AND pa_st_letivo05 IS NOT NULL THEN
			IF pa_st_letivo05 <> pn_st_letivo05 THEN
				vr_st_letivo05 := '"' || RTRIM(pn_st_letivo05) || '"';
			ELSE
				vr_st_letivo05 := '"' || RTRIM(pa_st_letivo05) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_dia_calendario set co_dia_calendario = ' || RTRIM(vr_co_dia_calend00) || '  , ds_dia_calendario = ' || RTRIM(vr_ds_dia_calend01) || '  , ano_sem = ' || RTRIM(vr_ano_sem02);
		v_sql2 := '  , ds_cor_calendario = ' || RTRIM(vr_ds_cor_calend03) || '  , co_unidade = ' || RTRIM(vr_co_unidade04) || '  , st_letivo = ' || RTRIM(vr_st_letivo05);
		v_sql3 := ' where co_dia_calendario = ' || RTRIM(vr_co_dia_calend00) || '  and ano_sem = ' || RTRIM(vr_ano_sem02) || '  and co_unidade = ' || RTRIM(vr_co_unidade04) || ';';
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
		       's_dia_calendario',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_dia_cale086;
/

