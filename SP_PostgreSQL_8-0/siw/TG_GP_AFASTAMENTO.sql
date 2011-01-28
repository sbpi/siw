create or replace trigger TG_GP_AFASTAMENTO
  before insert or update on gp_afastamento  
  for each row
declare
  -- local variables here
BEGIN
  If :new.dias is null Then
     :new.dias := :new.fim_data - :new.inicio_data + 1;
  Elsif :new.fim_data is null Then
     :new.fim_data := :new.inicio_data + :new.dias - 1;
  End If;