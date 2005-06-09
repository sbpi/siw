CREATE OR REPLACE PROCEDURE pr_s_observac128(
	P_OP_IN                CHAR,
	PA_co_observacao00_IN  s_observacoes.co_observacao%TYPE,
	PA_nome_observac01_IN  s_observacoes.nome_observacao%TYPE,
	PA_co_unidade02_IN     s_observacoes.co_unidade%TYPE,
	PA_ds_observacao03_IN  s_observacoes.ds_observacao%TYPE,
	PN_co_observacao00_IN  s_observacoes.co_observacao%TYPE,
	PN_nome_observac01_IN  s_observacoes.nome_observacao%TYPE,
	PN_co_unidade02_IN     s_observacoes.co_unidade%TYPE,
	PN_ds_observacao03_IN  s_observacoes.ds_observacao%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_observacao00  s_observacoes.co_observacao%TYPE := PA_co_observacao00_IN;
PA_nome_observac01  s_observacoes.nome_observacao%TYPE := PA_nome_observac01_IN;
PA_co_unidade02     s_observacoes.co_unidade%TYPE := PA_co_unidade02_IN;
PA_ds_observacao03  s_observacoes.ds_observacao%TYPE := PA_ds_observacao03_IN;
PN_co_observacao00  s_observacoes.co_observacao%TYPE := PN_co_observacao00_IN;
PN_nome_observac01  s_observacoes.nome_observacao%TYPE := PN_nome_observac01_IN;
PN_co_unidade02     s_observacoes.co_unidade%TYPE := PN_co_unidade02_IN;
PN_ds_observacao03  s_observacoes.ds_observacao%TYPE := PN_ds_observacao03_IN;
v_blob1             s_observacoes.ds_observacao%TYPE;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_observacao00  CHAR(10);
vr_nome_observac01  CHAR(60);
vr_co_unidade02     CHAR(10);
vr_ds_observacao03  CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	v_blob1 := NULL;
	IF p_op = 'ins' THEN
		IF pn_co_observacao00 IS NULL THEN
			vr_co_observacao00 := 'null';
		ELSE
			vr_co_observacao00 := pn_co_observacao00;
		END IF;
		IF pn_nome_observac01 IS NULL THEN
			vr_nome_observac01 := 'null';
		ELSE
			vr_nome_observac01 := pn_nome_observac01;
		END IF;
		IF pn_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := pn_co_unidade02;
		END IF;
		IF pn_ds_observacao03 IS NULL THEN
			vr_ds_observacao03 := NULL;
		ELSE
			vr_ds_observacao03 := ':vblob1';
		END IF;
		v_blob1 := pn_ds_observacao03;
		v_sql1 := 'insert into s_observacoes(co_observacao, nome_observacao, co_unidade, ds_observacao) values (';
		v_sql2 := RTRIM(vr_co_observacao00) || ',' || '"' || RTRIM(vr_nome_observac01) || '"' || ',' || '"' || RTRIM(vr_co_unidade02) || '"' || ',' || RTRIM(vr_ds_observacao03) || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_co_observacao00 IS NULL THEN
			vr_co_observacao00 := 'null';
		ELSE
			vr_co_observacao00 := pa_co_observacao00;
		END IF;
		IF pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := '"' || RTRIM(pa_co_unidade02) || '"';
		END IF;
		v_sql1 := '  delete from s_observacoes where co_observacao = ' || RTRIM(vr_co_observacao00) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_observacao00 IS NULL
		AND pa_co_observacao00 IS NULL THEN
			vr_co_observacao00 := 'null';
		END IF;
		IF pn_co_observacao00 IS NULL
		AND pa_co_observacao00 IS NOT NULL THEN
			vr_co_observacao00 := 'null';
		END IF;
		IF pn_co_observacao00 IS NOT NULL
		AND pa_co_observacao00 IS NULL THEN
			vr_co_observacao00 := pn_co_observacao00;
		END IF;
		IF pn_co_observacao00 IS NOT NULL
		AND pa_co_observacao00 IS NOT NULL THEN
			IF pa_co_observacao00 <> pn_co_observacao00 THEN
				vr_co_observacao00 := pn_co_observacao00;
			ELSE
				vr_co_observacao00 := pa_co_observacao00;
			END IF;
		END IF;
		IF pn_nome_observac01 IS NULL
		AND pa_nome_observac01 IS NULL THEN
			vr_nome_observac01 := 'null';
		END IF;
		IF pn_nome_observac01 IS NULL
		AND pa_nome_observac01 IS NOT NULL THEN
			vr_nome_observac01 := 'null';
		END IF;
		IF pn_nome_observac01 IS NOT NULL
		AND pa_nome_observac01 IS NULL THEN
			vr_nome_observac01 := '"' || RTRIM(pn_nome_observac01) || '"';
		END IF;
		IF pn_nome_observac01 IS NOT NULL
		AND pa_nome_observac01 IS NOT NULL THEN
			IF pa_nome_observac01 <> pn_nome_observac01 THEN
				vr_nome_observac01 := '"' || RTRIM(pn_nome_observac01) || '"';
			ELSE
				vr_nome_observac01 := '"' || RTRIM(pa_nome_observac01) || '"';
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
		IF pn_ds_observacao03 IS NULL THEN
			vr_ds_observacao03 := NULL;
		ELSE
			vr_ds_observacao03 := ':vblob1';
		END IF;
		v_blob1 := pn_ds_observacao03;
		v_sql1 := 'update s_observacoes set co_observacao = ' || RTRIM(vr_co_observacao00) || '  , nome_observacao = ' || RTRIM(vr_nome_observac01) || '  , co_unidade = ' || RTRIM(vr_co_unidade02) || '  , ds_observacao = ' || RTRIM(vr_ds_observacao03);
		v_sql2 := ' where co_observacao = ' || RTRIM(vr_co_observacao00) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
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
		       's_observacoes',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       v_blob1,
		       NULL);
	END IF;
END pr_s_observac128;
/

