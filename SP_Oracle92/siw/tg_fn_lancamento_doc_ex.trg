create or replace trigger TG_FN_LANCAMENTO_DOC_EX
  before delete on fn_lancamento_doc
  for each row
declare
begin
  -- Remove os impostos existentes para o documento que está sendo excluído
  delete fn_imposto_doc where sq_lancamento_doc = :old.sq_lancamento_doc;
end TG_FN_LANCAMENTO_DOC_EX;
/

