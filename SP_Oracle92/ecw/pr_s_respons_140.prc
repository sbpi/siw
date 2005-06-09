CREATE OR REPLACE PROCEDURE pr_s_respons_140(
	P_OP_IN                CHAR,
	PA_co_responsave00_IN  s_respons_aluno.co_responsavel%TYPE,
	PA_co_respons_al01_IN  s_respons_aluno.co_respons_aluno%TYPE,
	PA_co_aluno02_IN       s_respons_aluno.co_aluno%TYPE,
	PA_co_unidade03_IN     s_respons_aluno.co_unidade%TYPE,
	PN_co_responsave00_IN  s_respons_aluno.co_responsavel%TYPE,
	PN_co_respons_al01_IN  s_respons_aluno.co_respons_aluno%TYPE,
	PN_co_aluno02_IN       s_respons_aluno.co_aluno%TYPE,
	PN_co_unidade03_IN     s_respons_aluno.co_unidade%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_responsave00  s_respons_aluno.co_responsavel%TYPE := PA_co_responsave00_IN;
PA_co_respons_al01  s_respons_aluno.co_respons_aluno%TYPE := PA_co_respons_al01_IN;
PA_co_aluno02       s_respons_aluno.co_aluno%TYPE := PA_co_aluno02_IN;
PA_co_unidade03     s_respons_aluno.co_unidade%TYPE := PA_co_unidade03_IN;
PN_co_responsave00  s_respons_aluno.co_responsavel%TYPE := PN_co_responsave00_IN;
PN_co_respons_al01  s_respons_aluno.co_respons_aluno%TYPE := PN_co_respons_al01_IN;
PN_co_aluno02       s_respons_aluno.co_aluno%TYPE := PN_co_aluno02_IN;
PN_co_unidade03     s_respons_aluno.co_unidade%TYPE := PN_co_unidade03_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_responsave00  CHAR(30);
vr_co_respons_al01  CHAR(10);
vr_co_aluno02       CHAR(20);
vr_co_unidade03     CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_responsave00 IS NULL THEN
			vr_co_responsave00 := 'null';
		ELSE
			vr_co_responsave00 := pn_co_responsave00;
		END IF;
		IF pn_co_respons_al01 IS NULL THEN
			vr_co_respons_al01 := 'null';
		ELSE
			vr_co_respons_al01 := pn_co_respons_al01;
		END IF;
		IF pn_co_aluno02 IS NULL THEN
			vr_co_aluno02 := 'null';
		ELSE
			vr_co_aluno02 := pn_co_aluno02;
		END IF;
		IF pn_co_unidade03 IS NULL THEN
			vr_co_unidade03 := 'null';
		ELSE
			vr_co_unidade03 := pn_co_unidade03;
		END IF;
		v_sql1 := 'insert into S_RESPONSAVEL_ALUNO(co_responsavel, co_respons_aluno, co_aluno, co_unidade) values (';
		v_sql2 := '"' || RTRIM(vr_co_responsave00) || '"' || ',' || RTRIM(vr_co_respons_al01) || ',' || '"' || RTRIM(vr_co_aluno02) || '"' || ',' || '"' || RTRIM(vr_co_unidade03) || '"' || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_respons_al01 IS NULL THEN
			vr_co_respons_al01 := 'null';
		ELSE
			vr_co_respons_al01 := pa_co_respons_al01;
		END IF;
		v_sql1 := '  delete from S_RESPONSAVEL_ALUNO where co_respons_aluno = ' || RTRIM(vr_co_respons_al01) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_responsave00 IS NULL
		AND pa_co_responsave00 IS NULL THEN
			vr_co_responsave00 := 'null';
		END IF;
		IF pn_co_responsave00 IS NULL
		AND pa_co_responsave00 IS NOT NULL THEN
			vr_co_responsave00 := 'null';
		END IF;
		IF pn_co_responsave00 IS NOT NULL
		AND pa_co_responsave00 IS NULL THEN
			vr_co_responsave00 := '"' || RTRIM(pn_co_responsave00) || '"';
		END IF;
		IF pn_co_responsave00 IS NOT NULL
		AND pa_co_responsave00 IS NOT NULL THEN
			IF pa_co_responsave00 <> pn_co_responsave00 THEN
				vr_co_responsave00 := '"' || RTRIM(pn_co_responsave00) || '"';
			ELSE
				vr_co_responsave00 := '"' || RTRIM(pa_co_responsave00) || '"';
			END IF;
		END IF;
		IF pn_co_respons_al01 IS NULL
		AND pa_co_respons_al01 IS NULL THEN
			vr_co_respons_al01 := 'null';
		END IF;
		IF pn_co_respons_al01 IS NULL
		AND pa_co_respons_al01 IS NOT NULL THEN
			vr_co_respons_al01 := 'null';
		END IF;
		IF pn_co_respons_al01 IS NOT NULL
		AND pa_co_respons_al01 IS NULL THEN
			vr_co_respons_al01 := pn_co_respons_al01;
		END IF;
		IF pn_co_respons_al01 IS NOT NULL
		AND pa_co_respons_al01 IS NOT NULL THEN
			IF pa_co_respons_al01 <> pn_co_respons_al01 THEN
				vr_co_respons_al01 := pn_co_respons_al01;
			ELSE
				vr_co_respons_al01 := pa_co_respons_al01;
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
		v_sql1 := 'update S_RESPONSAVEL_ALUNO set co_responsavel = ' || RTRIM(vr_co_responsave00) || '  , co_respons_aluno = ' || RTRIM(vr_co_respons_al01) || '  , co_aluno = ' || RTRIM(vr_co_aluno02) || '  , co_unidade = ' || RTRIM(vr_co_unidade03);
		v_sql2 := ' where co_respons_aluno = ' || RTRIM(vr_co_respons_al01) || ';';
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
		       's_respons_aluno',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_respons_140;
/

