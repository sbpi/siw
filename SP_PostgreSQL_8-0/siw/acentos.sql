CREATE OR REPLACE FUNCTION ACENTOS ( Valor varchar, Tipo numeric) RETURNS  VARCHAR as $$
/*
Tipo = 1 => Converte acentos formato Benner (Paradox Intl) para ASCII Ansi
Tipo diferente de 1 ou nulo => Retira caracteres acentuados e converte para minúsculas
                               para ordenação no SELECT
*/
DECLARE
   nome varchar(8000) := Valor;

BEGIN

   IF Tipo IS NULL OR Tipo <> 1 THEN
      nome := ltrim(upper(translate(lower((nome)),'ãâáàéêíõôóúüç','aaaaeeiooouuc')));
   ELSE
      nome := translate(nome,'¿ Æ¿¿¡ä¢£¿','âáãêéíõóúç');
   END IF;

   RETURN nome ;
END; $$ language 'plpgsql' volatile;

CREATE OR REPLACE FUNCTION ACENTOS ( Valor varchar) RETURNS  VARCHAR as $$
DECLARE
   nome varchar(8000) := Valor;
BEGIN
   nome := ltrim(upper(translate(lower((nome)),'ãâáàéêíõôóúüç','aaaaeeiooouuc')));

   RETURN nome ;
END; $$ language 'plpgsql' volatile;

