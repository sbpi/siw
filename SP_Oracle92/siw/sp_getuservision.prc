create or replace procedure SP_GetUserVision
   (p_sq_menu   in number default null,
    p_sq_pessoa in number,
    p_result    out sys_refcursor
   ) is
begin
   -- Recupera os módulos geridos pela pessoa
   open p_result for 
      select a.sq_menu, a.sq_pessoa, a.sq_cc,
             b.nome nm_servico,
             c.nome nm_modulo,
             d.nome nm_cc
        from siw_pessoa_cc           a
             inner join siw_menu     b on (a.sq_menu   = b.sq_menu)
               inner join siw_modulo c on (b.sq_modulo = c.sq_modulo)
             inner join ct_cc        d on (a.sq_cc     = d.sq_cc)
       where a.sq_pessoa  = p_sq_pessoa
         and ((p_sq_menu  is null) or (p_sq_menu is not null and a.sq_menu = p_sq_menu))
       order by b.nome, c.nome;
end SP_GetUserVision;
/

