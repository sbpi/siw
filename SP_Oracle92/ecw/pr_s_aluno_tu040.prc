CREATE OR REPLACE PROCEDURE pr_s_aluno_tu040(
	P_OP_IN                CHAR,
	PA_co_unidade00_IN     s_aluno_turma_disc.co_unidade%TYPE,
	PA_ano_sem01_IN        s_aluno_turma_disc.ano_sem%TYPE,
	PA_co_turma02_IN       s_aluno_turma_disc.co_turma%TYPE,
	PA_co_disciplina03_IN  s_aluno_turma_disc.co_disciplina%TYPE,
	PA_co_aluno04_IN       s_aluno_turma_disc.co_aluno%TYPE,
	PA_nu_chamada05_IN     s_aluno_turma.nu_chamada%TYPE,
	PA_co_curso06_IN       s_aluno_turma_disc.co_curso%TYPE,
	PA_co_seq_serie07_IN   s_aluno_turma_disc.co_seq_serie%TYPE,
	PN_co_unidade00_IN     s_aluno_turma_disc.co_unidade%TYPE,
	PN_ano_sem01_IN        s_aluno_turma_disc.ano_sem%TYPE,
	PN_co_turma02_IN       s_aluno_turma_disc.co_turma%TYPE,
	PN_co_disciplina03_IN  s_aluno_turma_disc.co_disciplina%TYPE,
	PN_co_aluno04_IN       s_aluno_turma_disc.co_aluno%TYPE,
	PN_nu_chamada05_IN     s_aluno_turma.nu_chamada%TYPE,
	PN_co_curso06_IN       s_aluno_turma_disc.co_curso%TYPE,
	PN_co_seq_serie07_IN   s_aluno_turma_disc.co_seq_serie%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_unidade00     s_aluno_turma_disc.co_unidade%TYPE := PA_co_unidade00_IN;
PA_ano_sem01        s_aluno_turma_disc.ano_sem%TYPE := PA_ano_sem01_IN;
PA_co_turma02       s_aluno_turma_disc.co_turma%TYPE := PA_co_turma02_IN;
PA_co_disciplina03  s_aluno_turma_disc.co_disciplina%TYPE := PA_co_disciplina03_IN;
PA_co_aluno04       s_aluno_turma_disc.co_aluno%TYPE := PA_co_aluno04_IN;
PA_nu_chamada05     s_aluno_turma.nu_chamada%TYPE := PA_nu_chamada05_IN;
PA_co_curso06       s_aluno_turma_disc.co_curso%TYPE := PA_co_curso06_IN;
PA_co_seq_serie07   s_aluno_turma_disc.co_seq_serie%TYPE := PA_co_seq_serie07_IN;
PN_co_unidade00     s_aluno_turma_disc.co_unidade%TYPE := PN_co_unidade00_IN;
PN_ano_sem01        s_aluno_turma_disc.ano_sem%TYPE := PN_ano_sem01_IN;
PN_co_turma02       s_aluno_turma_disc.co_turma%TYPE := PN_co_turma02_IN;
PN_co_disciplina03  s_aluno_turma_disc.co_disciplina%TYPE := PN_co_disciplina03_IN;
PN_co_aluno04       s_aluno_turma_disc.co_aluno%TYPE := PN_co_aluno04_IN;
PN_nu_chamada05     s_aluno_turma.nu_chamada%TYPE := PN_nu_chamada05_IN;
PN_co_curso06       s_aluno_turma_disc.co_curso%TYPE := PN_co_curso06_IN;
PN_co_seq_serie07   s_aluno_turma_disc.co_seq_serie%TYPE := PN_co_seq_serie07_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_unidade00     CHAR(10);
vr_ano_sem01        CHAR(10);
vr_co_turma02       CHAR(10);
vr_co_disciplina03  CHAR(10);
vr_co_aluno04       CHAR(20);
vr_nu_chamada05     CHAR(10);
vr_co_curso06       CHAR(10);
vr_co_seq_serie07   CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := pn_co_unidade00;
		END IF;
		IF pn_ano_sem01 IS NULL THEN
			vr_ano_sem01 := 'null';
		ELSE
			vr_ano_sem01 := pn_ano_sem01;
		END IF;
		IF pn_co_turma02 IS NULL THEN
			vr_co_turma02 := 'null';
		ELSE
			vr_co_turma02 := pn_co_turma02;
		END IF;
		IF pn_co_disciplina03 IS NULL THEN
			vr_co_disciplina03 := 'null';
		ELSE
			vr_co_disciplina03 := pn_co_disciplina03;
		END IF;
		IF pn_co_aluno04 IS NULL THEN
			vr_co_aluno04 := 'null';
		ELSE
			vr_co_aluno04 := pn_co_aluno04;
		END IF;
		IF pn_nu_chamada05 IS NULL THEN
			vr_nu_chamada05 := 'null';
		ELSE
			vr_nu_chamada05 := pn_nu_chamada05;
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
		v_sql1 := 'insert into S_ALUNO_TURMA_DISCIPLINA(co_unidade, ano_sem, co_turma, co_disciplina, co_aluno, nu_chamada, co_curso, co_seq_serie) values (';
		v_sql2 := '"' || RTRIM(vr_co_unidade00) || '"' || ',' || '"' || RTRIM(vr_ano_sem01) || '"' || ',' || RTRIM(vr_co_turma02) || ',' || '"' || RTRIM(vr_co_disciplina03) || '"' || ',';
		v_sql3 := '"' || RTRIM(vr_co_aluno04) || '"' || ',' || '"' || RTRIM(vr_nu_chamada05) || '"' || ',' || RTRIM(vr_co_curso06) || ',' || RTRIM(vr_co_seq_serie07) || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := '"' || RTRIM(pa_co_unidade00) || '"';
		END IF;
		IF pa_ano_sem01 IS NULL THEN
			vr_ano_sem01 := 'null';
		ELSE
			vr_ano_sem01 := '"' || RTRIM(pa_ano_sem01) || '"';
		END IF;
		IF pa_co_turma02 IS NULL THEN
			vr_co_turma02 := 'null';
		ELSE
			vr_co_turma02 := pa_co_turma02;
		END IF;
		IF pa_co_disciplina03 IS NULL THEN
			vr_co_disciplina03 := 'null';
		ELSE
			vr_co_disciplina03 := '"' || RTRIM(pa_co_disciplina03) || '"';
		END IF;
		IF pa_co_aluno04 IS NULL THEN
			vr_co_aluno04 := 'null';
		ELSE
			vr_co_aluno04 := '"' || RTRIM(pa_co_aluno04) || '"';
		END IF;
		IF pa_co_curso06 IS NULL THEN
			vr_co_curso06 := 'null';
		ELSE
			vr_co_curso06 := pa_co_curso06;
		END IF;
		IF pa_co_seq_serie07 IS NULL THEN
			vr_co_seq_serie07 := 'null';
		ELSE
			vr_co_seq_serie07 := pa_co_seq_serie07;
		END IF;
		v_sql1 := '  delete from S_ALUNO_TURMA_DISCIPLINA where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_turma = ' || RTRIM(vr_co_turma02);
		v_sql2 := '  and co_disciplina = ' || RTRIM(vr_co_disciplina03) || '  and co_aluno = ' || RTRIM(vr_co_aluno04) || '  and co_curso = ' || RTRIM(vr_co_curso06) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie07) || ';';
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
		IF pn_co_turma02 IS NULL
		AND pa_co_turma02 IS NULL THEN
			vr_co_turma02 := 'null';
		END IF;
		IF pn_co_turma02 IS NULL
		AND pa_co_turma02 IS NOT NULL THEN
			vr_co_turma02 := 'null';
		END IF;
		IF pn_co_turma02 IS NOT NULL
		AND pa_co_turma02 IS NULL THEN
			vr_co_turma02 := pn_co_turma02;
		END IF;
		IF pn_co_turma02 IS NOT NULL
		AND pa_co_turma02 IS NOT NULL THEN
			IF pa_co_turma02 <> pn_co_turma02 THEN
				vr_co_turma02 := pn_co_turma02;
			ELSE
				vr_co_turma02 := pa_co_turma02;
			END IF;
		END IF;
		IF pn_co_disciplina03 IS NULL
		AND pa_co_disciplina03 IS NULL THEN
			vr_co_disciplina03 := 'null';
		END IF;
		IF pn_co_disciplina03 IS NULL
		AND pa_co_disciplina03 IS NOT NULL THEN
			vr_co_disciplina03 := 'null';
		END IF;
		IF pn_co_disciplina03 IS NOT NULL
		AND pa_co_disciplina03 IS NULL THEN
			vr_co_disciplina03 := '"' || RTRIM(pn_co_disciplina03) || '"';
		END IF;
		IF pn_co_disciplina03 IS NOT NULL
		AND pa_co_disciplina03 IS NOT NULL THEN
			IF pa_co_disciplina03 <> pn_co_disciplina03 THEN
				vr_co_disciplina03 := '"' || RTRIM(pn_co_disciplina03) || '"';
			ELSE
				vr_co_disciplina03 := '"' || RTRIM(pa_co_disciplina03) || '"';
			END IF;
		END IF;
		IF pn_co_aluno04 IS NULL
		AND pa_co_aluno04 IS NULL THEN
			vr_co_aluno04 := 'null';
		END IF;
		IF pn_co_aluno04 IS NULL
		AND pa_co_aluno04 IS NOT NULL THEN
			vr_co_aluno04 := 'null';
		END IF;
		IF pn_co_aluno04 IS NOT NULL
		AND pa_co_aluno04 IS NULL THEN
			vr_co_aluno04 := '"' || RTRIM(pn_co_aluno04) || '"';
		END IF;
		IF pn_co_aluno04 IS NOT NULL
		AND pa_co_aluno04 IS NOT NULL THEN
			IF pa_co_aluno04 <> pn_co_aluno04 THEN
				vr_co_aluno04 := '"' || RTRIM(pn_co_aluno04) || '"';
			ELSE
				vr_co_aluno04 := '"' || RTRIM(pa_co_aluno04) || '"';
			END IF;
		END IF;
		IF pn_nu_chamada05 IS NULL
		AND pa_nu_chamada05 IS NULL THEN
			vr_nu_chamada05 := 'null';
		END IF;
		IF pn_nu_chamada05 IS NULL
		AND pa_nu_chamada05 IS NOT NULL THEN
			vr_nu_chamada05 := 'null';
		END IF;
		IF pn_nu_chamada05 IS NOT NULL
		AND pa_nu_chamada05 IS NULL THEN
			vr_nu_chamada05 := '"' || RTRIM(pn_nu_chamada05) || '"';
		END IF;
		IF pn_nu_chamada05 IS NOT NULL
		AND pa_nu_chamada05 IS NOT NULL THEN
			IF pa_nu_chamada05 <> pn_nu_chamada05 THEN
				vr_nu_chamada05 := '"' || RTRIM(pn_nu_chamada05) || '"';
			ELSE
				vr_nu_chamada05 := '"' || RTRIM(pa_nu_chamada05) || '"';
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
		v_sql1 := 'update S_ALUNO_TURMA_DISCIPLINA set co_unidade = ' || RTRIM(vr_co_unidade00) || '  , ano_sem = ' || RTRIM(vr_ano_sem01) || '  , co_turma = ' || RTRIM(vr_co_turma02) || '  , co_disciplina = ' || RTRIM(vr_co_disciplina03);
		v_sql2 := '  , co_aluno = ' || RTRIM(vr_co_aluno04) || '  , nu_chamada = ' || RTRIM(vr_nu_chamada05) || '  , co_curso = ' || RTRIM(vr_co_curso06) || '  , co_seq_serie = ' || RTRIM(vr_co_seq_serie07);
		v_sql3 := ' where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_turma = ' || RTRIM(vr_co_turma02) || '  and co_disciplina = ' || RTRIM(vr_co_disciplina03) || '  and co_aluno = ' || RTRIM(vr_co_aluno04) || '  and co_curso = ' || RTRIM(vr_co_curso06) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie07) || ';';
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
		       's_aluno_turma_disc',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_aluno_tu040;
/

