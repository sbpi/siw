CREATE OR REPLACE PROCEDURE pr_s_aluno_do030(
	P_OP_IN                CHAR,
	PA_co_unidade00_IN     s_aluno_documento.co_unidade%TYPE,
	PA_ano_sem01_IN        s_aluno_documento.ano_sem%TYPE,
	PA_co_aluno02_IN       s_aluno_documento.co_aluno%TYPE,
	PA_co_curso03_IN       s_aluno_documento.co_curso%TYPE,
	PA_co_documento04_IN   s_aluno_documento.co_documento%TYPE,
	PA_co_tipo_curso05_IN  s_aluno_documento.co_tipo_curso%TYPE,
	PN_co_unidade00_IN     s_aluno_documento.co_unidade%TYPE,
	PN_ano_sem01_IN        s_aluno_documento.ano_sem%TYPE,
	PN_co_aluno02_IN       s_aluno_documento.co_aluno%TYPE,
	PN_co_curso03_IN       s_aluno_documento.co_curso%TYPE,
	PN_co_documento04_IN   s_aluno_documento.co_documento%TYPE,
	PN_co_tipo_curso05_IN  s_aluno_documento.co_tipo_curso%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_unidade00     s_aluno_documento.co_unidade%TYPE := PA_co_unidade00_IN;
PA_ano_sem01        s_aluno_documento.ano_sem%TYPE := PA_ano_sem01_IN;
PA_co_aluno02       s_aluno_documento.co_aluno%TYPE := PA_co_aluno02_IN;
PA_co_curso03       s_aluno_documento.co_curso%TYPE := PA_co_curso03_IN;
PA_co_documento04   s_aluno_documento.co_documento%TYPE := PA_co_documento04_IN;
PA_co_tipo_curso05  s_aluno_documento.co_tipo_curso%TYPE := PA_co_tipo_curso05_IN;
PN_co_unidade00     s_aluno_documento.co_unidade%TYPE := PN_co_unidade00_IN;
PN_ano_sem01        s_aluno_documento.ano_sem%TYPE := PN_ano_sem01_IN;
PN_co_aluno02       s_aluno_documento.co_aluno%TYPE := PN_co_aluno02_IN;
PN_co_curso03       s_aluno_documento.co_curso%TYPE := PN_co_curso03_IN;
PN_co_documento04   s_aluno_documento.co_documento%TYPE := PN_co_documento04_IN;
PN_co_tipo_curso05  s_aluno_documento.co_tipo_curso%TYPE := PN_co_tipo_curso05_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_unidade00     CHAR(10);
vr_ano_sem01        CHAR(10);
vr_co_aluno02       CHAR(20);
vr_co_curso03       CHAR(10);
vr_co_documento04   CHAR(10);
vr_co_tipo_curso05  CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := pn_co_unidade00;
		END IF;
		IF pn_ano_sem01 IS NULL THEN
			vr_ano_sem01 := 'null';
		ELSE
			vr_ano_sem01 := pn_ano_sem01;
		END IF;
		IF pn_co_aluno02 IS NULL THEN
			vr_co_aluno02 := 'null';
		ELSE
			vr_co_aluno02 := pn_co_aluno02;
		END IF;
		IF pn_co_curso03 IS NULL THEN
			vr_co_curso03 := 'null';
		ELSE
			vr_co_curso03 := pn_co_curso03;
		END IF;
		IF pn_co_documento04 IS NULL THEN
			vr_co_documento04 := 'null';
		ELSE
			vr_co_documento04 := pn_co_documento04;
		END IF;
		IF pn_co_tipo_curso05 IS NULL THEN
			vr_co_tipo_curso05 := 'null';
		ELSE
			vr_co_tipo_curso05 := pn_co_tipo_curso05;
		END IF;
		v_sql1 := 'insert into s_aluno_documento(co_unidade, ano_sem, co_aluno, co_curso, co_documento, co_tipo_curso) values (';
		v_sql2 := '"' || RTRIM(vr_co_unidade00) || '"' || ',' || '"' || RTRIM(vr_ano_sem01) || '"' || ',' || '"' || RTRIM(vr_co_aluno02) || '"' || ',' || RTRIM(vr_co_curso03) || ',' || RTRIM(vr_co_documento04) || ',' || RTRIM(vr_co_tipo_curso05) || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := '"' || RTRIM(pa_co_unidade00) || '"';
		END IF;
		IF pa_ano_sem01 IS NULL THEN
			vr_ano_sem01 := 'null';
		ELSE
			vr_ano_sem01 := '"' || RTRIM(pa_ano_sem01) || '"';
		END IF;
		IF pa_co_aluno02 IS NULL THEN
			vr_co_aluno02 := 'null';
		ELSE
			vr_co_aluno02 := '"' || RTRIM(pa_co_aluno02) || '"';
		END IF;
		IF pa_co_curso03 IS NULL THEN
			vr_co_curso03 := 'null';
		ELSE
			vr_co_curso03 := pa_co_curso03;
		END IF;
		IF pa_co_documento04 IS NULL THEN
			vr_co_documento04 := 'null';
		ELSE
			vr_co_documento04 := pa_co_documento04;
		END IF;
		IF pa_co_tipo_curso05 IS NULL THEN
			vr_co_tipo_curso05 := 'null';
		ELSE
			vr_co_tipo_curso05 := pa_co_tipo_curso05;
		END IF;
		v_sql1 := '  delete from s_aluno_documento where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_aluno = ' || RTRIM(vr_co_aluno02) || '  and co_curso = ' || RTRIM(vr_co_curso03) || '  and co_documento = ' || RTRIM(vr_co_documento04) || ' and co_tipo_curso = ' || RTRIM(vr_co_tipo_curso05) || ';';
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
		IF pn_ano_sem01 IS NULL
		AND pa_ano_sem01 IS NULL THEN
			vr_ano_sem01 := 'null';
		END IF;
		IF pn_ano_sem01 IS NULL
		AND pa_ano_sem01 IS NOT NULL THEN
			vr_ano_sem01 := 'null';
		END IF;
		IF pn_ano_sem01 IS NOT NULL
		AND pa_ano_sem01 IS NULL THEN
			vr_ano_sem01 := '"' || RTRIM(pn_ano_sem01) || '"';
		END IF;
		IF pn_ano_sem01 IS NOT NULL
		AND pa_ano_sem01 IS NOT NULL THEN
			IF pa_ano_sem01 <> pn_ano_sem01 THEN
				vr_ano_sem01 := '"' || RTRIM(pn_ano_sem01) || '"';
			ELSE
				vr_ano_sem01 := '"' || RTRIM(pa_ano_sem01) || '"';
			END IF;
		END IF;
		IF pn_co_aluno02 IS NULL
		AND pa_co_aluno02 IS NULL THEN
			vr_co_aluno02 := 'null';
		END IF;
		IF pn_co_aluno02 IS NULL
		AND pa_co_aluno02 IS NOT NULL THEN
			vr_co_aluno02 := 'null';
		END IF;
		IF pn_co_aluno02 IS NOT NULL
		AND pa_co_aluno02 IS NULL THEN
			vr_co_aluno02 := '"' || RTRIM(pn_co_aluno02) || '"';
		END IF;
		IF pn_co_aluno02 IS NOT NULL
		AND pa_co_aluno02 IS NOT NULL THEN
			IF pa_co_aluno02 <> pn_co_aluno02 THEN
				vr_co_aluno02 := '"' || RTRIM(pn_co_aluno02) || '"';
			ELSE
				vr_co_aluno02 := '"' || RTRIM(pa_co_aluno02) || '"';
			END IF;
		END IF;
		IF pn_co_curso03 IS NULL
		AND pa_co_curso03 IS NULL THEN
			vr_co_curso03 := 'null';
		END IF;
		IF pn_co_curso03 IS NULL
		AND pa_co_curso03 IS NOT NULL THEN
			vr_co_curso03 := 'null';
		END IF;
		IF pn_co_curso03 IS NOT NULL
		AND pa_co_curso03 IS NULL THEN
			vr_co_curso03 := pn_co_curso03;
		END IF;
		IF pn_co_curso03 IS NOT NULL
		AND pa_co_curso03 IS NOT NULL THEN
			IF pa_co_curso03 <> pn_co_curso03 THEN
				vr_co_curso03 := pn_co_curso03;
			ELSE
				vr_co_curso03 := pa_co_curso03;
			END IF;
		END IF;
		IF pn_co_documento04 IS NULL
		AND pa_co_documento04 IS NULL THEN
			vr_co_documento04 := 'null';
		END IF;
		IF pn_co_documento04 IS NULL
		AND pa_co_documento04 IS NOT NULL THEN
			vr_co_documento04 := 'null';
		END IF;
		IF pn_co_documento04 IS NOT NULL
		AND pa_co_documento04 IS NULL THEN
			vr_co_documento04 := pn_co_documento04;
		END IF;
		IF pn_co_documento04 IS NOT NULL
		AND pa_co_documento04 IS NOT NULL THEN
			IF pa_co_documento04 <> pn_co_documento04 THEN
				vr_co_documento04 := pn_co_documento04;
			ELSE
				vr_co_documento04 := pa_co_documento04;
			END IF;
		END IF;
		IF pn_co_tipo_curso05 IS NULL
		AND pa_co_tipo_curso05 IS NULL THEN
			vr_co_tipo_curso05 := 'null';
		END IF;
		IF pn_co_tipo_curso05 IS NULL
		AND pa_co_tipo_curso05 IS NOT NULL THEN
			vr_co_tipo_curso05 := 'null';
		END IF;
		IF pn_co_tipo_curso05 IS NOT NULL
		AND pa_co_tipo_curso05 IS NULL THEN
			vr_co_tipo_curso05 := pn_co_tipo_curso05;
		END IF;
		IF pn_co_tipo_curso05 IS NOT NULL
		AND pa_co_tipo_curso05 IS NOT NULL THEN
			IF pa_co_tipo_curso05 <> pn_co_tipo_curso05 THEN
				vr_co_tipo_curso05 := pn_co_tipo_curso05;
			ELSE
				vr_co_tipo_curso05 := pa_co_tipo_curso05;
			END IF;
		END IF;
		v_sql1 := 'update s_aluno_documento set co_unidade = ' || RTRIM(vr_co_unidade00) || '  , ano_sem = ' || RTRIM(vr_ano_sem01) || '  , co_aluno = ' || RTRIM(vr_co_aluno02) || '  , co_curso = ' || RTRIM(vr_co_curso03) || '  , co_documento = ' || RTRIM(vr_co_documento04) || ', co_tipo_curso = ' || RTRIM(vr_co_tipo_curso05);
		v_sql2 := ' where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_aluno = ' || RTRIM(vr_co_aluno02) || '  and co_curso = ' || RTRIM(vr_co_curso03) || '  and co_documento = ' || RTRIM(vr_co_documento04) || ' and co_tipo_curso = ' || RTRIM(vr_co_tipo_curso05) || ';';
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
		       's_aluno_documento',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_aluno_do030;
/

