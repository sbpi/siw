CREATE OR REPLACE PROCEDURE pr_s_aluno_de028(
	P_OP_IN                CHAR,
	PA_co_unidade00_IN     s_aluno_dependenc.co_unidade%TYPE,
	PA_ano_sem01_IN        s_aluno_dependenc.ano_sem%TYPE,
	PA_co_disciplina02_IN  s_aluno_dependenc.co_disciplina%TYPE,
	PA_co_aluno03_IN       s_aluno_dependenc.co_aluno%TYPE,
	PA_nu_nota04_IN        s_aluno_dependenc.nu_nota%TYPE,
	PA_sg_serie05_IN       s_aluno_dependenc.sg_serie%TYPE,
	PA_nu_aulas_dada06_IN  s_aluno_dependenc.nu_aulas_dadas%TYPE,
	PA_nu_faltas07_IN      s_aluno_dependenc.nu_faltas%TYPE,
	PA_dp_serie08_IN       s_aluno_dependenc.dp_serie%TYPE,
	PA_ds_opcao09_IN       s_aluno_dependenc.ds_opcao%TYPE,
	PA_dt_opcao10_IN       s_aluno_dependenc.dt_opcao%TYPE,
	PA_ds_resultado11_IN   s_aluno_dependenc.ds_resultado%TYPE,
	PN_co_unidade00_IN     s_aluno_dependenc.co_unidade%TYPE,
	PN_ano_sem01_IN        s_aluno_dependenc.ano_sem%TYPE,
	PN_co_disciplina02_IN  s_aluno_dependenc.co_disciplina%TYPE,
	PN_co_aluno03_IN       s_aluno_dependenc.co_aluno%TYPE,
	PN_nu_nota04_IN        s_aluno_dependenc.nu_nota%TYPE,
	PN_sg_serie05_IN       s_aluno_dependenc.sg_serie%TYPE,
	PN_nu_aulas_dada06_IN  s_aluno_dependenc.nu_aulas_dadas%TYPE,
	PN_nu_faltas07_IN      s_aluno_dependenc.nu_faltas%TYPE,
	PN_dp_serie08_IN       s_aluno_dependenc.dp_serie%TYPE,
	PN_ds_opcao09_IN       s_aluno_dependenc.ds_opcao%TYPE,
	PN_dt_opcao10_IN       s_aluno_dependenc.dt_opcao%TYPE,
	PN_ds_resultado11_IN   s_aluno_dependenc.ds_resultado%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_unidade00     s_aluno_dependenc.co_unidade%TYPE := PA_co_unidade00_IN;
PA_ano_sem01        s_aluno_dependenc.ano_sem%TYPE := PA_ano_sem01_IN;
PA_co_disciplina02  s_aluno_dependenc.co_disciplina%TYPE := PA_co_disciplina02_IN;
PA_co_aluno03       s_aluno_dependenc.co_aluno%TYPE := PA_co_aluno03_IN;
PA_nu_nota04        s_aluno_dependenc.nu_nota%TYPE := PA_nu_nota04_IN;
PA_sg_serie05       s_aluno_dependenc.sg_serie%TYPE := PA_sg_serie05_IN;
PA_nu_aulas_dada06  s_aluno_dependenc.nu_aulas_dadas%TYPE := PA_nu_aulas_dada06_IN;
PA_nu_faltas07      s_aluno_dependenc.nu_faltas%TYPE := PA_nu_faltas07_IN;
PA_dp_serie08       s_aluno_dependenc.dp_serie%TYPE := PA_dp_serie08_IN;
PA_ds_opcao09       s_aluno_dependenc.ds_opcao%TYPE := PA_ds_opcao09_IN;
PA_dt_opcao10       s_aluno_dependenc.dt_opcao%TYPE := PA_dt_opcao10_IN;
PA_ds_resultado11   s_aluno_dependenc.ds_resultado%TYPE := PA_ds_resultado11_IN;
PN_co_unidade00     s_aluno_dependenc.co_unidade%TYPE := PN_co_unidade00_IN;
PN_ano_sem01        s_aluno_dependenc.ano_sem%TYPE := PN_ano_sem01_IN;
PN_co_disciplina02  s_aluno_dependenc.co_disciplina%TYPE := PN_co_disciplina02_IN;
PN_co_aluno03       s_aluno_dependenc.co_aluno%TYPE := PN_co_aluno03_IN;
PN_nu_nota04        s_aluno_dependenc.nu_nota%TYPE := PN_nu_nota04_IN;
PN_sg_serie05       s_aluno_dependenc.sg_serie%TYPE := PN_sg_serie05_IN;
PN_nu_aulas_dada06  s_aluno_dependenc.nu_aulas_dadas%TYPE := PN_nu_aulas_dada06_IN;
PN_nu_faltas07      s_aluno_dependenc.nu_faltas%TYPE := PN_nu_faltas07_IN;
PN_dp_serie08       s_aluno_dependenc.dp_serie%TYPE := PN_dp_serie08_IN;
PN_ds_opcao09       s_aluno_dependenc.ds_opcao%TYPE := PN_ds_opcao09_IN;
PN_dt_opcao10       s_aluno_dependenc.dt_opcao%TYPE := PN_dt_opcao10_IN;
PN_ds_resultado11   s_aluno_dependenc.ds_resultado%TYPE := PN_ds_resultado11_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_unidade00     CHAR(10);
vr_ano_sem01        CHAR(10);
vr_co_disciplina02  CHAR(10);
vr_co_aluno03       CHAR(20);
vr_nu_nota04        CHAR(10);
vr_sg_serie05       CHAR(10);
vr_nu_aulas_dada06  CHAR(10);
vr_nu_faltas07      CHAR(10);
vr_dp_serie08       CHAR(50);
vr_ds_opcao09       CHAR(50);
vr_dt_opcao10       CHAR(40);
vr_ds_resultado11   CHAR(50);
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
		IF pn_co_aluno03 IS NULL THEN
			vr_co_aluno03 := 'null';
		ELSE
			vr_co_aluno03 := pn_co_aluno03;
		END IF;
		IF pn_nu_nota04 IS NULL THEN
			vr_nu_nota04 := 'null';
		ELSE
			vr_nu_nota04 := pn_nu_nota04;
		END IF;
		IF pn_sg_serie05 IS NULL THEN
			vr_sg_serie05 := 'null';
		ELSE
			vr_sg_serie05 := pn_sg_serie05;
		END IF;
		IF pn_nu_aulas_dada06 IS NULL THEN
			vr_nu_aulas_dada06 := 'null';
		ELSE
			vr_nu_aulas_dada06 := pn_nu_aulas_dada06;
		END IF;
		IF pn_nu_faltas07 IS NULL THEN
			vr_nu_faltas07 := 'null';
		ELSE
			vr_nu_faltas07 := pn_nu_faltas07;
		END IF;
		IF pn_dp_serie08 IS NULL THEN
			vr_dp_serie08 := 'null';
		ELSE
			vr_dp_serie08 := pn_dp_serie08;
		END IF;
		IF pn_ds_opcao09 IS NULL THEN
			vr_ds_opcao09 := 'null';
		ELSE
			vr_ds_opcao09 := pn_ds_opcao09;
		END IF;
		IF pn_dt_opcao10 IS NULL THEN
			vr_dt_opcao10 := 'null';
		ELSE
			vr_dt_opcao10 := pn_dt_opcao10;
		END IF;
		IF pn_ds_resultado11 IS NULL THEN
			vr_ds_resultado11 := 'null';
		ELSE
			vr_ds_resultado11 := pn_ds_resultado11;
		END IF;
		v_sql1 := 'insert into S_ALUNO_DEPENDENCIA(co_unidade, ano_sem, co_disciplina, co_aluno, nu_nota, sg_serie, nu_aulas_dadas, nu_faltas, dp_serie, ds_opcao, dt_opcao, ds_resultado) values (';
		v_sql2 := '"' || RTRIM(vr_co_unidade00) || '"' || ',' || '"' || RTRIM(vr_ano_sem01) || '"' || ',' || '"' || RTRIM(vr_co_disciplina02) || '"' || ',' || '"' || RTRIM(vr_co_aluno03) || '"' || ',' || '"' || RTRIM(vr_nu_nota04) || '"' || ',' || '"' || RTRIM(vr_sg_serie05) || '"' || ',' || '"' || RTRIM(vr_nu_aulas_dada06) || '"' || ',';
		v_sql3 := RTRIM(vr_nu_faltas07) || '"' || RTRIM(vr_dp_serie08) || '"' || ',' || '"' || RTRIM(vr_ds_opcao09) || '"' || ',' || '"' || RTRIM(vr_dt_opcao10) || '"' || ',' || '"' || RTRIM(vr_ds_resultado11) || '"' || ');';
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
		IF pa_co_aluno03 IS NULL THEN
			vr_co_aluno03 := 'null';
		ELSE
			vr_co_aluno03 := '"' || RTRIM(pa_co_aluno03) || '"';
		END IF;
		IF pa_sg_serie05 IS NULL THEN
			vr_sg_serie05 := 'null';
		ELSE
			vr_sg_serie05 := '"' || RTRIM(pa_sg_serie05) || '"';
		END IF;
		v_sql1 := '  delete from S_ALUNO_DEPENDENCIA where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_disciplina = ' || RTRIM(vr_co_disciplina02);
		v_sql2 := '  and co_aluno = ' || RTRIM(vr_co_aluno03) || '  and sg_serie = ' || RTRIM(vr_sg_serie05) || ';';
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
		IF pn_co_aluno03 IS NULL
		AND pa_co_aluno03 IS NULL THEN
			vr_co_aluno03 := 'null';
		END IF;
		IF pn_co_aluno03 IS NULL
		AND pa_co_aluno03 IS NOT NULL THEN
			vr_co_aluno03 := 'null';
		END IF;
		IF pn_co_aluno03 IS NOT NULL
		AND pa_co_aluno03 IS NULL THEN
			vr_co_aluno03 := '"' || RTRIM(pn_co_aluno03) || '"';
		END IF;
		IF pn_co_aluno03 IS NOT NULL
		AND pa_co_aluno03 IS NOT NULL THEN
			IF pa_co_aluno03 <> pn_co_aluno03 THEN
				vr_co_aluno03 := '"' || RTRIM(pn_co_aluno03) || '"';
			ELSE
				vr_co_aluno03 := '"' || RTRIM(pa_co_aluno03) || '"';
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
		IF pn_sg_serie05 IS NULL
		AND pa_sg_serie05 IS NULL THEN
			vr_sg_serie05 := 'null';
		END IF;
		IF pn_sg_serie05 IS NULL
		AND pa_sg_serie05 IS NOT NULL THEN
			vr_sg_serie05 := 'null';
		END IF;
		IF pn_sg_serie05 IS NOT NULL
		AND pa_sg_serie05 IS NULL THEN
			vr_sg_serie05 := '"' || RTRIM(pn_sg_serie05) || '"';
		END IF;
		IF pn_sg_serie05 IS NOT NULL
		AND pa_sg_serie05 IS NOT NULL THEN
			IF pa_sg_serie05 <> pn_sg_serie05 THEN
				vr_sg_serie05 := '"' || RTRIM(pn_sg_serie05) || '"';
			ELSE
				vr_sg_serie05 := '"' || RTRIM(pa_sg_serie05) || '"';
			END IF;
		END IF;
		IF pn_nu_aulas_dada06 IS NULL
		AND pa_nu_aulas_dada06 IS NULL THEN
			vr_nu_aulas_dada06 := 'null';
		END IF;
		IF pn_nu_aulas_dada06 IS NULL
		AND pa_nu_aulas_dada06 IS NOT NULL THEN
			vr_nu_aulas_dada06 := 'null';
		END IF;
		IF pn_nu_aulas_dada06 IS NOT NULL
		AND pa_nu_aulas_dada06 IS NULL THEN
			vr_nu_aulas_dada06 := '"' || RTRIM(pn_nu_aulas_dada06) || '"';
		END IF;
		IF pn_nu_aulas_dada06 IS NOT NULL
		AND pa_nu_aulas_dada06 IS NOT NULL THEN
			IF pa_nu_aulas_dada06 <> pn_nu_aulas_dada06 THEN
				vr_nu_aulas_dada06 := '"' || RTRIM(pn_nu_aulas_dada06) || '"';
			ELSE
				vr_nu_aulas_dada06 := '"' || RTRIM(pa_nu_aulas_dada06) || '"';
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
		IF pn_dp_serie08 IS NULL
		AND pa_dp_serie08 IS NULL THEN
			vr_dp_serie08 := 'null';
		END IF;
		IF pn_dp_serie08 IS NULL
		AND pa_dp_serie08 IS NOT NULL THEN
			vr_dp_serie08 := 'null';
		END IF;
		IF pn_dp_serie08 IS NOT NULL
		AND pa_dp_serie08 IS NULL THEN
			vr_dp_serie08 := '"' || RTRIM(pn_dp_serie08) || '"';
		END IF;
		IF pn_dp_serie08 IS NOT NULL
		AND pa_dp_serie08 IS NOT NULL THEN
			IF pa_dp_serie08 <> pn_dp_serie08 THEN
				vr_dp_serie08 := '"' || RTRIM(pn_dp_serie08) || '"';
			ELSE
				vr_dp_serie08 := '"' || RTRIM(pa_dp_serie08) || '"';
			END IF;
		END IF;
		IF pn_ds_opcao09 IS NULL
		AND pa_ds_opcao09 IS NULL THEN
			vr_ds_opcao09 := 'null';
		END IF;
		IF pn_ds_opcao09 IS NULL
		AND pa_ds_opcao09 IS NOT NULL THEN
			vr_ds_opcao09 := 'null';
		END IF;
		IF pn_ds_opcao09 IS NOT NULL
		AND pa_ds_opcao09 IS NULL THEN
			vr_ds_opcao09 := '"' || RTRIM(pn_ds_opcao09) || '"';
		END IF;
		IF pn_ds_opcao09 IS NOT NULL
		AND pa_ds_opcao09 IS NOT NULL THEN
			IF pa_ds_opcao09 <> pn_ds_opcao09 THEN
				vr_ds_opcao09 := '"' || RTRIM(pn_ds_opcao09) || '"';
			ELSE
				vr_ds_opcao09 := '"' || RTRIM(pa_ds_opcao09) || '"';
			END IF;
		END IF;
		IF pn_dt_opcao10 IS NULL
		AND pa_dt_opcao10 IS NULL THEN
			vr_dt_opcao10 := 'null';
		END IF;
		IF pn_dt_opcao10 IS NULL
		AND pa_dt_opcao10 IS NOT NULL THEN
			vr_dt_opcao10 := 'null';
		END IF;
		IF pn_dt_opcao10 IS NOT NULL
		AND pa_dt_opcao10 IS NULL THEN
			vr_dt_opcao10 := '"' || pn_dt_opcao10 || '"';
		END IF;
		IF pn_dt_opcao10 IS NOT NULL
		AND pa_dt_opcao10 IS NOT NULL THEN
			IF pa_dt_opcao10 <> pn_dt_opcao10 THEN
				vr_dt_opcao10 := '"' || pn_dt_opcao10 || '"';
			ELSE
				vr_dt_opcao10 := '"' || pa_dt_opcao10 || '"';
			END IF;
		END IF;
		IF pn_ds_resultado11 IS NULL
		AND pa_ds_resultado11 IS NULL THEN
			vr_ds_resultado11 := 'null';
		END IF;
		IF pn_ds_resultado11 IS NULL
		AND pa_ds_resultado11 IS NOT NULL THEN
			vr_ds_resultado11 := 'null';
		END IF;
		IF pn_ds_resultado11 IS NOT NULL
		AND pa_ds_resultado11 IS NULL THEN
			vr_ds_resultado11 := '"' || RTRIM(pn_ds_resultado11) || '"';
		END IF;
		IF pn_ds_resultado11 IS NOT NULL
		AND pa_ds_resultado11 IS NOT NULL THEN
			IF pa_ds_resultado11 <> pn_ds_resultado11 THEN
				vr_ds_resultado11 := '"' || RTRIM(pn_ds_resultado11) || '"';
			ELSE
				vr_ds_resultado11 := '"' || RTRIM(pa_ds_resultado11) || '"';
			END IF;
		END IF;
		v_sql1 := 'update S_ALUNO_DEPENDENCIA set co_unidade = ' || RTRIM(vr_co_unidade00) || '  , ano_sem = ' || RTRIM(vr_ano_sem01) || '  , co_disciplina = ' || RTRIM(vr_co_disciplina02) || '  , co_aluno = ' || RTRIM(vr_co_aluno03) || '  , nu_nota = ' || RTRIM(vr_nu_nota04) || '  , sg_serie = ' || RTRIM(vr_sg_serie05) || '  , nu_aulas_dadas = ' || RTRIM(vr_nu_aulas_dada06);
		v_sql2 := '  , nu_faltas = ' || RTRIM(vr_nu_faltas07) || ' dp_serie = ' || RTRIM(vr_dp_serie08) || ' ds_opcao = ' || RTRIM(vr_ds_opcao09) || ' dt_opcao = ' || RTRIM(vr_dt_opcao10) || ' ds_resultado = ' || RTRIM(vr_ds_resultado11);
		v_sql3 := ' where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_disciplina = ' || RTRIM(vr_co_disciplina02) || '  and co_aluno = ' || RTRIM(vr_co_aluno03) || '  and sg_serie = ' || RTRIM(vr_sg_serie05) || ';';
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
		       's_aluno_dependenc',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_aluno_de028;
/

