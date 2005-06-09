CREATE OR REPLACE FUNCTION recuperara(
	ds_bairro_IN           VARCHAR2,
	ds_cidade_IN           VARCHAR2) RETURN NUMBER AS

ds_bairro           VARCHAR2(30) := ds_bairro_IN;
ds_cidade           VARCHAR2(30) := ds_cidade_IN;
str_local           VARCHAR2(40);
resultado           NUMBER(10);
datahora            DATE;
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	resultado := - 1;
	str_local := UPPER(RTRIM(ds_bairro));
	BEGIN
		SELECT DECODE(str_local, 'BRAS�LIA', 1, 'BRASILIA', 1, 'BRAZILIA', 1, 'BRAZ�LIA', 1, 'BSB', 1, 'GAMA', 2, 'TAGUATINGA', 3, 'BRAZL�NDIA', 4, 'BRASL�NDIA', 4, 'BRAZLANDIA', 4, 'BRASLANDIA', 4, 'SOBRADINHO', 5, 'PLANALTINA', 6, 'PARANO�', 7, 'PARANOA', 7, 'N�CLEO BANDEIRANTE', 8, 'N�CLEO BANDEIRANTES', 8, 'NUCLEO BANDEIRANTE', 8, 'NUCLEO BANDEIRANTES', 8, 'CEIL�NDIA', 9, 'CEILANDIA', 9, 'GUAR�', 10, 'GUARA', 10, 'CRUZEIRO', 11, 'CRUSEIRO', 11, 'SAMAMBAIA', 12, 'SANTA MARIA', 13, 'S�O SEBASTI�O', 14, 'S�O SEBASTIAO', 14, 'SAO SEBASTI�O', 14, 'SAO SEBASTIAO', 14, 'RECANTO DAS EMAS', 15, 'LAGO SUL', 16, 'RIACHO FUNDO', 17, 'LAGO NORTE', 18, 'CANDANGOL�NDIA', 19, 'CANDANGOLANDIA', 19, /* DEFAULT */ - 1)
		INTO resultado
		FROM dual;
	EXCEPTION
	WHEN NO_DATA_FOUND THEN
		resultado := NULL;
	END;
	IF resultado = - 1 THEN
		str_local := UPPER(RTRIM(ds_cidade));
		BEGIN
			SELECT DECODE(str_local, 'BRASILIA', 1, 'BRAS�LIA', 1, 'BRAZILIA', 1, 'BRAZ�LIA', 1, 'BSB', 1, 'GAMA', 2, 'TAGUATINGA', 3, 'BRAZL�NDIA', 4, 'BRASL�NDIA', 4, 'BRAZLANDIA', 4, 'BRASLANDIA', 4, 'SOBRADINHO', 5, 'PLANALTINA', 6, 'PARANO�', 7, 'PARANOA', 7, 'N�CLEO BANDEIRANTE', 8, 'N�CLEO BANDEIRANTES', 8, 'NUCLEO BANDEIRANTE', 8, 'NUCLEO BANDEIRANTES', 8, 'CEIL�NDIA', 9, 'CEILANDIA', 9, 'GUAR�', 10, 'GUARA', 10, 'CRUZEIRO', 11, 'CRUSEIRO', 11, 'SAMAMBAIA', 12, 'SANTA MARIA', 13, 'S�O SEBASTI�O', 14, 'S�O SEBASTIAO', 14, 'SAO SEBASTI�O', 14, 'SAO SEBASTIAO', 14, 'RECANTO DAS EMAS', 15, 'LAGO SUL', 16, 'RIACHO FUNDO', 17, 'LAGO NORTE', 18, 'CANDANGOL�NDIA', 19, 'CANDANGOLANDIA', 19, /* DEFAULT */ - 1)
			INTO resultado
			FROM dual;
		EXCEPTION
		WHEN NO_DATA_FOUND THEN
			resultado := NULL;
		END;
	END IF;
	--if resultado = -1 then
	--                begin
	--                  let str_local = ds_bairro || ' - ' ||ds_cidade;
	--        let datahora = current;
	--  begin work;
	--     insert into erro_proc (erro, hora) values (str_local, datahora);
	--  commit work;
	--end;
	--end if;
	RETURN resultado;
END recuperara;
/

