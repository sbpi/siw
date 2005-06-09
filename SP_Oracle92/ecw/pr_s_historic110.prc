CREATE OR REPLACE PROCEDURE pr_s_historic110(
	P_OP_IN                CHAR,
	PA_co_aluno00_IN       s_historico.co_aluno%TYPE,
	PA_ds_observacao01_IN  s_historico.ds_observacao%TYPE,
	PA_co_unidade02_IN     s_historico.co_unidade%TYPE,
	PA_ds_apto_grau03_IN   s_historico.ds_apto_grau%TYPE,
	PA_ds_apto_serie04_IN  s_historico.ds_apto_serie%TYPE,
	PN_co_aluno00_IN       s_historico.co_aluno%TYPE,
	PN_ds_observacao01_IN  s_historico.ds_observacao%TYPE,
	PN_co_unidade02_IN     s_historico.co_unidade%TYPE,
	PN_ds_apto_grau03_IN   s_historico.ds_apto_grau%TYPE,
	PN_ds_apto_serie04_IN  s_historico.ds_apto_serie%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_aluno00       s_historico.co_aluno%TYPE := PA_co_aluno00_IN;
PA_ds_observacao01  s_historico.ds_observacao%TYPE := PA_ds_observacao01_IN;
PA_co_unidade02     s_historico.co_unidade%TYPE := PA_co_unidade02_IN;
PA_ds_apto_grau03   s_historico.ds_apto_grau%TYPE := PA_ds_apto_grau03_IN;
PA_ds_apto_serie04  s_historico.ds_apto_serie%TYPE := PA_ds_apto_serie04_IN;
PN_co_aluno00       s_historico.co_aluno%TYPE := PN_co_aluno00_IN;
PN_ds_observacao01  s_historico.ds_observacao%TYPE := PN_ds_observacao01_IN;
PN_co_unidade02     s_historico.co_unidade%TYPE := PN_co_unidade02_IN;
PN_ds_apto_grau03   s_historico.ds_apto_grau%TYPE := PN_ds_apto_grau03_IN;
PN_ds_apto_serie04  s_historico.ds_apto_serie%TYPE := PN_ds_apto_serie04_IN;
v_blob1             s_historico.ds_observacao%TYPE;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_aluno00       CHAR(20);
vr_ds_observacao01  CHAR(10);
vr_co_unidade02     CHAR(10);
vr_ds_apto_grau03   CHAR(10);
vr_ds_apto_serie04  CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	v_blob1 := NULL;
	IF p_op = 'ins' THEN
		IF pn_co_aluno00 IS NULL THEN
			vr_co_aluno00 := 'null';
		ELSE
			vr_co_aluno00 := pn_co_aluno00;
		END IF;
		IF pn_ds_observacao01 IS NULL THEN
			vr_ds_observacao01 := NULL;
		ELSE
			vr_ds_observacao01 := ':vblob1';
		END IF;
		v_blob1 := pn_ds_observacao01;
		IF pn_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := pn_co_unidade02;
		END IF;
		IF pn_ds_apto_grau03 IS NULL THEN
			vr_ds_apto_grau03 := 'null';
		ELSE
			vr_ds_apto_grau03 := pn_ds_apto_grau03;
		END IF;
		IF pn_ds_apto_serie04 IS NULL THEN
			vr_ds_apto_serie04 := 'null';
		ELSE
			vr_ds_apto_serie04 := pn_ds_apto_serie04;
		END IF;
		v_sql1 := 'insert into s_historico(co_aluno, ds_observacao, co_unidade, ds_apto_grau, ds_apto_serie) values (';
		v_sql2 := '"' || RTRIM(vr_co_aluno00) || '"' || ',' || RTRIM(vr_ds_observacao01) || ',' || '"' || RTRIM(vr_co_unidade02) || '"' || ',' || '"' || RTRIM(vr_ds_apto_grau03) || '"' || ',' || '"' || RTRIM(vr_ds_apto_serie04) || '"' || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_aluno00 IS NULL THEN
			vr_co_aluno00 := 'null';
		ELSE
			vr_co_aluno00 := '"' || RTRIM(pa_co_aluno00) || '"';
		END IF;
		IF pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := '"' || RTRIM(pa_co_unidade02) || '"';
		END IF;
		v_sql1 := '  delete from s_historico where co_aluno = ' || RTRIM(vr_co_aluno00) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
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
		IF pn_ds_observacao01 IS NULL THEN
			vr_ds_observacao01 := NULL;
		ELSE
			vr_ds_observacao01 := ':vblob1';
		END IF;
		v_blob1 := pn_ds_observacao01;
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
		IF pn_ds_apto_grau03 IS NULL
		AND pa_ds_apto_grau03 IS NULL THEN
			vr_ds_apto_grau03 := 'null';
		END IF;
		IF pn_ds_apto_grau03 IS NULL
		AND pa_ds_apto_grau03 IS NOT NULL THEN
			vr_ds_apto_grau03 := 'null';
		END IF;
		IF pn_ds_apto_grau03 IS NOT NULL
		AND pa_ds_apto_grau03 IS NULL THEN
			vr_ds_apto_grau03 := '"' || RTRIM(pn_ds_apto_grau03) || '"';
		END IF;
		IF pn_ds_apto_grau03 IS NOT NULL
		AND pa_ds_apto_grau03 IS NOT NULL THEN
			IF pa_ds_apto_grau03 <> pn_ds_apto_grau03 THEN
				vr_ds_apto_grau03 := '"' || RTRIM(pn_ds_apto_grau03) || '"';
			ELSE
				vr_ds_apto_grau03 := '"' || RTRIM(pa_ds_apto_grau03) || '"';
			END IF;
		END IF;
		IF pn_ds_apto_serie04 IS NULL
		AND pa_ds_apto_serie04 IS NULL THEN
			vr_ds_apto_serie04 := 'null';
		END IF;
		IF pn_ds_apto_serie04 IS NULL
		AND pa_ds_apto_serie04 IS NOT NULL THEN
			vr_ds_apto_serie04 := 'null';
		END IF;
		IF pn_ds_apto_serie04 IS NOT NULL
		AND pa_ds_apto_serie04 IS NULL THEN
			vr_ds_apto_serie04 := '"' || RTRIM(pn_ds_apto_serie04) || '"';
		END IF;
		IF pn_ds_apto_serie04 IS NOT NULL
		AND pa_ds_apto_serie04 IS NOT NULL THEN
			IF pa_ds_apto_serie04 <> pn_ds_apto_serie04 THEN
				vr_ds_apto_serie04 := '"' || RTRIM(pn_ds_apto_serie04) || '"';
			ELSE
				vr_ds_apto_serie04 := '"' || RTRIM(pa_ds_apto_serie04) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_historico set co_aluno = ' || RTRIM(vr_co_aluno00) || '  , ds_observacao = ' || RTRIM(vr_ds_observacao01) || '  , co_unidade = ' || RTRIM(vr_co_unidade02) || '  , ds_apto_grau = ' || RTRIM(vr_ds_apto_grau03) || '  , ds_apto_serie = ' || RTRIM(vr_ds_apto_serie04);
		v_sql2 := ' where co_aluno = ' || RTRIM(vr_co_aluno00) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
		v_sql3 := '';
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
		       's_historico',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       v_blob1,
		       NULL);
	END IF;
END pr_s_historic110;
/

