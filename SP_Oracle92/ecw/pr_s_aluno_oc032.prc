CREATE OR REPLACE PROCEDURE pr_s_aluno_oc032(
	P_OP_IN                CHAR,
	PA_co_unidade00_IN     s_aluno_ocorrencia.co_unidade%TYPE,
	PA_co_ocorrencia01_IN  s_aluno_ocorrencia.co_ocorrencia%TYPE,
	PA_ds_ocorrencia02_IN  s_aluno_ocorrencia.ds_ocorrencia%TYPE,
	PA_ano_sem03_IN        s_aluno_ocorrencia.ano_sem%TYPE,
	PA_co_aluno04_IN       s_aluno_ocorrencia.co_aluno%TYPE,
	PA_ho_ocorrencia05_IN  s_aluno_ocorrencia.ho_ocorrencia%TYPE,
	PA_co_tipo_ocorr06_IN  s_aluno_ocorrencia.co_tipo_ocorrencia%TYPE,
	PA_st_recado07_IN      s_aluno_ocorrencia.st_recado%TYPE,
	PA_st_recado_dad08_IN  s_aluno_ocorrencia.st_recado_dado%TYPE,
	PA_ds_usuario_re09_IN  s_aluno_ocorrencia.ds_usuario_recado%TYPE,
	PA_dt_ocorrencia10_IN  s_aluno_ocorrencia.dt_ocorrencia%TYPE,
	PN_co_unidade00_IN     s_aluno_ocorrencia.co_unidade%TYPE,
	PN_co_ocorrencia01_IN  s_aluno_ocorrencia.co_ocorrencia%TYPE,
	PN_ds_ocorrencia02_IN  s_aluno_ocorrencia.ds_ocorrencia%TYPE,
	PN_ano_sem03_IN        s_aluno_ocorrencia.ano_sem%TYPE,
	PN_co_aluno04_IN       s_aluno_ocorrencia.co_aluno%TYPE,
	PN_ho_ocorrencia05_IN  s_aluno_ocorrencia.ho_ocorrencia%TYPE,
	PN_co_tipo_ocorr06_IN  s_aluno_ocorrencia.co_tipo_ocorrencia%TYPE,
	PN_st_recado07_IN      s_aluno_ocorrencia.st_recado%TYPE,
	PN_st_recado_dad08_IN  s_aluno_ocorrencia.st_recado_dado%TYPE,
	PN_ds_usuario_re09_IN  s_aluno_ocorrencia.ds_usuario_recado%TYPE,
	PN_dt_ocorrencia10_IN  s_aluno_ocorrencia.dt_ocorrencia%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_unidade00     s_aluno_ocorrencia.co_unidade%TYPE := PA_co_unidade00_IN;
PA_co_ocorrencia01  s_aluno_ocorrencia.co_ocorrencia%TYPE := PA_co_ocorrencia01_IN;
PA_ds_ocorrencia02  s_aluno_ocorrencia.ds_ocorrencia%TYPE := PA_ds_ocorrencia02_IN;
PA_ano_sem03        s_aluno_ocorrencia.ano_sem%TYPE := PA_ano_sem03_IN;
PA_co_aluno04       s_aluno_ocorrencia.co_aluno%TYPE := PA_co_aluno04_IN;
PA_ho_ocorrencia05  s_aluno_ocorrencia.ho_ocorrencia%TYPE := PA_ho_ocorrencia05_IN;
PA_co_tipo_ocorr06  s_aluno_ocorrencia.co_tipo_ocorrencia%TYPE := PA_co_tipo_ocorr06_IN;
PA_st_recado07      s_aluno_ocorrencia.st_recado%TYPE := PA_st_recado07_IN;
PA_st_recado_dad08  s_aluno_ocorrencia.st_recado_dado%TYPE := PA_st_recado_dad08_IN;
PA_ds_usuario_re09  s_aluno_ocorrencia.ds_usuario_recado%TYPE := PA_ds_usuario_re09_IN;
PA_dt_ocorrencia10  s_aluno_ocorrencia.dt_ocorrencia%TYPE := PA_dt_ocorrencia10_IN;
PN_co_unidade00     s_aluno_ocorrencia.co_unidade%TYPE := PN_co_unidade00_IN;
PN_co_ocorrencia01  s_aluno_ocorrencia.co_ocorrencia%TYPE := PN_co_ocorrencia01_IN;
PN_ds_ocorrencia02  s_aluno_ocorrencia.ds_ocorrencia%TYPE := PN_ds_ocorrencia02_IN;
PN_ano_sem03        s_aluno_ocorrencia.ano_sem%TYPE := PN_ano_sem03_IN;
PN_co_aluno04       s_aluno_ocorrencia.co_aluno%TYPE := PN_co_aluno04_IN;
PN_ho_ocorrencia05  s_aluno_ocorrencia.ho_ocorrencia%TYPE := PN_ho_ocorrencia05_IN;
PN_co_tipo_ocorr06  s_aluno_ocorrencia.co_tipo_ocorrencia%TYPE := PN_co_tipo_ocorr06_IN;
PN_st_recado07      s_aluno_ocorrencia.st_recado%TYPE := PN_st_recado07_IN;
PN_st_recado_dad08  s_aluno_ocorrencia.st_recado_dado%TYPE := PN_st_recado_dad08_IN;
PN_ds_usuario_re09  s_aluno_ocorrencia.ds_usuario_recado%TYPE := PN_ds_usuario_re09_IN;
PN_dt_ocorrencia10  s_aluno_ocorrencia.dt_ocorrencia%TYPE := PN_dt_ocorrencia10_IN;
v_blob1             s_aluno_ocorrencia.ds_ocorrencia%TYPE;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_unidade00     CHAR(10);
vr_co_ocorrencia01  CHAR(10);
vr_ds_ocorrencia02  CHAR(10);
vr_ano_sem03        CHAR(10);
vr_co_aluno04       CHAR(20);
vr_ho_ocorrencia05  CHAR(10);
vr_co_tipo_ocorr06  CHAR(10);
vr_st_recado07      CHAR(40);
vr_st_recado_dad08  CHAR(40);
vr_ds_usuario_re09  CHAR(30);
vr_dt_ocorrencia10  CHAR(40);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	v_blob1 := NULL;
	IF p_op = 'ins' THEN
		IF pn_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := pn_co_unidade00;
		END IF;
		IF pn_co_ocorrencia01 IS NULL THEN
			vr_co_ocorrencia01 := 'null';
		ELSE
			vr_co_ocorrencia01 := pn_co_ocorrencia01;
		END IF;
		IF pn_ds_ocorrencia02 IS NULL THEN
			vr_ds_ocorrencia02 := NULL;
		ELSE
			vr_ds_ocorrencia02 := ':vblob1';
		END IF;
		v_blob1 := pn_ds_ocorrencia02;
		IF pn_ano_sem03 IS NULL THEN
			vr_ano_sem03 := 'null';
		ELSE
			vr_ano_sem03 := pn_ano_sem03;
		END IF;
		IF pn_co_aluno04 IS NULL THEN
			vr_co_aluno04 := 'null';
		ELSE
			vr_co_aluno04 := pn_co_aluno04;
		END IF;
		IF pn_ho_ocorrencia05 IS NULL THEN
			vr_ho_ocorrencia05 := 'null';
		ELSE
			vr_ho_ocorrencia05 := pn_ho_ocorrencia05;
		END IF;
		IF pn_co_tipo_ocorr06 IS NULL THEN
			vr_co_tipo_ocorr06 := 'null';
		ELSE
			vr_co_tipo_ocorr06 := pn_co_tipo_ocorr06;
		END IF;
		IF pn_st_recado07 IS NULL THEN
			vr_st_recado07 := 'null';
		ELSE
			vr_st_recado07 := pn_st_recado07;
		END IF;
		IF pn_st_recado_dad08 IS NULL THEN
			vr_st_recado_dad08 := 'null';
		ELSE
			vr_st_recado_dad08 := pn_st_recado_dad08;
		END IF;
		IF pn_ds_usuario_re09 IS NULL THEN
			vr_ds_usuario_re09 := 'null';
		ELSE
			vr_ds_usuario_re09 := pn_ds_usuario_re09;
		END IF;
		IF pn_dt_ocorrencia10 IS NULL THEN
			vr_dt_ocorrencia10 := 'null';
		ELSE
			vr_dt_ocorrencia10 := pn_dt_ocorrencia10;
		END IF;
		v_sql1 := 'insert into s_aluno_ocorrencia(co_unidade, co_ocorrencia, ds_ocorrencia, ano_sem, co_aluno, ho_ocorrencia, co_tipo_ocorrencia, st_recado, st_recado_dado, ds_usuario_recado, dt_ocorrencia) values (';
		v_sql2 := '"' || RTRIM(vr_co_unidade00) || '"' || ',' || RTRIM(vr_co_ocorrencia01) || ',' || RTRIM(vr_ds_ocorrencia02) || ',' || '"' || RTRIM(vr_ano_sem03) || '"' || ',' || '"' || RTRIM(vr_co_aluno04) || '"' || ',' || '"' || RTRIM(vr_ho_ocorrencia05) || '"' || ',';
		v_sql3 := RTRIM(vr_co_tipo_ocorr06) || ',' || '"' || RTRIM(vr_st_recado07) || '"' || ',' || '"' || RTRIM(vr_st_recado_dad08) || '"' || ',' || '"' || RTRIM(vr_ds_usuario_re09) || '"' || ',' || '"' || vr_dt_ocorrencia10 || '"' || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := '"' || RTRIM(pa_co_unidade00) || '"';
		END IF;
		IF pa_co_ocorrencia01 IS NULL THEN
			vr_co_ocorrencia01 := 'null';
		ELSE
			vr_co_ocorrencia01 := pa_co_ocorrencia01;
		END IF;
		IF pa_ano_sem03 IS NULL THEN
			vr_ano_sem03 := 'null';
		ELSE
			vr_ano_sem03 := '"' || RTRIM(pa_ano_sem03) || '"';
		END IF;
		IF pa_co_aluno04 IS NULL THEN
			vr_co_aluno04 := 'null';
		ELSE
			vr_co_aluno04 := '"' || RTRIM(pa_co_aluno04) || '"';
		END IF;
		v_sql1 := '  delete from s_aluno_ocorrencia where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and co_ocorrencia = ' || RTRIM(vr_co_ocorrencia01) || '  and ano_sem = ' || RTRIM(vr_ano_sem03) || '  and co_aluno = ' || RTRIM(vr_co_aluno04) || ';';
		v_sql2 := '';
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
		IF pn_co_ocorrencia01 IS NULL
		AND pa_co_ocorrencia01 IS NULL THEN
			vr_co_ocorrencia01 := 'null';
		END IF;
		IF pn_co_ocorrencia01 IS NULL
		AND pa_co_ocorrencia01 IS NOT NULL THEN
			vr_co_ocorrencia01 := 'null';
		END IF;
		IF pn_co_ocorrencia01 IS NOT NULL
		AND pa_co_ocorrencia01 IS NULL THEN
			vr_co_ocorrencia01 := pn_co_ocorrencia01;
		END IF;
		IF pn_co_ocorrencia01 IS NOT NULL
		AND pa_co_ocorrencia01 IS NOT NULL THEN
			IF pa_co_ocorrencia01 <> pn_co_ocorrencia01 THEN
				vr_co_ocorrencia01 := pn_co_ocorrencia01;
			ELSE
				vr_co_ocorrencia01 := pa_co_ocorrencia01;
			END IF;
		END IF;
		IF pn_ds_ocorrencia02 IS NULL THEN
			vr_ds_ocorrencia02 := NULL;
		ELSE
			vr_ds_ocorrencia02 := ':vblob1';
		END IF;
		v_blob1 := pn_ds_ocorrencia02;
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
		IF pn_ho_ocorrencia05 IS NULL
		AND pa_ho_ocorrencia05 IS NULL THEN
			vr_ho_ocorrencia05 := 'null';
		END IF;
		IF pn_ho_ocorrencia05 IS NULL
		AND pa_ho_ocorrencia05 IS NOT NULL THEN
			vr_ho_ocorrencia05 := 'null';
		END IF;
		IF pn_ho_ocorrencia05 IS NOT NULL
		AND pa_ho_ocorrencia05 IS NULL THEN
			vr_ho_ocorrencia05 := '"' || RTRIM(pn_ho_ocorrencia05) || '"';
		END IF;
		IF pn_ho_ocorrencia05 IS NOT NULL
		AND pa_ho_ocorrencia05 IS NOT NULL THEN
			IF pa_ho_ocorrencia05 <> pn_ho_ocorrencia05 THEN
				vr_ho_ocorrencia05 := '"' || RTRIM(pn_ho_ocorrencia05) || '"';
			ELSE
				vr_ho_ocorrencia05 := '"' || RTRIM(pa_ho_ocorrencia05) || '"';
			END IF;
		END IF;
		IF pn_co_tipo_ocorr06 IS NULL
		AND pa_co_tipo_ocorr06 IS NULL THEN
			vr_co_tipo_ocorr06 := 'null';
		END IF;
		IF pn_co_tipo_ocorr06 IS NULL
		AND pa_co_tipo_ocorr06 IS NOT NULL THEN
			vr_co_tipo_ocorr06 := 'null';
		END IF;
		IF pn_co_tipo_ocorr06 IS NOT NULL
		AND pa_co_tipo_ocorr06 IS NULL THEN
			vr_co_tipo_ocorr06 := pn_co_tipo_ocorr06;
		END IF;
		IF pn_co_tipo_ocorr06 IS NOT NULL
		AND pa_co_tipo_ocorr06 IS NOT NULL THEN
			IF pa_co_tipo_ocorr06 <> pn_co_tipo_ocorr06 THEN
				vr_co_tipo_ocorr06 := pn_co_tipo_ocorr06;
			ELSE
				vr_co_tipo_ocorr06 := pa_co_tipo_ocorr06;
			END IF;
		END IF;
		IF pn_st_recado07 IS NULL
		AND pa_st_recado07 IS NULL THEN
			vr_st_recado07 := 'null';
		END IF;
		IF pn_st_recado07 IS NULL
		AND pa_st_recado07 IS NOT NULL THEN
			vr_st_recado07 := 'null';
		END IF;
		IF pn_st_recado07 IS NOT NULL
		AND pa_st_recado07 IS NULL THEN
			vr_st_recado07 := '"' || RTRIM(pn_st_recado07) || '"';
		END IF;
		IF pn_st_recado07 IS NOT NULL
		AND pa_st_recado07 IS NOT NULL THEN
			IF pa_st_recado07 <> pn_st_recado07 THEN
				vr_st_recado07 := '"' || RTRIM(pn_st_recado07) || '"';
			ELSE
				vr_st_recado07 := '"' || RTRIM(pa_st_recado07) || '"';
			END IF;
		END IF;
		IF pn_st_recado_dad08 IS NULL
		AND pa_st_recado_dad08 IS NULL THEN
			vr_st_recado_dad08 := 'null';
		END IF;
		IF pn_st_recado_dad08 IS NULL
		AND pa_st_recado_dad08 IS NOT NULL THEN
			vr_st_recado_dad08 := 'null';
		END IF;
		IF pn_st_recado_dad08 IS NOT NULL
		AND pa_st_recado_dad08 IS NULL THEN
			vr_st_recado_dad08 := '"' || RTRIM(pn_st_recado_dad08) || '"';
		END IF;
		IF pn_st_recado_dad08 IS NOT NULL
		AND pa_st_recado_dad08 IS NOT NULL THEN
			IF pa_st_recado_dad08 <> pn_st_recado_dad08 THEN
				vr_st_recado_dad08 := '"' || RTRIM(pn_st_recado_dad08) || '"';
			ELSE
				vr_st_recado_dad08 := '"' || RTRIM(pa_st_recado_dad08) || '"';
			END IF;
		END IF;
		IF pn_ds_usuario_re09 IS NULL
		AND pa_ds_usuario_re09 IS NULL THEN
			vr_ds_usuario_re09 := 'null';
		END IF;
		IF pn_ds_usuario_re09 IS NULL
		AND pa_ds_usuario_re09 IS NOT NULL THEN
			vr_ds_usuario_re09 := 'null';
		END IF;
		IF pn_ds_usuario_re09 IS NOT NULL
		AND pa_ds_usuario_re09 IS NULL THEN
			vr_ds_usuario_re09 := '"' || RTRIM(pn_ds_usuario_re09) || '"';
		END IF;
		IF pn_ds_usuario_re09 IS NOT NULL
		AND pa_ds_usuario_re09 IS NOT NULL THEN
			IF pa_ds_usuario_re09 <> pn_ds_usuario_re09 THEN
				vr_ds_usuario_re09 := '"' || RTRIM(pn_ds_usuario_re09) || '"';
			ELSE
				vr_ds_usuario_re09 := '"' || RTRIM(pa_ds_usuario_re09) || '"';
			END IF;
		END IF;
		IF pn_dt_ocorrencia10 IS NULL
		AND pa_dt_ocorrencia10 IS NULL THEN
			vr_dt_ocorrencia10 := 'null';
		END IF;
		IF pn_dt_ocorrencia10 IS NULL
		AND pa_dt_ocorrencia10 IS NOT NULL THEN
			vr_dt_ocorrencia10 := 'null';
		END IF;
		IF pn_dt_ocorrencia10 IS NOT NULL
		AND pa_dt_ocorrencia10 IS NULL THEN
			vr_dt_ocorrencia10 := '"' || pn_dt_ocorrencia10 || '"';
		END IF;
		IF pn_dt_ocorrencia10 IS NOT NULL
		AND pa_dt_ocorrencia10 IS NOT NULL THEN
			IF pa_dt_ocorrencia10 <> pn_dt_ocorrencia10 THEN
				vr_dt_ocorrencia10 := '"' || pn_dt_ocorrencia10 || '"';
			ELSE
				vr_dt_ocorrencia10 := '"' || pa_dt_ocorrencia10 || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_aluno_ocorrencia set co_unidade = ' || RTRIM(vr_co_unidade00) || '  , co_ocorrencia = ' || RTRIM(vr_co_ocorrencia01) || '  , ds_ocorrencia = ' || RTRIM(vr_ds_ocorrencia02) || '  , ano_sem = ' || RTRIM(vr_ano_sem03) || '  , co_aluno = ' || RTRIM(vr_co_aluno04);
		v_sql2 := '  , ho_ocorrencia = ' || RTRIM(vr_ho_ocorrencia05) || '  , co_tipo_ocorrencia = ' || RTRIM(vr_co_tipo_ocorr06) || '  , st_recado = ' || RTRIM(vr_st_recado07) || '  , st_recado_dado = ' || RTRIM(vr_st_recado_dad08) || '  , ds_usuario_recado = ' || RTRIM(vr_ds_usuario_re09) || '  , dt_ocorrencia = ' || RTRIM(vr_dt_ocorrencia10);
		v_sql3 := ' where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and co_ocorrencia = ' || RTRIM(vr_co_ocorrencia01) || '  and ano_sem = ' || RTRIM(vr_ano_sem03) || '  and co_aluno = ' || RTRIM(vr_co_aluno04) || ';';
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
		       's_aluno_ocorrencia',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       v_blob1,
		       NULL);
	END IF;
END pr_s_aluno_oc032;
/

