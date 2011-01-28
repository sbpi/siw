create or replace trigger TG_PA_DOCUMENTO_IN_UP
  before insert or update on pa_documento  
  for each row
declare
  w_chave varchar(30) := null;
BEGIN
  If INSERTING Then
     PA_CriaParametro(:new.unidade_autuacao, :new.data_recebimento, w_chave);
     :new.prefixo          := substr(w_chave,1,5);
     :new.numero_documento := to_number(substr(w_chave,7,6));
     :new.ano              := to_number(substr(w_chave,14,4));
     :new.digito           := to_number(substr(w_chave,19,2));
  End If;