CREATE OR REPLACE PROCEDURE pr_s_sala144(
	P_OP_IN                CHAR,
	PA_co_bloco00_IN       s_sala.co_bloco%TYPE,
	PA_co_unidade01_IN     s_sala.co_unidade%TYPE,
	PA_co_sala02_IN        s_sala.co_sala%TYPE,
	PA_ds_sala03_IN        s_sala.ds_sala%TYPE,
	PA_co_seq_ambien04_IN  s_sala.co_seq_ambiente%TYPE,
	PA_nu_alunos_sal05_IN  s_sala.nu_alunos_sala%TYPE,
	PA_nu_metragem06_IN    s_sala.nu_metragem%TYPE,
	PA_co_tipo_sala07_IN   s_sala.co_tipo_sala%TYPE,
	PA_co_seq_sala08_IN    s_sala.co_seq_sala%TYPE,
	PN_co_bloco00_IN       s_sala.co_bloco%TYPE,
	PN_co_unidade01_IN     s_sala.co_unidade%TYPE,
	PN_co_sala02_IN        s_sala.co_sala%TYPE,
	PN_ds_sala03_IN        s_sala.ds_sala%TYPE,
	PN_co_seq_ambien04_IN  s_sala.co_seq_ambiente%TYPE,
	PN_nu_alunos_sal05_IN  s_sala.nu_alunos_sala%TYPE,
	PN_nu_metragem06_IN    s_sala.nu_metragem%TYPE,
	PN_co_tipo_sala07_IN   s_sala.co_tipo_sala%TYPE,
	PN_co_seq_sala08_IN    s_sala.co_seq_sala%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_bloco00       s_sala.co_bloco%TYPE := PA_co_bloco00_IN;
PA_co_unidade01     s_sala.co_unidade%TYPE := PA_co_unidade01_IN;
PA_co_sala02        s_sala.co_sala%TYPE := PA_co_sala02_IN;
PA_ds_sala03        s_sala.ds_sala%TYPE := PA_ds_sala03_IN;
PA_co_seq_ambien04  s_sala.co_seq_ambiente%TYPE := PA_co_seq_ambien04_IN;
PA_nu_alunos_sal05  s_sala.nu_alunos_sala%TYPE := PA_nu_alunos_sal05_IN;
PA_nu_metragem06    s_sala.nu_metragem%TYPE := PA_nu_metragem06_IN;
PA_co_tipo_sala07   s_sala.co_tipo_sala%TYPE := PA_co_tipo_sala07_IN;
PA_co_seq_sala08    s_sala.co_seq_sala%TYPE := PA_co_seq_sala08_IN;
PN_co_bloco00       s_sala.co_bloco%TYPE := PN_co_bloco00_IN;
PN_co_unidade01     s_sala.co_unidade%TYPE := PN_co_unidade01_IN;
PN_co_sala02        s_sala.co_sala%TYPE := PN_co_sala02_IN;
PN_ds_sala03        s_sala.ds_sala%TYPE := PN_ds_sala03_IN;
PN_co_seq_ambien04  s_sala.co_seq_ambiente%TYPE := PN_co_seq_ambien04_IN;
PN_nu_alunos_sal05  s_sala.nu_alunos_sala%TYPE := PN_nu_alunos_sal05_IN;
PN_nu_metragem06    s_sala.nu_metragem%TYPE := PN_nu_metragem06_IN;
PN_co_tipo_sala07   s_sala.co_tipo_sala%TYPE := PN_co_tipo_sala07_IN;
PN_co_seq_sala08    s_sala.co_seq_sala%TYPE := PN_co_seq_sala08_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_bloco00       CHAR(10);
vr_co_unidade01     CHAR(10);
vr_co_sala02        CHAR(10);
vr_ds_sala03        CHAR(40);
vr_co_seq_ambien04  CHAR(10);
vr_nu_alunos_sal05  CHAR(10);
vr_nu_metragem06    CHAR(10);
vr_co_tipo_sala07   CHAR(10);
vr_co_seq_sala08    CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_bloco00 IS NULL THEN
			vr_co_bloco00 := 'null';
		ELSE
			vr_co_bloco00 := pn_co_bloco00;
		END IF;
		IF pn_co_unidade01 IS NULL THEN
			vr_co_unidade01 := 'null';
		ELSE
			vr_co_unidade01 := pn_co_unidade01;
		END IF;
		IF pn_co_sala02 IS NULL THEN
			vr_co_sala02 := 'null';
		ELSE
			vr_co_sala02 := pn_co_sala02;
		END IF;
		IF pn_ds_sala03 IS NULL THEN
			vr_ds_sala03 := 'null';
		ELSE
			vr_ds_sala03 := pn_ds_sala03;
		END IF;
		IF pn_co_seq_ambien04 IS NULL THEN
			vr_co_seq_ambien04 := 'null';
		ELSE
			vr_co_seq_ambien04 := pn_co_seq_ambien04;
		END IF;
		IF pn_nu_alunos_sal05 IS NULL THEN
			vr_nu_alunos_sal05 := 'null';
		ELSE
			vr_nu_alunos_sal05 := pn_nu_alunos_sal05;
		END IF;
		IF pn_nu_metragem06 IS NULL THEN
			vr_nu_metragem06 := 'null';
		ELSE
			vr_nu_metragem06 := pn_nu_metragem06;
		END IF;
		IF pn_co_tipo_sala07 IS NULL THEN
			vr_co_tipo_sala07 := 'null';
		ELSE
			vr_co_tipo_sala07 := pn_co_tipo_sala07;
		END IF;
		IF pn_co_seq_sala08 IS NULL THEN
			vr_co_seq_sala08 := 'null';
		ELSE
			vr_co_seq_sala08 := pn_co_seq_sala08;
		END IF;
		v_sql1 := 'insert into s_sala(co_bloco, co_unidade, co_sala, ds_sala, co_seq_ambiente, nu_alunos_sala, nu_metragem, co_tipo_sala, co_seq_sala) values (';
		v_sql2 := '"' || RTRIM(vr_co_bloco00) || '"' || ',' || '"' || RTRIM(vr_co_unidade01) || '"' || ',' || '"' || RTRIM(vr_co_sala02) || '"' || ',' || '"' || RTRIM(vr_ds_sala03) || '"' || ',';
		v_sql3 := RTRIM(vr_co_seq_ambien04) || ',' || RTRIM(vr_nu_alunos_sal05) || ',' || RTRIM(vr_nu_metragem06) || ',' || RTRIM(vr_co_tipo_sala07) || ',' || RTRIM(vr_co_seq_sala08) || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_bloco00 IS NULL THEN
			vr_co_bloco00 := 'null';
		ELSE
			vr_co_bloco00 := '"' || RTRIM(pa_co_bloco00) || '"';
		END IF;
		IF pa_co_unidade01 IS NULL THEN
			vr_co_unidade01 := 'null';
		ELSE
			vr_co_unidade01 := '"' || RTRIM(pa_co_unidade01) || '"';
		END IF;
		IF pa_co_sala02 IS NULL THEN
			vr_co_sala02 := 'null';
		ELSE
			vr_co_sala02 := '"' || RTRIM(pa_co_sala02) || '"';
		END IF;
		v_sql1 := '  delete from s_sala where co_bloco = ' || RTRIM(vr_co_bloco00) || '  and co_unidade = ' || RTRIM(vr_co_unidade01) || '  and co_sala = ' || RTRIM(vr_co_sala02) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_bloco00 IS NULL
		AND pa_co_bloco00 IS NULL THEN
			vr_co_bloco00 := 'null';
		END IF;
		IF pn_co_bloco00 IS NULL
		AND pa_co_bloco00 IS NOT NULL THEN
			vr_co_bloco00 := 'null';
		END IF;
		IF pn_co_bloco00 IS NOT NULL
		AND pa_co_bloco00 IS NULL THEN
			vr_co_bloco00 := '"' || RTRIM(pn_co_bloco00) || '"';
		END IF;
		IF pn_co_bloco00 IS NOT NULL
		AND pa_co_bloco00 IS NOT NULL THEN
			IF pa_co_bloco00 <> pn_co_bloco00 THEN
				vr_co_bloco00 := '"' || RTRIM(pn_co_bloco00) || '"';
			ELSE
				vr_co_bloco00 := '"' || RTRIM(pa_co_bloco00) || '"';
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
		IF pn_co_sala02 IS NULL
		AND pa_co_sala02 IS NULL THEN
			vr_co_sala02 := 'null';
		END IF;
		IF pn_co_sala02 IS NULL
		AND pa_co_sala02 IS NOT NULL THEN
			vr_co_sala02 := 'null';
		END IF;
		IF pn_co_sala02 IS NOT NULL
		AND pa_co_sala02 IS NULL THEN
			vr_co_sala02 := '"' || RTRIM(pn_co_sala02) || '"';
		END IF;
		IF pn_co_sala02 IS NOT NULL
		AND pa_co_sala02 IS NOT NULL THEN
			IF pa_co_sala02 <> pn_co_sala02 THEN
				vr_co_sala02 := '"' || RTRIM(pn_co_sala02) || '"';
			ELSE
				vr_co_sala02 := '"' || RTRIM(pa_co_sala02) || '"';
			END IF;
		END IF;
		IF pn_ds_sala03 IS NULL
		AND pa_ds_sala03 IS NULL THEN
			vr_ds_sala03 := 'null';
		END IF;
		IF pn_ds_sala03 IS NULL
		AND pa_ds_sala03 IS NOT NULL THEN
			vr_ds_sala03 := 'null';
		END IF;
		IF pn_ds_sala03 IS NOT NULL
		AND pa_ds_sala03 IS NULL THEN
			vr_ds_sala03 := '"' || RTRIM(pn_ds_sala03) || '"';
		END IF;
		IF pn_ds_sala03 IS NOT NULL
		AND pa_ds_sala03 IS NOT NULL THEN
			IF pa_ds_sala03 <> pn_ds_sala03 THEN
				vr_ds_sala03 := '"' || RTRIM(pn_ds_sala03) || '"';
			ELSE
				vr_ds_sala03 := '"' || RTRIM(pa_ds_sala03) || '"';
			END IF;
		END IF;
		IF pn_co_seq_ambien04 IS NULL
		AND pa_co_seq_ambien04 IS NULL THEN
			vr_co_seq_ambien04 := 'null';
		END IF;
		IF pn_co_seq_ambien04 IS NULL
		AND pa_co_seq_ambien04 IS NOT NULL THEN
			vr_co_seq_ambien04 := 'null';
		END IF;
		IF pn_co_seq_ambien04 IS NOT NULL
		AND pa_co_seq_ambien04 IS NULL THEN
			vr_co_seq_ambien04 := pn_co_seq_ambien04;
		END IF;
		IF pn_co_seq_ambien04 IS NOT NULL
		AND pa_co_seq_ambien04 IS NOT NULL THEN
			IF pa_co_seq_ambien04 <> pn_co_seq_ambien04 THEN
				vr_co_seq_ambien04 := pn_co_seq_ambien04;
			ELSE
				vr_co_seq_ambien04 := pa_co_seq_ambien04;
			END IF;
		END IF;
		IF pn_nu_alunos_sal05 IS NULL
		AND pa_nu_alunos_sal05 IS NULL THEN
			vr_nu_alunos_sal05 := 'null';
		END IF;
		IF pn_nu_alunos_sal05 IS NULL
		AND pa_nu_alunos_sal05 IS NOT NULL THEN
			vr_nu_alunos_sal05 := 'null';
		END IF;
		IF pn_nu_alunos_sal05 IS NOT NULL
		AND pa_nu_alunos_sal05 IS NULL THEN
			vr_nu_alunos_sal05 := pn_nu_alunos_sal05;
		END IF;
		IF pn_nu_alunos_sal05 IS NOT NULL
		AND pa_nu_alunos_sal05 IS NOT NULL THEN
			IF pa_nu_alunos_sal05 <> pn_nu_alunos_sal05 THEN
				vr_nu_alunos_sal05 := pn_nu_alunos_sal05;
			ELSE
				vr_nu_alunos_sal05 := pa_nu_alunos_sal05;
			END IF;
		END IF;
		IF pn_nu_metragem06 IS NULL
		AND pa_nu_metragem06 IS NULL THEN
			vr_nu_metragem06 := 'null';
		END IF;
		IF pn_nu_metragem06 IS NULL
		AND pa_nu_metragem06 IS NOT NULL THEN
			vr_nu_metragem06 := 'null';
		END IF;
		IF pn_nu_metragem06 IS NOT NULL
		AND pa_nu_metragem06 IS NULL THEN
			vr_nu_metragem06 := pn_nu_metragem06;
		END IF;
		IF pn_nu_metragem06 IS NOT NULL
		AND pa_nu_metragem06 IS NOT NULL THEN
			IF pa_nu_metragem06 <> pn_nu_metragem06 THEN
				vr_nu_metragem06 := pn_nu_metragem06;
			ELSE
				vr_nu_metragem06 := pa_nu_metragem06;
			END IF;
		END IF;
		IF pn_co_tipo_sala07 IS NULL
		AND pa_co_tipo_sala07 IS NULL THEN
			vr_co_tipo_sala07 := 'null';
		END IF;
		IF pn_co_tipo_sala07 IS NULL
		AND pa_co_tipo_sala07 IS NOT NULL THEN
			vr_co_tipo_sala07 := 'null';
		END IF;
		IF pn_co_tipo_sala07 IS NOT NULL
		AND pa_co_tipo_sala07 IS NULL THEN
			vr_co_tipo_sala07 := pn_co_tipo_sala07;
		END IF;
		IF pn_co_tipo_sala07 IS NOT NULL
		AND pa_co_tipo_sala07 IS NOT NULL THEN
			IF pa_co_tipo_sala07 <> pn_co_tipo_sala07 THEN
				vr_co_tipo_sala07 := pn_co_tipo_sala07;
			ELSE
				vr_co_tipo_sala07 := pa_co_tipo_sala07;
			END IF;
		END IF;
		IF pn_co_seq_sala08 IS NULL
		AND pa_co_seq_sala08 IS NULL THEN
			vr_co_seq_sala08 := 'null';
		END IF;
		IF pn_co_seq_sala08 IS NULL
		AND pa_co_seq_sala08 IS NOT NULL THEN
			vr_co_seq_sala08 := 'null';
		END IF;
		IF pn_co_seq_sala08 IS NOT NULL
		AND pa_co_seq_sala08 IS NULL THEN
			vr_co_seq_sala08 := pn_co_seq_sala08;
		END IF;
		IF pn_co_seq_sala08 IS NOT NULL
		AND pa_co_seq_sala08 IS NOT NULL THEN
			IF pa_co_seq_sala08 <> pn_co_seq_sala08 THEN
				vr_co_seq_sala08 := pn_co_seq_sala08;
			ELSE
				vr_co_seq_sala08 := pa_co_seq_sala08;
			END IF;
		END IF;
		v_sql1 := 'update s_sala set co_bloco = ' || RTRIM(vr_co_bloco00) || '  , co_unidade = ' || RTRIM(vr_co_unidade01) || '  , co_sala = ' || RTRIM(vr_co_sala02) || '  , ds_sala = ' || RTRIM(vr_ds_sala03) || '  , co_seq_ambiente = ' || RTRIM(vr_co_seq_ambien04);
		v_sql2 := '  , nu_alunos_sala = ' || RTRIM(vr_nu_alunos_sal05) || '  , nu_metragem = ' || RTRIM(vr_nu_metragem06) || '  , co_tipo_sala = ' || RTRIM(vr_co_tipo_sala07) || '  , co_seq_sala = ' || RTRIM(vr_co_seq_sala08);
		v_sql3 := ' where co_bloco = ' || RTRIM(vr_co_bloco00) || '  and co_unidade = ' || RTRIM(vr_co_unidade01) || '  and co_sala = ' || RTRIM(vr_co_sala02) || ';';
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
		       's_sala',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_sala144;
/

