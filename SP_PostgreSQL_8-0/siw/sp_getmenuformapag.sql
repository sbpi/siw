create or replace FUNCTION SP_GetMenuFormaPag
   (p_sq_forma_pagamento numeric,
    p_sq_menu            numeric,
    p_result             REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os módulos geridos pela forma de pagamento
   open p_result for 
      select a.sq_forma_pagamento, a.sq_menu,
             b.nome nm_menu,c.nome nm_modulo,
             d.nome nm_forma_pagamento
        from siw_menu_forma_pag              a
             inner   join siw_menu           b on (a.sq_menu            = b.sq_menu)
             inner join siw_modulo           c on (b.sq_modulo          = c.sq_modulo)
             inner   join co_forma_pagamento d on (a.sq_forma_pagamento = d.sq_forma_pagamento)
       where a.sq_forma_pagamento = p_sq_forma_pagamento
         and ((p_sq_menu  is null) or (p_sq_menu is not null and a.sq_menu = p_sq_menu))
       order by b.nome, c.nome, d.nome;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;