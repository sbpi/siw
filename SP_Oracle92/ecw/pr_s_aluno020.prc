CREATE OR REPLACE PROCEDURE pr_s_aluno020(
	P_OP_IN                CHAR,
	PA_co_aluno00_IN       s_aluno.co_aluno%TYPE,
	PA_ds_aluno01_IN       s_aluno.ds_aluno%TYPE,
	PA_co_seq_cidade02_IN  s_aluno.co_seq_cidade%TYPE,
	PA_dt_nascimento03_IN  s_aluno.dt_nascimento%TYPE,
	PA_ds_aluno_orde04_IN  s_aluno.ds_aluno_ordem%TYPE,
	PA_tp_sexo_aluno05_IN  s_aluno.tp_sexo_aluno%TYPE,
	PA_ds_naturalida06_IN  s_aluno.ds_naturalidade%TYPE,
	PA_ds_uf_nascime07_IN  s_aluno.ds_uf_nascimento%TYPE,
	PA_ds_nacionalid08_IN  s_aluno.ds_nacionalidade%TYPE,
	PA_ds_endereco09_IN    s_aluno.ds_endereco%TYPE,
	PA_ds_bairro10_IN      s_aluno.ds_bairro%TYPE,
	PA_nu_cep11_IN         s_aluno.nu_cep%TYPE,
	PA_ds_cidade12_IN      s_aluno.ds_cidade%TYPE,
	PA_ds_uf_cidade13_IN   s_aluno.ds_uf_cidade%TYPE,
	PA_ds_e_mail14_IN      s_aluno.ds_e_mail%TYPE,
	PA_tp_estado_civ15_IN  s_aluno.tp_estado_civil%TYPE,
	PA_ds_conjuge16_IN     s_aluno.ds_conjuge%TYPE,
	PA_nu_rg17_IN          s_aluno.nu_rg%TYPE,
	PA_ds_orgao_emis18_IN  s_aluno.ds_orgao_emissor%TYPE,
	PA_dt_emissao19_IN     s_aluno.dt_emissao%TYPE,
	PA_nu_cpf20_IN         s_aluno.nu_cpf%TYPE,
	PA_ds_ficha_medi21_IN  s_aluno.ds_ficha_medica%TYPE,
	PA_tp_escola_ori22_IN  s_aluno.tp_escola_origem%TYPE,
	PA_dt_ingresso23_IN    s_aluno.dt_ingresso%TYPE,
	PA_nu_tempo_esco24_IN  s_aluno.nu_tempo_escolar%TYPE,
	PA_ds_certidao25_IN    s_aluno.ds_certidao%TYPE,
	PA_nu_certidao26_IN    s_aluno.nu_certidao%TYPE,
	PA_nu_livro27_IN       s_aluno.nu_livro%TYPE,
	PA_nu_folha28_IN       s_aluno.nu_folha%TYPE,
	PA_ds_cartorio29_IN    s_aluno.ds_cartorio%TYPE,
	PA_ds_cidade_cer30_IN  s_aluno.ds_cidade_certidao%TYPE,
	PA_ds_foto31_IN        s_aluno.ds_foto%TYPE,
	PA_ds_uf_certida32_IN  s_aluno.ds_uf_certidao%TYPE,
	PA_nu_reservista33_IN  s_aluno.nu_reservista%TYPE,
	PA_nu_titulo_ele34_IN  s_aluno.nu_titulo_eleitor%TYPE,
	PA_ds_zona35_IN        s_aluno.ds_zona%TYPE,
	PA_ds_secao36_IN       s_aluno.ds_secao%TYPE,
	PA_ds_uf_secao37_IN    s_aluno.ds_uf_secao%TYPE,
	PA_ds_pai38_IN         s_aluno.ds_pai%TYPE,
	PA_ds_mae39_IN         s_aluno.ds_mae%TYPE,
	PA_co_origem_esc40_IN  s_aluno.co_origem_escola%TYPE,
	PA_ds_web41_IN         s_aluno.ds_web%TYPE,
	PA_id_ativo_pass42_IN  s_aluno.id_ativo_passivo%TYPE,
	PA_co_unidade43_IN     s_aluno.co_unidade%TYPE,
	PA_co_aluno_anti44_IN  s_aluno.co_aluno_antigo%TYPE,
	PA_ds_categoria45_IN   s_aluno.ds_categoria%TYPE,
	PA_tp_anee46_IN        s_aluno.tp_anee%TYPE,
	PN_co_aluno00_IN       s_aluno.co_aluno%TYPE,
	PN_ds_aluno01_IN       s_aluno.ds_aluno%TYPE,
	PN_co_seq_cidade02_IN  s_aluno.co_seq_cidade%TYPE,
	PN_dt_nascimento03_IN  s_aluno.dt_nascimento%TYPE,
	PN_ds_aluno_orde04_IN  s_aluno.ds_aluno_ordem%TYPE,
	PN_tp_sexo_aluno05_IN  s_aluno.tp_sexo_aluno%TYPE,
	PN_ds_naturalida06_IN  s_aluno.ds_naturalidade%TYPE,
	PN_ds_uf_nascime07_IN  s_aluno.ds_uf_nascimento%TYPE,
	PN_ds_nacionalid08_IN  s_aluno.ds_nacionalidade%TYPE,
	PN_ds_endereco09_IN    s_aluno.ds_endereco%TYPE,
	PN_ds_bairro10_IN      s_aluno.ds_bairro%TYPE,
	PN_nu_cep11_IN         s_aluno.nu_cep%TYPE,
	PN_ds_cidade12_IN      s_aluno.ds_cidade%TYPE,
	PN_ds_uf_cidade13_IN   s_aluno.ds_uf_cidade%TYPE,
	PN_ds_e_mail14_IN      s_aluno.ds_e_mail%TYPE,
	PN_tp_estado_civ15_IN  s_aluno.tp_estado_civil%TYPE,
	PN_ds_conjuge16_IN     s_aluno.ds_conjuge%TYPE,
	PN_nu_rg17_IN          s_aluno.nu_rg%TYPE,
	PN_ds_orgao_emis18_IN  s_aluno.ds_orgao_emissor%TYPE,
	PN_dt_emissao19_IN     s_aluno.dt_emissao%TYPE,
	PN_nu_cpf20_IN         s_aluno.nu_cpf%TYPE,
	PN_ds_ficha_medi21_IN  s_aluno.ds_ficha_medica%TYPE,
	PN_tp_escola_ori22_IN  s_aluno.tp_escola_origem%TYPE,
	PN_dt_ingresso23_IN    s_aluno.dt_ingresso%TYPE,
	PN_nu_tempo_esco24_IN  s_aluno.nu_tempo_escolar%TYPE,
	PN_ds_certidao25_IN    s_aluno.ds_certidao%TYPE,
	PN_nu_certidao26_IN    s_aluno.nu_certidao%TYPE,
	PN_nu_livro27_IN       s_aluno.nu_livro%TYPE,
	PN_nu_folha28_IN       s_aluno.nu_folha%TYPE,
	PN_ds_cartorio29_IN    s_aluno.ds_cartorio%TYPE,
	PN_ds_cidade_cer30_IN  s_aluno.ds_cidade_certidao%TYPE,
	PN_ds_foto31_IN        s_aluno.ds_foto%TYPE,
	PN_ds_uf_certida32_IN  s_aluno.ds_uf_certidao%TYPE,
	PN_nu_reservista33_IN  s_aluno.nu_reservista%TYPE,
	PN_nu_titulo_ele34_IN  s_aluno.nu_titulo_eleitor%TYPE,
	PN_ds_zona35_IN        s_aluno.ds_zona%TYPE,
	PN_ds_secao36_IN       s_aluno.ds_secao%TYPE,
	PN_ds_uf_secao37_IN    s_aluno.ds_uf_secao%TYPE,
	PN_ds_pai38_IN         s_aluno.ds_pai%TYPE,
	PN_ds_mae39_IN         s_aluno.ds_mae%TYPE,
	PN_co_origem_esc40_IN  s_aluno.co_origem_escola%TYPE,
	PN_ds_web41_IN         s_aluno.ds_web%TYPE,
	PN_id_ativo_pass42_IN  s_aluno.id_ativo_passivo%TYPE,
	PN_co_unidade43_IN     s_aluno.co_unidade%TYPE,
	PN_co_aluno_anti44_IN  s_aluno.co_aluno_antigo%TYPE,
	PN_ds_categoria45_IN   s_aluno.ds_categoria%TYPE,
	PN_tp_anee46_IN        s_aluno.tp_anee%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_aluno00       s_aluno.co_aluno%TYPE := PA_co_aluno00_IN;
PA_ds_aluno01       s_aluno.ds_aluno%TYPE := PA_ds_aluno01_IN;
PA_co_seq_cidade02  s_aluno.co_seq_cidade%TYPE := PA_co_seq_cidade02_IN;
PA_dt_nascimento03  s_aluno.dt_nascimento%TYPE := PA_dt_nascimento03_IN;
PA_ds_aluno_orde04  s_aluno.ds_aluno_ordem%TYPE := PA_ds_aluno_orde04_IN;
PA_tp_sexo_aluno05  s_aluno.tp_sexo_aluno%TYPE := PA_tp_sexo_aluno05_IN;
PA_ds_naturalida06  s_aluno.ds_naturalidade%TYPE := PA_ds_naturalida06_IN;
PA_ds_uf_nascime07  s_aluno.ds_uf_nascimento%TYPE := PA_ds_uf_nascime07_IN;
PA_ds_nacionalid08  s_aluno.ds_nacionalidade%TYPE := PA_ds_nacionalid08_IN;
PA_ds_endereco09    s_aluno.ds_endereco%TYPE := PA_ds_endereco09_IN;
PA_ds_bairro10      s_aluno.ds_bairro%TYPE := PA_ds_bairro10_IN;
PA_nu_cep11         s_aluno.nu_cep%TYPE := PA_nu_cep11_IN;
PA_ds_cidade12      s_aluno.ds_cidade%TYPE := PA_ds_cidade12_IN;
PA_ds_uf_cidade13   s_aluno.ds_uf_cidade%TYPE := PA_ds_uf_cidade13_IN;
PA_ds_e_mail14      s_aluno.ds_e_mail%TYPE := PA_ds_e_mail14_IN;
PA_tp_estado_civ15  s_aluno.tp_estado_civil%TYPE := PA_tp_estado_civ15_IN;
PA_ds_conjuge16     s_aluno.ds_conjuge%TYPE := PA_ds_conjuge16_IN;
PA_nu_rg17          s_aluno.nu_rg%TYPE := PA_nu_rg17_IN;
PA_ds_orgao_emis18  s_aluno.ds_orgao_emissor%TYPE := PA_ds_orgao_emis18_IN;
PA_dt_emissao19     s_aluno.dt_emissao%TYPE := PA_dt_emissao19_IN;
PA_nu_cpf20         s_aluno.nu_cpf%TYPE := PA_nu_cpf20_IN;
PA_ds_ficha_medi21  s_aluno.ds_ficha_medica%TYPE := PA_ds_ficha_medi21_IN;
PA_tp_escola_ori22  s_aluno.tp_escola_origem%TYPE := PA_tp_escola_ori22_IN;
PA_dt_ingresso23    s_aluno.dt_ingresso%TYPE := PA_dt_ingresso23_IN;
PA_nu_tempo_esco24  s_aluno.nu_tempo_escolar%TYPE := PA_nu_tempo_esco24_IN;
PA_ds_certidao25    s_aluno.ds_certidao%TYPE := PA_ds_certidao25_IN;
PA_nu_certidao26    s_aluno.nu_certidao%TYPE := PA_nu_certidao26_IN;
PA_nu_livro27       s_aluno.nu_livro%TYPE := PA_nu_livro27_IN;
PA_nu_folha28       s_aluno.nu_folha%TYPE := PA_nu_folha28_IN;
PA_ds_cartorio29    s_aluno.ds_cartorio%TYPE := PA_ds_cartorio29_IN;
PA_ds_cidade_cer30  s_aluno.ds_cidade_certidao%TYPE := PA_ds_cidade_cer30_IN;
PA_ds_foto31        s_aluno.ds_foto%TYPE := PA_ds_foto31_IN;
PA_ds_uf_certida32  s_aluno.ds_uf_certidao%TYPE := PA_ds_uf_certida32_IN;
PA_nu_reservista33  s_aluno.nu_reservista%TYPE := PA_nu_reservista33_IN;
PA_nu_titulo_ele34  s_aluno.nu_titulo_eleitor%TYPE := PA_nu_titulo_ele34_IN;
PA_ds_zona35        s_aluno.ds_zona%TYPE := PA_ds_zona35_IN;
PA_ds_secao36       s_aluno.ds_secao%TYPE := PA_ds_secao36_IN;
PA_ds_uf_secao37    s_aluno.ds_uf_secao%TYPE := PA_ds_uf_secao37_IN;
PA_ds_pai38         s_aluno.ds_pai%TYPE := PA_ds_pai38_IN;
PA_ds_mae39         s_aluno.ds_mae%TYPE := PA_ds_mae39_IN;
PA_co_origem_esc40  s_aluno.co_origem_escola%TYPE := PA_co_origem_esc40_IN;
PA_ds_web41         s_aluno.ds_web%TYPE := PA_ds_web41_IN;
PA_id_ativo_pass42  s_aluno.id_ativo_passivo%TYPE := PA_id_ativo_pass42_IN;
PA_co_unidade43     s_aluno.co_unidade%TYPE := PA_co_unidade43_IN;
PA_co_aluno_anti44  s_aluno.co_aluno_antigo%TYPE := PA_co_aluno_anti44_IN;
PA_ds_categoria45   s_aluno.ds_categoria%TYPE := PA_ds_categoria45_IN;
PA_tp_anee46        s_aluno.tp_anee%TYPE := PA_tp_anee46_IN;
PN_co_aluno00       s_aluno.co_aluno%TYPE := PN_co_aluno00_IN;
PN_ds_aluno01       s_aluno.ds_aluno%TYPE := PN_ds_aluno01_IN;
PN_co_seq_cidade02  s_aluno.co_seq_cidade%TYPE := PN_co_seq_cidade02_IN;
PN_dt_nascimento03  s_aluno.dt_nascimento%TYPE := PN_dt_nascimento03_IN;
PN_ds_aluno_orde04  s_aluno.ds_aluno_ordem%TYPE := PN_ds_aluno_orde04_IN;
PN_tp_sexo_aluno05  s_aluno.tp_sexo_aluno%TYPE := PN_tp_sexo_aluno05_IN;
PN_ds_naturalida06  s_aluno.ds_naturalidade%TYPE := PN_ds_naturalida06_IN;
PN_ds_uf_nascime07  s_aluno.ds_uf_nascimento%TYPE := PN_ds_uf_nascime07_IN;
PN_ds_nacionalid08  s_aluno.ds_nacionalidade%TYPE := PN_ds_nacionalid08_IN;
PN_ds_endereco09    s_aluno.ds_endereco%TYPE := PN_ds_endereco09_IN;
PN_ds_bairro10      s_aluno.ds_bairro%TYPE := PN_ds_bairro10_IN;
PN_nu_cep11         s_aluno.nu_cep%TYPE := PN_nu_cep11_IN;
PN_ds_cidade12      s_aluno.ds_cidade%TYPE := PN_ds_cidade12_IN;
PN_ds_uf_cidade13   s_aluno.ds_uf_cidade%TYPE := PN_ds_uf_cidade13_IN;
PN_ds_e_mail14      s_aluno.ds_e_mail%TYPE := PN_ds_e_mail14_IN;
PN_tp_estado_civ15  s_aluno.tp_estado_civil%TYPE := PN_tp_estado_civ15_IN;
PN_ds_conjuge16     s_aluno.ds_conjuge%TYPE := PN_ds_conjuge16_IN;
PN_nu_rg17          s_aluno.nu_rg%TYPE := PN_nu_rg17_IN;
PN_ds_orgao_emis18  s_aluno.ds_orgao_emissor%TYPE := PN_ds_orgao_emis18_IN;
PN_dt_emissao19     s_aluno.dt_emissao%TYPE := PN_dt_emissao19_IN;
PN_nu_cpf20         s_aluno.nu_cpf%TYPE := PN_nu_cpf20_IN;
PN_ds_ficha_medi21  s_aluno.ds_ficha_medica%TYPE := PN_ds_ficha_medi21_IN;
PN_tp_escola_ori22  s_aluno.tp_escola_origem%TYPE := PN_tp_escola_ori22_IN;
PN_dt_ingresso23    s_aluno.dt_ingresso%TYPE := PN_dt_ingresso23_IN;
PN_nu_tempo_esco24  s_aluno.nu_tempo_escolar%TYPE := PN_nu_tempo_esco24_IN;
PN_ds_certidao25    s_aluno.ds_certidao%TYPE := PN_ds_certidao25_IN;
PN_nu_certidao26    s_aluno.nu_certidao%TYPE := PN_nu_certidao26_IN;
PN_nu_livro27       s_aluno.nu_livro%TYPE := PN_nu_livro27_IN;
PN_nu_folha28       s_aluno.nu_folha%TYPE := PN_nu_folha28_IN;
PN_ds_cartorio29    s_aluno.ds_cartorio%TYPE := PN_ds_cartorio29_IN;
PN_ds_cidade_cer30  s_aluno.ds_cidade_certidao%TYPE := PN_ds_cidade_cer30_IN;
PN_ds_foto31        s_aluno.ds_foto%TYPE := PN_ds_foto31_IN;
PN_ds_uf_certida32  s_aluno.ds_uf_certidao%TYPE := PN_ds_uf_certida32_IN;
PN_nu_reservista33  s_aluno.nu_reservista%TYPE := PN_nu_reservista33_IN;
PN_nu_titulo_ele34  s_aluno.nu_titulo_eleitor%TYPE := PN_nu_titulo_ele34_IN;
PN_ds_zona35        s_aluno.ds_zona%TYPE := PN_ds_zona35_IN;
PN_ds_secao36       s_aluno.ds_secao%TYPE := PN_ds_secao36_IN;
PN_ds_uf_secao37    s_aluno.ds_uf_secao%TYPE := PN_ds_uf_secao37_IN;
PN_ds_pai38         s_aluno.ds_pai%TYPE := PN_ds_pai38_IN;
PN_ds_mae39         s_aluno.ds_mae%TYPE := PN_ds_mae39_IN;
PN_co_origem_esc40  s_aluno.co_origem_escola%TYPE := PN_co_origem_esc40_IN;
PN_ds_web41         s_aluno.ds_web%TYPE := PN_ds_web41_IN;
PN_id_ativo_pass42  s_aluno.id_ativo_passivo%TYPE := PN_id_ativo_pass42_IN;
PN_co_unidade43     s_aluno.co_unidade%TYPE := PN_co_unidade43_IN;
PN_co_aluno_anti44  s_aluno.co_aluno_antigo%TYPE := PN_co_aluno_anti44_IN;
PN_ds_categoria45   s_aluno.ds_categoria%TYPE := PN_ds_categoria45_IN;
PN_tp_anee46        s_aluno.tp_anee%TYPE := PN_tp_anee46_IN;
v_blob1             s_aluno.ds_ficha_medica%TYPE;
v_blob2             s_aluno.ds_foto%TYPE;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(550);
v_sql2              CHAR(350);
v_sql3              CHAR(350);
v_sql4              CHAR(350);
v_sql5              CHAR(350);
v_sql6              CHAR(350);
v_sql7              CHAR(350);
v_sql8              CHAR(350);
v_sql9              CHAR(350);
v_sql10             CHAR(350);
v_sql11             CHAR(350);
v_sql12             CHAR(350);
v_sql13             CHAR(350);
v_sql14             CHAR(350);
v_sql15             CHAR(350);
v_sql16             CHAR(350);
v_sql17             CHAR(350);
v_sql18             CHAR(350);
v_sql19             CHAR(350);
v_uni               CHAR(10);
vr_co_aluno00       CHAR(20);
vr_ds_aluno01       CHAR(50);
vr_co_seq_cidade02  CHAR(10);
vr_dt_nascimento03  CHAR(40);
vr_ds_aluno_orde04  CHAR(50);
vr_tp_sexo_aluno05  CHAR(10);
vr_ds_naturalida06  CHAR(40);
vr_ds_uf_nascime07  CHAR(10);
vr_ds_nacionalid08  CHAR(30);
vr_ds_endereco09    CHAR(50);
vr_ds_bairro10      CHAR(30);
vr_nu_cep11         CHAR(20);
vr_ds_cidade12      CHAR(30);
vr_ds_uf_cidade13   CHAR(10);
vr_ds_e_mail14      CHAR(110);
vr_tp_estado_civ15  CHAR(20);
vr_ds_conjuge16     CHAR(50);
vr_nu_rg17          CHAR(20);
vr_ds_orgao_emis18  CHAR(40);
vr_dt_emissao19     CHAR(40);
vr_nu_cpf20         CHAR(20);
vr_ds_ficha_medi21  CHAR(10);
vr_tp_escola_ori22  CHAR(20);
vr_dt_ingresso23    CHAR(40);
vr_nu_tempo_esco24  CHAR(10);
vr_ds_certidao25    CHAR(20);
vr_nu_certidao26    CHAR(20);
vr_nu_livro27       CHAR(10);
vr_nu_folha28       CHAR(100);
vr_ds_cartorio29    CHAR(100);
vr_ds_cidade_cer30  CHAR(100);
vr_ds_foto31        CHAR(10);
vr_ds_uf_certida32  CHAR(10);
vr_nu_reservista33  CHAR(20);
vr_nu_titulo_ele34  CHAR(20);
vr_ds_zona35        CHAR(10);
vr_ds_secao36       CHAR(10);
vr_ds_uf_secao37    CHAR(10);
vr_ds_pai38         CHAR(50);
vr_ds_mae39         CHAR(50);
vr_co_origem_esc40  CHAR(10);
vr_ds_web41         CHAR(160);
vr_id_ativo_pass42  CHAR(10);
vr_co_unidade43     CHAR(10);
vr_co_aluno_anti44  CHAR(20);
vr_ds_categoria45   CHAR(10);
vr_tp_anee46        CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	v_blob1 := NULL;
	v_blob2 := NULL;
	IF p_op = 'ins' THEN
		IF pn_co_aluno00 IS NULL THEN
			vr_co_aluno00 := 'null';
		ELSE
			vr_co_aluno00 := pn_co_aluno00;
		END IF;
		IF pn_ds_aluno01 IS NULL THEN
			vr_ds_aluno01 := 'null';
		ELSE
			vr_ds_aluno01 := pn_ds_aluno01;
		END IF;
		IF pn_co_seq_cidade02 IS NULL THEN
			vr_co_seq_cidade02 := 'null';
		ELSE
			vr_co_seq_cidade02 := pn_co_seq_cidade02;
		END IF;
		IF pn_dt_nascimento03 IS NULL THEN
			vr_dt_nascimento03 := 'null';
		ELSE
			vr_dt_nascimento03 := pn_dt_nascimento03;
		END IF;
		IF pn_ds_aluno_orde04 IS NULL THEN
			vr_ds_aluno_orde04 := 'null';
		ELSE
			vr_ds_aluno_orde04 := pn_ds_aluno_orde04;
		END IF;
		IF pn_tp_sexo_aluno05 IS NULL THEN
			vr_tp_sexo_aluno05 := 'null';
		ELSE
			vr_tp_sexo_aluno05 := pn_tp_sexo_aluno05;
		END IF;
		IF pn_ds_naturalida06 IS NULL THEN
			vr_ds_naturalida06 := 'null';
		ELSE
			vr_ds_naturalida06 := pn_ds_naturalida06;
		END IF;
		IF pn_ds_uf_nascime07 IS NULL THEN
			vr_ds_uf_nascime07 := 'null';
		ELSE
			vr_ds_uf_nascime07 := pn_ds_uf_nascime07;
		END IF;
		IF pn_ds_nacionalid08 IS NULL THEN
			vr_ds_nacionalid08 := 'null';
		ELSE
			vr_ds_nacionalid08 := pn_ds_nacionalid08;
		END IF;
		IF pn_ds_endereco09 IS NULL THEN
			vr_ds_endereco09 := 'null';
		ELSE
			vr_ds_endereco09 := pn_ds_endereco09;
		END IF;
		IF pn_ds_bairro10 IS NULL THEN
			vr_ds_bairro10 := 'null';
		ELSE
			vr_ds_bairro10 := pn_ds_bairro10;
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
		IF pn_ds_e_mail14 IS NULL THEN
			vr_ds_e_mail14 := 'null';
		ELSE
			vr_ds_e_mail14 := pn_ds_e_mail14;
		END IF;
		IF pn_tp_estado_civ15 IS NULL THEN
			vr_tp_estado_civ15 := 'null';
		ELSE
			vr_tp_estado_civ15 := pn_tp_estado_civ15;
		END IF;
		IF pn_ds_conjuge16 IS NULL THEN
			vr_ds_conjuge16 := 'null';
		ELSE
			vr_ds_conjuge16 := pn_ds_conjuge16;
		END IF;
		IF pn_nu_rg17 IS NULL THEN
			vr_nu_rg17 := 'null';
		ELSE
			vr_nu_rg17 := pn_nu_rg17;
		END IF;
		IF pn_ds_orgao_emis18 IS NULL THEN
			vr_ds_orgao_emis18 := 'null';
		ELSE
			vr_ds_orgao_emis18 := pn_ds_orgao_emis18;
		END IF;
		IF pn_dt_emissao19 IS NULL THEN
			vr_dt_emissao19 := 'null';
		ELSE
			vr_dt_emissao19 := pn_dt_emissao19;
		END IF;
		IF pn_nu_cpf20 IS NULL THEN
			vr_nu_cpf20 := 'null';
		ELSE
			vr_nu_cpf20 := pn_nu_cpf20;
		END IF;
		IF pn_ds_ficha_medi21 IS NULL THEN
			vr_ds_ficha_medi21 := NULL;
		ELSE
			vr_ds_ficha_medi21 := ':vblob1';
		END IF;
		v_blob1 := pn_ds_ficha_medi21;
		IF pn_tp_escola_ori22 IS NULL THEN
			vr_tp_escola_ori22 := 'null';
		ELSE
			vr_tp_escola_ori22 := pn_tp_escola_ori22;
		END IF;
		IF pn_dt_ingresso23 IS NULL THEN
			vr_dt_ingresso23 := 'null';
		ELSE
			vr_dt_ingresso23 := pn_dt_ingresso23;
		END IF;
		IF pn_nu_tempo_esco24 IS NULL THEN
			vr_nu_tempo_esco24 := 'null';
		ELSE
			vr_nu_tempo_esco24 := pn_nu_tempo_esco24;
		END IF;
		IF pn_ds_certidao25 IS NULL THEN
			vr_ds_certidao25 := 'null';
		ELSE
			vr_ds_certidao25 := pn_ds_certidao25;
		END IF;
		IF pn_nu_certidao26 IS NULL THEN
			vr_nu_certidao26 := 'null';
		ELSE
			vr_nu_certidao26 := pn_nu_certidao26;
		END IF;
		IF pn_nu_livro27 IS NULL THEN
			vr_nu_livro27 := 'null';
		ELSE
			vr_nu_livro27 := pn_nu_livro27;
		END IF;
		IF pn_nu_folha28 IS NULL THEN
			vr_nu_folha28 := 'null';
		ELSE
			vr_nu_folha28 := pn_nu_folha28;
		END IF;
		IF pn_ds_cartorio29 IS NULL THEN
			vr_ds_cartorio29 := 'null';
		ELSE
			vr_ds_cartorio29 := pn_ds_cartorio29;
		END IF;
		IF pn_ds_cidade_cer30 IS NULL THEN
			vr_ds_cidade_cer30 := 'null';
		ELSE
			vr_ds_cidade_cer30 := pn_ds_cidade_cer30;
		END IF;
		IF pn_ds_foto31 IS NULL THEN
			vr_ds_foto31 := NULL;
		ELSE
			vr_ds_foto31 := ':vblob2';
		END IF;
		v_blob2 := pn_ds_foto31;
		IF pn_ds_uf_certida32 IS NULL THEN
			vr_ds_uf_certida32 := 'null';
		ELSE
			vr_ds_uf_certida32 := pn_ds_uf_certida32;
		END IF;
		IF pn_nu_reservista33 IS NULL THEN
			vr_nu_reservista33 := 'null';
		ELSE
			vr_nu_reservista33 := pn_nu_reservista33;
		END IF;
		IF pn_nu_titulo_ele34 IS NULL THEN
			vr_nu_titulo_ele34 := 'null';
		ELSE
			vr_nu_titulo_ele34 := pn_nu_titulo_ele34;
		END IF;
		IF pn_ds_zona35 IS NULL THEN
			vr_ds_zona35 := 'null';
		ELSE
			vr_ds_zona35 := pn_ds_zona35;
		END IF;
		IF pn_ds_secao36 IS NULL THEN
			vr_ds_secao36 := 'null';
		ELSE
			vr_ds_secao36 := pn_ds_secao36;
		END IF;
		IF pn_ds_uf_secao37 IS NULL THEN
			vr_ds_uf_secao37 := 'null';
		ELSE
			vr_ds_uf_secao37 := pn_ds_uf_secao37;
		END IF;
		IF pn_ds_pai38 IS NULL THEN
			vr_ds_pai38 := 'null';
		ELSE
			vr_ds_pai38 := pn_ds_pai38;
		END IF;
		IF pn_ds_mae39 IS NULL THEN
			vr_ds_mae39 := 'null';
		ELSE
			vr_ds_mae39 := pn_ds_mae39;
		END IF;
		IF pn_co_origem_esc40 IS NULL THEN
			vr_co_origem_esc40 := 'null';
		ELSE
			vr_co_origem_esc40 := pn_co_origem_esc40;
		END IF;
		IF pn_ds_web41 IS NULL THEN
			vr_ds_web41 := 'null';
		ELSE
			vr_ds_web41 := pn_ds_web41;
		END IF;
		IF pn_id_ativo_pass42 IS NULL THEN
			vr_id_ativo_pass42 := 'null';
		ELSE
			vr_id_ativo_pass42 := pn_id_ativo_pass42;
		END IF;
		IF pn_co_unidade43 IS NULL THEN
			vr_co_unidade43 := 'null';
		ELSE
			vr_co_unidade43 := pn_co_unidade43;
		END IF;
		IF pn_co_aluno_anti44 IS NULL THEN
			vr_co_aluno_anti44 := 'null';
		ELSE
			vr_co_aluno_anti44 := pn_co_aluno_anti44;
		END IF;
		IF pn_ds_categoria45 IS NULL THEN
			vr_ds_categoria45 := 'null';
		ELSE
			vr_ds_categoria45 := pn_ds_categoria45;
		END IF;
		IF pn_tp_anee46 IS NULL THEN
			vr_tp_anee46 := 'null';
		ELSE
			vr_tp_anee46 := pn_tp_anee46;
		END IF;
		v_sql1 := 'insert into s_aluno(co_aluno, ds_aluno, co_seq_cidade, dt_nascimento, ds_aluno_ordem, tp_sexo_aluno, ds_naturalidade, ds_uf_nascimento, ds_nacionalidade, ds_endereco, ds_bairro, nu_cep, ds_cidade, ' || 'ds_uf_cidade, ds_e_mail, tp_estado_civil, ds_conjuge, nu_rg, ds_orgao_emissor, dt_emissao, nu_cpf, ds_ficha_medica, tp_escola_origem, dt_ingresso, NU_TEMPO_ESCOLARIDADE, ds_certidao, ' || 'nu_certidao, nu_livro, nu_folha, ds_cartorio, ds_cidade_certidao, ds_foto, ds_uf_certidao, nu_reservista, nu_titulo_eleitor, ds_zona, ds_secao, ds_uf_secao, ds_pai, ds_mae, ' || 'co_origem_escola, ds_web, id_ativo_passivo, co_unidade, co_aluno_antigo, ds_categoria, tp_anee) values (';
		v_sql2 := '"' || RTRIM(vr_co_aluno00) || '"' || ',' || '"' || RTRIM(vr_ds_aluno01) || '"' || ',' || RTRIM(vr_co_seq_cidade02) || ',' || '"' || vr_dt_nascimento03 || '"' || ',' || '"' || RTRIM(vr_ds_aluno_orde04) || '"' || ',';
		v_sql3 := '"' || RTRIM(vr_tp_sexo_aluno05) || '"' || ',' || '"' || RTRIM(vr_ds_naturalida06) || '"' || ',' || '"' || RTRIM(vr_ds_uf_nascime07) || '"' || ',' || '"' || RTRIM(vr_ds_nacionalid08) || '"' || ',' || '"' || RTRIM(vr_ds_endereco09) || '"' || ',' || '"' || RTRIM(vr_ds_bairro10) || '"' || ',';
		v_sql4 := '"' || RTRIM(vr_nu_cep11) || '"' || ',' || '"' || RTRIM(vr_ds_cidade12) || '"' || ',' || '"' || RTRIM(vr_ds_uf_cidade13) || '"' || ',' || '"' || RTRIM(vr_ds_e_mail14) || '"' || ',' || '"' || RTRIM(vr_tp_estado_civ15) || '"' || ',';
		v_sql5 := '"' || RTRIM(vr_ds_conjuge16) || '"' || ',' || '"' || RTRIM(vr_nu_rg17) || '"' || ',' || '"' || RTRIM(vr_ds_orgao_emis18) || '"' || ',' || '"' || vr_dt_emissao19 || '"' || ',' || '"' || RTRIM(vr_nu_cpf20) || '"' || ',' || RTRIM(vr_ds_ficha_medi21) || ',' || '"' || RTRIM(vr_tp_escola_ori22) || '"' || ',';
		v_sql6 := '"' || vr_dt_ingresso23 || '"' || ',' || '"' || RTRIM(vr_nu_tempo_esco24) || '"' || ',' || '"' || RTRIM(vr_ds_certidao25) || '"' || ',';
		v_sql7 := '"' || RTRIM(vr_nu_certidao26) || '"' || ',' || '"' || RTRIM(vr_nu_livro27) || '"' || ',' || '"' || RTRIM(vr_nu_folha28) || '"' || ',' || '"' || RTRIM(vr_ds_cartorio29) || '"' || ',';
		v_sql8 := '"' || RTRIM(vr_ds_cidade_cer30) || '"' || ',' || RTRIM(vr_ds_foto31) || ',' || '"' || RTRIM(vr_ds_uf_certida32) || '"' || ',' || '"' || RTRIM(vr_nu_reservista33) || '"' || ',' || '"' || RTRIM(vr_nu_titulo_ele34) || '"' || ',';
		v_sql9 := '"' || RTRIM(vr_ds_zona35) || '"' || ',' || '"' || RTRIM(vr_ds_secao36) || '"' || ',' || '"' || RTRIM(vr_ds_uf_secao37) || '"' || ',';
		v_sql10 := '"' || RTRIM(vr_ds_pai38) || '"' || ',' || '"' || RTRIM(vr_ds_mae39) || '"' || ',' || RTRIM(vr_co_origem_esc40) || ',' || '"' || RTRIM(vr_ds_web41) || '"' || ',' || '"' || RTRIM(vr_id_ativo_pass42) || '"' || ',';
		v_sql11 := '"' || RTRIM(vr_co_unidade43) || '"' || ',' || '"' || RTRIM(vr_co_aluno_anti44) || '"' || ',' || '"' || RTRIM(vr_ds_categoria45) || '"' || ',' || '"' || RTRIM(vr_tp_anee46) || '"' || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5 || v_sql6 || v_sql7 || v_sql8 || v_sql9 || v_sql10 || v_sql11;
	ELSIF p_op = 'del' THEN
		IF pa_co_aluno00 IS NULL THEN
			vr_co_aluno00 := 'null';
		ELSE
			vr_co_aluno00 := '"' || RTRIM(pa_co_aluno00) || '"';
		END IF;
		v_sql1 := '  delete from s_aluno where co_aluno = ' || RTRIM(vr_co_aluno00) || ';';
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
		IF pn_ds_aluno01 IS NULL
		AND pa_ds_aluno01 IS NULL THEN
			vr_ds_aluno01 := 'null';
		END IF;
		IF pn_ds_aluno01 IS NULL
		AND pa_ds_aluno01 IS NOT NULL THEN
			vr_ds_aluno01 := 'null';
		END IF;
		IF pn_ds_aluno01 IS NOT NULL
		AND pa_ds_aluno01 IS NULL THEN
			vr_ds_aluno01 := '"' || RTRIM(pn_ds_aluno01) || '"';
		END IF;
		IF pn_ds_aluno01 IS NOT NULL
		AND pa_ds_aluno01 IS NOT NULL THEN
			IF pa_ds_aluno01 <> pn_ds_aluno01 THEN
				vr_ds_aluno01 := '"' || RTRIM(pn_ds_aluno01) || '"';
			ELSE
				vr_ds_aluno01 := '"' || RTRIM(pa_ds_aluno01) || '"';
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
		IF pn_dt_nascimento03 IS NULL
		AND pa_dt_nascimento03 IS NULL THEN
			vr_dt_nascimento03 := 'null';
		END IF;
		IF pn_dt_nascimento03 IS NULL
		AND pa_dt_nascimento03 IS NOT NULL THEN
			vr_dt_nascimento03 := 'null';
		END IF;
		IF pn_dt_nascimento03 IS NOT NULL
		AND pa_dt_nascimento03 IS NULL THEN
			vr_dt_nascimento03 := '"' || pn_dt_nascimento03 || '"';
		END IF;
		IF pn_dt_nascimento03 IS NOT NULL
		AND pa_dt_nascimento03 IS NOT NULL THEN
			IF pa_dt_nascimento03 <> pn_dt_nascimento03 THEN
				vr_dt_nascimento03 := '"' || pn_dt_nascimento03 || '"';
			ELSE
				vr_dt_nascimento03 := '"' || pa_dt_nascimento03 || '"';
			END IF;
		END IF;
		IF pn_ds_aluno_orde04 IS NULL
		AND pa_ds_aluno_orde04 IS NULL THEN
			vr_ds_aluno_orde04 := 'null';
		END IF;
		IF pn_ds_aluno_orde04 IS NULL
		AND pa_ds_aluno_orde04 IS NOT NULL THEN
			vr_ds_aluno_orde04 := 'null';
		END IF;
		IF pn_ds_aluno_orde04 IS NOT NULL
		AND pa_ds_aluno_orde04 IS NULL THEN
			vr_ds_aluno_orde04 := '"' || RTRIM(pn_ds_aluno_orde04) || '"';
		END IF;
		IF pn_ds_aluno_orde04 IS NOT NULL
		AND pa_ds_aluno_orde04 IS NOT NULL THEN
			IF pa_ds_aluno_orde04 <> pn_ds_aluno_orde04 THEN
				vr_ds_aluno_orde04 := '"' || RTRIM(pn_ds_aluno_orde04) || '"';
			ELSE
				vr_ds_aluno_orde04 := '"' || RTRIM(pa_ds_aluno_orde04) || '"';
			END IF;
		END IF;
		IF pn_tp_sexo_aluno05 IS NULL
		AND pa_tp_sexo_aluno05 IS NULL THEN
			vr_tp_sexo_aluno05 := 'null';
		END IF;
		IF pn_tp_sexo_aluno05 IS NULL
		AND pa_tp_sexo_aluno05 IS NOT NULL THEN
			vr_tp_sexo_aluno05 := 'null';
		END IF;
		IF pn_tp_sexo_aluno05 IS NOT NULL
		AND pa_tp_sexo_aluno05 IS NULL THEN
			vr_tp_sexo_aluno05 := '"' || RTRIM(pn_tp_sexo_aluno05) || '"';
		END IF;
		IF pn_tp_sexo_aluno05 IS NOT NULL
		AND pa_tp_sexo_aluno05 IS NOT NULL THEN
			IF pa_tp_sexo_aluno05 <> pn_tp_sexo_aluno05 THEN
				vr_tp_sexo_aluno05 := '"' || RTRIM(pn_tp_sexo_aluno05) || '"';
			ELSE
				vr_tp_sexo_aluno05 := '"' || RTRIM(pa_tp_sexo_aluno05) || '"';
			END IF;
		END IF;
		IF pn_ds_naturalida06 IS NULL
		AND pa_ds_naturalida06 IS NULL THEN
			vr_ds_naturalida06 := 'null';
		END IF;
		IF pn_ds_naturalida06 IS NULL
		AND pa_ds_naturalida06 IS NOT NULL THEN
			vr_ds_naturalida06 := 'null';
		END IF;
		IF pn_ds_naturalida06 IS NOT NULL
		AND pa_ds_naturalida06 IS NULL THEN
			vr_ds_naturalida06 := '"' || RTRIM(pn_ds_naturalida06) || '"';
		END IF;
		IF pn_ds_naturalida06 IS NOT NULL
		AND pa_ds_naturalida06 IS NOT NULL THEN
			IF pa_ds_naturalida06 <> pn_ds_naturalida06 THEN
				vr_ds_naturalida06 := '"' || RTRIM(pn_ds_naturalida06) || '"';
			ELSE
				vr_ds_naturalida06 := '"' || RTRIM(pa_ds_naturalida06) || '"';
			END IF;
		END IF;
		IF pn_ds_uf_nascime07 IS NULL
		AND pa_ds_uf_nascime07 IS NULL THEN
			vr_ds_uf_nascime07 := 'null';
		END IF;
		IF pn_ds_uf_nascime07 IS NULL
		AND pa_ds_uf_nascime07 IS NOT NULL THEN
			vr_ds_uf_nascime07 := 'null';
		END IF;
		IF pn_ds_uf_nascime07 IS NOT NULL
		AND pa_ds_uf_nascime07 IS NULL THEN
			vr_ds_uf_nascime07 := '"' || RTRIM(pn_ds_uf_nascime07) || '"';
		END IF;
		IF pn_ds_uf_nascime07 IS NOT NULL
		AND pa_ds_uf_nascime07 IS NOT NULL THEN
			IF pa_ds_uf_nascime07 <> pn_ds_uf_nascime07 THEN
				vr_ds_uf_nascime07 := '"' || RTRIM(pn_ds_uf_nascime07) || '"';
			ELSE
				vr_ds_uf_nascime07 := '"' || RTRIM(pa_ds_uf_nascime07) || '"';
			END IF;
		END IF;
		IF pn_ds_nacionalid08 IS NULL
		AND pa_ds_nacionalid08 IS NULL THEN
			vr_ds_nacionalid08 := 'null';
		END IF;
		IF pn_ds_nacionalid08 IS NULL
		AND pa_ds_nacionalid08 IS NOT NULL THEN
			vr_ds_nacionalid08 := 'null';
		END IF;
		IF pn_ds_nacionalid08 IS NOT NULL
		AND pa_ds_nacionalid08 IS NULL THEN
			vr_ds_nacionalid08 := '"' || RTRIM(pn_ds_nacionalid08) || '"';
		END IF;
		IF pn_ds_nacionalid08 IS NOT NULL
		AND pa_ds_nacionalid08 IS NOT NULL THEN
			IF pa_ds_nacionalid08 <> pn_ds_nacionalid08 THEN
				vr_ds_nacionalid08 := '"' || RTRIM(pn_ds_nacionalid08) || '"';
			ELSE
				vr_ds_nacionalid08 := '"' || RTRIM(pa_ds_nacionalid08) || '"';
			END IF;
		END IF;
		IF pn_ds_endereco09 IS NULL
		AND pa_ds_endereco09 IS NULL THEN
			vr_ds_endereco09 := 'null';
		END IF;
		IF pn_ds_endereco09 IS NULL
		AND pa_ds_endereco09 IS NOT NULL THEN
			vr_ds_endereco09 := 'null';
		END IF;
		IF pn_ds_endereco09 IS NOT NULL
		AND pa_ds_endereco09 IS NULL THEN
			vr_ds_endereco09 := '"' || RTRIM(pn_ds_endereco09) || '"';
		END IF;
		IF pn_ds_endereco09 IS NOT NULL
		AND pa_ds_endereco09 IS NOT NULL THEN
			IF pa_ds_endereco09 <> pn_ds_endereco09 THEN
				vr_ds_endereco09 := '"' || RTRIM(pn_ds_endereco09) || '"';
			ELSE
				vr_ds_endereco09 := '"' || RTRIM(pa_ds_endereco09) || '"';
			END IF;
		END IF;
		IF pn_ds_bairro10 IS NULL
		AND pa_ds_bairro10 IS NULL THEN
			vr_ds_bairro10 := 'null';
		END IF;
		IF pn_ds_bairro10 IS NULL
		AND pa_ds_bairro10 IS NOT NULL THEN
			vr_ds_bairro10 := 'null';
		END IF;
		IF pn_ds_bairro10 IS NOT NULL
		AND pa_ds_bairro10 IS NULL THEN
			vr_ds_bairro10 := '"' || RTRIM(pn_ds_bairro10) || '"';
		END IF;
		IF pn_ds_bairro10 IS NOT NULL
		AND pa_ds_bairro10 IS NOT NULL THEN
			IF pa_ds_bairro10 <> pn_ds_bairro10 THEN
				vr_ds_bairro10 := '"' || RTRIM(pn_ds_bairro10) || '"';
			ELSE
				vr_ds_bairro10 := '"' || RTRIM(pa_ds_bairro10) || '"';
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
		IF pn_ds_e_mail14 IS NULL
		AND pa_ds_e_mail14 IS NULL THEN
			vr_ds_e_mail14 := 'null';
		END IF;
		IF pn_ds_e_mail14 IS NULL
		AND pa_ds_e_mail14 IS NOT NULL THEN
			vr_ds_e_mail14 := 'null';
		END IF;
		IF pn_ds_e_mail14 IS NOT NULL
		AND pa_ds_e_mail14 IS NULL THEN
			vr_ds_e_mail14 := '"' || RTRIM(pn_ds_e_mail14) || '"';
		END IF;
		IF pn_ds_e_mail14 IS NOT NULL
		AND pa_ds_e_mail14 IS NOT NULL THEN
			IF pa_ds_e_mail14 <> pn_ds_e_mail14 THEN
				vr_ds_e_mail14 := '"' || RTRIM(pn_ds_e_mail14) || '"';
			ELSE
				vr_ds_e_mail14 := '"' || RTRIM(pa_ds_e_mail14) || '"';
			END IF;
		END IF;
		IF pn_tp_estado_civ15 IS NULL
		AND pa_tp_estado_civ15 IS NULL THEN
			vr_tp_estado_civ15 := 'null';
		END IF;
		IF pn_tp_estado_civ15 IS NULL
		AND pa_tp_estado_civ15 IS NOT NULL THEN
			vr_tp_estado_civ15 := 'null';
		END IF;
		IF pn_tp_estado_civ15 IS NOT NULL
		AND pa_tp_estado_civ15 IS NULL THEN
			vr_tp_estado_civ15 := '"' || RTRIM(pn_tp_estado_civ15) || '"';
		END IF;
		IF pn_tp_estado_civ15 IS NOT NULL
		AND pa_tp_estado_civ15 IS NOT NULL THEN
			IF pa_tp_estado_civ15 <> pn_tp_estado_civ15 THEN
				vr_tp_estado_civ15 := '"' || RTRIM(pn_tp_estado_civ15) || '"';
			ELSE
				vr_tp_estado_civ15 := '"' || RTRIM(pa_tp_estado_civ15) || '"';
			END IF;
		END IF;
		IF pn_ds_conjuge16 IS NULL
		AND pa_ds_conjuge16 IS NULL THEN
			vr_ds_conjuge16 := 'null';
		END IF;
		IF pn_ds_conjuge16 IS NULL
		AND pa_ds_conjuge16 IS NOT NULL THEN
			vr_ds_conjuge16 := 'null';
		END IF;
		IF pn_ds_conjuge16 IS NOT NULL
		AND pa_ds_conjuge16 IS NULL THEN
			vr_ds_conjuge16 := '"' || RTRIM(pn_ds_conjuge16) || '"';
		END IF;
		IF pn_ds_conjuge16 IS NOT NULL
		AND pa_ds_conjuge16 IS NOT NULL THEN
			IF pa_ds_conjuge16 <> pn_ds_conjuge16 THEN
				vr_ds_conjuge16 := '"' || RTRIM(pn_ds_conjuge16) || '"';
			ELSE
				vr_ds_conjuge16 := '"' || RTRIM(pa_ds_conjuge16) || '"';
			END IF;
		END IF;
		IF pn_nu_rg17 IS NULL
		AND pa_nu_rg17 IS NULL THEN
			vr_nu_rg17 := 'null';
		END IF;
		IF pn_nu_rg17 IS NULL
		AND pa_nu_rg17 IS NOT NULL THEN
			vr_nu_rg17 := 'null';
		END IF;
		IF pn_nu_rg17 IS NOT NULL
		AND pa_nu_rg17 IS NULL THEN
			vr_nu_rg17 := '"' || RTRIM(pn_nu_rg17) || '"';
		END IF;
		IF pn_nu_rg17 IS NOT NULL
		AND pa_nu_rg17 IS NOT NULL THEN
			IF pa_nu_rg17 <> pn_nu_rg17 THEN
				vr_nu_rg17 := '"' || RTRIM(pn_nu_rg17) || '"';
			ELSE
				vr_nu_rg17 := '"' || RTRIM(pa_nu_rg17) || '"';
			END IF;
		END IF;
		IF pn_ds_orgao_emis18 IS NULL
		AND pa_ds_orgao_emis18 IS NULL THEN
			vr_ds_orgao_emis18 := 'null';
		END IF;
		IF pn_ds_orgao_emis18 IS NULL
		AND pa_ds_orgao_emis18 IS NOT NULL THEN
			vr_ds_orgao_emis18 := 'null';
		END IF;
		IF pn_ds_orgao_emis18 IS NOT NULL
		AND pa_ds_orgao_emis18 IS NULL THEN
			vr_ds_orgao_emis18 := '"' || RTRIM(pn_ds_orgao_emis18) || '"';
		END IF;
		IF pn_ds_orgao_emis18 IS NOT NULL
		AND pa_ds_orgao_emis18 IS NOT NULL THEN
			IF pa_ds_orgao_emis18 <> pn_ds_orgao_emis18 THEN
				vr_ds_orgao_emis18 := '"' || RTRIM(pn_ds_orgao_emis18) || '"';
			ELSE
				vr_ds_orgao_emis18 := '"' || RTRIM(pa_ds_orgao_emis18) || '"';
			END IF;
		END IF;
		IF pn_dt_emissao19 IS NULL
		AND pa_dt_emissao19 IS NULL THEN
			vr_dt_emissao19 := 'null';
		END IF;
		IF pn_dt_emissao19 IS NULL
		AND pa_dt_emissao19 IS NOT NULL THEN
			vr_dt_emissao19 := 'null';
		END IF;
		IF pn_dt_emissao19 IS NOT NULL
		AND pa_dt_emissao19 IS NULL THEN
			vr_dt_emissao19 := '"' || pn_dt_emissao19 || '"';
		END IF;
		IF pn_dt_emissao19 IS NOT NULL
		AND pa_dt_emissao19 IS NOT NULL THEN
			IF pa_dt_emissao19 <> pn_dt_emissao19 THEN
				vr_dt_emissao19 := '"' || pn_dt_emissao19 || '"';
			ELSE
				vr_dt_emissao19 := '"' || pa_dt_emissao19 || '"';
			END IF;
		END IF;
		IF pn_nu_cpf20 IS NULL
		AND pa_nu_cpf20 IS NULL THEN
			vr_nu_cpf20 := 'null';
		END IF;
		IF pn_nu_cpf20 IS NULL
		AND pa_nu_cpf20 IS NOT NULL THEN
			vr_nu_cpf20 := 'null';
		END IF;
		IF pn_nu_cpf20 IS NOT NULL
		AND pa_nu_cpf20 IS NULL THEN
			vr_nu_cpf20 := '"' || RTRIM(pn_nu_cpf20) || '"';
		END IF;
		IF pn_nu_cpf20 IS NOT NULL
		AND pa_nu_cpf20 IS NOT NULL THEN
			IF pa_nu_cpf20 <> pn_nu_cpf20 THEN
				vr_nu_cpf20 := '"' || RTRIM(pn_nu_cpf20) || '"';
			ELSE
				vr_nu_cpf20 := '"' || RTRIM(pa_nu_cpf20) || '"';
			END IF;
		END IF;
		IF pn_ds_ficha_medi21 IS NULL THEN
			vr_ds_ficha_medi21 := NULL;
		ELSE
			vr_ds_ficha_medi21 := ':vblob2';
		END IF;
		v_blob2 := pn_ds_ficha_medi21;
		IF pn_tp_escola_ori22 IS NULL
		AND pa_tp_escola_ori22 IS NULL THEN
			vr_tp_escola_ori22 := 'null';
		END IF;
		IF pn_tp_escola_ori22 IS NULL
		AND pa_tp_escola_ori22 IS NOT NULL THEN
			vr_tp_escola_ori22 := 'null';
		END IF;
		IF pn_tp_escola_ori22 IS NOT NULL
		AND pa_tp_escola_ori22 IS NULL THEN
			vr_tp_escola_ori22 := '"' || RTRIM(pn_tp_escola_ori22) || '"';
		END IF;
		IF pn_tp_escola_ori22 IS NOT NULL
		AND pa_tp_escola_ori22 IS NOT NULL THEN
			IF pa_tp_escola_ori22 <> pn_tp_escola_ori22 THEN
				vr_tp_escola_ori22 := '"' || RTRIM(pn_tp_escola_ori22) || '"';
			ELSE
				vr_tp_escola_ori22 := '"' || RTRIM(pa_tp_escola_ori22) || '"';
			END IF;
		END IF;
		IF pn_dt_ingresso23 IS NULL
		AND pa_dt_ingresso23 IS NULL THEN
			vr_dt_ingresso23 := 'null';
		END IF;
		IF pn_dt_ingresso23 IS NULL
		AND pa_dt_ingresso23 IS NOT NULL THEN
			vr_dt_ingresso23 := 'null';
		END IF;
		IF pn_dt_ingresso23 IS NOT NULL
		AND pa_dt_ingresso23 IS NULL THEN
			vr_dt_ingresso23 := '"' || pn_dt_ingresso23 || '"';
		END IF;
		IF pn_dt_ingresso23 IS NOT NULL
		AND pa_dt_ingresso23 IS NOT NULL THEN
			IF pa_dt_ingresso23 <> pn_dt_ingresso23 THEN
				vr_dt_ingresso23 := '"' || pn_dt_ingresso23 || '"';
			ELSE
				vr_dt_ingresso23 := '"' || pa_dt_ingresso23 || '"';
			END IF;
		END IF;
		IF pn_nu_tempo_esco24 IS NULL
		AND pa_nu_tempo_esco24 IS NULL THEN
			vr_nu_tempo_esco24 := 'null';
		END IF;
		IF pn_nu_tempo_esco24 IS NULL
		AND pa_nu_tempo_esco24 IS NOT NULL THEN
			vr_nu_tempo_esco24 := 'null';
		END IF;
		IF pn_nu_tempo_esco24 IS NOT NULL
		AND pa_nu_tempo_esco24 IS NULL THEN
			vr_nu_tempo_esco24 := '"' || RTRIM(pn_nu_tempo_esco24) || '"';
		END IF;
		IF pn_nu_tempo_esco24 IS NOT NULL
		AND pa_nu_tempo_esco24 IS NOT NULL THEN
			IF pa_nu_tempo_esco24 <> pn_nu_tempo_esco24 THEN
				vr_nu_tempo_esco24 := '"' || RTRIM(pn_nu_tempo_esco24) || '"';
			ELSE
				vr_nu_tempo_esco24 := '"' || RTRIM(pa_nu_tempo_esco24) || '"';
			END IF;
		END IF;
		IF pn_ds_certidao25 IS NULL
		AND pa_ds_certidao25 IS NULL THEN
			vr_ds_certidao25 := 'null';
		END IF;
		IF pn_ds_certidao25 IS NULL
		AND pa_ds_certidao25 IS NOT NULL THEN
			vr_ds_certidao25 := 'null';
		END IF;
		IF pn_ds_certidao25 IS NOT NULL
		AND pa_ds_certidao25 IS NULL THEN
			vr_ds_certidao25 := '"' || RTRIM(pn_ds_certidao25) || '"';
		END IF;
		IF pn_ds_certidao25 IS NOT NULL
		AND pa_ds_certidao25 IS NOT NULL THEN
			IF pa_ds_certidao25 <> pn_ds_certidao25 THEN
				vr_ds_certidao25 := '"' || RTRIM(pn_ds_certidao25) || '"';
			ELSE
				vr_ds_certidao25 := '"' || RTRIM(pa_ds_certidao25) || '"';
			END IF;
		END IF;
		IF pn_nu_certidao26 IS NULL
		AND pa_nu_certidao26 IS NULL THEN
			vr_nu_certidao26 := 'null';
		END IF;
		IF pn_nu_certidao26 IS NULL
		AND pa_nu_certidao26 IS NOT NULL THEN
			vr_nu_certidao26 := 'null';
		END IF;
		IF pn_nu_certidao26 IS NOT NULL
		AND pa_nu_certidao26 IS NULL THEN
			vr_nu_certidao26 := '"' || RTRIM(pn_nu_certidao26) || '"';
		END IF;
		IF pn_nu_certidao26 IS NOT NULL
		AND pa_nu_certidao26 IS NOT NULL THEN
			IF pa_nu_certidao26 <> pn_nu_certidao26 THEN
				vr_nu_certidao26 := '"' || RTRIM(pn_nu_certidao26) || '"';
			ELSE
				vr_nu_certidao26 := '"' || RTRIM(pa_nu_certidao26) || '"';
			END IF;
		END IF;
		IF pn_nu_livro27 IS NULL
		AND pa_nu_livro27 IS NULL THEN
			vr_nu_livro27 := 'null';
		END IF;
		IF pn_nu_livro27 IS NULL
		AND pa_nu_livro27 IS NOT NULL THEN
			vr_nu_livro27 := 'null';
		END IF;
		IF pn_nu_livro27 IS NOT NULL
		AND pa_nu_livro27 IS NULL THEN
			vr_nu_livro27 := '"' || RTRIM(pn_nu_livro27) || '"';
		END IF;
		IF pn_nu_livro27 IS NOT NULL
		AND pa_nu_livro27 IS NOT NULL THEN
			IF pa_nu_livro27 <> pn_nu_livro27 THEN
				vr_nu_livro27 := '"' || RTRIM(pn_nu_livro27) || '"';
			ELSE
				vr_nu_livro27 := '"' || RTRIM(pa_nu_livro27) || '"';
			END IF;
		END IF;
		IF pn_nu_folha28 IS NULL
		AND pa_nu_folha28 IS NULL THEN
			vr_nu_folha28 := 'null';
		END IF;
		IF pn_nu_folha28 IS NULL
		AND pa_nu_folha28 IS NOT NULL THEN
			vr_nu_folha28 := 'null';
		END IF;
		IF pn_nu_folha28 IS NOT NULL
		AND pa_nu_folha28 IS NULL THEN
			vr_nu_folha28 := '"' || RTRIM(pn_nu_folha28) || '"';
		END IF;
		IF pn_nu_folha28 IS NOT NULL
		AND pa_nu_folha28 IS NOT NULL THEN
			IF pa_nu_folha28 <> pn_nu_folha28 THEN
				vr_nu_folha28 := '"' || RTRIM(pn_nu_folha28) || '"';
			ELSE
				vr_nu_folha28 := '"' || RTRIM(pa_nu_folha28) || '"';
			END IF;
		END IF;
		IF pn_ds_cartorio29 IS NULL
		AND pa_ds_cartorio29 IS NULL THEN
			vr_ds_cartorio29 := 'null';
		END IF;
		IF pn_ds_cartorio29 IS NULL
		AND pa_ds_cartorio29 IS NOT NULL THEN
			vr_ds_cartorio29 := 'null';
		END IF;
		IF pn_ds_cartorio29 IS NOT NULL
		AND pa_ds_cartorio29 IS NULL THEN
			vr_ds_cartorio29 := '"' || RTRIM(pn_ds_cartorio29) || '"';
		END IF;
		IF pn_ds_cartorio29 IS NOT NULL
		AND pa_ds_cartorio29 IS NOT NULL THEN
			IF pa_ds_cartorio29 <> pn_ds_cartorio29 THEN
				vr_ds_cartorio29 := '"' || RTRIM(pn_ds_cartorio29) || '"';
			ELSE
				vr_ds_cartorio29 := '"' || RTRIM(pa_ds_cartorio29) || '"';
			END IF;
		END IF;
		IF pn_ds_cidade_cer30 IS NULL
		AND pa_ds_cidade_cer30 IS NULL THEN
			vr_ds_cidade_cer30 := 'null';
		END IF;
		IF pn_ds_cidade_cer30 IS NULL
		AND pa_ds_cidade_cer30 IS NOT NULL THEN
			vr_ds_cidade_cer30 := 'null';
		END IF;
		IF pn_ds_cidade_cer30 IS NOT NULL
		AND pa_ds_cidade_cer30 IS NULL THEN
			vr_ds_cidade_cer30 := '"' || RTRIM(pn_ds_cidade_cer30) || '"';
		END IF;
		IF pn_ds_cidade_cer30 IS NOT NULL
		AND pa_ds_cidade_cer30 IS NOT NULL THEN
			IF pa_ds_cidade_cer30 <> pn_ds_cidade_cer30 THEN
				vr_ds_cidade_cer30 := '"' || RTRIM(pn_ds_cidade_cer30) || '"';
			ELSE
				vr_ds_cidade_cer30 := '"' || RTRIM(pa_ds_cidade_cer30) || '"';
			END IF;
		END IF;
		IF pn_ds_foto31 IS NULL THEN
			vr_ds_foto31 := NULL;
		ELSE
			vr_ds_foto31 := ':vblob2';
		END IF;
		v_blob2 := pn_ds_foto31;
		IF pn_ds_uf_certida32 IS NULL
		AND pa_ds_uf_certida32 IS NULL THEN
			vr_ds_uf_certida32 := 'null';
		END IF;
		IF pn_ds_uf_certida32 IS NULL
		AND pa_ds_uf_certida32 IS NOT NULL THEN
			vr_ds_uf_certida32 := 'null';
		END IF;
		IF pn_ds_uf_certida32 IS NOT NULL
		AND pa_ds_uf_certida32 IS NULL THEN
			vr_ds_uf_certida32 := '"' || RTRIM(pn_ds_uf_certida32) || '"';
		END IF;
		IF pn_ds_uf_certida32 IS NOT NULL
		AND pa_ds_uf_certida32 IS NOT NULL THEN
			IF pa_ds_uf_certida32 <> pn_ds_uf_certida32 THEN
				vr_ds_uf_certida32 := '"' || RTRIM(pn_ds_uf_certida32) || '"';
			ELSE
				vr_ds_uf_certida32 := '"' || RTRIM(pa_ds_uf_certida32) || '"';
			END IF;
		END IF;
		IF pn_nu_reservista33 IS NULL
		AND pa_nu_reservista33 IS NULL THEN
			vr_nu_reservista33 := 'null';
		END IF;
		IF pn_nu_reservista33 IS NULL
		AND pa_nu_reservista33 IS NOT NULL THEN
			vr_nu_reservista33 := 'null';
		END IF;
		IF pn_nu_reservista33 IS NOT NULL
		AND pa_nu_reservista33 IS NULL THEN
			vr_nu_reservista33 := '"' || RTRIM(pn_nu_reservista33) || '"';
		END IF;
		IF pn_nu_reservista33 IS NOT NULL
		AND pa_nu_reservista33 IS NOT NULL THEN
			IF pa_nu_reservista33 <> pn_nu_reservista33 THEN
				vr_nu_reservista33 := '"' || RTRIM(pn_nu_reservista33) || '"';
			ELSE
				vr_nu_reservista33 := '"' || RTRIM(pa_nu_reservista33) || '"';
			END IF;
		END IF;
		IF pn_nu_titulo_ele34 IS NULL
		AND pa_nu_titulo_ele34 IS NULL THEN
			vr_nu_titulo_ele34 := 'null';
		END IF;
		IF pn_nu_titulo_ele34 IS NULL
		AND pa_nu_titulo_ele34 IS NOT NULL THEN
			vr_nu_titulo_ele34 := 'null';
		END IF;
		IF pn_nu_titulo_ele34 IS NOT NULL
		AND pa_nu_titulo_ele34 IS NULL THEN
			vr_nu_titulo_ele34 := '"' || RTRIM(pn_nu_titulo_ele34) || '"';
		END IF;
		IF pn_nu_titulo_ele34 IS NOT NULL
		AND pa_nu_titulo_ele34 IS NOT NULL THEN
			IF pa_nu_titulo_ele34 <> pn_nu_titulo_ele34 THEN
				vr_nu_titulo_ele34 := '"' || RTRIM(pn_nu_titulo_ele34) || '"';
			ELSE
				vr_nu_titulo_ele34 := '"' || RTRIM(pa_nu_titulo_ele34) || '"';
			END IF;
		END IF;
		IF pn_ds_zona35 IS NULL
		AND pa_ds_zona35 IS NULL THEN
			vr_ds_zona35 := 'null';
		END IF;
		IF pn_ds_zona35 IS NULL
		AND pa_ds_zona35 IS NOT NULL THEN
			vr_ds_zona35 := 'null';
		END IF;
		IF pn_ds_zona35 IS NOT NULL
		AND pa_ds_zona35 IS NULL THEN
			vr_ds_zona35 := '"' || RTRIM(pn_ds_zona35) || '"';
		END IF;
		IF pn_ds_zona35 IS NOT NULL
		AND pa_ds_zona35 IS NOT NULL THEN
			IF pa_ds_zona35 <> pn_ds_zona35 THEN
				vr_ds_zona35 := '"' || RTRIM(pn_ds_zona35) || '"';
			ELSE
				vr_ds_zona35 := '"' || RTRIM(pa_ds_zona35) || '"';
			END IF;
		END IF;
		IF pn_ds_secao36 IS NULL
		AND pa_ds_secao36 IS NULL THEN
			vr_ds_secao36 := 'null';
		END IF;
		IF pn_ds_secao36 IS NULL
		AND pa_ds_secao36 IS NOT NULL THEN
			vr_ds_secao36 := 'null';
		END IF;
		IF pn_ds_secao36 IS NOT NULL
		AND pa_ds_secao36 IS NULL THEN
			vr_ds_secao36 := '"' || RTRIM(pn_ds_secao36) || '"';
		END IF;
		IF pn_ds_secao36 IS NOT NULL
		AND pa_ds_secao36 IS NOT NULL THEN
			IF pa_ds_secao36 <> pn_ds_secao36 THEN
				vr_ds_secao36 := '"' || RTRIM(pn_ds_secao36) || '"';
			ELSE
				vr_ds_secao36 := '"' || RTRIM(pa_ds_secao36) || '"';
			END IF;
		END IF;
		IF pn_ds_uf_secao37 IS NULL
		AND pa_ds_uf_secao37 IS NULL THEN
			vr_ds_uf_secao37 := 'null';
		END IF;
		IF pn_ds_uf_secao37 IS NULL
		AND pa_ds_uf_secao37 IS NOT NULL THEN
			vr_ds_uf_secao37 := 'null';
		END IF;
		IF pn_ds_uf_secao37 IS NOT NULL
		AND pa_ds_uf_secao37 IS NULL THEN
			vr_ds_uf_secao37 := '"' || RTRIM(pn_ds_uf_secao37) || '"';
		END IF;
		IF pn_ds_uf_secao37 IS NOT NULL
		AND pa_ds_uf_secao37 IS NOT NULL THEN
			IF pa_ds_uf_secao37 <> pn_ds_uf_secao37 THEN
				vr_ds_uf_secao37 := '"' || RTRIM(pn_ds_uf_secao37) || '"';
			ELSE
				vr_ds_uf_secao37 := '"' || RTRIM(pa_ds_uf_secao37) || '"';
			END IF;
		END IF;
		IF pn_ds_pai38 IS NULL
		AND pa_ds_pai38 IS NULL THEN
			vr_ds_pai38 := 'null';
		END IF;
		IF pn_ds_pai38 IS NULL
		AND pa_ds_pai38 IS NOT NULL THEN
			vr_ds_pai38 := 'null';
		END IF;
		IF pn_ds_pai38 IS NOT NULL
		AND pa_ds_pai38 IS NULL THEN
			vr_ds_pai38 := '"' || RTRIM(pn_ds_pai38) || '"';
		END IF;
		IF pn_ds_pai38 IS NOT NULL
		AND pa_ds_pai38 IS NOT NULL THEN
			IF pa_ds_pai38 <> pn_ds_pai38 THEN
				vr_ds_pai38 := '"' || RTRIM(pn_ds_pai38) || '"';
			ELSE
				vr_ds_pai38 := '"' || RTRIM(pa_ds_pai38) || '"';
			END IF;
		END IF;
		IF pn_ds_mae39 IS NULL
		AND pa_ds_mae39 IS NULL THEN
			vr_ds_mae39 := 'null';
		END IF;
		IF pn_ds_mae39 IS NULL
		AND pa_ds_mae39 IS NOT NULL THEN
			vr_ds_mae39 := 'null';
		END IF;
		IF pn_ds_mae39 IS NOT NULL
		AND pa_ds_mae39 IS NULL THEN
			vr_ds_mae39 := '"' || RTRIM(pn_ds_mae39) || '"';
		END IF;
		IF pn_ds_mae39 IS NOT NULL
		AND pa_ds_mae39 IS NOT NULL THEN
			IF pa_ds_mae39 <> pn_ds_mae39 THEN
				vr_ds_mae39 := '"' || RTRIM(pn_ds_mae39) || '"';
			ELSE
				vr_ds_mae39 := '"' || RTRIM(pa_ds_mae39) || '"';
			END IF;
		END IF;
		IF pn_co_origem_esc40 IS NULL
		AND pa_co_origem_esc40 IS NULL THEN
			vr_co_origem_esc40 := 'null';
		END IF;
		IF pn_co_origem_esc40 IS NULL
		AND pa_co_origem_esc40 IS NOT NULL THEN
			vr_co_origem_esc40 := 'null';
		END IF;
		IF pn_co_origem_esc40 IS NOT NULL
		AND pa_co_origem_esc40 IS NULL THEN
			vr_co_origem_esc40 := pn_co_origem_esc40;
		END IF;
		IF pn_co_origem_esc40 IS NOT NULL
		AND pa_co_origem_esc40 IS NOT NULL THEN
			IF pa_co_origem_esc40 <> pn_co_origem_esc40 THEN
				vr_co_origem_esc40 := pn_co_origem_esc40;
			ELSE
				vr_co_origem_esc40 := pa_co_origem_esc40;
			END IF;
		END IF;
		IF pn_ds_web41 IS NULL
		AND pa_ds_web41 IS NULL THEN
			vr_ds_web41 := 'null';
		END IF;
		IF pn_ds_web41 IS NULL
		AND pa_ds_web41 IS NOT NULL THEN
			vr_ds_web41 := 'null';
		END IF;
		IF pn_ds_web41 IS NOT NULL
		AND pa_ds_web41 IS NULL THEN
			vr_ds_web41 := '"' || RTRIM(pn_ds_web41) || '"';
		END IF;
		IF pn_ds_web41 IS NOT NULL
		AND pa_ds_web41 IS NOT NULL THEN
			IF pa_ds_web41 <> pn_ds_web41 THEN
				vr_ds_web41 := '"' || RTRIM(pn_ds_web41) || '"';
			ELSE
				vr_ds_web41 := '"' || RTRIM(pa_ds_web41) || '"';
			END IF;
		END IF;
		IF pn_id_ativo_pass42 IS NULL
		AND pa_id_ativo_pass42 IS NULL THEN
			vr_id_ativo_pass42 := 'null';
		END IF;
		IF pn_id_ativo_pass42 IS NULL
		AND pa_id_ativo_pass42 IS NOT NULL THEN
			vr_id_ativo_pass42 := 'null';
		END IF;
		IF pn_id_ativo_pass42 IS NOT NULL
		AND pa_id_ativo_pass42 IS NULL THEN
			vr_id_ativo_pass42 := '"' || RTRIM(pn_id_ativo_pass42) || '"';
		END IF;
		IF pn_id_ativo_pass42 IS NOT NULL
		AND pa_id_ativo_pass42 IS NOT NULL THEN
			IF pa_id_ativo_pass42 <> pn_id_ativo_pass42 THEN
				vr_id_ativo_pass42 := '"' || RTRIM(pn_id_ativo_pass42) || '"';
			ELSE
				vr_id_ativo_pass42 := '"' || RTRIM(pa_id_ativo_pass42) || '"';
			END IF;
		END IF;
		IF pn_co_unidade43 IS NULL
		AND pa_co_unidade43 IS NULL THEN
			vr_co_unidade43 := 'null';
		END IF;
		IF pn_co_unidade43 IS NULL
		AND pa_co_unidade43 IS NOT NULL THEN
			vr_co_unidade43 := 'null';
		END IF;
		IF pn_co_unidade43 IS NOT NULL
		AND pa_co_unidade43 IS NULL THEN
			vr_co_unidade43 := '"' || RTRIM(pn_co_unidade43) || '"';
		END IF;
		IF pn_co_unidade43 IS NOT NULL
		AND pa_co_unidade43 IS NOT NULL THEN
			IF pa_co_unidade43 <> pn_co_unidade43 THEN
				vr_co_unidade43 := '"' || RTRIM(pn_co_unidade43) || '"';
			ELSE
				vr_co_unidade43 := '"' || RTRIM(pa_co_unidade43) || '"';
			END IF;
		END IF;
		IF pn_co_aluno_anti44 IS NULL
		AND pa_co_aluno_anti44 IS NULL THEN
			vr_co_aluno_anti44 := 'null';
		END IF;
		IF pn_co_aluno_anti44 IS NULL
		AND pa_co_aluno_anti44 IS NOT NULL THEN
			vr_co_aluno_anti44 := 'null';
		END IF;
		IF pn_co_aluno_anti44 IS NOT NULL
		AND pa_co_aluno_anti44 IS NULL THEN
			vr_co_aluno_anti44 := '"' || RTRIM(pn_co_aluno_anti44) || '"';
		END IF;
		IF pn_co_aluno_anti44 IS NOT NULL
		AND pa_co_aluno_anti44 IS NOT NULL THEN
			IF pa_co_aluno_anti44 <> pn_co_aluno_anti44 THEN
				vr_co_aluno_anti44 := '"' || RTRIM(pn_co_aluno_anti44) || '"';
			ELSE
				vr_co_aluno_anti44 := '"' || RTRIM(pa_co_aluno_anti44) || '"';
			END IF;
		END IF;
		IF pn_ds_categoria45 IS NULL
		AND pa_ds_categoria45 IS NULL THEN
			vr_ds_categoria45 := 'null';
		END IF;
		IF pn_ds_categoria45 IS NULL
		AND pa_ds_categoria45 IS NOT NULL THEN
			vr_ds_categoria45 := 'null';
		END IF;
		IF pn_ds_categoria45 IS NOT NULL
		AND pa_ds_categoria45 IS NULL THEN
			vr_ds_categoria45 := '"' || RTRIM(pn_ds_categoria45) || '"';
		END IF;
		IF pn_ds_categoria45 IS NOT NULL
		AND pa_ds_categoria45 IS NOT NULL THEN
			IF pa_ds_categoria45 <> pn_ds_categoria45 THEN
				vr_ds_categoria45 := '"' || RTRIM(pn_ds_categoria45) || '"';
			ELSE
				vr_ds_categoria45 := '"' || RTRIM(pa_ds_categoria45) || '"';
			END IF;
		END IF;
		IF pn_tp_anee46 IS NULL
		AND pa_tp_anee46 IS NULL THEN
			vr_tp_anee46 := 'null';
		END IF;
		IF pn_tp_anee46 IS NULL
		AND pa_tp_anee46 IS NOT NULL THEN
			vr_tp_anee46 := 'null';
		END IF;
		IF pn_tp_anee46 IS NOT NULL
		AND pa_tp_anee46 IS NULL THEN
			vr_tp_anee46 := '"' || RTRIM(pn_tp_anee46) || '"';
		END IF;
		IF pn_tp_anee46 IS NOT NULL
		AND pa_tp_anee46 IS NOT NULL THEN
			IF pa_tp_anee46 <> pn_tp_anee46 THEN
				vr_tp_anee46 := '"' || RTRIM(pn_tp_anee46) || '"';
			ELSE
				vr_tp_anee46 := '"' || RTRIM(pa_tp_anee46) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_aluno set co_aluno = ' || RTRIM(vr_co_aluno00) || '  , ds_aluno = ' || RTRIM(vr_ds_aluno01) || '  , co_seq_cidade = ' || RTRIM(vr_co_seq_cidade02) || '  , dt_nascimento = ' || RTRIM(vr_dt_nascimento03) || '  , ds_aluno_ordem = ' || RTRIM(vr_ds_aluno_orde04);
		v_sql2 := '  , tp_sexo_aluno = ' || RTRIM(vr_tp_sexo_aluno05) || '  , ds_naturalidade = ' || RTRIM(vr_ds_naturalida06);
		v_sql3 := '  , ds_uf_nascimento = ' || RTRIM(vr_ds_uf_nascime07) || '  , ds_nacionalidade = ' || RTRIM(vr_ds_nacionalid08);
		v_sql4 := '  , ds_endereco = ' || RTRIM(vr_ds_endereco09) || '  , ds_bairro = ' || RTRIM(vr_ds_bairro10);
		v_sql5 := '  , nu_cep = ' || RTRIM(vr_nu_cep11) || '  , ds_cidade = ' || RTRIM(vr_ds_cidade12);
		v_sql6 := '  , ds_uf_cidade = ' || RTRIM(vr_ds_uf_cidade13) || '  , ds_e_mail = ' || RTRIM(vr_ds_e_mail14);
		v_sql7 := '  , tp_estado_civil = ' || RTRIM(vr_tp_estado_civ15) || '  , ds_conjuge = ' || RTRIM(vr_ds_conjuge16);
		v_sql8 := '  , nu_rg = ' || RTRIM(vr_nu_rg17) || '  , ds_orgao_emissor = ' || RTRIM(vr_ds_orgao_emis18);
		v_sql9 := '  , dt_emissao = ' || RTRIM(vr_dt_emissao19) || '  , nu_cpf = ' || RTRIM(vr_nu_cpf20);
		v_sql10 := '  , ds_ficha_medica = ' || RTRIM(vr_ds_ficha_medi21) || '  , tp_escola_origem = ' || RTRIM(vr_tp_escola_ori22);
		v_sql11 := '  , dt_ingresso = ' || RTRIM(vr_dt_ingresso23) || '  , NU_TEMPO_ESCOLARIDADE = ' || RTRIM(vr_nu_tempo_esco24);
		v_sql12 := '  , ds_certidao = ' || RTRIM(vr_ds_certidao25) || '  , nu_certidao = ' || RTRIM(vr_nu_certidao26);
		v_sql13 := '  , nu_livro = ' || RTRIM(vr_nu_livro27) || '  , nu_folha = ' || RTRIM(vr_nu_folha28) || '  , ds_cartorio = ' || RTRIM(vr_ds_cartorio29);
		v_sql14 := '  , ds_cidade_certidao = ' || RTRIM(vr_ds_cidade_cer30) || '  , ds_foto = ' || RTRIM(vr_ds_foto31) || '  , ds_uf_certidao = ' || RTRIM(vr_ds_uf_certida32);
		v_sql15 := '  , nu_reservista = ' || RTRIM(vr_nu_reservista33) || '  , nu_titulo_eleitor = ' || RTRIM(vr_nu_titulo_ele34) || '  , ds_zona = ' || RTRIM(vr_ds_zona35);
		v_sql16 := '  , ds_secao = ' || RTRIM(vr_ds_secao36) || '  , ds_uf_secao = ' || RTRIM(vr_ds_uf_secao37) || '  , ds_pai = ' || RTRIM(vr_ds_pai38) || '  , ds_mae = ' || RTRIM(vr_ds_mae39);
		v_sql17 := '  , co_origem_escola = ' || RTRIM(vr_co_origem_esc40) || '  , ds_web = ' || RTRIM(vr_ds_web41) || '  , id_ativo_passivo = ' || RTRIM(vr_id_ativo_pass42);
		v_sql18 := '  , co_unidade = ' || RTRIM(vr_co_unidade43) || '  , co_aluno_antigo = ' || RTRIM(vr_co_aluno_anti44) || '  , ds_categoria = ' || RTRIM(vr_ds_categoria45) || '  , tp_anee = ' || RTRIM(vr_tp_anee46);
		v_sql19 := ' where co_aluno = ' || RTRIM(vr_co_aluno00) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5 || v_sql6 || v_sql7 || v_sql8 || v_sql9 || v_sql10 || v_sql11 || v_sql12 || v_sql13 || v_sql14 || v_sql15 || v_sql16 || v_sql17 || v_sql18 || v_sql19;
	END IF;
	IF p_op = 'del' THEN
		v_uni := pa_co_unidade43;
	ELSE
		v_uni := pn_co_unidade43;
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
		       's_aluno',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       v_blob1,
		       v_blob2);
	END IF;
END pr_s_aluno020;
/

