CREATE OR REPLACE PROCEDURE pr_s_grau_ser108(
	P_OP_IN                CHAR,
	PA_ds_corresp_gr00_IN  s_grau_serie.ds_corresp_grau%TYPE,
	PA_co_curso01_IN       s_grau_serie.co_curso%TYPE,
	PA_co_unidade02_IN     s_grau_serie.co_unidade%TYPE,
	PA_ds_corresp_se03_IN  s_grau_serie.ds_corresp_serie%TYPE,
	PA_ano_sem04_IN        s_grau_serie.ano_sem%TYPE,
	PA_ds_corresp_oc05_IN  s_grau_serie.ds_corresp_ocor%TYPE,
	PA_ds_corresp_ap06_IN  s_grau_serie.ds_corresp_aprov%TYPE,
	PA_ds_corresp_re07_IN  s_grau_serie.ds_corresp_repr%TYPE,
	PA_ds_corresp_ad08_IN  s_grau_serie.ds_corresp_adapt%TYPE,
	PA_ds_corresp_fu09_IN  s_grau_serie.ds_corresp_func%TYPE,
	PA_ds_corresp_cu10_IN  s_grau_serie.ds_corresp_curso%TYPE,
	PA_ds_abrev_curs11_IN  s_grau_serie.ds_abrev_curso%TYPE,
	PN_ds_corresp_gr00_IN  s_grau_serie.ds_corresp_grau%TYPE,
	PN_co_curso01_IN       s_grau_serie.co_curso%TYPE,
	PN_co_unidade02_IN     s_grau_serie.co_unidade%TYPE,
	PN_ds_corresp_se03_IN  s_grau_serie.ds_corresp_serie%TYPE,
	PN_ano_sem04_IN        s_grau_serie.ano_sem%TYPE,
	PN_ds_corresp_oc05_IN  s_grau_serie.ds_corresp_ocor%TYPE,
	PN_ds_corresp_ap06_IN  s_grau_serie.ds_corresp_aprov%TYPE,
	PN_ds_corresp_re07_IN  s_grau_serie.ds_corresp_repr%TYPE,
	PN_ds_corresp_ad08_IN  s_grau_serie.ds_corresp_adapt%TYPE,
	PN_ds_corresp_fu09_IN  s_grau_serie.ds_corresp_func%TYPE,
	PN_ds_corresp_cu10_IN  s_grau_serie.ds_corresp_curso%TYPE,
	PN_ds_abrev_curs11_IN  s_grau_serie.ds_abrev_curso%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_ds_corresp_gr00  s_grau_serie.ds_corresp_grau%TYPE := PA_ds_corresp_gr00_IN;
PA_co_curso01       s_grau_serie.co_curso%TYPE := PA_co_curso01_IN;
PA_co_unidade02     s_grau_serie.co_unidade%TYPE := PA_co_unidade02_IN;
PA_ds_corresp_se03  s_grau_serie.ds_corresp_serie%TYPE := PA_ds_corresp_se03_IN;
PA_ano_sem04        s_grau_serie.ano_sem%TYPE := PA_ano_sem04_IN;
PA_ds_corresp_oc05  s_grau_serie.ds_corresp_ocor%TYPE := PA_ds_corresp_oc05_IN;
PA_ds_corresp_ap06  s_grau_serie.ds_corresp_aprov%TYPE := PA_ds_corresp_ap06_IN;
PA_ds_corresp_re07  s_grau_serie.ds_corresp_repr%TYPE := PA_ds_corresp_re07_IN;
PA_ds_corresp_ad08  s_grau_serie.ds_corresp_adapt%TYPE := PA_ds_corresp_ad08_IN;
PA_ds_corresp_fu09  s_grau_serie.ds_corresp_func%TYPE := PA_ds_corresp_fu09_IN;
PA_ds_corresp_cu10  s_grau_serie.ds_corresp_curso%TYPE := PA_ds_corresp_cu10_IN;
PA_ds_abrev_curs11  s_grau_serie.ds_abrev_curso%TYPE := PA_ds_abrev_curs11_IN;
PN_ds_corresp_gr00  s_grau_serie.ds_corresp_grau%TYPE := PN_ds_corresp_gr00_IN;
PN_co_curso01       s_grau_serie.co_curso%TYPE := PN_co_curso01_IN;
PN_co_unidade02     s_grau_serie.co_unidade%TYPE := PN_co_unidade02_IN;
PN_ds_corresp_se03  s_grau_serie.ds_corresp_serie%TYPE := PN_ds_corresp_se03_IN;
PN_ano_sem04        s_grau_serie.ano_sem%TYPE := PN_ano_sem04_IN;
PN_ds_corresp_oc05  s_grau_serie.ds_corresp_ocor%TYPE := PN_ds_corresp_oc05_IN;
PN_ds_corresp_ap06  s_grau_serie.ds_corresp_aprov%TYPE := PN_ds_corresp_ap06_IN;
PN_ds_corresp_re07  s_grau_serie.ds_corresp_repr%TYPE := PN_ds_corresp_re07_IN;
PN_ds_corresp_ad08  s_grau_serie.ds_corresp_adapt%TYPE := PN_ds_corresp_ad08_IN;
PN_ds_corresp_fu09  s_grau_serie.ds_corresp_func%TYPE := PN_ds_corresp_fu09_IN;
PN_ds_corresp_cu10  s_grau_serie.ds_corresp_curso%TYPE := PN_ds_corresp_cu10_IN;
PN_ds_abrev_curs11  s_grau_serie.ds_abrev_curso%TYPE := PN_ds_abrev_curs11_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_ds_corresp_gr00  CHAR(10);
vr_co_curso01       CHAR(10);
vr_co_unidade02     CHAR(10);
vr_ds_corresp_se03  CHAR(10);
vr_ano_sem04        CHAR(10);
vr_ds_corresp_oc05  CHAR(25);
vr_ds_corresp_ap06  CHAR(25);
vr_ds_corresp_re07  CHAR(25);
vr_ds_corresp_ad08  CHAR(30);
vr_ds_corresp_fu09  CHAR(30);
vr_ds_corresp_cu10  CHAR(30);
vr_ds_abrev_curs11  CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_ds_corresp_gr00 IS NULL THEN
			vr_ds_corresp_gr00 := 'null';
		ELSE
			vr_ds_corresp_gr00 := pn_ds_corresp_gr00;
		END IF;
		IF pn_co_curso01 IS NULL THEN
			vr_co_curso01 := 'null';
		ELSE
			vr_co_curso01 := pn_co_curso01;
		END IF;
		IF pn_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := pn_co_unidade02;
		END IF;
		IF pn_ds_corresp_se03 IS NULL THEN
			vr_ds_corresp_se03 := 'null';
		ELSE
			vr_ds_corresp_se03 := pn_ds_corresp_se03;
		END IF;
		IF pn_ano_sem04 IS NULL THEN
			vr_ano_sem04 := 'null';
		ELSE
			vr_ano_sem04 := pn_ano_sem04;
		END IF;
		IF pn_ds_corresp_oc05 IS NULL THEN
			vr_ds_corresp_oc05 := 'null';
		ELSE
			vr_ds_corresp_oc05 := pn_ds_corresp_oc05;
		END IF;
		IF pn_ds_corresp_ap06 IS NULL THEN
			vr_ds_corresp_ap06 := 'null';
		ELSE
			vr_ds_corresp_ap06 := pn_ds_corresp_ap06;
		END IF;
		IF pn_ds_corresp_re07 IS NULL THEN
			vr_ds_corresp_re07 := 'null';
		ELSE
			vr_ds_corresp_re07 := pn_ds_corresp_re07;
		END IF;
		IF pn_ds_corresp_ad08 IS NULL THEN
			vr_ds_corresp_ad08 := 'null';
		ELSE
			vr_ds_corresp_ad08 := pn_ds_corresp_ad08;
		END IF;
		IF pn_ds_corresp_fu09 IS NULL THEN
			vr_ds_corresp_fu09 := 'null';
		ELSE
			vr_ds_corresp_fu09 := pn_ds_corresp_fu09;
		END IF;
		IF pn_ds_corresp_cu10 IS NULL THEN
			vr_ds_corresp_cu10 := 'null';
		ELSE
			vr_ds_corresp_cu10 := pn_ds_corresp_cu10;
		END IF;
		IF pn_ds_abrev_curs11 IS NULL THEN
			vr_ds_abrev_curs11 := 'null';
		ELSE
			vr_ds_abrev_curs11 := pn_ds_abrev_curs11;
		END IF;
		v_sql1 := 'insert into s_grau_serie(DS_CORRESPONDENTE_GRAU, co_curso, co_unidade, DS_CORRESPONDENTE_SERIE, ano_sem, DS_CORRESPONDENTE_OCOR, DS_CORRESPONDENTE_APROV, ' || 'DS_CORRESPONDENTE_REPR, DS_CORRESPONDENTE_ADAPT, DS_CORRESPONDENTE_FUNCIONARIO, ds_corresp_curso, ds_abrev_curso) values (';
		v_sql2 := '"' || RTRIM(vr_ds_corresp_gr00) || '"' || ',' || RTRIM(vr_co_curso01) || ',' || '"' || RTRIM(vr_co_unidade02) || '"' || ',' || '"' || RTRIM(vr_ds_corresp_se03) || '"' || ',' || '"' || RTRIM(vr_ano_sem04) || '"' || ',' || '"' || RTRIM(vr_ds_corresp_oc05) || '"' || ',';
		v_sql3 := '"' || RTRIM(vr_ds_corresp_ap06) || '"' || ',' || '"' || RTRIM(vr_ds_corresp_re07) || '"' || ',' || '"' || RTRIM(vr_ds_corresp_ad08) || '"' || ',' || '"' || RTRIM(vr_ds_corresp_fu09) || '"' || ',' || '"' || RTRIM(vr_ds_corresp_cu10) || '"' || ',' || '"' || RTRIM(vr_ds_abrev_curs11) || '"' || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_curso01 IS NULL THEN
			vr_co_curso01 := 'null';
		ELSE
			vr_co_curso01 := pa_co_curso01;
		END IF;
		IF pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := '"' || RTRIM(pa_co_unidade02) || '"';
		END IF;
		IF pa_ano_sem04 IS NULL THEN
			vr_ano_sem04 := 'null';
		ELSE
			vr_ano_sem04 := '"' || RTRIM(pa_ano_sem04) || '"';
		END IF;
		v_sql1 := '  delete from s_grau_serie where co_curso = ' || RTRIM(vr_co_curso01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || '  and ano_sem = ' || RTRIM(vr_ano_sem04) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_ds_corresp_gr00 IS NULL
		AND pa_ds_corresp_gr00 IS NULL THEN
			vr_ds_corresp_gr00 := 'null';
		END IF;
		IF pn_ds_corresp_gr00 IS NULL
		AND pa_ds_corresp_gr00 IS NOT NULL THEN
			vr_ds_corresp_gr00 := 'null';
		END IF;
		IF pn_ds_corresp_gr00 IS NOT NULL
		AND pa_ds_corresp_gr00 IS NULL THEN
			vr_ds_corresp_gr00 := '"' || RTRIM(pn_ds_corresp_gr00) || '"';
		END IF;
		IF pn_ds_corresp_gr00 IS NOT NULL
		AND pa_ds_corresp_gr00 IS NOT NULL THEN
			IF pa_ds_corresp_gr00 <> pn_ds_corresp_gr00 THEN
				vr_ds_corresp_gr00 := '"' || RTRIM(pn_ds_corresp_gr00) || '"';
			ELSE
				vr_ds_corresp_gr00 := '"' || RTRIM(pa_ds_corresp_gr00) || '"';
			END IF;
		END IF;
		IF pn_co_curso01 IS NULL
		AND pa_co_curso01 IS NULL THEN
			vr_co_curso01 := 'null';
		END IF;
		IF pn_co_curso01 IS NULL
		AND pa_co_curso01 IS NOT NULL THEN
			vr_co_curso01 := 'null';
		END IF;
		IF pn_co_curso01 IS NOT NULL
		AND pa_co_curso01 IS NULL THEN
			vr_co_curso01 := pn_co_curso01;
		END IF;
		IF pn_co_curso01 IS NOT NULL
		AND pa_co_curso01 IS NOT NULL THEN
			IF pa_co_curso01 <> pn_co_curso01 THEN
				vr_co_curso01 := pn_co_curso01;
			ELSE
				vr_co_curso01 := pa_co_curso01;
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
		IF pn_ds_corresp_se03 IS NULL
		AND pa_ds_corresp_se03 IS NULL THEN
			vr_ds_corresp_se03 := 'null';
		END IF;
		IF pn_ds_corresp_se03 IS NULL
		AND pa_ds_corresp_se03 IS NOT NULL THEN
			vr_ds_corresp_se03 := 'null';
		END IF;
		IF pn_ds_corresp_se03 IS NOT NULL
		AND pa_ds_corresp_se03 IS NULL THEN
			vr_ds_corresp_se03 := '"' || RTRIM(pn_ds_corresp_se03) || '"';
		END IF;
		IF pn_ds_corresp_se03 IS NOT NULL
		AND pa_ds_corresp_se03 IS NOT NULL THEN
			IF pa_ds_corresp_se03 <> pn_ds_corresp_se03 THEN
				vr_ds_corresp_se03 := '"' || RTRIM(pn_ds_corresp_se03) || '"';
			ELSE
				vr_ds_corresp_se03 := '"' || RTRIM(pa_ds_corresp_se03) || '"';
			END IF;
		END IF;
		IF pn_ano_sem04 IS NULL
		AND pa_ano_sem04 IS NULL THEN
			vr_ano_sem04 := 'null';
		END IF;
		IF pn_ano_sem04 IS NULL
		AND pa_ano_sem04 IS NOT NULL THEN
			vr_ano_sem04 := 'null';
		END IF;
		IF pn_ano_sem04 IS NOT NULL
		AND pa_ano_sem04 IS NULL THEN
			vr_ano_sem04 := '"' || RTRIM(pn_ano_sem04) || '"';
		END IF;
		IF pn_ano_sem04 IS NOT NULL
		AND pa_ano_sem04 IS NOT NULL THEN
			IF pa_ano_sem04 <> pn_ano_sem04 THEN
				vr_ano_sem04 := '"' || RTRIM(pn_ano_sem04) || '"';
			ELSE
				vr_ano_sem04 := '"' || RTRIM(pa_ano_sem04) || '"';
			END IF;
		END IF;
		IF pn_ds_corresp_oc05 IS NULL
		AND pa_ds_corresp_oc05 IS NULL THEN
			vr_ds_corresp_oc05 := 'null';
		END IF;
		IF pn_ds_corresp_oc05 IS NULL
		AND pa_ds_corresp_oc05 IS NOT NULL THEN
			vr_ds_corresp_oc05 := 'null';
		END IF;
		IF pn_ds_corresp_oc05 IS NOT NULL
		AND pa_ds_corresp_oc05 IS NULL THEN
			vr_ds_corresp_oc05 := '"' || RTRIM(pn_ds_corresp_oc05) || '"';
		END IF;
		IF pn_ds_corresp_oc05 IS NOT NULL
		AND pa_ds_corresp_oc05 IS NOT NULL THEN
			IF pa_ds_corresp_oc05 <> pn_ds_corresp_oc05 THEN
				vr_ds_corresp_oc05 := '"' || RTRIM(pn_ds_corresp_oc05) || '"';
			ELSE
				vr_ds_corresp_oc05 := '"' || RTRIM(pa_ds_corresp_oc05) || '"';
			END IF;
		END IF;
		IF pn_ds_corresp_ap06 IS NULL
		AND pa_ds_corresp_ap06 IS NULL THEN
			vr_ds_corresp_ap06 := 'null';
		END IF;
		IF pn_ds_corresp_ap06 IS NULL
		AND pa_ds_corresp_ap06 IS NOT NULL THEN
			vr_ds_corresp_ap06 := 'null';
		END IF;
		IF pn_ds_corresp_ap06 IS NOT NULL
		AND pa_ds_corresp_ap06 IS NULL THEN
			vr_ds_corresp_ap06 := '"' || RTRIM(pn_ds_corresp_ap06) || '"';
		END IF;
		IF pn_ds_corresp_ap06 IS NOT NULL
		AND pa_ds_corresp_ap06 IS NOT NULL THEN
			IF pa_ds_corresp_ap06 <> pn_ds_corresp_ap06 THEN
				vr_ds_corresp_ap06 := '"' || RTRIM(pn_ds_corresp_ap06) || '"';
			ELSE
				vr_ds_corresp_ap06 := '"' || RTRIM(pa_ds_corresp_ap06) || '"';
			END IF;
		END IF;
		IF pn_ds_corresp_re07 IS NULL
		AND pa_ds_corresp_re07 IS NULL THEN
			vr_ds_corresp_re07 := 'null';
		END IF;
		IF pn_ds_corresp_re07 IS NULL
		AND pa_ds_corresp_re07 IS NOT NULL THEN
			vr_ds_corresp_re07 := 'null';
		END IF;
		IF pn_ds_corresp_re07 IS NOT NULL
		AND pa_ds_corresp_re07 IS NULL THEN
			vr_ds_corresp_re07 := '"' || RTRIM(pn_ds_corresp_re07) || '"';
		END IF;
		IF pn_ds_corresp_re07 IS NOT NULL
		AND pa_ds_corresp_re07 IS NOT NULL THEN
			IF pa_ds_corresp_re07 <> pn_ds_corresp_re07 THEN
				vr_ds_corresp_re07 := '"' || RTRIM(pn_ds_corresp_re07) || '"';
			ELSE
				vr_ds_corresp_re07 := '"' || RTRIM(pa_ds_corresp_re07) || '"';
			END IF;
		END IF;
		IF pn_ds_corresp_ad08 IS NULL
		AND pa_ds_corresp_ad08 IS NULL THEN
			vr_ds_corresp_ad08 := 'null';
		END IF;
		IF pn_ds_corresp_ad08 IS NULL
		AND pa_ds_corresp_ad08 IS NOT NULL THEN
			vr_ds_corresp_ad08 := 'null';
		END IF;
		IF pn_ds_corresp_ad08 IS NOT NULL
		AND pa_ds_corresp_ad08 IS NULL THEN
			vr_ds_corresp_ad08 := '"' || RTRIM(pn_ds_corresp_ad08) || '"';
		END IF;
		IF pn_ds_corresp_ad08 IS NOT NULL
		AND pa_ds_corresp_ad08 IS NOT NULL THEN
			IF pa_ds_corresp_ad08 <> pn_ds_corresp_ad08 THEN
				vr_ds_corresp_ad08 := '"' || RTRIM(pn_ds_corresp_ad08) || '"';
			ELSE
				vr_ds_corresp_ad08 := '"' || RTRIM(pa_ds_corresp_ad08) || '"';
			END IF;
		END IF;
		IF pn_ds_corresp_fu09 IS NULL
		AND pa_ds_corresp_fu09 IS NULL THEN
			vr_ds_corresp_fu09 := 'null';
		END IF;
		IF pn_ds_corresp_fu09 IS NULL
		AND pa_ds_corresp_fu09 IS NOT NULL THEN
			vr_ds_corresp_fu09 := 'null';
		END IF;
		IF pn_ds_corresp_fu09 IS NOT NULL
		AND pa_ds_corresp_fu09 IS NULL THEN
			vr_ds_corresp_fu09 := '"' || RTRIM(pn_ds_corresp_fu09) || '"';
		END IF;
		IF pn_ds_corresp_fu09 IS NOT NULL
		AND pa_ds_corresp_fu09 IS NOT NULL THEN
			IF pa_ds_corresp_fu09 <> pn_ds_corresp_fu09 THEN
				vr_ds_corresp_fu09 := '"' || RTRIM(pn_ds_corresp_fu09) || '"';
			ELSE
				vr_ds_corresp_fu09 := '"' || RTRIM(pa_ds_corresp_fu09) || '"';
			END IF;
		END IF;
		IF pn_ds_corresp_cu10 IS NULL
		AND pa_ds_corresp_cu10 IS NULL THEN
			vr_ds_corresp_cu10 := 'null';
		END IF;
		IF pn_ds_corresp_cu10 IS NULL
		AND pa_ds_corresp_cu10 IS NOT NULL THEN
			vr_ds_corresp_cu10 := 'null';
		END IF;
		IF pn_ds_corresp_cu10 IS NOT NULL
		AND pa_ds_corresp_cu10 IS NULL THEN
			vr_ds_corresp_cu10 := '"' || RTRIM(pn_ds_corresp_cu10) || '"';
		END IF;
		IF pn_ds_corresp_cu10 IS NOT NULL
		AND pa_ds_corresp_cu10 IS NOT NULL THEN
			IF pa_ds_corresp_cu10 <> pn_ds_corresp_cu10 THEN
				vr_ds_corresp_cu10 := '"' || RTRIM(pn_ds_corresp_cu10) || '"';
			ELSE
				vr_ds_corresp_cu10 := '"' || RTRIM(pa_ds_corresp_cu10) || '"';
			END IF;
		END IF;
		IF pn_ds_abrev_curs11 IS NULL
		AND pa_ds_abrev_curs11 IS NULL THEN
			vr_ds_abrev_curs11 := 'null';
		END IF;
		IF pn_ds_abrev_curs11 IS NULL
		AND pa_ds_abrev_curs11 IS NOT NULL THEN
			vr_ds_abrev_curs11 := 'null';
		END IF;
		IF pn_ds_abrev_curs11 IS NOT NULL
		AND pa_ds_abrev_curs11 IS NULL THEN
			vr_ds_abrev_curs11 := '"' || RTRIM(pn_ds_abrev_curs11) || '"';
		END IF;
		IF pn_ds_abrev_curs11 IS NOT NULL
		AND pa_ds_abrev_curs11 IS NOT NULL THEN
			IF pa_ds_abrev_curs11 <> pn_ds_abrev_curs11 THEN
				vr_ds_abrev_curs11 := '"' || RTRIM(pn_ds_abrev_curs11) || '"';
			ELSE
				vr_ds_abrev_curs11 := '"' || RTRIM(pa_ds_abrev_curs11) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_grau_serie set DS_CORRESPONDENTE_GRAU = ' || RTRIM(vr_ds_corresp_gr00) || '  , co_curso = ' || RTRIM(vr_co_curso01) || '  , co_unidade = ' || RTRIM(vr_co_unidade02) || '  , DS_CORRESPONDENTE_SERIE = ' || RTRIM(vr_ds_corresp_se03) || '  , ano_sem = ' || RTRIM(vr_ano_sem04) || '  , DS_CORRESPONDENTE_OCOR = ' || RTRIM(vr_ds_corresp_oc05) || '  , DS_CORRESPONDENTE_APROV = ' || RTRIM(vr_ds_corresp_ap06);
		v_sql2 := '  , DS_CORRESPONDENTE_REPR = ' || RTRIM(vr_ds_corresp_re07) || '  , DS_CORRESPONDENTE_ADAPT = ' || RTRIM(vr_ds_corresp_ad08) || '  , DS_CORRESPONDENTE_FUNCIONARIO = ' || RTRIM(vr_ds_corresp_fu09) || '  , ds_corresp_curso = ' || RTRIM(vr_ds_corresp_cu10) || '  , ds_abrev_curso = ' || RTRIM(vr_ds_abrev_curs11);
		v_sql3 := ' where co_curso = ' || RTRIM(vr_co_curso01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || '  and ano_sem = ' || RTRIM(vr_ano_sem04) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3;
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
		       's_grau_serie',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_grau_ser108;
/

