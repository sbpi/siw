CREATE OR REPLACE TRIGGER tr_i_s_escola094 BEFORE insert ON s_escola REFERENCING NEW  novo FOR EACH ROW
BEGIN
DECLARE

ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;
BEGIN

	pr_s_escola094( 'ins', NULL, NULL, NULL, :novo.co_unidade, :novo.ds_escola, :novo.co_sigre );
	END;
END;
/

