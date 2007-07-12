CREATE TABLE siw_menu_level (
    nivel numeric
)
INHERITS (siw_menu);


ALTER TABLE siw.siw_menu_level OWNER TO siw;

CREATE OR REPLACE FUNCTION SP_fGetMenuList (p_chave numeric,p_level numeric,p_direction varchar) RETURNS SETOF siw_menu_level AS $$
DECLARE
   temp RECORD;
   child RECORD;
BEGIN
   If upper(p_direction)='DOWN' THEN
      SELECT INTO temp *, p_level AS level FROM siw_menu WHERE sq_menu = p_chave;
      IF FOUND THEN RETURN NEXT temp;
         FOR child IN SELECT sq_menu FROM siw_menu WHERE sq_menu_pai = p_chave LOOP
            FOR temp IN SELECT * FROM SP_fGetMenuList(child.sq_menu, p_level + 1, p_direction) LOOP
               RETURN NEXT temp;
            END LOOP;
         END LOOP;
      END IF;
   ELSE
      SELECT INTO temp *, p_level AS level FROM siw_menu WHERE sq_menu = p_chave;
      IF FOUND THEN RETURN NEXT temp;
         FOR child IN SELECT sq_menu_pai FROM siw_menu WHERE sq_menu_pai is not null and sq_menu = p_chave LOOP
            FOR temp IN SELECT * FROM SP_fGetMenuList(child.sq_menu_pai, p_level - 1, p_direction) LOOP
               RETURN NEXT temp;
            END LOOP;
         END LOOP;
      END IF;
   END IF;
END; $$ LANGUAGE 'plpgsql' volatile;
 
