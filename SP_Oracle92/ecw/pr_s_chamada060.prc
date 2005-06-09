CREATE OR REPLACE PROCEDURE pr_s_chamada060(
	P_OP_IN                CHAR,
	PA_co_seq_chamad00_IN  s_chamada.co_seq_chamada%TYPE,
	PA_co_unidade01_IN     s_chamada.co_unidade%TYPE,
	PA_ano_sem02_IN        s_chamada.ano_sem%TYPE,
	PA_co_funcionari03_IN  s_chamada.co_funcionario%TYPE,
	PA_co_curso04_IN       s_chamada.co_curso%TYPE,
	PA_co_disciplina05_IN  s_chamada.co_disciplina%TYPE,
	PA_co_seq_serie06_IN   s_chamada.co_seq_serie%TYPE,
	PA_co_turma07_IN       s_chamada.co_turma%TYPE,
	PN_co_seq_chamad00_IN  s_chamada.co_seq_chamada%TYPE,
	PN_co_unidade01_IN     s_chamada.co_unidade%TYPE,
	PN_ano_sem02_IN        s_chamada.ano_sem%TYPE,
	PN_co_funcionari03_IN  s_chamada.co_funcionario%TYPE,
	PN_co_curso04_IN       s_chamada.co_curso%TYPE,
	PN_co_disciplina05_IN  s_chamada.co_disciplina%TYPE,
	PN_co_seq_serie06_IN   s_chamada.co_seq_serie%TYPE,
	PN_co_turma07_IN       s_chamada.co_turma%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_seq_chamad00  s_chamada.co_seq_chamada%TYPE := PA_co_seq_chamad00_IN;
PA_co_unidade01     s_chamada.co_unidade%TYPE := PA_co_unidade01_IN;
PA_ano_sem02        s_chamada.ano_sem%TYPE := PA_ano_sem02_IN;
PA_co_funcionari03  s_chamada.co_funcionario%TYPE := PA_co_funcionari03_IN;
PA_co_curso04       s_chamada.co_curso%TYPE := PA_co_curso04_IN;
PA_co_disciplina05  s_chamada.co_disciplina%TYPE := PA_co_disciplina05_IN;
PA_co_seq_serie06   s_chamada.co_seq_serie%TYPE := PA_co_seq_serie06_IN;
PA_co_turma07       s_chamada.co_turma%TYPE := PA_co_turma07_IN;
PN_co_seq_chamad00  s_chamada.co_seq_chamada%TYPE := PN_co_seq_chamad00_IN;
PN_co_unidade01     s_chamada.co_unidade%TYPE := PN_co_unidade01_IN;
PN_ano_sem02        s_chamada.ano_sem%TYPE := PN_ano_sem02_IN;
PN_co_funcionari03  s_chamada.co_funcionario%TYPE := PN_co_funcionari03_IN;
PN_co_curso04       s_chamada.co_curso%TYPE := PN_co_curso04_IN;
PN_co_disciplina05  s_chamada.co_disciplina%TYPE := PN_co_disciplina05_IN;
PN_co_seq_serie06   s_chamada.co_seq_serie%TYPE := PN_co_seq_serie06_IN;
PN_co_turma07       s_chamada.co_turma%TYPE := PN_co_turma07_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_seq_chamad00  CHAR(10);
vr_co_unidade01     CHAR(10);
vr_ano_sem02        CHAR(10);
vr_co_funcionari03  CHAR(20);
vr_co_curso04       CHAR(10);
vr_co_disciplina05  CHAR(10);
vr_co_seq_serie06   CHAR(10);
vr_co_turma07       CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_seq_chamad00 IS NULL THEN
			vr_co_seq_chamad00 := 'null';
		ELSE
			vr_co_seq_chamad00 := pn_co_seq_chamad00;
		END IF;
		IF pn_co_unidade01 IS NULL THEN
			vr_co_unidade01 := 'null';
		ELSE
			vr_co_unidade01 := pn_co_unidade01;
		END IF;
		IF pn_ano_sem02 IS NULL THEN
			vr_ano_sem02 := 'null';
		ELSE
			vr_ano_sem02 := pn_ano_sem02;
		END IF;
		IF pn_co_funcionari03 IS NULL THEN
			vr_co_funcionari03 := 'null';
		ELSE
			vr_co_funcionari03 := pn_co_funcionari03;
		END IF;
		IF pn_co_curso04 IS NULL THEN
			vr_co_curso04 := 'null';
		ELSE
			vr_co_curso04 := pn_co_curso04;
		END IF;
		IF pn_co_disciplina05 IS NULL THEN
			vr_co_disciplina05 := 'null';
		ELSE
			vr_co_disciplina05 := pn_co_disciplina05;
		END IF;
		IF pn_co_seq_serie06 IS NULL THEN
			vr_co_seq_serie06 := 'null';
		ELSE
			vr_co_seq_serie06 := pn_co_seq_serie06;
		END IF;
		IF pn_co_turma07 IS NULL THEN
			vr_co_turma07 := 'null';
		ELSE
			vr_co_turma07 := pn_co_turma07;
		END IF;
		v_sql1 := 'insert into s_chamada(co_seq_chamada, co_unidade, ano_sem, co_funcionario, co_curso, co_disciplina, co_seq_serie, co_turma) values (';
		v_sql2 := RTRIM(vr_co_seq_chamad00) || ',' || '"' || RTRIM(vr_co_unidade01) || '"' || ',' || '"' || RTRIM(vr_ano_sem02) || '"' || ',' || '"' || RTRIM(vr_co_funcionari03) || '"' || ',';
		v_sql3 := RTRIM(vr_co_curso04) || ',' || '"' || RTRIM(vr_co_disciplina05) || '"' || ',' || RTRIM(vr_co_seq_serie06) || ',' || RTRIM(vr_co_turma07) || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_seq_chamad00 IS NULL THEN
			vr_co_seq_chamad00 := 'null';
		ELSE
			vr_co_seq_chamad00 := pa_co_seq_chamad00;
		END IF;
		IF pa_co_unidade01 IS NULL THEN
			vr_co_unidade01 := 'null';
		ELSE
			vr_co_unidade01 := '"' || RTRIM(pa_co_unidade01) || '"';
		END IF;
		v_sql1 := '  delete from s_chamada where co_seq_chamada = ' || RTRIM(vr_co_seq_chamad00) || '  and co_unidade = ' || RTRIM(vr_co_unidade01) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_seq_chamad00 IS NULL
		AND pa_co_seq_chamad00 IS NULL THEN
			vr_co_seq_chamad00 := 'null';
		END IF;
		IF pn_co_seq_chamad00 IS NULL
		AND pa_co_seq_chamad00 IS NOT NULL THEN
			vr_co_seq_chamad00 := 'null';
		END IF;
		IF pn_co_seq_chamad00 IS NOT NULL
		AND pa_co_seq_chamad00 IS NULL THEN
			vr_co_seq_chamad00 := pn_co_seq_chamad00;
		END IF;
		IF pn_co_seq_chamad00 IS NOT NULL
		AND pa_co_seq_chamad00 IS NOT NULL THEN
			IF pa_co_seq_chamad00 <> pn_co_seq_chamad00 THEN
				vr_co_seq_chamad00 := pn_co_seq_chamad00;
			ELSE
				vr_co_seq_chamad00 := pa_co_seq_chamad00;
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
		IF pn_co_funcionari03 IS NULL
		AND pa_co_funcionari03 IS NULL THEN
			vr_co_funcionari03 := 'null';
		END IF;
		IF pn_co_funcionari03 IS NULL
		AND pa_co_funcionari03 IS NOT NULL THEN
			vr_co_funcionari03 := 'null';
		END IF;
		IF pn_co_funcionari03 IS NOT NULL
		AND pa_co_funcionari03 IS NULL THEN
			vr_co_funcionari03 := '"' || RTRIM(pn_co_funcionari03) || '"';
		END IF;
		IF pn_co_funcionari03 IS NOT NULL
		AND pa_co_funcionari03 IS NOT NULL THEN
			IF pa_co_funcionari03 <> pn_co_funcionari03 THEN
				vr_co_funcionari03 := '"' || RTRIM(pn_co_funcionari03) || '"';
			ELSE
				vr_co_funcionari03 := '"' || RTRIM(pa_co_funcionari03) || '"';
			END IF;
		END IF;
		IF pn_co_curso04 IS NULL
		AND pa_co_curso04 IS NULL THEN
			vr_co_curso04 := 'null';
		END IF;
		IF pn_co_curso04 IS NULL
		AND pa_co_curso04 IS NOT NULL THEN
			vr_co_curso04 := 'null';
		END IF;
		IF pn_co_curso04 IS NOT NULL
		AND pa_co_curso04 IS NULL THEN
			vr_co_curso04 := pn_co_curso04;
		END IF;
		IF pn_co_curso04 IS NOT NULL
		AND pa_co_curso04 IS NOT NULL THEN
			IF pa_co_curso04 <> pn_co_curso04 THEN
				vr_co_curso04 := pn_co_curso04;
			ELSE
				vr_co_curso04 := pa_co_curso04;
			END IF;
		END IF;
		IF pn_co_disciplina05 IS NULL
		AND pa_co_disciplina05 IS NULL THEN
			vr_co_disciplina05 := 'null';
		END IF;
		IF pn_co_disciplina05 IS NULL
		AND pa_co_disciplina05 IS NOT NULL THEN
			vr_co_disciplina05 := 'null';
		END IF;
		IF pn_co_disciplina05 IS NOT NULL
		AND pa_co_disciplina05 IS NULL THEN
			vr_co_disciplina05 := '"' || RTRIM(pn_co_disciplina05) || '"';
		END IF;
		IF pn_co_disciplina05 IS NOT NULL
		AND pa_co_disciplina05 IS NOT NULL THEN
			IF pa_co_disciplina05 <> pn_co_disciplina05 THEN
				vr_co_disciplina05 := '"' || RTRIM(pn_co_disciplina05) || '"';
			ELSE
				vr_co_disciplina05 := '"' || RTRIM(pa_co_disciplina05) || '"';
			END IF;
		END IF;
		IF pn_co_seq_serie06 IS NULL
		AND pa_co_seq_serie06 IS NULL THEN
			vr_co_seq_serie06 := 'null';
		END IF;
		IF pn_co_seq_serie06 IS NULL
		AND pa_co_seq_serie06 IS NOT NULL THEN
			vr_co_seq_serie06 := 'null';
		END IF;
		IF pn_co_seq_serie06 IS NOT NULL
		AND pa_co_seq_serie06 IS NULL THEN
			vr_co_seq_serie06 := pn_co_seq_serie06;
		END IF;
		IF pn_co_seq_serie06 IS NOT NULL
		AND pa_co_seq_serie06 IS NOT NULL THEN
			IF pa_co_seq_serie06 <> pn_co_seq_serie06 THEN
				vr_co_seq_serie06 := pn_co_seq_serie06;
			ELSE
				vr_co_seq_serie06 := pa_co_seq_serie06;
			END IF;
		END IF;
		IF pn_co_turma07 IS NULL
		AND pa_co_turma07 IS NULL THEN
			vr_co_turma07 := 'null';
		END IF;
		IF pn_co_turma07 IS NULL
		AND pa_co_turma07 IS NOT NULL THEN
			vr_co_turma07 := 'null';
		END IF;
		IF pn_co_turma07 IS NOT NULL
		AND pa_co_turma07 IS NULL THEN
			vr_co_turma07 := pn_co_turma07;
		END IF;
		IF pn_co_turma07 IS NOT NULL
		AND pa_co_turma07 IS NOT NULL THEN
			IF pa_co_turma07 <> pn_co_turma07 THEN
				vr_co_turma07 := pn_co_turma07;
			ELSE
				vr_co_turma07 := pa_co_turma07;
			END IF;
		END IF;
		v_sql1 := 'update s_chamada set co_seq_chamada = ' || RTRIM(vr_co_seq_chamad00) || '  , co_unidade = ' || RTRIM(vr_co_unidade01) || '  , ano_sem = ' || RTRIM(vr_ano_sem02) || '  , co_funcionario = ' || RTRIM(vr_co_funcionari03);
		v_sql2 := '  , co_curso = ' || RTRIM(vr_co_curso04) || '  , co_disciplina = ' || RTRIM(vr_co_disciplina05) || '  , co_seq_serie = ' || RTRIM(vr_co_seq_serie06) || '  , co_turma = ' || RTRIM(vr_co_turma07);
		v_sql3 := ' where co_seq_chamada = ' || RTRIM(vr_co_seq_chamad00) || '  and co_unidade = ' || RTRIM(vr_co_unidade01) || ';';
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
		       's_chamada',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_chamada060;
/

