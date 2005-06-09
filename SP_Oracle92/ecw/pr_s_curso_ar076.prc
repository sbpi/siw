CREATE OR REPLACE PROCEDURE pr_s_curso_ar076(
	P_OP_IN                CHAR,
	PA_co_curso00_IN       s_curso_arred_nota.co_curso%TYPE,
	PA_ano_sem01_IN        s_curso_arred_nota.ano_sem%TYPE,
	PA_co_arredonda02_IN   s_curso_arred_nota.co_arredonda%TYPE,
	PA_nu_nota_inici03_IN  s_curso_arred_nota.nu_nota_inicio%TYPE,
	PA_co_unidade04_IN     s_curso_arred_nota.co_unidade%TYPE,
	PA_nu_nota_fim05_IN    s_curso_arred_nota.nu_nota_fim%TYPE,
	PA_nu_nota_arred06_IN  s_curso_arred_nota.nu_nota_arredondad%TYPE,
	PN_co_curso00_IN       s_curso_arred_nota.co_curso%TYPE,
	PN_ano_sem01_IN        s_curso_arred_nota.ano_sem%TYPE,
	PN_co_arredonda02_IN   s_curso_arred_nota.co_arredonda%TYPE,
	PN_nu_nota_inici03_IN  s_curso_arred_nota.nu_nota_inicio%TYPE,
	PN_co_unidade04_IN     s_curso_arred_nota.co_unidade%TYPE,
	PN_nu_nota_fim05_IN    s_curso_arred_nota.nu_nota_fim%TYPE,
	PN_nu_nota_arred06_IN  s_curso_arred_nota.nu_nota_arredondad%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_curso00       s_curso_arred_nota.co_curso%TYPE := PA_co_curso00_IN;
PA_ano_sem01        s_curso_arred_nota.ano_sem%TYPE := PA_ano_sem01_IN;
PA_co_arredonda02   s_curso_arred_nota.co_arredonda%TYPE := PA_co_arredonda02_IN;
PA_nu_nota_inici03  s_curso_arred_nota.nu_nota_inicio%TYPE := PA_nu_nota_inici03_IN;
PA_co_unidade04     s_curso_arred_nota.co_unidade%TYPE := PA_co_unidade04_IN;
PA_nu_nota_fim05    s_curso_arred_nota.nu_nota_fim%TYPE := PA_nu_nota_fim05_IN;
PA_nu_nota_arred06  s_curso_arred_nota.nu_nota_arredondad%TYPE := PA_nu_nota_arred06_IN;
PN_co_curso00       s_curso_arred_nota.co_curso%TYPE := PN_co_curso00_IN;
PN_ano_sem01        s_curso_arred_nota.ano_sem%TYPE := PN_ano_sem01_IN;
PN_co_arredonda02   s_curso_arred_nota.co_arredonda%TYPE := PN_co_arredonda02_IN;
PN_nu_nota_inici03  s_curso_arred_nota.nu_nota_inicio%TYPE := PN_nu_nota_inici03_IN;
PN_co_unidade04     s_curso_arred_nota.co_unidade%TYPE := PN_co_unidade04_IN;
PN_nu_nota_fim05    s_curso_arred_nota.nu_nota_fim%TYPE := PN_nu_nota_fim05_IN;
PN_nu_nota_arred06  s_curso_arred_nota.nu_nota_arredondad%TYPE := PN_nu_nota_arred06_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_curso00       CHAR(10);
vr_ano_sem01        CHAR(10);
vr_co_arredonda02   CHAR(10);
vr_nu_nota_inici03  CHAR(10);
vr_co_unidade04     CHAR(10);
vr_nu_nota_fim05    CHAR(10);
vr_nu_nota_arred06  CHAR(10);
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
		IF pn_co_arredonda02 IS NULL THEN
			vr_co_arredonda02 := 'null';
		ELSE
			vr_co_arredonda02 := pn_co_arredonda02;
		END IF;
		IF pn_nu_nota_inici03 IS NULL THEN
			vr_nu_nota_inici03 := 'null';
		ELSE
			vr_nu_nota_inici03 := pn_nu_nota_inici03;
		END IF;
		IF pn_co_unidade04 IS NULL THEN
			vr_co_unidade04 := 'null';
		ELSE
			vr_co_unidade04 := pn_co_unidade04;
		END IF;
		IF pn_nu_nota_fim05 IS NULL THEN
			vr_nu_nota_fim05 := 'null';
		ELSE
			vr_nu_nota_fim05 := pn_nu_nota_fim05;
		END IF;
		IF pn_nu_nota_arred06 IS NULL THEN
			vr_nu_nota_arred06 := 'null';
		ELSE
			vr_nu_nota_arred06 := pn_nu_nota_arred06;
		END IF;
		v_sql1 := 'insert into S_CURSO_ARREDONDA_NOTA(co_curso, ano_sem, co_arredonda, nu_nota_inicio, co_unidade, nu_nota_fim, NU_NOTA_ARREDONDADA) values (';
		v_sql2 := RTRIM(vr_co_curso00) || ',' || '"' || RTRIM(vr_ano_sem01) || '"' || ',' || RTRIM(vr_co_arredonda02) || ',' || '"' || RTRIM(vr_nu_nota_inici03) || '"' || ',' || '"' || RTRIM(vr_co_unidade04) || '"' || ',';
		v_sql3 := '"' || RTRIM(vr_nu_nota_fim05) || '"' || ',' || '"' || RTRIM(vr_nu_nota_arred06) || '"' || ');';
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
		IF pa_co_arredonda02 IS NULL THEN
			vr_co_arredonda02 := 'null';
		ELSE
			vr_co_arredonda02 := pa_co_arredonda02;
		END IF;
		IF pa_co_unidade04 IS NULL THEN
			vr_co_unidade04 := 'null';
		ELSE
			vr_co_unidade04 := '"' || RTRIM(pa_co_unidade04) || '"';
		END IF;
		v_sql1 := '  delete from S_CURSO_ARREDONDA_NOTA where co_curso = ' || RTRIM(vr_co_curso00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_arredonda = ' || RTRIM(vr_co_arredonda02);
		v_sql2 := '  and co_unidade = ' || RTRIM(vr_co_unidade04) || ';';
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
		IF pn_co_arredonda02 IS NULL
		AND pa_co_arredonda02 IS NULL THEN
			vr_co_arredonda02 := 'null';
		END IF;
		IF pn_co_arredonda02 IS NULL
		AND pa_co_arredonda02 IS NOT NULL THEN
			vr_co_arredonda02 := 'null';
		END IF;
		IF pn_co_arredonda02 IS NOT NULL
		AND pa_co_arredonda02 IS NULL THEN
			vr_co_arredonda02 := pn_co_arredonda02;
		END IF;
		IF pn_co_arredonda02 IS NOT NULL
		AND pa_co_arredonda02 IS NOT NULL THEN
			IF pa_co_arredonda02 <> pn_co_arredonda02 THEN
				vr_co_arredonda02 := pn_co_arredonda02;
			ELSE
				vr_co_arredonda02 := pa_co_arredonda02;
			END IF;
		END IF;
		IF pn_nu_nota_inici03 IS NULL
		AND pa_nu_nota_inici03 IS NULL THEN
			vr_nu_nota_inici03 := 'null';
		END IF;
		IF pn_nu_nota_inici03 IS NULL
		AND pa_nu_nota_inici03 IS NOT NULL THEN
			vr_nu_nota_inici03 := 'null';
		END IF;
		IF pn_nu_nota_inici03 IS NOT NULL
		AND pa_nu_nota_inici03 IS NULL THEN
			vr_nu_nota_inici03 := '"' || RTRIM(pn_nu_nota_inici03) || '"';
		END IF;
		IF pn_nu_nota_inici03 IS NOT NULL
		AND pa_nu_nota_inici03 IS NOT NULL THEN
			IF pa_nu_nota_inici03 <> pn_nu_nota_inici03 THEN
				vr_nu_nota_inici03 := '"' || RTRIM(pn_nu_nota_inici03) || '"';
			ELSE
				vr_nu_nota_inici03 := '"' || RTRIM(pa_nu_nota_inici03) || '"';
			END IF;
		END IF;
		IF pn_co_unidade04 IS NULL
		AND pa_co_unidade04 IS NULL THEN
			vr_co_unidade04 := 'null';
		END IF;
		IF pn_co_unidade04 IS NULL
		AND pa_co_unidade04 IS NOT NULL THEN
			vr_co_unidade04 := 'null';
		END IF;
		IF pn_co_unidade04 IS NOT NULL
		AND pa_co_unidade04 IS NULL THEN
			vr_co_unidade04 := '"' || RTRIM(pn_co_unidade04) || '"';
		END IF;
		IF pn_co_unidade04 IS NOT NULL
		AND pa_co_unidade04 IS NOT NULL THEN
			IF pa_co_unidade04 <> pn_co_unidade04 THEN
				vr_co_unidade04 := '"' || RTRIM(pn_co_unidade04) || '"';
			ELSE
				vr_co_unidade04 := '"' || RTRIM(pa_co_unidade04) || '"';
			END IF;
		END IF;
		IF pn_nu_nota_fim05 IS NULL
		AND pa_nu_nota_fim05 IS NULL THEN
			vr_nu_nota_fim05 := 'null';
		END IF;
		IF pn_nu_nota_fim05 IS NULL
		AND pa_nu_nota_fim05 IS NOT NULL THEN
			vr_nu_nota_fim05 := 'null';
		END IF;
		IF pn_nu_nota_fim05 IS NOT NULL
		AND pa_nu_nota_fim05 IS NULL THEN
			vr_nu_nota_fim05 := '"' || RTRIM(pn_nu_nota_fim05) || '"';
		END IF;
		IF pn_nu_nota_fim05 IS NOT NULL
		AND pa_nu_nota_fim05 IS NOT NULL THEN
			IF pa_nu_nota_fim05 <> pn_nu_nota_fim05 THEN
				vr_nu_nota_fim05 := '"' || RTRIM(pn_nu_nota_fim05) || '"';
			ELSE
				vr_nu_nota_fim05 := '"' || RTRIM(pa_nu_nota_fim05) || '"';
			END IF;
		END IF;
		IF pn_nu_nota_arred06 IS NULL
		AND pa_nu_nota_arred06 IS NULL THEN
			vr_nu_nota_arred06 := 'null';
		END IF;
		IF pn_nu_nota_arred06 IS NULL
		AND pa_nu_nota_arred06 IS NOT NULL THEN
			vr_nu_nota_arred06 := 'null';
		END IF;
		IF pn_nu_nota_arred06 IS NOT NULL
		AND pa_nu_nota_arred06 IS NULL THEN
			vr_nu_nota_arred06 := '"' || RTRIM(pn_nu_nota_arred06) || '"';
		END IF;
		IF pn_nu_nota_arred06 IS NOT NULL
		AND pa_nu_nota_arred06 IS NOT NULL THEN
			IF pa_nu_nota_arred06 <> pn_nu_nota_arred06 THEN
				vr_nu_nota_arred06 := '"' || RTRIM(pn_nu_nota_arred06) || '"';
			ELSE
				vr_nu_nota_arred06 := '"' || RTRIM(pa_nu_nota_arred06) || '"';
			END IF;
		END IF;
		v_sql1 := 'update S_CURSO_ARREDONDA_NOTA set co_curso = ' || RTRIM(vr_co_curso00) || '  , ano_sem = ' || RTRIM(vr_ano_sem01) || '  , co_arredonda = ' || RTRIM(vr_co_arredonda02) || '  , nu_nota_inicio = ' || RTRIM(vr_nu_nota_inici03);
		v_sql2 := '  , co_unidade = ' || RTRIM(vr_co_unidade04) || '  , nu_nota_fim = ' || RTRIM(vr_nu_nota_fim05) || '  , NU_NOTA_ARREDONDADA = ' || RTRIM(vr_nu_nota_arred06);
		v_sql3 := ' where co_curso = ' || RTRIM(vr_co_curso00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_arredonda = ' || RTRIM(vr_co_arredonda02) || '  and co_unidade = ' || RTRIM(vr_co_unidade04) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	END IF;
	IF p_op = 'del' THEN
		v_uni := pa_co_unidade04;
	ELSE
		v_uni := pn_co_unidade04;
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
		       's_curso_arred_nota',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_curso_ar076;
/

