create or replace trigger TG_AC_ACORDO_ADITIVO_IN_UP
  before insert or update on ac_acordo_aditivo
  for each row
begin
  -- Calcula a data fim ou a duração do aditivo em dias, dependendo
  -- de qual dos dois foi informado
  If :new.duracao is not null or :new.fim is not null Then
     If :new.duracao is not null Then
        :new.fim := (:new.inicio + :new.duracao) - 1;
     Else
        :new.duracao := (:new.fim - :new.inicio) + 1;
     End If;
  End If;
  
  :new.valor_aditivo   := :new.valor_inicial   + :new.valor_reajuste     + :new.valor_acrescimo;
  :new.parcela_aditivo := :new.parcela_inicial + :new.parcela_reajustada + :new.parcela_acrescida;
end TG_AC_ACORDO_ADITIVO_IN_UP;
/
