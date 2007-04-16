create or replace trigger TG_GP_CONTRATO_COLAB_IN_UP
  before insert or update on gp_contrato_colaborador
  for each row
declare
  w_recurso eo_recurso.sq_recurso%type;
begin
  If :new.matricula <> :old.matricula and :new.fim is null Then
     -- Verifica se o usuário está ligado a um recurso
     select sq_recurso into w_recurso from co_pessoa where sq_pessoa = :new.sq_pessoa;
     
     If w_recurso is not null Then
        update eo_recurso set codigo = :new.matricula where sq_recurso = w_recurso;
     End If;
  End If;
end TG_GP_CONTRATO_COLAB_IN_UP;
/
