create or replace procedure SP_GetModMaster
   (p_cliente   in number,
    p_sq_pessoa in number,
    p_sq_menu   in number,
    p_result    out siw.sys_refcursor
   ) is
begin
   -- Verifica se o usuário é gestor do módulo no endereço informado
   open p_result for
      select decode(count(*),0,'N','S') gestor_modulo
        from sg_pessoa_modulo             a,
             siw_menu        b,
             sg_autenticacao c,
               eo_unidade      d
       where (a.sq_modulo          = b.sq_modulo and
              b.sq_menu            = p_sq_menu
             )
         and (a.sq_pessoa          = c.sq_pessoa)
         and (c.sq_unidade         = d.sq_unidade and
              a.sq_pessoa_endereco = d.sq_pessoa_endereco
             )
         and a.sq_pessoa          = p_sq_pessoa
         and a.cliente            = p_cliente;
end SP_GetModMaster;
/

