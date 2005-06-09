CREATE OR REPLACE FUNCTION retornanrodep(
	p_co_aluno_IN          CHAR,
	p_co_unidade_IN        CHAR,
	p_ano_sem_IN           CHAR) RETURN NUMBER AS

p_co_aluno          CHAR(12) := p_co_aluno_IN;
p_co_unidade        CHAR(5) := p_co_unidade_IN;
p_ano_sem           CHAR(5) := p_ano_sem_IN;
v_NroDep            NUMBER(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	BEGIN
		SELECT count(*)
		INTO v_NroDep
		FROM s_aluno_dependenc
		WHERE co_aluno = p_co_aluno
		AND co_unidade = p_co_unidade
		AND ano_sem = p_ano_sem;
	EXCEPTION
	WHEN NO_DATA_FOUND THEN
		v_NroDep := NULL;
	END;
	RETURN v_NroDep;
END retornanrodep;
/

