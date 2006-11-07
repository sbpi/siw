create or replace trigger TG_FN_DOCUMENTO_ITEM_IN_UP
  before insert or update on fn_documento_item
  for each row
declare
  -- local variables here
begin
  :new.valor_total := (:new.quantidade*:new.valor_unitario);
end TG_FN_DOCUMENTO_ITEM_IN_UP;
/
