CREATE OR REPLACE PROCEDURE pr_age_catego000(
	P_OP_IN                CHAR,
	PA_cat_sequencia00_IN  age_categoria.cat_sequencial%TYPE,
	PA_cat_descricao01_IN  age_categoria.cat_descricao%TYPE,
	PA_co_unidade02_IN     age_categoria.co_unidade%TYPE,
	PN_cat_sequencia00_IN  age_categoria.cat_sequencial%TYPE,
	PN_cat_descricao01_IN  age_categoria.cat_descricao%TYPE,
	PN_co_unidade02_IN     age_categoria.co_unidade%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_cat_sequencia00  age_categoria.cat_sequencial%TYPE := PA_cat_sequencia00_IN;
PA_cat_descricao01  age_categoria.cat_descricao%TYPE := PA_cat_descricao01_IN;
PA_co_unidade02     age_categoria.co_unidade%TYPE := PA_co_unidade02_IN;
PN_cat_sequencia00  age_categoria.cat_sequencial%TYPE := PN_cat_sequencia00_IN;
PN_cat_descricao01  age_categoria.cat_descricao%TYPE := PN_cat_descricao01_IN;
PN_co_unidade02     age_categoria.co_unidade%TYPE := PN_co_unidade02_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_cat_sequencia00  CHAR(10);
vr_cat_descricao01  CHAR(40);
vr_co_unidade02     CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_cat_sequencia00 IS NULL THEN
			vr_cat_sequencia00 := 'null';
		ELSE
			vr_cat_sequencia00 := pn_cat_sequencia00;
		END IF;
		IF pn_cat_descricao01 IS NULL THEN
			vr_cat_descricao01 := 'null';
		ELSE
			vr_cat_descricao01 := pn_cat_descricao01;
		END IF;
		IF pn_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := pn_co_unidade02;
		END IF;
		v_sql1 := 'insert into age_categoria(cat_sequencial, cat_descricao, co_unidade) values (';
		v_sql2 := RTRIM(vr_cat_sequencia00) || ',' || '"' || RTRIM(vr_cat_descricao01) || '"' || ',' || '"' || RTRIM(vr_co_unidade02) || '"' || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_cat_sequencia00 IS NULL THEN
			vr_cat_sequencia00 := 'null';
		ELSE
			vr_cat_sequencia00 := pa_cat_sequencia00;
		END IF;
		IF pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := '"' || RTRIM(pa_co_unidade02) || '"';
		END IF;
		v_sql1 := '  delete from age_categoria where cat_sequencial = ' || RTRIM(vr_cat_sequencia00) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_cat_sequencia00 IS NULL
		AND pa_cat_sequencia00 IS NULL THEN
			vr_cat_sequencia00 := 'null';
		END IF;
		IF pn_cat_sequencia00 IS NULL
		AND pa_cat_sequencia00 IS NOT NULL THEN
			vr_cat_sequencia00 := 'null';
		END IF;
		IF pn_cat_sequencia00 IS NOT NULL
		AND pa_cat_sequencia00 IS NULL THEN
			vr_cat_sequencia00 := pn_cat_sequencia00;
		END IF;
		IF pn_cat_sequencia00 IS NOT NULL
		AND pa_cat_sequencia00 IS NOT NULL THEN
			IF pa_cat_sequencia00 <> pn_cat_sequencia00 THEN
				vr_cat_sequencia00 := pn_cat_sequencia00;
			ELSE
				vr_cat_sequencia00 := pa_cat_sequencia00;
			END IF;
		END IF;
		IF pn_cat_descricao01 IS NULL
		AND pa_cat_descricao01 IS NULL THEN
			vr_cat_descricao01 := 'null';
		END IF;
		IF pn_cat_descricao01 IS NULL
		AND pa_cat_descricao01 IS NOT NULL THEN
			vr_cat_descricao01 := 'null';
		END IF;
		IF pn_cat_descricao01 IS NOT NULL
		AND pa_cat_descricao01 IS NULL THEN
			vr_cat_descricao01 := '"' || RTRIM(pn_cat_descricao01) || '"';
		END IF;
		IF pn_cat_descricao01 IS NOT NULL
		AND pa_cat_descricao01 IS NOT NULL THEN
			IF pa_cat_descricao01 <> pn_cat_descricao01 THEN
				vr_cat_descricao01 := '"' || RTRIM(pn_cat_descricao01) || '"';
			ELSE
				vr_cat_descricao01 := '"' || RTRIM(pa_cat_descricao01) || '"';
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
		v_sql1 := 'update age_categoria set cat_sequencial = ' || RTRIM(vr_cat_sequencia00) || '  , cat_descricao = ' || RTRIM(vr_cat_descricao01) || '  , co_unidade = ' || RTRIM(vr_co_unidade02);
		v_sql2 := ' where cat_sequencial = ' || RTRIM(vr_cat_sequencia00) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
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
		       'age_categoria',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_age_catego000;
/

