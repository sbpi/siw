CREATE TABLE pj_projeto_etapa_level (
    nivel numeric
)
INHERITS (pj_projeto_etapa);


ALTER TABLE siw.pj_projeto_etapa_level OWNER TO siw;

CREATE OR REPLACE FUNCTION SP_fGetEtapaList (p_chave numeric,p_level numeric,p_direction varchar) RETURNS SETOF pj_projeto_etapa_level AS $$
DECLARE
   temp RECORD;
   child RECORD;
BEGIN
   If upper(p_direction)='DOWN' THEN
      SELECT INTO temp *, p_level AS level FROM pj_projeto_etapa WHERE sq_projeto_etapa = p_chave;
      IF FOUND THEN RETURN NEXT temp;
         FOR child IN SELECT sq_projeto_etapa FROM pj_projeto_etapa WHERE sq_etapa_pai = p_chave LOOP
            FOR temp IN SELECT * FROM SP_fGetEtapaList(child.sq_projeto_etapa, p_level + 1, p_direction) LOOP
               RETURN NEXT temp;
            END LOOP;
         END LOOP;
      END IF;
   ELSE
      SELECT INTO temp *, p_level AS level FROM pj_projeto_etapa WHERE sq_projeto_etapa = p_chave;
      IF FOUND THEN RETURN NEXT temp;
         FOR child IN SELECT sq_etapa_pai FROM pj_projeto_etapa WHERE sq_etapa_pai is not null and sq_projeto_etapa = p_chave LOOP
            FOR temp IN SELECT * FROM SP_fGetEtapaList(child.sq_etapa_pai, p_level - 1, p_direction) LOOP
               RETURN NEXT temp;
            END LOOP;
         END LOOP;
      END IF;
   END IF;
   
END; $$ LANGUAGE 'plpgsql' volatile;
 
