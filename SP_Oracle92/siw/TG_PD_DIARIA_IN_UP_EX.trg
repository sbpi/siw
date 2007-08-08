create or replace trigger TG_PD_DIARIA_IN_UP_EX
  before insert or update or delete on pd_diaria  
  for each row
declare
  -- local variables here
begin
  If INSERTING Then
     -- Acrescenta ao valor da solicitação o valor da diária
     update siw_solicitacao set valor = valor + (:new.quantidade * :new.valor) where sq_siw_solicitacao = :new.sq_siw_solicitacao;
  Elsif UPDATING Then
     -- Subtrai o valor antigo e acrescenta ao valor da solicitação o valor da diária
     update siw_solicitacao set valor = valor 
                                        - (:old.quantidade * :old.valor) 
                                        + (:new.quantidade * :new.valor) 
     where sq_siw_solicitacao = :new.sq_siw_solicitacao;
  Else
     -- Subtrai o valor da diária da solicitação
     update siw_solicitacao set valor = valor - (:old.quantidade * :old.valor) where sq_siw_solicitacao = :old.sq_siw_solicitacao;
  End If;
end TG_PD_DIARIA_IN_UP_EX;
/
