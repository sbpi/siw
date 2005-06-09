CREATE OR REPLACE PROCEDURE pr_s_cont_edu070(
	P_OP_IN                CHAR,
	PA_co_unidade00_IN     s_cont_educ_obs_al.co_unidade%TYPE,
	PA_ano_sem01_IN        s_cont_educ_obs_al.ano_sem%TYPE,
	PA_co_turma02_IN       s_cont_educ_obs_al.co_turma%TYPE,
	PA_co_disciplina03_IN  s_cont_educ_obs_al.co_disciplina%TYPE,
	PA_co_aluno04_IN       s_cont_educ_obs_al.co_aluno%TYPE,
	PA_co_curso05_IN       s_cont_educ_obs_al.co_curso%TYPE,
	PA_co_seq_serie06_IN   s_cont_educ_obs_al.co_seq_serie%TYPE,
	PA_tp_conteudo_e07_IN  s_cont_educ_obs_al.tp_conteudo_educ%TYPE,
	PA_ds_cont_edu_o08_IN  s_cont_educ_obs_al.ds_cont_edu_obs_al%TYPE,
	PA_dt_cont_educa09_IN  s_cont_educ_obs_al.dt_cont_educativo%TYPE,
	PN_co_unidade00_IN     s_cont_educ_obs_al.co_unidade%TYPE,
	PN_ano_sem01_IN        s_cont_educ_obs_al.ano_sem%TYPE,
	PN_co_turma02_IN       s_cont_educ_obs_al.co_turma%TYPE,
	PN_co_disciplina03_IN  s_cont_educ_obs_al.co_disciplina%TYPE,
	PN_co_aluno04_IN       s_cont_educ_obs_al.co_aluno%TYPE,
	PN_co_curso05_IN       s_cont_educ_obs_al.co_curso%TYPE,
	PN_co_seq_serie06_IN   s_cont_educ_obs_al.co_seq_serie%TYPE,
	PN_tp_conteudo_e07_IN  s_cont_educ_obs_al.tp_conteudo_educ%TYPE,
	PN_ds_cont_edu_o08_IN  s_cont_educ_obs_al.ds_cont_edu_obs_al%TYPE,
	PN_dt_cont_educa09_IN  s_cont_educ_obs_al.dt_cont_educativo%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_unidade00     s_cont_educ_obs_al.co_unidade%TYPE := PA_co_unidade00_IN;
PA_ano_sem01        s_cont_educ_obs_al.ano_sem%TYPE := PA_ano_sem01_IN;
PA_co_turma02       s_cont_educ_obs_al.co_turma%TYPE := PA_co_turma02_IN;
PA_co_disciplina03  s_cont_educ_obs_al.co_disciplina%TYPE := PA_co_disciplina03_IN;
PA_co_aluno04       s_cont_educ_obs_al.co_aluno%TYPE := PA_co_aluno04_IN;
PA_co_curso05       s_cont_educ_obs_al.co_curso%TYPE := PA_co_curso05_IN;
PA_co_seq_serie06   s_cont_educ_obs_al.co_seq_serie%TYPE := PA_co_seq_serie06_IN;
PA_tp_conteudo_e07  s_cont_educ_obs_al.tp_conteudo_educ%TYPE := PA_tp_conteudo_e07_IN;
PA_ds_cont_edu_o08  s_cont_educ_obs_al.ds_cont_edu_obs_al%TYPE := PA_ds_cont_edu_o08_IN;
PA_dt_cont_educa09  s_cont_educ_obs_al.dt_cont_educativo%TYPE := PA_dt_cont_educa09_IN;
PN_co_unidade00     s_cont_educ_obs_al.co_unidade%TYPE := PN_co_unidade00_IN;
PN_ano_sem01        s_cont_educ_obs_al.ano_sem%TYPE := PN_ano_sem01_IN;
PN_co_turma02       s_cont_educ_obs_al.co_turma%TYPE := PN_co_turma02_IN;
PN_co_disciplina03  s_cont_educ_obs_al.co_disciplina%TYPE := PN_co_disciplina03_IN;
PN_co_aluno04       s_cont_educ_obs_al.co_aluno%TYPE := PN_co_aluno04_IN;
PN_co_curso05       s_cont_educ_obs_al.co_curso%TYPE := PN_co_curso05_IN;
PN_co_seq_serie06   s_cont_educ_obs_al.co_seq_serie%TYPE := PN_co_seq_serie06_IN;
PN_tp_conteudo_e07  s_cont_educ_obs_al.tp_conteudo_educ%TYPE := PN_tp_conteudo_e07_IN;
PN_ds_cont_edu_o08  s_cont_educ_obs_al.ds_cont_edu_obs_al%TYPE := PN_ds_cont_edu_o08_IN;
PN_dt_cont_educa09  s_cont_educ_obs_al.dt_cont_educativo%TYPE := PN_dt_cont_educa09_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(500);
v_sql4              CHAR(500);
v_uni               CHAR(10);
vr_co_unidade00     CHAR(10);
vr_ano_sem01        CHAR(10);
vr_co_turma02       CHAR(10);
vr_co_disciplina03  CHAR(10);
vr_co_aluno04       CHAR(20);
vr_co_curso05       CHAR(10);
vr_co_seq_serie06   CHAR(10);
vr_tp_conteudo_e07  CHAR(10);
vr_ds_cont_edu_o08  CHAR(210);
vr_dt_cont_educa09  CHAR(40);
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
		IF pn_co_curso05 IS NULL THEN
			vr_co_curso05 := 'null';
		ELSE
			vr_co_curso05 := pn_co_curso05;
		END IF;
		IF pn_co_seq_serie06 IS NULL THEN
			vr_co_seq_serie06 := 'null';
		ELSE
			vr_co_seq_serie06 := pn_co_seq_serie06;
		END IF;
		IF pn_tp_conteudo_e07 IS NULL THEN
			vr_tp_conteudo_e07 := 'null';
		ELSE
			vr_tp_conteudo_e07 := pn_tp_conteudo_e07;
		END IF;
		IF pn_ds_cont_edu_o08 IS NULL THEN
			vr_ds_cont_edu_o08 := 'null';
		ELSE
			vr_ds_cont_edu_o08 := pn_ds_cont_edu_o08;
		END IF;
		IF pn_dt_cont_educa09 IS NULL THEN
			vr_dt_cont_educa09 := 'null';
		ELSE
			vr_dt_cont_educa09 := pn_dt_cont_educa09;
		END IF;
		v_sql1 := 'insert into S_CONTEUDO_EDUCATIVO_OBS_ALUNO(co_unidade, ano_sem, co_turma, co_disciplina, co_aluno, co_curso, co_seq_serie, TP_CONTEUDO_EDUCATIVO, DS_CONTEUDO_EDUCATIVO_OBS_ALUN, DT_CONTEUDO_EDUCATIVO) values (';
		v_sql2 := '"' || RTRIM(vr_co_unidade00) || '"' || ',' || '"' || RTRIM(vr_ano_sem01) || '"' || ',' || RTRIM(vr_co_turma02) || ',' || '"' || RTRIM(vr_co_disciplina03) || '"' || ',';
		v_sql3 := '"' || RTRIM(vr_co_aluno04) || '"' || ',' || RTRIM(vr_co_curso05) || ',' || RTRIM(vr_co_seq_serie06) || ',' || RTRIM(vr_tp_conteudo_e07) || ',' || '"' || RTRIM(vr_ds_cont_edu_o08) || '"' || ',' || '"' || vr_dt_cont_educa09 || '"' || ');';
		v_sql4 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4;
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
		IF pa_co_curso05 IS NULL THEN
			vr_co_curso05 := 'null';
		ELSE
			vr_co_curso05 := pa_co_curso05;
		END IF;
		IF pa_co_seq_serie06 IS NULL THEN
			vr_co_seq_serie06 := 'null';
		ELSE
			vr_co_seq_serie06 := pa_co_seq_serie06;
		END IF;
		IF pa_tp_conteudo_e07 IS NULL THEN
			vr_tp_conteudo_e07 := 'null';
		ELSE
			vr_tp_conteudo_e07 := pa_tp_conteudo_e07;
		END IF;
		v_sql1 := '  delete from S_CONTEUDO_EDUCATIVO_OBS_ALUNO where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_turma = ' || RTRIM(vr_co_turma02) || '  and co_disciplina = ' || RTRIM(vr_co_disciplina03);
		v_sql2 := '  and co_aluno = ' || RTRIM(vr_co_aluno04) || '  and co_curso = ' || RTRIM(vr_co_curso05) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie06) || '  and TP_CONTEUDO_EDUCATIVO = ' || RTRIM(vr_tp_conteudo_e07) || ';';
		v_sql3 := '';
		v_sql4 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4;
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
		IF pn_tp_conteudo_e07 IS NULL
		AND pa_tp_conteudo_e07 IS NULL THEN
			vr_tp_conteudo_e07 := 'null';
		END IF;
		IF pn_tp_conteudo_e07 IS NULL
		AND pa_tp_conteudo_e07 IS NOT NULL THEN
			vr_tp_conteudo_e07 := 'null';
		END IF;
		IF pn_tp_conteudo_e07 IS NOT NULL
		AND pa_tp_conteudo_e07 IS NULL THEN
			vr_tp_conteudo_e07 := pn_tp_conteudo_e07;
		END IF;
		IF pn_tp_conteudo_e07 IS NOT NULL
		AND pa_tp_conteudo_e07 IS NOT NULL THEN
			IF pa_tp_conteudo_e07 <> pn_tp_conteudo_e07 THEN
				vr_tp_conteudo_e07 := pn_tp_conteudo_e07;
			ELSE
				vr_tp_conteudo_e07 := pa_tp_conteudo_e07;
			END IF;
		END IF;
		IF pn_ds_cont_edu_o08 IS NULL
		AND pa_ds_cont_edu_o08 IS NULL THEN
			vr_ds_cont_edu_o08 := 'null';
		END IF;
		IF pn_ds_cont_edu_o08 IS NULL
		AND pa_ds_cont_edu_o08 IS NOT NULL THEN
			vr_ds_cont_edu_o08 := 'null';
		END IF;
		IF pn_ds_cont_edu_o08 IS NOT NULL
		AND pa_ds_cont_edu_o08 IS NULL THEN
			vr_ds_cont_edu_o08 := '"' || RTRIM(pn_ds_cont_edu_o08) || '"';
		END IF;
		IF pn_ds_cont_edu_o08 IS NOT NULL
		AND pa_ds_cont_edu_o08 IS NOT NULL THEN
			IF pa_ds_cont_edu_o08 <> pn_ds_cont_edu_o08 THEN
				vr_ds_cont_edu_o08 := '"' || RTRIM(pn_ds_cont_edu_o08) || '"';
			ELSE
				vr_ds_cont_edu_o08 := '"' || RTRIM(pa_ds_cont_edu_o08) || '"';
			END IF;
		END IF;
		IF pn_dt_cont_educa09 IS NULL
		AND pa_dt_cont_educa09 IS NULL THEN
			vr_dt_cont_educa09 := 'null';
		END IF;
		IF pn_dt_cont_educa09 IS NULL
		AND pa_dt_cont_educa09 IS NOT NULL THEN
			vr_dt_cont_educa09 := 'null';
		END IF;
		IF pn_dt_cont_educa09 IS NOT NULL
		AND pa_dt_cont_educa09 IS NULL THEN
			vr_dt_cont_educa09 := '"' || pn_dt_cont_educa09 || '"';
		END IF;
		IF pn_dt_cont_educa09 IS NOT NULL
		AND pa_dt_cont_educa09 IS NOT NULL THEN
			IF pa_dt_cont_educa09 <> pn_dt_cont_educa09 THEN
				vr_dt_cont_educa09 := '"' || pn_dt_cont_educa09 || '"';
			ELSE
				vr_dt_cont_educa09 := '"' || pa_dt_cont_educa09 || '"';
			END IF;
		END IF;
		v_sql1 := 'update S_CONTEUDO_EDUCATIVO_OBS_ALUNO set co_unidade = ' || RTRIM(vr_co_unidade00) || '  , ano_sem = ' || RTRIM(vr_ano_sem01) || '  , co_turma = ' || RTRIM(vr_co_turma02) || '  , co_disciplina = ' || RTRIM(vr_co_disciplina03) || '  , co_aluno = ' || RTRIM(vr_co_aluno04) || '  , co_curso = ' || RTRIM(vr_co_curso05);
		v_sql2 := '  , co_seq_serie = ' || RTRIM(vr_co_seq_serie06) || '  , TP_CONTEUDO_EDUCATIVO = ' || RTRIM(vr_tp_conteudo_e07) || '  , DS_CONTEUDO_EDUCATIVO_OBS_ALUN = ' || RTRIM(vr_ds_cont_edu_o08) || '  , DT_CONTEUDO_EDUCATIVO = ' || RTRIM(vr_dt_cont_educa09);
		v_sql3 := ' where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_turma = ' || RTRIM(vr_co_turma02) || '  and co_disciplina = ' || RTRIM(vr_co_disciplina03) || '  and co_aluno = ' || RTRIM(vr_co_aluno04);
		v_sql4 := '  and co_curso = ' || RTRIM(vr_co_curso05) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie06) || '  and tp_conteudo_educ = ' || RTRIM(vr_tp_conteudo_e07) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4;
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
		       's_cont_educ_obs_al',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_cont_edu070;
/

