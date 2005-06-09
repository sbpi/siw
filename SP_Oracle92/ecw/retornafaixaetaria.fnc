CREATE OR REPLACE FUNCTION retornafaixaetaria(
	dt_nasc_IN             DATE) RETURN NUMBER AS

dt_nasc             DATE := dt_nasc_IN;
int_ano_nasc        NUMBER(10);
int_ano_atual       NUMBER(10);
idade               NUMBER(10);
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	BEGIN
	int_ano_nasc := to_char(dt_nasc,'YYYY');
	int_ano_atual := to_char(SYSDATE,'YYYY');
	idade := int_ano_atual - int_ano_nasc;
	IF idade > 18 THEN
		RETURN 5;
	ELSIF idade >= 15 THEN
		RETURN 4;
	ELSIF idade >= 7 THEN
		RETURN 3;
	ELSIF idade >= 4 THEN
		RETURN 2;
	ELSE
		RETURN 1;
	END IF;
	EXCEPTION
		/* SPCONV-WRN:(EXCEPTION) Emulation of Informix Exceptions incomplete. */
		WHEN OTHERS THEN
			RETURN 6;
	END;
END retornafaixaetaria;
/

