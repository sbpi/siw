create or replace trigger TG_FN_LANCAMENTO_IN
  before insert on fn_lancamento
  for each row

declare
  w_chave varchar2(60) := null;
begin
  FN_CriaParametro(:new.cliente, :new.vencimento, w_chave);
  :new.codigo_interno := w_chave;
end TG_FN_LANCAMENTO_IN;
/

