CREATE OR REPLACE PROCEDURE pr_s_curso_ho080(
	P_OP_IN                CHAR,
	PA_ano_sem00_IN        s_curso_hora_aula.ano_sem%TYPE,
	PA_co_turno01_IN       s_curso_hora_aula.co_turno%TYPE,
	PA_co_unidade02_IN     s_curso_hora_aula.co_unidade%TYPE,
	PA_co_curso03_IN       s_curso_hora_aula.co_curso%TYPE,
	PA_nu_hora_aula04_IN   s_curso_hora_aula.nu_hora_aula%TYPE,
	PN_ano_sem00_IN        s_curso_hora_aula.ano_sem%TYPE,
	PN_co_turno01_IN       s_curso_hora_aula.co_turno%TYPE,
	PN_co_unidade02_IN     s_curso_hora_aula.co_unidade%TYPE,
	PN_co_curso03_IN       s_curso_hora_aula.co_curso%TYPE,
	PN_nu_hora_aula04_IN   s_curso_hora_aula.nu_hora_aula%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_ano_sem00        s_curso_hora_aula.ano_sem%TYPE := PA_ano_sem00_IN;
PA_co_turno01       s_curso_hora_aula.co_turno%TYPE := PA_co_turno01_IN;
PA_co_unidade02     s_curso_hora_aula.co_unidade%TYPE := PA_co_unidade02_IN;
PA_co_curso03       s_curso_hora_aula.co_curso%TYPE := PA_co_curso03_IN;
PA_nu_hora_aula04   s_curso_hora_aula.nu_hora_aula%TYPE := PA_nu_hora_aula04_IN;
PN_ano_sem00        s_curso_hora_aula.ano_sem%TYPE := PN_ano_sem00_IN;
PN_co_turno01       s_curso_hora_aula.co_turno%TYPE := PN_co_turno01_IN;
PN_co_unidade02     s_curso_hora_aula.co_unidade%TYPE := PN_co_unidade02_IN;
PN_co_curso03       s_curso_hora_aula.co_curso%TYPE := PN_co_curso03_IN;
PN_nu_hora_aula04   s_curso_hora_aula.nu_hora_aula%TYPE := PN_nu_hora_aula04_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_ano_sem00        CHAR(10);
vr_co_turno01       CHAR(10);
vr_co_unidade02     CHAR(10);
vr_co_curso03       CHAR(10);
vr_nu_hora_aula04   CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_ano_sem00 IS NULL THEN
			vr_ano_sem00 := 'null';
		ELSE
			vr_ano_sem00 := pn_ano_sem00;
		END IF;
		IF pn_co_turno01 IS NULL THEN
			vr_co_turno01 := 'null';
		ELSE
			vr_co_turno01 := pn_co_turno01;
		END IF;
		IF pn_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := pn_co_unidade02;
		END IF;
		IF pn_co_curso03 IS NULL THEN
			vr_co_curso03 := 'null';
		ELSE
			vr_co_curso03 := pn_co_curso03;
		END IF;
		IF pn_nu_hora_aula04 IS NULL THEN
			vr_nu_hora_aula04 := 'null';
		ELSE
			vr_nu_hora_aula04 := pn_nu_hora_aula04;
		END IF;
		v_sql1 := 'insert into s_curso_hora_aula(ano_sem, co_turno, co_unidade, co_curso, nu_hora_aula) values (';
		v_sql2 := '"' || RTRIM(vr_ano_sem00) || '"' || ',' || '"' || RTRIM(vr_co_turno01) || '"' || ',' || '"' || RTRIM(vr_co_unidade02) || '"' || ',' || RTRIM(vr_co_curso03) || ',' || RTRIM(vr_nu_hora_aula04) || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_ano_sem00 IS NULL THEN
			vr_ano_sem00 := 'null';
		ELSE
			vr_ano_sem00 := '"' || RTRIM(pa_ano_sem00) || '"';
		END IF;
		IF pa_co_turno01 IS NULL THEN
			vr_co_turno01 := 'null';
		ELSE
			vr_co_turno01 := '"' || RTRIM(pa_co_turno01) || '"';
		END IF;
		IF pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := '"' || RTRIM(pa_co_unidade02) || '"';
		END IF;
		IF pa_co_curso03 IS NULL THEN
			vr_co_curso03 := 'null';
		ELSE
			vr_co_curso03 := pa_co_curso03;
		END IF;
		v_sql1 := '  delete from s_curso_hora_aula where ano_sem = ' || RTRIM(vr_ano_sem00) || '  and co_turno = ' || RTRIM(vr_co_turno01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || '  and co_curso = ' || RTRIM(vr_co_curso03) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_ano_sem00 IS NULL
		AND pa_ano_sem00 IS NULL THEN
			vr_ano_sem00 := 'null';
		END IF;
		IF pn_ano_sem00 IS NULL
		AND pa_ano_sem00 IS NOT NULL THEN
			vr_ano_sem00 := 'null';
		END IF;
		IF pn_ano_sem00 IS NOT NULL
		AND pa_ano_sem00 IS NULL THEN
			vr_ano_sem00 := '"' || RTRIM(pn_ano_sem00) || '"';
		END IF;
		IF pn_ano_sem00 IS NOT NULL
		AND pa_ano_sem00 IS NOT NULL THEN
			IF pa_ano_sem00 <> pn_ano_sem00 THEN
				vr_ano_sem00 := '"' || RTRIM(pn_ano_sem00) || '"';
			ELSE
				vr_ano_sem00 := '"' || RTRIM(pa_ano_sem00) || '"';
			END IF;
		END IF;
		IF pn_co_turno01 IS NULL
		AND pa_co_turno01 IS NULL THEN
			vr_co_turno01 := 'null';
		END IF;
		IF pn_co_turno01 IS NULL
		AND pa_co_turno01 IS NOT NULL THEN
			vr_co_turno01 := 'null';
		END IF;
		IF pn_co_turno01 IS NOT NULL
		AND pa_co_turno01 IS NULL THEN
			vr_co_turno01 := '"' || RTRIM(pn_co_turno01) || '"';
		END IF;
		IF pn_co_turno01 IS NOT NULL
		AND pa_co_turno01 IS NOT NULL THEN
			IF pa_co_turno01 <> pn_co_turno01 THEN
				vr_co_turno01 := '"' || RTRIM(pn_co_turno01) || '"';
			ELSE
				vr_co_turno01 := '"' || RTRIM(pa_co_turno01) || '"';
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
		IF pn_nu_hora_aula04 IS NULL
		AND pa_nu_hora_aula04 IS NULL THEN
			vr_nu_hora_aula04 := 'null';
		END IF;
		IF pn_nu_hora_aula04 IS NULL
		AND pa_nu_hora_aula04 IS NOT NULL THEN
			vr_nu_hora_aula04 := 'null';
		END IF;
		IF pn_nu_hora_aula04 IS NOT NULL
		AND pa_nu_hora_aula04 IS NULL THEN
			vr_nu_hora_aula04 := pn_nu_hora_aula04;
		END IF;
		IF pn_nu_hora_aula04 IS NOT NULL
		AND pa_nu_hora_aula04 IS NOT NULL THEN
			IF pa_nu_hora_aula04 <> pn_nu_hora_aula04 THEN
				vr_nu_hora_aula04 := pn_nu_hora_aula04;
			ELSE
				vr_nu_hora_aula04 := pa_nu_hora_aula04;
			END IF;
		END IF;
		v_sql1 := 'update s_curso_hora_aula set ano_sem = ' || RTRIM(vr_ano_sem00) || '  , co_turno = ' || RTRIM(vr_co_turno01) || '  , co_unidade = ' || RTRIM(vr_co_unidade02);
		v_sql2 := '  , co_curso = ' || RTRIM(vr_co_curso03) || '  , nu_hora_aula = ' || RTRIM(vr_nu_hora_aula04);
		v_sql3 := ' where ano_sem = ' || RTRIM(vr_ano_sem00) || '  and co_turno = ' || RTRIM(vr_co_turno01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || '  and co_curso = ' || RTRIM(vr_co_curso03) || ';';
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
		       's_curso_hora_aula',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_curso_ho080;
/

