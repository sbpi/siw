create or replace procedure SP_GetMenuFormaPag
   (p_sq_forma_pagamento in number default null,
    p_sq_menu            in number default null,
    p_result             out siw.sys_refcursor
   ) is
begin
   -- Recupera os módulos geridos pela forma de pagamento
   open p_result for 
      select a.sq_forma_pagamento, a.sq_menu,
             b.nome nm_menu,c.nome nm_modulo,
             d.nome nm_forma_pagamento
        from siw_menu_forma_pag a,
             siw_menu           b,
             siw_modulo         c,
             co_forma_pagamento d
       where (a.sq_menu            = b.sq_menu)
         and (b.sq_modulo          = c.sq_modulo)
         and (a.sq_forma_pagamento = d.sq_forma_pagamento)
         and a.sq_forma_pagamento = p_sq_forma_pagamento
         and ((p_sq_menu  is null) or (p_sq_menu is not null and a.sq_menu = p_sq_menu))
       order by b.nome, c.nome, d.nome;
end SP_GetMenuFormaPag;
/
