CREATE OR REPLACE FUNCTION getdatainisemana RETURN DATE AS

v_DataIniSemana     DATE;
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	BEGIN
		SELECT TRUNC(datainiciosem)
		INTO v_DataIniSemana
		FROM dm_parametros;
	EXCEPTION
	WHEN NO_DATA_FOUND THEN
		v_DataIniSemana := NULL;
	END;
	RETURN v_DataIniSemana;
END getdatainisemana;
/

