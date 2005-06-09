CREATE OR REPLACE PROCEDURE pr_s_avaliaca048(
	P_OP_IN                CHAR,
	PA_av_sequencial00_IN  s_avaliacao.av_sequencial%TYPE,
	PA_co_unidade01_IN     s_avaliacao.co_unidade%TYPE,
	PA_ano_sem02_IN        s_avaliacao.ano_sem%TYPE,
	PA_co_curso03_IN       s_avaliacao.co_curso%TYPE,
	PA_co_turma04_IN       s_avaliacao.co_turma%TYPE,
	PA_co_seq_serie05_IN   s_avaliacao.co_seq_serie%TYPE,
	PA_co_disciplina06_IN  s_avaliacao.co_disciplina%TYPE,
	PA_co_curs_serie07_IN  s_avaliacao.co_curs_serie_disc%TYPE,
	PA_co_funcionari08_IN  s_avaliacao.co_funcionario%TYPE,
	PN_av_sequencial00_IN  s_avaliacao.av_sequencial%TYPE,
	PN_co_unidade01_IN     s_avaliacao.co_unidade%TYPE,
	PN_ano_sem02_IN        s_avaliacao.ano_sem%TYPE,
	PN_co_curso03_IN       s_avaliacao.co_curso%TYPE,
	PN_co_turma04_IN       s_avaliacao.co_turma%TYPE,
	PN_co_seq_serie05_IN   s_avaliacao.co_seq_serie%TYPE,
	PN_co_disciplina06_IN  s_avaliacao.co_disciplina%TYPE,
	PN_co_curs_serie07_IN  s_avaliacao.co_curs_serie_disc%TYPE,
	PN_co_funcionari08_IN  s_avaliacao.co_funcionario%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_av_sequencial00  s_avaliacao.av_sequencial%TYPE := PA_av_sequencial00_IN;
PA_co_unidade01     s_avaliacao.co_unidade%TYPE := PA_co_unidade01_IN;
PA_ano_sem02        s_avaliacao.ano_sem%TYPE := PA_ano_sem02_IN;
PA_co_curso03       s_avaliacao.co_curso%TYPE := PA_co_curso03_IN;
PA_co_turma04       s_avaliacao.co_turma%TYPE := PA_co_turma04_IN;
PA_co_seq_serie05   s_avaliacao.co_seq_serie%TYPE := PA_co_seq_serie05_IN;
PA_co_disciplina06  s_avaliacao.co_disciplina%TYPE := PA_co_disciplina06_IN;
PA_co_curs_serie07  s_avaliacao.co_curs_serie_disc%TYPE := PA_co_curs_serie07_IN;
PA_co_funcionari08  s_avaliacao.co_funcionario%TYPE := PA_co_funcionari08_IN;
PN_av_sequencial00  s_avaliacao.av_sequencial%TYPE := PN_av_sequencial00_IN;
PN_co_unidade01     s_avaliacao.co_unidade%TYPE := PN_co_unidade01_IN;
PN_ano_sem02        s_avaliacao.ano_sem%TYPE := PN_ano_sem02_IN;
PN_co_curso03       s_avaliacao.co_curso%TYPE := PN_co_curso03_IN;
PN_co_turma04       s_avaliacao.co_turma%TYPE := PN_co_turma04_IN;
PN_co_seq_serie05   s_avaliacao.co_seq_serie%TYPE := PN_co_seq_serie05_IN;
PN_co_disciplina06  s_avaliacao.co_disciplina%TYPE := PN_co_disciplina06_IN;
PN_co_curs_serie07  s_avaliacao.co_curs_serie_disc%TYPE := PN_co_curs_serie07_IN;
PN_co_funcionari08  s_avaliacao.co_funcionario%TYPE := PN_co_funcionari08_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_av_sequencial00  CHAR(10);
vr_co_unidade01     CHAR(10);
vr_ano_sem02        CHAR(10);
vr_co_curso03       CHAR(10);
vr_co_turma04       CHAR(10);
vr_co_seq_serie05   CHAR(10);
vr_co_disciplina06  CHAR(10);
vr_co_curs_serie07  CHAR(10);
vr_co_funcionari08  CHAR(20);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_av_sequencial00 IS NULL THEN
			vr_av_sequencial00 := 'null';
		ELSE
			vr_av_sequencial00 := pn_av_sequencial00;
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
		IF pn_co_curso03 IS NULL THEN
			vr_co_curso03 := 'null';
		ELSE
			vr_co_curso03 := pn_co_curso03;
		END IF;
		IF pn_co_turma04 IS NULL THEN
			vr_co_turma04 := 'null';
		ELSE
			vr_co_turma04 := pn_co_turma04;
		END IF;
		IF pn_co_seq_serie05 IS NULL THEN
			vr_co_seq_serie05 := 'null';
		ELSE
			vr_co_seq_serie05 := pn_co_seq_serie05;
		END IF;
		IF pn_co_disciplina06 IS NULL THEN
			vr_co_disciplina06 := 'null';
		ELSE
			vr_co_disciplina06 := pn_co_disciplina06;
		END IF;
		IF pn_co_curs_serie07 IS NULL THEN
			vr_co_curs_serie07 := 'null';
		ELSE
			vr_co_curs_serie07 := pn_co_curs_serie07;
		END IF;
		IF pn_co_funcionari08 IS NULL THEN
			vr_co_funcionari08 := 'null';
		ELSE
			vr_co_funcionari08 := pn_co_funcionari08;
		END IF;
		v_sql1 := 'insert into s_avaliacao(av_sequencial, co_unidade, ano_sem, co_curso, co_turma, co_seq_serie, co_disciplina, CO_CURSO_SERIE_DISCIPLINA, co_funcionario) values (';
		v_sql2 := RTRIM(vr_av_sequencial00) || ',' || '"' || RTRIM(vr_co_unidade01) || '"' || ',' || '"' || RTRIM(vr_ano_sem02) || '"' || ',' || RTRIM(vr_co_curso03) || ',' || RTRIM(vr_co_turma04) || ',' || RTRIM(vr_co_seq_serie05) || ',';
		v_sql3 := '"' || RTRIM(vr_co_disciplina06) || '"' || ',' || RTRIM(vr_co_curs_serie07) || ',' || '"' || RTRIM(vr_co_funcionari08) || '"' || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_av_sequencial00 IS NULL THEN
			vr_av_sequencial00 := 'null';
		ELSE
			vr_av_sequencial00 := pa_av_sequencial00;
		END IF;
		IF pa_co_unidade01 IS NULL THEN
			vr_co_unidade01 := 'null';
		ELSE
			vr_co_unidade01 := '"' || RTRIM(pa_co_unidade01) || '"';
		END IF;
		v_sql1 := '  delete from s_avaliacao where av_sequencial = ' || RTRIM(vr_av_sequencial00) || '  and co_unidade = ' || RTRIM(vr_co_unidade01) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_av_sequencial00 IS NULL
		AND pa_av_sequencial00 IS NULL THEN
			vr_av_sequencial00 := 'null';
		END IF;
		IF pn_av_sequencial00 IS NULL
		AND pa_av_sequencial00 IS NOT NULL THEN
			vr_av_sequencial00 := 'null';
		END IF;
		IF pn_av_sequencial00 IS NOT NULL
		AND pa_av_sequencial00 IS NULL THEN
			vr_av_sequencial00 := pn_av_sequencial00;
		END IF;
		IF pn_av_sequencial00 IS NOT NULL
		AND pa_av_sequencial00 IS NOT NULL THEN
			IF pa_av_sequencial00 <> pn_av_sequencial00 THEN
				vr_av_sequencial00 := pn_av_sequencial00;
			ELSE
				vr_av_sequencial00 := pa_av_sequencial00;
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
		IF pn_co_seq_serie05 IS NULL
		AND pa_co_seq_serie05 IS NULL THEN
			vr_co_seq_serie05 := 'null';
		END IF;
		IF pn_co_seq_serie05 IS NULL
		AND pa_co_seq_serie05 IS NOT NULL THEN
			vr_co_seq_serie05 := 'null';
		END IF;
		IF pn_co_seq_serie05 IS NOT NULL
		AND pa_co_seq_serie05 IS NULL THEN
			vr_co_seq_serie05 := pn_co_seq_serie05;
		END IF;
		IF pn_co_seq_serie05 IS NOT NULL
		AND pa_co_seq_serie05 IS NOT NULL THEN
			IF pa_co_seq_serie05 <> pn_co_seq_serie05 THEN
				vr_co_seq_serie05 := pn_co_seq_serie05;
			ELSE
				vr_co_seq_serie05 := pa_co_seq_serie05;
			END IF;
		END IF;
		IF pn_co_disciplina06 IS NULL
		AND pa_co_disciplina06 IS NULL THEN
			vr_co_disciplina06 := 'null';
		END IF;
		IF pn_co_disciplina06 IS NULL
		AND pa_co_disciplina06 IS NOT NULL THEN
			vr_co_disciplina06 := 'null';
		END IF;
		IF pn_co_disciplina06 IS NOT NULL
		AND pa_co_disciplina06 IS NULL THEN
			vr_co_disciplina06 := '"' || RTRIM(pn_co_disciplina06) || '"';
		END IF;
		IF pn_co_disciplina06 IS NOT NULL
		AND pa_co_disciplina06 IS NOT NULL THEN
			IF pa_co_disciplina06 <> pn_co_disciplina06 THEN
				vr_co_disciplina06 := '"' || RTRIM(pn_co_disciplina06) || '"';
			ELSE
				vr_co_disciplina06 := '"' || RTRIM(pa_co_disciplina06) || '"';
			END IF;
		END IF;
		IF pn_co_curs_serie07 IS NULL
		AND pa_co_curs_serie07 IS NULL THEN
			vr_co_curs_serie07 := 'null';
		END IF;
		IF pn_co_curs_serie07 IS NULL
		AND pa_co_curs_serie07 IS NOT NULL THEN
			vr_co_curs_serie07 := 'null';
		END IF;
		IF pn_co_curs_serie07 IS NOT NULL
		AND pa_co_curs_serie07 IS NULL THEN
			vr_co_curs_serie07 := pn_co_curs_serie07;
		END IF;
		IF pn_co_curs_serie07 IS NOT NULL
		AND pa_co_curs_serie07 IS NOT NULL THEN
			IF pa_co_curs_serie07 <> pn_co_curs_serie07 THEN
				vr_co_curs_serie07 := pn_co_curs_serie07;
			ELSE
				vr_co_curs_serie07 := pa_co_curs_serie07;
			END IF;
		END IF;
		IF pn_co_funcionari08 IS NULL
		AND pa_co_funcionari08 IS NULL THEN
			vr_co_funcionari08 := 'null';
		END IF;
		IF pn_co_funcionari08 IS NULL
		AND pa_co_funcionari08 IS NOT NULL THEN
			vr_co_funcionari08 := 'null';
		END IF;
		IF pn_co_funcionari08 IS NOT NULL
		AND pa_co_funcionari08 IS NULL THEN
			vr_co_funcionari08 := '"' || RTRIM(pn_co_funcionari08) || '"';
		END IF;
		IF pn_co_funcionari08 IS NOT NULL
		AND pa_co_funcionari08 IS NOT NULL THEN
			IF pa_co_funcionari08 <> pn_co_funcionari08 THEN
				vr_co_funcionari08 := '"' || RTRIM(pn_co_funcionari08) || '"';
			ELSE
				vr_co_funcionari08 := '"' || RTRIM(pa_co_funcionari08) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_avaliacao set av_sequencial = ' || RTRIM(vr_av_sequencial00) || '  , co_unidade = ' || RTRIM(vr_co_unidade01) || '  , ano_sem = ' || RTRIM(vr_ano_sem02) || '  , co_curso = ' || RTRIM(vr_co_curso03) || '  , co_turma = ' || RTRIM(vr_co_turma04);
		v_sql2 := '  , co_seq_serie = ' || RTRIM(vr_co_seq_serie05) || '  , co_disciplina = ' || RTRIM(vr_co_disciplina06) || '  , CO_CURSO_SERIE_DISCIPLINA = ' || RTRIM(vr_co_curs_serie07) || '  , co_funcionario = ' || RTRIM(vr_co_funcionari08);
		v_sql3 := ' where av_sequencial = ' || RTRIM(vr_av_sequencial00) || '  and co_unidade = ' || RTRIM(vr_co_unidade01) || ';';
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
		       's_avaliacao',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_avaliaca048;
/

