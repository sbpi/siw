CREATE OR REPLACE FUNCTION to_char(p_valor numeric) RETURNS varchar AS $$
BEGIN
 return p_valor;
END $$ LANGUAGE 'plpgsql' VOLATILE;