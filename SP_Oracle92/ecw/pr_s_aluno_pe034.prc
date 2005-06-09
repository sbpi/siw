CREATE OR REPLACE PROCEDURE pr_s_aluno_pe034(
	P_OP_IN                CHAR,
	PA_ano_sem00_IN        s_aluno_per_unid.ano_sem%TYPE,
	PA_co_aluno01_IN       s_aluno_per_unid.co_aluno%TYPE,
	PA_co_unidade02_IN     s_aluno_per_unid.co_unidade%TYPE,
	PA_nu_altura03_IN      s_aluno_per_unid.nu_altura%TYPE,
	PA_nu_peso04_IN        s_aluno_per_unid.nu_peso%TYPE,
	PA_tp_apto_ed_fi05_IN  s_aluno_per_unid.tp_apto_ed_fisica%TYPE,
	PA_st_ens_religi06_IN  s_aluno_per_unid.st_ens_religioso%TYPE,
	PA_ds_situacao_a07_IN  s_aluno_per_unid.ds_situacao_aluno%TYPE,
	PA_dt_matricula08_IN   s_aluno_per_unid.dt_matricula%TYPE,
	PA_tp_bolsa_esco09_IN  s_aluno_per_unid.tp_bolsa_escola%TYPE,
	PA_nu_bolsa_esco10_IN  s_aluno_per_unid.nu_bolsa_escola%TYPE,
	PN_ano_sem00_IN        s_aluno_per_unid.ano_sem%TYPE,
	PN_co_aluno01_IN       s_aluno_per_unid.co_aluno%TYPE,
	PN_co_unidade02_IN     s_aluno_per_unid.co_unidade%TYPE,
	PN_nu_altura03_IN      s_aluno_per_unid.nu_altura%TYPE,
	PN_nu_peso04_IN        s_aluno_per_unid.nu_peso%TYPE,
	PN_tp_apto_ed_fi05_IN  s_aluno_per_unid.tp_apto_ed_fisica%TYPE,
	PN_st_ens_religi06_IN  s_aluno_per_unid.st_ens_religioso%TYPE,
	PN_ds_situacao_a07_IN  s_aluno_per_unid.ds_situacao_aluno%TYPE,
	PN_dt_matricula08_IN   s_aluno_per_unid.dt_matricula%TYPE,
	PN_tp_bolsa_esco09_IN  s_aluno_per_unid.tp_bolsa_escola%TYPE,
	PN_nu_bolsa_esco10_IN  s_aluno_per_unid.nu_bolsa_escola%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_ano_sem00        s_aluno_per_unid.ano_sem%TYPE := PA_ano_sem00_IN;
PA_co_aluno01       s_aluno_per_unid.co_aluno%TYPE := PA_co_aluno01_IN;
PA_co_unidade02     s_aluno_per_unid.co_unidade%TYPE := PA_co_unidade02_IN;
PA_nu_altura03      s_aluno_per_unid.nu_altura%TYPE := PA_nu_altura03_IN;
PA_nu_peso04        s_aluno_per_unid.nu_peso%TYPE := PA_nu_peso04_IN;
PA_tp_apto_ed_fi05  s_aluno_per_unid.tp_apto_ed_fisica%TYPE := PA_tp_apto_ed_fi05_IN;
PA_st_ens_religi06  s_aluno_per_unid.st_ens_religioso%TYPE := PA_st_ens_religi06_IN;
PA_ds_situacao_a07  s_aluno_per_unid.ds_situacao_aluno%TYPE := PA_ds_situacao_a07_IN;
PA_dt_matricula08   s_aluno_per_unid.dt_matricula%TYPE := PA_dt_matricula08_IN;
PA_tp_bolsa_esco09  s_aluno_per_unid.tp_bolsa_escola%TYPE := PA_tp_bolsa_esco09_IN;
PA_nu_bolsa_esco10  s_aluno_per_unid.nu_bolsa_escola%TYPE := PA_nu_bolsa_esco10_IN;
PN_ano_sem00        s_aluno_per_unid.ano_sem%TYPE := PN_ano_sem00_IN;
PN_co_aluno01       s_aluno_per_unid.co_aluno%TYPE := PN_co_aluno01_IN;
PN_co_unidade02     s_aluno_per_unid.co_unidade%TYPE := PN_co_unidade02_IN;
PN_nu_altura03      s_aluno_per_unid.nu_altura%TYPE := PN_nu_altura03_IN;
PN_nu_peso04        s_aluno_per_unid.nu_peso%TYPE := PN_nu_peso04_IN;
PN_tp_apto_ed_fi05  s_aluno_per_unid.tp_apto_ed_fisica%TYPE := PN_tp_apto_ed_fi05_IN;
PN_st_ens_religi06  s_aluno_per_unid.st_ens_religioso%TYPE := PN_st_ens_religi06_IN;
PN_ds_situacao_a07  s_aluno_per_unid.ds_situacao_aluno%TYPE := PN_ds_situacao_a07_IN;
PN_dt_matricula08   s_aluno_per_unid.dt_matricula%TYPE := PN_dt_matricula08_IN;
PN_tp_bolsa_esco09  s_aluno_per_unid.tp_bolsa_escola%TYPE := PN_tp_bolsa_esco09_IN;
PN_nu_bolsa_esco10  s_aluno_per_unid.nu_bolsa_escola%TYPE := PN_nu_bolsa_esco10_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_ano_sem00        CHAR(10);
vr_co_aluno01       CHAR(20);
vr_co_unidade02     CHAR(10);
vr_nu_altura03      CHAR(10);
vr_nu_peso04        CHAR(10);
vr_tp_apto_ed_fi05  CHAR(10);
vr_st_ens_religi06  CHAR(100);
vr_ds_situacao_a07  CHAR(20);
vr_dt_matricula08   CHAR(40);
vr_tp_bolsa_esco09  CHAR(10);
vr_nu_bolsa_esco10  CHAR(20);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_ano_sem00 IS NULL THEN
			vr_ano_sem00 := 'null';
		ELSE
			vr_ano_sem00 := pn_ano_sem00;
		END IF;
		IF pn_co_aluno01 IS NULL THEN
			vr_co_aluno01 := 'null';
		ELSE
			vr_co_aluno01 := pn_co_aluno01;
		END IF;
		IF pn_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := pn_co_unidade02;
		END IF;
		IF pn_nu_altura03 IS NULL THEN
			vr_nu_altura03 := 'null';
		ELSE
			vr_nu_altura03 := pn_nu_altura03;
		END IF;
		IF pn_nu_peso04 IS NULL THEN
			vr_nu_peso04 := 'null';
		ELSE
			vr_nu_peso04 := pn_nu_peso04;
		END IF;
		IF pn_tp_apto_ed_fi05 IS NULL THEN
			vr_tp_apto_ed_fi05 := 'null';
		ELSE
			vr_tp_apto_ed_fi05 := pn_tp_apto_ed_fi05;
		END IF;
		IF pn_st_ens_religi06 IS NULL THEN
			vr_st_ens_religi06 := 'null';
		ELSE
			vr_st_ens_religi06 := pn_st_ens_religi06;
		END IF;
		IF pn_ds_situacao_a07 IS NULL THEN
			vr_ds_situacao_a07 := 'null';
		ELSE
			vr_ds_situacao_a07 := pn_ds_situacao_a07;
		END IF;
		IF pn_dt_matricula08 IS NULL THEN
			vr_dt_matricula08 := 'null';
		ELSE
			vr_dt_matricula08 := pn_dt_matricula08;
		END IF;
		IF pn_tp_bolsa_esco09 IS NULL THEN
			vr_tp_bolsa_esco09 := 'null';
		ELSE
			vr_tp_bolsa_esco09 := pn_tp_bolsa_esco09;
		END IF;
		IF pn_nu_bolsa_esco10 IS NULL THEN
			vr_nu_bolsa_esco10 := 'null';
		ELSE
			vr_nu_bolsa_esco10 := pn_nu_bolsa_esco10;
		END IF;
		v_sql1 := 'insert into S_ALUNOPERIODOUNIDADE(ano_sem, co_aluno, co_unidade, nu_altura, nu_peso, TP_APTO_EDUCACAO_FISICA, ST_ENSINO_RELIGIOSO, ds_situacao_aluno, dt_matricula, tp_bolsa_escola, nu_bolsa_escola) values (';
		v_sql2 := '"' || RTRIM(vr_ano_sem00) || '"' || ',' || '"' || RTRIM(vr_co_aluno01) || '"' || ',' || '"' || RTRIM(vr_co_unidade02) || '"' || ',' || '"' || RTRIM(vr_nu_altura03) || '"' || ',' || '"' || RTRIM(vr_nu_peso04) || '"' || ',' || '"' || RTRIM(vr_tp_apto_ed_fi05) || '"' || ',';
		v_sql3 := '"' || RTRIM(vr_st_ens_religi06) || '"' || ',' || '"' || RTRIM(vr_ds_situacao_a07) || '"' || ',' || '"' || vr_dt_matricula08 || '"' || ',' || '"' || RTRIM(vr_tp_bolsa_esco09) || '"' || ',' || '"' || RTRIM(vr_nu_bolsa_esco10) || '"' || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_ano_sem00 IS NULL THEN
			vr_ano_sem00 := 'null';
		ELSE
			vr_ano_sem00 := '"' || RTRIM(pa_ano_sem00) || '"';
		END IF;
		IF pa_co_aluno01 IS NULL THEN
			vr_co_aluno01 := 'null';
		ELSE
			vr_co_aluno01 := '"' || RTRIM(pa_co_aluno01) || '"';
		END IF;
		IF pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := '"' || RTRIM(pa_co_unidade02) || '"';
		END IF;
		v_sql1 := '  delete from S_ALUNOPERIODOUNIDADE where ano_sem = ' || RTRIM(vr_ano_sem00) || '  and co_aluno = ' || RTRIM(vr_co_aluno01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_ano_sem00 IS NULL
		AND pa_ano_sem00 IS NULL THEN
			vr_ano_sem00 := 'null';
		END IF;
		IF pn_ano_sem00 IS NULL
		AND pa_ano_sem00 IS NOT NULL THEN
			vr_ano_sem00 := 'null';
		END IF;
		IF pn_ano_sem00 IS NOT NULL
		AND pa_ano_sem00 IS NULL THEN
			vr_ano_sem00 := '"' || RTRIM(pn_ano_sem00) || '"';
		END IF;
		IF pn_ano_sem00 IS NOT NULL
		AND pa_ano_sem00 IS NOT NULL THEN
			IF pa_ano_sem00 <> pn_ano_sem00 THEN
				vr_ano_sem00 := '"' || RTRIM(pn_ano_sem00) || '"';
			ELSE
				vr_ano_sem00 := '"' || RTRIM(pa_ano_sem00) || '"';
			END IF;
		END IF;
		IF pn_co_aluno01 IS NULL
		AND pa_co_aluno01 IS NULL THEN
			vr_co_aluno01 := 'null';
		END IF;
		IF pn_co_aluno01 IS NULL
		AND pa_co_aluno01 IS NOT NULL THEN
			vr_co_aluno01 := 'null';
		END IF;
		IF pn_co_aluno01 IS NOT NULL
		AND pa_co_aluno01 IS NULL THEN
			vr_co_aluno01 := '"' || RTRIM(pn_co_aluno01) || '"';
		END IF;
		IF pn_co_aluno01 IS NOT NULL
		AND pa_co_aluno01 IS NOT NULL THEN
			IF pa_co_aluno01 <> pn_co_aluno01 THEN
				vr_co_aluno01 := '"' || RTRIM(pn_co_aluno01) || '"';
			ELSE
				vr_co_aluno01 := '"' || RTRIM(pa_co_aluno01) || '"';
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
		IF pn_nu_altura03 IS NULL
		AND pa_nu_altura03 IS NULL THEN
			vr_nu_altura03 := 'null';
		END IF;
		IF pn_nu_altura03 IS NULL
		AND pa_nu_altura03 IS NOT NULL THEN
			vr_nu_altura03 := 'null';
		END IF;
		IF pn_nu_altura03 IS NOT NULL
		AND pa_nu_altura03 IS NULL THEN
			vr_nu_altura03 := '"' || RTRIM(pn_nu_altura03) || '"';
		END IF;
		IF pn_nu_altura03 IS NOT NULL
		AND pa_nu_altura03 IS NOT NULL THEN
			IF pa_nu_altura03 <> pn_nu_altura03 THEN
				vr_nu_altura03 := '"' || RTRIM(pn_nu_altura03) || '"';
			ELSE
				vr_nu_altura03 := '"' || RTRIM(pa_nu_altura03) || '"';
			END IF;
		END IF;
		IF pn_nu_peso04 IS NULL
		AND pa_nu_peso04 IS NULL THEN
			vr_nu_peso04 := 'null';
		END IF;
		IF pn_nu_peso04 IS NULL
		AND pa_nu_peso04 IS NOT NULL THEN
			vr_nu_peso04 := 'null';
		END IF;
		IF pn_nu_peso04 IS NOT NULL
		AND pa_nu_peso04 IS NULL THEN
			vr_nu_peso04 := '"' || RTRIM(pn_nu_peso04) || '"';
		END IF;
		IF pn_nu_peso04 IS NOT NULL
		AND pa_nu_peso04 IS NOT NULL THEN
			IF pa_nu_peso04 <> pn_nu_peso04 THEN
				vr_nu_peso04 := '"' || RTRIM(pn_nu_peso04) || '"';
			ELSE
				vr_nu_peso04 := '"' || RTRIM(pa_nu_peso04) || '"';
			END IF;
		END IF;
		IF pn_tp_apto_ed_fi05 IS NULL
		AND pa_tp_apto_ed_fi05 IS NULL THEN
			vr_tp_apto_ed_fi05 := 'null';
		END IF;
		IF pn_tp_apto_ed_fi05 IS NULL
		AND pa_tp_apto_ed_fi05 IS NOT NULL THEN
			vr_tp_apto_ed_fi05 := 'null';
		END IF;
		IF pn_tp_apto_ed_fi05 IS NOT NULL
		AND pa_tp_apto_ed_fi05 IS NULL THEN
			vr_tp_apto_ed_fi05 := '"' || RTRIM(pn_tp_apto_ed_fi05) || '"';
		END IF;
		IF pn_tp_apto_ed_fi05 IS NOT NULL
		AND pa_tp_apto_ed_fi05 IS NOT NULL THEN
			IF pa_tp_apto_ed_fi05 <> pn_tp_apto_ed_fi05 THEN
				vr_tp_apto_ed_fi05 := '"' || RTRIM(pn_tp_apto_ed_fi05) || '"';
			ELSE
				vr_tp_apto_ed_fi05 := '"' || RTRIM(pa_tp_apto_ed_fi05) || '"';
			END IF;
		END IF;
		IF pn_st_ens_religi06 IS NULL
		AND pa_st_ens_religi06 IS NULL THEN
			vr_st_ens_religi06 := 'null';
		END IF;
		IF pn_st_ens_religi06 IS NULL
		AND pa_st_ens_religi06 IS NOT NULL THEN
			vr_st_ens_religi06 := 'null';
		END IF;
		IF pn_st_ens_religi06 IS NOT NULL
		AND pa_st_ens_religi06 IS NULL THEN
			vr_st_ens_religi06 := '"' || RTRIM(pn_st_ens_religi06) || '"';
		END IF;
		IF pn_st_ens_religi06 IS NOT NULL
		AND pa_st_ens_religi06 IS NOT NULL THEN
			IF pa_st_ens_religi06 <> pn_st_ens_religi06 THEN
				vr_st_ens_religi06 := '"' || RTRIM(pn_st_ens_religi06) || '"';
			ELSE
				vr_st_ens_religi06 := '"' || RTRIM(pa_st_ens_religi06) || '"';
			END IF;
		END IF;
		IF pn_ds_situacao_a07 IS NULL
		AND pa_ds_situacao_a07 IS NULL THEN
			vr_ds_situacao_a07 := 'null';
		END IF;
		IF pn_ds_situacao_a07 IS NULL
		AND pa_ds_situacao_a07 IS NOT NULL THEN
			vr_ds_situacao_a07 := 'null';
		END IF;
		IF pn_ds_situacao_a07 IS NOT NULL
		AND pa_ds_situacao_a07 IS NULL THEN
			vr_ds_situacao_a07 := '"' || RTRIM(pn_ds_situacao_a07) || '"';
		END IF;
		IF pn_ds_situacao_a07 IS NOT NULL
		AND pa_ds_situacao_a07 IS NOT NULL THEN
			IF pa_ds_situacao_a07 <> pn_ds_situacao_a07 THEN
				vr_ds_situacao_a07 := '"' || RTRIM(pn_ds_situacao_a07) || '"';
			ELSE
				vr_ds_situacao_a07 := '"' || RTRIM(pa_ds_situacao_a07) || '"';
			END IF;
		END IF;
		IF pn_dt_matricula08 IS NULL
		AND pa_dt_matricula08 IS NULL THEN
			vr_dt_matricula08 := 'null';
		END IF;
		IF pn_dt_matricula08 IS NULL
		AND pa_dt_matricula08 IS NOT NULL THEN
			vr_dt_matricula08 := 'null';
		END IF;
		IF pn_dt_matricula08 IS NOT NULL
		AND pa_dt_matricula08 IS NULL THEN
			vr_dt_matricula08 := '"' || pn_dt_matricula08 || '"';
		END IF;
		IF pn_dt_matricula08 IS NOT NULL
		AND pa_dt_matricula08 IS NOT NULL THEN
			IF pa_dt_matricula08 <> pn_dt_matricula08 THEN
				vr_dt_matricula08 := '"' || pn_dt_matricula08 || '"';
			ELSE
				vr_dt_matricula08 := '"' || pa_dt_matricula08 || '"';
			END IF;
		END IF;
		IF pn_tp_bolsa_esco09 IS NULL
		AND pa_tp_bolsa_esco09 IS NULL THEN
			vr_tp_bolsa_esco09 := 'null';
		END IF;
		IF pn_tp_bolsa_esco09 IS NULL
		AND pa_tp_bolsa_esco09 IS NOT NULL THEN
			vr_tp_bolsa_esco09 := 'null';
		END IF;
		IF pn_tp_bolsa_esco09 IS NOT NULL
		AND pa_tp_bolsa_esco09 IS NULL THEN
			vr_tp_bolsa_esco09 := '"' || RTRIM(pn_tp_bolsa_esco09) || '"';
		END IF;
		IF pn_tp_bolsa_esco09 IS NOT NULL
		AND pa_tp_bolsa_esco09 IS NOT NULL THEN
			IF pa_tp_bolsa_esco09 <> pn_tp_bolsa_esco09 THEN
				vr_tp_bolsa_esco09 := '"' || RTRIM(pn_tp_bolsa_esco09) || '"';
			ELSE
				vr_tp_bolsa_esco09 := '"' || RTRIM(pa_tp_bolsa_esco09) || '"';
			END IF;
		END IF;
		IF pn_nu_bolsa_esco10 IS NULL
		AND pa_nu_bolsa_esco10 IS NULL THEN
			vr_nu_bolsa_esco10 := 'null';
		END IF;
		IF pn_nu_bolsa_esco10 IS NULL
		AND pa_nu_bolsa_esco10 IS NOT NULL THEN
			vr_nu_bolsa_esco10 := 'null';
		END IF;
		IF pn_nu_bolsa_esco10 IS NOT NULL
		AND pa_nu_bolsa_esco10 IS NULL THEN
			vr_nu_bolsa_esco10 := '"' || RTRIM(pn_nu_bolsa_esco10) || '"';
		END IF;
		IF pn_nu_bolsa_esco10 IS NOT NULL
		AND pa_nu_bolsa_esco10 IS NOT NULL THEN
			IF pa_nu_bolsa_esco10 <> pn_nu_bolsa_esco10 THEN
				vr_nu_bolsa_esco10 := '"' || RTRIM(pn_nu_bolsa_esco10) || '"';
			ELSE
				vr_nu_bolsa_esco10 := '"' || RTRIM(pa_nu_bolsa_esco10) || '"';
			END IF;
		END IF;
		v_sql1 := 'update S_ALUNOPERIODOUNIDADE set ano_sem = ' || RTRIM(vr_ano_sem00) || '  , co_aluno = ' || RTRIM(vr_co_aluno01) || '  , co_unidade = ' || RTRIM(vr_co_unidade02) || '  , nu_altura = ' || RTRIM(vr_nu_altura03) || '  , nu_peso = ' || RTRIM(vr_nu_peso04) || '  , TP_APTO_EDUCACAO_FISICA = ' || RTRIM(vr_tp_apto_ed_fi05);
		v_sql2 := '  , ST_ENSINO_RELIGIOSO = ' || RTRIM(vr_st_ens_religi06) || '  , ds_situacao_aluno = ' || RTRIM(vr_ds_situacao_a07) || '  , dt_matricula = ' || RTRIM(vr_dt_matricula08) || '  , tp_bolsa_escola = ' || RTRIM(vr_tp_bolsa_esco09) || '  , nu_bolsa_escola = ' || RTRIM(vr_nu_bolsa_esco10);
		v_sql3 := ' where ano_sem = ' || RTRIM(vr_ano_sem00) || '  and co_aluno = ' || RTRIM(vr_co_aluno01) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
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
		       's_aluno_per_unid',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_aluno_pe034;
/

