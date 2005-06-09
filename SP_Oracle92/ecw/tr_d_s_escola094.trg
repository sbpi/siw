CREATE OR REPLACE TRIGGER tr_d_s_escola094 BEFORE delete ON s_escola REFERENCING OLD  ant FOR EACH ROW
BEGIN
DECLARE

ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;
BEGIN

	pr_s_escola094( 'del', :ant.co_unidade, :ant.ds_escola, :ant.co_sigre, NULL, NULL, NULL );
	END;
END;
/

