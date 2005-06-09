CREATE OR REPLACE PROCEDURE pr_s_unidade178(
	P_OP_IN                CHAR,
	PA_co_unidade00_IN     s_unidade.co_unidade%TYPE,
	PA_ds_unidade01_IN     s_unidade.ds_unidade%TYPE,
	PA_co_seq_cidade02_IN  s_unidade.co_seq_cidade%TYPE,
	PA_tp_escola03_IN      s_unidade.tp_escola%TYPE,
	PA_ds_nome_relat04_IN  s_unidade.ds_nome_relatorio%TYPE,
	PA_ds_vinheta05_IN     s_unidade.ds_vinheta%TYPE,
	PA_ds_endereco06_IN    s_unidade.ds_endereco%TYPE,
	PA_ds_bairro07_IN      s_unidade.ds_bairro%TYPE,
	PA_nu_cep08_IN         s_unidade.nu_cep%TYPE,
	PA_ds_cidade09_IN      s_unidade.ds_cidade%TYPE,
	PA_ds_uf_cidade10_IN   s_unidade.ds_uf_cidade%TYPE,
	PA_nu_telefone_111_IN  s_unidade.nu_telefone_1%TYPE,
	PA_nu_telefone_212_IN  s_unidade.nu_telefone_2%TYPE,
	PA_nu_fax13_IN         s_unidade.nu_fax%TYPE,
	PA_ds_e_mail14_IN      s_unidade.ds_e_mail%TYPE,
	PA_ds_pagina_web15_IN  s_unidade.ds_pagina_web%TYPE,
	PA_ds_ato16_IN         s_unidade.ds_ato%TYPE,
	PA_ds_numero17_IN      s_unidade.ds_numero%TYPE,
	PA_dt_data18_IN        s_unidade.dt_data%TYPE,
	PA_ds_orgao19_IN       s_unidade.ds_orgao%TYPE,
	PA_ds_grade_curr20_IN  s_unidade.ds_grade_curric%TYPE,
	PA_nu_cgc_escola21_IN  s_unidade.nu_cgc_escola%TYPE,
	PA_nu_inscr_esco22_IN  s_unidade.nu_inscr_escola%TYPE,
	PA_ds_diretor23_IN     s_unidade.ds_diretor%TYPE,
	PA_ds_secretario24_IN  s_unidade.ds_secretario%TYPE,
	PA_tp_historico25_IN   s_unidade.tp_historico%TYPE,
	PA_fo_simbolo26_IN     s_unidade.fo_simbolo%TYPE,
	PA_ds_gre27_IN         s_unidade.ds_gre%TYPE,
	PN_co_unidade00_IN     s_unidade.co_unidade%TYPE,
	PN_ds_unidade01_IN     s_unidade.ds_unidade%TYPE,
	PN_co_seq_cidade02_IN  s_unidade.co_seq_cidade%TYPE,
	PN_tp_escola03_IN      s_unidade.tp_escola%TYPE,
	PN_ds_nome_relat04_IN  s_unidade.ds_nome_relatorio%TYPE,
	PN_ds_vinheta05_IN     s_unidade.ds_vinheta%TYPE,
	PN_ds_endereco06_IN    s_unidade.ds_endereco%TYPE,
	PN_ds_bairro07_IN      s_unidade.ds_bairro%TYPE,
	PN_nu_cep08_IN         s_unidade.nu_cep%TYPE,
	PN_ds_cidade09_IN      s_unidade.ds_cidade%TYPE,
	PN_ds_uf_cidade10_IN   s_unidade.ds_uf_cidade%TYPE,
	PN_nu_telefone_111_IN  s_unidade.nu_telefone_1%TYPE,
	PN_nu_telefone_212_IN  s_unidade.nu_telefone_2%TYPE,
	PN_nu_fax13_IN         s_unidade.nu_fax%TYPE,
	PN_ds_e_mail14_IN      s_unidade.ds_e_mail%TYPE,
	PN_ds_pagina_web15_IN  s_unidade.ds_pagina_web%TYPE,
	PN_ds_ato16_IN         s_unidade.ds_ato%TYPE,
	PN_ds_numero17_IN      s_unidade.ds_numero%TYPE,
	PN_dt_data18_IN        s_unidade.dt_data%TYPE,
	PN_ds_orgao19_IN       s_unidade.ds_orgao%TYPE,
	PN_ds_grade_curr20_IN  s_unidade.ds_grade_curric%TYPE,
	PN_nu_cgc_escola21_IN  s_unidade.nu_cgc_escola%TYPE,
	PN_nu_inscr_esco22_IN  s_unidade.nu_inscr_escola%TYPE,
	PN_ds_diretor23_IN     s_unidade.ds_diretor%TYPE,
	PN_ds_secretario24_IN  s_unidade.ds_secretario%TYPE,
	PN_tp_historico25_IN   s_unidade.tp_historico%TYPE,
	PN_fo_simbolo26_IN     s_unidade.fo_simbolo%TYPE,
	PN_ds_gre27_IN         s_unidade.ds_gre%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_unidade00     s_unidade.co_unidade%TYPE := PA_co_unidade00_IN;
PA_ds_unidade01     s_unidade.ds_unidade%TYPE := PA_ds_unidade01_IN;
PA_co_seq_cidade02  s_unidade.co_seq_cidade%TYPE := PA_co_seq_cidade02_IN;
PA_tp_escola03      s_unidade.tp_escola%TYPE := PA_tp_escola03_IN;
PA_ds_nome_relat04  s_unidade.ds_nome_relatorio%TYPE := PA_ds_nome_relat04_IN;
PA_ds_vinheta05     s_unidade.ds_vinheta%TYPE := PA_ds_vinheta05_IN;
PA_ds_endereco06    s_unidade.ds_endereco%TYPE := PA_ds_endereco06_IN;
PA_ds_bairro07      s_unidade.ds_bairro%TYPE := PA_ds_bairro07_IN;
PA_nu_cep08         s_unidade.nu_cep%TYPE := PA_nu_cep08_IN;
PA_ds_cidade09      s_unidade.ds_cidade%TYPE := PA_ds_cidade09_IN;
PA_ds_uf_cidade10   s_unidade.ds_uf_cidade%TYPE := PA_ds_uf_cidade10_IN;
PA_nu_telefone_111  s_unidade.nu_telefone_1%TYPE := PA_nu_telefone_111_IN;
PA_nu_telefone_212  s_unidade.nu_telefone_2%TYPE := PA_nu_telefone_212_IN;
PA_nu_fax13         s_unidade.nu_fax%TYPE := PA_nu_fax13_IN;
PA_ds_e_mail14      s_unidade.ds_e_mail%TYPE := PA_ds_e_mail14_IN;
PA_ds_pagina_web15  s_unidade.ds_pagina_web%TYPE := PA_ds_pagina_web15_IN;
PA_ds_ato16         s_unidade.ds_ato%TYPE := PA_ds_ato16_IN;
PA_ds_numero17      s_unidade.ds_numero%TYPE := PA_ds_numero17_IN;
PA_dt_data18        s_unidade.dt_data%TYPE := PA_dt_data18_IN;
PA_ds_orgao19       s_unidade.ds_orgao%TYPE := PA_ds_orgao19_IN;
PA_ds_grade_curr20  s_unidade.ds_grade_curric%TYPE := PA_ds_grade_curr20_IN;
PA_nu_cgc_escola21  s_unidade.nu_cgc_escola%TYPE := PA_nu_cgc_escola21_IN;
PA_nu_inscr_esco22  s_unidade.nu_inscr_escola%TYPE := PA_nu_inscr_esco22_IN;
PA_ds_diretor23     s_unidade.ds_diretor%TYPE := PA_ds_diretor23_IN;
PA_ds_secretario24  s_unidade.ds_secretario%TYPE := PA_ds_secretario24_IN;
PA_tp_historico25   s_unidade.tp_historico%TYPE := PA_tp_historico25_IN;
PA_fo_simbolo26     s_unidade.fo_simbolo%TYPE := PA_fo_simbolo26_IN;
PA_ds_gre27         s_unidade.ds_gre%TYPE := PA_ds_gre27_IN;
PN_co_unidade00     s_unidade.co_unidade%TYPE := PN_co_unidade00_IN;
PN_ds_unidade01     s_unidade.ds_unidade%TYPE := PN_ds_unidade01_IN;
PN_co_seq_cidade02  s_unidade.co_seq_cidade%TYPE := PN_co_seq_cidade02_IN;
PN_tp_escola03      s_unidade.tp_escola%TYPE := PN_tp_escola03_IN;
PN_ds_nome_relat04  s_unidade.ds_nome_relatorio%TYPE := PN_ds_nome_relat04_IN;
PN_ds_vinheta05     s_unidade.ds_vinheta%TYPE := PN_ds_vinheta05_IN;
PN_ds_endereco06    s_unidade.ds_endereco%TYPE := PN_ds_endereco06_IN;
PN_ds_bairro07      s_unidade.ds_bairro%TYPE := PN_ds_bairro07_IN;
PN_nu_cep08         s_unidade.nu_cep%TYPE := PN_nu_cep08_IN;
PN_ds_cidade09      s_unidade.ds_cidade%TYPE := PN_ds_cidade09_IN;
PN_ds_uf_cidade10   s_unidade.ds_uf_cidade%TYPE := PN_ds_uf_cidade10_IN;
PN_nu_telefone_111  s_unidade.nu_telefone_1%TYPE := PN_nu_telefone_111_IN;
PN_nu_telefone_212  s_unidade.nu_telefone_2%TYPE := PN_nu_telefone_212_IN;
PN_nu_fax13         s_unidade.nu_fax%TYPE := PN_nu_fax13_IN;
PN_ds_e_mail14      s_unidade.ds_e_mail%TYPE := PN_ds_e_mail14_IN;
PN_ds_pagina_web15  s_unidade.ds_pagina_web%TYPE := PN_ds_pagina_web15_IN;
PN_ds_ato16         s_unidade.ds_ato%TYPE := PN_ds_ato16_IN;
PN_ds_numero17      s_unidade.ds_numero%TYPE := PN_ds_numero17_IN;
PN_dt_data18        s_unidade.dt_data%TYPE := PN_dt_data18_IN;
PN_ds_orgao19       s_unidade.ds_orgao%TYPE := PN_ds_orgao19_IN;
PN_ds_grade_curr20  s_unidade.ds_grade_curric%TYPE := PN_ds_grade_curr20_IN;
PN_nu_cgc_escola21  s_unidade.nu_cgc_escola%TYPE := PN_nu_cgc_escola21_IN;
PN_nu_inscr_esco22  s_unidade.nu_inscr_escola%TYPE := PN_nu_inscr_esco22_IN;
PN_ds_diretor23     s_unidade.ds_diretor%TYPE := PN_ds_diretor23_IN;
PN_ds_secretario24  s_unidade.ds_secretario%TYPE := PN_ds_secretario24_IN;
PN_tp_historico25   s_unidade.tp_historico%TYPE := PN_tp_historico25_IN;
PN_fo_simbolo26     s_unidade.fo_simbolo%TYPE := PN_fo_simbolo26_IN;
PN_ds_gre27         s_unidade.ds_gre%TYPE := PN_ds_gre27_IN;
v_blob1             s_unidade.fo_simbolo%TYPE;
v_sql               VARCHAR2(3000);
v_sql1              CHAR(600);
v_sql2              CHAR(480);
v_sql3              CHAR(480);
v_sql4              CHAR(480);
v_sql5              CHAR(480);
v_sql6              CHAR(480);
v_uni               CHAR(10);
vr_co_unidade00     CHAR(10);
vr_ds_unidade01     CHAR(70);
vr_co_seq_cidade02  CHAR(10);
vr_tp_escola03      CHAR(10);
vr_ds_nome_relat04  CHAR(70);
vr_ds_vinheta05     CHAR(100);
vr_ds_endereco06    CHAR(100);
vr_ds_bairro07      CHAR(30);
vr_nu_cep08         CHAR(20);
vr_ds_cidade09      CHAR(40);
vr_ds_uf_cidade10   CHAR(10);
vr_nu_telefone_111  CHAR(20);
vr_nu_telefone_212  CHAR(20);
vr_nu_fax13         CHAR(20);
vr_ds_e_mail14      CHAR(100);
vr_ds_pagina_web15  CHAR(100);
vr_ds_ato16         CHAR(20);
vr_ds_numero17      CHAR(10);
vr_dt_data18        CHAR(40);
vr_ds_orgao19       CHAR(40);
vr_ds_grade_curr20  CHAR(20);
vr_nu_cgc_escola21  CHAR(30);
vr_nu_inscr_esco22  CHAR(40);
vr_ds_diretor23     CHAR(50);
vr_ds_secretario24  CHAR(50);
vr_tp_historico25   CHAR(10);
vr_fo_simbolo26     CHAR(10);
vr_ds_gre27         CHAR(80);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	v_blob1 := NULL;
	IF p_op = 'ins' THEN
		IF pn_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := pn_co_unidade00;
		END IF;
		IF pn_ds_unidade01 IS NULL THEN
			vr_ds_unidade01 := 'null';
		ELSE
			vr_ds_unidade01 := pn_ds_unidade01;
		END IF;
		IF pn_co_seq_cidade02 IS NULL THEN
			vr_co_seq_cidade02 := 'null';
		ELSE
			vr_co_seq_cidade02 := pn_co_seq_cidade02;
		END IF;
		IF pn_tp_escola03 IS NULL THEN
			vr_tp_escola03 := 'null';
		ELSE
			vr_tp_escola03 := pn_tp_escola03;
		END IF;
		IF pn_ds_nome_relat04 IS NULL THEN
			vr_ds_nome_relat04 := 'null';
		ELSE
			vr_ds_nome_relat04 := pn_ds_nome_relat04;
		END IF;
		IF pn_ds_vinheta05 IS NULL THEN
			vr_ds_vinheta05 := 'null';
		ELSE
			vr_ds_vinheta05 := pn_ds_vinheta05;
		END IF;
		IF pn_ds_endereco06 IS NULL THEN
			vr_ds_endereco06 := 'null';
		ELSE
			vr_ds_endereco06 := pn_ds_endereco06;
		END IF;
		IF pn_ds_bairro07 IS NULL THEN
			vr_ds_bairro07 := 'null';
		ELSE
			vr_ds_bairro07 := pn_ds_bairro07;
		END IF;
		IF pn_nu_cep08 IS NULL THEN
			vr_nu_cep08 := 'null';
		ELSE
			vr_nu_cep08 := pn_nu_cep08;
		END IF;
		IF pn_ds_cidade09 IS NULL THEN
			vr_ds_cidade09 := 'null';
		ELSE
			vr_ds_cidade09 := pn_ds_cidade09;
		END IF;
		IF pn_ds_uf_cidade10 IS NULL THEN
			vr_ds_uf_cidade10 := 'null';
		ELSE
			vr_ds_uf_cidade10 := pn_ds_uf_cidade10;
		END IF;
		IF pn_nu_telefone_111 IS NULL THEN
			vr_nu_telefone_111 := 'null';
		ELSE
			vr_nu_telefone_111 := pn_nu_telefone_111;
		END IF;
		IF pn_nu_telefone_212 IS NULL THEN
			vr_nu_telefone_212 := 'null';
		ELSE
			vr_nu_telefone_212 := pn_nu_telefone_212;
		END IF;
		IF pn_nu_fax13 IS NULL THEN
			vr_nu_fax13 := 'null';
		ELSE
			vr_nu_fax13 := pn_nu_fax13;
		END IF;
		IF pn_ds_e_mail14 IS NULL THEN
			vr_ds_e_mail14 := 'null';
		ELSE
			vr_ds_e_mail14 := pn_ds_e_mail14;
		END IF;
		IF pn_ds_pagina_web15 IS NULL THEN
			vr_ds_pagina_web15 := 'null';
		ELSE
			vr_ds_pagina_web15 := pn_ds_pagina_web15;
		END IF;
		IF pn_ds_ato16 IS NULL THEN
			vr_ds_ato16 := 'null';
		ELSE
			vr_ds_ato16 := pn_ds_ato16;
		END IF;
		IF pn_ds_numero17 IS NULL THEN
			vr_ds_numero17 := 'null';
		ELSE
			vr_ds_numero17 := pn_ds_numero17;
		END IF;
		IF pn_dt_data18 IS NULL THEN
			vr_dt_data18 := 'null';
		ELSE
			vr_dt_data18 := pn_dt_data18;
		END IF;
		IF pn_ds_orgao19 IS NULL THEN
			vr_ds_orgao19 := 'null';
		ELSE
			vr_ds_orgao19 := pn_ds_orgao19;
		END IF;
		IF pn_ds_grade_curr20 IS NULL THEN
			vr_ds_grade_curr20 := 'null';
		ELSE
			vr_ds_grade_curr20 := pn_ds_grade_curr20;
		END IF;
		IF pn_nu_cgc_escola21 IS NULL THEN
			vr_nu_cgc_escola21 := 'null';
		ELSE
			vr_nu_cgc_escola21 := pn_nu_cgc_escola21;
		END IF;
		IF pn_nu_inscr_esco22 IS NULL THEN
			vr_nu_inscr_esco22 := 'null';
		ELSE
			vr_nu_inscr_esco22 := pn_nu_inscr_esco22;
		END IF;
		IF pn_ds_diretor23 IS NULL THEN
			vr_ds_diretor23 := 'null';
		ELSE
			vr_ds_diretor23 := pn_ds_diretor23;
		END IF;
		IF pn_ds_secretario24 IS NULL THEN
			vr_ds_secretario24 := 'null';
		ELSE
			vr_ds_secretario24 := pn_ds_secretario24;
		END IF;
		IF pn_tp_historico25 IS NULL THEN
			vr_tp_historico25 := 'null';
		ELSE
			vr_tp_historico25 := pn_tp_historico25;
		END IF;
		IF pn_fo_simbolo26 IS NULL THEN
			vr_fo_simbolo26 := NULL;
		ELSE
			vr_fo_simbolo26 := ':vblob1';
		END IF;
		v_blob1 := pn_fo_simbolo26;
		IF pn_ds_gre27 IS NULL THEN
			vr_ds_gre27 := 'null';
		ELSE
			vr_ds_gre27 := pn_ds_gre27;
		END IF;
		v_sql1 := 'insert into s_unidade(co_unidade, ds_unidade, co_seq_cidade, tp_escola, ds_nome_relatorio, ds_vinheta, ds_endereco, ds_bairro, nu_cep, ' || 'ds_cidade, ds_uf_cidade, nu_telefone_1, nu_telefone_2, nu_fax, ds_e_mail, ds_pagina_web, ds_ato, ds_numero, dt_data, ds_orgao, DS_GRADE_CURRICULAR, nu_cgc_escola, NU_INSCRICAO_ESCOLA, ' || 'ds_diretor, ds_secretario, tp_historico, fo_simbolo, ds_gre) values (';
		v_sql2 := '"' || RTRIM(vr_co_unidade00) || '"' || ',' || '"' || RTRIM(vr_ds_unidade01) || '"' || ',' || RTRIM(vr_co_seq_cidade02) || ',' || '"' || RTRIM(vr_tp_escola03) || '"' || ',' || '"' || RTRIM(vr_ds_nome_relat04) || '"' || ',' || '"' || RTRIM(vr_ds_vinheta05) || '"' || ',' || '"' || RTRIM(vr_ds_endereco06) || '"' || ',' || '"' || RTRIM(vr_ds_bairro07) || '"' || ',';
		v_sql3 := '"' || RTRIM(vr_nu_cep08) || '"' || ',' || '"' || RTRIM(vr_ds_cidade09) || '"' || ',' || '"' || RTRIM(vr_ds_uf_cidade10) || '"' || ',' || '"' || RTRIM(vr_nu_telefone_111) || '"' || ',' || '"' || RTRIM(vr_nu_telefone_212) || '"' || ',' || '"' || RTRIM(vr_nu_fax13) || '"' || ',';
		v_sql4 := '"' || RTRIM(vr_ds_e_mail14) || '"' || ',' || '"' || RTRIM(vr_ds_pagina_web15) || '"' || ',' || '"' || RTRIM(vr_ds_ato16) || '"' || ',' || '"' || RTRIM(vr_ds_numero17) || '"' || ',' || '"' || vr_dt_data18 || '"' || ',' || '"' || RTRIM(vr_ds_orgao19) || '"' || ',';
		v_sql5 := '"' || RTRIM(vr_ds_grade_curr20) || '"' || ',' || '"' || RTRIM(vr_nu_cgc_escola21) || '"' || ',' || '"' || RTRIM(vr_nu_inscr_esco22) || '"' || ',' || '"' || RTRIM(vr_ds_diretor23) || '"' || ',' || '"' || RTRIM(vr_ds_secretario24) || '"' || ',' || '"' || RTRIM(vr_tp_historico25) || '"' || ',';
		v_sql6 := RTRIM(vr_fo_simbolo26) || ',' || '"' || RTRIM(vr_ds_gre27) || '"' || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5 || v_sql6;
	ELSIF p_op = 'del' THEN
		IF pa_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := '"' || RTRIM(pa_co_unidade00) || '"';
		END IF;
		v_sql1 := '  delete from s_unidade where co_unidade = ' || RTRIM(vr_co_unidade00) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_unidade00 IS NULL
		AND pa_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		END IF;
		IF pn_co_unidade00 IS NULL
		AND pa_co_unidade00 IS NOT NULL THEN
			vr_co_unidade00 := 'null';
		END IF;
		IF pn_co_unidade00 IS NOT NULL
		AND pa_co_unidade00 IS NULL THEN
			vr_co_unidade00 := '"' || RTRIM(pn_co_unidade00) || '"';
		END IF;
		IF pn_co_unidade00 IS NOT NULL
		AND pa_co_unidade00 IS NOT NULL THEN
			IF pa_co_unidade00 <> pn_co_unidade00 THEN
				vr_co_unidade00 := '"' || RTRIM(pn_co_unidade00) || '"';
			ELSE
				vr_co_unidade00 := '"' || RTRIM(pa_co_unidade00) || '"';
			END IF;
		END IF;
		IF pn_ds_unidade01 IS NULL
		AND pa_ds_unidade01 IS NULL THEN
			vr_ds_unidade01 := 'null';
		END IF;
		IF pn_ds_unidade01 IS NULL
		AND pa_ds_unidade01 IS NOT NULL THEN
			vr_ds_unidade01 := 'null';
		END IF;
		IF pn_ds_unidade01 IS NOT NULL
		AND pa_ds_unidade01 IS NULL THEN
			vr_ds_unidade01 := '"' || RTRIM(pn_ds_unidade01) || '"';
		END IF;
		IF pn_ds_unidade01 IS NOT NULL
		AND pa_ds_unidade01 IS NOT NULL THEN
			IF pa_ds_unidade01 <> pn_ds_unidade01 THEN
				vr_ds_unidade01 := '"' || RTRIM(pn_ds_unidade01) || '"';
			ELSE
				vr_ds_unidade01 := '"' || RTRIM(pa_ds_unidade01) || '"';
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
		IF pn_tp_escola03 IS NULL
		AND pa_tp_escola03 IS NULL THEN
			vr_tp_escola03 := 'null';
		END IF;
		IF pn_tp_escola03 IS NULL
		AND pa_tp_escola03 IS NOT NULL THEN
			vr_tp_escola03 := 'null';
		END IF;
		IF pn_tp_escola03 IS NOT NULL
		AND pa_tp_escola03 IS NULL THEN
			vr_tp_escola03 := '"' || RTRIM(pn_tp_escola03) || '"';
		END IF;
		IF pn_tp_escola03 IS NOT NULL
		AND pa_tp_escola03 IS NOT NULL THEN
			IF pa_tp_escola03 <> pn_tp_escola03 THEN
				vr_tp_escola03 := '"' || RTRIM(pn_tp_escola03) || '"';
			ELSE
				vr_tp_escola03 := '"' || RTRIM(pa_tp_escola03) || '"';
			END IF;
		END IF;
		IF pn_ds_nome_relat04 IS NULL
		AND pa_ds_nome_relat04 IS NULL THEN
			vr_ds_nome_relat04 := 'null';
		END IF;
		IF pn_ds_nome_relat04 IS NULL
		AND pa_ds_nome_relat04 IS NOT NULL THEN
			vr_ds_nome_relat04 := 'null';
		END IF;
		IF pn_ds_nome_relat04 IS NOT NULL
		AND pa_ds_nome_relat04 IS NULL THEN
			vr_ds_nome_relat04 := '"' || RTRIM(pn_ds_nome_relat04) || '"';
		END IF;
		IF pn_ds_nome_relat04 IS NOT NULL
		AND pa_ds_nome_relat04 IS NOT NULL THEN
			IF pa_ds_nome_relat04 <> pn_ds_nome_relat04 THEN
				vr_ds_nome_relat04 := '"' || RTRIM(pn_ds_nome_relat04) || '"';
			ELSE
				vr_ds_nome_relat04 := '"' || RTRIM(pa_ds_nome_relat04) || '"';
			END IF;
		END IF;
		IF pn_ds_vinheta05 IS NULL
		AND pa_ds_vinheta05 IS NULL THEN
			vr_ds_vinheta05 := 'null';
		END IF;
		IF pn_ds_vinheta05 IS NULL
		AND pa_ds_vinheta05 IS NOT NULL THEN
			vr_ds_vinheta05 := 'null';
		END IF;
		IF pn_ds_vinheta05 IS NOT NULL
		AND pa_ds_vinheta05 IS NULL THEN
			vr_ds_vinheta05 := '"' || RTRIM(pn_ds_vinheta05) || '"';
		END IF;
		IF pn_ds_vinheta05 IS NOT NULL
		AND pa_ds_vinheta05 IS NOT NULL THEN
			IF pa_ds_vinheta05 <> pn_ds_vinheta05 THEN
				vr_ds_vinheta05 := '"' || RTRIM(pn_ds_vinheta05) || '"';
			ELSE
				vr_ds_vinheta05 := '"' || RTRIM(pa_ds_vinheta05) || '"';
			END IF;
		END IF;
		IF pn_ds_endereco06 IS NULL
		AND pa_ds_endereco06 IS NULL THEN
			vr_ds_endereco06 := 'null';
		END IF;
		IF pn_ds_endereco06 IS NULL
		AND pa_ds_endereco06 IS NOT NULL THEN
			vr_ds_endereco06 := 'null';
		END IF;
		IF pn_ds_endereco06 IS NOT NULL
		AND pa_ds_endereco06 IS NULL THEN
			vr_ds_endereco06 := '"' || RTRIM(pn_ds_endereco06) || '"';
		END IF;
		IF pn_ds_endereco06 IS NOT NULL
		AND pa_ds_endereco06 IS NOT NULL THEN
			IF pa_ds_endereco06 <> pn_ds_endereco06 THEN
				vr_ds_endereco06 := '"' || RTRIM(pn_ds_endereco06) || '"';
			ELSE
				vr_ds_endereco06 := '"' || RTRIM(pa_ds_endereco06) || '"';
			END IF;
		END IF;
		IF pn_ds_bairro07 IS NULL
		AND pa_ds_bairro07 IS NULL THEN
			vr_ds_bairro07 := 'null';
		END IF;
		IF pn_ds_bairro07 IS NULL
		AND pa_ds_bairro07 IS NOT NULL THEN
			vr_ds_bairro07 := 'null';
		END IF;
		IF pn_ds_bairro07 IS NOT NULL
		AND pa_ds_bairro07 IS NULL THEN
			vr_ds_bairro07 := '"' || RTRIM(pn_ds_bairro07) || '"';
		END IF;
		IF pn_ds_bairro07 IS NOT NULL
		AND pa_ds_bairro07 IS NOT NULL THEN
			IF pa_ds_bairro07 <> pn_ds_bairro07 THEN
				vr_ds_bairro07 := '"' || RTRIM(pn_ds_bairro07) || '"';
			ELSE
				vr_ds_bairro07 := '"' || RTRIM(pa_ds_bairro07) || '"';
			END IF;
		END IF;
		IF pn_nu_cep08 IS NULL
		AND pa_nu_cep08 IS NULL THEN
			vr_nu_cep08 := 'null';
		END IF;
		IF pn_nu_cep08 IS NULL
		AND pa_nu_cep08 IS NOT NULL THEN
			vr_nu_cep08 := 'null';
		END IF;
		IF pn_nu_cep08 IS NOT NULL
		AND pa_nu_cep08 IS NULL THEN
			vr_nu_cep08 := '"' || RTRIM(pn_nu_cep08) || '"';
		END IF;
		IF pn_nu_cep08 IS NOT NULL
		AND pa_nu_cep08 IS NOT NULL THEN
			IF pa_nu_cep08 <> pn_nu_cep08 THEN
				vr_nu_cep08 := '"' || RTRIM(pn_nu_cep08) || '"';
			ELSE
				vr_nu_cep08 := '"' || RTRIM(pa_nu_cep08) || '"';
			END IF;
		END IF;
		IF pn_ds_cidade09 IS NULL
		AND pa_ds_cidade09 IS NULL THEN
			vr_ds_cidade09 := 'null';
		END IF;
		IF pn_ds_cidade09 IS NULL
		AND pa_ds_cidade09 IS NOT NULL THEN
			vr_ds_cidade09 := 'null';
		END IF;
		IF pn_ds_cidade09 IS NOT NULL
		AND pa_ds_cidade09 IS NULL THEN
			vr_ds_cidade09 := '"' || RTRIM(pn_ds_cidade09) || '"';
		END IF;
		IF pn_ds_cidade09 IS NOT NULL
		AND pa_ds_cidade09 IS NOT NULL THEN
			IF pa_ds_cidade09 <> pn_ds_cidade09 THEN
				vr_ds_cidade09 := '"' || RTRIM(pn_ds_cidade09) || '"';
			ELSE
				vr_ds_cidade09 := '"' || RTRIM(pa_ds_cidade09) || '"';
			END IF;
		END IF;
		IF pn_ds_uf_cidade10 IS NULL
		AND pa_ds_uf_cidade10 IS NULL THEN
			vr_ds_uf_cidade10 := 'null';
		END IF;
		IF pn_ds_uf_cidade10 IS NULL
		AND pa_ds_uf_cidade10 IS NOT NULL THEN
			vr_ds_uf_cidade10 := 'null';
		END IF;
		IF pn_ds_uf_cidade10 IS NOT NULL
		AND pa_ds_uf_cidade10 IS NULL THEN
			vr_ds_uf_cidade10 := '"' || RTRIM(pn_ds_uf_cidade10) || '"';
		END IF;
		IF pn_ds_uf_cidade10 IS NOT NULL
		AND pa_ds_uf_cidade10 IS NOT NULL THEN
			IF pa_ds_uf_cidade10 <> pn_ds_uf_cidade10 THEN
				vr_ds_uf_cidade10 := '"' || RTRIM(pn_ds_uf_cidade10) || '"';
			ELSE
				vr_ds_uf_cidade10 := '"' || RTRIM(pa_ds_uf_cidade10) || '"';
			END IF;
		END IF;
		IF pn_nu_telefone_111 IS NULL
		AND pa_nu_telefone_111 IS NULL THEN
			vr_nu_telefone_111 := 'null';
		END IF;
		IF pn_nu_telefone_111 IS NULL
		AND pa_nu_telefone_111 IS NOT NULL THEN
			vr_nu_telefone_111 := 'null';
		END IF;
		IF pn_nu_telefone_111 IS NOT NULL
		AND pa_nu_telefone_111 IS NULL THEN
			vr_nu_telefone_111 := '"' || RTRIM(pn_nu_telefone_111) || '"';
		END IF;
		IF pn_nu_telefone_111 IS NOT NULL
		AND pa_nu_telefone_111 IS NOT NULL THEN
			IF pa_nu_telefone_111 <> pn_nu_telefone_111 THEN
				vr_nu_telefone_111 := '"' || RTRIM(pn_nu_telefone_111) || '"';
			ELSE
				vr_nu_telefone_111 := '"' || RTRIM(pa_nu_telefone_111) || '"';
			END IF;
		END IF;
		IF pn_nu_telefone_212 IS NULL
		AND pa_nu_telefone_212 IS NULL THEN
			vr_nu_telefone_212 := 'null';
		END IF;
		IF pn_nu_telefone_212 IS NULL
		AND pa_nu_telefone_212 IS NOT NULL THEN
			vr_nu_telefone_212 := 'null';
		END IF;
		IF pn_nu_telefone_212 IS NOT NULL
		AND pa_nu_telefone_212 IS NULL THEN
			vr_nu_telefone_212 := '"' || RTRIM(pn_nu_telefone_212) || '"';
		END IF;
		IF pn_nu_telefone_212 IS NOT NULL
		AND pa_nu_telefone_212 IS NOT NULL THEN
			IF pa_nu_telefone_212 <> pn_nu_telefone_212 THEN
				vr_nu_telefone_212 := '"' || RTRIM(pn_nu_telefone_212) || '"';
			ELSE
				vr_nu_telefone_212 := '"' || RTRIM(pa_nu_telefone_212) || '"';
			END IF;
		END IF;
		IF pn_nu_fax13 IS NULL
		AND pa_nu_fax13 IS NULL THEN
			vr_nu_fax13 := 'null';
		END IF;
		IF pn_nu_fax13 IS NULL
		AND pa_nu_fax13 IS NOT NULL THEN
			vr_nu_fax13 := 'null';
		END IF;
		IF pn_nu_fax13 IS NOT NULL
		AND pa_nu_fax13 IS NULL THEN
			vr_nu_fax13 := '"' || RTRIM(pn_nu_fax13) || '"';
		END IF;
		IF pn_nu_fax13 IS NOT NULL
		AND pa_nu_fax13 IS NOT NULL THEN
			IF pa_nu_fax13 <> pn_nu_fax13 THEN
				vr_nu_fax13 := '"' || RTRIM(pn_nu_fax13) || '"';
			ELSE
				vr_nu_fax13 := '"' || RTRIM(pa_nu_fax13) || '"';
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
		IF pn_ds_pagina_web15 IS NULL
		AND pa_ds_pagina_web15 IS NULL THEN
			vr_ds_pagina_web15 := 'null';
		END IF;
		IF pn_ds_pagina_web15 IS NULL
		AND pa_ds_pagina_web15 IS NOT NULL THEN
			vr_ds_pagina_web15 := 'null';
		END IF;
		IF pn_ds_pagina_web15 IS NOT NULL
		AND pa_ds_pagina_web15 IS NULL THEN
			vr_ds_pagina_web15 := '"' || RTRIM(pn_ds_pagina_web15) || '"';
		END IF;
		IF pn_ds_pagina_web15 IS NOT NULL
		AND pa_ds_pagina_web15 IS NOT NULL THEN
			IF pa_ds_pagina_web15 <> pn_ds_pagina_web15 THEN
				vr_ds_pagina_web15 := '"' || RTRIM(pn_ds_pagina_web15) || '"';
			ELSE
				vr_ds_pagina_web15 := '"' || RTRIM(pa_ds_pagina_web15) || '"';
			END IF;
		END IF;
		IF pn_ds_ato16 IS NULL
		AND pa_ds_ato16 IS NULL THEN
			vr_ds_ato16 := 'null';
		END IF;
		IF pn_ds_ato16 IS NULL
		AND pa_ds_ato16 IS NOT NULL THEN
			vr_ds_ato16 := 'null';
		END IF;
		IF pn_ds_ato16 IS NOT NULL
		AND pa_ds_ato16 IS NULL THEN
			vr_ds_ato16 := '"' || RTRIM(pn_ds_ato16) || '"';
		END IF;
		IF pn_ds_ato16 IS NOT NULL
		AND pa_ds_ato16 IS NOT NULL THEN
			IF pa_ds_ato16 <> pn_ds_ato16 THEN
				vr_ds_ato16 := '"' || RTRIM(pn_ds_ato16) || '"';
			ELSE
				vr_ds_ato16 := '"' || RTRIM(pa_ds_ato16) || '"';
			END IF;
		END IF;
		IF pn_ds_numero17 IS NULL
		AND pa_ds_numero17 IS NULL THEN
			vr_ds_numero17 := 'null';
		END IF;
		IF pn_ds_numero17 IS NULL
		AND pa_ds_numero17 IS NOT NULL THEN
			vr_ds_numero17 := 'null';
		END IF;
		IF pn_ds_numero17 IS NOT NULL
		AND pa_ds_numero17 IS NULL THEN
			vr_ds_numero17 := '"' || RTRIM(pn_ds_numero17) || '"';
		END IF;
		IF pn_ds_numero17 IS NOT NULL
		AND pa_ds_numero17 IS NOT NULL THEN
			IF pa_ds_numero17 <> pn_ds_numero17 THEN
				vr_ds_numero17 := '"' || RTRIM(pn_ds_numero17) || '"';
			ELSE
				vr_ds_numero17 := '"' || RTRIM(pa_ds_numero17) || '"';
			END IF;
		END IF;
		IF pn_dt_data18 IS NULL
		AND pa_dt_data18 IS NULL THEN
			vr_dt_data18 := 'null';
		END IF;
		IF pn_dt_data18 IS NULL
		AND pa_dt_data18 IS NOT NULL THEN
			vr_dt_data18 := 'null';
		END IF;
		IF pn_dt_data18 IS NOT NULL
		AND pa_dt_data18 IS NULL THEN
			vr_dt_data18 := '"' || pn_dt_data18 || '"';
		END IF;
		IF pn_dt_data18 IS NOT NULL
		AND pa_dt_data18 IS NOT NULL THEN
			IF pa_dt_data18 <> pn_dt_data18 THEN
				vr_dt_data18 := '"' || pn_dt_data18 || '"';
			ELSE
				vr_dt_data18 := '"' || pa_dt_data18 || '"';
			END IF;
		END IF;
		IF pn_ds_orgao19 IS NULL
		AND pa_ds_orgao19 IS NULL THEN
			vr_ds_orgao19 := 'null';
		END IF;
		IF pn_ds_orgao19 IS NULL
		AND pa_ds_orgao19 IS NOT NULL THEN
			vr_ds_orgao19 := 'null';
		END IF;
		IF pn_ds_orgao19 IS NOT NULL
		AND pa_ds_orgao19 IS NULL THEN
			vr_ds_orgao19 := '"' || RTRIM(pn_ds_orgao19) || '"';
		END IF;
		IF pn_ds_orgao19 IS NOT NULL
		AND pa_ds_orgao19 IS NOT NULL THEN
			IF pa_ds_orgao19 <> pn_ds_orgao19 THEN
				vr_ds_orgao19 := '"' || RTRIM(pn_ds_orgao19) || '"';
			ELSE
				vr_ds_orgao19 := '"' || RTRIM(pa_ds_orgao19) || '"';
			END IF;
		END IF;
		IF pn_ds_grade_curr20 IS NULL
		AND pa_ds_grade_curr20 IS NULL THEN
			vr_ds_grade_curr20 := 'null';
		END IF;
		IF pn_ds_grade_curr20 IS NULL
		AND pa_ds_grade_curr20 IS NOT NULL THEN
			vr_ds_grade_curr20 := 'null';
		END IF;
		IF pn_ds_grade_curr20 IS NOT NULL
		AND pa_ds_grade_curr20 IS NULL THEN
			vr_ds_grade_curr20 := '"' || RTRIM(pn_ds_grade_curr20) || '"';
		END IF;
		IF pn_ds_grade_curr20 IS NOT NULL
		AND pa_ds_grade_curr20 IS NOT NULL THEN
			IF pa_ds_grade_curr20 <> pn_ds_grade_curr20 THEN
				vr_ds_grade_curr20 := '"' || RTRIM(pn_ds_grade_curr20) || '"';
			ELSE
				vr_ds_grade_curr20 := '"' || RTRIM(pa_ds_grade_curr20) || '"';
			END IF;
		END IF;
		IF pn_nu_cgc_escola21 IS NULL
		AND pa_nu_cgc_escola21 IS NULL THEN
			vr_nu_cgc_escola21 := 'null';
		END IF;
		IF pn_nu_cgc_escola21 IS NULL
		AND pa_nu_cgc_escola21 IS NOT NULL THEN
			vr_nu_cgc_escola21 := 'null';
		END IF;
		IF pn_nu_cgc_escola21 IS NOT NULL
		AND pa_nu_cgc_escola21 IS NULL THEN
			vr_nu_cgc_escola21 := '"' || RTRIM(pn_nu_cgc_escola21) || '"';
		END IF;
		IF pn_nu_cgc_escola21 IS NOT NULL
		AND pa_nu_cgc_escola21 IS NOT NULL THEN
			IF pa_nu_cgc_escola21 <> pn_nu_cgc_escola21 THEN
				vr_nu_cgc_escola21 := '"' || RTRIM(pn_nu_cgc_escola21) || '"';
			ELSE
				vr_nu_cgc_escola21 := '"' || RTRIM(pa_nu_cgc_escola21) || '"';
			END IF;
		END IF;
		IF pn_nu_inscr_esco22 IS NULL
		AND pa_nu_inscr_esco22 IS NULL THEN
			vr_nu_inscr_esco22 := 'null';
		END IF;
		IF pn_nu_inscr_esco22 IS NULL
		AND pa_nu_inscr_esco22 IS NOT NULL THEN
			vr_nu_inscr_esco22 := 'null';
		END IF;
		IF pn_nu_inscr_esco22 IS NOT NULL
		AND pa_nu_inscr_esco22 IS NULL THEN
			vr_nu_inscr_esco22 := '"' || RTRIM(pn_nu_inscr_esco22) || '"';
		END IF;
		IF pn_nu_inscr_esco22 IS NOT NULL
		AND pa_nu_inscr_esco22 IS NOT NULL THEN
			IF pa_nu_inscr_esco22 <> pn_nu_inscr_esco22 THEN
				vr_nu_inscr_esco22 := '"' || RTRIM(pn_nu_inscr_esco22) || '"';
			ELSE
				vr_nu_inscr_esco22 := '"' || RTRIM(pa_nu_inscr_esco22) || '"';
			END IF;
		END IF;
		IF pn_ds_diretor23 IS NULL
		AND pa_ds_diretor23 IS NULL THEN
			vr_ds_diretor23 := 'null';
		END IF;
		IF pn_ds_diretor23 IS NULL
		AND pa_ds_diretor23 IS NOT NULL THEN
			vr_ds_diretor23 := 'null';
		END IF;
		IF pn_ds_diretor23 IS NOT NULL
		AND pa_ds_diretor23 IS NULL THEN
			vr_ds_diretor23 := '"' || RTRIM(pn_ds_diretor23) || '"';
		END IF;
		IF pn_ds_diretor23 IS NOT NULL
		AND pa_ds_diretor23 IS NOT NULL THEN
			IF pa_ds_diretor23 <> pn_ds_diretor23 THEN
				vr_ds_diretor23 := '"' || RTRIM(pn_ds_diretor23) || '"';
			ELSE
				vr_ds_diretor23 := '"' || RTRIM(pa_ds_diretor23) || '"';
			END IF;
		END IF;
		IF pn_ds_secretario24 IS NULL
		AND pa_ds_secretario24 IS NULL THEN
			vr_ds_secretario24 := 'null';
		END IF;
		IF pn_ds_secretario24 IS NULL
		AND pa_ds_secretario24 IS NOT NULL THEN
			vr_ds_secretario24 := 'null';
		END IF;
		IF pn_ds_secretario24 IS NOT NULL
		AND pa_ds_secretario24 IS NULL THEN
			vr_ds_secretario24 := '"' || RTRIM(pn_ds_secretario24) || '"';
		END IF;
		IF pn_ds_secretario24 IS NOT NULL
		AND pa_ds_secretario24 IS NOT NULL THEN
			IF pa_ds_secretario24 <> pn_ds_secretario24 THEN
				vr_ds_secretario24 := '"' || RTRIM(pn_ds_secretario24) || '"';
			ELSE
				vr_ds_secretario24 := '"' || RTRIM(pa_ds_secretario24) || '"';
			END IF;
		END IF;
		IF pn_tp_historico25 IS NULL
		AND pa_tp_historico25 IS NULL THEN
			vr_tp_historico25 := 'null';
		END IF;
		IF pn_tp_historico25 IS NULL
		AND pa_tp_historico25 IS NOT NULL THEN
			vr_tp_historico25 := 'null';
		END IF;
		IF pn_tp_historico25 IS NOT NULL
		AND pa_tp_historico25 IS NULL THEN
			vr_tp_historico25 := '"' || RTRIM(pn_tp_historico25) || '"';
		END IF;
		IF pn_tp_historico25 IS NOT NULL
		AND pa_tp_historico25 IS NOT NULL THEN
			IF pa_tp_historico25 <> pn_tp_historico25 THEN
				vr_tp_historico25 := '"' || RTRIM(pn_tp_historico25) || '"';
			ELSE
				vr_tp_historico25 := '"' || RTRIM(pa_tp_historico25) || '"';
			END IF;
		END IF;
		IF pn_fo_simbolo26 IS NULL THEN
			vr_fo_simbolo26 := NULL;
		ELSE
			vr_fo_simbolo26 := ':vblob1';
		END IF;
		v_blob1 := pn_fo_simbolo26;
		IF pn_ds_gre27 IS NULL
		AND pa_ds_gre27 IS NULL THEN
			vr_ds_gre27 := 'null';
		END IF;
		IF pn_ds_gre27 IS NULL
		AND pa_ds_gre27 IS NOT NULL THEN
			vr_ds_gre27 := 'null';
		END IF;
		IF pn_ds_gre27 IS NOT NULL
		AND pa_ds_gre27 IS NULL THEN
			vr_ds_gre27 := '"' || RTRIM(pn_ds_gre27) || '"';
		END IF;
		IF pn_ds_gre27 IS NOT NULL
		AND pa_ds_gre27 IS NOT NULL THEN
			IF pa_ds_gre27 <> pn_ds_gre27 THEN
				vr_ds_gre27 := '"' || RTRIM(pn_ds_gre27) || '"';
			ELSE
				vr_ds_gre27 := '"' || RTRIM(pa_ds_gre27) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_unidade set co_unidade = ' || RTRIM(vr_co_unidade00) || '  , ds_unidade = ' || RTRIM(vr_ds_unidade01) || '  , co_seq_cidade = ' || RTRIM(vr_co_seq_cidade02) || '  , tp_escola = ' || RTRIM(vr_tp_escola03) || '  , ds_nome_relatorio = ' || RTRIM(vr_ds_nome_relat04) || '  , ds_vinheta = ' || RTRIM(vr_ds_vinheta05);
		v_sql2 := '  , ds_endereco = ' || RTRIM(vr_ds_endereco06) || '  , ds_bairro = ' || RTRIM(vr_ds_bairro07) || '  , nu_cep = ' || RTRIM(vr_nu_cep08) || '  , ds_cidade = ' || RTRIM(vr_ds_cidade09) || '  , ds_uf_cidade = ' || RTRIM(vr_ds_uf_cidade10) || '  , nu_telefone_1 = ' || RTRIM(vr_nu_telefone_111) || '  , nu_telefone_2 = ' || RTRIM(vr_nu_telefone_212) || '  , nu_fax = ' || RTRIM(vr_nu_fax13);
		v_sql3 := '  , ds_e_mail = ' || RTRIM(vr_ds_e_mail14) || '  , ds_pagina_web = ' || RTRIM(vr_ds_pagina_web15) || '  , ds_ato = ' || RTRIM(vr_ds_ato16) || '  , ds_numero = ' || RTRIM(vr_ds_numero17) || '  , dt_data = ' || RTRIM(vr_dt_data18) || '  , ds_orgao = ' || RTRIM(vr_ds_orgao19) || '  , DS_GRADE_CURRICULAR = ' || RTRIM(vr_ds_grade_curr20);
		v_sql4 := '  , nu_cgc_escola = ' || RTRIM(vr_nu_cgc_escola21) || '  , NU_INSCRICAO_ESCOLA = ' || RTRIM(vr_nu_inscr_esco22) || '  , ds_diretor = ' || RTRIM(vr_ds_diretor23);
		v_sql5 := '  , ds_secretario = ' || RTRIM(vr_ds_secretario24) || '  , tp_historico = ' || RTRIM(vr_tp_historico25) || '  , fo_simbolo = ' || RTRIM(vr_fo_simbolo26) || '  , ds_gre = ' || RTRIM(vr_ds_gre27);
		v_sql6 := ' where co_unidade = ' || RTRIM(vr_co_unidade00) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5 || v_sql6;
	END IF;
	IF p_op = 'del' THEN
		v_uni := pa_co_unidade00;
	ELSE
		v_uni := pn_co_unidade00;
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
		       's_unidade',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       v_blob1,
		       NULL);
	END IF;
END pr_s_unidade178;
/

