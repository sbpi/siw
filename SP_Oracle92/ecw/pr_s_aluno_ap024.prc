CREATE OR REPLACE PROCEDURE pr_s_aluno_ap024(
	P_OP_IN                CHAR,
	PA_co_unidade00_IN     s_aluno_aproveit.co_unidade%TYPE,
	PA_ano_sem01_IN        s_aluno_aproveit.ano_sem%TYPE,
	PA_co_disciplina02_IN  s_aluno_aproveit.co_disciplina%TYPE,
	PA_sg_serie03_IN       s_aluno_aproveit.sg_serie%TYPE,
	PA_nu_nota04_IN        s_aluno_aproveit.nu_nota%TYPE,
	PA_nu_aulas_dada05_IN  s_aluno_aproveit.nu_aulas_dadas%TYPE,
	PA_co_aluno06_IN       s_aluno_aproveit.co_aluno%TYPE,
	PA_nu_faltas07_IN      s_aluno_aproveit.nu_faltas%TYPE,
	PA_id_exame08_IN       s_aluno_aproveit.id_exame%TYPE,
	PA_dt_conclusao09_IN   s_aluno_aproveit.dt_conclusao%TYPE,
	PA_ds_estrategia10_IN  s_aluno_aproveit.ds_estrategia%TYPE,
	PN_co_unidade00_IN     s_aluno_aproveit.co_unidade%TYPE,
	PN_ano_sem01_IN        s_aluno_aproveit.ano_sem%TYPE,
	PN_co_disciplina02_IN  s_aluno_aproveit.co_disciplina%TYPE,
	PN_sg_serie03_IN       s_aluno_aproveit.sg_serie%TYPE,
	PN_nu_nota04_IN        s_aluno_aproveit.nu_nota%TYPE,
	PN_nu_aulas_dada05_IN  s_aluno_aproveit.nu_aulas_dadas%TYPE,
	PN_co_aluno06_IN       s_aluno_aproveit.co_aluno%TYPE,
	PN_nu_faltas07_IN      s_aluno_aproveit.nu_faltas%TYPE,
	PN_id_exame08_IN       s_aluno_aproveit.id_exame%TYPE,
	PN_dt_conclusao09_IN   s_aluno_aproveit.dt_conclusao%TYPE,
	PN_ds_estrategia10_IN  s_aluno_aproveit.ds_estrategia%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_unidade00     s_aluno_aproveit.co_unidade%TYPE := PA_co_unidade00_IN;
PA_ano_sem01        s_aluno_aproveit.ano_sem%TYPE := PA_ano_sem01_IN;
PA_co_disciplina02  s_aluno_aproveit.co_disciplina%TYPE := PA_co_disciplina02_IN;
PA_sg_serie03       s_aluno_aproveit.sg_serie%TYPE := PA_sg_serie03_IN;
PA_nu_nota04        s_aluno_aproveit.nu_nota%TYPE := PA_nu_nota04_IN;
PA_nu_aulas_dada05  s_aluno_aproveit.nu_aulas_dadas%TYPE := PA_nu_aulas_dada05_IN;
PA_co_aluno06       s_aluno_aproveit.co_aluno%TYPE := PA_co_aluno06_IN;
PA_nu_faltas07      s_aluno_aproveit.nu_faltas%TYPE := PA_nu_faltas07_IN;
PA_id_exame08       s_aluno_aproveit.id_exame%TYPE := PA_id_exame08_IN;
PA_dt_conclusao09   s_aluno_aproveit.dt_conclusao%TYPE := PA_dt_conclusao09_IN;
PA_ds_estrategia10  s_aluno_aproveit.ds_estrategia%TYPE := PA_ds_estrategia10_IN;
PN_co_unidade00     s_aluno_aproveit.co_unidade%TYPE := PN_co_unidade00_IN;
PN_ano_sem01        s_aluno_aproveit.ano_sem%TYPE := PN_ano_sem01_IN;
PN_co_disciplina02  s_aluno_aproveit.co_disciplina%TYPE := PN_co_disciplina02_IN;
PN_sg_serie03       s_aluno_aproveit.sg_serie%TYPE := PN_sg_serie03_IN;
PN_nu_nota04        s_aluno_aproveit.nu_nota%TYPE := PN_nu_nota04_IN;
PN_nu_aulas_dada05  s_aluno_aproveit.nu_aulas_dadas%TYPE := PN_nu_aulas_dada05_IN;
PN_co_aluno06       s_aluno_aproveit.co_aluno%TYPE := PN_co_aluno06_IN;
PN_nu_faltas07      s_aluno_aproveit.nu_faltas%TYPE := PN_nu_faltas07_IN;
PN_id_exame08       s_aluno_aproveit.id_exame%TYPE := PN_id_exame08_IN;
PN_dt_conclusao09   s_aluno_aproveit.dt_conclusao%TYPE := PN_dt_conclusao09_IN;
PN_ds_estrategia10  s_aluno_aproveit.ds_estrategia%TYPE := PN_ds_estrategia10_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_unidade00     CHAR(10);
vr_ano_sem01        CHAR(10);
vr_co_disciplina02  CHAR(10);
vr_sg_serie03       CHAR(10);
vr_nu_nota04        CHAR(10);
vr_nu_aulas_dada05  CHAR(10);
vr_co_aluno06       CHAR(20);
vr_nu_faltas07      CHAR(10);
vr_id_exame08       CHAR(10);
vr_dt_conclusao09   CHAR(40);
vr_ds_estrategia10  CHAR(50);
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
		IF pn_co_disciplina02 IS NULL THEN
			vr_co_disciplina02 := 'null';
		ELSE
			vr_co_disciplina02 := pn_co_disciplina02;
		END IF;
		IF pn_sg_serie03 IS NULL THEN
			vr_sg_serie03 := 'null';
		ELSE
			vr_sg_serie03 := pn_sg_serie03;
		END IF;
		IF pn_nu_nota04 IS NULL THEN
			vr_nu_nota04 := 'null';
		ELSE
			vr_nu_nota04 := pn_nu_nota04;
		END IF;
		IF pn_nu_aulas_dada05 IS NULL THEN
			vr_nu_aulas_dada05 := 'null';
		ELSE
			vr_nu_aulas_dada05 := pn_nu_aulas_dada05;
		END IF;
		IF pn_co_aluno06 IS NULL THEN
			vr_co_aluno06 := 'null';
		ELSE
			vr_co_aluno06 := pn_co_aluno06;
		END IF;
		IF pn_nu_faltas07 IS NULL THEN
			vr_nu_faltas07 := 'null';
		ELSE
			vr_nu_faltas07 := pn_nu_faltas07;
		END IF;
		IF pn_id_exame08 IS NULL THEN
			vr_id_exame08 := 'null';
		ELSE
			vr_id_exame08 := pn_id_exame08;
		END IF;
		IF pn_dt_conclusao09 IS NULL THEN
			vr_dt_conclusao09 := 'null';
		ELSE
			vr_dt_conclusao09 := pn_dt_conclusao09;
		END IF;
		IF pn_ds_estrategia10 IS NULL THEN
			vr_ds_estrategia10 := 'null';
		ELSE
			vr_ds_estrategia10 := pn_ds_estrategia10;
		END IF;
		v_sql1 := 'insert into S_ALUNO_APROVEITAMENTO(co_unidade, ano_sem, co_disciplina, sg_serie, nu_nota, nu_aulas_dadas, co_aluno, nu_faltas, id_exame, dt_conclusao, ds_estrategia) values (';
		v_sql2 := '"' || RTRIM(vr_co_unidade00) || '"' || ',' || '"' || RTRIM(vr_ano_sem01) || '"' || ',' || '"' || RTRIM(vr_co_disciplina02) || '"' || ',' || '"' || RTRIM(vr_sg_serie03) || '"' || ',' || '"' || RTRIM(vr_nu_nota04) || '"' || ',';
		v_sql3 := '"' || RTRIM(vr_nu_aulas_dada05) || '"' || ',' || '"' || RTRIM(vr_co_aluno06) || '"' || ',' || RTRIM(vr_nu_faltas07) || ',' || '"' || RTRIM(vr_id_exame08) || '"' || ',' || '"' || vr_dt_conclusao09 || '"' || ',' || '"' || RTRIM(vr_ds_estrategia10) || '"' || ');';
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
		IF pa_co_disciplina02 IS NULL THEN
			vr_co_disciplina02 := 'null';
		ELSE
			vr_co_disciplina02 := '"' || RTRIM(pa_co_disciplina02) || '"';
		END IF;
		IF pa_sg_serie03 IS NULL THEN
			vr_sg_serie03 := 'null';
		ELSE
			vr_sg_serie03 := '"' || RTRIM(pa_sg_serie03) || '"';
		END IF;
		IF pa_co_aluno06 IS NULL THEN
			vr_co_aluno06 := 'null';
		ELSE
			vr_co_aluno06 := '"' || RTRIM(pa_co_aluno06) || '"';
		END IF;
		v_sql1 := '  delete from S_ALUNO_APROVEITAMENTO where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_disciplina = ' || RTRIM(vr_co_disciplina02);
		v_sql2 := '  and sg_serie = ' || RTRIM(vr_sg_serie03) || '  and co_aluno = ' || RTRIM(vr_co_aluno06) || ';';
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
		IF pn_co_disciplina02 IS NULL
		AND pa_co_disciplina02 IS NULL THEN
			vr_co_disciplina02 := 'null';
		END IF;
		IF pn_co_disciplina02 IS NULL
		AND pa_co_disciplina02 IS NOT NULL THEN
			vr_co_disciplina02 := 'null';
		END IF;
		IF pn_co_disciplina02 IS NOT NULL
		AND pa_co_disciplina02 IS NULL THEN
			vr_co_disciplina02 := '"' || RTRIM(pn_co_disciplina02) || '"';
		END IF;
		IF pn_co_disciplina02 IS NOT NULL
		AND pa_co_disciplina02 IS NOT NULL THEN
			IF pa_co_disciplina02 <> pn_co_disciplina02 THEN
				vr_co_disciplina02 := '"' || RTRIM(pn_co_disciplina02) || '"';
			ELSE
				vr_co_disciplina02 := '"' || RTRIM(pa_co_disciplina02) || '"';
			END IF;
		END IF;
		IF pn_sg_serie03 IS NULL
		AND pa_sg_serie03 IS NULL THEN
			vr_sg_serie03 := 'null';
		END IF;
		IF pn_sg_serie03 IS NULL
		AND pa_sg_serie03 IS NOT NULL THEN
			vr_sg_serie03 := 'null';
		END IF;
		IF pn_sg_serie03 IS NOT NULL
		AND pa_sg_serie03 IS NULL THEN
			vr_sg_serie03 := '"' || RTRIM(pn_sg_serie03) || '"';
		END IF;
		IF pn_sg_serie03 IS NOT NULL
		AND pa_sg_serie03 IS NOT NULL THEN
			IF pa_sg_serie03 <> pn_sg_serie03 THEN
				vr_sg_serie03 := '"' || RTRIM(pn_sg_serie03) || '"';
			ELSE
				vr_sg_serie03 := '"' || RTRIM(pa_sg_serie03) || '"';
			END IF;
		END IF;
		IF pn_nu_nota04 IS NULL
		AND pa_nu_nota04 IS NULL THEN
			vr_nu_nota04 := 'null';
		END IF;
		IF pn_nu_nota04 IS NULL
		AND pa_nu_nota04 IS NOT NULL THEN
			vr_nu_nota04 := 'null';
		END IF;
		IF pn_nu_nota04 IS NOT NULL
		AND pa_nu_nota04 IS NULL THEN
			vr_nu_nota04 := '"' || RTRIM(pn_nu_nota04) || '"';
		END IF;
		IF pn_nu_nota04 IS NOT NULL
		AND pa_nu_nota04 IS NOT NULL THEN
			IF pa_nu_nota04 <> pn_nu_nota04 THEN
				vr_nu_nota04 := '"' || RTRIM(pn_nu_nota04) || '"';
			ELSE
				vr_nu_nota04 := '"' || RTRIM(pa_nu_nota04) || '"';
			END IF;
		END IF;
		IF pn_nu_aulas_dada05 IS NULL
		AND pa_nu_aulas_dada05 IS NULL THEN
			vr_nu_aulas_dada05 := 'null';
		END IF;
		IF pn_nu_aulas_dada05 IS NULL
		AND pa_nu_aulas_dada05 IS NOT NULL THEN
			vr_nu_aulas_dada05 := 'null';
		END IF;
		IF pn_nu_aulas_dada05 IS NOT NULL
		AND pa_nu_aulas_dada05 IS NULL THEN
			vr_nu_aulas_dada05 := '"' || RTRIM(pn_nu_aulas_dada05) || '"';
		END IF;
		IF pn_nu_aulas_dada05 IS NOT NULL
		AND pa_nu_aulas_dada05 IS NOT NULL THEN
			IF pa_nu_aulas_dada05 <> pn_nu_aulas_dada05 THEN
				vr_nu_aulas_dada05 := '"' || RTRIM(pn_nu_aulas_dada05) || '"';
			ELSE
				vr_nu_aulas_dada05 := '"' || RTRIM(pa_nu_aulas_dada05) || '"';
			END IF;
		END IF;
		IF pn_co_aluno06 IS NULL
		AND pa_co_aluno06 IS NULL THEN
			vr_co_aluno06 := 'null';
		END IF;
		IF pn_co_aluno06 IS NULL
		AND pa_co_aluno06 IS NOT NULL THEN
			vr_co_aluno06 := 'null';
		END IF;
		IF pn_co_aluno06 IS NOT NULL
		AND pa_co_aluno06 IS NULL THEN
			vr_co_aluno06 := '"' || RTRIM(pn_co_aluno06) || '"';
		END IF;
		IF pn_co_aluno06 IS NOT NULL
		AND pa_co_aluno06 IS NOT NULL THEN
			IF pa_co_aluno06 <> pn_co_aluno06 THEN
				vr_co_aluno06 := '"' || RTRIM(pn_co_aluno06) || '"';
			ELSE
				vr_co_aluno06 := '"' || RTRIM(pa_co_aluno06) || '"';
			END IF;
		END IF;
		IF pn_nu_faltas07 IS NULL
		AND pa_nu_faltas07 IS NULL THEN
			vr_nu_faltas07 := 'null';
		END IF;
		IF pn_nu_faltas07 IS NULL
		AND pa_nu_faltas07 IS NOT NULL THEN
			vr_nu_faltas07 := 'null';
		END IF;
		IF pn_nu_faltas07 IS NOT NULL
		AND pa_nu_faltas07 IS NULL THEN
			vr_nu_faltas07 := pn_nu_faltas07;
		END IF;
		IF pn_nu_faltas07 IS NOT NULL
		AND pa_nu_faltas07 IS NOT NULL THEN
			IF pa_nu_faltas07 <> pn_nu_faltas07 THEN
				vr_nu_faltas07 := pn_nu_faltas07;
			ELSE
				vr_nu_faltas07 := pa_nu_faltas07;
			END IF;
		END IF;
		IF pn_id_exame08 IS NULL
		AND pa_id_exame08 IS NULL THEN
			vr_id_exame08 := 'null';
		END IF;
		IF pn_id_exame08 IS NULL
		AND pa_id_exame08 IS NOT NULL THEN
			vr_id_exame08 := 'null';
		END IF;
		IF pn_id_exame08 IS NOT NULL
		AND pa_id_exame08 IS NULL THEN
			vr_id_exame08 := '"' || RTRIM(pn_id_exame08) || '"';
		END IF;
		IF pn_id_exame08 IS NOT NULL
		AND pa_id_exame08 IS NOT NULL THEN
			IF pa_id_exame08 <> pn_id_exame08 THEN
				vr_id_exame08 := '"' || RTRIM(pn_id_exame08) || '"';
			ELSE
				vr_id_exame08 := '"' || RTRIM(pa_id_exame08) || '"';
			END IF;
		END IF;
		IF pn_dt_conclusao09 IS NULL
		AND pa_dt_conclusao09 IS NULL THEN
			vr_dt_conclusao09 := 'null';
		END IF;
		IF pn_dt_conclusao09 IS NULL
		AND pa_dt_conclusao09 IS NOT NULL THEN
			vr_dt_conclusao09 := 'null';
		END IF;
		IF pn_dt_conclusao09 IS NOT NULL
		AND pa_dt_conclusao09 IS NULL THEN
			vr_dt_conclusao09 := '"' || pn_dt_conclusao09 || '"';
		END IF;
		IF pn_dt_conclusao09 IS NOT NULL
		AND pa_dt_conclusao09 IS NOT NULL THEN
			IF pa_dt_conclusao09 <> pn_dt_conclusao09 THEN
				vr_dt_conclusao09 := '"' || pn_dt_conclusao09 || '"';
			ELSE
				vr_dt_conclusao09 := '"' || pa_dt_conclusao09 || '"';
			END IF;
		END IF;
		IF pn_ds_estrategia10 IS NULL
		AND pa_ds_estrategia10 IS NULL THEN
			vr_ds_estrategia10 := 'null';
		END IF;
		IF pn_ds_estrategia10 IS NULL
		AND pa_ds_estrategia10 IS NOT NULL THEN
			vr_ds_estrategia10 := 'null';
		END IF;
		IF pn_ds_estrategia10 IS NOT NULL
		AND pa_ds_estrategia10 IS NULL THEN
			vr_ds_estrategia10 := '"' || RTRIM(pn_ds_estrategia10) || '"';
		END IF;
		IF pn_ds_estrategia10 IS NOT NULL
		AND pa_ds_estrategia10 IS NOT NULL THEN
			IF pa_ds_estrategia10 <> pn_ds_estrategia10 THEN
				vr_ds_estrategia10 := '"' || RTRIM(pn_ds_estrategia10) || '"';
			ELSE
				vr_ds_estrategia10 := '"' || RTRIM(pa_ds_estrategia10) || '"';
			END IF;
		END IF;
		v_sql1 := 'update S_ALUNO_APROVEITAMENTO set co_unidade = ' || RTRIM(vr_co_unidade00) || '  , ano_sem = ' || RTRIM(vr_ano_sem01) || '  , co_disciplina = ' || RTRIM(vr_co_disciplina02) || '  , sg_serie = ' || RTRIM(vr_sg_serie03) || '  , nu_nota = ' || RTRIM(vr_nu_nota04) || '  , nu_aulas_dadas = ' || RTRIM(vr_nu_aulas_dada05);
		v_sql2 := '  , co_aluno = ' || RTRIM(vr_co_aluno06) || '  , nu_faltas = ' || RTRIM(vr_nu_faltas07) || '  , id_exame = ' || RTRIM(vr_id_exame08) || '  , dt_conclusao = ' || RTRIM(vr_dt_conclusao09);
		v_sql3 := ' where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_disciplina = ' || RTRIM(vr_co_disciplina02) || '  and sg_serie = ' || RTRIM(vr_sg_serie03) || '  and co_aluno = ' || RTRIM(vr_co_aluno06) || '  , ds_estrategia = ' || RTRIM(vr_ds_estrategia10) || ';';
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
		       's_aluno_aproveit',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_aluno_ap024;
/

