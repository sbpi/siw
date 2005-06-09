CREATE OR REPLACE PROCEDURE pr_s_historic112(
	P_OP_IN                CHAR,
	PA_co_aluno00_IN       s_historico_fase.co_aluno%TYPE,
	PA_fase01_IN           s_historico_fase.fase%TYPE,
	PA_co_unidade02_IN     s_historico_fase.co_unidade%TYPE,
	PA_ano03_IN            s_historico_fase.ano%TYPE,
	PA_idade04_IN          s_historico_fase.idade%TYPE,
	PA_nu_dias_letiv05_IN  s_historico_fase.nu_dias_letivos%TYPE,
	PA_carga_horaria06_IN  s_historico_fase.carga_horaria%TYPE,
	PA_faltas07_IN         s_historico_fase.faltas%TYPE,
	PA_resultado08_IN      s_historico_fase.resultado%TYPE,
	PA_no_estab_ensi09_IN  s_historico_fase.no_estab_ensino%TYPE,
	PA_no_cidade_ens10_IN  s_historico_fase.no_cidade_ensino%TYPE,
	PA_sg_uf_ensino11_IN   s_historico_fase.sg_uf_ensino%TYPE,
	PN_co_aluno00_IN       s_historico_fase.co_aluno%TYPE,
	PN_fase01_IN           s_historico_fase.fase%TYPE,
	PN_co_unidade02_IN     s_historico_fase.co_unidade%TYPE,
	PN_ano03_IN            s_historico_fase.ano%TYPE,
	PN_idade04_IN          s_historico_fase.idade%TYPE,
	PN_nu_dias_letiv05_IN  s_historico_fase.nu_dias_letivos%TYPE,
	PN_carga_horaria06_IN  s_historico_fase.carga_horaria%TYPE,
	PN_faltas07_IN         s_historico_fase.faltas%TYPE,
	PN_resultado08_IN      s_historico_fase.resultado%TYPE,
	PN_no_estab_ensi09_IN  s_historico_fase.no_estab_ensino%TYPE,
	PN_no_cidade_ens10_IN  s_historico_fase.no_cidade_ensino%TYPE,
	PN_sg_uf_ensino11_IN   s_historico_fase.sg_uf_ensino%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_aluno00       s_historico_fase.co_aluno%TYPE := PA_co_aluno00_IN;
PA_fase01           s_historico_fase.fase%TYPE := PA_fase01_IN;
PA_co_unidade02     s_historico_fase.co_unidade%TYPE := PA_co_unidade02_IN;
PA_ano03            s_historico_fase.ano%TYPE := PA_ano03_IN;
PA_idade04          s_historico_fase.idade%TYPE := PA_idade04_IN;
PA_nu_dias_letiv05  s_historico_fase.nu_dias_letivos%TYPE := PA_nu_dias_letiv05_IN;
PA_carga_horaria06  s_historico_fase.carga_horaria%TYPE := PA_carga_horaria06_IN;
PA_faltas07         s_historico_fase.faltas%TYPE := PA_faltas07_IN;
PA_resultado08      s_historico_fase.resultado%TYPE := PA_resultado08_IN;
PA_no_estab_ensi09  s_historico_fase.no_estab_ensino%TYPE := PA_no_estab_ensi09_IN;
PA_no_cidade_ens10  s_historico_fase.no_cidade_ensino%TYPE := PA_no_cidade_ens10_IN;
PA_sg_uf_ensino11   s_historico_fase.sg_uf_ensino%TYPE := PA_sg_uf_ensino11_IN;
PN_co_aluno00       s_historico_fase.co_aluno%TYPE := PN_co_aluno00_IN;
PN_fase01           s_historico_fase.fase%TYPE := PN_fase01_IN;
PN_co_unidade02     s_historico_fase.co_unidade%TYPE := PN_co_unidade02_IN;
PN_ano03            s_historico_fase.ano%TYPE := PN_ano03_IN;
PN_idade04          s_historico_fase.idade%TYPE := PN_idade04_IN;
PN_nu_dias_letiv05  s_historico_fase.nu_dias_letivos%TYPE := PN_nu_dias_letiv05_IN;
PN_carga_horaria06  s_historico_fase.carga_horaria%TYPE := PN_carga_horaria06_IN;
PN_faltas07         s_historico_fase.faltas%TYPE := PN_faltas07_IN;
PN_resultado08      s_historico_fase.resultado%TYPE := PN_resultado08_IN;
PN_no_estab_ensi09  s_historico_fase.no_estab_ensino%TYPE := PN_no_estab_ensi09_IN;
PN_no_cidade_ens10  s_historico_fase.no_cidade_ensino%TYPE := PN_no_cidade_ens10_IN;
PN_sg_uf_ensino11   s_historico_fase.sg_uf_ensino%TYPE := PN_sg_uf_ensino11_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(1000);
v_sql2              CHAR(1000);
v_sql3              CHAR(1000);
v_uni               CHAR(10);
vr_co_aluno00       CHAR(20);
vr_fase01           CHAR(20);
vr_co_unidade02     CHAR(10);
vr_ano03            CHAR(10);
vr_idade04          CHAR(10);
vr_nu_dias_letiv05  CHAR(10);
vr_carga_horaria06  CHAR(20);
vr_faltas07         CHAR(20);
vr_resultado08      CHAR(30);
vr_no_estab_ensi09  CHAR(70);
vr_no_cidade_ens10  CHAR(50);
vr_sg_uf_ensino11   CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_aluno00 IS NULL THEN
			vr_co_aluno00 := 'null';
		ELSE
			vr_co_aluno00 := pn_co_aluno00;
		END IF;
		IF pn_fase01 IS NULL THEN
			vr_fase01 := 'null';
		ELSE
			vr_fase01 := pn_fase01;
		END IF;
		IF pn_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := pn_co_unidade02;
		END IF;
		IF pn_ano03 IS NULL THEN
			vr_ano03 := 'null';
		ELSE
			vr_ano03 := pn_ano03;
		END IF;
		IF pn_idade04 IS NULL THEN
			vr_idade04 := 'null';
		ELSE
			vr_idade04 := pn_idade04;
		END IF;
		IF pn_nu_dias_letiv05 IS NULL THEN
			vr_nu_dias_letiv05 := 'null';
		ELSE
			vr_nu_dias_letiv05 := pn_nu_dias_letiv05;
		END IF;
		IF pn_carga_horaria06 IS NULL THEN
			vr_carga_horaria06 := 'null';
		ELSE
			vr_carga_horaria06 := pn_carga_horaria06;
		END IF;
		IF pn_faltas07 IS NULL THEN
			vr_faltas07 := 'null';
		ELSE
			vr_faltas07 := pn_faltas07;
		END IF;
		IF pn_resultado08 IS NULL THEN
			vr_resultado08 := 'null';
		ELSE
			vr_resultado08 := pn_resultado08;
		END IF;
		IF pn_no_estab_ensi09 IS NULL THEN
			vr_no_estab_ensi09 := 'null';
		ELSE
			vr_no_estab_ensi09 := pn_no_estab_ensi09;
		END IF;
		IF pn_no_cidade_ens10 IS NULL THEN
			vr_no_cidade_ens10 := 'null';
		ELSE
			vr_no_cidade_ens10 := pn_no_cidade_ens10;
		END IF;
		IF pn_sg_uf_ensino11 IS NULL THEN
			vr_sg_uf_ensino11 := 'null';
		ELSE
			vr_sg_uf_ensino11 := pn_sg_uf_ensino11;
		END IF;
		v_sql1 := 'insert into s_historico_fase(co_aluno, fase, co_unidade, ano, idade, nu_dias_letivos, carga_horaria, faltas, resultado, no_estab_ensino, no_cidade_ensino, sg_uf_ensino) values (';
		v_sql2 := '"' || RTRIM(vr_co_aluno00) || '"' || ',' || '"' || RTRIM(vr_fase01) || '"' || ',' || '"' || RTRIM(vr_co_unidade02) || '"' || ',' || '"' || RTRIM(vr_ano03) || '"' || ',' || RTRIM(vr_idade04) || ',' || RTRIM(vr_nu_dias_letiv05) || ',' || '"' || RTRIM(vr_carga_horaria06) || '"' || ',';
		v_sql3 := '"' || RTRIM(vr_faltas07) || '"' || ',' || '"' || RTRIM(vr_resultado08) || '"' || ',' || '"' || RTRIM(vr_no_estab_ensi09) || '"' || ',' || '"' || RTRIM(vr_no_cidade_ens10) || '"' || ',' || '"' || RTRIM(vr_sg_uf_ensino11) || '"' || ');';
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
		v_sql1 := '  delete from s_historico_fase where co_aluno = ' || RTRIM(vr_co_aluno00) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
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
		IF pn_fase01 IS NULL
		AND pa_fase01 IS NULL THEN
			vr_fase01 := 'null';
		END IF;
		IF pn_fase01 IS NULL
		AND pa_fase01 IS NOT NULL THEN
			vr_fase01 := 'null';
		END IF;
		IF pn_fase01 IS NOT NULL
		AND pa_fase01 IS NULL THEN
			vr_fase01 := '"' || RTRIM(pn_fase01) || '"';
		END IF;
		IF pn_fase01 IS NOT NULL
		AND pa_fase01 IS NOT NULL THEN
			IF pa_fase01 <> pn_fase01 THEN
				vr_fase01 := '"' || RTRIM(pn_fase01) || '"';
			ELSE
				vr_fase01 := '"' || RTRIM(pa_fase01) || '"';
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
		IF pn_ano03 IS NULL
		AND pa_ano03 IS NULL THEN
			vr_ano03 := 'null';
		END IF;
		IF pn_ano03 IS NULL
		AND pa_ano03 IS NOT NULL THEN
			vr_ano03 := 'null';
		END IF;
		IF pn_ano03 IS NOT NULL
		AND pa_ano03 IS NULL THEN
			vr_ano03 := '"' || RTRIM(pn_ano03) || '"';
		END IF;
		IF pn_ano03 IS NOT NULL
		AND pa_ano03 IS NOT NULL THEN
			IF pa_ano03 <> pn_ano03 THEN
				vr_ano03 := '"' || RTRIM(pn_ano03) || '"';
			ELSE
				vr_ano03 := '"' || RTRIM(pa_ano03) || '"';
			END IF;
		END IF;
		IF pn_idade04 IS NULL
		AND pa_idade04 IS NULL THEN
			vr_idade04 := 'null';
		END IF;
		IF pn_idade04 IS NULL
		AND pa_idade04 IS NOT NULL THEN
			vr_idade04 := 'null';
		END IF;
		IF pn_idade04 IS NOT NULL
		AND pa_idade04 IS NULL THEN
			vr_idade04 := pn_idade04;
		END IF;
		IF pn_idade04 IS NOT NULL
		AND pa_idade04 IS NOT NULL THEN
			IF pa_idade04 <> pn_idade04 THEN
				vr_idade04 := pn_idade04;
			ELSE
				vr_idade04 := pa_idade04;
			END IF;
		END IF;
		IF pn_nu_dias_letiv05 IS NULL
		AND pa_nu_dias_letiv05 IS NULL THEN
			vr_nu_dias_letiv05 := 'null';
		END IF;
		IF pn_nu_dias_letiv05 IS NULL
		AND pa_nu_dias_letiv05 IS NOT NULL THEN
			vr_nu_dias_letiv05 := 'null';
		END IF;
		IF pn_nu_dias_letiv05 IS NOT NULL
		AND pa_nu_dias_letiv05 IS NULL THEN
			vr_nu_dias_letiv05 := pn_nu_dias_letiv05;
		END IF;
		IF pn_nu_dias_letiv05 IS NOT NULL
		AND pa_nu_dias_letiv05 IS NOT NULL THEN
			IF pa_nu_dias_letiv05 <> pn_nu_dias_letiv05 THEN
				vr_nu_dias_letiv05 := pn_nu_dias_letiv05;
			ELSE
				vr_nu_dias_letiv05 := pa_nu_dias_letiv05;
			END IF;
		END IF;
		IF pn_carga_horaria06 IS NULL
		AND pa_carga_horaria06 IS NULL THEN
			vr_carga_horaria06 := 'null';
		END IF;
		IF pn_carga_horaria06 IS NULL
		AND pa_carga_horaria06 IS NOT NULL THEN
			vr_carga_horaria06 := 'null';
		END IF;
		IF pn_carga_horaria06 IS NOT NULL
		AND pa_carga_horaria06 IS NULL THEN
			vr_carga_horaria06 := '"' || RTRIM(pn_carga_horaria06) || '"';
		END IF;
		IF pn_carga_horaria06 IS NOT NULL
		AND pa_carga_horaria06 IS NOT NULL THEN
			IF pa_carga_horaria06 <> pn_carga_horaria06 THEN
				vr_carga_horaria06 := '"' || RTRIM(pn_carga_horaria06) || '"';
			ELSE
				vr_carga_horaria06 := '"' || RTRIM(pa_carga_horaria06) || '"';
			END IF;
		END IF;
		IF pn_faltas07 IS NULL
		AND pa_faltas07 IS NULL THEN
			vr_faltas07 := 'null';
		END IF;
		IF pn_faltas07 IS NULL
		AND pa_faltas07 IS NOT NULL THEN
			vr_faltas07 := 'null';
		END IF;
		IF pn_faltas07 IS NOT NULL
		AND pa_faltas07 IS NULL THEN
			vr_faltas07 := '"' || RTRIM(pn_faltas07) || '"';
		END IF;
		IF pn_faltas07 IS NOT NULL
		AND pa_faltas07 IS NOT NULL THEN
			IF pa_faltas07 <> pn_faltas07 THEN
				vr_faltas07 := '"' || RTRIM(pn_faltas07) || '"';
			ELSE
				vr_faltas07 := '"' || RTRIM(pa_faltas07) || '"';
			END IF;
		END IF;
		IF pn_resultado08 IS NULL
		AND pa_resultado08 IS NULL THEN
			vr_resultado08 := 'null';
		END IF;
		IF pn_resultado08 IS NULL
		AND pa_resultado08 IS NOT NULL THEN
			vr_resultado08 := 'null';
		END IF;
		IF pn_resultado08 IS NOT NULL
		AND pa_resultado08 IS NULL THEN
			vr_resultado08 := '"' || RTRIM(pn_resultado08) || '"';
		END IF;
		IF pn_resultado08 IS NOT NULL
		AND pa_resultado08 IS NOT NULL THEN
			IF pa_resultado08 <> pn_resultado08 THEN
				vr_resultado08 := '"' || RTRIM(pn_resultado08) || '"';
			ELSE
				vr_resultado08 := '"' || RTRIM(pa_resultado08) || '"';
			END IF;
		END IF;
		IF pn_no_estab_ensi09 IS NULL
		AND pa_no_estab_ensi09 IS NULL THEN
			vr_no_estab_ensi09 := 'null';
		END IF;
		IF pn_no_estab_ensi09 IS NULL
		AND pa_no_estab_ensi09 IS NOT NULL THEN
			vr_no_estab_ensi09 := 'null';
		END IF;
		IF pn_no_estab_ensi09 IS NOT NULL
		AND pa_no_estab_ensi09 IS NULL THEN
			vr_no_estab_ensi09 := '"' || RTRIM(pn_no_estab_ensi09) || '"';
		END IF;
		IF pn_no_estab_ensi09 IS NOT NULL
		AND pa_no_estab_ensi09 IS NOT NULL THEN
			IF pa_no_estab_ensi09 <> pn_no_estab_ensi09 THEN
				vr_no_estab_ensi09 := '"' || RTRIM(pn_no_estab_ensi09) || '"';
			ELSE
				vr_no_estab_ensi09 := '"' || RTRIM(pa_no_estab_ensi09) || '"';
			END IF;
		END IF;
		IF pn_no_cidade_ens10 IS NULL
		AND pa_no_cidade_ens10 IS NULL THEN
			vr_no_cidade_ens10 := 'null';
		END IF;
		IF pn_no_cidade_ens10 IS NULL
		AND pa_no_cidade_ens10 IS NOT NULL THEN
			vr_no_cidade_ens10 := 'null';
		END IF;
		IF pn_no_cidade_ens10 IS NOT NULL
		AND pa_no_cidade_ens10 IS NULL THEN
			vr_no_cidade_ens10 := '"' || RTRIM(pn_no_cidade_ens10) || '"';
		END IF;
		IF pn_no_cidade_ens10 IS NOT NULL
		AND pa_no_cidade_ens10 IS NOT NULL THEN
			IF pa_no_cidade_ens10 <> pn_no_cidade_ens10 THEN
				vr_no_cidade_ens10 := '"' || RTRIM(pn_no_cidade_ens10) || '"';
			ELSE
				vr_no_cidade_ens10 := '"' || RTRIM(pa_no_cidade_ens10) || '"';
			END IF;
		END IF;
		IF pn_sg_uf_ensino11 IS NULL
		AND pa_sg_uf_ensino11 IS NULL THEN
			vr_sg_uf_ensino11 := 'null';
		END IF;
		IF pn_sg_uf_ensino11 IS NULL
		AND pa_sg_uf_ensino11 IS NOT NULL THEN
			vr_sg_uf_ensino11 := 'null';
		END IF;
		IF pn_sg_uf_ensino11 IS NOT NULL
		AND pa_sg_uf_ensino11 IS NULL THEN
			vr_sg_uf_ensino11 := '"' || RTRIM(pn_sg_uf_ensino11) || '"';
		END IF;
		IF pn_sg_uf_ensino11 IS NOT NULL
		AND pa_sg_uf_ensino11 IS NOT NULL THEN
			IF pa_sg_uf_ensino11 <> pn_sg_uf_ensino11 THEN
				vr_sg_uf_ensino11 := '"' || RTRIM(pn_sg_uf_ensino11) || '"';
			ELSE
				vr_sg_uf_ensino11 := '"' || RTRIM(pa_sg_uf_ensino11) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_historico_fase set co_aluno = ' || RTRIM(vr_co_aluno00) || '  , fase = ' || RTRIM(vr_fase01) || '  , co_unidade = ' || RTRIM(vr_co_unidade02) || '  , ano = ' || RTRIM(vr_ano03) || '  , idade = ' || RTRIM(vr_idade04) || '  , nu_dias_letivos = ' || RTRIM(vr_nu_dias_letiv05);
		v_sql2 := '  , carga_horaria = ' || RTRIM(vr_carga_horaria06) || '  , faltas = ' || RTRIM(vr_faltas07) || '  , resultado = ' || RTRIM(vr_resultado08) || '  , no_estab_ensino = ' || RTRIM(vr_no_estab_ensi09) || '  , no_cidade_ensino = ' || RTRIM(vr_no_cidade_ens10) || '  , sg_uf_ensino = ' || RTRIM(vr_sg_uf_ensino11);
		v_sql3 := ' where co_aluno = ' || RTRIM(vr_co_aluno00) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
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
		       's_historico_fase',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_historic112;
/

