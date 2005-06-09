CREATE OR REPLACE PROCEDURE pr_agenda002(
	P_OP_IN                CHAR,
	PA_ds_usuario00_IN     agenda.ds_usuario%TYPE,
	PA_age_sequencia01_IN  agenda.age_sequencial%TYPE,
	PA_co_unidade02_IN     agenda.co_unidade%TYPE,
	PA_age_nome03_IN       agenda.age_nome%TYPE,
	PA_age_endereco04_IN   agenda.age_endereco%TYPE,
	PA_age_bairro05_IN     agenda.age_bairro%TYPE,
	PA_age_cidade06_IN     agenda.age_cidade%TYPE,
	PA_age_estado07_IN     agenda.age_estado%TYPE,
	PA_age_cep08_IN        agenda.age_cep%TYPE,
	PA_age_enderecoc09_IN  agenda.age_enderecoc%TYPE,
	PA_age_bairroc10_IN    agenda.age_bairroc%TYPE,
	PA_age_cidadec11_IN    agenda.age_cidadec%TYPE,
	PA_age_estadoc12_IN    agenda.age_estadoc%TYPE,
	PA_age_cepc13_IN       agenda.age_cepc%TYPE,
	PA_age_email14_IN      agenda.age_email%TYPE,
	PA_age_observaca15_IN  agenda.age_observacao%TYPE,
	PN_ds_usuario00_IN     agenda.ds_usuario%TYPE,
	PN_age_sequencia01_IN  agenda.age_sequencial%TYPE,
	PN_co_unidade02_IN     agenda.co_unidade%TYPE,
	PN_age_nome03_IN       agenda.age_nome%TYPE,
	PN_age_endereco04_IN   agenda.age_endereco%TYPE,
	PN_age_bairro05_IN     agenda.age_bairro%TYPE,
	PN_age_cidade06_IN     agenda.age_cidade%TYPE,
	PN_age_estado07_IN     agenda.age_estado%TYPE,
	PN_age_cep08_IN        agenda.age_cep%TYPE,
	PN_age_enderecoc09_IN  agenda.age_enderecoc%TYPE,
	PN_age_bairroc10_IN    agenda.age_bairroc%TYPE,
	PN_age_cidadec11_IN    agenda.age_cidadec%TYPE,
	PN_age_estadoc12_IN    agenda.age_estadoc%TYPE,
	PN_age_cepc13_IN       agenda.age_cepc%TYPE,
	PN_age_email14_IN      agenda.age_email%TYPE,
	PN_age_observaca15_IN  agenda.age_observacao%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_ds_usuario00     agenda.ds_usuario%TYPE := PA_ds_usuario00_IN;
PA_age_sequencia01  agenda.age_sequencial%TYPE := PA_age_sequencia01_IN;
PA_co_unidade02     agenda.co_unidade%TYPE := PA_co_unidade02_IN;
PA_age_nome03       agenda.age_nome%TYPE := PA_age_nome03_IN;
PA_age_endereco04   agenda.age_endereco%TYPE := PA_age_endereco04_IN;
PA_age_bairro05     agenda.age_bairro%TYPE := PA_age_bairro05_IN;
PA_age_cidade06     agenda.age_cidade%TYPE := PA_age_cidade06_IN;
PA_age_estado07     agenda.age_estado%TYPE := PA_age_estado07_IN;
PA_age_cep08        agenda.age_cep%TYPE := PA_age_cep08_IN;
PA_age_enderecoc09  agenda.age_enderecoc%TYPE := PA_age_enderecoc09_IN;
PA_age_bairroc10    agenda.age_bairroc%TYPE := PA_age_bairroc10_IN;
PA_age_cidadec11    agenda.age_cidadec%TYPE := PA_age_cidadec11_IN;
PA_age_estadoc12    agenda.age_estadoc%TYPE := PA_age_estadoc12_IN;
PA_age_cepc13       agenda.age_cepc%TYPE := PA_age_cepc13_IN;
PA_age_email14      agenda.age_email%TYPE := PA_age_email14_IN;
PA_age_observaca15  agenda.age_observacao%TYPE := PA_age_observaca15_IN;
PN_ds_usuario00     agenda.ds_usuario%TYPE := PN_ds_usuario00_IN;
PN_age_sequencia01  agenda.age_sequencial%TYPE := PN_age_sequencia01_IN;
PN_co_unidade02     agenda.co_unidade%TYPE := PN_co_unidade02_IN;
PN_age_nome03       agenda.age_nome%TYPE := PN_age_nome03_IN;
PN_age_endereco04   agenda.age_endereco%TYPE := PN_age_endereco04_IN;
PN_age_bairro05     agenda.age_bairro%TYPE := PN_age_bairro05_IN;
PN_age_cidade06     agenda.age_cidade%TYPE := PN_age_cidade06_IN;
PN_age_estado07     agenda.age_estado%TYPE := PN_age_estado07_IN;
PN_age_cep08        agenda.age_cep%TYPE := PN_age_cep08_IN;
PN_age_enderecoc09  agenda.age_enderecoc%TYPE := PN_age_enderecoc09_IN;
PN_age_bairroc10    agenda.age_bairroc%TYPE := PN_age_bairroc10_IN;
PN_age_cidadec11    agenda.age_cidadec%TYPE := PN_age_cidadec11_IN;
PN_age_estadoc12    agenda.age_estadoc%TYPE := PN_age_estadoc12_IN;
PN_age_cepc13       agenda.age_cepc%TYPE := PN_age_cepc13_IN;
PN_age_email14      agenda.age_email%TYPE := PN_age_email14_IN;
PN_age_observaca15  agenda.age_observacao%TYPE := PN_age_observaca15_IN;
v_blob1             agenda.age_observacao%TYPE;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(500);
v_sql2              CHAR(500);
v_sql3              CHAR(500);
v_sql4              CHAR(500);
v_sql5              CHAR(500);
v_sql6              CHAR(500);
v_uni               CHAR(10);
vr_ds_usuario00     CHAR(40);
vr_age_sequencia01  CHAR(10);
vr_co_unidade02     CHAR(10);
vr_age_nome03       CHAR(60);
vr_age_endereco04   CHAR(50);
vr_age_bairro05     CHAR(30);
vr_age_cidade06     CHAR(30);
vr_age_estado07     CHAR(10);
vr_age_cep08        CHAR(20);
vr_age_enderecoc09  CHAR(50);
vr_age_bairroc10    CHAR(30);
vr_age_cidadec11    CHAR(30);
vr_age_estadoc12    CHAR(10);
vr_age_cepc13       CHAR(20);
vr_age_email14      CHAR(60);
vr_age_observaca15  CHAR(10);
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
		IF pn_age_sequencia01 IS NULL THEN
			vr_age_sequencia01 := 'null';
		ELSE
			vr_age_sequencia01 := pn_age_sequencia01;
		END IF;
		IF pn_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := pn_co_unidade02;
		END IF;
		IF pn_age_nome03 IS NULL THEN
			vr_age_nome03 := 'null';
		ELSE
			vr_age_nome03 := pn_age_nome03;
		END IF;
		IF pn_age_endereco04 IS NULL THEN
			vr_age_endereco04 := 'null';
		ELSE
			vr_age_endereco04 := pn_age_endereco04;
		END IF;
		IF pn_age_bairro05 IS NULL THEN
			vr_age_bairro05 := 'null';
		ELSE
			vr_age_bairro05 := pn_age_bairro05;
		END IF;
		IF pn_age_cidade06 IS NULL THEN
			vr_age_cidade06 := 'null';
		ELSE
			vr_age_cidade06 := pn_age_cidade06;
		END IF;
		IF pn_age_estado07 IS NULL THEN
			vr_age_estado07 := 'null';
		ELSE
			vr_age_estado07 := pn_age_estado07;
		END IF;
		IF pn_age_cep08 IS NULL THEN
			vr_age_cep08 := 'null';
		ELSE
			vr_age_cep08 := pn_age_cep08;
		END IF;
		IF pn_age_enderecoc09 IS NULL THEN
			vr_age_enderecoc09 := 'null';
		ELSE
			vr_age_enderecoc09 := pn_age_enderecoc09;
		END IF;
		IF pn_age_bairroc10 IS NULL THEN
			vr_age_bairroc10 := 'null';
		ELSE
			vr_age_bairroc10 := pn_age_bairroc10;
		END IF;
		IF pn_age_cidadec11 IS NULL THEN
			vr_age_cidadec11 := 'null';
		ELSE
			vr_age_cidadec11 := pn_age_cidadec11;
		END IF;
		IF pn_age_estadoc12 IS NULL THEN
			vr_age_estadoc12 := 'null';
		ELSE
			vr_age_estadoc12 := pn_age_estadoc12;
		END IF;
		IF pn_age_cepc13 IS NULL THEN
			vr_age_cepc13 := 'null';
		ELSE
			vr_age_cepc13 := pn_age_cepc13;
		END IF;
		IF pn_age_email14 IS NULL THEN
			vr_age_email14 := 'null';
		ELSE
			vr_age_email14 := pn_age_email14;
		END IF;
		IF pn_age_observaca15 IS NULL THEN
			vr_age_observaca15 := NULL;
		ELSE
			vr_age_observaca15 := ':vblob1';
		END IF;
		v_blob1 := pn_age_observaca15;
		v_sql1 := 'insert into agenda(ds_usuario, age_sequencial, co_unidade, age_nome, age_endereco, age_bairro, age_cidade, age_estado, age_cep, age_enderecoc, age_bairroc, age_cidadec, age_estadoc, ' || ' age_cepc, age_email, age_observacao) values (';
		v_sql2 := '"' || RTRIM(vr_ds_usuario00) || '"' || ',' || RTRIM(vr_age_sequencia01) || ',' || '"' || RTRIM(vr_co_unidade02) || '"' || ',' || '"' || RTRIM(vr_age_nome03) || '"' || ',' || '"' || RTRIM(vr_age_endereco04) || '"' || ',';
		v_sql3 := '"' || RTRIM(vr_age_bairro05) || '"' || ',' || '"' || RTRIM(vr_age_cidade06) || '"' || ',' || '"' || RTRIM(vr_age_estado07) || '"' || ',' || '"' || RTRIM(vr_age_cep08) || '"' || ',' || '"' || RTRIM(vr_age_enderecoc09) || '"' || ',';
		v_sql4 := '"' || RTRIM(vr_age_bairroc10) || '"' || ',' || '"' || RTRIM(vr_age_cidadec11) || '"' || ',' || '"' || RTRIM(vr_age_estadoc12) || '"' || ',' || '"' || RTRIM(vr_age_cepc13) || '"' || ',';
		v_sql5 := '"' || RTRIM(vr_age_email14) || '"' || ',' || RTRIM(vr_age_observaca15) || ');';
		v_sql6 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5 || v_sql6;
	ELSIF p_op = 'del' THEN
		IF pa_age_sequencia01 IS NULL THEN
			vr_age_sequencia01 := 'null';
		ELSE
			vr_age_sequencia01 := pa_age_sequencia01;
		END IF;
		IF pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := '"' || RTRIM(pa_co_unidade02) || '"';
		END IF;
		v_sql1 := '  delete from agenda where age_sequencial = ' || RTRIM(vr_age_sequencia01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
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
		IF pn_age_sequencia01 IS NULL
		AND pa_age_sequencia01 IS NULL THEN
			vr_age_sequencia01 := 'null';
		END IF;
		IF pn_age_sequencia01 IS NULL
		AND pa_age_sequencia01 IS NOT NULL THEN
			vr_age_sequencia01 := 'null';
		END IF;
		IF pn_age_sequencia01 IS NOT NULL
		AND pa_age_sequencia01 IS NULL THEN
			vr_age_sequencia01 := pn_age_sequencia01;
		END IF;
		IF pn_age_sequencia01 IS NOT NULL
		AND pa_age_sequencia01 IS NOT NULL THEN
			IF pa_age_sequencia01 <> pn_age_sequencia01 THEN
				vr_age_sequencia01 := pn_age_sequencia01;
			ELSE
				vr_age_sequencia01 := pa_age_sequencia01;
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
		IF pn_age_nome03 IS NULL
		AND pa_age_nome03 IS NULL THEN
			vr_age_nome03 := 'null';
		END IF;
		IF pn_age_nome03 IS NULL
		AND pa_age_nome03 IS NOT NULL THEN
			vr_age_nome03 := 'null';
		END IF;
		IF pn_age_nome03 IS NOT NULL
		AND pa_age_nome03 IS NULL THEN
			vr_age_nome03 := '"' || RTRIM(pn_age_nome03) || '"';
		END IF;
		IF pn_age_nome03 IS NOT NULL
		AND pa_age_nome03 IS NOT NULL THEN
			IF pa_age_nome03 <> pn_age_nome03 THEN
				vr_age_nome03 := '"' || RTRIM(pn_age_nome03) || '"';
			ELSE
				vr_age_nome03 := '"' || RTRIM(pa_age_nome03) || '"';
			END IF;
		END IF;
		IF pn_age_endereco04 IS NULL
		AND pa_age_endereco04 IS NULL THEN
			vr_age_endereco04 := 'null';
		END IF;
		IF pn_age_endereco04 IS NULL
		AND pa_age_endereco04 IS NOT NULL THEN
			vr_age_endereco04 := 'null';
		END IF;
		IF pn_age_endereco04 IS NOT NULL
		AND pa_age_endereco04 IS NULL THEN
			vr_age_endereco04 := '"' || RTRIM(pn_age_endereco04) || '"';
		END IF;
		IF pn_age_endereco04 IS NOT NULL
		AND pa_age_endereco04 IS NOT NULL THEN
			IF pa_age_endereco04 <> pn_age_endereco04 THEN
				vr_age_endereco04 := '"' || RTRIM(pn_age_endereco04) || '"';
			ELSE
				vr_age_endereco04 := '"' || RTRIM(pa_age_endereco04) || '"';
			END IF;
		END IF;
		IF pn_age_bairro05 IS NULL
		AND pa_age_bairro05 IS NULL THEN
			vr_age_bairro05 := 'null';
		END IF;
		IF pn_age_bairro05 IS NULL
		AND pa_age_bairro05 IS NOT NULL THEN
			vr_age_bairro05 := 'null';
		END IF;
		IF pn_age_bairro05 IS NOT NULL
		AND pa_age_bairro05 IS NULL THEN
			vr_age_bairro05 := '"' || RTRIM(pn_age_bairro05) || '"';
		END IF;
		IF pn_age_bairro05 IS NOT NULL
		AND pa_age_bairro05 IS NOT NULL THEN
			IF pa_age_bairro05 <> pn_age_bairro05 THEN
				vr_age_bairro05 := '"' || RTRIM(pn_age_bairro05) || '"';
			ELSE
				vr_age_bairro05 := '"' || RTRIM(pa_age_bairro05) || '"';
			END IF;
		END IF;
		IF pn_age_cidade06 IS NULL
		AND pa_age_cidade06 IS NULL THEN
			vr_age_cidade06 := 'null';
		END IF;
		IF pn_age_cidade06 IS NULL
		AND pa_age_cidade06 IS NOT NULL THEN
			vr_age_cidade06 := 'null';
		END IF;
		IF pn_age_cidade06 IS NOT NULL
		AND pa_age_cidade06 IS NULL THEN
			vr_age_cidade06 := '"' || RTRIM(pn_age_cidade06) || '"';
		END IF;
		IF pn_age_cidade06 IS NOT NULL
		AND pa_age_cidade06 IS NOT NULL THEN
			IF pa_age_cidade06 <> pn_age_cidade06 THEN
				vr_age_cidade06 := '"' || RTRIM(pn_age_cidade06) || '"';
			ELSE
				vr_age_cidade06 := '"' || RTRIM(pa_age_cidade06) || '"';
			END IF;
		END IF;
		IF pn_age_estado07 IS NULL
		AND pa_age_estado07 IS NULL THEN
			vr_age_estado07 := 'null';
		END IF;
		IF pn_age_estado07 IS NULL
		AND pa_age_estado07 IS NOT NULL THEN
			vr_age_estado07 := 'null';
		END IF;
		IF pn_age_estado07 IS NOT NULL
		AND pa_age_estado07 IS NULL THEN
			vr_age_estado07 := '"' || RTRIM(pn_age_estado07) || '"';
		END IF;
		IF pn_age_estado07 IS NOT NULL
		AND pa_age_estado07 IS NOT NULL THEN
			IF pa_age_estado07 <> pn_age_estado07 THEN
				vr_age_estado07 := '"' || RTRIM(pn_age_estado07) || '"';
			ELSE
				vr_age_estado07 := '"' || RTRIM(pa_age_estado07) || '"';
			END IF;
		END IF;
		IF pn_age_cep08 IS NULL
		AND pa_age_cep08 IS NULL THEN
			vr_age_cep08 := 'null';
		END IF;
		IF pn_age_cep08 IS NULL
		AND pa_age_cep08 IS NOT NULL THEN
			vr_age_cep08 := 'null';
		END IF;
		IF pn_age_cep08 IS NOT NULL
		AND pa_age_cep08 IS NULL THEN
			vr_age_cep08 := '"' || RTRIM(pn_age_cep08) || '"';
		END IF;
		IF pn_age_cep08 IS NOT NULL
		AND pa_age_cep08 IS NOT NULL THEN
			IF pa_age_cep08 <> pn_age_cep08 THEN
				vr_age_cep08 := '"' || RTRIM(pn_age_cep08) || '"';
			ELSE
				vr_age_cep08 := '"' || RTRIM(pa_age_cep08) || '"';
			END IF;
		END IF;
		IF pn_age_enderecoc09 IS NULL
		AND pa_age_enderecoc09 IS NULL THEN
			vr_age_enderecoc09 := 'null';
		END IF;
		IF pn_age_enderecoc09 IS NULL
		AND pa_age_enderecoc09 IS NOT NULL THEN
			vr_age_enderecoc09 := 'null';
		END IF;
		IF pn_age_enderecoc09 IS NOT NULL
		AND pa_age_enderecoc09 IS NULL THEN
			vr_age_enderecoc09 := '"' || RTRIM(pn_age_enderecoc09) || '"';
		END IF;
		IF pn_age_enderecoc09 IS NOT NULL
		AND pa_age_enderecoc09 IS NOT NULL THEN
			IF pa_age_enderecoc09 <> pn_age_enderecoc09 THEN
				vr_age_enderecoc09 := '"' || RTRIM(pn_age_enderecoc09) || '"';
			ELSE
				vr_age_enderecoc09 := '"' || RTRIM(pa_age_enderecoc09) || '"';
			END IF;
		END IF;
		IF pn_age_bairroc10 IS NULL
		AND pa_age_bairroc10 IS NULL THEN
			vr_age_bairroc10 := 'null';
		END IF;
		IF pn_age_bairroc10 IS NULL
		AND pa_age_bairroc10 IS NOT NULL THEN
			vr_age_bairroc10 := 'null';
		END IF;
		IF pn_age_bairroc10 IS NOT NULL
		AND pa_age_bairroc10 IS NULL THEN
			vr_age_bairroc10 := '"' || RTRIM(pn_age_bairroc10) || '"';
		END IF;
		IF pn_age_bairroc10 IS NOT NULL
		AND pa_age_bairroc10 IS NOT NULL THEN
			IF pa_age_bairroc10 <> pn_age_bairroc10 THEN
				vr_age_bairroc10 := '"' || RTRIM(pn_age_bairroc10) || '"';
			ELSE
				vr_age_bairroc10 := '"' || RTRIM(pa_age_bairroc10) || '"';
			END IF;
		END IF;
		IF pn_age_cidadec11 IS NULL
		AND pa_age_cidadec11 IS NULL THEN
			vr_age_cidadec11 := 'null';
		END IF;
		IF pn_age_cidadec11 IS NULL
		AND pa_age_cidadec11 IS NOT NULL THEN
			vr_age_cidadec11 := 'null';
		END IF;
		IF pn_age_cidadec11 IS NOT NULL
		AND pa_age_cidadec11 IS NULL THEN
			vr_age_cidadec11 := '"' || RTRIM(pn_age_cidadec11) || '"';
		END IF;
		IF pn_age_cidadec11 IS NOT NULL
		AND pa_age_cidadec11 IS NOT NULL THEN
			IF pa_age_cidadec11 <> pn_age_cidadec11 THEN
				vr_age_cidadec11 := '"' || RTRIM(pn_age_cidadec11) || '"';
			ELSE
				vr_age_cidadec11 := '"' || RTRIM(pa_age_cidadec11) || '"';
			END IF;
		END IF;
		IF pn_age_estadoc12 IS NULL
		AND pa_age_estadoc12 IS NULL THEN
			vr_age_estadoc12 := 'null';
		END IF;
		IF pn_age_estadoc12 IS NULL
		AND pa_age_estadoc12 IS NOT NULL THEN
			vr_age_estadoc12 := 'null';
		END IF;
		IF pn_age_estadoc12 IS NOT NULL
		AND pa_age_estadoc12 IS NULL THEN
			vr_age_estadoc12 := '"' || RTRIM(pn_age_estadoc12) || '"';
		END IF;
		IF pn_age_estadoc12 IS NOT NULL
		AND pa_age_estadoc12 IS NOT NULL THEN
			IF pa_age_estadoc12 <> pn_age_estadoc12 THEN
				vr_age_estadoc12 := '"' || RTRIM(pn_age_estadoc12) || '"';
			ELSE
				vr_age_estadoc12 := '"' || RTRIM(pa_age_estadoc12) || '"';
			END IF;
		END IF;
		IF pn_age_cepc13 IS NULL
		AND pa_age_cepc13 IS NULL THEN
			vr_age_cepc13 := 'null';
		END IF;
		IF pn_age_cepc13 IS NULL
		AND pa_age_cepc13 IS NOT NULL THEN
			vr_age_cepc13 := 'null';
		END IF;
		IF pn_age_cepc13 IS NOT NULL
		AND pa_age_cepc13 IS NULL THEN
			vr_age_cepc13 := '"' || RTRIM(pn_age_cepc13) || '"';
		END IF;
		IF pn_age_cepc13 IS NOT NULL
		AND pa_age_cepc13 IS NOT NULL THEN
			IF pa_age_cepc13 <> pn_age_cepc13 THEN
				vr_age_cepc13 := '"' || RTRIM(pn_age_cepc13) || '"';
			ELSE
				vr_age_cepc13 := '"' || RTRIM(pa_age_cepc13) || '"';
			END IF;
		END IF;
		IF pn_age_email14 IS NULL
		AND pa_age_email14 IS NULL THEN
			vr_age_email14 := 'null';
		END IF;
		IF pn_age_email14 IS NULL
		AND pa_age_email14 IS NOT NULL THEN
			vr_age_email14 := 'null';
		END IF;
		IF pn_age_email14 IS NOT NULL
		AND pa_age_email14 IS NULL THEN
			vr_age_email14 := '"' || RTRIM(pn_age_email14) || '"';
		END IF;
		IF pn_age_email14 IS NOT NULL
		AND pa_age_email14 IS NOT NULL THEN
			IF pa_age_email14 <> pn_age_email14 THEN
				vr_age_email14 := '"' || RTRIM(pn_age_email14) || '"';
			ELSE
				vr_age_email14 := '"' || RTRIM(pa_age_email14) || '"';
			END IF;
		END IF;
		IF pn_age_observaca15 IS NULL THEN
			vr_age_observaca15 := NULL;
		ELSE
			vr_age_observaca15 := ':vblob1';
		END IF;
		v_blob1 := pn_age_observaca15;
		v_sql1 := 'update agenda set ds_usuario = ' || RTRIM(vr_ds_usuario00) || '  , age_sequencial = ' || RTRIM(vr_age_sequencia01) || '  , co_unidade = ' || RTRIM(vr_co_unidade02) || '  , age_nome = ' || RTRIM(vr_age_nome03);
		v_sql2 := '  , age_endereco = ' || RTRIM(vr_age_endereco04) || '  , age_bairro = ' || RTRIM(vr_age_bairro05) || '  , age_cidade = ' || RTRIM(vr_age_cidade06) || '  , age_estado = ' || RTRIM(vr_age_estado07);
		v_sql3 := '  , age_cep = ' || RTRIM(vr_age_cep08) || '  , age_enderecoc = ' || RTRIM(vr_age_enderecoc09) || '  , age_bairroc = ' || RTRIM(vr_age_bairroc10) || '  , age_cidadec = ' || RTRIM(vr_age_cidadec11);
		v_sql4 := '  , age_estadoc = ' || RTRIM(vr_age_estadoc12) || '  , age_cepc = ' || RTRIM(vr_age_cepc13) || '  , age_email = ' || RTRIM(vr_age_email14) || '  , age_observacao = ' || RTRIM(vr_age_observaca15);
		v_sql5 := ' where age_sequencial = ' || RTRIM(vr_age_sequencia01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
		v_sql6 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5 || v_sql6;
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
		       'agenda',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       v_blob1,
		       NULL);
	END IF;
END pr_agenda002;
/

