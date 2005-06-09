CREATE OR REPLACE PROCEDURE pr_s_curso_do078(
	P_OP_IN                CHAR,
	PA_co_curso00_IN       s_curso_documento.co_curso%TYPE,
	PA_co_unidade01_IN     s_curso_documento.co_unidade%TYPE,
	PA_co_tipo_curso04_IN  s_curso_documento.co_tipo_curso%TYPE,
	PA_ano_sem02_IN        s_curso_documento.ano_sem%TYPE,
	PA_co_documento03_IN   s_curso_documento.co_documento%TYPE,
	PN_co_curso00_IN       s_curso_documento.co_curso%TYPE,
	PN_co_unidade01_IN     s_curso_documento.co_unidade%TYPE,
	PN_co_tipo_curso04_IN  s_curso_documento.co_tipo_curso%TYPE,
	PN_ano_sem02_IN        s_curso_documento.ano_sem%TYPE,
	PN_co_documento03_IN   s_curso_documento.co_documento%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_curso00       s_curso_documento.co_curso%TYPE := PA_co_curso00_IN;
PA_co_unidade01     s_curso_documento.co_unidade%TYPE := PA_co_unidade01_IN;
PA_co_tipo_curso04  s_curso_documento.co_tipo_curso%TYPE := PA_co_tipo_curso04_IN;
PA_ano_sem02        s_curso_documento.ano_sem%TYPE := PA_ano_sem02_IN;
PA_co_documento03   s_curso_documento.co_documento%TYPE := PA_co_documento03_IN;
PN_co_curso00       s_curso_documento.co_curso%TYPE := PN_co_curso00_IN;
PN_co_unidade01     s_curso_documento.co_unidade%TYPE := PN_co_unidade01_IN;
PN_co_tipo_curso04  s_curso_documento.co_tipo_curso%TYPE := PN_co_tipo_curso04_IN;
PN_ano_sem02        s_curso_documento.ano_sem%TYPE := PN_ano_sem02_IN;
PN_co_documento03   s_curso_documento.co_documento%TYPE := PN_co_documento03_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_curso00       CHAR(10);
vr_co_unidade01     CHAR(10);
vr_co_tipo_curso04  CHAR(10);
vr_ano_sem02        CHAR(10);
vr_co_documento03   CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_curso00 IS NULL THEN
			vr_co_curso00 := 'null';
		ELSE
			vr_co_curso00 := pn_co_curso00;
		END IF;
		IF pn_co_unidade01 IS NULL THEN
			vr_co_unidade01 := 'null';
		ELSE
			vr_co_unidade01 := pn_co_unidade01;
		END IF;
		IF pn_co_tipo_curso04 IS NULL THEN
			vr_co_tipo_curso04 := 'null';
		ELSE
			vr_co_tipo_curso04 := pn_co_tipo_curso04;
		END IF;
		IF pn_ano_sem02 IS NULL THEN
			vr_ano_sem02 := 'null';
		ELSE
			vr_ano_sem02 := pn_ano_sem02;
		END IF;
		IF pn_co_documento03 IS NULL THEN
			vr_co_documento03 := 'null';
		ELSE
			vr_co_documento03 := pn_co_documento03;
		END IF;
		v_sql1 := 'insert into s_curso_documento(co_curso, co_unidade, co_tipo_curso, ano_sem, co_documento) values (';
		v_sql2 := RTRIM(vr_co_curso00) || ',' || '"' || RTRIM(vr_co_unidade01) || '"' || ',' || RTRIM(vr_co_tipo_curso04) || ',' || '"' || RTRIM(vr_ano_sem02) || '"' || ',' || RTRIM(vr_co_documento03) || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_curso00 IS NULL THEN
			vr_co_curso00 := 'null';
		ELSE
			vr_co_curso00 := pa_co_curso00;
		END IF;
		IF pa_co_unidade01 IS NULL THEN
			vr_co_unidade01 := 'null';
		ELSE
			vr_co_unidade01 := '"' || RTRIM(pa_co_unidade01) || '"';
		END IF;
		IF pa_co_tipo_curso04 IS NULL THEN
			vr_co_tipo_curso04 := 'null';
		ELSE
			vr_co_tipo_curso04 := pa_co_tipo_curso04;
		END IF;
		IF pa_ano_sem02 IS NULL THEN
			vr_ano_sem02 := 'null';
		ELSE
			vr_ano_sem02 := '"' || RTRIM(pa_ano_sem02) || '"';
		END IF;
		IF pa_co_documento03 IS NULL THEN
			vr_co_documento03 := 'null';
		ELSE
			vr_co_documento03 := pa_co_documento03;
		END IF;
		v_sql1 := '  delete from s_curso_documento where co_curso = ' || RTRIM(vr_co_curso00) || '  and co_unidade = ' || RTRIM(vr_co_unidade01);
		v_sql2 := ' and co_tipo_curso = ' || RTRIM(vr_co_tipo_curso04) || '  and ano_sem = ' || RTRIM(vr_ano_sem02) || '  and co_documento = ' || RTRIM(vr_co_documento03) || ';';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_curso00 IS NULL
		AND pa_co_curso00 IS NULL THEN
			vr_co_curso00 := 'null';
		END IF;
		IF pn_co_curso00 IS NULL
		AND pa_co_curso00 IS NOT NULL THEN
			vr_co_curso00 := 'null';
		END IF;
		IF pn_co_curso00 IS NOT NULL
		AND pa_co_curso00 IS NULL THEN
			vr_co_curso00 := pn_co_curso00;
		END IF;
		IF pn_co_curso00 IS NOT NULL
		AND pa_co_curso00 IS NOT NULL THEN
			IF pa_co_curso00 <> pn_co_curso00 THEN
				vr_co_curso00 := pn_co_curso00;
			ELSE
				vr_co_curso00 := pa_co_curso00;
			END IF;
		END IF;
		IF pn_co_unidade01 IS NULL
		AND pa_co_unidade01 IS NULL THEN
			vr_co_unidade01 := 'null';
		END IF;
		IF pn_co_unidade01 IS NULL
		AND pa_co_unidade01 IS NOT NULL THEN
			vr_co_unidade01 := 'null';
		END IF;
		IF pn_co_unidade01 IS NOT NULL
		AND pa_co_unidade01 IS NULL THEN
			vr_co_unidade01 := '"' || RTRIM(pn_co_unidade01) || '"';
		END IF;
		IF pn_co_unidade01 IS NOT NULL
		AND pa_co_unidade01 IS NOT NULL THEN
			IF pa_co_unidade01 <> pn_co_unidade01 THEN
				vr_co_unidade01 := '"' || RTRIM(pn_co_unidade01) || '"';
			ELSE
				vr_co_unidade01 := '"' || RTRIM(pa_co_unidade01) || '"';
			END IF;
		END IF;
		IF pn_co_tipo_curso04 IS NULL
		AND pa_co_tipo_curso04 IS NULL THEN
			vr_co_tipo_curso04 := 'null';
		END IF;
		IF pn_co_tipo_curso04 IS NULL
		AND pa_co_tipo_curso04 IS NOT NULL THEN
			vr_co_tipo_curso04 := 'null';
		END IF;
		IF pn_co_tipo_curso04 IS NOT NULL
		AND pa_co_tipo_curso04 IS NULL THEN
			vr_co_tipo_curso04 := pn_co_tipo_curso04;
		END IF;
		IF pn_co_tipo_curso04 IS NOT NULL
		AND pa_co_tipo_curso04 IS NOT NULL THEN
			IF pa_co_tipo_curso04 <> pn_co_tipo_curso04 THEN
				vr_co_tipo_curso04 := pn_co_tipo_curso04;
			ELSE
				vr_co_tipo_curso04 := pa_co_tipo_curso04;
			END IF;
		END IF;
		IF pn_ano_sem02 IS NULL
		AND pa_ano_sem02 IS NULL THEN
			vr_ano_sem02 := 'null';
		END IF;
		IF pn_ano_sem02 IS NULL
		AND pa_ano_sem02 IS NOT NULL THEN
			vr_ano_sem02 := 'null';
		END IF;
		IF pn_ano_sem02 IS NOT NULL
		AND pa_ano_sem02 IS NULL THEN
			vr_ano_sem02 := '"' || RTRIM(pn_ano_sem02) || '"';
		END IF;
		IF pn_ano_sem02 IS NOT NULL
		AND pa_ano_sem02 IS NOT NULL THEN
			IF pa_ano_sem02 <> pn_ano_sem02 THEN
				vr_ano_sem02 := '"' || RTRIM(pn_ano_sem02) || '"';
			ELSE
				vr_ano_sem02 := '"' || RTRIM(pa_ano_sem02) || '"';
			END IF;
		END IF;
		IF pn_co_documento03 IS NULL
		AND pa_co_documento03 IS NULL THEN
			vr_co_documento03 := 'null';
		END IF;
		IF pn_co_documento03 IS NULL
		AND pa_co_documento03 IS NOT NULL THEN
			vr_co_documento03 := 'null';
		END IF;
		IF pn_co_documento03 IS NOT NULL
		AND pa_co_documento03 IS NULL THEN
			vr_co_documento03 := pn_co_documento03;
		END IF;
		IF pn_co_documento03 IS NOT NULL
		AND pa_co_documento03 IS NOT NULL THEN
			IF pa_co_documento03 <> pn_co_documento03 THEN
				vr_co_documento03 := pn_co_documento03;
			ELSE
				vr_co_documento03 := pa_co_documento03;
			END IF;
		END IF;
		v_sql1 := 'update s_curso_documento set co_curso = ' || RTRIM(vr_co_curso00) || '  , co_unidade = ' || RTRIM(vr_co_unidade01);
		v_sql2 := ' , co_tipo_curso = ' || RTRIM(vr_co_tipo_curso04) || '  , ano_sem = ' || RTRIM(vr_ano_sem02) || '  , co_documento = ' || RTRIM(vr_co_documento03);
		v_sql3 := ' where co_curso = ' || RTRIM(vr_co_curso00) || '  and co_unidade = ' || RTRIM(vr_co_unidade01) || ' and co_tipo_curso = ' || RTRIM(vr_co_tipo_curso04) || '  and ano_sem = ' || RTRIM(vr_ano_sem02) || '  and co_documento = ' || RTRIM(vr_co_documento03) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	END IF;
	IF p_op = 'del' THEN
		v_uni := pa_co_unidade01;
	ELSE
		v_uni := pn_co_unidade01;
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
		       's_curso_documento',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_curso_do078;
/

