CREATE OR REPLACE PROCEDURE pr_s_responsa142(
	P_OP_IN                CHAR,
	PA_co_responsave00_IN  s_responsavel.co_responsavel%TYPE,
	PA_ds_responsave01_IN  s_responsavel.ds_responsavel%TYPE,
	PA_co_unidade02_IN     s_responsavel.co_unidade%TYPE,
	PA_tp_sexo03_IN        s_responsavel.tp_sexo%TYPE,
	PA_co_tip_respon04_IN  s_responsavel.co_tip_responsavel%TYPE,
	PA_co_seq_cidade05_IN  s_responsavel.co_seq_cidade%TYPE,
	PA_ds_resp_ordem06_IN  s_responsavel.ds_resp_ordem%TYPE,
	PA_ds_naturalida07_IN  s_responsavel.ds_naturalidade%TYPE,
	PA_ds_uf_nascime08_IN  s_responsavel.ds_uf_nascimento%TYPE,
	PA_dt_nascimento09_IN  s_responsavel.dt_nascimento%TYPE,
	PA_ds_endereco10_IN    s_responsavel.ds_endereco%TYPE,
	PA_ds_bairro11_IN      s_responsavel.ds_bairro%TYPE,
	PA_nu_cep12_IN         s_responsavel.nu_cep%TYPE,
	PA_ds_cidade13_IN      s_responsavel.ds_cidade%TYPE,
	PA_ds_uf_cidade14_IN   s_responsavel.ds_uf_cidade%TYPE,
	PA_nu_telefone15_IN    s_responsavel.nu_telefone%TYPE,
	PA_nu_celular16_IN     s_responsavel.nu_celular%TYPE,
	PA_ds_profissao17_IN   s_responsavel.ds_profissao%TYPE,
	PA_ds_local_trab18_IN  s_responsavel.ds_local_trab%TYPE,
	PA_ds_endereco_t19_IN  s_responsavel.ds_endereco_trab%TYPE,
	PA_ds_bairro_tra20_IN  s_responsavel.ds_bairro_trab%TYPE,
	PA_nu_cep_trab21_IN    s_responsavel.nu_cep_trab%TYPE,
	PA_ds_cidade_tra22_IN  s_responsavel.ds_cidade_trab%TYPE,
	PA_ds_uf_cidade_23_IN  s_responsavel.ds_uf_cidade_trab%TYPE,
	PA_nu_telefone_t24_IN  s_responsavel.nu_telefone_trab%TYPE,
	PA_nu_ramal_trab25_IN  s_responsavel.nu_ramal_trab%TYPE,
	PA_ds_e_mail26_IN      s_responsavel.ds_e_mail%TYPE,
	PA_ds_instrucao27_IN   s_responsavel.ds_instrucao%TYPE,
	PA_nu_rg28_IN          s_responsavel.nu_rg%TYPE,
	PA_ds_orgao_emis29_IN  s_responsavel.ds_orgao_emissor%TYPE,
	PA_dt_emissao30_IN     s_responsavel.dt_emissao%TYPE,
	PA_nu_cpf31_IN         s_responsavel.nu_cpf%TYPE,
	PA_vl_renda_fami32_IN  s_responsavel.vl_renda_familiar%TYPE,
	PA_nu_dependente33_IN  s_responsavel.nu_dependentes%TYPE,
	PN_co_responsave00_IN  s_responsavel.co_responsavel%TYPE,
	PN_ds_responsave01_IN  s_responsavel.ds_responsavel%TYPE,
	PN_co_unidade02_IN     s_responsavel.co_unidade%TYPE,
	PN_tp_sexo03_IN        s_responsavel.tp_sexo%TYPE,
	PN_co_tip_respon04_IN  s_responsavel.co_tip_responsavel%TYPE,
	PN_co_seq_cidade05_IN  s_responsavel.co_seq_cidade%TYPE,
	PN_ds_resp_ordem06_IN  s_responsavel.ds_resp_ordem%TYPE,
	PN_ds_naturalida07_IN  s_responsavel.ds_naturalidade%TYPE,
	PN_ds_uf_nascime08_IN  s_responsavel.ds_uf_nascimento%TYPE,
	PN_dt_nascimento09_IN  s_responsavel.dt_nascimento%TYPE,
	PN_ds_endereco10_IN    s_responsavel.ds_endereco%TYPE,
	PN_ds_bairro11_IN      s_responsavel.ds_bairro%TYPE,
	PN_nu_cep12_IN         s_responsavel.nu_cep%TYPE,
	PN_ds_cidade13_IN      s_responsavel.ds_cidade%TYPE,
	PN_ds_uf_cidade14_IN   s_responsavel.ds_uf_cidade%TYPE,
	PN_nu_telefone15_IN    s_responsavel.nu_telefone%TYPE,
	PN_nu_celular16_IN     s_responsavel.nu_celular%TYPE,
	PN_ds_profissao17_IN   s_responsavel.ds_profissao%TYPE,
	PN_ds_local_trab18_IN  s_responsavel.ds_local_trab%TYPE,
	PN_ds_endereco_t19_IN  s_responsavel.ds_endereco_trab%TYPE,
	PN_ds_bairro_tra20_IN  s_responsavel.ds_bairro_trab%TYPE,
	PN_nu_cep_trab21_IN    s_responsavel.nu_cep_trab%TYPE,
	PN_ds_cidade_tra22_IN  s_responsavel.ds_cidade_trab%TYPE,
	PN_ds_uf_cidade_23_IN  s_responsavel.ds_uf_cidade_trab%TYPE,
	PN_nu_telefone_t24_IN  s_responsavel.nu_telefone_trab%TYPE,
	PN_nu_ramal_trab25_IN  s_responsavel.nu_ramal_trab%TYPE,
	PN_ds_e_mail26_IN      s_responsavel.ds_e_mail%TYPE,
	PN_ds_instrucao27_IN   s_responsavel.ds_instrucao%TYPE,
	PN_nu_rg28_IN          s_responsavel.nu_rg%TYPE,
	PN_ds_orgao_emis29_IN  s_responsavel.ds_orgao_emissor%TYPE,
	PN_dt_emissao30_IN     s_responsavel.dt_emissao%TYPE,
	PN_nu_cpf31_IN         s_responsavel.nu_cpf%TYPE,
	PN_vl_renda_fami32_IN  s_responsavel.vl_renda_familiar%TYPE,
	PN_nu_dependente33_IN  s_responsavel.nu_dependentes%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_responsave00  s_responsavel.co_responsavel%TYPE := PA_co_responsave00_IN;
PA_ds_responsave01  s_responsavel.ds_responsavel%TYPE := PA_ds_responsave01_IN;
PA_co_unidade02     s_responsavel.co_unidade%TYPE := PA_co_unidade02_IN;
PA_tp_sexo03        s_responsavel.tp_sexo%TYPE := PA_tp_sexo03_IN;
PA_co_tip_respon04  s_responsavel.co_tip_responsavel%TYPE := PA_co_tip_respon04_IN;
PA_co_seq_cidade05  s_responsavel.co_seq_cidade%TYPE := PA_co_seq_cidade05_IN;
PA_ds_resp_ordem06  s_responsavel.ds_resp_ordem%TYPE := PA_ds_resp_ordem06_IN;
PA_ds_naturalida07  s_responsavel.ds_naturalidade%TYPE := PA_ds_naturalida07_IN;
PA_ds_uf_nascime08  s_responsavel.ds_uf_nascimento%TYPE := PA_ds_uf_nascime08_IN;
PA_dt_nascimento09  s_responsavel.dt_nascimento%TYPE := PA_dt_nascimento09_IN;
PA_ds_endereco10    s_responsavel.ds_endereco%TYPE := PA_ds_endereco10_IN;
PA_ds_bairro11      s_responsavel.ds_bairro%TYPE := PA_ds_bairro11_IN;
PA_nu_cep12         s_responsavel.nu_cep%TYPE := PA_nu_cep12_IN;
PA_ds_cidade13      s_responsavel.ds_cidade%TYPE := PA_ds_cidade13_IN;
PA_ds_uf_cidade14   s_responsavel.ds_uf_cidade%TYPE := PA_ds_uf_cidade14_IN;
PA_nu_telefone15    s_responsavel.nu_telefone%TYPE := PA_nu_telefone15_IN;
PA_nu_celular16     s_responsavel.nu_celular%TYPE := PA_nu_celular16_IN;
PA_ds_profissao17   s_responsavel.ds_profissao%TYPE := PA_ds_profissao17_IN;
PA_ds_local_trab18  s_responsavel.ds_local_trab%TYPE := PA_ds_local_trab18_IN;
PA_ds_endereco_t19  s_responsavel.ds_endereco_trab%TYPE := PA_ds_endereco_t19_IN;
PA_ds_bairro_tra20  s_responsavel.ds_bairro_trab%TYPE := PA_ds_bairro_tra20_IN;
PA_nu_cep_trab21    s_responsavel.nu_cep_trab%TYPE := PA_nu_cep_trab21_IN;
PA_ds_cidade_tra22  s_responsavel.ds_cidade_trab%TYPE := PA_ds_cidade_tra22_IN;
PA_ds_uf_cidade_23  s_responsavel.ds_uf_cidade_trab%TYPE := PA_ds_uf_cidade_23_IN;
PA_nu_telefone_t24  s_responsavel.nu_telefone_trab%TYPE := PA_nu_telefone_t24_IN;
PA_nu_ramal_trab25  s_responsavel.nu_ramal_trab%TYPE := PA_nu_ramal_trab25_IN;
PA_ds_e_mail26      s_responsavel.ds_e_mail%TYPE := PA_ds_e_mail26_IN;
PA_ds_instrucao27   s_responsavel.ds_instrucao%TYPE := PA_ds_instrucao27_IN;
PA_nu_rg28          s_responsavel.nu_rg%TYPE := PA_nu_rg28_IN;
PA_ds_orgao_emis29  s_responsavel.ds_orgao_emissor%TYPE := PA_ds_orgao_emis29_IN;
PA_dt_emissao30     s_responsavel.dt_emissao%TYPE := PA_dt_emissao30_IN;
PA_nu_cpf31         s_responsavel.nu_cpf%TYPE := PA_nu_cpf31_IN;
PA_vl_renda_fami32  s_responsavel.vl_renda_familiar%TYPE := PA_vl_renda_fami32_IN;
PA_nu_dependente33  s_responsavel.nu_dependentes%TYPE := PA_nu_dependente33_IN;
PN_co_responsave00  s_responsavel.co_responsavel%TYPE := PN_co_responsave00_IN;
PN_ds_responsave01  s_responsavel.ds_responsavel%TYPE := PN_ds_responsave01_IN;
PN_co_unidade02     s_responsavel.co_unidade%TYPE := PN_co_unidade02_IN;
PN_tp_sexo03        s_responsavel.tp_sexo%TYPE := PN_tp_sexo03_IN;
PN_co_tip_respon04  s_responsavel.co_tip_responsavel%TYPE := PN_co_tip_respon04_IN;
PN_co_seq_cidade05  s_responsavel.co_seq_cidade%TYPE := PN_co_seq_cidade05_IN;
PN_ds_resp_ordem06  s_responsavel.ds_resp_ordem%TYPE := PN_ds_resp_ordem06_IN;
PN_ds_naturalida07  s_responsavel.ds_naturalidade%TYPE := PN_ds_naturalida07_IN;
PN_ds_uf_nascime08  s_responsavel.ds_uf_nascimento%TYPE := PN_ds_uf_nascime08_IN;
PN_dt_nascimento09  s_responsavel.dt_nascimento%TYPE := PN_dt_nascimento09_IN;
PN_ds_endereco10    s_responsavel.ds_endereco%TYPE := PN_ds_endereco10_IN;
PN_ds_bairro11      s_responsavel.ds_bairro%TYPE := PN_ds_bairro11_IN;
PN_nu_cep12         s_responsavel.nu_cep%TYPE := PN_nu_cep12_IN;
PN_ds_cidade13      s_responsavel.ds_cidade%TYPE := PN_ds_cidade13_IN;
PN_ds_uf_cidade14   s_responsavel.ds_uf_cidade%TYPE := PN_ds_uf_cidade14_IN;
PN_nu_telefone15    s_responsavel.nu_telefone%TYPE := PN_nu_telefone15_IN;
PN_nu_celular16     s_responsavel.nu_celular%TYPE := PN_nu_celular16_IN;
PN_ds_profissao17   s_responsavel.ds_profissao%TYPE := PN_ds_profissao17_IN;
PN_ds_local_trab18  s_responsavel.ds_local_trab%TYPE := PN_ds_local_trab18_IN;
PN_ds_endereco_t19  s_responsavel.ds_endereco_trab%TYPE := PN_ds_endereco_t19_IN;
PN_ds_bairro_tra20  s_responsavel.ds_bairro_trab%TYPE := PN_ds_bairro_tra20_IN;
PN_nu_cep_trab21    s_responsavel.nu_cep_trab%TYPE := PN_nu_cep_trab21_IN;
PN_ds_cidade_tra22  s_responsavel.ds_cidade_trab%TYPE := PN_ds_cidade_tra22_IN;
PN_ds_uf_cidade_23  s_responsavel.ds_uf_cidade_trab%TYPE := PN_ds_uf_cidade_23_IN;
PN_nu_telefone_t24  s_responsavel.nu_telefone_trab%TYPE := PN_nu_telefone_t24_IN;
PN_nu_ramal_trab25  s_responsavel.nu_ramal_trab%TYPE := PN_nu_ramal_trab25_IN;
PN_ds_e_mail26      s_responsavel.ds_e_mail%TYPE := PN_ds_e_mail26_IN;
PN_ds_instrucao27   s_responsavel.ds_instrucao%TYPE := PN_ds_instrucao27_IN;
PN_nu_rg28          s_responsavel.nu_rg%TYPE := PN_nu_rg28_IN;
PN_ds_orgao_emis29  s_responsavel.ds_orgao_emissor%TYPE := PN_ds_orgao_emis29_IN;
PN_dt_emissao30     s_responsavel.dt_emissao%TYPE := PN_dt_emissao30_IN;
PN_nu_cpf31         s_responsavel.nu_cpf%TYPE := PN_nu_cpf31_IN;
PN_vl_renda_fami32  s_responsavel.vl_renda_familiar%TYPE := PN_vl_renda_fami32_IN;
PN_nu_dependente33  s_responsavel.nu_dependentes%TYPE := PN_nu_dependente33_IN;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(550);
v_sql2              CHAR(350);
v_sql3              CHAR(350);
v_sql4              CHAR(350);
v_sql5              CHAR(350);
v_sql6              CHAR(350);
v_sql7              CHAR(350);
v_sql8              CHAR(350);
v_uni               CHAR(10);
vr_co_responsave00  CHAR(30);
vr_ds_responsave01  CHAR(50);
vr_co_unidade02     CHAR(10);
vr_tp_sexo03        CHAR(10);
vr_co_tip_respon04  CHAR(10);
vr_co_seq_cidade05  CHAR(10);
vr_ds_resp_ordem06  CHAR(40);
vr_ds_naturalida07  CHAR(30);
vr_ds_uf_nascime08  CHAR(10);
vr_dt_nascimento09  CHAR(40);
vr_ds_endereco10    CHAR(50);
vr_ds_bairro11      CHAR(30);
vr_nu_cep12         CHAR(20);
vr_ds_cidade13      CHAR(30);
vr_ds_uf_cidade14   CHAR(10);
vr_nu_telefone15    CHAR(30);
vr_nu_celular16     CHAR(30);
vr_ds_profissao17   CHAR(40);
vr_ds_local_trab18  CHAR(50);
vr_ds_endereco_t19  CHAR(50);
vr_ds_bairro_tra20  CHAR(30);
vr_nu_cep_trab21    CHAR(20);
vr_ds_cidade_tra22  CHAR(30);
vr_ds_uf_cidade_23  CHAR(10);
vr_nu_telefone_t24  CHAR(30);
vr_nu_ramal_trab25  CHAR(20);
vr_ds_e_mail26      CHAR(110);
vr_ds_instrucao27   CHAR(50);
vr_nu_rg28          CHAR(30);
vr_ds_orgao_emis29  CHAR(40);
vr_dt_emissao30     CHAR(40);
vr_nu_cpf31         CHAR(30);
vr_vl_renda_fami32  CHAR(10);
vr_nu_dependente33  CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_responsave00 IS NULL THEN
			vr_co_responsave00 := 'null';
		ELSE
			vr_co_responsave00 := pn_co_responsave00;
		END IF;
		IF pn_ds_responsave01 IS NULL THEN
			vr_ds_responsave01 := 'null';
		ELSE
			vr_ds_responsave01 := pn_ds_responsave01;
		END IF;
		IF pn_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := pn_co_unidade02;
		END IF;
		IF pn_tp_sexo03 IS NULL THEN
			vr_tp_sexo03 := 'null';
		ELSE
			vr_tp_sexo03 := pn_tp_sexo03;
		END IF;
		IF pn_co_tip_respon04 IS NULL THEN
			vr_co_tip_respon04 := 'null';
		ELSE
			vr_co_tip_respon04 := pn_co_tip_respon04;
		END IF;
		IF pn_co_seq_cidade05 IS NULL THEN
			vr_co_seq_cidade05 := 'null';
		ELSE
			vr_co_seq_cidade05 := pn_co_seq_cidade05;
		END IF;
		IF pn_ds_resp_ordem06 IS NULL THEN
			vr_ds_resp_ordem06 := 'null';
		ELSE
			vr_ds_resp_ordem06 := pn_ds_resp_ordem06;
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
		IF pn_ds_bairro11 IS NULL THEN
			vr_ds_bairro11 := 'null';
		ELSE
			vr_ds_bairro11 := pn_ds_bairro11;
		END IF;
		IF pn_nu_cep12 IS NULL THEN
			vr_nu_cep12 := 'null';
		ELSE
			vr_nu_cep12 := pn_nu_cep12;
		END IF;
		IF pn_ds_cidade13 IS NULL THEN
			vr_ds_cidade13 := 'null';
		ELSE
			vr_ds_cidade13 := pn_ds_cidade13;
		END IF;
		IF pn_ds_uf_cidade14 IS NULL THEN
			vr_ds_uf_cidade14 := 'null';
		ELSE
			vr_ds_uf_cidade14 := pn_ds_uf_cidade14;
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
		IF pn_ds_profissao17 IS NULL THEN
			vr_ds_profissao17 := 'null';
		ELSE
			vr_ds_profissao17 := pn_ds_profissao17;
		END IF;
		IF pn_ds_local_trab18 IS NULL THEN
			vr_ds_local_trab18 := 'null';
		ELSE
			vr_ds_local_trab18 := pn_ds_local_trab18;
		END IF;
		IF pn_ds_endereco_t19 IS NULL THEN
			vr_ds_endereco_t19 := 'null';
		ELSE
			vr_ds_endereco_t19 := pn_ds_endereco_t19;
		END IF;
		IF pn_ds_bairro_tra20 IS NULL THEN
			vr_ds_bairro_tra20 := 'null';
		ELSE
			vr_ds_bairro_tra20 := pn_ds_bairro_tra20;
		END IF;
		IF pn_nu_cep_trab21 IS NULL THEN
			vr_nu_cep_trab21 := 'null';
		ELSE
			vr_nu_cep_trab21 := pn_nu_cep_trab21;
		END IF;
		IF pn_ds_cidade_tra22 IS NULL THEN
			vr_ds_cidade_tra22 := 'null';
		ELSE
			vr_ds_cidade_tra22 := pn_ds_cidade_tra22;
		END IF;
		IF pn_ds_uf_cidade_23 IS NULL THEN
			vr_ds_uf_cidade_23 := 'null';
		ELSE
			vr_ds_uf_cidade_23 := pn_ds_uf_cidade_23;
		END IF;
		IF pn_nu_telefone_t24 IS NULL THEN
			vr_nu_telefone_t24 := 'null';
		ELSE
			vr_nu_telefone_t24 := pn_nu_telefone_t24;
		END IF;
		IF pn_nu_ramal_trab25 IS NULL THEN
			vr_nu_ramal_trab25 := 'null';
		ELSE
			vr_nu_ramal_trab25 := pn_nu_ramal_trab25;
		END IF;
		IF pn_ds_e_mail26 IS NULL THEN
			vr_ds_e_mail26 := 'null';
		ELSE
			vr_ds_e_mail26 := pn_ds_e_mail26;
		END IF;
		IF pn_ds_instrucao27 IS NULL THEN
			vr_ds_instrucao27 := 'null';
		ELSE
			vr_ds_instrucao27 := pn_ds_instrucao27;
		END IF;
		IF pn_nu_rg28 IS NULL THEN
			vr_nu_rg28 := 'null';
		ELSE
			vr_nu_rg28 := pn_nu_rg28;
		END IF;
		IF pn_ds_orgao_emis29 IS NULL THEN
			vr_ds_orgao_emis29 := 'null';
		ELSE
			vr_ds_orgao_emis29 := pn_ds_orgao_emis29;
		END IF;
		IF pn_dt_emissao30 IS NULL THEN
			vr_dt_emissao30 := 'null';
		ELSE
			vr_dt_emissao30 := pn_dt_emissao30;
		END IF;
		IF pn_nu_cpf31 IS NULL THEN
			vr_nu_cpf31 := 'null';
		ELSE
			vr_nu_cpf31 := pn_nu_cpf31;
		END IF;
		IF pn_vl_renda_fami32 IS NULL THEN
			vr_vl_renda_fami32 := 'null';
		ELSE
			vr_vl_renda_fami32 := pn_vl_renda_fami32;
		END IF;
		IF pn_nu_dependente33 IS NULL THEN
			vr_nu_dependente33 := 'null';
		ELSE
			vr_nu_dependente33 := pn_nu_dependente33;
		END IF;
		v_sql1 := 'insert into s_responsavel(co_responsavel, ds_responsavel, co_unidade, tp_sexo, CO_TIPO_RESPONSAVEL, co_seq_cidade, DS_RESPONSAVEL_ORDEM, ds_naturalidade, ' || 'ds_uf_nascimento, dt_nascimento, ds_endereco, ds_bairro, nu_cep, ds_cidade, ds_uf_cidade, nu_telefone, nu_celular, ds_profissao, ds_local_trab, ds_endereco_trab, ds_bairro_trab, ' || 'nu_cep_trab, ds_cidade_trab, ds_uf_cidade_trab, nu_telefone_trab, nu_ramal_trab, ds_e_mail, ds_instrucao, nu_rg, ds_orgao_emissor, dt_emissao, nu_cpf, ' || 'vl_renda_familiar, nu_dependentes) values (';
		v_sql2 := '"' || RTRIM(vr_co_responsave00) || '"' || ',' || '"' || RTRIM(vr_ds_responsave01) || '"' || ',' || '"' || RTRIM(vr_co_unidade02) || '"' || ',' || '"' || RTRIM(vr_tp_sexo03) || '"' || ',' || RTRIM(vr_co_tip_respon04) || ',';
		v_sql3 := RTRIM(vr_co_seq_cidade05) || ',' || '"' || RTRIM(vr_ds_resp_ordem06) || '"' || ',' || '"' || RTRIM(vr_ds_naturalida07) || '"' || ',' || '"' || RTRIM(vr_ds_uf_nascime08) || '"' || ',' || '"' || vr_dt_nascimento09 || '"' || ',';
		v_sql4 := '"' || RTRIM(vr_ds_endereco10) || '"' || ',' || '"' || RTRIM(vr_ds_bairro11) || '"' || ',' || '"' || RTRIM(vr_nu_cep12) || '"' || ',' || '"' || RTRIM(vr_ds_cidade13) || '"' || ',' || '"' || RTRIM(vr_ds_uf_cidade14) || '"' || ',';
		v_sql5 := '"' || RTRIM(vr_nu_telefone15) || '"' || ',' || '"' || RTRIM(vr_nu_celular16) || '"' || ',' || '"' || RTRIM(vr_ds_profissao17) || '"' || ',' || '"' || RTRIM(vr_ds_local_trab18) || '"' || ',' || '"' || RTRIM(vr_ds_endereco_t19) || '"' || ',';
		v_sql6 := '"' || RTRIM(vr_ds_bairro_tra20) || '"' || ',' || '"' || RTRIM(vr_nu_cep_trab21) || '"' || ',' || '"' || RTRIM(vr_ds_cidade_tra22) || '"' || ',' || '"' || RTRIM(vr_ds_uf_cidade_23) || '"' || ',' || '"' || RTRIM(vr_nu_telefone_t24) || '"' || ',';
		v_sql7 := '"' || RTRIM(vr_nu_ramal_trab25) || '"' || ',' || '"' || RTRIM(vr_ds_e_mail26) || '"' || ',' || '"' || RTRIM(vr_ds_instrucao27) || '"' || ',' || '"' || RTRIM(vr_nu_rg28) || '"' || ',' || '"' || RTRIM(vr_ds_orgao_emis29) || '"' || ',';
		v_sql8 := '"' || vr_dt_emissao30 || '"' || ',' || '"' || RTRIM(vr_nu_cpf31) || '"' || ',' || RTRIM(vr_vl_renda_fami32) || ',' || '"' || RTRIM(vr_nu_dependente33) || '"' || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5 || v_sql6 || v_sql7 || v_sql8;
	ELSIF p_op = 'del' THEN
		IF pa_co_responsave00 IS NULL THEN
			vr_co_responsave00 := 'null';
		ELSE
			vr_co_responsave00 := '"' || RTRIM(pa_co_responsave00) || '"';
		END IF;
		IF pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := '"' || RTRIM(pa_co_unidade02) || '"';
		END IF;
		v_sql1 := '  delete from s_responsavel where co_responsavel = ' || RTRIM(vr_co_responsave00) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_responsave00 IS NULL
		AND pa_co_responsave00 IS NULL THEN
			vr_co_responsave00 := 'null';
		END IF;
		IF pn_co_responsave00 IS NULL
		AND pa_co_responsave00 IS NOT NULL THEN
			vr_co_responsave00 := 'null';
		END IF;
		IF pn_co_responsave00 IS NOT NULL
		AND pa_co_responsave00 IS NULL THEN
			vr_co_responsave00 := '"' || RTRIM(pn_co_responsave00) || '"';
		END IF;
		IF pn_co_responsave00 IS NOT NULL
		AND pa_co_responsave00 IS NOT NULL THEN
			IF pa_co_responsave00 <> pn_co_responsave00 THEN
				vr_co_responsave00 := '"' || RTRIM(pn_co_responsave00) || '"';
			ELSE
				vr_co_responsave00 := '"' || RTRIM(pa_co_responsave00) || '"';
			END IF;
		END IF;
		IF pn_ds_responsave01 IS NULL
		AND pa_ds_responsave01 IS NULL THEN
			vr_ds_responsave01 := 'null';
		END IF;
		IF pn_ds_responsave01 IS NULL
		AND pa_ds_responsave01 IS NOT NULL THEN
			vr_ds_responsave01 := 'null';
		END IF;
		IF pn_ds_responsave01 IS NOT NULL
		AND pa_ds_responsave01 IS NULL THEN
			vr_ds_responsave01 := '"' || RTRIM(pn_ds_responsave01) || '"';
		END IF;
		IF pn_ds_responsave01 IS NOT NULL
		AND pa_ds_responsave01 IS NOT NULL THEN
			IF pa_ds_responsave01 <> pn_ds_responsave01 THEN
				vr_ds_responsave01 := '"' || RTRIM(pn_ds_responsave01) || '"';
			ELSE
				vr_ds_responsave01 := '"' || RTRIM(pa_ds_responsave01) || '"';
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
		IF pn_tp_sexo03 IS NULL
		AND pa_tp_sexo03 IS NULL THEN
			vr_tp_sexo03 := 'null';
		END IF;
		IF pn_tp_sexo03 IS NULL
		AND pa_tp_sexo03 IS NOT NULL THEN
			vr_tp_sexo03 := 'null';
		END IF;
		IF pn_tp_sexo03 IS NOT NULL
		AND pa_tp_sexo03 IS NULL THEN
			vr_tp_sexo03 := '"' || RTRIM(pn_tp_sexo03) || '"';
		END IF;
		IF pn_tp_sexo03 IS NOT NULL
		AND pa_tp_sexo03 IS NOT NULL THEN
			IF pa_tp_sexo03 <> pn_tp_sexo03 THEN
				vr_tp_sexo03 := '"' || RTRIM(pn_tp_sexo03) || '"';
			ELSE
				vr_tp_sexo03 := '"' || RTRIM(pa_tp_sexo03) || '"';
			END IF;
		END IF;
		IF pn_co_tip_respon04 IS NULL
		AND pa_co_tip_respon04 IS NULL THEN
			vr_co_tip_respon04 := 'null';
		END IF;
		IF pn_co_tip_respon04 IS NULL
		AND pa_co_tip_respon04 IS NOT NULL THEN
			vr_co_tip_respon04 := 'null';
		END IF;
		IF pn_co_tip_respon04 IS NOT NULL
		AND pa_co_tip_respon04 IS NULL THEN
			vr_co_tip_respon04 := pn_co_tip_respon04;
		END IF;
		IF pn_co_tip_respon04 IS NOT NULL
		AND pa_co_tip_respon04 IS NOT NULL THEN
			IF pa_co_tip_respon04 <> pn_co_tip_respon04 THEN
				vr_co_tip_respon04 := pn_co_tip_respon04;
			ELSE
				vr_co_tip_respon04 := pa_co_tip_respon04;
			END IF;
		END IF;
		IF pn_co_seq_cidade05 IS NULL
		AND pa_co_seq_cidade05 IS NULL THEN
			vr_co_seq_cidade05 := 'null';
		END IF;
		IF pn_co_seq_cidade05 IS NULL
		AND pa_co_seq_cidade05 IS NOT NULL THEN
			vr_co_seq_cidade05 := 'null';
		END IF;
		IF pn_co_seq_cidade05 IS NOT NULL
		AND pa_co_seq_cidade05 IS NULL THEN
			vr_co_seq_cidade05 := pn_co_seq_cidade05;
		END IF;
		IF pn_co_seq_cidade05 IS NOT NULL
		AND pa_co_seq_cidade05 IS NOT NULL THEN
			IF pa_co_seq_cidade05 <> pn_co_seq_cidade05 THEN
				vr_co_seq_cidade05 := pn_co_seq_cidade05;
			ELSE
				vr_co_seq_cidade05 := pa_co_seq_cidade05;
			END IF;
		END IF;
		IF pn_ds_resp_ordem06 IS NULL
		AND pa_ds_resp_ordem06 IS NULL THEN
			vr_ds_resp_ordem06 := 'null';
		END IF;
		IF pn_ds_resp_ordem06 IS NULL
		AND pa_ds_resp_ordem06 IS NOT NULL THEN
			vr_ds_resp_ordem06 := 'null';
		END IF;
		IF pn_ds_resp_ordem06 IS NOT NULL
		AND pa_ds_resp_ordem06 IS NULL THEN
			vr_ds_resp_ordem06 := '"' || RTRIM(pn_ds_resp_ordem06) || '"';
		END IF;
		IF pn_ds_resp_ordem06 IS NOT NULL
		AND pa_ds_resp_ordem06 IS NOT NULL THEN
			IF pa_ds_resp_ordem06 <> pn_ds_resp_ordem06 THEN
				vr_ds_resp_ordem06 := '"' || RTRIM(pn_ds_resp_ordem06) || '"';
			ELSE
				vr_ds_resp_ordem06 := '"' || RTRIM(pa_ds_resp_ordem06) || '"';
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
		IF pn_ds_bairro11 IS NULL
		AND pa_ds_bairro11 IS NULL THEN
			vr_ds_bairro11 := 'null';
		END IF;
		IF pn_ds_bairro11 IS NULL
		AND pa_ds_bairro11 IS NOT NULL THEN
			vr_ds_bairro11 := 'null';
		END IF;
		IF pn_ds_bairro11 IS NOT NULL
		AND pa_ds_bairro11 IS NULL THEN
			vr_ds_bairro11 := '"' || RTRIM(pn_ds_bairro11) || '"';
		END IF;
		IF pn_ds_bairro11 IS NOT NULL
		AND pa_ds_bairro11 IS NOT NULL THEN
			IF pa_ds_bairro11 <> pn_ds_bairro11 THEN
				vr_ds_bairro11 := '"' || RTRIM(pn_ds_bairro11) || '"';
			ELSE
				vr_ds_bairro11 := '"' || RTRIM(pa_ds_bairro11) || '"';
			END IF;
		END IF;
		IF pn_nu_cep12 IS NULL
		AND pa_nu_cep12 IS NULL THEN
			vr_nu_cep12 := 'null';
		END IF;
		IF pn_nu_cep12 IS NULL
		AND pa_nu_cep12 IS NOT NULL THEN
			vr_nu_cep12 := 'null';
		END IF;
		IF pn_nu_cep12 IS NOT NULL
		AND pa_nu_cep12 IS NULL THEN
			vr_nu_cep12 := '"' || RTRIM(pn_nu_cep12) || '"';
		END IF;
		IF pn_nu_cep12 IS NOT NULL
		AND pa_nu_cep12 IS NOT NULL THEN
			IF pa_nu_cep12 <> pn_nu_cep12 THEN
				vr_nu_cep12 := '"' || RTRIM(pn_nu_cep12) || '"';
			ELSE
				vr_nu_cep12 := '"' || RTRIM(pa_nu_cep12) || '"';
			END IF;
		END IF;
		IF pn_ds_cidade13 IS NULL
		AND pa_ds_cidade13 IS NULL THEN
			vr_ds_cidade13 := 'null';
		END IF;
		IF pn_ds_cidade13 IS NULL
		AND pa_ds_cidade13 IS NOT NULL THEN
			vr_ds_cidade13 := 'null';
		END IF;
		IF pn_ds_cidade13 IS NOT NULL
		AND pa_ds_cidade13 IS NULL THEN
			vr_ds_cidade13 := '"' || RTRIM(pn_ds_cidade13) || '"';
		END IF;
		IF pn_ds_cidade13 IS NOT NULL
		AND pa_ds_cidade13 IS NOT NULL THEN
			IF pa_ds_cidade13 <> pn_ds_cidade13 THEN
				vr_ds_cidade13 := '"' || RTRIM(pn_ds_cidade13) || '"';
			ELSE
				vr_ds_cidade13 := '"' || RTRIM(pa_ds_cidade13) || '"';
			END IF;
		END IF;
		IF pn_ds_uf_cidade14 IS NULL
		AND pa_ds_uf_cidade14 IS NULL THEN
			vr_ds_uf_cidade14 := 'null';
		END IF;
		IF pn_ds_uf_cidade14 IS NULL
		AND pa_ds_uf_cidade14 IS NOT NULL THEN
			vr_ds_uf_cidade14 := 'null';
		END IF;
		IF pn_ds_uf_cidade14 IS NOT NULL
		AND pa_ds_uf_cidade14 IS NULL THEN
			vr_ds_uf_cidade14 := '"' || RTRIM(pn_ds_uf_cidade14) || '"';
		END IF;
		IF pn_ds_uf_cidade14 IS NOT NULL
		AND pa_ds_uf_cidade14 IS NOT NULL THEN
			IF pa_ds_uf_cidade14 <> pn_ds_uf_cidade14 THEN
				vr_ds_uf_cidade14 := '"' || RTRIM(pn_ds_uf_cidade14) || '"';
			ELSE
				vr_ds_uf_cidade14 := '"' || RTRIM(pa_ds_uf_cidade14) || '"';
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
		IF pn_ds_profissao17 IS NULL
		AND pa_ds_profissao17 IS NULL THEN
			vr_ds_profissao17 := 'null';
		END IF;
		IF pn_ds_profissao17 IS NULL
		AND pa_ds_profissao17 IS NOT NULL THEN
			vr_ds_profissao17 := 'null';
		END IF;
		IF pn_ds_profissao17 IS NOT NULL
		AND pa_ds_profissao17 IS NULL THEN
			vr_ds_profissao17 := '"' || RTRIM(pn_ds_profissao17) || '"';
		END IF;
		IF pn_ds_profissao17 IS NOT NULL
		AND pa_ds_profissao17 IS NOT NULL THEN
			IF pa_ds_profissao17 <> pn_ds_profissao17 THEN
				vr_ds_profissao17 := '"' || RTRIM(pn_ds_profissao17) || '"';
			ELSE
				vr_ds_profissao17 := '"' || RTRIM(pa_ds_profissao17) || '"';
			END IF;
		END IF;
		IF pn_ds_local_trab18 IS NULL
		AND pa_ds_local_trab18 IS NULL THEN
			vr_ds_local_trab18 := 'null';
		END IF;
		IF pn_ds_local_trab18 IS NULL
		AND pa_ds_local_trab18 IS NOT NULL THEN
			vr_ds_local_trab18 := 'null';
		END IF;
		IF pn_ds_local_trab18 IS NOT NULL
		AND pa_ds_local_trab18 IS NULL THEN
			vr_ds_local_trab18 := '"' || RTRIM(pn_ds_local_trab18) || '"';
		END IF;
		IF pn_ds_local_trab18 IS NOT NULL
		AND pa_ds_local_trab18 IS NOT NULL THEN
			IF pa_ds_local_trab18 <> pn_ds_local_trab18 THEN
				vr_ds_local_trab18 := '"' || RTRIM(pn_ds_local_trab18) || '"';
			ELSE
				vr_ds_local_trab18 := '"' || RTRIM(pa_ds_local_trab18) || '"';
			END IF;
		END IF;
		IF pn_ds_endereco_t19 IS NULL
		AND pa_ds_endereco_t19 IS NULL THEN
			vr_ds_endereco_t19 := 'null';
		END IF;
		IF pn_ds_endereco_t19 IS NULL
		AND pa_ds_endereco_t19 IS NOT NULL THEN
			vr_ds_endereco_t19 := 'null';
		END IF;
		IF pn_ds_endereco_t19 IS NOT NULL
		AND pa_ds_endereco_t19 IS NULL THEN
			vr_ds_endereco_t19 := '"' || RTRIM(pn_ds_endereco_t19) || '"';
		END IF;
		IF pn_ds_endereco_t19 IS NOT NULL
		AND pa_ds_endereco_t19 IS NOT NULL THEN
			IF pa_ds_endereco_t19 <> pn_ds_endereco_t19 THEN
				vr_ds_endereco_t19 := '"' || RTRIM(pn_ds_endereco_t19) || '"';
			ELSE
				vr_ds_endereco_t19 := '"' || RTRIM(pa_ds_endereco_t19) || '"';
			END IF;
		END IF;
		IF pn_ds_bairro_tra20 IS NULL
		AND pa_ds_bairro_tra20 IS NULL THEN
			vr_ds_bairro_tra20 := 'null';
		END IF;
		IF pn_ds_bairro_tra20 IS NULL
		AND pa_ds_bairro_tra20 IS NOT NULL THEN
			vr_ds_bairro_tra20 := 'null';
		END IF;
		IF pn_ds_bairro_tra20 IS NOT NULL
		AND pa_ds_bairro_tra20 IS NULL THEN
			vr_ds_bairro_tra20 := '"' || RTRIM(pn_ds_bairro_tra20) || '"';
		END IF;
		IF pn_ds_bairro_tra20 IS NOT NULL
		AND pa_ds_bairro_tra20 IS NOT NULL THEN
			IF pa_ds_bairro_tra20 <> pn_ds_bairro_tra20 THEN
				vr_ds_bairro_tra20 := '"' || RTRIM(pn_ds_bairro_tra20) || '"';
			ELSE
				vr_ds_bairro_tra20 := '"' || RTRIM(pa_ds_bairro_tra20) || '"';
			END IF;
		END IF;
		IF pn_nu_cep_trab21 IS NULL
		AND pa_nu_cep_trab21 IS NULL THEN
			vr_nu_cep_trab21 := 'null';
		END IF;
		IF pn_nu_cep_trab21 IS NULL
		AND pa_nu_cep_trab21 IS NOT NULL THEN
			vr_nu_cep_trab21 := 'null';
		END IF;
		IF pn_nu_cep_trab21 IS NOT NULL
		AND pa_nu_cep_trab21 IS NULL THEN
			vr_nu_cep_trab21 := '"' || RTRIM(pn_nu_cep_trab21) || '"';
		END IF;
		IF pn_nu_cep_trab21 IS NOT NULL
		AND pa_nu_cep_trab21 IS NOT NULL THEN
			IF pa_nu_cep_trab21 <> pn_nu_cep_trab21 THEN
				vr_nu_cep_trab21 := '"' || RTRIM(pn_nu_cep_trab21) || '"';
			ELSE
				vr_nu_cep_trab21 := '"' || RTRIM(pa_nu_cep_trab21) || '"';
			END IF;
		END IF;
		IF pn_ds_cidade_tra22 IS NULL
		AND pa_ds_cidade_tra22 IS NULL THEN
			vr_ds_cidade_tra22 := 'null';
		END IF;
		IF pn_ds_cidade_tra22 IS NULL
		AND pa_ds_cidade_tra22 IS NOT NULL THEN
			vr_ds_cidade_tra22 := 'null';
		END IF;
		IF pn_ds_cidade_tra22 IS NOT NULL
		AND pa_ds_cidade_tra22 IS NULL THEN
			vr_ds_cidade_tra22 := '"' || RTRIM(pn_ds_cidade_tra22) || '"';
		END IF;
		IF pn_ds_cidade_tra22 IS NOT NULL
		AND pa_ds_cidade_tra22 IS NOT NULL THEN
			IF pa_ds_cidade_tra22 <> pn_ds_cidade_tra22 THEN
				vr_ds_cidade_tra22 := '"' || RTRIM(pn_ds_cidade_tra22) || '"';
			ELSE
				vr_ds_cidade_tra22 := '"' || RTRIM(pa_ds_cidade_tra22) || '"';
			END IF;
		END IF;
		IF pn_ds_uf_cidade_23 IS NULL
		AND pa_ds_uf_cidade_23 IS NULL THEN
			vr_ds_uf_cidade_23 := 'null';
		END IF;
		IF pn_ds_uf_cidade_23 IS NULL
		AND pa_ds_uf_cidade_23 IS NOT NULL THEN
			vr_ds_uf_cidade_23 := 'null';
		END IF;
		IF pn_ds_uf_cidade_23 IS NOT NULL
		AND pa_ds_uf_cidade_23 IS NULL THEN
			vr_ds_uf_cidade_23 := '"' || RTRIM(pn_ds_uf_cidade_23) || '"';
		END IF;
		IF pn_ds_uf_cidade_23 IS NOT NULL
		AND pa_ds_uf_cidade_23 IS NOT NULL THEN
			IF pa_ds_uf_cidade_23 <> pn_ds_uf_cidade_23 THEN
				vr_ds_uf_cidade_23 := '"' || RTRIM(pn_ds_uf_cidade_23) || '"';
			ELSE
				vr_ds_uf_cidade_23 := '"' || RTRIM(pa_ds_uf_cidade_23) || '"';
			END IF;
		END IF;
		IF pn_nu_telefone_t24 IS NULL
		AND pa_nu_telefone_t24 IS NULL THEN
			vr_nu_telefone_t24 := 'null';
		END IF;
		IF pn_nu_telefone_t24 IS NULL
		AND pa_nu_telefone_t24 IS NOT NULL THEN
			vr_nu_telefone_t24 := 'null';
		END IF;
		IF pn_nu_telefone_t24 IS NOT NULL
		AND pa_nu_telefone_t24 IS NULL THEN
			vr_nu_telefone_t24 := '"' || RTRIM(pn_nu_telefone_t24) || '"';
		END IF;
		IF pn_nu_telefone_t24 IS NOT NULL
		AND pa_nu_telefone_t24 IS NOT NULL THEN
			IF pa_nu_telefone_t24 <> pn_nu_telefone_t24 THEN
				vr_nu_telefone_t24 := '"' || RTRIM(pn_nu_telefone_t24) || '"';
			ELSE
				vr_nu_telefone_t24 := '"' || RTRIM(pa_nu_telefone_t24) || '"';
			END IF;
		END IF;
		IF pn_nu_ramal_trab25 IS NULL
		AND pa_nu_ramal_trab25 IS NULL THEN
			vr_nu_ramal_trab25 := 'null';
		END IF;
		IF pn_nu_ramal_trab25 IS NULL
		AND pa_nu_ramal_trab25 IS NOT NULL THEN
			vr_nu_ramal_trab25 := 'null';
		END IF;
		IF pn_nu_ramal_trab25 IS NOT NULL
		AND pa_nu_ramal_trab25 IS NULL THEN
			vr_nu_ramal_trab25 := '"' || RTRIM(pn_nu_ramal_trab25) || '"';
		END IF;
		IF pn_nu_ramal_trab25 IS NOT NULL
		AND pa_nu_ramal_trab25 IS NOT NULL THEN
			IF pa_nu_ramal_trab25 <> pn_nu_ramal_trab25 THEN
				vr_nu_ramal_trab25 := '"' || RTRIM(pn_nu_ramal_trab25) || '"';
			ELSE
				vr_nu_ramal_trab25 := '"' || RTRIM(pa_nu_ramal_trab25) || '"';
			END IF;
		END IF;
		IF pn_ds_e_mail26 IS NULL
		AND pa_ds_e_mail26 IS NULL THEN
			vr_ds_e_mail26 := 'null';
		END IF;
		IF pn_ds_e_mail26 IS NULL
		AND pa_ds_e_mail26 IS NOT NULL THEN
			vr_ds_e_mail26 := 'null';
		END IF;
		IF pn_ds_e_mail26 IS NOT NULL
		AND pa_ds_e_mail26 IS NULL THEN
			vr_ds_e_mail26 := '"' || RTRIM(pn_ds_e_mail26) || '"';
		END IF;
		IF pn_ds_e_mail26 IS NOT NULL
		AND pa_ds_e_mail26 IS NOT NULL THEN
			IF pa_ds_e_mail26 <> pn_ds_e_mail26 THEN
				vr_ds_e_mail26 := '"' || RTRIM(pn_ds_e_mail26) || '"';
			ELSE
				vr_ds_e_mail26 := '"' || RTRIM(pa_ds_e_mail26) || '"';
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
		IF pn_nu_rg28 IS NULL
		AND pa_nu_rg28 IS NULL THEN
			vr_nu_rg28 := 'null';
		END IF;
		IF pn_nu_rg28 IS NULL
		AND pa_nu_rg28 IS NOT NULL THEN
			vr_nu_rg28 := 'null';
		END IF;
		IF pn_nu_rg28 IS NOT NULL
		AND pa_nu_rg28 IS NULL THEN
			vr_nu_rg28 := '"' || RTRIM(pn_nu_rg28) || '"';
		END IF;
		IF pn_nu_rg28 IS NOT NULL
		AND pa_nu_rg28 IS NOT NULL THEN
			IF pa_nu_rg28 <> pn_nu_rg28 THEN
				vr_nu_rg28 := '"' || RTRIM(pn_nu_rg28) || '"';
			ELSE
				vr_nu_rg28 := '"' || RTRIM(pa_nu_rg28) || '"';
			END IF;
		END IF;
		IF pn_ds_orgao_emis29 IS NULL
		AND pa_ds_orgao_emis29 IS NULL THEN
			vr_ds_orgao_emis29 := 'null';
		END IF;
		IF pn_ds_orgao_emis29 IS NULL
		AND pa_ds_orgao_emis29 IS NOT NULL THEN
			vr_ds_orgao_emis29 := 'null';
		END IF;
		IF pn_ds_orgao_emis29 IS NOT NULL
		AND pa_ds_orgao_emis29 IS NULL THEN
			vr_ds_orgao_emis29 := '"' || RTRIM(pn_ds_orgao_emis29) || '"';
		END IF;
		IF pn_ds_orgao_emis29 IS NOT NULL
		AND pa_ds_orgao_emis29 IS NOT NULL THEN
			IF pa_ds_orgao_emis29 <> pn_ds_orgao_emis29 THEN
				vr_ds_orgao_emis29 := '"' || RTRIM(pn_ds_orgao_emis29) || '"';
			ELSE
				vr_ds_orgao_emis29 := '"' || RTRIM(pa_ds_orgao_emis29) || '"';
			END IF;
		END IF;
		IF pn_dt_emissao30 IS NULL
		AND pa_dt_emissao30 IS NULL THEN
			vr_dt_emissao30 := 'null';
		END IF;
		IF pn_dt_emissao30 IS NULL
		AND pa_dt_emissao30 IS NOT NULL THEN
			vr_dt_emissao30 := 'null';
		END IF;
		IF pn_dt_emissao30 IS NOT NULL
		AND pa_dt_emissao30 IS NULL THEN
			vr_dt_emissao30 := '"' || pn_dt_emissao30 || '"';
		END IF;
		IF pn_dt_emissao30 IS NOT NULL
		AND pa_dt_emissao30 IS NOT NULL THEN
			IF pa_dt_emissao30 <> pn_dt_emissao30 THEN
				vr_dt_emissao30 := '"' || pn_dt_emissao30 || '"';
			ELSE
				vr_dt_emissao30 := '"' || pa_dt_emissao30 || '"';
			END IF;
		END IF;
		IF pn_nu_cpf31 IS NULL
		AND pa_nu_cpf31 IS NULL THEN
			vr_nu_cpf31 := 'null';
		END IF;
		IF pn_nu_cpf31 IS NULL
		AND pa_nu_cpf31 IS NOT NULL THEN
			vr_nu_cpf31 := 'null';
		END IF;
		IF pn_nu_cpf31 IS NOT NULL
		AND pa_nu_cpf31 IS NULL THEN
			vr_nu_cpf31 := '"' || RTRIM(pn_nu_cpf31) || '"';
		END IF;
		IF pn_nu_cpf31 IS NOT NULL
		AND pa_nu_cpf31 IS NOT NULL THEN
			IF pa_nu_cpf31 <> pn_nu_cpf31 THEN
				vr_nu_cpf31 := '"' || RTRIM(pn_nu_cpf31) || '"';
			ELSE
				vr_nu_cpf31 := '"' || RTRIM(pa_nu_cpf31) || '"';
			END IF;
		END IF;
		IF pn_vl_renda_fami32 IS NULL
		AND pa_vl_renda_fami32 IS NULL THEN
			vr_vl_renda_fami32 := 'null';
		END IF;
		IF pn_vl_renda_fami32 IS NULL
		AND pa_vl_renda_fami32 IS NOT NULL THEN
			vr_vl_renda_fami32 := 'null';
		END IF;
		IF pn_vl_renda_fami32 IS NOT NULL
		AND pa_vl_renda_fami32 IS NULL THEN
			vr_vl_renda_fami32 := pn_vl_renda_fami32;
		END IF;
		IF pn_vl_renda_fami32 IS NOT NULL
		AND pa_vl_renda_fami32 IS NOT NULL THEN
			IF pa_vl_renda_fami32 <> pn_vl_renda_fami32 THEN
				vr_vl_renda_fami32 := pn_vl_renda_fami32;
			ELSE
				vr_vl_renda_fami32 := pa_vl_renda_fami32;
			END IF;
		END IF;
		IF pn_nu_dependente33 IS NULL
		AND pa_nu_dependente33 IS NULL THEN
			vr_nu_dependente33 := 'null';
		END IF;
		IF pn_nu_dependente33 IS NULL
		AND pa_nu_dependente33 IS NOT NULL THEN
			vr_nu_dependente33 := 'null';
		END IF;
		IF pn_nu_dependente33 IS NOT NULL
		AND pa_nu_dependente33 IS NULL THEN
			vr_nu_dependente33 := '"' || RTRIM(pn_nu_dependente33) || '"';
		END IF;
		IF pn_nu_dependente33 IS NOT NULL
		AND pa_nu_dependente33 IS NOT NULL THEN
			IF pa_nu_dependente33 <> pn_nu_dependente33 THEN
				vr_nu_dependente33 := '"' || RTRIM(pn_nu_dependente33) || '"';
			ELSE
				vr_nu_dependente33 := '"' || RTRIM(pa_nu_dependente33) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_responsavel set co_responsavel = ' || RTRIM(vr_co_responsave00) || '  , ds_responsavel = ' || RTRIM(vr_ds_responsave01) || '  , co_unidade = ' || RTRIM(vr_co_unidade02) || '  , tp_sexo = ' || RTRIM(vr_tp_sexo03) || '  , CO_TIPO_RESPONSAVEL = ' || RTRIM(vr_co_tip_respon04) || '  , co_seq_cidade = ' || RTRIM(vr_co_seq_cidade05);
		v_sql2 := '  , DS_RESPONSAVEL_ORDEM = ' || RTRIM(vr_ds_resp_ordem06) || '  , ds_naturalidade = ' || RTRIM(vr_ds_naturalida07) || '  , ds_uf_nascimento = ' || RTRIM(vr_ds_uf_nascime08) || '  , dt_nascimento = ' || RTRIM(vr_dt_nascimento09) || '  , ds_endereco = ' || RTRIM(vr_ds_endereco10);
		v_sql3 := '  , ds_bairro = ' || RTRIM(vr_ds_bairro11) || '  , nu_cep = ' || RTRIM(vr_nu_cep12) || '  , ds_cidade = ' || RTRIM(vr_ds_cidade13) || '  , ds_uf_cidade = ' || RTRIM(vr_ds_uf_cidade14) || '  , nu_telefone = ' || RTRIM(vr_nu_telefone15) || '  , nu_celular = ' || RTRIM(vr_nu_celular16);
		v_sql4 := '  , ds_profissao = ' || RTRIM(vr_ds_profissao17) || '  , ds_local_trab = ' || RTRIM(vr_ds_local_trab18) || '  , ds_endereco_trab = ' || RTRIM(vr_ds_endereco_t19) || '  , ds_bairro_trab = ' || RTRIM(vr_ds_bairro_tra20) || '  , nu_cep_trab = ' || RTRIM(vr_nu_cep_trab21);
		v_sql5 := '  , ds_cidade_trab = ' || RTRIM(vr_ds_cidade_tra22) || '  , ds_uf_cidade_trab = ' || RTRIM(vr_ds_uf_cidade_23) || '  , nu_telefone_trab = ' || RTRIM(vr_nu_telefone_t24) || '  , nu_ramal_trab = ' || RTRIM(vr_nu_ramal_trab25) || '  , ds_e_mail = ' || RTRIM(vr_ds_e_mail26);
		v_sql6 := '  , ds_instrucao = ' || RTRIM(vr_ds_instrucao27) || '  , nu_rg = ' || RTRIM(vr_nu_rg28) || '  , ds_orgao_emissor = ' || RTRIM(vr_ds_orgao_emis29) || '  , dt_emissao = ' || RTRIM(vr_dt_emissao30) || '  , nu_cpf = ' || RTRIM(vr_nu_cpf31) || '  , vl_renda_familiar = ' || RTRIM(vr_vl_renda_fami32);
		v_sql7 := '  , nu_dependentes = ' || RTRIM(vr_nu_dependente33);
		v_sql8 := ' where co_responsavel = ' || RTRIM(vr_co_responsave00) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5 || v_sql6 || v_sql7 || v_sql8;
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
		       's_responsavel',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_responsa142;
/

