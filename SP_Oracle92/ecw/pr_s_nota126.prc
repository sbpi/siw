CREATE OR REPLACE PROCEDURE pr_s_nota126(
	P_OP_IN                CHAR,
	PA_co_unidade00_IN     s_nota.co_unidade%TYPE,
	PA_ano_sem01_IN        s_nota.ano_sem%TYPE,
	PA_co_turma02_IN       s_nota.co_turma%TYPE,
	PA_co_aluno03_IN       s_nota.co_aluno%TYPE,
	PA_nu_aulas_dada04_IN  s_nota.nu_aulas_dadas_b1%TYPE,
	PA_co_curso05_IN       s_nota.co_curso%TYPE,
	PA_co_seq_serie06_IN   s_nota.co_seq_serie%TYPE,
	PA_nu_nota_b107_IN     s_nota.nu_nota_b1%TYPE,
	PA_co_disciplina08_IN  s_nota.co_disciplina%TYPE,
	PA_nu_nota_recup09_IN  s_nota.nu_nota_recup_1%TYPE,
	PA_nu_faltas_b110_IN   s_nota.nu_faltas_b1%TYPE,
	PA_nu_aulas_dada11_IN  s_nota.nu_aulas_dadas_b2%TYPE,
	PA_nu_nota_b212_IN     s_nota.nu_nota_b2%TYPE,
	PA_nu_nota_recup13_IN  s_nota.nu_nota_recup_2%TYPE,
	PA_nu_faltas_b214_IN   s_nota.nu_faltas_b2%TYPE,
	PA_nu_aulas_dada15_IN  s_nota.nu_aulas_dadas_b3%TYPE,
	PA_nu_nota_b316_IN     s_nota.nu_nota_b3%TYPE,
	PA_nu_nota_recup17_IN  s_nota.nu_nota_recup_3%TYPE,
	PA_nu_faltas_b318_IN   s_nota.nu_faltas_b3%TYPE,
	PA_nu_aulas_dada19_IN  s_nota.nu_aulas_dadas_b4%TYPE,
	PA_nu_nota_b420_IN     s_nota.nu_nota_b4%TYPE,
	PA_nu_nota_recup21_IN  s_nota.nu_nota_recup_4%TYPE,
	PA_nu_faltas_b422_IN   s_nota.nu_faltas_b4%TYPE,
	PA_nu_media_anua23_IN  s_nota.nu_media_anual%TYPE,
	PA_nu_recup_espe24_IN  s_nota.nu_recup_especial%TYPE,
	PA_nu_recup_fina25_IN  s_nota.nu_recup_final%TYPE,
	PA_nu_media_fina26_IN  s_nota.nu_media_final%TYPE,
	PA_nu_media_s127_IN    s_nota.nu_media_s1%TYPE,
	PA_nu_media_s228_IN    s_nota.nu_media_s2%TYPE,
	PA_nu_media_apos29_IN  s_nota.nu_media_apos_s1%TYPE,
	PA_nu_media_apos30_IN  s_nota.nu_media_apos_s2%TYPE,
	PA_nu_maxpontos_31_IN  s_nota.nu_maxpontos_b1%TYPE,
	PA_nu_maxpontos_32_IN  s_nota.nu_maxpontos_b2%TYPE,
	PA_nu_maxpontos_33_IN  s_nota.nu_maxpontos_b3%TYPE,
	PA_nu_maxpontos_34_IN  s_nota.nu_maxpontos_b4%TYPE,
	PA_nu_nota_sm135_IN    s_nota.nu_nota_sm1%TYPE,
	PA_nu_nota_sm236_IN    s_nota.nu_nota_sm2%TYPE,
	PA_nu_nota_sm337_IN    s_nota.nu_nota_sm3%TYPE,
	PA_nu_nota_sm438_IN    s_nota.nu_nota_sm4%TYPE,
	PA_nu_nota_sm539_IN    s_nota.nu_nota_sm5%TYPE,
	PA_nu_nota_sm640_IN    s_nota.nu_nota_sm6%TYPE,
	PA_nu_nota_sm741_IN    s_nota.nu_nota_sm7%TYPE,
	PA_nu_nota_sm842_IN    s_nota.nu_nota_sm8%TYPE,
	PA_nu_nota_sm943_IN    s_nota.nu_nota_sm9%TYPE,
	PA_st_conselho44_IN    s_nota.st_conselho%TYPE,
	PA_nu_ordem_145_IN     s_nota.nu_ordem_1%TYPE,
	PA_nu_ordem_246_IN     s_nota.nu_ordem_2%TYPE,
	PN_co_unidade00_IN     s_nota.co_unidade%TYPE,
	PN_ano_sem01_IN        s_nota.ano_sem%TYPE,
	PN_co_turma02_IN       s_nota.co_turma%TYPE,
	PN_co_aluno03_IN       s_nota.co_aluno%TYPE,
	PN_nu_aulas_dada04_IN  s_nota.nu_aulas_dadas_b1%TYPE,
	PN_co_curso05_IN       s_nota.co_curso%TYPE,
	PN_co_seq_serie06_IN   s_nota.co_seq_serie%TYPE,
	PN_nu_nota_b107_IN     s_nota.nu_nota_b1%TYPE,
	PN_co_disciplina08_IN  s_nota.co_disciplina%TYPE,
	PN_nu_nota_recup09_IN  s_nota.nu_nota_recup_1%TYPE,
	PN_nu_faltas_b110_IN   s_nota.nu_faltas_b1%TYPE,
	PN_nu_aulas_dada11_IN  s_nota.nu_aulas_dadas_b2%TYPE,
	PN_nu_nota_b212_IN     s_nota.nu_nota_b2%TYPE,
	PN_nu_nota_recup13_IN  s_nota.nu_nota_recup_2%TYPE,
	PN_nu_faltas_b214_IN   s_nota.nu_faltas_b2%TYPE,
	PN_nu_aulas_dada15_IN  s_nota.nu_aulas_dadas_b3%TYPE,
	PN_nu_nota_b316_IN     s_nota.nu_nota_b3%TYPE,
	PN_nu_nota_recup17_IN  s_nota.nu_nota_recup_3%TYPE,
	PN_nu_faltas_b318_IN   s_nota.nu_faltas_b3%TYPE,
	PN_nu_aulas_dada19_IN  s_nota.nu_aulas_dadas_b4%TYPE,
	PN_nu_nota_b420_IN     s_nota.nu_nota_b4%TYPE,
	PN_nu_nota_recup21_IN  s_nota.nu_nota_recup_4%TYPE,
	PN_nu_faltas_b422_IN   s_nota.nu_faltas_b4%TYPE,
	PN_nu_media_anua23_IN  s_nota.nu_media_anual%TYPE,
	PN_nu_recup_espe24_IN  s_nota.nu_recup_especial%TYPE,
	PN_nu_recup_fina25_IN  s_nota.nu_recup_final%TYPE,
	PN_nu_media_fina26_IN  s_nota.nu_media_final%TYPE,
	PN_nu_media_s127_IN    s_nota.nu_media_s1%TYPE,
	PN_nu_media_s228_IN    s_nota.nu_media_s2%TYPE,
	PN_nu_media_apos29_IN  s_nota.nu_media_apos_s1%TYPE,
	PN_nu_media_apos30_IN  s_nota.nu_media_apos_s2%TYPE,
	PN_nu_maxpontos_31_IN  s_nota.nu_maxpontos_b1%TYPE,
	PN_nu_maxpontos_32_IN  s_nota.nu_maxpontos_b2%TYPE,
	PN_nu_maxpontos_33_IN  s_nota.nu_maxpontos_b3%TYPE,
	PN_nu_maxpontos_34_IN  s_nota.nu_maxpontos_b4%TYPE,
	PN_nu_nota_sm135_IN    s_nota.nu_nota_sm1%TYPE,
	PN_nu_nota_sm236_IN    s_nota.nu_nota_sm2%TYPE,
	PN_nu_nota_sm337_IN    s_nota.nu_nota_sm3%TYPE,
	PN_nu_nota_sm438_IN    s_nota.nu_nota_sm4%TYPE,
	PN_nu_nota_sm539_IN    s_nota.nu_nota_sm5%TYPE,
	PN_nu_nota_sm640_IN    s_nota.nu_nota_sm6%TYPE,
	PN_nu_nota_sm741_IN    s_nota.nu_nota_sm7%TYPE,
	PN_nu_nota_sm842_IN    s_nota.nu_nota_sm8%TYPE,
	PN_nu_nota_sm943_IN    s_nota.nu_nota_sm9%TYPE,
	PN_st_conselho44_IN    s_nota.st_conselho%TYPE,
	PN_nu_ordem_145_IN     s_nota.nu_ordem_1%TYPE,
	PN_nu_ordem_246_IN     s_nota.nu_ordem_2%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_unidade00     s_nota.co_unidade%TYPE := PA_co_unidade00_IN;
PA_ano_sem01        s_nota.ano_sem%TYPE := PA_ano_sem01_IN;
PA_co_turma02       s_nota.co_turma%TYPE := PA_co_turma02_IN;
PA_co_aluno03       s_nota.co_aluno%TYPE := PA_co_aluno03_IN;
PA_nu_aulas_dada04  s_nota.nu_aulas_dadas_b1%TYPE := PA_nu_aulas_dada04_IN;
PA_co_curso05       s_nota.co_curso%TYPE := PA_co_curso05_IN;
PA_co_seq_serie06   s_nota.co_seq_serie%TYPE := PA_co_seq_serie06_IN;
PA_nu_nota_b107     s_nota.nu_nota_b1%TYPE := PA_nu_nota_b107_IN;
PA_co_disciplina08  s_nota.co_disciplina%TYPE := PA_co_disciplina08_IN;
PA_nu_nota_recup09  s_nota.nu_nota_recup_1%TYPE := PA_nu_nota_recup09_IN;
PA_nu_faltas_b110   s_nota.nu_faltas_b1%TYPE := PA_nu_faltas_b110_IN;
PA_nu_aulas_dada11  s_nota.nu_aulas_dadas_b2%TYPE := PA_nu_aulas_dada11_IN;
PA_nu_nota_b212     s_nota.nu_nota_b2%TYPE := PA_nu_nota_b212_IN;
PA_nu_nota_recup13  s_nota.nu_nota_recup_2%TYPE := PA_nu_nota_recup13_IN;
PA_nu_faltas_b214   s_nota.nu_faltas_b2%TYPE := PA_nu_faltas_b214_IN;
PA_nu_aulas_dada15  s_nota.nu_aulas_dadas_b3%TYPE := PA_nu_aulas_dada15_IN;
PA_nu_nota_b316     s_nota.nu_nota_b3%TYPE := PA_nu_nota_b316_IN;
PA_nu_nota_recup17  s_nota.nu_nota_recup_3%TYPE := PA_nu_nota_recup17_IN;
PA_nu_faltas_b318   s_nota.nu_faltas_b3%TYPE := PA_nu_faltas_b318_IN;
PA_nu_aulas_dada19  s_nota.nu_aulas_dadas_b4%TYPE := PA_nu_aulas_dada19_IN;
PA_nu_nota_b420     s_nota.nu_nota_b4%TYPE := PA_nu_nota_b420_IN;
PA_nu_nota_recup21  s_nota.nu_nota_recup_4%TYPE := PA_nu_nota_recup21_IN;
PA_nu_faltas_b422   s_nota.nu_faltas_b4%TYPE := PA_nu_faltas_b422_IN;
PA_nu_media_anua23  s_nota.nu_media_anual%TYPE := PA_nu_media_anua23_IN;
PA_nu_recup_espe24  s_nota.nu_recup_especial%TYPE := PA_nu_recup_espe24_IN;
PA_nu_recup_fina25  s_nota.nu_recup_final%TYPE := PA_nu_recup_fina25_IN;
PA_nu_media_fina26  s_nota.nu_media_final%TYPE := PA_nu_media_fina26_IN;
PA_nu_media_s127    s_nota.nu_media_s1%TYPE := PA_nu_media_s127_IN;
PA_nu_media_s228    s_nota.nu_media_s2%TYPE := PA_nu_media_s228_IN;
PA_nu_media_apos29  s_nota.nu_media_apos_s1%TYPE := PA_nu_media_apos29_IN;
PA_nu_media_apos30  s_nota.nu_media_apos_s2%TYPE := PA_nu_media_apos30_IN;
PA_nu_maxpontos_31  s_nota.nu_maxpontos_b1%TYPE := PA_nu_maxpontos_31_IN;
PA_nu_maxpontos_32  s_nota.nu_maxpontos_b2%TYPE := PA_nu_maxpontos_32_IN;
PA_nu_maxpontos_33  s_nota.nu_maxpontos_b3%TYPE := PA_nu_maxpontos_33_IN;
PA_nu_maxpontos_34  s_nota.nu_maxpontos_b4%TYPE := PA_nu_maxpontos_34_IN;
PA_nu_nota_sm135    s_nota.nu_nota_sm1%TYPE := PA_nu_nota_sm135_IN;
PA_nu_nota_sm236    s_nota.nu_nota_sm2%TYPE := PA_nu_nota_sm236_IN;
PA_nu_nota_sm337    s_nota.nu_nota_sm3%TYPE := PA_nu_nota_sm337_IN;
PA_nu_nota_sm438    s_nota.nu_nota_sm4%TYPE := PA_nu_nota_sm438_IN;
PA_nu_nota_sm539    s_nota.nu_nota_sm5%TYPE := PA_nu_nota_sm539_IN;
PA_nu_nota_sm640    s_nota.nu_nota_sm6%TYPE := PA_nu_nota_sm640_IN;
PA_nu_nota_sm741    s_nota.nu_nota_sm7%TYPE := PA_nu_nota_sm741_IN;
PA_nu_nota_sm842    s_nota.nu_nota_sm8%TYPE := PA_nu_nota_sm842_IN;
PA_nu_nota_sm943    s_nota.nu_nota_sm9%TYPE := PA_nu_nota_sm943_IN;
PA_st_conselho44    s_nota.st_conselho%TYPE := PA_st_conselho44_IN;
PA_nu_ordem_145     s_nota.nu_ordem_1%TYPE := PA_nu_ordem_145_IN;
PA_nu_ordem_246     s_nota.nu_ordem_2%TYPE := PA_nu_ordem_246_IN;
PN_co_unidade00     s_nota.co_unidade%TYPE := PN_co_unidade00_IN;
PN_ano_sem01        s_nota.ano_sem%TYPE := PN_ano_sem01_IN;
PN_co_turma02       s_nota.co_turma%TYPE := PN_co_turma02_IN;
PN_co_aluno03       s_nota.co_aluno%TYPE := PN_co_aluno03_IN;
PN_nu_aulas_dada04  s_nota.nu_aulas_dadas_b1%TYPE := PN_nu_aulas_dada04_IN;
PN_co_curso05       s_nota.co_curso%TYPE := PN_co_curso05_IN;
PN_co_seq_serie06   s_nota.co_seq_serie%TYPE := PN_co_seq_serie06_IN;
PN_nu_nota_b107     s_nota.nu_nota_b1%TYPE := PN_nu_nota_b107_IN;
PN_co_disciplina08  s_nota.co_disciplina%TYPE := PN_co_disciplina08_IN;
PN_nu_nota_recup09  s_nota.nu_nota_recup_1%TYPE := PN_nu_nota_recup09_IN;
PN_nu_faltas_b110   s_nota.nu_faltas_b1%TYPE := PN_nu_faltas_b110_IN;
PN_nu_aulas_dada11  s_nota.nu_aulas_dadas_b2%TYPE := PN_nu_aulas_dada11_IN;
PN_nu_nota_b212     s_nota.nu_nota_b2%TYPE := PN_nu_nota_b212_IN;
PN_nu_nota_recup13  s_nota.nu_nota_recup_2%TYPE := PN_nu_nota_recup13_IN;
PN_nu_faltas_b214   s_nota.nu_faltas_b2%TYPE := PN_nu_faltas_b214_IN;
PN_nu_aulas_dada15  s_nota.nu_aulas_dadas_b3%TYPE := PN_nu_aulas_dada15_IN;
PN_nu_nota_b316     s_nota.nu_nota_b3%TYPE := PN_nu_nota_b316_IN;
PN_nu_nota_recup17  s_nota.nu_nota_recup_3%TYPE := PN_nu_nota_recup17_IN;
PN_nu_faltas_b318   s_nota.nu_faltas_b3%TYPE := PN_nu_faltas_b318_IN;
PN_nu_aulas_dada19  s_nota.nu_aulas_dadas_b4%TYPE := PN_nu_aulas_dada19_IN;
PN_nu_nota_b420     s_nota.nu_nota_b4%TYPE := PN_nu_nota_b420_IN;
PN_nu_nota_recup21  s_nota.nu_nota_recup_4%TYPE := PN_nu_nota_recup21_IN;
PN_nu_faltas_b422   s_nota.nu_faltas_b4%TYPE := PN_nu_faltas_b422_IN;
PN_nu_media_anua23  s_nota.nu_media_anual%TYPE := PN_nu_media_anua23_IN;
PN_nu_recup_espe24  s_nota.nu_recup_especial%TYPE := PN_nu_recup_espe24_IN;
PN_nu_recup_fina25  s_nota.nu_recup_final%TYPE := PN_nu_recup_fina25_IN;
PN_nu_media_fina26  s_nota.nu_media_final%TYPE := PN_nu_media_fina26_IN;
PN_nu_media_s127    s_nota.nu_media_s1%TYPE := PN_nu_media_s127_IN;
PN_nu_media_s228    s_nota.nu_media_s2%TYPE := PN_nu_media_s228_IN;
PN_nu_media_apos29  s_nota.nu_media_apos_s1%TYPE := PN_nu_media_apos29_IN;
PN_nu_media_apos30  s_nota.nu_media_apos_s2%TYPE := PN_nu_media_apos30_IN;
PN_nu_maxpontos_31  s_nota.nu_maxpontos_b1%TYPE := PN_nu_maxpontos_31_IN;
PN_nu_maxpontos_32  s_nota.nu_maxpontos_b2%TYPE := PN_nu_maxpontos_32_IN;
PN_nu_maxpontos_33  s_nota.nu_maxpontos_b3%TYPE := PN_nu_maxpontos_33_IN;
PN_nu_maxpontos_34  s_nota.nu_maxpontos_b4%TYPE := PN_nu_maxpontos_34_IN;
PN_nu_nota_sm135    s_nota.nu_nota_sm1%TYPE := PN_nu_nota_sm135_IN;
PN_nu_nota_sm236    s_nota.nu_nota_sm2%TYPE := PN_nu_nota_sm236_IN;
PN_nu_nota_sm337    s_nota.nu_nota_sm3%TYPE := PN_nu_nota_sm337_IN;
PN_nu_nota_sm438    s_nota.nu_nota_sm4%TYPE := PN_nu_nota_sm438_IN;
PN_nu_nota_sm539    s_nota.nu_nota_sm5%TYPE := PN_nu_nota_sm539_IN;
PN_nu_nota_sm640    s_nota.nu_nota_sm6%TYPE := PN_nu_nota_sm640_IN;
PN_nu_nota_sm741    s_nota.nu_nota_sm7%TYPE := PN_nu_nota_sm741_IN;
PN_nu_nota_sm842    s_nota.nu_nota_sm8%TYPE := PN_nu_nota_sm842_IN;
PN_nu_nota_sm943    s_nota.nu_nota_sm9%TYPE := PN_nu_nota_sm943_IN;
PN_st_conselho44    s_nota.st_conselho%TYPE := PN_st_conselho44_IN;
PN_nu_ordem_145     s_nota.nu_ordem_1%TYPE := PN_nu_ordem_145_IN;
PN_nu_ordem_246     s_nota.nu_ordem_2%TYPE := PN_nu_ordem_246_IN;
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
vr_co_unidade00     CHAR(10);
vr_ano_sem01        CHAR(10);
vr_co_turma02       CHAR(10);
vr_co_aluno03       CHAR(20);
vr_nu_aulas_dada04  CHAR(10);
vr_co_curso05       CHAR(10);
vr_co_seq_serie06   CHAR(10);
vr_nu_nota_b107     CHAR(20);
vr_co_disciplina08  CHAR(10);
vr_nu_nota_recup09  CHAR(20);
vr_nu_faltas_b110   CHAR(20);
vr_nu_aulas_dada11  CHAR(10);
vr_nu_nota_b212     CHAR(20);
vr_nu_nota_recup13  CHAR(20);
vr_nu_faltas_b214   CHAR(20);
vr_nu_aulas_dada15  CHAR(10);
vr_nu_nota_b316     CHAR(20);
vr_nu_nota_recup17  CHAR(20);
vr_nu_faltas_b318   CHAR(20);
vr_nu_aulas_dada19  CHAR(10);
vr_nu_nota_b420     CHAR(20);
vr_nu_nota_recup21  CHAR(20);
vr_nu_faltas_b422   CHAR(20);
vr_nu_media_anua23  CHAR(10);
vr_nu_recup_espe24  CHAR(20);
vr_nu_recup_fina25  CHAR(20);
vr_nu_media_fina26  CHAR(20);
vr_nu_media_s127    CHAR(20);
vr_nu_media_s228    CHAR(20);
vr_nu_media_apos29  CHAR(20);
vr_nu_media_apos30  CHAR(20);
vr_nu_maxpontos_31  CHAR(10);
vr_nu_maxpontos_32  CHAR(10);
vr_nu_maxpontos_33  CHAR(10);
vr_nu_maxpontos_34  CHAR(10);
vr_nu_nota_sm135    CHAR(20);
vr_nu_nota_sm236    CHAR(20);
vr_nu_nota_sm337    CHAR(20);
vr_nu_nota_sm438    CHAR(20);
vr_nu_nota_sm539    CHAR(20);
vr_nu_nota_sm640    CHAR(20);
vr_nu_nota_sm741    CHAR(20);
vr_nu_nota_sm842    CHAR(20);
vr_nu_nota_sm943    CHAR(20);
vr_st_conselho44    CHAR(20);
vr_nu_ordem_145     CHAR(10);
vr_nu_ordem_246     CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := pn_co_unidade00;
		END IF;
		IF pn_ano_sem01 IS NULL THEN
			vr_ano_sem01 := 'null';
		ELSE
			vr_ano_sem01 := pn_ano_sem01;
		END IF;
		IF pn_co_turma02 IS NULL THEN
			vr_co_turma02 := 'null';
		ELSE
			vr_co_turma02 := pn_co_turma02;
		END IF;
		IF pn_co_aluno03 IS NULL THEN
			vr_co_aluno03 := 'null';
		ELSE
			vr_co_aluno03 := pn_co_aluno03;
		END IF;
		IF pn_nu_aulas_dada04 IS NULL THEN
			vr_nu_aulas_dada04 := 'null';
		ELSE
			vr_nu_aulas_dada04 := pn_nu_aulas_dada04;
		END IF;
		IF pn_co_curso05 IS NULL THEN
			vr_co_curso05 := 'null';
		ELSE
			vr_co_curso05 := pn_co_curso05;
		END IF;
		IF pn_co_seq_serie06 IS NULL THEN
			vr_co_seq_serie06 := 'null';
		ELSE
			vr_co_seq_serie06 := pn_co_seq_serie06;
		END IF;
		IF pn_nu_nota_b107 IS NULL THEN
			vr_nu_nota_b107 := 'null';
		ELSE
			vr_nu_nota_b107 := pn_nu_nota_b107;
		END IF;
		IF pn_co_disciplina08 IS NULL THEN
			vr_co_disciplina08 := 'null';
		ELSE
			vr_co_disciplina08 := pn_co_disciplina08;
		END IF;
		IF pn_nu_nota_recup09 IS NULL THEN
			vr_nu_nota_recup09 := 'null';
		ELSE
			vr_nu_nota_recup09 := pn_nu_nota_recup09;
		END IF;
		IF pn_nu_faltas_b110 IS NULL THEN
			vr_nu_faltas_b110 := 'null';
		ELSE
			vr_nu_faltas_b110 := pn_nu_faltas_b110;
		END IF;
		IF pn_nu_aulas_dada11 IS NULL THEN
			vr_nu_aulas_dada11 := 'null';
		ELSE
			vr_nu_aulas_dada11 := pn_nu_aulas_dada11;
		END IF;
		IF pn_nu_nota_b212 IS NULL THEN
			vr_nu_nota_b212 := 'null';
		ELSE
			vr_nu_nota_b212 := pn_nu_nota_b212;
		END IF;
		IF pn_nu_nota_recup13 IS NULL THEN
			vr_nu_nota_recup13 := 'null';
		ELSE
			vr_nu_nota_recup13 := pn_nu_nota_recup13;
		END IF;
		IF pn_nu_faltas_b214 IS NULL THEN
			vr_nu_faltas_b214 := 'null';
		ELSE
			vr_nu_faltas_b214 := pn_nu_faltas_b214;
		END IF;
		IF pn_nu_aulas_dada15 IS NULL THEN
			vr_nu_aulas_dada15 := 'null';
		ELSE
			vr_nu_aulas_dada15 := pn_nu_aulas_dada15;
		END IF;
		IF pn_nu_nota_b316 IS NULL THEN
			vr_nu_nota_b316 := 'null';
		ELSE
			vr_nu_nota_b316 := pn_nu_nota_b316;
		END IF;
		IF pn_nu_nota_recup17 IS NULL THEN
			vr_nu_nota_recup17 := 'null';
		ELSE
			vr_nu_nota_recup17 := pn_nu_nota_recup17;
		END IF;
		IF pn_nu_faltas_b318 IS NULL THEN
			vr_nu_faltas_b318 := 'null';
		ELSE
			vr_nu_faltas_b318 := pn_nu_faltas_b318;
		END IF;
		IF pn_nu_aulas_dada19 IS NULL THEN
			vr_nu_aulas_dada19 := 'null';
		ELSE
			vr_nu_aulas_dada19 := pn_nu_aulas_dada19;
		END IF;
		IF pn_nu_nota_b420 IS NULL THEN
			vr_nu_nota_b420 := 'null';
		ELSE
			vr_nu_nota_b420 := pn_nu_nota_b420;
		END IF;
		IF pn_nu_nota_recup21 IS NULL THEN
			vr_nu_nota_recup21 := 'null';
		ELSE
			vr_nu_nota_recup21 := pn_nu_nota_recup21;
		END IF;
		IF pn_nu_faltas_b422 IS NULL THEN
			vr_nu_faltas_b422 := 'null';
		ELSE
			vr_nu_faltas_b422 := pn_nu_faltas_b422;
		END IF;
		IF pn_nu_media_anua23 IS NULL THEN
			vr_nu_media_anua23 := 'null';
		ELSE
			vr_nu_media_anua23 := pn_nu_media_anua23;
		END IF;
		IF pn_nu_recup_espe24 IS NULL THEN
			vr_nu_recup_espe24 := 'null';
		ELSE
			vr_nu_recup_espe24 := pn_nu_recup_espe24;
		END IF;
		IF pn_nu_recup_fina25 IS NULL THEN
			vr_nu_recup_fina25 := 'null';
		ELSE
			vr_nu_recup_fina25 := pn_nu_recup_fina25;
		END IF;
		IF pn_nu_media_fina26 IS NULL THEN
			vr_nu_media_fina26 := 'null';
		ELSE
			vr_nu_media_fina26 := pn_nu_media_fina26;
		END IF;
		IF pn_nu_media_s127 IS NULL THEN
			vr_nu_media_s127 := 'null';
		ELSE
			vr_nu_media_s127 := pn_nu_media_s127;
		END IF;
		IF pn_nu_media_s228 IS NULL THEN
			vr_nu_media_s228 := 'null';
		ELSE
			vr_nu_media_s228 := pn_nu_media_s228;
		END IF;
		IF pn_nu_media_apos29 IS NULL THEN
			vr_nu_media_apos29 := 'null';
		ELSE
			vr_nu_media_apos29 := pn_nu_media_apos29;
		END IF;
		IF pn_nu_media_apos30 IS NULL THEN
			vr_nu_media_apos30 := 'null';
		ELSE
			vr_nu_media_apos30 := pn_nu_media_apos30;
		END IF;
		IF pn_nu_maxpontos_31 IS NULL THEN
			vr_nu_maxpontos_31 := 'null';
		ELSE
			vr_nu_maxpontos_31 := pn_nu_maxpontos_31;
		END IF;
		IF pn_nu_maxpontos_32 IS NULL THEN
			vr_nu_maxpontos_32 := 'null';
		ELSE
			vr_nu_maxpontos_32 := pn_nu_maxpontos_32;
		END IF;
		IF pn_nu_maxpontos_33 IS NULL THEN
			vr_nu_maxpontos_33 := 'null';
		ELSE
			vr_nu_maxpontos_33 := pn_nu_maxpontos_33;
		END IF;
		IF pn_nu_maxpontos_34 IS NULL THEN
			vr_nu_maxpontos_34 := 'null';
		ELSE
			vr_nu_maxpontos_34 := pn_nu_maxpontos_34;
		END IF;
		IF pn_nu_nota_sm135 IS NULL THEN
			vr_nu_nota_sm135 := 'null';
		ELSE
			vr_nu_nota_sm135 := pn_nu_nota_sm135;
		END IF;
		IF pn_nu_nota_sm236 IS NULL THEN
			vr_nu_nota_sm236 := 'null';
		ELSE
			vr_nu_nota_sm236 := pn_nu_nota_sm236;
		END IF;
		IF pn_nu_nota_sm337 IS NULL THEN
			vr_nu_nota_sm337 := 'null';
		ELSE
			vr_nu_nota_sm337 := pn_nu_nota_sm337;
		END IF;
		IF pn_nu_nota_sm438 IS NULL THEN
			vr_nu_nota_sm438 := 'null';
		ELSE
			vr_nu_nota_sm438 := pn_nu_nota_sm438;
		END IF;
		IF pn_nu_nota_sm539 IS NULL THEN
			vr_nu_nota_sm539 := 'null';
		ELSE
			vr_nu_nota_sm539 := pn_nu_nota_sm539;
		END IF;
		IF pn_nu_nota_sm640 IS NULL THEN
			vr_nu_nota_sm640 := 'null';
		ELSE
			vr_nu_nota_sm640 := pn_nu_nota_sm640;
		END IF;
		IF pn_nu_nota_sm741 IS NULL THEN
			vr_nu_nota_sm741 := 'null';
		ELSE
			vr_nu_nota_sm741 := pn_nu_nota_sm741;
		END IF;
		IF pn_nu_nota_sm842 IS NULL THEN
			vr_nu_nota_sm842 := 'null';
		ELSE
			vr_nu_nota_sm842 := pn_nu_nota_sm842;
		END IF;
		IF pn_nu_nota_sm943 IS NULL THEN
			vr_nu_nota_sm943 := 'null';
		ELSE
			vr_nu_nota_sm943 := pn_nu_nota_sm943;
		END IF;
		IF pn_st_conselho44 IS NULL THEN
			vr_st_conselho44 := 'null';
		ELSE
			vr_st_conselho44 := pn_st_conselho44;
		END IF;
		IF pn_nu_ordem_145 IS NULL THEN
			vr_nu_ordem_145 := 'null';
		ELSE
			vr_nu_ordem_145 := pn_nu_ordem_145;
		END IF;
		IF pn_nu_ordem_246 IS NULL THEN
			vr_nu_ordem_246 := 'null';
		ELSE
			vr_nu_ordem_246 := pn_nu_ordem_246;
		END IF;
		v_sql1 := 'insert into s_nota(co_unidade, ano_sem, co_turma, co_aluno, nu_aulas_dadas_b1, co_curso, co_seq_serie, nu_nota_b1, co_disciplina, ' || 'NU_NOTA_RECUPERACAO_1, nu_faltas_b1, nu_aulas_dadas_b2, nu_nota_b2, NU_NOTA_RECUPERACAO_2, nu_faltas_b2, nu_aulas_dadas_b3, nu_nota_b3, NU_NOTA_RECUPERACAO_3, nu_faltas_b3, nu_aulas_dadas_b4, ' || 'nu_nota_b4, NU_NOTA_RECUPERACAO_4, nu_faltas_b4, nu_media_anual, NU_RECUPERACAO_ESPECIAL, NU_RECUPERACAO_FINAL, nu_media_final, nu_media_s1, nu_media_s2, nu_media_apos_s1, ' || 'nu_media_apos_s2, nu_maxpontos_b1, nu_maxpontos_b2, nu_maxpontos_b3, nu_maxpontos_b4, nu_nota_sm1, nu_nota_sm2, nu_nota_sm3, nu_nota_sm4, nu_nota_sm5, ' || 'nu_nota_sm6, nu_nota_sm7, nu_nota_sm8, nu_nota_sm9, st_conselho, nu_ordem_1, nu_ordem_2) values (';
		v_sql2 := '"' || RTRIM(vr_co_unidade00) || '"' || ',' || '"' || RTRIM(vr_ano_sem01) || '"' || ',' || RTRIM(vr_co_turma02) || ',' || '"' || RTRIM(vr_co_aluno03) || '"' || ',' || '"' || RTRIM(vr_nu_aulas_dada04) || '"' || ',' || RTRIM(vr_co_curso05) || ',' || RTRIM(vr_co_seq_serie06) || ',';
		v_sql3 := '"' || RTRIM(vr_nu_nota_b107) || '"' || ',' || '"' || RTRIM(vr_co_disciplina08) || '"' || ',' || '"' || RTRIM(vr_nu_nota_recup09) || '"' || ',' || '"' || RTRIM(vr_nu_faltas_b110) || '"' || ',' || '"' || RTRIM(vr_nu_aulas_dada11) || '"' || ',' || '"' || RTRIM(vr_nu_nota_b212) || '"' || ',' || '"' || RTRIM(vr_nu_nota_recup13) || '"' || ',';
		v_sql4 := '"' || RTRIM(vr_nu_faltas_b214) || '"' || ',' || '"' || RTRIM(vr_nu_aulas_dada15) || '"' || ',' || '"' || RTRIM(vr_nu_nota_b316) || '"' || ',' || '"' || RTRIM(vr_nu_nota_recup17) || '"' || ',' || '"' || RTRIM(vr_nu_faltas_b318) || '"' || ',' || '"' || RTRIM(vr_nu_aulas_dada19) || '"' || ',' || '"' || RTRIM(vr_nu_nota_b420) || '"' || ',';
		v_sql5 := '"' || RTRIM(vr_nu_nota_recup21) || '"' || ',' || '"' || RTRIM(vr_nu_faltas_b422) || '"' || ',' || '"' || RTRIM(vr_nu_media_anua23) || '"' || ',' || '"' || RTRIM(vr_nu_recup_espe24) || '"' || ',' || '"' || RTRIM(vr_nu_recup_fina25) || '"' || ',' || '"' || RTRIM(vr_nu_media_fina26) || '"' || ',' || '"' || RTRIM(vr_nu_media_s127) || '"' || ',';
		v_sql6 := '"' || RTRIM(vr_nu_media_s228) || '"' || ',' || '"' || RTRIM(vr_nu_media_apos29) || '"' || ',' || '"' || RTRIM(vr_nu_media_apos30) || '"' || ',' || '"' || RTRIM(vr_nu_maxpontos_31) || '"' || ',' || '"' || RTRIM(vr_nu_maxpontos_32) || '"' || ',' || '"' || RTRIM(vr_nu_maxpontos_33) || '"' || ',' || '"' || RTRIM(vr_nu_maxpontos_34) || '"' || ',';
		v_sql7 := '"' || RTRIM(vr_nu_nota_sm135) || '"' || ',' || '"' || RTRIM(vr_nu_nota_sm236) || '"' || ',' || '"' || RTRIM(vr_nu_nota_sm337) || '"' || ',' || '"' || RTRIM(vr_nu_nota_sm438) || '"' || ',' || '"' || RTRIM(vr_nu_nota_sm539) || '"' || ',' || '"' || RTRIM(vr_nu_nota_sm640) || '"' || ',' || '"' || RTRIM(vr_nu_nota_sm741) || '"' || ',';
		v_sql8 := '"' || RTRIM(vr_nu_nota_sm842) || '"' || ',' || '"' || RTRIM(vr_nu_nota_sm943) || '"' || ',' || '"' || RTRIM(vr_st_conselho44) || '"' || ',' || RTRIM(vr_nu_ordem_145) || ',' || RTRIM(vr_nu_ordem_246) || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5 || v_sql6 || v_sql7 || v_sql8;
	ELSIF p_op = 'del' THEN
		IF pa_co_unidade00 IS NULL THEN
			vr_co_unidade00 := 'null';
		ELSE
			vr_co_unidade00 := '"' || RTRIM(pa_co_unidade00) || '"';
		END IF;
		IF pa_ano_sem01 IS NULL THEN
			vr_ano_sem01 := 'null';
		ELSE
			vr_ano_sem01 := '"' || RTRIM(pa_ano_sem01) || '"';
		END IF;
		IF pa_co_turma02 IS NULL THEN
			vr_co_turma02 := 'null';
		ELSE
			vr_co_turma02 := pa_co_turma02;
		END IF;
		IF pa_co_aluno03 IS NULL THEN
			vr_co_aluno03 := 'null';
		ELSE
			vr_co_aluno03 := '"' || RTRIM(pa_co_aluno03) || '"';
		END IF;
		IF pa_co_curso05 IS NULL THEN
			vr_co_curso05 := 'null';
		ELSE
			vr_co_curso05 := pa_co_curso05;
		END IF;
		IF pa_co_seq_serie06 IS NULL THEN
			vr_co_seq_serie06 := 'null';
		ELSE
			vr_co_seq_serie06 := pa_co_seq_serie06;
		END IF;
		IF pa_co_disciplina08 IS NULL THEN
			vr_co_disciplina08 := 'null';
		ELSE
			vr_co_disciplina08 := '"' || RTRIM(pa_co_disciplina08) || '"';
		END IF;
		v_sql1 := '  delete from s_nota where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_turma = ' || RTRIM(vr_co_turma02) || '  and co_aluno = ' || RTRIM(vr_co_aluno03);
		v_sql2 := '  and co_curso = ' || RTRIM(vr_co_curso05) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie06) || '  and co_disciplina = ' || RTRIM(vr_co_disciplina08) || ';';
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
		IF pn_ano_sem01 IS NULL
		AND pa_ano_sem01 IS NULL THEN
			vr_ano_sem01 := 'null';
		END IF;
		IF pn_ano_sem01 IS NULL
		AND pa_ano_sem01 IS NOT NULL THEN
			vr_ano_sem01 := 'null';
		END IF;
		IF pn_ano_sem01 IS NOT NULL
		AND pa_ano_sem01 IS NULL THEN
			vr_ano_sem01 := '"' || RTRIM(pn_ano_sem01) || '"';
		END IF;
		IF pn_ano_sem01 IS NOT NULL
		AND pa_ano_sem01 IS NOT NULL THEN
			IF pa_ano_sem01 <> pn_ano_sem01 THEN
				vr_ano_sem01 := '"' || RTRIM(pn_ano_sem01) || '"';
			ELSE
				vr_ano_sem01 := '"' || RTRIM(pa_ano_sem01) || '"';
			END IF;
		END IF;
		IF pn_co_turma02 IS NULL
		AND pa_co_turma02 IS NULL THEN
			vr_co_turma02 := 'null';
		END IF;
		IF pn_co_turma02 IS NULL
		AND pa_co_turma02 IS NOT NULL THEN
			vr_co_turma02 := 'null';
		END IF;
		IF pn_co_turma02 IS NOT NULL
		AND pa_co_turma02 IS NULL THEN
			vr_co_turma02 := pn_co_turma02;
		END IF;
		IF pn_co_turma02 IS NOT NULL
		AND pa_co_turma02 IS NOT NULL THEN
			IF pa_co_turma02 <> pn_co_turma02 THEN
				vr_co_turma02 := pn_co_turma02;
			ELSE
				vr_co_turma02 := pa_co_turma02;
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
		IF pn_nu_aulas_dada04 IS NULL
		AND pa_nu_aulas_dada04 IS NULL THEN
			vr_nu_aulas_dada04 := 'null';
		END IF;
		IF pn_nu_aulas_dada04 IS NULL
		AND pa_nu_aulas_dada04 IS NOT NULL THEN
			vr_nu_aulas_dada04 := 'null';
		END IF;
		IF pn_nu_aulas_dada04 IS NOT NULL
		AND pa_nu_aulas_dada04 IS NULL THEN
			vr_nu_aulas_dada04 := '"' || RTRIM(pn_nu_aulas_dada04) || '"';
		END IF;
		IF pn_nu_aulas_dada04 IS NOT NULL
		AND pa_nu_aulas_dada04 IS NOT NULL THEN
			IF pa_nu_aulas_dada04 <> pn_nu_aulas_dada04 THEN
				vr_nu_aulas_dada04 := '"' || RTRIM(pn_nu_aulas_dada04) || '"';
			ELSE
				vr_nu_aulas_dada04 := '"' || RTRIM(pa_nu_aulas_dada04) || '"';
			END IF;
		END IF;
		IF pn_co_curso05 IS NULL
		AND pa_co_curso05 IS NULL THEN
			vr_co_curso05 := 'null';
		END IF;
		IF pn_co_curso05 IS NULL
		AND pa_co_curso05 IS NOT NULL THEN
			vr_co_curso05 := 'null';
		END IF;
		IF pn_co_curso05 IS NOT NULL
		AND pa_co_curso05 IS NULL THEN
			vr_co_curso05 := pn_co_curso05;
		END IF;
		IF pn_co_curso05 IS NOT NULL
		AND pa_co_curso05 IS NOT NULL THEN
			IF pa_co_curso05 <> pn_co_curso05 THEN
				vr_co_curso05 := pn_co_curso05;
			ELSE
				vr_co_curso05 := pa_co_curso05;
			END IF;
		END IF;
		IF pn_co_seq_serie06 IS NULL
		AND pa_co_seq_serie06 IS NULL THEN
			vr_co_seq_serie06 := 'null';
		END IF;
		IF pn_co_seq_serie06 IS NULL
		AND pa_co_seq_serie06 IS NOT NULL THEN
			vr_co_seq_serie06 := 'null';
		END IF;
		IF pn_co_seq_serie06 IS NOT NULL
		AND pa_co_seq_serie06 IS NULL THEN
			vr_co_seq_serie06 := pn_co_seq_serie06;
		END IF;
		IF pn_co_seq_serie06 IS NOT NULL
		AND pa_co_seq_serie06 IS NOT NULL THEN
			IF pa_co_seq_serie06 <> pn_co_seq_serie06 THEN
				vr_co_seq_serie06 := pn_co_seq_serie06;
			ELSE
				vr_co_seq_serie06 := pa_co_seq_serie06;
			END IF;
		END IF;
		IF pn_nu_nota_b107 IS NULL
		AND pa_nu_nota_b107 IS NULL THEN
			vr_nu_nota_b107 := 'null';
		END IF;
		IF pn_nu_nota_b107 IS NULL
		AND pa_nu_nota_b107 IS NOT NULL THEN
			vr_nu_nota_b107 := 'null';
		END IF;
		IF pn_nu_nota_b107 IS NOT NULL
		AND pa_nu_nota_b107 IS NULL THEN
			vr_nu_nota_b107 := '"' || RTRIM(pn_nu_nota_b107) || '"';
		END IF;
		IF pn_nu_nota_b107 IS NOT NULL
		AND pa_nu_nota_b107 IS NOT NULL THEN
			IF pa_nu_nota_b107 <> pn_nu_nota_b107 THEN
				vr_nu_nota_b107 := '"' || RTRIM(pn_nu_nota_b107) || '"';
			ELSE
				vr_nu_nota_b107 := '"' || RTRIM(pa_nu_nota_b107) || '"';
			END IF;
		END IF;
		IF pn_co_disciplina08 IS NULL
		AND pa_co_disciplina08 IS NULL THEN
			vr_co_disciplina08 := 'null';
		END IF;
		IF pn_co_disciplina08 IS NULL
		AND pa_co_disciplina08 IS NOT NULL THEN
			vr_co_disciplina08 := 'null';
		END IF;
		IF pn_co_disciplina08 IS NOT NULL
		AND pa_co_disciplina08 IS NULL THEN
			vr_co_disciplina08 := '"' || RTRIM(pn_co_disciplina08) || '"';
		END IF;
		IF pn_co_disciplina08 IS NOT NULL
		AND pa_co_disciplina08 IS NOT NULL THEN
			IF pa_co_disciplina08 <> pn_co_disciplina08 THEN
				vr_co_disciplina08 := '"' || RTRIM(pn_co_disciplina08) || '"';
			ELSE
				vr_co_disciplina08 := '"' || RTRIM(pa_co_disciplina08) || '"';
			END IF;
		END IF;
		IF pn_nu_nota_recup09 IS NULL
		AND pa_nu_nota_recup09 IS NULL THEN
			vr_nu_nota_recup09 := 'null';
		END IF;
		IF pn_nu_nota_recup09 IS NULL
		AND pa_nu_nota_recup09 IS NOT NULL THEN
			vr_nu_nota_recup09 := 'null';
		END IF;
		IF pn_nu_nota_recup09 IS NOT NULL
		AND pa_nu_nota_recup09 IS NULL THEN
			vr_nu_nota_recup09 := '"' || RTRIM(pn_nu_nota_recup09) || '"';
		END IF;
		IF pn_nu_nota_recup09 IS NOT NULL
		AND pa_nu_nota_recup09 IS NOT NULL THEN
			IF pa_nu_nota_recup09 <> pn_nu_nota_recup09 THEN
				vr_nu_nota_recup09 := '"' || RTRIM(pn_nu_nota_recup09) || '"';
			ELSE
				vr_nu_nota_recup09 := '"' || RTRIM(pa_nu_nota_recup09) || '"';
			END IF;
		END IF;
		IF pn_nu_faltas_b110 IS NULL
		AND pa_nu_faltas_b110 IS NULL THEN
			vr_nu_faltas_b110 := 'null';
		END IF;
		IF pn_nu_faltas_b110 IS NULL
		AND pa_nu_faltas_b110 IS NOT NULL THEN
			vr_nu_faltas_b110 := 'null';
		END IF;
		IF pn_nu_faltas_b110 IS NOT NULL
		AND pa_nu_faltas_b110 IS NULL THEN
			vr_nu_faltas_b110 := '"' || RTRIM(pn_nu_faltas_b110) || '"';
		END IF;
		IF pn_nu_faltas_b110 IS NOT NULL
		AND pa_nu_faltas_b110 IS NOT NULL THEN
			IF pa_nu_faltas_b110 <> pn_nu_faltas_b110 THEN
				vr_nu_faltas_b110 := '"' || RTRIM(pn_nu_faltas_b110) || '"';
			ELSE
				vr_nu_faltas_b110 := '"' || RTRIM(pa_nu_faltas_b110) || '"';
			END IF;
		END IF;
		IF pn_nu_aulas_dada11 IS NULL
		AND pa_nu_aulas_dada11 IS NULL THEN
			vr_nu_aulas_dada11 := 'null';
		END IF;
		IF pn_nu_aulas_dada11 IS NULL
		AND pa_nu_aulas_dada11 IS NOT NULL THEN
			vr_nu_aulas_dada11 := 'null';
		END IF;
		IF pn_nu_aulas_dada11 IS NOT NULL
		AND pa_nu_aulas_dada11 IS NULL THEN
			vr_nu_aulas_dada11 := '"' || RTRIM(pn_nu_aulas_dada11) || '"';
		END IF;
		IF pn_nu_aulas_dada11 IS NOT NULL
		AND pa_nu_aulas_dada11 IS NOT NULL THEN
			IF pa_nu_aulas_dada11 <> pn_nu_aulas_dada11 THEN
				vr_nu_aulas_dada11 := '"' || RTRIM(pn_nu_aulas_dada11) || '"';
			ELSE
				vr_nu_aulas_dada11 := '"' || RTRIM(pa_nu_aulas_dada11) || '"';
			END IF;
		END IF;
		IF pn_nu_nota_b212 IS NULL
		AND pa_nu_nota_b212 IS NULL THEN
			vr_nu_nota_b212 := 'null';
		END IF;
		IF pn_nu_nota_b212 IS NULL
		AND pa_nu_nota_b212 IS NOT NULL THEN
			vr_nu_nota_b212 := 'null';
		END IF;
		IF pn_nu_nota_b212 IS NOT NULL
		AND pa_nu_nota_b212 IS NULL THEN
			vr_nu_nota_b212 := '"' || RTRIM(pn_nu_nota_b212) || '"';
		END IF;
		IF pn_nu_nota_b212 IS NOT NULL
		AND pa_nu_nota_b212 IS NOT NULL THEN
			IF pa_nu_nota_b212 <> pn_nu_nota_b212 THEN
				vr_nu_nota_b212 := '"' || RTRIM(pn_nu_nota_b212) || '"';
			ELSE
				vr_nu_nota_b212 := '"' || RTRIM(pa_nu_nota_b212) || '"';
			END IF;
		END IF;
		IF pn_nu_nota_recup13 IS NULL
		AND pa_nu_nota_recup13 IS NULL THEN
			vr_nu_nota_recup13 := 'null';
		END IF;
		IF pn_nu_nota_recup13 IS NULL
		AND pa_nu_nota_recup13 IS NOT NULL THEN
			vr_nu_nota_recup13 := 'null';
		END IF;
		IF pn_nu_nota_recup13 IS NOT NULL
		AND pa_nu_nota_recup13 IS NULL THEN
			vr_nu_nota_recup13 := '"' || RTRIM(pn_nu_nota_recup13) || '"';
		END IF;
		IF pn_nu_nota_recup13 IS NOT NULL
		AND pa_nu_nota_recup13 IS NOT NULL THEN
			IF pa_nu_nota_recup13 <> pn_nu_nota_recup13 THEN
				vr_nu_nota_recup13 := '"' || RTRIM(pn_nu_nota_recup13) || '"';
			ELSE
				vr_nu_nota_recup13 := '"' || RTRIM(pa_nu_nota_recup13) || '"';
			END IF;
		END IF;
		IF pn_nu_faltas_b214 IS NULL
		AND pa_nu_faltas_b214 IS NULL THEN
			vr_nu_faltas_b214 := 'null';
		END IF;
		IF pn_nu_faltas_b214 IS NULL
		AND pa_nu_faltas_b214 IS NOT NULL THEN
			vr_nu_faltas_b214 := 'null';
		END IF;
		IF pn_nu_faltas_b214 IS NOT NULL
		AND pa_nu_faltas_b214 IS NULL THEN
			vr_nu_faltas_b214 := '"' || RTRIM(pn_nu_faltas_b214) || '"';
		END IF;
		IF pn_nu_faltas_b214 IS NOT NULL
		AND pa_nu_faltas_b214 IS NOT NULL THEN
			IF pa_nu_faltas_b214 <> pn_nu_faltas_b214 THEN
				vr_nu_faltas_b214 := '"' || RTRIM(pn_nu_faltas_b214) || '"';
			ELSE
				vr_nu_faltas_b214 := '"' || RTRIM(pa_nu_faltas_b214) || '"';
			END IF;
		END IF;
		IF pn_nu_aulas_dada15 IS NULL
		AND pa_nu_aulas_dada15 IS NULL THEN
			vr_nu_aulas_dada15 := 'null';
		END IF;
		IF pn_nu_aulas_dada15 IS NULL
		AND pa_nu_aulas_dada15 IS NOT NULL THEN
			vr_nu_aulas_dada15 := 'null';
		END IF;
		IF pn_nu_aulas_dada15 IS NOT NULL
		AND pa_nu_aulas_dada15 IS NULL THEN
			vr_nu_aulas_dada15 := '"' || RTRIM(pn_nu_aulas_dada15) || '"';
		END IF;
		IF pn_nu_aulas_dada15 IS NOT NULL
		AND pa_nu_aulas_dada15 IS NOT NULL THEN
			IF pa_nu_aulas_dada15 <> pn_nu_aulas_dada15 THEN
				vr_nu_aulas_dada15 := '"' || RTRIM(pn_nu_aulas_dada15) || '"';
			ELSE
				vr_nu_aulas_dada15 := '"' || RTRIM(pa_nu_aulas_dada15) || '"';
			END IF;
		END IF;
		IF pn_nu_nota_b316 IS NULL
		AND pa_nu_nota_b316 IS NULL THEN
			vr_nu_nota_b316 := 'null';
		END IF;
		IF pn_nu_nota_b316 IS NULL
		AND pa_nu_nota_b316 IS NOT NULL THEN
			vr_nu_nota_b316 := 'null';
		END IF;
		IF pn_nu_nota_b316 IS NOT NULL
		AND pa_nu_nota_b316 IS NULL THEN
			vr_nu_nota_b316 := '"' || RTRIM(pn_nu_nota_b316) || '"';
		END IF;
		IF pn_nu_nota_b316 IS NOT NULL
		AND pa_nu_nota_b316 IS NOT NULL THEN
			IF pa_nu_nota_b316 <> pn_nu_nota_b316 THEN
				vr_nu_nota_b316 := '"' || RTRIM(pn_nu_nota_b316) || '"';
			ELSE
				vr_nu_nota_b316 := '"' || RTRIM(pa_nu_nota_b316) || '"';
			END IF;
		END IF;
		IF pn_nu_nota_recup17 IS NULL
		AND pa_nu_nota_recup17 IS NULL THEN
			vr_nu_nota_recup17 := 'null';
		END IF;
		IF pn_nu_nota_recup17 IS NULL
		AND pa_nu_nota_recup17 IS NOT NULL THEN
			vr_nu_nota_recup17 := 'null';
		END IF;
		IF pn_nu_nota_recup17 IS NOT NULL
		AND pa_nu_nota_recup17 IS NULL THEN
			vr_nu_nota_recup17 := '"' || RTRIM(pn_nu_nota_recup17) || '"';
		END IF;
		IF pn_nu_nota_recup17 IS NOT NULL
		AND pa_nu_nota_recup17 IS NOT NULL THEN
			IF pa_nu_nota_recup17 <> pn_nu_nota_recup17 THEN
				vr_nu_nota_recup17 := '"' || RTRIM(pn_nu_nota_recup17) || '"';
			ELSE
				vr_nu_nota_recup17 := '"' || RTRIM(pa_nu_nota_recup17) || '"';
			END IF;
		END IF;
		IF pn_nu_faltas_b318 IS NULL
		AND pa_nu_faltas_b318 IS NULL THEN
			vr_nu_faltas_b318 := 'null';
		END IF;
		IF pn_nu_faltas_b318 IS NULL
		AND pa_nu_faltas_b318 IS NOT NULL THEN
			vr_nu_faltas_b318 := 'null';
		END IF;
		IF pn_nu_faltas_b318 IS NOT NULL
		AND pa_nu_faltas_b318 IS NULL THEN
			vr_nu_faltas_b318 := '"' || RTRIM(pn_nu_faltas_b318) || '"';
		END IF;
		IF pn_nu_faltas_b318 IS NOT NULL
		AND pa_nu_faltas_b318 IS NOT NULL THEN
			IF pa_nu_faltas_b318 <> pn_nu_faltas_b318 THEN
				vr_nu_faltas_b318 := '"' || RTRIM(pn_nu_faltas_b318) || '"';
			ELSE
				vr_nu_faltas_b318 := '"' || RTRIM(pa_nu_faltas_b318) || '"';
			END IF;
		END IF;
		IF pn_nu_aulas_dada19 IS NULL
		AND pa_nu_aulas_dada19 IS NULL THEN
			vr_nu_aulas_dada19 := 'null';
		END IF;
		IF pn_nu_aulas_dada19 IS NULL
		AND pa_nu_aulas_dada19 IS NOT NULL THEN
			vr_nu_aulas_dada19 := 'null';
		END IF;
		IF pn_nu_aulas_dada19 IS NOT NULL
		AND pa_nu_aulas_dada19 IS NULL THEN
			vr_nu_aulas_dada19 := '"' || RTRIM(pn_nu_aulas_dada19) || '"';
		END IF;
		IF pn_nu_aulas_dada19 IS NOT NULL
		AND pa_nu_aulas_dada19 IS NOT NULL THEN
			IF pa_nu_aulas_dada19 <> pn_nu_aulas_dada19 THEN
				vr_nu_aulas_dada19 := '"' || RTRIM(pn_nu_aulas_dada19) || '"';
			ELSE
				vr_nu_aulas_dada19 := '"' || RTRIM(pa_nu_aulas_dada19) || '"';
			END IF;
		END IF;
		IF pn_nu_nota_b420 IS NULL
		AND pa_nu_nota_b420 IS NULL THEN
			vr_nu_nota_b420 := 'null';
		END IF;
		IF pn_nu_nota_b420 IS NULL
		AND pa_nu_nota_b420 IS NOT NULL THEN
			vr_nu_nota_b420 := 'null';
		END IF;
		IF pn_nu_nota_b420 IS NOT NULL
		AND pa_nu_nota_b420 IS NULL THEN
			vr_nu_nota_b420 := '"' || RTRIM(pn_nu_nota_b420) || '"';
		END IF;
		IF pn_nu_nota_b420 IS NOT NULL
		AND pa_nu_nota_b420 IS NOT NULL THEN
			IF pa_nu_nota_b420 <> pn_nu_nota_b420 THEN
				vr_nu_nota_b420 := '"' || RTRIM(pn_nu_nota_b420) || '"';
			ELSE
				vr_nu_nota_b420 := '"' || RTRIM(pa_nu_nota_b420) || '"';
			END IF;
		END IF;
		IF pn_nu_nota_recup21 IS NULL
		AND pa_nu_nota_recup21 IS NULL THEN
			vr_nu_nota_recup21 := 'null';
		END IF;
		IF pn_nu_nota_recup21 IS NULL
		AND pa_nu_nota_recup21 IS NOT NULL THEN
			vr_nu_nota_recup21 := 'null';
		END IF;
		IF pn_nu_nota_recup21 IS NOT NULL
		AND pa_nu_nota_recup21 IS NULL THEN
			vr_nu_nota_recup21 := '"' || RTRIM(pn_nu_nota_recup21) || '"';
		END IF;
		IF pn_nu_nota_recup21 IS NOT NULL
		AND pa_nu_nota_recup21 IS NOT NULL THEN
			IF pa_nu_nota_recup21 <> pn_nu_nota_recup21 THEN
				vr_nu_nota_recup21 := '"' || RTRIM(pn_nu_nota_recup21) || '"';
			ELSE
				vr_nu_nota_recup21 := '"' || RTRIM(pa_nu_nota_recup21) || '"';
			END IF;
		END IF;
		IF pn_nu_faltas_b422 IS NULL
		AND pa_nu_faltas_b422 IS NULL THEN
			vr_nu_faltas_b422 := 'null';
		END IF;
		IF pn_nu_faltas_b422 IS NULL
		AND pa_nu_faltas_b422 IS NOT NULL THEN
			vr_nu_faltas_b422 := 'null';
		END IF;
		IF pn_nu_faltas_b422 IS NOT NULL
		AND pa_nu_faltas_b422 IS NULL THEN
			vr_nu_faltas_b422 := '"' || RTRIM(pn_nu_faltas_b422) || '"';
		END IF;
		IF pn_nu_faltas_b422 IS NOT NULL
		AND pa_nu_faltas_b422 IS NOT NULL THEN
			IF pa_nu_faltas_b422 <> pn_nu_faltas_b422 THEN
				vr_nu_faltas_b422 := '"' || RTRIM(pn_nu_faltas_b422) || '"';
			ELSE
				vr_nu_faltas_b422 := '"' || RTRIM(pa_nu_faltas_b422) || '"';
			END IF;
		END IF;
		IF pn_nu_media_anua23 IS NULL
		AND pa_nu_media_anua23 IS NULL THEN
			vr_nu_media_anua23 := 'null';
		END IF;
		IF pn_nu_media_anua23 IS NULL
		AND pa_nu_media_anua23 IS NOT NULL THEN
			vr_nu_media_anua23 := 'null';
		END IF;
		IF pn_nu_media_anua23 IS NOT NULL
		AND pa_nu_media_anua23 IS NULL THEN
			vr_nu_media_anua23 := '"' || RTRIM(pn_nu_media_anua23) || '"';
		END IF;
		IF pn_nu_media_anua23 IS NOT NULL
		AND pa_nu_media_anua23 IS NOT NULL THEN
			IF pa_nu_media_anua23 <> pn_nu_media_anua23 THEN
				vr_nu_media_anua23 := '"' || RTRIM(pn_nu_media_anua23) || '"';
			ELSE
				vr_nu_media_anua23 := '"' || RTRIM(pa_nu_media_anua23) || '"';
			END IF;
		END IF;
		IF pn_nu_recup_espe24 IS NULL
		AND pa_nu_recup_espe24 IS NULL THEN
			vr_nu_recup_espe24 := 'null';
		END IF;
		IF pn_nu_recup_espe24 IS NULL
		AND pa_nu_recup_espe24 IS NOT NULL THEN
			vr_nu_recup_espe24 := 'null';
		END IF;
		IF pn_nu_recup_espe24 IS NOT NULL
		AND pa_nu_recup_espe24 IS NULL THEN
			vr_nu_recup_espe24 := '"' || RTRIM(pn_nu_recup_espe24) || '"';
		END IF;
		IF pn_nu_recup_espe24 IS NOT NULL
		AND pa_nu_recup_espe24 IS NOT NULL THEN
			IF pa_nu_recup_espe24 <> pn_nu_recup_espe24 THEN
				vr_nu_recup_espe24 := '"' || RTRIM(pn_nu_recup_espe24) || '"';
			ELSE
				vr_nu_recup_espe24 := '"' || RTRIM(pa_nu_recup_espe24) || '"';
			END IF;
		END IF;
		IF pn_nu_recup_fina25 IS NULL
		AND pa_nu_recup_fina25 IS NULL THEN
			vr_nu_recup_fina25 := 'null';
		END IF;
		IF pn_nu_recup_fina25 IS NULL
		AND pa_nu_recup_fina25 IS NOT NULL THEN
			vr_nu_recup_fina25 := 'null';
		END IF;
		IF pn_nu_recup_fina25 IS NOT NULL
		AND pa_nu_recup_fina25 IS NULL THEN
			vr_nu_recup_fina25 := '"' || RTRIM(pn_nu_recup_fina25) || '"';
		END IF;
		IF pn_nu_recup_fina25 IS NOT NULL
		AND pa_nu_recup_fina25 IS NOT NULL THEN
			IF pa_nu_recup_fina25 <> pn_nu_recup_fina25 THEN
				vr_nu_recup_fina25 := '"' || RTRIM(pn_nu_recup_fina25) || '"';
			ELSE
				vr_nu_recup_fina25 := '"' || RTRIM(pa_nu_recup_fina25) || '"';
			END IF;
		END IF;
		IF pn_nu_media_fina26 IS NULL
		AND pa_nu_media_fina26 IS NULL THEN
			vr_nu_media_fina26 := 'null';
		END IF;
		IF pn_nu_media_fina26 IS NULL
		AND pa_nu_media_fina26 IS NOT NULL THEN
			vr_nu_media_fina26 := 'null';
		END IF;
		IF pn_nu_media_fina26 IS NOT NULL
		AND pa_nu_media_fina26 IS NULL THEN
			vr_nu_media_fina26 := '"' || RTRIM(pn_nu_media_fina26) || '"';
		END IF;
		IF pn_nu_media_fina26 IS NOT NULL
		AND pa_nu_media_fina26 IS NOT NULL THEN
			IF pa_nu_media_fina26 <> pn_nu_media_fina26 THEN
				vr_nu_media_fina26 := '"' || RTRIM(pn_nu_media_fina26) || '"';
			ELSE
				vr_nu_media_fina26 := '"' || RTRIM(pa_nu_media_fina26) || '"';
			END IF;
		END IF;
		IF pn_nu_media_s127 IS NULL
		AND pa_nu_media_s127 IS NULL THEN
			vr_nu_media_s127 := 'null';
		END IF;
		IF pn_nu_media_s127 IS NULL
		AND pa_nu_media_s127 IS NOT NULL THEN
			vr_nu_media_s127 := 'null';
		END IF;
		IF pn_nu_media_s127 IS NOT NULL
		AND pa_nu_media_s127 IS NULL THEN
			vr_nu_media_s127 := '"' || RTRIM(pn_nu_media_s127) || '"';
		END IF;
		IF pn_nu_media_s127 IS NOT NULL
		AND pa_nu_media_s127 IS NOT NULL THEN
			IF pa_nu_media_s127 <> pn_nu_media_s127 THEN
				vr_nu_media_s127 := '"' || RTRIM(pn_nu_media_s127) || '"';
			ELSE
				vr_nu_media_s127 := '"' || RTRIM(pa_nu_media_s127) || '"';
			END IF;
		END IF;
		IF pn_nu_media_s228 IS NULL
		AND pa_nu_media_s228 IS NULL THEN
			vr_nu_media_s228 := 'null';
		END IF;
		IF pn_nu_media_s228 IS NULL
		AND pa_nu_media_s228 IS NOT NULL THEN
			vr_nu_media_s228 := 'null';
		END IF;
		IF pn_nu_media_s228 IS NOT NULL
		AND pa_nu_media_s228 IS NULL THEN
			vr_nu_media_s228 := '"' || RTRIM(pn_nu_media_s228) || '"';
		END IF;
		IF pn_nu_media_s228 IS NOT NULL
		AND pa_nu_media_s228 IS NOT NULL THEN
			IF pa_nu_media_s228 <> pn_nu_media_s228 THEN
				vr_nu_media_s228 := '"' || RTRIM(pn_nu_media_s228) || '"';
			ELSE
				vr_nu_media_s228 := '"' || RTRIM(pa_nu_media_s228) || '"';
			END IF;
		END IF;
		IF pn_nu_media_apos29 IS NULL
		AND pa_nu_media_apos29 IS NULL THEN
			vr_nu_media_apos29 := 'null';
		END IF;
		IF pn_nu_media_apos29 IS NULL
		AND pa_nu_media_apos29 IS NOT NULL THEN
			vr_nu_media_apos29 := 'null';
		END IF;
		IF pn_nu_media_apos29 IS NOT NULL
		AND pa_nu_media_apos29 IS NULL THEN
			vr_nu_media_apos29 := '"' || RTRIM(pn_nu_media_apos29) || '"';
		END IF;
		IF pn_nu_media_apos29 IS NOT NULL
		AND pa_nu_media_apos29 IS NOT NULL THEN
			IF pa_nu_media_apos29 <> pn_nu_media_apos29 THEN
				vr_nu_media_apos29 := '"' || RTRIM(pn_nu_media_apos29) || '"';
			ELSE
				vr_nu_media_apos29 := '"' || RTRIM(pa_nu_media_apos29) || '"';
			END IF;
		END IF;
		IF pn_nu_media_apos30 IS NULL
		AND pa_nu_media_apos30 IS NULL THEN
			vr_nu_media_apos30 := 'null';
		END IF;
		IF pn_nu_media_apos30 IS NULL
		AND pa_nu_media_apos30 IS NOT NULL THEN
			vr_nu_media_apos30 := 'null';
		END IF;
		IF pn_nu_media_apos30 IS NOT NULL
		AND pa_nu_media_apos30 IS NULL THEN
			vr_nu_media_apos30 := '"' || RTRIM(pn_nu_media_apos30) || '"';
		END IF;
		IF pn_nu_media_apos30 IS NOT NULL
		AND pa_nu_media_apos30 IS NOT NULL THEN
			IF pa_nu_media_apos30 <> pn_nu_media_apos30 THEN
				vr_nu_media_apos30 := '"' || RTRIM(pn_nu_media_apos30) || '"';
			ELSE
				vr_nu_media_apos30 := '"' || RTRIM(pa_nu_media_apos30) || '"';
			END IF;
		END IF;
		IF pn_nu_maxpontos_31 IS NULL
		AND pa_nu_maxpontos_31 IS NULL THEN
			vr_nu_maxpontos_31 := 'null';
		END IF;
		IF pn_nu_maxpontos_31 IS NULL
		AND pa_nu_maxpontos_31 IS NOT NULL THEN
			vr_nu_maxpontos_31 := 'null';
		END IF;
		IF pn_nu_maxpontos_31 IS NOT NULL
		AND pa_nu_maxpontos_31 IS NULL THEN
			vr_nu_maxpontos_31 := '"' || RTRIM(pn_nu_maxpontos_31) || '"';
		END IF;
		IF pn_nu_maxpontos_31 IS NOT NULL
		AND pa_nu_maxpontos_31 IS NOT NULL THEN
			IF pa_nu_maxpontos_31 <> pn_nu_maxpontos_31 THEN
				vr_nu_maxpontos_31 := '"' || RTRIM(pn_nu_maxpontos_31) || '"';
			ELSE
				vr_nu_maxpontos_31 := '"' || RTRIM(pa_nu_maxpontos_31) || '"';
			END IF;
		END IF;
		IF pn_nu_maxpontos_32 IS NULL
		AND pa_nu_maxpontos_32 IS NULL THEN
			vr_nu_maxpontos_32 := 'null';
		END IF;
		IF pn_nu_maxpontos_32 IS NULL
		AND pa_nu_maxpontos_32 IS NOT NULL THEN
			vr_nu_maxpontos_32 := 'null';
		END IF;
		IF pn_nu_maxpontos_32 IS NOT NULL
		AND pa_nu_maxpontos_32 IS NULL THEN
			vr_nu_maxpontos_32 := '"' || RTRIM(pn_nu_maxpontos_32) || '"';
		END IF;
		IF pn_nu_maxpontos_32 IS NOT NULL
		AND pa_nu_maxpontos_32 IS NOT NULL THEN
			IF pa_nu_maxpontos_32 <> pn_nu_maxpontos_32 THEN
				vr_nu_maxpontos_32 := '"' || RTRIM(pn_nu_maxpontos_32) || '"';
			ELSE
				vr_nu_maxpontos_32 := '"' || RTRIM(pa_nu_maxpontos_32) || '"';
			END IF;
		END IF;
		IF pn_nu_maxpontos_33 IS NULL
		AND pa_nu_maxpontos_33 IS NULL THEN
			vr_nu_maxpontos_33 := 'null';
		END IF;
		IF pn_nu_maxpontos_33 IS NULL
		AND pa_nu_maxpontos_33 IS NOT NULL THEN
			vr_nu_maxpontos_33 := 'null';
		END IF;
		IF pn_nu_maxpontos_33 IS NOT NULL
		AND pa_nu_maxpontos_33 IS NULL THEN
			vr_nu_maxpontos_33 := '"' || RTRIM(pn_nu_maxpontos_33) || '"';
		END IF;
		IF pn_nu_maxpontos_33 IS NOT NULL
		AND pa_nu_maxpontos_33 IS NOT NULL THEN
			IF pa_nu_maxpontos_33 <> pn_nu_maxpontos_33 THEN
				vr_nu_maxpontos_33 := '"' || RTRIM(pn_nu_maxpontos_33) || '"';
			ELSE
				vr_nu_maxpontos_33 := '"' || RTRIM(pa_nu_maxpontos_33) || '"';
			END IF;
		END IF;
		IF pn_nu_maxpontos_34 IS NULL
		AND pa_nu_maxpontos_34 IS NULL THEN
			vr_nu_maxpontos_34 := 'null';
		END IF;
		IF pn_nu_maxpontos_34 IS NULL
		AND pa_nu_maxpontos_34 IS NOT NULL THEN
			vr_nu_maxpontos_34 := 'null';
		END IF;
		IF pn_nu_maxpontos_34 IS NOT NULL
		AND pa_nu_maxpontos_34 IS NULL THEN
			vr_nu_maxpontos_34 := '"' || RTRIM(pn_nu_maxpontos_34) || '"';
		END IF;
		IF pn_nu_maxpontos_34 IS NOT NULL
		AND pa_nu_maxpontos_34 IS NOT NULL THEN
			IF pa_nu_maxpontos_34 <> pn_nu_maxpontos_34 THEN
				vr_nu_maxpontos_34 := '"' || RTRIM(pn_nu_maxpontos_34) || '"';
			ELSE
				vr_nu_maxpontos_34 := '"' || RTRIM(pa_nu_maxpontos_34) || '"';
			END IF;
		END IF;
		IF pn_nu_nota_sm135 IS NULL
		AND pa_nu_nota_sm135 IS NULL THEN
			vr_nu_nota_sm135 := 'null';
		END IF;
		IF pn_nu_nota_sm135 IS NULL
		AND pa_nu_nota_sm135 IS NOT NULL THEN
			vr_nu_nota_sm135 := 'null';
		END IF;
		IF pn_nu_nota_sm135 IS NOT NULL
		AND pa_nu_nota_sm135 IS NULL THEN
			vr_nu_nota_sm135 := '"' || RTRIM(pn_nu_nota_sm135) || '"';
		END IF;
		IF pn_nu_nota_sm135 IS NOT NULL
		AND pa_nu_nota_sm135 IS NOT NULL THEN
			IF pa_nu_nota_sm135 <> pn_nu_nota_sm135 THEN
				vr_nu_nota_sm135 := '"' || RTRIM(pn_nu_nota_sm135) || '"';
			ELSE
				vr_nu_nota_sm135 := '"' || RTRIM(pa_nu_nota_sm135) || '"';
			END IF;
		END IF;
		IF pn_nu_nota_sm236 IS NULL
		AND pa_nu_nota_sm236 IS NULL THEN
			vr_nu_nota_sm236 := 'null';
		END IF;
		IF pn_nu_nota_sm236 IS NULL
		AND pa_nu_nota_sm236 IS NOT NULL THEN
			vr_nu_nota_sm236 := 'null';
		END IF;
		IF pn_nu_nota_sm236 IS NOT NULL
		AND pa_nu_nota_sm236 IS NULL THEN
			vr_nu_nota_sm236 := '"' || RTRIM(pn_nu_nota_sm236) || '"';
		END IF;
		IF pn_nu_nota_sm236 IS NOT NULL
		AND pa_nu_nota_sm236 IS NOT NULL THEN
			IF pa_nu_nota_sm236 <> pn_nu_nota_sm236 THEN
				vr_nu_nota_sm236 := '"' || RTRIM(pn_nu_nota_sm236) || '"';
			ELSE
				vr_nu_nota_sm236 := '"' || RTRIM(pa_nu_nota_sm236) || '"';
			END IF;
		END IF;
		IF pn_nu_nota_sm337 IS NULL
		AND pa_nu_nota_sm337 IS NULL THEN
			vr_nu_nota_sm337 := 'null';
		END IF;
		IF pn_nu_nota_sm337 IS NULL
		AND pa_nu_nota_sm337 IS NOT NULL THEN
			vr_nu_nota_sm337 := 'null';
		END IF;
		IF pn_nu_nota_sm337 IS NOT NULL
		AND pa_nu_nota_sm337 IS NULL THEN
			vr_nu_nota_sm337 := '"' || RTRIM(pn_nu_nota_sm337) || '"';
		END IF;
		IF pn_nu_nota_sm337 IS NOT NULL
		AND pa_nu_nota_sm337 IS NOT NULL THEN
			IF pa_nu_nota_sm337 <> pn_nu_nota_sm337 THEN
				vr_nu_nota_sm337 := '"' || RTRIM(pn_nu_nota_sm337) || '"';
			ELSE
				vr_nu_nota_sm337 := '"' || RTRIM(pa_nu_nota_sm337) || '"';
			END IF;
		END IF;
		IF pn_nu_nota_sm438 IS NULL
		AND pa_nu_nota_sm438 IS NULL THEN
			vr_nu_nota_sm438 := 'null';
		END IF;
		IF pn_nu_nota_sm438 IS NULL
		AND pa_nu_nota_sm438 IS NOT NULL THEN
			vr_nu_nota_sm438 := 'null';
		END IF;
		IF pn_nu_nota_sm438 IS NOT NULL
		AND pa_nu_nota_sm438 IS NULL THEN
			vr_nu_nota_sm438 := '"' || RTRIM(pn_nu_nota_sm438) || '"';
		END IF;
		IF pn_nu_nota_sm438 IS NOT NULL
		AND pa_nu_nota_sm438 IS NOT NULL THEN
			IF pa_nu_nota_sm438 <> pn_nu_nota_sm438 THEN
				vr_nu_nota_sm438 := '"' || RTRIM(pn_nu_nota_sm438) || '"';
			ELSE
				vr_nu_nota_sm438 := '"' || RTRIM(pa_nu_nota_sm438) || '"';
			END IF;
		END IF;
		IF pn_nu_nota_sm539 IS NULL
		AND pa_nu_nota_sm539 IS NULL THEN
			vr_nu_nota_sm539 := 'null';
		END IF;
		IF pn_nu_nota_sm539 IS NULL
		AND pa_nu_nota_sm539 IS NOT NULL THEN
			vr_nu_nota_sm539 := 'null';
		END IF;
		IF pn_nu_nota_sm539 IS NOT NULL
		AND pa_nu_nota_sm539 IS NULL THEN
			vr_nu_nota_sm539 := '"' || RTRIM(pn_nu_nota_sm539) || '"';
		END IF;
		IF pn_nu_nota_sm539 IS NOT NULL
		AND pa_nu_nota_sm539 IS NOT NULL THEN
			IF pa_nu_nota_sm539 <> pn_nu_nota_sm539 THEN
				vr_nu_nota_sm539 := '"' || RTRIM(pn_nu_nota_sm539) || '"';
			ELSE
				vr_nu_nota_sm539 := '"' || RTRIM(pa_nu_nota_sm539) || '"';
			END IF;
		END IF;
		IF pn_nu_nota_sm640 IS NULL
		AND pa_nu_nota_sm640 IS NULL THEN
			vr_nu_nota_sm640 := 'null';
		END IF;
		IF pn_nu_nota_sm640 IS NULL
		AND pa_nu_nota_sm640 IS NOT NULL THEN
			vr_nu_nota_sm640 := 'null';
		END IF;
		IF pn_nu_nota_sm640 IS NOT NULL
		AND pa_nu_nota_sm640 IS NULL THEN
			vr_nu_nota_sm640 := '"' || RTRIM(pn_nu_nota_sm640) || '"';
		END IF;
		IF pn_nu_nota_sm640 IS NOT NULL
		AND pa_nu_nota_sm640 IS NOT NULL THEN
			IF pa_nu_nota_sm640 <> pn_nu_nota_sm640 THEN
				vr_nu_nota_sm640 := '"' || RTRIM(pn_nu_nota_sm640) || '"';
			ELSE
				vr_nu_nota_sm640 := '"' || RTRIM(pa_nu_nota_sm640) || '"';
			END IF;
		END IF;
		IF pn_nu_nota_sm741 IS NULL
		AND pa_nu_nota_sm741 IS NULL THEN
			vr_nu_nota_sm741 := 'null';
		END IF;
		IF pn_nu_nota_sm741 IS NULL
		AND pa_nu_nota_sm741 IS NOT NULL THEN
			vr_nu_nota_sm741 := 'null';
		END IF;
		IF pn_nu_nota_sm741 IS NOT NULL
		AND pa_nu_nota_sm741 IS NULL THEN
			vr_nu_nota_sm741 := '"' || RTRIM(pn_nu_nota_sm741) || '"';
		END IF;
		IF pn_nu_nota_sm741 IS NOT NULL
		AND pa_nu_nota_sm741 IS NOT NULL THEN
			IF pa_nu_nota_sm741 <> pn_nu_nota_sm741 THEN
				vr_nu_nota_sm741 := '"' || RTRIM(pn_nu_nota_sm741) || '"';
			ELSE
				vr_nu_nota_sm741 := '"' || RTRIM(pa_nu_nota_sm741) || '"';
			END IF;
		END IF;
		IF pn_nu_nota_sm842 IS NULL
		AND pa_nu_nota_sm842 IS NULL THEN
			vr_nu_nota_sm842 := 'null';
		END IF;
		IF pn_nu_nota_sm842 IS NULL
		AND pa_nu_nota_sm842 IS NOT NULL THEN
			vr_nu_nota_sm842 := 'null';
		END IF;
		IF pn_nu_nota_sm842 IS NOT NULL
		AND pa_nu_nota_sm842 IS NULL THEN
			vr_nu_nota_sm842 := '"' || RTRIM(pn_nu_nota_sm842) || '"';
		END IF;
		IF pn_nu_nota_sm842 IS NOT NULL
		AND pa_nu_nota_sm842 IS NOT NULL THEN
			IF pa_nu_nota_sm842 <> pn_nu_nota_sm842 THEN
				vr_nu_nota_sm842 := '"' || RTRIM(pn_nu_nota_sm842) || '"';
			ELSE
				vr_nu_nota_sm842 := '"' || RTRIM(pa_nu_nota_sm842) || '"';
			END IF;
		END IF;
		IF pn_nu_nota_sm943 IS NULL
		AND pa_nu_nota_sm943 IS NULL THEN
			vr_nu_nota_sm943 := 'null';
		END IF;
		IF pn_nu_nota_sm943 IS NULL
		AND pa_nu_nota_sm943 IS NOT NULL THEN
			vr_nu_nota_sm943 := 'null';
		END IF;
		IF pn_nu_nota_sm943 IS NOT NULL
		AND pa_nu_nota_sm943 IS NULL THEN
			vr_nu_nota_sm943 := '"' || RTRIM(pn_nu_nota_sm943) || '"';
		END IF;
		IF pn_nu_nota_sm943 IS NOT NULL
		AND pa_nu_nota_sm943 IS NOT NULL THEN
			IF pa_nu_nota_sm943 <> pn_nu_nota_sm943 THEN
				vr_nu_nota_sm943 := '"' || RTRIM(pn_nu_nota_sm943) || '"';
			ELSE
				vr_nu_nota_sm943 := '"' || RTRIM(pa_nu_nota_sm943) || '"';
			END IF;
		END IF;
		IF pn_st_conselho44 IS NULL
		AND pa_st_conselho44 IS NULL THEN
			vr_st_conselho44 := 'null';
		END IF;
		IF pn_st_conselho44 IS NULL
		AND pa_st_conselho44 IS NOT NULL THEN
			vr_st_conselho44 := 'null';
		END IF;
		IF pn_st_conselho44 IS NOT NULL
		AND pa_st_conselho44 IS NULL THEN
			vr_st_conselho44 := '"' || RTRIM(pn_st_conselho44) || '"';
		END IF;
		IF pn_st_conselho44 IS NOT NULL
		AND pa_st_conselho44 IS NOT NULL THEN
			IF pa_st_conselho44 <> pn_st_conselho44 THEN
				vr_st_conselho44 := '"' || RTRIM(pn_st_conselho44) || '"';
			ELSE
				vr_st_conselho44 := '"' || RTRIM(pa_st_conselho44) || '"';
			END IF;
		END IF;
		IF pn_nu_ordem_145 IS NULL
		AND pa_nu_ordem_145 IS NULL THEN
			vr_nu_ordem_145 := 'null';
		END IF;
		IF pn_nu_ordem_145 IS NULL
		AND pa_nu_ordem_145 IS NOT NULL THEN
			vr_nu_ordem_145 := 'null';
		END IF;
		IF pn_nu_ordem_145 IS NOT NULL
		AND pa_nu_ordem_145 IS NULL THEN
			vr_nu_ordem_145 := pn_nu_ordem_145;
		END IF;
		IF pn_nu_ordem_145 IS NOT NULL
		AND pa_nu_ordem_145 IS NOT NULL THEN
			IF pa_nu_ordem_145 <> pn_nu_ordem_145 THEN
				vr_nu_ordem_145 := pn_nu_ordem_145;
			ELSE
				vr_nu_ordem_145 := pa_nu_ordem_145;
			END IF;
		END IF;
		IF pn_nu_ordem_246 IS NULL
		AND pa_nu_ordem_246 IS NULL THEN
			vr_nu_ordem_246 := 'null';
		END IF;
		IF pn_nu_ordem_246 IS NULL
		AND pa_nu_ordem_246 IS NOT NULL THEN
			vr_nu_ordem_246 := 'null';
		END IF;
		IF pn_nu_ordem_246 IS NOT NULL
		AND pa_nu_ordem_246 IS NULL THEN
			vr_nu_ordem_246 := pn_nu_ordem_246;
		END IF;
		IF pn_nu_ordem_246 IS NOT NULL
		AND pa_nu_ordem_246 IS NOT NULL THEN
			IF pa_nu_ordem_246 <> pn_nu_ordem_246 THEN
				vr_nu_ordem_246 := pn_nu_ordem_246;
			ELSE
				vr_nu_ordem_246 := pa_nu_ordem_246;
			END IF;
		END IF;
		v_sql1 := 'update s_nota set co_unidade = ' || RTRIM(vr_co_unidade00) || '  , ano_sem = ' || RTRIM(vr_ano_sem01) || '  , co_turma = ' || RTRIM(vr_co_turma02) || '  , co_aluno = ' || RTRIM(vr_co_aluno03) || '  , nu_aulas_dadas_b1 = ' || RTRIM(vr_nu_aulas_dada04) || '  , co_curso = ' || RTRIM(vr_co_curso05) || '  , co_seq_serie = ' || RTRIM(vr_co_seq_serie06) || '  , nu_nota_b1 = ' || RTRIM(vr_nu_nota_b107) || '  , co_disciplina = ' || RTRIM(vr_co_disciplina08);
		v_sql2 := '  , NU_NOTA_RECUPERACAO_1 = ' || RTRIM(vr_nu_nota_recup09) || '  , nu_faltas_b1 = ' || RTRIM(vr_nu_faltas_b110) || '  , nu_aulas_dadas_b2 = ' || RTRIM(vr_nu_aulas_dada11) || '  , nu_nota_b2 = ' || RTRIM(vr_nu_nota_b212) || '  , NU_NOTA_RECUPERACAO_2 = ' || RTRIM(vr_nu_nota_recup13) || '  , nu_faltas_b2 = ' || RTRIM(vr_nu_faltas_b214) || '  , nu_aulas_dadas_b3 = ' || RTRIM(vr_nu_aulas_dada15);
		v_sql3 := '  , nu_nota_b3 = ' || RTRIM(vr_nu_nota_b316) || '  , NU_NOTA_RECUPERACAO_3 = ' || RTRIM(vr_nu_nota_recup17) || '  , nu_faltas_b3 = ' || RTRIM(vr_nu_faltas_b318) || '  , nu_aulas_dadas_b4 = ' || RTRIM(vr_nu_aulas_dada19) || '  , nu_nota_b4 = ' || RTRIM(vr_nu_nota_b420) || '  , NU_NOTA_RECUPERACAO_4 = ' || RTRIM(vr_nu_nota_recup21);
		v_sql4 := '  , nu_faltas_b4 = ' || RTRIM(vr_nu_faltas_b422) || '  , nu_media_anual = ' || RTRIM(vr_nu_media_anua23) || '  , NU_RECUPERACAO_ESPECIAL = ' || RTRIM(vr_nu_recup_espe24) || '  , NU_RECUPERACAO_FINAL = ' || RTRIM(vr_nu_recup_fina25) || '  , nu_media_final = ' || RTRIM(vr_nu_media_fina26) || '  , nu_media_s1 = ' || RTRIM(vr_nu_media_s127) || '  , nu_media_s2 = ' || RTRIM(vr_nu_media_s228);
		v_sql5 := '  , nu_media_apos_s1 = ' || RTRIM(vr_nu_media_apos29) || '  , nu_media_apos_s2 = ' || RTRIM(vr_nu_media_apos30) || '  , nu_maxpontos_b1 = ' || RTRIM(vr_nu_maxpontos_31) || '  , nu_maxpontos_b2 = ' || RTRIM(vr_nu_maxpontos_32) || '  , nu_maxpontos_b3 = ' || RTRIM(vr_nu_maxpontos_33) || '  , nu_maxpontos_b4 = ' || RTRIM(vr_nu_maxpontos_34) || '  , nu_nota_sm1 = ' || RTRIM(vr_nu_nota_sm135);
		v_sql6 := '  , nu_nota_sm2 = ' || RTRIM(vr_nu_nota_sm236) || '  , nu_nota_sm3 = ' || RTRIM(vr_nu_nota_sm337) || '  , nu_nota_sm4 = ' || RTRIM(vr_nu_nota_sm438) || '  , nu_nota_sm5 = ' || RTRIM(vr_nu_nota_sm539) || '  , nu_nota_sm6 = ' || RTRIM(vr_nu_nota_sm640) || '  , nu_nota_sm7 = ' || RTRIM(vr_nu_nota_sm741) || '  , nu_nota_sm8 = ' || RTRIM(vr_nu_nota_sm842) || '  , nu_nota_sm9 = ' || RTRIM(vr_nu_nota_sm943);
		v_sql7 := '  , st_conselho = ' || RTRIM(vr_st_conselho44) || '  , nu_ordem_1 = ' || RTRIM(vr_nu_ordem_145) || '  , nu_ordem_2 = ' || RTRIM(vr_nu_ordem_246);
		v_sql8 := ' where co_unidade = ' || RTRIM(vr_co_unidade00) || '  and ano_sem = ' || RTRIM(vr_ano_sem01) || '  and co_turma = ' || RTRIM(vr_co_turma02) || '  and co_aluno = ' || RTRIM(vr_co_aluno03) || '  and co_curso = ' || RTRIM(vr_co_curso05) || '  and co_seq_serie = ' || RTRIM(vr_co_seq_serie06) || '  and co_disciplina = ' || RTRIM(vr_co_disciplina08) || ';';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5 || v_sql6 || v_sql7 || v_sql8;
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
		       's_nota',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_nota126;
/

