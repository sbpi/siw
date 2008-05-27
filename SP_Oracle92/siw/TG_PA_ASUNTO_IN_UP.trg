create or replace trigger TG_PA_ASUNTO_IN_UP
  before insert or update on pa_assunto
  for each row
begin
  If :new.provisorio = 'S' Then 
    :new.tipo := '1 - Classe';  -- Se for código de classificação provisória, é classe
  Else
    If instr(:new.codigo,'.') > 0   Then :new.tipo := '4 - Subgrupo';  -- Se o código tem ponto é subgrupo
    Elsif (:new.codigo mod 100) = 0 Then :new.tipo := '1 - Classe';    -- Se o código é uma centena, é classe
    Elsif (:new.codigo mod 10) = 0  Then :new.tipo := '2 - Subclasse'; -- Se o código é uma dezena, é classe
    Else                                 :new.tipo := '3 - Grupo';     -- Caso contrário, é grupo
    End If;
  End If;
end TG_PA_ASUNTO_IN_UP;
/
