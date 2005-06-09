create or replace procedure Sp_GetFuncao_IS
   (p_chave   in  varchar2 default null,
    p_ativo   in  varchar2 default null,
    p_result  out sys_refcursor) is
begin
   -- Recupera as funcoes da acao ppa do PPA
   open p_result for 
      select a.cd_funcao, a.nome, a.ativo
        from is_ppa_funcao a
       where ((p_chave is null) or (p_chave  is not null and a.cd_funcao = p_chave))
         and ((p_ativo is null) or (p_ativo  is not null and a.ativo     = p_ativo));
end Sp_GetFuncao_IS;
/

