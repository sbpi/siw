CREATE OR REPLACE FUNCTION 
          ACENTOS ( Valor IN VARCHAR2, Tipo IN NUMBER DEFAULT NULL) RETURN  VARCHAR2 IS
/*
Tipo = 1 => Converte acentos formato Benner (Paradox Intl) para ASCII Ansi
Tipo diferente de 1 ou nulo => Retira caracteres acentuados e converte para min�sculas
                               para ordena��o no SELECT
*/

   nome varchar2(8000) := Valor;

BEGIN

   IF Tipo IS NULL OR Tipo <> 1 THEN
      nome := ltrim(upper(translate(lower((nome)),'�������������','aaaaeeiooouuc')));
   ELSE
      nome := translate(nome,'��ƿ��䢣�','����������');
   END IF;

   RETURN nome ;
END;
/

