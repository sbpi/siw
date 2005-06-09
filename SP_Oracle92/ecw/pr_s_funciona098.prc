CREATE OR REPLACE PROCEDURE pr_s_funciona098(
	P_OP_IN                CHAR,
	PA_co_funcionari00_IN  s_funcionario.co_funcionario%TYPE,
	PA_nu_matricula_01_IN  s_funcionario.nu_matricula_mec%TYPE,
	PA_co_seq_cidade02_IN  s_funcionario.co_seq_cidade%TYPE,
	PA_ds_funcionari03_IN  s_funcionario.ds_funcionario%TYPE,
	PA_ds_apelido04_IN     s_funcionario.ds_apelido%TYPE,
	PA_fo_funcionari05_IN  s_funcionario.fo_funcionario%TYPE,
	PA_tp_sexo06_IN        s_funcionario.tp_sexo%TYPE,
	PA_ds_naturalida07_IN  s_funcionario.ds_naturalidade%TYPE,
	PA_ds_uf_nascime08_IN  s_funcionario.ds_uf_nascimento%TYPE,
	PA_dt_nascimento09_IN  s_funcionario.dt_nascimento%TYPE,
	PA_ds_endereco10_IN    s_funcionario.ds_endereco%TYPE,
	PA_nu_cep11_IN         s_funcionario.nu_cep%TYPE,
	PA_ds_cidade12_IN      s_funcionario.ds_cidade%TYPE,
	PA_ds_uf_cidade13_IN   s_funcionario.ds_uf_cidade%TYPE,
	PA_ds_bairro14_IN      s_funcionario.ds_bairro%TYPE,
	PA_nu_telefone15_IN    s_funcionario.nu_telefone%TYPE,
	PA_nu_celular16_IN     s_funcionario.nu_celular%TYPE,
	PA_ds_e_mail17_IN      s_funcionario.ds_e_mail%TYPE,
	PA_tp_estado_civ18_IN  s_funcionario.tp_estado_civil%TYPE,
	PA_ds_conjuge19_IN     s_funcionario.ds_conjuge%TYPE,
	PA_nu_rg20_IN          s_funcionario.nu_rg%TYPE,
	PA_ds_orgao_emis21_IN  s_funcionario.ds_orgao_emissor%TYPE,
	PA_dt_emissao22_IN     s_funcionario.dt_emissao%TYPE,
	PA_nu_cpf23_IN         s_funcionario.nu_cpf%TYPE,
	PA_ds_pai24_IN         s_funcionario.ds_pai%TYPE,
	PA_ds_mae25_IN         s_funcionario.ds_mae%TYPE,
	PA_nu_registro26_IN    s_funcionario.nu_registro%TYPE,
	PA_ds_instrucao27_IN   s_funcionario.ds_instrucao%TYPE,
	PA_co_unidade28_IN     s_funcionario.co_unidade%TYPE,
	PA_lotacao_princ29_IN  s_funcionario.lotacao_princ%TYPE,
	PA_lotacao_secun30_IN  s_funcionario.lotacao_secun%TYPE,
	PN_co_funcionari00_IN  s_funcionario.co_funcionario%TYPE,
	PN_nu_matricula_01_IN  s_funcionario.nu_matricula_mec%TYPE,
	PN_co_seq_cidade02_IN  s_funcionario.co_seq_cidade%TYPE,
	PN_ds_funcionari03_IN  s_funcionario.ds_funcionario%TYPE,
	PN_ds_apelido04_IN     s_funcionario.ds_apelido%TYPE,
	PN_fo_funcionari05_IN  s_funcionario.fo_funcionario%TYPE,
	PN_tp_sexo06_IN        s_funcionario.tp_sexo%TYPE,
	PN_ds_naturalida07_IN  s_funcionario.ds_naturalidade%TYPE,
	PN_ds_uf_nascime08_IN  s_funcionario.ds_uf_nascimento%TYPE,
	PN_dt_nascimento09_IN  s_funcionario.dt_nascimento%TYPE,
	PN_ds_endereco10_IN    s_funcionario.ds_endereco%TYPE,
	PN_nu_cep11_IN         s_funcionario.nu_cep%TYPE,
	PN_ds_cidade12_IN      s_funcionario.ds_cidade%TYPE,
	PN_ds_uf_cidade13_IN   s_funcionario.ds_uf_cidade%TYPE,
	PN_ds_bairro14_IN      s_funcionario.ds_bairro%TYPE,
	PN_nu_telefone15_IN    s_funcionario.nu_telefone%TYPE,
	PN_nu_celular16_IN     s_funcionario.nu_celular%TYPE,
	PN_ds_e_mail17_IN      s_funcionario.ds_e_mail%TYPE,
	PN_tp_estado_civ18_IN  s_funcionario.tp_estado_civil%TYPE,
	PN_ds_conjuge19_IN     s_funcionario.ds_conjuge%TYPE,
	PN_nu_rg20_IN          s_funcionario.nu_rg%TYPE,
	PN_ds_orgao_emis21_IN  s_funcionario.ds_orgao_emissor%TYPE,
	PN_dt_emissao22_IN     s_funcionario.dt_emissao%TYPE,
	PN_nu_cpf23_IN         s_funcionario.nu_cpf%TYPE,
	PN_ds_pai24_IN         s_funcionario.ds_pai%TYPE,
	PN_ds_mae25_IN         s_funcionario.ds_mae%TYPE,
	PN_nu_registro26_IN    s_funcionario.nu_registro%TYPE,
	PN_ds_instrucao27_IN   s_funcionario.ds_instrucao%TYPE,
	PN_co_unidade28_IN     s_funcionario.co_unidade%TYPE,
	PN_lotacao_princ29_IN  s_funcionario.lotacao_princ%TYPE,
	PN_lotacao_secun30_IN  s_funcionario.lotacao_secun%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_funcionari00  s_funcionario.co_funcionario%TYPE := PA_co_funcionari00_IN;
PA_nu_matricula_01  s_funcionario.nu_matricula_mec%TYPE := PA_nu_matricula_01_IN;
PA_co_seq_cidade02  s_funcionario.co_seq_cidade%TYPE := PA_co_seq_cidade02_IN;
PA_ds_funcionari03  s_funcionario.ds_funcionario%TYPE := PA_ds_funcionari03_IN;
PA_ds_apelido04     s_funcionario.ds_apelido%TYPE := PA_ds_apelido04_IN;
PA_fo_funcionari05  s_funcionario.fo_funcionario%TYPE := PA_fo_funcionari05_IN;
PA_tp_sexo06        s_funcionario.tp_sexo%TYPE := PA_tp_sexo06_IN;
PA_ds_naturalida07  s_funcionario.ds_naturalidade%TYPE := PA_ds_naturalida07_IN;
PA_ds_uf_nascime08  s_funcionario.ds_uf_nascimento%TYPE := PA_ds_uf_nascime08_IN;
PA_dt_nascimento09  s_funcionario.dt_nascimento%TYPE := PA_dt_nascimento09_IN;
PA_ds_endereco10    s_funcionario.ds_endereco%TYPE := PA_ds_endereco10_IN;
PA_nu_cep11         s_funcionario.nu_cep%TYPE := PA_nu_cep11_IN;
PA_ds_cidade12      s_funcionario.ds_cidade%TYPE := PA_ds_cidade12_IN;
PA_ds_uf_cidade13   s_funcionario.ds_uf_cidade%TYPE := PA_ds_uf_cidade13_IN;
PA_ds_bairro14      s_funcionario.ds_bairro%TYPE := PA_ds_bairro14_IN;
PA_nu_telefone15    s_funcionario.nu_telefone%TYPE := PA_nu_telefone15_IN;
PA_nu_celular16     s_funcionario.nu_celular%TYPE := PA_nu_celular16_IN;
PA_ds_e_mail17      s_funcionario.ds_e_mail%TYPE := PA_ds_e_mail17_IN;
PA_tp_estado_civ18  s_funcionario.tp_estado_civil%TYPE := PA_tp_estado_civ18_IN;
PA_ds_conjuge19     s_funcionario.ds_conjuge%TYPE := PA_ds_conjuge19_IN;
PA_nu_rg20          s_funcionario.nu_rg%TYPE := PA_nu_rg20_IN;
PA_ds_orgao_emis21  s_funcionario.ds_orgao_emissor%TYPE := PA_ds_orgao_emis21_IN;
PA_dt_emissao22     s_funcionario.dt_emissao%TYPE := PA_dt_emissao22_IN;
PA_nu_cpf23         s_funcionario.nu_cpf%TYPE := PA_nu_cpf23_IN;
PA_ds_pai24         s_funcionario.ds_pai%TYPE := PA_ds_pai24_IN;
PA_ds_mae25         s_funcionario.ds_mae%TYPE := PA_ds_mae25_IN;
PA_nu_registro26    s_funcionario.nu_registro%TYPE := PA_nu_registro26_IN;
PA_ds_instrucao27   s_funcionario.ds_instrucao%TYPE := PA_ds_instrucao27_IN;
PA_co_unidade28     s_funcionario.co_unidade%TYPE := PA_co_unidade28_IN;
PA_lotacao_princ29  s_funcionario.lotacao_princ%TYPE := PA_lotacao_princ29_IN;
PA_lotacao_secun30  s_funcionario.lotacao_secun%TYPE := PA_lotacao_secun30_IN;
PN_co_funcionari00  s_funcionario.co_funcionario%TYPE := PN_co_funcionari00_IN;
PN_nu_matricula_01  s_funcionario.nu_matricula_mec%TYPE := PN_nu_matricula_01_IN;
PN_co_seq_cidade02  s_funcionario.co_seq_cidade%TYPE := PN_co_seq_cidade02_IN;
PN_ds_funcionari03  s_funcionario.ds_funcionario%TYPE := PN_ds_funcionari03_IN;
PN_ds_apelido04     s_funcionario.ds_apelido%TYPE := PN_ds_apelido04_IN;
PN_fo_funcionari05  s_funcionario.fo_funcionario%TYPE := PN_fo_funcionari05_IN;
PN_tp_sexo06        s_funcionario.tp_sexo%TYPE := PN_tp_sexo06_IN;
PN_ds_naturalida07  s_funcionario.ds_naturalidade%TYPE := PN_ds_naturalida07_IN;
PN_ds_uf_nascime08  s_funcionario.ds_uf_nascimento%TYPE := PN_ds_uf_nascime08_IN;
PN_dt_nascimento09  s_funcionario.dt_nascimento%TYPE := PN_dt_nascimento09_IN;
PN_ds_endereco10    s_funcionario.ds_endereco%TYPE := PN_ds_endereco10_IN;
PN_nu_cep11         s_funcionario.nu_cep%TYPE := PN_nu_cep11_IN;
PN_ds_cidade12      s_funcionario.ds_cidade%TYPE := PN_ds_cidade12_IN;
PN_ds_uf_cidade13   s_funcionario.ds_uf_cidade%TYPE := PN_ds_uf_cidade13_IN;
PN_ds_bairro14      s_funcionario.ds_bairro%TYPE := PN_ds_bairro14_IN;
PN_nu_telefone15    s_funcionario.nu_telefone%TYPE := PN_nu_telefone15_IN;
PN_nu_celular16     s_funcionario.nu_celular%TYPE := PN_nu_celular16_IN;
PN_ds_e_mail17      s_funcionario.ds_e_mail%TYPE := PN_ds_e_mail17_IN;
PN_tp_estado_civ18  s_funcionario.tp_estado_civil%TYPE := PN_tp_estado_civ18_IN;
PN_ds_conjuge19     s_funcionario.ds_conjuge%TYPE := PN_ds_conjuge19_IN;
PN_nu_rg20          s_funcionario.nu_rg%TYPE := PN_nu_rg20_IN;
PN_ds_orgao_emis21  s_funcionario.ds_orgao_emissor%TYPE := PN_ds_orgao_emis21_IN;
PN_dt_emissao22     s_funcionario.dt_emissao%TYPE := PN_dt_emissao22_IN;
PN_nu_cpf23         s_funcionario.nu_cpf%TYPE := PN_nu_cpf23_IN;
PN_ds_pai24         s_funcionario.ds_pai%TYPE := PN_ds_pai24_IN;
PN_ds_mae25         s_funcionario.ds_mae%TYPE := PN_ds_mae25_IN;
PN_nu_registro26    s_funcionario.nu_registro%TYPE := PN_nu_registro26_IN;
PN_ds_instrucao27   s_funcionario.ds_instrucao%TYPE := PN_ds_instrucao27_IN;
PN_co_unidade28     s_funcionario.co_unidade%TYPE := PN_co_unidade28_IN;
PN_lotacao_princ29  s_funcionario.lotacao_princ%TYPE := PN_lotacao_princ29_IN;
PN_lotacao_secun30  s_funcionario.lotacao_secun%TYPE := PN_lotacao_secun30_IN;
v_blob1             s_funcionario.fo_funcionario%TYPE;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(600);
v_sql2              CHAR(400);
v_sql3              CHAR(400);
v_sql4              CHAR(400);
v_sql5              CHAR(400);
v_sql6              CHAR(400);
v_sql7              CHAR(400);
v_uni               CHAR(10);
vr_co_funcionari00  CHAR(20);
vr_nu_matricula_01  CHAR(20);
vr_co_seq_cidade02  CHAR(10);
vr_ds_funcionari03  CHAR(50);
vr_ds_apelido04     CHAR(30);
vr_fo_funcionari05  CHAR(10);
vr_tp_sexo06        CHAR(10);
vr_ds_naturalida07  CHAR(40);
vr_ds_uf_nascime08  CHAR(10);
vr_dt_nascimento09  CHAR(40);
vr_ds_endereco10    CHAR(50);
vr_nu_cep11         CHAR(20);
vr_ds_cidade12      CHAR(30);
vr_ds_uf_cidade13   CHAR(10);
vr_ds_bairro14      CHAR(20);
vr_nu_telefone15    CHAR(20);
vr_nu_celular16     CHAR(20);
vr_ds_e_mail17      CHAR(110);
vr_tp_estado_civ18  CHAR(20);
vr_ds_conjuge19     CHAR(60);
vr_nu_rg20          CHAR(25);
vr_ds_orgao_emis21  CHAR(40);
vr_dt_emissao22     CHAR(40);
vr_nu_cpf23         CHAR(20);
vr_ds_pai24         CHAR(50);
vr_ds_mae25         CHAR(50);
vr_nu_registro26    CHAR(25);
vr_ds_instrucao27   CHAR(50);
vr_co_unidade28     CHAR(10);
vr_lotacao_princ29  CHAR(25);
vr_lotacao_secun30  CHAR(25);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	v_blob1 := NULL;
	IF p_op = 'ins' THEN
		IF pn_co_funcionari00 IS NULL THEN
			vr_co_funcionari00 := 'null';
		ELSE
			vr_co_funcionari00 := pn_co_funcionari00;
		END IF;
		IF pn_nu_matricula_01 IS NULL THEN
			vr_nu_matricula_01 := 'null';
		ELSE
			vr_nu_matricula_01 := pn_nu_matricula_01;
		END IF;
		IF pn_co_seq_cidade02 IS NULL THEN
			vr_co_seq_cidade02 := 'null';
		ELSE
			vr_co_seq_cidade02 := pn_co_seq_cidade02;
		END IF;
		IF pn_ds_funcionari03 IS NULL THEN
			vr_ds_funcionari03 := 'null';
		ELSE
			vr_ds_funcionari03 := pn_ds_funcionari03;
		END IF;
		IF pn_ds_apelido04 IS NULL THEN
			vr_ds_apelido04 := 'null';
		ELSE
			vr_ds_apelido04 := pn_ds_apelido04;
		END IF;
		IF pn_fo_funcionari05 IS NULL THEN
			vr_fo_funcionari05 := NULL;
		ELSE
			vr_fo_funcionari05 := ':vblob1';
		END IF;
		v_blob1 := pn_fo_funcionari05;
		IF pn_tp_sexo06 IS NULL THEN
			vr_tp_sexo06 := 'null';
		ELSE
			vr_tp_sexo06 := pn_tp_sexo06;
		END IF;
		IF pn_ds_naturalida07 IS NULL THEN
			vr_ds_naturalida07 := 'null';
		ELSE
			vr_ds_naturalida07 := pn_ds_naturalida07;
		END IF;
		IF pn_ds_uf_nascime08 IS NULL THEN
			vr_ds_uf_nascime08 := 'null';
		ELSE
			vr_ds_uf_nascime08 := pn_ds_uf_nascime08;
		END IF;
		IF pn_dt_nascimento09 IS NULL THEN
			vr_dt_nascimento09 := 'null';
		ELSE
			vr_dt_nascimento09 := pn_dt_nascimento09;
		END IF;
		IF pn_ds_endereco10 IS NULL THEN
			vr_ds_endereco10 := 'null';
		ELSE
			vr_ds_endereco10 := pn_ds_endereco10;
		END IF;
		IF pn_nu_cep11 IS NULL THEN
			vr_nu_cep11 := 'null';
		ELSE
			vr_nu_cep11 := pn_nu_cep11;
		END IF;
		IF pn_ds_cidade12 IS NULL THEN
			vr_ds_cidade12 := 'null';
		ELSE
			vr_ds_cidade12 := pn_ds_cidade12;
		END IF;
		IF pn_ds_uf_cidade13 IS NULL THEN
			vr_ds_uf_cidade13 := 'null';
		ELSE
			vr_ds_uf_cidade13 := pn_ds_uf_cidade13;
		END IF;
		IF pn_ds_bairro14 IS NULL THEN
			vr_ds_bairro14 := 'null';
		ELSE
			vr_ds_bairro14 := pn_ds_bairro14;
		END IF;
		IF pn_nu_telefone15 IS NULL THEN
			vr_nu_telefone15 := 'null';
		ELSE
			vr_nu_telefone15 := pn_nu_telefone15;
		END IF;
		IF pn_nu_celular16 IS NULL THEN
			vr_nu_celular16 := 'null';
		ELSE
			vr_nu_celular16 := pn_nu_celular16;
		END IF;
		IF pn_ds_e_mail17 IS NULL THEN
			vr_ds_e_mail17 := 'null';
		ELSE
			vr_ds_e_mail17 := pn_ds_e_mail17;
		END IF;
		IF pn_tp_estado_civ18 IS NULL THEN
			vr_tp_estado_civ18 := 'null';
		ELSE
			vr_tp_estado_civ18 := pn_tp_estado_civ18;
		END IF;
		IF pn_ds_conjuge19 IS NULL THEN
			vr_ds_conjuge19 := 'null';
		ELSE
			vr_ds_conjuge19 := pn_ds_conjuge19;
		END IF;
		IF pn_nu_rg20 IS NULL THEN
			vr_nu_rg20 := 'null';
		ELSE
			vr_nu_rg20 := pn_nu_rg20;
		END IF;
		IF pn_ds_orgao_emis21 IS NULL THEN
			vr_ds_orgao_emis21 := 'null';
		ELSE
			vr_ds_orgao_emis21 := pn_ds_orgao_emis21;
		END IF;
		IF pn_dt_emissao22 IS NULL THEN
			vr_dt_emissao22 := 'null';
		ELSE
			vr_dt_emissao22 := pn_dt_emissao22;
		END IF;
		IF pn_nu_cpf23 IS NULL THEN
			vr_nu_cpf23 := 'null';
		ELSE
			vr_nu_cpf23 := pn_nu_cpf23;
		END IF;
		IF pn_ds_pai24 IS NULL THEN
			vr_ds_pai24 := 'null';
		ELSE
			vr_ds_pai24 := pn_ds_pai24;
		END IF;
		IF pn_ds_mae25 IS NULL THEN
			vr_ds_mae25 := 'null';
		ELSE
			vr_ds_mae25 := pn_ds_mae25;
		END IF;
		IF pn_nu_registro26 IS NULL THEN
			vr_nu_registro26 := 'null';
		ELSE
			vr_nu_registro26 := pn_nu_registro26;
		END IF;
		IF pn_ds_instrucao27 IS NULL THEN
			vr_ds_instrucao27 := 'null';
		ELSE
			vr_ds_instrucao27 := pn_ds_instrucao27;
		END IF;
		IF pn_co_unidade28 IS NULL THEN
			vr_co_unidade28 := 'null';
		ELSE
			vr_co_unidade28 := pn_co_unidade28;
		END IF;
		IF pn_lotacao_princ29 IS NULL THEN
			vr_lotacao_princ29 := 'null';
		ELSE
			vr_lotacao_princ29 := pn_lotacao_princ29;
		END IF;
		IF pn_lotacao_secun30 IS NULL THEN
			vr_lotacao_secun30 := 'null';
		ELSE
			vr_lotacao_secun30 := pn_lotacao_secun30;
		END IF;
		v_sql1 := 'insert into s_funcionario(co_funcionario, nu_matricula_mec, co_seq_cidade, ds_funcionario, ds_apelido, fo_funcionario, tp_sexo, ds_naturalidade, ds_uf_nascimento, ' || 'dt_nascimento, ds_endereco, nu_cep, ds_cidade, ds_uf_cidade, ds_bairro, nu_telefone, nu_celular, ds_e_mail, tp_estado_civil, ds_conjuge, nu_rg, ds_orgao_emissor, dt_emissao, nu_cpf, ' || 'ds_pai, ds_mae, nu_registro, ds_instrucao, co_unidade, lotacao_princ, lotacao_secun) values (';
		v_sql2 := '"' || RTRIM(vr_co_funcionari00) || '"' || ',' || '"' || RTRIM(vr_nu_matricula_01) || '"' || ',' || RTRIM(vr_co_seq_cidade02) || ',' || '"' || RTRIM(vr_ds_funcionari03) || '"' || ',' || '"' || RTRIM(vr_ds_apelido04) || '"' || ',';
		v_sql3 := RTRIM(vr_fo_funcionari05) || ',' || '"' || RTRIM(vr_tp_sexo06) || '"' || ',' || '"' || RTRIM(vr_ds_naturalida07) || '"' || ',' || '"' || RTRIM(vr_ds_uf_nascime08) || '"' || ',' || '"' || vr_dt_nascimento09 || '"' || ',' || '"' || RTRIM(vr_ds_endereco10) || '"' || ',';
		v_sql4 := '"' || RTRIM(vr_nu_cep11) || '"' || ',' || '"' || RTRIM(vr_ds_cidade12) || '"' || ',' || '"' || RTRIM(vr_ds_uf_cidade13) || '"' || ',' || '"' || RTRIM(vr_ds_bairro14) || '"' || ',' || '"' || RTRIM(vr_nu_telefone15) || '"' || ',' || '"' || RTRIM(vr_nu_celular16) || '"' || ',';
		v_sql5 := '"' || RTRIM(vr_ds_e_mail17) || '"' || ',' || '"' || RTRIM(vr_tp_estado_civ18) || '"' || ',' || '"' || RTRIM(vr_ds_conjuge19) || '"' || ',' || '"' || RTRIM(vr_nu_rg20) || '"' || ',' || '"' || RTRIM(vr_ds_orgao_emis21) || '"' || ',' || '"' || vr_dt_emissao22 || '"' || ',';
		v_sql6 := '"' || RTRIM(vr_nu_cpf23) || '"' || ',' || '"' || RTRIM(vr_ds_pai24) || '"' || ',' || '"' || RTRIM(vr_ds_mae25) || '"' || ',' || '"' || RTRIM(vr_nu_registro26) || '"' || ',' || '"' || RTRIM(vr_ds_instrucao27) || '"' || ',' || '"' || RTRIM(vr_co_unidade28) || '"' || ',';
		v_sql7 := '"' || RTRIM(vr_lotacao_princ29) || '"' || ',' || '"' || RTRIM(vr_lotacao_secun30) || '"' || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5 || v_sql6 || v_sql7;
	ELSIF p_op = 'del' THEN
		IF pa_co_funcionari00 IS NULL THEN
			vr_co_funcionari00 := 'null';
		ELSE
			vr_co_funcionari00 := '"' || RTRIM(pa_co_funcionari00) || '"';
		END IF;
		v_sql1 := '  delete from s_funcionario where co_funcionario = ' || RTRIM(vr_co_funcionari00) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_funcionari00 IS NULL
		AND pa_co_funcionari00 IS NULL THEN
			vr_co_funcionari00 := 'null';
		END IF;
		IF pn_co_funcionari00 IS NULL
		AND pa_co_funcionari00 IS NOT NULL THEN
			vr_co_funcionari00 := 'null';
		END IF;
		IF pn_co_funcionari00 IS NOT NULL
		AND pa_co_funcionari00 IS NULL THEN
			vr_co_funcionari00 := '"' || RTRIM(pn_co_funcionari00) || '"';
		END IF;
		IF pn_co_funcionari00 IS NOT NULL
		AND pa_co_funcionari00 IS NOT NULL THEN
			IF pa_co_funcionari00 <> pn_co_funcionari00 THEN
				vr_co_funcionari00 := '"' || RTRIM(pn_co_funcionari00) || '"';
			ELSE
				vr_co_funcionari00 := '"' || RTRIM(pa_co_funcionari00) || '"';
			END IF;
		END IF;
		IF pn_nu_matricula_01 IS NULL
		AND pa_nu_matricula_01 IS NULL THEN
			vr_nu_matricula_01 := 'null';
		END IF;
		IF pn_nu_matricula_01 IS NULL
		AND pa_nu_matricula_01 IS NOT NULL THEN
			vr_nu_matricula_01 := 'null';
		END IF;
		IF pn_nu_matricula_01 IS NOT NULL
		AND pa_nu_matricula_01 IS NULL THEN
			vr_nu_matricula_01 := '"' || RTRIM(pn_nu_matricula_01) || '"';
		END IF;
		IF pn_nu_matricula_01 IS NOT NULL
		AND pa_nu_matricula_01 IS NOT NULL THEN
			IF pa_nu_matricula_01 <> pn_nu_matricula_01 THEN
				vr_nu_matricula_01 := '"' || RTRIM(pn_nu_matricula_01) || '"';
			ELSE
				vr_nu_matricula_01 := '"' || RTRIM(pa_nu_matricula_01) || '"';
			END IF;
		END IF;
		IF pn_co_seq_cidade02 IS NULL
		AND pa_co_seq_cidade02 IS NULL THEN
			vr_co_seq_cidade02 := 'null';
		END IF;
		IF pn_co_seq_cidade02 IS NULL
		AND pa_co_seq_cidade02 IS NOT NULL THEN
			vr_co_seq_cidade02 := 'null';
		END IF;
		IF pn_co_seq_cidade02 IS NOT NULL
		AND pa_co_seq_cidade02 IS NULL THEN
			vr_co_seq_cidade02 := pn_co_seq_cidade02;
		END IF;
		IF pn_co_seq_cidade02 IS NOT NULL
		AND pa_co_seq_cidade02 IS NOT NULL THEN
			IF pa_co_seq_cidade02 <> pn_co_seq_cidade02 THEN
				vr_co_seq_cidade02 := pn_co_seq_cidade02;
			ELSE
				vr_co_seq_cidade02 := pa_co_seq_cidade02;
			END IF;
		END IF;
		IF pn_ds_funcionari03 IS NULL
		AND pa_ds_funcionari03 IS NULL THEN
			vr_ds_funcionari03 := 'null';
		END IF;
		IF pn_ds_funcionari03 IS NULL
		AND pa_ds_funcionari03 IS NOT NULL THEN
			vr_ds_funcionari03 := 'null';
		END IF;
		IF pn_ds_funcionari03 IS NOT NULL
		AND pa_ds_funcionari03 IS NULL THEN
			vr_ds_funcionari03 := '"' || RTRIM(pn_ds_funcionari03) || '"';
		END IF;
		IF pn_ds_funcionari03 IS NOT NULL
		AND pa_ds_funcionari03 IS NOT NULL THEN
			IF pa_ds_funcionari03 <> pn_ds_funcionari03 THEN
				vr_ds_funcionari03 := '"' || RTRIM(pn_ds_funcionari03) || '"';
			ELSE
				vr_ds_funcionari03 := '"' || RTRIM(pa_ds_funcionari03) || '"';
			END IF;
		END IF;
		IF pn_ds_apelido04 IS NULL
		AND pa_ds_apelido04 IS NULL THEN
			vr_ds_apelido04 := 'null';
		END IF;
		IF pn_ds_apelido04 IS NULL
		AND pa_ds_apelido04 IS NOT NULL THEN
			vr_ds_apelido04 := 'null';
		END IF;
		IF pn_ds_apelido04 IS NOT NULL
		AND pa_ds_apelido04 IS NULL THEN
			vr_ds_apelido04 := '"' || RTRIM(pn_ds_apelido04) || '"';
		END IF;
		IF pn_ds_apelido04 IS NOT NULL
		AND pa_ds_apelido04 IS NOT NULL THEN
			IF pa_ds_apelido04 <> pn_ds_apelido04 THEN
				vr_ds_apelido04 := '"' || RTRIM(pn_ds_apelido04) || '"';
			ELSE
				vr_ds_apelido04 := '"' || RTRIM(pa_ds_apelido04) || '"';
			END IF;
		END IF;
		IF pn_fo_funcionari05 IS NULL THEN
			vr_fo_funcionari05 := NULL;
		ELSE
			vr_fo_funcionari05 := ':vblob1';
		END IF;
		v_blob1 := pn_fo_funcionari05;
		IF pn_tp_sexo06 IS NULL
		AND pa_tp_sexo06 IS NULL THEN
			vr_tp_sexo06 := 'null';
		END IF;
		IF pn_tp_sexo06 IS NULL
		AND pa_tp_sexo06 IS NOT NULL THEN
			vr_tp_sexo06 := 'null';
		END IF;
		IF pn_tp_sexo06 IS NOT NULL
		AND pa_tp_sexo06 IS NULL THEN
			vr_tp_sexo06 := '"' || RTRIM(pn_tp_sexo06) || '"';
		END IF;
		IF pn_tp_sexo06 IS NOT NULL
		AND pa_tp_sexo06 IS NOT NULL THEN
			IF pa_tp_sexo06 <> pn_tp_sexo06 THEN
				vr_tp_sexo06 := '"' || RTRIM(pn_tp_sexo06) || '"';
			ELSE
				vr_tp_sexo06 := '"' || RTRIM(pa_tp_sexo06) || '"';
			END IF;
		END IF;
		IF pn_ds_naturalida07 IS NULL
		AND pa_ds_naturalida07 IS NULL THEN
			vr_ds_naturalida07 := 'null';
		END IF;
		IF pn_ds_naturalida07 IS NULL
		AND pa_ds_naturalida07 IS NOT NULL THEN
			vr_ds_naturalida07 := 'null';
		END IF;
		IF pn_ds_naturalida07 IS NOT NULL
		AND pa_ds_naturalida07 IS NULL THEN
			vr_ds_naturalida07 := '"' || RTRIM(pn_ds_naturalida07) || '"';
		END IF;
		IF pn_ds_naturalida07 IS NOT NULL
		AND pa_ds_naturalida07 IS NOT NULL THEN
			IF pa_ds_naturalida07 <> pn_ds_naturalida07 THEN
				vr_ds_naturalida07 := '"' || RTRIM(pn_ds_naturalida07) || '"';
			ELSE
				vr_ds_naturalida07 := '"' || RTRIM(pa_ds_naturalida07) || '"';
			END IF;
		END IF;
		IF pn_ds_uf_nascime08 IS NULL
		AND pa_ds_uf_nascime08 IS NULL THEN
			vr_ds_uf_nascime08 := 'null';
		END IF;
		IF pn_ds_uf_nascime08 IS NULL
		AND pa_ds_uf_nascime08 IS NOT NULL THEN
			vr_ds_uf_nascime08 := 'null';
		END IF;
		IF pn_ds_uf_nascime08 IS NOT NULL
		AND pa_ds_uf_nascime08 IS NULL THEN
			vr_ds_uf_nascime08 := '"' || RTRIM(pn_ds_uf_nascime08) || '"';
		END IF;
		IF pn_ds_uf_nascime08 IS NOT NULL
		AND pa_ds_uf_nascime08 IS NOT NULL THEN
			IF pa_ds_uf_nascime08 <> pn_ds_uf_nascime08 THEN
				vr_ds_uf_nascime08 := '"' || RTRIM(pn_ds_uf_nascime08) || '"';
			ELSE
				vr_ds_uf_nascime08 := '"' || RTRIM(pa_ds_uf_nascime08) || '"';
			END IF;
		END IF;
		IF pn_dt_nascimento09 IS NULL
		AND pa_dt_nascimento09 IS NULL THEN
			vr_dt_nascimento09 := 'null';
		END IF;
		IF pn_dt_nascimento09 IS NULL
		AND pa_dt_nascimento09 IS NOT NULL THEN
			vr_dt_nascimento09 := 'null';
		END IF;
		IF pn_dt_nascimento09 IS NOT NULL
		AND pa_dt_nascimento09 IS NULL THEN
			vr_dt_nascimento09 := '"' || pn_dt_nascimento09 || '"';
		END IF;
		IF pn_dt_nascimento09 IS NOT NULL
		AND pa_dt_nascimento09 IS NOT NULL THEN
			IF pa_dt_nascimento09 <> pn_dt_nascimento09 THEN
				vr_dt_nascimento09 := '"' || pn_dt_nascimento09 || '"';
			ELSE
				vr_dt_nascimento09 := '"' || pa_dt_nascimento09 || '"';
			END IF;
		END IF;
		IF pn_ds_endereco10 IS NULL
		AND pa_ds_endereco10 IS NULL THEN
			vr_ds_endereco10 := 'null';
		END IF;
		IF pn_ds_endereco10 IS NULL
		AND pa_ds_endereco10 IS NOT NULL THEN
			vr_ds_endereco10 := 'null';
		END IF;
		IF pn_ds_endereco10 IS NOT NULL
		AND pa_ds_endereco10 IS NULL THEN
			vr_ds_endereco10 := '"' || RTRIM(pn_ds_endereco10) || '"';
		END IF;
		IF pn_ds_endereco10 IS NOT NULL
		AND pa_ds_endereco10 IS NOT NULL THEN
			IF pa_ds_endereco10 <> pn_ds_endereco10 THEN
				vr_ds_endereco10 := '"' || RTRIM(pn_ds_endereco10) || '"';
			ELSE
				vr_ds_endereco10 := '"' || RTRIM(pa_ds_endereco10) || '"';
			END IF;
		END IF;
		IF pn_nu_cep11 IS NULL
		AND pa_nu_cep11 IS NULL THEN
			vr_nu_cep11 := 'null';
		END IF;
		IF pn_nu_cep11 IS NULL
		AND pa_nu_cep11 IS NOT NULL THEN
			vr_nu_cep11 := 'null';
		END IF;
		IF pn_nu_cep11 IS NOT NULL
		AND pa_nu_cep11 IS NULL THEN
			vr_nu_cep11 := '"' || RTRIM(pn_nu_cep11) || '"';
		END IF;
		IF pn_nu_cep11 IS NOT NULL
		AND pa_nu_cep11 IS NOT NULL THEN
			IF pa_nu_cep11 <> pn_nu_cep11 THEN
				vr_nu_cep11 := '"' || RTRIM(pn_nu_cep11) || '"';
			ELSE
				vr_nu_cep11 := '"' || RTRIM(pa_nu_cep11) || '"';
			END IF;
		END IF;
		IF pn_ds_cidade12 IS NULL
		AND pa_ds_cidade12 IS NULL THEN
			vr_ds_cidade12 := 'null';
		END IF;
		IF pn_ds_cidade12 IS NULL
		AND pa_ds_cidade12 IS NOT NULL THEN
			vr_ds_cidade12 := 'null';
		END IF;
		IF pn_ds_cidade12 IS NOT NULL
		AND pa_ds_cidade12 IS NULL THEN
			vr_ds_cidade12 := '"' || RTRIM(pn_ds_cidade12) || '"';
		END IF;
		IF pn_ds_cidade12 IS NOT NULL
		AND pa_ds_cidade12 IS NOT NULL THEN
			IF pa_ds_cidade12 <> pn_ds_cidade12 THEN
				vr_ds_cidade12 := '"' || RTRIM(pn_ds_cidade12) || '"';
			ELSE
				vr_ds_cidade12 := '"' || RTRIM(pa_ds_cidade12) || '"';
			END IF;
		END IF;
		IF pn_ds_uf_cidade13 IS NULL
		AND pa_ds_uf_cidade13 IS NULL THEN
			vr_ds_uf_cidade13 := 'null';
		END IF;
		IF pn_ds_uf_cidade13 IS NULL
		AND pa_ds_uf_cidade13 IS NOT NULL THEN
			vr_ds_uf_cidade13 := 'null';
		END IF;
		IF pn_ds_uf_cidade13 IS NOT NULL
		AND pa_ds_uf_cidade13 IS NULL THEN
			vr_ds_uf_cidade13 := '"' || RTRIM(pn_ds_uf_cidade13) || '"';
		END IF;
		IF pn_ds_uf_cidade13 IS NOT NULL
		AND pa_ds_uf_cidade13 IS NOT NULL THEN
			IF pa_ds_uf_cidade13 <> pn_ds_uf_cidade13 THEN
				vr_ds_uf_cidade13 := '"' || RTRIM(pn_ds_uf_cidade13) || '"';
			ELSE
				vr_ds_uf_cidade13 := '"' || RTRIM(pa_ds_uf_cidade13) || '"';
			END IF;
		END IF;
		IF pn_ds_bairro14 IS NULL
		AND pa_ds_bairro14 IS NULL THEN
			vr_ds_bairro14 := 'null';
		END IF;
		IF pn_ds_bairro14 IS NULL
		AND pa_ds_bairro14 IS NOT NULL THEN
			vr_ds_bairro14 := 'null';
		END IF;
		IF pn_ds_bairro14 IS NOT NULL
		AND pa_ds_bairro14 IS NULL THEN
			vr_ds_bairro14 := '"' || RTRIM(pn_ds_bairro14) || '"';
		END IF;
		IF pn_ds_bairro14 IS NOT NULL
		AND pa_ds_bairro14 IS NOT NULL THEN
			IF pa_ds_bairro14 <> pn_ds_bairro14 THEN
				vr_ds_bairro14 := '"' || RTRIM(pn_ds_bairro14) || '"';
			ELSE
				vr_ds_bairro14 := '"' || RTRIM(pa_ds_bairro14) || '"';
			END IF;
		END IF;
		IF pn_nu_telefone15 IS NULL
		AND pa_nu_telefone15 IS NULL THEN
			vr_nu_telefone15 := 'null';
		END IF;
		IF pn_nu_telefone15 IS NULL
		AND pa_nu_telefone15 IS NOT NULL THEN
			vr_nu_telefone15 := 'null';
		END IF;
		IF pn_nu_telefone15 IS NOT NULL
		AND pa_nu_telefone15 IS NULL THEN
			vr_nu_telefone15 := '"' || RTRIM(pn_nu_telefone15) || '"';
		END IF;
		IF pn_nu_telefone15 IS NOT NULL
		AND pa_nu_telefone15 IS NOT NULL THEN
			IF pa_nu_telefone15 <> pn_nu_telefone15 THEN
				vr_nu_telefone15 := '"' || RTRIM(pn_nu_telefone15) || '"';
			ELSE
				vr_nu_telefone15 := '"' || RTRIM(pa_nu_telefone15) || '"';
			END IF;
		END IF;
		IF pn_nu_celular16 IS NULL
		AND pa_nu_celular16 IS NULL THEN
			vr_nu_celular16 := 'null';
		END IF;
		IF pn_nu_celular16 IS NULL
		AND pa_nu_celular16 IS NOT NULL THEN
			vr_nu_celular16 := 'null';
		END IF;
		IF pn_nu_celular16 IS NOT NULL
		AND pa_nu_celular16 IS NULL THEN
			vr_nu_celular16 := '"' || RTRIM(pn_nu_celular16) || '"';
		END IF;
		IF pn_nu_celular16 IS NOT NULL
		AND pa_nu_celular16 IS NOT NULL THEN
			IF pa_nu_celular16 <> pn_nu_celular16 THEN
				vr_nu_celular16 := '"' || RTRIM(pn_nu_celular16) || '"';
			ELSE
				vr_nu_celular16 := '"' || RTRIM(pa_nu_celular16) || '"';
			END IF;
		END IF;
		IF pn_ds_e_mail17 IS NULL
		AND pa_ds_e_mail17 IS NULL THEN
			vr_ds_e_mail17 := 'null';
		END IF;
		IF pn_ds_e_mail17 IS NULL
		AND pa_ds_e_mail17 IS NOT NULL THEN
			vr_ds_e_mail17 := 'null';
		END IF;
		IF pn_ds_e_mail17 IS NOT NULL
		AND pa_ds_e_mail17 IS NULL THEN
			vr_ds_e_mail17 := '"' || RTRIM(pn_ds_e_mail17) || '"';
		END IF;
		IF pn_ds_e_mail17 IS NOT NULL
		AND pa_ds_e_mail17 IS NOT NULL THEN
			IF pa_ds_e_mail17 <> pn_ds_e_mail17 THEN
				vr_ds_e_mail17 := '"' || RTRIM(pn_ds_e_mail17) || '"';
			ELSE
				vr_ds_e_mail17 := '"' || RTRIM(pa_ds_e_mail17) || '"';
			END IF;
		END IF;
		IF pn_tp_estado_civ18 IS NULL
		AND pa_tp_estado_civ18 IS NULL THEN
			vr_tp_estado_civ18 := 'null';
		END IF;
		IF pn_tp_estado_civ18 IS NULL
		AND pa_tp_estado_civ18 IS NOT NULL THEN
			vr_tp_estado_civ18 := 'null';
		END IF;
		IF pn_tp_estado_civ18 IS NOT NULL
		AND pa_tp_estado_civ18 IS NULL THEN
			vr_tp_estado_civ18 := '"' || RTRIM(pn_tp_estado_civ18) || '"';
		END IF;
		IF pn_tp_estado_civ18 IS NOT NULL
		AND pa_tp_estado_civ18 IS NOT NULL THEN
			IF pa_tp_estado_civ18 <> pn_tp_estado_civ18 THEN
				vr_tp_estado_civ18 := '"' || RTRIM(pn_tp_estado_civ18) || '"';
			ELSE
				vr_tp_estado_civ18 := '"' || RTRIM(pa_tp_estado_civ18) || '"';
			END IF;
		END IF;
		IF pn_ds_conjuge19 IS NULL
		AND pa_ds_conjuge19 IS NULL THEN
			vr_ds_conjuge19 := 'null';
		END IF;
		IF pn_ds_conjuge19 IS NULL
		AND pa_ds_conjuge19 IS NOT NULL THEN
			vr_ds_conjuge19 := 'null';
		END IF;
		IF pn_ds_conjuge19 IS NOT NULL
		AND pa_ds_conjuge19 IS NULL THEN
			vr_ds_conjuge19 := '"' || RTRIM(pn_ds_conjuge19) || '"';
		END IF;
		IF pn_ds_conjuge19 IS NOT NULL
		AND pa_ds_conjuge19 IS NOT NULL THEN
			IF pa_ds_conjuge19 <> pn_ds_conjuge19 THEN
				vr_ds_conjuge19 := '"' || RTRIM(pn_ds_conjuge19) || '"';
			ELSE
				vr_ds_conjuge19 := '"' || RTRIM(pa_ds_conjuge19) || '"';
			END IF;
		END IF;
		IF pn_nu_rg20 IS NULL
		AND pa_nu_rg20 IS NULL THEN
			vr_nu_rg20 := 'null';
		END IF;
		IF pn_nu_rg20 IS NULL
		AND pa_nu_rg20 IS NOT NULL THEN
			vr_nu_rg20 := 'null';
		END IF;
		IF pn_nu_rg20 IS NOT NULL
		AND pa_nu_rg20 IS NULL THEN
			vr_nu_rg20 := '"' || RTRIM(pn_nu_rg20) || '"';
		END IF;
		IF pn_nu_rg20 IS NOT NULL
		AND pa_nu_rg20 IS NOT NULL THEN
			IF pa_nu_rg20 <> pn_nu_rg20 THEN
				vr_nu_rg20 := '"' || RTRIM(pn_nu_rg20) || '"';
			ELSE
				vr_nu_rg20 := '"' || RTRIM(pa_nu_rg20) || '"';
			END IF;
		END IF;
		IF pn_ds_orgao_emis21 IS NULL
		AND pa_ds_orgao_emis21 IS NULL THEN
			vr_ds_orgao_emis21 := 'null';
		END IF;
		IF pn_ds_orgao_emis21 IS NULL
		AND pa_ds_orgao_emis21 IS NOT NULL THEN
			vr_ds_orgao_emis21 := 'null';
		END IF;
		IF pn_ds_orgao_emis21 IS NOT NULL
		AND pa_ds_orgao_emis21 IS NULL THEN
			vr_ds_orgao_emis21 := '"' || RTRIM(pn_ds_orgao_emis21) || '"';
		END IF;
		IF pn_ds_orgao_emis21 IS NOT NULL
		AND pa_ds_orgao_emis21 IS NOT NULL THEN
			IF pa_ds_orgao_emis21 <> pn_ds_orgao_emis21 THEN
				vr_ds_orgao_emis21 := '"' || RTRIM(pn_ds_orgao_emis21) || '"';
			ELSE
				vr_ds_orgao_emis21 := '"' || RTRIM(pa_ds_orgao_emis21) || '"';
			END IF;
		END IF;
		IF pn_dt_emissao22 IS NULL
		AND pa_dt_emissao22 IS NULL THEN
			vr_dt_emissao22 := 'null';
		END IF;
		IF pn_dt_emissao22 IS NULL
		AND pa_dt_emissao22 IS NOT NULL THEN
			vr_dt_emissao22 := 'null';
		END IF;
		IF pn_dt_emissao22 IS NOT NULL
		AND pa_dt_emissao22 IS NULL THEN
			vr_dt_emissao22 := '"' || pn_dt_emissao22 || '"';
		END IF;
		IF pn_dt_emissao22 IS NOT NULL
		AND pa_dt_emissao22 IS NOT NULL THEN
			IF pa_dt_emissao22 <> pn_dt_emissao22 THEN
				vr_dt_emissao22 := '"' || pn_dt_emissao22 || '"';
			ELSE
				vr_dt_emissao22 := '"' || pa_dt_emissao22 || '"';
			END IF;
		END IF;
		IF pn_nu_cpf23 IS NULL
		AND pa_nu_cpf23 IS NULL THEN
			vr_nu_cpf23 := 'null';
		END IF;
		IF pn_nu_cpf23 IS NULL
		AND pa_nu_cpf23 IS NOT NULL THEN
			vr_nu_cpf23 := 'null';
		END IF;
		IF pn_nu_cpf23 IS NOT NULL
		AND pa_nu_cpf23 IS NULL THEN
			vr_nu_cpf23 := '"' || RTRIM(pn_nu_cpf23) || '"';
		END IF;
		IF pn_nu_cpf23 IS NOT NULL
		AND pa_nu_cpf23 IS NOT NULL THEN
			IF pa_nu_cpf23 <> pn_nu_cpf23 THEN
				vr_nu_cpf23 := '"' || RTRIM(pn_nu_cpf23) || '"';
			ELSE
				vr_nu_cpf23 := '"' || RTRIM(pa_nu_cpf23) || '"';
			END IF;
		END IF;
		IF pn_ds_pai24 IS NULL
		AND pa_ds_pai24 IS NULL THEN
			vr_ds_pai24 := 'null';
		END IF;
		IF pn_ds_pai24 IS NULL
		AND pa_ds_pai24 IS NOT NULL THEN
			vr_ds_pai24 := 'null';
		END IF;
		IF pn_ds_pai24 IS NOT NULL
		AND pa_ds_pai24 IS NULL THEN
			vr_ds_pai24 := '"' || RTRIM(pn_ds_pai24) || '"';
		END IF;
		IF pn_ds_pai24 IS NOT NULL
		AND pa_ds_pai24 IS NOT NULL THEN
			IF pa_ds_pai24 <> pn_ds_pai24 THEN
				vr_ds_pai24 := '"' || RTRIM(pn_ds_pai24) || '"';
			ELSE
				vr_ds_pai24 := '"' || RTRIM(pa_ds_pai24) || '"';
			END IF;
		END IF;
		IF pn_ds_mae25 IS NULL
		AND pa_ds_mae25 IS NULL THEN
			vr_ds_mae25 := 'null';
		END IF;
		IF pn_ds_mae25 IS NULL
		AND pa_ds_mae25 IS NOT NULL THEN
			vr_ds_mae25 := 'null';
		END IF;
		IF pn_ds_mae25 IS NOT NULL
		AND pa_ds_mae25 IS NULL THEN
			vr_ds_mae25 := '"' || RTRIM(pn_ds_mae25) || '"';
		END IF;
		IF pn_ds_mae25 IS NOT NULL
		AND pa_ds_mae25 IS NOT NULL THEN
			IF pa_ds_mae25 <> pn_ds_mae25 THEN
				vr_ds_mae25 := '"' || RTRIM(pn_ds_mae25) || '"';
			ELSE
				vr_ds_mae25 := '"' || RTRIM(pa_ds_mae25) || '"';
			END IF;
		END IF;
		IF pn_nu_registro26 IS NULL
		AND pa_nu_registro26 IS NULL THEN
			vr_nu_registro26 := 'null';
		END IF;
		IF pn_nu_registro26 IS NULL
		AND pa_nu_registro26 IS NOT NULL THEN
			vr_nu_registro26 := 'null';
		END IF;
		IF pn_nu_registro26 IS NOT NULL
		AND pa_nu_registro26 IS NULL THEN
			vr_nu_registro26 := '"' || RTRIM(pn_nu_registro26) || '"';
		END IF;
		IF pn_nu_registro26 IS NOT NULL
		AND pa_nu_registro26 IS NOT NULL THEN
			IF pa_nu_registro26 <> pn_nu_registro26 THEN
				vr_nu_registro26 := '"' || RTRIM(pn_nu_registro26) || '"';
			ELSE
				vr_nu_registro26 := '"' || RTRIM(pa_nu_registro26) || '"';
			END IF;
		END IF;
		IF pn_ds_instrucao27 IS NULL
		AND pa_ds_instrucao27 IS NULL THEN
			vr_ds_instrucao27 := 'null';
		END IF;
		IF pn_ds_instrucao27 IS NULL
		AND pa_ds_instrucao27 IS NOT NULL THEN
			vr_ds_instrucao27 := 'null';
		END IF;
		IF pn_ds_instrucao27 IS NOT NULL
		AND pa_ds_instrucao27 IS NULL THEN
			vr_ds_instrucao27 := '"' || RTRIM(pn_ds_instrucao27) || '"';
		END IF;
		IF pn_ds_instrucao27 IS NOT NULL
		AND pa_ds_instrucao27 IS NOT NULL THEN
			IF pa_ds_instrucao27 <> pn_ds_instrucao27 THEN
				vr_ds_instrucao27 := '"' || RTRIM(pn_ds_instrucao27) || '"';
			ELSE
				vr_ds_instrucao27 := '"' || RTRIM(pa_ds_instrucao27) || '"';
			END IF;
		END IF;
		IF pn_co_unidade28 IS NULL
		AND pa_co_unidade28 IS NULL THEN
			vr_co_unidade28 := 'null';
		END IF;
		IF pn_co_unidade28 IS NULL
		AND pa_co_unidade28 IS NOT NULL THEN
			vr_co_unidade28 := 'null';
		END IF;
		IF pn_co_unidade28 IS NOT NULL
		AND pa_co_unidade28 IS NULL THEN
			vr_co_unidade28 := '"' || RTRIM(pn_co_unidade28) || '"';
		END IF;
		IF pn_co_unidade28 IS NOT NULL
		AND pa_co_unidade28 IS NOT NULL THEN
			IF pa_co_unidade28 <> pn_co_unidade28 THEN
				vr_co_unidade28 := '"' || RTRIM(pn_co_unidade28) || '"';
			ELSE
				vr_co_unidade28 := '"' || RTRIM(pa_co_unidade28) || '"';
			END IF;
		END IF;
		IF pn_lotacao_princ29 IS NULL
		AND pa_lotacao_princ29 IS NULL THEN
			vr_lotacao_princ29 := 'null';
		END IF;
		IF pn_lotacao_princ29 IS NULL
		AND pa_lotacao_princ29 IS NOT NULL THEN
			vr_lotacao_princ29 := 'null';
		END IF;
		IF pn_lotacao_princ29 IS NOT NULL
		AND pa_lotacao_princ29 IS NULL THEN
			vr_lotacao_princ29 := '"' || RTRIM(pn_lotacao_princ29) || '"';
		END IF;
		IF pn_lotacao_princ29 IS NOT NULL
		AND pa_lotacao_princ29 IS NOT NULL THEN
			IF pa_lotacao_princ29 <> pn_lotacao_princ29 THEN
				vr_lotacao_princ29 := '"' || RTRIM(pn_lotacao_princ29) || '"';
			ELSE
				vr_lotacao_princ29 := '"' || RTRIM(pa_lotacao_princ29) || '"';
			END IF;
		END IF;
		IF pn_lotacao_secun30 IS NULL
		AND pa_lotacao_secun30 IS NULL THEN
			vr_lotacao_secun30 := 'null';
		END IF;
		IF pn_lotacao_secun30 IS NULL
		AND pa_lotacao_secun30 IS NOT NULL THEN
			vr_lotacao_secun30 := 'null';
		END IF;
		IF pn_lotacao_secun30 IS NOT NULL
		AND pa_lotacao_secun30 IS NULL THEN
			vr_lotacao_secun30 := '"' || RTRIM(pn_lotacao_secun30) || '"';
		END IF;
		IF pn_lotacao_secun30 IS NOT NULL
		AND pa_lotacao_secun30 IS NOT NULL THEN
			IF pa_lotacao_secun30 <> pn_lotacao_secun30 THEN
				vr_lotacao_secun30 := '"' || RTRIM(pn_lotacao_secun30) || '"';
			ELSE
				vr_lotacao_secun30 := '"' || RTRIM(pa_lotacao_secun30) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_funcionario set co_funcionario = ' || RTRIM(vr_co_funcionari00) || '  , nu_matricula_mec = ' || RTRIM(vr_nu_matricula_01) || '  , co_seq_cidade = ' || RTRIM(vr_co_seq_cidade02) || '  , ds_funcionario = ' || RTRIM(vr_ds_funcionari03);
		v_sql2 := '  , ds_apelido = ' || RTRIM(vr_ds_apelido04) || '  , fo_funcionario = ' || RTRIM(vr_fo_funcionari05) || '  , tp_sexo = ' || RTRIM(vr_tp_sexo06) || '  , ds_naturalidade = ' || RTRIM(vr_ds_naturalida07) || '  , ds_uf_nascimento = ' || RTRIM(vr_ds_uf_nascime08);
		v_sql3 := '  , dt_nascimento = ' || RTRIM(vr_dt_nascimento09) || '  , ds_endereco = ' || RTRIM(vr_ds_endereco10) || '  , nu_cep = ' || RTRIM(vr_nu_cep11) || '  , ds_cidade = ' || RTRIM(vr_ds_cidade12) || '  , ds_uf_cidade = ' || RTRIM(vr_ds_uf_cidade13);
		v_sql4 := '  , ds_bairro = ' || RTRIM(vr_ds_bairro14) || '  , nu_telefone = ' || RTRIM(vr_nu_telefone15) || '  , nu_celular = ' || RTRIM(vr_nu_celular16) || '  , ds_e_mail = ' || RTRIM(vr_ds_e_mail17) || '  , tp_estado_civil = ' || RTRIM(vr_tp_estado_civ18) || '  , ds_conjuge = ' || RTRIM(vr_ds_conjuge19) || '  , nu_rg = ' || RTRIM(vr_nu_rg20);
		v_sql5 := '  , ds_orgao_emissor = ' || RTRIM(vr_ds_orgao_emis21) || '  , dt_emissao = ' || RTRIM(vr_dt_emissao22) || '  , nu_cpf = ' || RTRIM(vr_nu_cpf23) || '  , ds_pai = ' || RTRIM(vr_ds_pai24) || '  , ds_mae = ' || RTRIM(vr_ds_mae25) || '  , nu_registro = ' || RTRIM(vr_nu_registro26);
		v_sql6 := '  , ds_instrucao = ' || RTRIM(vr_ds_instrucao27) || '  , co_unidade = ' || RTRIM(vr_co_unidade28) || '  , lotacao_princ = ' || RTRIM(vr_lotacao_princ29) || '  , lotacao_secun = ' || RTRIM(vr_lotacao_secun30);
		v_sql7 := ' where co_funcionario = ' || RTRIM(vr_co_funcionari00) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5 || v_sql6 || v_sql7;
	END IF;
	IF p_op = 'del' THEN
		v_uni := pa_co_unidade28;
	ELSE
		v_uni := pn_co_unidade28;
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
		       's_funcionario',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       v_blob1,
		       NULL);
	END IF;
END pr_s_funciona098;
/

