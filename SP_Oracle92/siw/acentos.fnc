CREATE OR REPLACE FUNCTION 
          ACENTOS ( Valor IN VARCHAR2, Tipo IN NUMBER DEFAULT NULL) RETURN  VARCHAR2 IS
/*
Tipo = 1 => Converte acentos formato Benner (Paradox Intl) para ASCII Ansi
Tipo = 2 => Apenas remove acentos
Demais valores ou nulo => Retira caracteres acentuados e converte para minúsculas
                               para ordenação no SELECT
*/

   nome varchar2(8000) := trim(Valor);

BEGIN

   IF Tipo = 1 THEN
      nome := translate(nome,'ƒ Æˆ‚¡ä¢£‡´''','âáãêéíõóúç  ');
   ELSIF Tipo = 2 THEN
      nome := replace(replace(translate(nome,'ÃÂÁÀÄÉÈËÍÌÏÕÔÓÒÖÚÙÜÇÑãâáàäàéèêëíïìõôóöòúüùçñ´''','AAAAAEEEIIIOOOOOUUUÇNaaaaaaeeeeiiiooooouuucn  '),'&','e'),'-','- ');
   ELSE
      nome := upper(ltrim(lower(translate(lower(nome),'ãâáàäàéèêëíïìõôóöòúüùçñ´''','aaaaaaeeeeiiiooooouuucn  '))));
   END IF;
      
   RETURN nome;
END;
/
