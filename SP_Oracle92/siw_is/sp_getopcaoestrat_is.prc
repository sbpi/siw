create or replace procedure sp_GetOpcaoEstrat_IS
   (p_chave    in  varchar2 default null,
    p_nome     in  varchar2 default null,
    p_ativo    in  varchar2 default null,
    p_result   out sys_refcursor) is
begin
   -- Recupera as opções estratégicas
   open p_result for 
      select a.cd_opcao chave, a.nome, a.ativo, a.flag_inclusao
        from is_sig_opcao_estrat a
       where ((p_chave    is null) or (p_chave   is not null and a.cd_opcao     = p_chave))
         and ((p_nome     is null) or (p_nome    is not null and upper(a.nome) like '%'||upper(p_nome)||'%'))
         and ((p_ativo    is null) or (p_ativo   is not null and a.ativo        = p_ativo));
end sp_GetOpcaoEstrat_IS;
/
