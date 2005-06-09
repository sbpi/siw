CREATE OR REPLACE PROCEDURE pr_lembrete010(
	P_OP_IN                CHAR,
	PA_ds_usuario00_IN     lembrete.ds_usuario%TYPE,
	PA_lemb_sequenci01_IN  lembrete.lemb_sequencial%TYPE,
	PA_co_unidade02_IN     lembrete.co_unidade%TYPE,
	PA_lemb_data03_IN      lembrete.lemb_data%TYPE,
	PA_lemb_memo04_IN      lembrete.lemb_memo%TYPE,
	PA_indicador_lid05_IN  lembrete.indicador_lido%TYPE,
	PN_ds_usuario00_IN     lembrete.ds_usuario%TYPE,
	PN_lemb_sequenci01_IN  lembrete.lemb_sequencial%TYPE,
	PN_co_unidade02_IN     lembrete.co_unidade%TYPE,
	PN_lemb_data03_IN      lembrete.lemb_data%TYPE,
	PN_lemb_memo04_IN      lembrete.lemb_memo%TYPE,
	PN_indicador_lid05_IN  lembrete.indicador_lido%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_ds_usuario00     lembrete.ds_usuario%TYPE := PA_ds_usuario00_IN;
PA_lemb_sequenci01  lembrete.lemb_sequencial%TYPE := PA_lemb_sequenci01_IN;
PA_co_unidade02     lembrete.co_unidade%TYPE := PA_co_unidade02_IN;
PA_lemb_data03      lembrete.lemb_data%TYPE := PA_lemb_data03_IN;
PA_lemb_memo04      lembrete.lemb_memo%TYPE := PA_lemb_memo04_IN;
PA_indicador_lid05  lembrete.indicador_lido%TYPE := PA_indicador_lid05_IN;
PN_ds_usuario00     lembrete.ds_usuario%TYPE := PN_ds_usuario00_IN;
PN_lemb_sequenci01  lembrete.lemb_sequencial%TYPE := PN_lemb_sequenci01_IN;
PN_co_unidade02     lembrete.co_unidade%TYPE := PN_co_unidade02_IN;
PN_lemb_data03      lembrete.lemb_data%TYPE := PN_lemb_data03_IN;
PN_lemb_memo04      lembrete.lemb_memo%TYPE := PN_lemb_memo04_IN;
PN_indicador_lid05  lembrete.indicador_lido%TYPE := PN_indicador_lid05_IN;
v_blob1             lembrete.lemb_memo%TYPE;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_ds_usuario00     CHAR(30);
vr_lemb_sequenci01  CHAR(10);
vr_co_unidade02     CHAR(10);
vr_lemb_data03      CHAR(40);
vr_lemb_memo04      CHAR(10);
vr_indicador_lid05  CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	v_blob1 := NULL;
	IF p_op = 'ins' THEN
		IF pn_ds_usuario00 IS NULL THEN
			vr_ds_usuario00 := 'null';
		ELSE
			vr_ds_usuario00 := pn_ds_usuario00;
		END IF;
		IF pn_lemb_sequenci01 IS NULL THEN
			vr_lemb_sequenci01 := 'null';
		ELSE
			vr_lemb_sequenci01 := pn_lemb_sequenci01;
		END IF;
		IF pn_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := pn_co_unidade02;
		END IF;
		IF pn_lemb_data03 IS NULL THEN
			vr_lemb_data03 := 'null';
		ELSE
			vr_lemb_data03 := pn_lemb_data03;
		END IF;
		IF pn_lemb_memo04 IS NULL THEN
			vr_lemb_memo04 := NULL;
		ELSE
			vr_lemb_memo04 := ':vblob1';
		END IF;
		v_blob1 := pn_lemb_memo04;
		IF pn_indicador_lid05 IS NULL THEN
			vr_indicador_lid05 := 'null';
		ELSE
			vr_indicador_lid05 := pn_indicador_lid05;
		END IF;
		v_sql1 := 'insert into lembrete(ds_usuario, lemb_sequencial, co_unidade, lemb_data, lemb_memo, indicador_lido) values (';
		v_sql2 := '"' || RTRIM(vr_ds_usuario00) || '"' || ',' || RTRIM(vr_lemb_sequenci01) || ',' || '"' || RTRIM(vr_co_unidade02) || '"' || ',' || '"' || vr_lemb_data03 || '"' || ',' || RTRIM(vr_lemb_memo04) || ',' || '"' || RTRIM(vr_indicador_lid05) || '"' || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_lemb_sequenci01 IS NULL THEN
			vr_lemb_sequenci01 := 'null';
		ELSE
			vr_lemb_sequenci01 := pa_lemb_sequenci01;
		END IF;
		IF pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := '"' || RTRIM(pa_co_unidade02) || '"';
		END IF;
		v_sql1 := '  delete from lembrete where lemb_sequencial = ' || RTRIM(vr_lemb_sequenci01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_ds_usuario00 IS NULL
		AND pa_ds_usuario00 IS NULL THEN
			vr_ds_usuario00 := 'null';
		END IF;
		IF pn_ds_usuario00 IS NULL
		AND pa_ds_usuario00 IS NOT NULL THEN
			vr_ds_usuario00 := 'null';
		END IF;
		IF pn_ds_usuario00 IS NOT NULL
		AND pa_ds_usuario00 IS NULL THEN
			vr_ds_usuario00 := '"' || RTRIM(pn_ds_usuario00) || '"';
		END IF;
		IF pn_ds_usuario00 IS NOT NULL
		AND pa_ds_usuario00 IS NOT NULL THEN
			IF pa_ds_usuario00 <> pn_ds_usuario00 THEN
				vr_ds_usuario00 := '"' || RTRIM(pn_ds_usuario00) || '"';
			ELSE
				vr_ds_usuario00 := '"' || RTRIM(pa_ds_usuario00) || '"';
			END IF;
		END IF;
		IF pn_lemb_sequenci01 IS NULL
		AND pa_lemb_sequenci01 IS NULL THEN
			vr_lemb_sequenci01 := 'null';
		END IF;
		IF pn_lemb_sequenci01 IS NULL
		AND pa_lemb_sequenci01 IS NOT NULL THEN
			vr_lemb_sequenci01 := 'null';
		END IF;
		IF pn_lemb_sequenci01 IS NOT NULL
		AND pa_lemb_sequenci01 IS NULL THEN
			vr_lemb_sequenci01 := pn_lemb_sequenci01;
		END IF;
		IF pn_lemb_sequenci01 IS NOT NULL
		AND pa_lemb_sequenci01 IS NOT NULL THEN
			IF pa_lemb_sequenci01 <> pn_lemb_sequenci01 THEN
				vr_lemb_sequenci01 := pn_lemb_sequenci01;
			ELSE
				vr_lemb_sequenci01 := pa_lemb_sequenci01;
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
		IF pn_lemb_data03 IS NULL
		AND pa_lemb_data03 IS NULL THEN
			vr_lemb_data03 := 'null';
		END IF;
		IF pn_lemb_data03 IS NULL
		AND pa_lemb_data03 IS NOT NULL THEN
			vr_lemb_data03 := 'null';
		END IF;
		IF pn_lemb_data03 IS NOT NULL
		AND pa_lemb_data03 IS NULL THEN
			vr_lemb_data03 := '"' || pn_lemb_data03 || '"';
		END IF;
		IF pn_lemb_data03 IS NOT NULL
		AND pa_lemb_data03 IS NOT NULL THEN
			IF pa_lemb_data03 <> pn_lemb_data03 THEN
				vr_lemb_data03 := '"' || pn_lemb_data03 || '"';
			ELSE
				vr_lemb_data03 := '"' || pa_lemb_data03 || '"';
			END IF;
		END IF;
		IF pn_lemb_memo04 IS NULL THEN
			vr_lemb_memo04 := NULL;
		ELSE
			vr_lemb_memo04 := ':vblob1';
		END IF;
		v_blob1 := pn_lemb_memo04;
		IF pn_indicador_lid05 IS NULL
		AND pa_indicador_lid05 IS NULL THEN
			vr_indicador_lid05 := 'null';
		END IF;
		IF pn_indicador_lid05 IS NULL
		AND pa_indicador_lid05 IS NOT NULL THEN
			vr_indicador_lid05 := 'null';
		END IF;
		IF pn_indicador_lid05 IS NOT NULL
		AND pa_indicador_lid05 IS NULL THEN
			vr_indicador_lid05 := '"' || RTRIM(pn_indicador_lid05) || '"';
		END IF;
		IF pn_indicador_lid05 IS NOT NULL
		AND pa_indicador_lid05 IS NOT NULL THEN
			IF pa_indicador_lid05 <> pn_indicador_lid05 THEN
				vr_indicador_lid05 := '"' || RTRIM(pn_indicador_lid05) || '"';
			ELSE
				vr_indicador_lid05 := '"' || RTRIM(pa_indicador_lid05) || '"';
			END IF;
		END IF;
		v_sql1 := 'update lembrete set ds_usuario = ' || RTRIM(vr_ds_usuario00) || '  , lemb_sequencial = ' || RTRIM(vr_lemb_sequenci01) || '  , co_unidade = ' || RTRIM(vr_co_unidade02) || '  , lemb_data = ' || RTRIM(vr_lemb_data03) || '  , lemb_memo = ' || RTRIM(vr_lemb_memo04) || '  , indicador_lido = ' || RTRIM(vr_indicador_lid05);
		v_sql2 := ' where lemb_sequencial = ' || RTRIM(vr_lemb_sequenci01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
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
		       'lembrete',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       v_blob1,
		       NULL);
	END IF;
END pr_lembrete010;
/

