create or replace procedure sp_GetHorizonte_IS
   (p_chave   in  number   default null,
    p_cliente in  number   default null,
    p_nome    in  varchar2 default null,
    p_ativo   in  varchar2 default null,
    p_result  out siw.siw.sys_refcursor) is
begin
   -- Recupera os tipos de arquivos
   open p_result for 
      select a.sq_horizonte chave, a.cliente, a.nome, a.ativo
        from is_horizonte a
       where ((p_chave   is null) or (p_chave   is not null and a.sq_horizonte = p_chave))
         and ((p_cliente is null) or (p_cliente is not null and a.cliente      = p_cliente))
         and ((p_nome    is null) or (p_nome    is not null and upper(a.nome) like '%'||upper(p_nome)||'%'))
         and ((p_ativo   is null) or (p_ativo   is not null and a.ativo        = p_ativo));
end sp_GetHorizonte_IS;
/

