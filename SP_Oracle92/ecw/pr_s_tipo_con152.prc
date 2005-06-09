CREATE OR REPLACE PROCEDURE pr_s_tipo_con152(
	P_OP_IN                CHAR,
	PA_tp_conteudo_e00_IN  s_tipo_cont_educ.tp_conteudo_educ%TYPE,
	PA_ano_sem01_IN        s_tipo_cont_educ.ano_sem%TYPE,
	PA_co_curso02_IN       s_tipo_cont_educ.co_curso%TYPE,
	PA_co_seq_serie03_IN   s_tipo_cont_educ.co_seq_serie%TYPE,
	PA_co_unidade04_IN     s_tipo_cont_educ.co_unidade%TYPE,
	PA_ds_conteudo_e05_IN  s_tipo_cont_educ.ds_conteudo_educ%TYPE,
	PA_co_ordem_cont06_IN  s_tipo_cont_educ.co_ordem_conteudo%TYPE,
	PN_tp_conteudo_e00_IN  s_tipo_cont_educ.tp_conteudo_educ%TYPE,
	PN_ano_sem01_IN        s_tipo_cont_educ.ano_sem%TYPE,
	PN_co_curso02_IN       s_tipo_cont_educ.co_curso%TYPE,
	PN_co_seq_serie03_IN   s_tipo_cont_educ.co_seq_serie%TYPE,
	PN_co_unidade04_IN     s_tipo_cont_educ.co_unidade%TYPE,
	PN_ds_conteudo_e05_IN  s_tipo_cont_educ.ds_conteudo_educ%TYPE,
	PN_co_ordem_cont06_IN  s_tipo_cont_educ.co_ordem_conteudo%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_tp_conteudo_e00  s_tipo_cont_educ.tp_conteudo_educ%TYPE := PA_tp_conteudo_e00_IN;
PA_ano_sem01        s_tipo_cont_educ.ano_sem%TYPE := PA_ano_sem01_IN;
PA_co_curso02       s_tipo_cont_educ.co_curso%TYPE := PA_co_curso02_IN;
PA_co_seq_serie03   s_tipo_cont_educ.co_seq_serie%TYPE := PA_co_seq_serie03_IN;
PA_co_unidade04     s_tipo_cont_educ.co_unidade%TYPE := PA_co_unidade04_IN;
PA_ds_conteudo_e05  s_tipo_cont_educ.ds_conteudo_educ%TYPE := PA_ds_conteudo_e05_IN;
PA_co_ordem_cont06  s_tipo_cont_educ.co_ordem_conteudo%TYPE := PA_co_ordem_cont06_IN;
PN_tp_conteudo_e00  s_tipo_cont_educ.tp_conteudo_educ%TYPE := PN_tp_conteudo_e00_IN;
PN_ano_sem01        s_tipo_cont_educ.ano_sem%TYPE := PN_ano_sem01_IN;
PN_co_curso02       s_tipo_cont_educ.co_curso%TYPE := PN_co_curso02_IN;
PN_co_seq_serie03   s_tipo_cont_educ.co_seq_serie%TYPE := PN_co_seq_serie03_IN;
PN_co_unidade04     s_tipo_cont_educ.co_unidade%TYPE := PN_co_unidade04_IN;
PN_ds_conteudo_e05  s_tipo_cont_educ.ds_conteudo_educ%TYPE := PN_ds_conteudo_e05_IN;
PN_co_ordem_cont06  s_tipo_cont_educ.co_ordem_conteudo%TYPE := PN_co_ordem_cont06_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_tp_conteudo_e00  CHAR(10);
vr_ano_sem01        CHAR(10);
vr_co_curso02       CHAR(10);
vr_co_seq_serie03   CHAR(10);
vr_co_unidade04     CHAR(10);
vr_ds_conteudo_e05  CHAR(80);
vr_co_ordem_cont06  CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_tp_conteudo_e00 IS NULL THEN
			vr_tp_conteudo_e00 := 'null';
		ELSE
			vr_tp_conteudo_e00 := pn_tp_conteudo_e00;
		END IF;
		IF pn_ano_sem01 IS NULL THEN
			vr_ano_sem01 := 'null';
		ELSE
			vr_ano_sem01 := pn_ano_sem01;
		END IF;
		IF pn_co_curso02 IS NULL THEN
			vr_co_curso02 := 'null';
		ELSE
			vr_co_curso02 := pn_co_curso02;
		END IF;
		IF pn_co_seq_serie03 IS NULL THEN
			vr_co_seq_serie03 := 'null';
		ELSE
			vr_co_seq_serie03 := pn_co_seq_serie03;
		END IF;
		IF pn_co_unidade04 IS NULL THEN
			vr_co_unidade04 := 'null';
		ELSE
			vr_co_unidade04 := pn_co_unidade04;
		END IF;
		IF pn_ds_conteudo_e05 IS NULL THEN
			vr_ds_conteudo_e05 := 'null';
		ELSE
			vr_ds_conteudo_e05 := pn_ds_conteudo_e05;
		END IF;
		IF pn_co_ordem_cont06 IS NULL THEN
			vr_co_ordem_cont06 := 'null';
		ELSE
			vr_co_ordem_cont06 := pn_co_ordem_cont06;
		END IF;
		v_sql1 := 'insert into S_TIPO_CONTEUDO_EDUCATIVO(TP_CONTEUDO_EDUCATIVO, ano_sem, co_curso, co_seq_serie, co_unidade, DS_CONTEUDO_EDUCATIVO, co_ordem_conteudo) values (';
		v_sql2 := RTRIM(vr_tp_conteudo_e00) || ',' || '"' || RTRIM(vr_ano_sem01) || '"' || ',' || RTRIM(vr_co_curso02) || ',' || RTRIM(vr_co_seq_serie03) || ',' || '"' || RTRIM(vr_co_unidade04) || '"' || ',' || '"' || RTRIM(vr_ds_conteudo_e05) || '"' || ',' || RTRIM(vr_co_ordem_cont06) || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_tp_conteudo_e00 IS NULL THEN
			vr_tp_conteudo_e00 := 'null';
		ELSE
			vr_tp_conteudo_e00 := pa_tp_conteudo_e00;
		END IF;
		IF pa_ano_sem01 IS NULL THEN
			vr_ano_sem01 := 'null';
		ELSE
			vr_ano_sem01 := '"' || RTRIM(pa_ano_sem01) || '"';
		END IF;
		IF pa_co_curso02 IS NULL THEN
			vr_co_curso02 := 'null';
		ELSE
			vr_co_curso02 := pa_co_curso02;
		END IF;
		IF pa_co_seq_serie03 IS NULL THEN
			vr_co_seq_serie03 := 'null';
		ELSE
			vr_co_seq_serie03 := pa_co_seq_serie03;
		END IF;
		IF pa_co_unidade04 IS NULL THEN
			vr_co_unidade04 := 'null';
		ELSE
			vr_co_unidade04 := '"' || RTRIM(pa_co_unidade04) || '"';
		END IF;
		v_sql1 := '  delete from S_TIPO_CONTEUDO_EDUCATIVO where TP_CONTEUDO_EDUCATIVO = ' || RTRIM(vr_tp_conteudo_e00);
		v_sql2 := '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_curso = ' || RTRIM(vr_co_curso02) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie03) || '  and co_unidade = ' || RTRIM(vr_co_unidade04) || ';';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_tp_conteudo_e00 IS NULL
		AND pa_tp_conteudo_e00 IS NULL THEN
			vr_tp_conteudo_e00 := 'null';
		END IF;
		IF pn_tp_conteudo_e00 IS NULL
		AND pa_tp_conteudo_e00 IS NOT NULL THEN
			vr_tp_conteudo_e00 := 'null';
		END IF;
		IF pn_tp_conteudo_e00 IS NOT NULL
		AND pa_tp_conteudo_e00 IS NULL THEN
			vr_tp_conteudo_e00 := pn_tp_conteudo_e00;
		END IF;
		IF pn_tp_conteudo_e00 IS NOT NULL
		AND pa_tp_conteudo_e00 IS NOT NULL THEN
			IF pa_tp_conteudo_e00 <> pn_tp_conteudo_e00 THEN
				vr_tp_conteudo_e00 := pn_tp_conteudo_e00;
			ELSE
				vr_tp_conteudo_e00 := pa_tp_conteudo_e00;
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
		IF pn_co_curso02 IS NULL
		AND pa_co_curso02 IS NULL THEN
			vr_co_curso02 := 'null';
		END IF;
		IF pn_co_curso02 IS NULL
		AND pa_co_curso02 IS NOT NULL THEN
			vr_co_curso02 := 'null';
		END IF;
		IF pn_co_curso02 IS NOT NULL
		AND pa_co_curso02 IS NULL THEN
			vr_co_curso02 := pn_co_curso02;
		END IF;
		IF pn_co_curso02 IS NOT NULL
		AND pa_co_curso02 IS NOT NULL THEN
			IF pa_co_curso02 <> pn_co_curso02 THEN
				vr_co_curso02 := pn_co_curso02;
			ELSE
				vr_co_curso02 := pa_co_curso02;
			END IF;
		END IF;
		IF pn_co_seq_serie03 IS NULL
		AND pa_co_seq_serie03 IS NULL THEN
			vr_co_seq_serie03 := 'null';
		END IF;
		IF pn_co_seq_serie03 IS NULL
		AND pa_co_seq_serie03 IS NOT NULL THEN
			vr_co_seq_serie03 := 'null';
		END IF;
		IF pn_co_seq_serie03 IS NOT NULL
		AND pa_co_seq_serie03 IS NULL THEN
			vr_co_seq_serie03 := pn_co_seq_serie03;
		END IF;
		IF pn_co_seq_serie03 IS NOT NULL
		AND pa_co_seq_serie03 IS NOT NULL THEN
			IF pa_co_seq_serie03 <> pn_co_seq_serie03 THEN
				vr_co_seq_serie03 := pn_co_seq_serie03;
			ELSE
				vr_co_seq_serie03 := pa_co_seq_serie03;
			END IF;
		END IF;
		IF pn_co_unidade04 IS NULL
		AND pa_co_unidade04 IS NULL THEN
			vr_co_unidade04 := 'null';
		END IF;
		IF pn_co_unidade04 IS NULL
		AND pa_co_unidade04 IS NOT NULL THEN
			vr_co_unidade04 := 'null';
		END IF;
		IF pn_co_unidade04 IS NOT NULL
		AND pa_co_unidade04 IS NULL THEN
			vr_co_unidade04 := '"' || RTRIM(pn_co_unidade04) || '"';
		END IF;
		IF pn_co_unidade04 IS NOT NULL
		AND pa_co_unidade04 IS NOT NULL THEN
			IF pa_co_unidade04 <> pn_co_unidade04 THEN
				vr_co_unidade04 := '"' || RTRIM(pn_co_unidade04) || '"';
			ELSE
				vr_co_unidade04 := '"' || RTRIM(pa_co_unidade04) || '"';
			END IF;
		END IF;
		IF pn_ds_conteudo_e05 IS NULL
		AND pa_ds_conteudo_e05 IS NULL THEN
			vr_ds_conteudo_e05 := 'null';
		END IF;
		IF pn_ds_conteudo_e05 IS NULL
		AND pa_ds_conteudo_e05 IS NOT NULL THEN
			vr_ds_conteudo_e05 := 'null';
		END IF;
		IF pn_ds_conteudo_e05 IS NOT NULL
		AND pa_ds_conteudo_e05 IS NULL THEN
			vr_ds_conteudo_e05 := '"' || RTRIM(pn_ds_conteudo_e05) || '"';
		END IF;
		IF pn_ds_conteudo_e05 IS NOT NULL
		AND pa_ds_conteudo_e05 IS NOT NULL THEN
			IF pa_ds_conteudo_e05 <> pn_ds_conteudo_e05 THEN
				vr_ds_conteudo_e05 := '"' || RTRIM(pn_ds_conteudo_e05) || '"';
			ELSE
				vr_ds_conteudo_e05 := '"' || RTRIM(pa_ds_conteudo_e05) || '"';
			END IF;
		END IF;
		IF pn_co_ordem_cont06 IS NULL
		AND pa_co_ordem_cont06 IS NULL THEN
			vr_co_ordem_cont06 := 'null';
		END IF;
		IF pn_co_ordem_cont06 IS NULL
		AND pa_co_ordem_cont06 IS NOT NULL THEN
			vr_co_ordem_cont06 := 'null';
		END IF;
		IF pn_co_ordem_cont06 IS NOT NULL
		AND pa_co_ordem_cont06 IS NULL THEN
			vr_co_ordem_cont06 := pn_co_ordem_cont06;
		END IF;
		IF pn_co_ordem_cont06 IS NOT NULL
		AND pa_co_ordem_cont06 IS NOT NULL THEN
			IF pa_co_ordem_cont06 <> pn_co_ordem_cont06 THEN
				vr_co_ordem_cont06 := pn_co_ordem_cont06;
			ELSE
				vr_co_ordem_cont06 := pa_co_ordem_cont06;
			END IF;
		END IF;
		v_sql1 := 'update S_TIPO_CONTEUDO_EDUCATIVO set TP_CONTEUDO_EDUCATIVO = ' || RTRIM(vr_tp_conteudo_e00) || '  , ano_sem = ' || RTRIM(vr_ano_sem01) || '  , co_curso = ' || RTRIM(vr_co_curso02) || '  , co_seq_serie = ' || RTRIM(vr_co_seq_serie03) || '  , co_unidade = ' || RTRIM(vr_co_unidade04);
		v_sql2 := '  , DS_CONTEUDO_EDUCATIVO = ' || RTRIM(vr_ds_conteudo_e05) || '  , co_ordem_conteudo = ' || RTRIM(vr_co_ordem_cont06) || ' where tp_conteudo_educ = ' || RTRIM(vr_tp_conteudo_e00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01);
		v_sql3 := '  and co_curso = ' || RTRIM(vr_co_curso02) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie03) || '  and co_unidade = ' || RTRIM(vr_co_unidade04) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	END IF;
	IF p_op = 'del' THEN
		v_uni := pa_co_unidade04;
	ELSE
		v_uni := pn_co_unidade04;
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
		       's_tipo_cont_educ',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_tipo_con152;
/

