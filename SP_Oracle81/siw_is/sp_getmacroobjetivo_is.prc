create or replace procedure sp_GetMacroObjetivo_IS
   (p_chave     in  varchar2 default null,
    p_cd_opcao  in  varchar2 default null,
    p_nome      in  varchar2 default null,
    p_ativo     in  varchar2 default null,
    p_result    out siw.siw.sys_refcursor) is
begin
   -- Recupera as opções estratégicas
   open p_result for 
      select a.cd_macro chave, a.cd_opcao, a.nome, a.ativo, a.flag_inclusao
        from is_sig_macro_objetivo a
       where ((p_chave    is null) or (p_chave    is not null and a.cd_macro     = p_chave))
         and ((p_cd_opcao is null) or (p_cd_opcao is not null and a.cd_opcao     = p_cd_opcao))
         and ((p_nome     is null) or (p_nome     is not null and upper(a.nome) like '%'||upper(p_nome)||'%'))
         and ((p_ativo    is null) or (p_ativo    is not null and a.ativo        = p_ativo));
end sp_GetMacroObjetivo_IS;
/
