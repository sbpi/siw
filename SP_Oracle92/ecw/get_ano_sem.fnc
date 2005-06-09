CREATE OR REPLACE FUNCTION get_ano_sem RETURN CHAR AS

v_ano_sem           CHAR(5);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	BEGIN
		SELECT ano_sem
		INTO v_ano_sem
		FROM dm_parametros;
	EXCEPTION
	WHEN NO_DATA_FOUND THEN
		v_ano_sem := NULL;
	END;
	RETURN v_ano_sem;
END get_ano_sem;
/

