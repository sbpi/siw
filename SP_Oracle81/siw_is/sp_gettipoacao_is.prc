create or replace procedure Sp_GetTipoAcao_IS
   (p_chave   in  number   default null,
    p_ativo   in  varchar2 default null,
    p_result  out siw.siw.sys_refcursor) is
begin
   -- Recupera os tipos de acao da acao ppa do SIGPLAN
   open p_result for 
      select a.cd_tipo_acao, a.nome, a.ativo
        from is_sig_tipo_acao a
       where ((p_chave is null) or (p_chave  is not null and a.cd_tipo_acao = p_chave))
         and ((p_ativo is null) or (p_ativo  is not null and a.ativo        = p_ativo));
end Sp_GetTipoAcao_IS;
/

