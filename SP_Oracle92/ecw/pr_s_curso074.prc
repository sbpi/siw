CREATE OR REPLACE PROCEDURE pr_s_curso074(
	P_OP_IN                CHAR,
	PA_co_curso00_IN       s_curso.co_curso%TYPE,
	PA_ds_curso01_IN       s_curso.ds_curso%TYPE,
	PA_co_unidade02_IN     s_curso.co_unidade%TYPE,
	PA_tp_recuperaca03_IN  s_curso.tp_recuperacao%TYPE,
	PA_co_grade_curr04_IN  s_curso.co_grade_curric%TYPE,
	PA_co_tipo_curso05_IN  s_curso.co_tipo_curso%TYPE,
	PA_ds_formula_1_06_IN  s_curso.ds_formula_1_bim%TYPE,
	PA_ano07_IN            s_curso.ano%TYPE,
	PA_turno08_IN          s_curso.turno%TYPE,
	PA_ds_formula_2_09_IN  s_curso.ds_formula_2_bim%TYPE,
	PA_ds_formula_3_10_IN  s_curso.ds_formula_3_bim%TYPE,
	PA_ds_formula_4_11_IN  s_curso.ds_formula_4_bim%TYPE,
	PA_ds_formula_1_12_IN  s_curso.ds_formula_1_sem%TYPE,
	PA_ds_formula_2_13_IN  s_curso.ds_formula_2_sem%TYPE,
	PA_ds_form_md_an14_IN  s_curso.ds_form_md_anual%TYPE,
	PA_ds_form_nota_15_IN  s_curso.ds_form_nota_final%TYPE,
	PA_st_ano_letivo16_IN  s_curso.st_ano_letivo_enc%TYPE,
	PA_nu_media_nota17_IN  s_curso.nu_media_nota%TYPE,
	PA_ds_nota_acumu18_IN  s_curso.ds_nota_acumulada%TYPE,
	PA_tp_recalc_md_19_IN  s_curso.tp_recalc_md_sem%TYPE,
	PA_st_arred_nota20_IN  s_curso.st_arred_nota_sem%TYPE,
	PA_nu_md_minima_21_IN  s_curso.nu_md_minima_2_sem%TYPE,
	PA_nu_mat_rec_fi22_IN  s_curso.nu_mat_rec_final%TYPE,
	PA_nu_freq_minim23_IN  s_curso.nu_freq_minima_obr%TYPE,
	PA_tp_frequencia24_IN  s_curso.tp_frequencia_min%TYPE,
	PA_nu_md_min_fre25_IN  s_curso.nu_md_min_freq_men%TYPE,
	PA_st_aula_sabad26_IN  s_curso.st_aula_sabado%TYPE,
	PA_ds_parecer_le27_IN  s_curso.ds_parecer_legal%TYPE,
	PA_nu_dias_letiv28_IN  s_curso.nu_dias_letivos_b1%TYPE,
	PA_nu_dias_letiv29_IN  s_curso.nu_dias_letivos_b2%TYPE,
	PA_nu_dias_letiv30_IN  s_curso.nu_dias_letivos_b3%TYPE,
	PA_nu_dias_letiv31_IN  s_curso.nu_dias_letivos_b4%TYPE,
	PA_nu_md_min_rec32_IN  s_curso.nu_md_min_rec_esp%TYPE,
	PA_ds_formula_ap33_IN  s_curso.ds_formula_apos_s1%TYPE,
	PA_ds_formula_ap34_IN  s_curso.ds_formula_apos_s2%TYPE,
	PA_ano_sem35_IN        s_curso.ano_sem%TYPE,
	PN_co_curso00_IN       s_curso.co_curso%TYPE,
	PN_ds_curso01_IN       s_curso.ds_curso%TYPE,
	PN_co_unidade02_IN     s_curso.co_unidade%TYPE,
	PN_tp_recuperaca03_IN  s_curso.tp_recuperacao%TYPE,
	PN_co_grade_curr04_IN  s_curso.co_grade_curric%TYPE,
	PN_co_tipo_curso05_IN  s_curso.co_tipo_curso%TYPE,
	PN_ds_formula_1_06_IN  s_curso.ds_formula_1_bim%TYPE,
	PN_ano07_IN            s_curso.ano%TYPE,
	PN_turno08_IN          s_curso.turno%TYPE,
	PN_ds_formula_2_09_IN  s_curso.ds_formula_2_bim%TYPE,
	PN_ds_formula_3_10_IN  s_curso.ds_formula_3_bim%TYPE,
	PN_ds_formula_4_11_IN  s_curso.ds_formula_4_bim%TYPE,
	PN_ds_formula_1_12_IN  s_curso.ds_formula_1_sem%TYPE,
	PN_ds_formula_2_13_IN  s_curso.ds_formula_2_sem%TYPE,
	PN_ds_form_md_an14_IN  s_curso.ds_form_md_anual%TYPE,
	PN_ds_form_nota_15_IN  s_curso.ds_form_nota_final%TYPE,
	PN_st_ano_letivo16_IN  s_curso.st_ano_letivo_enc%TYPE,
	PN_nu_media_nota17_IN  s_curso.nu_media_nota%TYPE,
	PN_ds_nota_acumu18_IN  s_curso.ds_nota_acumulada%TYPE,
	PN_tp_recalc_md_19_IN  s_curso.tp_recalc_md_sem%TYPE,
	PN_st_arred_nota20_IN  s_curso.st_arred_nota_sem%TYPE,
	PN_nu_md_minima_21_IN  s_curso.nu_md_minima_2_sem%TYPE,
	PN_nu_mat_rec_fi22_IN  s_curso.nu_mat_rec_final%TYPE,
	PN_nu_freq_minim23_IN  s_curso.nu_freq_minima_obr%TYPE,
	PN_tp_frequencia24_IN  s_curso.tp_frequencia_min%TYPE,
	PN_nu_md_min_fre25_IN  s_curso.nu_md_min_freq_men%TYPE,
	PN_st_aula_sabad26_IN  s_curso.st_aula_sabado%TYPE,
	PN_ds_parecer_le27_IN  s_curso.ds_parecer_legal%TYPE,
	PN_nu_dias_letiv28_IN  s_curso.nu_dias_letivos_b1%TYPE,
	PN_nu_dias_letiv29_IN  s_curso.nu_dias_letivos_b2%TYPE,
	PN_nu_dias_letiv30_IN  s_curso.nu_dias_letivos_b3%TYPE,
	PN_nu_dias_letiv31_IN  s_curso.nu_dias_letivos_b4%TYPE,
	PN_nu_md_min_rec32_IN  s_curso.nu_md_min_rec_esp%TYPE,
	PN_ds_formula_ap33_IN  s_curso.ds_formula_apos_s1%TYPE,
	PN_ds_formula_ap34_IN  s_curso.ds_formula_apos_s2%TYPE,
	PN_ano_sem35_IN        s_curso.ano_sem%TYPE) AS

P_OP                CHAR(3) := P_OP_IN;
PA_co_curso00       s_curso.co_curso%TYPE := PA_co_curso00_IN;
PA_ds_curso01       s_curso.ds_curso%TYPE := PA_ds_curso01_IN;
PA_co_unidade02     s_curso.co_unidade%TYPE := PA_co_unidade02_IN;
PA_tp_recuperaca03  s_curso.tp_recuperacao%TYPE := PA_tp_recuperaca03_IN;
PA_co_grade_curr04  s_curso.co_grade_curric%TYPE := PA_co_grade_curr04_IN;
PA_co_tipo_curso05  s_curso.co_tipo_curso%TYPE := PA_co_tipo_curso05_IN;
PA_ds_formula_1_06  s_curso.ds_formula_1_bim%TYPE := PA_ds_formula_1_06_IN;
PA_ano07            s_curso.ano%TYPE := PA_ano07_IN;
PA_turno08          s_curso.turno%TYPE := PA_turno08_IN;
PA_ds_formula_2_09  s_curso.ds_formula_2_bim%TYPE := PA_ds_formula_2_09_IN;
PA_ds_formula_3_10  s_curso.ds_formula_3_bim%TYPE := PA_ds_formula_3_10_IN;
PA_ds_formula_4_11  s_curso.ds_formula_4_bim%TYPE := PA_ds_formula_4_11_IN;
PA_ds_formula_1_12  s_curso.ds_formula_1_sem%TYPE := PA_ds_formula_1_12_IN;
PA_ds_formula_2_13  s_curso.ds_formula_2_sem%TYPE := PA_ds_formula_2_13_IN;
PA_ds_form_md_an14  s_curso.ds_form_md_anual%TYPE := PA_ds_form_md_an14_IN;
PA_ds_form_nota_15  s_curso.ds_form_nota_final%TYPE := PA_ds_form_nota_15_IN;
PA_st_ano_letivo16  s_curso.st_ano_letivo_enc%TYPE := PA_st_ano_letivo16_IN;
PA_nu_media_nota17  s_curso.nu_media_nota%TYPE := PA_nu_media_nota17_IN;
PA_ds_nota_acumu18  s_curso.ds_nota_acumulada%TYPE := PA_ds_nota_acumu18_IN;
PA_tp_recalc_md_19  s_curso.tp_recalc_md_sem%TYPE := PA_tp_recalc_md_19_IN;
PA_st_arred_nota20  s_curso.st_arred_nota_sem%TYPE := PA_st_arred_nota20_IN;
PA_nu_md_minima_21  s_curso.nu_md_minima_2_sem%TYPE := PA_nu_md_minima_21_IN;
PA_nu_mat_rec_fi22  s_curso.nu_mat_rec_final%TYPE := PA_nu_mat_rec_fi22_IN;
PA_nu_freq_minim23  s_curso.nu_freq_minima_obr%TYPE := PA_nu_freq_minim23_IN;
PA_tp_frequencia24  s_curso.tp_frequencia_min%TYPE := PA_tp_frequencia24_IN;
PA_nu_md_min_fre25  s_curso.nu_md_min_freq_men%TYPE := PA_nu_md_min_fre25_IN;
PA_st_aula_sabad26  s_curso.st_aula_sabado%TYPE := PA_st_aula_sabad26_IN;
PA_ds_parecer_le27  s_curso.ds_parecer_legal%TYPE := PA_ds_parecer_le27_IN;
PA_nu_dias_letiv28  s_curso.nu_dias_letivos_b1%TYPE := PA_nu_dias_letiv28_IN;
PA_nu_dias_letiv29  s_curso.nu_dias_letivos_b2%TYPE := PA_nu_dias_letiv29_IN;
PA_nu_dias_letiv30  s_curso.nu_dias_letivos_b3%TYPE := PA_nu_dias_letiv30_IN;
PA_nu_dias_letiv31  s_curso.nu_dias_letivos_b4%TYPE := PA_nu_dias_letiv31_IN;
PA_nu_md_min_rec32  s_curso.nu_md_min_rec_esp%TYPE := PA_nu_md_min_rec32_IN;
PA_ds_formula_ap33  s_curso.ds_formula_apos_s1%TYPE := PA_ds_formula_ap33_IN;
PA_ds_formula_ap34  s_curso.ds_formula_apos_s2%TYPE := PA_ds_formula_ap34_IN;
PA_ano_sem35        s_curso.ano_sem%TYPE := PA_ano_sem35_IN;
PN_co_curso00       s_curso.co_curso%TYPE := PN_co_curso00_IN;
PN_ds_curso01       s_curso.ds_curso%TYPE := PN_ds_curso01_IN;
PN_co_unidade02     s_curso.co_unidade%TYPE := PN_co_unidade02_IN;
PN_tp_recuperaca03  s_curso.tp_recuperacao%TYPE := PN_tp_recuperaca03_IN;
PN_co_grade_curr04  s_curso.co_grade_curric%TYPE := PN_co_grade_curr04_IN;
PN_co_tipo_curso05  s_curso.co_tipo_curso%TYPE := PN_co_tipo_curso05_IN;
PN_ds_formula_1_06  s_curso.ds_formula_1_bim%TYPE := PN_ds_formula_1_06_IN;
PN_ano07            s_curso.ano%TYPE := PN_ano07_IN;
PN_turno08          s_curso.turno%TYPE := PN_turno08_IN;
PN_ds_formula_2_09  s_curso.ds_formula_2_bim%TYPE := PN_ds_formula_2_09_IN;
PN_ds_formula_3_10  s_curso.ds_formula_3_bim%TYPE := PN_ds_formula_3_10_IN;
PN_ds_formula_4_11  s_curso.ds_formula_4_bim%TYPE := PN_ds_formula_4_11_IN;
PN_ds_formula_1_12  s_curso.ds_formula_1_sem%TYPE := PN_ds_formula_1_12_IN;
PN_ds_formula_2_13  s_curso.ds_formula_2_sem%TYPE := PN_ds_formula_2_13_IN;
PN_ds_form_md_an14  s_curso.ds_form_md_anual%TYPE := PN_ds_form_md_an14_IN;
PN_ds_form_nota_15  s_curso.ds_form_nota_final%TYPE := PN_ds_form_nota_15_IN;
PN_st_ano_letivo16  s_curso.st_ano_letivo_enc%TYPE := PN_st_ano_letivo16_IN;
PN_nu_media_nota17  s_curso.nu_media_nota%TYPE := PN_nu_media_nota17_IN;
PN_ds_nota_acumu18  s_curso.ds_nota_acumulada%TYPE := PN_ds_nota_acumu18_IN;
PN_tp_recalc_md_19  s_curso.tp_recalc_md_sem%TYPE := PN_tp_recalc_md_19_IN;
PN_st_arred_nota20  s_curso.st_arred_nota_sem%TYPE := PN_st_arred_nota20_IN;
PN_nu_md_minima_21  s_curso.nu_md_minima_2_sem%TYPE := PN_nu_md_minima_21_IN;
PN_nu_mat_rec_fi22  s_curso.nu_mat_rec_final%TYPE := PN_nu_mat_rec_fi22_IN;
PN_nu_freq_minim23  s_curso.nu_freq_minima_obr%TYPE := PN_nu_freq_minim23_IN;
PN_tp_frequencia24  s_curso.tp_frequencia_min%TYPE := PN_tp_frequencia24_IN;
PN_nu_md_min_fre25  s_curso.nu_md_min_freq_men%TYPE := PN_nu_md_min_fre25_IN;
PN_st_aula_sabad26  s_curso.st_aula_sabado%TYPE := PN_st_aula_sabad26_IN;
PN_ds_parecer_le27  s_curso.ds_parecer_legal%TYPE := PN_ds_parecer_le27_IN;
PN_nu_dias_letiv28  s_curso.nu_dias_letivos_b1%TYPE := PN_nu_dias_letiv28_IN;
PN_nu_dias_letiv29  s_curso.nu_dias_letivos_b2%TYPE := PN_nu_dias_letiv29_IN;
PN_nu_dias_letiv30  s_curso.nu_dias_letivos_b3%TYPE := PN_nu_dias_letiv30_IN;
PN_nu_dias_letiv31  s_curso.nu_dias_letivos_b4%TYPE := PN_nu_dias_letiv31_IN;
PN_nu_md_min_rec32  s_curso.nu_md_min_rec_esp%TYPE := PN_nu_md_min_rec32_IN;
PN_ds_formula_ap33  s_curso.ds_formula_apos_s1%TYPE := PN_ds_formula_ap33_IN;
PN_ds_formula_ap34  s_curso.ds_formula_apos_s2%TYPE := PN_ds_formula_ap34_IN;
PN_ano_sem35        s_curso.ano_sem%TYPE := PN_ano_sem35_IN;
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
vr_co_curso00       CHAR(10);
vr_ds_curso01       CHAR(70);
vr_co_unidade02     CHAR(10);
vr_tp_recuperaca03  CHAR(20);
vr_co_grade_curr04  CHAR(20);
vr_co_tipo_curso05  CHAR(10);
vr_ds_formula_1_06  CHAR(110);
vr_ano07            CHAR(10);
vr_turno08          CHAR(10);
vr_ds_formula_2_09  CHAR(110);
vr_ds_formula_3_10  CHAR(110);
vr_ds_formula_4_11  CHAR(110);
vr_ds_formula_1_12  CHAR(110);
vr_ds_formula_2_13  CHAR(110);
vr_ds_form_md_an14  CHAR(110);
vr_ds_form_nota_15  CHAR(110);
vr_st_ano_letivo16  CHAR(10);
vr_nu_media_nota17  CHAR(10);
vr_ds_nota_acumu18  CHAR(20);
vr_tp_recalc_md_19  CHAR(10);
vr_st_arred_nota20  CHAR(10);
vr_nu_md_minima_21  CHAR(10);
vr_nu_mat_rec_fi22  CHAR(10);
vr_nu_freq_minim23  CHAR(10);
vr_tp_frequencia24  CHAR(35);
vr_nu_md_min_fre25  CHAR(10);
vr_st_aula_sabad26  CHAR(10);
vr_ds_parecer_le27  CHAR(60);
vr_nu_dias_letiv28  CHAR(10);
vr_nu_dias_letiv29  CHAR(10);
vr_nu_dias_letiv30  CHAR(10);
vr_nu_dias_letiv31  CHAR(10);
vr_nu_md_min_rec32  CHAR(10);
vr_ds_formula_ap33  CHAR(110);
vr_ds_formula_ap34  CHAR(110);
vr_ano_sem35        CHAR(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	IF p_op = 'ins' THEN
		IF pn_co_curso00 IS NULL THEN
			vr_co_curso00 := 'null';
		ELSE
			vr_co_curso00 := pn_co_curso00;
		END IF;
		IF pn_ds_curso01 IS NULL THEN
			vr_ds_curso01 := 'null';
		ELSE
			vr_ds_curso01 := pn_ds_curso01;
		END IF;
		IF pn_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := pn_co_unidade02;
		END IF;
		IF pn_tp_recuperaca03 IS NULL THEN
			vr_tp_recuperaca03 := 'null';
		ELSE
			vr_tp_recuperaca03 := pn_tp_recuperaca03;
		END IF;
		IF pn_co_grade_curr04 IS NULL THEN
			vr_co_grade_curr04 := 'null';
		ELSE
			vr_co_grade_curr04 := pn_co_grade_curr04;
		END IF;
		IF pn_co_tipo_curso05 IS NULL THEN
			vr_co_tipo_curso05 := 'null';
		ELSE
			vr_co_tipo_curso05 := pn_co_tipo_curso05;
		END IF;
		IF pn_ds_formula_1_06 IS NULL THEN
			vr_ds_formula_1_06 := 'null';
		ELSE
			vr_ds_formula_1_06 := pn_ds_formula_1_06;
		END IF;
		IF pn_ano07 IS NULL THEN
			vr_ano07 := 'null';
		ELSE
			vr_ano07 := pn_ano07;
		END IF;
		IF pn_turno08 IS NULL THEN
			vr_turno08 := 'null';
		ELSE
			vr_turno08 := pn_turno08;
		END IF;
		IF pn_ds_formula_2_09 IS NULL THEN
			vr_ds_formula_2_09 := 'null';
		ELSE
			vr_ds_formula_2_09 := pn_ds_formula_2_09;
		END IF;
		IF pn_ds_formula_3_10 IS NULL THEN
			vr_ds_formula_3_10 := 'null';
		ELSE
			vr_ds_formula_3_10 := pn_ds_formula_3_10;
		END IF;
		IF pn_ds_formula_4_11 IS NULL THEN
			vr_ds_formula_4_11 := 'null';
		ELSE
			vr_ds_formula_4_11 := pn_ds_formula_4_11;
		END IF;
		IF pn_ds_formula_1_12 IS NULL THEN
			vr_ds_formula_1_12 := 'null';
		ELSE
			vr_ds_formula_1_12 := pn_ds_formula_1_12;
		END IF;
		IF pn_ds_formula_2_13 IS NULL THEN
			vr_ds_formula_2_13 := 'null';
		ELSE
			vr_ds_formula_2_13 := pn_ds_formula_2_13;
		END IF;
		IF pn_ds_form_md_an14 IS NULL THEN
			vr_ds_form_md_an14 := 'null';
		ELSE
			vr_ds_form_md_an14 := pn_ds_form_md_an14;
		END IF;
		IF pn_ds_form_nota_15 IS NULL THEN
			vr_ds_form_nota_15 := 'null';
		ELSE
			vr_ds_form_nota_15 := pn_ds_form_nota_15;
		END IF;
		IF pn_st_ano_letivo16 IS NULL THEN
			vr_st_ano_letivo16 := 'null';
		ELSE
			vr_st_ano_letivo16 := pn_st_ano_letivo16;
		END IF;
		IF pn_nu_media_nota17 IS NULL THEN
			vr_nu_media_nota17 := 'null';
		ELSE
			vr_nu_media_nota17 := pn_nu_media_nota17;
		END IF;
		IF pn_ds_nota_acumu18 IS NULL THEN
			vr_ds_nota_acumu18 := 'null';
		ELSE
			vr_ds_nota_acumu18 := pn_ds_nota_acumu18;
		END IF;
		IF pn_tp_recalc_md_19 IS NULL THEN
			vr_tp_recalc_md_19 := 'null';
		ELSE
			vr_tp_recalc_md_19 := pn_tp_recalc_md_19;
		END IF;
		IF pn_st_arred_nota20 IS NULL THEN
			vr_st_arred_nota20 := 'null';
		ELSE
			vr_st_arred_nota20 := pn_st_arred_nota20;
		END IF;
		IF pn_nu_md_minima_21 IS NULL THEN
			vr_nu_md_minima_21 := 'null';
		ELSE
			vr_nu_md_minima_21 := pn_nu_md_minima_21;
		END IF;
		IF pn_nu_mat_rec_fi22 IS NULL THEN
			vr_nu_mat_rec_fi22 := 'null';
		ELSE
			vr_nu_mat_rec_fi22 := pn_nu_mat_rec_fi22;
		END IF;
		IF pn_nu_freq_minim23 IS NULL THEN
			vr_nu_freq_minim23 := 'null';
		ELSE
			vr_nu_freq_minim23 := pn_nu_freq_minim23;
		END IF;
		IF pn_tp_frequencia24 IS NULL THEN
			vr_tp_frequencia24 := 'null';
		ELSE
			vr_tp_frequencia24 := pn_tp_frequencia24;
		END IF;
		IF pn_nu_md_min_fre25 IS NULL THEN
			vr_nu_md_min_fre25 := 'null';
		ELSE
			vr_nu_md_min_fre25 := pn_nu_md_min_fre25;
		END IF;
		IF pn_st_aula_sabad26 IS NULL THEN
			vr_st_aula_sabad26 := 'null';
		ELSE
			vr_st_aula_sabad26 := pn_st_aula_sabad26;
		END IF;
		IF pn_ds_parecer_le27 IS NULL THEN
			vr_ds_parecer_le27 := 'null';
		ELSE
			vr_ds_parecer_le27 := pn_ds_parecer_le27;
		END IF;
		IF pn_nu_dias_letiv28 IS NULL THEN
			vr_nu_dias_letiv28 := 'null';
		ELSE
			vr_nu_dias_letiv28 := pn_nu_dias_letiv28;
		END IF;
		IF pn_nu_dias_letiv29 IS NULL THEN
			vr_nu_dias_letiv29 := 'null';
		ELSE
			vr_nu_dias_letiv29 := pn_nu_dias_letiv29;
		END IF;
		IF pn_nu_dias_letiv30 IS NULL THEN
			vr_nu_dias_letiv30 := 'null';
		ELSE
			vr_nu_dias_letiv30 := pn_nu_dias_letiv30;
		END IF;
		IF pn_nu_dias_letiv31 IS NULL THEN
			vr_nu_dias_letiv31 := 'null';
		ELSE
			vr_nu_dias_letiv31 := pn_nu_dias_letiv31;
		END IF;
		IF pn_nu_md_min_rec32 IS NULL THEN
			vr_nu_md_min_rec32 := 'null';
		ELSE
			vr_nu_md_min_rec32 := pn_nu_md_min_rec32;
		END IF;
		IF pn_ds_formula_ap33 IS NULL THEN
			vr_ds_formula_ap33 := 'null';
		ELSE
			vr_ds_formula_ap33 := pn_ds_formula_ap33;
		END IF;
		IF pn_ds_formula_ap34 IS NULL THEN
			vr_ds_formula_ap34 := 'null';
		ELSE
			vr_ds_formula_ap34 := pn_ds_formula_ap34;
		END IF;
		IF pn_ano_sem35 IS NULL THEN
			vr_ano_sem35 := 'null';
		ELSE
			vr_ano_sem35 := pn_ano_sem35;
		END IF;
		v_sql1 := 'insert into s_curso(co_curso, ds_curso, co_unidade, tp_recuperacao, CO_GRADE_CURRICULAR, co_tipo_curso, ds_formula_1_bim, ano, turno, ds_formula_2_bim, ' || 'ds_formula_3_bim, ds_formula_4_bim, ds_formula_1_sem, ds_formula_2_sem, DS_FORMULA_MEDIA_ANUAL, DS_FORMULA_NOTA_FINAL, ST_ANO_LETIVO_ENCERRADO, nu_media_nota, ds_nota_acumulada, TP_RECALCULA_MEDIA_SEM, ST_ARREDONDA_NOTA_SEM, NU_MEDIA_MINIMA_2_SEM, ' || 'NU_MATERIAS_REC_FINAL, nu_freq_minima_obr, TP_FREQUENCIA_MINIMA, NU_MEDIA_MIN_FREQ_MENOR, st_aula_sabado, ds_parecer_legal, nu_dias_letivos_b1, nu_dias_letivos_b2, nu_dias_letivos_b3, nu_dias_letivos_b4, ' || 'NU_MEDIA_MIN_REC_ESP, ds_formula_apos_s1, ds_formula_apos_s2, ano_sem) values (';
		v_sql2 := RTRIM(vr_co_curso00) || ',' || '"' || RTRIM(vr_ds_curso01) || '"' || ',' || '"' || RTRIM(vr_co_unidade02) || '"' || ',' || '"' || RTRIM(vr_tp_recuperaca03) || '"' || ',' || '"' || RTRIM(vr_co_grade_curr04) || '"' || ',' || RTRIM(vr_co_tipo_curso05) || ',' || '"' || RTRIM(vr_ds_formula_1_06) || '"' || ',' || RTRIM(vr_ano07) || ',' || '"' || RTRIM(vr_turno08) || '"' || ',';
		v_sql3 := '"' || RTRIM(vr_ds_formula_2_09) || '"' || ',' || '"' || RTRIM(vr_ds_formula_3_10) || '"' || ',';
		v_sql4 := '"' || RTRIM(vr_ds_formula_4_11) || '"' || ',' || '"' || RTRIM(vr_ds_formula_1_12) || '"' || ',';
		v_sql5 := '"' || RTRIM(vr_ds_formula_2_13) || '"' || ',' || '"' || RTRIM(vr_ds_form_md_an14) || '"' || ',';
		v_sql6 := '"' || RTRIM(vr_ds_form_nota_15) || '"' || ',' || '"' || RTRIM(vr_st_ano_letivo16) || '"' || ',' || '"' || RTRIM(vr_nu_media_nota17) || '"' || ',' || '"' || RTRIM(vr_ds_nota_acumu18) || '"' || ',' || '"' || RTRIM(vr_tp_recalc_md_19) || '"' || ',' || '"' || RTRIM(vr_st_arred_nota20) || '"' || ',' || RTRIM(vr_nu_md_minima_21) || ',' || RTRIM(vr_nu_mat_rec_fi22) || ',' || RTRIM(vr_nu_freq_minim23) || ',' || '"' || RTRIM(vr_tp_frequencia24) || '"' || ',' || RTRIM(vr_nu_md_min_fre25) || ',' || '"' || RTRIM(vr_st_aula_sabad26) || '"' || ',';
		v_sql7 := '"' || RTRIM(vr_ds_parecer_le27) || '"' || ',' || '"' || RTRIM(vr_nu_dias_letiv28) || '"' || ',' || '"' || RTRIM(vr_nu_dias_letiv29) || '"' || ',' || '"' || RTRIM(vr_nu_dias_letiv30) || '"' || ',' || '"' || RTRIM(vr_nu_dias_letiv31) || '"' || ',' || '"' || RTRIM(vr_nu_md_min_rec32) || '"' || ',';
		v_sql8 := '"' || RTRIM(vr_ds_formula_ap33) || '"' || ',' || '"' || RTRIM(vr_ds_formula_ap34) || '"' || ',' || '"' || RTRIM(vr_ano_sem35) || '"' || ');';
		v_sql := v_sql1 || v_sql2 || v_sql3 || v_sql4 || v_sql5 || v_sql6 || v_sql7 || v_sql8;
	ELSIF p_op = 'del' THEN
		IF pa_co_curso00 IS NULL THEN
			vr_co_curso00 := 'null';
		ELSE
			vr_co_curso00 := pa_co_curso00;
		END IF;
		IF pa_co_unidade02 IS NULL THEN
			vr_co_unidade02 := 'null';
		ELSE
			vr_co_unidade02 := '"' || RTRIM(pa_co_unidade02) || '"';
		END IF;
		IF pa_ano_sem35 IS NULL THEN
			vr_ano_sem35 := 'null';
		ELSE
			vr_ano_sem35 := '"' || RTRIM(pa_ano_sem35) || '"';
		END IF;
		v_sql1 := '  delete from s_curso where co_curso = ' || RTRIM(vr_co_curso00) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || '  and ano_sem = ' || RTRIM(vr_ano_sem35) || ';';
		v_sql2 := '';
		v_sql3 := '';
		v_sql := v_sql1 || v_sql2 || v_sql3;
	ELSIF p_op = 'upd' THEN
		IF pn_co_curso00 IS NULL
		AND pa_co_curso00 IS NULL THEN
			vr_co_curso00 := 'null';
		END IF;
		IF pn_co_curso00 IS NULL
		AND pa_co_curso00 IS NOT NULL THEN
			vr_co_curso00 := 'null';
		END IF;
		IF pn_co_curso00 IS NOT NULL
		AND pa_co_curso00 IS NULL THEN
			vr_co_curso00 := pn_co_curso00;
		END IF;
		IF pn_co_curso00 IS NOT NULL
		AND pa_co_curso00 IS NOT NULL THEN
			IF pa_co_curso00 <> pn_co_curso00 THEN
				vr_co_curso00 := pn_co_curso00;
			ELSE
				vr_co_curso00 := pa_co_curso00;
			END IF;
		END IF;
		IF pn_ds_curso01 IS NULL
		AND pa_ds_curso01 IS NULL THEN
			vr_ds_curso01 := 'null';
		END IF;
		IF pn_ds_curso01 IS NULL
		AND pa_ds_curso01 IS NOT NULL THEN
			vr_ds_curso01 := 'null';
		END IF;
		IF pn_ds_curso01 IS NOT NULL
		AND pa_ds_curso01 IS NULL THEN
			vr_ds_curso01 := '"' || RTRIM(pn_ds_curso01) || '"';
		END IF;
		IF pn_ds_curso01 IS NOT NULL
		AND pa_ds_curso01 IS NOT NULL THEN
			IF pa_ds_curso01 <> pn_ds_curso01 THEN
				vr_ds_curso01 := '"' || RTRIM(pn_ds_curso01) || '"';
			ELSE
				vr_ds_curso01 := '"' || RTRIM(pa_ds_curso01) || '"';
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
		IF pn_tp_recuperaca03 IS NULL
		AND pa_tp_recuperaca03 IS NULL THEN
			vr_tp_recuperaca03 := 'null';
		END IF;
		IF pn_tp_recuperaca03 IS NULL
		AND pa_tp_recuperaca03 IS NOT NULL THEN
			vr_tp_recuperaca03 := 'null';
		END IF;
		IF pn_tp_recuperaca03 IS NOT NULL
		AND pa_tp_recuperaca03 IS NULL THEN
			vr_tp_recuperaca03 := '"' || RTRIM(pn_tp_recuperaca03) || '"';
		END IF;
		IF pn_tp_recuperaca03 IS NOT NULL
		AND pa_tp_recuperaca03 IS NOT NULL THEN
			IF pa_tp_recuperaca03 <> pn_tp_recuperaca03 THEN
				vr_tp_recuperaca03 := '"' || RTRIM(pn_tp_recuperaca03) || '"';
			ELSE
				vr_tp_recuperaca03 := '"' || RTRIM(pa_tp_recuperaca03) || '"';
			END IF;
		END IF;
		IF pn_co_grade_curr04 IS NULL
		AND pa_co_grade_curr04 IS NULL THEN
			vr_co_grade_curr04 := 'null';
		END IF;
		IF pn_co_grade_curr04 IS NULL
		AND pa_co_grade_curr04 IS NOT NULL THEN
			vr_co_grade_curr04 := 'null';
		END IF;
		IF pn_co_grade_curr04 IS NOT NULL
		AND pa_co_grade_curr04 IS NULL THEN
			vr_co_grade_curr04 := '"' || RTRIM(pn_co_grade_curr04) || '"';
		END IF;
		IF pn_co_grade_curr04 IS NOT NULL
		AND pa_co_grade_curr04 IS NOT NULL THEN
			IF pa_co_grade_curr04 <> pn_co_grade_curr04 THEN
				vr_co_grade_curr04 := '"' || RTRIM(pn_co_grade_curr04) || '"';
			ELSE
				vr_co_grade_curr04 := '"' || RTRIM(pa_co_grade_curr04) || '"';
			END IF;
		END IF;
		IF pn_co_tipo_curso05 IS NULL
		AND pa_co_tipo_curso05 IS NULL THEN
			vr_co_tipo_curso05 := 'null';
		END IF;
		IF pn_co_tipo_curso05 IS NULL
		AND pa_co_tipo_curso05 IS NOT NULL THEN
			vr_co_tipo_curso05 := 'null';
		END IF;
		IF pn_co_tipo_curso05 IS NOT NULL
		AND pa_co_tipo_curso05 IS NULL THEN
			vr_co_tipo_curso05 := pn_co_tipo_curso05;
		END IF;
		IF pn_co_tipo_curso05 IS NOT NULL
		AND pa_co_tipo_curso05 IS NOT NULL THEN
			IF pa_co_tipo_curso05 <> pn_co_tipo_curso05 THEN
				vr_co_tipo_curso05 := pn_co_tipo_curso05;
			ELSE
				vr_co_tipo_curso05 := pa_co_tipo_curso05;
			END IF;
		END IF;
		IF pn_ds_formula_1_06 IS NULL
		AND pa_ds_formula_1_06 IS NULL THEN
			vr_ds_formula_1_06 := 'null';
		END IF;
		IF pn_ds_formula_1_06 IS NULL
		AND pa_ds_formula_1_06 IS NOT NULL THEN
			vr_ds_formula_1_06 := 'null';
		END IF;
		IF pn_ds_formula_1_06 IS NOT NULL
		AND pa_ds_formula_1_06 IS NULL THEN
			vr_ds_formula_1_06 := '"' || RTRIM(pn_ds_formula_1_06) || '"';
		END IF;
		IF pn_ds_formula_1_06 IS NOT NULL
		AND pa_ds_formula_1_06 IS NOT NULL THEN
			IF pa_ds_formula_1_06 <> pn_ds_formula_1_06 THEN
				vr_ds_formula_1_06 := '"' || RTRIM(pn_ds_formula_1_06) || '"';
			ELSE
				vr_ds_formula_1_06 := '"' || RTRIM(pa_ds_formula_1_06) || '"';
			END IF;
		END IF;
		IF pn_ano07 IS NULL
		AND pa_ano07 IS NULL THEN
			vr_ano07 := 'null';
		END IF;
		IF pn_ano07 IS NULL
		AND pa_ano07 IS NOT NULL THEN
			vr_ano07 := 'null';
		END IF;
		IF pn_ano07 IS NOT NULL
		AND pa_ano07 IS NULL THEN
			vr_ano07 := pn_ano07;
		END IF;
		IF pn_ano07 IS NOT NULL
		AND pa_ano07 IS NOT NULL THEN
			IF pa_ano07 <> pn_ano07 THEN
				vr_ano07 := pn_ano07;
			ELSE
				vr_ano07 := pa_ano07;
			END IF;
		END IF;
		IF pn_turno08 IS NULL
		AND pa_turno08 IS NULL THEN
			vr_turno08 := 'null';
		END IF;
		IF pn_turno08 IS NULL
		AND pa_turno08 IS NOT NULL THEN
			vr_turno08 := 'null';
		END IF;
		IF pn_turno08 IS NOT NULL
		AND pa_turno08 IS NULL THEN
			vr_turno08 := '"' || RTRIM(pn_turno08) || '"';
		END IF;
		IF pn_turno08 IS NOT NULL
		AND pa_turno08 IS NOT NULL THEN
			IF pa_turno08 <> pn_turno08 THEN
				vr_turno08 := '"' || RTRIM(pn_turno08) || '"';
			ELSE
				vr_turno08 := '"' || RTRIM(pa_turno08) || '"';
			END IF;
		END IF;
		IF pn_ds_formula_2_09 IS NULL
		AND pa_ds_formula_2_09 IS NULL THEN
			vr_ds_formula_2_09 := 'null';
		END IF;
		IF pn_ds_formula_2_09 IS NULL
		AND pa_ds_formula_2_09 IS NOT NULL THEN
			vr_ds_formula_2_09 := 'null';
		END IF;
		IF pn_ds_formula_2_09 IS NOT NULL
		AND pa_ds_formula_2_09 IS NULL THEN
			vr_ds_formula_2_09 := '"' || RTRIM(pn_ds_formula_2_09) || '"';
		END IF;
		IF pn_ds_formula_2_09 IS NOT NULL
		AND pa_ds_formula_2_09 IS NOT NULL THEN
			IF pa_ds_formula_2_09 <> pn_ds_formula_2_09 THEN
				vr_ds_formula_2_09 := '"' || RTRIM(pn_ds_formula_2_09) || '"';
			ELSE
				vr_ds_formula_2_09 := '"' || RTRIM(pa_ds_formula_2_09) || '"';
			END IF;
		END IF;
		IF pn_ds_formula_3_10 IS NULL
		AND pa_ds_formula_3_10 IS NULL THEN
			vr_ds_formula_3_10 := 'null';
		END IF;
		IF pn_ds_formula_3_10 IS NULL
		AND pa_ds_formula_3_10 IS NOT NULL THEN
			vr_ds_formula_3_10 := 'null';
		END IF;
		IF pn_ds_formula_3_10 IS NOT NULL
		AND pa_ds_formula_3_10 IS NULL THEN
			vr_ds_formula_3_10 := '"' || RTRIM(pn_ds_formula_3_10) || '"';
		END IF;
		IF pn_ds_formula_3_10 IS NOT NULL
		AND pa_ds_formula_3_10 IS NOT NULL THEN
			IF pa_ds_formula_3_10 <> pn_ds_formula_3_10 THEN
				vr_ds_formula_3_10 := '"' || RTRIM(pn_ds_formula_3_10) || '"';
			ELSE
				vr_ds_formula_3_10 := '"' || RTRIM(pa_ds_formula_3_10) || '"';
			END IF;
		END IF;
		IF pn_ds_formula_4_11 IS NULL
		AND pa_ds_formula_4_11 IS NULL THEN
			vr_ds_formula_4_11 := 'null';
		END IF;
		IF pn_ds_formula_4_11 IS NULL
		AND pa_ds_formula_4_11 IS NOT NULL THEN
			vr_ds_formula_4_11 := 'null';
		END IF;
		IF pn_ds_formula_4_11 IS NOT NULL
		AND pa_ds_formula_4_11 IS NULL THEN
			vr_ds_formula_4_11 := '"' || RTRIM(pn_ds_formula_4_11) || '"';
		END IF;
		IF pn_ds_formula_4_11 IS NOT NULL
		AND pa_ds_formula_4_11 IS NOT NULL THEN
			IF pa_ds_formula_4_11 <> pn_ds_formula_4_11 THEN
				vr_ds_formula_4_11 := '"' || RTRIM(pn_ds_formula_4_11) || '"';
			ELSE
				vr_ds_formula_4_11 := '"' || RTRIM(pa_ds_formula_4_11) || '"';
			END IF;
		END IF;
		IF pn_ds_formula_1_12 IS NULL
		AND pa_ds_formula_1_12 IS NULL THEN
			vr_ds_formula_1_12 := 'null';
		END IF;
		IF pn_ds_formula_1_12 IS NULL
		AND pa_ds_formula_1_12 IS NOT NULL THEN
			vr_ds_formula_1_12 := 'null';
		END IF;
		IF pn_ds_formula_1_12 IS NOT NULL
		AND pa_ds_formula_1_12 IS NULL THEN
			vr_ds_formula_1_12 := '"' || RTRIM(pn_ds_formula_1_12) || '"';
		END IF;
		IF pn_ds_formula_1_12 IS NOT NULL
		AND pa_ds_formula_1_12 IS NOT NULL THEN
			IF pa_ds_formula_1_12 <> pn_ds_formula_1_12 THEN
				vr_ds_formula_1_12 := '"' || RTRIM(pn_ds_formula_1_12) || '"';
			ELSE
				vr_ds_formula_1_12 := '"' || RTRIM(pa_ds_formula_1_12) || '"';
			END IF;
		END IF;
		IF pn_ds_formula_2_13 IS NULL
		AND pa_ds_formula_2_13 IS NULL THEN
			vr_ds_formula_2_13 := 'null';
		END IF;
		IF pn_ds_formula_2_13 IS NULL
		AND pa_ds_formula_2_13 IS NOT NULL THEN
			vr_ds_formula_2_13 := 'null';
		END IF;
		IF pn_ds_formula_2_13 IS NOT NULL
		AND pa_ds_formula_2_13 IS NULL THEN
			vr_ds_formula_2_13 := '"' || RTRIM(pn_ds_formula_2_13) || '"';
		END IF;
		IF pn_ds_formula_2_13 IS NOT NULL
		AND pa_ds_formula_2_13 IS NOT NULL THEN
			IF pa_ds_formula_2_13 <> pn_ds_formula_2_13 THEN
				vr_ds_formula_2_13 := '"' || RTRIM(pn_ds_formula_2_13) || '"';
			ELSE
				vr_ds_formula_2_13 := '"' || RTRIM(pa_ds_formula_2_13) || '"';
			END IF;
		END IF;
		IF pn_ds_form_md_an14 IS NULL
		AND pa_ds_form_md_an14 IS NULL THEN
			vr_ds_form_md_an14 := 'null';
		END IF;
		IF pn_ds_form_md_an14 IS NULL
		AND pa_ds_form_md_an14 IS NOT NULL THEN
			vr_ds_form_md_an14 := 'null';
		END IF;
		IF pn_ds_form_md_an14 IS NOT NULL
		AND pa_ds_form_md_an14 IS NULL THEN
			vr_ds_form_md_an14 := '"' || RTRIM(pn_ds_form_md_an14) || '"';
		END IF;
		IF pn_ds_form_md_an14 IS NOT NULL
		AND pa_ds_form_md_an14 IS NOT NULL THEN
			IF pa_ds_form_md_an14 <> pn_ds_form_md_an14 THEN
				vr_ds_form_md_an14 := '"' || RTRIM(pn_ds_form_md_an14) || '"';
			ELSE
				vr_ds_form_md_an14 := '"' || RTRIM(pa_ds_form_md_an14) || '"';
			END IF;
		END IF;
		IF pn_ds_form_nota_15 IS NULL
		AND pa_ds_form_nota_15 IS NULL THEN
			vr_ds_form_nota_15 := 'null';
		END IF;
		IF pn_ds_form_nota_15 IS NULL
		AND pa_ds_form_nota_15 IS NOT NULL THEN
			vr_ds_form_nota_15 := 'null';
		END IF;
		IF pn_ds_form_nota_15 IS NOT NULL
		AND pa_ds_form_nota_15 IS NULL THEN
			vr_ds_form_nota_15 := '"' || RTRIM(pn_ds_form_nota_15) || '"';
		END IF;
		IF pn_ds_form_nota_15 IS NOT NULL
		AND pa_ds_form_nota_15 IS NOT NULL THEN
			IF pa_ds_form_nota_15 <> pn_ds_form_nota_15 THEN
				vr_ds_form_nota_15 := '"' || RTRIM(pn_ds_form_nota_15) || '"';
			ELSE
				vr_ds_form_nota_15 := '"' || RTRIM(pa_ds_form_nota_15) || '"';
			END IF;
		END IF;
		IF pn_st_ano_letivo16 IS NULL
		AND pa_st_ano_letivo16 IS NULL THEN
			vr_st_ano_letivo16 := 'null';
		END IF;
		IF pn_st_ano_letivo16 IS NULL
		AND pa_st_ano_letivo16 IS NOT NULL THEN
			vr_st_ano_letivo16 := 'null';
		END IF;
		IF pn_st_ano_letivo16 IS NOT NULL
		AND pa_st_ano_letivo16 IS NULL THEN
			vr_st_ano_letivo16 := '"' || RTRIM(pn_st_ano_letivo16) || '"';
		END IF;
		IF pn_st_ano_letivo16 IS NOT NULL
		AND pa_st_ano_letivo16 IS NOT NULL THEN
			IF pa_st_ano_letivo16 <> pn_st_ano_letivo16 THEN
				vr_st_ano_letivo16 := '"' || RTRIM(pn_st_ano_letivo16) || '"';
			ELSE
				vr_st_ano_letivo16 := '"' || RTRIM(pa_st_ano_letivo16) || '"';
			END IF;
		END IF;
		IF pn_nu_media_nota17 IS NULL
		AND pa_nu_media_nota17 IS NULL THEN
			vr_nu_media_nota17 := 'null';
		END IF;
		IF pn_nu_media_nota17 IS NULL
		AND pa_nu_media_nota17 IS NOT NULL THEN
			vr_nu_media_nota17 := 'null';
		END IF;
		IF pn_nu_media_nota17 IS NOT NULL
		AND pa_nu_media_nota17 IS NULL THEN
			vr_nu_media_nota17 := '"' || RTRIM(pn_nu_media_nota17) || '"';
		END IF;
		IF pn_nu_media_nota17 IS NOT NULL
		AND pa_nu_media_nota17 IS NOT NULL THEN
			IF pa_nu_media_nota17 <> pn_nu_media_nota17 THEN
				vr_nu_media_nota17 := '"' || RTRIM(pn_nu_media_nota17) || '"';
			ELSE
				vr_nu_media_nota17 := '"' || RTRIM(pa_nu_media_nota17) || '"';
			END IF;
		END IF;
		IF pn_ds_nota_acumu18 IS NULL
		AND pa_ds_nota_acumu18 IS NULL THEN
			vr_ds_nota_acumu18 := 'null';
		END IF;
		IF pn_ds_nota_acumu18 IS NULL
		AND pa_ds_nota_acumu18 IS NOT NULL THEN
			vr_ds_nota_acumu18 := 'null';
		END IF;
		IF pn_ds_nota_acumu18 IS NOT NULL
		AND pa_ds_nota_acumu18 IS NULL THEN
			vr_ds_nota_acumu18 := '"' || RTRIM(pn_ds_nota_acumu18) || '"';
		END IF;
		IF pn_ds_nota_acumu18 IS NOT NULL
		AND pa_ds_nota_acumu18 IS NOT NULL THEN
			IF pa_ds_nota_acumu18 <> pn_ds_nota_acumu18 THEN
				vr_ds_nota_acumu18 := '"' || RTRIM(pn_ds_nota_acumu18) || '"';
			ELSE
				vr_ds_nota_acumu18 := '"' || RTRIM(pa_ds_nota_acumu18) || '"';
			END IF;
		END IF;
		IF pn_tp_recalc_md_19 IS NULL
		AND pa_tp_recalc_md_19 IS NULL THEN
			vr_tp_recalc_md_19 := 'null';
		END IF;
		IF pn_tp_recalc_md_19 IS NULL
		AND pa_tp_recalc_md_19 IS NOT NULL THEN
			vr_tp_recalc_md_19 := 'null';
		END IF;
		IF pn_tp_recalc_md_19 IS NOT NULL
		AND pa_tp_recalc_md_19 IS NULL THEN
			vr_tp_recalc_md_19 := '"' || RTRIM(pn_tp_recalc_md_19) || '"';
		END IF;
		IF pn_tp_recalc_md_19 IS NOT NULL
		AND pa_tp_recalc_md_19 IS NOT NULL THEN
			IF pa_tp_recalc_md_19 <> pn_tp_recalc_md_19 THEN
				vr_tp_recalc_md_19 := '"' || RTRIM(pn_tp_recalc_md_19) || '"';
			ELSE
				vr_tp_recalc_md_19 := '"' || RTRIM(pa_tp_recalc_md_19) || '"';
			END IF;
		END IF;
		IF pn_st_arred_nota20 IS NULL
		AND pa_st_arred_nota20 IS NULL THEN
			vr_st_arred_nota20 := 'null';
		END IF;
		IF pn_st_arred_nota20 IS NULL
		AND pa_st_arred_nota20 IS NOT NULL THEN
			vr_st_arred_nota20 := 'null';
		END IF;
		IF pn_st_arred_nota20 IS NOT NULL
		AND pa_st_arred_nota20 IS NULL THEN
			vr_st_arred_nota20 := '"' || RTRIM(pn_st_arred_nota20) || '"';
		END IF;
		IF pn_st_arred_nota20 IS NOT NULL
		AND pa_st_arred_nota20 IS NOT NULL THEN
			IF pa_st_arred_nota20 <> pn_st_arred_nota20 THEN
				vr_st_arred_nota20 := '"' || RTRIM(pn_st_arred_nota20) || '"';
			ELSE
				vr_st_arred_nota20 := '"' || RTRIM(pa_st_arred_nota20) || '"';
			END IF;
		END IF;
		IF pn_nu_md_minima_21 IS NULL
		AND pa_nu_md_minima_21 IS NULL THEN
			vr_nu_md_minima_21 := 'null';
		END IF;
		IF pn_nu_md_minima_21 IS NULL
		AND pa_nu_md_minima_21 IS NOT NULL THEN
			vr_nu_md_minima_21 := 'null';
		END IF;
		IF pn_nu_md_minima_21 IS NOT NULL
		AND pa_nu_md_minima_21 IS NULL THEN
			vr_nu_md_minima_21 := pn_nu_md_minima_21;
		END IF;
		IF pn_nu_md_minima_21 IS NOT NULL
		AND pa_nu_md_minima_21 IS NOT NULL THEN
			IF pa_nu_md_minima_21 <> pn_nu_md_minima_21 THEN
				vr_nu_md_minima_21 := pn_nu_md_minima_21;
			ELSE
				vr_nu_md_minima_21 := pa_nu_md_minima_21;
			END IF;
		END IF;
		IF pn_nu_mat_rec_fi22 IS NULL
		AND pa_nu_mat_rec_fi22 IS NULL THEN
			vr_nu_mat_rec_fi22 := 'null';
		END IF;
		IF pn_nu_mat_rec_fi22 IS NULL
		AND pa_nu_mat_rec_fi22 IS NOT NULL THEN
			vr_nu_mat_rec_fi22 := 'null';
		END IF;
		IF pn_nu_mat_rec_fi22 IS NOT NULL
		AND pa_nu_mat_rec_fi22 IS NULL THEN
			vr_nu_mat_rec_fi22 := pn_nu_mat_rec_fi22;
		END IF;
		IF pn_nu_mat_rec_fi22 IS NOT NULL
		AND pa_nu_mat_rec_fi22 IS NOT NULL THEN
			IF pa_nu_mat_rec_fi22 <> pn_nu_mat_rec_fi22 THEN
				vr_nu_mat_rec_fi22 := pn_nu_mat_rec_fi22;
			ELSE
				vr_nu_mat_rec_fi22 := pa_nu_mat_rec_fi22;
			END IF;
		END IF;
		IF pn_nu_freq_minim23 IS NULL
		AND pa_nu_freq_minim23 IS NULL THEN
			vr_nu_freq_minim23 := 'null';
		END IF;
		IF pn_nu_freq_minim23 IS NULL
		AND pa_nu_freq_minim23 IS NOT NULL THEN
			vr_nu_freq_minim23 := 'null';
		END IF;
		IF pn_nu_freq_minim23 IS NOT NULL
		AND pa_nu_freq_minim23 IS NULL THEN
			vr_nu_freq_minim23 := pn_nu_freq_minim23;
		END IF;
		IF pn_nu_freq_minim23 IS NOT NULL
		AND pa_nu_freq_minim23 IS NOT NULL THEN
			IF pa_nu_freq_minim23 <> pn_nu_freq_minim23 THEN
				vr_nu_freq_minim23 := pn_nu_freq_minim23;
			ELSE
				vr_nu_freq_minim23 := pa_nu_freq_minim23;
			END IF;
		END IF;
		IF pn_tp_frequencia24 IS NULL
		AND pa_tp_frequencia24 IS NULL THEN
			vr_tp_frequencia24 := 'null';
		END IF;
		IF pn_tp_frequencia24 IS NULL
		AND pa_tp_frequencia24 IS NOT NULL THEN
			vr_tp_frequencia24 := 'null';
		END IF;
		IF pn_tp_frequencia24 IS NOT NULL
		AND pa_tp_frequencia24 IS NULL THEN
			vr_tp_frequencia24 := '"' || RTRIM(pn_tp_frequencia24) || '"';
		END IF;
		IF pn_tp_frequencia24 IS NOT NULL
		AND pa_tp_frequencia24 IS NOT NULL THEN
			IF pa_tp_frequencia24 <> pn_tp_frequencia24 THEN
				vr_tp_frequencia24 := '"' || RTRIM(pn_tp_frequencia24) || '"';
			ELSE
				vr_tp_frequencia24 := '"' || RTRIM(pa_tp_frequencia24) || '"';
			END IF;
		END IF;
		IF pn_nu_md_min_fre25 IS NULL
		AND pa_nu_md_min_fre25 IS NULL THEN
			vr_nu_md_min_fre25 := 'null';
		END IF;
		IF pn_nu_md_min_fre25 IS NULL
		AND pa_nu_md_min_fre25 IS NOT NULL THEN
			vr_nu_md_min_fre25 := 'null';
		END IF;
		IF pn_nu_md_min_fre25 IS NOT NULL
		AND pa_nu_md_min_fre25 IS NULL THEN
			vr_nu_md_min_fre25 := pn_nu_md_min_fre25;
		END IF;
		IF pn_nu_md_min_fre25 IS NOT NULL
		AND pa_nu_md_min_fre25 IS NOT NULL THEN
			IF pa_nu_md_min_fre25 <> pn_nu_md_min_fre25 THEN
				vr_nu_md_min_fre25 := pn_nu_md_min_fre25;
			ELSE
				vr_nu_md_min_fre25 := pa_nu_md_min_fre25;
			END IF;
		END IF;
		IF pn_st_aula_sabad26 IS NULL
		AND pa_st_aula_sabad26 IS NULL THEN
			vr_st_aula_sabad26 := 'null';
		END IF;
		IF pn_st_aula_sabad26 IS NULL
		AND pa_st_aula_sabad26 IS NOT NULL THEN
			vr_st_aula_sabad26 := 'null';
		END IF;
		IF pn_st_aula_sabad26 IS NOT NULL
		AND pa_st_aula_sabad26 IS NULL THEN
			vr_st_aula_sabad26 := '"' || RTRIM(pn_st_aula_sabad26) || '"';
		END IF;
		IF pn_st_aula_sabad26 IS NOT NULL
		AND pa_st_aula_sabad26 IS NOT NULL THEN
			IF pa_st_aula_sabad26 <> pn_st_aula_sabad26 THEN
				vr_st_aula_sabad26 := '"' || RTRIM(pn_st_aula_sabad26) || '"';
			ELSE
				vr_st_aula_sabad26 := '"' || RTRIM(pa_st_aula_sabad26) || '"';
			END IF;
		END IF;
		IF pn_ds_parecer_le27 IS NULL
		AND pa_ds_parecer_le27 IS NULL THEN
			vr_ds_parecer_le27 := 'null';
		END IF;
		IF pn_ds_parecer_le27 IS NULL
		AND pa_ds_parecer_le27 IS NOT NULL THEN
			vr_ds_parecer_le27 := 'null';
		END IF;
		IF pn_ds_parecer_le27 IS NOT NULL
		AND pa_ds_parecer_le27 IS NULL THEN
			vr_ds_parecer_le27 := '"' || RTRIM(pn_ds_parecer_le27) || '"';
		END IF;
		IF pn_ds_parecer_le27 IS NOT NULL
		AND pa_ds_parecer_le27 IS NOT NULL THEN
			IF pa_ds_parecer_le27 <> pn_ds_parecer_le27 THEN
				vr_ds_parecer_le27 := '"' || RTRIM(pn_ds_parecer_le27) || '"';
			ELSE
				vr_ds_parecer_le27 := '"' || RTRIM(pa_ds_parecer_le27) || '"';
			END IF;
		END IF;
		IF pn_nu_dias_letiv28 IS NULL
		AND pa_nu_dias_letiv28 IS NULL THEN
			vr_nu_dias_letiv28 := 'null';
		END IF;
		IF pn_nu_dias_letiv28 IS NULL
		AND pa_nu_dias_letiv28 IS NOT NULL THEN
			vr_nu_dias_letiv28 := 'null';
		END IF;
		IF pn_nu_dias_letiv28 IS NOT NULL
		AND pa_nu_dias_letiv28 IS NULL THEN
			vr_nu_dias_letiv28 := '"' || RTRIM(pn_nu_dias_letiv28) || '"';
		END IF;
		IF pn_nu_dias_letiv28 IS NOT NULL
		AND pa_nu_dias_letiv28 IS NOT NULL THEN
			IF pa_nu_dias_letiv28 <> pn_nu_dias_letiv28 THEN
				vr_nu_dias_letiv28 := '"' || RTRIM(pn_nu_dias_letiv28) || '"';
			ELSE
				vr_nu_dias_letiv28 := '"' || RTRIM(pa_nu_dias_letiv28) || '"';
			END IF;
		END IF;
		IF pn_nu_dias_letiv29 IS NULL
		AND pa_nu_dias_letiv29 IS NULL THEN
			vr_nu_dias_letiv29 := 'null';
		END IF;
		IF pn_nu_dias_letiv29 IS NULL
		AND pa_nu_dias_letiv29 IS NOT NULL THEN
			vr_nu_dias_letiv29 := 'null';
		END IF;
		IF pn_nu_dias_letiv29 IS NOT NULL
		AND pa_nu_dias_letiv29 IS NULL THEN
			vr_nu_dias_letiv29 := '"' || RTRIM(pn_nu_dias_letiv29) || '"';
		END IF;
		IF pn_nu_dias_letiv29 IS NOT NULL
		AND pa_nu_dias_letiv29 IS NOT NULL THEN
			IF pa_nu_dias_letiv29 <> pn_nu_dias_letiv29 THEN
				vr_nu_dias_letiv29 := '"' || RTRIM(pn_nu_dias_letiv29) || '"';
			ELSE
				vr_nu_dias_letiv29 := '"' || RTRIM(pa_nu_dias_letiv29) || '"';
			END IF;
		END IF;
		IF pn_nu_dias_letiv30 IS NULL
		AND pa_nu_dias_letiv30 IS NULL THEN
			vr_nu_dias_letiv30 := 'null';
		END IF;
		IF pn_nu_dias_letiv30 IS NULL
		AND pa_nu_dias_letiv30 IS NOT NULL THEN
			vr_nu_dias_letiv30 := 'null';
		END IF;
		IF pn_nu_dias_letiv30 IS NOT NULL
		AND pa_nu_dias_letiv30 IS NULL THEN
			vr_nu_dias_letiv30 := '"' || RTRIM(pn_nu_dias_letiv30) || '"';
		END IF;
		IF pn_nu_dias_letiv30 IS NOT NULL
		AND pa_nu_dias_letiv30 IS NOT NULL THEN
			IF pa_nu_dias_letiv30 <> pn_nu_dias_letiv30 THEN
				vr_nu_dias_letiv30 := '"' || RTRIM(pn_nu_dias_letiv30) || '"';
			ELSE
				vr_nu_dias_letiv30 := '"' || RTRIM(pa_nu_dias_letiv30) || '"';
			END IF;
		END IF;
		IF pn_nu_dias_letiv31 IS NULL
		AND pa_nu_dias_letiv31 IS NULL THEN
			vr_nu_dias_letiv31 := 'null';
		END IF;
		IF pn_nu_dias_letiv31 IS NULL
		AND pa_nu_dias_letiv31 IS NOT NULL THEN
			vr_nu_dias_letiv31 := 'null';
		END IF;
		IF pn_nu_dias_letiv31 IS NOT NULL
		AND pa_nu_dias_letiv31 IS NULL THEN
			vr_nu_dias_letiv31 := '"' || RTRIM(pn_nu_dias_letiv31) || '"';
		END IF;
		IF pn_nu_dias_letiv31 IS NOT NULL
		AND pa_nu_dias_letiv31 IS NOT NULL THEN
			IF pa_nu_dias_letiv31 <> pn_nu_dias_letiv31 THEN
				vr_nu_dias_letiv31 := '"' || RTRIM(pn_nu_dias_letiv31) || '"';
			ELSE
				vr_nu_dias_letiv31 := '"' || RTRIM(pa_nu_dias_letiv31) || '"';
			END IF;
		END IF;
		IF pn_nu_md_min_rec32 IS NULL
		AND pa_nu_md_min_rec32 IS NULL THEN
			vr_nu_md_min_rec32 := 'null';
		END IF;
		IF pn_nu_md_min_rec32 IS NULL
		AND pa_nu_md_min_rec32 IS NOT NULL THEN
			vr_nu_md_min_rec32 := 'null';
		END IF;
		IF pn_nu_md_min_rec32 IS NOT NULL
		AND pa_nu_md_min_rec32 IS NULL THEN
			vr_nu_md_min_rec32 := '"' || RTRIM(pn_nu_md_min_rec32) || '"';
		END IF;
		IF pn_nu_md_min_rec32 IS NOT NULL
		AND pa_nu_md_min_rec32 IS NOT NULL THEN
			IF pa_nu_md_min_rec32 <> pn_nu_md_min_rec32 THEN
				vr_nu_md_min_rec32 := '"' || RTRIM(pn_nu_md_min_rec32) || '"';
			ELSE
				vr_nu_md_min_rec32 := '"' || RTRIM(pa_nu_md_min_rec32) || '"';
			END IF;
		END IF;
		IF pn_ds_formula_ap33 IS NULL
		AND pa_ds_formula_ap33 IS NULL THEN
			vr_ds_formula_ap33 := 'null';
		END IF;
		IF pn_ds_formula_ap33 IS NULL
		AND pa_ds_formula_ap33 IS NOT NULL THEN
			vr_ds_formula_ap33 := 'null';
		END IF;
		IF pn_ds_formula_ap33 IS NOT NULL
		AND pa_ds_formula_ap33 IS NULL THEN
			vr_ds_formula_ap33 := '"' || RTRIM(pn_ds_formula_ap33) || '"';
		END IF;
		IF pn_ds_formula_ap33 IS NOT NULL
		AND pa_ds_formula_ap33 IS NOT NULL THEN
			IF pa_ds_formula_ap33 <> pn_ds_formula_ap33 THEN
				vr_ds_formula_ap33 := '"' || RTRIM(pn_ds_formula_ap33) || '"';
			ELSE
				vr_ds_formula_ap33 := '"' || RTRIM(pa_ds_formula_ap33) || '"';
			END IF;
		END IF;
		IF pn_ds_formula_ap34 IS NULL
		AND pa_ds_formula_ap34 IS NULL THEN
			vr_ds_formula_ap34 := 'null';
		END IF;
		IF pn_ds_formula_ap34 IS NULL
		AND pa_ds_formula_ap34 IS NOT NULL THEN
			vr_ds_formula_ap34 := 'null';
		END IF;
		IF pn_ds_formula_ap34 IS NOT NULL
		AND pa_ds_formula_ap34 IS NULL THEN
			vr_ds_formula_ap34 := '"' || RTRIM(pn_ds_formula_ap34) || '"';
		END IF;
		IF pn_ds_formula_ap34 IS NOT NULL
		AND pa_ds_formula_ap34 IS NOT NULL THEN
			IF pa_ds_formula_ap34 <> pn_ds_formula_ap34 THEN
				vr_ds_formula_ap34 := '"' || RTRIM(pn_ds_formula_ap34) || '"';
			ELSE
				vr_ds_formula_ap34 := '"' || RTRIM(pa_ds_formula_ap34) || '"';
			END IF;
		END IF;
		IF pn_ano_sem35 IS NULL
		AND pa_ano_sem35 IS NULL THEN
			vr_ano_sem35 := 'null';
		END IF;
		IF pn_ano_sem35 IS NULL
		AND pa_ano_sem35 IS NOT NULL THEN
			vr_ano_sem35 := 'null';
		END IF;
		IF pn_ano_sem35 IS NOT NULL
		AND pa_ano_sem35 IS NULL THEN
			vr_ano_sem35 := '"' || RTRIM(pn_ano_sem35) || '"';
		END IF;
		IF pn_ano_sem35 IS NOT NULL
		AND pa_ano_sem35 IS NOT NULL THEN
			IF pa_ano_sem35 <> pn_ano_sem35 THEN
				vr_ano_sem35 := '"' || RTRIM(pn_ano_sem35) || '"';
			ELSE
				vr_ano_sem35 := '"' || RTRIM(pa_ano_sem35) || '"';
			END IF;
		END IF;
		v_sql1 := 'update s_curso set co_curso = ' || RTRIM(vr_co_curso00) || '  , ds_curso = ' || RTRIM(vr_ds_curso01) || '  , co_unidade = ' || RTRIM(vr_co_unidade02) || '  , tp_recuperacao = ' || RTRIM(vr_tp_recuperaca03) || '  , CO_GRADE_CURRICULAR = ' || RTRIM(vr_co_grade_curr04) || '  , co_tipo_curso = ' || RTRIM(vr_co_tipo_curso05) || '  , ds_formula_1_bim = ' || RTRIM(vr_ds_formula_1_06) || '  , ano = ' || RTRIM(vr_ano07) || '  , turno = ' || RTRIM(vr_turno08);
		v_sql2 := '  , ds_formula_2_bim = ' || RTRIM(vr_ds_formula_2_09) || '  , ds_formula_3_bim = ' || RTRIM(vr_ds_formula_3_10) || '  , ds_formula_4_bim = ' || RTRIM(vr_ds_formula_4_11);
		v_sql3 := '  , ds_formula_1_sem = ' || RTRIM(vr_ds_formula_1_12) || '  , ds_formula_2_sem = ' || RTRIM(vr_ds_formula_2_13) || '  , DS_FORMULA_MEDIA_ANUAL = ' || RTRIM(vr_ds_form_md_an14);
		v_sql4 := '  , DS_FORMULA_NOTA_FINAL = ' || RTRIM(vr_ds_form_nota_15) || '  , ST_ANO_LETIVO_ENCERRADO = ' || RTRIM(vr_st_ano_letivo16) || '  , nu_media_nota = ' || RTRIM(vr_nu_media_nota17) || '  , ds_nota_acumulada = ' || RTRIM(vr_ds_nota_acumu18);
		v_sql5 := '  , TP_RECALCULA_MEDIA_SEM = ' || RTRIM(vr_tp_recalc_md_19) || '  , ST_ARREDONDA_NOTA_SEM = ' || RTRIM(vr_st_arred_nota20) || '  , NU_MEDIA_MINIMA_2_SEM = ' || RTRIM(vr_nu_md_minima_21) || '  , NU_MATERIAS_REC_FINAL = ' || RTRIM(vr_nu_mat_rec_fi22) || '  , nu_freq_minima_obr = ' || RTRIM(vr_nu_freq_minim23) || '  , TP_FREQUENCIA_MINIMA = ' || RTRIM(vr_tp_frequencia24) || '  , NU_MEDIA_MIN_FREQ_MENOR = ' || RTRIM(vr_nu_md_min_fre25) || '  , st_aula_sabado = ' || RTRIM(vr_st_aula_sabad26);
		v_sql6 := '  , ds_parecer_legal = ' || RTRIM(vr_ds_parecer_le27) || '  , nu_dias_letivos_b1 = ' || RTRIM(vr_nu_dias_letiv28) || '  , nu_dias_letivos_b2 = ' || RTRIM(vr_nu_dias_letiv29) || '  , nu_dias_letivos_b3 = ' || RTRIM(vr_nu_dias_letiv30) || '  , nu_dias_letivos_b4 = ' || RTRIM(vr_nu_dias_letiv31) || '  , NU_MEDIA_MIN_REC_ESP = ' || RTRIM(vr_nu_md_min_rec32);
		v_sql7 := '  , ds_formula_apos_s1 = ' || RTRIM(vr_ds_formula_ap33) || '  , ds_formula_apos_s2 = ' || RTRIM(vr_ds_formula_ap34) || '  , ano_sem = ' || RTRIM(vr_ano_sem35);
		v_sql8 := ' where co_curso = ' || RTRIM(vr_co_curso00) || '  and co_unidade = ' || RTRIM(vr_co_unidade02) || '  and ano_sem = ' || RTRIM(vr_ano_sem35) || ';';
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
		       's_curso',
		       p_op,
		       v_sql,
		       '0',
		       SYSDATE,
		       NULL,
		       NULL);
	END IF;
END pr_s_curso074;
/

