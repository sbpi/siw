CREATE OR REPLACE PROCEDURE pr_s_curso_se082(
	P_OP_IN                CHAR,
	PA_co_curso00_IN       s_curso_serie.co_curso%TYPE,
	PA_ano_sem01_IN        s_curso_serie.ano_sem%TYPE,
	PA_co_seq_serie02_IN   s_curso_serie.co_seq_serie%TYPE,
	PA_co_unidade03_IN     s_curso_serie.co_unidade%TYPE,
	PA_sg_serie04_IN       s_curso_serie.sg_serie%TYPE,
	PN_co_curso00_IN       s_curso_serie.co_curso%TYPE,
	PN_ano_sem01_IN        s_curso_serie.ano_sem%TYPE,
	PN_co_seq_serie02_IN   s_curso_serie.co_seq_serie%TYPE,
	PN_co_unidade03_IN     s_curso_serie.co_unidade%TYPE,
	PN_sg_serie04_IN       s_curso_serie.sg_serie%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_curso00       s_curso_serie.co_curso%TYPE := PA_co_curso00_IN;
PA_ano_sem01        s_curso_serie.ano_sem%TYPE := PA_ano_sem01_IN;
PA_co_seq_serie02   s_curso_serie.co_seq_serie%TYPE := PA_co_seq_serie02_IN;
PA_co_unidade03     s_curso_serie.co_unidade%TYPE := PA_co_unidade03_IN;
PA_sg_serie04       s_curso_serie.sg_serie%TYPE := PA_sg_serie04_IN;
PN_co_curso00       s_curso_serie.co_curso%TYPE := PN_co_curso00_IN;
PN_ano_sem01        s_curso_serie.ano_sem%TYPE := PN_ano_sem01_IN;
PN_co_seq_serie02   s_curso_serie.co_seq_serie%TYPE := PN_co_seq_serie02_IN;
PN_co_unidade03     s_curso_serie.co_unidade%TYPE := PN_co_unidade03_IN;
PN_sg_serie04       s_curso_serie.sg_serie%TYPE := PN_sg_serie04_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_curso00       CHAR(10);
vr_ano_sem01        CHAR(10);
vr_co_seq_serie02   CHAR(10);
vr_co_unidade03     CHAR(10);
vr_sg_serie04       CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_curso00 IS NULL THEN
			vr_co_curso00 := 'null';
		ELSE
			vr_co_curso00 := pn_co_curso00;
		END IF;
		IF pn_ano_sem01 IS NULL THEN
			vr_ano_sem01 := 'null';
		ELSE
			vr_ano_sem01 := pn_ano_sem01;
		END IF;
		IF pn_co_seq_serie02 IS NULL THEN
			vr_co_seq_serie02 := 'null';
		ELSE
			vr_co_seq_serie02 := pn_co_seq_serie02;
		END IF;
		IF pn_co_unidade03 IS NULL THEN
			vr_co_unidade03 := 'null';
		ELSE
			vr_co_unidade03 := pn_co_unidade03;
		END IF;
		IF pn_sg_serie04 IS NULL THEN
			vr_sg_serie04 := 'null';
		ELSE
			vr_sg_serie04 := pn_sg_serie04;
		END IF;
		v_sql1 := 'insert into s_curso_serie(co_curso, ano_sem, co_seq_serie, co_unidade, sg_serie) values (';
		v_sql2 := RTRIM(vr_co_curso00) || ',' || '"' || RTRIM(vr_ano_sem01) || '"' || ',' || RTRIM(vr_co_seq_serie02) || ',' || '"' || RTRIM(vr_co_unidade03) || '"' || ',' || '"' || RTRIM(vr_sg_serie04) || '"' || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_curso00 IS NULL THEN
			vr_co_curso00 := 'null';
		ELSE
			vr_co_curso00 := pa_co_curso00;
		END IF;
		IF pa_ano_sem01 IS NULL THEN
			vr_ano_sem01 := 'null';
		ELSE
			vr_ano_sem01 := '"' || RTRIM(pa_ano_sem01) || '"';
		END IF;
		IF pa_co_seq_serie02 IS NULL THEN
			vr_co_seq_serie02 := 'null';
		ELSE
			vr_co_seq_serie02 := pa_co_seq_serie02;
		END IF;
		IF pa_co_unidade03 IS NULL THEN
			vr_co_unidade03 := 'null';
		ELSE
			vr_co_unidade03 := '"' || RTRIM(pa_co_unidade03) || '"';
		END IF;
		v_sql1 := '  delete from s_curso_serie where co_curso = ' || RTRIM(vr_co_curso00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie02) || '  and co_unidade = ' || RTRIM(vr_co_unidade03) || ';';
		v_sql2 := '';
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
		IF pn_co_seq_serie02 IS NULL
		AND pa_co_seq_serie02 IS NULL THEN
			vr_co_seq_serie02 := 'null';
		END IF;
		IF pn_co_seq_serie02 IS NULL
		AND pa_co_seq_serie02 IS NOT NULL THEN
			vr_co_seq_serie02 := 'null';
		END IF;
		IF pn_co_seq_serie02 IS NOT NULL
		AND pa_co_seq_serie02 IS NULL THEN
			vr_co_seq_serie02 := pn_co_seq_serie02;
		END IF;
		IF pn_co_seq_serie02 IS NOT NULL
		AND pa_co_seq_serie02 IS NOT NULL THEN
			IF pa_co_seq_serie02 <> pn_co_seq_serie02 THEN
				vr_co_seq_serie02 := pn_co_seq_serie02;
			ELSE
				vr_co_seq_serie02 := pa_co_seq_serie02;
			END IF;
		END IF;
		IF pn_co_unidade03 IS NULL
		AND pa_co_unidade03 IS NULL THEN
			vr_co_unidade03 := 'null';
		END IF;
		IF pn_co_unidade03 IS NULL
		AND pa_co_unidade03 IS NOT NULL THEN
			vr_co_unidade03 := 'null';
		END IF;
		IF pn_co_unidade03 IS NOT NULL
		AND pa_co_unidade03 IS NULL THEN
			vr_co_unidade03 := '"' || RTRIM(pn_co_unidade03) || '"';
		END IF;
		IF pn_co_unidade03 IS NOT NULL
		AND pa_co_unidade03 IS NOT NULL THEN
			IF pa_co_unidade03 <> pn_co_unidade03 THEN
				vr_co_unidade03 := '"' || RTRIM(pn_co_unidade03) || '"';
			ELSE
				vr_co_unidade03 := '"' || RTRIM(pa_co_unidade03) || '"';
			END IF;
		END IF;
		IF pn_sg_serie04 IS NULL
		AND pa_sg_serie04 IS NULL THEN
			vr_sg_serie04 := 'null';
		END IF;
		IF pn_sg_serie04 IS NULL
		AND pa_sg_serie04 IS NOT NULL THEN
			vr_sg_serie04 := 'null';
		END IF;
		IF pn_sg_serie04 IS NOT NULL
		AND pa_sg_serie04 IS NULL THEN
			vr_sg_serie04 := '"' || RTRIM(pn_sg_serie04) || '"';
		END IF;
		IF pn_sg_serie04 IS NOT NULL
		AND pa_sg_serie04 IS NOT NULL THEN
			IF pa_sg_serie04 <> pn_sg_serie04 THEN
				vr_sg_serie04 := '"' || RTRIM(pn_sg_serie04) || '"';
			ELSE
				vr_sg_serie04 := '"' || RTRIM(pa_sg_serie04) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_curso_serie set co_curso = ' || RTRIM(vr_co_curso00) || '  , ano_sem = ' || RTRIM(vr_ano_sem01) || '  , co_seq_serie = ' || RTRIM(vr_co_seq_serie02) || '  , co_unidade = ' || RTRIM(vr_co_unidade03) || '  , sg_serie = ' || RTRIM(vr_sg_serie04);
		v_sql2 := ' where co_curso = ' || RTRIM(vr_co_curso00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie02) || '  and co_unidade = ' || RTRIM(vr_co_unidade03) || ';';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	END IF;
	IF p_op = 'del' THEN
		v_uni := pa_co_unidade03;
	ELSE
		v_uni := pn_co_unidade03;
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
		       's_curso_serie',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_curso_se082;
/

