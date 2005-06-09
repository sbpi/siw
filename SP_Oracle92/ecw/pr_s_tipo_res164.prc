CREATE OR REPLACE PROCEDURE pr_s_tipo_res164(
	P_OP_IN                CHAR,
	PA_co_tip_respon00_IN  s_tipo_responsavel.co_tip_responsavel%TYPE,
	PA_ds_tip_respon01_IN  s_tipo_responsavel.ds_tip_responsavel%TYPE,
	PA_co_unidade02_IN     s_unidade.co_unidade%TYPE,
	PN_co_tip_respon00_IN  s_tipo_responsavel.co_tip_responsavel%TYPE,
	PN_ds_tip_respon01_IN  s_tipo_responsavel.ds_tip_responsavel%TYPE,
	PN_co_unidade02_IN     s_unidade.co_unidade%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_tip_respon00  s_tipo_responsavel.co_tip_responsavel%TYPE := PA_co_tip_respon00_IN;
PA_ds_tip_respon01  s_tipo_responsavel.ds_tip_responsavel%TYPE := PA_ds_tip_respon01_IN;
PA_co_unidade02     s_unidade.co_unidade%TYPE := PA_co_unidade02_IN;
PN_co_tip_respon00  s_tipo_responsavel.co_tip_responsavel%TYPE := PN_co_tip_respon00_IN;
PN_ds_tip_respon01  s_tipo_responsavel.ds_tip_responsavel%TYPE := PN_ds_tip_respon01_IN;
PN_co_unidade02     s_unidade.co_unidade%TYPE := PN_co_unidade02_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_tip_respon00  CHAR(10);
vr_ds_tip_respon01  CHAR(100);
vr_co_unidade02     CHAR(100);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_tip_respon00 IS NULL THEN
			vr_co_tip_respon00 := 'null';
		ELSE
			vr_co_tip_respon00 := pn_co_tip_respon00;
		END IF;
		IF pn_ds_tip_respon01 IS NULL THEN
			vr_ds_tip_respon01 := 'null';
		ELSE
			vr_ds_tip_respon01 := pn_ds_tip_respon01;
		END IF;
		IF pn_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := pn_co_unidade02;
		END IF;
		v_sql1 := 'insert into s_tipo_responsavel(CO_TIPO_RESPONSAVEL, DS_TIPO_RESPONSAVEL, co_unidade) values (';
		v_sql2 := RTRIM(vr_co_tip_respon00) || ',' || '"' || RTRIM(vr_ds_tip_respon01) || '"' || ',' || '"' || RTRIM(vr_co_unidade02) || '"' || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_tip_respon00 IS NULL THEN
			vr_co_tip_respon00 := 'null';
		ELSE
			vr_co_tip_respon00 := pa_co_tip_respon00;
		END IF;
		IF pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := '"' || RTRIM(pa_co_unidade02) || '"';
		END IF;
		v_sql1 := '  delete from s_tipo_responsavel where CO_TIPO_RESPONSAVEL = ' || RTRIM(vr_co_tip_respon00) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_tip_respon00 IS NULL
		AND pa_co_tip_respon00 IS NULL THEN
			vr_co_tip_respon00 := 'null';
		END IF;
		IF pn_co_tip_respon00 IS NULL
		AND pa_co_tip_respon00 IS NOT NULL THEN
			vr_co_tip_respon00 := 'null';
		END IF;
		IF pn_co_tip_respon00 IS NOT NULL
		AND pa_co_tip_respon00 IS NULL THEN
			vr_co_tip_respon00 := pn_co_tip_respon00;
		END IF;
		IF pn_co_tip_respon00 IS NOT NULL
		AND pa_co_tip_respon00 IS NOT NULL THEN
			IF pa_co_tip_respon00 <> pn_co_tip_respon00 THEN
				vr_co_tip_respon00 := pn_co_tip_respon00;
			ELSE
				vr_co_tip_respon00 := pa_co_tip_respon00;
			END IF;
		END IF;
		IF pn_ds_tip_respon01 IS NULL
		AND pa_ds_tip_respon01 IS NULL THEN
			vr_ds_tip_respon01 := 'null';
		END IF;
		IF pn_ds_tip_respon01 IS NULL
		AND pa_ds_tip_respon01 IS NOT NULL THEN
			vr_ds_tip_respon01 := 'null';
		END IF;
		IF pn_ds_tip_respon01 IS NOT NULL
		AND pa_ds_tip_respon01 IS NULL THEN
			vr_ds_tip_respon01 := '"' || RTRIM(pn_ds_tip_respon01) || '"';
		END IF;
		IF pn_ds_tip_respon01 IS NOT NULL
		AND pa_ds_tip_respon01 IS NOT NULL THEN
			IF pa_ds_tip_respon01 <> pn_ds_tip_respon01 THEN
				vr_ds_tip_respon01 := '"' || RTRIM(pn_ds_tip_respon01) || '"';
			ELSE
				vr_ds_tip_respon01 := '"' || RTRIM(pa_ds_tip_respon01) || '"';
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
		v_sql1 := 'update s_tipo_responsavel set CO_TIPO_RESPONSAVEL = ' || RTRIM(vr_co_tip_respon00) || '  , DS_TIPO_RESPONSAVEL = ' || RTRIM(vr_ds_tip_respon01) || '  , co_unidade = ' || RTRIM(vr_co_unidade02);
		v_sql2 := ' where co_tip_responsavel = ' || RTRIM(vr_co_tip_respon00) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
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
		       's_tipo_responsavel',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_tipo_res164;
/

