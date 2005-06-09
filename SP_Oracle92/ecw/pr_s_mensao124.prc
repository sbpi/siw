CREATE OR REPLACE PROCEDURE pr_s_mensao124(
	P_OP_IN                CHAR,
	PA_co_mensao00_IN      s_mensao.co_mensao%TYPE,
	PA_ds_mensao01_IN      s_mensao.ds_mensao%TYPE,
	PA_co_curso02_IN       s_mensao.co_curso%TYPE,
	PA_ano_sem03_IN        s_mensao.ano_sem%TYPE,
	PA_nu_nota_corre04_IN  s_mensao.nu_nota_corresp%TYPE,
	PA_co_unidade05_IN     s_mensao.co_unidade%TYPE,
	PA_nu_nota_inici06_IN  s_mensao.nu_nota_inicio%TYPE,
	PA_nu_nota_fim07_IN    s_mensao.nu_nota_fim%TYPE,
	PN_co_mensao00_IN      s_mensao.co_mensao%TYPE,
	PN_ds_mensao01_IN      s_mensao.ds_mensao%TYPE,
	PN_co_curso02_IN       s_mensao.co_curso%TYPE,
	PN_ano_sem03_IN        s_mensao.ano_sem%TYPE,
	PN_nu_nota_corre04_IN  s_mensao.nu_nota_corresp%TYPE,
	PN_co_unidade05_IN     s_mensao.co_unidade%TYPE,
	PN_nu_nota_inici06_IN  s_mensao.nu_nota_inicio%TYPE,
	PN_nu_nota_fim07_IN    s_mensao.nu_nota_fim%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_mensao00      s_mensao.co_mensao%TYPE := PA_co_mensao00_IN;
PA_ds_mensao01      s_mensao.ds_mensao%TYPE := PA_ds_mensao01_IN;
PA_co_curso02       s_mensao.co_curso%TYPE := PA_co_curso02_IN;
PA_ano_sem03        s_mensao.ano_sem%TYPE := PA_ano_sem03_IN;
PA_nu_nota_corre04  s_mensao.nu_nota_corresp%TYPE := PA_nu_nota_corre04_IN;
PA_co_unidade05     s_mensao.co_unidade%TYPE := PA_co_unidade05_IN;
PA_nu_nota_inici06  s_mensao.nu_nota_inicio%TYPE := PA_nu_nota_inici06_IN;
PA_nu_nota_fim07    s_mensao.nu_nota_fim%TYPE := PA_nu_nota_fim07_IN;
PN_co_mensao00      s_mensao.co_mensao%TYPE := PN_co_mensao00_IN;
PN_ds_mensao01      s_mensao.ds_mensao%TYPE := PN_ds_mensao01_IN;
PN_co_curso02       s_mensao.co_curso%TYPE := PN_co_curso02_IN;
PN_ano_sem03        s_mensao.ano_sem%TYPE := PN_ano_sem03_IN;
PN_nu_nota_corre04  s_mensao.nu_nota_corresp%TYPE := PN_nu_nota_corre04_IN;
PN_co_unidade05     s_mensao.co_unidade%TYPE := PN_co_unidade05_IN;
PN_nu_nota_inici06  s_mensao.nu_nota_inicio%TYPE := PN_nu_nota_inici06_IN;
PN_nu_nota_fim07    s_mensao.nu_nota_fim%TYPE := PN_nu_nota_fim07_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_mensao00      CHAR(10);
vr_ds_mensao01      CHAR(40);
vr_co_curso02       CHAR(10);
vr_ano_sem03        CHAR(10);
vr_nu_nota_corre04  CHAR(10);
vr_co_unidade05     CHAR(10);
vr_nu_nota_inici06  CHAR(10);
vr_nu_nota_fim07    CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_mensao00 IS NULL THEN
			vr_co_mensao00 := 'null';
		ELSE
			vr_co_mensao00 := pn_co_mensao00;
		END IF;
		IF pn_ds_mensao01 IS NULL THEN
			vr_ds_mensao01 := 'null';
		ELSE
			vr_ds_mensao01 := pn_ds_mensao01;
		END IF;
		IF pn_co_curso02 IS NULL THEN
			vr_co_curso02 := 'null';
		ELSE
			vr_co_curso02 := pn_co_curso02;
		END IF;
		IF pn_ano_sem03 IS NULL THEN
			vr_ano_sem03 := 'null';
		ELSE
			vr_ano_sem03 := pn_ano_sem03;
		END IF;
		IF pn_nu_nota_corre04 IS NULL THEN
			vr_nu_nota_corre04 := 'null';
		ELSE
			vr_nu_nota_corre04 := pn_nu_nota_corre04;
		END IF;
		IF pn_co_unidade05 IS NULL THEN
			vr_co_unidade05 := 'null';
		ELSE
			vr_co_unidade05 := pn_co_unidade05;
		END IF;
		IF pn_nu_nota_inici06 IS NULL THEN
			vr_nu_nota_inici06 := 'null';
		ELSE
			vr_nu_nota_inici06 := pn_nu_nota_inici06;
		END IF;
		IF pn_nu_nota_fim07 IS NULL THEN
			vr_nu_nota_fim07 := 'null';
		ELSE
			vr_nu_nota_fim07 := pn_nu_nota_fim07;
		END IF;
		v_sql1 := 'insert into s_mensao(co_mensao, ds_mensao, co_curso, ano_sem, NU_NOTA_CORRESPONDENTE, co_unidade, nu_nota_inicio, nu_nota_fim) values (';
		v_sql2 := '"' || RTRIM(vr_co_mensao00) || '"' || ',' || '"' || RTRIM(vr_ds_mensao01) || '"' || ',' || RTRIM(vr_co_curso02) || ',' || '"' || RTRIM(vr_ano_sem03) || '"' || ',' || RTRIM(vr_nu_nota_corre04) || ',';
		v_sql3 := '"' || RTRIM(vr_co_unidade05) || '"' || ',' || RTRIM(vr_nu_nota_inici06) || ',' || RTRIM(vr_nu_nota_fim07) || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_mensao00 IS NULL THEN
			vr_co_mensao00 := 'null';
		ELSE
			vr_co_mensao00 := '"' || RTRIM(pa_co_mensao00) || '"';
		END IF;
		IF pa_co_curso02 IS NULL THEN
			vr_co_curso02 := 'null';
		ELSE
			vr_co_curso02 := pa_co_curso02;
		END IF;
		IF pa_ano_sem03 IS NULL THEN
			vr_ano_sem03 := 'null';
		ELSE
			vr_ano_sem03 := '"' || RTRIM(pa_ano_sem03) || '"';
		END IF;
		IF pa_co_unidade05 IS NULL THEN
			vr_co_unidade05 := 'null';
		ELSE
			vr_co_unidade05 := '"' || RTRIM(pa_co_unidade05) || '"';
		END IF;
		v_sql1 := '  delete from s_mensao where co_mensao = ' || RTRIM(vr_co_mensao00) || '  and co_curso = ' || RTRIM(vr_co_curso02) || '  and ano_sem = ' || RTRIM(vr_ano_sem03) || '  and co_unidade = ' || RTRIM(vr_co_unidade05) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_mensao00 IS NULL
		AND pa_co_mensao00 IS NULL THEN
			vr_co_mensao00 := 'null';
		END IF;
		IF pn_co_mensao00 IS NULL
		AND pa_co_mensao00 IS NOT NULL THEN
			vr_co_mensao00 := 'null';
		END IF;
		IF pn_co_mensao00 IS NOT NULL
		AND pa_co_mensao00 IS NULL THEN
			vr_co_mensao00 := '"' || RTRIM(pn_co_mensao00) || '"';
		END IF;
		IF pn_co_mensao00 IS NOT NULL
		AND pa_co_mensao00 IS NOT NULL THEN
			IF pa_co_mensao00 <> pn_co_mensao00 THEN
				vr_co_mensao00 := '"' || RTRIM(pn_co_mensao00) || '"';
			ELSE
				vr_co_mensao00 := '"' || RTRIM(pa_co_mensao00) || '"';
			END IF;
		END IF;
		IF pn_ds_mensao01 IS NULL
		AND pa_ds_mensao01 IS NULL THEN
			vr_ds_mensao01 := 'null';
		END IF;
		IF pn_ds_mensao01 IS NULL
		AND pa_ds_mensao01 IS NOT NULL THEN
			vr_ds_mensao01 := 'null';
		END IF;
		IF pn_ds_mensao01 IS NOT NULL
		AND pa_ds_mensao01 IS NULL THEN
			vr_ds_mensao01 := '"' || RTRIM(pn_ds_mensao01) || '"';
		END IF;
		IF pn_ds_mensao01 IS NOT NULL
		AND pa_ds_mensao01 IS NOT NULL THEN
			IF pa_ds_mensao01 <> pn_ds_mensao01 THEN
				vr_ds_mensao01 := '"' || RTRIM(pn_ds_mensao01) || '"';
			ELSE
				vr_ds_mensao01 := '"' || RTRIM(pa_ds_mensao01) || '"';
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
		IF pn_nu_nota_corre04 IS NULL
		AND pa_nu_nota_corre04 IS NULL THEN
			vr_nu_nota_corre04 := 'null';
		END IF;
		IF pn_nu_nota_corre04 IS NULL
		AND pa_nu_nota_corre04 IS NOT NULL THEN
			vr_nu_nota_corre04 := 'null';
		END IF;
		IF pn_nu_nota_corre04 IS NOT NULL
		AND pa_nu_nota_corre04 IS NULL THEN
			vr_nu_nota_corre04 := pn_nu_nota_corre04;
		END IF;
		IF pn_nu_nota_corre04 IS NOT NULL
		AND pa_nu_nota_corre04 IS NOT NULL THEN
			IF pa_nu_nota_corre04 <> pn_nu_nota_corre04 THEN
				vr_nu_nota_corre04 := pn_nu_nota_corre04;
			ELSE
				vr_nu_nota_corre04 := pa_nu_nota_corre04;
			END IF;
		END IF;
		IF pn_co_unidade05 IS NULL
		AND pa_co_unidade05 IS NULL THEN
			vr_co_unidade05 := 'null';
		END IF;
		IF pn_co_unidade05 IS NULL
		AND pa_co_unidade05 IS NOT NULL THEN
			vr_co_unidade05 := 'null';
		END IF;
		IF pn_co_unidade05 IS NOT NULL
		AND pa_co_unidade05 IS NULL THEN
			vr_co_unidade05 := '"' || RTRIM(pn_co_unidade05) || '"';
		END IF;
		IF pn_co_unidade05 IS NOT NULL
		AND pa_co_unidade05 IS NOT NULL THEN
			IF pa_co_unidade05 <> pn_co_unidade05 THEN
				vr_co_unidade05 := '"' || RTRIM(pn_co_unidade05) || '"';
			ELSE
				vr_co_unidade05 := '"' || RTRIM(pa_co_unidade05) || '"';
			END IF;
		END IF;
		IF pn_nu_nota_inici06 IS NULL
		AND pa_nu_nota_inici06 IS NULL THEN
			vr_nu_nota_inici06 := 'null';
		END IF;
		IF pn_nu_nota_inici06 IS NULL
		AND pa_nu_nota_inici06 IS NOT NULL THEN
			vr_nu_nota_inici06 := 'null';
		END IF;
		IF pn_nu_nota_inici06 IS NOT NULL
		AND pa_nu_nota_inici06 IS NULL THEN
			vr_nu_nota_inici06 := pn_nu_nota_inici06;
		END IF;
		IF pn_nu_nota_inici06 IS NOT NULL
		AND pa_nu_nota_inici06 IS NOT NULL THEN
			IF pa_nu_nota_inici06 <> pn_nu_nota_inici06 THEN
				vr_nu_nota_inici06 := pn_nu_nota_inici06;
			ELSE
				vr_nu_nota_inici06 := pa_nu_nota_inici06;
			END IF;
		END IF;
		IF pn_nu_nota_fim07 IS NULL
		AND pa_nu_nota_fim07 IS NULL THEN
			vr_nu_nota_fim07 := 'null';
		END IF;
		IF pn_nu_nota_fim07 IS NULL
		AND pa_nu_nota_fim07 IS NOT NULL THEN
			vr_nu_nota_fim07 := 'null';
		END IF;
		IF pn_nu_nota_fim07 IS NOT NULL
		AND pa_nu_nota_fim07 IS NULL THEN
			vr_nu_nota_fim07 := pn_nu_nota_fim07;
		END IF;
		IF pn_nu_nota_fim07 IS NOT NULL
		AND pa_nu_nota_fim07 IS NOT NULL THEN
			IF pa_nu_nota_fim07 <> pn_nu_nota_fim07 THEN
				vr_nu_nota_fim07 := pn_nu_nota_fim07;
			ELSE
				vr_nu_nota_fim07 := pa_nu_nota_fim07;
			END IF;
		END IF;
		v_sql1 := 'update s_mensao set co_mensao = ' || RTRIM(vr_co_mensao00) || '  , ds_mensao = ' || RTRIM(vr_ds_mensao01) || '  , co_curso = ' || RTRIM(vr_co_curso02) || '  , ano_sem = ' || RTRIM(vr_ano_sem03) || '  , NU_NOTA_CORRESPONDENTE = ' || RTRIM(vr_nu_nota_corre04);
		v_sql2 := '  , co_unidade = ' || RTRIM(vr_co_unidade05) || '  , nu_nota_inicio = ' || RTRIM(vr_nu_nota_inici06) || '  , nu_nota_fim = ' || RTRIM(vr_nu_nota_fim07);
		v_sql3 := ' where co_mensao = ' || RTRIM(vr_co_mensao00) || '  and co_curso = ' || RTRIM(vr_co_curso02) || '  and ano_sem = ' || RTRIM(vr_ano_sem03) || '  and co_unidade = ' || RTRIM(vr_co_unidade05) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	END IF;
	IF p_op = 'del' THEN
		v_uni := pa_co_unidade05;
	ELSE
		v_uni := pn_co_unidade05;
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
		       's_mensao',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_mensao124;
/

