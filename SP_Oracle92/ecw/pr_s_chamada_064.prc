CREATE OR REPLACE PROCEDURE pr_s_chamada_064(
	P_OP_IN                CHAR,
	PA_co_unidade00_IN     s_chamada_turma.co_unidade%TYPE,
	PA_co_seq_chamad01_IN  s_chamada_turma.co_seq_chamada%TYPE,
	PA_co_chamada_tu02_IN  s_chamada_turma.co_chamada_turma%TYPE,
	PA_data_chamada03_IN   s_chamada_turma.data_chamada%TYPE,
	PA_co_controle04_IN    s_chamada_turma.co_controle%TYPE,
	PA_co_curso05_IN       s_chamada_turma.co_curso%TYPE,
	PA_ano_sem06_IN        s_chamada_turma.ano_sem%TYPE,
	PA_aula07_IN           s_chamada_turma.aula%TYPE,
	PN_co_unidade00_IN     s_chamada_turma.co_unidade%TYPE,
	PN_co_seq_chamad01_IN  s_chamada_turma.co_seq_chamada%TYPE,
	PN_co_chamada_tu02_IN  s_chamada_turma.co_chamada_turma%TYPE,
	PN_data_chamada03_IN   s_chamada_turma.data_chamada%TYPE,
	PN_co_controle04_IN    s_chamada_turma.co_controle%TYPE,
	PN_co_curso05_IN       s_chamada_turma.co_curso%TYPE,
	PN_ano_sem06_IN        s_chamada_turma.ano_sem%TYPE,
	PN_aula07_IN           s_chamada_turma.aula%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_unidade00     s_chamada_turma.co_unidade%TYPE := PA_co_unidade00_IN;
PA_co_seq_chamad01  s_chamada_turma.co_seq_chamada%TYPE := PA_co_seq_chamad01_IN;
PA_co_chamada_tu02  s_chamada_turma.co_chamada_turma%TYPE := PA_co_chamada_tu02_IN;
PA_data_chamada03   s_chamada_turma.data_chamada%TYPE := PA_data_chamada03_IN;
PA_co_controle04    s_chamada_turma.co_controle%TYPE := PA_co_controle04_IN;
PA_co_curso05       s_chamada_turma.co_curso%TYPE := PA_co_curso05_IN;
PA_ano_sem06        s_chamada_turma.ano_sem%TYPE := PA_ano_sem06_IN;
PA_aula07           s_chamada_turma.aula%TYPE := PA_aula07_IN;
PN_co_unidade00     s_chamada_turma.co_unidade%TYPE := PN_co_unidade00_IN;
PN_co_seq_chamad01  s_chamada_turma.co_seq_chamada%TYPE := PN_co_seq_chamad01_IN;
PN_co_chamada_tu02  s_chamada_turma.co_chamada_turma%TYPE := PN_co_chamada_tu02_IN;
PN_data_chamada03   s_chamada_turma.data_chamada%TYPE := PN_data_chamada03_IN;
PN_co_controle04    s_chamada_turma.co_controle%TYPE := PN_co_controle04_IN;
PN_co_curso05       s_chamada_turma.co_curso%TYPE := PN_co_curso05_IN;
PN_ano_sem06        s_chamada_turma.ano_sem%TYPE := PN_ano_sem06_IN;
PN_aula07           s_chamada_turma.aula%TYPE := PN_aula07_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_unidade00     CHAR(10);
vr_co_seq_chamad01  CHAR(10);
vr_co_chamada_tu02  CHAR(10);
vr_data_chamada03   CHAR(40);
vr_co_controle04    CHAR(10);
vr_co_curso05       CHAR(10);
vr_ano_sem06        CHAR(10);
vr_aula07           CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := pn_co_unidade00;
		END IF;
		IF pn_co_seq_chamad01 IS NULL THEN
			vr_co_seq_chamad01 := 'null';
		ELSE
			vr_co_seq_chamad01 := pn_co_seq_chamad01;
		END IF;
		IF pn_co_chamada_tu02 IS NULL THEN
			vr_co_chamada_tu02 := 'null';
		ELSE
			vr_co_chamada_tu02 := pn_co_chamada_tu02;
		END IF;
		IF pn_data_chamada03 IS NULL THEN
			vr_data_chamada03 := 'null';
		ELSE
			vr_data_chamada03 := pn_data_chamada03;
		END IF;
		IF pn_co_controle04 IS NULL THEN
			vr_co_controle04 := 'null';
		ELSE
			vr_co_controle04 := pn_co_controle04;
		END IF;
		IF pn_co_curso05 IS NULL THEN
			vr_co_curso05 := 'null';
		ELSE
			vr_co_curso05 := pn_co_curso05;
		END IF;
		IF pn_ano_sem06 IS NULL THEN
			vr_ano_sem06 := 'null';
		ELSE
			vr_ano_sem06 := pn_ano_sem06;
		END IF;
		IF pn_aula07 IS NULL THEN
			vr_aula07 := 'null';
		ELSE
			vr_aula07 := pn_aula07;
		END IF;
		v_sql1 := 'insert into s_chamada_turma(co_unidade, co_seq_chamada, CO_SEQ_CHAMADA_TURMA, data_chamada, co_controle, co_curso, ano_sem, aula) values (';
		v_sql2 := '"' || RTRIM(vr_co_unidade00) || '"' || ',' || RTRIM(vr_co_seq_chamad01) || ',' || RTRIM(vr_co_chamada_tu02) || ',' || '"' || vr_data_chamada03 || '"' || ',' || RTRIM(vr_co_controle04) || ',';
		v_sql3 := RTRIM(vr_co_curso05) || ',' || '"' || RTRIM(vr_ano_sem06) || '"' || ',' || '"' || RTRIM(vr_aula07) || '"' || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := '"' || RTRIM(pa_co_unidade00) || '"';
		END IF;
		IF pa_co_seq_chamad01 IS NULL THEN
			vr_co_seq_chamad01 := 'null';
		ELSE
			vr_co_seq_chamad01 := pa_co_seq_chamad01;
		END IF;
		IF pa_co_chamada_tu02 IS NULL THEN
			vr_co_chamada_tu02 := 'null';
		ELSE
			vr_co_chamada_tu02 := pa_co_chamada_tu02;
		END IF;
		v_sql1 := '  delete from s_chamada_turma where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and co_seq_chamada = ' || RTRIM(vr_co_seq_chamad01) || '  and CO_SEQ_CHAMADA_TURMA = ' || RTRIM(vr_co_chamada_tu02) || ';';
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
		IF pn_co_seq_chamad01 IS NULL
		AND pa_co_seq_chamad01 IS NULL THEN
			vr_co_seq_chamad01 := 'null';
		END IF;
		IF pn_co_seq_chamad01 IS NULL
		AND pa_co_seq_chamad01 IS NOT NULL THEN
			vr_co_seq_chamad01 := 'null';
		END IF;
		IF pn_co_seq_chamad01 IS NOT NULL
		AND pa_co_seq_chamad01 IS NULL THEN
			vr_co_seq_chamad01 := pn_co_seq_chamad01;
		END IF;
		IF pn_co_seq_chamad01 IS NOT NULL
		AND pa_co_seq_chamad01 IS NOT NULL THEN
			IF pa_co_seq_chamad01 <> pn_co_seq_chamad01 THEN
				vr_co_seq_chamad01 := pn_co_seq_chamad01;
			ELSE
				vr_co_seq_chamad01 := pa_co_seq_chamad01;
			END IF;
		END IF;
		IF pn_co_chamada_tu02 IS NULL
		AND pa_co_chamada_tu02 IS NULL THEN
			vr_co_chamada_tu02 := 'null';
		END IF;
		IF pn_co_chamada_tu02 IS NULL
		AND pa_co_chamada_tu02 IS NOT NULL THEN
			vr_co_chamada_tu02 := 'null';
		END IF;
		IF pn_co_chamada_tu02 IS NOT NULL
		AND pa_co_chamada_tu02 IS NULL THEN
			vr_co_chamada_tu02 := pn_co_chamada_tu02;
		END IF;
		IF pn_co_chamada_tu02 IS NOT NULL
		AND pa_co_chamada_tu02 IS NOT NULL THEN
			IF pa_co_chamada_tu02 <> pn_co_chamada_tu02 THEN
				vr_co_chamada_tu02 := pn_co_chamada_tu02;
			ELSE
				vr_co_chamada_tu02 := pa_co_chamada_tu02;
			END IF;
		END IF;
		IF pn_data_chamada03 IS NULL
		AND pa_data_chamada03 IS NULL THEN
			vr_data_chamada03 := 'null';
		END IF;
		IF pn_data_chamada03 IS NULL
		AND pa_data_chamada03 IS NOT NULL THEN
			vr_data_chamada03 := 'null';
		END IF;
		IF pn_data_chamada03 IS NOT NULL
		AND pa_data_chamada03 IS NULL THEN
			vr_data_chamada03 := '"' || pn_data_chamada03 || '"';
		END IF;
		IF pn_data_chamada03 IS NOT NULL
		AND pa_data_chamada03 IS NOT NULL THEN
			IF pa_data_chamada03 <> pn_data_chamada03 THEN
				vr_data_chamada03 := '"' || pn_data_chamada03 || '"';
			ELSE
				vr_data_chamada03 := '"' || pa_data_chamada03 || '"';
			END IF;
		END IF;
		IF pn_co_controle04 IS NULL
		AND pa_co_controle04 IS NULL THEN
			vr_co_controle04 := 'null';
		END IF;
		IF pn_co_controle04 IS NULL
		AND pa_co_controle04 IS NOT NULL THEN
			vr_co_controle04 := 'null';
		END IF;
		IF pn_co_controle04 IS NOT NULL
		AND pa_co_controle04 IS NULL THEN
			vr_co_controle04 := pn_co_controle04;
		END IF;
		IF pn_co_controle04 IS NOT NULL
		AND pa_co_controle04 IS NOT NULL THEN
			IF pa_co_controle04 <> pn_co_controle04 THEN
				vr_co_controle04 := pn_co_controle04;
			ELSE
				vr_co_controle04 := pa_co_controle04;
			END IF;
		END IF;
		IF pn_co_curso05 IS NULL
		AND pa_co_curso05 IS NULL THEN
			vr_co_curso05 := 'null';
		END IF;
		IF pn_co_curso05 IS NULL
		AND pa_co_curso05 IS NOT NULL THEN
			vr_co_curso05 := 'null';
		END IF;
		IF pn_co_curso05 IS NOT NULL
		AND pa_co_curso05 IS NULL THEN
			vr_co_curso05 := pn_co_curso05;
		END IF;
		IF pn_co_curso05 IS NOT NULL
		AND pa_co_curso05 IS NOT NULL THEN
			IF pa_co_curso05 <> pn_co_curso05 THEN
				vr_co_curso05 := pn_co_curso05;
			ELSE
				vr_co_curso05 := pa_co_curso05;
			END IF;
		END IF;
		IF pn_ano_sem06 IS NULL
		AND pa_ano_sem06 IS NULL THEN
			vr_ano_sem06 := 'null';
		END IF;
		IF pn_ano_sem06 IS NULL
		AND pa_ano_sem06 IS NOT NULL THEN
			vr_ano_sem06 := 'null';
		END IF;
		IF pn_ano_sem06 IS NOT NULL
		AND pa_ano_sem06 IS NULL THEN
			vr_ano_sem06 := '"' || RTRIM(pn_ano_sem06) || '"';
		END IF;
		IF pn_ano_sem06 IS NOT NULL
		AND pa_ano_sem06 IS NOT NULL THEN
			IF pa_ano_sem06 <> pn_ano_sem06 THEN
				vr_ano_sem06 := '"' || RTRIM(pn_ano_sem06) || '"';
			ELSE
				vr_ano_sem06 := '"' || RTRIM(pa_ano_sem06) || '"';
			END IF;
		END IF;
		IF pn_aula07 IS NULL
		AND pa_aula07 IS NULL THEN
			vr_aula07 := 'null';
		END IF;
		IF pn_aula07 IS NULL
		AND pa_aula07 IS NOT NULL THEN
			vr_aula07 := 'null';
		END IF;
		IF pn_aula07 IS NOT NULL
		AND pa_aula07 IS NULL THEN
			vr_aula07 := '"' || RTRIM(pn_aula07) || '"';
		END IF;
		IF pn_aula07 IS NOT NULL
		AND pa_aula07 IS NOT NULL THEN
			IF pa_aula07 <> pn_aula07 THEN
				vr_aula07 := '"' || RTRIM(pn_aula07) || '"';
			ELSE
				vr_aula07 := '"' || RTRIM(pa_aula07) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_chamada_turma set co_unidade = ' || RTRIM(vr_co_unidade00) || '  , co_seq_chamada = ' || RTRIM(vr_co_seq_chamad01) || '  , CO_SEQ_CHAMADA_TURMA = ' || RTRIM(vr_co_chamada_tu02);
		v_sql2 := '  , data_chamada = ' || RTRIM(vr_data_chamada03) || '  , co_controle = ' || RTRIM(vr_co_controle04) || '  , co_curso = ' || RTRIM(vr_co_curso05) || '  , ano_sem = ' || RTRIM(vr_ano_sem06) || '  , aula = ' || RTRIM(vr_aula07);
		v_sql3 := ' where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and co_seq_chamada = ' || RTRIM(vr_co_seq_chamad01) || '  and co_chamada_turma = ' || RTRIM(vr_co_chamada_tu02) || ';';
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
		       's_chamada_turma',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_chamada_064;
/

