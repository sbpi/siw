CREATE OR REPLACE FUNCTION weekofyear(
	pDay_IN                CHAR,
	pMonth_IN              CHAR,
	pYear_IN               CHAR) RETURN NUMBER AS

pDay                CHAR(2) := pDay_IN;
pMonth              CHAR(2) := pMonth_IN;
pYear               CHAR(4) := pYear_IN;
NumberOfDays        NUMBER(10);
StrDate             CHAR(10);
StrLastDay          CHAR(10);
StrFirstDay         CHAR(10);
FirstDay            NUMBER(10);
NumberOfWeeks       NUMBER(10);
vDay                CHAR(2);
dtDate              DATE;
dtLastDay           DATE;
dtFirstDay          DATE;
ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;

BEGIN
	--        set debug file to '/des/u/dmge/debug.trace';
	--        trace on;
	pDay := LPAD(RTRIM(pDay), 2, '0');
	pMonth := LPAD(RTRIM(pMonth), 2, '0');
	pYear := LPAD(RTRIM(pYear), 4, '0');
	StrDate := pYear || '-' || pMonth || '-' || pDay;
	StrLastDay := pYear || '-12-31';
	StrFirstDay := pYear || '-01-01';
	--        trace 'Passei 1';
	dtDate := TO_DATE(StrDate, 'YYYY"-" MM"-" DD' /* %Y-%m-%d  */);
	dtLastDay := TO_DATE(StrLastDay, 'YYYY"-" MM"-" DD' /* %Y-%m-%d  */);
	dtFirstDay := TO_DATE(StrFirstDay, 'YYYY"-" MM"-" DD' /* %Y-%m-%d  */);
	--        trace 'Passei 2';
	FirstDay := to_char(dtFirstDay,'d');
	NumberOfDays := dtLastDay - dtDate;
	NumberOfDays := 365 - NumberOfDays + FirstDay;
	NumberOfWeeks := NumberOfDays / 7;
	IF MOD(NumberOfDays, 7) > 0 THEN
		NumberOfWeeks := NumberOfWeeks + 1;
	END IF;
	--        trace off;
	RETURN NumberOfWeeks;
END weekofyear;
/

