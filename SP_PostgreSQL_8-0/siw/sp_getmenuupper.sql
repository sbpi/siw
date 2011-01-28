create or replace FUNCTION SP_GetMenuUpper
   (p_sq_menu    numeric,
    p_result    REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os dados de uma opção do menu
   open p_result for
     select a.sq_menu, a.sq_menu_pai, a.nome, b.level
       from siw_menu a
            inner join (select sq_menu, level 
                          from connectby('siw_menu','sq_menu_pai','sq_menu',to_char(p_chave),0) 
                               as (sq_menu numeric, sq_menu_pai numeric, level int)
                       ) b on (a.sq_menu = b.sq_menu)
     order by level;

  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;