CREATE OR REPLACE PROCEDURE pr_s_escola094(
	P_OP_IN                CHAR,
	PA_co_unidade00_IN     s_escola.co_unidade%TYPE,
	PA_ds_escola01_IN      s_escola.ds_escola%TYPE,
	PA_co_sigre02_IN       s_escola.co_sigre%TYPE,
	PN_co_unidade00_IN     s_escola.co_unidade%TYPE,
	PN_ds_escola01_IN      s_escola.ds_escola%TYPE,
	PN_co_sigre02_IN       s_escola.co_sigre%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_unidade00     s_escola.co_unidade%TYPE := PA_co_unidade00_IN;
PA_ds_escola01      s_escola.ds_escola%TYPE := PA_ds_escola01_IN;
PA_co_sigre02       s_escola.co_sigre%TYPE := PA_co_sigre02_IN;
PN_co_unidade00     s_escola.co_unidade%TYPE := PN_co_unidade00_IN;
PN_ds_escola01      s_escola.ds_escola%TYPE := PN_ds_escola01_IN;
PN_co_sigre02       s_escola.co_sigre%TYPE := PN_co_sigre02_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_unidade00     CHAR(10);
vr_ds_escola01      CHAR(70);
vr_co_sigre02       CHAR(25);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := pn_co_unidade00;
		END IF;
		IF pn_ds_escola01 IS NULL THEN
			vr_ds_escola01 := 'null';
		ELSE
			vr_ds_escola01 := pn_ds_escola01;
		END IF;
		IF pn_co_sigre02 IS NULL THEN
			vr_co_sigre02 := 'null';
		ELSE
			vr_co_sigre02 := pn_co_sigre02;
		END IF;
		v_sql1 := 'insert into s_escola(co_unidade, ds_escola, co_sigre) values (';
		v_sql2 := '"' || RTRIM(vr_co_unidade00) || '"' || ',' || '"' || RTRIM(vr_ds_escola01) || '"' || ',' || '"' || RTRIM(vr_co_sigre02) || '"' || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := '"' || RTRIM(pa_co_unidade00) || '"';
		END IF;
		v_sql1 := '  delete from s_escola where co_unidade = ' || RTRIM(vr_co_unidade00) || ';';
		v_sql2 := '';
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
		IF pn_ds_escola01 IS NULL
		AND pa_ds_escola01 IS NULL THEN
			vr_ds_escola01 := 'null';
		END IF;
		IF pn_ds_escola01 IS NULL
		AND pa_ds_escola01 IS NOT NULL THEN
			vr_ds_escola01 := 'null';
		END IF;
		IF pn_ds_escola01 IS NOT NULL
		AND pa_ds_escola01 IS NULL THEN
			vr_ds_escola01 := '"' || RTRIM(pn_ds_escola01) || '"';
		END IF;
		IF pn_ds_escola01 IS NOT NULL
		AND pa_ds_escola01 IS NOT NULL THEN
			IF pa_ds_escola01 <> pn_ds_escola01 THEN
				vr_ds_escola01 := '"' || RTRIM(pn_ds_escola01) || '"';
			ELSE
				vr_ds_escola01 := '"' || RTRIM(pa_ds_escola01) || '"';
			END IF;
		END IF;
		IF pn_co_sigre02 IS NULL
		AND pa_co_sigre02 IS NULL THEN
			vr_co_sigre02 := 'null';
		END IF;
		IF pn_co_sigre02 IS NULL
		AND pa_co_sigre02 IS NOT NULL THEN
			vr_co_sigre02 := 'null';
		END IF;
		IF pn_co_sigre02 IS NOT NULL
		AND pa_co_sigre02 IS NULL THEN
			vr_co_sigre02 := '"' || RTRIM(pn_co_sigre02) || '"';
		END IF;
		IF pn_co_sigre02 IS NOT NULL
		AND pa_co_sigre02 IS NOT NULL THEN
			IF pa_co_sigre02 <> pn_co_sigre02 THEN
				vr_co_sigre02 := '"' || RTRIM(pn_co_sigre02) || '"';
			ELSE
				vr_co_sigre02 := '"' || RTRIM(pa_co_sigre02) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_escola set co_unidade = ' || RTRIM(vr_co_unidade00) || '  , ds_escola = ' || RTRIM(vr_ds_escola01) || '  , co_sigre = ' || RTRIM(vr_co_sigre02);
		v_sql2 := ' where co_unidade = ' || RTRIM(vr_co_unidade00) || ';';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
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
		       's_escola',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_escola094;
/

