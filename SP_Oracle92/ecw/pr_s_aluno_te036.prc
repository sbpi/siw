CREATE OR REPLACE PROCEDURE pr_s_aluno_te036(
	P_OP_IN                CHAR,
	PA_co_aluno00_IN       s_aluno_telefone.co_aluno%TYPE,
	PA_nu_seq_telefo01_IN  s_aluno_telefone.nu_seq_telefone%TYPE,
	PA_ds_telefone02_IN    s_aluno_telefone.ds_telefone%TYPE,
	PA_co_unidade03_IN     s_aluno_telefone.co_unidade%TYPE,
	PN_co_aluno00_IN       s_aluno_telefone.co_aluno%TYPE,
	PN_nu_seq_telefo01_IN  s_aluno_telefone.nu_seq_telefone%TYPE,
	PN_ds_telefone02_IN    s_aluno_telefone.ds_telefone%TYPE,
	PN_co_unidade03_IN     s_aluno_telefone.co_unidade%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_aluno00       s_aluno_telefone.co_aluno%TYPE := PA_co_aluno00_IN;
PA_nu_seq_telefo01  s_aluno_telefone.nu_seq_telefone%TYPE := PA_nu_seq_telefo01_IN;
PA_ds_telefone02    s_aluno_telefone.ds_telefone%TYPE := PA_ds_telefone02_IN;
PA_co_unidade03     s_aluno_telefone.co_unidade%TYPE := PA_co_unidade03_IN;
PN_co_aluno00       s_aluno_telefone.co_aluno%TYPE := PN_co_aluno00_IN;
PN_nu_seq_telefo01  s_aluno_telefone.nu_seq_telefone%TYPE := PN_nu_seq_telefo01_IN;
PN_ds_telefone02    s_aluno_telefone.ds_telefone%TYPE := PN_ds_telefone02_IN;
PN_co_unidade03     s_aluno_telefone.co_unidade%TYPE := PN_co_unidade03_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_aluno00       CHAR(20);
vr_nu_seq_telefo01  CHAR(10);
vr_ds_telefone02    CHAR(30);
vr_co_unidade03     CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_aluno00 IS NULL THEN
			vr_co_aluno00 := 'null';
		ELSE
			vr_co_aluno00 := pn_co_aluno00;
		END IF;
		IF pn_nu_seq_telefo01 IS NULL THEN
			vr_nu_seq_telefo01 := 'null';
		ELSE
			vr_nu_seq_telefo01 := pn_nu_seq_telefo01;
		END IF;
		IF pn_ds_telefone02 IS NULL THEN
			vr_ds_telefone02 := 'null';
		ELSE
			vr_ds_telefone02 := pn_ds_telefone02;
		END IF;
		IF pn_co_unidade03 IS NULL THEN
			vr_co_unidade03 := 'null';
		ELSE
			vr_co_unidade03 := pn_co_unidade03;
		END IF;
		v_sql1 := 'insert into s_aluno_telefone(co_aluno, nu_seq_telefone, ds_telefone, co_unidade) values (';
		v_sql2 := '"' || RTRIM(vr_co_aluno00) || '"' || ',' || RTRIM(vr_nu_seq_telefo01) || ',' || '"' || RTRIM(vr_ds_telefone02) || '"' || ',' || '"' || RTRIM(vr_co_unidade03) || '"' || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_aluno00 IS NULL THEN
			vr_co_aluno00 := 'null';
		ELSE
			vr_co_aluno00 := '"' || RTRIM(pa_co_aluno00) || '"';
		END IF;
		IF pa_nu_seq_telefo01 IS NULL THEN
			vr_nu_seq_telefo01 := 'null';
		ELSE
			vr_nu_seq_telefo01 := pa_nu_seq_telefo01;
		END IF;
		v_sql1 := '  delete from s_aluno_telefone where co_aluno = ' || RTRIM(vr_co_aluno00) || '  and nu_seq_telefone = ' || RTRIM(vr_nu_seq_telefo01) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_aluno00 IS NULL
		AND pa_co_aluno00 IS NULL THEN
			vr_co_aluno00 := 'null';
		END IF;
		IF pn_co_aluno00 IS NULL
		AND pa_co_aluno00 IS NOT NULL THEN
			vr_co_aluno00 := 'null';
		END IF;
		IF pn_co_aluno00 IS NOT NULL
		AND pa_co_aluno00 IS NULL THEN
			vr_co_aluno00 := '"' || RTRIM(pn_co_aluno00) || '"';
		END IF;
		IF pn_co_aluno00 IS NOT NULL
		AND pa_co_aluno00 IS NOT NULL THEN
			IF pa_co_aluno00 <> pn_co_aluno00 THEN
				vr_co_aluno00 := '"' || RTRIM(pn_co_aluno00) || '"';
			ELSE
				vr_co_aluno00 := '"' || RTRIM(pa_co_aluno00) || '"';
			END IF;
		END IF;
		IF pn_nu_seq_telefo01 IS NULL
		AND pa_nu_seq_telefo01 IS NULL THEN
			vr_nu_seq_telefo01 := 'null';
		END IF;
		IF pn_nu_seq_telefo01 IS NULL
		AND pa_nu_seq_telefo01 IS NOT NULL THEN
			vr_nu_seq_telefo01 := 'null';
		END IF;
		IF pn_nu_seq_telefo01 IS NOT NULL
		AND pa_nu_seq_telefo01 IS NULL THEN
			vr_nu_seq_telefo01 := pn_nu_seq_telefo01;
		END IF;
		IF pn_nu_seq_telefo01 IS NOT NULL
		AND pa_nu_seq_telefo01 IS NOT NULL THEN
			IF pa_nu_seq_telefo01 <> pn_nu_seq_telefo01 THEN
				vr_nu_seq_telefo01 := pn_nu_seq_telefo01;
			ELSE
				vr_nu_seq_telefo01 := pa_nu_seq_telefo01;
			END IF;
		END IF;
		IF pn_ds_telefone02 IS NULL
		AND pa_ds_telefone02 IS NULL THEN
			vr_ds_telefone02 := 'null';
		END IF;
		IF pn_ds_telefone02 IS NULL
		AND pa_ds_telefone02 IS NOT NULL THEN
			vr_ds_telefone02 := 'null';
		END IF;
		IF pn_ds_telefone02 IS NOT NULL
		AND pa_ds_telefone02 IS NULL THEN
			vr_ds_telefone02 := '"' || RTRIM(pn_ds_telefone02) || '"';
		END IF;
		IF pn_ds_telefone02 IS NOT NULL
		AND pa_ds_telefone02 IS NOT NULL THEN
			IF pa_ds_telefone02 <> pn_ds_telefone02 THEN
				vr_ds_telefone02 := '"' || RTRIM(pn_ds_telefone02) || '"';
			ELSE
				vr_ds_telefone02 := '"' || RTRIM(pa_ds_telefone02) || '"';
			END IF;
		END IF;
		IF pn_co_unidade03 IS NULL
		AND pa_co_unidade03 IS NULL THEN
			vr_co_unidade03 := 'null';
		END IF;
		IF pn_co_unidade03 IS NULL
		AND pa_co_unidade03 IS NOT NULL THEN
			vr_co_unidade03 := 'null';
		END IF;
		IF pn_co_unidade03 IS NOT NULL
		AND pa_co_unidade03 IS NULL THEN
			vr_co_unidade03 := '"' || RTRIM(pn_co_unidade03) || '"';
		END IF;
		IF pn_co_unidade03 IS NOT NULL
		AND pa_co_unidade03 IS NOT NULL THEN
			IF pa_co_unidade03 <> pn_co_unidade03 THEN
				vr_co_unidade03 := '"' || RTRIM(pn_co_unidade03) || '"';
			ELSE
				vr_co_unidade03 := '"' || RTRIM(pa_co_unidade03) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_aluno_telefone set co_aluno = ' || RTRIM(vr_co_aluno00) || '  , nu_seq_telefone = ' || RTRIM(vr_nu_seq_telefo01) || '  , ds_telefone = ' || RTRIM(vr_ds_telefone02) || '  , co_unidade = ' || RTRIM(vr_co_unidade03);
		v_sql2 := ' where co_aluno = ' || RTRIM(vr_co_aluno00) || '  and nu_seq_telefone = ' || RTRIM(vr_nu_seq_telefo01) || ';';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	END IF;
	IF p_op = 'del' THEN
		v_uni := pa_co_unidade03;
	ELSE
		v_uni := pn_co_unidade03;
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
		       's_aluno_telefone',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_aluno_te036;
/

