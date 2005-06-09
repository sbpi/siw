CREATE OR REPLACE PROCEDURE pr_s_chamada_062(
	P_OP_IN                CHAR,
	PA_co_unidade00_IN     s_chamada_falta.co_unidade%TYPE,
	PA_co_chamada_tu01_IN  s_chamada_falta.co_chamada_turma%TYPE,
	PA_ano_sem02_IN        s_chamada_falta.ano_sem%TYPE,
	PA_co_seq_chamad03_IN  s_chamada_falta.co_seq_chamada%TYPE,
	PA_co_turma04_IN       s_chamada_falta.co_turma%TYPE,
	PA_co_disciplina05_IN  s_chamada_falta.co_disciplina%TYPE,
	PA_co_curso06_IN       s_chamada_falta.co_curso%TYPE,
	PA_co_seq_serie07_IN   s_chamada_falta.co_seq_serie%TYPE,
	PA_co_aluno08_IN       s_chamada_falta.co_aluno%TYPE,
	PA_chamada09_IN        s_chamada_falta.chamada%TYPE,
	PA_justificativa10_IN  s_chamada_falta.justificativa%TYPE,
	PN_co_unidade00_IN     s_chamada_falta.co_unidade%TYPE,
	PN_co_chamada_tu01_IN  s_chamada_falta.co_chamada_turma%TYPE,
	PN_ano_sem02_IN        s_chamada_falta.ano_sem%TYPE,
	PN_co_seq_chamad03_IN  s_chamada_falta.co_seq_chamada%TYPE,
	PN_co_turma04_IN       s_chamada_falta.co_turma%TYPE,
	PN_co_disciplina05_IN  s_chamada_falta.co_disciplina%TYPE,
	PN_co_curso06_IN       s_chamada_falta.co_curso%TYPE,
	PN_co_seq_serie07_IN   s_chamada_falta.co_seq_serie%TYPE,
	PN_co_aluno08_IN       s_chamada_falta.co_aluno%TYPE,
	PN_chamada09_IN        s_chamada_falta.chamada%TYPE,
	PN_justificativa10_IN  s_chamada_falta.justificativa%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_unidade00     s_chamada_falta.co_unidade%TYPE := PA_co_unidade00_IN;
PA_co_chamada_tu01  s_chamada_falta.co_chamada_turma%TYPE := PA_co_chamada_tu01_IN;
PA_ano_sem02        s_chamada_falta.ano_sem%TYPE := PA_ano_sem02_IN;
PA_co_seq_chamad03  s_chamada_falta.co_seq_chamada%TYPE := PA_co_seq_chamad03_IN;
PA_co_turma04       s_chamada_falta.co_turma%TYPE := PA_co_turma04_IN;
PA_co_disciplina05  s_chamada_falta.co_disciplina%TYPE := PA_co_disciplina05_IN;
PA_co_curso06       s_chamada_falta.co_curso%TYPE := PA_co_curso06_IN;
PA_co_seq_serie07   s_chamada_falta.co_seq_serie%TYPE := PA_co_seq_serie07_IN;
PA_co_aluno08       s_chamada_falta.co_aluno%TYPE := PA_co_aluno08_IN;
PA_chamada09        s_chamada_falta.chamada%TYPE := PA_chamada09_IN;
PA_justificativa10  s_chamada_falta.justificativa%TYPE := PA_justificativa10_IN;
PN_co_unidade00     s_chamada_falta.co_unidade%TYPE := PN_co_unidade00_IN;
PN_co_chamada_tu01  s_chamada_falta.co_chamada_turma%TYPE := PN_co_chamada_tu01_IN;
PN_ano_sem02        s_chamada_falta.ano_sem%TYPE := PN_ano_sem02_IN;
PN_co_seq_chamad03  s_chamada_falta.co_seq_chamada%TYPE := PN_co_seq_chamad03_IN;
PN_co_turma04       s_chamada_falta.co_turma%TYPE := PN_co_turma04_IN;
PN_co_disciplina05  s_chamada_falta.co_disciplina%TYPE := PN_co_disciplina05_IN;
PN_co_curso06       s_chamada_falta.co_curso%TYPE := PN_co_curso06_IN;
PN_co_seq_serie07   s_chamada_falta.co_seq_serie%TYPE := PN_co_seq_serie07_IN;
PN_co_aluno08       s_chamada_falta.co_aluno%TYPE := PN_co_aluno08_IN;
PN_chamada09        s_chamada_falta.chamada%TYPE := PN_chamada09_IN;
PN_justificativa10  s_chamada_falta.justificativa%TYPE := PN_justificativa10_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_unidade00     CHAR(10);
vr_co_chamada_tu01  CHAR(10);
vr_ano_sem02        CHAR(10);
vr_co_seq_chamad03  CHAR(10);
vr_co_turma04       CHAR(10);
vr_co_disciplina05  CHAR(10);
vr_co_curso06       CHAR(10);
vr_co_seq_serie07   CHAR(10);
vr_co_aluno08       CHAR(20);
vr_chamada09        CHAR(10);
vr_justificativa10  CHAR(30);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := pn_co_unidade00;
		END IF;
		IF pn_co_chamada_tu01 IS NULL THEN
			vr_co_chamada_tu01 := 'null';
		ELSE
			vr_co_chamada_tu01 := pn_co_chamada_tu01;
		END IF;
		IF pn_ano_sem02 IS NULL THEN
			vr_ano_sem02 := 'null';
		ELSE
			vr_ano_sem02 := pn_ano_sem02;
		END IF;
		IF pn_co_seq_chamad03 IS NULL THEN
			vr_co_seq_chamad03 := 'null';
		ELSE
			vr_co_seq_chamad03 := pn_co_seq_chamad03;
		END IF;
		IF pn_co_turma04 IS NULL THEN
			vr_co_turma04 := 'null';
		ELSE
			vr_co_turma04 := pn_co_turma04;
		END IF;
		IF pn_co_disciplina05 IS NULL THEN
			vr_co_disciplina05 := 'null';
		ELSE
			vr_co_disciplina05 := pn_co_disciplina05;
		END IF;
		IF pn_co_curso06 IS NULL THEN
			vr_co_curso06 := 'null';
		ELSE
			vr_co_curso06 := pn_co_curso06;
		END IF;
		IF pn_co_seq_serie07 IS NULL THEN
			vr_co_seq_serie07 := 'null';
		ELSE
			vr_co_seq_serie07 := pn_co_seq_serie07;
		END IF;
		IF pn_co_aluno08 IS NULL THEN
			vr_co_aluno08 := 'null';
		ELSE
			vr_co_aluno08 := pn_co_aluno08;
		END IF;
		IF pn_chamada09 IS NULL THEN
			vr_chamada09 := 'null';
		ELSE
			vr_chamada09 := pn_chamada09;
		END IF;
		IF pn_justificativa10 IS NULL THEN
			vr_justificativa10 := 'null';
		ELSE
			vr_justificativa10 := pn_justificativa10;
		END IF;
		v_sql1 := 'insert into s_chamada_falta(co_unidade, CO_SEQ_CHAMADA_TURMA, ano_sem, co_seq_chamada, co_turma, co_disciplina, co_curso, co_seq_serie, co_aluno, chamada, justificativa) values (';
		v_sql2 := '"' || RTRIM(vr_co_unidade00) || '"' || ',' || RTRIM(vr_co_chamada_tu01) || ',' || '"' || RTRIM(vr_ano_sem02) || '"' || ',' || RTRIM(vr_co_seq_chamad03) || ',' || RTRIM(vr_co_turma04) || ',' || '"' || RTRIM(vr_co_disciplina05) || '"' || ',';
		v_sql3 := RTRIM(vr_co_curso06) || ',' || RTRIM(vr_co_seq_serie07) || ',' || '"' || RTRIM(vr_co_aluno08) || '"' || ',' || '"' || RTRIM(vr_chamada09) || '"' || ',' || '"' || RTRIM(vr_justificativa10) || '"' || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := '"' || RTRIM(pa_co_unidade00) || '"';
		END IF;
		IF pa_co_chamada_tu01 IS NULL THEN
			vr_co_chamada_tu01 := 'null';
		ELSE
			vr_co_chamada_tu01 := pa_co_chamada_tu01;
		END IF;
		IF pa_co_seq_chamad03 IS NULL THEN
			vr_co_seq_chamad03 := 'null';
		ELSE
			vr_co_seq_chamad03 := pa_co_seq_chamad03;
		END IF;
		IF pa_co_aluno08 IS NULL THEN
			vr_co_aluno08 := 'null';
		ELSE
			vr_co_aluno08 := '"' || RTRIM(pa_co_aluno08) || '"';
		END IF;
		v_sql1 := '  delete from s_chamada_falta where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and CO_SEQ_CHAMADA_TURMA = ' || RTRIM(vr_co_chamada_tu01);
		v_sql2 := '  and co_seq_chamada = ' || RTRIM(vr_co_seq_chamad03) || '  and co_aluno = ' || RTRIM(vr_co_aluno08) || ';';
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
		IF pn_co_chamada_tu01 IS NULL
		AND pa_co_chamada_tu01 IS NULL THEN
			vr_co_chamada_tu01 := 'null';
		END IF;
		IF pn_co_chamada_tu01 IS NULL
		AND pa_co_chamada_tu01 IS NOT NULL THEN
			vr_co_chamada_tu01 := 'null';
		END IF;
		IF pn_co_chamada_tu01 IS NOT NULL
		AND pa_co_chamada_tu01 IS NULL THEN
			vr_co_chamada_tu01 := pn_co_chamada_tu01;
		END IF;
		IF pn_co_chamada_tu01 IS NOT NULL
		AND pa_co_chamada_tu01 IS NOT NULL THEN
			IF pa_co_chamada_tu01 <> pn_co_chamada_tu01 THEN
				vr_co_chamada_tu01 := pn_co_chamada_tu01;
			ELSE
				vr_co_chamada_tu01 := pa_co_chamada_tu01;
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
		IF pn_co_seq_chamad03 IS NULL
		AND pa_co_seq_chamad03 IS NULL THEN
			vr_co_seq_chamad03 := 'null';
		END IF;
		IF pn_co_seq_chamad03 IS NULL
		AND pa_co_seq_chamad03 IS NOT NULL THEN
			vr_co_seq_chamad03 := 'null';
		END IF;
		IF pn_co_seq_chamad03 IS NOT NULL
		AND pa_co_seq_chamad03 IS NULL THEN
			vr_co_seq_chamad03 := pn_co_seq_chamad03;
		END IF;
		IF pn_co_seq_chamad03 IS NOT NULL
		AND pa_co_seq_chamad03 IS NOT NULL THEN
			IF pa_co_seq_chamad03 <> pn_co_seq_chamad03 THEN
				vr_co_seq_chamad03 := pn_co_seq_chamad03;
			ELSE
				vr_co_seq_chamad03 := pa_co_seq_chamad03;
			END IF;
		END IF;
		IF pn_co_turma04 IS NULL
		AND pa_co_turma04 IS NULL THEN
			vr_co_turma04 := 'null';
		END IF;
		IF pn_co_turma04 IS NULL
		AND pa_co_turma04 IS NOT NULL THEN
			vr_co_turma04 := 'null';
		END IF;
		IF pn_co_turma04 IS NOT NULL
		AND pa_co_turma04 IS NULL THEN
			vr_co_turma04 := pn_co_turma04;
		END IF;
		IF pn_co_turma04 IS NOT NULL
		AND pa_co_turma04 IS NOT NULL THEN
			IF pa_co_turma04 <> pn_co_turma04 THEN
				vr_co_turma04 := pn_co_turma04;
			ELSE
				vr_co_turma04 := pa_co_turma04;
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
		IF pn_co_curso06 IS NULL
		AND pa_co_curso06 IS NULL THEN
			vr_co_curso06 := 'null';
		END IF;
		IF pn_co_curso06 IS NULL
		AND pa_co_curso06 IS NOT NULL THEN
			vr_co_curso06 := 'null';
		END IF;
		IF pn_co_curso06 IS NOT NULL
		AND pa_co_curso06 IS NULL THEN
			vr_co_curso06 := pn_co_curso06;
		END IF;
		IF pn_co_curso06 IS NOT NULL
		AND pa_co_curso06 IS NOT NULL THEN
			IF pa_co_curso06 <> pn_co_curso06 THEN
				vr_co_curso06 := pn_co_curso06;
			ELSE
				vr_co_curso06 := pa_co_curso06;
			END IF;
		END IF;
		IF pn_co_seq_serie07 IS NULL
		AND pa_co_seq_serie07 IS NULL THEN
			vr_co_seq_serie07 := 'null';
		END IF;
		IF pn_co_seq_serie07 IS NULL
		AND pa_co_seq_serie07 IS NOT NULL THEN
			vr_co_seq_serie07 := 'null';
		END IF;
		IF pn_co_seq_serie07 IS NOT NULL
		AND pa_co_seq_serie07 IS NULL THEN
			vr_co_seq_serie07 := pn_co_seq_serie07;
		END IF;
		IF pn_co_seq_serie07 IS NOT NULL
		AND pa_co_seq_serie07 IS NOT NULL THEN
			IF pa_co_seq_serie07 <> pn_co_seq_serie07 THEN
				vr_co_seq_serie07 := pn_co_seq_serie07;
			ELSE
				vr_co_seq_serie07 := pa_co_seq_serie07;
			END IF;
		END IF;
		IF pn_co_aluno08 IS NULL
		AND pa_co_aluno08 IS NULL THEN
			vr_co_aluno08 := 'null';
		END IF;
		IF pn_co_aluno08 IS NULL
		AND pa_co_aluno08 IS NOT NULL THEN
			vr_co_aluno08 := 'null';
		END IF;
		IF pn_co_aluno08 IS NOT NULL
		AND pa_co_aluno08 IS NULL THEN
			vr_co_aluno08 := '"' || RTRIM(pn_co_aluno08) || '"';
		END IF;
		IF pn_co_aluno08 IS NOT NULL
		AND pa_co_aluno08 IS NOT NULL THEN
			IF pa_co_aluno08 <> pn_co_aluno08 THEN
				vr_co_aluno08 := '"' || RTRIM(pn_co_aluno08) || '"';
			ELSE
				vr_co_aluno08 := '"' || RTRIM(pa_co_aluno08) || '"';
			END IF;
		END IF;
		IF pn_chamada09 IS NULL
		AND pa_chamada09 IS NULL THEN
			vr_chamada09 := 'null';
		END IF;
		IF pn_chamada09 IS NULL
		AND pa_chamada09 IS NOT NULL THEN
			vr_chamada09 := 'null';
		END IF;
		IF pn_chamada09 IS NOT NULL
		AND pa_chamada09 IS NULL THEN
			vr_chamada09 := '"' || RTRIM(pn_chamada09) || '"';
		END IF;
		IF pn_chamada09 IS NOT NULL
		AND pa_chamada09 IS NOT NULL THEN
			IF pa_chamada09 <> pn_chamada09 THEN
				vr_chamada09 := '"' || RTRIM(pn_chamada09) || '"';
			ELSE
				vr_chamada09 := '"' || RTRIM(pa_chamada09) || '"';
			END IF;
		END IF;
		IF pn_justificativa10 IS NULL
		AND pa_justificativa10 IS NULL THEN
			vr_justificativa10 := 'null';
		END IF;
		IF pn_justificativa10 IS NULL
		AND pa_justificativa10 IS NOT NULL THEN
			vr_justificativa10 := 'null';
		END IF;
		IF pn_justificativa10 IS NOT NULL
		AND pa_justificativa10 IS NULL THEN
			vr_justificativa10 := '"' || RTRIM(pn_justificativa10) || '"';
		END IF;
		IF pn_justificativa10 IS NOT NULL
		AND pa_justificativa10 IS NOT NULL THEN
			IF pa_justificativa10 <> pn_justificativa10 THEN
				vr_justificativa10 := '"' || RTRIM(pn_justificativa10) || '"';
			ELSE
				vr_justificativa10 := '"' || RTRIM(pa_justificativa10) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_chamada_falta set co_unidade = ' || RTRIM(vr_co_unidade00) || '  , CO_SEQ_CHAMADA_TURMA = ' || RTRIM(vr_co_chamada_tu01) || '  , ano_sem = ' || RTRIM(vr_ano_sem02) || '  , co_seq_chamada = ' || RTRIM(vr_co_seq_chamad03) || '  , co_turma = ' || RTRIM(vr_co_turma04) || '  , co_disciplina = ' || RTRIM(vr_co_disciplina05);
		v_sql2 := '  , co_curso = ' || RTRIM(vr_co_curso06) || '  , co_seq_serie = ' || RTRIM(vr_co_seq_serie07) || '  , co_aluno = ' || RTRIM(vr_co_aluno08) || '  , chamada = ' || RTRIM(vr_chamada09) || '  , justificativa = ' || RTRIM(vr_justificativa10);
		v_sql3 := ' where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and co_chamada_turma = ' || RTRIM(vr_co_chamada_tu01) || '  and co_seq_chamada = ' || RTRIM(vr_co_seq_chamad03) || '  and co_aluno = ' || RTRIM(vr_co_aluno08) || ';';
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
		       's_chamada_falta',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_chamada_062;
/

