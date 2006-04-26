create or replace function SP_GetUserVision
   (p_sq_menu   numeric,
    p_sq_pessoa numeric,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera os módulos geridos pela pessoa
   open p_result for 
      select a.sq_menu, a.sq_pessoa, a.sq_cc,
             b.nome as nm_servico,
             c.nome as nm_modulo,
             d.nome as nm_cc
        from siw_pessoa_cc           a
             inner join siw_menu     b on (a.sq_menu   = b.sq_menu)
               inner join siw_modulo c on (b.sq_modulo = c.sq_modulo)
             inner join ct_cc        d on (a.sq_cc     = d.sq_cc)
       where a.sq_pessoa  = p_sq_pessoa
         and ((p_sq_menu  is null) or (p_sq_menu is not null and a.sq_menu = p_sq_menu))
       order by b.nome, c.nome;
   return p_result;
end; $$ language 'plpgsql' volatile;
