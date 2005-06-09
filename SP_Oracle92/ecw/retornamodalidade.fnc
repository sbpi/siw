CREATE OR REPLACE FUNCTION retornamodalidade(
	pCo_Curso_IN           NUMBER,
	pAno_Sem_IN            CHAR,
	pCo_Unidade_IN         CHAR) RETURN NUMBER AS

pCo_Curso           NUMBER(10) := pCo_Curso_IN;
pAno_Sem            CHAR(5) := pAno_Sem_IN;
pCo_Unidade         CHAR(5) := pCo_Unidade_IN;
vCo_Tipo_Curso      NUMBER(10);
vCount              NUMBER(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	vCount := 0;
	BEGIN
		SELECT count(*)
		INTO vCount
		FROM s_curso
		WHERE co_curso = pCo_Curso
		AND ano_sem = pAno_Sem
		AND co_unidade = pCo_Unidade;
	EXCEPTION
	WHEN NO_DATA_FOUND THEN
		vCount := NULL;
	END;
	IF vCount > 0 THEN
		BEGIN
			SELECT co_tipo_curso
			INTO vCo_Tipo_Curso
			FROM s_curso
			WHERE co_curso = pCo_Curso
			AND ano_sem = pAno_Sem
			AND co_unidade = pCo_Unidade;
		EXCEPTION
		WHEN NO_DATA_FOUND THEN
			vCo_Tipo_Curso := NULL;
		END;
		RETURN vCo_Tipo_Curso;
	ELSE
		RETURN - 1;
	END IF;
END retornamodalidade;
/

