CREATE OR REPLACE TRIGGER tr_u_age_catego000 BEFORE update ON age_categoria REFERENCING OLD  ant NEW  novo FOR EACH ROW
BEGIN
DECLARE

ItoO_selcnt         NUMBER;
ItoO_rowcnt         NUMBER;
BEGIN

	pr_age_catego000( 'upd', :ant.cat_sequencial, :ant.cat_descricao, :ant.co_unidade, :novo.cat_sequencial, :novo.cat_descricao, :novo.co_unidade );
	END;
END;
/

