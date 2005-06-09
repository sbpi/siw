create or replace trigger TG_AC_ACORDO_IN_UP
  before insert or update on ac_acordo  
  for each row
declare
  w_chave varchar2(60) := null;
begin
  If INSERTING Then
     AC_CriaParametro(:new.cliente, :new.inicio, w_chave);
     :new.codigo_interno := w_chave;
  End If;
  
  -- Calcula a data fim ou a duração do acordo em dias, dependendo
  -- de qual dos dois foi informado
  If :new.duracao is not null or :new.fim is not null Then
     If :new.duracao is not null Then
        :new.fim := (:new.inicio + :new.duracao) - 1;
     Else
        :new.duracao := (:new.fim - :new.inicio) + 1;
     End If;
  End If;
end TG_AC_ACORDO_IN_UP;
/

