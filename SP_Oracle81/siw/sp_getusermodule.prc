create or replace procedure SP_GetUserModule
   (p_cliente   in number,
    p_sq_pessoa in number,
    p_result    out siw.sys_refcursor
   ) is
begin
   -- Recupera os módulos geridos pela pessoa
   open p_result for
      select a.sq_modulo, d.sq_pessoa_endereco, b.nome modulo, d.logradouro endereco, e.nome usuario
        from siw_cliente_modulo              a,
             siw_modulo         b,
             sg_pessoa_modulo   c,
             co_pessoa_endereco d,
             co_pessoa          e
       where (a.sq_modulo          = b.sq_modulo)
         and (a.sq_pessoa          = c.cliente and
              a.sq_modulo          = c.sq_modulo
             )
         and (c.sq_pessoa_endereco = d.sq_pessoa_endereco)
         and (c.sq_pessoa          = e.sq_pessoa)
         and a.sq_pessoa          = p_cliente
         and c.sq_pessoa          = p_sq_pessoa
       order by b.nome;
end SP_GetUserModule;
/

