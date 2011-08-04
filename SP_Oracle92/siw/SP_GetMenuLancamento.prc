create or replace procedure SP_GetMenuLancamento
   (p_sq_tipo_lancamento in number default null,
    p_sq_menu           in number default null,
    p_result            out sys_refcursor
   ) is
begin
   -- Recupera os módulos geridos pela forma de pagamento
   open p_result for 
      select a.sq_tipo_lancamento, a.sq_menu,
             b.nome nm_menu,c.nome nm_modulo,
             d.nome nm_forma_pagamento
        from fn_tipo_lanc_vinc               a
             inner join siw_menu             b on (a.sq_menu            = b.sq_menu)
             inner join siw_modulo           c on (b.sq_modulo          = c.sq_modulo)
             inner join fn_tipo_lancamento    d on (d.sq_tipo_lancamento  = a.sq_tipo_lancamento)
       where a.sq_tipo_lancamento = p_sq_tipo_lancamento
         and ((p_sq_menu  is null) or (p_sq_menu is not null and a.sq_menu = p_sq_menu))
       order by b.nome, c.nome, d.nome;
end SP_GetMenuLancamento;
/
