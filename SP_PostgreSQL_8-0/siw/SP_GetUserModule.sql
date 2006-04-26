create or replace function SP_GetUserModule
   (p_cliente   numeric,
    p_sq_pessoa numeric,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera os módulos geridos pela pessoa
   open p_result for 
      select a.sq_modulo, d.sq_pessoa_endereco, b.nome as modulo, d.logradouro as endereco, e.nome as usuario 
        from siw_cliente_modulo              a
             inner   join siw_modulo         b on (a.sq_modulo          = b.sq_modulo)
             inner   join sg_pessoa_modulo   c on (a.sq_pessoa          = c.cliente and
                                                   a.sq_modulo          = c.sq_modulo
                                                  )
               inner join co_pessoa_endereco d on (c.sq_pessoa_endereco = d.sq_pessoa_endereco)
               inner join co_pessoa          e on (c.sq_pessoa          = e.sq_pessoa)
       where a.sq_pessoa          = p_cliente
         and c.sq_pessoa          = p_sq_pessoa
       order by b.nome;
   return p_result;
end; $$ language 'plpgsql' volatile;