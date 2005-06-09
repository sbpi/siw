CREATE OR REPLACE TRIGGER tr_u_s_escola094 BEFORE update ON s_escola REFERENCING OLD  ant NEW  novo FOR EACH ROW
BEGIN
DECLARE

ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;
BEGIN

	pr_s_escola094( 'upd', :ant.co_unidade, :ant.ds_escola, :ant.co_sigre, :novo.co_unidade, :novo.ds_escola, :novo.co_sigre );
	END;
END;
/

