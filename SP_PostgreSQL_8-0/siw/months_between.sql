CREATE OR REPLACE FUNCTION months_between (date, date) RETURNS float AS $$ 
BEGIN
  return months_between(cast($1 as timestamp),cast($2 as timestamp)); 
END $$ LANGUAGE 'plpgsql' VOLATILE CALLED ON NULL INPUT SECURITY INVOKER; 


CREATE OR REPLACE FUNCTION months_between (timestamp, timestamp) RETURNS float AS $$ 
DECLARE 
  mes FLOAT; 
  mes1 FLOAT; 
  ano FLOAT; 
BEGIN
  mes=extract(month from (age($1,$2))); 
  ano=extract(year from (age($1,$2))); 
  mes1=(ano*12) + mes; 
  return mes1; 
END $$ LANGUAGE 'plpgsql' VOLATILE CALLED ON NULL INPUT SECURITY INVOKER; 

