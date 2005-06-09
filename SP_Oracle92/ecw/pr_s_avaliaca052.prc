CREATE OR REPLACE PROCEDURE pr_s_avaliaca052(
	P_OP_IN                CHAR,
	PA_av_sequencial00_IN  s_avaliacao_turma.av_sequencial%TYPE,
	PA_co_unidade01_IN     s_avaliacao_turma.co_unidade%TYPE,
	PA_dt_avaliacao02_IN   s_avaliacao_turma.dt_avaliacao%TYPE,
	PA_co_avaliacao03_IN   s_avaliacao_turma.co_avaliacao%TYPE,
	PA_obs_avaliacao04_IN  s_avaliacao_turma.obs_avaliacao%TYPE,
	PA_co_tipo_avali05_IN  s_avaliacao_turma.co_tipo_avaliacao%TYPE,
	PA_avt_max_ponto06_IN  s_avaliacao_turma.avt_max_pontos%TYPE,
	PA_ds_habilidade07_IN  s_avaliacao_turma.ds_habilidade%TYPE,
	PA_avt_bateria08_IN    s_avaliacao_turma.avt_bateria%TYPE,
	PN_av_sequencial00_IN  s_avaliacao_turma.av_sequencial%TYPE,
	PN_co_unidade01_IN     s_avaliacao_turma.co_unidade%TYPE,
	PN_dt_avaliacao02_IN   s_avaliacao_turma.dt_avaliacao%TYPE,
	PN_co_avaliacao03_IN   s_avaliacao_turma.co_avaliacao%TYPE,
	PN_obs_avaliacao04_IN  s_avaliacao_turma.obs_avaliacao%TYPE,
	PN_co_tipo_avali05_IN  s_avaliacao_turma.co_tipo_avaliacao%TYPE,
	PN_avt_max_ponto06_IN  s_avaliacao_turma.avt_max_pontos%TYPE,
	PN_ds_habilidade07_IN  s_avaliacao_turma.ds_habilidade%TYPE,
	PN_avt_bateria08_IN    s_avaliacao_turma.avt_bateria%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_av_sequencial00  s_avaliacao_turma.av_sequencial%TYPE := PA_av_sequencial00_IN;
PA_co_unidade01     s_avaliacao_turma.co_unidade%TYPE := PA_co_unidade01_IN;
PA_dt_avaliacao02   s_avaliacao_turma.dt_avaliacao%TYPE := PA_dt_avaliacao02_IN;
PA_co_avaliacao03   s_avaliacao_turma.co_avaliacao%TYPE := PA_co_avaliacao03_IN;
PA_obs_avaliacao04  s_avaliacao_turma.obs_avaliacao%TYPE := PA_obs_avaliacao04_IN;
PA_co_tipo_avali05  s_avaliacao_turma.co_tipo_avaliacao%TYPE := PA_co_tipo_avali05_IN;
PA_avt_max_ponto06  s_avaliacao_turma.avt_max_pontos%TYPE := PA_avt_max_ponto06_IN;
PA_ds_habilidade07  s_avaliacao_turma.ds_habilidade%TYPE := PA_ds_habilidade07_IN;
PA_avt_bateria08    s_avaliacao_turma.avt_bateria%TYPE := PA_avt_bateria08_IN;
PN_av_sequencial00  s_avaliacao_turma.av_sequencial%TYPE := PN_av_sequencial00_IN;
PN_co_unidade01     s_avaliacao_turma.co_unidade%TYPE := PN_co_unidade01_IN;
PN_dt_avaliacao02   s_avaliacao_turma.dt_avaliacao%TYPE := PN_dt_avaliacao02_IN;
PN_co_avaliacao03   s_avaliacao_turma.co_avaliacao%TYPE := PN_co_avaliacao03_IN;
PN_obs_avaliacao04  s_avaliacao_turma.obs_avaliacao%TYPE := PN_obs_avaliacao04_IN;
PN_co_tipo_avali05  s_avaliacao_turma.co_tipo_avaliacao%TYPE := PN_co_tipo_avali05_IN;
PN_avt_max_ponto06  s_avaliacao_turma.avt_max_pontos%TYPE := PN_avt_max_ponto06_IN;
PN_ds_habilidade07  s_avaliacao_turma.ds_habilidade%TYPE := PN_ds_habilidade07_IN;
PN_avt_bateria08    s_avaliacao_turma.avt_bateria%TYPE := PN_avt_bateria08_IN;
v_blob1             s_avaliacao_turma.obs_avaliacao%TYPE;
v_blob2             s_avaliacao_turma.ds_habilidade%TYPE;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_av_sequencial00  CHAR(10);
vr_co_unidade01     CHAR(10);
vr_dt_avaliacao02   CHAR(40);
vr_co_avaliacao03   CHAR(10);
vr_obs_avaliacao04  CHAR(10);
vr_co_tipo_avali05  CHAR(10);
vr_avt_max_ponto06  CHAR(10);
vr_ds_habilidade07  CHAR(10);
vr_avt_bateria08    CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	v_blob1 := NULL;
	v_blob2 := NULL;
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
		IF pn_dt_avaliacao02 IS NULL THEN
			vr_dt_avaliacao02 := 'null';
		ELSE
			vr_dt_avaliacao02 := pn_dt_avaliacao02;
		END IF;
		IF pn_co_avaliacao03 IS NULL THEN
			vr_co_avaliacao03 := 'null';
		ELSE
			vr_co_avaliacao03 := pn_co_avaliacao03;
		END IF;
		IF pn_obs_avaliacao04 IS NULL THEN
			vr_obs_avaliacao04 := NULL;
		ELSE
			vr_obs_avaliacao04 := ':vblob1';
		END IF;
		v_blob1 := pn_obs_avaliacao04;
		IF pn_co_tipo_avali05 IS NULL THEN
			vr_co_tipo_avali05 := 'null';
		ELSE
			vr_co_tipo_avali05 := pn_co_tipo_avali05;
		END IF;
		IF pn_avt_max_ponto06 IS NULL THEN
			vr_avt_max_ponto06 := 'null';
		ELSE
			vr_avt_max_ponto06 := pn_avt_max_ponto06;
		END IF;
		IF pn_ds_habilidade07 IS NULL THEN
			vr_ds_habilidade07 := NULL;
		ELSE
			vr_ds_habilidade07 := ':vblob2';
		END IF;
		v_blob2 := pn_ds_habilidade07;
		IF pn_avt_bateria08 IS NULL THEN
			vr_avt_bateria08 := 'null';
		ELSE
			vr_avt_bateria08 := pn_avt_bateria08;
		END IF;
		v_sql1 := 'insert into s_avaliacao_turma(av_sequencial, co_unidade, dt_avaliacao, co_avaliacao, obs_avaliacao, co_tipo_avaliacao, avt_max_pontos, ds_habilidade, avt_bateria) values (';
		v_sql2 := RTRIM(vr_av_sequencial00) || ',' || '"' || RTRIM(vr_co_unidade01) || '"' || ',' || '"' || vr_dt_avaliacao02 || '"' || ',' || RTRIM(vr_co_avaliacao03) || ',' || RTRIM(vr_obs_avaliacao04) || ',' || RTRIM(vr_co_tipo_avali05) || ',' || '"';
		v_sql3 := RTRIM(vr_avt_max_ponto06) || '"' || ',' || RTRIM(vr_ds_habilidade07) || ',' || RTRIM(vr_avt_bateria08) || ');';
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
		IF pa_dt_avaliacao02 IS NULL THEN
			vr_dt_avaliacao02 := 'null';
		ELSE
			vr_dt_avaliacao02 := '"' || pa_dt_avaliacao02 || '"';
		END IF;
		IF pa_co_avaliacao03 IS NULL THEN
			vr_co_avaliacao03 := 'null';
		ELSE
			vr_co_avaliacao03 := pa_co_avaliacao03;
		END IF;
		IF pa_co_tipo_avali05 IS NULL THEN
			vr_co_tipo_avali05 := 'null';
		ELSE
			vr_co_tipo_avali05 := pa_co_tipo_avali05;
		END IF;
		v_sql1 := '  delete from s_avaliacao_turma where av_sequencial = ' || RTRIM(vr_av_sequencial00) || '  and co_unidade = ' || RTRIM(vr_co_unidade01) || '  and dt_avaliacao = ' || RTRIM(vr_dt_avaliacao02);
		v_sql2 := '  and co_avaliacao = ' || RTRIM(vr_co_avaliacao03) || '  and co_tipo_avaliacao = ' || RTRIM(vr_co_tipo_avali05) || ';';
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
		IF pn_dt_avaliacao02 IS NULL
		AND pa_dt_avaliacao02 IS NULL THEN
			vr_dt_avaliacao02 := 'null';
		END IF;
		IF pn_dt_avaliacao02 IS NULL
		AND pa_dt_avaliacao02 IS NOT NULL THEN
			vr_dt_avaliacao02 := 'null';
		END IF;
		IF pn_dt_avaliacao02 IS NOT NULL
		AND pa_dt_avaliacao02 IS NULL THEN
			vr_dt_avaliacao02 := '"' || pn_dt_avaliacao02 || '"';
		END IF;
		IF pn_dt_avaliacao02 IS NOT NULL
		AND pa_dt_avaliacao02 IS NOT NULL THEN
			IF pa_dt_avaliacao02 <> pn_dt_avaliacao02 THEN
				vr_dt_avaliacao02 := '"' || pn_dt_avaliacao02 || '"';
			ELSE
				vr_dt_avaliacao02 := '"' || pa_dt_avaliacao02 || '"';
			END IF;
		END IF;
		IF pn_co_avaliacao03 IS NULL
		AND pa_co_avaliacao03 IS NULL THEN
			vr_co_avaliacao03 := 'null';
		END IF;
		IF pn_co_avaliacao03 IS NULL
		AND pa_co_avaliacao03 IS NOT NULL THEN
			vr_co_avaliacao03 := 'null';
		END IF;
		IF pn_co_avaliacao03 IS NOT NULL
		AND pa_co_avaliacao03 IS NULL THEN
			vr_co_avaliacao03 := pn_co_avaliacao03;
		END IF;
		IF pn_co_avaliacao03 IS NOT NULL
		AND pa_co_avaliacao03 IS NOT NULL THEN
			IF pa_co_avaliacao03 <> pn_co_avaliacao03 THEN
				vr_co_avaliacao03 := pn_co_avaliacao03;
			ELSE
				vr_co_avaliacao03 := pa_co_avaliacao03;
			END IF;
		END IF;
		IF pn_obs_avaliacao04 IS NULL THEN
			vr_obs_avaliacao04 := NULL;
		ELSE
			vr_obs_avaliacao04 := ':vblob2';
		END IF;
		v_blob2 := pn_obs_avaliacao04;
		IF pn_co_tipo_avali05 IS NULL
		AND pa_co_tipo_avali05 IS NULL THEN
			vr_co_tipo_avali05 := 'null';
		END IF;
		IF pn_co_tipo_avali05 IS NULL
		AND pa_co_tipo_avali05 IS NOT NULL THEN
			vr_co_tipo_avali05 := 'null';
		END IF;
		IF pn_co_tipo_avali05 IS NOT NULL
		AND pa_co_tipo_avali05 IS NULL THEN
			vr_co_tipo_avali05 := pn_co_tipo_avali05;
		END IF;
		IF pn_co_tipo_avali05 IS NOT NULL
		AND pa_co_tipo_avali05 IS NOT NULL THEN
			IF pa_co_tipo_avali05 <> pn_co_tipo_avali05 THEN
				vr_co_tipo_avali05 := pn_co_tipo_avali05;
			ELSE
				vr_co_tipo_avali05 := pa_co_tipo_avali05;
			END IF;
		END IF;
		IF pn_avt_max_ponto06 IS NULL
		AND pa_avt_max_ponto06 IS NULL THEN
			vr_avt_max_ponto06 := 'null';
		END IF;
		IF pn_avt_max_ponto06 IS NULL
		AND pa_avt_max_ponto06 IS NOT NULL THEN
			vr_avt_max_ponto06 := 'null';
		END IF;
		IF pn_avt_max_ponto06 IS NOT NULL
		AND pa_avt_max_ponto06 IS NULL THEN
			vr_avt_max_ponto06 := '"' || RTRIM(pn_avt_max_ponto06) || '"';
		END IF;
		IF pn_avt_max_ponto06 IS NOT NULL
		AND pa_avt_max_ponto06 IS NOT NULL THEN
			IF pa_avt_max_ponto06 <> pn_avt_max_ponto06 THEN
				vr_avt_max_ponto06 := '"' || RTRIM(pn_avt_max_ponto06) || '"';
			ELSE
				vr_avt_max_ponto06 := '"' || RTRIM(pa_avt_max_ponto06) || '"';
			END IF;
		END IF;
		IF pn_ds_habilidade07 IS NULL THEN
			vr_ds_habilidade07 := NULL;
		ELSE
			vr_ds_habilidade07 := ':vblob2';
		END IF;
		v_blob2 := pn_ds_habilidade07;
		IF pn_avt_bateria08 IS NULL
		AND pa_avt_bateria08 IS NULL THEN
			vr_avt_bateria08 := 'null';
		END IF;
		IF pn_avt_bateria08 IS NULL
		AND pa_avt_bateria08 IS NOT NULL THEN
			vr_avt_bateria08 := 'null';
		END IF;
		IF pn_avt_bateria08 IS NOT NULL
		AND pa_avt_bateria08 IS NULL THEN
			vr_avt_bateria08 := pn_avt_bateria08;
		END IF;
		IF pn_avt_bateria08 IS NOT NULL
		AND pa_avt_bateria08 IS NOT NULL THEN
			IF pa_avt_bateria08 <> pn_avt_bateria08 THEN
				vr_avt_bateria08 := pn_avt_bateria08;
			ELSE
				vr_avt_bateria08 := pa_avt_bateria08;
			END IF;
		END IF;
		v_sql1 := 'update s_avaliacao_turma set av_sequencial = ' || RTRIM(vr_av_sequencial00) || '  , co_unidade = ' || RTRIM(vr_co_unidade01) || '  , dt_avaliacao = ' || RTRIM(vr_dt_avaliacao02) || '  , co_avaliacao = ' || RTRIM(vr_co_avaliacao03) || '  , obs_avaliacao = ' || RTRIM(vr_obs_avaliacao04);
		v_sql2 := '  , co_tipo_avaliacao = ' || RTRIM(vr_co_tipo_avali05) || '  , avt_max_pontos = ' || RTRIM(vr_avt_max_ponto06) || '  , ds_habilidade = ' || RTRIM(vr_ds_habilidade07) || '  , avt_bateria = ' || RTRIM(vr_avt_bateria08);
		v_sql3 := ' where av_sequencial = ' || RTRIM(vr_av_sequencial00) || '  and co_unidade = ' || RTRIM(vr_co_unidade01) || '  and dt_avaliacao = ' || RTRIM(vr_dt_avaliacao02) || '  and co_avaliacao = ' || RTRIM(vr_co_avaliacao03) || '  and co_tipo_avaliacao = ' || RTRIM(vr_co_tipo_avali05) || ';';
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
		       's_avaliacao_turma',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       v_blob1,
		       v_blob2);
	END IF;
END pr_s_avaliaca052;
/

