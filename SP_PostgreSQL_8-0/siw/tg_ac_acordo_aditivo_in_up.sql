create or replace trigger TG_AC_ACORDO_ADITIVO_IN_UP
  before insert or update on ac_acordo_aditivo
  for each row
BEGIN
  -- Calcula a data fim ou a duração do aditivo em dias, dependendo
  -- de qual dos dois foi informado
  If :new.duracao is not null or :new.fim is not null Then
     If :new.duracao is not null Then
        :new.fim := (:new.inicio + :new.duracao) - 1;
     Else
        :new.duracao := (:new.fim - :new.inicio) + 1;
     End If;
  End If;
  
  :new.valor_aditivo   := coalesce(:new.valor_inicial,0)   + coalesce(:new.valor_reajuste,0)     + coalesce(:new.valor_acrescimo,0);
  :new.parcela_aditivo := coalesce(:new.parcela_inicial,0) + coalesce(:new.parcela_reajustada,0) + coalesce(:new.parcela_acrescida,0);