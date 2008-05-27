create or replace trigger TG_PA_ASUNTO_IN_UP
  before insert or update on pa_assunto
  for each row
begin
  If :new.provisorio = 'S' Then 
    :new.tipo := '1 - Classe';  -- Se for c�digo de classifica��o provis�ria, � classe
  Else
    If instr(:new.codigo,'.') > 0   Then :new.tipo := '4 - Subgrupo';  -- Se o c�digo tem ponto � subgrupo
    Elsif (:new.codigo mod 100) = 0 Then :new.tipo := '1 - Classe';    -- Se o c�digo � uma centena, � classe
    Elsif (:new.codigo mod 10) = 0  Then :new.tipo := '2 - Subclasse'; -- Se o c�digo � uma dezena, � classe
    Else                                 :new.tipo := '3 - Grupo';     -- Caso contr�rio, � grupo
    End If;
  End If;
end TG_PA_ASUNTO_IN_UP;
/
