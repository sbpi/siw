create or replace trigger TG_SIW_RESTRICAO_IN_UP
  before insert or update on siw_restricao
  for each row
declare
  I numeric(1);
  P numeric(1);
BEGIN
  -- Define a criticidade para os riscos, em função da probabilidade e do impacto
  If :new.risco = 'S' Then
     I := :new.impacto;
     P := :new.probabilidade;
     If    (I = 1 and P < 5) or (I < 5 and P = 1) or (I = 2 and P = 2) Then :new.criticidade := 1;
     Elsif (I > 1 and P = 5) or (I = 5 and P > 1) or (I = 4 and P = 4) Then :new.criticidade := 3;
     Else :new.criticidade := 2;
     End If;
  End If;