alter procedure SP_GetUserVision
   (@p_menu   int =null,
    @p_chave int
   ) as
begin
   -- Recupera os módulos geridos pela pessoa
      select a.sq_menu, a.sq_pessoa, a.sq_cc,
             b.nome nm_servico,
             c.nome nm_modulo,
             d.nome nm_cc,
             e.qtd_servico
        from siw_pessoa_cc           a
             inner join siw_menu     b on (a.sq_menu   = b.sq_menu)
               inner join siw_modulo c on (b.sq_modulo = c.sq_modulo)
             inner join ct_cc        d on (a.sq_cc     = d.sq_cc)
             left  join (select count(x.sq_cc) qtd_servico, x.sq_menu, x.sq_pessoa
                           from siw_pessoa_cc x
                         group by x.sq_menu, x.sq_pessoa
                        )            e on (a.sq_menu   = e.sq_menu and
                                           a.sq_pessoa = e.sq_pessoa)
       where a.sq_pessoa  = @p_chave
         and ((@p_menu  is null) or (@p_menu is not null and a.sq_menu = @p_menu))
       order by b.nome, c.nome;
end