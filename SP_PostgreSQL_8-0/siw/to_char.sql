CREATE OR REPLACE FUNCTION to_char(p_valor numeric)
  RETURNS varchar AS
$BODY$
BEGIN
 return p_valor;
end
$BODY$
  LANGUAGE 'plpgsql' VOLATILE;