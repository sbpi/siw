CREATE OR REPLACE PROCEDURE pr_fone008(
	P_OP_IN                CHAR,
	PA_fone_sequenci00_IN  fone.fone_sequencial%TYPE,
	PA_age_sequencia01_IN  fone.age_sequencial%TYPE,
	PA_co_unidade02_IN     fone.co_unidade%TYPE,
	PA_cli_codigo03_IN     fone.cli_codigo%TYPE,
	PA_fon_descr04_IN      fone.fon_descr%TYPE,
	PA_fon_tipo05_IN       fone.fon_tipo%TYPE,
	PN_fone_sequenci00_IN  fone.fone_sequencial%TYPE,
	PN_age_sequencia01_IN  fone.age_sequencial%TYPE,
	PN_co_unidade02_IN     fone.co_unidade%TYPE,
	PN_cli_codigo03_IN     fone.cli_codigo%TYPE,
	PN_fon_descr04_IN      fone.fon_descr%TYPE,
	PN_fon_tipo05_IN       fone.fon_tipo%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_fone_sequenci00  fone.fone_sequencial%TYPE := PA_fone_sequenci00_IN;
PA_age_sequencia01  fone.age_sequencial%TYPE := PA_age_sequencia01_IN;
PA_co_unidade02     fone.co_unidade%TYPE := PA_co_unidade02_IN;
PA_cli_codigo03     fone.cli_codigo%TYPE := PA_cli_codigo03_IN;
PA_fon_descr04      fone.fon_descr%TYPE := PA_fon_descr04_IN;
PA_fon_tipo05       fone.fon_tipo%TYPE := PA_fon_tipo05_IN;
PN_fone_sequenci00  fone.fone_sequencial%TYPE := PN_fone_sequenci00_IN;
PN_age_sequencia01  fone.age_sequencial%TYPE := PN_age_sequencia01_IN;
PN_co_unidade02     fone.co_unidade%TYPE := PN_co_unidade02_IN;
PN_cli_codigo03     fone.cli_codigo%TYPE := PN_cli_codigo03_IN;
PN_fon_descr04      fone.fon_descr%TYPE := PN_fon_descr04_IN;
PN_fon_tipo05       fone.fon_tipo%TYPE := PN_fon_tipo05_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_fone_sequenci00  CHAR(10);
vr_age_sequencia01  CHAR(10);
vr_co_unidade02     CHAR(10);
vr_cli_codigo03     CHAR(10);
vr_fon_descr04      CHAR(30);
vr_fon_tipo05       CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_fone_sequenci00 IS NULL THEN
			vr_fone_sequenci00 := 'null';
		ELSE
			vr_fone_sequenci00 := pn_fone_sequenci00;
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
		IF pn_cli_codigo03 IS NULL THEN
			vr_cli_codigo03 := 'null';
		ELSE
			vr_cli_codigo03 := pn_cli_codigo03;
		END IF;
		IF pn_fon_descr04 IS NULL THEN
			vr_fon_descr04 := 'null';
		ELSE
			vr_fon_descr04 := pn_fon_descr04;
		END IF;
		IF pn_fon_tipo05 IS NULL THEN
			vr_fon_tipo05 := 'null';
		ELSE
			vr_fon_tipo05 := pn_fon_tipo05;
		END IF;
		v_sql1 := 'insert into fone(fone_sequencial, age_sequencial, co_unidade, cli_codigo, fon_descr, fon_tipo) values (';
		v_sql2 := RTRIM(vr_fone_sequenci00) || ',' || RTRIM(vr_age_sequencia01) || ',' || '"' || RTRIM(vr_co_unidade02) || '"' || ',' || RTRIM(vr_cli_codigo03) || ',' || '"' || RTRIM(vr_fon_descr04) || '"' || ',' || '"' || RTRIM(vr_fon_tipo05) || '"' || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_fone_sequenci00 IS NULL THEN
			vr_fone_sequenci00 := 'null';
		ELSE
			vr_fone_sequenci00 := pa_fone_sequenci00;
		END IF;
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
		v_sql1 := '  delete from fone where fone_sequencial = ' || RTRIM(vr_fone_sequenci00) || '  and age_sequencial = ' || RTRIM(vr_age_sequencia01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_fone_sequenci00 IS NULL
		AND pa_fone_sequenci00 IS NULL THEN
			vr_fone_sequenci00 := 'null';
		END IF;
		IF pn_fone_sequenci00 IS NULL
		AND pa_fone_sequenci00 IS NOT NULL THEN
			vr_fone_sequenci00 := 'null';
		END IF;
		IF pn_fone_sequenci00 IS NOT NULL
		AND pa_fone_sequenci00 IS NULL THEN
			vr_fone_sequenci00 := pn_fone_sequenci00;
		END IF;
		IF pn_fone_sequenci00 IS NOT NULL
		AND pa_fone_sequenci00 IS NOT NULL THEN
			IF pa_fone_sequenci00 <> pn_fone_sequenci00 THEN
				vr_fone_sequenci00 := pn_fone_sequenci00;
			ELSE
				vr_fone_sequenci00 := pa_fone_sequenci00;
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
		IF pn_cli_codigo03 IS NULL
		AND pa_cli_codigo03 IS NULL THEN
			vr_cli_codigo03 := 'null';
		END IF;
		IF pn_cli_codigo03 IS NULL
		AND pa_cli_codigo03 IS NOT NULL THEN
			vr_cli_codigo03 := 'null';
		END IF;
		IF pn_cli_codigo03 IS NOT NULL
		AND pa_cli_codigo03 IS NULL THEN
			vr_cli_codigo03 := pn_cli_codigo03;
		END IF;
		IF pn_cli_codigo03 IS NOT NULL
		AND pa_cli_codigo03 IS NOT NULL THEN
			IF pa_cli_codigo03 <> pn_cli_codigo03 THEN
				vr_cli_codigo03 := pn_cli_codigo03;
			ELSE
				vr_cli_codigo03 := pa_cli_codigo03;
			END IF;
		END IF;
		IF pn_fon_descr04 IS NULL
		AND pa_fon_descr04 IS NULL THEN
			vr_fon_descr04 := 'null';
		END IF;
		IF pn_fon_descr04 IS NULL
		AND pa_fon_descr04 IS NOT NULL THEN
			vr_fon_descr04 := 'null';
		END IF;
		IF pn_fon_descr04 IS NOT NULL
		AND pa_fon_descr04 IS NULL THEN
			vr_fon_descr04 := '"' || RTRIM(pn_fon_descr04) || '"';
		END IF;
		IF pn_fon_descr04 IS NOT NULL
		AND pa_fon_descr04 IS NOT NULL THEN
			IF pa_fon_descr04 <> pn_fon_descr04 THEN
				vr_fon_descr04 := '"' || RTRIM(pn_fon_descr04) || '"';
			ELSE
				vr_fon_descr04 := '"' || RTRIM(pa_fon_descr04) || '"';
			END IF;
		END IF;
		IF pn_fon_tipo05 IS NULL
		AND pa_fon_tipo05 IS NULL THEN
			vr_fon_tipo05 := 'null';
		END IF;
		IF pn_fon_tipo05 IS NULL
		AND pa_fon_tipo05 IS NOT NULL THEN
			vr_fon_tipo05 := 'null';
		END IF;
		IF pn_fon_tipo05 IS NOT NULL
		AND pa_fon_tipo05 IS NULL THEN
			vr_fon_tipo05 := '"' || RTRIM(pn_fon_tipo05) || '"';
		END IF;
		IF pn_fon_tipo05 IS NOT NULL
		AND pa_fon_tipo05 IS NOT NULL THEN
			IF pa_fon_tipo05 <> pn_fon_tipo05 THEN
				vr_fon_tipo05 := '"' || RTRIM(pn_fon_tipo05) || '"';
			ELSE
				vr_fon_tipo05 := '"' || RTRIM(pa_fon_tipo05) || '"';
			END IF;
		END IF;
		v_sql1 := 'update fone set fone_sequencial = ' || RTRIM(vr_fone_sequenci00) || '  , age_sequencial = ' || RTRIM(vr_age_sequencia01) || '  , co_unidade = ' || RTRIM(vr_co_unidade02) || '  , cli_codigo = ' || RTRIM(vr_cli_codigo03) || '  , fon_descr = ' || RTRIM(vr_fon_descr04) || '  , fon_tipo = ' || RTRIM(vr_fon_tipo05);
		v_sql2 := ' where fone_sequencial = ' || RTRIM(vr_fone_sequenci00) || '  and age_sequencial = ' || RTRIM(vr_age_sequencia01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
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
		       'fone',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_fone008;
/

