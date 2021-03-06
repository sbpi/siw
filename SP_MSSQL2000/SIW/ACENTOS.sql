alter function dbo.ACENTOS(@VALOR VARCHAR(8000)=null) RETURNS VARCHAR(8000)
AS

BEGIN
  DECLARE @nome VARCHAR(8000)
  SET @nome = lower(rtrim(ltrim(@VALOR)))
  SET @nome = REPLACE(lower(@nome), 'ã', 'a')
  SET @nome = REPLACE(lower(@nome), 'â', 'a')
  SET @nome = REPLACE(lower(@nome), 'á', 'a')
  SET @nome = REPLACE(lower(@nome), 'à', 'a')
  SET @nome = REPLACE(lower(@nome), 'é', 'e')
  SET @nome = REPLACE(lower(@nome), 'ê', 'e')
  SET @nome = REPLACE(lower(@nome), 'í', 'i')
  SET @nome = REPLACE(lower(@nome), 'õ', 'o')
  SET @nome = REPLACE(lower(@nome), 'ô', 'o')
  SET @nome = REPLACE(lower(@nome), 'ó', 'o')
  SET @nome = REPLACE(lower(@nome), 'ú', 'u')
  SET @nome = REPLACE(lower(@nome), 'ü', 'u')
  SET @nome = REPLACE(lower(@nome), 'ç', 'c')

  SET @nome = upper(@nome)
  RETURN(@nome)
END
