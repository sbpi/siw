CREATE OR REPLACE PROCEDURE pr_s_funciona102(
	P_OP_IN                CHAR,
	PA_co_unidade00_IN     s_funcionario_ocor.co_unidade%TYPE,
	PA_co_ocorrencia01_IN  s_funcionario_ocor.co_ocorrencia%TYPE,
	PA_co_funcionari02_IN  s_funcionario_ocor.co_funcionario%TYPE,
	PA_ano_sem03_IN        s_funcionario_ocor.ano_sem%TYPE,
	PA_co_tipo_ocorr04_IN  s_funcionario_ocor.co_tipo_ocorrencia%TYPE,
	PA_dt_ocorrencia05_IN  s_funcionario_ocor.dt_ocorrencia%TYPE,
	PA_ds_ocorrencia06_IN  s_funcionario_ocor.ds_ocorrencia%TYPE,
	PA_ho_ocorrencia07_IN  s_funcionario_ocor.ho_ocorrencia%TYPE,
	PA_st_recado08_IN      s_funcionario_ocor.st_recado%TYPE,
	PA_st_recado_dad09_IN  s_funcionario_ocor.st_recado_dado%TYPE,
	PA_ds_usuario_re10_IN  s_funcionario_ocor.ds_usuario_recado%TYPE,
	PN_co_unidade00_IN     s_funcionario_ocor.co_unidade%TYPE,
	PN_co_ocorrencia01_IN  s_funcionario_ocor.co_ocorrencia%TYPE,
	PN_co_funcionari02_IN  s_funcionario_ocor.co_funcionario%TYPE,
	PN_ano_sem03_IN        s_funcionario_ocor.ano_sem%TYPE,
	PN_co_tipo_ocorr04_IN  s_funcionario_ocor.co_tipo_ocorrencia%TYPE,
	PN_dt_ocorrencia05_IN  s_funcionario_ocor.dt_ocorrencia%TYPE,
	PN_ds_ocorrencia06_IN  s_funcionario_ocor.ds_ocorrencia%TYPE,
	PN_ho_ocorrencia07_IN  s_funcionario_ocor.ho_ocorrencia%TYPE,
	PN_st_recado08_IN      s_funcionario_ocor.st_recado%TYPE,
	PN_st_recado_dad09_IN  s_funcionario_ocor.st_recado_dado%TYPE,
	PN_ds_usuario_re10_IN  s_funcionario_ocor.ds_usuario_recado%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_unidade00     s_funcionario_ocor.co_unidade%TYPE := PA_co_unidade00_IN;
PA_co_ocorrencia01  s_funcionario_ocor.co_ocorrencia%TYPE := PA_co_ocorrencia01_IN;
PA_co_funcionari02  s_funcionario_ocor.co_funcionario%TYPE := PA_co_funcionari02_IN;
PA_ano_sem03        s_funcionario_ocor.ano_sem%TYPE := PA_ano_sem03_IN;
PA_co_tipo_ocorr04  s_funcionario_ocor.co_tipo_ocorrencia%TYPE := PA_co_tipo_ocorr04_IN;
PA_dt_ocorrencia05  s_funcionario_ocor.dt_ocorrencia%TYPE := PA_dt_ocorrencia05_IN;
PA_ds_ocorrencia06  s_funcionario_ocor.ds_ocorrencia%TYPE := PA_ds_ocorrencia06_IN;
PA_ho_ocorrencia07  s_funcionario_ocor.ho_ocorrencia%TYPE := PA_ho_ocorrencia07_IN;
PA_st_recado08      s_funcionario_ocor.st_recado%TYPE := PA_st_recado08_IN;
PA_st_recado_dad09  s_funcionario_ocor.st_recado_dado%TYPE := PA_st_recado_dad09_IN;
PA_ds_usuario_re10  s_funcionario_ocor.ds_usuario_recado%TYPE := PA_ds_usuario_re10_IN;
PN_co_unidade00     s_funcionario_ocor.co_unidade%TYPE := PN_co_unidade00_IN;
PN_co_ocorrencia01  s_funcionario_ocor.co_ocorrencia%TYPE := PN_co_ocorrencia01_IN;
PN_co_funcionari02  s_funcionario_ocor.co_funcionario%TYPE := PN_co_funcionari02_IN;
PN_ano_sem03        s_funcionario_ocor.ano_sem%TYPE := PN_ano_sem03_IN;
PN_co_tipo_ocorr04  s_funcionario_ocor.co_tipo_ocorrencia%TYPE := PN_co_tipo_ocorr04_IN;
PN_dt_ocorrencia05  s_funcionario_ocor.dt_ocorrencia%TYPE := PN_dt_ocorrencia05_IN;
PN_ds_ocorrencia06  s_funcionario_ocor.ds_ocorrencia%TYPE := PN_ds_ocorrencia06_IN;
PN_ho_ocorrencia07  s_funcionario_ocor.ho_ocorrencia%TYPE := PN_ho_ocorrencia07_IN;
PN_st_recado08      s_funcionario_ocor.st_recado%TYPE := PN_st_recado08_IN;
PN_st_recado_dad09  s_funcionario_ocor.st_recado_dado%TYPE := PN_st_recado_dad09_IN;
PN_ds_usuario_re10  s_funcionario_ocor.ds_usuario_recado%TYPE := PN_ds_usuario_re10_IN;
v_blob1             s_funcionario_ocor.ds_ocorrencia%TYPE;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_unidade00     CHAR(10);
vr_co_ocorrencia01  CHAR(10);
vr_co_funcionari02  CHAR(20);
vr_ano_sem03        CHAR(10);
vr_co_tipo_ocorr04  CHAR(10);
vr_dt_ocorrencia05  CHAR(40);
vr_ds_ocorrencia06  CHAR(10);
vr_ho_ocorrencia07  CHAR(10);
vr_st_recado08      CHAR(40);
vr_st_recado_dad09  CHAR(40);
vr_ds_usuario_re10  CHAR(30);
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
		IF pn_co_funcionari02 IS NULL THEN
			vr_co_funcionari02 := 'null';
		ELSE
			vr_co_funcionari02 := pn_co_funcionari02;
		END IF;
		IF pn_ano_sem03 IS NULL THEN
			vr_ano_sem03 := 'null';
		ELSE
			vr_ano_sem03 := pn_ano_sem03;
		END IF;
		IF pn_co_tipo_ocorr04 IS NULL THEN
			vr_co_tipo_ocorr04 := 'null';
		ELSE
			vr_co_tipo_ocorr04 := pn_co_tipo_ocorr04;
		END IF;
		IF pn_dt_ocorrencia05 IS NULL THEN
			vr_dt_ocorrencia05 := 'null';
		ELSE
			vr_dt_ocorrencia05 := pn_dt_ocorrencia05;
		END IF;
		IF pn_ds_ocorrencia06 IS NULL THEN
			vr_ds_ocorrencia06 := NULL;
		ELSE
			vr_ds_ocorrencia06 := ':vblob1';
		END IF;
		v_blob1 := pn_ds_ocorrencia06;
		IF pn_ho_ocorrencia07 IS NULL THEN
			vr_ho_ocorrencia07 := 'null';
		ELSE
			vr_ho_ocorrencia07 := pn_ho_ocorrencia07;
		END IF;
		IF pn_st_recado08 IS NULL THEN
			vr_st_recado08 := 'null';
		ELSE
			vr_st_recado08 := pn_st_recado08;
		END IF;
		IF pn_st_recado_dad09 IS NULL THEN
			vr_st_recado_dad09 := 'null';
		ELSE
			vr_st_recado_dad09 := pn_st_recado_dad09;
		END IF;
		IF pn_ds_usuario_re10 IS NULL THEN
			vr_ds_usuario_re10 := 'null';
		ELSE
			vr_ds_usuario_re10 := pn_ds_usuario_re10;
		END IF;
		v_sql1 := 'insert into S_FUNCIONARIO_OCORRENCIA(co_unidade, co_ocorrencia, co_funcionario, ano_sem, co_tipo_ocorrencia, dt_ocorrencia, ds_ocorrencia, ho_ocorrencia, st_recado, st_recado_dado, ds_usuario_recado) values (';
		v_sql2 := '"' || RTRIM(vr_co_unidade00) || '"' || ',' || RTRIM(vr_co_ocorrencia01) || ',' || '"' || RTRIM(vr_co_funcionari02) || '"' || ',' || '"' || RTRIM(vr_ano_sem03) || '"' || ',' || RTRIM(vr_co_tipo_ocorr04) || ',' || '"' || vr_dt_ocorrencia05 || '"' || ',' || RTRIM(vr_ds_ocorrencia06) || ',';
		v_sql3 := '"' || RTRIM(vr_ho_ocorrencia07) || '"' || ',' || '"' || RTRIM(vr_st_recado08) || '"' || ',' || '"' || RTRIM(vr_st_recado_dad09) || '"' || ',' || '"' || RTRIM(vr_ds_usuario_re10) || '"' || ');';
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
		IF pa_co_funcionari02 IS NULL THEN
			vr_co_funcionari02 := 'null';
		ELSE
			vr_co_funcionari02 := '"' || RTRIM(pa_co_funcionari02) || '"';
		END IF;
		IF pa_ano_sem03 IS NULL THEN
			vr_ano_sem03 := 'null';
		ELSE
			vr_ano_sem03 := '"' || RTRIM(pa_ano_sem03) || '"';
		END IF;
		v_sql1 := '  delete from S_FUNCIONARIO_OCORRENCIA where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and co_ocorrencia = ' || RTRIM(vr_co_ocorrencia01) || '  and co_funcionario = ' || RTRIM(vr_co_funcionari02) || '  and ano_sem = ' || RTRIM(vr_ano_sem03) || ';';
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
		IF pn_co_funcionari02 IS NULL
		AND pa_co_funcionari02 IS NULL THEN
			vr_co_funcionari02 := 'null';
		END IF;
		IF pn_co_funcionari02 IS NULL
		AND pa_co_funcionari02 IS NOT NULL THEN
			vr_co_funcionari02 := 'null';
		END IF;
		IF pn_co_funcionari02 IS NOT NULL
		AND pa_co_funcionari02 IS NULL THEN
			vr_co_funcionari02 := '"' || RTRIM(pn_co_funcionari02) || '"';
		END IF;
		IF pn_co_funcionari02 IS NOT NULL
		AND pa_co_funcionari02 IS NOT NULL THEN
			IF pa_co_funcionari02 <> pn_co_funcionari02 THEN
				vr_co_funcionari02 := '"' || RTRIM(pn_co_funcionari02) || '"';
			ELSE
				vr_co_funcionari02 := '"' || RTRIM(pa_co_funcionari02) || '"';
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
		IF pn_co_tipo_ocorr04 IS NULL
		AND pa_co_tipo_ocorr04 IS NULL THEN
			vr_co_tipo_ocorr04 := 'null';
		END IF;
		IF pn_co_tipo_ocorr04 IS NULL
		AND pa_co_tipo_ocorr04 IS NOT NULL THEN
			vr_co_tipo_ocorr04 := 'null';
		END IF;
		IF pn_co_tipo_ocorr04 IS NOT NULL
		AND pa_co_tipo_ocorr04 IS NULL THEN
			vr_co_tipo_ocorr04 := pn_co_tipo_ocorr04;
		END IF;
		IF pn_co_tipo_ocorr04 IS NOT NULL
		AND pa_co_tipo_ocorr04 IS NOT NULL THEN
			IF pa_co_tipo_ocorr04 <> pn_co_tipo_ocorr04 THEN
				vr_co_tipo_ocorr04 := pn_co_tipo_ocorr04;
			ELSE
				vr_co_tipo_ocorr04 := pa_co_tipo_ocorr04;
			END IF;
		END IF;
		IF pn_dt_ocorrencia05 IS NULL
		AND pa_dt_ocorrencia05 IS NULL THEN
			vr_dt_ocorrencia05 := 'null';
		END IF;
		IF pn_dt_ocorrencia05 IS NULL
		AND pa_dt_ocorrencia05 IS NOT NULL THEN
			vr_dt_ocorrencia05 := 'null';
		END IF;
		IF pn_dt_ocorrencia05 IS NOT NULL
		AND pa_dt_ocorrencia05 IS NULL THEN
			vr_dt_ocorrencia05 := '"' || pn_dt_ocorrencia05 || '"';
		END IF;
		IF pn_dt_ocorrencia05 IS NOT NULL
		AND pa_dt_ocorrencia05 IS NOT NULL THEN
			IF pa_dt_ocorrencia05 <> pn_dt_ocorrencia05 THEN
				vr_dt_ocorrencia05 := '"' || pn_dt_ocorrencia05 || '"';
			ELSE
				vr_dt_ocorrencia05 := '"' || pa_dt_ocorrencia05 || '"';
			END IF;
		END IF;
		IF pn_ds_ocorrencia06 IS NULL THEN
			vr_ds_ocorrencia06 := NULL;
		ELSE
			vr_ds_ocorrencia06 := ':vblob1';
		END IF;
		v_blob1 := pn_ds_ocorrencia06;
		IF pn_ho_ocorrencia07 IS NULL
		AND pa_ho_ocorrencia07 IS NULL THEN
			vr_ho_ocorrencia07 := 'null';
		END IF;
		IF pn_ho_ocorrencia07 IS NULL
		AND pa_ho_ocorrencia07 IS NOT NULL THEN
			vr_ho_ocorrencia07 := 'null';
		END IF;
		IF pn_ho_ocorrencia07 IS NOT NULL
		AND pa_ho_ocorrencia07 IS NULL THEN
			vr_ho_ocorrencia07 := '"' || RTRIM(pn_ho_ocorrencia07) || '"';
		END IF;
		IF pn_ho_ocorrencia07 IS NOT NULL
		AND pa_ho_ocorrencia07 IS NOT NULL THEN
			IF pa_ho_ocorrencia07 <> pn_ho_ocorrencia07 THEN
				vr_ho_ocorrencia07 := '"' || RTRIM(pn_ho_ocorrencia07) || '"';
			ELSE
				vr_ho_ocorrencia07 := '"' || RTRIM(pa_ho_ocorrencia07) || '"';
			END IF;
		END IF;
		IF pn_st_recado08 IS NULL
		AND pa_st_recado08 IS NULL THEN
			vr_st_recado08 := 'null';
		END IF;
		IF pn_st_recado08 IS NULL
		AND pa_st_recado08 IS NOT NULL THEN
			vr_st_recado08 := 'null';
		END IF;
		IF pn_st_recado08 IS NOT NULL
		AND pa_st_recado08 IS NULL THEN
			vr_st_recado08 := '"' || RTRIM(pn_st_recado08) || '"';
		END IF;
		IF pn_st_recado08 IS NOT NULL
		AND pa_st_recado08 IS NOT NULL THEN
			IF pa_st_recado08 <> pn_st_recado08 THEN
				vr_st_recado08 := '"' || RTRIM(pn_st_recado08) || '"';
			ELSE
				vr_st_recado08 := '"' || RTRIM(pa_st_recado08) || '"';
			END IF;
		END IF;
		IF pn_st_recado_dad09 IS NULL
		AND pa_st_recado_dad09 IS NULL THEN
			vr_st_recado_dad09 := 'null';
		END IF;
		IF pn_st_recado_dad09 IS NULL
		AND pa_st_recado_dad09 IS NOT NULL THEN
			vr_st_recado_dad09 := 'null';
		END IF;
		IF pn_st_recado_dad09 IS NOT NULL
		AND pa_st_recado_dad09 IS NULL THEN
			vr_st_recado_dad09 := '"' || RTRIM(pn_st_recado_dad09) || '"';
		END IF;
		IF pn_st_recado_dad09 IS NOT NULL
		AND pa_st_recado_dad09 IS NOT NULL THEN
			IF pa_st_recado_dad09 <> pn_st_recado_dad09 THEN
				vr_st_recado_dad09 := '"' || RTRIM(pn_st_recado_dad09) || '"';
			ELSE
				vr_st_recado_dad09 := '"' || RTRIM(pa_st_recado_dad09) || '"';
			END IF;
		END IF;
		IF pn_ds_usuario_re10 IS NULL
		AND pa_ds_usuario_re10 IS NULL THEN
			vr_ds_usuario_re10 := 'null';
		END IF;
		IF pn_ds_usuario_re10 IS NULL
		AND pa_ds_usuario_re10 IS NOT NULL THEN
			vr_ds_usuario_re10 := 'null';
		END IF;
		IF pn_ds_usuario_re10 IS NOT NULL
		AND pa_ds_usuario_re10 IS NULL THEN
			vr_ds_usuario_re10 := '"' || RTRIM(pn_ds_usuario_re10) || '"';
		END IF;
		IF pn_ds_usuario_re10 IS NOT NULL
		AND pa_ds_usuario_re10 IS NOT NULL THEN
			IF pa_ds_usuario_re10 <> pn_ds_usuario_re10 THEN
				vr_ds_usuario_re10 := '"' || RTRIM(pn_ds_usuario_re10) || '"';
			ELSE
				vr_ds_usuario_re10 := '"' || RTRIM(pa_ds_usuario_re10) || '"';
			END IF;
		END IF;
		v_sql1 := 'update S_FUNCIONARIO_OCORRENCIA set co_unidade = ' || RTRIM(vr_co_unidade00) || '  , co_ocorrencia = ' || RTRIM(vr_co_ocorrencia01) || '  , co_funcionario = ' || RTRIM(vr_co_funcionari02) || '  , ano_sem = ' || RTRIM(vr_ano_sem03) || '  , co_tipo_ocorrencia = ' || RTRIM(vr_co_tipo_ocorr04) || '  , dt_ocorrencia = ' || RTRIM(vr_dt_ocorrencia05);
		v_sql2 := '  , ds_ocorrencia = ' || RTRIM(vr_ds_ocorrencia06) || '  , ho_ocorrencia = ' || RTRIM(vr_ho_ocorrencia07) || '  , st_recado = ' || RTRIM(vr_st_recado08) || '  , st_recado_dado = ' || RTRIM(vr_st_recado_dad09) || '  , ds_usuario_recado = ' || RTRIM(vr_ds_usuario_re10);
		v_sql3 := ' where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and co_ocorrencia = ' || RTRIM(vr_co_ocorrencia01) || '  and co_funcionario = ' || RTRIM(vr_co_funcionari02) || '  and ano_sem = ' || RTRIM(vr_ano_sem03) || ';';
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
		       's_funcionario_ocor',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       v_blob1,
		       NULL);
	END IF;
END pr_s_funciona102;
/

