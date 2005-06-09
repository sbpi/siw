CREATE OR REPLACE PROCEDURE pr_s_conteudo072(
	P_OP_IN                CHAR,
	PA_co_conteudo00_IN    s_conteudo_program.co_conteudo%TYPE,
	PA_co_curs_serie01_IN  s_conteudo_program.co_curs_serie_disc%TYPE,
	PA_co_unidade02_IN     s_conteudo_program.co_unidade%TYPE,
	PA_ano_sem03_IN        s_conteudo_program.ano_sem%TYPE,
	PA_co_disciplina04_IN  s_conteudo_program.co_disciplina%TYPE,
	PA_nu_frente05_IN      s_conteudo_program.nu_frente%TYPE,
	PA_nu_aula06_IN        s_conteudo_program.nu_aula%TYPE,
	PA_ds_conteudo07_IN    s_conteudo_program.ds_conteudo%TYPE,
	PN_co_conteudo00_IN    s_conteudo_program.co_conteudo%TYPE,
	PN_co_curs_serie01_IN  s_conteudo_program.co_curs_serie_disc%TYPE,
	PN_co_unidade02_IN     s_conteudo_program.co_unidade%TYPE,
	PN_ano_sem03_IN        s_conteudo_program.ano_sem%TYPE,
	PN_co_disciplina04_IN  s_conteudo_program.co_disciplina%TYPE,
	PN_nu_frente05_IN      s_conteudo_program.nu_frente%TYPE,
	PN_nu_aula06_IN        s_conteudo_program.nu_aula%TYPE,
	PN_ds_conteudo07_IN    s_conteudo_program.ds_conteudo%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_conteudo00    s_conteudo_program.co_conteudo%TYPE := PA_co_conteudo00_IN;
PA_co_curs_serie01  s_conteudo_program.co_curs_serie_disc%TYPE := PA_co_curs_serie01_IN;
PA_co_unidade02     s_conteudo_program.co_unidade%TYPE := PA_co_unidade02_IN;
PA_ano_sem03        s_conteudo_program.ano_sem%TYPE := PA_ano_sem03_IN;
PA_co_disciplina04  s_conteudo_program.co_disciplina%TYPE := PA_co_disciplina04_IN;
PA_nu_frente05      s_conteudo_program.nu_frente%TYPE := PA_nu_frente05_IN;
PA_nu_aula06        s_conteudo_program.nu_aula%TYPE := PA_nu_aula06_IN;
PA_ds_conteudo07    s_conteudo_program.ds_conteudo%TYPE := PA_ds_conteudo07_IN;
PN_co_conteudo00    s_conteudo_program.co_conteudo%TYPE := PN_co_conteudo00_IN;
PN_co_curs_serie01  s_conteudo_program.co_curs_serie_disc%TYPE := PN_co_curs_serie01_IN;
PN_co_unidade02     s_conteudo_program.co_unidade%TYPE := PN_co_unidade02_IN;
PN_ano_sem03        s_conteudo_program.ano_sem%TYPE := PN_ano_sem03_IN;
PN_co_disciplina04  s_conteudo_program.co_disciplina%TYPE := PN_co_disciplina04_IN;
PN_nu_frente05      s_conteudo_program.nu_frente%TYPE := PN_nu_frente05_IN;
PN_nu_aula06        s_conteudo_program.nu_aula%TYPE := PN_nu_aula06_IN;
PN_ds_conteudo07    s_conteudo_program.ds_conteudo%TYPE := PN_ds_conteudo07_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_conteudo00    CHAR(10);
vr_co_curs_serie01  CHAR(10);
vr_co_unidade02     CHAR(10);
vr_ano_sem03        CHAR(10);
vr_co_disciplina04  CHAR(10);
vr_nu_frente05      CHAR(10);
vr_nu_aula06        CHAR(10);
vr_ds_conteudo07    CHAR(90);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_conteudo00 IS NULL THEN
			vr_co_conteudo00 := 'null';
		ELSE
			vr_co_conteudo00 := pn_co_conteudo00;
		END IF;
		IF pn_co_curs_serie01 IS NULL THEN
			vr_co_curs_serie01 := 'null';
		ELSE
			vr_co_curs_serie01 := pn_co_curs_serie01;
		END IF;
		IF pn_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := pn_co_unidade02;
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
		IF pn_nu_frente05 IS NULL THEN
			vr_nu_frente05 := 'null';
		ELSE
			vr_nu_frente05 := pn_nu_frente05;
		END IF;
		IF pn_nu_aula06 IS NULL THEN
			vr_nu_aula06 := 'null';
		ELSE
			vr_nu_aula06 := pn_nu_aula06;
		END IF;
		IF pn_ds_conteudo07 IS NULL THEN
			vr_ds_conteudo07 := 'null';
		ELSE
			vr_ds_conteudo07 := pn_ds_conteudo07;
		END IF;
		v_sql1 := 'insert into S_CONTEUDO_PROGRAMATICO(co_conteudo, CO_CURSO_SERIE_DISCIPLINA, co_unidade, ano_sem, co_disciplina, nu_frente, nu_aula, ds_conteudo) values (';
		v_sql2 := RTRIM(vr_co_conteudo00) || ',' || RTRIM(vr_co_curs_serie01) || ',' || '"' || RTRIM(vr_co_unidade02) || '"' || ',' || '"' || RTRIM(vr_ano_sem03) || '"' || ',' || '"' || RTRIM(vr_co_disciplina04) || '"' || ',';
		v_sql3 := RTRIM(vr_nu_frente05) || ',' || RTRIM(vr_nu_aula06) || ',' || '"' || RTRIM(vr_ds_conteudo07) || '"' || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_conteudo00 IS NULL THEN
			vr_co_conteudo00 := 'null';
		ELSE
			vr_co_conteudo00 := pa_co_conteudo00;
		END IF;
		IF pa_co_curs_serie01 IS NULL THEN
			vr_co_curs_serie01 := 'null';
		ELSE
			vr_co_curs_serie01 := pa_co_curs_serie01;
		END IF;
		IF pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := '"' || RTRIM(pa_co_unidade02) || '"';
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
		v_sql1 := '  delete from S_CONTEUDO_PROGRAMATICO where co_conteudo = ' || RTRIM(vr_co_conteudo00) || '  and CO_CURSO_SERIE_DISCIPLINA = ' || RTRIM(vr_co_curs_serie01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02);
		v_sql2 := '  and ano_sem = ' || RTRIM(vr_ano_sem03) || '  and co_disciplina = ' || RTRIM(vr_co_disciplina04) || ';';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_conteudo00 IS NULL
		AND pa_co_conteudo00 IS NULL THEN
			vr_co_conteudo00 := 'null';
		END IF;
		IF pn_co_conteudo00 IS NULL
		AND pa_co_conteudo00 IS NOT NULL THEN
			vr_co_conteudo00 := 'null';
		END IF;
		IF pn_co_conteudo00 IS NOT NULL
		AND pa_co_conteudo00 IS NULL THEN
			vr_co_conteudo00 := pn_co_conteudo00;
		END IF;
		IF pn_co_conteudo00 IS NOT NULL
		AND pa_co_conteudo00 IS NOT NULL THEN
			IF pa_co_conteudo00 <> pn_co_conteudo00 THEN
				vr_co_conteudo00 := pn_co_conteudo00;
			ELSE
				vr_co_conteudo00 := pa_co_conteudo00;
			END IF;
		END IF;
		IF pn_co_curs_serie01 IS NULL
		AND pa_co_curs_serie01 IS NULL THEN
			vr_co_curs_serie01 := 'null';
		END IF;
		IF pn_co_curs_serie01 IS NULL
		AND pa_co_curs_serie01 IS NOT NULL THEN
			vr_co_curs_serie01 := 'null';
		END IF;
		IF pn_co_curs_serie01 IS NOT NULL
		AND pa_co_curs_serie01 IS NULL THEN
			vr_co_curs_serie01 := pn_co_curs_serie01;
		END IF;
		IF pn_co_curs_serie01 IS NOT NULL
		AND pa_co_curs_serie01 IS NOT NULL THEN
			IF pa_co_curs_serie01 <> pn_co_curs_serie01 THEN
				vr_co_curs_serie01 := pn_co_curs_serie01;
			ELSE
				vr_co_curs_serie01 := pa_co_curs_serie01;
			END IF;
		END IF;
		IF pn_co_unidade02 IS NULL
		AND pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		END IF;
		IF pn_co_unidade02 IS NULL
		AND pa_co_unidade02 IS NOT NULL THEN
			vr_co_unidade02 := 'null';
		END IF;
		IF pn_co_unidade02 IS NOT NULL
		AND pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := '"' || RTRIM(pn_co_unidade02) || '"';
		END IF;
		IF pn_co_unidade02 IS NOT NULL
		AND pa_co_unidade02 IS NOT NULL THEN
			IF pa_co_unidade02 <> pn_co_unidade02 THEN
				vr_co_unidade02 := '"' || RTRIM(pn_co_unidade02) || '"';
			ELSE
				vr_co_unidade02 := '"' || RTRIM(pa_co_unidade02) || '"';
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
		IF pn_nu_frente05 IS NULL
		AND pa_nu_frente05 IS NULL THEN
			vr_nu_frente05 := 'null';
		END IF;
		IF pn_nu_frente05 IS NULL
		AND pa_nu_frente05 IS NOT NULL THEN
			vr_nu_frente05 := 'null';
		END IF;
		IF pn_nu_frente05 IS NOT NULL
		AND pa_nu_frente05 IS NULL THEN
			vr_nu_frente05 := pn_nu_frente05;
		END IF;
		IF pn_nu_frente05 IS NOT NULL
		AND pa_nu_frente05 IS NOT NULL THEN
			IF pa_nu_frente05 <> pn_nu_frente05 THEN
				vr_nu_frente05 := pn_nu_frente05;
			ELSE
				vr_nu_frente05 := pa_nu_frente05;
			END IF;
		END IF;
		IF pn_nu_aula06 IS NULL
		AND pa_nu_aula06 IS NULL THEN
			vr_nu_aula06 := 'null';
		END IF;
		IF pn_nu_aula06 IS NULL
		AND pa_nu_aula06 IS NOT NULL THEN
			vr_nu_aula06 := 'null';
		END IF;
		IF pn_nu_aula06 IS NOT NULL
		AND pa_nu_aula06 IS NULL THEN
			vr_nu_aula06 := pn_nu_aula06;
		END IF;
		IF pn_nu_aula06 IS NOT NULL
		AND pa_nu_aula06 IS NOT NULL THEN
			IF pa_nu_aula06 <> pn_nu_aula06 THEN
				vr_nu_aula06 := pn_nu_aula06;
			ELSE
				vr_nu_aula06 := pa_nu_aula06;
			END IF;
		END IF;
		IF pn_ds_conteudo07 IS NULL
		AND pa_ds_conteudo07 IS NULL THEN
			vr_ds_conteudo07 := 'null';
		END IF;
		IF pn_ds_conteudo07 IS NULL
		AND pa_ds_conteudo07 IS NOT NULL THEN
			vr_ds_conteudo07 := 'null';
		END IF;
		IF pn_ds_conteudo07 IS NOT NULL
		AND pa_ds_conteudo07 IS NULL THEN
			vr_ds_conteudo07 := '"' || RTRIM(pn_ds_conteudo07) || '"';
		END IF;
		IF pn_ds_conteudo07 IS NOT NULL
		AND pa_ds_conteudo07 IS NOT NULL THEN
			IF pa_ds_conteudo07 <> pn_ds_conteudo07 THEN
				vr_ds_conteudo07 := '"' || RTRIM(pn_ds_conteudo07) || '"';
			ELSE
				vr_ds_conteudo07 := '"' || RTRIM(pa_ds_conteudo07) || '"';
			END IF;
		END IF;
		v_sql1 := 'update S_CONTEUDO_PROGRAMATICO set co_conteudo = ' || RTRIM(vr_co_conteudo00) || '  , CO_CURSO_SERIE_DISCIPLINA = ' || RTRIM(vr_co_curs_serie01) || '  , co_unidade = ' || RTRIM(vr_co_unidade02) || '  , ano_sem = ' || RTRIM(vr_ano_sem03);
		v_sql2 := '  , co_disciplina = ' || RTRIM(vr_co_disciplina04) || '  , nu_frente = ' || RTRIM(vr_nu_frente05) || '  , nu_aula = ' || RTRIM(vr_nu_aula06) || '  , ds_conteudo = ' || RTRIM(vr_ds_conteudo07);
		v_sql3 := ' where co_conteudo = ' || RTRIM(vr_co_conteudo00) || '  and co_curs_serie_disc = ' || RTRIM(vr_co_curs_serie01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || '  and ano_sem = ' || RTRIM(vr_ano_sem03) || '  and co_disciplina = ' || RTRIM(vr_co_disciplina04) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	END IF;
	IF p_op = 'del' THEN
		v_uni := pa_co_unidade02;
	ELSE
		v_uni := pn_co_unidade02;
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
		       's_conteudo_program',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_conteudo072;
/

