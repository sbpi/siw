create  procedure SP_GetMenuUpper
   (@p_chave   int
 
   ) as
begin
  
      select sq_menu, sq_menu_pai, nome
        from siw_menu
        where sq_menu in (select chave from dbo.SP_fGetMenuUpper(@p_chave , 'UP'))
--        start with sq_menu           = @p_chave
  --      connect by prior sq_menu_pai = sq_menu;
end 