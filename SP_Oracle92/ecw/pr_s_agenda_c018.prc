CREATE OR REPLACE PROCEDURE pr_s_agenda_c018(
	P_OP_IN                CHAR,
	PA_co_unidade00_IN     s_agenda_categoria.co_unidade%TYPE,
	PA_age_sequencia01_IN  s_agenda_categoria.age_sequencial%TYPE,
	PA_cat_sequencia02_IN  s_agenda_categoria.cat_sequencial%TYPE,
	PN_co_unidade00_IN     s_agenda_categoria.co_unidade%TYPE,
	PN_age_sequencia01_IN  s_agenda_categoria.age_sequencial%TYPE,
	PN_cat_sequencia02_IN  s_agenda_categoria.cat_sequencial%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_unidade00     s_agenda_categoria.co_unidade%TYPE := PA_co_unidade00_IN;
PA_age_sequencia01  s_agenda_categoria.age_sequencial%TYPE := PA_age_sequencia01_IN;
PA_cat_sequencia02  s_agenda_categoria.cat_sequencial%TYPE := PA_cat_sequencia02_IN;
PN_co_unidade00     s_agenda_categoria.co_unidade%TYPE := PN_co_unidade00_IN;
PN_age_sequencia01  s_agenda_categoria.age_sequencial%TYPE := PN_age_sequencia01_IN;
PN_cat_sequencia02  s_agenda_categoria.cat_sequencial%TYPE := PN_cat_sequencia02_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_unidade00     CHAR(10);
vr_age_sequencia01  CHAR(10);
vr_cat_sequencia02  CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := pn_co_unidade00;
		END IF;
		IF pn_age_sequencia01 IS NULL THEN
			vr_age_sequencia01 := 'null';
		ELSE
			vr_age_sequencia01 := pn_age_sequencia01;
		END IF;
		IF pn_cat_sequencia02 IS NULL THEN
			vr_cat_sequencia02 := 'null';
		ELSE
			vr_cat_sequencia02 := pn_cat_sequencia02;
		END IF;
		v_sql1 := 'insert into s_agenda_categoria(co_unidade, age_sequencial, cat_sequencial) values (';
		v_sql2 := '"' || RTRIM(vr_co_unidade00) || '"' || ',' || RTRIM(vr_age_sequencia01) || ',' || RTRIM(vr_cat_sequencia02) || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := '"' || RTRIM(pa_co_unidade00) || '"';
		END IF;
		IF pa_age_sequencia01 IS NULL THEN
			vr_age_sequencia01 := 'null';
		ELSE
			vr_age_sequencia01 := pa_age_sequencia01;
		END IF;
		IF pa_cat_sequencia02 IS NULL THEN
			vr_cat_sequencia02 := 'null';
		ELSE
			vr_cat_sequencia02 := pa_cat_sequencia02;
		END IF;
		v_sql1 := '  delete from s_agenda_categoria where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and age_sequencial = ' || RTRIM(vr_age_sequencia01) || '  and cat_sequencial = ' || RTRIM(vr_cat_sequencia02) || ';';
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
		IF pn_cat_sequencia02 IS NULL
		AND pa_cat_sequencia02 IS NULL THEN
			vr_cat_sequencia02 := 'null';
		END IF;
		IF pn_cat_sequencia02 IS NULL
		AND pa_cat_sequencia02 IS NOT NULL THEN
			vr_cat_sequencia02 := 'null';
		END IF;
		IF pn_cat_sequencia02 IS NOT NULL
		AND pa_cat_sequencia02 IS NULL THEN
			vr_cat_sequencia02 := pn_cat_sequencia02;
		END IF;
		IF pn_cat_sequencia02 IS NOT NULL
		AND pa_cat_sequencia02 IS NOT NULL THEN
			IF pa_cat_sequencia02 <> pn_cat_sequencia02 THEN
				vr_cat_sequencia02 := pn_cat_sequencia02;
			ELSE
				vr_cat_sequencia02 := pa_cat_sequencia02;
			END IF;
		END IF;
		v_sql1 := 'update s_agenda_categoria set co_unidade = ' || RTRIM(vr_co_unidade00) || '  , age_sequencial = ' || RTRIM(vr_age_sequencia01) || '  , cat_sequencial = ' || RTRIM(vr_cat_sequencia02);
		v_sql2 := ' where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and age_sequencial = ' || RTRIM(vr_age_sequencia01) || '  and cat_sequencial = ' || RTRIM(vr_cat_sequencia02) || ';';
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
		       's_agenda_categoria',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_agenda_c018;
/

