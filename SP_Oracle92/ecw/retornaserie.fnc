CREATE OR REPLACE FUNCTION retornaserie(
	p_ano_sem_IN           CHAR,
	p_co_curso_IN          NUMBER,
	p_co_seq_serie_IN      NUMBER,
	p_co_unidade_IN        CHAR) RETURN VARCHAR2 AS

p_ano_sem           CHAR(5) := p_ano_sem_IN;
p_co_curso          NUMBER(10) := p_co_curso_IN;
p_co_seq_serie      NUMBER(10) := p_co_seq_serie_IN;
p_co_unidade        CHAR(5) := p_co_unidade_IN;
v_sg_serie          VARCHAR2(5);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	BEGIN
		SELECT DECODE(sg_serie, NULL, '', sg_serie)
		INTO v_sg_serie
		FROM s_curso_serie
		WHERE ano_sem = p_ano_sem
		AND co_curso = p_co_curso
		AND co_seq_serie = p_co_seq_serie
		AND co_unidade = p_co_unidade;
	EXCEPTION
	WHEN NO_DATA_FOUND THEN
		v_sg_serie := NULL;
	END;
	RETURN v_sg_serie;
END retornaserie;
/

