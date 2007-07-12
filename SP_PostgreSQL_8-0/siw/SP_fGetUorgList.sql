CREATE TABLE eo_unidade_level (
    nivel numeric
)
INHERITS (eo_unidade);


ALTER TABLE siw.eo_unidade_level OWNER TO siw;


CREATE OR REPLACE FUNCTION SP_fGetUorgList (p_chave numeric,p_level numeric,p_direction varchar) RETURNS SETOF eo_unidade_level AS $$
DECLARE
   temp RECORD;
   child RECORD;
BEGIN
   If upper(p_direction)='DOWN' THEN
      SELECT INTO temp *, p_level AS level FROM eo_unidade WHERE sq_unidade = p_chave;
      IF FOUND THEN RETURN NEXT temp;
         FOR child IN SELECT sq_unidade FROM eo_unidade WHERE sq_unidade_pai = p_chave LOOP
            FOR temp IN SELECT * FROM SP_fGetUorgList(child.sq_unidade, p_level + 1, p_direction) LOOP
               RETURN NEXT temp;
            END LOOP;
         END LOOP;
      END IF;
   ELSE
      SELECT INTO temp *, p_level AS level FROM eo_unidade WHERE sq_unidade = p_chave;
      IF FOUND THEN RETURN NEXT temp;
         FOR child IN SELECT sq_unidade_pai FROM eo_unidade WHERE sq_unidade_pai is not null and sq_unidade = p_chave LOOP
            FOR temp IN SELECT * FROM SP_fGetUorgList(child.sq_unidade_pai, p_level - 1, p_direction) LOOP
               RETURN NEXT temp;
            END LOOP;
         END LOOP;
      END IF;
   END IF;
   
END; $$ LANGUAGE 'plpgsql' volatile;
 
