create or replace trigger TG_FN_DOCUMENTO_ITEM
  before insert or update or delete on fn_documento_item
  for each row
declare
  -- local variables here
begin
  -- Calcula valor total do item
  If INSERTING or UPDATING Then :new.valor_total := (:new.quantidade*:new.valor_unitario); End If;

  -- Se o item estiver ligado a uma rubrica, atualiza a saída prevista da rubrica.
  If INSERTING Then
    update pj_rubrica set saida_prevista = saida_prevista + :new.valor_total where sq_projeto_rubrica = :new.sq_projeto_rubrica;
  Elsif UPDATING Then
    update pj_rubrica set saida_prevista = saida_prevista - :old.valor_total where sq_projeto_rubrica = :old.sq_projeto_rubrica;
    update pj_rubrica set saida_prevista = saida_prevista + :new.valor_total where sq_projeto_rubrica = :new.sq_projeto_rubrica;
  Else
    update pj_rubrica set saida_prevista = saida_prevista - :old.valor_total where sq_projeto_rubrica = :old.sq_projeto_rubrica;
  End If;

end TG_FN_DOCUMENTO_ITEM;
/
