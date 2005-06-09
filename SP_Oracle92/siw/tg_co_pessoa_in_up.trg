create or replace trigger TG_CO_PESSOA_IN_UP
  before insert or update on co_pessoa  
  for each row
declare
  -- local variables here
begin
  :new.nome_indice          := acentos(:new.nome);
  :new.nome_resumido_ind    := acentos(:new.nome_resumido);
end TG_CO_PESSOA_IN_UP;
/

