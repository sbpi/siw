CREATE OR REPLACE PROCEDURE pr_compromiss006(
	P_OP_IN                CHAR,
	PA_ds_usuario00_IN     compromisso.ds_usuario%TYPE,
	PA_com_sequencia01_IN  compromisso.com_sequencial%TYPE,
	PA_co_unidade02_IN     compromisso.co_unidade%TYPE,
	PA_com_data03_IN       compromisso.com_data%TYPE,
	PA_com_descricao04_IN  compromisso.com_descricao%TYPE,
	PA_com_hora05_IN       compromisso.com_hora%TYPE,
	PA_com_contato06_IN    compromisso.com_contato%TYPE,
	PA_com_aviso07_IN      compromisso.com_aviso%TYPE,
	PA_com_observaca08_IN  compromisso.com_observacao%TYPE,
	PA_com_confirmad09_IN  compromisso.com_confirmado%TYPE,
	PN_ds_usuario00_IN     compromisso.ds_usuario%TYPE,
	PN_com_sequencia01_IN  compromisso.com_sequencial%TYPE,
	PN_co_unidade02_IN     compromisso.co_unidade%TYPE,
	PN_com_data03_IN       compromisso.com_data%TYPE,
	PN_com_descricao04_IN  compromisso.com_descricao%TYPE,
	PN_com_hora05_IN       compromisso.com_hora%TYPE,
	PN_com_contato06_IN    compromisso.com_contato%TYPE,
	PN_com_aviso07_IN      compromisso.com_aviso%TYPE,
	PN_com_observaca08_IN  compromisso.com_observacao%TYPE,
	PN_com_confirmad09_IN  compromisso.com_confirmado%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_ds_usuario00     compromisso.ds_usuario%TYPE := PA_ds_usuario00_IN;
PA_com_sequencia01  compromisso.com_sequencial%TYPE := PA_com_sequencia01_IN;
PA_co_unidade02     compromisso.co_unidade%TYPE := PA_co_unidade02_IN;
PA_com_data03       compromisso.com_data%TYPE := PA_com_data03_IN;
PA_com_descricao04  compromisso.com_descricao%TYPE := PA_com_descricao04_IN;
PA_com_hora05       compromisso.com_hora%TYPE := PA_com_hora05_IN;
PA_com_contato06    compromisso.com_contato%TYPE := PA_com_contato06_IN;
PA_com_aviso07      compromisso.com_aviso%TYPE := PA_com_aviso07_IN;
PA_com_observaca08  compromisso.com_observacao%TYPE := PA_com_observaca08_IN;
PA_com_confirmad09  compromisso.com_confirmado%TYPE := PA_com_confirmad09_IN;
PN_ds_usuario00     compromisso.ds_usuario%TYPE := PN_ds_usuario00_IN;
PN_com_sequencia01  compromisso.com_sequencial%TYPE := PN_com_sequencia01_IN;
PN_co_unidade02     compromisso.co_unidade%TYPE := PN_co_unidade02_IN;
PN_com_data03       compromisso.com_data%TYPE := PN_com_data03_IN;
PN_com_descricao04  compromisso.com_descricao%TYPE := PN_com_descricao04_IN;
PN_com_hora05       compromisso.com_hora%TYPE := PN_com_hora05_IN;
PN_com_contato06    compromisso.com_contato%TYPE := PN_com_contato06_IN;
PN_com_aviso07      compromisso.com_aviso%TYPE := PN_com_aviso07_IN;
PN_com_observaca08  compromisso.com_observacao%TYPE := PN_com_observaca08_IN;
PN_com_confirmad09  compromisso.com_confirmado%TYPE := PN_com_confirmad09_IN;
v_blob1             compromisso.com_observacao%TYPE;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(500);
v_sql2              CHAR(500);
v_sql3              CHAR(500);
v_sql4              CHAR(500);
v_sql5              CHAR(500);
v_sql6              CHAR(500);
v_uni               CHAR(10);
vr_ds_usuario00     CHAR(40);
vr_com_sequencia01  CHAR(10);
vr_co_unidade02     CHAR(10);
vr_com_data03       CHAR(40);
vr_com_descricao04  CHAR(90);
vr_com_hora05       CHAR(10);
vr_com_contato06    CHAR(40);
vr_com_aviso07      CHAR(40);
vr_com_observaca08  CHAR(10);
vr_com_confirmad09  CHAR(10);
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
		IF pn_com_sequencia01 IS NULL THEN
			vr_com_sequencia01 := 'null';
		ELSE
			vr_com_sequencia01 := pn_com_sequencia01;
		END IF;
		IF pn_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := pn_co_unidade02;
		END IF;
		IF pn_com_data03 IS NULL THEN
			vr_com_data03 := 'null';
		ELSE
			vr_com_data03 := pn_com_data03;
		END IF;
		IF pn_com_descricao04 IS NULL THEN
			vr_com_descricao04 := 'null';
		ELSE
			vr_com_descricao04 := pn_com_descricao04;
		END IF;
		IF pn_com_hora05 IS NULL THEN
			vr_com_hora05 := 'null';
		ELSE
			vr_com_hora05 := pn_com_hora05;
		END IF;
		IF pn_com_contato06 IS NULL THEN
			vr_com_contato06 := 'null';
		ELSE
			vr_com_contato06 := pn_com_contato06;
		END IF;
		IF pn_com_aviso07 IS NULL THEN
			vr_com_aviso07 := 'null';
		ELSE
			vr_com_aviso07 := pn_com_aviso07;
		END IF;
		IF pn_com_observaca08 IS NULL THEN
			vr_com_observaca08 := NULL;
		ELSE
			vr_com_observaca08 := ':vblob1';
		END IF;
		v_blob1 := pn_com_observaca08;
		IF pn_com_confirmad09 IS NULL THEN
			vr_com_confirmad09 := 'null';
		ELSE
			vr_com_confirmad09 := pn_com_confirmad09;
		END IF;
		v_sql1 := 'insert into compromisso(ds_usuario, com_sequencial, co_unidade, com_data, com_descricao, com_hora, com_contato, com_aviso, com_observacao, com_confirmado) values (';
		v_sql2 := '"' || RTRIM(vr_ds_usuario00) || '"' || ',' || RTRIM(vr_com_sequencia01) || ',' || '"' || RTRIM(vr_co_unidade02) || '"' || ',' || '"' || vr_com_data03 || '"' || ',' || '"' || RTRIM(vr_com_descricao04) || '"' || ',';
		v_sql3 := '"' || RTRIM(vr_com_hora05) || '"' || ',' || '"' || RTRIM(vr_com_contato06) || '"' || ',' || '"' || vr_com_aviso07 || '"' || ',' || RTRIM(vr_com_observaca08) || ',' || '"' || RTRIM(vr_com_confirmad09) || '"' || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_com_sequencia01 IS NULL THEN
			vr_com_sequencia01 := 'null';
		ELSE
			vr_com_sequencia01 := pa_com_sequencia01;
		END IF;
		IF pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := '"' || RTRIM(pa_co_unidade02) || '"';
		END IF;
		v_sql1 := '  delete from compromisso where com_sequencial = ' || RTRIM(vr_com_sequencia01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
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
		IF pn_com_sequencia01 IS NULL
		AND pa_com_sequencia01 IS NULL THEN
			vr_com_sequencia01 := 'null';
		END IF;
		IF pn_com_sequencia01 IS NULL
		AND pa_com_sequencia01 IS NOT NULL THEN
			vr_com_sequencia01 := 'null';
		END IF;
		IF pn_com_sequencia01 IS NOT NULL
		AND pa_com_sequencia01 IS NULL THEN
			vr_com_sequencia01 := pn_com_sequencia01;
		END IF;
		IF pn_com_sequencia01 IS NOT NULL
		AND pa_com_sequencia01 IS NOT NULL THEN
			IF pa_com_sequencia01 <> pn_com_sequencia01 THEN
				vr_com_sequencia01 := pn_com_sequencia01;
			ELSE
				vr_com_sequencia01 := pa_com_sequencia01;
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
		IF pn_com_data03 IS NULL
		AND pa_com_data03 IS NULL THEN
			vr_com_data03 := 'null';
		END IF;
		IF pn_com_data03 IS NULL
		AND pa_com_data03 IS NOT NULL THEN
			vr_com_data03 := 'null';
		END IF;
		IF pn_com_data03 IS NOT NULL
		AND pa_com_data03 IS NULL THEN
			vr_com_data03 := '"' || pn_com_data03 || '"';
		END IF;
		IF pn_com_data03 IS NOT NULL
		AND pa_com_data03 IS NOT NULL THEN
			IF pa_com_data03 <> pn_com_data03 THEN
				vr_com_data03 := '"' || pn_com_data03 || '"';
			ELSE
				vr_com_data03 := '"' || pa_com_data03 || '"';
			END IF;
		END IF;
		IF pn_com_descricao04 IS NULL
		AND pa_com_descricao04 IS NULL THEN
			vr_com_descricao04 := 'null';
		END IF;
		IF pn_com_descricao04 IS NULL
		AND pa_com_descricao04 IS NOT NULL THEN
			vr_com_descricao04 := 'null';
		END IF;
		IF pn_com_descricao04 IS NOT NULL
		AND pa_com_descricao04 IS NULL THEN
			vr_com_descricao04 := '"' || RTRIM(pn_com_descricao04) || '"';
		END IF;
		IF pn_com_descricao04 IS NOT NULL
		AND pa_com_descricao04 IS NOT NULL THEN
			IF pa_com_descricao04 <> pn_com_descricao04 THEN
				vr_com_descricao04 := '"' || RTRIM(pn_com_descricao04) || '"';
			ELSE
				vr_com_descricao04 := '"' || RTRIM(pa_com_descricao04) || '"';
			END IF;
		END IF;
		IF pn_com_hora05 IS NULL
		AND pa_com_hora05 IS NULL THEN
			vr_com_hora05 := 'null';
		END IF;
		IF pn_com_hora05 IS NULL
		AND pa_com_hora05 IS NOT NULL THEN
			vr_com_hora05 := 'null';
		END IF;
		IF pn_com_hora05 IS NOT NULL
		AND pa_com_hora05 IS NULL THEN
			vr_com_hora05 := '"' || RTRIM(pn_com_hora05) || '"';
		END IF;
		IF pn_com_hora05 IS NOT NULL
		AND pa_com_hora05 IS NOT NULL THEN
			IF pa_com_hora05 <> pn_com_hora05 THEN
				vr_com_hora05 := '"' || RTRIM(pn_com_hora05) || '"';
			ELSE
				vr_com_hora05 := '"' || RTRIM(pa_com_hora05) || '"';
			END IF;
		END IF;
		IF pn_com_contato06 IS NULL
		AND pa_com_contato06 IS NULL THEN
			vr_com_contato06 := 'null';
		END IF;
		IF pn_com_contato06 IS NULL
		AND pa_com_contato06 IS NOT NULL THEN
			vr_com_contato06 := 'null';
		END IF;
		IF pn_com_contato06 IS NOT NULL
		AND pa_com_contato06 IS NULL THEN
			vr_com_contato06 := '"' || RTRIM(pn_com_contato06) || '"';
		END IF;
		IF pn_com_contato06 IS NOT NULL
		AND pa_com_contato06 IS NOT NULL THEN
			IF pa_com_contato06 <> pn_com_contato06 THEN
				vr_com_contato06 := '"' || RTRIM(pn_com_contato06) || '"';
			ELSE
				vr_com_contato06 := '"' || RTRIM(pa_com_contato06) || '"';
			END IF;
		END IF;
		IF pn_com_aviso07 IS NULL
		AND pa_com_aviso07 IS NULL THEN
			vr_com_aviso07 := 'null';
		END IF;
		IF pn_com_aviso07 IS NULL
		AND pa_com_aviso07 IS NOT NULL THEN
			vr_com_aviso07 := 'null';
		END IF;
		IF pn_com_aviso07 IS NOT NULL
		AND pa_com_aviso07 IS NULL THEN
			vr_com_aviso07 := '"' || pn_com_aviso07 || '"';
		END IF;
		IF pn_com_aviso07 IS NOT NULL
		AND pa_com_aviso07 IS NOT NULL THEN
			IF pa_com_aviso07 <> pn_com_aviso07 THEN
				vr_com_aviso07 := '"' || pn_com_aviso07 || '"';
			ELSE
				vr_com_aviso07 := '"' || pa_com_aviso07 || '"';
			END IF;
		END IF;
		IF pn_com_observaca08 IS NULL THEN
			vr_com_observaca08 := NULL;
		ELSE
			vr_com_observaca08 := ':vblob1';
		END IF;
		v_blob1 := pn_com_observaca08;
		IF pn_com_confirmad09 IS NULL
		AND pa_com_confirmad09 IS NULL THEN
			vr_com_confirmad09 := 'null';
		END IF;
		IF pn_com_confirmad09 IS NULL
		AND pa_com_confirmad09 IS NOT NULL THEN
			vr_com_confirmad09 := 'null';
		END IF;
		IF pn_com_confirmad09 IS NOT NULL
		AND pa_com_confirmad09 IS NULL THEN
			vr_com_confirmad09 := '"' || RTRIM(pn_com_confirmad09) || '"';
		END IF;
		IF pn_com_confirmad09 IS NOT NULL
		AND pa_com_confirmad09 IS NOT NULL THEN
			IF pa_com_confirmad09 <> pn_com_confirmad09 THEN
				vr_com_confirmad09 := '"' || RTRIM(pn_com_confirmad09) || '"';
			ELSE
				vr_com_confirmad09 := '"' || RTRIM(pa_com_confirmad09) || '"';
			END IF;
		END IF;
		v_sql1 := 'update compromisso set ds_usuario = ' || RTRIM(vr_ds_usuario00) || '  , com_sequencial = ' || RTRIM(vr_com_sequencia01) || '  , co_unidade = ' || RTRIM(vr_co_unidade02) || '  , com_data = ' || RTRIM(vr_com_data03);
		v_sql2 := '  , com_descricao = ' || RTRIM(vr_com_descricao04) || '  , com_hora = ' || RTRIM(vr_com_hora05) || '  , com_contato = ' || RTRIM(vr_com_contato06) || '  , com_aviso = ' || RTRIM(vr_com_aviso07);
		v_sql3 := '  , com_observacao = ' || RTRIM(vr_com_observaca08) || '  , com_confirmado = ' || RTRIM(vr_com_confirmad09);
		v_sql4 := ' where com_sequencial = ' || RTRIM(vr_com_sequencia01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4;
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
		       'compromisso',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       v_blob1,
		       NULL);
	END IF;
END pr_compromiss006;
/

