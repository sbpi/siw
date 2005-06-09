CREATE OR REPLACE FUNCTION getdatafimsemana RETURN DATE AS

v_DataFimSemana     DATE;
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	BEGIN
		SELECT TRUNC(datafimsem)
		INTO v_DataFimSemana
		FROM dm_parametros;
	EXCEPTION
	WHEN NO_DATA_FOUND THEN
		v_DataFimSemana := NULL;
	END;
	RETURN v_DataFimSemana;
END getdatafimsemana;
/

