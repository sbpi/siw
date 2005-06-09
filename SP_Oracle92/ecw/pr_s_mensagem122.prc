CREATE OR REPLACE PROCEDURE pr_s_mensagem122(
	P_OP_IN                CHAR,
	PA_ano_sem00_IN        s_mensagem.ano_sem%TYPE,
	PA_ds_relatorio01_IN   s_mensagem.ds_relatorio%TYPE,
	PA_ds_mensagem02_IN    s_mensagem.ds_mensagem%TYPE,
	PA_co_aluno03_IN       s_mensagem.co_aluno%TYPE,
	PA_co_unidade04_IN     s_mensagem.co_unidade%TYPE,
	PN_ano_sem00_IN        s_mensagem.ano_sem%TYPE,
	PN_ds_relatorio01_IN   s_mensagem.ds_relatorio%TYPE,
	PN_ds_mensagem02_IN    s_mensagem.ds_mensagem%TYPE,
	PN_co_aluno03_IN       s_mensagem.co_aluno%TYPE,
	PN_co_unidade04_IN     s_mensagem.co_unidade%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_ano_sem00        s_mensagem.ano_sem%TYPE := PA_ano_sem00_IN;
PA_ds_relatorio01   s_mensagem.ds_relatorio%TYPE := PA_ds_relatorio01_IN;
PA_ds_mensagem02    s_mensagem.ds_mensagem%TYPE := PA_ds_mensagem02_IN;
PA_co_aluno03       s_mensagem.co_aluno%TYPE := PA_co_aluno03_IN;
PA_co_unidade04     s_mensagem.co_unidade%TYPE := PA_co_unidade04_IN;
PN_ano_sem00        s_mensagem.ano_sem%TYPE := PN_ano_sem00_IN;
PN_ds_relatorio01   s_mensagem.ds_relatorio%TYPE := PN_ds_relatorio01_IN;
PN_ds_mensagem02    s_mensagem.ds_mensagem%TYPE := PN_ds_mensagem02_IN;
PN_co_aluno03       s_mensagem.co_aluno%TYPE := PN_co_aluno03_IN;
PN_co_unidade04     s_mensagem.co_unidade%TYPE := PN_co_unidade04_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_ano_sem00        CHAR(10);
vr_ds_relatorio01   CHAR(30);
vr_ds_mensagem02    CHAR(265);
vr_co_aluno03       CHAR(20);
vr_co_unidade04     CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_ano_sem00 IS NULL THEN
			vr_ano_sem00 := 'null';
		ELSE
			vr_ano_sem00 := pn_ano_sem00;
		END IF;
		IF pn_ds_relatorio01 IS NULL THEN
			vr_ds_relatorio01 := 'null';
		ELSE
			vr_ds_relatorio01 := pn_ds_relatorio01;
		END IF;
		IF pn_ds_mensagem02 IS NULL THEN
			vr_ds_mensagem02 := 'null';
		ELSE
			vr_ds_mensagem02 := pn_ds_mensagem02;
		END IF;
		IF pn_co_aluno03 IS NULL THEN
			vr_co_aluno03 := 'null';
		ELSE
			vr_co_aluno03 := pn_co_aluno03;
		END IF;
		IF pn_co_unidade04 IS NULL THEN
			vr_co_unidade04 := 'null';
		ELSE
			vr_co_unidade04 := pn_co_unidade04;
		END IF;
		v_sql1 := 'insert into s_mensagem(ano_sem, ds_relatorio, ds_mensagem, co_aluno, co_unidade) values (';
		v_sql2 := '"' || RTRIM(vr_ano_sem00) || '"' || ',' || '"' || RTRIM(vr_ds_relatorio01) || '"' || ',' || '"' || RTRIM(vr_ds_mensagem02) || '"' || ',' || '"' || RTRIM(vr_co_aluno03) || '"' || ',' || '"' || RTRIM(vr_co_unidade04) || '"' || ');';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'del' THEN
		IF pa_ano_sem00 IS NULL THEN
			vr_ano_sem00 := 'null';
		ELSE
			vr_ano_sem00 := '"' || RTRIM(pa_ano_sem00) || '"';
		END IF;
		IF pa_ds_relatorio01 IS NULL THEN
			vr_ds_relatorio01 := 'null';
		ELSE
			vr_ds_relatorio01 := '"' || RTRIM(pa_ds_relatorio01) || '"';
		END IF;
		IF pa_co_aluno03 IS NULL THEN
			vr_co_aluno03 := 'null';
		ELSE
			vr_co_aluno03 := '"' || RTRIM(pa_co_aluno03) || '"';
		END IF;
		IF pa_co_unidade04 IS NULL THEN
			vr_co_unidade04 := 'null';
		ELSE
			vr_co_unidade04 := '"' || RTRIM(pa_co_unidade04) || '"';
		END IF;
		v_sql1 := '  delete from s_mensagem where ano_sem = ' || RTRIM(vr_ano_sem00) || '  and ds_relatorio = ' || RTRIM(vr_ds_relatorio01) || '  and co_aluno = ' || RTRIM(vr_co_aluno03) || '  and co_unidade = ' || RTRIM(vr_co_unidade04) || ';';
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
		IF pn_ds_relatorio01 IS NULL
		AND pa_ds_relatorio01 IS NULL THEN
			vr_ds_relatorio01 := 'null';
		END IF;
		IF pn_ds_relatorio01 IS NULL
		AND pa_ds_relatorio01 IS NOT NULL THEN
			vr_ds_relatorio01 := 'null';
		END IF;
		IF pn_ds_relatorio01 IS NOT NULL
		AND pa_ds_relatorio01 IS NULL THEN
			vr_ds_relatorio01 := '"' || RTRIM(pn_ds_relatorio01) || '"';
		END IF;
		IF pn_ds_relatorio01 IS NOT NULL
		AND pa_ds_relatorio01 IS NOT NULL THEN
			IF pa_ds_relatorio01 <> pn_ds_relatorio01 THEN
				vr_ds_relatorio01 := '"' || RTRIM(pn_ds_relatorio01) || '"';
			ELSE
				vr_ds_relatorio01 := '"' || RTRIM(pa_ds_relatorio01) || '"';
			END IF;
		END IF;
		IF pn_ds_mensagem02 IS NULL
		AND pa_ds_mensagem02 IS NULL THEN
			vr_ds_mensagem02 := 'null';
		END IF;
		IF pn_ds_mensagem02 IS NULL
		AND pa_ds_mensagem02 IS NOT NULL THEN
			vr_ds_mensagem02 := 'null';
		END IF;
		IF pn_ds_mensagem02 IS NOT NULL
		AND pa_ds_mensagem02 IS NULL THEN
			vr_ds_mensagem02 := '"' || RTRIM(pn_ds_mensagem02) || '"';
		END IF;
		IF pn_ds_mensagem02 IS NOT NULL
		AND pa_ds_mensagem02 IS NOT NULL THEN
			IF pa_ds_mensagem02 <> pn_ds_mensagem02 THEN
				vr_ds_mensagem02 := '"' || RTRIM(pn_ds_mensagem02) || '"';
			ELSE
				vr_ds_mensagem02 := '"' || RTRIM(pa_ds_mensagem02) || '"';
			END IF;
		END IF;
		IF pn_co_aluno03 IS NULL
		AND pa_co_aluno03 IS NULL THEN
			vr_co_aluno03 := 'null';
		END IF;
		IF pn_co_aluno03 IS NULL
		AND pa_co_aluno03 IS NOT NULL THEN
			vr_co_aluno03 := 'null';
		END IF;
		IF pn_co_aluno03 IS NOT NULL
		AND pa_co_aluno03 IS NULL THEN
			vr_co_aluno03 := '"' || RTRIM(pn_co_aluno03) || '"';
		END IF;
		IF pn_co_aluno03 IS NOT NULL
		AND pa_co_aluno03 IS NOT NULL THEN
			IF pa_co_aluno03 <> pn_co_aluno03 THEN
				vr_co_aluno03 := '"' || RTRIM(pn_co_aluno03) || '"';
			ELSE
				vr_co_aluno03 := '"' || RTRIM(pa_co_aluno03) || '"';
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
		v_sql1 := 'update s_mensagem set ano_sem = ' || RTRIM(vr_ano_sem00) || '  , ds_relatorio = ' || RTRIM(vr_ds_relatorio01) || '  , ds_mensagem = ' || RTRIM(vr_ds_mensagem02) || '  , co_aluno = ' || RTRIM(vr_co_aluno03) || '  , co_unidade = ' || RTRIM(vr_co_unidade04);
		v_sql2 := ' where ano_sem = ' || RTRIM(vr_ano_sem00) || '  and ds_relatorio = ' || RTRIM(vr_ds_relatorio01) || '  and co_aluno = ' || RTRIM(vr_co_aluno03) || '  and co_unidade = ' || RTRIM(vr_co_unidade04) || ';';
		v_sql3 := '';
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
		       's_mensagem',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_mensagem122;
/

