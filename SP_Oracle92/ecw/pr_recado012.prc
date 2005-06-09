CREATE OR REPLACE PROCEDURE pr_recado012(
	P_OP_IN                CHAR,
	PA_rec_sequencia00_IN  recado.rec_sequencial%TYPE,
	PA_rec_dataenvio01_IN  recado.rec_dataenvio%TYPE,
	PA_co_unidade02_IN     recado.co_unidade%TYPE,
	PA_rec_dataleitu03_IN  recado.rec_dataleitura%TYPE,
	PA_rec_memo04_IN       recado.rec_memo%TYPE,
	PA_rec_de05_IN         recado.rec_de%TYPE,
	PA_rec_para06_IN       recado.rec_para%TYPE,
	PN_rec_sequencia00_IN  recado.rec_sequencial%TYPE,
	PN_rec_dataenvio01_IN  recado.rec_dataenvio%TYPE,
	PN_co_unidade02_IN     recado.co_unidade%TYPE,
	PN_rec_dataleitu03_IN  recado.rec_dataleitura%TYPE,
	PN_rec_memo04_IN       recado.rec_memo%TYPE,
	PN_rec_de05_IN         recado.rec_de%TYPE,
	PN_rec_para06_IN       recado.rec_para%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_rec_sequencia00  recado.rec_sequencial%TYPE := PA_rec_sequencia00_IN;
PA_rec_dataenvio01  recado.rec_dataenvio%TYPE := PA_rec_dataenvio01_IN;
PA_co_unidade02     recado.co_unidade%TYPE := PA_co_unidade02_IN;
PA_rec_dataleitu03  recado.rec_dataleitura%TYPE := PA_rec_dataleitu03_IN;
PA_rec_memo04       recado.rec_memo%TYPE := PA_rec_memo04_IN;
PA_rec_de05         recado.rec_de%TYPE := PA_rec_de05_IN;
PA_rec_para06       recado.rec_para%TYPE := PA_rec_para06_IN;
PN_rec_sequencia00  recado.rec_sequencial%TYPE := PN_rec_sequencia00_IN;
PN_rec_dataenvio01  recado.rec_dataenvio%TYPE := PN_rec_dataenvio01_IN;
PN_co_unidade02     recado.co_unidade%TYPE := PN_co_unidade02_IN;
PN_rec_dataleitu03  recado.rec_dataleitura%TYPE := PN_rec_dataleitu03_IN;
PN_rec_memo04       recado.rec_memo%TYPE := PN_rec_memo04_IN;
PN_rec_de05         recado.rec_de%TYPE := PN_rec_de05_IN;
PN_rec_para06       recado.rec_para%TYPE := PN_rec_para06_IN;
v_blob1             recado.rec_memo%TYPE;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_rec_sequencia00  CHAR(10);
vr_rec_dataenvio01  CHAR(40);
vr_co_unidade02     CHAR(10);
vr_rec_dataleitu03  CHAR(40);
vr_rec_memo04       CHAR(10);
vr_rec_de05         CHAR(40);
vr_rec_para06       CHAR(40);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	v_blob1 := NULL;
	IF p_op = 'ins' THEN
		IF pn_rec_sequencia00 IS NULL THEN
			vr_rec_sequencia00 := 'null';
		ELSE
			vr_rec_sequencia00 := pn_rec_sequencia00;
		END IF;
		IF pn_rec_dataenvio01 IS NULL THEN
			vr_rec_dataenvio01 := 'null';
		ELSE
			vr_rec_dataenvio01 := pn_rec_dataenvio01;
		END IF;
		IF pn_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := pn_co_unidade02;
		END IF;
		IF pn_rec_dataleitu03 IS NULL THEN
			vr_rec_dataleitu03 := 'null';
		ELSE
			vr_rec_dataleitu03 := pn_rec_dataleitu03;
		END IF;
		IF pn_rec_memo04 IS NULL THEN
			vr_rec_memo04 := NULL;
		ELSE
			vr_rec_memo04 := ':vblob1';
		END IF;
		v_blob1 := pn_rec_memo04;
		IF pn_rec_de05 IS NULL THEN
			vr_rec_de05 := 'null';
		ELSE
			vr_rec_de05 := pn_rec_de05;
		END IF;
		IF pn_rec_para06 IS NULL THEN
			vr_rec_para06 := 'null';
		ELSE
			vr_rec_para06 := pn_rec_para06;
		END IF;
		v_sql1 := 'insert into recado(rec_sequencial, rec_dataenvio, co_unidade, rec_dataleitura, rec_memo, rec_de, rec_para) values (';
		v_sql2 := RTRIM(vr_rec_sequencia00) || ',' || '"' || vr_rec_dataenvio01 || '"' || ',' || '"' || RTRIM(vr_co_unidade02) || '"' || ',' || '"' || vr_rec_dataleitu03 || '"' || ',' || RTRIM(vr_rec_memo04) || ',' || '"' || RTRIM(vr_rec_de05) || '"' || ',' || '"' || RTRIM(vr_rec_para06) || '"' || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_rec_sequencia00 IS NULL THEN
			vr_rec_sequencia00 := 'null';
		ELSE
			vr_rec_sequencia00 := pa_rec_sequencia00;
		END IF;
		IF pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := '"' || RTRIM(pa_co_unidade02) || '"';
		END IF;
		v_sql1 := '  delete from recado where rec_sequencial = ' || RTRIM(vr_rec_sequencia00) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_rec_sequencia00 IS NULL
		AND pa_rec_sequencia00 IS NULL THEN
			vr_rec_sequencia00 := 'null';
		END IF;
		IF pn_rec_sequencia00 IS NULL
		AND pa_rec_sequencia00 IS NOT NULL THEN
			vr_rec_sequencia00 := 'null';
		END IF;
		IF pn_rec_sequencia00 IS NOT NULL
		AND pa_rec_sequencia00 IS NULL THEN
			vr_rec_sequencia00 := pn_rec_sequencia00;
		END IF;
		IF pn_rec_sequencia00 IS NOT NULL
		AND pa_rec_sequencia00 IS NOT NULL THEN
			IF pa_rec_sequencia00 <> pn_rec_sequencia00 THEN
				vr_rec_sequencia00 := pn_rec_sequencia00;
			ELSE
				vr_rec_sequencia00 := pa_rec_sequencia00;
			END IF;
		END IF;
		IF pn_rec_dataenvio01 IS NULL
		AND pa_rec_dataenvio01 IS NULL THEN
			vr_rec_dataenvio01 := 'null';
		END IF;
		IF pn_rec_dataenvio01 IS NULL
		AND pa_rec_dataenvio01 IS NOT NULL THEN
			vr_rec_dataenvio01 := 'null';
		END IF;
		IF pn_rec_dataenvio01 IS NOT NULL
		AND pa_rec_dataenvio01 IS NULL THEN
			vr_rec_dataenvio01 := '"' || pn_rec_dataenvio01 || '"';
		END IF;
		IF pn_rec_dataenvio01 IS NOT NULL
		AND pa_rec_dataenvio01 IS NOT NULL THEN
			IF pa_rec_dataenvio01 <> pn_rec_dataenvio01 THEN
				vr_rec_dataenvio01 := '"' || pn_rec_dataenvio01 || '"';
			ELSE
				vr_rec_dataenvio01 := '"' || pa_rec_dataenvio01 || '"';
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
		IF pn_rec_dataleitu03 IS NULL
		AND pa_rec_dataleitu03 IS NULL THEN
			vr_rec_dataleitu03 := 'null';
		END IF;
		IF pn_rec_dataleitu03 IS NULL
		AND pa_rec_dataleitu03 IS NOT NULL THEN
			vr_rec_dataleitu03 := 'null';
		END IF;
		IF pn_rec_dataleitu03 IS NOT NULL
		AND pa_rec_dataleitu03 IS NULL THEN
			vr_rec_dataleitu03 := '"' || pn_rec_dataleitu03 || '"';
		END IF;
		IF pn_rec_dataleitu03 IS NOT NULL
		AND pa_rec_dataleitu03 IS NOT NULL THEN
			IF pa_rec_dataleitu03 <> pn_rec_dataleitu03 THEN
				vr_rec_dataleitu03 := '"' || pn_rec_dataleitu03 || '"';
			ELSE
				vr_rec_dataleitu03 := '"' || pa_rec_dataleitu03 || '"';
			END IF;
		END IF;
		IF pn_rec_memo04 IS NULL THEN
			vr_rec_memo04 := NULL;
		ELSE
			vr_rec_memo04 := ':vblob1';
		END IF;
		v_blob1 := pn_rec_memo04;
		IF pn_rec_de05 IS NULL
		AND pa_rec_de05 IS NULL THEN
			vr_rec_de05 := 'null';
		END IF;
		IF pn_rec_de05 IS NULL
		AND pa_rec_de05 IS NOT NULL THEN
			vr_rec_de05 := 'null';
		END IF;
		IF pn_rec_de05 IS NOT NULL
		AND pa_rec_de05 IS NULL THEN
			vr_rec_de05 := '"' || RTRIM(pn_rec_de05) || '"';
		END IF;
		IF pn_rec_de05 IS NOT NULL
		AND pa_rec_de05 IS NOT NULL THEN
			IF pa_rec_de05 <> pn_rec_de05 THEN
				vr_rec_de05 := '"' || RTRIM(pn_rec_de05) || '"';
			ELSE
				vr_rec_de05 := '"' || RTRIM(pa_rec_de05) || '"';
			END IF;
		END IF;
		IF pn_rec_para06 IS NULL
		AND pa_rec_para06 IS NULL THEN
			vr_rec_para06 := 'null';
		END IF;
		IF pn_rec_para06 IS NULL
		AND pa_rec_para06 IS NOT NULL THEN
			vr_rec_para06 := 'null';
		END IF;
		IF pn_rec_para06 IS NOT NULL
		AND pa_rec_para06 IS NULL THEN
			vr_rec_para06 := '"' || RTRIM(pn_rec_para06) || '"';
		END IF;
		IF pn_rec_para06 IS NOT NULL
		AND pa_rec_para06 IS NOT NULL THEN
			IF pa_rec_para06 <> pn_rec_para06 THEN
				vr_rec_para06 := '"' || RTRIM(pn_rec_para06) || '"';
			ELSE
				vr_rec_para06 := '"' || RTRIM(pa_rec_para06) || '"';
			END IF;
		END IF;
		v_sql1 := 'update recado set rec_sequencial = ' || RTRIM(vr_rec_sequencia00) || '  , rec_dataenvio = ' || RTRIM(vr_rec_dataenvio01) || '  , co_unidade = ' || RTRIM(vr_co_unidade02) || '  , rec_dataleitura = ' || RTRIM(vr_rec_dataleitu03) || '  , rec_memo = ' || RTRIM(vr_rec_memo04) || '  , rec_de = ' || RTRIM(vr_rec_de05) || '  , rec_para = ' || RTRIM(vr_rec_para06);
		v_sql2 := ' where rec_sequencial = ' || RTRIM(vr_rec_sequencia00) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
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
		       'recado',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       v_blob1,
		       NULL);
	END IF;
END pr_recado012;
/

