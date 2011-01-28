create or replace function HASH_MD5(string varchar)  RETURNS varchar
DECLARE
as language java name 'MD5.hash(java.lang.String) return String';
/
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;