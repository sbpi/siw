CREATE OR REPLACE TRIGGER tr_i_age_catego000 BEFORE insert ON age_categoria REFERENCING NEW  novo FOR EACH ROW
BEGIN
DECLARE

ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;
BEGIN

	pr_age_catego000( 'ins', NULL, NULL, NULL, :novo.cat_sequencial, :novo.cat_descricao, :novo.co_unidade );
	END;
END;
/

