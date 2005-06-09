create or replace procedure SP_GetTipoRestricao_IS
   (p_chave   in  number   default null,
    p_ativo   in  varchar2 default null,
    p_result  out siw.siw.sys_refcursor) is
begin
   -- Recupera todos os tipos de restrições
   open p_result for 
      select a.cd_tipo_restricao chave, a.nome, a.ativo
        from is_sig_tipo_restricao a
       where ((p_chave   is null) or (p_chave   is not null and a.cd_tipo_restricao  = p_chave))
         and ((p_ativo   is null) or (p_ativo   is not null and a.ativo              = p_ativo));
end SP_GetTipoRestricao_IS;
/

