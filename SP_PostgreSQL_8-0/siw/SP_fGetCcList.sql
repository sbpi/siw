CREATE TABLE ct_cc_level (
    nivel numeric
)
INHERITS (ct_cc);


ALTER TABLE siw.ct_cc_level OWNER TO siw;

CREATE OR REPLACE FUNCTION SP_fGetCcList (p_chave numeric,p_level numeric,p_direction varchar) RETURNS SETOF ct_cc_level AS $$
DECLARE
   temp RECORD;
   child RECORD;
BEGIN
   If upper(p_direction)='DOWN' THEN
      SELECT INTO temp *, p_level AS level FROM ct_cc WHERE sq_cc = p_chave;
      IF FOUND THEN RETURN NEXT temp;
         FOR child IN SELECT sq_cc FROM ct_cc WHERE sq_cc_pai = p_chave LOOP
            FOR temp IN SELECT * FROM SP_fGetCcList(child.sq_cc, p_level + 1, p_direction) LOOP
               RETURN NEXT temp;
            END LOOP;
         END LOOP;
      END IF;
   ELSE
      SELECT INTO temp *, p_level AS level FROM ct_cc WHERE sq_cc = p_chave;
      IF FOUND THEN RETURN NEXT temp;
         FOR child IN SELECT sq_cc_pai FROM ct_cc WHERE sq_cc_pai is not null and sq_cc = p_chave LOOP
            FOR temp IN SELECT * FROM SP_fGetCcList(child.sq_cc_pai, p_level - 1, p_direction) LOOP
               RETURN NEXT temp;
            END LOOP;
         END LOOP;
      END IF;
   END IF;
   
END; $$ LANGUAGE 'plpgsql' volatile;
 
