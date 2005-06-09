CREATE OR REPLACE PROCEDURE pr_s_funciona100(
	P_OP_IN                CHAR,
	PA_co_unidade00_IN     s_funcionario_disc.co_unidade%TYPE,
	PA_st_habilitado01_IN  s_funcionario_disc.st_habilitado%TYPE,
	PA_co_funcionari02_IN  s_funcionario_disc.co_funcionario%TYPE,
	PA_ano_sem03_IN        s_funcionario_disc.ano_sem%TYPE,
	PA_co_disciplina04_IN  s_funcionario_disc.co_disciplina%TYPE,
	PN_co_unidade00_IN     s_funcionario_disc.co_unidade%TYPE,
	PN_st_habilitado01_IN  s_funcionario_disc.st_habilitado%TYPE,
	PN_co_funcionari02_IN  s_funcionario_disc.co_funcionario%TYPE,
	PN_ano_sem03_IN        s_funcionario_disc.ano_sem%TYPE,
	PN_co_disciplina04_IN  s_funcionario_disc.co_disciplina%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_unidade00     s_funcionario_disc.co_unidade%TYPE := PA_co_unidade00_IN;
PA_st_habilitado01  s_funcionario_disc.st_habilitado%TYPE := PA_st_habilitado01_IN;
PA_co_funcionari02  s_funcionario_disc.co_funcionario%TYPE := PA_co_funcionari02_IN;
PA_ano_sem03        s_funcionario_disc.ano_sem%TYPE := PA_ano_sem03_IN;
PA_co_disciplina04  s_funcionario_disc.co_disciplina%TYPE := PA_co_disciplina04_IN;
PN_co_unidade00     s_funcionario_disc.co_unidade%TYPE := PN_co_unidade00_IN;
PN_st_habilitado01  s_funcionario_disc.st_habilitado%TYPE := PN_st_habilitado01_IN;
PN_co_funcionari02  s_funcionario_disc.co_funcionario%TYPE := PN_co_funcionari02_IN;
PN_ano_sem03        s_funcionario_disc.ano_sem%TYPE := PN_ano_sem03_IN;
PN_co_disciplina04  s_funcionario_disc.co_disciplina%TYPE := PN_co_disciplina04_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_unidade00     CHAR(10);
vr_st_habilitado01  CHAR(10);
vr_co_funcionari02  CHAR(20);
vr_ano_sem03        CHAR(10);
vr_co_disciplina04  CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := pn_co_unidade00;
		END IF;
		IF pn_st_habilitado01 IS NULL THEN
			vr_st_habilitado01 := 'null';
		ELSE
			vr_st_habilitado01 := pn_st_habilitado01;
		END IF;
		IF pn_co_funcionari02 IS NULL THEN
			vr_co_funcionari02 := 'null';
		ELSE
			vr_co_funcionari02 := pn_co_funcionari02;
		END IF;
		IF pn_ano_sem03 IS NULL THEN
			vr_ano_sem03 := 'null';
		ELSE
			vr_ano_sem03 := pn_ano_sem03;
		END IF;
		IF pn_co_disciplina04 IS NULL THEN
			vr_co_disciplina04 := 'null';
		ELSE
			vr_co_disciplina04 := pn_co_disciplina04;
		END IF;
		v_sql1 := 'insert into S_FUNCIONARIO_DISCIPLINA(co_unidade, st_habilitado, co_funcionario, ano_sem, co_disciplina) values (';
		v_sql2 := '"' || RTRIM(vr_co_unidade00) || '"' || ',' || '"' || RTRIM(vr_st_habilitado01) || '"' || ',' || '"' || RTRIM(vr_co_funcionari02) || '"' || ',' || '"' || RTRIM(vr_ano_sem03) || '"' || ',' || '"' || RTRIM(vr_co_disciplina04) || '"' || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := '"' || RTRIM(pa_co_unidade00) || '"';
		END IF;
		IF pa_co_funcionari02 IS NULL THEN
			vr_co_funcionari02 := 'null';
		ELSE
			vr_co_funcionari02 := '"' || RTRIM(pa_co_funcionari02) || '"';
		END IF;
		IF pa_ano_sem03 IS NULL THEN
			vr_ano_sem03 := 'null';
		ELSE
			vr_ano_sem03 := '"' || RTRIM(pa_ano_sem03) || '"';
		END IF;
		IF pa_co_disciplina04 IS NULL THEN
			vr_co_disciplina04 := 'null';
		ELSE
			vr_co_disciplina04 := '"' || RTRIM(pa_co_disciplina04) || '"';
		END IF;
		v_sql1 := '  delete from S_FUNCIONARIO_DISCIPLINA where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and co_funcionario = ' || RTRIM(vr_co_funcionari02) || '  and ano_sem = ' || RTRIM(vr_ano_sem03) || '  and co_disciplina = ' || RTRIM(vr_co_disciplina04) || ';';
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
		IF pn_st_habilitado01 IS NULL
		AND pa_st_habilitado01 IS NULL THEN
			vr_st_habilitado01 := 'null';
		END IF;
		IF pn_st_habilitado01 IS NULL
		AND pa_st_habilitado01 IS NOT NULL THEN
			vr_st_habilitado01 := 'null';
		END IF;
		IF pn_st_habilitado01 IS NOT NULL
		AND pa_st_habilitado01 IS NULL THEN
			vr_st_habilitado01 := '"' || RTRIM(pn_st_habilitado01) || '"';
		END IF;
		IF pn_st_habilitado01 IS NOT NULL
		AND pa_st_habilitado01 IS NOT NULL THEN
			IF pa_st_habilitado01 <> pn_st_habilitado01 THEN
				vr_st_habilitado01 := '"' || RTRIM(pn_st_habilitado01) || '"';
			ELSE
				vr_st_habilitado01 := '"' || RTRIM(pa_st_habilitado01) || '"';
			END IF;
		END IF;
		IF pn_co_funcionari02 IS NULL
		AND pa_co_funcionari02 IS NULL THEN
			vr_co_funcionari02 := 'null';
		END IF;
		IF pn_co_funcionari02 IS NULL
		AND pa_co_funcionari02 IS NOT NULL THEN
			vr_co_funcionari02 := 'null';
		END IF;
		IF pn_co_funcionari02 IS NOT NULL
		AND pa_co_funcionari02 IS NULL THEN
			vr_co_funcionari02 := '"' || RTRIM(pn_co_funcionari02) || '"';
		END IF;
		IF pn_co_funcionari02 IS NOT NULL
		AND pa_co_funcionari02 IS NOT NULL THEN
			IF pa_co_funcionari02 <> pn_co_funcionari02 THEN
				vr_co_funcionari02 := '"' || RTRIM(pn_co_funcionari02) || '"';
			ELSE
				vr_co_funcionari02 := '"' || RTRIM(pa_co_funcionari02) || '"';
			END IF;
		END IF;
		IF pn_ano_sem03 IS NULL
		AND pa_ano_sem03 IS NULL THEN
			vr_ano_sem03 := 'null';
		END IF;
		IF pn_ano_sem03 IS NULL
		AND pa_ano_sem03 IS NOT NULL THEN
			vr_ano_sem03 := 'null';
		END IF;
		IF pn_ano_sem03 IS NOT NULL
		AND pa_ano_sem03 IS NULL THEN
			vr_ano_sem03 := '"' || RTRIM(pn_ano_sem03) || '"';
		END IF;
		IF pn_ano_sem03 IS NOT NULL
		AND pa_ano_sem03 IS NOT NULL THEN
			IF pa_ano_sem03 <> pn_ano_sem03 THEN
				vr_ano_sem03 := '"' || RTRIM(pn_ano_sem03) || '"';
			ELSE
				vr_ano_sem03 := '"' || RTRIM(pa_ano_sem03) || '"';
			END IF;
		END IF;
		IF pn_co_disciplina04 IS NULL
		AND pa_co_disciplina04 IS NULL THEN
			vr_co_disciplina04 := 'null';
		END IF;
		IF pn_co_disciplina04 IS NULL
		AND pa_co_disciplina04 IS NOT NULL THEN
			vr_co_disciplina04 := 'null';
		END IF;
		IF pn_co_disciplina04 IS NOT NULL
		AND pa_co_disciplina04 IS NULL THEN
			vr_co_disciplina04 := '"' || RTRIM(pn_co_disciplina04) || '"';
		END IF;
		IF pn_co_disciplina04 IS NOT NULL
		AND pa_co_disciplina04 IS NOT NULL THEN
			IF pa_co_disciplina04 <> pn_co_disciplina04 THEN
				vr_co_disciplina04 := '"' || RTRIM(pn_co_disciplina04) || '"';
			ELSE
				vr_co_disciplina04 := '"' || RTRIM(pa_co_disciplina04) || '"';
			END IF;
		END IF;
		v_sql1 := 'update S_FUNCIONARIO_DISCIPLINA set co_unidade = ' || RTRIM(vr_co_unidade00) || '  , st_habilitado = ' || RTRIM(vr_st_habilitado01) || '  , co_funcionario = ' || RTRIM(vr_co_funcionari02) || '  , ano_sem = ' || RTRIM(vr_ano_sem03) || '  , co_disciplina = ' || RTRIM(vr_co_disciplina04);
		v_sql2 := ' where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and co_funcionario = ' || RTRIM(vr_co_funcionari02) || '  and ano_sem = ' || RTRIM(vr_ano_sem03) || '  and co_disciplina = ' || RTRIM(vr_co_disciplina04) || ';';
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
		       's_funcionario_disc',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_funciona100;
/

