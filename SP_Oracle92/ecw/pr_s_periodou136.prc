CREATE OR REPLACE PROCEDURE pr_s_periodou136(
	P_OP_IN                CHAR,
	PA_ano_sem00_IN        s_periodounidade.ano_sem%TYPE,
	PA_co_unidade01_IN     s_periodounidade.co_unidade%TYPE,
	PA_tp_ano_letivo02_IN  s_periodounidade.tp_ano_letivo%TYPE,
	PN_ano_sem00_IN        s_periodounidade.ano_sem%TYPE,
	PN_co_unidade01_IN     s_periodounidade.co_unidade%TYPE,
	PN_tp_ano_letivo02_IN  s_periodounidade.tp_ano_letivo%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_ano_sem00        s_periodounidade.ano_sem%TYPE := PA_ano_sem00_IN;
PA_co_unidade01     s_periodounidade.co_unidade%TYPE := PA_co_unidade01_IN;
PA_tp_ano_letivo02  s_periodounidade.tp_ano_letivo%TYPE := PA_tp_ano_letivo02_IN;
PN_ano_sem00        s_periodounidade.ano_sem%TYPE := PN_ano_sem00_IN;
PN_co_unidade01     s_periodounidade.co_unidade%TYPE := PN_co_unidade01_IN;
PN_tp_ano_letivo02  s_periodounidade.tp_ano_letivo%TYPE := PN_tp_ano_letivo02_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_ano_sem00        CHAR(10);
vr_co_unidade01     CHAR(10);
vr_tp_ano_letivo02  CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_ano_sem00 IS NULL THEN
			vr_ano_sem00 := 'null';
		ELSE
			vr_ano_sem00 := pn_ano_sem00;
		END IF;
		IF pn_co_unidade01 IS NULL THEN
			vr_co_unidade01 := 'null';
		ELSE
			vr_co_unidade01 := pn_co_unidade01;
		END IF;
		IF pn_tp_ano_letivo02 IS NULL THEN
			vr_tp_ano_letivo02 := 'null';
		ELSE
			vr_tp_ano_letivo02 := pn_tp_ano_letivo02;
		END IF;
		v_sql1 := 'insert into s_periodounidade(ano_sem, co_unidade, tp_ano_letivo) values (';
		v_sql2 := '"' || RTRIM(vr_ano_sem00) || '"' || ',' || '"' || RTRIM(vr_co_unidade01) || '"' || ',' || '"' || RTRIM(vr_tp_ano_letivo02) || '"' || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_ano_sem00 IS NULL THEN
			vr_ano_sem00 := 'null';
		ELSE
			vr_ano_sem00 := '"' || RTRIM(pa_ano_sem00) || '"';
		END IF;
		IF pa_co_unidade01 IS NULL THEN
			vr_co_unidade01 := 'null';
		ELSE
			vr_co_unidade01 := '"' || RTRIM(pa_co_unidade01) || '"';
		END IF;
		v_sql1 := '  delete from s_periodounidade where ano_sem = ' || RTRIM(vr_ano_sem00) || '  and co_unidade = ' || RTRIM(vr_co_unidade01) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_ano_sem00 IS NULL
		AND pa_ano_sem00 IS NULL THEN
			vr_ano_sem00 := 'null';
		END IF;
		IF pn_ano_sem00 IS NULL
		AND pa_ano_sem00 IS NOT NULL THEN
			vr_ano_sem00 := 'null';
		END IF;
		IF pn_ano_sem00 IS NOT NULL
		AND pa_ano_sem00 IS NULL THEN
			vr_ano_sem00 := '"' || RTRIM(pn_ano_sem00) || '"';
		END IF;
		IF pn_ano_sem00 IS NOT NULL
		AND pa_ano_sem00 IS NOT NULL THEN
			IF pa_ano_sem00 <> pn_ano_sem00 THEN
				vr_ano_sem00 := '"' || RTRIM(pn_ano_sem00) || '"';
			ELSE
				vr_ano_sem00 := '"' || RTRIM(pa_ano_sem00) || '"';
			END IF;
		END IF;
		IF pn_co_unidade01 IS NULL
		AND pa_co_unidade01 IS NULL THEN
			vr_co_unidade01 := 'null';
		END IF;
		IF pn_co_unidade01 IS NULL
		AND pa_co_unidade01 IS NOT NULL THEN
			vr_co_unidade01 := 'null';
		END IF;
		IF pn_co_unidade01 IS NOT NULL
		AND pa_co_unidade01 IS NULL THEN
			vr_co_unidade01 := '"' || RTRIM(pn_co_unidade01) || '"';
		END IF;
		IF pn_co_unidade01 IS NOT NULL
		AND pa_co_unidade01 IS NOT NULL THEN
			IF pa_co_unidade01 <> pn_co_unidade01 THEN
				vr_co_unidade01 := '"' || RTRIM(pn_co_unidade01) || '"';
			ELSE
				vr_co_unidade01 := '"' || RTRIM(pa_co_unidade01) || '"';
			END IF;
		END IF;
		IF pn_tp_ano_letivo02 IS NULL
		AND pa_tp_ano_letivo02 IS NULL THEN
			vr_tp_ano_letivo02 := 'null';
		END IF;
		IF pn_tp_ano_letivo02 IS NULL
		AND pa_tp_ano_letivo02 IS NOT NULL THEN
			vr_tp_ano_letivo02 := 'null';
		END IF;
		IF pn_tp_ano_letivo02 IS NOT NULL
		AND pa_tp_ano_letivo02 IS NULL THEN
			vr_tp_ano_letivo02 := '"' || RTRIM(pn_tp_ano_letivo02) || '"';
		END IF;
		IF pn_tp_ano_letivo02 IS NOT NULL
		AND pa_tp_ano_letivo02 IS NOT NULL THEN
			IF pa_tp_ano_letivo02 <> pn_tp_ano_letivo02 THEN
				vr_tp_ano_letivo02 := '"' || RTRIM(pn_tp_ano_letivo02) || '"';
			ELSE
				vr_tp_ano_letivo02 := '"' || RTRIM(pa_tp_ano_letivo02) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_periodounidade set ano_sem = ' || RTRIM(vr_ano_sem00) || '  , co_unidade = ' || RTRIM(vr_co_unidade01) || '  , tp_ano_letivo = ' || RTRIM(vr_tp_ano_letivo02);
		v_sql2 := ' where ano_sem = ' || RTRIM(vr_ano_sem00) || '  and co_unidade = ' || RTRIM(vr_co_unidade01) || ';';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	END IF;
	IF p_op = 'del' THEN
		v_uni := pa_co_unidade01;
	ELSE
		v_uni := pn_co_unidade01;
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
		       's_periodounidade',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_periodou136;
/

