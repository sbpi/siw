create or replace trigger TG_GP_CONTRATO_COLAB_IN_UP
  before insert or update on gp_contrato_colaborador
  for each row
declare
  w_recurso eo_recurso.sq_recurso%type;
  w_minutos_manha numeric(4) := 0;
  w_minutos_tarde numeric(4) := 0;
  w_minutos_noite numeric(4) := 0;
BEGIN
  If :new.matricula <> :old.matricula and :new.fim is null Then
     -- Verifica se o usuário está ligado a um recurso
     select sq_recurso into w_recurso from co_pessoa where sq_pessoa = :new.sq_pessoa;
     
     If w_recurso is not null Then
        update eo_recurso set codigo = :new.matricula where sq_recurso = w_recurso;
     End If;
  End If;
  
  -- Calcula os minutos dos horários de trabalho
  If :new.entrada_manha is not null Then w_minutos_manha := horario2minutos(:new.entrada_manha,:new.saida_manha); End If;
  If :new.entrada_tarde is not null Then w_minutos_tarde := horario2minutos(:new.entrada_tarde,:new.saida_tarde); End If;  
  If :new.entrada_noite is not null Then w_minutos_noite := horario2minutos(:new.entrada_noite,:new.saida_noite); End If;  

  :new.minutos_diarios := w_minutos_manha + w_minutos_tarde + w_minutos_noite;