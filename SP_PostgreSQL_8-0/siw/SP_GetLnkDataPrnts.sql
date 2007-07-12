create or replace function SP_GetLnkDataPrnts (p_sq_menu   in  numeric, p_result refcursor) returns refcursor as $$
begin
   -- Recupera os links acima do informado
   open p_result for 
      select sq_menu as sq_pagina, sq_menu_pai as sq_pagina_pai, nome as descricao 
      from siw_menu 
      where sq_menu in (select sq_menu from sp_fGetMenuList(p_sq_menu,0,'UP'));
   return p_result;
end; $$ language 'plpgsql' volatile;
