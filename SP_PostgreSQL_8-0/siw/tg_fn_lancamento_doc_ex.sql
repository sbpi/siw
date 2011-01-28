create or replace trigger TG_FN_LANCAMENTO_DOC_EX
  before DELETE FROM on fn_lancamento_doc
  for each row
declare
BEGIN
  -- Remove os impostos existentes para o documento que está sendo excluído
  DELETE FROM fn_imposto_doc where sq_lancamento_doc = :old.sq_lancamento_doc;