CREATE OR REPLACE PROCEDURE pr_s_tipo_not160(
	P_OP_IN                CHAR,
	PA_co_controle00_IN    s_tipo_nota.co_controle%TYPE,
	PA_co_curso01_IN       s_tipo_nota.co_curso%TYPE,
	PA_co_unidade02_IN     s_tipo_nota.co_unidade%TYPE,
	PA_ds_controle03_IN    s_tipo_nota.ds_controle%TYPE,
	PA_ano_sem04_IN        s_tipo_nota.ano_sem%TYPE,
	PA_abv_mostra05_IN     s_tipo_nota.abv_mostra%TYPE,
	PA_abv_formula06_IN    s_tipo_nota.abv_formula%TYPE,
	PA_abv_formula_m07_IN  s_tipo_nota.abv_formula_mostra%TYPE,
	PN_co_controle00_IN    s_tipo_nota.co_controle%TYPE,
	PN_co_curso01_IN       s_tipo_nota.co_curso%TYPE,
	PN_co_unidade02_IN     s_tipo_nota.co_unidade%TYPE,
	PN_ds_controle03_IN    s_tipo_nota.ds_controle%TYPE,
	PN_ano_sem04_IN        s_tipo_nota.ano_sem%TYPE,
	PN_abv_mostra05_IN     s_tipo_nota.abv_mostra%TYPE,
	PN_abv_formula06_IN    s_tipo_nota.abv_formula%TYPE,
	PN_abv_formula_m07_IN  s_tipo_nota.abv_formula_mostra%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_controle00    s_tipo_nota.co_controle%TYPE := PA_co_controle00_IN;
PA_co_curso01       s_tipo_nota.co_curso%TYPE := PA_co_curso01_IN;
PA_co_unidade02     s_tipo_nota.co_unidade%TYPE := PA_co_unidade02_IN;
PA_ds_controle03    s_tipo_nota.ds_controle%TYPE := PA_ds_controle03_IN;
PA_ano_sem04        s_tipo_nota.ano_sem%TYPE := PA_ano_sem04_IN;
PA_abv_mostra05     s_tipo_nota.abv_mostra%TYPE := PA_abv_mostra05_IN;
PA_abv_formula06    s_tipo_nota.abv_formula%TYPE := PA_abv_formula06_IN;
PA_abv_formula_m07  s_tipo_nota.abv_formula_mostra%TYPE := PA_abv_formula_m07_IN;
PN_co_controle00    s_tipo_nota.co_controle%TYPE := PN_co_controle00_IN;
PN_co_curso01       s_tipo_nota.co_curso%TYPE := PN_co_curso01_IN;
PN_co_unidade02     s_tipo_nota.co_unidade%TYPE := PN_co_unidade02_IN;
PN_ds_controle03    s_tipo_nota.ds_controle%TYPE := PN_ds_controle03_IN;
PN_ano_sem04        s_tipo_nota.ano_sem%TYPE := PN_ano_sem04_IN;
PN_abv_mostra05     s_tipo_nota.abv_mostra%TYPE := PN_abv_mostra05_IN;
PN_abv_formula06    s_tipo_nota.abv_formula%TYPE := PN_abv_formula06_IN;
PN_abv_formula_m07  s_tipo_nota.abv_formula_mostra%TYPE := PN_abv_formula_m07_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_controle00    CHAR(10);
vr_co_curso01       CHAR(10);
vr_co_unidade02     CHAR(10);
vr_ds_controle03    CHAR(40);
vr_ano_sem04        CHAR(10);
vr_abv_mostra05     CHAR(25);
vr_abv_formula06    CHAR(10);
vr_abv_formula_m07  CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_controle00 IS NULL THEN
			vr_co_controle00 := 'null';
		ELSE
			vr_co_controle00 := pn_co_controle00;
		END IF;
		IF pn_co_curso01 IS NULL THEN
			vr_co_curso01 := 'null';
		ELSE
			vr_co_curso01 := pn_co_curso01;
		END IF;
		IF pn_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := pn_co_unidade02;
		END IF;
		IF pn_ds_controle03 IS NULL THEN
			vr_ds_controle03 := 'null';
		ELSE
			vr_ds_controle03 := pn_ds_controle03;
		END IF;
		IF pn_ano_sem04 IS NULL THEN
			vr_ano_sem04 := 'null';
		ELSE
			vr_ano_sem04 := pn_ano_sem04;
		END IF;
		IF pn_abv_mostra05 IS NULL THEN
			vr_abv_mostra05 := 'null';
		ELSE
			vr_abv_mostra05 := pn_abv_mostra05;
		END IF;
		IF pn_abv_formula06 IS NULL THEN
			vr_abv_formula06 := 'null';
		ELSE
			vr_abv_formula06 := pn_abv_formula06;
		END IF;
		IF pn_abv_formula_m07 IS NULL THEN
			vr_abv_formula_m07 := 'null';
		ELSE
			vr_abv_formula_m07 := pn_abv_formula_m07;
		END IF;
		v_sql1 := 'insert into s_tipo_nota(co_controle, co_curso, co_unidade, ds_controle, ano_sem, abv_mostra, abv_formula, abv_formula_mostra) values (';
		v_sql2 := RTRIM(vr_co_controle00) || ',' || RTRIM(vr_co_curso01) || ',' || '"' || RTRIM(vr_co_unidade02) || '"' || ',' || '"' || RTRIM(vr_ds_controle03) || '"' || ',' || '"' || RTRIM(vr_ano_sem04) || '"' || ',' || '"' || RTRIM(vr_abv_mostra05) || '"' || ',' || '"' || RTRIM(vr_abv_formula06) || '"' || ',' || '"' || RTRIM(vr_abv_formula_m07) || '"' || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_controle00 IS NULL THEN
			vr_co_controle00 := 'null';
		ELSE
			vr_co_controle00 := pa_co_controle00;
		END IF;
		IF pa_co_curso01 IS NULL THEN
			vr_co_curso01 := 'null';
		ELSE
			vr_co_curso01 := pa_co_curso01;
		END IF;
		IF pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := '"' || RTRIM(pa_co_unidade02) || '"';
		END IF;
		IF pa_ano_sem04 IS NULL THEN
			vr_ano_sem04 := 'null';
		ELSE
			vr_ano_sem04 := '"' || RTRIM(pa_ano_sem04) || '"';
		END IF;
		v_sql1 := '  delete from s_tipo_nota where co_controle = ' || RTRIM(vr_co_controle00) || '  and co_curso = ' || RTRIM(vr_co_curso01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || '  and ano_sem = ' || RTRIM(vr_ano_sem04) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_controle00 IS NULL
		AND pa_co_controle00 IS NULL THEN
			vr_co_controle00 := 'null';
		END IF;
		IF pn_co_controle00 IS NULL
		AND pa_co_controle00 IS NOT NULL THEN
			vr_co_controle00 := 'null';
		END IF;
		IF pn_co_controle00 IS NOT NULL
		AND pa_co_controle00 IS NULL THEN
			vr_co_controle00 := pn_co_controle00;
		END IF;
		IF pn_co_controle00 IS NOT NULL
		AND pa_co_controle00 IS NOT NULL THEN
			IF pa_co_controle00 <> pn_co_controle00 THEN
				vr_co_controle00 := pn_co_controle00;
			ELSE
				vr_co_controle00 := pa_co_controle00;
			END IF;
		END IF;
		IF pn_co_curso01 IS NULL
		AND pa_co_curso01 IS NULL THEN
			vr_co_curso01 := 'null';
		END IF;
		IF pn_co_curso01 IS NULL
		AND pa_co_curso01 IS NOT NULL THEN
			vr_co_curso01 := 'null';
		END IF;
		IF pn_co_curso01 IS NOT NULL
		AND pa_co_curso01 IS NULL THEN
			vr_co_curso01 := pn_co_curso01;
		END IF;
		IF pn_co_curso01 IS NOT NULL
		AND pa_co_curso01 IS NOT NULL THEN
			IF pa_co_curso01 <> pn_co_curso01 THEN
				vr_co_curso01 := pn_co_curso01;
			ELSE
				vr_co_curso01 := pa_co_curso01;
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
		IF pn_ds_controle03 IS NULL
		AND pa_ds_controle03 IS NULL THEN
			vr_ds_controle03 := 'null';
		END IF;
		IF pn_ds_controle03 IS NULL
		AND pa_ds_controle03 IS NOT NULL THEN
			vr_ds_controle03 := 'null';
		END IF;
		IF pn_ds_controle03 IS NOT NULL
		AND pa_ds_controle03 IS NULL THEN
			vr_ds_controle03 := '"' || RTRIM(pn_ds_controle03) || '"';
		END IF;
		IF pn_ds_controle03 IS NOT NULL
		AND pa_ds_controle03 IS NOT NULL THEN
			IF pa_ds_controle03 <> pn_ds_controle03 THEN
				vr_ds_controle03 := '"' || RTRIM(pn_ds_controle03) || '"';
			ELSE
				vr_ds_controle03 := '"' || RTRIM(pa_ds_controle03) || '"';
			END IF;
		END IF;
		IF pn_ano_sem04 IS NULL
		AND pa_ano_sem04 IS NULL THEN
			vr_ano_sem04 := 'null';
		END IF;
		IF pn_ano_sem04 IS NULL
		AND pa_ano_sem04 IS NOT NULL THEN
			vr_ano_sem04 := 'null';
		END IF;
		IF pn_ano_sem04 IS NOT NULL
		AND pa_ano_sem04 IS NULL THEN
			vr_ano_sem04 := '"' || RTRIM(pn_ano_sem04) || '"';
		END IF;
		IF pn_ano_sem04 IS NOT NULL
		AND pa_ano_sem04 IS NOT NULL THEN
			IF pa_ano_sem04 <> pn_ano_sem04 THEN
				vr_ano_sem04 := '"' || RTRIM(pn_ano_sem04) || '"';
			ELSE
				vr_ano_sem04 := '"' || RTRIM(pa_ano_sem04) || '"';
			END IF;
		END IF;
		IF pn_abv_mostra05 IS NULL
		AND pa_abv_mostra05 IS NULL THEN
			vr_abv_mostra05 := 'null';
		END IF;
		IF pn_abv_mostra05 IS NULL
		AND pa_abv_mostra05 IS NOT NULL THEN
			vr_abv_mostra05 := 'null';
		END IF;
		IF pn_abv_mostra05 IS NOT NULL
		AND pa_abv_mostra05 IS NULL THEN
			vr_abv_mostra05 := '"' || RTRIM(pn_abv_mostra05) || '"';
		END IF;
		IF pn_abv_mostra05 IS NOT NULL
		AND pa_abv_mostra05 IS NOT NULL THEN
			IF pa_abv_mostra05 <> pn_abv_mostra05 THEN
				vr_abv_mostra05 := '"' || RTRIM(pn_abv_mostra05) || '"';
			ELSE
				vr_abv_mostra05 := '"' || RTRIM(pa_abv_mostra05) || '"';
			END IF;
		END IF;
		IF pn_abv_formula06 IS NULL
		AND pa_abv_formula06 IS NULL THEN
			vr_abv_formula06 := 'null';
		END IF;
		IF pn_abv_formula06 IS NULL
		AND pa_abv_formula06 IS NOT NULL THEN
			vr_abv_formula06 := 'null';
		END IF;
		IF pn_abv_formula06 IS NOT NULL
		AND pa_abv_formula06 IS NULL THEN
			vr_abv_formula06 := '"' || RTRIM(pn_abv_formula06) || '"';
		END IF;
		IF pn_abv_formula06 IS NOT NULL
		AND pa_abv_formula06 IS NOT NULL THEN
			IF pa_abv_formula06 <> pn_abv_formula06 THEN
				vr_abv_formula06 := '"' || RTRIM(pn_abv_formula06) || '"';
			ELSE
				vr_abv_formula06 := '"' || RTRIM(pa_abv_formula06) || '"';
			END IF;
		END IF;
		IF pn_abv_formula_m07 IS NULL
		AND pa_abv_formula_m07 IS NULL THEN
			vr_abv_formula_m07 := 'null';
		END IF;
		IF pn_abv_formula_m07 IS NULL
		AND pa_abv_formula_m07 IS NOT NULL THEN
			vr_abv_formula_m07 := 'null';
		END IF;
		IF pn_abv_formula_m07 IS NOT NULL
		AND pa_abv_formula_m07 IS NULL THEN
			vr_abv_formula_m07 := '"' || RTRIM(pn_abv_formula_m07) || '"';
		END IF;
		IF pn_abv_formula_m07 IS NOT NULL
		AND pa_abv_formula_m07 IS NOT NULL THEN
			IF pa_abv_formula_m07 <> pn_abv_formula_m07 THEN
				vr_abv_formula_m07 := '"' || RTRIM(pn_abv_formula_m07) || '"';
			ELSE
				vr_abv_formula_m07 := '"' || RTRIM(pa_abv_formula_m07) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_tipo_nota set co_controle = ' || RTRIM(vr_co_controle00) || '  , co_curso = ' || RTRIM(vr_co_curso01) || '  , co_unidade = ' || RTRIM(vr_co_unidade02) || '  , ds_controle = ' || RTRIM(vr_ds_controle03) || '  , ano_sem = ' || RTRIM(vr_ano_sem04);
		v_sql2 := '  , abv_mostra = ' || RTRIM(vr_abv_mostra05) || '  , abv_formula = ' || RTRIM(vr_abv_formula06) || '  , abv_formula_mostra = ' || RTRIM(vr_abv_formula_m07);
		v_sql3 := ' where co_controle = ' || RTRIM(vr_co_controle00) || '  and co_curso = ' || RTRIM(vr_co_curso01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || '  and ano_sem = ' || RTRIM(vr_ano_sem04) || ';';
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
		       's_tipo_nota',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_tipo_not160;
/

