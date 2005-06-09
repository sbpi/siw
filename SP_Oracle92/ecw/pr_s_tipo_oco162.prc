CREATE OR REPLACE PROCEDURE pr_s_tipo_oco162(
	P_OP_IN                CHAR,
	PA_co_tipo_ocorr00_IN  s_tipo_ocorrencia.co_tipo_ocorrencia%TYPE,
	PA_ds_tipo_ocorr01_IN  s_tipo_ocorrencia.ds_tipo_ocorrencia%TYPE,
	PA_co_unidade02_IN     s_tipo_ocorrencia.co_unidade%TYPE,
	PN_co_tipo_ocorr00_IN  s_tipo_ocorrencia.co_tipo_ocorrencia%TYPE,
	PN_ds_tipo_ocorr01_IN  s_tipo_ocorrencia.ds_tipo_ocorrencia%TYPE,
	PN_co_unidade02_IN     s_tipo_ocorrencia.co_unidade%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_tipo_ocorr00  s_tipo_ocorrencia.co_tipo_ocorrencia%TYPE := PA_co_tipo_ocorr00_IN;
PA_ds_tipo_ocorr01  s_tipo_ocorrencia.ds_tipo_ocorrencia%TYPE := PA_ds_tipo_ocorr01_IN;
PA_co_unidade02     s_tipo_ocorrencia.co_unidade%TYPE := PA_co_unidade02_IN;
PN_co_tipo_ocorr00  s_tipo_ocorrencia.co_tipo_ocorrencia%TYPE := PN_co_tipo_ocorr00_IN;
PN_ds_tipo_ocorr01  s_tipo_ocorrencia.ds_tipo_ocorrencia%TYPE := PN_ds_tipo_ocorr01_IN;
PN_co_unidade02     s_tipo_ocorrencia.co_unidade%TYPE := PN_co_unidade02_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_tipo_ocorr00  CHAR(10);
vr_ds_tipo_ocorr01  CHAR(60);
vr_co_unidade02     CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_tipo_ocorr00 IS NULL THEN
			vr_co_tipo_ocorr00 := 'null';
		ELSE
			vr_co_tipo_ocorr00 := pn_co_tipo_ocorr00;
		END IF;
		IF pn_ds_tipo_ocorr01 IS NULL THEN
			vr_ds_tipo_ocorr01 := 'null';
		ELSE
			vr_ds_tipo_ocorr01 := pn_ds_tipo_ocorr01;
		END IF;
		IF pn_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := pn_co_unidade02;
		END IF;
		v_sql1 := 'insert into s_tipo_ocorrencia(co_tipo_ocorrencia, ds_tipo_ocorrencia, co_unidade) values (';
		v_sql2 := RTRIM(vr_co_tipo_ocorr00) || ',' || '"' || RTRIM(vr_ds_tipo_ocorr01) || '"' || ',' || '"' || RTRIM(vr_co_unidade02) || '"' || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_tipo_ocorr00 IS NULL THEN
			vr_co_tipo_ocorr00 := 'null';
		ELSE
			vr_co_tipo_ocorr00 := pa_co_tipo_ocorr00;
		END IF;
		IF pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := '"' || RTRIM(pa_co_unidade02) || '"';
		END IF;
		v_sql1 := '  delete from s_tipo_ocorrencia where co_tipo_ocorrencia = ' || RTRIM(vr_co_tipo_ocorr00) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_tipo_ocorr00 IS NULL
		AND pa_co_tipo_ocorr00 IS NULL THEN
			vr_co_tipo_ocorr00 := 'null';
		END IF;
		IF pn_co_tipo_ocorr00 IS NULL
		AND pa_co_tipo_ocorr00 IS NOT NULL THEN
			vr_co_tipo_ocorr00 := 'null';
		END IF;
		IF pn_co_tipo_ocorr00 IS NOT NULL
		AND pa_co_tipo_ocorr00 IS NULL THEN
			vr_co_tipo_ocorr00 := pn_co_tipo_ocorr00;
		END IF;
		IF pn_co_tipo_ocorr00 IS NOT NULL
		AND pa_co_tipo_ocorr00 IS NOT NULL THEN
			IF pa_co_tipo_ocorr00 <> pn_co_tipo_ocorr00 THEN
				vr_co_tipo_ocorr00 := pn_co_tipo_ocorr00;
			ELSE
				vr_co_tipo_ocorr00 := pa_co_tipo_ocorr00;
			END IF;
		END IF;
		IF pn_ds_tipo_ocorr01 IS NULL
		AND pa_ds_tipo_ocorr01 IS NULL THEN
			vr_ds_tipo_ocorr01 := 'null';
		END IF;
		IF pn_ds_tipo_ocorr01 IS NULL
		AND pa_ds_tipo_ocorr01 IS NOT NULL THEN
			vr_ds_tipo_ocorr01 := 'null';
		END IF;
		IF pn_ds_tipo_ocorr01 IS NOT NULL
		AND pa_ds_tipo_ocorr01 IS NULL THEN
			vr_ds_tipo_ocorr01 := '"' || RTRIM(pn_ds_tipo_ocorr01) || '"';
		END IF;
		IF pn_ds_tipo_ocorr01 IS NOT NULL
		AND pa_ds_tipo_ocorr01 IS NOT NULL THEN
			IF pa_ds_tipo_ocorr01 <> pn_ds_tipo_ocorr01 THEN
				vr_ds_tipo_ocorr01 := '"' || RTRIM(pn_ds_tipo_ocorr01) || '"';
			ELSE
				vr_ds_tipo_ocorr01 := '"' || RTRIM(pa_ds_tipo_ocorr01) || '"';
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
		v_sql1 := 'update s_tipo_ocorrencia set co_tipo_ocorrencia = ' || RTRIM(vr_co_tipo_ocorr00) || '  , ds_tipo_ocorrencia = ' || RTRIM(vr_ds_tipo_ocorr01) || '  , co_unidade = ' || RTRIM(vr_co_unidade02);
		v_sql2 := ' where co_tipo_ocorrencia = ' || RTRIM(vr_co_tipo_ocorr00) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
		v_sql3 := '';
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
		       's_tipo_ocorrencia',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_tipo_oco162;
/

