CREATE OR REPLACE PROCEDURE pr_s_aluno_cl026(
	P_OP_IN                CHAR,
	PA_co_aluno00_IN       s_aluno_classifica.co_aluno%TYPE,
	PA_ano_sem01_IN        s_aluno_classifica.ano_sem%TYPE,
	PA_co_unidade02_IN     s_aluno_classifica.co_unidade%TYPE,
	PA_nu_soma_nota03_IN   s_aluno_classifica.nu_soma_nota%TYPE,
	PA_nu_classifica04_IN  s_aluno_classifica.nu_classifica%TYPE,
	PN_co_aluno00_IN       s_aluno_classifica.co_aluno%TYPE,
	PN_ano_sem01_IN        s_aluno_classifica.ano_sem%TYPE,
	PN_co_unidade02_IN     s_aluno_classifica.co_unidade%TYPE,
	PN_nu_soma_nota03_IN   s_aluno_classifica.nu_soma_nota%TYPE,
	PN_nu_classifica04_IN  s_aluno_classifica.nu_classifica%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_aluno00       s_aluno_classifica.co_aluno%TYPE := PA_co_aluno00_IN;
PA_ano_sem01        s_aluno_classifica.ano_sem%TYPE := PA_ano_sem01_IN;
PA_co_unidade02     s_aluno_classifica.co_unidade%TYPE := PA_co_unidade02_IN;
PA_nu_soma_nota03   s_aluno_classifica.nu_soma_nota%TYPE := PA_nu_soma_nota03_IN;
PA_nu_classifica04  s_aluno_classifica.nu_classifica%TYPE := PA_nu_classifica04_IN;
PN_co_aluno00       s_aluno_classifica.co_aluno%TYPE := PN_co_aluno00_IN;
PN_ano_sem01        s_aluno_classifica.ano_sem%TYPE := PN_ano_sem01_IN;
PN_co_unidade02     s_aluno_classifica.co_unidade%TYPE := PN_co_unidade02_IN;
PN_nu_soma_nota03   s_aluno_classifica.nu_soma_nota%TYPE := PN_nu_soma_nota03_IN;
PN_nu_classifica04  s_aluno_classifica.nu_classifica%TYPE := PN_nu_classifica04_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_aluno00       CHAR(20);
vr_ano_sem01        CHAR(10);
vr_co_unidade02     CHAR(10);
vr_nu_soma_nota03   CHAR(10);
vr_nu_classifica04  CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_aluno00 IS NULL THEN
			vr_co_aluno00 := 'null';
		ELSE
			vr_co_aluno00 := pn_co_aluno00;
		END IF;
		IF pn_ano_sem01 IS NULL THEN
			vr_ano_sem01 := 'null';
		ELSE
			vr_ano_sem01 := pn_ano_sem01;
		END IF;
		IF pn_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := pn_co_unidade02;
		END IF;
		IF pn_nu_soma_nota03 IS NULL THEN
			vr_nu_soma_nota03 := 'null';
		ELSE
			vr_nu_soma_nota03 := pn_nu_soma_nota03;
		END IF;
		IF pn_nu_classifica04 IS NULL THEN
			vr_nu_classifica04 := 'null';
		ELSE
			vr_nu_classifica04 := pn_nu_classifica04;
		END IF;
		v_sql1 := 'insert into s_aluno_classifica(co_aluno, ano_sem, co_unidade, nu_soma_nota, nu_classifica) values (';
		v_sql2 := '"' || RTRIM(vr_co_aluno00) || '"' || ',' || '"' || RTRIM(vr_ano_sem01) || '"' || ',' || '"' || RTRIM(vr_co_unidade02) || '"' || ',' || RTRIM(vr_nu_soma_nota03) || ',' || RTRIM(vr_nu_classifica04) || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_aluno00 IS NULL THEN
			vr_co_aluno00 := 'null';
		ELSE
			vr_co_aluno00 := '"' || RTRIM(pa_co_aluno00) || '"';
		END IF;
		IF pa_ano_sem01 IS NULL THEN
			vr_ano_sem01 := 'null';
		ELSE
			vr_ano_sem01 := '"' || RTRIM(pa_ano_sem01) || '"';
		END IF;
		IF pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := '"' || RTRIM(pa_co_unidade02) || '"';
		END IF;
		v_sql1 := '  delete from s_aluno_classifica where co_aluno = ' || RTRIM(vr_co_aluno00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_aluno00 IS NULL
		AND pa_co_aluno00 IS NULL THEN
			vr_co_aluno00 := 'null';
		END IF;
		IF pn_co_aluno00 IS NULL
		AND pa_co_aluno00 IS NOT NULL THEN
			vr_co_aluno00 := 'null';
		END IF;
		IF pn_co_aluno00 IS NOT NULL
		AND pa_co_aluno00 IS NULL THEN
			vr_co_aluno00 := '"' || RTRIM(pn_co_aluno00) || '"';
		END IF;
		IF pn_co_aluno00 IS NOT NULL
		AND pa_co_aluno00 IS NOT NULL THEN
			IF pa_co_aluno00 <> pn_co_aluno00 THEN
				vr_co_aluno00 := '"' || RTRIM(pn_co_aluno00) || '"';
			ELSE
				vr_co_aluno00 := '"' || RTRIM(pa_co_aluno00) || '"';
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
		IF pn_nu_soma_nota03 IS NULL
		AND pa_nu_soma_nota03 IS NULL THEN
			vr_nu_soma_nota03 := 'null';
		END IF;
		IF pn_nu_soma_nota03 IS NULL
		AND pa_nu_soma_nota03 IS NOT NULL THEN
			vr_nu_soma_nota03 := 'null';
		END IF;
		IF pn_nu_soma_nota03 IS NOT NULL
		AND pa_nu_soma_nota03 IS NULL THEN
			vr_nu_soma_nota03 := pn_nu_soma_nota03;
		END IF;
		IF pn_nu_soma_nota03 IS NOT NULL
		AND pa_nu_soma_nota03 IS NOT NULL THEN
			IF pa_nu_soma_nota03 <> pn_nu_soma_nota03 THEN
				vr_nu_soma_nota03 := pn_nu_soma_nota03;
			ELSE
				vr_nu_soma_nota03 := pa_nu_soma_nota03;
			END IF;
		END IF;
		IF pn_nu_classifica04 IS NULL
		AND pa_nu_classifica04 IS NULL THEN
			vr_nu_classifica04 := 'null';
		END IF;
		IF pn_nu_classifica04 IS NULL
		AND pa_nu_classifica04 IS NOT NULL THEN
			vr_nu_classifica04 := 'null';
		END IF;
		IF pn_nu_classifica04 IS NOT NULL
		AND pa_nu_classifica04 IS NULL THEN
			vr_nu_classifica04 := pn_nu_classifica04;
		END IF;
		IF pn_nu_classifica04 IS NOT NULL
		AND pa_nu_classifica04 IS NOT NULL THEN
			IF pa_nu_classifica04 <> pn_nu_classifica04 THEN
				vr_nu_classifica04 := pn_nu_classifica04;
			ELSE
				vr_nu_classifica04 := pa_nu_classifica04;
			END IF;
		END IF;
		v_sql1 := 'update s_aluno_classifica set co_aluno = ' || RTRIM(vr_co_aluno00) || '  , ano_sem = ' || RTRIM(vr_ano_sem01) || '  , co_unidade = ' || RTRIM(vr_co_unidade02) || '  , nu_soma_nota = ' || RTRIM(vr_nu_soma_nota03) || '  , nu_classifica = ' || RTRIM(vr_nu_classifica04);
		v_sql2 := ' where co_aluno = ' || RTRIM(vr_co_aluno00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
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
		       's_aluno_classifica',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_aluno_cl026;
/

