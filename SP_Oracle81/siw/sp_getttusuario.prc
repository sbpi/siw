create or replace procedure SP_GetTTUsuario
   (p_chave           in  number   default null,
    p_cliente         in  number   default null,
    p_usuario         in  number   default null,
    p_sq_central_fone in  number   default null,
    p_codigo          in  varchar2 default null,
    p_result          out siw.sys_refcursor) is
begin
   -- Recupera os tipos de Tabela
     open   p_result for
     select a.sq_usuario_central chave, a.usuario,
            c.nome nm_usuario
     from tt_usuario               a,
          co_pessoa        c
     where (a.usuario            = c.sq_pessoa)
       and ((p_chave           is null) or (p_chave           is not null and a.sq_usuario_central = p_chave))
       and ((p_cliente         is null) or (p_cliente         is not null and a.cliente            = p_cliente))
       and ((p_usuario         is null) or (p_usuario         is not null and a.usuario            = p_usuario))
       and ((p_sq_central_fone is null) or (p_sq_central_fone is not null and a.sq_central_fone    = p_sq_central_fone))
       and ((p_codigo          is null) or (p_codigo          is not null and a.codigo             = p_codigo));
end SP_GetTTUsuario;
/

