create or replace trigger TG_SR_VEICULO_IN_UP
  before insert or update on sr_veiculo  
  for each row
BEGIN
  If (:new.placa <> :old.placa or :new.marca <> :old.marca or :new.modelo <> :old.modelo)and :new.sq_recurso is not null Then
     update eo_recurso 
        set nome   = substr(:new.placa,1,3)||'-'||substr(:new.placa,4)||' - '||:new.marca||' '||:new.modelo,
            codigo = substr(:new.placa,1,3)||'-'||substr(:new.placa,4)
     where sq_recurso = :new.sq_recurso;
  End If;